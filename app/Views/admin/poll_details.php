<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
// Calcula el estado
$nowTs = time();
$endTs = strtotime((string) ($poll['end_date'] ?? '')) ?: 0;
$isActive = (int) ($poll['is_active'] ?? 0) === 1;

$statusBadge = '<span class="status-dot draft"></span> Borrador';
if ($isActive) {
    if ($endTs > 0 && $nowTs > $endTs) {
        $statusBadge = '<span class="status-dot closed"></span> Cerrado';
    } else {
        $statusBadge = '<span class="status-dot active"></span> En votación';
    }
} elseif ($endTs > 0 && $nowTs > $endTs) {
    $statusBadge = '<span class="status-dot closed"></span> Cerrado';
}

$startTs = strtotime((string) ($poll['start_date'] ?? ''));
$createdTs = strtotime((string) ($poll['created_at'] ?? ''));

// Helper para fechas en español
$meses = ['Jan' => 'Ene', 'Feb' => 'Feb', 'Mar' => 'Mar', 'Apr' => 'Abr', 'May' => 'May', 'Jun' => 'Jun', 'Jul' => 'Jul', 'Aug' => 'Ago', 'Sep' => 'Sep', 'Oct' => 'Oct', 'Nov' => 'Nov', 'Dec' => 'Dic'];
$formatDateStr = function ($ts) use ($meses) {
    if (!$ts)
        return '--';
    return $meses[date('M', $ts)] . ' ' . date('j, Y', $ts);
};

// Calcula el tiempo restante
$timeRemaining = 'Configuración manual';
if ($isActive && $endTs > $nowTs) {
    $diff = $endTs - $nowTs;
    $days = floor($diff / 86400);
    $diff -= $days * 86400;
    $hours = floor($diff / 3600);
    $timeRemaining = "{$days}d {$hours}h";
} elseif ($endTs > 0 && $nowTs > $endTs) {
    $timeRemaining = 'Terminado';
}

$colors = ['c1', 'c2', 'c3', 'c4', 'c5'];
?>

