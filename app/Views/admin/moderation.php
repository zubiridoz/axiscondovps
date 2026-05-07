<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$allReports = is_array($reports ?? null) ? $reports : [];
$allBlocks  = is_array($blocks ?? null)  ? $blocks  : [];

$pendingReports  = (int)($stats['pending_reports'] ?? 0);
$totalReports    = (int)($stats['total_reports'] ?? 0);
$resolvedReports = (int)($stats['resolved_reports'] ?? 0);
$totalBlocks     = (int)($stats['total_blocks'] ?? 0);

$timeAgo = static function ($dateStr): string {
    if (!$dateStr) return 'Sin fecha';
    $diff = time() - strtotime($dateStr);
    if ($diff < 60)    return 'hace un momento';
    if ($diff < 3600)  return 'hace ' . floor($diff / 60) . ' min';
    if ($diff < 86400) return 'hace ' . floor($diff / 3600) . ' hr' . (floor($diff / 3600) > 1 ? 's' : '');
    return 'hace ' . floor($diff / 86400) . ' día' . (floor($diff / 86400) > 1 ? 's' : '');
};
?>

<style>
    /* ── Hero (same as Calendar module) ── */
    .mod-hero {
        background: #ffffff;
        border-radius: .5rem;
        padding: 0.85rem 1.25rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }
    .mod-hero-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .mod-hero-title {
        margin: 0;
        font-weight: 500;
        font-size: 1.05rem;
        color: #3F67AC;
    }
    .mod-hero-divider {
        width: 1px;
        height: 22px;
        background-color: #cbd5e1;
    }
    .mod-hero-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.85rem;
        color: #64748b;
    }
    .mod-hero-breadcrumb i.bi-chevron-right {
        font-size: 0.65rem;
        color: #94a3b8;
    }
    .mod-hero-right {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .mod-hero-btn {
        background: #238b71ff;
        color: #ffffff;
        border: none;
        border-radius: 0.45rem;
        padding: 0.55rem 1.1rem;
        font-size: 0.88rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.25s ease;
    }
    .mod-hero-btn:hover {
        background: #5cad99ff;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }
    .mod-hero-btn.active {
        background: #238b71ff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
    }
    .mod-hero-btn.outline {
        background: #ffffff;
        color: #334155;
        border: 1px solid #d0d8e2;
        box-shadow: none;
    }
    .mod-hero-btn.outline:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        box-shadow: none;
        transform: translateY(-1px);
    }
    .mod-hero-btn .badge {
        font-size: 0.65rem;
        padding: 0.2em 0.5em;
        border-radius: 10px;
    }

    .mod-stat-card {
        border: 1px solid #d9e1eb;
        border-radius: 0.6rem;
        background: #fff;
        padding: 1rem 1.2rem;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }
    .mod-stat-card:hover {
        border-color: #c8d3df;
        box-shadow: 6px 8px 16px rgba(15, 23, 42, 0.12);
        transform: translateY(-1px);
    }
    .mod-stat-label { font-size: 0.82rem; color: #64748b; margin-bottom: 0.35rem; font-weight: 500; }
    .mod-stat-value { margin: 0; color: #0f172a; font-size: 2rem; line-height: 1; font-weight: 700; }
    .mod-stat-icon {
        width: 40px; height: 40px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center; font-size: 1rem;
    }

    .mod-panel { border: 1px solid #d9e1eb; border-radius: 0.6rem; }

    .mod-search-wrap { position: relative; max-width: 430px; width: 100%; }
    .mod-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.9rem; }
    .mod-search {
        width: 100%; border: 1px solid #d0d8e2; border-radius: 0.45rem;
        padding: 0.55rem 0.85rem 0.55rem 2rem; font-size: 0.88rem; color: #334155;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .mod-search:focus { outline: none; border-color: #94a3b8; box-shadow: 0 0 0 4px rgba(148,163,184,0.14); }

    .mod-table thead th {
        font-size: 0.74rem; color: #64748b; font-weight: 600; letter-spacing: 0.02em;
        text-transform: uppercase; border-bottom: 1px solid #e2e8f0; padding: 0.85rem 0.9rem;
    }
    .mod-table tbody td {
        border-bottom: 1px solid #eef2f7; color: #334155; font-size: 0.86rem;
        vertical-align: middle; padding: 0.9rem;
    }
    .mod-table tbody tr { transition: background 0.15s; }
    .mod-table tbody tr:hover td { background: #f8fafc; }

    .mod-empty {
        min-height: 350px; border: 1px dashed #d9e1eb; border-radius: 0.75rem;
        background: #fbfdff; text-align: center; display: flex;
        align-items: center; justify-content: center; padding: 2rem 1rem;
    }
    .mod-empty-icon {
        width: 72px; height: 72px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 1.5rem; margin-bottom: 1rem;
    }
    .mod-empty-title { color: #0f172a; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem; }
    .mod-empty-desc { color: #3F67AC; max-width: 480px; margin: 0 auto; font-size: 0.9rem; }

    .mod-reason-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;
    }
    .mod-status-dot {
        width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 6px;
    }
    .mod-action-btn {
        width: 30px; height: 30px; border-radius: 0.4rem; display: inline-flex;
        align-items: center; justify-content: center; border: 1px solid; transition: all 0.15s;
        font-size: 0.82rem;
    }
    .mod-action-btn:hover { transform: translateY(-1px); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }

    /* ── Clickable row + content preview ── */
    .mod-table tbody tr.mod-row-main { cursor: pointer; }
    .mod-table tbody tr.mod-row-main:hover td { background: #f1f5f9; }
    .mod-row-detail { display: none; }
    .mod-row-detail.open { display: table-row; }
    .mod-content-card {
        background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 0.5rem;
        padding: 0.75rem 1rem; font-size: 0.84rem; color: #334155;
    }
    .mod-content-card .mod-content-label {
        font-size: 0.72rem; font-weight: 700; color: #64748b; text-transform: uppercase;
        letter-spacing: 0.03em; margin-bottom: 0.3rem; display: flex; align-items: center; gap: 0.3rem;
    }
    .mod-content-card .mod-content-title {
        font-weight: 700; color: #0f172a; font-size: 0.9rem; margin-bottom: 0.25rem;
    }
    .mod-content-card .mod-content-body {
        color: #475569; line-height: 1.5; word-break: break-word;
    }
    .mod-unit-badge {
        display: inline-flex; align-items: center; gap: 3px;
        background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe;
        border-radius: 0.35rem; padding: 2px 8px; font-size: 0.78rem; font-weight: 600;
    }
</style>

<!-- Header (same style as Calendar hero) -->
<div class="mod-hero">
    <div class="mod-hero-left">
        <h2 class="mod-hero-title">Moderación</h2>
        <div class="mod-hero-divider"></div>
        <div class="mod-hero-breadcrumb">
            <i class="bi bi-shield-exclamation"></i>
            <i class="bi bi-chevron-right"></i>
            Moderación de Contenido
        </div>
    </div>
    <div class="mod-hero-right">
        <button class="mod-hero-btn active" onclick="showTab('reports', this)">
            <i class="bi bi-flag"></i> Reportes
            <?php if ($pendingReports > 0): ?>
                <span class="badge bg-danger"><?= $pendingReports ?></span>
            <?php endif; ?>
        </button>
        <button class="mod-hero-btn outline" onclick="showTab('blocks', this)">
            <i class="bi bi-person-x"></i> Bloqueos
            <span class="badge bg-secondary"><?= $totalBlocks ?></span>
        </button>
    </div>
</div>

<!-- Flash messages -->
<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i><?= esc(session()->getFlashdata('success')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i><?= esc(session()->getFlashdata('error')) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="mod-stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="mod-stat-label">Pendientes</div>
                    <p class="mod-stat-value" style="color:#ef4444;"><?= $pendingReports ?></p>
                </div>
                <div class="mod-stat-icon" style="background:#fef2f2; color:#ef4444;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="mod-stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="mod-stat-label">Totales</div>
                    <p class="mod-stat-value" style="color:#3b82f6;"><?= $totalReports ?></p>
                </div>
                <div class="mod-stat-icon" style="background:#eff6ff; color:#3b82f6;">
                    <i class="bi bi-flag-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="mod-stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="mod-stat-label">Resueltos</div>
                    <p class="mod-stat-value" style="color:#10b981;"><?= $resolvedReports ?></p>
                </div>
                <div class="mod-stat-icon" style="background:#ecfdf5; color:#10b981;">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="mod-stat-card">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="mod-stat-label">Bloqueos</div>
                    <p class="mod-stat-value" style="color:#f59e0b;"><?= $totalBlocks ?></p>
                </div>
                <div class="mod-stat-icon" style="background:#fffbeb; color:#f59e0b;">
                    <i class="bi bi-person-fill-x"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- TAB: Reportes -->
<div id="panel-reports">
    <div class="mod-panel bg-white">
        <!-- Search bar -->
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom">
            <div class="mod-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" class="mod-search" placeholder="Buscar por nombre, motivo..." id="searchReports"
                       oninput="filterTable('mod-reports-table', this.value)">
            </div>
            <a href="<?= base_url('admin/moderacion') ?>" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-clockwise me-1"></i>Actualizar
            </a>
        </div>

        <?php if (empty($allReports)): ?>
            <div class="mod-empty">
                <div>
                    <div class="mod-empty-icon" style="border:1px solid #a7f3d0; color:#10b981; background:#ecfdf5;">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="mod-empty-title">Sin reportes</div>
                    <div class="mod-empty-desc">No se han recibido reportes de contenido. Cuando un residente reporte una publicación o comentario, aparecerá aquí.</div>
                </div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table mod-table align-middle mb-0" id="mod-reports-table">
                    <thead>
                        <tr>
                            <th style="width:45px">#</th>
                            <th>Reportado por</th>
                            <th>Unidad</th>
                            <th>Usuario reportado</th>
                            <th>Unidad</th>
                            <th>Motivo</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th style="width:130px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allReports as $r): ?>
                            <?php
                                $status = $r['status'] ?? 'pending';
                                $statusMeta = match($status) {
                                    'pending'      => ['dot' => '#ef4444', 'label' => 'Pendiente',     'bg' => 'bg-warning-subtle text-warning-emphasis'],
                                    'reviewed'     => ['dot' => '#3b82f6', 'label' => 'Revisado',      'bg' => 'bg-info-subtle text-info-emphasis'],
                                    'action_taken' => ['dot' => '#10b981', 'label' => 'Acción tomada', 'bg' => 'bg-success-subtle text-success-emphasis'],
                                    'dismissed'    => ['dot' => '#94a3b8', 'label' => 'Descartado',    'bg' => 'bg-secondary-subtle text-secondary-emphasis'],
                                    default        => ['dot' => '#94a3b8', 'label' => ucfirst($status),'bg' => 'bg-secondary-subtle text-secondary-emphasis'],
                                };
                                $reasonMeta = match($r['reason'] ?? '') {
                                    'spam'           => ['icon' => '📩', 'label' => 'Spam',       'bg' => '#fef2f2', 'color' => '#b91c1c'],
                                    'harassment'     => ['icon' => '😡', 'label' => 'Acoso',      'bg' => '#fff7ed', 'color' => '#c2410c'],
                                    'offensive'      => ['icon' => '🚫', 'label' => 'Ofensivo',   'bg' => '#fdf2f8', 'color' => '#be185d'],
                                    'misinformation' => ['icon' => '❌', 'label' => 'Info falsa', 'bg' => '#eff6ff', 'color' => '#1d4ed8'],
                                    'other'          => ['icon' => '📝', 'label' => 'Otro',       'bg' => '#f8fafc', 'color' => '#475569'],
                                    default          => ['icon' => '❓', 'label' => ucfirst($r['reason'] ?? 'N/A'), 'bg' => '#f8fafc', 'color' => '#475569'],
                                };
                                $typeLabel = !empty($r['comment_id']) ? 'Comentario' : 'Publicación';
                                $typeIcon  = !empty($r['comment_id']) ? 'bi-chat-dots' : 'bi-megaphone';
                                $reporterName = trim(($r['reporter_first_name'] ?? '') . ' ' . ($r['reporter_last_name'] ?? '')) ?: 'Anónimo';
                                $reportedName = trim(($r['reported_first_name'] ?? '') . ' ' . ($r['reported_last_name'] ?? '')) ?: 'N/A';
                                $reporterUnit = $r['reporter_unit'] ?? '';
                                $reportedUnit = $r['reported_unit'] ?? '';
                                // Contenido reportado
                                if (!empty($r['comment_id']) && !empty($r['comment_content'])) {
                                    $reportedContent = strip_tags($r['comment_content']);
                                    $reportedContentTitle = $r['announcement_title'] ?? '';
                                    $reportedContentType = 'Comentario';
                                } elseif (!empty($r['announcement_id'])) {
                                    $reportedContent = strip_tags($r['announcement_content'] ?? '');
                                    $reportedContentTitle = $r['announcement_title'] ?? '';
                                    $reportedContentType = 'Publicación';
                                } else {
                                    $reportedContent = '';
                                    $reportedContentTitle = '';
                                    $reportedContentType = '';
                                }
                            ?>
                            <tr class="mod-row-main" data-report-id="<?= $r['id'] ?>" data-search="<?= strtolower($reporterName . ' ' . $reportedName . ' ' . $reporterUnit . ' ' . $reportedUnit . ' ' . $reasonMeta['label'] . ' ' . $statusMeta['label']) ?>" onclick="toggleDetail(<?= $r['id'] ?>)">
                                <td class="text-muted fw-semibold"><?= esc((string)$r['id']) ?></td>
                                <td>
                                    <div class="fw-semibold" style="font-size:0.88rem;"><?= esc($reporterName) ?></div>
                                </td>
                                <td>
                                    <?php if ($reporterUnit): ?>
                                        <span class="mod-unit-badge"><i class="bi bi-building"></i> <?= esc($reporterUnit) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size:0.78rem;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="color:#ef4444; font-size:0.88rem;"><?= esc($reportedName) ?></div>
                                </td>
                                <td>
                                    <?php if ($reportedUnit): ?>
                                        <span class="mod-unit-badge"><i class="bi bi-building"></i> <?= esc($reportedUnit) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size:0.78rem;">—</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="mod-reason-badge" style="background:<?= $reasonMeta['bg'] ?>; color:<?= $reasonMeta['color'] ?>;">
                                        <?= $reasonMeta['icon'] ?> <?= $reasonMeta['label'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border" style="font-size:0.76rem;">
                                        <i class="bi <?= $typeIcon ?> me-1"></i><?= $typeLabel ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size:0.82rem;">
                                        <span class="mod-status-dot" style="background:<?= $statusMeta['dot'] ?>;"></span>
                                        <?= $statusMeta['label'] ?>
                                    </span>
                                </td>
                                <td class="text-muted" style="font-size:0.82rem;"><?= esc($timeAgo($r['created_at'] ?? '')) ?></td>
                                <td>
                                    <?php if ($status === 'pending'): ?>
                                        <div class="d-flex gap-1">
                                            <form method="post" action="<?= base_url('admin/moderacion/resolver/' . $r['id']) ?>">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="reviewed">
                                                <button class="mod-action-btn" style="border-color:#3b82f6; color:#3b82f6; background:#eff6ff;" title="Revisado">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </form>
                                            <form method="post" action="<?= base_url('admin/moderacion/resolver/' . $r['id']) ?>">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="action_taken">
                                                <button class="mod-action-btn" style="border-color:#10b981; color:#10b981; background:#ecfdf5;" title="Acción tomada">
                                                    <i class="bi bi-check-lg"></i>
                                                </button>
                                            </form>
                                            <form method="post" action="<?= base_url('admin/moderacion/resolver/' . $r['id']) ?>">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="dismissed">
                                                <button class="mod-action-btn" style="border-color:#94a3b8; color:#64748b; background:#f8fafc;" title="Descartar">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </form>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size:0.78rem;">Procesado</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="mod-row-detail" id="detail-<?= $r['id'] ?>">
                                <td></td>
                                <td colspan="9" class="pt-0 pb-3" style="border-bottom:none;">
                                    <?php if (!empty($r['description'])): ?>
                                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:0.4rem; padding:0.5rem 0.8rem; font-size:0.82rem; color:#475569; margin-bottom:0.5rem;">
                                            <i class="bi bi-chat-quote me-1 text-muted"></i><?= esc($r['description']) ?>
                                        </div>
                                    <?php endif; ?>
                                    <?php if (!empty($reportedContent)): ?>
                                        <div class="mod-content-card">
                                            <div class="mod-content-label">
                                                <i class="bi bi-<?= $reportedContentType === 'Comentario' ? 'chat-dots' : 'megaphone' ?>"></i>
                                                Contenido reportado (<?= $reportedContentType ?>)
                                            </div>
                                            <?php if ($reportedContentTitle): ?>
                                                <div class="mod-content-title"><?= esc($reportedContentTitle) ?></div>
                                            <?php endif; ?>
                                            <div class="mod-content-body"><?= esc(mb_strimwidth($reportedContent, 0, 800, '…')) ?></div>
                                        </div>
                                    <?php else: ?>
                                        <div class="mod-content-card" style="color:#94a3b8; font-style:italic;">
                                            <i class="bi bi-info-circle me-1"></i>Contenido no disponible o eliminado.
                                        </div>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- TAB: Bloqueos -->
<div id="panel-blocks" style="display:none;">
    <div class="mod-panel bg-white">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom">
            <div class="mod-search-wrap">
                <i class="bi bi-search"></i>
                <input type="text" class="mod-search" placeholder="Buscar por nombre..." id="searchBlocks"
                       oninput="filterTable('mod-blocks-table', this.value)">
            </div>
        </div>

        <?php if (empty($allBlocks)): ?>
            <div class="mod-empty">
                <div>
                    <div class="mod-empty-icon" style="border:1px solid #bfdbfe; color:#3b82f6; background:#eff6ff;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="mod-empty-title">Sin bloqueos</div>
                    <div class="mod-empty-desc">No hay bloqueos entre usuarios en esta comunidad. Cuando un residente bloquee a otro, aparecerá aquí.</div>
                </div>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table mod-table align-middle mb-0" id="mod-blocks-table">
                    <thead>
                        <tr>
                            <th style="width:45px">#</th>
                            <th>Quien bloqueó</th>
                            <th style="width:40px"></th>
                            <th>Usuario bloqueado</th>
                            <th>Fecha</th>
                            <th style="width:140px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($allBlocks as $b): ?>
                            <?php
                                $blockerName = trim(($b['blocker_first_name'] ?? '') . ' ' . ($b['blocker_last_name'] ?? '')) ?: 'N/A';
                                $blockedName = trim(($b['blocked_first_name'] ?? '') . ' ' . ($b['blocked_last_name'] ?? '')) ?: 'N/A';
                            ?>
                            <tr data-search="<?= strtolower($blockerName . ' ' . $blockedName) ?>">
                                <td class="text-muted fw-semibold"><?= esc((string)$b['id']) ?></td>
                                <td class="fw-semibold" style="font-size:0.88rem;"><?= esc($blockerName) ?></td>
                                <td class="text-center"><i class="bi bi-arrow-right text-muted"></i></td>
                                <td class="fw-semibold" style="color:#ef4444; font-size:0.88rem;"><?= esc($blockedName) ?></td>
                                <td class="text-muted" style="font-size:0.82rem;"><?= esc($timeAgo($b['created_at'] ?? '')) ?></td>
                                <td>
                                    <form method="post" action="<?= base_url('admin/moderacion/desbloquear/' . $b['id']) ?>"
                                          onsubmit="return confirm('¿Eliminar este bloqueo? Ambos usuarios volverán a ver el contenido del otro.')">
                                        <?= csrf_field() ?>
                                        <button class="btn btn-sm" style="background:#fef2f2; color:#ef4444; border:1px solid #fecaca; font-weight:600; font-size:0.82rem; border-radius:0.45rem;">
                                            <i class="bi bi-unlock me-1"></i>Desbloquear
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function showTab(tab, btn) {
    document.getElementById('panel-reports').style.display = tab === 'reports' ? '' : 'none';
    document.getElementById('panel-blocks').style.display  = tab === 'blocks'  ? '' : 'none';
    document.querySelectorAll('.mod-hero-btn').forEach(b => {
        b.classList.remove('active');
        b.classList.add('outline');
    });
    btn.classList.add('active');
    btn.classList.remove('outline');
}

function filterTable(tableId, query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        const search = row.getAttribute('data-search') || '';
        row.style.display = !q || search.includes(q) ? '' : 'none';
    });
}

function toggleDetail(reportId) {
    // Don't toggle if clicking action buttons/forms
    if (event && (event.target.closest('form') || event.target.closest('.mod-action-btn'))) return;
    const detail = document.getElementById('detail-' + reportId);
    if (detail) detail.classList.toggle('open');
}
</script>

<?= $this->endSection() ?>
