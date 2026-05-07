<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\Tenant\ContentReportModel;
use App\Models\Tenant\BlockedUserModel;
use App\Services\TenantService;

class ModerationController extends BaseController
{
    /**
     * Vista principal de moderación — Reportes + Bloqueos
     */
    public function index()
    {
        $tenantId = TenantService::getInstance()->getTenantId();

        // ── Reportes de contenido ──
        $reportModel = new ContentReportModel();
        $reports = $reportModel
            ->select('content_reports.*, 
                      reporter.first_name as reporter_first_name, reporter.last_name as reporter_last_name,
                      reported.first_name as reported_first_name, reported.last_name as reported_last_name,
                      u_reporter.unit_number as reporter_unit,
                      u_reported.unit_number as reported_unit,
                      ann.title as announcement_title, ann.content as announcement_content,
                      ac.content as comment_content')
            ->join('users as reporter', 'reporter.id = content_reports.reporter_user_id', 'left')
            ->join('users as reported', 'reported.id = content_reports.reported_user_id', 'left')
            ->join('residents as res', 'res.user_id = content_reports.reporter_user_id AND res.condominium_id = content_reports.condominium_id', 'left')
            ->join('units as u_reporter', 'u_reporter.id = res.unit_id', 'left')
            ->join('residents as res2', 'res2.user_id = content_reports.reported_user_id AND res2.condominium_id = content_reports.condominium_id', 'left')
            ->join('units as u_reported', 'u_reported.id = res2.unit_id', 'left')
            ->join('announcements as ann', 'ann.id = content_reports.announcement_id', 'left')
            ->join('announcement_comments as ac', 'ac.id = content_reports.comment_id', 'left')
            ->where('content_reports.condominium_id', $tenantId)
            ->orderBy('content_reports.created_at', 'DESC')
            ->findAll();

        // ── Bloqueos entre usuarios ──
        $blockModel = new BlockedUserModel();
        $blocks = $blockModel
            ->select('blocked_users.*, 
                      blocker.first_name as blocker_first_name, blocker.last_name as blocker_last_name,
                      blocked.first_name as blocked_first_name, blocked.last_name as blocked_last_name')
            ->join('users as blocker', 'blocker.id = blocked_users.user_id', 'left')
            ->join('users as blocked', 'blocked.id = blocked_users.blocked_user_id', 'left')
            ->where('blocked_users.condominium_id', $tenantId)
            ->orderBy('blocked_users.created_at', 'DESC')
            ->findAll();

        // ── Estadísticas ──
        $stats = [
            'total_reports'   => count($reports),
            'pending_reports' => count(array_filter($reports, fn($r) => ($r['status'] ?? 'pending') === 'pending')),
            'resolved_reports' => count(array_filter($reports, fn($r) => ($r['status'] ?? 'pending') !== 'pending')),
            'total_blocks'    => count($blocks),
        ];

        return view('admin/moderation', [
            'reports' => $reports,
            'blocks'  => $blocks,
            'stats'   => $stats,
        ]);
    }

    /**
     * Marcar reporte como revisado/resuelto
     */
    public function resolveReport($reportId)
    {
        $reportModel = new ContentReportModel();
        $report = $reportModel->find($reportId);

        if (!$report) {
            return redirect()->back()->with('error', 'Reporte no encontrado.');
        }

        $action = $this->request->getPost('action') ?? 'reviewed';

        $reportModel->update($reportId, [
            'status' => $action, // 'reviewed', 'dismissed', 'action_taken'
        ]);

        return redirect()->to('/admin/moderacion')->with('success', 'Reporte actualizado correctamente.');
    }

    /**
     * Desbloquear usuario (admin override)
     */
    public function adminUnblock($blockId)
    {
        $blockModel = new BlockedUserModel();
        $block = $blockModel->find($blockId);

        if (!$block) {
            return redirect()->back()->with('error', 'Bloqueo no encontrado.');
        }

        $blockModel->delete($blockId);

        return redirect()->to('/admin/moderacion')->with('success', 'Bloqueo eliminado correctamente.');
    }
}
