<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\TenantDocumentModel;

class FileController extends BaseController
{
    public function indexView()
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $documentModel = new TenantDocumentModel();
        
        $folderHash = $this->request->getVar('folder');
        $filter = $this->request->getVar('filter');
        $accessFilter = $this->request->getVar('access');
        $categoryFilter = $this->request->getVar('category');
        $view = $this->request->getVar('view'); // 'analytics'
        
        $folderId = null;
        $currentFolder = null;
        
        if ($folderHash) {
            $currentFolder = $documentModel->where('hash_id', $folderHash)->first();
            if ($currentFolder) {
                $folderId = $currentFolder['id'];
            }
        }
        
        // Compute storage for sidebar (always needed)
        $allDocs = $documentModel->where('type', 'file')->findAll();
        $totalStorageBytes = array_sum(array_column($allDocs, 'size_bytes'));
        
        // Analytics view
        if ($view === 'analytics') {
            return $this->analyticsView($documentModel, $totalStorageBytes);
        }
        
        $query = $documentModel->orderBy('type', 'desc')->orderBy('created_at', 'desc');
        
        if ($filter === 'destacados') {
            $query->where('is_starred', 1);
        } elseif ($filter === 'recientes') {
            // Already ordered by created_at, just fetch all or top N. Unfiltered by parent_id.
        } elseif ($accessFilter) {
            // Filter by access level
            $accessMap = [
                'admin' => 'Solo Admins',
                'propietarios' => 'Propietarios',
                'todos' => 'Todos'
            ];
            if (isset($accessMap[$accessFilter])) {
                $query->where('access_level', $accessMap[$accessFilter]);
            }
        } elseif ($categoryFilter) {
            $query->where('category', $categoryFilter);
        } else {
            if ($folderId) {
                $query->where('parent_id', $folderId);
            } else {
                $query->where('parent_id IS NULL');
            }
        }
        
        $documents = $query->findAll();
        
        foreach ($documents as &$doc) {
            if ($doc['type'] === 'folder') {
                $doc['element_count'] = $documentModel->where('parent_id', $doc['id'])->countAllResults();
            }
        }
        
        // Si hay filtros globales, anulamos currentFolder para no mostrar breadcrumbs de una carpeta específica
        if ($filter || $accessFilter || $categoryFilter) {
            $currentFolder = null;
        }

        $allCategories = [
            'Financiero',
            'Legal y Contratos',
            'Reglas y Regulaciones',
            'Recibos',
            'Mantenimiento',
            'Actas de Reuniones',
            'General'
        ];

