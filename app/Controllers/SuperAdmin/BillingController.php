<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Services\Billing\StripeService;
use App\Models\Tenant\CondominiumModel;

/**
 * BillingController
 * 
 * Administra el Checkout para cobrar las suscripciones a los Clientes/Condominios.
 * Actúa como Listener ciego (Webhook) de eventos asíncronos en Stripe.
 * 
 * Eventos escuchados:
 * - checkout.session.completed → Activa el plan del condominio
 * - invoice.paid              → Renueva la vigencia de la suscripción
 * - invoice.payment_failed    → Registra el fallo de pago
 * - customer.subscription.deleted → Desactiva la suscripción
 */
class BillingController extends BaseController
{
    /**
     * Retorno visual en caso de éxito (Stripe redirige aquí tras pago).
     * La activación REAL ocurre asincrónamente vía Webhook.
     */
    public function success()
    {
        return view('superadmin/billing_result', [
            'status'  => 'success',
            'title'   => '¡Pago Autorizado!',
            'message' => 'Tu suscripción se activará en breves momentos. Serás redirigido automáticamente.',
        ]);
    }

    /**
     * Retorno visual en caso de cancelación por el usuario.
     */
    public function cancel()
    {
        return view('superadmin/billing_result', [
            'status'  => 'cancel',
            'title'   => 'Pago Cancelado',
            'message' => 'El proceso de pago fue cancelado de forma segura. No se realizó ningún cargo.',
        ]);
    }

