<?php
$meses = [
    'January' => 'Enero',
    'February' => 'Febrero',
    'March' => 'Marzo',
    'April' => 'Abril',
    'May' => 'Mayo',
    'June' => 'Junio',
    'July' => 'Julio',
    'August' => 'Agosto',
    'September' => 'Septiembre',
    'October' => 'Octubre',
    'November' => 'Noviembre',
    'December' => 'Diciembre'
];
function fnDateSpanish($date, $meses)
{
    if (!$date)
        return 'No definida';
    $time = strtotime($date);
    return date('j', $time) . ' de ' . $meses[date('F', $time)] . ' de ' . date('Y', $time);
}
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --det-bg: #EEF1F9;
        --det-card: #ffffff;
        --det-text: #1e293b;
        --det-muted: #64748b;
        --det-border: #e2e8f0;
        --det-primary: #232d3f;
        --det-success: #10b981;
        --det-danger: #ef4444;
        --det-danger-light: #fee2e2;
        --det-danger-border: #fca5a5;
        --det-warning: #f59e0b;
        --det-radius: 8px;
        --det-shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --det-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    body {
        background-color: var(--det-bg);
    }

    /* Header */
    .det-header {
        background-color: var(--det-primary);
        color: white;
        padding: 1.5rem 2rem;
        border-radius: var(--det-radius);
        margin-top: -1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--det-shadow-md);
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
        color: #3F67AC;
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

    .det-back-btn {
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: white;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
    }

    .det-back-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    .det-title-area h1 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
    }

    .det-title-area p {
        margin: 0;
        font-size: 0.8rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* KPIs */
    .det-kpi-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .det-kpi-card {
        background: var(--det-card);
        border: 1px solid var(--det-border);
        border-radius: var(--det-radius);
        padding: 1.25rem;
        box-shadow: var(--det-shadow-sm);
    }

    .det-kpi-label {
        font-size: 0.8rem;
        color: var(--det-muted);
        margin-bottom: 0.25rem;
    }

    .det-kpi-val {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--det-text);
    }

    .val-green {
        color: var(--det-success);
    }

    .val-warning {
        color: var(--det-warning);
    }

    /* Progress Banner */
    .det-progress-banner {
        background: var(--det-card);
        border: 1px solid var(--det-border);
        border-radius: var(--det-radius);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: var(--det-shadow-sm);
    }

    .det-prog-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--det-text);
    }

    .det-prog-sub {
        font-weight: 400;
        color: var(--det-muted);
    }

    .det-prog-bar-container {
        height: 8px;
        background: var(--det-border);
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .det-prog-fill {
        height: 100%;
        background: var(--det-success);
        transition: width 0.3s;
    }

    .det-prog-footer {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: var(--det-muted);
    }

    /* Tabs */
    .det-tabs {
        display: flex;
        gap: 1rem;
        border-bottom: 1px solid var(--det-border);
        margin-bottom: 1.5rem;
    }

    .det-tab {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--det-muted);
        cursor: pointer;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
    }

    .det-tab.active {
        color: var(--det-text);
        border-bottom-color: var(--det-primary);
    }

    .det-tab-content {
        display: none;
    }

    .det-tab-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Finanzas View */
    .fin-toolbar {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .fin-search {
        padding: 0.5rem 1rem;
        border: 1px solid var(--det-border);
        border-radius: 6px;
        width: 300px;
        font-size: 0.85rem;
    }

    .fin-filter {
        padding: 0.5rem 1rem;
        border: 1px solid var(--det-border);
        border-radius: 6px;
        font-size: 0.85rem;
        background: white;
    }

    .fin-table {
        width: 100%;
        border-collapse: collapse;
        background: var(--det-card);
        border: 1px solid var(--det-border);
        border-radius: var(--det-radius);
        overflow: hidden;
    }

    .fin-table th {
        background: #f8fafc;
        padding: 0.75rem 1rem;
        font-size: 0.75rem;
        color: var(--det-muted);
        font-weight: 600;
        text-align: left;
        border-bottom: 1px solid var(--det-border);
    }

    .fin-table td {
        padding: 1rem;
        font-size: 0.85rem;
        color: var(--det-text);
        border-bottom: 1px solid var(--det-border);
        vertical-align: middle;
    }

    .fin-table tr:last-child td {
        border-bottom: none;
    }

    .status-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-paid {
        background: #d1fae5;
        color: #10b981;
    }

    /* Action Buttons */
    .ext-actions {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .act-btn-pay {
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #10b981;
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: bold;
        transition: all 0.2s;
        position: relative;
    }

    .act-btn-pay:hover {
        background: #d1fae5;
        transform: translateY(-1px);
    }

    .act-btn-dots {
        background: transparent;
        border: 1px solid var(--det-border);
        color: var(--det-muted);
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.85rem;
        transition: all 0.2s;
        position: relative;
    }

    .act-btn-dots:hover {
        background: #f1f5f9;
        color: var(--det-text);
        border-color: #cbd5e1;
    }

    /* Tooltips */
    .act-btn-pay[data-tooltip]::after,
    .act-btn-dots[data-tooltip]::after {
        content: attr(data-tooltip);
        opacity: 0;
        visibility: hidden;
        position: absolute;
        bottom: calc(100% + 6px);
        left: 50%;
        transform: translate(-50%, 4px);
        background: #1e293b;
        color: #fff;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 500;
        white-space: nowrap;
        box-shadow: 0 4px 6px rgba(0, 0, 0, .1);
        z-index: 100;
        pointer-events: none;
        transition: all 0.2s cubic-bezier(.175, .885, .32, 1.275);
    }

    .act-btn-pay[data-tooltip]:hover::after,
    .act-btn-dots[data-tooltip]:hover::after {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, 0);
    }

    /* Dropdown Menu */
    .ext-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 4px;
        background: white;
        border: 1px solid var(--det-border);
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, .12);
        min-width: 180px;
        z-index: 200;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-4px);
        transition: all 0.15s ease;
    }

    .ext-dropdown.open {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .ext-dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem 1rem;
        font-size: 0.85rem;
        color: var(--det-text);
        cursor: pointer;
        transition: background 0.15s;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
    }

    .ext-dropdown-item:first-child {
        border-radius: 8px 8px 0 0;
    }

    .ext-dropdown-item:last-child {
        border-radius: 0 0 8px 8px;
    }

    .ext-dropdown-item:hover {
        background: #f8fafc;
    }

    .ext-dropdown-item.text-danger {
        color: var(--det-danger);
    }

    .ext-dropdown-item.text-danger:hover {
        background: #fef2f2;
    }

    .ext-dropdown-divider {
        border-top: 1px solid var(--det-border);
        margin: 0;
    }

    /* Overlay Modals */
    .ext-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .45);
        z-index: 10000;
        display: none;
        justify-content: center;
        align-items: center;
    }

    .ext-overlay.open {
        display: flex;
    }

    .ext-modal {
        background: white;
        border-radius: 12px;
        width: 520px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
        padding: 1.75rem;
        animation: extModalIn 0.25s ease;
    }

    @keyframes extModalIn {
        from {
            opacity: 0;
            transform: scale(.96) translateY(-8px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .ext-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .ext-modal-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
    }

    .ext-modal-close {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1rem;
        padding: 4px;
    }

    .ext-modal-close:hover {
        color: #1e293b;
    }

    .ext-info-banner {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.25rem;
        display: flex;
        justify-content: space-between;
    }

    .ext-info-col label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 2px;
        display: block;
    }

    .ext-info-col .val {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
    }

    .ext-info-col .val-green {
        color: #059669;
    }

    .ext-info-col .val-red {
        color: #ef4444;
    }

    .ext-form-group {
        margin-bottom: 1rem;
    }

    .ext-form-group label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.35rem;
        display: block;
    }

    .ext-form-group .hint {
        font-size: 0.78rem;
        color: #059669;
        margin-top: 2px;
    }

    .ext-input {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s;
    }

    .ext-input:focus {
        border-color: #3b82f6;
    }

    .ext-input-money {
        padding-left: 2rem;
    }

    .ext-input-wrap {
        position: relative;
    }

    .ext-input-prefix {
        position: absolute;
        left: 0.8rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-weight: 600;
    }

    .ext-select {
        width: 100%;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.6rem 0.8rem;
        font-size: 0.9rem;
        color: #1e293b;
        background: white;
        outline: none;
    }

    .ext-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        margin-top: 1.25rem;
    }

    .ext-btn-cancel {
        padding: 0.55rem 1.25rem;
        background: #fff;
        border: 1px solid #cbd5e1;
        color: #334155;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s;
    }

    .ext-btn-cancel:hover {
        background: #f8fafc;
    }

    .ext-btn-primary {
        padding: 0.55rem 1.25rem;
        background: #3F67AC;
        border: none;
        color: #fff;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .ext-btn-primary:hover {
        background: #334155;
    }

    .ext-btn-danger {
        padding: 0.55rem 1.25rem;
        background: #ef4444;
        border: none;
        color: #fff;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .ext-btn-danger:hover {
        background: #dc2626;
    }

    /* Upload Zone */
    .ext-upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.2s;
        position: relative;
    }

    .ext-upload-zone:hover {
        border-color: #94a3b8;
    }

    .ext-upload-zone i {
        font-size: 1.5rem;
        color: #94a3b8;
    }

    .ext-upload-zone p {
        font-size: 0.82rem;
        color: #64748b;
        margin: 0.5rem 0 0;
    }

    .ext-upload-zone span {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    .ext-upload-zone input[type=file] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }

    /* Toast */
    .ext-toast {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 11000;
        background: white;
        border-radius: 10px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transform: translateX(120%);
        transition: transform 0.35s cubic-bezier(.22, 1, .36, 1);
        border-left: 4px solid var(--det-success);
        max-width: 400px;
    }

    .ext-toast.toast-error {
        border-left-color: var(--det-danger);
    }

    .ext-toast.show {
        transform: translateX(0);
    }

    .ext-toast .t-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #d1fae5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #059669;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .ext-toast.toast-error .t-icon {
        background: #fee2e2;
        color: #dc2626;
    }

    .ext-toast .t-title {
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--det-text);
    }

    .ext-toast .t-msg {
        font-size: 0.78rem;
        color: var(--det-muted);
        margin-top: 1px;
    }

    /* Detalles View */
    .det-info-box {
        background: var(--det-card);
        border: 1px solid var(--det-border);
        border-radius: var(--det-radius);
        padding: 2rem;
        margin-bottom: 1.5rem;
        max-width: 600px;
    }

    .grid-2-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .det-label {
        font-size: 0.75rem;
        color: var(--det-muted);
        margin-bottom: 0.25rem;
    }

    .det-val {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--det-text);
    }

    .det-desc-box {
        background: #f1f5f9;
        padding: 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        color: var(--det-text);
        margin-top: 0.5rem;
    }

    .danger-zone {
        background: white;
        border: 1px solid var(--det-danger);
        border-radius: var(--det-radius);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        max-width: 600px;
    }

    .danger-zone-text h4 {
        color: var(--det-danger);
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .danger-zone-text p {
        color: var(--det-muted);
        margin: 0;
        font-size: 0.8rem;
    }

    .btn-danger {
        background: var(--det-danger);
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.85rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger:hover {
        background: #dc2626;
    }
</style>

<div class="row">
    <div class="col-12">



        <!-- ── Hero ── -->
        <div class="cc-hero">
            <div class="cc-hero-left">
                <a href="<?= base_url('admin/finanzas/extraordinarias') ?>" class="btn-back"><i
                        class="bi bi-arrow-left"></i></a>


                <h2 class="cc-hero-title"> <?= esc($fee['title']) ?></h2>
                <div class="cc-hero-divider"></div>
                <div class="cc-hero-breadcrumb">

                    <i class="bi bi-chevron-right"></i>
                    <?= date('M j, Y', strtotime($fee['created_at'])) ?>
                    <?php if ($fee['due_date']): ?>
                        -
                        <?= date('M j, Y', strtotime($fee['due_date'])) ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>



        <div class="det-kpi-row">
            <div class="det-kpi-card">
                <div class="det-kpi-label">Total Esperado</div>
                <div class="det-kpi-val">MX$<?= number_format($stats['expected'], 2) ?></div>
            </div>
            <div class="det-kpi-card">
                <div class="det-kpi-label">Total Recaudado</div>
                <div class="det-kpi-val val-green">MX$<?= number_format($stats['collected'], 2) ?></div>
            </div>
            <div class="det-kpi-card">
                <div class="det-kpi-label">Pendiente</div>
                <div class="det-kpi-val val-warning">MX$<?= number_format($stats['pending'], 2) ?></div>
            </div>
        </div>

        <div class="det-progress-banner">
            <div class="det-prog-header">
                <span>Progreso</span>
                <span class="det-prog-sub"><?= $stats['units_paid'] ?> / <?= $stats['units_total'] ?> unidades
                    pagadas</span>
            </div>
            <div class="det-prog-bar-container">
                <div class="det-prog-fill" style="width: <?= $stats['progress_perc'] ?>%"></div>
            </div>
            <div class="det-prog-footer">
                <span>MX$<?= number_format($stats['collected'], 2) ?> recaudado</span>
                <span><?= $stats['progress_perc'] ?>%</span>
            </div>
        </div>

        <div class="det-tabs">
            <div class="det-tab active" onclick="switchTab('finanzas', this)">Finanzas</div>
            <div class="det-tab" onclick="switchTab('detalles', this)">Detalles</div>
        </div>

        <!-- TAB: FINANZAS -->
        <div class="det-tab-content active" id="tab-finanzas">
            <div class="fin-toolbar">
                <input type="text" class="fin-search" id="searchInput" placeholder="Buscar por nombre de unidad..."
                    onkeyup="filterTable()">
                <select class="fin-filter" id="statusFilter" onchange="filterTable()">
                    <option value="all">Todas (<?= count($charges) ?>)</option>
                    <option value="paid">Pagadas</option>
                    <option value="pending">Pendientes</option>
                </select>
            </div>

            <table class="fin-table" id="finTable">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align:center;"></th>
                        <th>Unidad</th>
                        <th>Cargado</th>
                        <th>Pagadas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($charges as $idx => $charge): ?>
                        <tr class="unit-row" data-unit="<?= strtolower($charge['unit_number']) ?>"
                            data-status="<?= ($charge['status'] === 'paid' || $charge['status'] === 'completed') ? 'paid' : 'pending' ?>">
                            <td style="text-align:center;"><input type="checkbox"
                                    style="width:16px; height:16px; border-color:#cbd5e1; border-radius:4px; margin-top:5px;"
                                    disabled></td>
                            <td style="color:var(--det-text);"><?= esc($charge['unit_number']) ?></td>
                            <td style="color:var(--det-text);">MX$<?= number_format($charge['amount'], 2) ?></td>
                            <td>
                                <span style="color:var(--det-muted)">MX$<?= number_format($charge['paid_amount'], 2) ?>
                                    /</span>
                                <span style="color:var(--det-muted)">MX$<?= number_format($charge['amount'], 2) ?></span>
                            </td>
                            <td>
                                <?php if ($charge['status'] === 'paid' || $charge['status'] === 'completed'): ?>
                                    <span class="status-badge status-paid">Pagado</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending"
                                        style="background:#f97316; color:white; font-weight:500; text-transform:none; border-radius:12px; padding:0.2rem 0.6rem; font-size:0.75rem;">Pendientes</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="ext-actions">
                                    <button class="act-btn-pay" data-tooltip="Registrar Pago"
                                        onclick="openPayModal(<?= $charge['id'] ?>, '<?= esc($charge['unit_number']) ?>', <?= $charge['amount'] ?>, <?= $charge['paid_amount'] ?>, <?= $charge['balance'] ?>, '<?= esc($charge['resident_name']) ?>')">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                    <button class="act-btn-dots" data-tooltip="Más opciones"
                                        onclick="toggleDropdown(event, 'dd-<?= $idx ?>')">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <div class="ext-dropdown" id="dd-<?= $idx ?>">
                                        <button class="ext-dropdown-item"
                                            onclick="openEditModal(<?= $charge['id'] ?>, '<?= esc($charge['unit_number']) ?>', <?= $charge['amount'] ?>)">
                                            <i class="bi bi-pencil-square"></i> Editar monto
                                        </button>
                                        <div class="ext-dropdown-divider"></div>
                                        <button class="ext-dropdown-item text-danger"
                                            onclick="openDelChargeModal(<?= $charge['id'] ?>, '<?= esc($charge['unit_number']) ?>')">
                                            <i class="bi bi-trash"></i> Eliminar cargo
                                        </button>
                                    </div>
                                </div>
                            </td>
                        <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- TAB: DETALLES -->
        <div class="det-tab-content" id="tab-detalles">
            <div style="max-width: 650px; margin: 2rem auto;">
                <div style="display:flex; justify-content:flex-end; margin-bottom:1rem;">
                    <button class="ext-btn-outline"
                        style="border: 1px solid #cbd5e1; background:white; color:#1e293b; padding:0.4rem 1rem; border-radius:6px; font-size:0.85rem; font-weight:600; cursor:pointer;"
                        onclick="openEditFeeGlobal()">
                        <i class="bi bi-pencil-square"></i> Editar
                    </button>
                </div>

                <div class="det-info-box"
                    style="max-width:100%; width:100%; border:1px solid #e2e8f0; border-radius:8px; padding:2rem; box-shadow:0 1px 2px 0 rgba(0,0,0,0.02); margin-bottom:1.5rem; box-sizing:border-box;">
                    <div class="grid-2-details">
                        <div>
                            <div class="det-label">Titulo de la Cuota</div>
                            <div class="det-val"><?= esc($fee['title']) ?></div>
                        </div>
                        <div>
                            <div class="det-label">Categoria</div>
                            <div class="det-val"><?= esc($catName) ?></div>
                        </div>
                    </div>
                    <div class="grid-2-details">
                        <div>
                            <div class="det-label">Fecha Inicio</div>
                            <div class="det-val"><?= fnDateSpanish($fee['created_at'], $meses) ?></div>
                        </div>
                        <div>
                            <div class="det-label">Fecha de Vencimiento</div>
                            <div class="det-val"><?= fnDateSpanish($fee['due_date'], $meses) ?></div>
                        </div>
                    </div>
                    <div>
                        <div class="det-label">Descripción</div>
                        <div class="det-desc-box" style="border:1px solid #e2e8f0; background:#f8fafc; color:#334155;">
                            <?= $fee['description'] ? esc($fee['description']) : '<em>Sin descripción</em>' ?>
                        </div>
                    </div>
                </div>

                <div class="danger-zone"
                    style="max-width:100%; width:100%; border:1px solid #ef4444; border-radius:8px; padding:1.5rem; background:white; box-sizing:border-box; display:flex; justify-content:space-between; align-items:center;">
                    <div class="danger-zone-text">
                        <h4 style="color:#ef4444; margin:0 0 0.25rem 0; font-size:0.95rem; font-weight:600;">Eliminar
                            esta cuota</h4>
                        <p style="color:#64748b; margin:0; font-size:0.8rem;">Esto eliminará permanentemente todos los
                            cargos y pagos</p>
                    </div>
                    <button class="btn-danger"
                        style="background:#ef4444; border-radius:6px; padding:0.5rem 1.2rem; border:none; color:white; font-size:0.85rem; font-weight:500; cursor:pointer; display:flex; align-items:center; gap:0.4rem;"
                        onclick="openDelFeeModal(<?= $fee['id'] ?>)">
                        <i class="bi bi-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Registrar Pago -->
<div class="ext-overlay" id="payModalOverlay">
    <div class="ext-modal">
        <div class="ext-modal-header">
            <h3>Registrar Pago</h3>
            <button class="ext-modal-close" onclick="closePayModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="ext-info-banner">
            <div class="ext-info-col">
                <label><?= esc($fee['title']) ?></label>
                <div class="val">Unidad: <span id="pm-unit"></span> • <span id="pm-resident"
                        style="font-weight:400; font-size:0.88rem;"></span></div>
            </div>
        </div>
        <div class="ext-info-banner" style="gap:1.5rem;">
            <div class="ext-info-col"><label>Total Cargado</label>
                <div class="val" id="pm-total"></div>
            </div>
            <div class="ext-info-col"><label>Ya Pagado</label>
                <div class="val val-green" id="pm-paid"></div>
            </div>
            <div class="ext-info-col"><label>Saldo Restante</label>
                <div class="val val-red" id="pm-balance"></div>
            </div>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
            <div class="ext-form-group">
                <label>Monto *</label>
                <div class="ext-input-wrap"><span class="ext-input-prefix">$</span><input type="number" id="pm-amount"
                        class="ext-input ext-input-money" step="0.01" min="0.01"></div>
                <div class="hint" id="pm-hint"></div>
            </div>
            <div class="ext-form-group">
                <label>Método de Pago *</label>
                <select id="pm-method" class="ext-select">
                    <option>TRANSFERENCIA BANCARIA</option>
                    <option>EFECTIVO</option>
                    <option>CHEQUE</option>
                    <option>TARJETA</option>
                    <option>OTRO</option>
                </select>
            </div>
        </div>
        <div class="ext-form-group">
            <label>Fecha de Pago *</label>
            <div class="ext-input-wrap" style="max-width:260px;">
                <i class="bi bi-calendar ext-input-prefix" style="font-weight:400;"></i>
                <input type="text" id="pm-date" class="ext-input ext-input-money" readonly>
            </div>
        </div>
        <div class="ext-form-group">
            <label>Comprobante (opcional)</label>
            <div class="ext-upload-zone" id="pm-upload-zone">
                <i class="bi bi-cloud-arrow-up"></i>
                <p>Arrastre y suelte el archivo aquí, o haga clic para seleccionar</p>
                <span>Soportado: JPG, PNG, PDF (máx 10MB)</span>
                <input type="file" id="pm-file" accept=".jpg,.jpeg,.png,.pdf">
            </div>
        </div>
        <input type="hidden" id="pm-charge-id">
        <div class="ext-modal-footer">
            <button class="ext-btn-cancel" onclick="closePayModal()">Cancelar</button>
            <button class="ext-btn-primary" onclick="submitPay()"><i class="bi bi-check-lg"></i> Registrar Pago</button>
        </div>
    </div>
</div>

<!-- Modal: Editar Monto -->
<div class="ext-overlay" id="editModalOverlay">
    <div class="ext-modal" style="width:460px;">
        <div class="ext-modal-header">
            <h3>Editar Monto del Cargo</h3>
            <button class="ext-modal-close" onclick="closeEditModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="ext-info-banner">
            <div class="ext-info-col"><label>UNIDAD</label>
                <div class="val" id="em-unit" style="font-size:1.2rem;"></div>
            </div>
            <div class="ext-info-col"><label>CARGO ACTUAL</label>
                <div class="val" id="em-current" style="font-size:1.2rem;"></div>
            </div>
        </div>
        <p style="font-size:0.85rem; color:#3F67AC; margin:0 0 1rem;">Editar este monto solo afectará a esta unidad. Si
            en nuevo monto es menor, los pagos anteriores se reducirán para coincidir con el nuevo monto. Si el nuevo
            monto es mayor, la unidad deberá pagar el remanente.</p>
        <div class="ext-form-group">
            <label>Nuevo Monto del Cargo *</label>
            <div class="ext-input-wrap"><span class="ext-input-prefix">$</span><input type="number" id="em-amount"
                    class="ext-input ext-input-money" step="0.01" min="0"></div>
        </div>
        <input type="hidden" id="em-charge-id">
        <div class="ext-modal-footer">
            <button class="ext-btn-cancel" onclick="closeEditModal()">Cancelar</button>
            <button class="ext-btn-primary" onclick="submitEdit()">Actualizar Cargo</button>
        </div>
    </div>
</div>

<!-- Modal: Eliminar Cargo -->
<div class="ext-overlay" id="delModalOverlay">
    <div class="ext-modal" style="width:440px;">
        <div class="ext-modal-header">
            <h3>¿Eliminar cargo de esta unidad?</h3>
            <button class="ext-modal-close" onclick="closeDelModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="ext-info-banner">
            <div class="ext-info-col"><label>Cuota Extraordinaria</label>
                <div class="val"><?= esc($fee['title']) ?></div>
            </div>
            <div class="ext-info-col"><label>Unidad</label>
                <div class="val" id="dm-unit"></div>
            </div>
        </div>
        <p style="font-size:0.85rem; color:#64748b; margin:0 0 1.25rem;">Esto eliminará permanentemente el cargo y
            cualquier pago registrado para esta unidad. Elimine este cargo si lo que desea es que esta unidad no tenga
            que pagar esta cuota extraordinaria.</p>
        <input type="hidden" id="dm-charge-id">
        <div class="ext-modal-footer">
            <button class="ext-btn-cancel" onclick="closeDelModal()">Cancelar</button>
            <button class="ext-btn-danger" onclick="submitDel()">Eliminar cargo</button>
        </div>
    </div>
</div>



<!-- Global Toast para Notificaciones -->
<div class="ext-toast" id="extToast">
    <div class="t-icon"><i class="bi bi-check-lg"></i></div>
    <div>
        <div class="t-title" id="toastTitle">Éxito</div>
        <div class="t-msg" id="toastMsg">Operación completada.</div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const fmt = v => 'MX$' + parseFloat(v).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    // Tabs & Filter
    function switchTab(tabId, el) {
        document.querySelectorAll('.det-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.det-tab-content').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('tab-' + tabId).classList.add('active');
    }
    function filterTable() {
        const term = document.getElementById('searchInput').value.toLowerCase();
        const status = document.getElementById('statusFilter').value;
        document.querySelectorAll('.unit-row').forEach(row => {
            const u = row.getAttribute('data-unit');
            const s = row.getAttribute('data-status');
            row.style.display = (u.includes(term) && (status === 'all' || status === s)) ? '' : 'none';
        });
    }

    // Toast
    function showToast(title, msg, type) {
        const t = document.getElementById('extToast');
        const icon = t.querySelector('.t-icon i');
        document.getElementById('toastTitle').textContent = title;
        document.getElementById('toastMsg').textContent = msg;
        if (type === 'error') { t.classList.add('toast-error'); icon.className = 'bi bi-x-lg'; }
        else { t.classList.remove('toast-error'); icon.className = 'bi bi-check-lg'; }
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), 4000);
    }

    // Dropdown
    function toggleDropdown(e, id) {
        e.stopPropagation();
        document.querySelectorAll('.ext-dropdown.open').forEach(d => { if (d.id !== id) d.classList.remove('open'); });
        document.getElementById(id).classList.toggle('open');
    }
    document.addEventListener('click', () => document.querySelectorAll('.ext-dropdown.open').forEach(d => d.classList.remove('open')));

    // ═══ PAY MODAL ═══
    let payFp;
    function openPayModal(chargeId, unit, total, paid, balance, resident) {
        document.getElementById('pm-charge-id').value = chargeId;
        document.getElementById('pm-unit').textContent = unit;
        document.getElementById('pm-resident').textContent = resident || '';
        document.getElementById('pm-total').textContent = fmt(total);
        document.getElementById('pm-paid').textContent = fmt(paid);
        document.getElementById('pm-balance').textContent = fmt(balance);
        document.getElementById('pm-amount').value = balance;
        document.getElementById('pm-hint').textContent = 'Nuevo saldo después del pago: ' + fmt(0);
        document.getElementById('pm-file').value = '';
        document.getElementById('payModalOverlay').classList.add('open');
        if (!payFp) payFp = flatpickr('#pm-date', { locale: 'es', dateFormat: 'Y-m-d', altInput: true, altFormat: 'd \\d\\e F \\d\\e Y', defaultDate: 'today' });
        else payFp.setDate('today');
        // Update hint on amount change
        document.getElementById('pm-amount').oninput = function () {
            const newBal = Math.max(0, balance - parseFloat(this.value || 0));
            document.getElementById('pm-hint').textContent = 'Nuevo saldo después del pago: ' + fmt(newBal);
        };
    }
    function closePayModal() { document.getElementById('payModalOverlay').classList.remove('open'); }

    function submitPay() {
        const chargeId = document.getElementById('pm-charge-id').value;
        const amount = document.getElementById('pm-amount').value;
        const method = document.getElementById('pm-method').value;
        const date = document.getElementById('pm-date').value;
        if (!amount || parseFloat(amount) <= 0) { showToast('Error', 'El monto debe ser mayor a 0.', 'error'); return; }
        const fd = new FormData();
        fd.append('charge_id', chargeId);
        fd.append('amount', amount);
        fd.append('method', method);
        fd.append('date', date);
        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        const fileEl = document.getElementById('pm-file');
        if (fileEl.files[0]) fd.append('attachment', fileEl.files[0]);
        fetch('<?= base_url("admin/finanzas/extraordinarias/charge/pay") ?>', {
            method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: fd
        }).then(r => r.json()).then(d => {
            closePayModal();
            if (d.success) { showToast('Pago registrado', d.message, 'success'); setTimeout(() => location.reload(), 1500); }
            else showToast('Error', d.message || 'Error al registrar pago.', 'error');
        }).catch(() => { closePayModal(); showToast('Error', 'Problema de conexión.', 'error'); });
    }

    // ═══ EDIT MODAL ═══
    function openEditModal(chargeId, unit, currentAmount) {
        document.querySelectorAll('.ext-dropdown.open').forEach(d => d.classList.remove('open'));
        document.getElementById('em-charge-id').value = chargeId;
        document.getElementById('em-unit').textContent = unit;
        document.getElementById('em-current').textContent = fmt(currentAmount);
        document.getElementById('em-amount').value = currentAmount;
        document.getElementById('editModalOverlay').classList.add('open');
    }
    function closeEditModal() { document.getElementById('editModalOverlay').classList.remove('open'); }

    function submitEdit() {
        const id = document.getElementById('em-charge-id').value;
        const val = document.getElementById('em-amount').value;
        if (!val || parseFloat(val) < 0) { showToast('Error', 'Monto inválido.', 'error'); return; }
        fetch('<?= base_url("admin/finanzas/extraordinarias/charge/update") ?>', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ transaction_id: parseInt(id), amount: val })
        }).then(r => r.json()).then(d => {
            closeEditModal();
            if (d.success) { showToast('Cargo actualizado', d.message || 'Monto actualizado exitosamente.', 'success'); setTimeout(() => location.reload(), 1500); }
            else showToast('Error', d.message || 'Error al actualizar.', 'error');
        }).catch(() => { closeEditModal(); showToast('Error', 'Problema de conexión.', 'error'); });
    }

    // ═══ DELETE CHARGE MODAL ═══
    function openDelChargeModal(chargeId, unit) {
        document.querySelectorAll('.ext-dropdown.open').forEach(d => d.classList.remove('open'));
        document.getElementById('dm-charge-id').value = chargeId;
        document.getElementById('dm-unit').textContent = unit;
        document.getElementById('delModalOverlay').classList.add('open');
    }
    function closeDelModal() { document.getElementById('delModalOverlay').classList.remove('open'); }

    function submitDel() {
        const id = document.getElementById('dm-charge-id').value;
        fetch('<?= base_url("admin/finanzas/extraordinarias/charge/delete") ?>', {
            method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ transaction_id: parseInt(id) })
        }).then(r => r.json()).then(d => {
            closeDelModal();
            if (d.success) { showToast('Cargo eliminado', d.message || 'Cargo eliminado permanentemente.', 'success'); setTimeout(() => location.reload(), 1500); }
            else showToast('Error', d.message || 'Error al eliminar.', 'error');
        }).catch(() => { closeDelModal(); showToast('Error', 'Problema de conexión.', 'error'); });
    }

    // ═══ DELETE FEE (Danger Zone) ═══
    function openDelFeeModal(feeId) {
        Swal.fire({
            title: '<div style="color:#ef4444; font-size:1.2rem; display:flex; align-items:center; gap:0.5rem; text-align:left;"><i class="bi bi-exclamation-triangle"></i> ¿Eliminar Cuota Extraordinaria?</div>',
            html: `
            <div style="background:#e2e8f0; padding:1.25rem; border-radius:8px; display:flex; justify-content:space-between; margin-bottom:1rem;">
                <div style="text-align:left; width:50%;"><div style="font-size:0.75rem; color:#64748b; font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">NOMBRE DE LA CUOTA</div><div style="font-size:1.1rem; font-weight:700; color:#1e293b;"><?= esc(addslashes($fee['title'])) ?></div></div>
                <div style="text-align:left; width:50%;"><div style="font-size:0.75rem; color:#64748b; font-weight:600; text-transform:uppercase; margin-bottom:0.25rem;">TOTAL ESPERADO</div><div style="font-size:1.1rem; font-weight:700; color:#1e293b;">MX$<?= number_format($stats['expected'], 2) ?></div><div style="font-size:0.8rem; color:#64748b; margin-top:0.25rem;">Total Recaudado: MX$<?= number_format($stats['collected'], 2) ?> &bull; <?= $stats['units_total'] ?> Unidades</div></div>
            </div>
            <p style="font-size:0.9rem; color:#64748b; text-align:left; margin-bottom:1rem;">¿Está seguro de que desea eliminar esta cuota extraordinaria?</p>
            <div style="border:1px solid #fca5a5; background:#fef2f2; border-radius:8px; padding:1rem; display:flex; gap:0.75rem; text-align:left; color:#ef4444; font-size:0.85rem; line-height:1.4;"><i class="bi bi-exclamation-triangle" style="font-size:1.25rem; padding-top:2px;"></i><div>Esto eliminará permanentemente el registro de la cuota, todos los cargos a unidades, todos los pagos relacionados y todo el historial financiero.</div></div>`,
            showCancelButton: true, confirmButtonText: 'Eliminar', cancelButtonText: 'Cancelar', buttonsStyling: false,
            didOpen: () => {
                const cb = Swal.getConfirmButton(); const xb = Swal.getCancelButton();
                cb.style.cssText = 'padding:0.6rem 1.5rem; border-radius:6px; font-weight:600; font-size:0.9rem; background:#ef4444; color:#fff; border:none; cursor:pointer; margin-left:10px;';
                xb.style.cssText = 'padding:0.6rem 1.5rem; border-radius:6px; font-weight:600; font-size:0.9rem; background:#fff; color:#1e293b; border:1px solid #94a3b8; cursor:pointer;';
            },
            preConfirm: () => fetch('<?= base_url("admin/finanzas/extraordinarias/delete") ?>', {
                method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: JSON.stringify({ fee_id: feeId })
            }).then(r => r.json()).then(d => { if (!d.success) throw new Error(d.message); return d; }).catch(e => Swal.showValidationMessage(e))
        }).then(res => { if (res.isConfirmed) { showToast('Cuota eliminada', 'La cuota extraordinaria fue eliminada exitosamente.', 'success'); setTimeout(() => window.location.href = '<?= base_url("admin/finanzas/extraordinarias") ?>', 1500); } });
    }

    // ═══ EDIT FEE GLOBAL (Detalles Tab) ═══
    function openEditFeeGlobal() {
        Swal.fire({
            title: '<div style="font-size:1.1rem; text-align:left;">Editar Cuota Extraordinaria</div>', width: '600px',
            html: `<div style="text-align:left;">
            <div class="form-group"><label style="font-size:0.85rem; color:#1e293b; font-weight:600;">Título de la Cuota *</label><input type="text" id="swal-fee-title" value="<?= esc($fee['title'], 'attr') ?>" style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:0.6rem 0.8rem; font-size:0.9rem;"></div>
            <div class="form-group" style="margin-top:1rem;"><label style="font-size:0.85rem; color:#1e293b; font-weight:600;">Descripción</label><textarea id="swal-fee-desc" rows="3" style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:0.6rem 0.8rem; font-size:0.9rem; resize:vertical; max-height:120px;"><?= esc($fee['description']) ?></textarea></div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:1rem;">
                <div><label style="font-size:0.85rem; color:#1e293b; font-weight:600;">Monto por Unidad *</label><div style="position:relative;"><span style="position:absolute; left:0.8rem; top:0.6rem; color:#94a3b8;">$</span><input type="text" value="<?= esc($fee['amount'], 'attr') ?>" disabled style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:0.6rem 0.8rem 0.6rem 1.8rem; font-size:0.9rem; background:#f1f5f9; color:#94a3b8;"></div></div>
                <div><label style="font-size:0.85rem; color:#1e293b; font-weight:600;">Categoría</label><select id="swal-fee-cat" style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:0.6rem 0.8rem; font-size:0.9rem; background:white;"><option value="1">Mejora de Capital</option><option value="2">Reparación de Emergencia</option><option value="3">Honorarios Legales</option><option value="4">Seguro</option><option value="5">Proyecto Especial</option><option value="6">Otro</option></select></div>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:1rem;">
                <div><label style="font-size:0.85rem; color:#1e293b; font-weight:600;">Fecha Inicio *</label><div style="position:relative;"><i class="bi bi-calendar" style="position:absolute; left:0.8rem; top:0.6rem; color:#94a3b8;"></i><input type="text" id="swal-fee-start" value="<?= date('Y-m-d', strtotime($fee['created_at'])) ?>" style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:0.6rem 0.8rem 0.6rem 2.2rem; font-size:0.9rem;"></div></div>
                <div><label style="font-size:0.85rem; color:#1e293b; font-weight:600;">Fecha de Vencimiento</label><div style="position:relative;"><i class="bi bi-calendar" style="position:absolute; left:0.8rem; top:0.6rem; color:#94a3b8;"></i><input type="text" id="swal-fee-end" value="<?= $fee['due_date'] ? date('Y-m-d', strtotime($fee['due_date'])) : '' ?>" style="width:100%; border:1px solid #cbd5e1; border-radius:6px; padding:0.6rem 0.8rem 0.6rem 2.2rem; font-size:0.9rem;"></div></div>
            </div></div>`,
            showCancelButton: true, confirmButtonText: 'Guardar', cancelButtonText: 'Cancelar', buttonsStyling: false,
            didOpen: () => {
                document.getElementById('swal-fee-cat').value = "<?= esc($fee['category_id'] ?? '5', 'js') ?>";
                flatpickr("#swal-fee-start", { locale: "es", dateFormat: "Y-m-d", altInput: true, altFormat: "d \\d\\e F \\d\\e Y" });
                flatpickr("#swal-fee-end", { locale: "es", dateFormat: "Y-m-d", altInput: true, altFormat: "d \\d\\e F \\d\\e Y" });
                const cb = Swal.getConfirmButton(); const xb = Swal.getCancelButton();
                cb.style.cssText = 'padding:0.6rem 1.5rem; border-radius:6px; font-weight:600; font-size:0.9rem; background:#3F67AC; color:#fff; border:none; cursor:pointer; margin-left:10px;';
                xb.style.cssText = 'padding:0.6rem 1.5rem; border-radius:6px; font-weight:600; font-size:0.9rem; background:#fff; color:#1e293b; border:1px solid #cbd5e1; cursor:pointer;';
            },
            preConfirm: () => {
                const t = document.getElementById('swal-fee-title').value, d = document.getElementById('swal-fee-desc').value;
                const c = document.getElementById('swal-fee-cat').value, s = document.getElementById('swal-fee-start').value, e = document.getElementById('swal-fee-end').value;
                if (!t || !s) { Swal.showValidationMessage('Título y Fecha de Inicio son obligatorios'); return false; }
                return fetch('<?= base_url("admin/finanzas/extraordinarias/update") ?>', { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: JSON.stringify({ fee_id: <?= $fee['id'] ?>, title: t, description: d, category_id: c, created_at: s, due_date: e }) }).then(r => r.json()).then(r => { if (!r.success) throw new Error(r.message); return r; }).catch(e => Swal.showValidationMessage(e));
            }
        }).then(res => { if (res.isConfirmed) { showToast('Guardado', 'Los detalles han sido actualizados.', 'success'); setTimeout(() => location.reload(), 1500); } });
    }

</script>
<?= $this->endSection() ?>