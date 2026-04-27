<?php

namespace App\Controllers\Api\V1;

use App\Models\Tenant\TenantDocumentModel;
use App\Models\Tenant\ResidentModel;

use CodeIgniter\RESTful\ResourceController;

class DocumentController extends ResourceController
{
    protected function respondSuccess($data = [])
    {
        return $this->respond([
            'status' => 'success',
            'data'   => $data
        ]);
    }

    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON([
            'status'  => 'error',
            'message' => $message
        ])->setStatusCode($status);
    }
    /**
     * GET /api/v1/resident/documents
     * Lista documentos (archivos y carpetas) de la comunidad
     */
    public function index()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autenticado', 401);

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $folderId = $this->request->getGet('folder_id'); // nullable
        $search = $this->request->getGet('search');
        
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        
        if (!$tenantId && $resident) {
            $tenantId = $resident['condominium_id'];
            \App\Services\TenantService::getInstance()->setTenantId($tenantId);
        }
        
        $db = \Config\Database::connect();
        
        // Verificar si es admin
        $isAdminQuery = 0;
        if ($tenantId) {
            $isAdminQuery = $db->table('user_condominium_roles ucr')
                ->join('roles r', 'r.id = ucr.role_id')
                ->where('ucr.user_id', $userId)
                ->where('ucr.condominium_id', $tenantId)
                ->whereIn('r.name', ['admin', 'super_admin'])
                ->countAllResults();
        }
            
        $isAdmin = $isAdminQuery > 0;
        $isOwner = $resident && in_array(strtolower((string)$resident['type']), ['owner', 'propietario']);
        
        $documentModel = new TenantDocumentModel();
        $query = $documentModel->orderBy('type', 'desc')->orderBy('created_at', 'desc');
        
        // Filtro de acceso
        if (!$isAdmin) {
            $query->groupStart(); // Start OR group
            // Condition 1: Always allow viewing 'folder' so navigation structural tree is accessible
            $query->where('type', 'folder');
            
            // Condition 2: Actual access levels for files
            $query->orGroupStart();
            if ($isOwner) {
                $query->whereIn('access_level', ['Todos', 'Propietarios']);
            } else {
                $query->where('access_level', 'Todos');
            }
            $query->groupEnd();
            
            $query->groupEnd(); // End OR group
        }
        
        if (!empty($search)) {
            $query->like('name', $search);
        } else {
            if ($folderId) {
                $query->where('parent_id', $folderId);
            } else {
                $query->where('parent_id IS NULL');
            }
        }
        
        $documents = $query->findAll();
        
        // Agregar property element_count y limpiar urls para flutter
        foreach ($documents as &$doc) {
            $doc['id'] = (int) $doc['id'];
            if ($doc['type'] === 'folder') {
                $doc['element_count'] = $documentModel->where('parent_id', $doc['id'])->countAllResults();
            } else {
                $doc['element_count'] = 0;
            }
        }

        return $this->respondSuccess([
            'is_admin' => $isAdmin,
            'documents' => $documents,
            'current_folder_id' => $folderId,
            'debug' => [
                'tenant_id' => $tenantId,
                'user_id' => $userId,
                'resident_type' => $resident['type'] ?? 'null',
                'is_owner' => $isOwner,
                'sql' => (string)$db->getLastQuery()
            ]
        ]);
    }

    /**
     * GET /api/v1/resident/documents/folders
     * Obtiene el listado de solo carpetas (para picker de mover archivos, etc)
     */
    public function getFolders()
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $documentModel = new TenantDocumentModel();
        $folders = $documentModel->where('type', 'folder')->orderBy('name', 'asc')->findAll();
        
        return $this->respondSuccess(['folders' => $folders]);
    }
    
    /**
     * GET /api/v1/resident/documents/{id}
     * Detalle individual de un documento
     */
    public function getDocument(int $id)
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $documentModel = new TenantDocumentModel();
        
        $doc = $documentModel->where('id', $id)->first();
        if (!$doc) return $this->respondError('Documento no encontrado', 404);
        
        // Track view action asynchronously (or synchronously here)
        $this->trackDocumentAction($tenantId, $id, 'view');
        
        return $this->respondSuccess(['document' => $doc]);
    }

    /**
     * GET /api/v1/resident/documents/download/{id}
     * Devuelve el archivo original en formato Binario/Streaming y registra descarga
     */
    public function download(int $id)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->response->setStatusCode(401, 'No autenticado');

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $residentModel = new ResidentModel();
        $resident = $residentModel->where('user_id', $userId)->first();
        if (!$tenantId && $resident) {
            $tenantId = $resident['condominium_id'];
            \App\Services\TenantService::getInstance()->setTenantId($tenantId);
        }

        $documentModel = new TenantDocumentModel();
        
        $doc = $documentModel->find($id);
        if (!$doc || $doc['condominium_id'] != $tenantId) {
            return $this->response->setStatusCode(404, 'File not found');
        }

        if ($doc['type'] !== 'file') {
            return $this->response->setStatusCode(400, 'Not a file');
        }

        // Validación de permisos
        $db = \Config\Database::connect();
        $isAdmin = $db->table('user_condominium_roles ucr')->where('ucr.user_id', $userId)->where('ucr.condominium_id', $tenantId)->countAllResults() > 0;
        $isOwner = $resident && in_array(strtolower((string)$resident['type']), ['owner', 'propietario']);

        if (!$isAdmin) {
            if ($doc['access_level'] === 'Solo Admins') return $this->response->setStatusCode(403, 'Acceso Denegado (Solo Administradores)');
            if ($doc['access_level'] === 'Propietarios' && !$isOwner) return $this->response->setStatusCode(403, 'Acceso Denegado (Solo Propietarios)');
        }

        $path = WRITEPATH . 'uploads/documents/' . $doc['path'];
        if (!file_exists($path)) {
            return $this->response->setStatusCode(404, 'File missing in disk');
        }

        $this->trackDocumentAction($tenantId, $id, 'download');

        $fileName = $doc['name'];
        $originalExt = pathinfo($path, PATHINFO_EXTENSION);
        $nameExt = pathinfo($fileName, PATHINFO_EXTENSION);
        if (empty($nameExt) && !empty($originalExt)) {
            $fileName .= '.' . $originalExt;
        }

        // Manejar correctamente los headers y envío para preview en el móvil
        return $this->response->download($path, null)->setFileName($fileName)->inline();
    }

    /**
     * POST /api/v1/resident/documents/folder
     * Crea un nuevo folder (si el usuario tiene permisos admin)
     */
    public function createFolder()
    {
        $userId = $this->request->userId ?? null;
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        // Validación admin rápida
        $db = \Config\Database::connect();
        $isAdmin = $db->table('user_condominium_roles ucr')->where('ucr.user_id', $userId)->where('ucr.condominium_id', $tenantId)->countAllResults() > 0;
        
        if (!$isAdmin) return $this->respondError('Solo administradores pueden crear carpetas', 403);
        
        $name = $this->request->getPost('name');
        if (empty($name)) return $this->respondError('El nombre es requerido', 400);

        $documentModel = new TenantDocumentModel();
        $data = [
            'condominium_id' => $tenantId,
            'parent_id' => $this->request->getPost('parent_id') ?: null,
            'type' => 'folder',
            'name' => trim((string)$name),
            'access_level' => 'Todos',
            'uploaded_by' => $userId
        ];

        $documentModel->insert($data);
        return $this->respondSuccess(['message' => 'Carpeta creada', 'id' => $documentModel->getInsertID()]);
    }

    /**
     * POST /api/v1/resident/documents/upload
     * Sube múltiples archivos simultáneamente.
     */
    public function uploadFiles()
    {
        $userId = $this->request->userId ?? null;
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $db = \Config\Database::connect();
        $isAdmin = $db->table('user_condominium_roles ucr')->where('ucr.user_id', $userId)->where('ucr.condominium_id', $tenantId)->countAllResults() > 0;
        
        // O logica particular: Si quieres que los residentes suban archivos a su propia unidad, 
        // eso normalmente va en el comprobante. Los Documentos Comunitarios suelen ser read-only para residentes.
        if (!$isAdmin) return $this->respondError('Los residentes no tienen permisos para subir archivos globales', 403);

        $documentModel = new TenantDocumentModel();
        $configsJson = $this->request->getPost('configs');
        $configs = json_decode((string)$configsJson, true) ?? [];
        $files = $this->request->getFiles();

        if (empty($files) || !isset($files['files'])) {
            return $this->respondError('No se encontraron archivos validos enviados', 400);
        }
        
        $uploadMap = is_array($files['files']) ? $files['files'] : [$files['files']];
        $i = 0; $uploaded = 0;

        foreach ($uploadMap as $file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $newName = $file->getRandomName();
                $sizeBytes = $file->getSize();
                $mimeType = $file->getMimeType();
                $clientName = $file->getClientName();

                if (!is_dir(WRITEPATH . 'uploads/documents')) {
                    mkdir(WRITEPATH . 'uploads/documents', 0755, true);
                }
                $file->move(WRITEPATH . 'uploads/documents', $newName);
                
                $fileConfig = $configs[$i] ?? [];
                
                $documentModel->insert([
                    'condominium_id' => $tenantId,
                    'parent_id' => $this->request->getPost('parent_id') ?: null,
                    'type' => 'file',
                    'name' => trim((string)($fileConfig['name'] ?? $clientName)),
                    'path' => $newName,
                    'category' => $fileConfig['category'] ?? 'General',
                    'access_level' => $fileConfig['access'] ?? 'Todos',
                    'size_bytes' => $sizeBytes,
                    'mime_type' => $mimeType,
                    'uploaded_by' => $userId
                ]);
                $uploaded++;
            }
            $i++;
        }

        return $this->respondSuccess(['message' => "$uploaded archivos subidos exitosamente"]);
    }

    /**
     * POST /api/v1/resident/documents/rename/{id}
     */
    public function renameDocument(int $id)
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $name = $this->request->getPost('name');
        
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        
        if (!$doc || $doc['condominium_id'] != $tenantId) return $this->respondError('No encontrado', 404);
        
        $documentModel->update($id, ['name' => trim((string)$name)]);
        return $this->respondSuccess(['message' => 'Renombrado con exito']);
    }

    /**
     * POST /api/v1/resident/documents/delete/{id}
     */
    public function deleteDocument(int $id)
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        $userId = $this->request->userId ?? null;
        
        $db = \Config\Database::connect();
        $isAdmin = $db->table('user_condominium_roles ucr')->where('ucr.user_id', $userId)->where('ucr.condominium_id', $tenantId)->countAllResults() > 0;
        
        if (!$isAdmin) return $this->respondError('No autorizado para eliminar archivos globales', 403);
        
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        
        if (!$doc || $doc['condominium_id'] != $tenantId) return $this->respondError('No encontrado', 404);
        
        $documentModel->delete($id);
        return $this->respondSuccess(['message' => 'Elemento eliminado']);
    }

    /**
     * POST /api/v1/resident/documents/toggle-star/{id}
     */
    public function toggleStar(int $id)
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        
        $documentModel = new TenantDocumentModel();
        $doc = $documentModel->find($id);
        
        if (!$doc || $doc['condominium_id'] != $tenantId) return $this->respondError('No encontrado', 404);
        
        $newStatus = $doc['is_starred'] == 1 ? 0 : 1;
        $documentModel->update($id, ['is_starred' => $newStatus]);
        return $this->respondSuccess(['message' => 'Favorito actualizado', 'is_starred' => $newStatus]);
    }

    /**
     * Tracker
     */
    private function trackDocumentAction($tenantId, $docId, $action)
    {
        $db = \Config\Database::connect();
        $db->table('document_views')->insert([
            'condominium_id' => $tenantId,
            'document_id'    => $docId,
            'user_id'        => $this->request->userId ?? null,
            'action'         => $action,
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
    }
}
