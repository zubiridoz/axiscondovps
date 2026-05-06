<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="koti-header d-flex justify-content-between align-items-center">
    <div>
        <h4 class="mb-1 fw-bold"><i class="bi bi-shield-exclamation me-2"></i>Moderación de Contenido</h4>
        <small class="opacity-75">Apple Guideline 1.2 — Reportes y bloqueos de la comunidad</small>
    </div>
</div>

<!-- Estadísticas -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="koti-card text-center">
            <div style="font-size: 2rem; font-weight: 800; color: #ef4444;"><?= esc((string)($stats['pending_reports'] ?? 0)) ?></div>
            <div class="text-muted small fw-semibold">Reportes Pendientes</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="koti-card text-center">
            <div style="font-size: 2rem; font-weight: 800; color: #3b82f6;"><?= esc((string)($stats['total_reports'] ?? 0)) ?></div>
            <div class="text-muted small fw-semibold">Reportes Totales</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="koti-card text-center">
            <div style="font-size: 2rem; font-weight: 800; color: #10b981;"><?= esc((string)($stats['resolved_reports'] ?? 0)) ?></div>
            <div class="text-muted small fw-semibold">Reportes Resueltos</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="koti-card text-center">
            <div style="font-size: 2rem; font-weight: 800; color: #f59e0b;"><?= esc((string)($stats['total_blocks'] ?? 0)) ?></div>
            <div class="text-muted small fw-semibold">Bloqueos Activos</div>
        </div>
    </div>
</div>

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

<!-- Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item">
        <a class="nav-link active fw-semibold" data-bs-toggle="tab" href="#tab-reports">
            <i class="bi bi-flag me-1"></i> Reportes
            <?php if (($stats['pending_reports'] ?? 0) > 0): ?>
                <span class="badge bg-danger ms-1"><?= esc((string)$stats['pending_reports']) ?></span>
            <?php endif; ?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link fw-semibold" data-bs-toggle="tab" href="#tab-blocks">
            <i class="bi bi-person-x me-1"></i> Bloqueos
            <span class="badge bg-secondary ms-1"><?= esc((string)($stats['total_blocks'] ?? 0)) ?></span>
        </a>
    </li>
</ul>

