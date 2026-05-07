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
    .mod-header {
        background: linear-gradient(135deg, #2f3a4d 0%, #243246 100%);
        border-radius: 0.6rem;
        padding: 1.5rem;
        color: #fff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.16);
    }
    .mod-header p { color: rgba(255,255,255,0.8); margin-bottom: 0; font-size: 0.92rem; }

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

    .mod-tab-btn {
        background: rgba(255,255,255,0.13); color: #fff; border: 1px solid rgba(255,255,255,0.16);
        font-size: 0.82rem; font-weight: 600; border-radius: 0.45rem; padding: 0.4rem 0.9rem;
        transition: background 0.15s;
    }
    .mod-tab-btn:hover, .mod-tab-btn.active { background: rgba(255,255,255,0.22); color: #fff; border-color: rgba(255,255,255,0.3); }

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
</style>

<!-- Header -->
<div class="mod-header mb-4">
    <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h4 class="mb-1 fw-bold"><i class="bi bi-shield-exclamation me-2"></i>Moderación de Contenido</h4>
            <p>Apple Guideline 1.2 — Reportes y bloqueos de usuarios de la comunidad</p>
        </div>
        <div class="d-flex gap-2">
            <button class="mod-tab-btn active" onclick="showTab('reports', this)">
                <i class="bi bi-flag me-1"></i>Reportes
                <?php if ($pendingReports > 0): ?>
                    <span class="badge bg-danger ms-1" style="font-size:0.65rem;"><?= $pendingReports ?></span>
                <?php endif; ?>
            </button>
            <button class="mod-tab-btn" onclick="showTab('blocks', this)">
                <i class="bi bi-person-x me-1"></i>Bloqueos
                <span class="badge bg-light text-dark ms-1" style="font-size:0.65rem;"><?= $totalBlocks ?></span>
            </button>
        </div>
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
                            <th>Usuario reportado</th>
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
                            ?>
                            <tr data-search="<?= strtolower($reporterName . ' ' . $reportedName . ' ' . $reasonMeta['label'] . ' ' . $statusMeta['label']) ?>">
                                <td class="text-muted fw-semibold"><?= esc((string)$r['id']) ?></td>
                                <td>
                                    <div class="fw-semibold" style="font-size:0.88rem;"><?= esc($reporterName) ?></div>
                                </td>
                                <td>
                                    <div class="fw-semibold" style="color:#ef4444; font-size:0.88rem;"><?= esc($reportedName) ?></div>
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
                            <?php if (!empty($r['description'])): ?>
                                <tr data-search="<?= strtolower($r['description']) ?>">
                                    <td></td>
                                    <td colspan="7" class="pt-0 pb-2" style="border-bottom:none;">
                                        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:0.4rem; padding:0.5rem 0.8rem; font-size:0.82rem; color:#475569;">
                                            <i class="bi bi-chat-quote me-1 text-muted"></i><?= esc($r['description']) ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
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
    document.querySelectorAll('.mod-tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
}

function filterTable(tableId, query) {
    const q = query.toLowerCase().trim();
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        const search = row.getAttribute('data-search') || '';
        row.style.display = !q || search.includes(q) ? '' : 'none';
    });
}
</script>

<?= $this->endSection() ?>
