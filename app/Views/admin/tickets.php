<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$currentView = (string) ($current_view ?? 'lista');
$isList = $currentView === 'lista';
$isPanel = $currentView === 'panel';
$isMetrics = $currentView === 'metricas';

$tray = (string) ($tray ?? 'activos');
$tickets = is_array($tickets ?? null) ? $tickets : [];
$activeCount = (int) ($active_count ?? 0);
$archiveCount = (int) ($archive_count ?? 0);

$totals = is_array($totals ?? null) ? $totals : [];
$aging = is_array($aging ?? null) ? $aging : [];
$alerts = is_array($alerts ?? null) ? $alerts : [];
$trend = is_array($trend ?? null) ? $trend : ['points' => [], 'max_volume' => 1, 'max_backlog' => 1, 'net_change' => 0, 'created_total' => 0, 'resolved_total' => 0];
$categories = is_array($categories ?? null) ? $categories : [];
$resolution = is_array($resolution ?? null) ? $resolution : ['total_reports' => 0, 'open_reports' => 0, 'resolved_reports' => 0, 'avg_resolution' => '0m', 'resolution_rate' => 0];

$baseTickets = base_url('admin/tickets');
$panelUrl = base_url('admin/tickets/panel');
$metricsUrl = base_url('admin/tickets/metricas');
$activeTrayUrl = $baseTickets . '?bandeja=activos';
$archiveTrayUrl = $baseTickets . '?bandeja=archivo';

$period = (string) ($period ?? 'mes');
$periodLabels = [
    'semana' => 'Semana',
    'mes' => 'Mes',
    'trimestre' => 'Trimestre',
    'anio' => 'Año',
];
$periodUrls = [
    'semana' => $metricsUrl . '?periodo=semana',
    'mes' => $metricsUrl . '?periodo=mes',
    'trimestre' => $metricsUrl . '?periodo=trimestre',
    'anio' => $metricsUrl . '?periodo=anio',
];
?>

