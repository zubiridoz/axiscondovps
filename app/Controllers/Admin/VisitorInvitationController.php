<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\VisitorInvitationModel;
use App\Models\Tenant\QrCodeModel;

/**
 * VisitorInvitationController
 * 
 * Gestión de invitaciones de visitantes, creadas por residentes o admin.
 */
class VisitorInvitationController extends BaseController
{
    /**
     * Lista todas las invitaciones
     */
    public function index()
    {
        $invitationModel = new VisitorInvitationModel();
        // Filtro implícito multi-tenant
        $invitations = $invitationModel->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $invitations]);
    }

    /**
     * Crea una nueva invitación
     */
    public function create()
    {
        $data = [
            'unit_id'               => $this->request->getPost('unit_id'),
            'created_by'            => session()->get('user_id'),
            'visitor_name'          => $this->request->getPost('visitor_name'),
            'expected_arrival_date' => $this->request->getPost('expected_arrival_date'),
            'notes'                 => $this->request->getPost('notes'),
            'status'                => 'pending' // pending, approved, cancelled, used
        ];

        if (empty($data['visitor_name']) || empty($data['unit_id'])) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Nombre y Unidad requeridos']);
        }

        $invitationModel = new VisitorInvitationModel();
        $invitationId = $invitationModel->insert($data);

        return $this->response->setJSON(['status' => 201, 'message' => 'Invitación generada', 'id' => $invitationId]);
    }

    /**
     * Cancela una invitación anticipadamente
     */
    public function cancel($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $invitationModel = new VisitorInvitationModel();
        if (!$invitationModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Invitación no encontrada']);
        }

        $invitationModel->update($id, ['status' => 'cancelled']);

        return $this->response->setJSON(['status' => 200, 'message' => 'Invitación cancelada']);
    }

    /**
     * Ver estado de la invitación
     */
    public function viewStatus($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $invitationModel = new VisitorInvitationModel();
        $invitation = $invitationModel->find($id);

        if (!$invitation) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Invitación no encontrada']);
        }

        return $this->response->setJSON(['status' => 200, 'data' => $invitation]);
    }

    /**
     * Aprueba la invitación y genera automáticamente su código QR correspondiente
     */
    public function generateQrCode($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID de invitación requerido']);

        $invitationModel = new VisitorInvitationModel();
        $invitation = $invitationModel->find($id);

        if (!$invitation) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Invitación no encontrada']);
        }

        if ($invitation['status'] === 'cancelled' || $invitation['status'] === 'used') {
            return $this->response->setJSON(['status' => 400, 'error' => 'La invitación no está en estado válido para generar QR']);
        }

        // Generar token único seguro
        $qrToken = bin2hex(random_bytes(16));
        
        $qrData = [
            'unit_id'      => $invitation['unit_id'],
            'created_by'   => session()->get('user_id'),
            'visitor_name' => $invitation['visitor_name'],
            'token'        => $qrToken,
            'valid_from'   => date('Y-m-d H:i:s'), // Puede configurarse basado en expected_arrival_date
            'valid_until'  => date('Y-m-d H:i:s', strtotime('+24 hours')), // Vigencia 24 hrs
            'usage_limit'  => 1, // Solo un pase
            'times_used'   => 0,
            'status'       => 'active'
        ];

        $qrCodeModel = new QrCodeModel();
        $qrId = $qrCodeModel->insert($qrData);

        // Actualizamos estado de la invitación a aprobada
        $invitationModel->update($id, ['status' => 'approved']);

        return $this->response->setJSON([
            'status'  => 201, 
            'message' => 'QR generado y vinculado a la invitación', 
            'qr_id'   => $qrId,
            'token'   => $qrToken
        ]);
    }
}