<style>
    /* ── Hero ── */
    .cc-hero {
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

    .cc-hero-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .cc-hero-title {
        margin: 0;
        font-weight: 500;
        font-size: 1.05rem;
        color: #475569;
    }

    .cc-hero-divider {
        width: 1px;
        height: 22px;
        background-color: #cbd5e1;
    }

    .cc-hero-breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.85rem;
        color: #64748b;
    }

    .cc-hero-breadcrumb i.bi-house-door {
        color: #3b82f6;
        font-size: 0.95rem;
    }

    .cc-hero-breadcrumb i.bi-chevron-right {
        font-size: 0.65rem;
        color: #94a3b8;
    }

    .cc-hero-btn {
        background: #238b71ff;
        color: #ffffff;
        border: none;
        border-radius: 0.45rem;
        padding: 0.65rem 1.4rem;
        font-size: 0.98rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
        cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
        transition: all 0.25s ease;
    }

    .cc-hero-btn:hover {
        background: #5cad99ff;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .stats-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.25rem;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stat-card .icon-box {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .stat-card.s-status .icon-box {
        background: #dcfce7;
        color: #16a34a;
    }

    .stat-card.s-votes .icon-box {
        background: #e0f2fe;
        color: #0284c7;
    }

    .stat-card.s-rate .icon-box {
        background: #ffedd5;
        color: #ea580c;
    }

    .stat-card.s-time .icon-box {
        background: #f3e8ff;
        color: #9333ea;
    }

    .stat-info h3 {
        margin: 0;
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
    }

    .stat-info p {
        margin: 0;
        font-size: 0.75rem;
        color: #64748b;
    }

    .status-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #94a3b8;
    }

    .status-dot.active {
        background: #16a34a;
    }

    .status-dot.closed {
        background: #475569;
    }

    .main-grid {
        display: grid;
        grid-template-columns: 220px 1fr 280px;
        gap: 1.25rem;
    }

    .sidebar-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .sidebar-card h5 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1rem;
    }

    .action-panel h6 {
        font-size: 0.8rem;
        font-weight: bold;
        color: #0f172a;
        margin-top: 1rem;
    }

    .action-panel .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: 0.8rem;
        color: #475569;
    }

    .btn-close-poll {
        background: transparent;
        border: 1px solid #fbbf24;
        color: #d97706;
        width: 100%;
        border-radius: 4px;
        padding: 0.4rem;
        font-size: 0.85rem;
        font-weight: 600;
        margin-top: 1.5rem;
        transition: all 0.2s;
    }

    .btn-close-poll:hover {
        background: #fef3c7;
    }

    .btn-delete-poll {
        background: #ef4444;
        border: 1px solid #dc2626;
        color: #fff;
        width: 100%;
        border-radius: 4px;
        padding: 0.4rem;
        font-size: 0.85rem;
        font-weight: 600;
        text-align: center;
        display: block;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-delete-poll:hover {
        background: #dc2626;
        color: #fff;
    }

    .votes-container {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1.4rem;
    }

    .votes-container h5 {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: #1e293b;
    }

    .vote-item {
        margin-bottom: 1.2rem;
    }

    .vote-item-header {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        color: #334155;
    }

    .vote-bar-bg {
        background: #f1f5f9;
        height: 12px;
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }

    .vote-bar-fill {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        border-radius: 12px;
        transition: width 0.5s ease;
    }

    .vote-bar-fill.c1 {
        background: #3b82f6;
    }

    .vote-bar-fill.c2 {
        background: #10b981;
    }

    .vote-bar-fill.c3 {
        background: #f59e0b;
    }

    .vote-bar-fill.c4 {
        background: #ef4444;
    }

    .vote-bar-fill.c5 {
        background: #8b5cf6;
    }

    .waiting-votes {
        text-align: center;
        margin-top: 4rem;
        margin-bottom: 3rem;
    }

    .waiting-votes .icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #eff6ff;
        color: #3b82f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin: 0 auto 1rem;
    }

    .waiting-votes h4 {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.4rem;
    }

    .waiting-votes p {
        font-size: 0.85rem;
        color: #64748b;
        max-width: 320px;
        margin: 0 auto 1.5rem;
    }

    .legend {
        display: flex;
        justify-content: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 1rem;
    }

    .legend-item {
        font-size: 0.75rem;
        color: #475569;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .detail-item {
        margin-bottom: 1.1rem;
    }

    .detail-item label {
        display: block;
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.15rem;
    }

    .detail-item .val {
        font-size: 0.85rem;
        color: #1e293b;
        font-weight: 500;
    }

    @media (max-width: 992px) {
        .stats-row {
            grid-template-columns: repeat(2, 1fr);
        }

        .main-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 576px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
    }
</style>



<div class="cc-hero">
    <div class="cc-hero-left">
        <a href="<?= base_url('/admin/encuestas') ?>" class="btn-back"><i class="bi bi-arrow-left"></i></a>
        <h2 class="cc-hero-title">Encuestas</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-check2-square"></i>
            <i class="bi bi-chevron-right"></i>
            Detalles de la encuesta <i class="bi bi-chevron-right"></i><?= esc($poll['title']) ?>
        </div>
    </div>

</div>

<div class="stats-row">
    <div class="stat-card s-status">
        <div class="icon-box"><i class="bi bi-circle-fill" style="font-size:10px;"></i></div>
        <div class="stat-info">
            <h3><?= $statusBadge ?></h3>
            <p>Estado</p>
        </div>
    </div>
    <div class="stat-card s-votes">
        <div class="icon-box"><i class="bi bi-people"></i></div>
        <div class="stat-info">
            <h3><?= number_format($totalVotes) ?></h3>
            <p>Total de Votos</p>
        </div>
    </div>
    <div class="stat-card s-rate">
        <div class="icon-box"><i class="bi bi-bar-chart"></i></div>
        <div class="stat-info">
            <h3><?= $participationRate ?>%</h3>
            <p>Tasa de participación</p>
        </div>
    </div>
    <div class="stat-card s-time">
        <div class="icon-box"><i class="bi bi-stopwatch"></i></div>
        <div class="stat-info">
            <h3><?= $timeRemaining ?></h3>
            <p>Tiempo restante</p>
        </div>
    </div>