        return view('admin/documents', [
            'documents' => $documents,
            'currentFolder' => $currentFolder,
            'filter' => $filter,
            'accessFilter' => $accessFilter,
            'categoryFilter' => $categoryFilter,
            'allCategories' => $allCategories,
            'totalStorageBytes' => $totalStorageBytes
        ]);
    }

    public function createFolder()
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $name = $this->request->getPost('name');
        
        if (empty($name)) {
            return $this->response->setJSON(['status' => 400, 'error' => 'El nombre de la carpeta es requerido']);
        }

        $documentModel = new TenantDocumentModel();
        
        $data = [
            'condominium_id' => $tenantId,
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'type' => 'folder',
            'name' => trim((string)$name),
            'category' => $this->request->getPost('category') ?: 'General',
            'access_level' => 'Solo Admins',
            'uploaded_by' => session()->get('user_id') ?? null
        ];

        try {
            $documentModel->insert($data);
            return $this->response->setJSON(['status' => 201]);
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 500, 'error' => $e->getMessage()]);
        }
    }

    public function uploadFiles()
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $documentModel = new TenantDocumentModel();

        // Extraer los metadatos recibidos por JSON (array objects config_)
        $configsJson = $this->request->getPost('configs');
        $configs = json_decode((string)$configsJson, true) ?? [];
        
        $files = $this->request->getFiles();

        if (empty($files) || !isset($files['files'])) {
            return $this->response->setJSON(['status' => 400, 'error' => 'No se encontraron archivos validos enviados']);
        }
        
        $uploadMap = is_array($files['files']) ? $files['files'] : [$files['files']];
        $i = 0;

        try {
            foreach ($uploadMap as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $newName = $file->getRandomName();
                    // Extraer los metadatos de archivo antes de moverlo (sino el tmp_file desaparece y da error)
                    $sizeBytes = $file->getSize();
                    $mimeType = $file->getMimeType();
                    $clientName = $file->getClientName();

                    // Asegurar directorio y mover
                    if (!is_dir(WRITEPATH . 'uploads/documents')) {
                        mkdir(WRITEPATH . 'uploads/documents', 0755, true);
                    }
                    $file->move(WRITEPATH . 'uploads/documents', $newName);
                    
                    // Matchear datos del array en JSON
                    $fileConfig = $configs[$i] ?? [];
                    
                    $data = [
                        'condominium_id' => $tenantId,
                        'parent_id' => $this->request->getPost('parent_id') ?: null,
                        'type' => 'file',
                        'name' => trim((string)($fileConfig['name'] ?? $clientName)),
                        'path' => $newName,
                        'category' => $fileConfig['category'] ?? 'General',
                        'access_level' => $fileConfig['access'] ?? 'Todos',
                        'size_bytes' => $sizeBytes,
                        'mime_type' => $mimeType,
                        'uploaded_by' => session()->get('user_id') ?? null
                    ];

                    $documentModel->insert($data);
                }
                $i++;
            }
        } catch (\Exception $e) {
            return $this->response->setJSON(['status' => 500, 'error' => $e->getMessage()]);
        }

        return $this->response->setJSON(['status' => 201, 'message' => 'Upload successful']);
    }

    public function updateAccess($id = null)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $accessLevel = $this->request->getPost('access_level');
        if (empty($accessLevel) || !$id) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Datos invalidos']);
        }
        
        $documentModel = new TenantDocumentModel();
        
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Documento no encontrado o sin permisos']);
        }

        $documentModel->update($id, ['access_level' => $accessLevel]);
        
        return $this->response->setJSON(['status' => 200, 'message' => 'Nivel de acceso actualizado con exito']);
    }

    public function downloadFile($id)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setStatusCode(404, 'File not found');
        }

        if ($doc['type'] !== 'file') {
            return $this->response->setStatusCode(400, 'Not a file');
        }

        $path = WRITEPATH . 'uploads/documents/' . $doc['path'];
        if (!file_exists($path)) {
            return $this->response->setStatusCode(404, 'File missing in disk');
        }

        // Track download
        $this->trackDocumentAction($tenantId, $id, 'download');

        $fileName = $doc['name'];
        $originalExt = pathinfo($path, PATHINFO_EXTENSION);
        $nameExt = pathinfo($fileName, PATHINFO_EXTENSION);
        
        if (empty($nameExt) && !empty($originalExt)) {
            $fileName .= '.' . $originalExt;
        }

        return $this->response->download($path, null)->setFileName($fileName);
    }

    public function previewFile($id)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setStatusCode(404, 'File not found');
        }

        if ($doc['type'] !== 'file') {
            return $this->response->setStatusCode(400, 'Not a file');
        }

        $path = WRITEPATH . 'uploads/documents/' . $doc['path'];
        if (!file_exists($path)) {
            return $this->response->setStatusCode(404, 'File missing in disk');
        }

        // Output file inline
        $mime = mime_content_type($path);
        
        // Track view
        $this->trackDocumentAction($tenantId, $id, 'view');
        
        $this->response->setContentType($mime);
        $this->response->setHeader('Content-Disposition', 'inline; filename="' . basename($doc['name']) . '"');
        $this->response->setHeader('Content-Length', filesize($path));
        $this->response->setBody(file_get_contents($path));
        return $this->response;
    }

    public function trackView($id)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $this->trackDocumentAction($tenantId, (int)$id, 'view');
        return $this->response->setJSON(['status' => 200]);
    }

    public function toggleStar($id)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setJSON(['status' => 404, 'error' => 'No encontrado']);
        }
        
        $newStatus = $doc['is_starred'] == 1 ? 0 : 1;
        $documentModel->update($id, ['is_starred' => $newStatus]);
        
        return $this->response->setJSON(['status' => 200, 'is_starred' => $newStatus]);
    }

    public function moveDocument($id)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $newParentId = $this->request->getPost('parent_id');
        
        if ($newParentId === 'root' || empty($newParentId)) {
            $newParentId = null;
        } else {
            $newParentId = (int)$newParentId;
        }
        
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setJSON(['status' => 404, 'error' => 'No encontrado']);
        }
        
        // Evitar moverse dentro de si mismo
        if ($newParentId !== null && $id == $newParentId) {
            return $this->response->setJSON(['status' => 400, 'error' => 'No puedes mover a la misma carpeta']);
        }

        if (!$documentModel->update($id, ['parent_id' => $newParentId])) {
             return $this->response->setJSON(['status' => 500, 'error' => 'Error al mover en BD']);
        }
        
        return $this->response->setJSON([
            'status' => 200, 
            'message' => 'Elemento movido', 
            'debug_parent' => $newParentId
        ]);
    }

    public function renameDocument($id)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $newName = trim((string)$this->request->getPost('name'));
        $newCategory = $this->request->getPost('category');
        if (empty($newName)) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Nombre requerido']);
        }

        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setJSON(['status' => 404, 'error' => 'No encontrado']);
        }

        $updateData = ['name' => $newName];
        if ($newCategory !== null) {
            $updateData['category'] = $newCategory;
        }

        $documentModel->update($id, $updateData);
        
        return $this->response->setJSON(['status' => 200, 'message' => 'Actualizado con éxito']);
    }

    public function deleteDocument($id)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setJSON(['status' => 404, 'error' => 'No encontrado']);
        }

        $documentModel->delete($id);
        
        return $this->response->setJSON(['status' => 200, 'message' => 'Elemento eliminado']);
    }

    public function apiGetFolders()
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $documentModel = new TenantDocumentModel();
        // Return all folders for the move modal
        $folders = $documentModel->where('type', 'folder')->orderBy('name', 'asc')->findAll();
        
        return $this->response->setJSON(['status' => 200, 'folders' => $folders]);
    }

    private function analyticsView($documentModel, $totalStorageBytes)
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $db = \Config\Database::connect();
        
        // Total files
        $totalFiles = $documentModel->where('type', 'file')->countAllResults();
        
        // Total views
        $totalViews = $db->table('document_views')
            ->where('condominium_id', $tenantId)
            ->where('action', 'view')
            ->countAllResults();
        
        // Unique viewers
        $uniqueViewers = $db->table('document_views')
            ->where('condominium_id', $tenantId)
            ->where('action', 'view')
            ->where('user_id IS NOT NULL')
            ->distinct()
            ->select('user_id')
            ->countAllResults();
        
        // Total downloads
        $totalDownloads = $db->table('document_views')
            ->where('condominium_id', $tenantId)
            ->where('action', 'download')
            ->countAllResults();
        
        // Shared files (access_level != Solo Admins)
        $sharedFiles = $documentModel->where('type', 'file')
            ->where('access_level !=', 'Solo Admins')
            ->countAllResults();
        
        // Recent activity (last 30 days)
        $thirtyDaysAgo = date('Y-m-d H:i:s', strtotime('-30 days'));
        $recentActivity = $db->table('document_views')
            ->where('condominium_id', $tenantId)
            ->where('created_at >=', $thirtyDaysAgo)
            ->countAllResults();
        
        // Storage
        $totalStorageKB = round($totalStorageBytes / 1024, 1);
        $limitGB = 1.0;
        $storagePercent = round(($totalStorageBytes / ($limitGB * 1024 * 1024 * 1024)) * 100, 1);
        
        // Distribution by category (Resumen tab - donut chart)
        $categories = $documentModel->where('type', 'file')
            ->select('category, COUNT(*) as cnt, SUM(size_bytes) as total_size')
            ->groupBy('category')
            ->findAll();
        
        // Popular tab: Most viewed
        $mostViewed = $db->table('document_views dv')
            ->select('dv.document_id, td.name, td.category, COUNT(*) as view_count')
            ->join('tenant_documents td', 'td.id = dv.document_id')
            ->where('dv.condominium_id', $tenantId)
            ->where('dv.action', 'view')
            ->groupBy('dv.document_id')
            ->orderBy('view_count', 'DESC')
            ->limit(10)
            ->get()->getResultArray();
        
        // Most viewed unique counts
        foreach ($mostViewed as &$mv) {
            $mv['unique_viewers'] = $db->table('document_views')
                ->where('document_id', $mv['document_id'])
                ->where('action', 'view')
                ->where('user_id IS NOT NULL')
                ->distinct()
                ->select('user_id')
                ->countAllResults();
        }
        
        // Most downloaded
        $mostDownloaded = $db->table('document_views dv')
            ->select('dv.document_id, td.name, td.category, COUNT(*) as download_count')
            ->join('tenant_documents td', 'td.id = dv.document_id')
            ->where('dv.condominium_id', $tenantId)
            ->where('dv.action', 'download')
            ->groupBy('dv.document_id')
            ->orderBy('download_count', 'DESC')
            ->limit(10)
            ->get()->getResultArray();
        
        // Activity tab: last 30 days grouped by date
        $activityData = $db->table('document_views')
            ->select("DATE(created_at) as date, action, COUNT(*) as cnt")
            ->where('condominium_id', $tenantId)
            ->where('created_at >=', $thirtyDaysAgo)
            ->groupBy('DATE(created_at), action')
            ->orderBy('date', 'ASC')
            ->get()->getResultArray();
        
        // Build activity chart data
        $activityViews = [];
        $activityDownloads = [];
        foreach ($activityData as $row) {
            if ($row['action'] === 'view') {
                $activityViews[$row['date']] = (int)$row['cnt'];
            } else {
                $activityDownloads[$row['date']] = (int)$row['cnt'];
            }
        }
        
        // Sum views and downloads for last 30 days
        $recentViews = array_sum($activityViews);
        $recentDownloads = array_sum($activityDownloads);
        
        // Top viewers tab
        $topViewers = $db->table('document_views dv')
            ->select("dv.user_id, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email, COUNT(*) as view_count")
            ->join('users u', 'u.id = dv.user_id', 'left')
            ->where('dv.condominium_id', $tenantId)
            ->whereIn('dv.action', ['view', 'download'])
            ->where('dv.user_id IS NOT NULL')
            ->groupBy('dv.user_id')
            ->orderBy('view_count', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        foreach ($topViewers as &$tv) {
            $units = $db->table('residents r')
                ->select('un.unit_number')
                ->join('units un', 'un.id = r.unit_id')
                ->where('r.user_id', $tv['user_id'])
                ->where('r.condominium_id', $tenantId)
                ->get()->getResultArray();
            $tv['unit_number'] = empty($units) ? null : implode(', ', array_column($units, 'unit_number'));
        }
        
        $allCategories = [
            'Financiero',
            'Legal y Contratos',
            'Reglas y Regulaciones',
            'Recibos',
            'Mantenimiento',
            'Actas de Reuniones',
            'General'
        ];
        
        return view('admin/documents', [
            'documents' => [],
            'currentFolder' => null,
            'filter' => null,
            'accessFilter' => null,
            'allCategories' => $allCategories,
            'totalStorageBytes' => $totalStorageBytes,
            'analyticsView' => true,
            'analytics' => [
                'totalFiles' => $totalFiles,
                'totalViews' => $totalViews,
                'uniqueViewers' => $uniqueViewers,
                'totalDownloads' => $totalDownloads,
                'sharedFiles' => $sharedFiles,
                'recentActivity' => $recentActivity,
                'totalStorageKB' => $totalStorageKB,
                'storagePercent' => $storagePercent,
                'categories' => $categories,
                'mostViewed' => $mostViewed,
                'mostDownloaded' => $mostDownloaded,
                'activityViews' => $activityViews,
                'activityDownloads' => $activityDownloads,
                'recentViews' => $recentViews,
                'recentDownloads' => $recentDownloads,
                'topViewers' => $topViewers,
            ]
        ]);
    }

    public function getDocumentActivity($id)
    {
        $this->bootstrapTenant();
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();

        // Verificar que el documento pertenece al tenant
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setStatusCode(404, 'Documento no encontrado o sin acceso');
        }

        $db = \Config\Database::connect();
        
        $builder = $db->table('document_views dv')
            ->select("
                dv.id,
                dv.action,
                dv.created_at,
                u.first_name,
                u.last_name,
                u.email,
                un.unit_number
            ")
            ->join('users u', 'u.id = dv.user_id', 'left')
            ->join('residents r', 'r.user_id = u.id AND r.condominium_id = dv.condominium_id', 'left')
            ->join('units un', 'un.id = r.unit_id', 'left')
            ->where('dv.condominium_id', $tenantId)
            ->where('dv.document_id', (int)$id);

        $requestedAction = $this->request->getGet('action');
        if (in_array($requestedAction, ['view', 'download'])) {
            $builder->where('dv.action', $requestedAction);
        }

        $activity = $builder->orderBy('dv.created_at', 'DESC')->get()->getResultArray();

        // Agrupar posibles unidades si un residente tiene más de una, o simplemente limpiar nulos
        // Usamos dv.id como llave única para evitar pérdida de eventos independientes simultáneos
        $uniqueEvents = [];
        foreach ($activity as $row) {
            $key = $row['id'];
            if (!isset($uniqueEvents[$key])) {
                $uniqueEvents[$key] = $row;
                $uniqueEvents[$key]['unit_number'] = $row['unit_number'] ? [$row['unit_number']] : [];
            } else if ($row['unit_number']) {
                if (!in_array($row['unit_number'], $uniqueEvents[$key]['unit_number'])) {
                    $uniqueEvents[$key]['unit_number'][] = $row['unit_number'];
                }
            }
        }

        $formatted = array_values($uniqueEvents);
        foreach ($formatted as &$f) {
            $f['user_name'] = trim(($f['first_name'] ?? '') . ' ' . ($f['last_name'] ?? ''));
            if (empty($f['user_name'])) {
                $f['user_name'] = 'Usuario Eliminado';
            }
            $f['units'] = empty($f['unit_number']) ? null : implode(', ', $f['unit_number']);
            unset($f['unit_number']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'document' => ['name' => $doc['name']],
            'activity' => $formatted
        ]);
    }

    private function trackDocumentAction($tenantId, $docId, $action)
    {
        $db = \Config\Database::connect();
        $db->table('document_views')->insert([
            'condominium_id' => $tenantId,
            'document_id' => $docId,
            'user_id' => session()->get('user_id') ?? null,
            'action' => $action,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function bootstrapTenant(): void
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) {
            \App\Services\TenantService::getInstance()->setTenantId((int) $demoCondo['id']);
        }
    }
}