<div class="tab-content">
    <!-- TAB: Reportes -->
    <div class="tab-pane fade show active" id="tab-reports">
        <div class="koti-card">
            <?php if (empty($reports)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-check-circle" style="font-size: 3rem; color: #10b981;"></i>
                    <h5 class="mt-3 fw-bold">Sin reportes</h5>
                    <p class="text-muted">No se han recibido reportes de contenido en esta comunidad.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px">#</th>
                                <th>Reportado por</th>
                                <th>Usuario reportado</th>
                                <th>Motivo</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th style="width:140px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($reports as $r): ?>
                                <?php
                                    $status = $r['status'] ?? 'pending';
                                    $badgeClass = match($status) {
                                        'pending' => 'bg-warning text-dark',
                                        'reviewed' => 'bg-info',
                                        'action_taken' => 'bg-success',
                                        'dismissed' => 'bg-secondary',
                                        default => 'bg-secondary',
                                    };
                                    $badgeLabel = match($status) {
                                        'pending' => 'Pendiente',
                                        'reviewed' => 'Revisado',
                                        'action_taken' => 'Acción tomada',
                                        'dismissed' => 'Descartado',
                                        default => ucfirst($status),
                                    };
                                    $reasonLabel = match($r['reason'] ?? '') {
                                        'spam' => '🚫 Spam',
                                        'harassment' => '⚠️ Acoso',
                                        'offensive' => '🔞 Ofensivo',
                                        'misinformation' => '📰 Info falsa',
                                        'other' => '📝 Otro',
                                        default => ucfirst($r['reason'] ?? 'N/A'),
                                    };
                                    $typeLabel = !empty($r['comment_id']) ? 'Comentario' : 'Publicación';
                                    $typeIcon = !empty($r['comment_id']) ? 'bi-chat-dots' : 'bi-megaphone';
                                ?>
                                <tr>
                                    <td class="text-muted"><?= esc((string)$r['id']) ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= esc(trim(($r['reporter_first_name'] ?? '') . ' ' . ($r['reporter_last_name'] ?? ''))) ?: 'Anónimo' ?></div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-danger"><?= esc(trim(($r['reported_first_name'] ?? '') . ' ' . ($r['reported_last_name'] ?? ''))) ?: 'N/A' ?></div>
                                    </td>
                                    <td><?= $reasonLabel ?></td>
                                    <td><span class="badge bg-light text-dark"><i class="bi <?= $typeIcon ?> me-1"></i><?= $typeLabel ?></span></td>
                                    <td><span class="badge <?= $badgeClass ?>"><?= $badgeLabel ?></span></td>
                                    <td class="text-muted small"><?= esc($r['created_at'] ?? '') ?></td>
                                    <td>
                                        <?php if ($status === 'pending'): ?>
                                            <div class="btn-group btn-group-sm">
                                                <form method="post" action="<?= base_url('admin/moderacion/resolver/' . $r['id']) ?>" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="action" value="reviewed">
                                                    <button class="btn btn-outline-primary btn-sm" title="Marcar revisado">
                                                        <i class="bi bi-eye"></i>
                                                    </button>
                                                </form>
                                                <form method="post" action="<?= base_url('admin/moderacion/resolver/' . $r['id']) ?>" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="action" value="action_taken">
                                                    <button class="btn btn-outline-success btn-sm" title="Acción tomada">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <form method="post" action="<?= base_url('admin/moderacion/resolver/' . $r['id']) ?>" class="d-inline">
                                                    <?= csrf_field() ?>
                                                    <input type="hidden" name="action" value="dismissed">
                                                    <button class="btn btn-outline-secondary btn-sm" title="Descartar">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted small">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if (!empty($r['description'])): ?>
                                    <tr>
                                        <td></td>
                                        <td colspan="7" class="pt-0 pb-2">
                                            <small class="text-muted"><i class="bi bi-chat-quote me-1"></i><?= esc($r['description']) ?></small>
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
    <div class="tab-pane fade" id="tab-blocks">
        <div class="koti-card">
            <?php if (empty($blocks)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-people" style="font-size: 3rem; color: #3b82f6;"></i>
                    <h5 class="mt-3 fw-bold">Sin bloqueos</h5>
                    <p class="text-muted">No hay bloqueos entre usuarios en esta comunidad.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:50px">#</th>
                                <th>Usuario que bloqueó</th>
                                <th><i class="bi bi-arrow-right"></i></th>
                                <th>Usuario bloqueado</th>
                                <th>Fecha</th>
                                <th style="width:120px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($blocks as $b): ?>
                                <tr>
                                    <td class="text-muted"><?= esc((string)$b['id']) ?></td>
                                    <td class="fw-semibold"><?= esc(trim(($b['blocker_first_name'] ?? '') . ' ' . ($b['blocker_last_name'] ?? ''))) ?: 'N/A' ?></td>
                                    <td class="text-center"><i class="bi bi-arrow-right text-muted"></i></td>
                                    <td class="fw-semibold text-danger"><?= esc(trim(($b['blocked_first_name'] ?? '') . ' ' . ($b['blocked_last_name'] ?? ''))) ?: 'N/A' ?></td>
                                    <td class="text-muted small"><?= esc($b['created_at'] ?? '') ?></td>
                                    <td>
                                        <form method="post" action="<?= base_url('admin/moderacion/desbloquear/' . $b['id']) ?>"
                                              onsubmit="return confirm('¿Eliminar este bloqueo? Ambos usuarios volverán a ver el contenido del otro.')">
                                            <?= csrf_field() ?>
                                            <button class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-unlock me-1"></i> Desbloquear
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
</div>

<?= $this->endSection() ?>
