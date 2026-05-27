<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\CondominiumModel;
use App\Services\AssetService;

class SettingsController extends BaseController
{
    public function indexView()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first() ?? [];

        $name = trim((string) ($condo['name'] ?? 'Comunidad'));
        if ($name === '') {
            $name = 'Comunidad';
        }

        $address = $this->normalizeAddress((string) ($condo['address'] ?? ''));
        $timezoneLabel = $this->formatTimezone((string) ($condo['timezone'] ?? 'America/Mexico_City'));

        $userModel = new \App\Models\Core\UserModel();
        $me = $userModel->find(session()->get('user_id')) ?? [];
        $meInitial = strtoupper(substr((string) ($me['first_name'] ?? 'U'), 0, 1));

        $sectionModel = new \App\Models\Tenant\SectionModel();
        $unitModel = new \App\Models\Tenant\UnitModel();
        
        $sections = $sectionModel->orderBy('name', 'ASC')->findAll();
        $units = $unitModel->orderBy('id', 'ASC')->findAll();

        $db = \Config\Database::connect();
        $categoriesRaw = $db->table('financial_categories')
            ->where('condominium_id', $condo['id'] ?? 0)
            ->orderBy('is_system', 'DESC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
            
        $financialCategories = [
            'income' => [],
            'expense' => []
        ];
        
        foreach ($categoriesRaw as $cat) {
            if ($cat['type'] === 'income') {
                $financialCategories['income'][] = $cat;
            } elseif ($cat['type'] === 'expense') {
                $financialCategories['expense'][] = $cat;
            }
        }

        return view('admin/settings', [
            'me' => [
                'first_name' => $me['first_name'] ?? '',
                'last_name'  => $me['last_name']  ?? '',
                'email'      => $me['email']      ?? '',
                'phone'      => $me['phone']      ?? '',
                'avatar'     => $me['avatar']     ?? null,
                'initial'    => $meInitial,
            ],
            'community' => [
                'id'              => $condo['id'] ?? 0,
                'name'            => $name,
                'initial'         => strtoupper(substr($name, 0, 1)),
                'timezone'        => $condo['timezone'] ?? 'America/Mexico_City',
                'timezone_label'  => $timezoneLabel,
                'street'          => $address['street'],
                'city'            => $address['city'],
                'state'           => $address['state'],
                'postal_code'     => $address['postal_code'],
                'country'         => $address['country'],
                'logo'            => $condo['logo'] ?? null,
                'cover_image'     => $condo['cover_image'] ?? null,
                'owner_financial_access'   => $condo['owner_financial_access'] ?? 'unit_community',
                'tenant_financial_access'  => $condo['tenant_financial_access'] ?? 'none',
                'show_delinquent_units'    => ($condo['show_delinquent_units'] ?? 0) ? true : false,
                'show_delinquency_amounts' => ($condo['show_delinquency_amounts'] ?? 0) ? true : false,
                'allow_resident_posts'     => ($condo['allow_resident_posts'] ?? 0) ? true : false,
                'allow_resident_events'    => ($condo['allow_resident_events'] ?? 0) ? true : false,
                'allow_post_comments'      => ($condo['allow_post_comments'] ?? 0) ? true : false,
                'resident_view_comments'   => ($condo['resident_view_comments'] ?? 0) ? true : false,
                'always_email_posts'       => ($condo['always_email_posts'] ?? 0) ? true : false,
                'payment_approval_mode'    => $condo['payment_approval_mode'] ?? 'manual',
                'restrict_qr_delinquent'        => !empty($condo['restrict_qr_delinquent'] ?? 0),
                'restrict_amenities_delinquent' => !empty($condo['restrict_amenities_delinquent'] ?? 0),
                'bank_name'             => $condo['bank_name'] ?? '',
                'bank_clabe'            => $condo['bank_clabe'] ?? '',
                'bank_rfc'              => $condo['bank_rfc'] ?? '',
                'bank_card'             => $condo['bank_card'] ?? '',
                'currency'              => $condo['currency'] ?? 'MXN',
                'billing_due_day'       => (int)($condo['billing_due_day'] ?? 15),
                'billing_start_date'    => $condo['billing_start_date'] ?? null,
                'is_billing_active'     => !empty($condo['is_billing_active']),
                'late_fee_enabled'       => !empty($condo['late_fee_enabled']),
                'late_fee_type'          => $condo['late_fee_type'] ?? 'fixed',
                'late_fee_amount'        => (float)($condo['late_fee_amount'] ?? 0),
                'late_fee_percentage'    => (float)($condo['late_fee_percentage'] ?? 0),
                'late_fee_max_amount'    => $condo['late_fee_max_amount'] !== null ? (float)$condo['late_fee_max_amount'] : null,
                'late_fee_grace_enabled' => !empty($condo['late_fee_grace_enabled']),
                'late_fee_grace_days'    => (int)($condo['late_fee_grace_days'] ?? 0),
                'late_fee_categories'    => json_decode($condo['late_fee_categories'] ?? '[]', true) ?: [],
            ],
            'sections' => $sections,
            'units'    => $units,
            'financial_categories' => $financialCategories,
            'payment_reminders' => \App\Services\PaymentReminderService::getRemindersForCondominium($condo['id'] ?? 0)
        ]);
    }

    /**
     * Update community name & timezone via AJAX.
     */
    public function updateInfo()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();

        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $name     = trim((string) $this->request->getPost('name'));
        $timezone = trim((string) $this->request->getPost('timezone'));

        if ($name === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre es obligatorio.'])->setStatusCode(422);
        }

        if ($timezone === '') {
            $timezone = 'America/Mexico_City';
        }

        $condoModel->update($condo['id'], [
            'name'     => $name,
            'timezone' => $timezone,
        ]);

        $initial = strtoupper(substr($name, 0, 1));
        $timezoneLabel = $this->formatTimezone($timezone);

        // Parse the address to get the city for the sidebar
        $address = $this->normalizeAddress((string) ($condo['address'] ?? ''));

