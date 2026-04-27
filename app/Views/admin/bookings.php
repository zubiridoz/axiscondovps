<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$meses = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
$mesesCortos = ['', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

$statusLabels = [
    'pending' => ['Pendiente', 'bk-badge-pending', 'bi-clock-history'],
    'approved' => ['Aprobado', 'bk-badge-approved', 'bi-check-circle'],
    'rejected' => ['Rechazado', 'bk-badge-rejected', 'bi-x-circle'],
    'cancelled' => ['Cancelado', 'bk-badge-cancelled', 'bi-slash-circle'],
];

$formatDate = function ($dateStr) use ($mesesCortos) {
    if (!$dateStr)
        return '--';
    $ts = strtotime($dateStr);
    return (int) date('j', $ts) . ' ' . ($mesesCortos[(int) date('n', $ts)] ?? '') . ' ' . date('Y', $ts);
};

$formatTime = function ($dateStr) {
    if (!$dateStr)
        return '--';
    return date('h:i A', strtotime($dateStr));
};

$formatTimeRange = function ($start, $end) {
    if (!$start)
        return '--';
    $s = date('H:i', strtotime($start));
    $e = $end ? date('H:i', strtotime($end)) : '';
    if ($s === '00:00' && ($e === '23:59' || $e === '00:00' || !$e))
        return 'Todo el día';
    return $s . ' - ' . $e;
};
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

    .cc-hero-btndark {
        background: #1C2434;
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

    .cc-hero-btndark:hover {
        background: #3a4864ff;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    /* ── end Hero ── */

    /* ── KPIs ── */
    .bk-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.25rem
    }

    .bk-stat {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: .6rem;
        padding: 1rem 1.15rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        transition: box-shadow .2s
    }

    .bk-stat:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, .06)
    }

    .bk-stat-label {
        font-size: .78rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: .3rem
    }

    .bk-stat-value {
        font-size: 1.6rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1
    }

    .bk-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0
    }

    .bk-stat-icon.amber {
        background: #fffbeb;
        color: #f59e0b
    }

    .bk-stat-icon.green {
        background: #f0fdf4;
        color: #10b981
    }

    .bk-stat-icon.red {
        background: #fef2f2;
        color: #ef4444
    }

    .bk-stat-icon.blue {
        background: #eff6ff;
        color: #3b82f6
    }

    /* ── Toolbar ── */
    .bk-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: .75rem;
        margin-bottom: 1rem
    }

    .bk-toolbar-left {
        display: flex;
        align-items: center;
        gap: .6rem;
        flex-wrap: wrap
    }

    .bk-search-wrap {
        position: relative;
        width: 240px
    }

    .bk-search-wrap i {
        position: absolute;
        left: .75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: .85rem
    }

    .bk-search {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: .45rem;
        padding: .5rem .8rem .5rem 2rem;
        font-size: .85rem;
        color: #334155;
        background: #fff
    }

    .bk-search:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, .14)
    }

    .bk-filter-pills {
        display: flex;
        gap: .25rem;
        background: #f8fafc;
        padding: 3px;
        border-radius: 8px;
        border: 1px solid #e2e8f0
    }

    .bk-pill {
        padding: .35rem .7rem;
        border: none;
        background: transparent;
        font-size: .8rem;
        font-weight: 500;
        color: #64748b;
        border-radius: 6px;
        cursor: pointer;
        transition: all .2s
    }

    .bk-pill.active,
    .bk-pill:hover {
        background: #fff;
        color: #1e293b;
        box-shadow: 0 1px 2px rgba(0, 0, 0, .06)
    }

    .bk-amenity-filter {
        border: 1px solid #d0d8e2;
        border-radius: .45rem;
        padding: .42rem .7rem;
        font-size: .82rem;
        color: #334155;
        background: #fff;
        cursor: pointer
    }

    /* ── Table ── */
    .bk-panel {
        border: 1px solid #d9e1eb;
        border-radius: .6rem;
        background: #fff;
        overflow: hidden
    }

    .bk-table {
        width: 100%;
        border-collapse: collapse
    }

    .bk-table thead th {
        font-size: .74rem;
        color: #64748b;
        font-weight: 600;
        letter-spacing: .02em;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        padding: .85rem .9rem;
        background: #f8fafc
    }

    .bk-table tbody td {
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: .86rem;
        vertical-align: middle;
        padding: .85rem .9rem
    }

    .bk-table tbody tr {
        cursor: pointer;
        transition: background .15s
    }

    .bk-table tbody tr:hover td {
        background: #f8fafc
    }

    .bk-table tbody tr:last-child td {
        border-bottom: none
    }

    .bk-table tbody tr.is-pending {
        border-left: 3px solid #f59e0b
    }

    /* Status Badges */
    .bk-badge {
        padding: .22rem .6rem;
        border-radius: 1rem;
        font-size: .72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .25rem
    }

    .bk-badge-pending {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fef3c7
    }

    .bk-badge-approved {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #dcfce7
    }

    .bk-badge-rejected {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fee2e2
    }

    .bk-badge-cancelled {
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0
    }

    /* Actions Dropdown */
    .bk-actions-dd {
        position: relative
    }

    .bk-actions-dd .dropdown-menu {
        min-width: 180px;
        box-shadow: 0 10px 32px rgba(0, 0, 0, .15);
        border: 1px solid #e2e8f0;
        border-radius: .6rem
    }

    .bk-actions-dd .dropdown-item {
        font-size: .86rem;
        padding: .6rem 1rem;
        display: flex;
        align-items: center;
        gap: .5rem
    }

    .bk-actions-dd .dropdown-item:hover {
        background: #f1f5f9
    }

    /* Empty State */
    .bk-empty {
        min-height: 400px;
        border: 1px dashed #d9e1eb;
        border-radius: .75rem;
        background: #fbfdff;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem
    }

    .bk-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        background: #eff6ff;
        color: #3b82f6;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto 1rem
    }

    .bk-empty-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: .5rem
    }

    .bk-empty-desc {
        font-size: .85rem;
        color: #64748b;
        max-width: 450px;
        margin: 0 auto
    }

    /* ── Create Modal ── */
    .bk-create-modal .modal-content {
        border: none;
        border-radius: .75rem;
        box-shadow: 0 25px 50px rgba(0, 0, 0, .15)
    }

    .bk-create-modal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.2rem 1.5rem
    }

    .bk-create-modal .modal-body {
        padding: 1.5rem
    }

    .bk-create-modal .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 1rem 1.5rem
    }

    /* User Selector */
    .user-selector {
        position: relative
    }

    .user-selector-trigger {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: .5rem;
        padding: .6rem 1rem;
        font-size: .9rem;
        color: #334155;
        background: #fff;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: border-color .2s
    }

    .user-selector-trigger:focus,
    .user-selector-trigger.open {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .12)
    }

    .user-selector-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: .5rem;
        box-shadow: 0 10px 32px rgba(0, 0, 0, .12);
        z-index: 1060;
        max-height: 340px;
        overflow-y: auto;
        display: none
    }

    .user-selector-dropdown.open {
        display: block
    }

    .user-selector-search {
        width: 100%;
        border: none;
        border-bottom: 1px solid #f1f5f9;
        padding: .65rem 1rem;
        font-size: .85rem;
        outline: none;
        color: #334155
    }

    .user-selector-search::placeholder {
        color: #94a3b8
    }

    .user-group-label {
        font-size: .7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .04em;
        padding: .6rem 1rem .3rem;
        background: #f8fafc
    }

    .user-option {
        display: flex;
        align-items: center;
        gap: .7rem;
        padding: .55rem 1rem;
        cursor: pointer;
        transition: background .12s
    }

    .user-option:hover {
        background: #f1f5f9
    }

    .user-option .user-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .75rem;
        color: #64748b;
        flex-shrink: 0;
        overflow: hidden
    }

    .user-option .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover
    }

    .user-option .user-info {
        flex: 1;
        min-width: 0
    }

    .user-option .user-name {
        font-size: .86rem;
        font-weight: 500;
        color: #1e293b
    }

    .user-option .user-meta {
        font-size: .72rem;
        color: #64748b
    }

    /* Amenity Info Card */
    .amenity-info-card {
        background: #1e293b;
        color: #fff;
        border-radius: .6rem;
        padding: 1rem;
        margin-bottom: 1rem;
        display: flex;
        gap: 1rem;
        align-items: center
    }

    .amenity-info-card img {
        width: 64px;
        height: 64px;
        border-radius: .5rem;
        object-fit: cover
    }

    .amenity-info-card .amenity-details h6 {
        margin: 0 0 .3rem;
        font-size: .95rem;
        font-weight: 600
    }

    .amenity-info-card .amenity-details .meta {
        font-size: .78rem;
        color: rgba(255, 255, 255, .7)
    }

    /* Calendar */
    .bk-cal {
        border: 1px solid #e2e8f0;
        border-radius: .5rem;
        overflow: hidden
    }

    .bk-cal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .7rem 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0
    }

    .bk-cal-header .cal-title {
        font-weight: 600;
        font-size: .9rem;
        color: #1e293b;
        text-transform: capitalize
    }

    .bk-cal-nav {
        width: 28px;
        height: 28px;
        border: 1px solid #d0d8e2;
        border-radius: .4rem;
        background: #fff;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer
    }

    .bk-cal-nav:hover {
        background: #f1f5f9
    }

    .bk-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr)
    }

    .bk-cal-daylbl {
        text-align: center;
        font-size: .7rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: lowercase;
        padding: 6px 4px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9
    }

    .bk-cal-cell {
        text-align: center;
        padding: 8px 4px;
        font-size: .82rem;
        color: #334155;
        position: relative;
        min-height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .12s
    }

    .bk-cal-cell:hover:not(.empty):not(.past):not(.disabled) {
        background: #eff6ff
    }

    .bk-cal-cell.empty {
        color: transparent;
        cursor: default
    }

    .bk-cal-cell.past {
        color: #cbd5e1;
        cursor: default
    }

    .bk-cal-cell.disabled {
        color: #e2e8f0;
        cursor: not-allowed;
        background: #fafafa
    }

    .bk-cal-cell.today span {
        background: #3b82f6;
        color: #fff;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center
    }

    .bk-cal-cell.selected span {
        background: #1e293b;
        color: #fff;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center
    }

    .bk-cal-cell.has-booking::after {
        content: '';
        position: absolute;
        bottom: 3px;
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #f59e0b
    }

    /* Time Slots */
    .slot-list {
        display: flex;
        flex-direction: column;
        gap: .4rem;
        max-height: 260px;
        overflow-y: auto
    }

    .slot-item {
        border: 1px solid #e2e8f0;
        border-radius: .45rem;
        padding: .6rem .85rem;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        gap: .5rem
    }

    .slot-item:hover {
        border-color: #6366f1;
        background: #f5f3ff
    }

    .slot-item.selected {
        border-color: #6366f1;
        background: #eef2ff;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, .15)
    }

    .slot-item .slot-icon {
        color: #10b981;
        font-size: .85rem
    }

    .slot-item .slot-label {
        font-size: .85rem;
        font-weight: 500;
        color: #1e293b
    }

    .slot-item .slot-avail {
        font-size: .72rem;
        color: #64748b
    }

    /* ── Detail Modal ── */
    .bk-detail-modal .modal-content {
        border: none;
        border-radius: .75rem;
        box-shadow: 0 25px 50px rgba(0, 0, 0, .15)
    }

    .bk-detail-modal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1rem 1.5rem
    }

    .bk-detail-modal .modal-body {
        padding: 1.25rem 1.5rem
    }

    .detail-info-card {
        border: 1px solid #e2e8f0;
        border-radius: .6rem;
        padding: 1rem;
        margin-bottom: 1rem
    }

    .detail-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem
    }

    .detail-info-grid .info-block .label {
        font-size: .72rem;
        color: #6366f1;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-bottom: 2px
    }

    .detail-info-grid .info-block .value {
        font-size: .9rem;
        font-weight: 600;
        color: #0f172a
    }

    .detail-meta {
        font-size: .78rem;
        color: #94a3b8;
        margin-top: .75rem;
        display: flex;
        align-items: center;
        gap: .4rem
    }

    .btn-delete-booking {
        background: #fff;
        border: 1px solid #fca5a5;
        color: #dc2626;
        border-radius: .5rem;
        padding: .55rem 1.2rem;
        font-size: .88rem;
        font-weight: 600;
        transition: all .2s;
        display: inline-flex;
        align-items: center;
        gap: .4rem
    }

    .btn-delete-booking:hover {
        background: #fef2f2;
        border-color: #f87171;
        color: #dc2626
    }

    .btn-approve {
        background: #f0fdf4;
        border: 1px solid #86efac;
        color: #16a34a;
        border-radius: .5rem;
        padding: .55rem 1.2rem;
        font-size: .88rem;
        font-weight: 600
    }

    .btn-approve:hover {
        background: #dcfce7
    }

    .btn-reject {
        background: #fef2f2;
        border: 1px solid #fca5a5;
        color: #dc2626;
        border-radius: .5rem;
        padding: .55rem 1.2rem;
        font-size: .88rem;
        font-weight: 600
    }

    .btn-reject:hover {
        background: #fee2e2
    }

    /* New Booking Btn */
    .btn-new-bk {
        background: #1e293b;
        color: #fff;
        border: none;
        padding: .5rem 1rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: .5rem;
        cursor: pointer;
        transition: all .2s
    }

    .btn-new-bk:hover {
        background: #334155;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 41, 59, .2);
        color: #fff
    }

    @media(max-width:768px) {
        .bk-stats {
            grid-template-columns: repeat(2, 1fr)
        }
    }

    @media(max-width:576px) {
        .bk-stats {
            grid-template-columns: 1fr
        }
    }
