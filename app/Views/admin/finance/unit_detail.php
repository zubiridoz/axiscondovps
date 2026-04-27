<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>

<!-- Flatpickr for Premium Calendar -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    :root {
        --ud-bg: #f8fafc;
        --ud-card: #fff;
        --ud-primary: #232d3f;
        --ud-text: #1e293b;
        --ud-muted: #64748b;
        --ud-border: #e2e8f0;
        --ud-success: #10b981;
        --ud-danger: #ef4444;
        --ud-warning: #f59e0b;
        --ud-info: #3b82f6;
        --ud-br: 8px;
        --ud-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
    }



    .ud-nav-group {
        display: flex;
        align-items: center;
        gap: .5rem;
    }

    .ud-nav-btn {
        background: #1C2434;
        border: 1px solid #1C2434;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background .2s;
        text-decoration: none;
        font-size: .85rem;
    }

    .ud-nav-btn:hover {
        background: #050505ff;
    }

    .ud-nav-btn.disabled {
        opacity: .35;
        pointer-events: none;
    }

    .ud-unit-select {
        background: #1C2434;
        border: 1px solid rgba(255, 255, 255, .2);
        color: white;
        height: 30px;
        border-radius: 5px;
        padding: 0 .6rem;
        font-size: .82rem;
        cursor: pointer;
    }

    .ud-unit-select option {
        background: #1e293b;
        color: white;
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



    /* ── KPI Cards ── */
    .ud-kpis {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width: 900px) {
        .ud-kpis {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .ud-kpi-card {
        background: var(--ud-card);
        border: 1px solid var(--ud-border);
        border-radius: var(--ud-br);
        padding: 1.25rem 1.5rem;
        box-shadow: var(--ud-shadow);
    }

    .ud-kpi-label {
        font-size: .75rem;
        color: var(--ud-muted);
        margin-bottom: .4rem;
        font-weight: 500;
    }

    .ud-kpi-value {
        font-size: 1.6rem;
        font-weight: 700;
        margin-bottom: .3rem;
        line-height: 1;
    }

    .ud-kpi-sub {
        font-size: .75rem;
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    .kpi-success .ud-kpi-value {
        color: var(--ud-success);
    }

    .kpi-danger .ud-kpi-value {
        color: var(--ud-danger);
    }

    .kpi-info .ud-kpi-value {
        color: var(--ud-info);
    }

    .kpi-neutral .ud-kpi-value {
        color: var(--ud-text);
    }

    .kpi-date .ud-kpi-value {
        font-size: 1.25rem;
        color: var(--ud-text);
    }

    /* ── Tabs ── */
    .ud-tabs-panel {
        background: var(--ud-card);
        border: 1px solid var(--ud-border);
        border-radius: var(--ud-br);
        box-shadow: var(--ud-shadow);
        margin-bottom: 2rem;
    }

    .ud-tabs-header {
        display: flex;
        align-items: center;
        padding: 0 1.5rem;
        border-bottom: 1px solid var(--ud-border);
        gap: 0;
        flex-wrap: wrap;
    }

    .ud-tab {
        padding: .9rem 1.25rem;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
        color: var(--ud-muted);
        border-bottom: 2px solid transparent;
        transition: color .2s, border-color .2s;
        white-space: nowrap;
        margin-bottom: -1px;
        background: none;
        border-top: none;
        border-left: none;
        border-right: none;
    }

    .ud-tab:hover {
        color: var(--ud-text);
    }

    .ud-tab.active {
        color: var(--ud-info);
        border-bottom-color: var(--ud-info);
    }

    .ud-tabs-body {
        padding: 1.5rem;
    }

    .ud-tab-pane {
        display: none;
    }

    .ud-tab-pane.active {
        display: block;
    }

    /* ── Tables inside tabs ── */
    .ud-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .85rem;
    }

    .ud-table th {
        color: var(--ud-muted);
        font-weight: 500;
        padding: .65rem .75rem;
        text-align: left;
        border-bottom: 1px solid var(--ud-border);
        white-space: nowrap;
    }

    .ud-table td {
        padding: .75rem .75rem;
        border-bottom: 1px solid #f8fafc;
        color: var(--ud-text);
        vertical-align: middle;
    }

    .ud-table tbody tr:hover {
        background: #f8fafc;
    }

    .ud-table tfoot td {
        font-weight: 700;
        border-top: 2px solid var(--ud-border);
    }

    .badge-charge {
        background: #fef2f2;
        color: #b91c1c;
        padding: .2rem .55rem;
        border-radius: 10px;
        font-size: .72rem;
        font-weight: 600;
    }

    .badge-credit {
        background: #d1fae5;
        color: #065f46;
        padding: .2rem .55rem;
        border-radius: 10px;
        font-size: .72rem;
        font-weight: 600;
    }

    .badge-initial {
        background: #f0f9ff;
        color: #0369a1;
        padding: .2rem .55rem;
        border-radius: 10px;
        font-size: .72rem;
        font-weight: 500;
    }

    .badge-pending {
        background: #fef9c3;
        color: #854d0e;
        padding: .2rem .55rem;
        border-radius: 10px;
        font-size: .72rem;
        font-weight: 600;
    }

    .badge-paid {
        background: #d1fae5;
        color: #065f46;
        padding: .2rem .55rem;
        border-radius: 10px;
        font-size: .72rem;
        font-weight: 600;
    }

    .badge-overdue {
        background: #fee2e2;
        color: #991b1b;
        padding: .2rem .55rem;
        border-radius: 10px;
        font-size: .72rem;
        font-weight: 600;
    }

    .voucher-row:hover td {
        background: #f0f7ff;
        transition: background .15s;
    }

    /* Estado de Cuenta – initial balance row */
    .initial-row td {
        background: #f0f9ff;
    }

    .edit-balance-btn {
        background: none;
        border: none;
        color: var(--ud-muted);
        cursor: pointer;
        padding: .1rem .3rem;
        border-radius: 4px;
        transition: color .2s;
        font-size: .9rem;
    }

    .edit-balance-btn:hover {
        color: var(--ud-info);
        background: #eff6ff;
    }

    /* Pending table tag */
    .cat-tag {
        display: inline-block;
        background: #1e293b;
        color: white;
        padding: .2rem .65rem;
        border-radius: 20px;
        font-size: .72rem;
        font-weight: 500;
    }

    /* Balance colors */
    .balance-pos {
        color: var(--ud-danger);
        font-weight: 600;
    }

    .balance-zero {
        color: var(--ud-success);
        font-weight: 600;
    }

    /* Section header */
    .section-label {
        font-size: .85rem;
        font-weight: 600;
        color: var(--ud-text);
        margin-bottom: .25rem;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .section-sublabel {
        font-size: .78rem;
        color: var(--ud-muted);
        margin-bottom: 1rem;
    }

    /* Modal */
    .ud-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity .2s;
    }

    .ud-modal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    /* Force SweetAlert2 above modals (z-index: 9999) */
    .swal2-container {
        z-index: 10100 !important;
    }

    .ud-modal {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        width: 600px;
        max-width: 95vw;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .15);
        transform: translateY(-10px);
        transition: transform .2s;
        box-sizing: border-box;
    }

    .ud-modal * {
        box-sizing: border-box;
    }

    .ud-modal-overlay.open .ud-modal {
        transform: translateY(0);
    }

    .ud-modal h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin: 0 0 .25rem;
    }

    .ud-modal p {
        font-size: .8rem;
        color: var(--ud-muted);
        margin: 0 0 1rem;
    }

    .ud-modal-alert {
        background: #fefce8;
        border: 1px solid #fde68a;
        border-radius: 6px;
        padding: .75rem 1rem;
        font-size: .8rem;
        color: #78350f;
        margin-bottom: 1rem;
        display: flex;
        gap: .5rem;
        align-items: flex-start;
    }

    .modal-input-group {
        display: flex;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        height: 40px;
        margin-bottom: 1.25rem;
        background: white;
        transition: border-color .2s, box-shadow .2s;
        position: relative;
    }

    .modal-input-group:focus-within {
        border-color: #64748b;
        box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
    }

    .modal-input-prefix {
        padding: 0 .75rem 0 1rem;
        color: var(--ud-muted);
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-input {
        border: none !important;
        outline: none !important;
        flex: 1;
        padding: 0 .75rem;
        font-size: .95rem;
        background: transparent !important;
        height: 100%;
        color: #1e293b;
        min-width: 0;
    }

    /* Hide number spinners */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }

    /* Flatpickr Premium Overrides */
    .flatpickr-input[readonly] {
        background: transparent;
        cursor: pointer;
    }

    input#editTransDate {
        display: none !important;
    }

    .flatpickr-calendar {
        z-index: 10001 !important;
    }

    .modal-btns {
        display: flex;
        gap: .75rem;
        justify-content: flex-end;
    }

    .btn-cancel {
        background: white;
        border: 1px solid var(--ud-border);
        color: var(--ud-text);
        padding: .5rem 1.2rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: .85rem;
    }

    .btn-save {
        background: #334155;
        border: none;
        color: white;
        padding: .65rem 1.8rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: .88rem;
        font-weight: 500;
        transition: all .2s;
    }

    .btn-save:hover {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-trash {
        background: none;
        border: none;
        color: var(--ud-danger);
        cursor: pointer;
        padding: .1rem .3rem;
        border-radius: 4px;
        transition: color .2s;
        font-size: .9rem;
    }

    .btn-trash:hover {
        background: #fee2e2;
    }

    /* Modal Grid (2 columns for Amount/Date) */
    .modal-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    @media (max-width: 480px) {
        .modal-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Form stylings for modal */
    .ud-modal select.modal-input,
    .ud-modal textarea.modal-input {
        width: 100%;
        border: 1px solid var(--ud-border);
        border-radius: 6px;
        padding: .5rem .75rem;
        font-size: .9rem;
    }

    .ud-modal textarea.modal-input {
        height: 100px;
        resize: vertical;
    }

    /* Action Icons alignment */
    .actions-cell {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 1.5rem;
    }

    .actions-cell i,
    .actions-cell button {
        font-size: 1.1rem;
    }

    /* Delete Modal specific styles */
    .delete-warning-box {
        background: #fff5f5;
        border: 1px solid #feb2b2;
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .delete-details-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .delete-details-row {
        display: flex;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .delete-details-label {
        font-weight: 700;
        width: 100px;
        color: var(--ud-text);
    }

    .delete-details-value {
        color: var(--ud-text);
        flex: 1;
    }

    .text-danger-soft {
        color: #e53e3e;
        font-size: 0.82rem;
        margin-bottom: 1.5rem;
    }

    .empty-state {
        text-align: center;
        padding: 2.5rem 1rem;
        color: var(--ud-muted);
        font-size: .9rem;
    }

    .empty-state i {
        font-size: 2rem;
        display: block;
        margin-bottom: .75rem;
        opacity: .5;
    }

    .btn-new-reg {
        background: #10b981;
        border: none;
        color: white;
        padding: .45rem 1rem;
        border-radius: 6px;
        font-size: .8rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        float: right;
        margin-bottom: .75rem;
    }

    .btn-new-reg:hover {
        background: #059669;
    }
</style>

<?php
$baseUrl = base_url('admin/finanzas/pagos-por-unidad/');
$prevUrl = $prevId ? ($baseUrl . $prevId) : null;
$nextUrl = $nextId ? ($baseUrl . $nextId) : null;
$formatMXN = fn($v) => 'MX$' . number_format((float) $v, 2);
?>

<div class="ppu-container">





    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <a class="ud-back-btn" href="<?= base_url('admin/finanzas/pagos-por-unidad') ?>" title="Regresar">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="cc-hero-title">Unidad <?= esc($unit['unit_number']) ?></h2>
            <div class="cc-hero-divider"></div>
            <div class="cc-hero-breadcrumb">

                <i class="bi bi-chevron-right"></i>
                Resumen Financiero
            </div>
        </div>
        <div class="ud-nav-group">
            <a class="ud-nav-btn <?= $prevUrl ? '' : 'disabled' ?>" href="<?= $prevUrl ?? '#' ?>" title="Anterior">
                <i class="bi bi-chevron-left"></i>
            </a>
            <select class="ud-unit-select" id="unitNav" onchange="window.location.href=this.value">
                <?php foreach ($allUnits as $u): ?>
                    <option value="<?= $baseUrl . ($u['hash_id'] ?? $u['id']) ?>" <?= $u['id'] == $unit['id'] ? 'selected' : '' ?>>
                        <?= esc($u['unit_number']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <a class="ud-nav-btn <?= $nextUrl ? '' : 'disabled' ?>" href="<?= $nextUrl ?? '#' ?>" title="Siguiente">
                <i class="bi bi-chevron-right"></i>
            </a>
        </div>
    </div>





    <!-- KPI Cards -->
    <div class="ud-kpis">
        <!-- KPI 1 Total Pagado -->
        <div class="ud-kpi-card kpi-success">
            <div class="ud-kpi-label">Total Pagado</div>
            <div class="ud-kpi-value"><?= $formatMXN($totalPaid) ?></div>
            <div class="ud-kpi-sub" style="color: var(--ud-success);">
                <i class="bi bi-arrow-up-circle-fill"></i>
                <?= $numPagos ?> pago<?= $numPagos != 1 ? 's' : '' ?> realizado<?= $numPagos != 1 ? 's' : '' ?>
            </div>
        </div>
        <!-- KPI 2 Saldo Pendiente -->
        <?php
        $kpiClass = 'kpi-success';
        if (isset($saldoVencido) && $saldoVencido > 0.01) {
            $kpiClass = 'kpi-danger';
        } elseif ($saldoPendiente > 0.01) {
            $kpiClass = 'kpi-info';
        }

        $subColor = 'var(--ud-success)';
        if (isset($saldoVencido) && $saldoVencido > 0.01) {
            $subColor = 'var(--ud-danger)';
        } elseif ($saldoPendiente > 0.01) {
            $subColor = 'var(--ud-info)';
        }
        ?>
        <div class="ud-kpi-card <?= $kpiClass ?>">
            <div class="ud-kpi-label"><?= $saldoPendiente < -0.01 ? 'Saldo a Favor' : 'Saldo Pendiente' ?></div>
            <?php if ($saldoPendiente < -0.01): ?>
                <div class="ud-kpi-value" style="color:var(--ud-success);">MX$<?= number_format(abs($saldoPendiente), 2) ?>
                </div>
            <?php else: ?>
                <div class="ud-kpi-value"><?= $formatMXN($saldoPendiente) ?></div>
            <?php endif; ?>

            <div class="ud-kpi-sub" style="color: <?= $subColor ?>;">
                <?php if (isset($saldoVencido) && $saldoVencido > 0.01): ?>
                    <i class="bi bi-exclamation-circle-fill"></i> Pago requerido (Morosidad)
                <?php elseif ($saldoPendiente > 0.01): ?>
                    <i class="bi bi-info-circle-fill"></i> Al corriente (Saldo pendiente)
                <?php elseif ($saldoPendiente < -0.01): ?>
                    <i class="bi bi-star-fill"></i> Saldo a favor
                <?php else: ?>
                    <i class="bi bi-check-circle-fill"></i> Sin adeudos
                <?php endif; ?>
            </div>
        </div>
        <!-- KPI 3 Cuota Mensual -->
        <div class="ud-kpi-card kpi-neutral">
            <div class="ud-kpi-label">Cuota Mensual</div>
            <div class="ud-kpi-value"><?= $formatMXN($cuotaMensual) ?></div>
            <div class="ud-kpi-sub" style="color: var(--ud-muted);">
                <i class="bi bi-calendar-month"></i> Cargo mensual regular
            </div>
        </div>
        <!-- KPI 4 Próximo Pago Vence -->
        <div class="ud-kpi-card kpi-date">
            <div class="ud-kpi-label">Próximo Pago Vence</div>
            <div class="ud-kpi-value"><?= $nextDueDate ?></div>
            <div class="ud-kpi-sub" style="color: var(--ud-muted);">
                <i class="bi bi-clock"></i> <?= $daysLeft ?> días
            </div>
        </div>
    </div>

    <!-- Tabs Panel -->
    <div class="ud-tabs-panel">
        <div class="ud-tabs-header">
            <button class="ud-tab active" data-tab="resumen">Resumen</button>
            <button class="ud-tab" data-tab="estado">Estado de Cuenta</button>
            <button class="ud-tab" data-tab="historial">Historial de Pagos</button>
            <?php $__pendingVCount = count(array_filter($vouchers, fn($v) => ($v['status'] ?? '') === 'pending')); ?>
            <button class="ud-tab" data-tab="comprobantes">Comprobantes de Pago<?php if ($__pendingVCount > 0): ?> <span class="badge bg-warning rounded-pill" style="font-size:.6rem; font-weight:600; vertical-align:middle;"><?= $__pendingVCount ?></span><?php endif; ?></button>
        </div>

        <div class="ud-tabs-body">

            <!-- ── TAB 1: Resumen – Cargos Pendientes ── -->
            <div class="ud-tab-pane active" id="tab-resumen">
                <div class="section-label"><i class="bi bi-info-circle" style="color: var(--ud-warning);"></i> Cargos
                    Pendientes</div>
                <div class="section-sublabel">Cargos que contribuyen al saldo pendiente</div>

                <?php if (empty($pendingRows)): ?>
                    <div class="empty-state">
                        <i class="bi bi-check2-circle"></i>
                        Esta unidad no tiene cargos pendientes.
                    </div>
                <?php else: ?>
                    <table class="ud-table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Categoría</th>
                                <th style="text-align:right;">Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingRows as $row): ?>
                                <tr>
                                    <td style="color: var(--ud-info);">
                                        <?= date('j M Y', strtotime($row['due_date'] ?? $row['created_at'])) ?>
                                    </td>
                                    <td><?= esc($row['description']) ?></td>
                                    <td>
                                        <?php if ($row['category_name']): ?>
                                            <span class="cat-tag"><?= esc(strtoupper($row['category_name'])) ?></span>
                                        <?php else: ?>
                                            <span style="color:var(--ud-muted);">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align:right;" class="balance-pos">
                                        MX$<?= number_format((float) $row['amount'], 2) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3">Total Pendiente</td>
                                <td style="text-align:right;" class="balance-pos"><?= $formatMXN($saldoPendiente) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php endif; ?>
            </div>

            <!-- ── TAB 2: Estado de Cuenta ── -->
            <div class="ud-tab-pane" id="tab-estado">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: .5rem;">
                    <div style="font-size:.8rem; color:var(--ud-muted);">Selecciona transacciones para acciones masivas
                    </div>
                    <a href="<?= base_url('admin/finanzas/nuevo-registro') ?>" class="btn-new-reg">
                        <i class="bi bi-plus"></i> Nuevo Registro
                    </a>
                </div>

                <table class="ud-table">
                    <thead>
                        <tr>
                            <th style="width:36px"><input type="checkbox" id="selectAllEs"></th>
                            <th>Fecha <i class="bi bi-arrow-up"></i></th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th style="text-align:right;">Monto</th>
                            <th style="text-align:right;">Saldo</th>
                            <th style="text-align:right;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Saldo Inicial -->
                        <tr class="initial-row">
                            <td></td>
                            <td style="color: var(--ud-muted);">—</td>
                            <td><strong>Saldo Inicial</strong></td>
                            <td><span class="badge-initial">Inicial</span></td>
                            <td style="text-align:right;">
                                <?php if ($initialBalance < 0): ?>
                                    <span
                                        style="color:#059669; font-weight:600;">MX$<?= number_format(abs($initialBalance), 2) ?>
                                        (A favor)</span>
                                <?php else: ?>
                                    MX$<?= number_format($initialBalance, 2) ?>
                                <?php endif; ?>
                            </td>
                            <td style="text-align:right;">
                                <?php if ($initialBalance < 0): ?>
                                    <span
                                        style="color:#059669; font-weight:600;">MX$<?= number_format(abs($initialBalance), 2) ?>
                                        (A favor)</span>
                                <?php else: ?>
                                    MX$<?= number_format($initialBalance, 2) ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="actions-cell">
                                    <button class="edit-balance-btn" title="Editar saldo inicial" id="openModalBtn"
                                        style="padding:0;">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php foreach ($statementRows as $row): ?>
                            <tr>
                                <td><input type="checkbox"></td>
                                <td style="color: var(--ud-info);">
                                    <?= date('j M Y', strtotime($row['due_date'] ?? $row['created_at'])) ?>
                                </td>
                                <td><?= esc($row['description']) ?></td>
                                <td>
                                    <?php if ($row['type'] === 'charge'): ?>
                                        <span class="badge-charge">Cargo</span>
                                    <?php else: ?>
                                        <span class="badge-credit">Pago</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align:right;"
                                    class="<?= $row['type'] === 'charge' ? 'balance-pos' : 'balance-zero' ?>">
                                    MX$<?= number_format((float) $row['amount'], 2) ?>
                                </td>
                                <td style="text-align:right;"
                                    class="<?= (float) $row['running_balance'] > 0 ? 'balance-pos' : 'balance-zero' ?>">
                                    <?php if ($row['running_balance'] < 0): ?>
                                        <span
                                            style="color:#059669; font-weight:600;">MX$<?= number_format(abs($row['running_balance']), 2) ?>
                                            <small>(A favor)</small></span>
                                    <?php else: ?>
                                        MX$<?= number_format((float) $row['running_balance'], 2) ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions-cell">
                                        <?php if ($row['type'] === 'credit'): ?>
                                            <a href="<?= base_url('admin/finanzas/recibo-pago/' . $row['id']) ?>"
                                                target="_blank" title="Descargar recibo">
                                                <i class="bi bi-download" style="cursor:pointer; color:var(--ud-info);"></i>
                                            </a>
                                        <?php endif; ?>
                                        <i class="bi bi-pencil edit-trans-btn"
                                            style="cursor:pointer; color:var(--ud-muted);" data-id="<?= $row['id'] ?>"
                                            data-amount="<?= number_format((float) $row['amount'], 2, '.', '') ?>"
                                            data-date="<?= !empty($row['due_date']) ? $row['due_date'] : date('Y-m-d', strtotime($row['created_at'])) ?>"
                                            data-desc="<?= esc($row['description']) ?>"
                                            data-cat="<?= $row['category_id'] ?>"
                                            data-type="<?= $row['type'] === 'charge' ? 'Cargo' : 'Pago' ?>"
                                            data-catname="<?= !empty($row['category_id']) ? esc(array_values(array_filter($categories, fn($c) => $c['id'] == $row['category_id']))[0]['name'] ?? 'General') : 'General' ?>"
                                            title="Editar transacción"></i>
                                        <i class="bi bi-trash delete-trans-btn"
                                            style="cursor:pointer; color:var(--ud-danger);" data-id="<?= $row['id'] ?>"
                                            data-amount="<?= number_format((float) $row['amount'], 2, '.', '') ?>"
                                            data-date="<?= !empty($row['due_date']) ? date('M jS, Y', strtotime($row['due_date'])) : date('M jS, Y', strtotime($row['created_at'])) ?>"
                                            data-desc="<?= esc($row['description']) ?>"
                                            data-type="<?= $row['type'] === 'charge' ? 'Cargo' : 'Pago' ?>"
                                            data-catname="<?= !empty($row['category_id']) ? esc(array_values(array_filter($categories, fn($c) => $c['id'] == $row['category_id']))[0]['name'] ?? 'General') : 'General' ?>"
                                            title="Eliminar transacción"></i>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- ── TAB 3: Historial de Pagos ── -->
            <div class="ud-tab-pane" id="tab-historial">
                <?php if (empty($paymentHistory)): ?>
                    <div class="empty-state">
                        <i class="bi bi-clock-history"></i>
                        No hay pagos registrados para esta unidad.
                    </div>
                <?php else: ?>
                    <table class="ud-table">
                        <thead>
                            <tr>
                                <th>Fecha <i class="bi bi-arrow-down" style="font-size:.7rem;"></i></th>
                                <th>N° Recibo</th>
                                <th>Descripción</th>
                                <th>Monto</th>
                                <th>Estado</th>
                                <th>Método de Pago</th>
                                <th style="text-align:right;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paymentHistory as $ph): ?>
                                <tr>
                                    <td style="color: var(--ud-info);"><?= date('j M Y', strtotime($ph['created_at'])) ?></td>
                                    <td style="font-family: 'SF Mono', ui-monospace, monospace; font-size: 0.85rem; font-weight: 600; color: #64748B;">#REC-<?= strtoupper(substr(md5($ph['id'] . $ph['created_at']), 0, 8)) ?></td>
                                    <td><strong><?= strtoupper(esc($ph['description'])) ?></strong></td>
                                    <td>MX$<?= number_format((float) $ph['amount'], 2) ?></td>
                                    <td><span class="badge-paid"><i class="bi bi-check-circle-fill"></i> Completado</span></td>
                                    <td><?= esc($ph['payment_method'] ?? 'Transferencia Bancaria') ?></td>
                                    <td>
                                        <div class="actions-cell">
                                            <i class="bi bi-eye view-payment-btn" style="cursor:pointer; color:var(--ud-muted);"
                                                title="Ver detalle" data-unit="<?= esc($unit['unit_number']) ?>"
                                                data-amount="MX$<?= number_format((float) $ph['amount'], 2) ?>"
                                                data-category="<?= esc($ph['category_name'] ?? 'Cuota de Mantenimiento') ?>"
                                                data-method="<?= esc($ph['payment_method'] ?? 'Transferencia Bancaria') ?>"
                                                data-date="<?= (new IntlDateFormatter('es_MX', IntlDateFormatter::LONG, IntlDateFormatter::NONE, null, null, "d 'de' MMMM 'de' yyyy"))->format(strtotime($ph['due_date'] ?? $ph['created_at'])) ?>"
                                                data-desc="<?= strtoupper(esc($ph['description'])) ?>"
                                                data-attachment="<?= esc($ph['attachment'] ?? '') ?>"></i>
                                            <a href="<?= base_url('admin/finanzas/recibo-pago/' . $ph['id']) ?>" target="_blank"
                                                title="Descargar recibo">
                                                <i class="bi bi-download" style="cursor:pointer; color:var(--ud-muted);"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>

            <!-- ── TAB 4: Comprobantes de Pago ── -->
            <div class="ud-tab-pane" id="tab-comprobantes">
                <?php if (empty($vouchers)): ?>
                    <div class="empty-state">
                        <i class="bi bi-file-earmark-image"></i>
                        No hay comprobantes de pago disponibles.<br>
                        <small style="font-size:.78rem; margin-top:.4rem; display:block;">Los comprobantes subidos por el
                            residente desde la app aparecerán aquí.</small>
                    </div>
                <?php else: ?>
                    <table class="ud-table">
                        <thead>
                            <tr>
                                <th>Comprobante</th>
                                <th>Monto</th>
                                <th>Método</th>
                                <th>Subido el</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $cnt = 1;
                            foreach ($vouchers as $v):
                                $statusClass = match ($v['status']) {
                                    'approved' => 'badge-paid',
                                    'rejected' => 'badge-overdue',
                                    default => 'badge-pending',
                                };
                                $statusLabel = match ($v['status']) {
                                    'approved' => '<i class="bi bi-check-circle-fill"></i> Aprobado',
                                    'rejected' => '<i class="bi bi-x-circle-fill"></i> Rechazado',
                                    default => '<i class="bi bi-hourglass-split"></i> Pendiente',
                                };
                                $methodLabel = match ($v['payment_method'] ?? 'transfer') {
                                    'cash' => 'Efectivo',
                                    'transfer' => 'Transferencia',
                                    'check' => 'Cheque',
                                    'stripe' => 'Stripe',
                                    default => 'Transferencia',
                                };
                                $proofUrl = $v['proof_url'] ?? '';
                                $isPdf = str_ends_with(strtolower($proofUrl), '.pdf');
                                ?>
                                <tr class="voucher-row" style="cursor:pointer;" data-id="<?= $v['id'] ?>"
                                    data-status="<?= esc($v['status']) ?>"
                                    data-amount="<?= number_format((float) ($v['amount'] ?? 0), 2, '.', '') ?>"
                                    data-method="<?= esc($v['payment_method'] ?? 'transfer') ?>"
                                    data-proof="<?= esc($proofUrl) ?>" data-notes="<?= esc($v['notes'] ?? '') ?>"
                                    data-date="<?= date('Y-m-d', strtotime($v['created_at'])) ?>">
                                    <td>
                                        <div style="display:flex; align-items:center; gap:.75rem;">
                                            <div
                                                style="width:38px; height:38px; background:#f1f5f9; border-radius:6px; display:flex; align-items:center; justify-content:center;">
                                                <?php if ($isPdf): ?>
                                                    <i class="bi bi-file-earmark-pdf" style="color:#ef4444; font-size:1.1rem;"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-file-earmark-image"
                                                        style="color:var(--ud-info); font-size:1.1rem;"></i>
                                                <?php endif; ?>
                                            </div>
                                            Comprobante #<?= $cnt++ ?>
                                        </div>
                                    </td>
                                    <td style="font-weight:600;">MX$<?= number_format((float) ($v['amount'] ?? 0), 2) ?></td>
                                    <td><?= $methodLabel ?></td>
                                    <td style="color: var(--ud-info);"><?= date('j M Y', strtotime($v['created_at'])) ?></td>
                                    <td><span class="<?= $statusClass ?>"><?= $statusLabel ?></span></td>
                                    <td>
                                        <div class="actions-cell" style="justify-content:center;">
                                            <i class="bi bi-eye" style="color:var(--ud-info); font-size:1rem;"
                                                title="Ver comprobante"></i>
                                            <?php if ($v['status'] === 'pending'): ?>
                                                <i class="bi bi-trash delete-voucher-btn"
                                                    style="color:var(--ud-danger); cursor:pointer;" title="Eliminar"
                                                    data-id="<?= $v['id'] ?>" onclick="event.stopPropagation();"></i>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>


        </div><!-- /ud-tabs-body -->
    </div><!-- /ud-tabs-panel -->

</div><!-- /ppu-container -->

<!-- Modal: Editar Saldo Inicial -->
<div class="ud-modal-overlay" id="balanceModal">
    <div class="ud-modal">
        <h3>Editar Saldo Inicial</h3>
        <p>Establecer el saldo inicial para la unidad <?= esc($unit['unit_number']) ?></p>
        <div class="ud-modal-alert">
            <i class="bi bi-info-circle-fill" style="flex-shrink:0; margin-top:.1rem;"></i>
            <div>Un valor positivo significa que la unidad <strong>debe</strong> dinero.<br>
                Un valor negativo significa que la unidad tiene <strong>saldo a favor</strong>.</div>
        </div>
        <label style="font-size:.82rem; color:var(--ud-muted); margin-bottom:.4rem; display:block;">Monto</label>
        <div class="modal-input-group">
            <div class="modal-input-prefix"><i class="bi bi-currency-dollar"></i></div>
            <input type="number" id="initialBalanceInput" class="modal-input" step="0.01"
                value="<?= number_format($initialBalance, 2, '.', '') ?>">
        </div>
        <div class="modal-btns">
            <button class="btn-cancel" id="closeModalBtn">Cancelar</button>
            <button class="btn-save" id="saveBalanceBtn">Guardar</button>
        </div>
    </div>
</div>

<!-- Modal: Editar Transacción -->
<div class="ud-modal-overlay" id="editTransModal">
    <div class="ud-modal">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
            <h3>Editar Transacción</h3>
            <button type="button" class="btn-close"
                style="background:none; border:none; border-radius: 50%; width: 30px; height: 30px; display:flex; align-items:center; justify-content:center; color: var(--ud-muted); cursor:pointer;"
                onclick="closeEditModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editTransForm">
            <input type="hidden" id="editTransId">

            <div style="margin-bottom: 1.25rem; position:relative;">
                <label
                    style="font-size:.82rem; color:var(--ud-muted); margin-bottom:.4rem; display:block; font-weight: 400;">Categoría</label>
                <input type="hidden" id="editTransCategory" value="">
                <div class="cat-select" id="catSelectTrigger" onclick="toggleCatDropdown()">
                    <span class="cat-select-icon" id="catSelectedIcon"><i class="bi bi-tag"></i></span>
                    <span class="cat-select-label" id="catSelectedLabel">Seleccionar categoría</span>
                    <i class="bi bi-chevron-down cat-select-chevron"></i>
                </div>
                <div class="cat-dropdown" id="catDropdown">
                    <?php
                    $catIcons = [
                        'Cuota de Mantenimiento' => 'bi-currency-dollar',
                        'Cargo por Mora' => 'bi-hourglass-split',
                        'Cargo de Reserva de Amenidad' => 'bi-calendar2-event',
                        'Multa de Amenidad' => 'bi-exclamation-triangle',
                        'Multa de Estacionamiento' => 'bi-p-square',
                        'Multa de Mascota' => 'bi-emoji-heart-eyes',
                        'Multa por Infracción' => 'bi-shield-exclamation',
                        'Otro Ingreso' => 'bi-file-earmark-text',
                    ];
                    // Explicit order matching the reference
                    $catOrder = [
                        'Cuota de Mantenimiento',
                        'Cargo por Mora',
                        'Cargo de Reserva de Amenidad',
                        'Multa de Amenidad',
                        'Multa de Estacionamiento',
                        'Multa de Mascota',
                        'Multa por Infracción',
                        'Otro Ingreso',
                    ];
                    $incomeCats = array_filter($categories, fn($c) => ($c['type'] ?? '') === 'income');
                    foreach ($catOrder as $catName):
                        $cat = current(array_filter($incomeCats, fn($c) => $c['name'] === $catName));
                        if (!$cat)
                            continue;
                        $icon = $catIcons[$cat['name']] ?? 'bi-tag';
                        ?>
                        <div class="cat-option" data-value="<?= $cat['id'] ?>" data-icon="<?= $icon ?>"
                            data-label="<?= esc($cat['name']) ?>" onclick="selectCatOption(this)">
                            <i class="bi <?= $icon ?> cat-option-icon"></i>
                            <span class="cat-option-label"><?= esc($cat['name']) ?></span>
                            <i class="bi bi-check2 cat-option-check"></i>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="modal-grid">
                <div style="margin-bottom: 1.25rem;">
                    <label
                        style="font-size:.82rem; color:var(--ud-muted); margin-bottom:.4rem; display:block; font-weight: 400;">Monto</label>
                    <div class="modal-input-group">
                        <div class="modal-input-prefix"><i class="bi bi-currency-dollar"></i></div>
                        <input type="number" id="editTransAmount" class="modal-input" step="0.01">
                    </div>
                </div>
                <div style="margin-bottom: 1.25rem;">
                    <label
                        style="font-size:.82rem; color:var(--ud-muted); margin-bottom:.4rem; display:block; font-weight: 400;">Fecha
                        de Transacción</label>
                    <div class="modal-input-group">
                        <div class="modal-input-prefix"><i class="bi bi-calendar3"></i></div>
                        <input type="text" id="editTransDate" class="modal-input" placeholder="aaaa-mm-dd">
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label
                    style="font-size:.82rem; color:var(--ud-muted); margin-bottom:.4rem; display:block; font-weight: 400;">Descripción</label>
                <div class="modal-input-group" style="height: auto;">
                    <textarea id="editTransDescription" class="modal-input"
                        style="height: 80px; padding: .6rem .75rem; border: none !important;"></textarea>
                </div>
            </div>

            <div class="modal-btns">
                <button type="button" class="btn-cancel" onclick="closeEditModal()">Cancelar</button>
                <button type="button" class="btn-save" id="saveTransBtn">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Eliminar Transacción -->
<div class="ud-modal-overlay" id="deleteTransModal">
    <div class="ud-modal" style="width: 440px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
            <div style="display:flex; align-items:center; gap: 0.75rem;">
                <i class="bi bi-exclamation-triangle" style="color:#f59e0b; font-size: 1.25rem;"></i>
                <h3 style="margin:0;">Eliminar Transacción</h3>
            </div>
            <button type="button" style="background:none; border:none; color: var(--ud-muted); cursor:pointer;"
                onclick="closeDeleteModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="delete-warning-box">
            <i class="bi bi-info-circle" style="color:#e53e3e; font-size: 1.1rem; margin-top: 2px;"></i>
            <p style="margin:0; color:#c53030; font-size: 0.88rem; line-height: 1.4;">
                Esta acción no se puede deshacer. El saldo de la unidad se recalculará después de la eliminación.
            </p>
        </div>

        <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.75rem; font-weight: 500;">
            Detalles de la Transacción:
        </div>

        <div class="delete-details-box">
            <div class="delete-details-row">
                <div class="delete-details-label">Fecha:</div>
                <div class="delete-details-value" id="delTransDate">March 10th, 2026</div>
            </div>
            <div class="delete-details-row">
                <div class="delete-details-label">Descripción:</div>
                <div class="delete-details-value" id="delTransDesc">CUOTA MANUAL MARZO</div>
            </div>
            <div class="delete-details-row">
                <div class="delete-details-label">Monto:</div>
                <div class="delete-details-value" id="delTransAmount">-MX$5,000.00</div>
            </div>
            <div class="delete-details-row">
                <div class="delete-details-label">Tipo:</div>
                <div class="delete-details-value" id="delTransType">Cargo</div>
            </div>
            <div class="delete-details-row">
                <div class="delete-details-label">Categoría:</div>
                <div class="delete-details-value" id="delTransCat">Cuota de Mantenimiento</div>
            </div>
        </div>

        <p class="text-danger-soft">
            Eliminar esta transacción la eliminará permanentemente del libro mayor de la cuenta y actualizará el saldo
            de la unidad.
        </p>

        <div style="display:flex; justify-content:flex-end; gap: 0.75rem;">
            <button type="button" class="btn-cancel" style="border: 1px solid var(--ud-border);"
                onclick="closeDeleteModal()">Cancelar</button>
            <button type="button" class="btn-save" style="background:#ef4444;" id="confirmDeleteBtn">Eliminar</button>
        </div>
    </div>
</div>

<!-- Modal: Detalles de Pago -->
<div id="paymentDetailOverlay"
    style="display:none; position:fixed; inset:0; z-index:9998; background:rgba(0,0,0,.45); justify-content:center; align-items:center;">
    <div
        style="background:#fff; border-radius:12px; width:560px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); padding:1.75rem;">
        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <h3 style="margin:0; font-size:1.15rem; font-weight:700; color:#1e293b;">Detalles de Pago</h3>
                <span
                    style="display:inline-flex; align-items:center; gap:4px; background:#ecfdf5; color:#059669; font-size:.75rem; font-weight:600; padding:3px 10px; border-radius:20px;">
                    <i class="bi bi-graph-up-arrow" style="font-size:.7rem;"></i> Ingreso
                </span>
            </div>
            <button type="button" onclick="closePaymentDetail()"
                style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.1rem;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <!-- Unit + Amount box -->
        <div
            style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <div
                    style="display:flex; align-items:center; gap:6px; color:#94a3b8; font-size:.78rem; margin-bottom:4px;">
                    <i class="bi bi-house-door"></i> Unidad
                </div>
                <div style="font-weight:700; font-size:1.05rem; color:#1e293b;" id="pd-unit">A-100</div>
            </div>
            <div style="text-align:right;">
                <div
                    style="display:flex; align-items:center; gap:6px; color:#94a3b8; font-size:.78rem; margin-bottom:4px; justify-content:flex-end;">
                    <i class="bi bi-currency-dollar"></i> Monto
                </div>
                <div style="font-weight:700; font-size:1.15rem; color:#059669;" id="pd-amount">
                    <i class="bi bi-graph-up-arrow" style="font-size:.8rem;"></i> MX$5,000.00
                </div>
            </div>
        </div>
        <!-- Category / Method / Date -->
        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1.5rem;">
            <div>
                <div
                    style="display:flex; align-items:center; gap:5px; color:#94a3b8; font-size:.75rem; margin-bottom:3px;">
                    <i class="bi bi-tag"></i> Categoría
                </div>
                <div style="font-weight:600; font-size:.85rem; color:#1e293b;" id="pd-category">Cuota de Mantenimiento
                </div>
            </div>
            <div>
                <div
                    style="display:flex; align-items:center; gap:5px; color:#94a3b8; font-size:.75rem; margin-bottom:3px;">
                    <i class="bi bi-credit-card"></i> Método de Pago
                </div>
                <div style="font-weight:600; font-size:.85rem; color:#1e293b;" id="pd-method">Transferencia Bancaria
                </div>
            </div>
            <div>
                <div
                    style="display:flex; align-items:center; gap:5px; color:#94a3b8; font-size:.75rem; margin-bottom:3px;">
                    <i class="bi bi-calendar3"></i> Fecha de Pago
                </div>
                <div style="font-weight:600; font-size:.85rem; color:#1e293b;" id="pd-date">25 de Marzo de 2026</div>
            </div>
        </div>
        <hr style="border:none; border-top:1px solid #e2e8f0; margin:0 0 1.25rem 0;">
        <!-- Descripción -->
        <div style="margin-bottom:1.25rem;">
            <div
                style="display:flex; align-items:center; gap:6px; color:#64748b; font-size:.78rem; font-weight:600; margin-bottom:6px;">
                <i class="bi bi-file-text"></i> Descripción
            </div>
            <div style="font-size:.9rem; color:#334155;" id="pd-desc">PAGO MANUAL MARZO</div>
        </div>
        <!-- Adjuntos -->
        <div id="pd-adjuntos-section" style="margin-bottom:1.5rem; display:none;">
            <hr style="border:none; border-top:1px solid #e2e8f0; margin:0 0 1.25rem 0;">
            <div
                style="display:flex; align-items:center; gap:6px; color:#64748b; font-size:.78rem; font-weight:600; margin-bottom:10px;">
                <i class="bi bi-paperclip"></i> Adjuntos
            </div>
            <div id="pd-adjuntos" style="display:flex; flex-wrap:wrap; gap:12px;"></div>
        </div>
        <!-- Footer -->
        <div style="display:flex; justify-content:flex-end;">
            <button type="button" onclick="closePaymentDetail()"
                style="padding:8px 24px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#374151; font-size:.88rem; font-weight:500; cursor:pointer;">Cerrar</button>
        </div>
    </div>
</div>

<!-- Lightbox overlay -->
<div id="lightboxOverlay"
    style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,.85); justify-content:center; align-items:center; flex-direction:column;">
    <button onclick="closeLightbox()"
        style="position:absolute; top:20px; right:20px; background:none; border:none; color:#fff; font-size:1.5rem; cursor:pointer; z-index:10000;">
        <i class="bi bi-x-lg"></i>
    </button>
    <img id="lightboxImg" src="" alt="Comprobante"
        style="max-width:90vw; max-height:80vh; border-radius:8px; object-fit:contain;" />
    <div style="color:#94a3b8; font-size:.8rem; margin-top:12px;">Desplaza para ampliar, arrastra para mover</div>
</div>

<style>
    .pd-thumb {
        width: 120px;
        cursor: pointer;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        transition: box-shadow .2s;
    }

    .pd-thumb:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
    }

    .pd-thumb img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        display: block;
    }

    .pd-filename {
        display: block;
        padding: 6px 8px;
        font-size: .72rem;
        color: #64748b;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Custom Category Dropdown ── */
    .cat-select {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        background: #fff;
        transition: border-color .2s, box-shadow .2s;
        position: relative;
    }

    .cat-select:hover {
        border-color: #cbd5e1;
    }

    .cat-select:focus,
    .cat-select.active {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
    }

    .cat-select-icon {
        color: #64748b;
        font-size: 1rem;
        display: flex;
        align-items: center;
    }

    .cat-select-label {
        flex: 1;
        font-size: .88rem;
        color: #1e293b;
        font-weight: 500;
    }

    .cat-select-chevron {
        color: #94a3b8;
        font-size: .7rem;
        transition: transform .2s;
    }

    .cat-select.active .cat-select-chevron {
        transform: rotate(180deg);
    }

    .cat-dropdown {
        display: none;
        position: absolute;
        left: 0;
        top: 100%;
        width: 100%;
        z-index: 9990;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, .12);
        padding: 6px 0;
        margin-top: 4px;
        max-height: 280px;
        overflow-y: auto;
        animation: catDropIn .15s ease-out;
    }

    .cat-dropdown.open {
        display: block;
    }

    @keyframes catDropIn {
        from {
            opacity: 0;
            transform: translateY(-6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .cat-option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        cursor: pointer;
        transition: background .15s;
    }

    .cat-option:hover {
        background: #f8fafc;
    }

    .cat-option.selected {
        background: #f0f9ff;
    }

    .cat-option-icon {
        font-size: 1rem;
        color: #64748b;
        width: 20px;
        text-align: center;
    }

    .cat-option-label {
        flex: 1;
        font-size: .88rem;
        color: #1e293b;
        font-weight: 400;
    }

    .cat-option-check {
        color: #6366f1;
        font-size: 1rem;
        opacity: 0;
        transition: opacity .15s;
    }

    .cat-option.selected .cat-option-check {
        opacity: 1;
    }

    /* ── Toast Notification ── */
    .ud-toast-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .ud-toast {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, .15);
        border-left: 4px solid #22c55e;
        min-width: 320px;
        animation: toastSlideIn .3s ease-out;
        font-size: .9rem;
        color: #1e293b;
    }

    .ud-toast.error {
        border-left-color: #ef4444;
    }

    .ud-toast-icon {
        font-size: 1.3rem;
    }

    .ud-toast-icon.success {
        color: #22c55e;
    }

    .ud-toast-icon.error {
        color: #ef4444;
    }

    .ud-toast-body {
        flex: 1;
    }

    .ud-toast-title {
        font-weight: 600;
        margin-bottom: 2px;
    }

    .ud-toast-msg {
        font-size: .82rem;
        color: #64748b;
    }

    .ud-toast-close {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 1.1rem;
        padding: 4px;
    }

    .ud-toast-close:hover {
        color: #475569;
    }

    @keyframes toastSlideIn {
        from {
            opacity: 0;
            transform: translateX(40px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes toastSlideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }

        to {
            opacity: 0;
            transform: translateX(40px);
        }
    }
</style>

<!-- Toast Container -->
<div class="ud-toast-container" id="toastContainer"></div>

<!-- Modal: Comprobante de Pago -->
<div class="ud-modal-overlay" id="reviewVoucherModal">
    <div class="ud-modal" style="width:660px; max-height:92vh; overflow-y:auto; padding:0; border-radius:16px;">

        <!-- Header dinámico -->
        <div id="rvHeader"
            style="padding:1.25rem 1.5rem; border-radius:16px 16px 0 0; display:flex; justify-content:space-between; align-items:center; background:linear-gradient(135deg, #1D4C9D 0%, #2563eb 100%); color:#fff;">
            <div style="display:flex; align-items:center; gap:.65rem;">
                <div
                    style="width:36px; height:36px; background:rgba(255,255,255,.18); border-radius:10px; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-receipt-cutoff" style="font-size:1.15rem;"></i>
                </div>
                <div>
                    <h3 style="margin:0; font-size:1.05rem; font-weight:700;" id="rvTitle">Revisar Comprobante</h3>
                    <span style="font-size:.72rem; opacity:.8;" id="rvSubtitle">Subido por el residente</span>
                </div>
            </div>
            <button type="button"
                style="background:rgba(255,255,255,.15); border:none; color:#fff; cursor:pointer; width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; transition:background .2s;"
                onclick="closeReviewModal()" onmouseover="this.style.background='rgba(255,255,255,.3)'"
                onmouseout="this.style.background='rgba(255,255,255,.15)'">
                <i class="bi bi-x-lg" style="font-size:.85rem;"></i>
            </button>
        </div>

        <input type="hidden" id="rvPaymentId">
        <input type="hidden" id="rvStatus" value="pending">

        <div style="padding:1.5rem;">
            <!-- Preview del comprobante -->
            <div id="rvPreview"
                style="background:linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%); border:1px dashed #cbd5e1; border-radius:12px; margin-bottom:1.5rem; min-height:180px; display:flex; align-items:center; justify-content:center; overflow:hidden; position:relative;">
            </div>

            <!-- Badge de estado -->
            <div id="rvStatusBadge"
                style="display:none; margin-bottom:1.25rem; padding:.75rem 1rem; border-radius:10px; font-size:.84rem; font-weight:600;">
            </div>

            <!-- Campos editables -->
            <div id="rvFormFields">
                <div class="modal-grid">
                    <div style="margin-bottom:1.15rem;">
                        <label
                            style="font-size:.76rem; color:#64748b; margin-bottom:.35rem; display:block; font-weight:500; text-transform:uppercase; letter-spacing:.5px;">Monto</label>
                        <div class="modal-input-group"
                            style="border-radius:10px; border:1.5px solid #e2e8f0; transition:border-color .2s;">
                            <div class="modal-input-prefix"
                                style="background:#f8fafc; border-right:1px solid #e2e8f0; padding:0 12px;"><i
                                    class="bi bi-currency-dollar" style="color:#1D4C9D;"></i></div>
                            <input type="number" id="rvAmount" class="modal-input" step="0.01"
                                style="font-weight:600; font-size:.95rem;"
                                onfocus="this.parentElement.style.borderColor='#1D4C9D'"
                                onblur="this.parentElement.style.borderColor='#e2e8f0'">
                        </div>
                    </div>
                    <div style="margin-bottom:1.15rem;">
                        <label
                            style="font-size:.76rem; color:#64748b; margin-bottom:.35rem; display:block; font-weight:500; text-transform:uppercase; letter-spacing:.5px;">Fecha
                            de pago</label>
                        <div class="modal-input-group"
                            style="border-radius:10px; border:1.5px solid #e2e8f0; transition:border-color .2s;">
                            <div class="modal-input-prefix"
                                style="background:#f8fafc; border-right:1px solid #e2e8f0; padding:0 12px;"><i
                                    class="bi bi-calendar-event" style="color:#1D4C9D;"></i></div>
                            <input type="text" id="rvDate" class="modal-input rv-flatpickr" style="font-weight:500;"
                                placeholder="Seleccionar fecha">
                        </div>
                    </div>
                </div>

                <div class="modal-grid">
                    <div style="margin-bottom:1.15rem;">
                        <label
                            style="font-size:.76rem; color:#64748b; margin-bottom:.35rem; display:block; font-weight:500; text-transform:uppercase; letter-spacing:.5px;">Método
                            de pago</label>
                        <select id="rvMethod"
                            style="border:1.5px solid #e2e8f0; border-radius:10px; padding:10px 14px; width:100%; font-size:.88rem; font-weight:500; color:#1e293b; background:#fff; cursor:pointer; transition:border-color .2s; outline:none; box-sizing:border-box;"
                            onfocus="this.style.borderColor='#1D4C9D'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="transfer">Transferencia Bancaria</option>
                            <option value="cash">Efectivo</option>
                            <option value="check">Cheque</option>
                            <option value="stripe">Stripe</option>
                        </select>
                    </div>
                    <div style="margin-bottom:1.15rem;">
                        <label
                            style="font-size:.76rem; color:#64748b; margin-bottom:.35rem; display:block; font-weight:500; text-transform:uppercase; letter-spacing:.5px;">Aplicar
                            a cuota</label>
                        <select id="rvChargeId"
                            style="border:1.5px solid #e2e8f0; border-radius:10px; padding:10px 14px; width:100%; font-size:.85rem; font-weight:500; color:#1e293b; background:#fff; cursor:pointer; transition:border-color .2s; outline:none; box-sizing:border-box; max-width:100%; text-overflow:ellipsis; overflow:hidden; white-space:nowrap;"
                            onfocus="this.style.borderColor='#1D4C9D'" onblur="this.style.borderColor='#e2e8f0'">
                            <option value="0">— Sin asignar —</option>
                            <?php foreach ($pendingCharges as $pc): ?>
                                <option value="<?= $pc['id'] ?>">
                                    <?= date('M Y', strtotime($pc['due_date'] ?? $pc['created_at'])) ?> —
                                    MX$<?= number_format((float) $pc['amount'], 2) ?> — <?= esc($pc['description']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="margin-bottom:1.25rem;">
                    <label
                        style="font-size:.76rem; color:#64748b; margin-bottom:.35rem; display:block; font-weight:500; text-transform:uppercase; letter-spacing:.5px;">Notas
                        <span style="font-weight:400; text-transform:none; letter-spacing:0;">(requerido para
                            rechazo)</span></label>
                    <textarea id="rvNotes"
                        style="border:1.5px solid #e2e8f0; border-radius:10px; padding:.65rem .85rem; width:100%; font-size:.88rem; min-height:68px; resize:vertical; font-family:inherit; color:#1e293b; transition:border-color .2s; outline:none; box-sizing:border-box;"
                        placeholder="Motivo del rechazo o comentarios..." onfocus="this.style.borderColor='#1D4C9D'"
                        onblur="this.style.borderColor='#e2e8f0'"></textarea>
                </div>
            </div>

            <!-- Detalles de solo lectura para aprobados/rechazados -->
            <div id="rvReadOnlyDetails" style="display:none;">
                <div id="rvReadOnlyNotes"
                    style="padding:.85rem 1rem; background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0; font-size:.88rem; color:#475569; line-height:1.5;">
                </div>
            </div>

            <!-- Botones de acción -->
            <div id="rvActions"
                style="display:flex; justify-content:flex-end; gap:.65rem; padding-top:.75rem; border-top:1px solid #f1f5f9; margin-top:.5rem;">
                <button type="button" onclick="closeReviewModal()"
                    style="padding:.55rem 1.1rem; border:1.5px solid #e2e8f0; border-radius:10px; background:#fff; color:#64748b; font-size:.84rem; font-weight:600; cursor:pointer; transition:all .2s;"
                    onmouseover="this.style.background='#f8fafc';this.style.borderColor='#cbd5e1'"
                    onmouseout="this.style.background='#fff';this.style.borderColor='#e2e8f0'">Cerrar</button>
                <button type="button" id="btnRejectVoucher"
                    style="padding:.55rem 1.1rem; border:none; border-radius:10px; background:linear-gradient(135deg, #ef4444, #dc2626); color:#fff; font-size:.84rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:.4rem; transition:all .2s; box-shadow:0 2px 8px rgba(239,68,68,.25);"
                    onmouseover="this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-x-circle"></i> Rechazar
                </button>
                <button type="button" id="btnApproveVoucher"
                    style="padding:.55rem 1.25rem; border:none; border-radius:10px; background:linear-gradient(135deg, #10b981, #059669); color:#fff; font-size:.84rem; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:.4rem; transition:all .2s; box-shadow:0 2px 8px rgba(16,185,129,.25);"
                    onmouseover="this.style.transform='translateY(-1px)'"
                    onmouseout="this.style.transform='translateY(0)'">
                    <i class="bi bi-check-circle"></i> Aprobar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    // ── Global Flatpickr ──
    const fpConfig = {
        locale: "es",
        dateFormat: "Y-m-d",
        disableMobile: true
    };

    let editDatePicker = flatpickr("#editTransDate", {
        ...fpConfig,
        altInput: true,
        altInputClass: "modal-input",
        altFormat: "j \\d\\e F \\d\\e Y",
    });

    // ── Tabs ──
    document.querySelectorAll('.ud-tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.ud-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.ud-tab-pane').forEach(p => p.classList.remove('active'));
            tab.classList.add('active');
            document.getElementById('tab-' + tab.dataset.tab).classList.add('active');
        });
    });

    // ── Estado de cuenta: select all ──
    const selEs = document.getElementById('selectAllEs');
    if (selEs) selEs.addEventListener('change', e => {
        document.querySelectorAll('#tab-estado input[type=checkbox]').forEach(cb => cb.checked = e.target.checked);
    });

    // ── Modal: Saldo Inicial ──
    const modal = document.getElementById('balanceModal');
    const openBtn = document.getElementById('openModalBtn');
    const closeBtn = document.getElementById('closeModalBtn');
    const saveBtn = document.getElementById('saveBalanceBtn');

    openBtn?.addEventListener('click', () => modal.classList.add('open'));
    closeBtn?.addEventListener('click', () => modal.classList.remove('open'));
    modal?.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });

    saveBtn?.addEventListener('click', () => {
        const val = document.getElementById('initialBalanceInput').value;
        const csrfToken = '<?= csrf_hash() ?>';

        fetch('<?= base_url('admin/finanzas/pagos-por-unidad/set-initial-balance') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': csrfToken
            },
            body: new URLSearchParams({
                unit_id: '<?= $unit['id'] ?>',
                initial_balance: val,
                '<?= csrf_token() ?>': csrfToken
            })
        })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    modal.classList.remove('open');
                    window.location.reload();
                } else {
                    alert(data.message || 'Error al guardar.');
                }
            })
            .catch(() => alert('Error de conexión.'));
    });

    // ── Modal: Editar Transacción ──
    const editModal = document.getElementById('editTransModal');
    const editForm = document.getElementById('editTransForm');

    function closeEditModal() {
        editModal.classList.remove('open');
    }

    document.querySelectorAll('.edit-trans-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const data = this.dataset;
            document.getElementById('editTransId').value = data.id;
            document.getElementById('editTransAmount').value = data.amount;
            document.getElementById('editTransCategory').value = data.cat || "";
            setCatDropdownValue(data.cat || '', data.catname || '');
            document.getElementById('editTransDescription').value = data.desc;

            // Update flatpickr
            editDatePicker.setDate(data.date);

            editModal.classList.add('open');
        });
    });

    // ── Modal: Eliminar Transacción ──
    const deleteModal = document.getElementById('deleteTransModal');
    let deleteId = null;

    function closeDeleteModal() {
        deleteModal.classList.remove('open');
    }

    document.querySelectorAll('.delete-trans-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const data = this.dataset;
            console.log('Delete button clicked', data);
            deleteId = data.id;

            document.getElementById('delTransDate').innerText = data.date;
            document.getElementById('delTransDesc').innerText = data.desc;
            document.getElementById('delTransType').innerText = data.type;
            document.getElementById('delTransCat').innerText = data.catname;

            // Formatting amount as shown in images (- prefix for charges)
            const numAmount = parseFloat(data.amount);
            const fmtAmount = new Intl.NumberFormat('es-MX', { style: 'currency', currency: 'MXN' }).format(numAmount);
            document.getElementById('delTransAmount').innerText = (data.type === 'Cargo' ? '-' : '') + fmtAmount;

            console.log('Opening delete modal');
            deleteModal.classList.add('open');
        });
    });

    document.getElementById('confirmDeleteBtn')?.addEventListener('click', () => {
        if (!deleteId) return;

        const csrfToken = '<?= csrf_hash() ?>';

        fetch('<?= base_url('admin/finanzas/transaccion/delete') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': csrfToken
            },
            body: new URLSearchParams({
                id: deleteId,
                '<?= csrf_token() ?>': csrfToken
            })
        })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    closeDeleteModal();
                    showToast('Registro eliminado', 'La transacción se eliminó correctamente.', 'success');
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    alert(data.message || 'Error al eliminar registro.');
                }
            })
            .catch(() => alert('Error de conexión al servidor.'));
    });

    document.getElementById('saveTransBtn')?.addEventListener('click', () => {
        const id = document.getElementById('editTransId').value;
        const amount = document.getElementById('editTransAmount').value;
        const date = document.getElementById('editTransDate').value;
        const cat = document.getElementById('editTransCategory').value;
        const desc = document.getElementById('editTransDescription').value;

        const csrfToken = '<?= csrf_hash() ?>';

        fetch('<?= base_url('admin/finanzas/transaccion/update') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': csrfToken
            },
            body: new URLSearchParams({
                id: id,
                amount: amount,
                due_date: date,
                category_id: cat,
                description: desc,
                '<?= csrf_token() ?>': csrfToken
            })
        })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    closeEditModal();
                    showToast('Cambios guardados', 'La transacción se actualizó correctamente.', 'success');
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    alert(data.message || 'Error al guardar cambios.');
                }
            })
            .catch(() => alert('Error de conexión al servidor.'));
    });
    /* ── Payment Detail Modal ── */
    document.querySelectorAll('.view-payment-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('pd-unit').textContent = btn.dataset.unit;
            document.getElementById('pd-amount').textContent = btn.dataset.amount;
            document.getElementById('pd-category').textContent = btn.dataset.category;
            document.getElementById('pd-method').textContent = btn.dataset.method;
            document.getElementById('pd-date').textContent = btn.dataset.date;
            document.getElementById('pd-desc').textContent = btn.dataset.desc;

            const adjuntosContainer = document.getElementById('pd-adjuntos');
            const adjuntosSection = document.getElementById('pd-adjuntos-section');
            adjuntosContainer.innerHTML = '';

            if (btn.dataset.attachment) {
                adjuntosSection.style.display = 'block';
                let attachments = [];
                try {
                    attachments = JSON.parse(btn.dataset.attachment);
                } catch (e) {
                    attachments = [btn.dataset.attachment];
                }

                // Asegurar scroll horizontal si hay múltiples
                adjuntosContainer.style.display = 'flex';
                adjuntosContainer.style.overflowX = 'auto';
                adjuntosContainer.style.gap = '12px';
                adjuntosContainer.style.paddingBottom = '8px';

                attachments.forEach(att => {
                    const filename = att.split('/').pop() || att;
                    let imgUrl;
                    let cleanAtt;
                    if (att.startsWith('payments/')) {
                        cleanAtt = att.replace('payments/', '');
                        imgUrl = '<?= base_url('admin/finanzas/archivo/payments/') ?>' + cleanAtt;
                    } else {
                        cleanAtt = att.replace('financial/', '');
                        imgUrl = '<?= base_url('admin/finanzas/archivo/financial/') ?>' + cleanAtt;
                    }

                    const thumb = document.createElement('div');
                    thumb.className = 'pd-thumb';
                    thumb.style.flexShrink = '0'; // Evitar que se encojan
                    thumb.style.textAlign = 'center';

                    const fileExt = cleanAtt.split('.').pop().toLowerCase();
                    if (fileExt === 'pdf') {
                        thumb.innerHTML = `
                            <div style="height:80px; display:flex; align-items:center; justify-content:center; background:#f8fafc;">
                                <i class="bi bi-file-earmark-pdf" style="font-size:2.5rem; color:#ef4444;"></i>
                            </div>
                            <span class="pd-filename">${cleanAtt}</span>
                        `;
                        thumb.addEventListener('click', () => window.open(imgUrl, '_blank'));
                    } else {
                        thumb.innerHTML = `<img src="${imgUrl}" alt="Comprobante" /><span class="pd-filename">${cleanAtt}</span>`;
                        thumb.addEventListener('click', () => openLightbox(imgUrl));
                    }

                    adjuntosContainer.appendChild(thumb);
                });
            } else {
                adjuntosSection.style.display = 'none';
            }

            document.getElementById('paymentDetailOverlay').style.display = 'flex';
        });
    });

    function closePaymentDetail() {
        document.getElementById('paymentDetailOverlay').style.display = 'none';
    }

    function openLightbox(src) {
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightboxOverlay').style.display = 'flex';
    }
    function closeLightbox() {
        document.getElementById('lightboxOverlay').style.display = 'none';
        document.getElementById('lightboxImg').src = '';
    }

    /* ── Custom Category Dropdown ── */
    function toggleCatDropdown() {
        const trigger = document.getElementById('catSelectTrigger');
        const dropdown = document.getElementById('catDropdown');
        const isOpen = dropdown.classList.contains('open');
        if (isOpen) {
            dropdown.classList.remove('open');
            trigger.classList.remove('active');
        } else {
            dropdown.classList.add('open');
            trigger.classList.add('active');
        }
    }

    function selectCatOption(el) {
        document.getElementById('editTransCategory').value = el.dataset.value;
        document.getElementById('catSelectedIcon').innerHTML = '<i class="bi ' + el.dataset.icon + '"></i>';
        document.getElementById('catSelectedLabel').textContent = el.dataset.label;
        document.querySelectorAll('.cat-option').forEach(o => o.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('catDropdown').classList.remove('open');
        document.getElementById('catSelectTrigger').classList.remove('active');
    }

    function setCatDropdownValue(value, label) {
        document.querySelectorAll('.cat-option').forEach(o => {
            o.classList.remove('selected');
            if (o.dataset.value === String(value)) {
                o.classList.add('selected');
                document.getElementById('catSelectedIcon').innerHTML = '<i class="bi ' + o.dataset.icon + '"></i>';
                document.getElementById('catSelectedLabel').textContent = o.dataset.label;
            }
        });
        if (!value) {
            document.getElementById('catSelectedIcon').innerHTML = '<i class="bi bi-tag"></i>';
            document.getElementById('catSelectedLabel').textContent = 'Seleccionar categoría';
        }
    }

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.cat-select') && !e.target.closest('.cat-dropdown')) {
            document.getElementById('catDropdown').classList.remove('open');
            document.getElementById('catSelectTrigger').classList.remove('active');
        }
    });

    /* ── Toast Notification ── */
    function showToast(title, msg, type) {
        const container = document.getElementById('toastContainer');
        const iconClass = type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        const toast = document.createElement('div');
        toast.className = 'ud-toast' + (type === 'error' ? ' error' : '');
        toast.innerHTML = `
            <i class="bi ${iconClass} ud-toast-icon ${type}"></i>
            <div class="ud-toast-body">
                <div class="ud-toast-title">${title}</div>
                <div class="ud-toast-msg">${msg}</div>
            </div>
            <button class="ud-toast-close" onclick="this.parentElement.remove()">&times;</button>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.animation = 'toastSlideOut .3s ease-in forwards';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ═══════════════════════════════════════════
    // COMPROBANTE MODAL (view + review)
    // ═══════════════════════════════════════════

    let rvDatePicker = null;

    document.querySelectorAll('.voucher-row').forEach(btn => {
        btn.addEventListener('click', function () {
            const d = this.dataset;
            const status = d.status || 'pending';
            const isPending = status === 'pending';
            const isApproved = status === 'approved';
            const isRejected = status === 'rejected';

            document.getElementById('rvPaymentId').value = d.id;
            document.getElementById('rvStatus').value = status;
            document.getElementById('rvAmount').value = d.amount;
            document.getElementById('rvMethod').value = d.method || 'transfer';
            document.getElementById('rvNotes').value = d.notes || '';

            // Flatpickr
            if (rvDatePicker) rvDatePicker.destroy();
            const dateInput = document.getElementById('rvDate');
            dateInput.value = d.date;
            rvDatePicker = flatpickr(dateInput, {
                ...fpConfig,
                defaultDate: d.date,
                altInput: true,
                altFormat: 'j M Y',
                altInputClass: 'modal-input',
                clickOpens: isPending
            });
            if (!isPending && rvDatePicker.altInput) {
                rvDatePicker.altInput.style.cursor = 'default';
            }

            // Header gradient por estado
            const header = document.getElementById('rvHeader');
            const title = document.getElementById('rvTitle');
            const subtitle = document.getElementById('rvSubtitle');
            if (isApproved) {
                header.style.background = 'linear-gradient(135deg, #059669 0%, #10b981 100%)';
                title.textContent = 'Comprobante Aprobado';
                subtitle.textContent = 'Pago verificado y aplicado';
            } else if (isRejected) {
                header.style.background = 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)';
                title.textContent = 'Comprobante Rechazado';
                subtitle.textContent = 'Pago no válido';
            } else {
                header.style.background = 'linear-gradient(135deg, #282F3E 0%, #2B3548 100%)';
                title.textContent = 'Revisar Comprobante';
                subtitle.textContent = 'Pendiente de aprobación';
            }

            // Status badge
            const badge = document.getElementById('rvStatusBadge');
            if (isApproved) {
                badge.style.display = 'flex';
                badge.style.background = '#ecfdf5';
                badge.style.color = '#065f46';
                badge.style.border = '1px solid #a7f3d0';
                badge.innerHTML = '<i class="bi bi-check-circle-fill" style="margin-right:.5rem;"></i> Este comprobante fue aprobado y el pago fue aplicado al saldo.';
            } else if (isRejected) {
                badge.style.display = 'flex';
                badge.style.background = '#fef2f2';
                badge.style.color = '#991b1b';
                badge.style.border = '1px solid #fecaca';
                badge.innerHTML = '<i class="bi bi-x-circle-fill" style="margin-right:.5rem;"></i> Este comprobante fue rechazado por el administrador.';
            } else {
                badge.style.display = 'none';
            }

            // Toggle editable vs read-only
            const formFields = document.getElementById('rvFormFields');
            const readOnly = document.getElementById('rvReadOnlyDetails');
            const actions = document.getElementById('rvActions');

            if (isPending) {
                formFields.style.display = 'block';
                readOnly.style.display = 'none';
                document.getElementById('btnApproveVoucher').style.display = 'flex';
                document.getElementById('btnRejectVoucher').style.display = 'flex';
                // Enable fields
                document.getElementById('rvAmount').readOnly = false;
                document.getElementById('rvMethod').disabled = false;
                document.getElementById('rvChargeId').disabled = false;
                document.getElementById('rvNotes').readOnly = false;
                document.getElementById('rvNotes').placeholder = 'Motivo del rechazo o comentarios...';
            } else {
                formFields.style.display = 'none';
                readOnly.style.display = 'block';
                document.getElementById('btnApproveVoucher').style.display = 'none';
                document.getElementById('btnRejectVoucher').style.display = 'none';

                const methodNames = { transfer: 'Transferencia Bancaria', cash: 'Efectivo', check: 'Cheque', stripe: 'Stripe' };
                const notes = d.notes ? `<div style="margin-top:.75rem; padding-top:.75rem; border-top:1px solid #e2e8f0;"><strong style="font-size:.76rem; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8;">Notas</strong><p style="margin:.35rem 0 0; color:#334155;">${d.notes}</p></div>` : '';
                document.getElementById('rvReadOnlyNotes').innerHTML = `
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                        <div><strong style="font-size:.76rem; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8;">Monto</strong><p style="margin:.35rem 0 0; font-size:1.1rem; font-weight:700; color:#1e293b;">MX$${parseFloat(d.amount).toLocaleString('es-MX', { minimumFractionDigits: 2 })}</p></div>
                        <div><strong style="font-size:.76rem; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8;">Método</strong><p style="margin:.35rem 0 0; color:#334155;">${methodNames[d.method] || d.method}</p></div>
                        <div><strong style="font-size:.76rem; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8;">Fecha de pago</strong><p style="margin:.35rem 0 0; color:#334155;">${new Date(d.date + 'T12:00:00').toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' })}</p></div>
                        <div><strong style="font-size:.76rem; text-transform:uppercase; letter-spacing:.5px; color:#94a3b8;">Estado</strong><p style="margin:.35rem 0 0; color:${isApproved ? '#059669' : '#dc2626'}; font-weight:600;">${isApproved ? '✓ Aprobado' : '✕ Rechazado'}</p></div>
                    </div>
                    ${notes}
                `;
            }

            // Preview del comprobante
            const previewArea = document.getElementById('rvPreview');
            const _proofPath = (d.proof || '').startsWith('payments/') ? d.proof : 'payments/' + d.proof;
            const proofUrl = '<?= base_url("admin/finanzas/archivo/") ?>' + _proofPath;
            const isPdf = (d.proof || '').toLowerCase().endsWith('.pdf');

            if (isPdf) {
                previewArea.innerHTML = `<div style="text-align:center; padding:2rem;">
                    <div style="width:64px; height:64px; background:#fef2f2; border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto .75rem;">
                        <i class="bi bi-file-earmark-pdf-fill" style="font-size:2rem; color:#ef4444;"></i>
                    </div>
                    <p style="margin:0 0 .5rem; font-weight:600; color:#1e293b;">Documento PDF</p>
                    <a href="${proofUrl}" target="_blank" style="color:#1D4C9D; font-size:.84rem; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:.3rem;">
                        <i class="bi bi-box-arrow-up-right"></i> Abrir en nueva pestaña
                    </a>
                </div>`;
            } else if (d.proof) {
                previewArea.innerHTML = `<img src="${proofUrl}" alt="Comprobante" style="max-width:100%; max-height:320px; border-radius:10px; cursor:pointer; object-fit:contain; display:block; margin:auto;" onclick="openLightbox('${proofUrl}')" title="Clic para ampliar">`;
            } else {
                previewArea.innerHTML = `<div style="text-align:center; padding:2.5rem; color:#94a3b8;">
                    <i class="bi bi-image" style="font-size:2.5rem; opacity:.5;"></i><br>
                    <span style="font-size:.84rem; margin-top:.5rem; display:block;">Sin comprobante adjunto</span>
                </div>`;
            }

            document.getElementById('reviewVoucherModal').classList.add('open');
        });
    });

    function closeReviewModal() {
        document.getElementById('reviewVoucherModal').classList.remove('open');
        if (rvDatePicker) rvDatePicker.destroy();
        rvDatePicker = null;
    }

    // Lightbox
    function openLightbox(imgUrl) {
        event.stopPropagation();
        let lb = document.getElementById('imgLightbox');
        if (!lb) {
            lb = document.createElement('div');
            lb.id = 'imgLightbox';
            lb.style.cssText = 'position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,.85);display:flex;align-items:center;justify-content:center;opacity:0;transition:opacity .25s;cursor:zoom-out;';
            lb.innerHTML = '<img style="max-width:92vw;max-height:92vh;border-radius:8px;box-shadow:0 8px 40px rgba(0,0,0,.5);object-fit:contain;transform:scale(.95);transition:transform .25s;"><button style="position:absolute;top:20px;right:24px;background:rgba(255,255,255,.15);border:none;color:#fff;width:40px;height:40px;border-radius:10px;font-size:1.2rem;cursor:pointer;display:flex;align-items:center;justify-content:center;backdrop-filter:blur(8px);"><i class="bi bi-x-lg"></i></button>';
            lb.onclick = function () { closeLightbox(); };
            document.body.appendChild(lb);
        }
        lb.querySelector('img').src = imgUrl;
        lb.style.display = 'flex';
        requestAnimationFrame(() => { lb.style.opacity = '1'; lb.querySelector('img').style.transform = 'scale(1)'; });
    }
    function closeLightbox() {
        const lb = document.getElementById('imgLightbox');
        if (lb) { lb.style.opacity = '0'; lb.querySelector('img').style.transform = 'scale(.95)'; setTimeout(() => { lb.style.display = 'none'; }, 250); }
    }

    document.getElementById('btnApproveVoucher').addEventListener('click', function () {
        submitVoucherReview('approve');
    });

    document.getElementById('btnRejectVoucher').addEventListener('click', function () {
        const notes = document.getElementById('rvNotes').value;
        if (!notes.trim()) {
            Swal.fire({
                title: 'Motivo requerido',
                text: 'Por favor indica el motivo del rechazo en el campo de notas.',
                icon: 'info',
                confirmButtonColor: '#1D4C9D'
            });
            return;
        }
        submitVoucherReview('reject');
    });

    function submitVoucherReview(action) {
        const paymentId = document.getElementById('rvPaymentId').value;
        const amount = document.getElementById('rvAmount').value;
        const method = document.getElementById('rvMethod').value;
        const date = document.getElementById('rvDate').value;
        const chargeId = document.getElementById('rvChargeId').value;
        const notes = document.getElementById('rvNotes').value;

        closeReviewModal();

        Swal.fire({
            title: action === 'approve' ? 'Aprobando...' : 'Rechazando...',
            text: 'Procesando comprobante',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('<?= base_url("admin/finanzas/comprobante/review") ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            body: new URLSearchParams({
                payment_id: paymentId,
                action: action,
                amount: amount,
                payment_method: method,
                payment_date: date,
                charge_id: chargeId,
                admin_notes: notes,
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: action === 'approve' ? '¡Aprobado!' : 'Rechazado',
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#1D4C9D'
                    }).then(() => window.location.reload());
                } else {
                    Swal.fire('Error', data.message || 'Error al procesar.', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Problema de conexión.', 'error');
            });
    }

    // Delete voucher
    document.querySelectorAll('.delete-voucher-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Eliminar Comprobante',
                text: '¿Está seguro de que desea eliminar este comprobante?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch('<?= base_url("admin/finanzas/comprobante/delete") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        body: new URLSearchParams({
                            payment_id: id,
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire('Eliminado', data.message, 'success').then(() => window.location.reload());
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        });
                }
            });
        });
    });

</script>

<?= $this->endSection() ?>