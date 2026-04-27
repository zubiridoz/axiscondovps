<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\I18n\Time;

/**
 * OnboardingController
 * 
 * Gestiona el wizard multi-step para crear un nuevo condominio desde el panel web.
 * Reutiliza patrones de RegisterController (creación atómica) y UnitController (CSV import).
 * 
 * NO modifica ni interfiere con: TenantService, FinanceController, SettingsController.
 */
class OnboardingController extends BaseController
{
    /**
     * Renderiza la vista del wizard de onboarding.
     */
    public function index()
    {
        // Solo el fundador (is_owner) puede crear nuevas sociedades
        if (!session()->get('is_owner')) {
            return redirect()->to(base_url('admin/dashboard'))
                ->with('error', 'Solo el administrador fundador puede crear nuevas sociedades.');
        }
        return view('admin/onboarding');
    }

    /**
     * Procesa la creación completa del condominio (transacción atómica).
     * Recibe JSON con todos los datos del wizard.
     */
    public function create()
    {
        $db  = \Config\Database::connect();
        $now = Time::now()->toDateTimeString();

        // Datos del wizard (JSON payload)
        $input = $this->request->getJSON(true);

        // Validación mínima
        $condoName = trim($input['name'] ?? '');
        if ($condoName === '') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El nombre del condominio es obligatorio.'
            ])->setStatusCode(422);
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Sesión no válida.'
            ])->setStatusCode(401);
        }

        // Solo el fundador (is_owner) puede crear nuevas sociedades
        if (!session()->get('is_owner')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo el administrador fundador puede crear nuevas sociedades.'
            ])->setStatusCode(403);
        }

        $db->transStart();

        try {
            // ──────────────────────────────────────
            // A. Plan & Suscripción
            // ──────────────────────────────────────
            $plan = $db->table('plans')
                ->where('status', 'active')
                ->orderBy('id', 'ASC')
                ->get()->getRow();

            if (!$plan) {
                $db->table('plans')->insert([
                    'name'               => 'Plan Básico',
                    'max_condominiums'   => 5,
                    'price'              => 0.00,
                    'status'             => 'active',
                    'created_at'         => $now,
                    'updated_at'         => $now,
                ]);
                $planId = $db->insertID();
            } else {
                $planId = $plan->id;
            }

            $db->table('subscriptions')->insert([
                'user_id'    => $userId,
                'plan_id'    => $planId,
                'starts_at'  => date('Y-m-d'),
                'expires_at' => date('Y-m-d', strtotime('+15 days')), // Período de prueba de 15 días
                'status'     => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $subscriptionId = $db->insertID();
            $expiresAt = date('Y-m-d H:i:s', strtotime('+15 days'));

            // ──────────────────────────────────────
            // B. Crear Condominio
            // ──────────────────────────────────────
            // Construir dirección desde partes
            $addressParts = array_filter([
                trim($input['street'] ?? ''),
                trim($input['city'] ?? ''),
                trim($input['state'] ?? ''),
                trim($input['postal_code'] ?? ''),
                trim($input['country'] ?? ''),
            ], fn($v) => $v !== '');
            $addressString = implode(', ', $addressParts);

            $condoData = [
                'subscription_id'     => $subscriptionId,
                'name'                => $condoName,
                'address'             => $addressString,
                'currency'            => trim($input['currency'] ?? 'MXN'),
                'status'              => 'active',
                'plan_id'             => $planId,
                'plan_expires_at'     => $expiresAt,
                'billing_cycle'       => 'monthly',
                'payment_method'      => 'stripe', // Por defecto Stripe para que el usuario elija su plan
                'is_billing_active'   => 0, // No activar billing al crear
                'billing_due_day'     => (int)($input['billing_due_day'] ?? 10),
                'billing_start_date'  => $input['billing_start_month'] ?? null,
                'bank_name'           => trim($input['bank_name'] ?? ''),
                'bank_clabe'          => trim($input['bank_clabe'] ?? ''),
                'bank_rfc'            => trim($input['bank_rfc'] ?? ''),
                'bank_card'           => trim($input['bank_card'] ?? ''),
                'created_at'          => $now,
                'updated_at'          => $now,
            ];

            $db->table('condominiums')->insert($condoData);
            $condoId = $db->insertID();

            // ──────────────────────────────────────
            // C. Asignar Rol ADMIN al creador
            // ──────────────────────────────────────
            $adminRole = $db->table('roles')
                ->where('name', 'ADMIN')
                ->get()->getRow();

            if (!$adminRole) {
                throw new \Exception('Error crítico: El rol ADMIN no existe en el sistema.');
            }

            $db->table('user_condominium_roles')->insert([
                'user_id'        => $userId,
                'condominium_id' => $condoId,
                'role_id'        => $adminRole->id,
                'is_owner'       => 1, // Fundador de la comunidad
                'created_at'     => $now,
            ]);

            // ──────────────────────────────────────
            // D. Crear Unidades (bulk)
            // ──────────────────────────────────────
            $units       = $input['units'] ?? [];
            $sameFee     = !empty($input['same_amount']);
            $defaultFee  = (float)($input['monthly_fee'] ?? 0);
            $unitIdMap   = []; // unit_number => unit_id

            foreach ($units as $u) {
                $unitNumber = strtoupper(trim((string)($u['name'] ?? $u['unit_number'] ?? '')));
                if ($unitNumber === '') continue;

                $fee = $sameFee ? $defaultFee : (float)($u['fee'] ?? $u['maintenance_fee'] ?? $defaultFee);

                $db->table('units')->insert([
                    'condominium_id'  => $condoId,
                    'unit_number'     => $unitNumber,
                    'maintenance_fee' => $fee,
                    'type'            => 'apartment',
                    'hash_id'         => bin2hex(random_bytes(12)),
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ]);
                $unitIdMap[$unitNumber] = $db->insertID();
            }

            // ──────────────────────────────────────
            // E. Crear Residentes (bulk)
            // ──────────────────────────────────────
            $residents = $input['residents'] ?? [];

            foreach ($residents as $r) {
                $email     = strtolower(trim($r['email'] ?? ''));
                $name      = trim($r['name'] ?? '');
                $unitLabel = strtoupper(trim($r['unit'] ?? ''));

                if ($name === '' || $email === '') continue;

                // Buscar o crear usuario
                $existingUser = $db->table('users')
                    ->where('email', $email)
                    ->get()->getRow();

                if ($existingUser) {
                    $residentUserId = $existingUser->id;
                } else {
                    $parts     = explode(' ', $name, 2);
                    $firstName = $parts[0];
                    $lastName  = $parts[1] ?? '';

                    $db->table('users')->insert([
                        'first_name'    => $firstName,
                        'last_name'     => $lastName,
                        'email'         => $email,
                        'password_hash' => password_hash('changeme123', PASSWORD_DEFAULT),
                        'status'        => 'active',
                        'created_at'    => $now,
                        'updated_at'    => $now,
                    ]);
                    $residentUserId = $db->insertID();
                }

                // Asignar rol RESIDENT en este condominio
                $residentRole = $db->table('roles')
                    ->where('name', 'RESIDENT')
                    ->get()->getRow();

                if ($residentRole) {
                    $existingPivot = $db->table('user_condominium_roles')
                        ->where('user_id', $residentUserId)
                        ->where('condominium_id', $condoId)
                        ->countAllResults();

                    if ($existingPivot === 0) {
                        $db->table('user_condominium_roles')->insert([
                            'user_id'        => $residentUserId,
                            'condominium_id' => $condoId,
                            'role_id'        => $residentRole->id,
                            'created_at'     => $now,
                        ]);
                    }
                }

                // Crear registro resident vinculado a la unidad
                $unitId = $unitIdMap[$unitLabel] ?? null;

                $db->table('residents')->insert([
                    'condominium_id' => $condoId,
                    'user_id'        => $residentUserId,
                    'unit_id'        => $unitId,
                    'type'           => 'owner',
                    'is_active'      => 1,
                    'created_at'     => $now,
                    'updated_at'     => $now,
                ]);
            }

            // ──────────────────────────────────────
            // F. Crear categorías financieras base
            // ──────────────────────────────────────
            $baseCategories = [
                ['name' => 'Cuota de Mantenimiento', 'type' => 'income', 'is_system' => 1],
                ['name' => 'Cuota Extraordinaria',   'type' => 'income', 'is_system' => 1],
                ['name' => 'Mora / Recargo',         'type' => 'income', 'is_system' => 1],
                ['name' => 'Gastos Generales',       'type' => 'expense', 'is_system' => 1],
            ];

            foreach ($baseCategories as $cat) {
                $cat['condominium_id'] = $condoId;
                $cat['created_at']     = $now;
                $cat['updated_at']     = $now;
                $db->table('financial_categories')->insert($cat);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de base de datos.');
            }

            // Actualizar la sesión para incluir el nuevo condominio
            $session = session();
            $allowed = $session->get('allowed_tenants') ?? [];
            $allowed[] = $condoId;
            $session->set('allowed_tenants', $allowed);

            // Cambiar el contexto al nuevo condominio automáticamente
            $session->set([
                'current_condominium_id' => $condoId,
                'current_role_id'        => $adminRole->id,
            ]);

            return $this->response->setJSON([
                'success'        => true,
                'condominium_id' => $condoId,
                'message'        => 'Condominio creado exitosamente.',
                'redirect'       => base_url('admin/dashboard'),
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage(),
            ])->setStatusCode(500);
        }
    }

    /**
     * Genera un CSV template con N unidades para descarga.
     */
    public function downloadUnitsTemplate()
    {
        $numberOfUnits = (int)($this->request->getPost('number_of_units') ?? 10);
        if ($numberOfUnits <= 0) $numberOfUnits = 10;
        if ($numberOfUnits > 5000) $numberOfUnits = 5000;

        $filename = 'Plantilla_Unidades_' . date('Y-m-d') . '.csv';

        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: text/csv; charset=UTF-8");

        $file = fopen('php://output', 'w');
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM

        fputcsv($file, ['nombre_de_unidad', 'cuota_mensual']);

        for ($i = 1; $i <= $numberOfUnits; $i++) {
            fputcsv($file, [$i, '']);
        }

        fclose($file);
        exit;
    }

    /**
     * Preview de un CSV de unidades subido por el usuario.
     */
    public function previewUnitsCSV()
    {
        $file = $this->request->getFile('file_csv');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo no válido'
            ])->setStatusCode(400);
        }

        $preview = [];
        if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
            $header = fgetcsv($handle, 1000, ","); // Skip header
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (!empty(trim($data[0] ?? ''))) {
                    $preview[] = [
                        'name'    => trim($data[0]),
                        'fee'     => trim($data[1] ?? '0'),
                        'section' => trim($data[3] ?? ''),
                    ];
                }
            }
            fclose($handle);
        }

        return $this->response->setJSON([
            'success' => true,
            'total'   => count($preview),
            'preview' => $preview,
        ]);
    }

    /**
     * Preview de un CSV de residentes subido por el usuario.
     */
    public function previewResidentsCSV()
    {
        $file = $this->request->getFile('file_csv');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Archivo no válido'
            ])->setStatusCode(400);
        }

        $preview = [];
        if (($handle = fopen($file->getTempName(), "r")) !== FALSE) {
            $headerFound = false;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // Skip header
                if (!$headerFound && isset($data[0]) && strtolower(trim($data[0])) === 'nombre') {
                    $headerFound = true;
                    continue;
                }
                if (count($data) >= 2 && !empty(trim($data[0] ?? ''))) {
                    $preview[] = [
                        'name'  => trim($data[0]),
                        'email' => strtolower(trim($data[1] ?? '')),
                        'unit'  => trim($data[2] ?? ''),
                    ];
                }
            }
            fclose($handle);
        }

        return $this->response->setJSON([
            'success' => true,
            'total'   => count($preview),
            'preview' => $preview,
        ]);
    }
}
