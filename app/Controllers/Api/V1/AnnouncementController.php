<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\AnnouncementModel;
use App\Models\Tenant\AnnouncementAttachmentModel;
use App\Models\Tenant\AnnouncementLikeModel;
use App\Models\Tenant\AnnouncementCommentModel;

/**
 * AnnouncementController (API V1)
 *
 * Consultas y operaciones del muro de avisos desde la PWA / Flutter.
 */
class AnnouncementController extends ResourceController
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
     * Verifica si el usuario autenticado tiene rol administrativo en el condominio actual.
     */
    private function isAdmin(?int $userId = null): bool
    {
        if (!$userId) return false;
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        if (!$tenantId) return false;

        $db = \Config\Database::connect();
        $pivot = $db->table('user_condominium_roles')
            ->select('roles.name as role_name')
            ->join('roles', 'roles.id = user_condominium_roles.role_id', 'left')
            ->where('user_id', $userId)
            ->where('condominium_id', $tenantId)
            ->get()->getRow();

        if (!$pivot) return false;
        return in_array(strtolower($pivot->role_name ?? ''), ['admin', 'super_admin', 'owner']);
    }

    /**
     * GET /api/v1/announcements
     * Listar avisos paginados con Eager Loading simulado para mitigar consultas N+1
     */
    public function index()
    {
        $page  = (int) $this->request->getGet('page') ?: 1;
        $limit = (int) $this->request->getGet('limit') ?: 10;
        $offset = ($page - 1) * $limit;
        
        $userId = $this->request->userId ?? null; // Seteado por ApiAuthFilter

        $model = new AnnouncementModel();
        
        // 1. Obtener la página actual
        $announcements = $model
            ->select('announcements.*, users.first_name, users.last_name, users.avatar as author_avatar')
            ->join('users', 'users.id = announcements.created_by', 'left')
            ->where('announcements.is_active', 1)
            ->orderBy('announcements.created_at', 'DESC')
            ->findAll($limit, $offset);

        if (empty($announcements)) {
            return $this->respondSuccess(['announcements' => [], 'has_more' => false, 'total' => 0]);
        }

        // Extraer IDs para eager loading
        $ids = array_column($announcements, 'id');

        // 2. Fetch de Attachments en masa
        $attachModel = new AnnouncementAttachmentModel();
        $attachmentsRaw = $attachModel->whereIn('announcement_id', $ids)->findAll();
        $attachmentsMapped = [];
        foreach ($attachmentsRaw as $att) {
            $attachmentsMapped[$att['announcement_id']][] = $att;
        }

        // 3. Fetch de Likes (conteo masivo agrupado y likes del usuario)
        $likeModel = new AnnouncementLikeModel();
        $likesRaw = $likeModel->select('announcement_id, COUNT(id) as total')
                              ->whereIn('announcement_id', $ids)
                              ->groupBy('announcement_id')
                              ->findAll();
        $likesCountMapped = [];
        foreach ($likesRaw as $lr) {
            $likesCountMapped[$lr['announcement_id']] = (int) $lr['total'];
        }

        $userLikesMapped = [];
        if ($userId) {
            $userLikesRaw = $likeModel->select('announcement_id')
                                      ->whereIn('announcement_id', $ids)
                                      ->where('user_id', $userId)
                                      ->findAll();
            $userLikesMapped = array_column($userLikesRaw, 'announcement_id');
        }

        // 4. Fetch de Comments (conteo masivo agrupado)
        $commentModel = new AnnouncementCommentModel();
        $commentsRaw = $commentModel->select('announcement_id, COUNT(id) as total')
                                    ->whereIn('announcement_id', $ids)
                                    ->groupBy('announcement_id')
                                    ->findAll();
        $commentsCountMapped = [];
        foreach ($commentsRaw as $cr) {
            $commentsCountMapped[$cr['announcement_id']] = (int) $cr['total'];
        }

        // Obtener configuración global de comentarios ANTES de enriquecer
        $condoModel = new \App\Models\Tenant\CondominiumModel();
        $condo = $condoModel->first();
        $globalAllowComments = (int)($condo['allow_post_comments'] ?? 1);

        // Enriquecer el arreglo en memoria
        foreach ($announcements as &$a) {
            $aId = $a['id'];
            $a['id'] = (int)$a['id'];
            $a['user_id'] = (int)($a['created_by'] ?? 0); // Alias para que Flutter identifique al autor
            $a['attachments'] = $attachmentsMapped[$aId] ?? [];
            $a['like_count'] = (int)($likesCountMapped[$aId] ?? 0);
            $a['comment_count'] = (int)($commentsCountMapped[$aId] ?? 0);
            $a['user_liked'] = in_array($aId, $userLikesMapped);
            
            // El flag global del condominio prevalece sobre la configuración por anuncio
            $a['allow_comments'] = $globalAllowComments ? (int)($a['allow_comments'] ?? 1) : 0;
            
            // Thumbnail / cover para feeds rápidos
            $a['cover_file'] = null;
            foreach ($a['attachments'] as $att) {
                if (in_array($att['file_type'], ['image', 'video'])) {
                    $a['cover_file'] = $att['file_name'];
                    break;
                }
            }
        }

        // Checar si hay más resultados para el infinite scroll
        $totalResults = $model->where('is_active', 1)->countAllResults();
        $hasMore = ($offset + count($announcements)) < $totalResults;

        $allowResidentPost = (int)($condo['allow_resident_posts'] ?? 0);
        $userIsAdmin = $this->isAdmin($userId);

        return $this->respondSuccess([
            'announcements'       => $announcements, 
            'has_more'            => $hasMore,
            'total'               => $totalResults,
            'allow_resident_post' => $allowResidentPost,
            'allow_post_comments' => $globalAllowComments,
            'user_is_admin'       => $userIsAdmin,
        ]);
    }

    /**
     * GET /api/v1/announcements/(:num)
     * Detalle completo (incluye comentarios completos)
     */
    public function detail($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');

        $userId = $this->request->userId ?? null;
        $model = new AnnouncementModel();
        
        $ann = $model
            ->select('announcements.*, users.first_name, users.last_name, users.avatar as author_avatar')
            ->join('users', 'users.id = announcements.created_by', 'left')
            ->find($id);

        if (!$ann) return $this->respondError('Anuncio no encontrado', 404);

        $ann['id'] = (int)$ann['id'];
        $ann['user_id'] = (int)($ann['created_by'] ?? 0); // Alias para ownership check en Flutter

        // Incrementar vista solo si entra al detalle
        $model->set('view_count', 'view_count + 1', false)->where('id', $id)->update();
        $ann['view_count'] = ((int)($ann['view_count'] ?? 0)) + 1;

        $attachModel = new AnnouncementAttachmentModel();
        $ann['attachments'] = $attachModel->where('announcement_id', $id)->orderBy('id', 'ASC')->findAll();

        $likeModel = new AnnouncementLikeModel();
        
        $likesList = $likeModel
            ->select('users.first_name, users.last_name, users.avatar as author_avatar')
            ->join('users', 'users.id = announcement_likes.user_id', 'left')
            ->where('announcement_id', $id)
            ->findAll();

        $ann['like_count'] = count($likesList);
        $ann['user_liked'] = $likeModel->where('announcement_id', $id)->where('user_id', $userId)->countAllResults() > 0;
        $ann['likes'] = $likesList;

        $commentModel = new AnnouncementCommentModel();
        $comments = $commentModel
            ->select('announcement_comments.*, users.first_name, users.last_name, users.avatar as author_avatar')
            ->join('users', 'users.id = announcement_comments.user_id', 'left')
            ->where('announcement_id', $id)
            ->orderBy('announcement_comments.created_at', 'ASC')
            ->findAll();
            
        foreach ($comments as &$c) {
            if (!empty($c['created_at'])) {
                $c['time_ago'] = \CodeIgniter\I18n\Time::parse($c['created_at'])->humanize();
            } else {
                $c['time_ago'] = 'hace poco';
            }
        }
            
        // Aplicar configuración global de comentarios
        $condoModel = new \App\Models\Tenant\CondominiumModel();
        $condo = $condoModel->first();
        $globalAllowComments = (int)($condo['allow_post_comments'] ?? 1);
        $ann['allow_comments'] = $globalAllowComments ? (int)($ann['allow_comments'] ?? 1) : 0;

        if (!$globalAllowComments) {
            // Si comentarios están deshabilitados globalmente, ocultar los existentes
            $ann['comments'] = [];
            $ann['comment_count'] = 0;
        } else {
            $ann['comments'] = $comments;
            $ann['comment_count'] = count($comments);
        }

        $ann['allow_post_comments'] = $globalAllowComments;

        return $this->respondSuccess($ann);
    }

    /**
     * POST /api/v1/announcements/(:num)/like
     */
    public function toggleLike($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $likeModel = new AnnouncementLikeModel();
        $existing = $likeModel->where('announcement_id', $id)->where('user_id', $userId)->first();
        
        if ($existing) {
            $likeModel->delete($existing['id']);
            $liked = false;
        } else {
            $likeModel->insert([
                'announcement_id' => $id,
                'user_id'         => $userId,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
            $liked = true;
        }

        $count = $likeModel->where('announcement_id', $id)->countAllResults();
        return $this->respondSuccess(['liked' => $liked, 'count' => $count]);
    }

    /**
     * POST /api/v1/announcements/(:num)/comments
     */
    public function addComment($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');
        $userId = $this->request->userId ?? null;

        // Verificar que los comentarios estén habilitados globalmente
        $condoModel = new \App\Models\Tenant\CondominiumModel();
        $condo = $condoModel->first();
        if (!$condo || !(int)($condo['allow_post_comments'] ?? 1)) {
            return $this->respondError('Los comentarios están deshabilitados por el administrador', 403);
        }
        
        $json = $this->request->getJSON(true);
        $content = '';
        if (is_array($json) && isset($json['content'])) {
            $content = trim((string)$json['content']);
        } else {
            $content = trim((string)$this->request->getPost('content'));
        }
        
        if ($content === '') {
            return $this->respondError('Comentario vacío');
        }

        $commentModel = new AnnouncementCommentModel();
        $commentId = $commentModel->insert([
            'announcement_id' => $id,
            'user_id'         => $userId,
            'content'         => $content,
        ]);

        $count = $commentModel->where('announcement_id', $id)->countAllResults();
        
        // Retornar el detalle del comentario recién creado
        $newComment = $commentModel
            ->select('announcement_comments.*, users.first_name, users.last_name, users.avatar as author_avatar')
            ->join('users', 'users.id = announcement_comments.user_id', 'left')
            ->find($commentId);
            
        if ($newComment && !empty($newComment['created_at'])) {
            $newComment['time_ago'] = \CodeIgniter\I18n\Time::parse($newComment['created_at'])->humanize();
        } else if ($newComment) {
            $newComment['time_ago'] = 'hace poco';
        }
            
        return $this->respondSuccess([
            'message' => 'Comentario publicado', 
            'count'   => $count, 
            'comment' => $newComment
        ]);
    }

    /**
     * POST o DELETE /api/v1/announcements/comments/(:num)/delete
     */
    public function deleteComment($commentId = null)
    {
        if (!$commentId) return $this->respondError('ID no proporcionado');
        $userId = $this->request->userId ?? null;

        $commentModel = new AnnouncementCommentModel();
        $comment = $commentModel->find($commentId);
        
        if (!$comment) return $this->respondError('Comentario no encontrado', 404);
        
        if ($comment['user_id'] != $userId && !$this->isAdmin($userId)) {
            return $this->respondError('No tienes permiso para eliminar este comentario', 403);
        }

        $commentModel->delete($commentId);
        return $this->respondSuccess(['message' => 'Comentario eliminado']);
    }

    /**
     * PUT /api/v1/announcements/(:num)
     * Editar publicación propia del residente
     */
    public function update($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $model = new AnnouncementModel();
        $ann = $model->find($id);
        if (!$ann) return $this->respondError('Anuncio no encontrado', 404);

        // Solo el autor o un admin pueden editar la publicación
        if ((int)$ann['created_by'] !== (int)$userId && !$this->isAdmin($userId)) {
            return $this->respondError('No tienes permiso para editar esta publicación', 403);
        }

        $json = $this->request->getJSON(true);
        $content = trim((string)($json['content'] ?? ''));
        if ($content === '') {
            return $this->respondError('El contenido es obligatorio');
        }

        $model->update($id, [
            'title'   => mb_substr(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0, 120),
            'content' => $content,
        ]);

        return $this->respondSuccess(['message' => 'Publicación actualizada']);
    }

    /**
     * DELETE /api/v1/announcements/(:num)
     * Eliminar publicación propia del residente
     */
    public function destroy($id = null)
    {
        if (!$id) return $this->respondError('ID no proporcionado');
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        $model = new AnnouncementModel();
        $ann = $model->find($id);
        if (!$ann) return $this->respondError('Anuncio no encontrado', 404);

        // Solo el autor o un admin pueden eliminar la publicación
        if ((int)$ann['created_by'] !== (int)$userId && !$this->isAdmin($userId)) {
            return $this->respondError('No tienes permiso para eliminar esta publicación', 403);
        }

        // Eliminar attachments del disco
        $attachModel = new AnnouncementAttachmentModel();
        $attachments = $attachModel->where('announcement_id', $id)->findAll();
        $uploadDir = WRITEPATH . 'uploads/announcements/';
        foreach ($attachments as $att) {
            $filePath = $uploadDir . $att['file_name'];
            if (is_file($filePath)) {
                unlink($filePath);
            }
        }
        $attachModel->where('announcement_id', $id)->delete();

        // Eliminar likes y comentarios asociados
        $likeModel = new AnnouncementLikeModel();
        $likeModel->where('announcement_id', $id)->delete();

        $commentModel = new AnnouncementCommentModel();
        $commentModel->where('announcement_id', $id)->delete();

        // Eliminar el anuncio
        $model->delete($id);

        return $this->respondSuccess(['message' => 'Publicación eliminada']);
    }

    /**
     * POST /api/v1/announcements
     * Crear publicación desde la app del residente
     */
    public function store()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No Autorizado', 401);

        // Los administradores siempre pueden publicar; residentes solo si el condominio lo permite
        if (!$this->isAdmin($userId)) {
            $condoModel = new \App\Models\Tenant\CondominiumModel();
            $condo = $condoModel->first();
            if (!$condo || !(int)($condo['allow_resident_posts'] ?? 0)) {
                return $this->respondError('No tienes permiso para crear publicaciones', 403);
            }
        }

        $content = trim((string)($this->request->getPost('content') ?? ''));
        if ($content === '') {
            return $this->respondError('El contenido es obligatorio');
        }

        $allowComments = (int)($this->request->getPost('allow_comments') ?? 1);

        $model = new AnnouncementModel();
        $announcementId = $model->insert([
            'created_by'   => $userId,
            'title'        => mb_substr(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0, 120),
            'content'      => $content,
            'type'         => 'resident_post',
            'category'     => 'general',
            'is_active'    => 1,
            'send_email'   => 0,
            'view_count'   => 0,
        ]);

        if (!$announcementId) {
            return $this->respondError('Error al crear la publicación', 500);
        }

        // Procesar archivos adjuntos (imágenes, videos, documentos/PDFs)
        $this->handleApiUploads($announcementId);

        // Disparar notificaciones push a todos los residentes del condominio
        $category = trim((string)($this->request->getPost('category') ?? 'general'));
        $this->dispatchAnnouncementPush($announcementId, $content, $category, (int)$userId);

        return $this->respondSuccess([
            'message' => 'Publicación creada exitosamente',
            'id'      => (int)$announcementId,
        ]);
    }

    /**
     * Procesar uploads multipart desde la API móvil.
     * Acepta images[], videos[], documents[], files[] y attachments[] como campos de archivo.
     * El campo genérico attachments[] y files[] detectan el tipo automáticamente por MIME.
     */
    private function handleApiUploads(int $announcementId): void
    {
        $uploadDir = WRITEPATH . 'uploads/announcements/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $attachModel = new AnnouncementAttachmentModel();
        $files = $this->request->getFiles();

        // Procesar imágenes
        if (!empty($files['images'])) {
            foreach ($files['images'] as $file) {
                if (!$file->isValid() || $file->hasMoved()) continue;

                $originalName = $file->getClientName();
                $ext  = $file->getClientExtension();
                $mime = $file->getClientMimeType();
                $newName = 'ann_' . uniqid() . '_' . time() . '.' . $ext;
                $file->move($uploadDir, $newName);

                // Comprimir imagen en el servidor
                $this->compressImage($uploadDir . $newName, $mime);
                $size = filesize($uploadDir . $newName);

                $attachModel->insert([
                    'announcement_id' => $announcementId,
                    'file_name'       => $newName,
                    'original_name'   => $originalName,
                    'display_name'    => pathinfo($originalName, PATHINFO_FILENAME),
                    'file_type'       => 'image',
                    'file_size'       => $size,
                    'mime_type'       => $mime,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Procesar videos (máximo 50MB validado)
        if (!empty($files['videos'])) {
            foreach ($files['videos'] as $file) {
                if (!$file->isValid() || $file->hasMoved()) continue;

                $size = $file->getSize();
                // Rechazar videos mayores a 50 MB
                if ($size > 50 * 1024 * 1024) continue;

                $originalName = $file->getClientName();
                $ext  = $file->getClientExtension();
                $mime = $file->getClientMimeType();
                $newName = 'ann_' . uniqid() . '_' . time() . '.' . $ext;
                $file->move($uploadDir, $newName);

                $attachModel->insert([
                    'announcement_id' => $announcementId,
                    'file_name'       => $newName,
                    'original_name'   => $originalName,
                    'display_name'    => pathinfo($originalName, PATHINFO_FILENAME),
                    'file_type'       => 'video',
                    'file_size'       => $size,
                    'mime_type'       => $mime,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Procesar documentos/PDFs
        if (!empty($files['documents'])) {
            foreach ($files['documents'] as $file) {
                if (!$file->isValid() || $file->hasMoved()) continue;

                $originalName = $file->getClientName();
                $ext  = $file->getClientExtension();
                $mime = $file->getClientMimeType();
                $size = $file->getSize();
                // Rechazar documentos mayores a 20 MB
                if ($size > 20 * 1024 * 1024) continue;

                $newName = 'ann_' . uniqid() . '_' . time() . '.' . $ext;
                $file->move($uploadDir, $newName);

                $attachModel->insert([
                    'announcement_id' => $announcementId,
                    'file_name'       => $newName,
                    'original_name'   => $originalName,
                    'display_name'    => pathinfo($originalName, PATHINFO_FILENAME),
                    'file_type'       => ($mime === 'application/pdf') ? 'pdf' : 'document',
                    'file_size'       => $size,
                    'mime_type'       => $mime,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Campo genérico 'attachments[]' — detecta tipo por MIME (compatibilidad con admin)
        if (!empty($files['attachments'])) {
            foreach ($files['attachments'] as $file) {
                if (!$file->isValid() || $file->hasMoved()) continue;

                $originalName = $file->getClientName();
                $ext  = $file->getClientExtension();
                $mime = $file->getClientMimeType();
                $size = $file->getSize();

                // Determinar tipo por MIME
                $fileType = 'image';
                if (str_starts_with($mime, 'video/')) {
                    $fileType = 'video';
                    if ($size > 50 * 1024 * 1024) continue;
                } elseif ($mime === 'application/pdf') {
                    $fileType = 'pdf';
                    if ($size > 20 * 1024 * 1024) continue;
                } elseif (!str_starts_with($mime, 'image/')) {
                    $fileType = 'document';
                    if ($size > 20 * 1024 * 1024) continue;
                }

                $newName = 'ann_' . uniqid() . '_' . time() . '.' . $ext;
                $file->move($uploadDir, $newName);

                // Comprimir solo si es imagen
                if ($fileType === 'image') {
                    $this->compressImage($uploadDir . $newName, $mime);
                    $size = filesize($uploadDir . $newName);
                }

                $attachModel->insert([
                    'announcement_id' => $announcementId,
                    'file_name'       => $newName,
                    'original_name'   => $originalName,
                    'display_name'    => pathinfo($originalName, PATHINFO_FILENAME),
                    'file_type'       => $fileType,
                    'file_size'       => $size,
                    'mime_type'       => $mime,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }
        }

        // Campo 'files[]' — usado por Flutter para PDFs (compatibilidad)
        if (!empty($files['files'])) {
            foreach ($files['files'] as $file) {
                if (!$file->isValid() || $file->hasMoved()) continue;

                $originalName = $file->getClientName();
                $ext  = $file->getClientExtension();
                $mime = $file->getClientMimeType();
                $size = $file->getSize();

                // Determinar tipo por MIME
                $fileType = 'image';
                if (str_starts_with($mime, 'video/')) {
                    $fileType = 'video';
                    if ($size > 50 * 1024 * 1024) continue;
                } elseif ($mime === 'application/pdf') {
                    $fileType = 'pdf';
                    if ($size > 20 * 1024 * 1024) continue;
                } elseif (!str_starts_with($mime, 'image/')) {
                    $fileType = 'document';
                    if ($size > 20 * 1024 * 1024) continue;
                }

                $newName = 'ann_' . uniqid() . '_' . time() . '.' . $ext;
                $file->move($uploadDir, $newName);

                if ($fileType === 'image') {
                    $this->compressImage($uploadDir . $newName, $mime);
                    $size = filesize($uploadDir . $newName);
                }

                $attachModel->insert([
                    'announcement_id' => $announcementId,
                    'file_name'       => $newName,
                    'original_name'   => $originalName,
                    'display_name'    => pathinfo($originalName, PATHINFO_FILENAME),
                    'file_type'       => $fileType,
                    'file_size'       => $size,
                    'mime_type'       => $mime,
                    'created_at'      => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    /**
     * Compress and resize image to reduce file weight.
     * Max dimension: 1200px. JPEG quality: 75%.
     */
    private function compressImage(string $path, string $mime): void
    {
        $maxDim = 1200;
        $quality = 75;

        $img = null;
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $img = @imagecreatefromjpeg($path);
                break;
            case 'image/png':
                $img = @imagecreatefrompng($path);
                break;
            case 'image/webp':
                $img = @imagecreatefromwebp($path);
                break;
            default:
                return;
        }

        if (!$img) return;

        $w = imagesx($img);
        $h = imagesy($img);

        if ($w > $maxDim || $h > $maxDim) {
            if ($w >= $h) {
                $newW = $maxDim;
                $newH = (int) round($h * ($maxDim / $w));
            } else {
                $newH = $maxDim;
                $newW = (int) round($w * ($maxDim / $h));
            }
            $resized = imagecreatetruecolor($newW, $newH);
            if ($mime === 'image/png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }
            imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($img);
            $img = $resized;
        }

        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($img, $path, $quality);
                break;
            case 'image/png':
                imagepng($img, $path, 6);
                break;
            case 'image/webp':
                imagewebp($img, $path, $quality);
                break;
        }
        imagedestroy($img);
    }

    /**
     * GET /api/v1/announcements/file/(:any)
     * Servir archivo adjunto de manera segura
     */
    public function serveFile($filename = null)
    {
        if (!$filename) {
            return $this->response->setStatusCode(404);
        }
        
        $path = WRITEPATH . 'uploads/announcements/' . $filename;
        if (!is_file($path)) {
            // Fallback for Flutter app expecting author avatars in this route
            $path = WRITEPATH . 'uploads/avatars/' . $filename;
            if (!is_file($path)) {
                // Check legacy subdirectory structure /avatars/{user_id}/{filename}
                $legacyPaths = glob(WRITEPATH . 'uploads/avatars/*/' . $filename);
                if (!empty($legacyPaths) && is_file($legacyPaths[0])) {
                    $path = $legacyPaths[0];
                } else {
                    return $this->response->setStatusCode(404);
                }
            }
        }

        // El modo correcto de servir binarios en CI4 y no corromper PDF es usando ->download()->inline()
        return $this->response->download($path, null)->inline();
    }

    /**
     * Crea registros en la tabla notifications para cada residente
     * y envía push notification masiva vía FCM HTTP v1.
     */
    private function dispatchAnnouncementPush(int $announcementId, string $content, string $category, int $excludeUserId = 0): void
    {
        log_message('info', '[PUSH-API] ========== DISPATCH START ==========');
        log_message('info', "[PUSH-API] Announcement #{$announcementId}, category={$category}");

        try {
            $condominiumId = \App\Services\TenantService::getInstance()->getTenantId();
            log_message('info', "[PUSH-API] Condominium ID: {$condominiumId}");

            $db = \Config\Database::connect();

            // Obtener todos los user_id de residentes
            $residents = $db->table('residents')
                ->select('user_id')
                ->where('condominium_id', $condominiumId)
                ->where('user_id IS NOT NULL')
                ->get()->getResultArray();

            log_message('info', '[PUSH-API] Residents found: ' . count($residents));

            if (empty($residents)) {
                log_message('warning', '[PUSH-API] No residents in condominium - aborting');
                return;
            }

            // Obtener nombre del condominio para el título
            $condoRow = $db->table('condominiums')->select('name')->where('id', $condominiumId)->get()->getRowArray();
            $condoName = $condoRow['name'] ?? 'Mi Condominio';

            // Título según categoría + nombre del condominio
            $categoryLabels = [
                'general'       => ["\xF0\x9F\x93\xA2", 'Nuevo Aviso'],
                'mantenimiento' => ["\xF0\x9F\x94\xA7", 'Aviso de Mantenimiento'],
                'urgente'       => ["\xF0\x9F\x9A\xA8", 'Aviso Urgente'],
                'evento'        => ["\xF0\x9F\x93\x85", 'Nuevo Evento'],
            ];
            $catInfo = $categoryLabels[$category] ?? $categoryLabels['general'];
            $pushTitle = "{$catInfo[0]} {$catInfo[1]} - {$condoName}";
            $pushBody  = mb_substr(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0, 200);

            log_message('info', "[PUSH-API] Title: {$pushTitle}");
            log_message('info', "[PUSH-API] Body: {$pushBody}");

            // Insertar notificaciones en DB para pantalla "Avisos"
            $now = date('Y-m-d H:i:s');
            $notifType = ($category === 'urgente') ? 'urgent' : 'announcement';
            $insertedCount = 0;

            foreach ($residents as $r) {
                // No notificar al propio autor de la publicación
                if ((int)$r['user_id'] === $excludeUserId) continue;

                $inserted = $db->table('notifications')->insert([
                    'condominium_id' => $condominiumId,
                    'user_id'        => $r['user_id'],
                    'type'           => $notifType,
                    'title'          => $pushTitle,
                    'body'           => $pushBody,
                    'data'           => json_encode([
                        'announcement_id' => $announcementId,
                        'category'        => $category,
                    ]),
                    'read_at'    => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
                if ($inserted) $insertedCount++;
            }

            log_message('info', "[PUSH-API] Notifications inserted in DB: {$insertedCount}/" . count($residents));

            // Verificar que hay tokens FCM antes de enviar
            $tokenCount = $db->table('device_push_subscriptions')
                ->where('condominium_id', $condominiumId)
                ->where('fcm_token IS NOT NULL')
                ->where('fcm_token !=', '')
                ->countAllResults();

            log_message('info', "[PUSH-API] FCM tokens available: {$tokenCount}");

            if ($tokenCount === 0) {
                log_message('warning', '[PUSH-API] No FCM tokens found - push NOT sent (but DB notifications saved)');
                return;
            }

            // Enviar push FCM
            $pushService = new \App\Services\Notifications\PushNotificationService();
            $result = $pushService->sendToCondominium($condominiumId, $pushTitle, $pushBody, [
                'type'            => 'announcement',
                'announcement_id' => (string) $announcementId,
                'category'        => $category,
                'click_action'    => 'FLUTTER_NOTIFICATION_CLICK',
            ]);

            log_message('info', '[PUSH-API] FCM send result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            log_message('info', '[PUSH-API] ========== DISPATCH END ==========');

        } catch (\Throwable $e) {
            log_message('error', '[PUSH-API] Exception: ' . $e->getMessage());
            log_message('error', '[PUSH-API] Stack: ' . $e->getTraceAsString());
        }
    }
}
