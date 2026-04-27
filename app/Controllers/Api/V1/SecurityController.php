<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\QrCodeModel;
use App\Models\Tenant\AccessLogModel;

/**
 * SecurityController (API V1)
 * 
 * Endpoints robustos para la Tableta o PWA de los Guardias de Seguridad.
 */
class SecurityController extends ResourceController
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
     * Valida la lectura de un código QR escaneado por el guardia.
     * Devuelve datos enriquecidos con unit_number, section, vehicle info.
     */
    public function validateQr()
    {
        $json = $this->request->getJSON();
        $token = $json ? $json->token : $this->request->getPost('token');

        if (empty($token)) {
            return $this->respondError('Scan vacío. Faltan datos.');
        }

        $qrModel = new QrCodeModel();
        $qr = $qrModel->where('token', $token)->first();

        if (!$qr) {
            return $this->respondError('QR INHÁBIL: No pertenece a este condominio o es inventado', 404);
        }

        if ($qr['status'] === 'revoked') {
             return $this->respondError('QR DENEGADO: Este código fue revocado permanentemente.', 403);
        }

        if ($qr['status'] === 'used') {
            return $this->respondError('QR UTILIZADO: Este código ya fue usado y no puede ser reutilizado.', 403);
        }

        // ── Validación de Ventana Temporal ──
        $now = new \DateTime('now', new \DateTimeZone('America/Mexico_City'));
        $validFrom = new \DateTime($qr['valid_from'], new \DateTimeZone('America/Mexico_City'));
        $validUntil = new \DateTime($qr['valid_until'], new \DateTimeZone('America/Mexico_City'));

        $isSingleEntry = ((int)($qr['usage_limit'] ?? 1) === 1);

        if ($isSingleEntry) {
            // "Una entrada": Solo puede usarse en la fecha exacta de valid_from
            $todayStr = $now->format('Y-m-d');
            $entryDateStr = $validFrom->format('Y-m-d');

            if ($todayStr < $entryDateStr) {
                return $this->respondError(
                    "QR NO VÁLIDO AÚN: Este pase es válido a partir del " . $validFrom->format('d/m/Y') . ".",
                    403
                );
            }
            if ($todayStr > $entryDateStr) {
                return $this->respondError(
                    'QR EXPIRADO: La fecha de acceso de este pase ya pasó.',
                    403
                );
            }
        } else {
            // "QR temporal": Puede usarse dentro del rango valid_from – valid_until
            if ($now < $validFrom) {
                return $this->respondError(
                    "QR NO VÁLIDO AÚN: Este pase temporal es válido a partir del " . $validFrom->format('d/m/Y H:i') . ".",
                    403
                );
            }
            if ($now > $validUntil) {
                return $this->respondError(
                    'QR EXPIRADO: El periodo de acceso de este pase temporal ha finalizado.',
                    403
                );
            }
        }

        // Enriquecer con datos de la unidad
        $db = \Config\Database::connect();
        $unit = $db->table('units')
                   ->select('units.unit_number, units.floor, sections.name as section_name')
                   ->join('sections', 'sections.id = units.section_id', 'left')
                   ->where('units.id', $qr['unit_id'])
                   ->get()
                   ->getRowArray();

        // ── Recuperar Residentes de la Unidad ──
        $residents = $db->table('residents')
                        ->select('users.first_name, users.last_name')
                        ->join('users', 'users.id = residents.user_id')
                        ->where('residents.unit_id', $qr['unit_id'])
                        ->where('residents.is_active', 1)
                        ->get()
                        ->getResultArray();

        $unitDataResponse = [
            'unit_number' => $unit['unit_number'] ?? 'N/A',
            'floor'       => $unit['floor'] ?? '',
            'section'     => $unit['section_name'] ?? '',
            'residents'   => array_map(function($r) {
                return ['name' => trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''))];
            }, $residents)
        ];

        // Verificar si ya hay una entrada activa (sin salida) para este QR
        $activeEntry = $db->query("
            SELECT e.id, e.visitor_name, e.visitor_type, e.plate_number,
                   e.photo_url, e.photo_plate_url, e.created_at, e.unit_id, e.qr_code_id
            FROM access_logs e
            WHERE e.qr_code_id = ? AND e.type = 'entry'
              AND NOT EXISTS (SELECT 1 FROM access_logs x WHERE x.entry_log_id = e.id AND x.type = 'exit')
            ORDER BY e.created_at DESC LIMIT 1
        ", [$qr['id']])->getRowArray();

        if ($activeEntry) {
            // El QR ya fue escaneado y tiene una entrada activa — redirigir a salida
            $activeEntry['unit_number'] = $unit['unit_number'] ?? 'N/A';
            $activeEntry['visit_type'] = $qr['visit_type'] ?? 'Visita';
            $activeEntry['vehicle_type'] = $qr['vehicle_type'] ?? '';
            return $this->respondSuccess([
                'message'       => 'SALIDA PENDIENTE',
                'action'        => 'exit',
                'active_entry'  => $activeEntry,
                'qr_data'       => $qr,
                'unit_number'   => $unit['unit_number'] ?? 'N/A',
                'section_name'  => $unit['section_name'] ?? '',
                'floor'         => $unit['floor'] ?? '',
                'unit'          => $unitDataResponse, // Inyección al cliente
            ]);
        }

        // Cambiar el estado del QR a 'renovado' al ser escaneado exitosamente
        $qrModel->update($qr['id'], ['status' => 'renovado']);
        $qr['status'] = 'renovado';

        return $this->respondSuccess([
            'message'       => 'ACCESO CONCEDIDO',
            'action'        => 'entry',
            'qr_data'       => $qr,
            'unit_number'   => $unit['unit_number'] ?? 'N/A',
            'section_name'  => $unit['section_name'] ?? '',
            'floor'         => $unit['floor'] ?? '',
            'unit'          => $unitDataResponse, // Inyección al cliente
        ]);
    }

    /**
     * Registro físico de entrada con fotos (multipart/form-data)
     */
    public function entry()
    {
        $qrId        = $this->request->getPost('qr_code_id');
        $visitorName = $this->request->getPost('visitor_name');
        $visitorType = $this->request->getPost('visitor_type') ?: 'pedestrian';
        $visitType   = $this->request->getPost('visit_type');
        $vehicleType = $this->request->getPost('vehicle_type');
        $plateNumber = $this->request->getPost('plate_number');
        $unitId      = $this->request->getPost('unit_id');
        $gate        = $this->request->getPost('gate_number') ?: 'Caseta Principal';
        $notes       = $this->request->getPost('notes');

        // Procesar foto ID
        $photoIdUrl = null;
        $fileId = $this->request->getFile('photo_id');
        if ($fileId && $fileId->isValid() && !$fileId->hasMoved()) {
            $newName = 'id_' . time() . '_' . $fileId->getRandomName();
            $fileId->move(WRITEPATH . 'uploads/access', $newName);
            $photoIdUrl = 'writable/uploads/access/' . $newName;
        }

        // Procesar foto placas
        $photoPlateUrl = null;
        $filePlate = $this->request->getFile('photo_plate');
        if ($filePlate && $filePlate->isValid() && !$filePlate->hasMoved()) {
            $newName = 'plate_' . time() . '_' . $filePlate->getRandomName();
            $filePlate->move(WRITEPATH . 'uploads/access', $newName);
            $photoPlateUrl = 'writable/uploads/access/' . $newName;
        }

        // Resolver unit_id desde QR si no se envió directamente
        if ($qrId && !$unitId) {
            $qrModel = new QrCodeModel();
            $qr = $qrModel->find($qrId);
            if ($qr) {
                $unitId = $qr['unit_id'];
                // Quemar un uso del QR
                $qrModel->update($qrId, ['times_used' => $qr['times_used'] + 1]);
            }
        }

        $data = [
            'type'            => 'entry',
            'recorded_by'     => $this->request->userId,
            'qr_code_id'      => $qrId,
            'unit_id'         => $unitId,
            'visitor_name'    => $visitorName,
            'visitor_type'    => $visitorType,
            'visit_type'      => $visitType,
            'vehicle_type'    => $vehicleType,
            'plate_number'    => $plateNumber,
            'photo_url'       => $photoIdUrl,
            'photo_plate_url' => $photoPlateUrl,
            'gate_number'     => $gate,
            'notes'           => $notes,
        ];

        $logModel = new AccessLogModel();
        $logId = $logModel->insert($data);

        // 🔔 Notificación push al residente de la unidad
        if ($unitId) {
            $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
            // ✅ Ajuste #1: Usar qr_code_id como visita_id para trazabilidad
            $visitaId = !empty($qrId) ? (int)$qrId : null;
            \App\Services\AccessNotificationService::notifyEntry(
                (int)$unitId, $visitorName ?? 'Visitante', (int)$tenantId, $visitaId
            );
        }

        return $this->respondSuccess([
            'message' => 'ENTRADA REGISTRADA',
            'log_id'  => $logId,
        ]);
    }

    /**
     * Registro de salida vinculado a un entry_log_id
     * Acepta JSON o multipart/form-data (cuando incluye foto de evidencia)
     */
    public function exit()
    {
        // Soportar tanto JSON como multipart/form-data
        $contentType = $this->request->getHeaderLine('Content-Type');
        $isJson = str_contains($contentType, 'application/json');

        if ($isJson) {
            $json = $this->request->getJSON();
            $entryLogId = $json ? ($json->entry_log_id ?? null) : null;
        } else {
            $entryLogId = $this->request->getPost('entry_log_id');
        }

        if (empty($entryLogId)) {
            return $this->respondError('Falta el ID de entrada para registrar la salida.');
        }

        $logModel = new AccessLogModel();
        $entryLog = $logModel->find($entryLogId);

        if (!$entryLog || $entryLog['type'] !== 'entry') {
            return $this->respondError('Registro de entrada no encontrado.', 404);
        }

        // Verificar que no exista ya una salida
        $existingExit = $logModel->where('entry_log_id', $entryLogId)->where('type', 'exit')->first();
        if ($existingExit) {
            return $this->respondError('Ya se registró una salida para este visitante.');
        }

        // Procesar foto de evidencia de salida
        $photoExitUrl = null;
        $fileExit = $this->request->getFile('photo_exit');
        if ($fileExit && $fileExit->isValid() && !$fileExit->hasMoved()) {
            $newName = 'exit_' . time() . '_' . $fileExit->getRandomName();
            $fileExit->move(WRITEPATH . 'uploads/access', $newName);
            $photoExitUrl = 'writable/uploads/access/' . $newName;
        }

        $data = [
            'type'           => 'exit',
            'recorded_by'    => $this->request->userId,
            'entry_log_id'   => $entryLogId,
            'qr_code_id'     => $entryLog['qr_code_id'],
            'unit_id'        => $entryLog['unit_id'],
            'visitor_name'   => $entryLog['visitor_name'],
            'visitor_type'   => $entryLog['visitor_type'],
            'plate_number'   => $entryLog['plate_number'],
            'gate_number'    => 'Caseta Principal',
            'notes'          => 'Salida registrada por guardia',
            'photo_exit_url' => $photoExitUrl,
        ];

        $exitLogId = $logModel->insert($data);

        // Invalidar QR después de registrar salida — SOLO para pases de "Una entrada"
        if (!empty($entryLog['qr_code_id'])) {
            $qrModel = new QrCodeModel();
            $qr = $qrModel->find($entryLog['qr_code_id']);
            if ($qr && (int)($qr['usage_limit'] ?? 1) === 1) {
                $qrModel->update($qr['id'], ['status' => 'used']);
            }
        }

        // 🔔 Notificación push al residente de la unidad
        if (!empty($entryLog['unit_id'])) {
            $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
            // ✅ Ajuste #1: Usar qr_code_id como visita_id (consistente con entrada)
            $visitaId = !empty($entryLog['qr_code_id']) ? (int)$entryLog['qr_code_id'] : (int)$entryLog['id'];
            \App\Services\AccessNotificationService::notifyExit(
                (int)$entryLog['unit_id'], $entryLog['visitor_name'] ?? 'Visitante', (int)$tenantId, $visitaId
            );
        }

        return $this->respondSuccess([
            'message' => 'SALIDA REGISTRADA',
            'log_id'  => $exitLogId,
        ]);
    }

    /**
     * Visitantes activos (entraron hoy y no tienen salida registrada)
     */
    public function activeVisitors()
    {
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $visitors = $db->table('access_logs as e')
            ->select("e.id, e.visitor_name, e.visitor_type, e.plate_number, e.photo_url, e.photo_plate_url, e.created_at, e.unit_id, e.qr_code_id, units.unit_number, COALESCE(qr_codes.visit_type, e.visit_type, 'Visita') AS visit_type, COALESCE(qr_codes.vehicle_type, e.vehicle_type, '') AS vehicle_type")
            ->join('units', 'units.id = e.unit_id', 'left')
            ->join('qr_codes', 'qr_codes.id = e.qr_code_id', 'left')
            ->where('e.condominium_id', $tenantId)
            ->where('e.type', 'entry')
            ->where('NOT EXISTS (SELECT 1 FROM access_logs x WHERE x.entry_log_id = e.id AND x.type = "exit")', null, false)
            ->orderBy('e.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->respondSuccess($visitors);
    }

    /**
     * Directorio de Unidades y Residentes para el Teclado PWA
     */
    public function unitsDirectory()
    {
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $units = $db->table('units')
                    ->select('units.id, units.unit_number, units.floor, sections.name as section_name')
                    ->join('sections', 'sections.id = units.section_id', 'left')
                    ->where('units.condominium_id', $tenantId)
                    ->orderBy('units.unit_number', 'ASC')
                    ->get()
                    ->getResultArray();

        $residents = $db->table('residents')
                        ->select('residents.unit_id, residents.type, users.id as user_id, users.first_name, users.last_name')
                        ->join('users', 'users.id = residents.user_id')
                        ->where('residents.condominium_id', $tenantId)
                        ->where('residents.is_active', 1)
                        ->get()
                        ->getResultArray();

        $resByUnit = [];
        foreach ($residents as $r) {
            $r['name'] = trim($r['first_name'] . ' ' . $r['last_name']);
            $r['type_label'] = ($r['type'] === 'owner') ? 'Propietario' : 'Inquilino';
            
            $words = explode(' ', $r['name']);
            $initials = '';
            foreach (array_slice($words, 0, 2) as $w) {
                if(!empty($w)) $initials .= strtoupper($w[0]);
            }
            $r['initials'] = $initials ?: 'U';

            $resByUnit[$r['unit_id']][] = $r;
        }

        foreach ($units as &$u) {
            $u['residents'] = $resByUnit[$u['id']] ?? [];
            $u['total_residents'] = count($u['residents']);
            
            $searchString = strtolower($u['unit_number']);
            foreach($u['residents'] as $r) {
                $searchString .= ' ' . strtolower($r['name']);
            }
            $u['search_string'] = $searchString;
        }

        return $this->respondSuccess($units);
    }

    /**
     * Registro de entradas y salidas con filtro por mes/año
     * Devuelve solo entries con su exit_time si existe
     */
    public function entryLogs()
    {
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $month = $this->request->getGet('month') ?: date('m');
        $year  = $this->request->getGet('year')  ?: date('Y');

        $entries = $db->query("
            SELECT e.id, e.visitor_name, e.visitor_type, e.plate_number, 
                   e.photo_url, e.photo_plate_url, e.created_at AS entry_time,
                   e.unit_id, e.qr_code_id,
                   u.unit_number,
                   COALESCE(qr.visit_type, e.visit_type, 'Visita') AS visit_type,
                   COALESCE(qr.vehicle_type, e.vehicle_type, '') AS vehicle_type,
                   x.created_at AS exit_time,
                   x.photo_exit_url
            FROM access_logs e
            LEFT JOIN units u ON u.id = e.unit_id
            LEFT JOIN qr_codes qr ON qr.id = e.qr_code_id
            LEFT JOIN access_logs x ON x.entry_log_id = e.id AND x.type = 'exit'
            WHERE e.condominium_id = ?
              AND e.type = 'entry'
              AND MONTH(e.created_at) = ?
              AND YEAR(e.created_at) = ?
            ORDER BY e.created_at DESC
        ", [$tenantId, (int)$month, (int)$year])->getResultArray();

        return $this->respondSuccess($entries);
    }

    /**
     * Detalle completo de un visitante (entry log) con datos de salida
     */
    public function visitorDetail($entryLogId)
    {
        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $entry = $db->query("
            SELECT e.id, e.visitor_name, e.visitor_type, e.plate_number,
                   e.photo_url, e.photo_plate_url, e.created_at AS entry_time,
                   e.unit_id, e.qr_code_id, e.notes,
                   u.unit_number,
                   COALESCE(qr.visit_type, e.visit_type, 'Visita') AS visit_type,
                   COALESCE(qr.vehicle_type, e.vehicle_type, '') AS vehicle_type,
                   e.plate_number AS vehicle_plate,
                   x.created_at AS exit_time,
                   x.photo_exit_url
            FROM access_logs e
            LEFT JOIN units u ON u.id = e.unit_id
            LEFT JOIN qr_codes qr ON qr.id = e.qr_code_id
            LEFT JOIN access_logs x ON x.entry_log_id = e.id AND x.type = 'exit'
            WHERE e.id = ? AND e.condominium_id = ? AND e.type = 'entry'
        ", [(int)$entryLogId, $tenantId])->getRowArray();

        if (!$entry) {
            return $this->respondError('Registro no encontrado.', 404);
        }

        return $this->respondSuccess($entry);
    }

    /**
     * Sirve imágenes de acceso desde la carpeta writable (ruta pública no autenticada)
     */
    public function serveImage($fileName)
    {
        // Prevenir directory traversal
        $fileName = str_replace(['..', '\\', '/'], '', $fileName);
        $filePath = WRITEPATH . 'uploads/access/' . $fileName;

        if (!is_file($filePath)) {
            // Devolver imagen por defecto o 404
            return $this->response->setStatusCode(404)->setBody('Image not found');
        }

        $mimeType = mime_content_type($filePath) ?: 'image/jpeg';
        
        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Cache-Control', 'public, max-age=86400')
            ->setBody(file_get_contents($filePath));
    }

    /**
     * GET /api/v1/security/offline-cache
     * Devuelve QR codes activos para cache offline del guardia.
     * Solo: status=active, times_used < usage_limit, valid_until >= NOW()
     */
    public function offlineCache()
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $db = \Config\Database::connect();

        $now = date('Y-m-d H:i:s');

        $qrs = $db->table('qr_codes q')
            ->select('q.token, q.unit_id, q.visitor_name, q.valid_from, q.valid_until, q.usage_limit, q.times_used, q.status, q.visit_type, q.vehicle_type, u.unit_number')
            ->join('units u', 'u.id = q.unit_id', 'left')
            ->where('q.condominium_id', $tenantId)
            ->where('q.status', 'active')
            ->where('q.valid_until >=', $now)
            ->where('q.times_used < q.usage_limit', null, false)
            ->get()
            ->getResultArray();

        return $this->respondSuccess([
            'qr_codes'   => $qrs,
            'cached_at'  => $now,
            'count'      => count($qrs),
        ]);
    }

    /**
     * POST /api/v1/security/sync-access
     * Recibe logs de accesos registrados offline y los procesa contra la BD real.
     * Payload: { "logs": [{ "token": "...", "timestamp": "...", "guard_id": 123 }, ...] }
     */
    public function syncAccess()
    {
        $json = $this->request->getJSON(true);
        $logs = $json['logs'] ?? [];

        if (empty($logs)) {
            return $this->respondError('No hay logs para sincronizar.', 422);
        }

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $db = \Config\Database::connect();

        $processed = 0;
        $duplicates = 0;
        $errors = [];

        // Use authenticated user as fallback guard_id
        $authenticatedUserId = $this->request->userId ?? 0;

        foreach ($logs as $idx => $log) {
            $token    = $log['token'] ?? '';
            $timestamp = $log['timestamp'] ?? '';
            $guardId  = (int)($log['guard_id'] ?? 0);

            // Fallback: if client sent guard_id=0, use authenticated user
            if ($guardId === 0) {
                $guardId = (int)$authenticatedUserId;
            }

            if (empty($token) || empty($timestamp)) {
                $errors[] = ['index' => $idx, 'reason' => 'Token o timestamp vacío'];
                continue;
            }

            // Buscar QR en BD real (fuente de verdad)
            $qr = $db->table('qr_codes')
                ->where('token', $token)
                ->where('condominium_id', $tenantId)
                ->get()
                ->getRowArray();

            if (!$qr) {
                $errors[] = ['index' => $idx, 'token' => $token, 'reason' => 'QR no encontrado en este condominio'];
                continue;
            }

            // Validar duplicado: mismo token + timestamp + guard_id
            $exists = $db->table('access_logs')
                ->where('qr_code_id', $qr['id'])
                ->where('recorded_by', $guardId)
                ->where('created_at', $timestamp)
                ->where('type', 'entry')
                ->countAllResults();

            if ($exists > 0) {
                $duplicates++;
                continue;
            }

            // Validar status actual del QR en BD real
            if ($qr['status'] === 'revoked' || $qr['status'] === 'used') {
                $errors[] = ['index' => $idx, 'token' => $token, 'reason' => 'QR revocado o agotado (status=' . $qr['status'] . ')'];
                continue;
            }

            // Validar usos reales contra BD (NO confiar en cache del cliente)
            $realTimesUsed = (int)$qr['times_used'];
            $usageLimit    = (int)$qr['usage_limit'];
            if ($realTimesUsed >= $usageLimit) {
                $errors[] = ['index' => $idx, 'token' => $token, 'reason' => 'Límite de usos alcanzado (real=' . $realTimesUsed . '/' . $usageLimit . ')'];
                continue;
            }

            // Registrar access_log
            $db->table('access_logs')->insert([
                'type'         => 'entry',
                'recorded_by'  => $guardId,
                'qr_code_id'   => $qr['id'],
                'unit_id'      => $qr['unit_id'],
                'visitor_name' => $qr['visitor_name'] ?? 'Visitante',
                'visitor_type' => 'pedestrian',
                'visit_type'   => $qr['visit_type'] ?? 'Visita',
                'gate_number'  => 'Caseta Principal',
                'notes'        => 'Registrado offline — sincronizado',
                'condominium_id' => $tenantId,
                'created_at'   => $timestamp,
            ]);

            // Actualizar times_used en BD real
            $db->table('qr_codes')->where('id', $qr['id'])->update([
                'times_used' => $realTimesUsed + 1,
            ]);

            // Marcar como 'used' si alcanzó el límite
            if (($realTimesUsed + 1) >= $usageLimit) {
                $db->table('qr_codes')->where('id', $qr['id'])->update([
                    'status' => 'used',
                ]);
            }

            $processed++;
        }

        return $this->respondSuccess([
            'processed'  => $processed,
            'duplicates' => $duplicates,
            'errors'     => $errors,
            'total_sent' => count($logs),
        ]);
    }
}