</style>

<!-- ═══ HERO ═══ -->
<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">Reservas</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-calendar-check"></i>
            <i class="bi bi-chevron-right" style="font-size:.65rem;color:#94a3b8"></i>
            Gestión de reservas de amenidades
        </div>
    </div>
    <button class="cc-hero-btn" data-bs-toggle="modal" data-bs-target="#createBookingModal">
        <i class="bi bi-plus-lg"></i> Nueva Reserva
    </button>
</div>

<!-- ═══ KPIs ═══ -->
<div class="bk-stats">
    <div class="bk-stat">
        <div>
            <div class="bk-stat-label" style="color:#d97706">Pendientes</div>
            <div class="bk-stat-value"><?= $pending ?? 0 ?></div>
        </div>
        <div class="bk-stat-icon amber"><i class="bi bi-clock-history"></i></div>
    </div>
    <div class="bk-stat">
        <div>
            <div class="bk-stat-label" style="color:#10b981">Aprobadas</div>
            <div class="bk-stat-value"><?= $approved ?? 0 ?></div>
        </div>
        <div class="bk-stat-icon green"><i class="bi bi-check-circle"></i></div>
    </div>
    <div class="bk-stat">
        <div>
            <div class="bk-stat-label" style="color:#ef4444">Rechazadas</div>
            <div class="bk-stat-value"><?= $rejected ?? 0 ?></div>
        </div>
        <div class="bk-stat-icon red"><i class="bi bi-x-circle"></i></div>
    </div>
    <div class="bk-stat">
        <div>
            <div class="bk-stat-label">Hoy</div>
            <div class="bk-stat-value"><?= $todayBookings ?? 0 ?></div>
        </div>
        <div class="bk-stat-icon blue"><i class="bi bi-calendar-date"></i></div>
    </div>
