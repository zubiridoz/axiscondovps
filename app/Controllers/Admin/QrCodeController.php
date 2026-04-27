<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\QrCodeModel;

/**
 * QrCodeController
 * 
 * Gestión de la emisión, validación y revocación de Códigos QR de acceso temporal.
 */
class QrCodeController extends BaseController
{
    /**
     * Lista códigos QR activos
     */
    public function index()
    {
        $qrModel = new QrCodeModel();
        $codes = $qrModel->where('status', 'active')->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $codes]);
    }

    /**
     * Genera un código QR de manera manual e independiente
     */
    public function generate()
    {
        $data = [
            'unit_id'      => $this->request->getPost('unit_id'),
            'created_by'   => session()->get('user_id'),
            'visitor_id'   => $this->request->getPost('visitor_id'), // Puede ser visitante registrado
            'visitor_name' => $this->request->getPost('visitor_name'), // O visitante casual
            'valid_from'   => date('Y-m-d H:i:s'),
            'valid_until'  => !empty($this->request->getPost('valid_until')) ? $this->request->getPost('valid_until') : date('Y-m-d H:i:s', strtotime('+24 hours')),
            'usage_limit'  => !empty($this->request->getPost('usage_limit')) ? $this->request->getPost('usage_limit') : 1,
            'times_used'   => 0,
            'status'       => 'active'
        ];

        // token seguro único en toda la base (optimizado por INDEX en bd)
        $data['token'] = bin2hex(random_bytes(16));

        $qrModel = new QrCodeModel();
        $qrId = $qrModel->insert($data);

        return $this->response->setJSON(['status' => 201, 'message' => 'Código QR generado', 'token' => $data['token']]);
    }

    /**
     * Revoca o inactiva un código QR manual
     */
    public function revoke($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $qrModel = new QrCodeModel();
        if (!$qrModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Código QR no encontrado']);
        }

        $qrModel->update($id, ['status' => 'revoked']);

        return $this->response->setJSON(['status' => 200, 'message' => 'Código QR revocado permanentemente']);
    }

    /**
     * Valida si un QR token es apto para brindar acceso (Normalmente disparado por la PWA del guardia)
     */
    public function validateQr()
    {
        $token = $this->request->getPost('token');

        if (empty($token)) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Token vacío']);
        }

        $qrModel = new QrCodeModel();
        // BaseTenantModel aplicará el 'condominium_id' asegurando que el QR sea de ESTE edificio
        $qr = $qrModel->where('token', $token)->first();

        if (!$qr) {
            return $this->response->setJSON(['status' => 404, 'error' => 'QR Inválido o inexistente en este Condominio.']);
        }

        if ($qr['status'] === 'revoked') {
            return $this->response->setJSON(['status' => 403, 'error' => 'Este código QR fue revocado permanentemente.']);
        }

        if ($qr['status'] === 'used') {
            return $this->response->setJSON(['status' => 403, 'error' => 'QR UTILIZADO: Este código ya fue usado y no puede ser reutilizado.']);
        }

        // ── Validación de Ventana Temporal ──
        $now = new \DateTime('now', new \DateTimeZone('America/Mexico_City'));
        $validFrom = new \DateTime($qr['valid_from'], new \DateTimeZone('America/Mexico_City'));
        $validUntil = new \DateTime($qr['valid_until'], new \DateTimeZone('America/Mexico_City'));

        $isSingleEntry = ((int)($qr['usage_limit'] ?? 1) === 1);

        if ($isSingleEntry) {
            $todayStr = $now->format('Y-m-d');
            $entryDateStr = $validFrom->format('Y-m-d');

            if ($todayStr < $entryDateStr) {
                return $this->response->setJSON([
                    'status' => 403,
                    'error' => "QR NO VÁLIDO AÚN: Este pase es válido a partir del " . $validFrom->format('d/m/Y') . "."
                ]);
            }
            if ($todayStr > $entryDateStr) {
                return $this->response->setJSON([
                    'status' => 403,
                    'error' => 'QR EXPIRADO: La fecha de acceso de este pase ya pasó.'
                ]);
            }
        } else {
            if ($now < $validFrom) {
                return $this->response->setJSON([
                    'status' => 403,
                    'error' => "QR NO VÁLIDO AÚN: Este pase temporal es válido a partir del " . $validFrom->format('d/m/Y H:i') . "."
                ]);
            }
            if ($now > $validUntil) {
                return $this->response->setJSON([
                    'status' => 403,
                    'error' => 'QR EXPIRADO: El periodo de acceso de este pase temporal ha finalizado.'
                ]);
            }
        }

        // Cambiar estado a 'renovado' al ser validado exitosamente
        $qrModel->update($qr['id'], ['status' => 'renovado']);
        $qr['status'] = 'renovado';

        return $this->response->setJSON(['status' => 200, 'message' => 'ACCESO CONCEDIDO', 'qr_data' => $qr]);
    }
}
