<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Core\UserModel;
use App\Models\Tenant\ResidentModel;
use App\Models\Tenant\FinancialTransactionModel;

/**
 * ResidentController
 * 
 * Endpoints generales para el perfil del Residente (PWA).
 */
class ResidentController extends ResourceController
{
    /**
     * Devuelve el formato de respuesta de éxito requerido
     */
    protected function respondSuccess($data = [])
    {
        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    /**
     * Devuelve el formato de respuesta de error requerido
     */
    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $message
        ])->setStatusCode($status);
    }

    /**
     * Obtiene el perfil del usuario actual basado en el Token (inyectado en la request por ApiAuthFilter)
     */
    public function profile()
    {
        $userId = $this->request->userId ?? null; // Si el filtro lo inyectó
        
        if (!$userId) {
            return $this->respondError('Usuario no autenticado', 401);
        }

        $userModel = new UserModel();
        $user = $userModel->select('id, first_name, last_name, email, phone, avatar')->find($userId);
        
        if (!$user) {
            return $this->respondError('Usuario no encontrado', 404);
        }

        // Obtener datos del residente en ESTE condominio
        $residentModel = new ResidentModel();
        $residentData = $residentModel->where('user_id', $userId)->first();

        // Obtener Condominio y Unidad (importante para PWA/Flutter)
        $db = \Config\Database::connect();
        
        $condoName = 'Mi Condominio';
        $condoId = \App\Services\TenantService::getInstance()->getTenantId();
        if ($condoId) {
            $condo = $db->table('condominiums')->select('name')->where('id', $condoId)->get()->getRowArray();
            if ($condo) {
                $condoName = $condo['name'];
            }
        }
        
        $unitNumber = 'Sin unidad';
        if ($residentData && !empty($residentData['unit_id'])) {
            $unit = $db->table('units')->select('unit_number')->where('id', $residentData['unit_id'])->get()->getRowArray();
            if ($unit) {
                $unitNumber = $unit['unit_number'];
            }
        }

        // Merge para que Flutter lo cachee directo en userData
        $user['condo_name'] = $condoName;
        $user['unit_number'] = $unitNumber;
        $user['unit_id'] = ($residentData && !empty($residentData['unit_id'])) ? (int) $residentData['unit_id'] : null;

        return $this->respondSuccess([
            'profile'  => $user,
            'resident' => $residentData
        ]);
    }

    /**
     * GET /api/v1/resident/my-unit
     * Devuelve la información de la unidad del residente actual,
     * incluyendo el condominio y la lista de residentes (propietarios/inquilinos).
     */
    public function myUnit()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('Usuario no autenticado', 401);

        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        $unitId = $resident['unit_id'] ?? null;

        $db = \Config\Database::connect();

        // 1. Condominium Info
        $condominium = [
            'name' => 'Mi Condominio',
            'image' => null
        ];
        $condoId = \App\Services\TenantService::getInstance()->getTenantId();
        $restrictQr = false;
        $restrictAmenities = false;

        if ($condoId) {
            $condoRow = $db->table('condominiums')
                           ->select('name, logo, restrict_qr_delinquent, restrict_amenities_delinquent, bank_name, bank_clabe, bank_rfc, bank_card')
                           ->where('id', $condoId)
                           ->get()->getRowArray();
            if ($condoRow) {
                $condominium['name'] = $condoRow['name'];
                if (!empty($condoRow['logo'])) {
                    $condominium['image'] = $condoRow['logo'];
                }
                $condominium['bank_name'] = $condoRow['bank_name'] ?? '';
                $condominium['bank_clabe'] = $condoRow['bank_clabe'] ?? '';
                $condominium['bank_rfc'] = $condoRow['bank_rfc'] ?? '';
                
                $rawCard = $condoRow['bank_card'] ?? '';
                $condominium['bank_card'] = !empty($rawCard) ? implode(' ', str_split(preg_replace('/\D/', '', $rawCard), 4)) : '';
                
                $restrictQr = !empty($condoRow['restrict_qr_delinquent']);
                $restrictAmenities = !empty($condoRow['restrict_amenities_delinquent']);
            }
        }

        $emptyDelinquency = [
            'is_delinquent'   => false,
            'pending_balance' => 0,
            'restrictions'    => [
                'qr'        => false,
                'amenities' => false
            ]
        ];

        if (!$unitId) {
            return $this->respondSuccess([
                'condominium' => $condominium,
                'unit'        => null,
                'delinquency' => $emptyDelinquency
            ]);
        }

        // 2. Unit Info
        $unitRow = $db->table('units')
                      ->select('units.*, sections.name as section_name')
                      ->join('sections', 'sections.id = units.section_id', 'left')
                      ->where('units.id', $unitId)
                      ->get()->getRowArray();
                      
        if (!$unitRow) {
            return $this->respondSuccess([
                'condominium' => $condominium,
                'unit'        => null,
                'delinquency' => $emptyDelinquency
            ]);
        }

        $unitData = [
            'unit_number'     => $unitRow['unit_number'],
            'section'         => $unitRow['section_name'] ?? null,
            'floor'           => (int)($unitRow['floor'] ?? 0),
            'maintenance_fee' => (float)($unitRow['maintenance_fee'] ?? 0),
            'occupancy_type'  => $unitRow['occupancy_type'] ?? 'empty',
            'residents'       => []
        ];

        // 3. Residents Info
        $residentsList = $db->table('residents')
            ->select('users.first_name, users.last_name, users.avatar, residents.type')
            ->join('users', 'users.id = residents.user_id')
            ->where('residents.unit_id', $unitId)
            ->get()->getResultArray();

        foreach ($residentsList as $r) {
            $name = trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
            if (empty($name)) $name = 'Usuario';
            
            $avatarUrl = null;
            if (!empty($r['avatar'])) {
                $avatarUrl = $r['avatar'];
            }
            
            $type = $r['type'] ?? 'tenant';
            if ($type !== 'owner') $type = 'tenant';
            
            $unitData['residents'][] = [
                'name'   => $name,
                'avatar' => $avatarUrl,
                'type'   => $type
            ];
        }

        // 4. Delinquency Info (Real Balance Calculation)
        $initialBalance = (float)($unitRow['initial_balance'] ?? 0);

        $chargesRow = $db->table('financial_transactions')
            ->selectSum('amount')
            ->where('unit_id', $unitId)
            ->where('type', 'charge')
            ->where('status !=', 'cancelled')
            ->where('deleted_at IS NULL')
            ->get()->getRowArray();
        $totalCharges = (float) ($chargesRow['amount'] ?? 0);

        $creditsRow = $db->table('financial_transactions')
            ->selectSum('amount')
            ->where('unit_id', $unitId)
            ->where('type', 'credit')
            ->where('status !=', 'cancelled')
            ->where('deleted_at IS NULL')
            ->get()->getRowArray();
        $totalCredits = (float) ($creditsRow['amount'] ?? 0);

        $rawBalance = $initialBalance + $totalCharges - $totalCredits;
        $totalDebt = $rawBalance > 0 ? $rawBalance : 0;

        $delinquency = [
            'is_delinquent'   => $totalDebt > 0,
            'pending_balance' => $totalDebt,
            'restrictions'    => [
                'qr'        => $restrictQr,
                'amenities' => $restrictAmenities
            ]
        ];

        return $this->respondSuccess([
            'condominium' => $condominium,
            'unit'        => $unitData,
            'delinquency' => $delinquency
        ]);
    }

    /**
     * Estado de cuenta del residente (de sus unidades)
     */
    public function accountStatement()
    {
        $userId = $this->request->userId;
        
        // 1. Encontrar la(s) unidad(es) del usuario
        $residentModel = new ResidentModel();
        $myUnits = $residentModel->where('user_id', $userId)->findAll();
        
        if (empty($myUnits)) {
            return $this->respondSuccess(['transactions' => [], 'total_debt' => 0]);
        }
        
        $unitIds = array_column($myUnits, 'unit_id');
        
        // 2. Traer transacciones de estas unidades
        $transactionModel = new FinancialTransactionModel();
        $transactions = $transactionModel->whereIn('unit_id', $unitIds)
                                         ->orderBy('created_at', 'DESC')
                                         ->findAll();

        // 3. Calcular deuda
        $totalDebt = 0;
        foreach ($transactions as $txn) {
            if ($txn['type'] === 'charge' && in_array($txn['status'], ['pending', 'partial'])) {
                $totalDebt += current((array)$txn['amount']);
            }
        }

        return $this->respondSuccess([
            'total_debt'   => $totalDebt,
            'transactions' => $transactions
        ]);
    }

    /**
     * POST /api/v1/resident/update-profile
     * Actualiza el perfil del usuario (nombre, apellido, teléfono)
     */
    public function updateProfile()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $json = $this->request->getJSON(true);
        $userModel = new UserModel();

        $data = [];
        if (isset($json['first_name'])) $data['first_name'] = $json['first_name'];
        if (isset($json['last_name'])) $data['last_name'] = $json['last_name'];
        if (isset($json['phone'])) $data['phone'] = $json['phone'];

        if (empty($data)) {
            return $this->respondError('No hay datos para actualizar');
        }

        $userModel->update($userId, $data);
        
        $updatedUser = $userModel->select('id, first_name, last_name, email, phone, avatar')->find($userId);

        return $this->respondSuccess([
            'message' => 'Perfil actualizado correctamente',
            'user'    => $updatedUser
        ]);
    }

    /**
     * POST /api/v1/resident/update-password
     * Actualiza la contraseña validando la anterior
     */
    public function updatePassword()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $json = $this->request->getJSON(true);
        $currentPassword = $json['current_password'] ?? '';
        $newPassword = $json['new_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword)) {
            return $this->respondError('Contraseña actual y nueva son requeridas');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!password_verify($currentPassword, $user['password_hash'])) {
            return $this->respondError('La contraseña actual es incorrecta');
        }

        $userModel->update($userId, [
            'password_hash' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);

        return $this->respondSuccess([
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }

    /**
     * POST /api/v1/resident/upload-avatar
     * Sube el avatar al disco seguro y devuelve la información actualizada
     */
    public function uploadAvatar()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $file = $this->request->getFile('avatar');
        if (!$file || !$file->isValid()) {
            return $this->respondError('No se recibió la imagen de avatar o el archivo es inválido');
        }

        // Crear folder si no existe
        $uploadPath = WRITEPATH . 'uploads/avatars';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        $userModel = new UserModel();
        // Borrar avatar anterior si existe
        $user = $userModel->find($userId);
        if ($user && !empty($user['avatar'])) {
            $oldPath = $uploadPath . '/' . $user['avatar'];
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        $userModel->update($userId, ['avatar' => $newName]);
        $updatedUser = $userModel->select('id, first_name, last_name, email, phone, avatar')->find($userId);

        return $this->respondSuccess([
            'message' => 'Avatar actualizado correctamente',
            'user'    => $updatedUser
        ]);
    }

    /**
     * GET /api/v1/resident/avatar/(:segment)
     * Sirve el archivo de avatar protegiendo la ruta (usa apiauth middleware)
     */
    public function serveAvatar($filename = null)
    {
        if (!$filename) return $this->failNotFound('Archivo no especificado');

        $path = WRITEPATH . 'uploads/avatars/' . $filename;
        if (!file_exists($path)) {
            return $this->failNotFound('Archivo no encontrado');
        }

        $mime = mime_content_type($path);
        
        $this->response->setContentType($mime);
        $this->response->setBody(file_get_contents($path));
        return $this->response;
    }

    /**
     * GET /api/v1/public/image/(:any)
     * Sirve imágenes públicas sin necesidad de token Bearer (necesario para NetworkImage en Flutter)
     */
    public function servePublicImage()
    {
        $args = func_get_args();
        $path = implode('/', $args);
        
        // Evitar directory traversal
        if (strpos($path, '..') !== false) return $this->failNotFound();

        $fullPath = WRITEPATH . 'uploads/' . $path;
        if (!is_file($fullPath)) {
            return $this->failNotFound('Archivo no encontrado');
        }

        $fileMTime = filemtime($fullPath);
        $lastModified = gmdate('D, d M Y H:i:s', $fileMTime) . ' GMT';

        $this->response->removeHeader('Cache-Control');
        $this->response->setHeader('Cache-Control', 'public, max-age=31536000, immutable');
        $this->response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        $this->response->setHeader('Last-Modified', $lastModified);

        if ($this->request->getHeaderLine('If-Modified-Since') === $lastModified) {
            return $this->response->setStatusCode(304);
        }

        $mime = mime_content_type($fullPath);
        $this->response->setContentType($mime);
        $this->response->setBody(file_get_contents($fullPath));
        return $this->response;
    }

    // =========================================================
    // QR CODE MANAGEMENT FOR RESIDENTS
    // =========================================================

    /**
     * GET /api/v1/resident/qr-codes
     * Lista los QR generados por este residente
     */
    public function getQrs()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        // Resolver la unidad ACTUAL del residente para aislar datos por unidad
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        $currentUnitId = $resident['unit_id'] ?? null;

        $qrModel = new \App\Models\Tenant\QrCodeModel();
        $builder = $qrModel->select('qr_codes.*, units.unit_number')
                           ->join('units', 'units.id = qr_codes.unit_id', 'left')
                           ->where('qr_codes.created_by', $userId);

        // Filtrar por unidad actual — solo muestra QRs de la unidad vigente
        if ($currentUnitId) {
            $builder->where('qr_codes.unit_id', $currentUnitId);
        }

        $qrs = $builder->orderBy('qr_codes.created_at', 'DESC')
                       ->findAll();

        // Post-process: añadir aliases que QrViewScreen de Flutter espera
        foreach ($qrs as &$qr) {
            $qr['qr_token'] = $qr['token'] ?? '';
            $qr['unit']     = $qr['unit_number'] ?? 'N/A';

            // Derivar qr_type legible desde usage_limit
            $limit = (int)($qr['usage_limit'] ?? 1);
            if ($limit === 1) {
                $qr['qr_type'] = 'Una sola entrada';
            } elseif (!empty($qr['valid_until']) && !empty($qr['valid_from'])) {
                $from = strtotime($qr['valid_from']);
                $until = strtotime($qr['valid_until']);
                // Si valid_from y valid_until son el mismo día → Pase de Fiesta
                if (date('Y-m-d', $from) === date('Y-m-d', $until)) {
                    $qr['qr_type'] = 'Pase de Fiesta';
                } else {
                    $qr['qr_type'] = 'QR temporal';
                }
            } else {
                $qr['qr_type'] = 'QR temporal';
            }

            // Formatear fecha legible
            if (!empty($qr['valid_from'])) {
                $qr['date'] = date('d/m/Y', strtotime($qr['valid_from']));
            } else {
                $qr['date'] = date('d/m/Y');
            }
            
            // Si es QR temporal, mandar la fecha fin valid_until
            if ($qr['qr_type'] === 'QR temporal' && !empty($qr['valid_until'])) {
                $qr['end_date'] = date('d/m/Y', strtotime($qr['valid_until']));
            } else {
                $qr['end_date'] = null;
            }
        }
        unset($qr);

        return $this->respondSuccess($qrs);
    }

    /**
     * POST /api/v1/resident/qr-codes
     * Genera un nuevo QR para invitar a un visitante
     */
    public function createQr()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $json = $this->request->getJSON(true);
        $visitorName  = trim($json['visitor_name'] ?? '');
        $qrType       = $json['qr_type'] ?? 'Una sola entrada';
        $visitType     = $json['visit_type'] ?? 'Visita';
        $vehicle       = $json['vehicle'] ?? 'Sin vehículo';
        $dateStr       = $json['date'] ?? date('Y-m-d');
        $endDateStr    = $json['end_date'] ?? null;

        if (empty($visitorName)) {
            return $this->respondError('El nombre del visitante es obligatorio.');
        }

        // Resolver la unidad: admins pueden elegir, residentes usan la propia
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        $unitId = $resident['unit_id'] ?? null;

        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        // Si el request trae unit_id explícito, verificar si es admin
        $requestUnitId = $json['unit_id'] ?? null;
        if ($requestUnitId && (!$unitId || (int)$requestUnitId !== (int)$unitId)) {
            $isAdmin = $db->table('user_condominium_roles')
                          ->where('user_id', $userId)
                          ->where('condominium_id', $tenantId)
                          ->where('role_id', 2)
                          ->countAllResults() > 0;

            if ($isAdmin) {
                $unitId = (int)$requestUnitId;
            }
        }

        // Admin "uso propio": sin unit_id → usar primera unidad del condominio
        if (!$unitId) {
            $isAdmin = $db->table('user_condominium_roles')
                          ->where('user_id', $userId)
                          ->where('condominium_id', $tenantId)
                          ->where('role_id', 2)
                          ->countAllResults() > 0;

            if ($isAdmin) {
                $firstUnit = $db->table('units')
                                ->select('id')
                                ->where('condominium_id', $tenantId)
                                ->orderBy('unit_number', 'ASC')
                                ->limit(1)
                                ->get()->getRowArray();
                $unitId = $firstUnit['id'] ?? null;
            }
        }

        if (!$unitId) {
            return $this->respondError('No tienes una unidad asignada. Contacta al administrador.');
        }

        // Mapear tipo de QR a usage_limit y fechas
        $validFrom = $dateStr . ' 00:00:00';
        $usageLimit = 1;

        if ($qrType === 'QR temporal') {
            // QR temporal: usar end_date del Flutter, fallback +7 días
            $endDate = $endDateStr ?: date('Y-m-d', strtotime($dateStr . ' +7 days'));
            $validUntil = $endDate . ' 23:59:59';
            $usageLimit = 999;
        } elseif ($qrType === 'Pase de Fiesta') {
            // Pase de fiesta: válido por 1 día pero usos ilimitados
            $validUntil = $dateStr . ' 23:59:59';
            $usageLimit = 999;
        } else {
            // Una sola entrada
            $validUntil = $dateStr . ' 23:59:59';
            $usageLimit = 1;
        }

        // Mapear vehículo a vehicle_type del schema
        $vehicleType = 'pedestrian';
        if ($vehicle === 'Auto') {
            $vehicleType = 'car';
        } elseif ($vehicle === 'Motocicleta') {
            $vehicleType = 'motorcycle';
        }

        // Generar token seguro único
        $token = bin2hex(random_bytes(16));

        $qrModel = new \App\Models\Tenant\QrCodeModel();
        $qrModel->insert([
            'unit_id'       => $unitId,
            'created_by'    => $userId,
            'visitor_name'  => $visitorName,
            'token'         => $token,
            'visit_type'    => $visitType,
            'vehicle_type'  => $vehicleType,
            'valid_from'    => $validFrom,
            'valid_until'   => $validUntil,
            'usage_limit'   => $usageLimit,
            'times_used'    => 0,
            'status'        => 'active',
        ]);

        $qrId = (int) $qrModel->getInsertID();
        $publicUrl = base_url("qr/" . $token);

        // Obtener unit_number para la vista Flutter
        $db = \Config\Database::connect();
        $unitRow = $db->table('units')->select('unit_number')->where('id', (int)$unitId)->get()->getRowArray();
        $unitNumber = (!empty($unitRow['unit_number'])) ? $unitRow['unit_number'] : 'Unidad ' . $unitId;

        return $this->respondSuccess([
            'id'            => $qrId,
            'token'         => $token,
            'qr_token'      => $token,
            'visitor_name'  => $visitorName,
            'visit_type'    => $visitType,
            'vehicle_type'  => $vehicleType,
            'qr_type'       => $qrType,
            'valid_from'    => $validFrom,
            'valid_until'   => $validUntil,
            'usage_limit'   => $usageLimit,
            'status'        => 'active',
            'url'           => $publicUrl,
            'unit_number'   => $unitNumber,
            'unit'          => $unitNumber,
            'date'          => date('d/m/Y', strtotime($dateStr)),
            'end_date'      => ($qrType === 'QR temporal' && !empty($endDate)) ? date('d/m/Y', strtotime($endDate)) : null,
        ]);
    }

    /**
     * DELETE /api/v1/resident/qr-codes/(:num)
     * Revoca (elimina lógicamente) un QR propio o cualquier QR del condominio (admin)
     */
    public function deleteQr($qrId = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        if (!$qrId) return $this->respondError('ID de QR requerido.');

        $qrModel = new \App\Models\Tenant\QrCodeModel();
        $qr = $qrModel->find($qrId);

        if (!$qr) {
            return $this->respondError('QR no encontrado.', 404);
        }

        // Verificar permisos: el creador siempre puede eliminar
        $isCreator = ((int)$qr['created_by'] === (int)$userId);

        // Si no es el creador, verificar si es administrador del condominio
        if (!$isCreator) {
            $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
            $db = \Config\Database::connect();
            $isAdmin = $db->table('user_condominium_roles')
                          ->where('user_id', $userId)
                          ->where('condominium_id', $tenantId)
                          ->where('role_id', 2)
                          ->countAllResults() > 0;

            if (!$isAdmin) {
                return $this->respondError('No tienes permiso para eliminar este QR.', 403);
            }
        }

        // Usar una nueva instancia para evitar scope residual del find()
        $qrModel2 = new \App\Models\Tenant\QrCodeModel();
        $qrModel2->update($qrId, ['status' => 'revoked']);

        return $this->respondSuccess([
            'message' => 'QR revocado correctamente.',
            'qr_id'  => (int) $qrId,
        ]);
    }

    /**
     * GET /api/v1/resident/access-logs
     * Lista el registro de accesos de visitas de la unidad del residente
     */
    public function getAccessLogs()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $db = \Config\Database::connect();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        $isAdmin = $db->table('user_condominium_roles')
                      ->where('user_id', $userId)
                      ->where('condominium_id', $tenantId)
                      ->where('role_id', 2)
                      ->countAllResults() > 0;

        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        $unitId = $resident['unit_id'] ?? null;

        if (!$isAdmin && !$unitId) {
            return $this->respondSuccess([]);
        }
        
        $whereClause = $isAdmin ? "e.condominium_id = ?" : "e.unit_id = ?";
        $param = $isAdmin ? $tenantId : $unitId;
        
        // Obtener todos los registros de tipo 'entry' y cruzarlos con su 'exit' respectiva
        $entries = $db->query("
            SELECT e.id, e.visitor_name, e.visitor_type,
                   COALESCE(qr.visit_type, e.visit_type, 'Visita') AS visit_type,
                   IFNULL(qr.vehicle_plate, e.plate_number) AS vehicle_plate,
                   e.created_at AS entry_at,
                   x.created_at AS exit_at,
                   e.photo_url AS photo_id,
                   e.photo_plate_url AS photo_vehicle,
                   x.photo_exit_url AS photo_exit,
                   e.notes AS comment,
                   u.unit_number
            FROM access_logs e
            LEFT JOIN qr_codes qr ON qr.id = e.qr_code_id
            LEFT JOIN access_logs x ON x.entry_log_id = e.id AND x.type = 'exit'
            LEFT JOIN units u ON u.id = e.unit_id
            WHERE $whereClause AND e.type = 'entry'
            ORDER BY e.created_at DESC
            LIMIT 50
        ", [$param])->getResultArray();
        
        return $this->respondSuccess($entries);
    }
}