<style>
    .tickets-hero {
        background: linear-gradient(135deg, #364861 0%, #1f2b42 100%);
        border-radius: 0.6rem;
        padding: 1.55rem;
        color: #fff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.16);
    }

    .tickets-hero p {
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 0.92rem;
    }

    .tickets-hero-action {
        background: rgba(255, 255, 255, 0.14);
        border: 1px solid rgba(255, 255, 255, 0.18);
        color: #fff;
        font-size: 0.86rem;
        font-weight: 600;
    }

    .tickets-hero-action:hover {
        background: rgba(255, 255, 255, 0.22);
        border-color: rgba(255, 255, 255, 0.24);
        color: #fff;
    }

    .tickets-back-link {
        width: 30px;
        height: 30px;
        border-radius: 0.45rem;
        border: 1px solid rgba(255, 255, 255, 0.15);
        background: rgba(255, 255, 255, 0.14);
        color: rgba(255, 255, 255, 0.92);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .tickets-back-link:hover {
        color: #fff;
        background: rgba(255, 255, 255, 0.24);
        transform: translateX(-1px);
    }

    .tickets-card {
        border: none;
        border-radius: 0.6rem;
        background: #fff;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.05);
    }

    .tickets-kpi {
        border: 1px solid #d9e1eb;
        border-radius: 0.6rem;
        background: #fff;
        padding: 0.95rem 1.15rem;
    }

    .tickets-kpi-title {
        color: #64748b;
        font-size: 0.84rem;
        margin-bottom: 0.35rem;
    }

    .tickets-kpi-value {
        font-size: 2rem;
        line-height: 1;
        margin: 0;
        font-weight: 700;
        color: #0f172a;
    }

    .tickets-kpi-note {
        font-size: 0.82rem;
        margin-top: 0.5rem;
        color: #64748b;
    }

    .tickets-kpi-icon {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .tickets-filter-pill {
        background: #eef2f7;
        border-radius: 0.45rem;
        padding: 0.24rem;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .tickets-filter-pill a {
        text-decoration: none;
        color: #57708f;
        font-size: 0.92rem;
        font-weight: 500;
        padding: 0.38rem 0.8rem;
        border-radius: 0.35rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }

    .tickets-filter-pill a.active {
        background: #fff;
        color: #0f172a;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
    }

    .tickets-search {
        border: 1px solid #d0d8e2;
        border-radius: 0.45rem;
        padding: 0.55rem 0.8rem 0.55rem 2rem;
        font-size: 0.9rem;
        color: #334155;
        min-width: 300px;
        background: #fff;
    }

    .tickets-search-wrap {
        position: relative;
    }

    .tickets-search-wrap i {
        position: absolute;
        left: 0.68rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .tickets-tool-btn {
        border: 1px solid #d0d8e2;
        background: #fff;
        color: #0f172a;
        font-size: 0.9rem;
        border-radius: 0.45rem;
        padding: 0.48rem 0.85rem;
    }

    .tickets-tool-btn:hover {
        background: #f8fafc;
        border-color: #c0cad7;
    }

    .tickets-table thead th {
        color: #64748b;
        font-size: 0.72rem;
        font-weight: 600;
        letter-spacing: 0.01em;
        text-transform: none;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.85rem 0.6rem;
        white-space: nowrap;
        background: #fdfdfd;
    }

    .tickets-table tbody td {
        padding: 0.65rem 0.6rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #475569;
        font-size: 0.82rem;
    }

    .tickets-row-attention {
        border-left: 2px solid #f59e0b;
    }

    .tickets-avatar {
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #1e293b;
        font-size: 0.62rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .tickets-empty {
        border: 1px dashed #d9e1eb;
        border-radius: 0.7rem;
        padding: 2.4rem 1rem;
        text-align: center;
        color: #64748b;
        background: #fbfdff;
    }

    .tickets-progress-track {
        background: #e8edf3;
        height: 4px;
        border-radius: 999px;
        overflow: hidden;
    }

    .tickets-progress-fill {
        height: 100%;
    }

    .tickets-person-load {
        border: 2px dashed #d3dbe6;
        border-radius: 0.7rem;
        padding: 0.8rem;
    }

    .tickets-period-group {
        background: #eef2f7;
        border-radius: 0.5rem;
        padding: 0.2rem;
        display: inline-flex;
        gap: 0.2rem;
    }

    .tickets-period-group a {
        text-decoration: none;
        color: #57708f;
        font-size: 0.95rem;
        font-weight: 500;
        padding: 0.35rem 0.8rem;
        border-radius: 0.35rem;
    }

    .tickets-period-group a.active {
        background: #fff;
        color: #0f172a;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.08);
    }

    .tickets-chart-wrap {
        min-height: 260px;
    }

    .tickets-chart-bars {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        min-height: 180px;
        padding: 1.2rem 0 0.4rem;
        border-bottom: 1px solid #e8edf3;
    }

    .tickets-chart-group {
        width: 34px;
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 3px;
        position: relative;
    }

    .tickets-chart-bar {
        width: 10px;
        border-radius: 4px 4px 0 0;
        min-height: 2px;
    }

    .tickets-chart-created {
        background: #2f465a;
    }

    .tickets-chart-resolved {
        background: #1f9f92;
    }

    .tickets-chart-labels {
        display: flex;
        gap: 10px;
    }

    .tickets-chart-labels span {
        width: 34px;
        text-align: center;
        color: #64748b;
        font-size: 0.72rem;
    }

    .tickets-legend {
        display: flex;
        justify-content: center;
        gap: 0.9rem;
        color: #334155;
        font-size: 0.8rem;
        margin-top: 0.9rem;
    }

    .tickets-dot {
        width: 8px;
        height: 8px;
        border-radius: 2px;
        display: inline-block;
    }

    @media (max-width: 1200px) {
        .tickets-table {
            min-width: 1250px;
        }
    }

    @media (max-width: 991px) {
        .tickets-hero .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1rem;
        }

        .tickets-search {
            min-width: 100%;
            width: 100%;
        }
    }

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

    /* ── end Hero ── */
</style>

<?php if ($isList): ?>


    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <h2 class="cc-hero-title">Tickets</h2>
            <div class="cc-hero-divider"></div>
            <div class="cc-hero-breadcrumb">
                <i class="bi bi-exclamation-circle"></i>
                <i class="bi bi-chevron-right"></i>
                Atención a oportunidades de mejoras
            </div>
        </div>
        <div class="cc-hero-right">
            <button type="button" data-bs-toggle="modal" data-bs-target="#modalNewTicket" class="cc-hero-btn"><i
                    class="bi bi-plus-lg me-1"></i> Nuevo
                Ticket</button>
        </div>
    </div>
    <!-- ── END Hero ── -->




    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
        <div class="tickets-filter-pill">
            <a href="<?= esc($activeTrayUrl) ?>" class="<?= $tray === 'activos' ? 'active' : '' ?>">
                <i class="bi bi-inbox"></i> Activos <span
                    class="badge text-bg-light"><?= esc((string) $activeCount) ?></span>
            </a>
            <a href="<?= esc($archiveTrayUrl) ?>" class="<?= $tray === 'archivo' ? 'active' : '' ?>">
                <i class="bi bi-archive"></i> Archivo <span
                    class="badge text-bg-light"><?= esc((string) $archiveCount) ?></span>
            </a>
        </div>

    </div>

    <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
        <div class="tickets-search-wrap">
            <i class="bi bi-search"></i>
            <input id="ticket-search" class="tickets-search" type="text" placeholder="Buscar tickets...">
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button class="tickets-tool-btn"><i class="bi bi-funnel me-1"></i> Agregar filtro</button>
            <button class="tickets-tool-btn"><i class="bi bi-bookmark me-1"></i> Vistas Guardadas</button>
            <button class="tickets-tool-btn"><i class="bi bi-layout-three-columns me-1"></i> Columnas</button>
            <button class="tickets-tool-btn"><i class="bi bi-download me-1"></i> Exportar</button>
        </div>
    </div>

    <div class="tickets-card" style="overflow: visible;">
        <table class="table tickets-table mb-0">
            <thead>
                <tr>
                    <th style="width:34px; border-top-left-radius: 0.5rem;"><input type="checkbox" class="form-check-input"
                            style="border-color:#cbd5e1; border-radius:0.25rem;"></th>
                    <th>N° Reporte</th>
                    <th>Categoria <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem; opacity:0.4;"></i></th>
                    <th>Estado <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem; opacity:0.4;"></i></th>
                    <th>Prioridad <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem; opacity:0.4;"></i></th>
                    <th>Descripción</th>
                    <th>Reportado por</th>
                    <th>Reportado en <i class="bi bi-arrow-down ms-1" style="font-size:0.6rem; opacity:0.7;"></i></th>
                    <th>Tiempo en Estado <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem; opacity:0.4;"></i>
                    </th>
                    <th>Días Abierto <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem; opacity:0.4;"></i></th>
                    <th>Asignado a</th>
                    <th>Unidad</th>
                    <th>Última Actividad <i class="bi bi-arrow-down-up ms-1" style="font-size:0.6rem; opacity:0.4;"></i>
                    </th>
                    <th class="text-end" style="border-top-right-radius: 0.5rem;">Acciones</th>
                </tr>
            </thead>
            <tbody id="tickets-table-body">
                <?php if (empty($tickets)): ?>
                    <tr>
                        <td colspan="14">
                            <div class="tickets-empty my-3 mx-2">
                                <i class="bi bi-check2-circle fs-3 d-block mb-2 text-success"></i>
                                No hay tickets en esta bandeja.
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($tickets as $item): ?>
                        <tr class="<?= $item['is_attention'] ? 'tickets-row-attention' : '' ?>"
                            data-search="<?= esc($item['search']) ?>" style="cursor:pointer; transition: background 0.15s;"
                            onmouseover="this.style.background='#f8fafc';" onmouseout="this.style.background='transparent';"
                            onclick='if(!event.target.closest(".no-redirect")) window.location.href="<?= base_url("admin/tickets") ?>/" + <?= json_encode($item["hash"] ?? $item["id"]) ?>'>
                            <td class="no-redirect"><input type="checkbox" class="form-check-input"
                                    style="border-color:#cbd5e1; border-radius:0.25rem;"></td>
                            <td>
                                <span style="font-family:'SF Mono',SFMono-Regular,Menlo,Consolas,monospace;font-size:.76rem;font-weight:600;color:#6366f1;letter-spacing:.03em;background:#f5f3ff;padding:.18rem .45rem;border-radius:.3rem;border:1px solid #e0e7ff;white-space:nowrap">#<?= esc(substr($item['hash'] ?? ('000' . $item['id']), -6)) ?></span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2 text-dark fw-medium">
                                    <i
                                        class="bi <?= $item['category'] === 'Ruido' ? 'bi-volume-up' : ($item['category'] === 'Mantenimiento' ? 'bi-wrench' : 'bi-shield-check') ?> text-muted"></i>
                                    <span><?= esc($item['category']) ?></span>
                                    <?php if ($item['is_attention']): ?>
                                        <i class="bi bi-exclamation-triangle text-warning"></i>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td><span class="badge rounded-pill px-2 py-1 <?= esc($item['status_class']) ?>"
                                    style="font-weight:600; letter-spacing:0.01em;"><?= esc($item['status_label']) ?></span></td>
                            <td><span class="badge rounded-pill px-2 py-1 <?= esc($item['priority_class']) ?>"
                                    style="font-weight:600; letter-spacing:0.01em;"><?= esc($item['priority']) ?></span></td>
                            <td class="text-truncate text-secondary" style="max-width:200px; font-size:0.8rem;">
                                <?= esc($item['description'] !== '' ? $item['description'] : $item['subject']) ?>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div
                                        style="width:20px;height:20px;border-radius:50%;background:#f1f5f9;color:#475569;display:flex;align-items:center;justify-content:center;font-size:0.55rem;font-weight:700;border:1px solid #e2e8f0;">
                                        <?= esc($item['reporter_initials']) ?>
                                    </div>
                                    <span class="text-dark"><?= esc($item['reporter']) ?></span>
                                </div>
                            </td>
                            <td class="text-secondary" style="font-size:0.75rem;"><?= esc($item['created_at_label']) ?></td>
                            <td>
                                <?php if ($item['is_attention']): ?>
                                    <span class="badge rounded-pill text-bg-danger fw-normal"><?= esc($item['time_in_state']) ?></span>
                                <?php elseif ($item['is_overdue']): ?>
                                    <span class="badge rounded-pill text-bg-warning fw-normal"><?= esc($item['time_in_state']) ?></span>
                                <?php else: ?>
                                    <span class="text-secondary" style="font-size:0.75rem;"><i class="bi bi-chevron-left align-middle"
                                            style="font-size:0.5rem; opacity:0.5"></i> <?= esc($item['time_in_state']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-secondary" style="font-size:0.75rem;"><i class="bi bi-chevron-left align-middle"
                                    style="font-size:0.5rem; opacity:0.5"></i> <?= esc($item['open_duration']) ?></td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if (empty($item['assigned_to_name'])): ?>
                                        <div
                                            style="width:20px;height:20px;border-radius:50%;border:1px dashed #cbd5e1;display:flex;align-items:center;justify-content:center;">
                                        </div>
                                        <span class="text-secondary fst-italic" style="font-size:0.8rem;">Por asignar</span>
                                    <?php else: ?>
                                        <div
                                            style="width:20px;height:20px;border-radius:50%;background:#e0e7ff;color:#4f46e5;display:flex;align-items:center;justify-content:center;font-size:0.55rem;font-weight:700;">
                                            <?= esc(substr($item['assigned_to_name'], 0, 2)) ?>
                                        </div>
                                        <span class="text-dark"><?= esc($item['assigned_to_name']) ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="text-secondary fst-italic" style="font-size:0.75rem;">
                                <?= empty($item['unit_name']) ? 'Creado por admin' : esc($item['unit_name']) ?>
                            </td>
                            <td class="text-secondary" style="font-size:0.75rem;"><?= esc($item['last_activity']) ?></td>
                            <td class="text-end text-secondary no-redirect" style="white-space: nowrap;">
                                <div class="dropdown d-inline-block">
                                    <i class="bi bi-arrow-clockwise me-1 align-middle cursor-pointer"
                                        style="font-size:0.95rem; opacity:0.7; padding:0.25rem;" title="Cambiar Estado"
                                        data-bs-toggle="dropdown"></i>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                                        style="font-size:0.85rem; border-radius:0.5rem; min-width:200px;">
                                        <?php if (($item['status'] ?? '') === 'open'): ?>
                                            <li><a class="dropdown-item py-2 td-action-status" href="#"
                                                    data-ticket-id="<?= $item['id'] ?>" data-status="in_progress"><i
                                                        class="bi bi-play-circle me-2 text-muted"></i> Comenzar a Trabajar</a></li>
                                            <li><a class="dropdown-item py-2 td-action-status" href="#"
                                                    data-ticket-id="<?= $item['id'] ?>" data-status="info_needed"><i
                                                        class="bi bi-question-circle me-2 text-muted"></i> Solicitar Info</a></li>
                                            <li><a class="dropdown-item py-2 td-action-status" href="#"
                                                    data-ticket-id="<?= $item['id'] ?>" data-status="resolved"><i
                                                        class="bi bi-check-circle me-2 text-muted"></i> Marcar Resuelto</a></li>
                                        <?php else: ?>
                                            <li><a class="dropdown-item py-2 td-action-status" href="#"
                                                    data-ticket-id="<?= $item['id'] ?>" data-status="info_needed"><i
                                                        class="bi bi-question-circle me-2 text-muted"></i> Solicitar Info</a></li>
                                            <li><a class="dropdown-item py-2 td-action-status" href="#"
                                                    data-ticket-id="<?= $item['id'] ?>" data-status="pending_approval"><i
                                                        class="bi bi-clock me-2 text-muted"></i> Solicitar Aprobación</a></li>
                                            <li><a class="dropdown-item py-2 td-action-status" href="#"
                                                    data-ticket-id="<?= $item['id'] ?>" data-status="resolved"><i
                                                        class="bi bi-check-circle me-2 text-muted"></i> Marcar Resuelto</a></li>
                                            <li><a class="dropdown-item py-2 td-action-status" href="#"
                                                    data-ticket-id="<?= $item['id'] ?>" data-status="paused"><i
                                                        class="bi bi-pause me-2 text-muted"></i> Pausar</a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <div class="dropdown d-inline-block">
                                    <i class="bi bi-person-plus mx-1 align-middle cursor-pointer"
                                        style="font-size:1.05rem; opacity:0.7; padding:0.25rem;" title="Asignar"
                                        data-bs-toggle="dropdown"></i>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                                        style="font-size:0.85rem; border-radius:0.5rem; min-width:200px;">
                                        <li><a class="dropdown-item py-2 td-action-assign" href="#"
                                                data-ticket-id="<?= $item['id'] ?>" data-user=""><i
                                                    class="bi bi-dash-circle text-muted me-2"></i> Por asignar</a></li>

                                        <?php if (!empty($assignees['admins'])): ?>
                                            <li>
                                                <h6 class="dropdown-header bg-light py-2 mb-1 mt-1 text-uppercase fw-bold"
                                                    style="font-size: 0.65rem;">Administradores</h6>
                                            </li>
                                            <?php foreach ($assignees['admins'] as $admin): ?>
                                                <li><a class="dropdown-item py-2 td-action-assign" href="#"
                                                        data-ticket-id="<?= $item['id'] ?>" data-user="<?= esc('user_' . $admin['id']) ?>">
                                                        <div class="d-inline-flex mx-1 align-items-center justify-content-center"
                                                            style="width:20px;height:20px;border-radius:50%;background:#e2e8f0;font-size:0.55rem;font-weight:700;">
                                                            <?= esc(strtoupper(substr($admin['first_name'] ?? 'A', 0, 1) . substr($admin['last_name'] ?? 'A', 0, 1))) ?>
                                                        </div> <?= esc(trim($admin['first_name'] . ' ' . $admin['last_name'])) ?>
                                                    </a></li>
                                            <?php endforeach; endif; ?>

                                        <?php if (!empty($assignees['staff'])): ?>
                                            <li>
                                                <h6 class="dropdown-header bg-light py-2 mb-1 mt-1 text-uppercase fw-bold"
                                                    style="font-size: 0.65rem;">Personal</h6>
                                            </li>
                                            <?php foreach ($assignees['staff'] as $stf): ?>
                                                <li><a class="dropdown-item py-2 td-action-assign" href="#"
                                                        data-ticket-id="<?= $item['id'] ?>" data-user="<?= esc('staff_' . $stf['id']) ?>">
                                                        <div class="d-inline-flex mx-1 border bg-white align-items-center justify-content-center text-muted"
                                                            style="width:20px;height:20px;border-radius:50%;font-size:0.55rem;">
                                                            <?= esc(strtoupper(substr($stf['first_name'] ?? 'S', 0, 1) . substr($stf['last_name'] ?? 'T', 0, 1))) ?>
                                                        </div> <?= esc(trim($stf['first_name'] . ' ' . $stf['last_name'])) ?>
                                                    </a></li>
                                            <?php endforeach; endif; ?>
                                    </ul>
                                </div>
                                <div class="dropdown d-inline-block">
                                    <i class="bi bi-three-dots-vertical ms-1 align-middle cursor-pointer"
                                        style="font-size:0.95rem; opacity:0.7; padding:0.25rem;" title="Más"
                                        data-bs-toggle="dropdown"></i>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0"
                                        style="font-size:0.85rem; border-radius:0.5rem; min-width:200px;">
                                        <li><a class="dropdown-item py-2"
                                                href="<?= base_url("admin/tickets/" . esc($item["hash"] ?? $item["id"])) ?>"><i
                                                    class="bi bi-clock-history me-2 text-muted"></i> Gestionar Reporte</a></li>
                                        <li><a class="dropdown-item py-2 td-action-delete" href="#"
                                                data-ticket-id="<?= $item['id'] ?>"><i class="bi bi-trash text-danger me-2"></i>
                                                Eliminar</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if (!empty($tickets)): ?>
        <div id="tickets-search-empty" class="tickets-empty mt-3 d-none">
            <i class="bi bi-search fs-3 d-block mb-2 text-secondary"></i>
            Sin coincidencias para la busqueda.
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php if ($isPanel): ?>

    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <a href="<?= base_url('admin/tickets') ?>" class="td-back-btn"><i class="bi bi-arrow-left"></i></a>
            <h2 class="cc-hero-title">Tickets</h2>
            <i class="bi bi-exclamation-circle"></i>
            <div class="cc-hero-divider"></div>
            <i class="bi bi-chevron-right"></i>
            <h2 class="cc-hero-title">Panel de Tickets</h2>


            <div class="cc-hero-breadcrumb">

                <i class="bi bi-chevron-right"></i>
                Vista general del estado de tickets
            </div>
        </div>

    </div>
    <!-- ── END Hero ── -->


    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="tickets-kpi d-flex justify-content-between align-items-start">
                <div>
                    <div class="tickets-kpi-title">Total Abiertos</div>
                    <p class="tickets-kpi-value"><?= esc((string) ($totals['open_total'] ?? 0)) ?></p>
                    <div class="tickets-kpi-note text-primary">+<?= esc((string) ($totals['created_today'] ?? 0)) ?> hoy
                    </div>
                </div>
                <span class="tickets-kpi-icon bg-primary-subtle text-primary-emphasis"><i class="bi bi-inbox"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="tickets-kpi d-flex justify-content-between align-items-start">
                <div>
                    <div class="tickets-kpi-title">Pendientes</div>
                    <p class="tickets-kpi-value text-warning-emphasis"><?= esc((string) ($totals['pending'] ?? 0)) ?></p>
                    <div class="tickets-kpi-note text-warning-emphasis">
                        <?= esc((string) ($totals['overdue_pending'] ?? 0)) ?> vencidos
                    </div>
                </div>
                <span class="tickets-kpi-icon bg-warning-subtle text-warning-emphasis"><i
                        class="bi bi-clock-history"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="tickets-kpi d-flex justify-content-between align-items-start">
                <div>
                    <div class="tickets-kpi-title">En Progreso</div>
                    <p class="tickets-kpi-value"><?= esc((string) ($totals['in_progress'] ?? 0)) ?></p>
                    <div class="tickets-kpi-note"><?= esc((string) ($totals['unassigned'] ?? 0)) ?> asignados</div>
                </div>
                <span class="tickets-kpi-icon bg-success-subtle text-success-emphasis"><i
                        class="bi bi-play-circle"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="tickets-kpi d-flex justify-content-between align-items-start">
                <div>
                    <div class="tickets-kpi-title">Requieren Atencion</div>
                    <p class="tickets-kpi-value text-danger"><?= esc((string) ($totals['needs_attention'] ?? 0)) ?></p>
                </div>
                <span class="tickets-kpi-icon bg-danger-subtle text-danger-emphasis"><i
                        class="bi bi-exclamation-triangle"></i></span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-6">
            <div class="tickets-card p-3 h-100">
                <h3 class="h3 fw-semibold mb-3">Desglose por Antiguedad</h3>
                <?php foreach ($aging as $line): ?>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-secondary-emphasis"><?= esc($line['label']) ?></small>
                        <small class="fw-semibold"><?= esc((string) $line['count']) ?>
                            (<?= esc((string) $line['pct']) ?>%)</small>
                    </div>
                    <div class="tickets-progress-track mb-3">
                        <div class="tickets-progress-fill <?= esc($line['bar']) ?>"
                            style="width: <?= esc((string) $line['pct']) ?>%;"></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="tickets-card p-3 h-100">
                <h3 class="h3 fw-semibold mb-1">Carga del Personal</h3>
                <p class="text-secondary-emphasis small mb-2">
                    Total: <?= esc((string) ($totals['open_total'] ?? 0)) ?> abiertos |
                    <?= esc((string) ($totals['staff_total'] ?? 0)) ?> personal |
                    Prom: <?= ($totals['staff_total'] ?? 0) > 0 ? round((($totals['open_total'] ?? 0) - ($totals['unassigned'] ?? 0)) / $totals['staff_total'], 1) : 0 ?> por persona
                </p>

                <div class="tickets-person-load">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold text-secondary-emphasis">Por asignar</span>
                        <span class="fw-semibold"><?= esc((string) ($totals['unassigned'] ?? 0)) ?></span>
                    </div>
                    <div class="tickets-progress-track mb-2">
                        <?php $unassignedPct = ($totals['open_total'] ?? 0) > 0 ? (int) round((($totals['unassigned'] ?? 0) / max(1, $totals['open_total'])) * 100) : 0; ?>
                        <div class="tickets-progress-fill bg-secondary"
                            style="width: <?= esc((string) $unassignedPct) ?>%;"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-secondary-emphasis"><i class="bi bi-circle-fill text-warning"
                                style="font-size:0.5rem;"></i> <?= esc((string) ($totals['pending'] ?? 0)) ?> nuevos</small>
                        <button class="tickets-tool-btn py-1 px-2">Ver Sin Asignar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-12 col-xl-6">
            <div class="tickets-card p-3">
                <h3 class="h3 fw-semibold mb-3">Alertas</h3>
                <?php foreach ($alerts as $index => $alert): ?>
                    <div class="d-flex align-items-center gap-2 py-2 <?= $index > 0 ? 'border-top' : '' ?>">
                        <i class="bi <?= esc($alert['icon']) ?> <?= esc($alert['class']) ?>"></i>
                        <span><?= esc($alert['text']) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($isMetrics): ?>


    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <a href="<?= base_url('admin/tickets') ?>" class="td-back-btn"><i class="bi bi-arrow-left"></i></a>
            <h2 class="cc-hero-title">Tickets</h2>
            <i class="bi bi-exclamation-circle"></i>
            <div class="cc-hero-divider"></div>
            <i class="bi bi-chevron-right"></i>
            <h2 class="cc-hero-title">Panel de Análisis</h2>


            <div class="cc-hero-breadcrumb">

                <i class="bi bi-chevron-right"></i>
                Métricas detalladas e información de rendimiento
            </div>
        </div>

    </div>
    <!-- ── END Hero ── -->





    <div class="tickets-period-group mb-3">
        <?php foreach ($periodLabels as $key => $label): ?>
            <a href="<?= esc($periodUrls[$key]) ?>" class="<?= $period === $key ? 'active' : '' ?>"><?= esc($label) ?></a>
        <?php endforeach; ?>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="tickets-kpi d-flex justify-content-between align-items-center">
                <div>
                    <div class="tickets-kpi-title">Total de Reportes</div>
                    <p class="tickets-kpi-value"><?= esc((string) ($resolution['total_reports'] ?? 0)) ?></p>
                </div>
                <span class="tickets-kpi-icon bg-primary-subtle text-primary-emphasis"><i
                        class="bi bi-file-earmark-text"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="tickets-kpi d-flex justify-content-between align-items-center">
                <div>
                    <div class="tickets-kpi-title">Tiempo Promedio de Resolucion</div>
                    <p class="tickets-kpi-value"><?= esc((string) ($resolution['avg_resolution'] ?? '0m')) ?></p>
                </div>
                <span class="tickets-kpi-icon bg-warning-subtle text-warning-emphasis"><i
                        class="bi bi-clock-history"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="tickets-kpi d-flex justify-content-between align-items-center">
                <div>
                    <div class="tickets-kpi-title">Tasa de Resolucion</div>
                    <p class="tickets-kpi-value"><?= esc((string) ($resolution['resolution_rate'] ?? 0)) ?>%</p>
                </div>
                <span class="tickets-kpi-icon bg-warning-subtle text-warning-emphasis"><i
                        class="bi bi-check-circle"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="tickets-kpi d-flex justify-content-between align-items-center">
                <div>
                    <div class="tickets-kpi-title">Reportes Abiertos</div>
                    <p class="tickets-kpi-value"><?= esc((string) ($resolution['open_reports'] ?? 0)) ?></p>
                </div>
                <span class="tickets-kpi-icon bg-secondary-subtle text-secondary-emphasis"><i
                        class="bi bi-inbox"></i></span>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-12 col-xl-6">
            <div class="tickets-card p-3 tickets-chart-wrap">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h3 class="h3 fw-semibold mb-1"><i class="bi bi-graph-up-arrow me-1"></i>Creados vs Resueltos</h3>
                        <p class="text-secondary-emphasis mb-0">Creacion y resolucion de tickets en el periodo seleccionado
                        </p>
                    </div>
                    <span
                        class="badge rounded-pill text-bg-danger-subtle text-danger-emphasis">+<?= esc((string) ($trend['net_change'] ?? 0)) ?>
                        cambio neto</span>
                </div>

                <div class="tickets-chart-bars">
                    <?php foreach (($trend['points'] ?? []) as $point): ?>
                        <?php
                        $maxVolume = max(1, (int) ($trend['max_volume'] ?? 1));
                        $createdHeight = (int) round((($point['created'] ?? 0) / $maxVolume) * 100);
                        $resolvedHeight = (int) round((($point['resolved'] ?? 0) / $maxVolume) * 100);
                        ?>
                        <div class="tickets-chart-group">
                            <div class="tickets-chart-bar tickets-chart-created"
                                style="height: <?= esc((string) $createdHeight) ?>%;"></div>
                            <div class="tickets-chart-bar tickets-chart-resolved"
                                style="height: <?= esc((string) $resolvedHeight) ?>%;"></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="tickets-chart-labels">
                    <?php foreach (($trend['points'] ?? []) as $point): ?>
                        <span><?= esc(substr((string) ($point['label'] ?? ''), 4)) ?></span>
                    <?php endforeach; ?>
                </div>

                <div class="tickets-legend">
                    <span><span class="tickets-dot" style="background:#2f465a;"></span> Creados</span>
                    <span><span class="tickets-dot" style="background:#1f9f92;"></span> Resueltos</span>
                    <span><span class="tickets-dot" style="background:#ff7f50;"></span> Backlog Neto:
                        <?= esc((string) ($trend['net_change'] ?? 0)) ?></span>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="tickets-card p-3 h-100">
                <h3 class="h3 fw-semibold mb-3">Distribucion por Categoria</h3>
                <?php if (empty($categories)): ?>
                    <div class="tickets-empty">Sin datos para este periodo.</div>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <span class="text-secondary-emphasis"><?= esc($cat['category']) ?></span>
                            <span class="fw-semibold"><?= esc((string) $cat['count']) ?> (<?= esc((string) $cat['pct']) ?>%)</span>
                        </div>
                        <div class="tickets-progress-track mb-3">
                            <div class="tickets-progress-fill bg-secondary" style="width: <?= esc((string) $cat['pct']) ?>%;"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="tickets-card p-3">
        <h3 class="h3 fw-semibold mb-3">Métricas del Equipo</h3>
        <?php if (empty($staffMetrics)): ?>
            <div class="tickets-empty">
                <div class="fw-medium">Sin datos del personal para este periodo</div>
                <div class="small mt-2">Intenta seleccionar un periodo más amplio o verifica que haya reportes asignados en este rango de fechas.</div>
            </div>
        <?php else: ?>
            <table class="table tickets-table mb-0">
                <thead>
                    <tr>
                        <th>Personal</th>
                        <th class="text-center">Asignados</th>
                        <th class="text-center">Resueltos</th>
                        <th class="text-end">Tiempo Prom.</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($staffMetrics as $metric): ?>
                        <tr>
                            <td class="text-dark fw-medium"><?= esc($metric['name']) ?></td>
                            <td class="text-center"><?= esc((string) $metric['assigned']) ?></td>
                            <td class="text-center text-success fw-bold"><?= esc((string) $metric['resolved']) ?></td>
                            <td class="text-end text-secondary"><?= esc($metric['avg_resolution']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if ($isList && !empty($tickets)): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('ticket-search');
            var rows = Array.from(document.querySelectorAll('#tickets-table-body tr[data-search]'));
            var emptyState = document.getElementById('tickets-search-empty');
            var tableBody = document.getElementById('tickets-table-body');

            if (!input || rows.length === 0 || !emptyState || !tableBody) {
                // Setup listeners anyway if we just have actions to bind
            } else {
                input.addEventListener('input', function () {
                    var term = (input.value || '').trim().toLowerCase();
                    var visible = 0;

                    rows.forEach(function (row) {
                        var haystack = (row.dataset.search || '').toLowerCase();
                        var match = term === '' || haystack.indexOf(term) !== -1;
                        row.style.display = match ? '' : 'none';
                        if (match) {
                            visible += 1;
                        }
                    });

                    if (visible === 0) {
                        emptyState.classList.remove('d-none');
                        tableBody.parentElement.classList.add('d-none');
                    } else {
                        emptyState.classList.add('d-none');
                        tableBody.parentElement.classList.remove('d-none');
                    }
                });
            }

            // ═══════════════════════════════════════════════════
            // ▌ TABLE PREMIUM ACTIONS (Dropdowns)
            // ═══════════════════════════════════════════════════

            // Cambiar Estado
            document.querySelectorAll('.td-action-status').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const ticketId = item.dataset.ticketId;
                    const statusVal = item.dataset.status;
                    const text = item.textContent.trim();

                    const formData = new FormData();
                    formData.append('status', statusVal);
                    fetch('<?= base_url() ?>/admin/tickets/update-details/' + ticketId, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(r => r.json())
                        .then(res => {
                            if (res.status === 200) {
                                if (typeof Swal !== 'undefined') { Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Estado actualizado a: ' + text, showConfirmButton: false, timer: 2000 }); }
                                setTimeout(() => window.location.reload(), 800);
                            } else {
                                if (typeof Swal !== 'undefined') { Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error al cambiar estado', showConfirmButton: false, timer: 2000 }); }
                            }
                        })
                        .catch(err => console.error(err));
                });
            });

            // Asignar Reporte
            document.querySelectorAll('.td-action-assign').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const userVal = item.dataset.user;
                    const ticketId = item.dataset.ticketId;
                    const text = userVal === '' ? 'Por asignar' : item.textContent.trim();

                    const formData = new FormData();
                    formData.append('assigned_to', userVal);

                    fetch('<?= base_url() ?>/admin/tickets/update-details/' + ticketId, {
                        method: 'POST',
                        body: formData,
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(r => r.json())
                        .then(res => {
                            if (res.status === 200) {
                                if (typeof Swal !== 'undefined') { Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Reporte asignado a: ' + text, showConfirmButton: false, timer: 2000 }); }
                                setTimeout(() => window.location.reload(), 1000);
                            } else {
                                if (typeof Swal !== 'undefined') { Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error al asignar', showConfirmButton: false, timer: 2000 }); }
                            }
                        })
                        .catch(err => console.error(err));
                });
            });

            // Eliminar (Delete Meatball option)
            document.querySelectorAll('.td-action-delete').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const ticketId = item.dataset.ticketId;

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¿Eliminar reporte?',
                            text: "Esta acción no se puede deshacer.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#ef4444',
                            cancelButtonColor: '#64748b',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                fetch('<?= base_url() ?>/admin/tickets/delete/' + ticketId, {
                                    method: 'POST',
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                                })
                                    .then(r => r.json())
                                    .then(res => {
                                        if (res.status === 200) {
                                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Reporte eliminado', showConfirmButton: false, timer: 2000 });
                                            setTimeout(() => window.location.reload(), 800);
                                        } else {
                                            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: res.error || 'Error al eliminar', showConfirmButton: false, timer: 2000 });
                                        }
                                    })
                                    .catch(err => console.error(err));
                            }
                        });
                    }
                });
            });
        });
    </script>
<?php endif; ?>

<?= $this->include('admin/tickets_partials') ?>

<?= $this->endSection() ?>