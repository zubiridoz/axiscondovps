<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;

/**
 * PlanController (SuperAdmin)
 * CRUD de planes SaaS + asignación a condominios.
 */
class PlanController extends BaseController
{
    private function db()
    {
        return \Config\Database::connect();
    }

    /**
     * Vista principal de planes.
     */
    public function index()
    {
        return view('superadmin/plans');
    }

    /**
     * Listar todos los planes (AJAX).
     */
    public function list()
    {
        $plans = $this->db()->table('plans')
            ->orderBy('sort_order', 'ASC')
            ->orderBy('min_units', 'ASC')
            ->get()->getResultArray();

        foreach ($plans as &$plan) {
            $plan['condos_count'] = $this->db()->table('condominiums')
                ->where('plan_id', $plan['id'])
                ->where('deleted_at IS NULL')
                ->countAllResults();
        }

        return $this->response->setJSON(['success' => true, 'plans' => $plans]);
    }

    /**
     * Crear plan (AJAX).
     */
    public function store()
    {
        $data = $this->validatePlanData();
        if (is_object($data)) return $data;

        $data['slug'] = url_title($data['name'], '-', true);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $existing = $this->db()->table('plans')->where('slug', $data['slug'])->countAllResults();
        if ($existing > 0) {
            $data['slug'] .= '-' . time();
        }

        $this->db()->table('plans')->insert($data);
        return $this->response->setJSON(['success' => true, 'message' => 'Plan creado exitosamente.']);
    }

    /**
     * Actualizar plan (AJAX).
     */
    public function update($id)
    {
        $plan = $this->db()->table('plans')->where('id', $id)->get()->getRowArray();
        if (!$plan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Plan no encontrado.'])->setStatusCode(404);
        }

        $data = $this->validatePlanData();
        if (is_object($data)) return $data;

        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db()->table('plans')->where('id', $id)->update($data);
        return $this->response->setJSON(['success' => true, 'message' => 'Plan actualizado exitosamente.']);
    }

    /**
     * Eliminar plan (AJAX).
     */
    public function delete($id)
    {
        $condos = $this->db()->table('condominiums')->where('plan_id', $id)->where('deleted_at IS NULL')->countAllResults();
        if ($condos > 0) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "No se puede eliminar: {$condos} condominio(s) tienen este plan asignado."
            ])->setStatusCode(422);
        }

        $this->db()->table('plans')->where('id', $id)->delete();
        return $this->response->setJSON(['success' => true, 'message' => 'Plan eliminado.']);
    }

    /**
     * Obtener un plan (AJAX).
     */
    public function get($id)
    {
        $plan = $this->db()->table('plans')->where('id', $id)->get()->getRowArray();
        if (!$plan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Plan no encontrado.'])->setStatusCode(404);
        }
        return $this->response->setJSON(['success' => true, 'plan' => $plan]);
    }

    /**
     * Asignar plan a condominio (AJAX).
     */
    public function assign()
    {
        $condoId       = (int) $this->request->getPost('condominium_id');
        $planId        = (int) $this->request->getPost('plan_id');
        $cycle         = $this->request->getPost('billing_cycle') ?: 'monthly';
        $paymentMethod = $this->request->getPost('payment_method') ?: 'stripe';

        if (!in_array($cycle, ['monthly', 'yearly'])) $cycle = 'monthly';
        if (!in_array($paymentMethod, ['stripe', 'manual'])) $paymentMethod = 'stripe';

        $plan = $this->db()->table('plans')->where('id', $planId)->get()->getRowArray();
        if (!$plan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Plan no encontrado.'])->setStatusCode(404);
        }

        $condo = $this->db()->table('condominiums')->where('id', $condoId)->get()->getRowArray();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado.'])->setStatusCode(404);
        }

        $unitCount = $this->db()->table('units')->where('condominium_id', $condoId)->countAllResults();
        if ($unitCount > (int) $plan['max_units']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "El condominio tiene {$unitCount} unidades pero el plan permite máximo {$plan['max_units']}."
            ])->setStatusCode(422);
        }

        $updateData = [
            'plan_id'        => $planId,
            'billing_cycle'  => $cycle,
            'payment_method' => $paymentMethod,
        ];

        // Solo asignar fecha de expiración automática si es Stripe
        // Para manual, la vigencia se establece al registrar cada pago
        if ($paymentMethod === 'stripe') {
            $updateData['plan_expires_at'] = $cycle === 'yearly'
                ? date('Y-m-d H:i:s', strtotime('+1 year'))
                : date('Y-m-d H:i:s', strtotime('+1 month'));
        }

        $this->db()->table('condominiums')->where('id', $condoId)->update($updateData);

        $methodLabel = $paymentMethod === 'manual' ? ' (Pago Manual)' : '';
        return $this->response->setJSON(['success' => true, 'message' => "Plan \"{$plan['name']}\" asignado exitosamente.{$methodLabel}"]);
    }

    /**
     * Listar condominios para dropdown de asignación.
     */
    public function condominiums()
    {
        $condos = $this->db()->query("
            SELECT c.id, c.name, c.plan_id, p.name AS plan_name,
                   (SELECT COUNT(*) FROM units WHERE units.condominium_id = c.id) AS unit_count
            FROM condominiums c
            LEFT JOIN plans p ON p.id = c.plan_id
            WHERE c.deleted_at IS NULL
            ORDER BY c.name ASC
        ")->getResultArray();

        return $this->response->setJSON(['success' => true, 'condominiums' => $condos]);
    }

    /**
     * Validar datos del plan.
     */
    private function validatePlanData()
    {
        $name = trim((string) $this->request->getPost('name'));
        $minUnits = (int) $this->request->getPost('min_units');
        $maxUnits = (int) $this->request->getPost('max_units');
        $priceMonthly = (float) $this->request->getPost('price_monthly');
        $priceYearly = (float) $this->request->getPost('price_yearly');
        $sortOrder = (int) $this->request->getPost('sort_order');
        $isActive = $this->request->getPost('is_active') !== null ? (int) $this->request->getPost('is_active') : 1;

        if ($name === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre es obligatorio.'])->setStatusCode(422);
        }
        if ($minUnits < 1) $minUnits = 1;
        if ($maxUnits < $minUnits) {
            return $this->response->setJSON(['success' => false, 'message' => 'Max unidades debe ser >= Min unidades.'])->setStatusCode(422);
        }

        return [
            'name' => $name,
            'min_units' => $minUnits,
            'max_units' => $maxUnits,
            'price_monthly' => $priceMonthly,
            'price_yearly' => $priceYearly,
            'sort_order' => $sortOrder,
            'is_active' => $isActive,
            'features' => trim((string) $this->request->getPost('features')) ?: null,
        ];
    }
}
