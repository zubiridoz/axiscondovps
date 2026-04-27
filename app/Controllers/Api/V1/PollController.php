<?php

namespace App\Controllers\Api\V1;

use CodeIgniter\RESTful\ResourceController;
use App\Models\Tenant\PollModel;
use App\Models\Tenant\PollOptionModel;
use App\Models\Tenant\PollVoteModel;
use App\Models\Tenant\PollVoteLogModel;

class PollController extends ResourceController
{
    protected function respondSuccess($data = [], $message = null)
    {
        $response = ['status' => 'success', 'data' => $data];
        if ($message) $response['message'] = $message;
        return $this->respond($response);
    }

    protected function respondError($message, $status = 400)
    {
        return $this->response->setJSON(['status' => 'error', 'message' => $message])->setStatusCode($status);
    }

    private function isAdmin(int $userId): bool
    {
        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        if (!$tenantId) return false;

        $db = \Config\Database::connect();
        $adminRole = $db->table('user_condominium_roles')
            ->where('user_id', $userId)
            ->where('condominium_id', $tenantId)
            ->whereIn('role_id', [2, 5]) // 2: Admin, 5: Owner
            ->get()
            ->getRowArray();

        return !empty($adminRole);
    }

    /**
     * GET /api/v1/resident/polls
     */
    public function index()
    {
        $userId = $this->request->userId ?? null;
        $search = $this->request->getGet('search') ?? '';

        $pollModel = new PollModel();
        
        $builder = $pollModel->orderBy('created_at', 'DESC');
        if (!empty(trim($search))) {
            $builder->like('title', trim($search));
        }
        
        $polls = $builder->findAll();

        $now = time();
        $inProgress = [];
        $finished = [];

        // Eager load options to get total votes easily
        $pollIds = array_column($polls, 'id');
        $optionsMap = [];
        
        if (!empty($pollIds)) {
            $optionModel = new PollOptionModel();
            $optionsRaw = $optionModel->whereIn('poll_id', $pollIds)->findAll();
            foreach ($optionsRaw as $opt) {
                $optionsMap[$opt['poll_id']][] = $opt;
            }
        }

        // Get user votes
        $userVotesMap = [];
        if ($userId && !empty($pollIds)) {
            $voteModel = new PollVoteModel();
            $userVotesRaw = $voteModel->where('user_id', $userId)->whereIn('poll_id', $pollIds)->findAll();
            foreach ($userVotesRaw as $uv) {
                $userVotesMap[$uv['poll_id']] = $uv;
            }
        }

        foreach ($polls as $poll) {
            $pId = $poll['id'];
            $pollOptions = $optionsMap[$pId] ?? [];
            
            $totalVotes = 0;
            foreach ($pollOptions as $opt) {
                $totalVotes += (int)($opt['vote_count'] ?? 0);
            }
            
            $pollData = [
                'hash_id' => !empty($poll['hash_id']) ? $poll['hash_id'] : $poll['id'],
                'title' => $poll['title'],
                'category' => $poll['category'],
                'start_date' => $poll['start_date'],
                'end_date' => $poll['end_date'],
                'total_votes' => $totalVotes,
                'user_voted' => isset($userVotesMap[$pId]),
            ];

            $hasEndDate = !empty($poll['end_date']) && $poll['end_date'] !== '0000-00-00 00:00:00';
            $endTs = $hasEndDate ? strtotime((string)$poll['end_date']) : 0;
            $isActive = (int)($poll['is_active'] ?? 0) === 1;
            
            if ($isActive && (!$hasEndDate || $endTs > $now)) {
                $inProgress[] = $pollData;
            } else {
                $finished[] = $pollData;
            }
        }

        return $this->respondSuccess([
            'in_progress' => $inProgress,
            'finished' => $finished,
            'is_admin' => $userId ? $this->isAdmin($userId) : false,
        ]);
    }

