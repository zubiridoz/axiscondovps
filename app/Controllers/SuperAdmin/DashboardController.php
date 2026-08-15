<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\Tenant\CondominiumModel;
use App\Models\Core\UserModel;
use App\Models\Core\SubscriptionModel;

/**
 * DashboardController
 * 
 * Panel principal del dueño del SaaS (SuperAdmin).
 * Muestra métricas globales y tabla completa de tenants con administradores.
 */
class DashboardController extends BaseController
{
    /**
     * Muestra las métricas globales del SaaS y la tabla de todos los condominios.
     */
    public function index()
    {
        $db = \Config\Database::connect();

        // ── Métricas globales ──
        $activeCondominiums  = $db->table('condominiums')->where('status', 'active')->where('deleted_at IS NULL')->countAllResults();
        $suspendedCondominiums = $db->table('condominiums')->where('status', 'suspended')->where('deleted_at IS NULL')->countAllResults();
        $totalUsers          = $db->table('users')->countAllResults();
        $activeSubscriptions = $db->table('subscriptions')->where('status', 'active')->countAllResults();

        // ── Tabla de todos los condominios (incluye admins + métricas) ──
        // Subquery para obtener el admin más antiguo de cada condominio
        $condominiums = $db->query("
            SELECT 
                c.id,
                c.name,
                c.address,
                c.status,
                c.created_at,
                c.suspended_at,
                c.deleted_at,
                p.name AS plan_name,
                (SELECT COUNT(*) FROM units WHERE units.condominium_id = c.id) AS total_units,
                (SELECT COUNT(*) FROM residents WHERE residents.condominium_id = c.id) AS total_residents,
                (SELECT MAX(ft.created_at) FROM financial_transactions ft WHERE ft.condominium_id = c.id) AS last_activity
            FROM condominiums c
            LEFT JOIN plans p ON p.id = c.plan_id
            WHERE c.deleted_at IS NULL
            ORDER BY c.created_at DESC
        ")->getResultArray();

        // Cargar TODOS los administradores de cada condominio
        $condoIds = array_column($condominiums, 'id');
        $adminsMap = [];
        if (!empty($condoIds)) {
            $adminsData = $db->table('user_condominium_roles ucr')
                ->select('ucr.condominium_id, u.first_name, u.last_name, u.email, u.last_web_activity, u.last_app_activity')
                ->join('roles r', 'r.id = ucr.role_id')
                ->join('users u', 'u.id = ucr.user_id')
                ->where('r.name', 'ADMIN')
                ->where('u.deleted_at IS NULL')
                ->whereIn('ucr.condominium_id', $condoIds)
                ->orderBy('u.last_web_activity', 'DESC')
                ->get()
                ->getResultArray();
            
            foreach ($adminsData as $ad) {
                $adminsMap[$ad['condominium_id']][] = $ad;
            }
        }

        foreach ($condominiums as &$c) {
            $c['admins'] = $adminsMap[$c['id']] ?? [];
        }

        // ── Métricas de Ingresos (Revenue) ──
        // MRR: Suma de price_monthly de planes asignados a condominios activos (excluye eliminados)
        $mrrResult = $db->query("
            SELECT COALESCE(SUM(
                CASE 
                    WHEN c.billing_cycle = 'yearly' THEN p.price_yearly / 12
                    ELSE p.price_monthly
                END
            ), 0) AS mrr
            FROM condominiums c
            INNER JOIN plans p ON p.id = c.plan_id
            WHERE c.status = 'active' 
              AND c.deleted_at IS NULL
              AND c.plan_id IS NOT NULL
        ")->getRowArray();
        $mrr = (float) ($mrrResult['mrr'] ?? 0);

        // Total Facturado Estimado: MRR × meses desde el primer condominio activo
        $firstCondoDate = $db->table('condominiums')
            ->where('status', 'active')
            ->where('deleted_at IS NULL')
            ->where('plan_id IS NOT NULL')
            ->selectMin('created_at')
            ->get()->getRowArray();
        $monthsActive = 1;
        if (!empty($firstCondoDate['created_at'])) {
            $start = new \DateTime($firstCondoDate['created_at']);
            $now = new \DateTime();
            $diff = $start->diff($now);
            $monthsActive = max(1, ($diff->y * 12) + $diff->m + ($diff->d > 0 ? 1 : 0));
        }
        $totalBilled = $mrr * $monthsActive;

        // Suscripciones con plan activo (condominios con plan que NO están eliminados)
        $paidSubscriptions = $db->table('condominiums')
            ->where('status', 'active')
            ->where('deleted_at IS NULL')
            ->where('plan_id IS NOT NULL')
            ->countAllResults();

        $data = [
            'metrics' => [
                'active_condominiums'    => $activeCondominiums,
                'suspended_condominiums' => $suspendedCondominiums,
                'total_users'            => $totalUsers,
                'active_subscriptions'   => $activeSubscriptions
            ],
            'revenue' => [
                'mrr'                => $mrr,
                'total_billed'       => $totalBilled,
                'paid_subscriptions' => $paidSubscriptions,
                'months_active'      => $monthsActive,
            ],
            'condominiums' => $condominiums
        ];

        return view('superadmin/dashboard', $data);
    }
}
