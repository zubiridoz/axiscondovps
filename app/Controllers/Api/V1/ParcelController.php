<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\ParcelModel;
use App\Models\Tenant\CourierModel;

/**
 * ParcelController (API V1)
 * 
 * Endpoints para que los guardias gestionen la paquetería desde su PWA.
 */
class ParcelController extends ResourceController
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
     * GET /api/v1/security/parcels/pending
     * Lista paquetes pendientes en caseta (status = at_gate)
     */
    public function pending()
    {
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $parcels = $db->table('parcels')
            ->select('parcels.*, units.unit_number, sections.name as section_name')
            ->join('units', 'units.id = parcels.unit_id', 'left')
            ->join('sections', 'sections.id = units.section_id', 'left')
            ->where('parcels.condominium_id', $tenantId)
            ->where('parcels.status', 'at_gate')
            ->orderBy('parcels.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Enrich with resident data (id, name)
        foreach ($parcels as &$p) {
            // 🔐 Ocultar PIN del response — el guardia NO debe ver el PIN
            unset($p['delivery_pin']);

            $residents = $db->table('residents')
                ->select('users.id as user_id, users.first_name, users.last_name, residents.type')
                ->join('users', 'users.id = residents.user_id')
                ->where('residents.unit_id', $p['unit_id'])
                ->where('residents.condominium_id', $tenantId)
                ->where('residents.is_active', 1)
                ->get()
                ->getResultArray();
            
            $p['residents'] = array_map(function($r) {
                return [
                    'user_id' => $r['user_id'],
                    'name'    => trim($r['first_name'] . ' ' . $r['last_name']),
                    'type'    => $r['type']
                ];
            }, $residents);

            $names = array_map(fn($r) => $r['name'], $p['residents']);
            $p['resident_names'] = implode(', ', $names) ?: 'Sin Asignar';
        }

        return $this->respondSuccess(['parcels' => $parcels, 'total' => count($parcels)]);
    }

    /**
     * GET /api/v1/security/parcels/count
     * Retorna conteo de paquetes pendientes para badge
     */
    public function count()
    {
        $parcelModel = new ParcelModel();
        $total = $parcelModel->where('status', 'at_gate')->countAllResults();

        return $this->respondSuccess(['count' => $total]);
    }

    /**
     * POST /api/v1/security/parcels
     * Registrar llegada de paquete a caseta (multipart/form-data con foto)
     */
    public function create()
    {
        $unitId     = $this->request->getPost('unit_id');
        $courier    = $this->request->getPost('courier');
        $quantity   = (int)($this->request->getPost('quantity') ?: 1);
        $parcelType = $this->request->getPost('parcel_type') ?: 'Paquete';

        if (empty($unitId)) {
            return $this->respondError('Se requiere indicar a qué unidad va el paquete');
        }

        if (empty($courier)) {
            return $this->respondError('Se requiere seleccionar un proveedor de paquetería');
        }

        // Process parcel photo
        $photoUrl = null;
        $file = $this->request->getFile('photo');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = 'parcel_' . time() . '_' . $file->getRandomName();
            $file->move(WRITEPATH . 'uploads/parcels', $newName);
            $photoUrl = 'writable/uploads/parcels/' . $newName;
        }

        if (empty($photoUrl)) {
            return $this->respondError('La foto del paquete es obligatoria');
        }

        $userId = $this->request->userId;

        // 🔐 Generar PIN de entrega de 4 dígitos
        $deliveryPin = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        $parcelModel = new ParcelModel();
        $id = $parcelModel->insert([
            'unit_id'      => $unitId,
            'received_by'  => $userId,
            'courier'      => $courier,
            'photo_url'    => $photoUrl,
            'quantity'     => $quantity,
            'parcel_type'  => $parcelType,
            'status'       => 'at_gate',
            'delivery_pin' => $deliveryPin,
        ]);

        // 🔔 Notificación push al residente de la unidad
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        // Resolver nombre del dispositivo/caseta desde el usuario guardia
        $deviceName = 'Portería';
        $guardUser = \Config\Database::connect()->table('users')
            ->select('first_name, last_name')
            ->where('id', $userId)
            ->get()->getRowArray();
        if ($guardUser) {
            // Dispositivos de caseta se registran con first_name='AxisCondo' y last_name='Caseta X'
            $deviceName = ($guardUser['first_name'] === 'AxisCondo')
                ? $guardUser['last_name']
                : trim($guardUser['first_name'] . ' ' . $guardUser['last_name']);
        }
        \App\Services\ParcelNotificationService::notifyArrival(
            (int)$unitId, (int)$tenantId, $quantity, $parcelType, $courier, $deviceName, (int)$id, $deliveryPin
        );

        return $this->respondSuccess([
            'message' => 'Paquete recibido en caseta',
            'id'      => $id
        ]);
    }

    /**
     * POST /api/v1/security/parcels/:id/deliver
     * Registrar entrega de paquete al residente con firma
     */
    public function deliver($id = null)
    {
        if (!$id) {
            return $this->respondError('ID de paquete no proporcionado');
        }

        $parcelModel = new ParcelModel();
        $parcel = $parcelModel->find($id);

        if (!$parcel) {
            return $this->respondError('Paquete no encontrado', 404);
        }

        if ($parcel['status'] !== 'at_gate') {
            return $this->respondError('Este paquete ya fue entregado o devuelto');
        }

        // Leer JSON o POST según Content-Type
        $contentType = $this->request->getHeaderLine('Content-Type');

        if (str_contains($contentType, 'application/json')) {
            $input = $this->request->getJSON(true);
        } else {
            $input = $this->request->getPost();
        }

        $pickedUpName  = $input['picked_up_name'] ?? null;
        $pickedUpBy    = $input['picked_up_by'] ?? null;
        $inputPin      = $input['delivery_pin'] ?? null;

        if (empty($pickedUpName)) {
            return $this->respondError('Se requiere indicar quién recoge el paquete');
        }

        // 🔐 Validar PIN de entrega
        $storedPin = $parcel['delivery_pin'] ?? null;
        if (!empty($storedPin)) {
            // Paquete tiene PIN — validación obligatoria
            if (empty($inputPin)) {
                return $this->respondError('Se requiere el PIN de entrega. Solicítalo al residente.');
            }
            if ((string)$inputPin !== (string)$storedPin) {
                return $this->respondError('PIN de entrega incorrecto. Verifica con el residente.');
            }
        }
        // Si storedPin es null → paquete legacy, se permite sin PIN

        // Firma opcional (legacy support) — ya no se requiere obligatoriamente
        $signatureUrl = null;
        $signatureData = $input['signature'] ?? null;
        if (!empty($signatureData)) {
            if (str_starts_with($signatureData, 'data:')) {
                $signatureData = preg_replace('/^data:image\/\w+;base64,/', '', $signatureData);
            }
            $decoded = base64_decode($signatureData);
            if ($decoded !== false) {
                $signatureName = 'sig_' . time() . '_' . uniqid() . '.png';
                $signaturePath = WRITEPATH . 'uploads/parcels/' . $signatureName;
                if (!is_dir(WRITEPATH . 'uploads/parcels')) {
                    mkdir(WRITEPATH . 'uploads/parcels', 0755, true);
                }
                file_put_contents($signaturePath, $decoded);
                $signatureUrl = 'writable/uploads/parcels/' . $signatureName;
            }
        }

        $updateData = [
            'status'         => 'delivered_to_resident',
            'delivered_at'   => date('Y-m-d H:i:s'),
            'picked_up_by'   => $pickedUpBy ?: null,
            'picked_up_name' => $pickedUpName,
        ];
        if ($signatureUrl) {
            $updateData['signature_url'] = $signatureUrl;
        }

        $parcelModel->update($id, $updateData);

        // 🔔 Notificación push al residente de la unidad
        if (!empty($parcel['unit_id'])) {
            $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
            \App\Services\ParcelNotificationService::notifyDelivery(
                (int)$parcel['unit_id'],
                (int)$tenantId,
                $parcel['parcel_type'] ?? 'Paquete',
                $pickedUpName,
                (int)$id
            );
        }

        return $this->respondSuccess([
            'message' => 'Paquete entregado exitosamente'
        ]);
    }
    /**
     * GET /api/v1/security/parcels/history?month=&year=
     * Historial de paquetería filtrado por mes/año
     */
    public function history()
    {
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $month = $this->request->getGet('month') ?: date('m');
        $year  = $this->request->getGet('year')  ?: date('Y');

        $parcels = $db->table('parcels')
            ->select('parcels.*, units.unit_number, sections.name as section_name')
            ->join('units', 'units.id = parcels.unit_id', 'left')
            ->join('sections', 'sections.id = units.section_id', 'left')
            ->where('parcels.condominium_id', $tenantId)
            ->where('MONTH(parcels.created_at)', (int)$month)
            ->where('YEAR(parcels.created_at)', (int)$year)
            ->orderBy('parcels.created_at', 'DESC')
            ->get()
            ->getResultArray();

        // Enrich each parcel
        foreach ($parcels as &$p) {
            $residents = $db->table('residents')
                ->select('users.first_name, users.last_name')
                ->join('users', 'users.id = residents.user_id')
                ->where('residents.unit_id', $p['unit_id'])
                ->where('residents.condominium_id', $tenantId)
                ->where('residents.is_active', 1)
                ->get()
                ->getResultArray();

            $names = array_map(fn($r) => trim($r['first_name'] . ' ' . $r['last_name']), $residents);
            $p['resident_names'] = implode(', ', $names) ?: 'Sin Asignar';
        }

        return $this->respondSuccess(['parcels' => $parcels]);
    }

    /**
     * GET /api/v1/security/parcels/:id
     * Detalle completo de un paquete
     */
    public function detail($id = null)
    {
        if (!$id) {
            return $this->respondError('ID no proporcionado');
        }

        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $parcel = $db->table('parcels')
            ->select('parcels.*, units.unit_number, sections.name as section_name')
            ->join('units', 'units.id = parcels.unit_id', 'left')
            ->join('sections', 'sections.id = units.section_id', 'left')
            ->where('parcels.id', $id)
            ->where('parcels.condominium_id', $tenantId)
            ->get()
            ->getRowArray();

        if (!$parcel) {
            return $this->respondError('Paquete no encontrado', 404);
        }

        return $this->respondSuccess($parcel);
    }

    /**
     * GET /api/v1/security/couriers
     * Lista de proveedores de paquetería activos
     */
    public function couriers()
    {
        $courierModel = new CourierModel();
        $couriers = $courierModel->where('is_active', 1)
                                 ->orderBy('sort_order', 'ASC')
                                 ->findAll();

        return $this->respondSuccess($couriers);
    }

    /**
     * Sirve imágenes de paquetes desde la carpeta writable
     */
    public function servePhoto($fileName)
    {
        $fileName = str_replace(['..', '\\', '/'], '', $fileName);
        $filePath = WRITEPATH . 'uploads/parcels/' . $fileName;

        if (!is_file($filePath)) {
            return $this->response->setStatusCode(404)->setBody('Image not found');
        }

        $mimeType = mime_content_type($filePath) ?: 'image/jpeg';

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }
}