    /**
     * GET /api/v1/resident/polls/active-count
     */
    public function activeCount()
    {
        $pollModel = new PollModel();
        $polls = $pollModel->where('is_active', 1)->findAll();
        
        $activeCount = 0;
        $now = time();
        foreach ($polls as $poll) {
            $hasEndDate = !empty($poll['end_date']) && $poll['end_date'] !== '0000-00-00 00:00:00';
            $endTs = $hasEndDate ? strtotime((string)$poll['end_date']) : 0;
            if (!$hasEndDate || $endTs > $now) {
                $activeCount++;
            }
        }

        return $this->respondSuccess(['count' => $activeCount]);
    }

    /**
     * GET /api/v1/resident/polls/(:any)
     */
    public function details($hash = null)
    {
        if (!$hash) return $this->respondError('Hash de encuesta requerido');

        $userId = $this->request->userId ?? null;

        $pollModel = new PollModel();
        $poll = $pollModel->where('hash_id', $hash)->orWhere('id', $hash)->first();

        if (!$poll) return $this->respondError('Encuesta no encontrada', 404);

        $pollId = $poll['id'];

        $optionModel = new PollOptionModel();
        $optionsRaw = $optionModel->where('poll_id', $pollId)->findAll();

        $totalVotes = 0;
        foreach ($optionsRaw as $opt) {
            $totalVotes += (int)($opt['vote_count'] ?? 0);
        }

        $voteModel = new PollVoteModel();
        $userVote = $voteModel->where('poll_id', $pollId)->where('user_id', $userId)->first();

        $changesCount = 0;
        if ($userVote) {
            $db = \Config\Database::connect();
            if ($db->tableExists('poll_vote_logs')) {
                $changesCount = $db->table('poll_vote_logs')->where('poll_id', $pollId)->where('user_id', $userId)->countAllResults();
            }
        }

        $userOptionId = null;
        if ($userVote) {
            $userOptionId = isset($userVote['poll_option_id']) ? (int)$userVote['poll_option_id'] : (isset($userVote['option_id']) ? (int)$userVote['option_id'] : null);
        }

        $options = [];
        foreach ($optionsRaw as $opt) {
            $vc = (int)($opt['vote_count'] ?? 0);
            $options[] = [
                'hash_id' => $opt['id'], // El front enviará este ID
                'id' => $opt['id'], 
                'text' => $opt['option_text'],
                'vote_count' => $vc,
                'percentage' => $totalVotes > 0 ? round(($vc / $totalVotes) * 100) : 0
            ];
        }

        $now = time();
        $hasEndDate = !empty($poll['end_date']) && $poll['end_date'] !== '0000-00-00 00:00:00';
        $endTs = $hasEndDate ? strtotime((string)$poll['end_date']) : 0;
        $isActive = (int)($poll['is_active'] ?? 0) === 1 && (!$hasEndDate || $endTs > $now);

        return $this->respondSuccess([
            'hash_id' => !empty($poll['hash_id']) ? $poll['hash_id'] : $poll['id'],
            'title' => $poll['title'],
            'description' => $poll['description'],
            'is_active' => $isActive,
            'total_votes' => $totalVotes,
            'user_voted' => $userVote ? true : false,
            'user_option_id' => $userOptionId,
            'changes_count' => $changesCount,
            'max_changes' => 2,
            'options' => $options,
            'is_admin' => $userId ? $this->isAdmin($userId) : false,
        ]);
    }

    /**
     * POST /api/v1/resident/polls/vote
     */
    public function vote()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autorizado', 401);

        $json = $this->request->getJSON();
        if (!$json || !isset($json->poll_hash_id) || !isset($json->option_id)) {
            return $this->respondError('Datos inválidos');
        }

        $pollModel = new PollModel();
        $poll = $pollModel->where('hash_id', $json->poll_hash_id)->orWhere('id', $json->poll_hash_id)->first();
        if (!$poll) return $this->respondError('Encuesta no encontrada', 404);

