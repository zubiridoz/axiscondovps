<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Services\Billing\StripeService;
use CodeIgniter\I18n\Time;

/**
 * CondominiumController
 * 
 * Gestión global de Tenants (Condominios) para el SuperAdmin.
 * Endpoints AJAX para suspend/activate/softDelete/detail.
 */
class CondominiumController extends BaseController
{
    /**
     * Detalle completo de un condominio (JSON para modal).
     */
    public function detail($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID requerido'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();

        // Info básica del condominio
        $condo = $db->table('condominiums')->where('id', $id)->get()->getRowArray();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado'])->setStatusCode(404);
        }

        // Info del Plan asignado (si lo hay)
        $plan = null;
        if (!empty($condo['plan_id'])) {
            $plan = $db->table('plans')->where('id', $condo['plan_id'])->get()->getRowArray();
        }

        // Administradores del condominio
        $admins = $db->query("
            SELECT u.id, u.first_name, u.last_name, u.email, u.created_at, r.name AS role_name
            FROM user_condominium_roles ucr
            INNER JOIN users u ON u.id = ucr.user_id
            INNER JOIN roles r ON r.id = ucr.role_id
            WHERE ucr.condominium_id = ? AND r.name IN ('ADMIN', 'SUPER_ADMIN')
            ORDER BY ucr.id ASC
        ", [$id])->getResultArray();

        // Métricas
        $totalUnits     = $db->table('units')->where('condominium_id', $id)->countAllResults();
        $totalResidents = $db->table('residents')->where('condominium_id', $id)->countAllResults();
        $totalTickets   = $db->table('tickets')->where('condominium_id', $id)->countAllResults();

        // Última actividad
        $lastActivity = $db->table('financial_transactions')
            ->selectMax('created_at')
            ->where('condominium_id', $id)
            ->get()->getRow();

        return $this->response->setJSON([
            'success' => true,
            'data'    => [
                'condominium' => $condo,
                'plan'        => $plan,
                'admins'      => $admins,
                'metrics'     => [
                    'total_units'     => $totalUnits,
                    'total_residents' => $totalResidents,
                    'total_tickets'   => $totalTickets,
                ],
                'last_activity' => $lastActivity->created_at ?? null
            ]
        ]);
    }

    /**
     * Suspender operación de un Condominio (Bloquear acceso a todo el Tenant)
     */
    public function suspend($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID requerido'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $condo = $db->table('condominiums')->where('id', $id)->get()->getRow();

        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado'])->setStatusCode(404);
        }

        if ($condo->status === 'suspended') {
            return $this->response->setJSON(['success' => false, 'message' => 'El condominio ya está suspendido'])->setStatusCode(409);
        }

        // ── Cancelar suscripción en Stripe si existe ──
        if (!empty($condo->stripe_subscription_id)) {
            $stripeSvc = new StripeService();
            $canceled = $stripeSvc->cancelSubscription($condo->stripe_subscription_id);
            log_message('info', "[SUPERADMIN] Stripe sub {$condo->stripe_subscription_id} cancelada: " . ($canceled ? 'OK' : 'FALLÓ'));
        }

        $db->table('condominiums')->where('id', $id)->update([
            'status'                 => 'suspended',
            'subscription_status'    => 'suspended',
            'stripe_subscription_id' => null,
            'suspended_at'           => Time::now()->toDateTimeString(),
            'updated_at'             => Time::now()->toDateTimeString()
        ]);

        log_message('notice', "[SUPERADMIN] Condominio ID={$id} SUSPENDIDO por user_id=" . session()->get('user_id'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Condominio suspendido exitosamente. Todos los usuarios han sido bloqueados.',
            'new_status' => 'suspended'
        ]);
    }

    /**
     * Reactivar Condominio
     */
    public function activate($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID requerido'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $condo = $db->table('condominiums')->where('id', $id)->get()->getRow();

        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado'])->setStatusCode(404);
        }

        if ($condo->status === 'active') {
            return $this->response->setJSON(['success' => false, 'message' => 'El condominio ya está activo'])->setStatusCode(409);
        }

        $db->table('condominiums')->where('id', $id)->update([
            'status'       => 'active',
            'suspended_at' => null,
            'updated_at'   => Time::now()->toDateTimeString()
        ]);

        log_message('notice', "[SUPERADMIN] Condominio ID={$id} REACTIVADO por user_id=" . session()->get('user_id'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Condominio reactivado exitosamente. Los usuarios pueden acceder nuevamente.',
            'new_status' => 'active'
        ]);
    }

    /**
     * Soft Delete — Eliminar condominio (reversible desde BD)
     */
    public function softDelete($id = null)
    {
        if (!$id) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID requerido'])->setStatusCode(400);
        }

        $db = \Config\Database::connect();
        $condo = $db->table('condominiums')->where('id', $id)->where('deleted_at IS NULL')->get()->getRow();

        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado'])->setStatusCode(404);
        }

        // ── Cancelar suscripción en Stripe si existe ──
        if (!empty($condo->stripe_subscription_id)) {
            $stripeSvc = new StripeService();
            $canceled = $stripeSvc->cancelSubscription($condo->stripe_subscription_id);
            log_message('info', "[SUPERADMIN] Stripe sub {$condo->stripe_subscription_id} cancelada (softDelete): " . ($canceled ? 'OK' : 'FALLÓ'));
        }

        $db->table('condominiums')->where('id', $id)->update([
            'status'                 => 'suspended',
            'subscription_status'    => 'canceled',
            'stripe_subscription_id' => null,
            'plan_id'                => null,
            'deleted_at'             => Time::now()->toDateTimeString(),
            'updated_at'             => Time::now()->toDateTimeString()
        ]);

        log_message('notice', "[SUPERADMIN] Condominio ID={$id} ELIMINADO (soft) por user_id=" . session()->get('user_id'));

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Condominio eliminado exitosamente.',
            'new_status' => 'deleted'
        ]);
    }
}
