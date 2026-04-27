<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\PollModel;

/**
 * PollController
 * 
 * Gestión de encuestas/votaciones para consultas al Condominio.
 */
class PollController extends BaseController
{
    /**
     * Lista todas las encuestas
     */
    public function index()
    {
        $pollModel = new PollModel();
        $polls = $pollModel->orderBy('created_at', 'DESC')->findAll();

        return $this->response->setJSON(['status' => 200, 'data' => $polls]);
    }

    /**
     * Crea una nueva encuesta
     */
    public function create()
    {
        $payload = $this->request->getJSON();

        if (!$payload) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Datos inválidos']);
        }

        $title       = trim($payload->title ?? '');
        $options     = $payload->options ?? [];
        $startDate   = $payload->start_date ?? date('Y-m-d H:i:s');
        $endDate     = $payload->end_date ?? null;
        $category    = trim($payload->category ?? 'General');
        $isActive    = isset($payload->is_active) ? (int)$payload->is_active : 0;

        if (empty($title)) {
             return $this->response->setJSON(['status' => 400, 'error' => 'La pregunta de la encuesta es obligatoria']);
        }

        if (empty($options) || count($options) < 2) {
             return $this->response->setJSON(['status' => 400, 'error' => 'Se requieren al menos 2 opciones']);
        }

        // [HACK LOCAL] Forzamos el contexto Tenant para la inserción
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $db = \Config\Database::connect();
        $db->transStart();

        $pollData = [
            'condominium_id' => $demoCondo ? $demoCondo['id'] : 1,
            'title'       => $title,
            'description' => null, // Opcional, pero en este diseño parece que solo se usa título como pregunta
            'start_date'  => $startDate,
            'end_date'    => $endDate,
            'category'    => $category,
            'is_active'   => $isActive,
            'hash_id'     => bin2hex(random_bytes(12)),
            'created_at'  => date('Y-m-d H:i:s')
        ];

        try {
            // Bypass Model wrapper to force a raw DB exception if MySQL rejects the query
            $db->table('polls')->insert($pollData);
            $pollId = $db->insertID();

            if (!$pollId) {
                // If it STILL fails, let's get the DB error directly
                $dbError = $db->error();
                $db->transRollback();
                return $this->response->setJSON([
                    'status' => 500, 
                    'error' => 'Error crudo de BD', 
                    'details' => $dbError
                ]);
            }

            $pollOptionModel = new \App\Models\Tenant\PollOptionModel();
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
                return $this->response->setJSON(['status' => 500, 'error' => 'Error al guardar las opciones']);
            }

            // Retrieve the hash_id for the newly created poll
            $newPoll = $db->table('polls')->where('id', $pollId)->get()->getRowArray();

            if ($isActive) {
                \App\Services\PollNotificationService::notifyNewPoll($title);
            }

            return $this->response->setJSON(['status' => 201, 'message' => 'Encuesta creada exitosamente', 'id' => $pollId, 'hash_id' => $newPoll['hash_id'] ?? '']);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status' => 500, 
                'error' => 'Base de datos: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Activa una encuesta para que inicie la votación
     */
    public function activate($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $pollModel = new PollModel();
        if (!$pollModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Encuesta no encontrada']);
        }

        $pollModel->update($id, ['is_active' => 1]);
        $poll = $pollModel->find($id);
        \App\Services\PollNotificationService::notifyNewPoll($poll['title']);

        return $this->response->setJSON(['status' => 200, 'message' => 'La encuesta ha sido activada']);
    }

    /**
     * Cierra definitivamente una encuesta
     */
    public function closePoll($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $pollModel = new PollModel();
        if (!$pollModel->find($id)) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Encuesta no encontrada']);
        }

        $pollModel->update($id, ['end_date' => date('Y-m-d H:i:s'), 'is_active' => 0]);
        $poll = $pollModel->find($id);
        \App\Services\PollNotificationService::notifyPollFinished($poll['title']);

