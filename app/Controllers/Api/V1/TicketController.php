<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\TicketModel;
use App\Models\Tenant\ResidentModel;

/**
 * TicketController (API V1)
 * 
 * Mesa de Ayuda / Reportes de vecinos desde la PWA.
 */
class TicketController extends ResourceController
{
    protected function respondSuccess($data = [])
    {
        return $this->respond(['status' => 'success', 'data' => $data]);
    }

    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON(['status' => 'error', 'message' => $message])->setStatusCode($status);
    }

    /**
     * Listar mis tickets
     */
    public function index()
    {
        $userId = $this->request->userId;
        
        $ticketModel = new TicketModel();
        $tickets = $ticketModel->where('reported_by', $userId)
                               ->orderBy('created_at', 'DESC')
                               ->findAll();

        return $this->respondSuccess(['tickets' => $tickets]);
    }

    /**
     * Crear un nuevo reporte
     */
    public function create()
    {
        $userId = $this->request->userId;
        $subject = $this->request->getPost('subject');
        $description = $this->request->getPost('description');

        if (empty($subject) || empty($description)) {
            return $this->respondError('Asunto y descripción son obligatorios');
        }

        // Determinar unidad originaria
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        $unitId = $resident ? $resident['unit_id'] : null;

        $ticketModel = new TicketModel();
        $ticketId = $ticketModel->insert([
            'unit_id'     => $unitId,
            'reported_by' => $userId,
            'subject'     => $subject,
            'description' => $description,
            'status'      => 'open'
        ]);

        return $this->respondSuccess([
            'message'   => 'Ticket enviado a administración',
            'ticket_id' => $ticketId
        ]);
    }
}
