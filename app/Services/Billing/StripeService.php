<?php

namespace App\Services\Billing;

use Stripe\StripeClient;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

/**
 * StripeService
 * 
 * Servicio principal para la intermediación entre AXISCONDO y la pasarela Stripe.
 * Permite manejar facturación SaaS de los Condominios (Tenants).
 * 
 * ==========================================
 * CONFIGURACIÓN STRIPE (.env)
 * ==========================================
 * STRIPE_SECRET_KEY="sk_test_..."
 * STRIPE_PUBLIC_KEY="pk_test_..."
 * STRIPE_WEBHOOK_SECRET="whsec_..."
 * ==========================================
 * 
 * Composer requiere: `composer require stripe/stripe-php`
 */
class StripeService
{
    protected StripeClient $stripe;
    protected string $webhookSecret;

    public function __construct()
    {
        $secretKey = env('STRIPE_SECRET_KEY', '');
        $this->webhookSecret = env('STRIPE_WEBHOOK_SECRET', '');

        if (empty($secretKey)) {
            log_message('error', '[StripeService] STRIPE_SECRET_KEY no configurada en .env');
        }

        $this->stripe = new StripeClient($secretKey);
    }

    /**
     * Crea un cliente en Stripe 
     * Retorna el id del cliente (ej: cus_...) que deberá almacenarse en la tabla condominiums.
     */
    public function createCustomer(string $email, string $name, array $metadata = [])
    {
        try {
            $customer = $this->stripe->customers->create([
                'email'    => $email,
                'name'     => $name,
                'metadata' => $metadata
            ]);
            return $customer->id;
        } catch (\Exception $e) {
            log_message('error', '[StripeService] Error creando Customer: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea sesión de Checkout para la Suscripción inicial al SaaS
     * 
     * @param string $customerId El ID de stripe (cus_...)
     * @param string $priceId El ID del plan/precio en Stripe (price_...)
     * @param string $successUrl 
     * @param string $cancelUrl
     * @return string|false
     */
    public function createSubscription(string $customerId, string $priceId, string $successUrl, string $cancelUrl, array $metadata = []): string|bool
    {
        try {
            /** @var array<string, mixed> $params */
            $params = [
                'customer' => $customerId,
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price' => $priceId,
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'subscription_data' => [
                    'metadata' => $metadata
                ],
                'metadata' => $metadata
            ];
            
            /** @var mixed $sessionsApi */
            $sessionsApi = $this->stripe->checkout->sessions;
            
            /** @var mixed $session */
            $session = $sessionsApi->create($params);

            return $session->url;
            
        } catch (\Exception $e) {
            log_message('error', '[StripeService] Error creando Checkout Session: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Crea una sesión del Customer Portal de Stripe.
     * Permite al cliente gestionar métodos de pago, ver facturas y cancelar suscripciones.
     *
     * @param string $customerId  ID del cliente en Stripe (cus_...)
     * @param string $returnUrl   URL absoluta a la que Stripe redirigirá al salir del portal
     * @return string|null        URL del portal o null si falla
     */
    public function createBillingPortalSession(string $customerId, string $returnUrl): ?string
    {
        try {
            $session = $this->stripe->billingPortal->sessions->create([
                'customer'   => $customerId,
                'return_url' => $returnUrl,
            ]);

            return $session->url;
        } catch (\Exception $e) {
            log_message('error', '[StripeService] Error creando Billing Portal Session: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualiza una suscripción existente (Upgrade / Downgrade o Cambio de Ciclo)
     *
     * @param string $subscriptionId ID de la suscripción (sub_...)
     * @param string $newPriceId     Nuevo ID de precio (price_...)
     */
    public function updateSubscription(string $subscriptionId, string $newPriceId)
    {
        try {
            $subscription = $this->stripe->subscriptions->retrieve($subscriptionId);
            
            // Actualizamos el primer ítem de la suscripción (que corresponde al plan)
            $this->stripe->subscriptions->update($subscriptionId, [
                'items' => [
                    [
                        'id'    => $subscription->items->data[0]->id,
                        'price' => $newPriceId,
                    ],
                ],
                'proration_behavior' => 'always_invoice', // Cobra la diferencia de costo de inmediato
            ]);

            return true;
        } catch (\Exception $e) {
            log_message('error', '[StripeService] Error actualizando Subscription: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cancela remotamente una suscripción activa de Stripe
     */
    public function cancelSubscription(string $stripeSubscriptionId)
    {
        try {
            $canceled = $this->stripe->subscriptions->cancel($stripeSubscriptionId);
            return $canceled->status === 'canceled';
        } catch (\Exception $e) {
            log_message('error', '[StripeService] Error cancelando Subscription: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Valida y procesa la firma criptográfica del Webhook
     */
    public function handleWebhook(string $payload, string $sigHeader)
    {
        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $this->webhookSecret);
            return $event;
        } catch (\UnexpectedValueException $e) {
            log_message('error', '[Stripe Webhook] Payload JSON inválido.');
            return null;
        } catch (SignatureVerificationException $e) {
            log_message('error', '[Stripe Webhook] Firma de seguridad errónea: ' . $sigHeader);
            return null;
        }
    }
}