        return $this->response->setJSON(['status' => 200, 'message' => 'La encuesta fue cerrada']);
    }

    /**
     * Detalles de la encuesta (Vista) — resolve by hash_id
     */
    public function details($hash = null)
    {
        if (!$hash) return redirect()->to('/admin/encuestas');
        
        // [HACK LOCAL] Forzamos el contexto Tenant
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $pollModel = new PollModel();
        $poll = $pollModel->where('hash_id', $hash)->orWhere('id', $hash)->first();

        if (!$poll) {
             return redirect()->to('/admin/encuestas')->with('error', 'Encuesta no encontrada');
        }

        $id = $poll['id'];

        // Obtener Opciones
        $optionModel = new \App\Models\Tenant\PollOptionModel();
        $options = $optionModel->where('poll_id', $id)->findAll();

        // Obtener Votos
        $voteModel = new \App\Models\Tenant\PollVoteModel();
        $votes = $voteModel->where('poll_id', $id)->findAll();

        $totalVotes = count($votes);

        // Agrupar votos por opcion
        $votesPerOption = [];
        foreach ($options as $opt) {
            $votesPerOption[$opt['id']] = 0;
        }
        foreach ($votes as $v) {
            $optId = isset($v['poll_option_id']) ? $v['poll_option_id'] : (isset($v['option_id']) ? $v['option_id'] : null);
            if ($optId && isset($votesPerOption[$optId])) {
                $votesPerOption[$optId]++;
            }
        }

        // Calcular porcentajes
        foreach ($options as &$opt) {
            $optVotes = $votesPerOption[$opt['id']];
            $opt['vote_count'] = $optVotes;
            $opt['percentage'] = $totalVotes > 0 ? round(($optVotes / $totalVotes) * 100) : 0;
        }

        // Estimar audiencia (Total de residentes activos)
        $residentModel = new \App\Models\Tenant\ResidentModel();
        $totalResidents = $residentModel->where('is_active', 1)->countAllResults();

        $participationRate = $totalResidents > 0 ? round(($totalVotes / $totalResidents) * 100) : 0;

        // Obtener lista detallada de votantes
        $db = \Config\Database::connect();
        $optionColName = $db->fieldExists('poll_option_id', 'poll_votes') ? 'poll_option_id' : 'option_id';
        
        $voterDetails = $db->table('poll_votes')
            ->select("poll_votes.created_at, users.first_name, users.last_name, units.unit_number as unit_name, poll_options.option_text as option_chosen")
            ->join('users', 'users.id = poll_votes.user_id', 'left')
            ->join('residents', 'residents.user_id = users.id', 'left')
            ->join('units', 'units.id = residents.unit_id', 'left')
            ->join('poll_options', "poll_options.id = poll_votes.{$optionColName}", 'left')
            ->where('poll_votes.poll_id', $id)
            ->get()
            ->getResultArray();

        return view('admin/poll_details', [
            'poll'              => $poll,
            'options'           => $options,
            'totalVotes'        => $totalVotes,
            'totalResidents'    => $totalResidents,
            'participationRate' => $participationRate,
            'voterDetails'      => $voterDetails
        ]);
    }

    /**
     * RENDER HTML MVC - Vista Frontal del Administrador
     */
    public function indexView()
    {
        // [HACK LOCAL] Forzamos el contexto Tenant
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $pollModel = new PollModel();
        $polls = $pollModel->orderBy('created_at', 'DESC')->findAll();

        $residentModel = new \App\Models\Tenant\ResidentModel();
        $totalResidents = $residentModel->where('is_active', 1)->countAllResults();
        
        $unitModel = new \App\Models\Tenant\UnitModel();
        $totalUnits = $unitModel->countAllResults();

        // Calcular Estadisticas Reales
        $totalPolls = count($polls);
        $activePolls = 0;
        $now = time();
        foreach ($polls as $p) {
            $endTs = strtotime((string)($p['end_date'] ?? ''));
            if ((int)($p['is_active'] ?? 0) === 1 && ($endTs === 0 || $endTs > $now)) {
                $activePolls++;
            }
        }

        $db = \Config\Database::connect();
        $totalSystemVotes = $db->table('poll_votes')->countAllResults();
        
        $pollVotes = $db->table('poll_options')->select('poll_id, sum(vote_count) as total_votes')->groupBy('poll_id')->get()->getResultArray();
        $votesMap = [];
        foreach($pollVotes as $pv) {
             $votesMap[$pv['poll_id']] = (int)$pv['total_votes'];
        }
        foreach ($polls as &$p) {
             $p['total_votes'] = $votesMap[$p['id']] ?? 0;
        }

        return view('admin/polls', [
            'polls' => $polls,
            'totalResidents' => $totalResidents,
            'totalUnits' => $totalUnits,
            'totalPolls' => count($polls),
            'activePolls' => $activePolls,
            'totalSystemVotes' => $totalSystemVotes
        ]);
    }

    public function delete($hash = null)
    {
        // [HACK LOCAL] Forzamos el contexto Tenant 
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $pollModel = new PollModel();
        
        $poll = $pollModel->where('hash_id', $hash)->orWhere('id', $hash)->first();
        if (!$poll) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Encuesta no encontrada']);
        }

        if ($pollModel->delete($poll['id'])) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Encuesta eliminada exitosamente']);
        }

        return $this->response->setJSON(['status' => 500, 'error' => 'No se pudo eliminar la encuesta']);
    }

    /**
     * Cierra una encuesta activa
     */
    public function close($hash = null)
    {
        // [HACK LOCAL] Forzamos el contexto Tenant 
        $demoCondo = (new \App\Models\Tenant\CondominiumModel())->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);

        $pollModel = new PollModel();
        
        $poll = $pollModel->where('hash_id', $hash)->orWhere('id', $hash)->first();
        if (!$poll) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Encuesta no encontrada']);
        }

        $update = $pollModel->update($poll['id'], [
            'end_date' => date('Y-m-d H:i:s'), // Forzamos el cierre ahora
            'is_active' => 0
        ]);

        if ($update) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Encuesta cerrada exitosamente']);
        }

        return $this->response->setJSON(['status' => 500, 'error' => 'No se pudo cerrar la encuesta']);
    }
}
