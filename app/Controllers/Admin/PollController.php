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
        $options     = array_unique(array_map("trim", $payload->options ?? []));
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
     * Edita una encuesta existente
     */
    public function edit($id = null)
    {
        if (!$id) return $this->response->setJSON(['status' => 400, 'error' => 'ID no proporcionado']);

        $pollModel = new PollModel();
        $poll = $pollModel->find($id);

        if (!$poll) {
            return $this->response->setJSON(['status' => 404, 'error' => 'Encuesta no encontrada']);
        }

        $now = time();
        $hasEndDate = !empty($poll['end_date']) && $poll['end_date'] !== '0000-00-00 00:00:00';
        $endTs = $hasEndDate ? strtotime((string)$poll['end_date']) : 0;
        $isClosed = (int)$poll['is_active'] === 0 || ($hasEndDate && $endTs <= $now);

        if ($isClosed) {
            return $this->response->setJSON(['status' => 403, 'error' => 'No se puede editar una encuesta que ya está cerrada.']);
        }

        $payload = $this->request->getJSON();
        if (!$payload) {
            return $this->response->setJSON(['status' => 400, 'error' => 'Datos inválidos']);
        }

        $title       = trim($payload->title ?? '');
        $description = trim($payload->description ?? '');
        $endDate     = $payload->end_date ?? null;
        $category    = trim($payload->category ?? 'General');

        if (empty($title)) {
             return $this->response->setJSON(['status' => 400, 'error' => 'El título de la encuesta es obligatorio']);
        }

        $updateData = [
            'title'       => $title,
            'description' => $description,
            'end_date'    => $endDate,
            'category'    => $category,
        ];

        if ($pollModel->update($id, $updateData)) {
            return $this->response->setJSON(['status' => 200, 'message' => 'Encuesta actualizada correctamente']);
        }

        return $this->response->setJSON(['status' => 500, 'error' => 'Error al actualizar la encuesta']);
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

        // Agrupar votos por opcion unificando textos duplicados
        $mergedOptions = [];
        foreach ($options as $opt) {
            $text = trim($opt['option_text']);
            if (!isset($mergedOptions[$text])) {
                $mergedOptions[$text] = $opt;
                $mergedOptions[$text]['vote_count'] = 0;
                $mergedOptions[$text]['ids'] = [];
            }
            $mergedOptions[$text]['ids'][] = $opt['id'];
        }

        foreach ($votes as $v) {
            $optId = isset($v['poll_option_id']) ? $v['poll_option_id'] : (isset($v['option_id']) ? $v['option_id'] : null);
            foreach ($mergedOptions as &$mOpt) {
                if (in_array($optId, $mOpt['ids'])) {
                    $mOpt['vote_count']++;
                    break;
                }
            }
        }

        // Calcular porcentajes
        foreach ($mergedOptions as &$mOpt) {
            $mOpt['percentage'] = $totalVotes > 0 ? round(($mOpt['vote_count'] / $totalVotes) * 100) : 0;
        }
        
        $options = array_values($mergedOptions);

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

    /**
     * Exportar reporte de encuesta a PDF
     */
    public function exportPdf($hash = null)
    {
        if (!$hash) return redirect()->to('/admin/encuestas');

        $detailed = $this->request->getGet('detailed') == '1';

        // [HACK LOCAL] Forzamos el contexto Tenant
        $condoModel = new \App\Models\Tenant\CondominiumModel();
        $demoCondo = $condoModel->first();
        if ($demoCondo) \App\Services\TenantService::getInstance()->setTenantId((int)$demoCondo['id']);
        
        $condominiumName = $demoCondo ? $demoCondo['name'] : 'Condominio';
        $condoAddress = $demoCondo ? ($demoCondo['address'] ?? 'Sin Dirección') : '';
        $logoFile = $demoCondo['logo'] ?? '';
        if (!empty($logoFile)) {
            $logoPath = (strpos($logoFile, '/') !== false)
                ? WRITEPATH . 'uploads/' . $logoFile
                : WRITEPATH . 'uploads/condominiums/' . $demoCondo['id'] . '/' . $logoFile;
            $hasLogo = is_file($logoPath);
        } else {
            $logoPath = '';
            $hasLogo = false;
        }

        $signatureFile = $demoCondo && !empty($demoCondo['signature_image']) ? $demoCondo['signature_image'] : '';
        $signatureName = $demoCondo && !empty($demoCondo['signature_name']) ? $demoCondo['signature_name'] : '';
        $hasSignature = false;
        if (!empty($signatureFile)) {
            $signaturePath = (strpos($signatureFile, '/') !== false)
                ? WRITEPATH . 'uploads/' . $signatureFile
                : WRITEPATH . 'uploads/condominiums/' . $demoCondo['id'] . '/' . $signatureFile;
            $hasSignature = is_file($signaturePath);
        } else {
            $signaturePath = '';
        }

        $pollModel = new \App\Models\Tenant\PollModel();
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

        // Agrupar votos por opcion unificando textos duplicados
        $mergedOptions = [];
        foreach ($options as $opt) {
            $text = trim($opt['option_text']);
            if (!isset($mergedOptions[$text])) {
                $mergedOptions[$text] = $opt;
                $mergedOptions[$text]['vote_count'] = 0;
                $mergedOptions[$text]['ids'] = [];
            }
            $mergedOptions[$text]['ids'][] = $opt['id'];
        }

        foreach ($votes as $v) {
            $optId = isset($v['poll_option_id']) ? $v['poll_option_id'] : (isset($v['option_id']) ? $v['option_id'] : null);
            foreach ($mergedOptions as &$mOpt) {
                if (in_array($optId, $mOpt['ids'])) {
                    $mOpt['vote_count']++;
                    break;
                }
            }
        }

        // Calcular porcentajes
        foreach ($mergedOptions as &$mOpt) {
            $mOpt['percentage'] = $totalVotes > 0 ? round(($mOpt['vote_count'] / $totalVotes) * 100) : 0;
        }
        
        $options = array_values($mergedOptions);

        // Estimar audiencia (Total de residentes activos)
        $residentModel = new \App\Models\Tenant\ResidentModel();
        $totalResidents = $residentModel->where('is_active', 1)->countAllResults();
        $participationRate = $totalResidents > 0 ? round(($totalVotes / $totalResidents) * 100) : 0;

        $voterDetails = [];
        if ($detailed) {
            $db = \Config\Database::connect();
            $optionColName = $db->fieldExists('poll_option_id', 'poll_votes') ? 'poll_option_id' : 'option_id';
            
            $voterDetails = $db->table('poll_votes')
                ->select("poll_votes.created_at, users.first_name, users.last_name, units.unit_number as unit_name, poll_options.option_text as option_chosen")
                ->join('users', 'users.id = poll_votes.user_id', 'left')
                ->join('residents', 'residents.user_id = users.id', 'left')
                ->join('units', 'units.id = residents.unit_id', 'left')
                ->join('poll_options', "poll_options.id = poll_votes.{$optionColName}", 'left')
                ->where('poll_votes.poll_id', $id)
                ->orderBy('poll_votes.created_at', 'DESC')
                ->get()
                ->getResultArray();
        }

        $fechaGen = date('d/m/Y H:i');

        // TCPDF Class definition
        $pdf = new class ('P', 'mm', 'LETTER', true, 'UTF-8', false) extends \TCPDF {
            public $condoName = '';
            public $emissionDate = '';
            public function Footer()
            {
                $this->SetY(-15);
                $this->SetDrawColor(220, 220, 220);
                $this->Line(20, $this->GetY(), 195.6, $this->GetY());
                $this->SetY(-13);
                $this->SetFont('helvetica', 'B', 7);
                $this->SetTextColor(80, 80, 80);
                $this->Cell(60, 5, strtoupper($this->condoName), 0, 0, 'L');
                $this->SetFont('helvetica', '', 7);
                $this->Cell(60, 5, 'Generado el ' . $this->emissionDate, 0, 0, 'C');
                $this->Cell(55.6, 5, $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
            }
        };

        $pdf->condoName = $condominiumName;
        $pdf->emissionDate = $fechaGen;

        $pdf->SetCreator('AxisCondo');
        $pdf->SetAuthor($condominiumName);
        $pdf->SetTitle('Reporte de Votación - ' . $condominiumName);
        $pdf->SetSubject('Reporte de Votación');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        $pdf->SetMargins(20, 15, 20);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        // HEADER BAR
        $pdf->SetFillColor(29, 76, 157); // #1D4C9D
        $pdf->Rect(20, 15, 175.6, 36, 'F');

        if ($hasLogo) {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(24, 19, 28, 28, 'F');
            $pdf->Image($logoPath, 25, 20, 26, 26, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);
        } else {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(24, 19, 28, 28, 'F');
            $pdf->SetFillColor(29, 76, 157);
            $pdf->Rect(28, 29, 20, 4, 'F');
        }

        // Title
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 16);
        $pdf->SetXY(56, 18);
        $pdf->Cell(136, 8, 'REPORTE DE VOTACIÓN', 0, 1, 'C');

        // Community name
        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetXY(56, 27);
        $pdf->Cell(136, 7, 'COMUNIDAD: ' . strtoupper($condominiumName), 0, 1, 'C');

        // Address (auto-fit)
        $addrText = strtoupper($condoAddress ?? '');
        $maxAddrWidth = 132;
        $addrFontSize = 7;
        $pdf->SetFont('helvetica', '', $addrFontSize);
        while ($pdf->GetStringWidth($addrText) > $maxAddrWidth && $addrFontSize > 5) {
            $addrFontSize -= 0.5;
            $pdf->SetFont('helvetica', '', $addrFontSize);
        }
        if ($pdf->GetStringWidth($addrText) > $maxAddrWidth) {
            while ($pdf->GetStringWidth($addrText . '...') > $maxAddrWidth && mb_strlen($addrText) > 10) {
                $addrText = mb_substr($addrText, 0, -1);
            }
            $addrText .= '...';
        }
        $pdf->SetTextColor(199, 210, 232);
        $pdf->SetXY(56, 35);
        $pdf->Cell(136, 5, $addrText, 0, 1, 'C');

        $pdf->SetY(60);

        // Body HTML
        $html = "
        <style>
            h3 { color: #1D4C9D; font-size: 11pt; font-weight: bold; }
            h4 { color: #334155; font-size: 11pt; font-weight: bold; }
            p { color: #475569; font-size: 10pt; line-height: 1.5; }
            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
            th { background-color: #f1f5f9; color: #475569; font-weight: bold; font-size: 10pt; padding: 8px; border: 1px solid #cbd5e1; }
            td { font-size: 10pt; color: #334155; padding: 8px; border: 1px solid #cbd5e1; }
            .bg-blue { background-color: #1D4C9D; color: #ffffff; }
        </style>
        ";

        $statusStr = $poll['is_active'] ? 'ACTIVA' : 'CERRADA';
        $statusColor = $poll['is_active'] ? '#059669' : '#dc2626';
        $statusBg = $poll['is_active'] ? '#f0fdf4' : '#fef2f2';
        $statusBorder = $poll['is_active'] ? '#bbf7d0' : '#fecaca';

        $category = htmlspecialchars($poll['category'] ?? 'General');
        $pollTitle = nl2br(htmlspecialchars($poll['title']));
        $pollDesc = nl2br(htmlspecialchars($poll['description'] ?? ''));
        $dateCreated = date('d/m/Y', strtotime($poll['created_at']));
        $dateClosed = $poll['end_date'] ? date('d/m/Y H:i', strtotime($poll['end_date'])) : 'Sin límite';

        $html .= "
        <h3>{$pollTitle}</h3>
        <p><strong>Descripción:</strong> {$pollDesc}</p>
        <br/><br/>
        <h3 style=\"color: #1e293b; font-size: 11pt; font-weight: bold; margin-bottom: 2px;\">Resumen Ejecutivo</h3>
        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\"><tr><td style=\"background-color: #e2e8f0; height: 1px; line-height: 1px; font-size: 1px;\">&nbsp;</td></tr></table>
        <br><br>
        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
                <td width=\"32%\">
                    <table width=\"100%\" cellpadding=\"10\" bgcolor=\"{$statusBg}\" style=\"border: 1px solid {$statusBorder};\">
                        <tr><td>
                            <div style=\"font-size: 7pt; color: {$statusColor}; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;\">ESTADO</div>
                            <div style=\"font-size: 14pt; font-weight: bold; color: {$statusColor};\">{$statusStr}</div>
                        </td></tr>
                    </table>
                </td>
                <td width=\"2%\"></td>
                <td width=\"32%\">
                    <table width=\"100%\" cellpadding=\"10\" bgcolor=\"#eff6ff\" style=\"border: 1px solid #bfdbfe;\">
                        <tr><td>
                            <div style=\"font-size: 7pt; color: #1d4ed8; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;\">TOTAL VOTOS</div>
                            <div style=\"font-size: 14pt; font-weight: bold; color: #1d4ed8;\">{$totalVotes}</div>
                        </td></tr>
                    </table>
                </td>
                <td width=\"2%\"></td>
                <td width=\"32%\">
                    <table width=\"100%\" cellpadding=\"10\" bgcolor=\"#f5f3ff\" style=\"border: 1px solid #ddd6fe;\">
                        <tr><td>
                            <div style=\"font-size: 7pt; color: #6d28d9; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;\">PARTICIPACIÓN</div>
                            <div style=\"font-size: 14pt; font-weight: bold; color: #6d28d9;\">{$participationRate}%</div>
                        </td></tr>
                    </table>
                </td>
            </tr>
        </table>
        <br>
        <table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\">
            <tr>
                <td width=\"32%\">
                    <table width=\"100%\" cellpadding=\"10\" bgcolor=\"#f8fafc\" style=\"border: 1px solid #e2e8f0;\">
                        <tr><td>
                            <div style=\"font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;\">CATEGORÍA</div>
                            <div style=\"font-size: 11pt; font-weight: bold; color: #0f172a;\">{$category}</div>
                        </td></tr>
                    </table>
                </td>
                <td width=\"2%\"></td>
                <td width=\"32%\">
                    <table width=\"100%\" cellpadding=\"10\" bgcolor=\"#f8fafc\" style=\"border: 1px solid #e2e8f0;\">
                        <tr><td>
                            <div style=\"font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;\">APERTURA</div>
                            <div style=\"font-size: 11pt; font-weight: bold; color: #0f172a;\">{$dateCreated}</div>
                        </td></tr>
                    </table>
                </td>
                <td width=\"2%\"></td>
                <td width=\"32%\">
                    <table width=\"100%\" cellpadding=\"10\" bgcolor=\"#f8fafc\" style=\"border: 1px solid #e2e8f0;\">
                        <tr><td>
                            <div style=\"font-size: 7pt; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; font-weight: bold;\">CIERRE</div>
                            <div style=\"font-size: 11pt; font-weight: bold; color: #0f172a;\">{$dateClosed}</div>
                        </td></tr>
                    </table>
                </td>
            </tr>
        </table>
        ";
        $pdf->writeHTML($html, true, false, true, false, '');
        $html = '';

        if ($totalVotes > 0) {
            $pdf->Ln(5);
            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetTextColor(15, 23, 42);
            $pdf->Cell(0, 8, 'Distribución de Votos', 0, 1, 'C');
            $pdf->Ln(2);
            
            $cy = $pdf->GetY() + 20;
            $pieColors = [
                [59, 130, 246], // blue
                [16, 185, 129], // emerald
                [245, 158, 11], // amber
                [236, 72, 153], // pink
                [139, 92, 246], // violet
                [14, 165, 233], // sky
                [249, 115, 22]  // orange
            ];

            $startAngle = 0;
            $cIdx = 0;
            $legendHtml = '<table cellpadding="4">';
            
            foreach ($options as $opt) {
                $pct = ($totalVotes > 0) ? ($opt['vote_count'] / $totalVotes) : 0;
                $angle = $pct * 360;

                if ($angle > 0) {
                    $endAngle = $startAngle + $angle;
                    $c = $pieColors[$cIdx % count($pieColors)];
                    $pdf->SetFillColor($c[0], $c[1], $c[2]);

                    if ($angle >= 359.9) {
                        $pdf->Circle(60, $cy, 20, 0, 360, 'F');
                    } else {
                        $pdf->PieSector(60, $cy, 20, $startAngle, $endAngle, 'F');
                    }
                    $startAngle = $endAngle;
                }

                $cHex = sprintf("#%02x%02x%02x", $pieColors[$cIdx % count($pieColors)][0], $pieColors[$cIdx % count($pieColors)][1], $pieColors[$cIdx % count($pieColors)][2]);
                $percentFmt = round($pct * 100);
                
                $legendHtml .= '<tr>
                    <td width="15px" bgcolor="' . $cHex . '"></td>
                    <td width="300px" style="font-size: 10pt; color: #334155;">' . htmlspecialchars($opt['option_text']) . '<br><span style="font-size: 8pt; color: #64748b;">(' . $opt['vote_count'] . ' votos - ' . $percentFmt . '%)</span></td>
                </tr>';
                $cIdx++;
            }
            $legendHtml .= '</table>';

            $pdf->setXY(100, $cy - 15);
            $pdf->writeHTML($legendHtml, true, false, true, false, '');

            if ($pdf->GetY() < $cy + 25) {
                $pdf->SetY($cy + 25);
            }
        }
        
        // Restaurar fuente normal para el resto del documento
        $pdf->SetFont('helvetica', '', 10);

        if ($detailed) {
            $html .= "
            <style>
                table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                th { background-color: #f1f5f9; color: #475569; font-weight: bold; font-size: 10pt; padding: 8px; border: 1px solid #cbd5e1; }
                td { font-size: 10pt; color: #334155; padding: 8px; border: 1px solid #cbd5e1; }
            </style>
            <br/><br/><br/>
            <h4 style=\"color:#ea580c; font-size:11pt; font-weight:bold;\">Anexo: Listado Detallado de Votantes</h4>
            <p style=\"font-size:8pt; color:#64748b;\"><em>NOTA: Este anexo contiene información sensible y debe ser manejado de acuerdo con las políticas de privacidad del condominio.</em></p>
            <table>
                <thead>
                    <tr>
                        <th width=\"30%\">Residente</th>
                        <th width=\"20%\">Unidad</th>
                        <th width=\"30%\">Opción Elegida</th>
                        <th width=\"20%\">Fecha del Voto</th>
                    </tr>
                </thead>
                <tbody>
            ";

            if (empty($voterDetails)) {
                $html .= "<tr><td colspan=\"4\" align=\"center\">No hay votos registrados.</td></tr>";
            } else {
                foreach ($voterDetails as $vd) {
                    $voterName = htmlspecialchars(trim(($vd['first_name'] ?? '') . ' ' . ($vd['last_name'] ?? '')));
                    if (empty($voterName)) $voterName = 'Usuario Desconocido';
                    
                    $unitName = htmlspecialchars($vd['unit_name'] ?? 'Sin unidad');
                    $optChosen = htmlspecialchars($vd['option_chosen'] ?? 'Eliminada');
                    $voteDate = date('d/m/Y H:i', strtotime($vd['created_at']));

                    $html .= "
                    <tr>
                        <td width=\"30%\">{$voterName}</td>
                        <td width=\"20%\">{$unitName}</td>
                        <td width=\"30%\">{$optChosen}</td>
                        <td width=\"20%\">{$voteDate}</td>
                    </tr>
                    ";
                }
            }

            $html .= "
                </tbody>
            </table>
            ";
        }

        $pdf->writeHTML($html, true, false, true, false, '');

        if ($hasSignature) {
            $pdf->Ln(5);
            $imgWidth = 35;
            $imgHeight = 12;
            $centerX = 20 + (175.6 - $imgWidth) / 2;
            
            $currentY = $pdf->GetY();
            if ($currentY + $imgHeight + 10 > 250) {
                $pdf->AddPage();
                $currentY = $pdf->GetY();
            }

            $pdf->Image($signaturePath, $centerX, $currentY, $imgWidth, $imgHeight, '', '', '', false, 300, '', false, false, 0, 'CM', false, false);
            $pdf->SetY($currentY + $imgHeight);

            $pdf->SetTextColor(15, 23, 42);
            $pdf->SetFont('helvetica', 'B', 8);
            $pdf->Cell(175.6, 4, $signatureName, 0, 1, 'C');
        }

        $fileName = 'Reporte_Votacion_' . date('Ymd_His') . '.pdf';
        
        $this->response->setHeader('Content-Type', 'application/pdf');
        if (ob_get_length()) ob_end_clean();
        $pdf->Output($fileName, 'D'); // Force Download ('D') or Inline ('I')
        exit;
    }
}
