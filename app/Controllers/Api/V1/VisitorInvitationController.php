<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\VisitorInvitationModel;
use App\Models\Tenant\QrCodeModel;
use App\Models\Tenant\ResidentModel;

/**
 * VisitorInvitationController (API V1)
 * 
 * Gestión de pases/invitaciones desde la app del Residente.
 */
class VisitorInvitationController extends ResourceController
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
     * Listar invitaciones generadas por el residente
     */
    public function index()
    {
        $userId = $this->request->userId;
        
        // Resolver la unidad ACTUAL del residente para aislar datos por unidad
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        $currentUnitId = $resident['unit_id'] ?? null;

        $invitationModel = new VisitorInvitationModel();
        $builder = $invitationModel->where('created_by', $userId);

        // Filtrar por unidad actual — solo muestra invitaciones de la unidad vigente
        if ($currentUnitId) {
            $builder->where('unit_id', $currentUnitId);
        }

        $invitations = $builder->orderBy('created_at', 'DESC')
                               ->findAll();

        return $this->respondSuccess(['invitations' => $invitations]);
    }

    /**
     * Crear invitación + Generar Token QR simultáneamente (Flujo automatizado para Residentes)
     */
    public function create()
    {
        $userId = $this->request->userId;
        $visitorName = $this->request->getPost('visitor_name');
        $expectedDate = $this->request->getPost('expected_arrival_date') ?? date('Y-m-d H:i:s');
        
        if (empty($visitorName)) {
            return $this->respondError('El nombre del visitante es obligatorio');
        }

        // Obtener la unidad por defecto del residente
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        
        if (!$resident || empty($resident['unit_id'])) {
            return $this->respondError('No tienes asiganda una unidad funcional en este condominio', 403);
        }

        // 1. Guardar la invitación
        $invitationModel = new VisitorInvitationModel();
        $invId = $invitationModel->insert([
            'unit_id'               => $resident['unit_id'],
            'created_by'            => $userId,
            'visitor_name'          => $visitorName,
            'expected_arrival_date' => $expectedDate,
            'notes'                 => $this->request->getPost('notes'),
            'status'                => 'approved' // Auto-aprobada si es propietario
        ]);

        // 2. Generar el Token QR en la tabla qr_codes
        $qrToken = bin2hex(random_bytes(16));
        $qrCodeModel = new QrCodeModel();
        
        $qrId = $qrCodeModel->insert([
            'unit_id'      => $resident['unit_id'],
            'created_by'   => $userId,
            'visitor_name' => $visitorName,
            'token'        => $qrToken,
            'valid_from'   => date('Y-m-d H:i:s'),
            'valid_until'  => date('Y-m-d H:i:s', strtotime($expectedDate . ' + 24 hours')),
            'usage_limit'  => 1,
            'times_used'   => 0,
            'status'       => 'active'
        ]);

        return $this->respondSuccess([
            'message'       => 'Pase de visitante generado exitosamente',
            'invitation_id' => $invId,
            'qr_token'      => $qrToken
        ]);
    }
}