    /**
     * Endpoint Webhook público. Llamado por los servidores de Stripe.
     * Ruta: POST /api/webhooks/stripe
     * 
     * Autenticación: Firma criptográfica vía STRIPE_WEBHOOK_SECRET.
     * NO requiere sesión ni filtro de autenticación.
     */
    public function webhook()
    {
        $payload   = $this->request->getBody();
        $sigHeader = $this->request->getHeaderLine('stripe-signature');

        // ── Validar firma criptográfica ──
        $stripeSvc = new StripeService();
        $event = $stripeSvc->handleWebhook($payload, $sigHeader);

        if (!$event) {
            log_message('error', '[Stripe Webhook] Firma inválida o payload corrupto.');
            return $this->response->setStatusCode(400)->setBody('Webhook signature verification failed');
        }

        log_message('info', '[Stripe Webhook] Evento recibido: ' . $event->type);

        $db = \Config\Database::connect();

        switch ($event->type) {

            // ══════════════════════════════════════════════════════════
            // CHECKOUT COMPLETADO — Primera activación de suscripción
            // ══════════════════════════════════════════════════════════
            case 'checkout.session.completed':
                $session = $event->data->object;
                $metadata = $session->metadata ?? null;

                if (!$metadata || empty($metadata->condominium_id) || empty($metadata->plan_id)) {
                    log_message('error', '[Stripe Webhook] checkout.session.completed sin metadata válida.');
                    break;
                }

                $condominiumId = (int) $metadata->condominium_id;
                $planId        = (int) $metadata->plan_id;
                $billingCycle  = $metadata->billing_cycle ?? 'monthly';
                $stripeSubId   = $session->subscription ?? null;

                $expiresAt = $billingCycle === 'yearly'
                    ? date('Y-m-d H:i:s', strtotime('+1 year'))
                    : date('Y-m-d H:i:s', strtotime('+1 month'));

                $db->table('condominiums')->where('id', $condominiumId)->update([
                    'plan_id'                 => $planId,
                    'billing_cycle'           => $billingCycle,
                    'plan_expires_at'         => $expiresAt,
                    'stripe_subscription_id'  => $stripeSubId,
                    'subscription_status'     => 'active',
                    'grace_until'             => null,
                    'status'                  => 'active', // General access status
                    'updated_at'              => date('Y-m-d H:i:s'),
                ]);

                log_message('info', "[Stripe Webhook] Plan #{$planId} activado para condominio #{$condominiumId}. Sub: {$stripeSubId}");
                break;

            // ══════════════════════════════════════════════════════════
            // FACTURA PAGADA — Renovación exitosa de suscripción
            // ══════════════════════════════════════════════════════════
            case 'invoice.paid':
                $invoice = $event->data->object;
                $stripeSubId = $invoice->subscription;

                if ($stripeSubId) {
                    // Buscar el condominio vinculado a esta suscripción
                    $condo = $db->table('condominiums')
                        ->where('stripe_subscription_id', $stripeSubId)
                        ->get()->getRowArray();

                    if ($condo) {
                        $billingCycle = $condo['billing_cycle'] ?? 'monthly';
                        $expiresAt = $billingCycle === 'yearly'
                            ? date('Y-m-d H:i:s', strtotime('+1 year'))
                            : date('Y-m-d H:i:s', strtotime('+1 month'));

                        $db->table('condominiums')->where('id', $condo['id'])->update([
                            'plan_expires_at'     => $expiresAt,
                            'status'              => 'active', // general status
                            'subscription_status' => 'active',
                            'grace_until'         => null,
                            'updated_at'          => date('Y-m-d H:i:s'),
                        ]);

                        log_message('info', "[Stripe Webhook] Renovación exitosa para condominio #{$condo['id']}. Vence: {$expiresAt}");
                    }
                }
                break;

            // ══════════════════════════════════════════════════════════
            // PAGO FALLIDO — Tarjeta rechazada o sin fondos
            // ══════════════════════════════════════════════════════════
            case 'invoice.payment_failed':
                $invoice = $event->data->object;
                $stripeSubId = $invoice->subscription;

                if ($stripeSubId) {
                    $condo = $db->table('condominiums')
                        ->where('stripe_subscription_id', $stripeSubId)
                        ->get()->getRowArray();

                    if ($condo) {
                        log_message('warning', "[Stripe Webhook] Pago fallido para condominio #{$condo['id']} (Sub: {$stripeSubId}). Attempt: {$invoice->attempt_count}");
                        
                        // Entramos en periodo de gracia de 3 días para past_due
                        $graceUntil = date('Y-m-d H:i:s', strtotime('+3 days'));
                        $db->table('condominiums')->where('id', $condo['id'])->update([
                            'subscription_status' => 'past_due',
                            'grace_until'         => $graceUntil,
                            'updated_at'          => date('Y-m-d H:i:s'),
                        ]);
                    }
                }
                break;

            // ══════════════════════════════════════════════════════════
            // SUSCRIPCIÓN CANCELADA — Expiró, se canceló o agotó reintentos
            // ══════════════════════════════════════════════════════════
            case 'customer.subscription.deleted':
                $subscription = $event->data->object;
                $stripeSubId = $subscription->id;

                $condo = $db->table('condominiums')
                    ->where('stripe_subscription_id', $stripeSubId)
                    ->get()->getRowArray();

                if ($condo) {
                    $db->table('condominiums')->where('id', $condo['id'])->update([
                        'stripe_subscription_id' => null,
                        'plan_id'                => null,
                        'plan_expires_at'        => null,
                        'subscription_status'    => 'canceled',
                        'grace_until'            => null,
                        'status'                 => 'suspended', // Suspende también el status global por seguridad
                        'updated_at'             => date('Y-m-d H:i:s'),
                    ]);

                    log_message('warning', "[Stripe Webhook] Suscripción cancelada para condominio #{$condo['id']}. Sub: {$stripeSubId}");
                }
                break;

            // ══════════════════════════════════════════════════════════
            // SUSCRIPCIÓN ACTUALIZADA — Cambio de plan (Upgrade/Downgrade)
            // ══════════════════════════════════════════════════════════
            case 'customer.subscription.updated':
                $subscription = $event->data->object;
                $stripeSubId = $subscription->id;
                
                // Extraer el Price ID actual de la suscripción
                $priceId = $subscription->items->data[0]->price->id ?? null;
                $currentPeriodEnd = $subscription->current_period_end ?? null;
                
                if ($priceId && $stripeSubId) {
                    $condo = $db->table('condominiums')
                        ->where('stripe_subscription_id', $stripeSubId)
                        ->get()->getRowArray();
                        
                    if ($condo) {
                        // Identificar a qué plan y ciclo pertenece este Price ID
                        $plan = $db->table('plans')
                            ->groupStart()
                                ->where('stripe_price_id_monthly', $priceId)
                                ->orWhere('stripe_price_id_yearly', $priceId)
                            ->groupEnd()
                            ->get()->getRowArray();
                            
                        if ($plan) {
                            $billingCycle = ($plan['stripe_price_id_yearly'] === $priceId) ? 'yearly' : 'monthly';
                            
                            $updateData = [
                                'plan_id'             => $plan['id'],
                                'billing_cycle'       => $billingCycle,
                                'status'              => 'active',
                                'subscription_status' => 'active',
                                'grace_until'         => null,
                                'updated_at'          => date('Y-m-d H:i:s'),
                            ];
                            
                            // Sincronizar también la fecha de expiración real de Stripe
                            if ($currentPeriodEnd) {
                                $updateData['plan_expires_at'] = date('Y-m-d H:i:s', $currentPeriodEnd);
                            }
                            
                            $db->table('condominiums')->where('id', $condo['id'])->update($updateData);
                            
                            log_message('info', "[Stripe Webhook] Suscripción actualizada para condominio #{$condo['id']}. Plan: {$plan['name']} ({$billingCycle})");
                        } else {
                            log_message('warning', "[Stripe Webhook] Cambio de suscripción: Price ID {$priceId} no coincide con ningún plan.");
                        }
                    }
                }
                break;

            default:
                log_message('info', '[Stripe Webhook] Evento no manejado: ' . $event->type);
        }

        return $this->response->setStatusCode(200)->setBody('Event Received');
    }
}