</div>

<!-- ═══ TOOLBAR ═══ -->
<div class="bk-toolbar">
    <div class="bk-toolbar-left">
        <div class="bk-search-wrap">
            <i class="bi bi-search"></i>
            <input type="text" class="bk-search" id="bkSearch" placeholder="Buscar reservas...">
        </div>
        <div class="bk-filter-pills" id="statusFilters">
            <button class="bk-pill active" data-filter="all">Todos</button>
            <button class="bk-pill" data-filter="pending">Pendientes</button>
            <button class="bk-pill" data-filter="approved">Aprobadas</button>
            <button class="bk-pill" data-filter="rejected">Rechazadas</button>
        </div>
        <select class="bk-amenity-filter" id="amenityFilter">
            <option value="">Todas las amenidades</option>
            <?php foreach ($amenities as $a): ?>
                <option value="<?= esc($a['name']) ?>"><?= esc($a['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<!-- ═══ TABLE ═══ -->
<?php if (empty($bookings)): ?>
    <div class="bk-empty">
        <div>
            <div class="bk-empty-icon"><i class="bi bi-calendar-event"></i></div>
            <div class="bk-empty-title">No hay reservas registradas</div>
            <p class="bk-empty-desc">Las reservas aparecerán aquí cuando los residentes las soliciten o cuando crees una
                nueva reserva.</p>
        </div>
    </div>
<?php else: ?>
    <div class="bk-panel">
        <table class="bk-table" id="bkTable">
            <thead>
                <tr>
                    <th>N° Reserva</th>
                    <th>Amenidad</th>
                    <th>Residente</th>
                    <th>Unidad</th>
                    <th>Fecha</th>
                    <th>Horario</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $b):
                    $st = $statusLabels[$b['status']] ?? ['Desconocido', 'bk-badge-cancelled', 'bi-question-circle'];
                    $amenityImg = !empty($b['amenity_image']) ? base_url('admin/amenidades/imagen/' . $b['amenity_image']) : '';
                    $roleName = ($b['role_name'] ?? '') === 'ADMIN' ? 'Administrador' : 'Residente';
                    ?>
                    <tr class="bk-row <?= $b['status'] === 'pending' ? 'is-pending' : '' ?>"
                        data-status="<?= esc($b['status']) ?>" data-amenity="<?= esc($b['amenity_name'] ?? '') ?>"
                        data-search="<?= esc(strtolower(($b['short_hash'] ?? '') . ' ' . ($b['amenity_name'] ?? '') . ' ' . ($b['first_name'] ?? '') . ' ' . ($b['last_name'] ?? '') . ' ' . ($b['unit_number'] ?? '') . ' ' . ($b['section_name'] ?? ''))) ?>"
                        data-id="<?= $b['id'] ?>" data-json="<?= esc(json_encode([
                              'id' => $b['id'],
                              'status' => $b['status'],
                              'short_hash' => $b['short_hash'] ?? '',
                              'amenity_name' => $b['amenity_name'] ?? '',
                              'amenity_image' => $amenityImg,
                              'first_name' => $b['first_name'] ?? '',
                              'last_name' => $b['last_name'] ?? '',
                              'avatar' => $b['avatar'] ?? '',
                              'role_name' => $roleName,
                              'unit_number' => $b['unit_number'] ?? '',
                              'section_name' => $b['section_name'] ?? '',
                              'start_time' => $b['start_time'],
                              'end_time' => $b['end_time'],
                              'price' => $b['price'] ?? null,
                              'created_at' => $b['created_at'] ?? '',
                              'time_range' => $formatTimeRange($b['start_time'], $b['end_time']),
                              'date_label' => $formatDate($b['start_time']),
                          ])) ?>">
                        <td>
                            <span
                                style="font-family:'SF Mono',SFMono-Regular,Menlo,Consolas,monospace;font-size:.8rem;font-weight:600;color:#6366f1;letter-spacing:.03em;background:#f5f3ff;padding:.2rem .5rem;border-radius:.35rem;border:1px solid #e0e7ff">#<?= esc($b['short_hash'] ?? $b['id']) ?></span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <?php if ($amenityImg): ?>
                                    <img src="<?= $amenityImg ?>" style="width:32px;height:32px;border-radius:8px;object-fit:cover"
                                        alt="">
                                <?php else: ?>
                                    <div
                                        style="width:32px;height:32px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center">
                                        <i class="bi bi-building" style="color:#64748b;font-size:.85rem"></i>
                                    </div>
                                <?php endif; ?>
                                <span class="fw-medium"><?= esc($b['amenity_name'] ?? 'N/A') ?></span>
                            </div>
                        </td>
                        <td><?= esc(($b['first_name'] ?? '') . ' ' . ($b['last_name'] ?? '')) ?></td>
                        <td><span class="badge bg-light text-dark border"
                                style="font-size:.75rem"><?= esc($b['unit_number'] ?? '—') ?></span></td>
                        <td><?= $formatDate($b['start_time']) ?></td>
                        <td><span class="text-muted"
                                style="font-size:.8rem"><?= $formatTimeRange($b['start_time'], $b['end_time']) ?></span></td>
                        <td><span class="bk-badge <?= $st[1] ?>"><i class="bi <?= $st[2] ?>"></i> <?= $st[0] ?></span></td>
                        <td class="text-end" onclick="event.stopPropagation()">
                            <div class="bk-actions-dd dropdown">
                                <button class="btn btn-sm btn-light border" data-bs-toggle="dropdown"
                                    style="padding:.25rem .45rem"><i class="bi bi-three-dots-vertical"></i></button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item text-danger" href="#"
                                            onclick="confirmDeleteBooking(<?= $b['id'] ?>)"><i class="bi bi-trash3"></i>
                                            Eliminar Reserva</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<!-- ═══ MODAL: CREAR RESERVACIÓN ═══ -->