        $now = time();
        $hasEndDate = !empty($poll['end_date']) && $poll['end_date'] !== '0000-00-00 00:00:00';
        $endTs = $hasEndDate ? strtotime((string)$poll['end_date']) : 0;
        if ((int)($poll['is_active'] ?? 0) === 0 || ($hasEndDate && $endTs <= $now)) {
            return $this->respondError('La encuesta ya está cerrada');
        }

        $pollId = $poll['id'];
        $optionId = (int)$json->option_id;

        $optionModel = new PollOptionModel();
        $option = $optionModel->where('id', $optionId)->where('poll_id', $pollId)->first();
        if (!$option) return $this->respondError('Opción inválida');

        $voteModel = new PollVoteModel();
        $existingVote = $voteModel->where('poll_id', $pollId)->where('user_id', $userId)->first();
        
        if ($existingVote) {
            return $this->respondError('Ya has votado en esta encuesta. Usa la opción de cambiar voto.');
        }

        $db = \Config\Database::connect();
        
        // Determine if updated_at exists to avoid silent transaction failures
        $hasUpdatedAt = $db->fieldExists('updated_at', 'poll_votes');
        $hasCreatedAt = $db->fieldExists('created_at', 'poll_votes');
        
        // Handle migration variations (poll_option_id vs option_id)
        $optionColName = $db->fieldExists('poll_option_id', 'poll_votes') ? 'poll_option_id' : 'option_id';

        $db->transStart();

        try {
            $insertData = [
                'poll_id' => $pollId,
                $optionColName => $optionId,
                'user_id' => $userId
            ];

            if ($hasCreatedAt) {
                $insertData['created_at'] = date('Y-m-d H:i:s');
            }
            if ($hasUpdatedAt) {
                $insertData['updated_at'] = date('Y-m-d H:i:s');
            }

            // 1. Insert vote via query builder
            $inserted = $db->table('poll_votes')->insert($insertData);
            
            if (!$inserted) {
                $err = $db->error();
                $db->transRollback();
                
                // If column doesn't exist, dump fields to see what's actually in the database
                $fields = $db->getFieldNames('poll_votes');
                return $this->respondError('Error al insertar el voto. Columnas de BD: ' . implode(', ', $fields) . ' | Error: ' . json_encode($err), 500);
            }

            // 2. Increment option vote count
            $updated = $db->table('poll_options')->where('id', $optionId)->set('vote_count', 'vote_count + 1', false)->update();
            
            if (!$updated) {
                $err = $db->error();
                $db->transRollback();
                return $this->respondError('Error al actualizar conteo: ' . json_encode($err), 500);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->respondError('Ocurrió un error general de transacción', 500);
            }

            \App\Services\PollNotificationService::notifyVote($userId, $poll['title']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->respondError('Excepción DB: ' . $e->getMessage(), 500);
        }

        return $this->respondSuccess([], 'Voto registrado exitosamente');
    }

