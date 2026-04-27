<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Core\UserModel;
use App\Models\Tenant\AccessLogModel;
use App\Models\Tenant\DeviceModel;
use App\Models\Tenant\QrCodeModel;
use App\Models\Tenant\StaffMemberModel;

/**
 * AccessLogController
 * 
 * Bitácora Inmutable del Condominio (Auditoría de Entradas y Salidas).
 * IMPORTANTE: No debe existir método de Update() o Delete() para garantizar la integridad fiscal/legal.
 */
class AccessLogController extends BaseController
{
    /**
     * Lista los accesos de hoy (o por fechas en filtros más avanzados)
     */
    public function index()
    {
        $today = date('Y-m-d');
        
        $logModel = new AccessLogModel();
        // findAll() aplica base_tenant + filtramos por el día de hoy
        $accessLogs = $logModel->like('created_at', $today, 'after')
                               ->orderBy('created_at', 'DESC')
                               ->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $accessLogs]);
    }

    /**
     * Visualiza el historial inmutable histórico
     */
    public function history()
    {
        // En producción real requeriría Paginación (paginate(50))
        $logModel = new AccessLogModel();
        $accessLogs = $logModel->orderBy('created_at', 'DESC')->findAll(100); // Límite a los últimos 100

        return $this->response->setJSON(['status' => 200, 'data' => $accessLogs]);
    }

    /**
     * Registra una **ENTRADA** peatonal o vehicular
     */
    public function logEntry()
    {
        return $this->insertLog('entry');
    }

    /**
     * Registra una **SALIDA** peatonal o vehicular
     */
    public function logExit()
    {
        return $this->insertLog('exit');
    }

    /**
     * Motor interno inmutable de inserción
     */
    private function insertLog(string $accessType)
    {
        $data = [
            'visitor_id'  => $this->request->getPost('visitor_id'), // Puede ser nulo
            'unit_id'     => $this->request->getPost('unit_id'), // Puede ser nulo (ej: visitante general, trabajador)
            'qr_code_id'  => $this->request->getPost('qr_code_id'),
            'access_type' => $accessType,
            'gate_number' => $this->request->getPost('gate_number') ?? 'A1',
            'notes'       => $this->request->getPost('notes')
        ];

        // Validaciones si fue por QR
        if ($data['qr_code_id']) {
            $qrModel = new QrCodeModel();
            $qr = $qrModel->find($data['qr_code_id']);
            if ($qr) {
                // Autocompletamos unidad y descontamos uso en la tabla de QR
                $data['unit_id'] = $qr['unit_id'];
                
                if ($accessType === 'entry') {
                    $qrModel->update($qr['id'], ['times_used' => $qr['times_used'] + 1]);
                }
            }
        }

        $logModel = new AccessLogModel();
        // El BaseTenantModel forzará la inserción del 'condominium_id' automáticamente
        $logId = $logModel->insert($data);

        $accion = $accessType === 'entry' ? 'Entrada' : 'Salida';
        return $this->response->setJSON(['status' => 201, 'message' => "Registro de $accion guardado.", 'id' => $logId]);
    }

    /**
     * RENDER HTML MVC - Vista Frontal del Administrador
     */
    public function indexView()
    {
        $condoId = $this->resolveCondominiumId();
        if (!$condoId) {
            return redirect()->back()->with('error', 'No se encontró contexto de condominio.');
        }

        $db = \Config\Database::connect();

        // 1. Estadísticas del Periodo (Entradas)
        $reqStart = $this->request->getGet('start');
        $reqEnd = $this->request->getGet('end');
        $start = $reqStart ? $reqStart : date('Y-m-d');
        $end = $reqEnd ? $reqEnd : date('Y-m-d');
        
        // Filtros independientes para QR
        $reqQstart = $this->request->getGet('qstart');
        $reqQend = $this->request->getGet('qend');
        $qstart = $reqQstart ? $reqQstart : date('Y-m-d');
        $qend = $reqQend ? $reqQend : date('Y-m-d');

        // Total de Entradas (Periodo)
        $totalEntradasHoy = $db->table('access_logs')
            ->where('condominium_id', $condoId)
            ->where('type', 'entry')
            ->where("DATE(created_at) >= '$start' AND DATE(created_at) <= '$end'")
            ->countAllResults();

        // Actualmente Adentro (Cualquier entrada sin salida vinculada de cualquier fecha)
        $actualmenteAdentro = $db->table('access_logs e')
            ->where('e.condominium_id', $condoId)
            ->where('e.type', 'entry')
            ->where('NOT EXISTS (SELECT 1 FROM access_logs x WHERE x.entry_log_id = e.id AND x.type = "exit")', null, false)
            ->countAllResults();

        // Con Vehículos (Actualmente adentro con vehículo)
        $conVehiculosHoy = $db->table('access_logs e')
            ->where('e.condominium_id', $condoId)
            ->where('e.type', 'entry')
            ->where('e.visitor_type', 'vehicle')
            ->where('NOT EXISTS (SELECT 1 FROM access_logs x WHERE x.entry_log_id = e.id AND x.type = "exit")', null, false)
            ->countAllResults();

        // 2. Registros Detallados (Agrupados Entrada-Salida)
        $logModel = new AccessLogModel();
        $accessLogs = $logModel->select('access_logs.*, units.unit_number, x.created_at as exit_time, x.photo_exit_url as exit_photo_url, COALESCE(qr_codes.visit_type, access_logs.visit_type, "Visita") AS visit_type, COALESCE(qr_codes.vehicle_type, access_logs.vehicle_type, "") AS vehicle_type')
                               ->join('access_logs x', 'x.entry_log_id = access_logs.id AND x.type = "exit"', 'left')
                               ->join('units', 'units.id = access_logs.unit_id', 'left')
                               ->join('qr_codes', 'qr_codes.id = access_logs.qr_code_id', 'left')
                               ->where('access_logs.condominium_id', $condoId)
                               ->where('access_logs.type', 'entry')
                               ->where("DATE(access_logs.created_at) >= '$start'")
                               ->where("DATE(access_logs.created_at) <= '$end'")
                               ->orderBy('access_logs.created_at', 'DESC')
                               ->findAll(500); // Expanded limit because of date filtering

        // 3. Unidades para filtros/modal
        $unitModel = new \App\Models\Tenant\UnitModel();
        $units = $unitModel->where('condominium_id', $condoId)->findAll();

        // 4. Códigos QR (Restaurado para la pestaña correspondiente)
        // Mostrar TODOS los QR del periodo para visibilidad completa del ciclo de vida
        $qrModel = new \App\Models\Tenant\QrCodeModel();
        $qrCodes = $qrModel->select('qr_codes.*, units.unit_number')
                           ->join('units', 'units.id = qr_codes.unit_id', 'left')
                           ->where('qr_codes.condominium_id', $condoId)
                           ->where("DATE(qr_codes.created_at) >= '$qstart'")
                           ->where("DATE(qr_codes.created_at) <= '$qend'")
                           ->orderBy('qr_codes.created_at', 'DESC')
                           ->findAll();

        // 5. Dispositivos de Seguridad (credenciales PWA)
        $devicePayload = $this->loadSecurityDevices($condoId);
        $securityDevices = $devicePayload['devices'];
        $deviceStats = $devicePayload['stats'];

        // 6. Staff Members
        $staffModel = new StaffMemberModel();
        $staffMembers = $staffModel->where('condominium_id', $condoId)
                                   ->orderBy('created_at', 'DESC')
                                   ->findAll();

        // Enrich staff with linked device email
        foreach ($staffMembers as &$sm) {
            $sm['device_email'] = '';
            if (!empty($sm['device_id'])) {
                foreach ($securityDevices as $sd) {
                    if ((int)$sd['id'] === (int)$sm['device_id']) {
                        $sm['device_email'] = $sd['email'];
                        break;
                    }
                }
            }
        }
        unset($sm);

        $stats = [
            'total_entradas' => $totalEntradasHoy,
            'adentro'        => $actualmenteAdentro,
            'con_vehiculo'   => $conVehiculosHoy,
            'total_registros'=> count($accessLogs)
        ];

        return view('admin/security', [
            'accessLogs' => $accessLogs, 
            'units'      => $units, 
            'qrCodes'    => $qrCodes,
            'stats'      => $stats,
            'startDate'  => $start,
            'endDate'    => $end,
            'qrStartDate'=> $qstart,
            'qrEndDate'  => $qend,
            'securityDevices' => $securityDevices,
            'deviceStats' => $deviceStats,
            'staffMembers' => $staffMembers,
            'axisDomain' => 'axiscondo.mx',
            'axisBrand' => 'AxisCondo'
        ]);
    }

    public function createDeviceCredential()
    {
        $condoId = $this->resolveCondominiumId();
        if (!$condoId) {
            return $this->response->setJSON(['status' => 400, 'message' => 'No se encontró contexto de condominio.']);
        }

        $rawName = (string) ($this->request->getPost('device_name') ?? '');
        $deviceName = $this->normalizeDeviceName($rawName);
        if (mb_strlen($deviceName) < 2 || mb_strlen($deviceName) > 40) {
            return $this->response->setJSON([
                'status' => 422,
                'message' => 'El nombre del dispositivo debe tener entre 2 y 40 caracteres.'
            ]);
        }

        $db = \Config\Database::connect();
        $securityRoleId = $this->getSecurityRoleId($db);
        if (!$securityRoleId) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => 'No existe el rol SECURITY en el sistema.'
            ]);
        }

        $plainPassword = $this->generateOneTimePassword();
        $email = $this->buildUniqueAxisEmail($db, $deviceName);
        $deviceIdentifier = $this->generateUniqueDeviceIdentifier($db, $condoId);

        if (!$email || !$deviceIdentifier) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => 'No se pudieron generar credenciales únicas. Intenta de nuevo.'
            ]);
        }

        $userModel = new UserModel();
        $deviceModel = new DeviceModel();
        $now = date('Y-m-d H:i:s');

        $db->transStart();
        $userModel->insert([
            'first_name'    => 'AxisCondo',
            'last_name'     => $deviceName,
            'email'         => $email,
            'password_hash' => password_hash($plainPassword, PASSWORD_BCRYPT),
            'status'        => 'active'
        ]);
        $userId = (int) $userModel->getInsertID();

        $db->table('user_condominium_roles')->insert([
            'user_id' => $userId,
            'condominium_id' => $condoId,
            'role_id' => $securityRoleId,
            'created_at' => $now,
            'updated_at' => $now
        ]);

        $deviceModel->insert([
            'user_id' => $userId,
            'device_identifier' => $deviceIdentifier,
            'app_version' => $deviceName,
            'os_version' => 'active'
        ]);
        $deviceId = (int) $deviceModel->getInsertID();
        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => 'No se pudo crear el dispositivo. Intenta de nuevo.'
            ]);
        }

        $device = $this->getSecurityDeviceById($db, $condoId, $deviceId);
        if (!$device) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => 'Se creó el dispositivo, pero no se pudo recuperar su información.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 201,
            'message' => 'Credenciales generadas correctamente.',
            'data' => [
                'device' => $device,
                'credentials' => [
                    'email' => $email,
                    'password' => $plainPassword
                ]
            ]
        ]);
    }

    public function updateDeviceName()
    {
        $condoId = $this->resolveCondominiumId();
        if (!$condoId) {
            return $this->response->setJSON(['status' => 400, 'message' => 'No se encontró contexto de condominio.']);
        }

        $deviceId = (int) ($this->request->getPost('device_id') ?? 0);
        $rawName = (string) ($this->request->getPost('device_name') ?? '');
        $deviceName = $this->normalizeDeviceName($rawName);

        if ($deviceId <= 0) {
            return $this->response->setJSON(['status' => 422, 'message' => 'ID de dispositivo inválido.']);
        }
        if (mb_strlen($deviceName) < 2 || mb_strlen($deviceName) > 40) {
            return $this->response->setJSON([
                'status' => 422,
                'message' => 'El nombre del dispositivo debe tener entre 2 y 40 caracteres.'
            ]);
        }

        $db = \Config\Database::connect();
        $existing = $this->getSecurityDeviceById($db, $condoId, $deviceId);
        if (!$existing) {
            return $this->response->setJSON(['status' => 404, 'message' => 'Dispositivo no encontrado.']);
        }

        $deviceModel = new DeviceModel();
        $deviceModel->update($deviceId, ['app_version' => $deviceName]);

        $updated = $this->getSecurityDeviceById($db, $condoId, $deviceId);
        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Nombre actualizado correctamente.',
            'data' => [
                'device' => $updated
            ]
        ]);
    }

    public function resetDevicePassword()
    {
        $condoId = $this->resolveCondominiumId();
        if (!$condoId) {
            return $this->response->setJSON(['status' => 400, 'message' => 'No se encontró contexto de condominio.']);
        }

        $deviceId = (int) ($this->request->getPost('device_id') ?? 0);
        if ($deviceId <= 0) {
            return $this->response->setJSON(['status' => 422, 'message' => 'ID de dispositivo inválido.']);
        }

        $db = \Config\Database::connect();
        $device = $this->getSecurityDeviceById($db, $condoId, $deviceId);
        if (!$device) {
            return $this->response->setJSON(['status' => 404, 'message' => 'Dispositivo no encontrado.']);
        }

        $plainPassword = $this->generateOneTimePassword();
        $userModel = new UserModel();
        $userModel->update((int) $device['user_id'], [
            'password_hash' => password_hash($plainPassword, PASSWORD_BCRYPT),
            'status' => 'active'
        ]);

        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Contraseña restablecida correctamente.',
            'data' => [
                'device' => $device,
                'credentials' => [
                    'email' => $device['email'],
                    'password' => $plainPassword
                ]
            ]
        ]);
    }

    public function deleteDevice()
    {
        $condoId = $this->resolveCondominiumId();
        if (!$condoId) {
            return $this->response->setJSON(['status' => 400, 'message' => 'No se encontró contexto de condominio.']);
        }

        $deviceId = (int) ($this->request->getPost('device_id') ?? 0);
        if ($deviceId <= 0) {
            return $this->response->setJSON(['status' => 422, 'message' => 'ID de dispositivo inválido.']);
        }

        $db = \Config\Database::connect();
        $device = $this->getSecurityDeviceById($db, $condoId, $deviceId);
        if (!$device) {
            return $this->response->setJSON(['status' => 404, 'message' => 'Dispositivo no encontrado.']);
        }

        $userId = (int) $device['user_id'];

        $db->transStart();

        // 1. Deactivate user account (soft-delete via status)
        $userModel = new UserModel();
        $userModel->update($userId, [
            'status' => 'inactive',
            'deleted_at' => date('Y-m-d H:i:s')
        ]);

        // 2. Remove role assignment for this condominium
        $db->table('user_condominium_roles')
            ->where('user_id', $userId)
            ->where('condominium_id', $condoId)
            ->delete();

        // 3. Delete device record
        $deviceModel = new DeviceModel();
        $deviceModel->delete($deviceId);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON([
                'status' => 500,
                'message' => 'No se pudo eliminar el dispositivo. Intenta de nuevo.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Dispositivo eliminado correctamente.',
            'data' => [
                'device_id' => $deviceId
            ]
        ]);
    }

    private function resolveCondominiumId(): ?int
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) {
            \App\Services\TenantService::getInstance()->setTenantId((int) $demoCondo['id']);
        }

        $condoId = session()->get('condominium_id') ?? ($demoCondo['id'] ?? null);
        return $condoId ? (int) $condoId : null;
    }

    private function loadSecurityDevices(int $condoId): array
    {
        $db = \Config\Database::connect();
        $builder = $db->table('devices d');
        $rows = $builder->select('d.id, d.user_id, d.device_identifier, d.app_version, d.os_version, d.created_at, d.updated_at, u.email, u.status AS user_status')
            ->join('users u', 'u.id = d.user_id AND u.deleted_at IS NULL', 'inner')
            ->join('user_condominium_roles ucr', 'ucr.user_id = u.id', 'inner')
            ->join('roles r', 'r.id = ucr.role_id', 'inner')
            ->where('ucr.condominium_id', $condoId)
            ->where('r.name', 'SECURITY')
            ->orderBy('d.created_at', 'DESC')
            ->get()
            ->getResultArray();

        $devices = array_map(static function (array $row): array {
            $name = trim((string) ($row['app_version'] ?? ''));
            $status = strtolower((string) ($row['user_status'] ?? 'active')) === 'active' ? 'active' : 'inactive';

            return [
                'id' => (int) $row['id'],
                'user_id' => (int) $row['user_id'],
                'name' => $name !== '' ? $name : 'Dispositivo',
                'email' => (string) ($row['email'] ?? ''),
                'identifier' => (string) ($row['device_identifier'] ?? ''),
                'status' => $status,
                'created_at' => (string) ($row['created_at'] ?? ''),
                'updated_at' => (string) ($row['updated_at'] ?? '')
            ];
        }, $rows);

        $active = count(array_filter($devices, static fn(array $d): bool => $d['status'] === 'active'));

        return [
            'devices' => $devices,
            'stats' => [
                'total' => count($devices),
                'active' => $active
            ]
        ];
    }

    private function getSecurityDeviceById(\CodeIgniter\Database\BaseConnection $db, int $condoId, int $deviceId): ?array
    {
        $row = $db->table('devices d')
            ->select('d.id, d.user_id, d.device_identifier, d.app_version, d.os_version, d.created_at, d.updated_at, u.email, u.status AS user_status')
            ->join('users u', 'u.id = d.user_id AND u.deleted_at IS NULL', 'inner')
            ->join('user_condominium_roles ucr', 'ucr.user_id = u.id', 'inner')
            ->join('roles r', 'r.id = ucr.role_id', 'inner')
            ->where('ucr.condominium_id', $condoId)
            ->where('r.name', 'SECURITY')
            ->where('d.id', $deviceId)
            ->get()
            ->getRowArray();

        if (!$row) {
            return null;
        }

        $name = trim((string) ($row['app_version'] ?? ''));
        $status = strtolower((string) ($row['user_status'] ?? 'active')) === 'active' ? 'active' : 'inactive';

        return [
            'id' => (int) $row['id'],
            'user_id' => (int) $row['user_id'],
            'name' => $name !== '' ? $name : 'Dispositivo',
            'email' => (string) ($row['email'] ?? ''),
            'identifier' => (string) ($row['device_identifier'] ?? ''),
            'status' => $status,
            'created_at' => (string) ($row['created_at'] ?? ''),
            'updated_at' => (string) ($row['updated_at'] ?? '')
        ];
    }

    private function getSecurityRoleId(\CodeIgniter\Database\BaseConnection $db): ?int
    {
        $row = $db->table('roles')->select('id')->where('name', 'SECURITY')->get()->getRowArray();
        return $row ? (int) $row['id'] : null;
    }

    private function normalizeDeviceName(string $value): string
    {
        $clean = preg_replace('/\s+/', ' ', trim($value));
        return (string) $clean;
    }

    private function slugifyLabel(string $value): string
    {
        $value = strtolower($value);
        $value = preg_replace('/[^a-z0-9]+/u', '-', $value);
        $value = trim((string) $value, '-');
        return $value !== '' ? $value : 'dispositivo';
    }

    private function buildUniqueAxisEmail(\CodeIgniter\Database\BaseConnection $db, string $deviceName): ?string
    {
        $base = substr($this->slugifyLabel($deviceName), 0, 18);
        if ($base === '') {
            $base = 'dispositivo';
        }

        for ($i = 0; $i < 10; $i++) {
            $candidate = $base . '-' . random_int(1000, 9999) . '@axiscondo.mx';
            $exists = (int) $db->table('users')->where('email', $candidate)->countAllResults();
            if ($exists === 0) {
                return $candidate;
            }
        }

        return null;
    }

    private function generateUniqueDeviceIdentifier(\CodeIgniter\Database\BaseConnection $db, int $condoId): ?string
    {
        for ($i = 0; $i < 10; $i++) {
            $candidate = 'axis-sec-' . $condoId . '-' . bin2hex(random_bytes(4));
            $exists = (int) $db->table('devices')->where('device_identifier', $candidate)->countAllResults();
            if ($exists === 0) {
                return $candidate;
            }
        }
        return null;
    }

    private function generateOneTimePassword(): string
    {
        return (string) random_int(100000, 999999);
    }

    // =========================================================
    // STAFF MEMBERS CRUD
    // =========================================================

    public function createStaffMember()
    {
        $condoId = $this->resolveCondominiumId();
        if (!$condoId) {
            return $this->response->setJSON(['status' => 400, 'message' => 'No se encontró contexto de condominio.']);
        }

        $firstName = trim((string)($this->request->getPost('first_name') ?? ''));
        $lastName  = trim((string)($this->request->getPost('last_name') ?? ''));
        $staffType = trim((string)($this->request->getPost('staff_type') ?? 'security'));
        $deviceId  = $this->request->getPost('device_id');

        if (mb_strlen($firstName) < 2 || mb_strlen($lastName) < 2) {
            return $this->response->setJSON(['status' => 422, 'message' => 'Nombre y apellido son obligatorios (mín. 2 caracteres).']);
        }

        $validTypes = ['security', 'maintenance', 'other'];
        if (!in_array($staffType, $validTypes, true)) {
            $staffType = 'other';
        }

        // Handle photo upload
        $photoUrl = null;
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = 'staff_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $photo->getExtension();
            $photo->move(WRITEPATH . 'uploads/staff', $newName);
            $photoUrl = 'writable/uploads/staff/' . $newName;
        }

        // Handle ID document upload
        $idDocUrl = null;
        $idDoc = $this->request->getFile('id_document');
        if ($idDoc && $idDoc->isValid() && !$idDoc->hasMoved()) {
            $newName = 'staffid_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $idDoc->getExtension();
            $idDoc->move(WRITEPATH . 'uploads/staff', $newName);
            $idDocUrl = 'writable/uploads/staff/' . $newName;
        }

        $staffModel = new StaffMemberModel();
        $staffModel->insert([
            'condominium_id' => $condoId,
            'first_name'     => $firstName,
            'last_name'      => $lastName,
            'staff_type'     => $staffType,
            'device_id'      => ($staffType === 'security' && $deviceId) ? (int)$deviceId : null,
            'photo_url'      => $photoUrl,
            'id_document_url'=> $idDocUrl,
            'status'         => 'active',
        ]);
        $newId = (int)$staffModel->getInsertID();

        $member = $staffModel->find($newId);
        if (!$member) {
            return $this->response->setJSON(['status' => 500, 'message' => 'Se creó pero no se pudo recuperar.']);
        }

        // Enrich with device email
        $member['device_email'] = '';
        if (!empty($member['device_id'])) {
            $db = \Config\Database::connect();
            $device = $this->getSecurityDeviceById($db, $condoId, (int)$member['device_id']);
            if ($device) $member['device_email'] = $device['email'];
        }

        return $this->response->setJSON([
            'status' => 201,
            'message' => 'Personal agregado correctamente.',
            'data' => ['staff' => $member]
        ]);
    }

    public function updateStaffMember()
    {
        $condoId = $this->resolveCondominiumId();
        if (!$condoId) {
            return $this->response->setJSON(['status' => 400, 'message' => 'No se encontró contexto de condominio.']);
        }

        $staffId   = (int)($this->request->getPost('staff_id') ?? 0);
        $firstName = trim((string)($this->request->getPost('first_name') ?? ''));
        $lastName  = trim((string)($this->request->getPost('last_name') ?? ''));
        $staffType = trim((string)($this->request->getPost('staff_type') ?? ''));
        $deviceId  = $this->request->getPost('device_id');

        if ($staffId <= 0) {
            return $this->response->setJSON(['status' => 422, 'message' => 'ID de personal inválido.']);
        }
        if (mb_strlen($firstName) < 2 || mb_strlen($lastName) < 2) {
            return $this->response->setJSON(['status' => 422, 'message' => 'Nombre y apellido son obligatorios (mín. 2 caracteres).']);
        }

        $staffModel = new StaffMemberModel();
        $existing = $staffModel->where('condominium_id', $condoId)->find($staffId);
        if (!$existing) {
            return $this->response->setJSON(['status' => 404, 'message' => 'Personal no encontrado.']);
        }

        $validTypes = ['security', 'maintenance', 'other'];
        if (!in_array($staffType, $validTypes, true)) {
            $staffType = $existing['staff_type'];
        }

        $updateData = [
            'first_name' => $firstName,
            'last_name'  => $lastName,
            'staff_type' => $staffType,
            'device_id'  => ($staffType === 'security' && $deviceId) ? (int)$deviceId : null,
        ];

        // Handle photo upload
        $photo = $this->request->getFile('photo');
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $newName = 'staff_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $photo->getExtension();
            $photo->move(WRITEPATH . 'uploads/staff', $newName);
            $updateData['photo_url'] = 'writable/uploads/staff/' . $newName;
        }

        // Handle ID document upload
        $idDoc = $this->request->getFile('id_document');
        if ($idDoc && $idDoc->isValid() && !$idDoc->hasMoved()) {
            $newName = 'staffid_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $idDoc->getExtension();
            $idDoc->move(WRITEPATH . 'uploads/staff', $newName);
            $updateData['id_document_url'] = 'writable/uploads/staff/' . $newName;
        }

        $staffModel->update($staffId, $updateData);

        $updated = $staffModel->find($staffId);
        $updated['device_email'] = '';
        if (!empty($updated['device_id'])) {
            $db = \Config\Database::connect();
            $device = $this->getSecurityDeviceById($db, $condoId, (int)$updated['device_id']);
            if ($device) $updated['device_email'] = $device['email'];
        }

        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Personal actualizado correctamente.',
            'data' => ['staff' => $updated]
        ]);
    }

    public function deleteStaffMember()
    {
        $condoId = $this->resolveCondominiumId();
        if (!$condoId) {
            return $this->response->setJSON(['status' => 400, 'message' => 'No se encontró contexto de condominio.']);
        }

        $staffId = (int)($this->request->getPost('staff_id') ?? 0);
        if ($staffId <= 0) {
            return $this->response->setJSON(['status' => 422, 'message' => 'ID de personal inválido.']);
        }

        $staffModel = new StaffMemberModel();
        $existing = $staffModel->where('condominium_id', $condoId)->find($staffId);
        if (!$existing) {
            return $this->response->setJSON(['status' => 404, 'message' => 'Personal no encontrado.']);
        }

        $staffModel->delete($staffId);

        return $this->response->setJSON([
            'status' => 200,
            'message' => 'Personal eliminado correctamente.',
            'data' => ['staff_id' => $staffId]
        ]);
    }

    public function storeQr()
    {
        $visitorName = $this->request->getPost('visitor_name');
        $unitId = $this->request->getPost('unit_id');
        $visitType = $this->request->getPost('visit_type');
        $vehicleType = $this->request->getPost('vehicle_type');
        $vehiclePlate = $this->request->getPost('vehicle_plate');
        $validFromDate = $this->request->getPost('valid_from');
        $validUntilDate = $this->request->getPost('valid_until');
        $timeType = $this->request->getPost('qr_time_type');

        if (empty($visitorName) || empty($validFromDate)) {
            return $this->response->setJSON(['status' => 400, 'message' => 'Nombre del visitante y fecha son obligatorios.']);
        }

        // Format datetimes correctly for DB
        $validFrom = $validFromDate . ' 00:00:00';
        if ($timeType === 'Una entrada' || empty($validUntilDate)) {
            $validUntil = $validFromDate . ' 23:59:59';
        } else {
            $validUntil = $validUntilDate . ' 23:59:59';
        }

        // Generate Secure Unique Token
        $token = bin2hex(random_bytes(16)); // 32 characters

        $qrModel = new \App\Models\Tenant\QrCodeModel();
        
        $data = [
            'unit_id'       => $unitId ?: null,
            'created_by'    => session()->get('user_id') ?? 1, // Fallback for testing if session not active
            'visitor_name'  => $visitorName,
            'token'         => $token,
            'visit_type'    => $visitType,
            'vehicle_type'  => $vehicleType,
            'vehicle_plate' => $vehiclePlate ?: null,
            'valid_from'    => $validFrom,
            'valid_until'   => $validUntil,
            'usage_limit'   => ($timeType === 'Una entrada') ? 1 : 999,
            'status'        => 'active'
        ];

        // Ensure tenant is set
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $qrModel->insert($data);

        $publicUrl = base_url("qr/" . $token);

        return $this->response->setJSON([
            'status' => 201, 
            'message' => 'QR Generado Exitosamente',
            'url' => $publicUrl
        ]);
    }

    /**
     * Sirve imágenes de staff desde la carpeta writable de forma segura.
     */
    public function serveStaffImage($fileName)
    {
        // Prevenir directory traversal
        $fileName = str_replace(['..', '\\', '/'], '', $fileName);
        $filePath = WRITEPATH . 'uploads/staff/' . $fileName;

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
