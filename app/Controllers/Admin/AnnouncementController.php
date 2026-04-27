<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\AnnouncementModel;
use App\Models\Tenant\AnnouncementAttachmentModel;
use App\Models\Tenant\AnnouncementLikeModel;
use App\Models\Tenant\AnnouncementCommentModel;

class AnnouncementController extends BaseController
{
    private function bootstrapTenant(): void
    {
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) {
            \App\Services\TenantService::getInstance()->setTenantId((int) $demoCondo['id']);
        }
    }

    /* ─── View ─── */

    public function indexView()
    {
        $this->bootstrapTenant();

        $model = new AnnouncementModel();
        $announcements = $model
            ->select('announcements.*, users.first_name, users.last_name')
            ->join('users', 'users.id = announcements.created_by', 'left')
            ->where('announcements.is_active', 1)
            ->orderBy('announcements.created_at', 'DESC')
            ->findAll();

        // Enrich with counts
        $likeModel = new AnnouncementLikeModel();
        $commentModel = new AnnouncementCommentModel();
        $attachModel = new AnnouncementAttachmentModel();

        foreach ($announcements as &$a) {
            $a['like_count']    = $likeModel->where('announcement_id', $a['id'])->countAllResults();
            $a['comment_count'] = $commentModel->where('announcement_id', $a['id'])->countAllResults();
            $a['attach_count']  = $attachModel->where('announcement_id', $a['id'])->countAllResults();
            // First image for cover
            $firstImg = $attachModel->where('announcement_id', $a['id'])
                ->whereIn('file_type', ['image'])
                ->orderBy('id', 'ASC')->first();
            $a['cover_file'] = $firstImg ? $firstImg['file_name'] : null;
        }

        return view('admin/announcements', ['announcements' => $announcements]);
    }

    /* ─── Create ─── */

    public function create()
    {
        $this->bootstrapTenant();

        $content = trim($this->request->getPost('content') ?? '');
        if ($content === '') {
            return $this->response->setJSON(['status' => 400, 'error' => 'El mensaje es obligatorio']);
        }

        $category    = $this->request->getPost('category') ?? 'general';
        $sendEmail   = (int) ($this->request->getPost('send_email') ?? 0);
        $emailTarget = $this->request->getPost('email_target') ?? null;

        $model = new AnnouncementModel();
        $announcementId = $model->insert([
            'created_by'   => session()->get('user_id') ?? 4,
            'title'        => mb_substr(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0, 120),
            'content'      => $content,
            'type'         => 'news',
            'category'     => $category,
            'is_active'    => 1,
            'send_email'   => $sendEmail,
            'email_target' => $sendEmail ? $emailTarget : null,
            'view_count'   => 0,
        ]);

        if (!$announcementId) {
            return $this->response->setJSON(['status' => 500, 'error' => 'Error al crear anuncio']);
        }

        // Handle file uploads
        $this->handleUploads($announcementId);

        // 🔔 Disparar notificaciones push a todos los residentes del condominio
        $this->dispatchAnnouncementPush($announcementId, $content, $category);

        return $this->response->setJSON([
            'status'  => 201,
            'message' => 'Comunicación publicada exitosamente',
            'id'      => $announcementId,
        ]);
    }

    /**
     * Crea registros en la tabla notifications para cada residente
     * y envía push notification masiva vía FCM HTTP v1.
     */
    private function dispatchAnnouncementPush(int $announcementId, string $content, string $category): void
    {
        log_message('info', '[PUSH] ========== DISPATCH START ==========');
        log_message('info', "[PUSH] Announcement #{$announcementId}, category={$category}");

        try {
            $condominiumId = \App\Services\TenantService::getInstance()->getTenantId();
            log_message('info', "[PUSH] Condominium ID: {$condominiumId}");

            $db = \Config\Database::connect();

            // Obtener todos los user_id de residentes
            $residents = $db->table('residents')
                ->select('user_id')
                ->where('condominium_id', $condominiumId)
                ->where('user_id IS NOT NULL')
                ->get()->getResultArray();

            log_message('info', '[PUSH] Residents found: ' . count($residents));

            if (empty($residents)) {
                log_message('warning', '[PUSH] No residents in condominium — aborting');
                return;
            }

            // Obtener nombre del condominio para el título
            $condoRow = $db->table('condominiums')->select('name')->where('id', $condominiumId)->get()->getRowArray();
            $condoName = $condoRow['name'] ?? 'Mi Condominio';

            // Título según categoría + nombre del condominio
            $categoryLabels = [
                'general'       => ['📢', 'Nuevo Aviso'],
                'mantenimiento' => ['🔧', 'Aviso de Mantenimiento'],
                'urgente'       => ['🚨', 'Aviso Urgente'],
                'evento'        => ['📅', 'Nuevo Evento'],
            ];
            $catInfo = $categoryLabels[$category] ?? $categoryLabels['general'];
            $pushTitle = "{$catInfo[0]} {$catInfo[1]} · {$condoName}";
            $pushBody  = mb_substr(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0, 200);

            log_message('info', "[PUSH] Title: {$pushTitle}");
            log_message('info', "[PUSH] Body: {$pushBody}");

            // Insertar notificaciones en DB para pantalla "Avisos"
            $now = date('Y-m-d H:i:s');
            $notifType = ($category === 'urgente') ? 'urgent' : 'announcement';
            $insertedCount = 0;

            foreach ($residents as $r) {
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

            log_message('info', "[PUSH] ✅ Notifications inserted in DB: {$insertedCount}/" . count($residents));

            // Verificar que hay tokens FCM antes de enviar
            $tokenCount = $db->table('device_push_subscriptions')
                ->where('condominium_id', $condominiumId)
                ->where('fcm_token IS NOT NULL')
                ->where('fcm_token !=', '')
                ->countAllResults();

            log_message('info', "[PUSH] FCM tokens available: {$tokenCount}");

            if ($tokenCount === 0) {
                log_message('warning', '[PUSH] ⚠️ No FCM tokens found — push NOT sent (but DB notifications saved)');
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

            log_message('info', '[PUSH] FCM send result: ' . ($result ? 'SUCCESS' : 'FAILED'));
            log_message('info', '[PUSH] ========== DISPATCH END ==========');

        } catch (\Throwable $e) {
            log_message('error', '[PUSH] ❌ Exception: ' . $e->getMessage());
            log_message('error', '[PUSH] Stack: ' . $e->getTraceAsString());
        }
    }

    /* ─── Update ─── */

    public function update($id = null)
    {
        $this->bootstrapTenant();
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $model = new AnnouncementModel();
        $ann   = $model->find($id);
        if (!$ann) return $this->response->setJSON(['status' => 404, 'error' => 'Anuncio no encontrado']);

        $content = trim($this->request->getPost('content') ?? '');
        if ($content === '') {
            return $this->response->setJSON(['status' => 400, 'error' => 'El mensaje es obligatorio']);
        }

        $model->update($id, [
            'title'        => mb_substr(html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8'), 0, 120),
            'content'      => $content,
            'category'     => $this->request->getPost('category') ?? $ann['category'],
            'send_email'   => $this->request->getPost('send_email') ?? $ann['send_email'],
            'email_target' => $this->request->getPost('email_target') ?? $ann['email_target'],
        ]);

        // Handle new file uploads if any
        $this->handleUploads($id);

        return $this->response->setJSON(['status' => 200, 'message' => 'Comunicación actualizada']);
    }

    /* ─── Delete ─── */

    public function delete($id = null)
    {
        $this->bootstrapTenant();
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $model = new AnnouncementModel();
        $model->delete($id);

        return $this->response->setJSON(['status' => 200, 'message' => 'Comunicación eliminada']);
    }

    /* ─── Get Detail ─── */

    public function getAnnouncement($id = null)
    {
        $this->bootstrapTenant();
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $model = new AnnouncementModel();
        $ann = $model
            ->select('announcements.*, users.first_name, users.last_name')
            ->join('users', 'users.id = announcements.created_by', 'left')
            ->find($id);

        if (!$ann) return $this->response->setJSON(['status' => 404, 'error' => 'Anuncio no encontrado']);

        // Increment views
        $model->set('view_count', 'view_count + 1', false)->where('id', $id)->update();
        $ann['view_count'] = ((int)($ann['view_count'] ?? 0)) + 1;

        // Attachments
        $attachModel = new AnnouncementAttachmentModel();
        $ann['attachments'] = $attachModel->where('announcement_id', $id)->orderBy('id', 'ASC')->findAll();

        // Likes
        $likeModel = new AnnouncementLikeModel();
        $ann['like_count'] = $likeModel->where('announcement_id', $id)->countAllResults();
        $currentUser = session()->get('user_id') ?? 4;
        $ann['user_liked'] = $likeModel->where('announcement_id', $id)->where('user_id', $currentUser)->countAllResults() > 0;

        // Comments
        $commentModel = new AnnouncementCommentModel();
        $comments = $commentModel
            ->select('announcement_comments.*, users.first_name, users.last_name')
            ->join('users', 'users.id = announcement_comments.user_id', 'left')
            ->where('announcement_id', $id)
            ->orderBy('announcement_comments.created_at', 'ASC')
            ->findAll();
        $ann['comments'] = $comments;
        $ann['comment_count'] = count($comments);

        return $this->response->setJSON(['status' => 200, 'data' => $ann]);
    }

    /* ─── Like Toggle ─── */

    public function toggleLike($id = null)
    {
        $this->bootstrapTenant();
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $userId = session()->get('user_id') ?? 4;
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
        return $this->response->setJSON(['status' => 200, 'liked' => $liked, 'count' => $count]);
    }

    /* ─── Comment ─── */

    public function addComment($id = null)
    {
        $this->bootstrapTenant();
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $content = trim($this->request->getPost('content') ?? '');
        if ($content === '') {
            return $this->response->setJSON(['status' => 400, 'error' => 'Comentario vacío']);
        }

        $userId = session()->get('user_id') ?? 4;
        $commentModel = new AnnouncementCommentModel();
        $commentId = $commentModel->insert([
            'announcement_id' => $id,
            'user_id'         => $userId,
            'content'         => $content,
        ]);

        $count = $commentModel->where('announcement_id', $id)->countAllResults();
        return $this->response->setJSON(['status' => 201, 'message' => 'Comentario agregado', 'count' => $count, 'id' => $commentId]);
    }

    public function deleteComment($commentId = null)
    {
        $this->bootstrapTenant();
        if (!$commentId) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $commentModel = new AnnouncementCommentModel();
        $commentModel->delete($commentId);

        return $this->response->setJSON(['status' => 200, 'message' => 'Comentario eliminado']);
    }

    /* ─── Serve File ─── */

    public function serveFile($filename = null)
    {
        if (!$filename) {
            return $this->response->setStatusCode(404);
        }
        $path = WRITEPATH . 'uploads/announcements/' . $filename;
        if (!is_file($path)) {
            return $this->response->setStatusCode(404);
        }

        $mime = mime_content_type($path);
        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody(file_get_contents($path));
    }

    /* ─── Helpers ─── */

    private function handleUploads(int $announcementId): void
    {
        $uploadDir = WRITEPATH . 'uploads/announcements/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $attachModel = new AnnouncementAttachmentModel();
        $files = $this->request->getFiles();

        if (empty($files['attachments'])) return;

        $displayNames = $this->request->getPost('display_names');
        if (is_string($displayNames)) {
            $displayNames = json_decode($displayNames, true);
        }
        if (!is_array($displayNames)) $displayNames = [];

        $idx = 0;
        foreach ($files['attachments'] as $file) {
            if (!$file->isValid() || $file->hasMoved()) continue;

            $originalName = $file->getClientName();
            $ext = $file->getClientExtension();
            $mime = $file->getClientMimeType();
            $size = $file->getSize();

            // Determine type
            $fileType = 'image';
            if (str_starts_with($mime, 'video/')) $fileType = 'video';
            elseif ($mime === 'application/pdf') $fileType = 'pdf';

            $newName = uniqid('ann_') . '_' . time() . '.' . $ext;
            $file->move($uploadDir, $newName);

            // Compress images to reduce file size
            if ($fileType === 'image') {
                $this->compressImage($uploadDir . $newName, $mime);
                $size = filesize($uploadDir . $newName); // update size after compression
            }

            $displayName = $displayNames[$idx] ?? pathinfo($originalName, PATHINFO_FILENAME);

            $attachModel->insert([
                'announcement_id' => $announcementId,
                'file_name'       => $newName,
                'original_name'   => $originalName,
                'display_name'    => $displayName,
                'file_type'       => $fileType,
                'file_size'       => $size,
                'mime_type'       => $mime,
                'created_at'      => date('Y-m-d H:i:s'),
            ]);
            $idx++;
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
                return; // Unsupported format, skip compression
        }

        if (!$img) return;

        $w = imagesx($img);
        $h = imagesy($img);

        // Resize if larger than max dimension
        if ($w > $maxDim || $h > $maxDim) {
            if ($w >= $h) {
                $newW = $maxDim;
                $newH = (int) round($h * ($maxDim / $w));
            } else {
                $newH = $maxDim;
                $newW = (int) round($w * ($maxDim / $h));
            }
            $resized = imagecreatetruecolor($newW, $newH);
            // Preserve transparency for PNG
            if ($mime === 'image/png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }
            imagecopyresampled($resized, $img, 0, 0, 0, 0, $newW, $newH, $w, $h);
            imagedestroy($img);
            $img = $resized;
        }

        // Save compressed
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($img, $path, $quality);
                break;
            case 'image/png':
                imagepng($img, $path, 6); // 0-9 compression level
                break;
            case 'image/webp':
                imagewebp($img, $path, $quality);
                break;
        }
        imagedestroy($img);
    }
}
