<?php

namespace App\Controllers\SuperAdmin;

use App\Controllers\BaseController;
use App\Models\Core\SubscriptionModel;

/**
 * SubscriptionController
 * 
 * Controlador encargado de procesar y visualizar el estado de cuenta
 * del cliente hacia nosotros (el Dueño del SaaS).
 */
class SubscriptionController extends BaseController
{
    /**
     * Obtener todas las suscripciones (Activas, Vencidas, Canceladas)
     */
    public function index()
    {
        $subModel = new SubscriptionModel();
        $subscriptions = $subModel->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $subscriptions]);
    }

    /**
     * Activar / Renovar una Suscripción
     */
    public function activate($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID requerido']);

        $subModel = new SubscriptionModel();
        $subscription = $subModel->find($id);

        if (!$subscription) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Suscripción no encontrada']);
        }
        
        // Sumar 30 días o 1 mes desde el inicio actual o desde hoy si estaba vencida
        $startDate = date('Y-m-d H:i:s');
        $endDate   = date('Y-m-d H:i:s', strtotime('+1 month'));

        $subModel->update($id, [
            'status'     => 'active',
            'start_date' => $startDate,
            'end_date'   => $endDate
        ]);

        return $this->response->setJSON(['status' => 200, 'message' => 'Suscripción renovada exitosamente', 'valid_until' => $endDate]);
    }

    /**
     * Cancelar definitivamente una suscripción
     */
    public function cancel($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID requerido']);

        $subModel = new SubscriptionModel();
        if (!$subModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Suscripción no encontrada']);
        }

        $subModel->update($id, ['status' => 'cancelled']);

        return $this->response->setJSON(['status' => 200, 'message' => 'Suscripción CANCELADA']);
    }
}