<div class="modal fade bk-create-modal" id="createBookingModal" tabindex="-1" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title fw-bold">Crear Reservación</h5>
                    <p class="text-muted small mb-0">Selecciona una amenidad y horario para crear una nueva reservación
                    </p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3 mb-3">
                    <!-- User Selector -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Usuario</label>
                        <div class="user-selector" id="userSelector">
                            <div class="user-selector-trigger" id="userTrigger" tabindex="0">
                                <span id="userTriggerLabel" class="text-muted">Seleccionar usuario...</span>
                                <i class="bi bi-chevron-expand" style="font-size:.75rem;color:#94a3b8"></i>
                            </div>
                            <div class="user-selector-dropdown" id="userDropdown">
                                <input type="text" class="user-selector-search" id="userSearchInput"
                                    placeholder="Buscar por nombre...">
                                <div id="userListContainer"></div>
                            </div>
                        </div>
                        <input type="hidden" id="selectedUserId">
                        <input type="hidden" id="selectedUnitId">
                    </div>
                    <!-- Amenity Selector -->
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="font-size:.85rem">Amenidad</label>
                        <select class="form-select" id="amenitySelect"
                            style="border-radius:.5rem;padding:.6rem 1rem;font-size:.9rem;border:1px solid #d0d8e2">
                            <option value="">Seleccionar amenidad...</option>
                            <?php foreach ($amenities as $a): ?>
                                <option value="<?= $a['id'] ?>"
                                    data-img="<?= !empty($a['image']) ? base_url('admin/amenidades/imagen/' . $a['image']) : '' ?>">
                                    <?= esc($a['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Amenity Info + Calendar + Slots (hidden until amenity selected) -->
                <div id="amenityPanel" style="display:none">
                    <div class="amenity-info-card" id="amenityInfoCard"></div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Selecciona una fecha</label>
                            <div class="bk-cal" id="bookingCalendar"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold" style="font-size:.85rem">Selecciona un horario</label>
                            <div class="slot-list" id="slotList">
                                <div class="text-center text-muted py-4" style="font-size:.85rem">
                                    <i class="bi bi-calendar3 d-block mb-2" style="font-size:1.5rem;color:#cbd5e1"></i>
                                    Selecciona una fecha para ver horarios disponibles
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn px-4" id="btnCreateBooking" disabled
                    style="background:#1e293b;color:#fff;font-weight:600">
                    Crear Reservación
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ MODAL: DETALLE DE RESERVACIÓN ═══ -->
<div class="modal fade bk-detail-modal" id="detailBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold mb-0">Detalles de reservación</h6>
                <span id="detailReservationNum" class="ms-2"
                    style="font-family:'SF Mono',SFMono-Regular,Menlo,Consolas,monospace;font-size:.78rem;font-weight:600;color:#6366f1;letter-spacing:.03em;background:#f5f3ff;padding:.2rem .5rem;border-radius:.35rem;border:1px solid #e0e7ff"></span>
                <span id="detailStatusBadge" class="ms-2"></span>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="detail-info-card">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <div id="detailAmenityImg"
                                style="width:48px;height:48px;border-radius:10px;background:#1e293b;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                                <i class="bi bi-building text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold" id="detailAmenityName">—</div>
                                <div class="text-muted" style="font-size:.78rem"><i
                                        class="bi bi-calendar3 me-1"></i><span id="detailDate">—</span></div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div id="detailUserAvatar"
                                style="width:36px;height:36px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;overflow:hidden;flex-shrink:0">
                                <i class="bi bi-person" style="color:#64748b"></i>
                            </div>
                            <div>
                                <div class="fw-semibold" style="font-size:.88rem" id="detailUserName">—</div>
                                <div style="font-size:.72rem" id="detailUserRole" class="text-muted">—</div>
                            </div>
                        </div>
                    </div>
                    <div class="detail-info-grid">
                        <div class="info-block">
                            <div class="label"><i class="bi bi-clock me-1"></i> Hora</div>
                            <div class="value" id="detailTime">—</div>
                        </div>
                        <div class="info-block">
                            <div class="label"><i class="bi bi-currency-dollar me-1"></i> Cargo</div>
                            <div class="value" id="detailPrice">N/A</div>
                        </div>
                    </div>
                </div>
                <div class="detail-meta" id="detailCreatedAt">
                    <i class="bi bi-calendar-plus"></i> <span>—</span>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <div id="detailPendingActions" style="display:none" class="d-flex gap-2">
                    <button class="btn-approve" id="btnApproveDetail"><i class="bi bi-check-lg me-1"></i>
                        Aprobar</button>
                    <button class="btn-reject" id="btnRejectDetail"><i class="bi bi-x-lg me-1"></i> Rechazar</button>
                </div>
                <div class="ms-auto">
                    <button class="btn-delete-booking" id="btnDeleteDetail"><i class="bi bi-trash3"></i> Eliminar
                        Reserva</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const BASE = '<?= base_url() ?>';
        const CSRF_NAME = '<?= csrf_token() ?>';
        const CSRF_HASH = '<?= csrf_hash() ?>';
        const MONTHS_ES = ['', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];

        // ────── TOAST ──────
        function showToast(icon, title) {
            if (window.Swal) {
                Swal.fire({ toast: true, position: 'top-end', icon: icon, title: title, showConfirmButton: false, timer: 2500, timerProgressBar: true });
            }
        }

        // ────── TABLE FILTERS ──────
        const searchInput = document.getElementById('bkSearch');
        const statusBtns = document.querySelectorAll('#statusFilters .bk-pill');
        const amenityFilter = document.getElementById('amenityFilter');
        let activeStatusFilter = 'all';

        function applyFilters() {
            const q = (searchInput?.value || '').toLowerCase();
            const amenity = amenityFilter?.value || '';
            document.querySelectorAll('#bkTable tbody .bk-row').forEach(tr => {
                const s = tr.dataset.status;
                const a = tr.dataset.amenity || '';
                const search = tr.dataset.search || '';
                const matchStatus = activeStatusFilter === 'all' || s === activeStatusFilter;
                const matchAmenity = !amenity || a === amenity;
                const matchSearch = !q || search.includes(q);
                tr.style.display = (matchStatus && matchAmenity && matchSearch) ? '' : 'none';
            });
        }

        statusBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                statusBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                activeStatusFilter = btn.dataset.filter;
                applyFilters();
            });
        });
        if (searchInput) searchInput.addEventListener('input', applyFilters);
        if (amenityFilter) amenityFilter.addEventListener('change', applyFilters);

        // ────── ROW CLICK → DETAIL MODAL ──────
        document.querySelectorAll('.bk-row').forEach(tr => {
            tr.addEventListener('click', function () {
                const d = JSON.parse(this.dataset.json);
                openDetailModal(d);
            });
        });

        function openDetailModal(d) {
            const statusMap = {
                pending: { label: 'Pendiente', cls: 'bk-badge-pending', icon: 'bi-clock-history' },
                approved: { label: 'Aprobado', cls: 'bk-badge-approved', icon: 'bi-check-circle' },
                rejected: { label: 'Rechazado', cls: 'bk-badge-rejected', icon: 'bi-x-circle' },
                cancelled: { label: 'Cancelado', cls: 'bk-badge-cancelled', icon: 'bi-slash-circle' },
            };
            const st = statusMap[d.status] || statusMap.cancelled;
            document.getElementById('detailStatusBadge').innerHTML = `<span class="bk-badge ${st.cls}"><i class="bi ${st.icon}"></i> ${st.label}</span>`;
            document.getElementById('detailReservationNum').textContent = d.short_hash ? '#' + d.short_hash : '#' + d.id;

            // Amenity info
            const imgEl = document.getElementById('detailAmenityImg');
            if (d.amenity_image) {
                imgEl.innerHTML = `<img src="${d.amenity_image}" style="width:100%;height:100%;object-fit:cover">`;
            } else {
                imgEl.innerHTML = `<i class="bi bi-building text-white"></i>`;
                imgEl.style.background = '#1e293b';
            }
            document.getElementById('detailAmenityName').textContent = d.amenity_name || '—';
            document.getElementById('detailDate').textContent = d.date_label || '—';

            // User info
            const avatarEl = document.getElementById('detailUserAvatar');
            if (d.avatar) {
                avatarEl.innerHTML = `<img src="${BASE}admin/configuracion/avatar/${d.avatar}" style="width:100%;height:100%;object-fit:cover">`;
            } else {
                const ini = ((d.first_name || '')[0] || '') + ((d.last_name || '')[0] || '');
                avatarEl.innerHTML = `<span style="font-weight:600;font-size:.8rem;color:#475569">${ini.toUpperCase()}</span>`;
            }
            document.getElementById('detailUserName').textContent = (d.first_name || '') + ' ' + (d.last_name || '');
            const roleColor = d.role_name === 'Administrador' ? '#16a34a' : '#6366f1';
            document.getElementById('detailUserRole').innerHTML = `<i class="bi bi-shield-check" style="color:${roleColor}"></i> ${d.role_name || 'Residente'}`;

            document.getElementById('detailTime').textContent = d.time_range || '—';
            document.getElementById('detailPrice').textContent = d.price ? ('$' + parseFloat(d.price).toFixed(2)) : 'N/A';

            // Created
            if (d.created_at) {
                const cd = new Date(d.created_at);
                const mo = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                document.getElementById('detailCreatedAt').innerHTML = `<i class="bi bi-calendar-plus"></i> Creado ${cd.getDate()} ${mo[cd.getMonth()]} ${cd.getFullYear()}, ${cd.getHours().toString().padStart(2, '0')}:${cd.getMinutes().toString().padStart(2, '0')}`;
            }

            // Pending actions — solo mostrar si el estado es pending (solicitud de residente)
            const pendingDiv = document.getElementById('detailPendingActions');
            if (d.status === 'pending') {
                pendingDiv.style.display = 'flex';
                pendingDiv.style.visibility = 'visible';
            } else {
                pendingDiv.style.display = 'none';
                pendingDiv.style.visibility = 'hidden';
            }

            // Wire buttons
            document.getElementById('btnDeleteDetail').onclick = () => confirmDeleteBooking(d.id);
            document.getElementById('btnApproveDetail').onclick = () => updateBookingStatus(d.id, 'aprobar');
            document.getElementById('btnRejectDetail').onclick = () => updateBookingStatus(d.id, 'rechazar');

            bootstrap.Modal.getOrCreateInstance(document.getElementById('detailBookingModal')).show();
        }

        // ────── DELETE ──────
        window.confirmDeleteBooking = async function (id) {
            const r = await Swal.fire({
                title: '¿Eliminar reserva?', text: 'Esta acción no se puede deshacer.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#64748b',
                confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
            });
            if (!r.isConfirmed) return;
            try {
                const res = await fetch(BASE + 'admin/amenidades/reservas/eliminar/' + id, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', [CSRF_NAME]: CSRF_HASH } });
                const j = await res.json();
                if (j.status === 200) { showToast('success', j.message); setTimeout(() => location.reload(), 1500); }
                else showToast('error', j.error || 'Error');
            } catch (e) { showToast('error', 'Error de red'); }
        };

        async function updateBookingStatus(id, action) {
            const r = await Swal.fire({
                title: `¿${action.charAt(0).toUpperCase() + action.slice(1)} reserva?`, icon: 'question',
                showCancelButton: true, confirmButtonText: 'Sí', cancelButtonText: 'No', confirmButtonColor: '#1e293b'
            });
            if (!r.isConfirmed) return;
            try {
                const res = await fetch(BASE + 'admin/amenidades/reservas/' + action + '/' + id, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', [CSRF_NAME]: CSRF_HASH } });
                const j = await res.json();
                if (j.status === 200) { showToast('success', j.message); setTimeout(() => location.reload(), 1500); }
                else showToast('error', j.error || 'Error');
            } catch (e) { showToast('error', 'Error de red'); }
        }

        // ────── CREATE MODAL ──────
        let selectedUser = null;
        let selectedAmenityId = null;
        let selectedDate = null;
        let selectedSlot = null;
        let amenityData = null;
        let calMonth, calYear;

        const userTrigger = document.getElementById('userTrigger');
        const userDropdown = document.getElementById('userDropdown');
        const userSearch = document.getElementById('userSearchInput');
        const amenitySelect = document.getElementById('amenitySelect');
        const amenityPanel = document.getElementById('amenityPanel');
        const btnCreate = document.getElementById('btnCreateBooking');

        // User Selector
        userTrigger.addEventListener('click', () => {
            userDropdown.classList.toggle('open');
            userTrigger.classList.toggle('open');
            if (userDropdown.classList.contains('open')) {
                userSearch.focus();
                fetchUsers('');
            }
        });

        document.addEventListener('click', (e) => {
            if (!e.target.closest('#userSelector')) {
                userDropdown.classList.remove('open');
                userTrigger.classList.remove('open');
            }
        });

        let userSearchTimer;
        userSearch.addEventListener('input', () => {
            clearTimeout(userSearchTimer);
            userSearchTimer = setTimeout(() => fetchUsers(userSearch.value), 250);
        });

        async function fetchUsers(q) {
            try {
                const res = await fetch(BASE + 'admin/amenidades/reservas/usuarios?q=' + encodeURIComponent(q));
                const data = await res.json();
                renderUserList(data.admins || [], data.residents || []);
            } catch (e) { console.error(e); }
        }

        function renderUserList(admins, residents) {
            const container = document.getElementById('userListContainer');
            let html = '';
            if (admins.length) {
                html += '<div class="user-group-label">Administradores</div>';
                admins.forEach(u => { html += userOptionHtml(u); });
            }
            if (residents.length) {
                html += '<div class="user-group-label">Residentes</div>';
                residents.forEach(u => { html += userOptionHtml(u); });
            }
            if (!admins.length && !residents.length) {
                html = '<div class="text-center text-muted py-3" style="font-size:.85rem">No se encontraron usuarios</div>';
            }
            container.innerHTML = html;
            container.querySelectorAll('.user-option').forEach(opt => {
                opt.addEventListener('click', () => selectUser(opt));
            });
        }

        function userOptionHtml(u) {
            const initials = ((u.first_name || '')[0] || '') + ((u.last_name || '')[0] || '');
            const avatarContent = u.avatar
                ? `<img src="${BASE}admin/configuracion/avatar/${u.avatar}" alt="">`
                : `<span style="font-weight:600;font-size:.75rem;color:#475569">${initials.toUpperCase()}</span>`;
            const meta = u.role_label === 'ADMIN' ? 'Administrador' : ((u.section_name || '') + (u.unit_number ? ' · ' + u.unit_number : ''));
            return `<div class="user-option" data-user-id="${u.user_id}" data-unit-id="${u.unit_id || ''}" data-name="${(u.first_name || '')} ${(u.last_name || '')}">
            <div class="user-avatar">${avatarContent}</div>
            <div class="user-info">
                <div class="user-name">${(u.first_name || '')} ${(u.last_name || '')}</div>
                <div class="user-meta">${meta}</div>
            </div>
        </div>`;
        }

        function selectUser(opt) {
            selectedUser = { id: opt.dataset.userId, unitId: opt.dataset.unitId, name: opt.dataset.name };
            document.getElementById('selectedUserId').value = selectedUser.id;
            document.getElementById('selectedUnitId').value = selectedUser.unitId || '';
            document.getElementById('userTriggerLabel').textContent = selectedUser.name;
            document.getElementById('userTriggerLabel').classList.remove('text-muted');
            userDropdown.classList.remove('open');
            userTrigger.classList.remove('open');
            checkCanCreate();
        }

        // Amenity Selector
        amenitySelect.addEventListener('change', async function () {
            selectedAmenityId = this.value;
            selectedDate = null;
            selectedSlot = null;
            if (!selectedAmenityId) { amenityPanel.style.display = 'none'; checkCanCreate(); return; }

            const now = new Date();
            calMonth = now.getMonth() + 1;
            calYear = now.getFullYear();
            await loadAmenityAvailability();
            amenityPanel.style.display = 'block';
            checkCanCreate();
        });

        async function loadAmenityAvailability() {
            try {
                const res = await fetch(BASE + 'admin/amenidades/reservas/disponibilidad/' + selectedAmenityId + '?month=' + calMonth + '&year=' + calYear);
                amenityData = await res.json();
                renderAmenityInfo();
                renderCalendar();
                renderSlots(null);
            } catch (e) { console.error(e); }
        }

        function renderAmenityInfo() {
            const a = amenityData.amenity;
            const imgSrc = a.image ? BASE + 'admin/amenidades/imagen/' + a.image : '';
            const intervalLabel = a.reservation_interval === 'full_day' ? 'Día completo' : a.reservation_interval + ' hora(s)';
            const maxLabel = a.max_active_reservations === 'unlimited' ? 'Ilimitado' : a.max_active_reservations;
            document.getElementById('amenityInfoCard').innerHTML = `
            ${imgSrc ? `<img src="${imgSrc}" alt="">` : '<div style="width:64px;height:64px;border-radius:.5rem;background:#334155;display:flex;align-items:center;justify-content:center"><i class="bi bi-building text-white fs-4"></i></div>'}
            <div class="amenity-details">
                <h6>${a.name}</h6>
                <div class="meta">Intervalo de Reservación: <strong>${intervalLabel}</strong></div>
                <div class="meta">Máximo de Reservas Activas: <strong>${maxLabel}</strong></div>
            </div>`;
        }

        function renderCalendar() {
            const container = document.getElementById('bookingCalendar');
            const daysInMonth = new Date(calYear, calMonth, 0).getDate();
            const firstDay = new Date(calYear, calMonth - 1, 1).getDay(); // 0=Sun
            const today = new Date();
            const todayStr = `${today.getFullYear()}-${String(today.getMonth() + 1).padStart(2, '0')}-${String(today.getDate()).padStart(2, '0')}`;

            // Build set of booked dates
            const bookedDates = {};
            (amenityData.bookings || []).forEach(b => {
                const d = b.start_time.split(' ')[0];
                bookedDates[d] = (bookedDates[d] || 0) + 1;
            });

            // Schedule map
            const schedMap = amenityData.schedule || {};

            let availableFrom = null;
            let blockedDatesArr = [];
            try {
                if (amenityData.amenity.available_from) availableFrom = amenityData.amenity.available_from;
                if (amenityData.amenity.blocked_dates) {
                    blockedDatesArr = JSON.parse(amenityData.amenity.blocked_dates);
                }
            } catch (e) { }

            let html = `<div class="bk-cal-header">
            <button class="bk-cal-nav" onclick="calNavigate(-1)"><i class="bi bi-chevron-left" style="font-size:.7rem"></i></button>
            <span class="cal-title">${MONTHS_ES[calMonth]} ${calYear}</span>
            <button class="bk-cal-nav" onclick="calNavigate(1)"><i class="bi bi-chevron-right" style="font-size:.7rem"></i></button>
        </div><div class="bk-cal-grid">`;

            const dayLabels = ['do', 'lu', 'ma', 'mi', 'ju', 'vi', 'sá'];
            dayLabels.forEach(d => { html += `<div class="bk-cal-daylbl">${d}</div>`; });

            for (let i = 0; i < firstDay; i++) html += '<div class="bk-cal-cell empty"></div>';

            for (let day = 1; day <= daysInMonth; day++) {
                const dateStr = `${calYear}-${String(calMonth).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
                const stdDow = new Date(calYear, calMonth - 1, day).getDay();
                const dbDow = stdDow === 0 ? 6 : stdDow - 1; // 0=Mon ... 6=Sun in our DB
                const sched = schedMap[dbDow];

                const isPast = dateStr < todayStr;
                const isDisabled = (sched && !parseInt(sched.is_enabled)) ||
                    (availableFrom && dateStr < availableFrom) ||
                    (blockedDatesArr.includes(dateStr));

                const isToday = dateStr === todayStr;
                const isSelected = dateStr === selectedDate;
                const hasBooking = bookedDates[dateStr];

                let cls = 'bk-cal-cell';
                if (isPast) cls += ' past';
                else if (isDisabled) cls += ' disabled';
                if (isToday) cls += ' today';
                if (isSelected) cls += ' selected';
                if (hasBooking) cls += ' has-booking';

                const clickable = !isPast && !isDisabled;
                html += `<div class="${cls}" ${clickable ? `onclick="selectCalDate('${dateStr}')"` : ''}><span>${day}</span></div>`;
            }

            html += '</div>';
            container.innerHTML = html;
        }

        window.calNavigate = function (dir) {
            calMonth += dir;
            if (calMonth > 12) { calMonth = 1; calYear++; }
            if (calMonth < 1) { calMonth = 12; calYear--; }
            loadAmenityAvailability();
        };

        window.selectCalDate = function (dateStr) {
            selectedDate = dateStr;
            selectedSlot = null;
            renderCalendar();
            renderSlots(dateStr);
            checkCanCreate();
        };

        function renderSlots(dateStr) {
            const container = document.getElementById('slotList');
            if (!dateStr || !amenityData) {
                container.innerHTML = '<div class="text-center text-muted py-4" style="font-size:.85rem"><i class="bi bi-calendar3 d-block mb-2" style="font-size:1.5rem;color:#cbd5e1"></i>Selecciona una fecha para ver horarios disponibles</div>';
                return;
            }

            const interval = amenityData.amenity.reservation_interval;
            const stdDow = new Date(dateStr + 'T12:00:00').getDay();
            const dbDow = stdDow === 0 ? 6 : stdDow - 1;
            const sched = amenityData.schedule[dbDow];

            if (!sched || !parseInt(sched.is_enabled)) {
                container.innerHTML = '<div class="text-center text-muted py-4" style="font-size:.85rem">No disponible este día</div>';
                return;
            }

            // Get existing bookings for this date
            const dayBookings = (amenityData.bookings || []).filter(b => b.start_time.startsWith(dateStr));

            if (interval === 'full_day') {
                const isBooked = dayBookings.length > 0;
                container.innerHTML = `<div class="slot-item ${isBooked ? '' : ''}" ${!isBooked ? `onclick="selectSlot(this,'${dateStr} 00:00:00','${dateStr} 23:59:59')"` : ''} style="${isBooked ? 'opacity:.5;cursor:not-allowed' : ''}">
                <i class="bi ${isBooked ? 'bi-x-circle text-danger' : 'bi-clock slot-icon'}"></i>
                <div><div class="slot-label">Todo el día</div><div class="slot-avail">${isBooked ? 'Ocupado' : 'Disponible'}</div></div>
            </div>`;
                return;
            }

            // Generate hourly slots
            const openH = parseInt(sched.open_time.split(':')[0]);
            const closeH = parseInt(sched.close_time.split(':')[0]);
            const intHours = parseInt(interval) || 1;
            let html = '';

            for (let h = openH; h + intHours <= closeH; h += intHours) {
                const startStr = `${dateStr} ${String(h).padStart(2, '0')}:00:00`;
                const endStr = `${dateStr} ${String(h + intHours).padStart(2, '0')}:00:00`;
                const isBooked = dayBookings.some(b => {
                    const bs = new Date(b.start_time).getHours();
                    const be = new Date(b.end_time).getHours();
                    return (h >= bs && h < be) || (h + intHours > bs && h + intHours <= be);
                });

                html += `<div class="slot-item" ${!isBooked ? `onclick="selectSlot(this,'${startStr}','${endStr}')"` : ''} style="${isBooked ? 'opacity:.5;cursor:not-allowed' : ''}">
                <i class="bi ${isBooked ? 'bi-x-circle text-danger' : 'bi-clock slot-icon'}"></i>
                <div><div class="slot-label">${String(h).padStart(2, '0')}:00 - ${String(h + intHours).padStart(2, '0')}:00</div><div class="slot-avail">${isBooked ? 'Ocupado' : 'Disponible'}</div></div>
            </div>`;
            }

            container.innerHTML = html || '<div class="text-center text-muted py-4">No hay horarios disponibles</div>';
        }

        window.selectSlot = function (el, start, end) {
            document.querySelectorAll('.slot-item').forEach(s => s.classList.remove('selected'));
            el.classList.add('selected');
            selectedSlot = { start, end };
            checkCanCreate();
        };

        function checkCanCreate() {
            btnCreate.disabled = !(selectedUser && selectedAmenityId && selectedDate && selectedSlot);
        }

        // Create Booking
        btnCreate.addEventListener('click', async () => {
            if (!selectedUser || !selectedAmenityId || !selectedSlot) return;
            btnCreate.disabled = true;
            btnCreate.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Creando...';

            try {
                const res = await fetch(BASE + 'admin/amenidades/reservas/crear', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({
                        amenity_id: selectedAmenityId,
                        user_id: selectedUser.id,
                        unit_id: selectedUser.unitId || null,
                        start_time: selectedSlot.start,
                        end_time: selectedSlot.end,
                    })
                });
                const j = await res.json();
                if (j.status === 201) {
                    bootstrap.Modal.getInstance(document.getElementById('createBookingModal'))?.hide();
                    showToast('success', j.message);
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showToast('error', j.error || 'Error al crear');
                    btnCreate.disabled = false;
                    btnCreate.innerHTML = 'Crear Reservación';
                }
            } catch (e) {
                showToast('error', 'Error de conexión');
                btnCreate.disabled = false;
                btnCreate.innerHTML = 'Crear Reservación';
            }
        });

        // Reset modal on close
        document.getElementById('createBookingModal').addEventListener('hidden.bs.modal', () => {
            selectedUser = null; selectedAmenityId = null; selectedDate = null; selectedSlot = null; amenityData = null;
            document.getElementById('selectedUserId').value = '';
            document.getElementById('selectedUnitId').value = '';
            document.getElementById('userTriggerLabel').textContent = 'Seleccionar usuario...';
            document.getElementById('userTriggerLabel').classList.add('text-muted');
            amenitySelect.value = '';
            amenityPanel.style.display = 'none';
            userSearch.value = '';
            document.getElementById('userListContainer').innerHTML = '';
            btnCreate.disabled = true;
            btnCreate.innerHTML = 'Crear Reservación';
        });
    });
</script>

<?= $this->endSection() ?>