    /**
     * PUT /api/v1/resident/polls/change-vote
     */
    public function changeVote()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId) return $this->respondError('No autorizado', 401);

        $json = $this->request->getJSON();
        if (!$json || !isset($json->poll_hash_id) || !isset($json->option_id)) {
            return $this->respondError('Datos inválidos');
        }

        $pollModel = new PollModel();
        $poll = $pollModel->where('hash_id', $json->poll_hash_id)->orWhere('id', $json->poll_hash_id)->first();
        if (!$poll) return $this->respondError('Encuesta no encontrada', 404);

        $now = time();
        $hasEndDate = !empty($poll['end_date']) && $poll['end_date'] !== '0000-00-00 00:00:00';
        $endTs = $hasEndDate ? strtotime((string)$poll['end_date']) : 0;
        if ((int)($poll['is_active'] ?? 0) === 0 || ($hasEndDate && $endTs <= $now)) {
            return $this->respondError('La encuesta ya está cerrada');
        }

        $pollId = $poll['id'];
        $newOptionId = (int)$json->option_id;

        $optionModel = new PollOptionModel();
        $newOption = $optionModel->where('id', $newOptionId)->where('poll_id', $pollId)->first();
        if (!$newOption) return $this->respondError('Opción inválida');

        $voteModel = new PollVoteModel();
        $existingVote = $voteModel->where('poll_id', $pollId)->where('user_id', $userId)->first();
        
        if (!$existingVote) {
            return $this->respondError('Aún no has votado en esta encuesta');
        }

        $oldOptionId = isset($existingVote['poll_option_id']) ? (int)$existingVote['poll_option_id'] : (isset($existingVote['option_id']) ? (int)$existingVote['option_id'] : null);

        if ($oldOptionId === $newOptionId) {
            return $this->respondError('Ya has seleccionado esta opción');
        }

        $db = \Config\Database::connect();

        // Ensure log table exists
        $db->query("CREATE TABLE IF NOT EXISTS `poll_vote_logs` (
            `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `poll_id` INT(11) UNSIGNED NOT NULL,
            `user_id` INT(11) UNSIGNED NOT NULL,
            `previous_option_id` INT(11) UNSIGNED NOT NULL,
            `new_option_id` INT(11) UNSIGNED NOT NULL,
            `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
        )");

        $logModel = new PollVoteLogModel();
        $changesCount = $logModel->where('poll_id', $pollId)->where('user_id', $userId)->countAllResults();

        if ($changesCount >= 2) {
            return $this->respondError('Has alcanzado el límite máximo de 2 cambios de voto');
        }

        $db->transStart();

        try {
            // 1. Registrar el log del cambio
            $insertedLog = $db->table('poll_vote_logs')->insert([
                'poll_id' => $pollId,
                'user_id' => $userId,
                'previous_option_id' => $oldOptionId,
                'new_option_id' => $newOptionId
            ]);
            if (!$insertedLog) {
                $err = $db->error();
                $db->transRollback();
                return $this->respondError('Error al registrar log: ' . json_encode($err), 500);
            }

            // Handle migration variations (poll_option_id vs option_id)
            $optionColName = $db->fieldExists('poll_option_id', 'poll_votes') ? 'poll_option_id' : 'option_id';

            // 2. Actualizar el voto actual
            $updatedVote = $db->table('poll_votes')->where('id', $existingVote['id'])->update([
                $optionColName => $newOptionId
            ]);
            if (!$updatedVote) {
                $err = $db->error();
                $db->transRollback();
                return $this->respondError('Error al actualizar voto: ' . json_encode($err), 500);
            }

            // 3. Decrementar voto viejo (evitando valores negativos)
            $db->query("UPDATE poll_options SET vote_count = GREATEST(0, vote_count - 1) WHERE id = " . $db->escape($oldOptionId));

            // 4. Incrementar voto nuevo
            $updatedOption = $db->table('poll_options')->where('id', $newOptionId)->set('vote_count', 'vote_count + 1', false)->update();
            if (!$updatedOption) {
                $err = $db->error();
                $db->transRollback();
                return $this->respondError('Error al actualizar conteo de opción: ' . json_encode($err), 500);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->respondError('Ocurrió un error general de transacción al cambiar el voto', 500);
            }

            \App\Services\PollNotificationService::notifyVoteChange($userId, $poll['title']);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->respondError('Excepción al cambiar el voto: ' . $e->getMessage(), 500);
        }

        return $this->respondSuccess([], 'Voto cambiado exitosamente');
    }

    /**
     * POST /api/v1/resident/polls/create (Admin only)
     */
    public function create()
    {
        $userId = $this->request->userId ?? null;
        if (!$userId || !$this->isAdmin($userId)) return $this->respondError('No autorizado', 403);

        $json = $this->request->getJSON();
        if (!$json) return $this->respondError('Datos inválidos');

        $title       = trim($json->title ?? '');
        $options     = $json->options ?? [];
        $startDate   = $json->start_date ?? date('Y-m-d H:i:s');
        $endDate     = $json->end_date ?? null;
        $category    = trim($json->category ?? 'General');
        $isActive    = isset($json->is_active) ? (int)$json->is_active : 0;

        if (empty($title)) return $this->respondError('La pregunta de la encuesta es obligatoria');
        if (empty($options) || count($options) < 2) return $this->respondError('Se requieren al menos 2 opciones');

        $tenantId = \App\Services\TenantService::getInstance()->getTenantId();
        if (!$tenantId) return $this->respondError('Contexto de condominio no encontrado');

        $db = \Config\Database::connect();
        $db->transStart();

        $pollData = [
            'condominium_id' => $tenantId,
            'title'       => $title,
            'description' => null,
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'category'    => $category,
            'is_active'   => $isActive,
            'hash_id'     => bin2hex(random_bytes(12)),
            'created_at'  => date('Y-m-d H:i:s')
        ];

        try {
            $db->table('polls')->insert($pollData);
            $pollId = $db->insertID();

            if (!$pollId) {
                $db->transRollback();
                return $this->respondError('Error al crear encuesta en BD', 500);
            }

            $pollOptionModel = new PollOptionModel();
            foreach ($options as $optText) {
                if (!empty(trim($optText))) {
                    $pollOptionModel->insert([
                        'poll_id'     => $pollId,
                        'option_text' => trim($optText)
                    ]);
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                return $this->respondError('Error al guardar las opciones', 500);
            }

            if ($isActive) {
                \App\Services\PollNotificationService::notifyNewPoll($title);
            }

            return $this->respondSuccess(['id' => $pollId, 'hash_id' => $pollData['hash_id']], 'Encuesta creada exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->respondError('Excepción al crear: ' . $e->getMessage(), 500);
        }
    }

    /**
     * DELETE /api/v1/resident/polls/(:any)/delete (Admin only)
     */
    public function delete($hash = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId || !$this->isAdmin($userId)) return $this->respondError('No autorizado', 403);

        if (!$hash) return $this->respondError('Hash de encuesta requerido');

        $pollModel = new PollModel();
        $poll = $pollModel->where('hash_id', $hash)->orWhere('id', $hash)->first();

        if (!$poll) return $this->respondError('Encuesta no encontrada', 404);

        // Use fresh model instance to avoid residual query builder state
        $deleteModel = new PollModel();
        if ($deleteModel->delete($poll['id'])) {
            return $this->respondSuccess([], 'Encuesta eliminada exitosamente');
        }

        return $this->respondError('No se pudo eliminar la encuesta', 500);
    }

    /**
     * GET /api/v1/resident/polls/(:any)/voters (Admin only)
     */
    public function voters($hash = null)
    {
        $userId = $this->request->userId ?? null;
        if (!$userId || !$this->isAdmin($userId)) return $this->respondError('No autorizado', 403);

        if (!$hash) return $this->respondError('Hash de encuesta requerido');

        $pollModel = new PollModel();
        $poll = $pollModel->where('hash_id', $hash)->orWhere('id', $hash)->first();

        if (!$poll) return $this->respondError('Encuesta no encontrada', 404);

        $db = \Config\Database::connect();
        $optionColName = $db->fieldExists('poll_option_id', 'poll_votes') ? 'poll_option_id' : 'option_id';
        
        $voterDetails = $db->table('poll_votes')
            ->select("poll_votes.created_at, users.first_name, users.last_name, units.unit_number as unit_name, poll_options.option_text as option_chosen")
            ->join('users', 'users.id = poll_votes.user_id', 'left')
            ->join('residents', 'residents.user_id = users.id', 'left')
            ->join('units', 'units.id = residents.unit_id', 'left')
            ->join('poll_options', "poll_options.id = poll_votes.{$optionColName}", 'left')
            ->where('poll_votes.poll_id', $poll['id'])
            ->orderBy('poll_votes.created_at', 'DESC')
            ->get()
            ->getResultArray();

        return $this->respondSuccess(['voters' => $voterDetails]);
    }
}