</div>

<div class="main-grid">
    <!-- Left Column (Actions) -->
    <div>
        <div class="sidebar-card action-panel">
            <h5><i class="bi bi-lightning-charge"></i> Acciones</h5>
            <p class="text-truncate-2"><?= esc($poll['title']) ?></p>
            <div class="small text-muted mb-3 d-flex flex-column gap-1">
                <span><i class="bi bi-ui-radios me-1"></i> <?= count($options) ?> opciones</span>
                <span><i class="bi bi-calendar me-1"></i> <?= $startTs ? $formatDateStr($startTs) : 'Borrador' ?></span>
            </div>

            <div class="mt-4 mb-2">
                <?= $statusBadge ?>
            </div>

            <?php if ($isActive && ($endTs == 0 || $endTs > $nowTs)): ?>
                <button class="btn-close-poll" onclick="confirmClosePoll(event, '<?= esc($poll['hash_id']) ?>')">
                    <i class="bi bi-x-circle me-1"></i> Cerrar Encuesta
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Middle Column (Bars) -->
    <div>
        <div class="votes-container">
            <h5><?= esc($poll['title']) ?></h5>
            <?php if (!empty($poll['description'])): ?>
                <p class="text-muted mb-4" style="font-size: 0.95rem;"><?= nl2br(esc($poll['description'])) ?></p>
            <?php endif; ?>

            <?php foreach ($options as $idx => $opt): ?>
                <?php $colClass = $colors[$idx % count($colors)]; ?>
                <div class="vote-item">
                    <div class="vote-item-header">
                        <span><?= esc($opt['option_text']) ?></span>
                        <div><span class="text-muted fw-normal me-2"><?= $opt['vote_count'] ?> votos</span>
                            <?= $opt['percentage'] ?>%</div>
                    </div>
                    <div class="vote-bar-bg">
                        <div class="vote-bar-fill <?= $colClass ?>" style="width: <?= $opt['percentage'] ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($totalVotes === 0): ?>
                <div class="waiting-votes">
                    <div class="icon"><i class="bi bi-check2-square"></i></div>
                    <h4>Esperando votos</h4>
                    <p>Los residentes ya fueron notificados. Los resultados aparecerán aquí a medida que voten.</p>
                </div>
            <?php endif; ?>

            <div class="legend">
                <?php foreach ($options as $idx => $opt): ?>
                    <?php $colClass = $colors[$idx % count($colors)]; ?>
                    <div class="legend-item">
                        <span class="status-dot <?= $colClass ?>"
                            style="background: var(--bs-<?= ['primary', 'success', 'warning', 'danger', 'purple'][$idx % 5] ?>)"></span>
                        <?= esc($opt['option_text']) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (isset($voterDetails) && !empty($voterDetails)): ?>
        <div class="votes-container mt-4">
            <h5><i class="bi bi-card-list me-1"></i> Registro de Votantes</h5>
            <div class="table-responsive mt-3">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="text-muted fw-semibold">Residente</th>
                            <th class="text-muted fw-semibold">Unidad</th>
                            <th class="text-muted fw-semibold">Opción Elegida</th>
                            <th class="text-muted fw-semibold">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($voterDetails as $vd): ?>
                            <tr>
                                <td><?= esc(trim(($vd['first_name'] ?? '') . ' ' . ($vd['last_name'] ?? ''))) ?: 'Usuario Desconocido' ?></td>
                                <td>
                                    <?php if (!empty($vd['unit_name'])): ?>
                                        <span class="badge bg-light text-dark border"><?= esc($vd['unit_name']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge" style="background: #eef2f7; color: #334155; border: 1px solid #d0d8e2;">
                                        <?= esc($vd['option_chosen']) ?>
                                    </span>
                                </td>
                                <td><span class="text-muted"><?= date('d', strtotime($vd['created_at'])) . ' ' . $meses[date('M', strtotime($vd['created_at']))] . ' ' . date('Y, H:i', strtotime($vd['created_at'])) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right Column (Config) -->
    <div>
        <div class="sidebar-card">
            <h5>Detalles de la encuesta</h5>

            <div class="detail-item">
                <label><i class="bi bi-calendar-range"></i> Período de Encuesta</label>
                <div class="val"><?= $startTs ? $formatDateStr($startTs) : '--' ?> &rarr;
                    <?= $endTs > 0 ? $formatDateStr($endTs) : '--' ?>
                </div>
            </div>

            <div class="detail-item">
                <label><i class="bi bi-person"></i> Total de Votos</label>
                <div class="val"><?= number_format($totalVotes) ?></div>
            </div>

            <div class="detail-item">
                <label><i class="bi bi-clock-history"></i> Creado el</label>
                <div class="val"><?= $createdTs ? $formatDateStr($createdTs) : '--' ?></div>
            </div>
        </div>

        <div class="sidebar-card">
            <h5>Configuración</h5>

            <div class="detail-item">
                <label>Tipo de Encuesta</label>
                <div class="val">Opción Única</div>
            </div>

            <div class="detail-item">
                <label>Categoría</label>
                <div class="val"><?= esc($poll['category'] ?? 'General') ?></div>
            </div>

            <div class="detail-item">
                <label>Visibilidad</label>
                <div class="val">Todos los residentes (<span class="text-secondary">~<?= $totalResidents ?></span>)
                </div>
            </div>

            <div class="detail-item">
                <label>Permitir Votación Anónima</label>
                <div class="val">No</div>
            </div>

            <div class="detail-item">
                <label>Visibilidad de resultados</label>
                <div class="val">Siempre</div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <a href="#" class="btn-delete-poll" onclick="confirmDelete(event, '<?= esc($poll['hash_id']) ?>')"><i
                        class="bi bi-trash"></i> Eliminar</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showToast(icon, title) {
        if (window.Swal) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true
            });
        } else {
            alert(title);
        }
    }

    function confirmDelete(e, pollId) {
        e.preventDefault();
        if (window.Swal && window.Swal.fire) {
            Swal.fire({
                title: '¿Eliminar encuesta?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= base_url("admin/encuestas/eliminar") ?>/' + pollId, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        }
                    }).then(res => res.json()).then(data => {
                        if (data.status === 200) {
                            showToast('success', data.message);
                            setTimeout(() => window.location.href = '<?= base_url("admin/encuestas") ?>', 2000);
                        } else {
                            showToast('error', data.error || 'No se pudo eliminar');
                        }
                    }).catch(() => showToast('error', 'Error de red'));
                }
            });
        }
    }

    function confirmClosePoll(e, pollId) {
        e.preventDefault();
        if (window.Swal && window.Swal.fire) {
            Swal.fire({
                title: 'Cerrar encuesta',
                text: 'Al cerrar esta encuesta, dejará de aceptar votos.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#475569',
                cancelButtonColor: '#fff',
                confirmButtonText: 'Cerrar Encuesta',
                cancelButtonText: '<span style="color:#475569">Cancelar</span>'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch('<?= base_url("admin/encuestas/cerrar") ?>/' + pollId, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        }
                    }).then(res => res.json()).then(data => {
                        if (data.status === 200) {
                            showToast('success', data.message);
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showToast('error', data.error || 'No se pudo cerrar');
                        }
                    }).catch(() => showToast('error', 'Error de red'));
                }
            });
        }
    }
</script>

<?= $this->endSection() ?>