        return $this->response->setJSON([
            'success'        => true,
            'name'           => $name,
            'initial'        => $initial,
            'timezone_label' => $timezoneLabel,
            'city'           => $address['city'],
        ]);
    }

    /**
     * Update financial access preferences.
     */
    public function saveFinancialAccess()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();

        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $ownerAccess = $this->request->getPost('owner_financial_access') ?: 'unit_community';
        $tenantAccess = $this->request->getPost('tenant_financial_access') ?: 'none';
        $showDelinquent = $this->request->getPost('show_delinquent_units') === '1' || $this->request->getPost('show_delinquent_units') === 'true' ? 1 : 0;
        $showAmounts = $this->request->getPost('show_delinquency_amounts') === '1' || $this->request->getPost('show_delinquency_amounts') === 'true' ? 1 : 0;
        
        $paymentApprovalMode = $this->request->getPost('payment_approval_mode') ?: 'manual';

        $condoModel->update($condo['id'], [
            'owner_financial_access'   => $ownerAccess,
            'tenant_financial_access'  => $tenantAccess,
            'show_delinquent_units'    => $showDelinquent,
            'show_delinquency_amounts' => $showAmounts,
            'payment_approval_mode'    => $paymentApprovalMode,
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Toggle active status of a financial category via AJAX.
     */
    public function toggleFinancialCategory()
    {
        $this->bootstrapTenant();
        
        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $id = (int) $this->request->getPost('id');
        $isActive = $this->request->getPost('is_active') === '1' || $this->request->getPost('is_active') === 'true' ? 1 : 0;

        $db = \Config\Database::connect();
        $category = $db->table('financial_categories')
            ->where('id', $id)
            ->where('condominium_id', $condo['id'])
            ->get()
            ->getRowArray();

        if (! $category) {
            return $this->response->setJSON(['success' => false, 'message' => 'Categoría no encontrada.'])->setStatusCode(404);
        }

        $db->table('financial_categories')->where('id', $id)->update(['is_active' => $isActive]);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Add a custom financial category via AJAX.
     */
    public function addFinancialCategory()
    {
        $this->bootstrapTenant();
        
        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $name = trim((string) $this->request->getPost('name'));
        $type = $this->request->getPost('type') === 'expense' ? 'expense' : 'income';
        $icon = trim((string) $this->request->getPost('icon')) ?: 'bi-tag';

        if ($name === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre de la categoría es obligatorio.'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();
        
        // Prevent duplicate names
        $exists = $db->table('financial_categories')
            ->where('condominium_id', $condo['id'])
            ->where('name', $name)
            ->where('type', $type)
            ->countAllResults();
            
        if ($exists > 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ya existe una categoría con este nombre y tipo.'])->setStatusCode(409);
        }

        $db->table('financial_categories')->insert([
            'condominium_id' => $condo['id'],
            'name'           => $name,
            'type'           => $type,
            'is_system'      => 0,
            'is_active'      => 1,
            'icon'           => $icon,
            'created_at'     => date('Y-m-d H:i:s'),
            'updated_at'     => date('Y-m-d H:i:s')
        ]);

        return $this->response->setJSON([
            'success' => true, 
            'message' => 'Categoría agregada correctamente.',
            'category' => [
                'id' => $db->insertID(),
                'name' => $name,
                'type' => $type,
                'icon' => $icon,
                'is_active' => 1,
                'is_system' => 0
            ]
        ]);
    }


    /**
     * Update wall/announcements access preferences.
     */
    public function saveWallAccess()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();

        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $allowPosts = $this->request->getPost('allow_resident_posts') === '1' || $this->request->getPost('allow_resident_posts') === 'true' ? 1 : 0;
        $allowEvents = $this->request->getPost('allow_resident_events') === '1' || $this->request->getPost('allow_resident_events') === 'true' ? 1 : 0;
        $allowComments = $this->request->getPost('allow_post_comments') === '1' || $this->request->getPost('allow_post_comments') === 'true' ? 1 : 0;
        $residentViewComments = $this->request->getPost('resident_view_comments') === '1' || $this->request->getPost('resident_view_comments') === 'true' ? 1 : 0;
        $alwaysEmail = $this->request->getPost('always_email_posts') === '1' || $this->request->getPost('always_email_posts') === 'true' ? 1 : 0;

        $condoModel->update($condo['id'], [
            'allow_resident_posts'   => $allowPosts,
            'allow_resident_events'  => $allowEvents,
            'allow_post_comments'    => $allowComments,
            'resident_view_comments' => $residentViewComments,
            'always_email_posts'     => $alwaysEmail,
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Update community address via AJAX.
     */
    public function updateAddress()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();

        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $country    = trim(str_replace(',', ' ', (string) $this->request->getPost('country')));
        $state      = trim(str_replace(',', ' ', (string) $this->request->getPost('state')));
        $city       = trim(str_replace(',', ' ', (string) $this->request->getPost('city')));
        $postalCode = trim(str_replace(',', ' ', (string) $this->request->getPost('postal_code')));
        $street     = trim(str_replace(',', ' ', (string) $this->request->getPost('street')));

        // Construir string separado por comas, asegurando 5 partes fijas para no desfasar al cargar
        $parts = [
            $street !== '' ? $street : 'Sin definir',
            $city !== '' ? $city : 'Sin definir',
            $state !== '' ? $state : 'Sin definir',
            $postalCode !== '' ? $postalCode : 'Sin definir',
            $country !== '' ? $country : 'Sin definir'
        ];
        $addressString = implode(', ', $parts);

        $condoModel->update($condo['id'], [
            'address' => $addressString,
        ]);

        $address = $this->normalizeAddress($addressString);

        return $this->response->setJSON([
            'success'     => true,
            'street'      => $address['street'],
            'city'        => $address['city'],
            'state'       => $address['state'],
            'postal_code' => $address['postal_code'],
            'country'     => $address['country'],
        ]);
    }

    /**
     * Upload logo image via AJAX.
     */
    public function uploadLogo()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();

        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $file = $this->request->getFile('logo');
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no válido.'])->setStatusCode(422);
        }

        try {
            // Eliminar logo anterior si existe
            if (! empty($condo['logo'])) {
                AssetService::delete('condominiums', (string) $condo['id'], $condo['logo']);
            }

            // Upload con AssetService
            $newName = AssetService::upload('condominiums', $file, (string) $condo['id']);
            $condoModel->update($condo['id'], ['logo' => $newName]);

            return $this->response->setJSON([
                'success' => true,
                'url'     => AssetService::getUrl('condominiums', (string) $condo['id'], $newName),
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Upload cover image via AJAX.
     */
    public function uploadCover()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();

        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $file = $this->request->getFile('cover');
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo no válido.'])->setStatusCode(422);
        }

        try {
            // Eliminar cover anterior si existe
            if (! empty($condo['cover_image'])) {
                AssetService::delete('condominiums', (string) $condo['id'], $condo['cover_image']);
            }

            // Upload con AssetService
            $newName = AssetService::upload('condominiums', $file, (string) $condo['id']);
            $condoModel->update($condo['id'], ['cover_image' => $newName]);

            return $this->response->setJSON([
                'success' => true,
                'url'     => AssetService::getUrl('condominiums', (string) $condo['id'], $newName),
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => $e->getMessage()])->setStatusCode(500);
        }
    }

    /**
     * Serve uploaded images from writable/uploads/condominiums.
     */
    public function serveImage(string ...$segments)
    {
        $path = implode(DIRECTORY_SEPARATOR, $segments);
        $fullPath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . $path;

        if (! is_file($fullPath)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $fileMTime = filemtime($fullPath);
        $lastModified = gmdate('D, d M Y H:i:s', $fileMTime) . ' GMT';

        $this->response->setHeader('Cache-Control', 'public, max-age=31536000, immutable');
        $this->response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        $this->response->setHeader('Last-Modified', $lastModified);

        if ($this->request->getHeaderLine('If-Modified-Since') === $lastModified) {
            return $this->response->setStatusCode(304);
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($fullPath))
            ->setBody(file_get_contents($fullPath));
    }

    // ══════════════════════════════════════════════════
    //  ADMINISTRADORES TAB
    // ══════════════════════════════════════════════════

    /**
     * List administrators for the current condominium via AJAX.
     */
    public function listAdmins()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $db = \Config\Database::connect();
        $admins = $db->table('user_condominium_roles AS ucr')
            ->select('ucr.id AS assignment_id, ucr.role_id, ucr.is_owner, u.id AS user_id, u.first_name, u.last_name, u.email, r.name AS role_name')
            ->join('users AS u', 'u.id = ucr.user_id')
            ->join('roles AS r', 'r.id = ucr.role_id')
            ->where('ucr.condominium_id', $condo['id'])
            ->where('ucr.role_id', 2) // role_id 2 = ADMIN
            ->where('u.deleted_at IS NULL')
            ->orderBy('ucr.created_at', 'ASC')
            ->get()
            ->getResultArray();

        return $this->response->setJSON(['success' => true, 'admins' => $admins]);
    }

    /**
     * Add administrator to the current condominium via AJAX.
     * If the user exists by email, link them. Otherwise, create a new user.
     */
    public function addAdmin()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $email     = trim((string) $this->request->getPost('email'));
        $firstName = trim((string) $this->request->getPost('first_name'));
        $lastName  = trim((string) $this->request->getPost('last_name'));
        $password  = (string) $this->request->getPost('password');

        if ($email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email inválido.'])->setStatusCode(422);
        }
        if ($firstName === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre es obligatorio.'])->setStatusCode(422);
        }
        if (strlen($password) < 8) {
            return $this->response->setJSON(['success' => false, 'message' => 'La contraseña debe tener al menos 8 caracteres.'])->setStatusCode(422);
        }

        $userModel = new \App\Models\Core\UserModel();
        $user = $userModel->where('email', $email)->first();

        $db = \Config\Database::connect();

        if ($user) {
            // Check if already an admin for this condominium
            $existing = $db->table('user_condominium_roles')
                ->where('user_id', $user['id'])
                ->where('condominium_id', $condo['id'])
                ->where('role_id', 2)
                ->countAllResults();

            if ($existing > 0) {
                return $this->response->setJSON(['success' => false, 'message' => 'Este usuario ya es administrador de esta comunidad.'])->setStatusCode(409);
            }

            $userId = $user['id'];
        } else {
            // Create a new user with the provided password
            $userId = $userModel->insert([
                'first_name'    => $firstName,
                'last_name'     => $lastName,
                'email'         => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'status'        => 'active',
            ]);

            if (! $userId) {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al crear usuario.'])->setStatusCode(500);
            }
        }

        // Link to condominium as ADMIN (role_id = 2), Co-Admin (is_owner = 0)
        $db->table('user_condominium_roles')->insert([
            'user_id'        => $userId,
            'condominium_id' => $condo['id'],
            'role_id'        => 2,
            'is_owner'       => 0, // Co-Admin, NO es fundador
            'created_at'     => date('Y-m-d H:i:s'),
        ]);

        // Return the full admin record for UI
        $admin = $db->table('user_condominium_roles AS ucr')
            ->select('ucr.id AS assignment_id, ucr.role_id, u.id AS user_id, u.first_name, u.last_name, u.email, r.name AS role_name')
            ->join('users AS u', 'u.id = ucr.user_id')
            ->join('roles AS r', 'r.id = ucr.role_id')
            ->where('ucr.user_id', $userId)
            ->where('ucr.condominium_id', $condo['id'])
            ->where('ucr.role_id', 2)
            ->get()
            ->getRowArray();

        return $this->response->setJSON(['success' => true, 'admin' => $admin]);
    }

    /**
     * Remove administrator from the current condominium via AJAX.
     */
    public function removeAdmin()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (! $condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $assignmentId = (int) $this->request->getPost('assignment_id');
        if ($assignmentId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID inválido.'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();

        // Verify the assignment belongs to this condominium and is ADMIN
        $record = $db->table('user_condominium_roles')
            ->where('id', $assignmentId)
            ->where('condominium_id', $condo['id'])
            ->where('role_id', 2)
            ->get()
            ->getRowArray();

        if (! $record) {
            return $this->response->setJSON(['success' => false, 'message' => 'Administrador no encontrado.'])->setStatusCode(404);
        }

        // PROTECCIÓN: No permitir eliminar al Administrador Fundador (is_owner = 1)
        if (!empty($record['is_owner'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No puedes eliminar al Administrador Fundador de la comunidad.'
            ])->setStatusCode(403);
        }

        // Check: don't allow removing the last admin
        $adminCount = $db->table('user_condominium_roles')
            ->where('condominium_id', $condo['id'])
            ->where('role_id', 2)
            ->countAllResults();

        if ($adminCount <= 1) {
            return $this->response->setJSON(['success' => false, 'message' => 'No puedes eliminar al último administrador.'])->setStatusCode(422);
        }

        $db->table('user_condominium_roles')->where('id', $assignmentId)->delete();

        return $this->response->setJSON(['success' => true]);
    }

    /**
     * Promover un Co-Admin a Fundador (AJAX).
     * Solo ejecutable por un Fundador existente (is_owner = 1).
     * Permite múltiples fundadores simultáneos en un condominio.
     *
     * POST: assignment_id (int) — ID del registro en user_condominium_roles
     */
    public function promoteToFounder()
    {
        $this->bootstrapTenant();

        // 1. Solo un fundador puede promover
        if (!session()->get('is_owner')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo un Fundador puede realizar esta acción.'
            ])->setStatusCode(403);
        }

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $assignmentId = (int) $this->request->getPost('assignment_id');
        if ($assignmentId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID inválido.'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();

        // 2. Verificar que el target existe, pertenece a este condominio, es ADMIN y NO es ya fundador
        $target = $db->table('user_condominium_roles')
            ->where('id', $assignmentId)
            ->where('condominium_id', $condo['id'])
            ->where('role_id', 2)
            ->get()
            ->getRowArray();

        if (!$target) {
            return $this->response->setJSON(['success' => false, 'message' => 'Administrador no encontrado.'])->setStatusCode(404);
        }

        if (!empty($target['is_owner'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Este administrador ya es Fundador.'])->setStatusCode(409);
        }

        // 3. Promover: is_owner = 1 en el condominio actual
        $db->table('user_condominium_roles')
            ->where('id', $assignmentId)
            ->update(['is_owner' => 1]);

        // 4. Dar acceso a TODOS los condominios donde el fundador promotor es también fundador
        $currentUserId = session()->get('user_id');
        $promotedUserId = (int) $target['user_id'];

        $founderCondos = $db->table('user_condominium_roles')
            ->select('condominium_id')
            ->where('user_id', $currentUserId)
            ->where('role_id', 2)
            ->where('is_owner', 1)
            ->where('condominium_id !=', $condo['id']) // Excluir el actual (ya tiene acceso)
            ->get()
            ->getResultArray();

        $now = date('Y-m-d H:i:s');
        foreach ($founderCondos as $fc) {
            $otherCondoId = (int) $fc['condominium_id'];

            // Verificar si ya tiene acceso a ese condominio
            $existingEntry = $db->table('user_condominium_roles')
                ->where('user_id', $promotedUserId)
                ->where('condominium_id', $otherCondoId)
                ->get()
                ->getRowArray();

            if ($existingEntry) {
                // Ya existe: solo actualizar a is_owner = 1 si no lo es
                if (empty($existingEntry['is_owner'])) {
                    $db->table('user_condominium_roles')
                        ->where('id', $existingEntry['id'])
                        ->update(['is_owner' => 1, 'role_id' => 2]);
                }
            } else {
                // No existe: crear nuevo registro como fundador
                $db->table('user_condominium_roles')->insert([
                    'user_id'        => $promotedUserId,
                    'condominium_id' => $otherCondoId,
                    'role_id'        => 2, // ADMIN
                    'is_owner'       => 1,
                    'created_at'     => $now,
                ]);
            }
        }

        $addedCount = count($founderCondos);
        log_message('info', "[ADMIN] Fundador promovido: assignment_id={$assignmentId}, condo={$condo['id']}, +{$addedCount} condominios adicionales, por user_id={$currentUserId}");

        $msg = 'Administrador promovido a Fundador exitosamente.';
        if ($addedCount > 0) {
            $msg .= " Se le otorgó acceso a {$addedCount} comunidad(es) adicional(es).";
        }
        return $this->response->setJSON(['success' => true, 'message' => $msg]);
    }

    /**
     * Revocar rol de Fundador a otro admin (degradar a Co-Admin) (AJAX).
     * Solo ejecutable por un Fundador existente (is_owner = 1).
     * Protecciones:
     * - No puede degradarse a sí mismo (nunca)
     * - Siempre debe quedar mínimo 1 fundador en la comunidad
     *
     * POST: assignment_id (int) — ID del registro en user_condominium_roles
     */
    public function demoteFounder()
    {
        $this->bootstrapTenant();

        // 1. Solo un fundador puede revocar
        if (!session()->get('is_owner')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo un Fundador puede realizar esta acción.'
            ])->setStatusCode(403);
        }

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Comunidad no encontrada.'])->setStatusCode(404);
        }

        $assignmentId = (int) $this->request->getPost('assignment_id');
        if ($assignmentId <= 0) {
            return $this->response->setJSON(['success' => false, 'message' => 'ID inválido.'])->setStatusCode(422);
        }

        $db = \Config\Database::connect();

        // 2. Verificar que el target existe, pertenece a este condominio, es ADMIN y ES fundador
        $target = $db->table('user_condominium_roles')
            ->where('id', $assignmentId)
            ->where('condominium_id', $condo['id'])
            ->where('role_id', 2)
            ->get()
            ->getRowArray();

        if (!$target) {
            return $this->response->setJSON(['success' => false, 'message' => 'Administrador no encontrado.'])->setStatusCode(404);
        }

        if (empty($target['is_owner'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Este administrador no es Fundador.'])->setStatusCode(409);
        }

        // 3. No permitir auto-degradación (nunca)
        $currentUserId = session()->get('user_id');
        if ((int) $target['user_id'] === (int) $currentUserId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No puedes quitarte el rol de Fundador a ti mismo.'
            ])->setStatusCode(403);
        }

        // 4. Verificar que quede al menos 1 fundador después de la revocación
        $founderCount = $db->table('user_condominium_roles')
            ->where('condominium_id', $condo['id'])
            ->where('role_id', 2)
            ->where('is_owner', 1)
            ->countAllResults();

        if ($founderCount <= 1) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se puede revocar: debe existir al menos un Fundador en la comunidad.'
            ])->setStatusCode(422);
        }

        // 5. Revocar: is_owner = 0 en el condominio actual (conserva acceso como Co-Admin)
        $db->table('user_condominium_roles')
            ->where('id', $assignmentId)
            ->update(['is_owner' => 0]);

        // 6. ELIMINAR acceso a los condominios que fueron otorgados por la promoción.
        //    Solo borra los que aún tienen is_owner = 1 (creados/actualizados por promoteToFounder).
        //    Conserva registros donde is_owner = 0 (co-admin independiente previo a la promoción).
        $demotedUserId = (int) $target['user_id'];
        $db->table('user_condominium_roles')
            ->where('user_id', $demotedUserId)
            ->where('role_id', 2)
            ->where('is_owner', 1)
            ->where('condominium_id !=', $condo['id'])
            ->delete();

        log_message('info', "[ADMIN] Fundador revocado y acceso removido: assignment_id={$assignmentId}, user_id={$demotedUserId}, solo conserva condo={$condo['id']}, por user_id={$currentUserId}");

        return $this->response->setJSON(['success' => true, 'message' => 'Rol de Fundador revocado. Solo conserva acceso a esta comunidad.']);
    }

    private function bootstrapTenant(): void
    {
        $demoCondo = (new CondominiumModel())->first();
        if ($demoCondo) {
            \App\Services\TenantService::getInstance()->setTenantId((int) $demoCondo['id']);
        }
    }

    private function normalizeAddress(string $addressRaw): array
    {
        $cleanAddress = trim($addressRaw);
        $parts = array_values(array_filter(array_map('trim', explode(',', $cleanAddress)), static fn($item) => $item !== ''));

        $postalCode = 'Sin definir';
        if (preg_match('/\b\d{5}\b/', $cleanAddress, $matches)) {
            $postalCode = $matches[0];
        }

        return [
            'street'      => $parts[0] ?? ($cleanAddress !== '' ? $cleanAddress : 'Sin definir'),
            'city'        => $parts[1] ?? 'Sin definir',
            'state'       => $parts[2] ?? 'Sin definir',
            'postal_code' => $postalCode,
            'country'     => $parts[4] ?? ($parts[3] ?? 'Mexico'),
        ];
    }

    private function formatTimezone(string $timezoneId): string
    {
        $timezoneId = trim($timezoneId);
        if ($timezoneId === '') {
            $timezoneId = 'America/Mexico_City';
        }

        try {
            $timezone = new \DateTimeZone($timezoneId);
        } catch (\Throwable $exception) {
            $timezone = new \DateTimeZone('America/Mexico_City');
            $timezoneId = 'America/Mexico_City';
        }

        $now = new \DateTimeImmutable('now', $timezone);
        $offset = $timezone->getOffset($now);
        $sign = $offset >= 0 ? '+' : '-';
        $absoluteOffset = abs($offset);
        $hours = (int) floor($absoluteOffset / 3600);
        $minutes = (int) floor(($absoluteOffset % 3600) / 60);

        $offsetLabel = 'GMT' . $sign . $hours;
        if ($minutes > 0) {
            $offsetLabel .= ':' . str_pad((string) $minutes, 2, '0', STR_PAD_LEFT);
        }

        $parts = explode('/', $timezoneId);
        $city = end($parts);
        $city = $city !== false && $city !== '' ? str_replace('_', ' ', $city) : $timezoneId;

        return $city . ' (' . $offsetLabel . ')';
    }
    // ══════════════════════════════════════════════════
    //  PERFIL & SEGURIDAD (MI CUENTA)
    // ══════════════════════════════════════════════════

    public function updateProfile()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida.'])->setStatusCode(401);

        $first_name = trim((string) $this->request->getPost('first_name'));
        $last_name  = trim((string) $this->request->getPost('last_name'));
        $phone      = trim((string) $this->request->getPost('phone'));

        if ($first_name === '') {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre es obligatorio.'])->setStatusCode(422);
        }

        $userModel = new \App\Models\Core\UserModel();
        $userModel->update($userId, [
            'first_name' => $first_name,
            'last_name'  => $last_name,
            'phone'      => $phone,
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    public function uploadAvatar()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida.'])->setStatusCode(401);

        $file = $this->request->getFile('avatar');
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON(['success' => false, 'message' => 'Archivo inválido o no subido.'])->setStatusCode(422);
        }

        $uploadPath = WRITEPATH . 'uploads/avatars/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $newName = $file->getRandomName();
        $file->move($uploadPath, $newName);

        $userModel = new \App\Models\Core\UserModel();
        $user = $userModel->find($userId);
        
        if ($user && !empty($user['avatar'])) {
            // Delete old avatar (whether it's flat or in a subfolder)
            $oldPathFlat = $uploadPath . $user['avatar'];
            $oldPathUser = $uploadPath . $userId . '/' . $user['avatar'];
            if (is_file($oldPathFlat)) unlink($oldPathFlat);
            if (is_file($oldPathUser)) unlink($oldPathUser);
        }

        $userModel->update($userId, ['avatar' => $newName]);

        return $this->response->setJSON([
            'success' => true,
            'url'     => base_url('media/image/avatars/' . $newName)
        ]);
    }

    public function serveAvatar($filename)
    {
        $userId = session()->get('user_id');
        
        // Check flat path first (where new avatars are uploaded)
        $fullPath = WRITEPATH . 'uploads/avatars/' . $filename;

        if (! is_file($fullPath)) {
            // Check legacy folder /avatars/ID/
            $fullPath = WRITEPATH . 'uploads/avatars/' . $userId . '/' . $filename;
            if (! is_file($fullPath)) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
            }
        }

        $fileMTime = filemtime($fullPath);
        $lastModified = gmdate('D, d M Y H:i:s', $fileMTime) . ' GMT';

        $this->response->setHeader('Cache-Control', 'public, max-age=31536000, immutable');
        $this->response->setHeader('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        $this->response->setHeader('Last-Modified', $lastModified);

        if ($this->request->getHeaderLine('If-Modified-Since') === $lastModified) {
            return $this->response->setStatusCode(304);
        }

        return $this->response
            ->setHeader('Content-Type', mime_content_type($fullPath))
            ->setBody(file_get_contents($fullPath));
    }

    public function updatePassword()
    {
        $userId = session()->get('user_id');
        if (!$userId) return $this->response->setJSON(['success' => false, 'message' => 'Sesión no válida.'])->setStatusCode(401);

        $current_pwd = (string) $this->request->getPost('current_password');
        $new_pwd     = (string) $this->request->getPost('new_password');
        $confirm_pwd = (string) $this->request->getPost('confirm_password');

        if (strlen($new_pwd) < 8) {
            return $this->response->setJSON(['success' => false, 'message' => 'La nueva contraseña debe tener al menos 8 caracteres.'])->setStatusCode(422);
        }

        if ($new_pwd !== $confirm_pwd) {
            return $this->response->setJSON(['success' => false, 'message' => 'La confirmación no coincide con la nueva contraseña.'])->setStatusCode(422);
        }

        $userModel = new \App\Models\Core\UserModel();
        $user = $userModel->find($userId);

        if (!password_verify($current_pwd, $user['password_hash'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'La contraseña actual es incorrecta.'])->setStatusCode(422);
        }

        $userModel->update($userId, [
            'password_hash' => password_hash($new_pwd, PASSWORD_DEFAULT),
        ]);

        return $this->response->setJSON(['success' => true]);
    }

    // ══════════════════════════════════════════════════
    //  SECCIONES (TORRES/BLOQUES)
    // ══════════════════════════════════════════════════

    public function saveSection()
    {
        $this->bootstrapTenant();
        $id = $this->request->getPost('id');
        $name = trim((string) $this->request->getPost('name'));
        
        // El frontend enviará un arreglo normal si es JSON o form-data
        $unitIds = $this->request->getPost('unit_ids');
        if (!is_array($unitIds)) {
            $unitIds = json_decode($unitIds ?? '[]', true) ?? [];
        }

        if (empty($name)) {
            return $this->response->setJSON(['success' => false, 'message' => 'El nombre de la sección es obligatorio.'])->setStatusCode(422);
        }

        $sectionModel = new \App\Models\Tenant\SectionModel();
        
        if ($id) {
            // Edit
            $section = $sectionModel->find($id);
            if (!$section) {
                return $this->response->setJSON(['success' => false, 'message' => 'Sección no encontrada.'])->setStatusCode(404);
            }
            $sectionModel->update($id, ['name' => $name]);
        } else {
            // Create
            $id = $sectionModel->insert(['name' => $name]);
        }

        $db = \Config\Database::connect();
        $builder = $db->table('units');
        
        // 1. Desvincular unidades previas asignadas a esta sección
        $builder->where('section_id', $id)->update([
            'section_id' => null, 
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // 2. Vincular nuevas unidades
        if (!empty($unitIds)) {
            // Need to filter out any empty values to avoid DB errors
            $unitIds = array_filter(array_map('intval', $unitIds));
            if (!empty($unitIds)) {
                $builder->whereIn('id', $unitIds)->update([
                    'section_id' => $id, 
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }
        }

        return $this->response->setJSON(['success' => true]);
    }

    public function deleteSection()
    {
        $this->bootstrapTenant();
        $id = $this->request->getPost('id');
        
        $sectionModel = new \App\Models\Tenant\SectionModel();
        if (!$sectionModel->find($id)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Sección no encontrada.'])->setStatusCode(404);
        }
        
        // Delete, the DB will CASCADE SET NULL to units' section_id
        $sectionModel->delete($id);
        
        return $this->response->setJSON(['success' => true]);
    }

    // ------------------------------------------------------------------------
    // PAYMENT REMINDERS (CRUD)
    // ------------------------------------------------------------------------

    public function savePaymentReminder()
    {
        $this->bootstrapTenant();
        $id = $this->request->getPost('id'); // empty if new
        
        $data = [
            'trigger_type'  => $this->request->getPost('trigger_type'),
            'trigger_value' => (int) $this->request->getPost('trigger_value'),
            'message_title' => $this->request->getPost('message_title'),
            'message_body'  => $this->request->getPost('message_body'),
        ];
        
        $model = new \App\Models\Tenant\PaymentReminderModel();
        
        if ($id) {
            $model->update($id, $data);
            return $this->response->setJSON(['success' => true, 'message' => 'Recordatorio actualizado correctamente']);
        } else {
            // Count current to prevent more than 5
            $condo = (new CondominiumModel())->first();
            $count = $model->where('condominium_id', $condo['id'])->countAllResults();
            if ($count >= 5) {
                return $this->response->setJSON(['success' => false, 'message' => 'Límite de 5 recordatorios alcanzado']);
            }
            
            $data['condominium_id'] = $condo['id'];
            $data['is_active'] = 1;
            $model->insert($data);
            return $this->response->setJSON(['success' => true, 'message' => 'Recordatorio creado correctamente']);
        }
    }

    public function togglePaymentReminder()
    {
        $this->bootstrapTenant();
        $id = $this->request->getPost('id');
        $isActive = (int) $this->request->getPost('is_active');
        
        $model = new \App\Models\Tenant\PaymentReminderModel();
        if ($model->find($id)) {
            $model->update($id, ['is_active' => $isActive]);
            return $this->response->setJSON(['success' => true]);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'No encontrado']);
    }

    public function deletePaymentReminder()
    {
        $this->bootstrapTenant();
        $id = $this->request->getPost('id');
        
        $model = new \App\Models\Tenant\PaymentReminderModel();
        if ($model->find($id)) {
            $model->delete($id);
            return $this->response->setJSON(['success' => true, 'message' => 'Eliminado correctamente']);
        }
        return $this->response->setJSON(['success' => false, 'message' => 'No encontrado']);
    }
    // ------------------------------------------------------------------------
    // DELINQUENCY RESTRICTIONS (MOROSIDAD)
    // ------------------------------------------------------------------------

    public function saveDelinquencyRestrictions()
    {
        $this->bootstrapTenant();
        
        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado']);
        }
        
        $restrict_qr = (int) $this->request->getPost('restrict_qr_delinquent');
        $restrict_amenities = (int) $this->request->getPost('restrict_amenities_delinquent');
        
        $condoModel->update($condo['id'], [
            'restrict_qr_delinquent' => $restrict_qr,
            'restrict_amenities_delinquent' => $restrict_amenities
        ]);
        
        return $this->response->setJSON([
            'success' => true,
            'message' => 'Restricciones de morosidad actualizadas'
        ]);
    }

    /**
     * Save bank details via AJAX
     */
    public function saveBankDetails()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado']);
        }

        $condoModel->update($condo['id'], [
            'bank_name'  => trim($this->request->getPost('bank_name') ?? ''),
            'bank_clabe' => trim($this->request->getPost('bank_clabe') ?? ''),
            'bank_rfc'   => trim($this->request->getPost('bank_rfc') ?? ''),
            'bank_card'  => trim($this->request->getPost('bank_card') ?? ''),
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Datos bancarios actualizados correctamente'
        ]);
    }

    /**
     * Save payment configuration (currency + due day) via AJAX
     */
    public function savePaymentConfig()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado']);
        }

        $currency = $this->request->getPost('currency');
        $dueDay   = (int) $this->request->getPost('billing_due_day');

        if (!in_array($currency, ['MXN', 'USD'])) {
            $currency = 'MXN';
        }
        if ($dueDay < 1 || $dueDay > 28) {
            $dueDay = 15;
        }

        $condoModel->update($condo['id'], [
            'currency'        => $currency,
            'billing_due_day' => $dueDay,
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Configuración de pagos actualizada correctamente'
        ]);
    }

    /**
     * Save late fee configuration via AJAX
     */
    public function saveLateFeeConfig()
    {
        $this->bootstrapTenant();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado']);
        }

        $enabled = $this->request->getPost('late_fee_enabled') === 'true' || $this->request->getPost('late_fee_enabled') === '1';
        $type = $this->request->getPost('late_fee_type');
        $amount = (float) $this->request->getPost('late_fee_amount');
        $percentage = (float) $this->request->getPost('late_fee_percentage');
        
        $maxAmountRaw = $this->request->getPost('late_fee_max_amount');
        $maxAmount = ($maxAmountRaw === '' || $maxAmountRaw === null) ? null : (float) $maxAmountRaw;
        
        $graceEnabled = $this->request->getPost('late_fee_grace_enabled') === 'true' || $this->request->getPost('late_fee_grace_enabled') === '1';
        $graceDays = (int) $this->request->getPost('late_fee_grace_days');
        
        $categoriesRaw = $this->request->getPost('late_fee_categories');
        $categories = [];
        if (!empty($categoriesRaw)) {
            $decoded = json_decode($categoriesRaw, true);
            if (is_array($decoded)) {
                $categories = array_map('intval', $decoded);
            }
        }

        // Validations
        if (!in_array($type, ['fixed', 'percentage'])) {
            $type = 'fixed';
        }
        if ($type === 'percentage') {
            $amount = 0;
            if ($percentage < 0) $percentage = 0;
            if ($percentage > 100) $percentage = 100;
        } else {
            $percentage = 0;
            $maxAmount = null;
            if ($amount < 0) $amount = 0;
        }

        if ($graceDays < 0) $graceDays = 0;
        if ($graceDays > 365) $graceDays = 365;

        $condoModel->update($condo['id'], [
            'late_fee_enabled'       => $enabled ? 1 : 0,
            'late_fee_type'          => $type,
            'late_fee_amount'        => $amount,
            'late_fee_percentage'    => $percentage,
            'late_fee_max_amount'    => $maxAmount,
            'late_fee_grace_enabled' => $graceEnabled ? 1 : 0,
            'late_fee_grace_days'    => $graceDays,
            'late_fee_categories'    => json_encode($categories)
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Configuración de recargos por mora actualizada correctamente'
        ]);
    }

    /**
     * Obtener info de suscripción del condominio actual (AJAX).
     */
    public function getSubscription()
    {
        $this->bootstrapTenant();
        $db = \Config\Database::connect();
        $condoModel = new CondominiumModel();
        $condo = $condoModel->first() ?? [];

        if (empty($condo)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado.'])->setStatusCode(404);
        }

        // Plan actual
        $currentPlan = null;
        if (!empty($condo['plan_id'])) {
            $currentPlan = $db->table('plans')->where('id', $condo['plan_id'])->get()->getRowArray();
        }

        // Contar unidades actuales
        $unitCount = $db->table('units')->where('condominium_id', $condo['id'])->countAllResults();

        // Todos los planes activos
        $plans = $db->table('plans')
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('min_units', 'ASC')
            ->get()->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'current_plan' => $currentPlan,
            'billing_cycle' => $condo['billing_cycle'] ?? 'monthly',
            'payment_method' => $condo['payment_method'] ?? 'stripe',
            'plan_expires_at' => $condo['plan_expires_at'] ?? null,
            'subscription_status' => $condo['subscription_status'] ?? 'active',
            'grace_until' => $condo['grace_until'] ?? null,
            'stripe_subscription_id' => $condo['stripe_subscription_id'] ?? null,
            'unit_count' => $unitCount,
            'plans' => $plans,
        ]);
    }

    /**
     * Cambiar plan del condominio actual (AJAX).
     */
    public function changePlan()
    {
        $this->bootstrapTenant();
        $db = \Config\Database::connect();
        $condoModel = new CondominiumModel();
        $condo = $condoModel->first() ?? [];

        if (empty($condo)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado.'])->setStatusCode(404);
        }

        // ── Bloquear cambios de plan para condominios con pago manual ──
        if (($condo['payment_method'] ?? 'stripe') === 'manual') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Tu plan es administrado manualmente. Contacta a hola@axiscondo.mx para realizar cambios.'
            ])->setStatusCode(403);
        }

        $newPlanId = (int) $this->request->getPost('plan_id');
        $cycle = $this->request->getPost('billing_cycle') ?: ($condo['billing_cycle'] ?? 'monthly');

        $plan = $db->table('plans')->where('id', $newPlanId)->get()->getRowArray();
        if (!$plan) {
            return $this->response->setJSON(['success' => false, 'message' => 'Plan no encontrado.'])->setStatusCode(404);
        }

        // Validar unidades actuales
        $unitCount = $db->table('units')->where('condominium_id', $condo['id'])->countAllResults();
        if ($unitCount > (int) $plan['max_units']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => "Tu condominio tiene {$unitCount} unidades pero el plan \"{$plan['name']}\" permite máximo {$plan['max_units']}. Reduce las unidades antes de cambiar."
            ])->setStatusCode(422);
        }

        // Obtener el Price ID de Stripe
        $stripePriceId = $cycle === 'yearly' ? $plan['stripe_price_id_yearly'] : $plan['stripe_price_id_monthly'];

        if (empty($stripePriceId)) {
            // Si el plan es gratuito o no tiene ID de Stripe configurado, permitimos el cambio directo
            // (Asumimos price = 0)
            if ((float)($cycle === 'yearly' ? $plan['price_yearly'] : $plan['price_monthly']) == 0) {
                $expiresAt = $cycle === 'yearly'
                    ? date('Y-m-d H:i:s', strtotime('+1 year'))
                    : date('Y-m-d H:i:s', strtotime('+1 month'));

                $condoModel->update($condo['id'], [
                    'plan_id' => $newPlanId,
                    'billing_cycle' => $cycle,
                    'plan_expires_at' => $expiresAt,
                ]);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Plan cambiado a \"{$plan['name']}\" exitosamente."
                ]);
            }

            return $this->response->setJSON([
                'success' => false,
                'message' => "El plan \"{$plan['name']}\" no tiene configurado un ID de cobro en Stripe. Contacta a soporte."
            ])->setStatusCode(422);
        }

        // ── Obtener o crear Stripe Customer ──
        $stripeCustomerId = $condo['stripe_customer_id'] ?? null;
        $stripeSvc = new \App\Services\Billing\StripeService();

        if (empty($stripeCustomerId)) {
            // Obtener el Owner
            $userId = session()->get('user_id') ?? session()->get('user')['id'];
            $owner = $db->table('user_condominium_roles')
                ->where('condominium_id', $condo['id'])
                ->where('role_id', 2)
                ->orderBy('id', 'ASC')
                ->limit(1)
                ->get()
                ->getRow();

            $ownerUserId = $owner ? $owner->user_id : $userId;
            $ownerUser = $db->table('users')->where('id', $ownerUserId)->get()->getRowArray();
            $customerEmail = $ownerUser['email'] ?? '';
            $customerName  = trim(($ownerUser['first_name'] ?? '') . ' ' . ($ownerUser['last_name'] ?? ''));

            $stripeCustomerId = $stripeSvc->createCustomer(
                $customerEmail,
                $condo['name'] . ' — ' . $customerName,
                ['condominium_id' => $condo['id'], 'condominium_name' => $condo['name']]
            );

            if (!$stripeCustomerId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al conectar con Stripe para crear el cliente.'
                ])->setStatusCode(502);
            }

            $condoModel->update($condo['id'], ['stripe_customer_id' => $stripeCustomerId]);
        }

        // ── Si ya tiene una suscripción activa, la actualizamos directamente en Stripe ──
        if (!empty($condo['stripe_subscription_id'])) {
            $updated = $stripeSvc->updateSubscription($condo['stripe_subscription_id'], $stripePriceId);
            
            if ($updated) {
                // Actualizamos DB local (la fecha de expiración se actualizará asíncronamente con el webhook si cambian los tiempos)
                $condoModel->update($condo['id'], [
                    'plan_id'       => $newPlanId,
                    'billing_cycle' => $cycle,
                ]);

                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Suscripción actualizada exitosamente. El ajuste prorrateado se aplicará en tu próxima factura.'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al modificar la suscripción en Stripe. Revisa el portal de facturación.'
                ])->setStatusCode(502);
            }
        }

        // ── Crear Checkout Session (Para Nuevas Suscripciones) ──
        $successUrl = site_url('superadmin/billing/success?session_id={CHECKOUT_SESSION_ID}');
        $cancelUrl  = site_url('superadmin/billing/cancel');

        $metadata = [
            'condominium_id' => $condo['id'],
            'plan_id'        => $newPlanId,
            'billing_cycle'  => $cycle
        ];

        $checkoutUrl = $stripeSvc->createSubscription($stripeCustomerId, $stripePriceId, $successUrl, $cancelUrl, $metadata);

        if (!$checkoutUrl) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se pudo generar la sesión de pago con Stripe.'
            ])->setStatusCode(502);
        }

        return $this->response->setJSON([
            'success' => true,
            'url'     => $checkoutUrl,
            'message' => "Redirigiendo al pago seguro de Stripe..."
        ]);
    }
    /**
     * Eliminar comunidad permanentemente (Soft Delete robusto + Seguridad Multi-Tenant)
     */
    public function deleteCommunity()
    {
        $this->bootstrapTenant();
        $db = \Config\Database::connect();
        
        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado.'])->setStatusCode(404);
        }

        $userId = session()->get('user_id') ?? session()->get('user')['id'];
        
        // 1. Validar Permisos: Solo el Fundador (is_owner = 1) puede eliminar
        if (!session()->get('is_owner')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Acceso Denegado: Solo el Administrador Fundador de la comunidad puede eliminarla.'
            ])->setStatusCode(403);
        }

        // 2. Validar Confirmación por Nombre Exacto
        $confirmName = $this->request->getPost('confirm_name');
        if (trim($confirmName) !== trim($condo['name'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'El nombre ingresado no coincide con el de la comunidad.'
            ])->setStatusCode(422);
        }

        // 3. Iniciar Transacción de Eliminación Segura
        $db->transStart();

        try {
            // A. Registrar Auditoría Estructurada (Observabilidad CRÍTICA)
            $auditData = [
                'user_id' => $userId,
                'condominium_id' => $condo['id'],
                'action' => 'delete_condominium',
                'details' => "Comunidad eliminada: {$condo['name']}",
                'ip_address' => $this->request->getIPAddress(),
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($db->tableExists('system_audit_logs')) {
                $db->table('system_audit_logs')->insert($auditData);
            } else {
                // Fallback a log estructurado en JSON si la tabla no existe aún
                $logLine = json_encode($auditData) . PHP_EOL;
                file_put_contents(WRITEPATH . 'logs/audit_delete_condominium.log', $logLine, FILE_APPEND);
            }
            log_message('critical', "[AUDIT] CondominiumDeletedEvent: " . json_encode($auditData));

            // Disparar Evento de Dominio (Domain Event) para desacoplar lógica futura (Billing, Notificaciones)
            \CodeIgniter\Events\Events::trigger('condominium_deleted', $auditData);

            // B. Cancelar Suscripción en Stripe (CRÍTICO — evitar cobros zombies)
            if (!empty($condo['stripe_subscription_id'])) {
                $stripeSvc = new \App\Services\Billing\StripeService();
                $canceled = $stripeSvc->cancelSubscription($condo['stripe_subscription_id']);
                log_message('info', "[DELETE_COMMUNITY] Stripe sub {$condo['stripe_subscription_id']} cancelada: " . ($canceled ? 'OK' : 'FALLÓ'));
            }

            // C. Limpiar datos de suscripción en BD
            $condoModel->update($condo['id'], [
                'status'                 => 'suspended',
                'subscription_status'    => 'canceled',
                'plan_id'                => null,
                'stripe_subscription_id' => null,
            ]);

            // C. Soft Delete del Condominio
            // Esto automáticamente gatilla deleted_at = NOW() gracias a $useSoftDeletes = true
            $condoModel->delete($condo['id']);

            // D. Nota: NO eliminamos `user_condominium_roles` para preservar historial.
            
            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->response->setJSON(['success' => false, 'message' => 'Error al ejecutar la eliminación en la base de datos.'])->setStatusCode(500);
            }

            // 4. Invalidar Contexto Tenant
            session()->remove('condominium_id');
            session()->remove('current_condominium_id');
            // Removemos roles cacheados si existen
            session()->remove('user_roles'); 

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Comunidad eliminada correctamente. Redirigiendo...'
            ]);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', '[DELETE_COMMUNITY] Error: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Error interno del servidor.'])->setStatusCode(500);
        }
    }

    /**
     * Genera sesión del Stripe Customer Portal para gestionar facturación.
     * Solo accesible para el Administrador Propietario del condominio.
     */
    public function billingPortal()
    {
        $this->bootstrapTenant();
        $db = \Config\Database::connect();

        $condoModel = new CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo) {
            return $this->response->setJSON(['success' => false, 'message' => 'Condominio no encontrado.'])->setStatusCode(404);
        }

        // ── Validar que el usuario sea el Owner (admin más antiguo) ──
        $userId = session()->get('user_id') ?? session()->get('user')['id'];
        $owner = $db->table('user_condominium_roles')
            ->where('condominium_id', $condo['id'])
            ->where('role_id', 2) // ADMIN
            ->orderBy('id', 'ASC')
            ->limit(1)
            ->get()
            ->getRow();

        if (!$owner || $owner->user_id != $userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Solo el administrador propietario puede gestionar la facturación.'
            ])->setStatusCode(403);
        }

        // ── Obtener o crear Stripe Customer ──
        $stripeCustomerId = $condo['stripe_customer_id'] ?? null;

        if (empty($stripeCustomerId)) {
            // Obtener email del owner para crear el customer
            $ownerUser = $db->table('users')->where('id', $owner->user_id)->get()->getRowArray();
            $customerEmail = $ownerUser['email'] ?? '';
            $customerName  = trim(($ownerUser['first_name'] ?? '') . ' ' . ($ownerUser['last_name'] ?? ''));

            $stripeSvc = new \App\Services\Billing\StripeService();
            $stripeCustomerId = $stripeSvc->createCustomer(
                $customerEmail,
                $condo['name'] . ' — ' . $customerName,
                ['condominium_id' => $condo['id'], 'condominium_name' => $condo['name']]
            );

            if (!$stripeCustomerId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Error al conectar con el servicio de pagos. Intente más tarde.'
                ])->setStatusCode(502);
            }

            // Guardar en BD para no duplicar
            $condoModel->update($condo['id'], ['stripe_customer_id' => $stripeCustomerId]);
        }

        // ── Crear Billing Portal Session ──
        $returnUrl = rtrim(site_url('admin/configuracion'), '/');
        $stripeSvc = $stripeSvc ?? new \App\Services\Billing\StripeService();
        $portalUrl = $stripeSvc->createBillingPortalSession($stripeCustomerId, $returnUrl);

        if (!$portalUrl) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'No se pudo generar la sesión de facturación. Verifica la configuración de Stripe.'
            ])->setStatusCode(502);
        }

        return $this->response->setJSON([
            'success' => true,
            'url'     => $portalUrl
        ]);
    }
}
