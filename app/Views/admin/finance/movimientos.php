<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">

<style>
    /* Variables Premium SaaS Base */
    :root {
        --fin-bg: #EEF1F9;
        --fin-card-bg: #ffffff;
        --fin-text-main: #1e293b;
        --fin-text-muted: #64748b;
        --fin-border: #e2e8f0;
        --fin-primary: #232d3f;
        /* Dark sidebar matching */
        --fin-success: #10b981;
        --fin-danger: #ef4444;
        --fin-border-radius: 6px;
        --fin-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --fin-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    body {
        margin: 0;
        background-color: var(--fin-bg);
    }

    /* Container global */
    .mov-container {
        width: 100%;
        min-height: 100vh;
        /* Allow layout to grow instead of strictly being 100% fixed */
        display: flex;
        flex-direction: column;
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

    .cc-hero-btndark {
        background: #1D4C9D;
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

    .btn-config {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: var(--fin-border-radius);
        font-size: 0.85rem;
        transition: all 0.2s;
    }

    .btn-config:hover {
        background: rgba(255, 255, 255, 0.2);
        color: white;
    }

    /* Toolbar / Filtros */
    .mov-toolbar {
        background: var(--fin-card-bg);
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-border-radius);
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-end;
        gap: 1rem;
        box-shadow: var(--fin-shadow-sm);
    }

    .toolbar-left {
        display: flex;
        align-items: flex-end;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .toolbar-form-group {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .toolbar-label {
        font-size: 0.75rem;
        color: var(--fin-text-muted);
        font-weight: 500;
    }

    .toolbar-control {
        height: 38px;
        padding: 0 0.75rem;
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-border-radius);
        font-size: 0.85rem;
        color: var(--fin-text-main);
        outline: none;
        min-width: 140px;
        background: white;
    }

    .toolbar-control:focus {
        border-color: #3b82f6;
    }

    .month-nav-group {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .btn-month-nav {
        background: white;
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-border-radius);
        width: 38px;
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--fin-text-main);
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s;
    }

    .btn-month-nav:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .toolbar-right {
        display: flex;
        gap: 0.75rem;
    }

    .btn-outline {
        border: 1px solid var(--fin-border);
        background: white;
        color: var(--fin-text-main);
        padding: 0 1rem;
        height: 38px;
        border-radius: var(--fin-border-radius);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s;
    }

    .btn-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-primary {
        background: #334155;
        border: none;
        color: white;
        padding: 0 1rem;
        height: 38px;
        border-radius: var(--fin-border-radius);
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        transition: background 0.2s;
    }

    .btn-primary:hover {
        background: #1e293b;
    }

    /* Tabla y Sumarios Container */
    .mov-content-box {
        background: var(--fin-card-bg);
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-border-radius);
        box-shadow: var(--fin-shadow-sm);
        display: flex;
        flex-direction: column;
        margin-bottom: 2rem;
        min-height: 400px;
        overflow: hidden;
    }

    /* Table */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
        flex-grow: 1;
        /* Table container stretches */
    }

    .mov-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .mov-table th,
    .mov-table td {
        padding: 1rem 1.25rem;
        text-align: left;
        font-size: 0.85rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }

    .mov-table th {
        color: var(--fin-text-muted);
        font-weight: 500;
        background: #f8fafc;
        white-space: nowrap;
    }

    .mov-table td {
        color: var(--fin-text-main);
        vertical-align: middle;
    }

    .mov-table tbody tr:hover {
        background: #f8fafc;
    }

    .text-amount-success {
        color: var(--fin-success);
        font-weight: 600;
    }

    .text-amount-danger {
        color: var(--fin-danger);
        font-weight: 600;
    }

    .badge-status {
        padding: 0.25rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }

    .badge-completed {
        background: #dcfce7;
        color: #166534;
    }

    .badge-pending {
        background: #fef9c3;
        color: #854d0e;
    }

    .action-icons i {
        color: var(--fin-text-muted);
        cursor: pointer;
        margin: 0 0.3rem;
        font-size: 0.95rem;
        transition: color 0.2s;
    }

    .action-icons i:hover {
        color: #3b82f6;
    }

    .action-icons i.text-danger:hover {
        color: #dc2626 !important;
    }

    /* Sumarios debajo de la tabla */
    .mov-summaries {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1.5rem;
        background: #ffffff;
        border-top: 1px solid var(--fin-border);
        border-bottom: 1px solid var(--fin-border);
        flex-wrap: wrap;
    }

    .summary-card {
        border: 1px solid #f1f5f9;
        border-radius: var(--fin-border-radius);
        padding: 1rem 1.5rem;
        min-width: 180px;
        background: #fafafa;
    }

    .summary-label {
        font-size: 0.75rem;
        color: var(--fin-text-muted);
        margin-bottom: 0.4rem;
        font-weight: 500;
    }

    .summary-val {
        font-size: 1.25rem;
        font-weight: 700;
    }

    /* Footer de Paginación */
    .mov-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: #ffffff;
        border-bottom-left-radius: var(--fin-border-radius);
        border-bottom-right-radius: var(--fin-border-radius);
    }

    .pag-left {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--fin-text-muted);
    }

    .pag-left select {
        padding: 0.2rem;
        border: 1px solid var(--fin-border);
        border-radius: 4px;
        font-size: 0.8rem;
    }

    .pag-right {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.8rem;
        color: var(--fin-text-muted);
    }

    .pag-arrows {
        display: flex;
        gap: 0.5rem;
    }

    .pag-arrows button {
        background: transparent;
        border: none;
        color: var(--fin-text-muted);
        cursor: pointer;
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
    }

    .pag-arrows button:hover {
        background: #f1f5f9;
        color: var(--fin-text-main);
    }

    /* Vista Agrupada */
    .group-header {
        background-color: #f8fafc !important;
        cursor: pointer;
    }

    .group-header td {
        font-weight: 600;
        color: var(--fin-text-main);
        border-top: 1px solid var(--fin-border);
    }

    .group-child.hidden {
        display: none;
    }

    .flatpickr-monthSelect-month {
        text-transform: capitalize;
    }

    /* Premium Tooltips for Action Icons */
    .action-icons {
        white-space: nowrap;
    }

    .action-icons i {
        position: relative;
        cursor: pointer;
        display: inline-block;
        padding: 4px;
        margin: 0 2px;
    }

    .action-icons i[data-tooltip]::after {
        content: attr(data-tooltip);
        opacity: 0;
        visibility: hidden;
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translate(-50%, 5px);
        background: #1e293b;
        color: #fff;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-family: inherit;
        font-weight: 500;
        white-space: nowrap;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        z-index: 100;
        pointer-events: none;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .action-icons i[data-tooltip]:hover::after {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, 0);
    }

    /* ===== Bulk Delete Button ===== */
    .btn-bulk-delete {
        background: #ef4444;
        border: none;
        color: white;
        padding: 0 1rem;
        height: 38px;
        border-radius: var(--fin-border-radius);
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: none;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
        animation: bulkBtnAppear 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .btn-bulk-delete:hover {
        background: #dc2626;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.35);
        transform: translateY(-1px);
    }

    .btn-bulk-delete .bulk-count {
        background: rgba(255, 255, 255, 0.25);
        padding: 1px 8px;
        border-radius: 10px;
        font-size: 0.78rem;
        font-weight: 700;
    }

    @keyframes bulkBtnAppear {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(4px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    /* Row selected state */
    .mov-table tbody tr.row-selected {
        background: #fef2f2 !important;
    }

    .mov-table tbody tr.row-selected td {
        border-bottom-color: #fecaca;
    }

    /* Checkbox styling */
    .mov-table input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #ef4444;
        cursor: pointer;
    }

    /* ===== Bulk Delete Confirmation Modal ===== */
    .bulk-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
        z-index: 10000;
        display: none;
        justify-content: center;
        align-items: center;
        opacity: 0;
        transition: opacity 0.2s;
    }

    .bulk-modal-overlay.open {
        display: flex;
        opacity: 1;
    }

    .bulk-modal {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        width: 460px;
        max-width: 95vw;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        transform: translateY(-10px);
        transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .bulk-modal-overlay.open .bulk-modal {
        transform: translateY(0);
    }

    .bulk-modal h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 0.75rem;
    }

    .bulk-modal p {
        font-size: 0.9rem;
        color: #3F67AC;
        margin: 0 0 1.5rem;
        line-height: 1.5;
    }

    .bulk-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .bulk-modal-footer .btn-cancel {
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

    .bulk-modal-footer .btn-cancel:hover {
        background: #f8fafc;
    }

    .bulk-modal-footer .btn-confirm-delete {
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

    .bulk-modal-footer .btn-confirm-delete:hover {
        background: #dc2626;
    }

    /* ===== Toast Notification ===== */
    .mov-toast {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 11000;
        background: white;
        border-radius: 10px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transform: translateX(120%);
        transition: transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        border-left: 4px solid var(--fin-success);
        max-width: 400px;
    }

    .mov-toast.toast-error {
        border-left-color: var(--fin-danger);
    }

    .mov-toast.show {
        transform: translateX(0);
    }

    .mov-toast .toast-icon {
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

    .mov-toast.toast-error .toast-icon {
        background: #fee2e2;
        color: #dc2626;
    }

    .mov-toast .toast-body {
        flex: 1;
    }

    .mov-toast .toast-title {
        font-weight: 600;
        font-size: 0.88rem;
        color: var(--fin-text-main);
    }

    .mov-toast .toast-msg {
        font-size: 0.78rem;
        color: var(--fin-text-muted);
        margin-top: 1px;
    }
</style>

<div class="mov-container">

    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <h2 class="cc-hero-title">Finanzas</h2>
            <div class="cc-hero-divider"></div>
            <div class="cc-hero-breadcrumb">
                <i class="bi bi-credit-card"></i>
                <i class="bi bi-chevron-right"></i>
                <h2 class="cc-hero-title">Movimientos Mensuales</h2>
                <i class="bi bi-chevron-right"></i>
                Registro financiero mensual
            </div>
        </div>
        <div class="toolbar-right">
            <button class="btn-bulk-delete" id="btnBulkDelete" onclick="openBulkDeleteModal()">
                <i class="bi bi-trash3"></i> Eliminar <span class="bulk-count" id="bulkCount">0</span>
            </button>
            <button class="cc-hero-btndark" onclick="descargarReporte()"><i class="bi bi-download"></i> Descargar
                Reporte Financero</button>
            <button class="cc-hero-btn"
                onclick="window.location.href='<?= base_url('admin/finanzas/nuevo-registro') ?>'"><i
                    class="bi bi-plus"></i> Nuevo Registro</button>
        </div>
    </div>
    <!-- ── END Hero ── -->

    <!-- Toolbar Lateral -->
    <div class="mov-toolbar">
        <div class="toolbar-left">
            <!-- Date Picker (Mes) -->
            <div class="toolbar-form-group">
                <label class="toolbar-label">Mes</label>
                <div class="month-nav-group">
                    <button type="button" class="btn-month-nav" id="btnPrevMonth"><i
                            class="bi bi-chevron-left"></i></button>
                    <div class="position-relative">
                        <i class="bi bi-calendar position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"
                            style="font-size: 0.8rem;"></i>
                        <input type="text" id="monthPicker" class="toolbar-control ps-4 pe-2" placeholder="Mes"
                            value="<?= esc($selectedMonth) ?>"
                            style="cursor: pointer; background: white; text-align: center; width: 130px; min-width: 0;">
                    </div>
                    <button type="button" class="btn-month-nav" id="btnNextMonth"><i
                            class="bi bi-chevron-right"></i></button>
                </div>
            </div>

            <!-- Filtros -->
            <div class="toolbar-form-group">
                <label class="toolbar-label">Buscar pagos</label>
                <div class="position-relative">
                    <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"
                        style="font-size: 0.8rem;"></i>
                    <input type="text" id="searchInput" class="toolbar-control ps-4" placeholder="Buscar pagos">
                </div>
            </div>

            <div class="toolbar-form-group">
                <label class="toolbar-label">Tipo</label>
                <select id="typeFilter" class="toolbar-control">
                    <option value="todos">Todos</option>
                    <option value="ingreso">Ingresos</option>
                    <option value="egreso">Gastos</option>
                </select>
            </div>

            <div class="toolbar-form-group">
                <label class="toolbar-label">Categoría</label>
                <select id="categoryFilter" class="toolbar-control">
                    <option value="todos">Todos</option>
                    <?php
                    $incomes = array_filter($categories, fn($c) => $c['type'] === 'income');
                    $expenses = array_filter($categories, fn($c) => $c['type'] === 'expense');
                    ?>
                    <?php if (count($incomes) > 0): ?>
                        <optgroup label="Ingresos">
                            <?php foreach ($incomes as $i): ?>
                                <option value="<?= esc($i['name']) ?>"><?= esc($i['name']) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                    <?php if (count($expenses) > 0): ?>
                        <optgroup label="Gastos">
                            <?php foreach ($expenses as $e): ?>
                                <option value="<?= esc($e['name']) ?>"><?= esc($e['name']) ?></option>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endif; ?>
                </select>
            </div>

            <div class="toolbar-form-group">
                <label class="toolbar-label">Vista</label>
                <select id="viewFilter" class="toolbar-control">
                    <option value="lista">Vista Lista</option>
                    <option value="agrupada">Vista Agrupada</option>
                </select>
            </div>
        </div>


    </div>

    <!-- Resultados -->
    <div class="mov-content-box">

        <div class="table-responsive">
            <table class="mov-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"><input type="checkbox" id="selectAllCheckbox"></th>
                        <th>Fecha <i class="bi bi-chevron-up ms-1" style="font-size:0.7rem;"></i></th>
                        <th style="min-width: 200px;">Descripción</th>
                        <th>Categoría</th>
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <!-- VISTA LISTA -->
                <tbody id="tbody-lista">
                    <?php if (!empty($records)): ?>
                        <?php foreach ($records as $rec): ?>
                            <tr class="mov-row" data-desc="<?= htmlspecialchars(strtolower($rec['descripcion'])) ?>"
                                data-type="<?= $rec['tipo'] ?>" data-cat="<?= htmlspecialchars($rec['categoria']) ?>">
                                <td><input type="checkbox" class="row-checkbox" data-id="<?= esc($rec['id']) ?>"></td>
                                <td><?= esc($rec['fecha']) ?></td>
                                <td class="text-uppercase" style="font-size:0.8rem; font-weight:500;">
                                    <?= esc($rec['descripcion']) ?>
                                </td>
                                <td class="text-uppercase" style="font-size:0.75rem; color:#64748b;">
                                    <?= esc($rec['categoria']) ?>
                                </td>

                                <td>
                                    <?php if ($rec['tipo'] == 'ingreso'): ?>
                                        <span class="text-amount-success">+MX$<?= number_format($rec['monto'], 2) ?></span>
                                    <?php else: ?>
                                        <span class="text-amount-danger">-MX$<?= number_format($rec['monto'], 2) ?></span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?php if ($rec['estado'] == 'Completado'): ?>
                                        <span class="badge-status badge-completed">Completado</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-pending"><?= esc($rec['estado']) ?></span>
                                    <?php endif; ?>
                                </td>

                                <td class="action-icons">
                                    <i class="bi bi-eye view-payment-btn" data-tooltip="Ver detalles"
                                        data-id="<?= esc($rec['id']) ?>" data-unit="<?= esc($rec['unidad']) ?>"
                                        data-amount-raw="<?= esc($rec['monto']) ?>"
                                        data-amount="<?= $rec['tipo'] == 'ingreso' ? '+' : '-' ?>MX$<?= number_format($rec['monto'], 2) ?>"
                                        data-type="<?= $rec['tipo'] ?>" data-category="<?= esc($rec['categoria']) ?>"
                                        data-category-id="<?= esc($rec['category_id'] ?? '') ?>"
                                        data-method="<?= esc($rec['metodo_pago']) ?>"
                                        data-date="<?= esc($rec['fecha_larga']) ?>"
                                        data-date-raw="<?= esc(date('Y-m-d', strtotime($rec['fecha_raw']))) ?>"
                                        data-desc="<?= esc($rec['descripcion']) ?>"
                                        data-attachment="<?= esc($rec['adjunto'] ?? '') ?>"></i>
                                    <i class="bi bi-pencil-square edit-payment-btn"
                                        data-tooltip="Editar <?= $rec['tipo'] == 'ingreso' ? 'Pago' : 'Gasto' ?>"
                                        data-id="<?= esc($rec['id']) ?>"></i>
                                    <i class="bi bi-trash text-danger delete-payment-btn"
                                        data-tooltip="Eliminar <?= $rec['tipo'] == 'ingreso' ? 'Pago' : 'Gasto' ?>"
                                        data-id="<?= esc($rec['id']) ?>"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4" id="emptyRow">No se encontraron movimientos.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>

                <!-- VISTA AGRUPADA -->
                <tbody id="tbody-agrupada" style="display:none;">
                    <?php if (!empty($groupedRecords)): ?>
                        <?php $gidx = 0;
                        foreach ($groupedRecords as $catName => $group):
                            $gidx++; ?>
                            <tr class="group-header mov-row-group" data-target="group-<?= $gidx ?>"
                                data-cat="<?= htmlspecialchars($catName) ?>" data-type="<?= $group['tipo'] ?>">
                                <td></td>
                                <td colspan="3">
                                    <i class="bi bi-chevron-down group-icon me-2"
                                        style="font-size:0.8rem; transition: transform 0.2s;"></i>
                                    <span class="text-uppercase"><?= esc($catName) ?></span> <span
                                        class="text-muted fw-normal ms-2"
                                        style="font-size:0.75rem;"><?= count($group['records']) ?> transacciones</span>
                                </td>
                                <td colspan="3" class="text-end pe-4">
                                    Subtotal:
                                    <span class="<?= $group['subtotal'] > 0 ? 'text-amount-success' : 'text-amount-danger' ?>">
                                        <?= $group['subtotal'] > 0 ? '+' : '' ?>MX$<?= number_format($group['subtotal'], 2) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php foreach ($group['records'] as $rec): ?>
                                <tr class="group-child group-<?= $gidx ?> mov-row" style="background-color: #ffffff;"
                                    data-desc="<?= htmlspecialchars(strtolower($rec['descripcion'])) ?>"
                                    data-type="<?= $rec['tipo'] ?>" data-cat="<?= htmlspecialchars($rec['categoria']) ?>">
                                    <td><input type="checkbox" class="row-checkbox" data-id="<?= esc($rec['id']) ?>"></td>
                                    <td class="ps-4 text-muted"><i
                                            class="bi bi-arrow-return-right me-2"></i><?= esc($rec['fecha']) ?></td>
                                    <td class="text-uppercase" style="font-size:0.8rem; font-weight:500;">
                                        <?= esc($rec['descripcion']) ?>
                                    </td>
                                    <td class="text-uppercase" style="font-size:0.75rem; color:#64748b;">
                                        <?= esc($rec['categoria']) ?>
                                    </td>
                                    <td>
                                        <?php if ($rec['tipo'] == 'ingreso'): ?>
                                            <span class="text-amount-success">+MX$<?= number_format($rec['monto'], 2) ?></span>
                                        <?php else: ?>
                                            <span class="text-amount-danger">-MX$<?= number_format($rec['monto'], 2) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if ($rec['estado'] == 'Completado'): ?>
                                            <span class="badge-status badge-completed">Completado</span>
                                        <?php else: ?>
                                            <span class="badge-status badge-pending"><?= esc($rec['estado']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="action-icons">
                                        <i class="bi bi-eye view-payment-btn" data-tooltip="Ver detalles"
                                            data-id="<?= esc($rec['id']) ?>" data-unit="<?= esc($rec['unidad']) ?>"
                                            data-amount-raw="<?= esc($rec['monto']) ?>"
                                            data-amount="<?= $rec['tipo'] == 'ingreso' ? '+' : '-' ?>MX$<?= number_format($rec['monto'], 2) ?>"
                                            data-type="<?= $rec['tipo'] ?>" data-category="<?= esc($rec['categoria']) ?>"
                                            data-category-id="<?= esc($rec['category_id'] ?? '') ?>"
                                            data-method="<?= esc($rec['metodo_pago']) ?>"
                                            data-date="<?= esc($rec['fecha_larga']) ?>"
                                            data-date-raw="<?= esc(date('Y-m-d', strtotime($rec['fecha_raw']))) ?>"
                                            data-desc="<?= esc($rec['descripcion']) ?>"
                                            data-attachment="<?= esc($rec['adjunto'] ?? '') ?>"></i>
                                        <i class="bi bi-pencil-square edit-payment-btn"
                                            data-tooltip="Editar <?= $rec['tipo'] == 'ingreso' ? 'Pago' : 'Gasto' ?>"
                                            data-id="<?= esc($rec['id']) ?>"></i>
                                        <i class="bi bi-trash text-danger delete-payment-btn"
                                            data-tooltip="Eliminar <?= $rec['tipo'] == 'ingreso' ? 'Pago' : 'Gasto' ?>"
                                            data-id="<?= esc($rec['id']) ?>"></i>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No se encontraron movimientos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>

            </table>
        </div>

        <!-- Summary Cards -->
        <div class="mov-summaries">
            <div class="summary-card" style="border-color:#dcfce7;">
                <div class="summary-label">Total Ingresos</div>
                <div class="summary-val text-amount-success">+MX$<?= number_format($total_ingresos, 2) ?></div>
            </div>
            <div class="summary-card" style="border-color:#fee2e2;">
                <div class="summary-label">Total Gastos</div>
                <div class="summary-val text-amount-danger">-MX$<?= number_format($total_gastos, 2) ?></div>
            </div>
            <div class="summary-card" style="border-color:#e0f2fe; background:#f0f9ff;">
                <div class="summary-label">Total Neto</div>
                <div class="summary-val text-amount-success">
                    <?= $total_neto >= 0 ? '+' : '' ?>MX$<?= number_format($total_neto, 2) ?>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mov-pagination">
            <div class="pag-left">
                Resultados por página:
                <select id="pageSizeSelect">
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100" selected>100</option>
                    <option value="todos">Todos</option>
                </select>
            </div>
            <div class="pag-right">
                <span id="pagInfo">0-0 de 0</span>
                <div class="pag-arrows">
                    <button id="btnPageFirst"><i class="bi bi-chevron-double-left"></i></button>
                    <button id="btnPagePrev"><i class="bi bi-chevron-left"></i></button>
                    <button id="btnPageNext"><i class="bi bi-chevron-right"></i></button>
                    <button id="btnPageLast"><i class="bi bi-chevron-double-right"></i></button>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Bulk Delete Confirmation Modal -->
<div class="bulk-modal-overlay" id="bulkDeleteModalOverlay">
    <div class="bulk-modal">
        <h3>Eliminar Pagos</h3>
        <p>¿Está seguro de que desea eliminar <strong id="bulkDeleteCount">0</strong> pago(s)? Esta acción no se puede
            deshacer.</p>
        <div class="bulk-modal-footer">
            <button class="btn-cancel" onclick="closeBulkDeleteModal()">Cancelar</button>
            <button class="btn-confirm-delete" id="btnConfirmBulkDelete">Eliminar (<span
                    id="bulkDeleteCountBtn">0</span>)</button>
        </div>
    </div>
</div>

<!-- Toast Notification (for bulk delete confirmation) -->
<div class="mov-toast" id="movToast">
    <div class="toast-icon"><i class="bi bi-check-lg"></i></div>
    <div class="toast-body">
        <div class="toast-title" id="movToastTitle">Éxito</div>
        <div class="toast-msg" id="movToastMsg">Operación completada.</div>
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
                <span id="pd-badge-type"
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
                    <i class="bi bi-graph-up-arrow" id="pd-amount-icon" style="font-size:.8rem;"></i> <span
                        id="pd-amount-txt">MX$5,000.00</span>
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
                <div style="font-weight:600; font-size:.85rem; color:#1e293b; text-transform:capitalize;" id="pd-date">
                    25 de Marzo de 2026</div>
            </div>
        </div>
        <hr style="border:none; border-top:1px solid #e2e8f0; margin:0 0 1.25rem 0;">
        <!-- Descripción -->
        <div style="margin-bottom:1.25rem;">
            <div
                style="display:flex; align-items:center; gap:6px; color:#64748b; font-size:.78rem; font-weight:600; margin-bottom:6px;">
                <i class="bi bi-file-text"></i> Descripción
            </div>
            <div style="font-size:.9rem; color:#334155; text-transform:uppercase;" id="pd-desc">PAGO MANUAL MARZO</div>
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
        <div style="display:flex; justify-content:flex-end; gap:0.5rem;">
            <button type="button" onclick="closePaymentDetail()"
                style="padding:8px 24px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#374151; font-size:.88rem; font-weight:500; cursor:pointer;">Cerrar</button>
            <button type="button"
                style="padding:8px 20px; border:none; border-radius:8px; background:#3F67AC; color:#fff; font-size:.88rem; font-weight:500; cursor:pointer;"
                id="pd-btn-edit">Editar Pago</button>
        </div>
    </div>
</div>

<!-- Modal: Editar Pago -->
<div id="editPaymentOverlay"
    style="display:none; position:fixed; inset:0; z-index:9998; background:rgba(0,0,0,.45); justify-content:center; align-items:center;">
    <div
        style="background:#fff; border-radius:12px; width:650px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); padding:2rem;">

        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h3 style="margin:0; font-size:1.15rem; font-weight:600; color:#1e293b;">Editar Pago</h3>
            <button type="button" onclick="closeEditPayment()"
                style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.1rem;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="editPaymentForm">
            <input type="hidden" id="ep-id">

            <!-- Información Básica -->
            <div style="font-size:0.85rem; font-weight:600; color:#334155; margin-bottom:0.75rem;">Información Básica
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.5rem;">
                <div>
                    <label style="font-size:0.8rem; color:#3F67AC; margin-bottom:0.4rem; display:block;">Tipo</label>
                    <select id="ep-tipo" class="ep-select" disabled>
                        <option value="ingreso">INGRESO</option>
                        <option value="egreso">GASTO</option>
                    </select>
                </div>
                <div style="position:relative;">
                    <label
                        style="font-size:0.8rem; color:#3F67AC; margin-bottom:0.4rem; display:block;">Categoría</label>
                    <input type="hidden" id="ep-categoria">
                    <div class="ep-custom-select" id="epCatTrigger" onclick="toggleEpCat()">
                        <span id="epCatIcon"><i class="bi bi-tag"></i></span>
                        <span id="epCatLabel"
                            style="flex:1; margin-left:8px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Seleccionar
                            categoría</span>
                        <i class="bi bi-chevron-down ep-custom-chevron"></i>
                    </div>
                    <div class="ep-custom-dropdown" id="epCatDropdown">
                        <div class="ep-dropdown-header" id="epCatDropdownHeader">Categorías de Gastos</div>
                        <div id="epCatList">
                            <!-- Populated dynamically via JS -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monto y Detalles de Pago -->
            <div style="font-size:0.85rem; font-weight:600; color:#334155; margin-bottom:0.75rem;">Monto y Detalles de
                Pago</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.5rem;">
                <div>
                    <label style="font-size:0.8rem; color:#3F67AC; margin-bottom:0.4rem; display:block;">Monto</label>
                    <div style="position:relative;">
                        <span
                            style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#64748b; font-size:0.9rem;">$</span>
                        <input type="number" id="ep-monto" class="ep-input" style="padding-left:1.75rem;" step="0.01">
                    </div>
                </div>
                <div>
                    <label style="font-size:0.8rem; color:#3F67AC; margin-bottom:0.4rem; display:block;">Método de
                        Pago</label>
                    <select id="ep-metodo" class="ep-select">
                        <option value="transferencia">TRANSFERENCIA BANCARIA</option>
                        <option value="efectivo">EFECTIVO</option>
                        <option value="cheque">CHEQUE</option>
                        <option value="stripe">STRIPE</option>
                        <option value="N/A">N/A</option>
                    </select>
                </div>
            </div>

            <!-- Fecha de Pago -->
            <div style="font-size:0.85rem; font-weight:600; color:#334155; margin-bottom:0.75rem;">Fecha de Pago</div>
            <div style="margin-bottom:1.5rem;">
                <div style="position:relative; width:50%;">
                    <span
                        style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#64748b;"><i
                            class="bi bi-calendar3"></i></span>
                    <input type="text" id="ep-fecha" class="ep-input bg-white"
                        style="padding-left:2.25rem; cursor:pointer;" readonly placeholder="Seleccionar Fecha">
                </div>
            </div>

            <!-- Descripción -->
            <div style="font-size:0.85rem; font-weight:600; color:#334155; margin-bottom:0.75rem;">Descripción</div>
            <div style="margin-bottom:1.5rem;">
                <textarea id="ep-descripcion" class="ep-input" rows="2"></textarea>
            </div>

            <!-- Adjuntos -->
            <div style="font-size:0.85rem; font-weight:600; color:#334155; margin-bottom:0.75rem;">Adjuntos</div>
            <div style="margin-bottom:2rem;">
                <input type="file" id="ep-adjunto-input" style="display:none;" accept="image/*,.pdf">
                <button type="button" class="ep-btn-adjunto"
                    onclick="document.getElementById('ep-adjunto-input').click()">
                    <i class="bi bi-file-image"></i> Agregar Recibo o Factura
                </button>
                <div id="ep-adjunto-preview" style="margin-top:0.75rem; font-size:0.8rem; color:#64748b;"></div>
            </div>

            <!-- Footer Buttons -->
            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" onclick="closeEditPayment()" class="ep-btn-cancel">Cancelar</button>
                <button type="button" class="ep-btn-delete" id="ep-btn-delete-action">Eliminar</button>
                <button type="button" class="ep-btn-save" id="ep-btn-save-action">Guardar</button>
            </div>
        </form>
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
        style="max-width:90vw; max-height:80vh; border-radius:8px; object-fit:contain; display:none;" />
    <iframe id="lightboxPdf" src=""
        style="width:90vw; height:80vh; border-radius:8px; border:none; display:none;"></iframe>
    <div style="color:#94a3b8; font-size:.8rem; margin-top:12px;">Desplaza para ampliar, arrastra para mover</div>
</div>

<style>
    .pd-thumb {
        width: 120px;
        height: 90px;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        position: relative;
        transition: border-color 0.2s;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .pd-thumb:hover {
        border-color: #3b82f6;
    }

    .pd-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .pd-thumb i.pdf-icon {
        font-size: 2.5rem;
        color: #ef4444;
    }

    .pd-filename {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.6);
        color: white;
        font-size: 0.65rem;
        padding: 2px 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .ep-select,
    .ep-input {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #334155;
        outline: none;
        transition: border-color 0.2s;
        background: #fff;
        box-sizing: border-box;
        font-family: inherit;
    }

    .ep-select:focus,
    .ep-input:focus {
        border-color: #3b82f6;
    }

    .ep-select:disabled {
        background: #f8fafc;
        color: #94a3b8;
    }

    /* Custom Premium Dropdown */
    .ep-custom-select {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #334155;
        background: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        box-sizing: border-box;
        transition: all 0.2s;
    }

    .ep-custom-select:hover {
        border-color: #94a3b8;
    }

    .ep-custom-select.active {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .ep-custom-chevron {
        margin-left: auto;
        color: #94a3b8;
        font-size: 0.8rem;
        transition: transform 0.2s;
    }

    .ep-custom-select.active .ep-custom-chevron {
        transform: rotate(180deg);
    }

    .ep-custom-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: none;
        overflow: hidden;
        transform: translateY(-5px);
        opacity: 0;
        transition: all 0.2s;
        min-width: 100%;
    }

    .ep-custom-dropdown.open {
        display: block;
        transform: translateY(0);
        opacity: 1;
    }

    .ep-dropdown-header {
        background: #f8fafc;
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #3F67AC;
        border-bottom: 1px solid #e2e8f0;
    }

    .ep-cat-option {
        padding: 0.6rem 1rem;
        cursor: pointer;
        font-size: 0.85rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: background 0.2s;
    }

    .ep-cat-option:hover {
        background: #f1f5f9;
    }

    .ep-cat-option.selected {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 500;
    }

    .ep-cat-option i {
        font-size: 0.95rem;
        opacity: 0.7;
    }

    .ep-cat-option.selected i {
        opacity: 1;
        color: #3b82f6;
    }

    .ep-cat-option-check {
        margin-left: auto;
        opacity: 0;
        color: #3b82f6;
        font-size: 1rem;
        transition: opacity 0.2s;
    }

    .ep-cat-option.selected .ep-cat-option-check {
        opacity: 1;
    }

    .ep-btn-adjunto {
        background: transparent;
        border: 1px solid #e2e8f0;
        color: #3F67AC;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .ep-btn-adjunto:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .ep-btn-cancel {
        padding: 0.55rem 1.25rem;
        background: #fff;
        border: 1px solid #cbd5e1;
        color: #334155;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .ep-btn-delete {
        padding: 0.55rem 1.25rem;
        background: #ef4444;
        border: none;
        color: #fff;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .ep-btn-delete:hover {
        background: #dc2626;
    }

    .ep-btn-save {
        padding: 0.55rem 1.5rem;
        background: #3F67AC;
        border: none;
        color: #fff;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .ep-btn-save:hover {
        background: #334155;
    }
</style>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicializar Selector de Mes
        flatpickr("#monthPicker", {
            plugins: [
                new monthSelectPlugin({
                    shorthand: true, // defaults to false
                    dateFormat: "Y-m", // defaults to "F Y"
                    altFormat: "F Y", // defaults to "F Y"
                    theme: "light" // defaults to "light"
                })
            ],
            locale: "es",
            altInput: true,
            onChange: function (selectedDates, dateStr, instance) {
                if (dateStr) {
                    window.location.href = '?month=' + dateStr;
                }
            }
        });

        const currentSelectedMonth = '<?= esc($selectedMonth) ?>'; // format: YYYY-MM

        document.getElementById('btnPrevMonth').addEventListener('click', function () {
            if (!currentSelectedMonth) return;
            let parts = currentSelectedMonth.split('-');
            let date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1 - 1, 1);
            let prevMonth = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
            window.location.href = '?month=' + prevMonth;
        });

        document.getElementById('btnNextMonth').addEventListener('click', function () {
            if (!currentSelectedMonth) return;
            let parts = currentSelectedMonth.split('-');
            let date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1 + 1, 1);
            let nextMonth = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
            window.location.href = '?month=' + nextMonth;
        });

        // Toggle View (Lista / Agrupada)
        const viewFilter = document.getElementById('viewFilter');
        const viewLista = document.getElementById('tbody-lista');
        const viewAgrupada = document.getElementById('tbody-agrupada');

        viewFilter.addEventListener('change', function () {
            if (this.value === 'lista') {
                viewLista.style.display = 'table-row-group';
                viewAgrupada.style.display = 'none';
            } else {
                viewLista.style.display = 'none';
                viewAgrupada.style.display = 'table-row-group';
            }
        });

        // Filtrado Frontend
        const searchInput = document.getElementById('searchInput');
        const typeFilter = document.getElementById('typeFilter');
        const categoryFilter = document.getElementById('categoryFilter');

        function applyFilters() {
            const term = searchInput.value.toLowerCase();
            const type = typeFilter.value;
            const cat = categoryFilter.value;

            // Filtrar Vista Lista
            document.querySelectorAll('#tbody-lista .mov-row').forEach(row => {
                const rDesc = row.dataset.desc;
                const rType = row.dataset.type;
                const rCat = row.dataset.cat;

                let visible = true;
                if (term && !rDesc.includes(term)) visible = false;
                if (type !== 'todos' && rType !== type) visible = false;
                if (cat !== 'todos' && rCat !== cat) visible = false;

                row.style.display = visible ? 'table-row' : 'none';
            });

            // Filtrar Vista Agrupada
            document.querySelectorAll('.group-header').forEach(header => {
                const hType = header.dataset.type;
                const hCat = header.dataset.cat;
                const targetClass = header.dataset.target;

                // Ver si el header pasa el filtro
                let headerVisible = true;
                if (type !== 'todos' && hType !== type) headerVisible = false;
                if (cat !== 'todos' && hCat !== cat) headerVisible = false;

                let hasVisibleChildren = false;

                document.querySelectorAll('.' + targetClass).forEach(child => {
                    const cDesc = child.dataset.desc;
                    let childVisible = headerVisible; // Heredar inicialmente

                    if (term && !cDesc.includes(term)) childVisible = false;

                    child.style.display = childVisible ? 'table-row' : 'none';
                    if (childVisible) hasVisibleChildren = true;
                });

                // Ocultar cabecera si no hay hijos visibles
                header.style.display = hasVisibleChildren ? 'table-row' : 'none';
            });

            // Resetea paginación al filtrar
            currentPage = 1;
            applyPagination();
        }

        /* Variables de Paginación */
        let currentPage = 1;
        let pageSize = 100;

        function applyPagination() {
            if (viewFilter.value === 'agrupada') {
                document.querySelector('.mov-pagination').style.display = 'none';
                return;
            } else {
                document.querySelector('.mov-pagination').style.display = 'flex';
            }

            const rows = Array.from(document.querySelectorAll('#tbody-lista .mov-row'));
            let visibleRows = [];

            const term = searchInput.value.toLowerCase();
            const type = typeFilter.value;
            const cat = categoryFilter.value;

            // Recopilar filas que coinciden con el filtro
            rows.forEach(row => {
                const rDesc = row.dataset.desc;
                const rType = row.dataset.type;
                const rCat = row.dataset.cat;
                let visible = true;
                if (term && !rDesc.includes(term)) visible = false;
                if (type !== 'todos' && rType !== type) visible = false;
                if (cat !== 'todos' && rCat !== cat) visible = false;

                if (visible) {
                    visibleRows.push(row);
                } else {
                    row.style.display = 'none';
                }
            });

            const totalRows = visibleRows.length;
            if (pageSize === 'todos' || pageSize >= totalRows) {
                // Muestra todo lo filtrado
                visibleRows.forEach(r => r.style.display = 'table-row');
                document.getElementById('pagInfo').textContent = `1-${totalRows} de ${totalRows}`;
                currentPage = 1;
                togglePagButtons(1, 1);
                return;
            }

            const totalPages = Math.ceil(totalRows / pageSize);
            if (currentPage > totalPages) currentPage = totalPages;
            if (currentPage < 1) currentPage = 1;

            const startIdx = (currentPage - 1) * pageSize;
            const endIdx = Math.min(startIdx + pageSize, totalRows);

            visibleRows.forEach((r, idx) => {
                if (idx >= startIdx && idx < endIdx) {
                    r.style.display = 'table-row';
                } else {
                    r.style.display = 'none';
                }
            });

            document.getElementById('pagInfo').textContent = `${totalRows > 0 ? startIdx + 1 : 0}-${endIdx} de ${totalRows}`;
            togglePagButtons(currentPage, totalPages);
        }

        function togglePagButtons(current, total) {
            document.getElementById('btnPageFirst').disabled = current <= 1;
            document.getElementById('btnPagePrev').disabled = current <= 1;
            document.getElementById('btnPageNext').disabled = current >= total;
            document.getElementById('btnPageLast').disabled = current >= total;
        }

        // Listener Paginación
        document.getElementById('pageSizeSelect').addEventListener('change', function() {
            pageSize = this.value === 'todos' ? 'todos' : parseInt(this.value);
            currentPage = 1;
            applyPagination();
        });

        document.getElementById('btnPageFirst').addEventListener('click', () => { currentPage = 1; applyPagination(); });
        document.getElementById('btnPagePrev').addEventListener('click', () => { if(currentPage > 1) { currentPage--; applyPagination(); }});
        document.getElementById('btnPageNext').addEventListener('click', () => { 
            const rows = document.querySelectorAll('#tbody-lista .mov-row').length; 
            const total = Math.ceil(rows/parseInt(pageSize));
            if(currentPage < total) { currentPage++; applyPagination(); }
        });
        document.getElementById('btnPageLast').addEventListener('click', () => { 
            const rows = document.querySelectorAll('#tbody-lista .mov-row').length; 
            currentPage = Math.ceil(rows/parseInt(pageSize));
            applyPagination();
        });

        searchInput.addEventListener('keyup', applyFilters);
        typeFilter.addEventListener('change', applyFilters);
        categoryFilter.addEventListener('change', applyFilters);
        viewFilter.addEventListener('change', () => {
            applyPagination(); // Update UI state on view change
        });

        // Aplicar paginación al cargar
        applyPagination();

        // Colapsar / Expandir Grupos
        document.querySelectorAll('.group-header').forEach(header => {
            header.addEventListener('click', function () {
                const target = this.dataset.target;
                const icon = this.querySelector('.group-icon');
                const isHidden = icon.style.transform === 'rotate(-90deg)';

                document.querySelectorAll('.' + target).forEach(child => {
                    // Si aplicamos collapse, ocultamos de verdad. Pero si lo mostramos, revisamos los filtros primero.
                    if (!isHidden) {
                        child.classList.add('hidden');
                    } else {
                        child.classList.remove('hidden');
                    }
                });

                if (!isHidden) {
                    icon.style.transform = 'rotate(-90deg)';
                } else {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        });

        // Eventos del Modal de Detalles
        document.querySelectorAll('.view-payment-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                window.currentPaymentData = btn.dataset; // Store for Edit usage
                document.getElementById('pd-unit').textContent = btn.dataset.unit;

                const type = btn.dataset.type;
                const amtTxt = btn.dataset.amount;

                const badge = document.getElementById('pd-badge-type');
                const amtEl = document.getElementById('pd-amount');

                if (type === 'ingreso') {
                    badge.innerHTML = '<i class="bi bi-graph-up-arrow" style="font-size:.7rem;"></i> Ingreso';
                    badge.style.background = '#ecfdf5'; badge.style.color = '#059669';
                    amtEl.innerHTML = '<i class="bi bi-graph-up-arrow" style="font-size:.8rem;"></i> <span>' + amtTxt + '</span>';
                    amtEl.style.color = '#059669';
                } else {
                    badge.innerHTML = '<i class="bi bi-graph-down-arrow" style="font-size:.7rem;"></i> Gasto';
                    badge.style.background = '#fef2f2'; badge.style.color = '#ef4444';
                    amtEl.innerHTML = '<i class="bi bi-graph-down-arrow" style="font-size:.8rem;"></i> <span>' + amtTxt + '</span>';
                    amtEl.style.color = '#ef4444';
                }

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

                    // Contenedor flex con scroll horizontal
                    adjuntosContainer.style.display = 'flex';
                    adjuntosContainer.style.overflowX = 'auto';
                    adjuntosContainer.style.gap = '12px';
                    adjuntosContainer.style.paddingBottom = '8px';

                    attachments.forEach(att => {
                        const fileExt = att.split('.').pop().toLowerCase();
                        const isPdf = fileExt === 'pdf';
                        const cleanAtt = att.replace('financial/', '');
                        const attUrl = '<?= base_url('admin/finanzas/archivo/financial/') ?>' + cleanAtt;

                        const thumb = document.createElement('div');
                        thumb.className = 'pd-thumb';
                        thumb.style.flexShrink = '0'; // Evitar encogimiento en contenedor flex
                        thumb.style.textAlign = 'center';

                        if (isPdf) {
                            thumb.innerHTML = `
                                <div style="height:80px; display:flex; align-items:center; justify-content:center; background:#f8fafc;">
                                    <i class="bi bi-file-earmark-pdf" style="font-size:2.5rem; color:#ef4444;"></i>
                                </div>
                                <span class="pd-filename">${cleanAtt}</span>
                            `;
                            thumb.addEventListener('click', () => window.open(attUrl, '_blank'));
                        } else {
                            thumb.innerHTML = `<img src="${attUrl}" alt="Comprobante" /><span class="pd-filename">${cleanAtt}</span>`;
                            thumb.addEventListener('click', () => openLightboxImg(attUrl));
                        }
                        adjuntosContainer.appendChild(thumb);
                    });
                } else {
                    adjuntosSection.style.display = 'none';
                }

                document.getElementById('paymentDetailOverlay').style.display = 'flex';
            });
        });

        const epDatePicker = flatpickr("#ep-fecha", {
            dateFormat: "Y-m-d",
            altInput: true,
            altFormat: "d \\d\\e F \\d\\e Y",
            locale: "es",
            theme: "light"
        });

        document.getElementById('pd-btn-edit').addEventListener('click', () => {
            openEditPayment(window.currentPaymentData);
            closePaymentDetail();
        });

        document.querySelectorAll('.edit-payment-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const tr = this.closest('tr');
                const viewBtn = tr.querySelector('.view-payment-btn');
                if (viewBtn) openEditPayment(viewBtn.dataset);
            });
        });

        document.querySelectorAll('.delete-payment-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const tr = this.closest('tr');
                const viewBtn = tr.querySelector('.view-payment-btn');
                if (viewBtn) triggerDeletePayment(viewBtn.dataset.id);
            });
        });

        function triggerDeletePayment(id) {
            closeEditPayment();
            closePaymentDetail();
            // ...
            Swal.fire({
                title: '<span style="font-size:1.15rem; font-weight:600; color:#1e293b;">Eliminar Pago</span>',
                html: '<div style="color:#3F67AC; font-size:0.95rem; text-align:left; margin-bottom:0.5rem;">¿Está seguro de que desea eliminar este pago? Esta acción no se puede deshacer.</div>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#fff',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'ep-btn-delete',
                    cancelButton: 'ep-btn-cancel me-3'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    executeDelete(id);
                }
            });
        }

        function executeDelete(id) {
            fetch('<?= base_url('admin/finanzas/transaccion/delete') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                },
                body: new URLSearchParams({ id: id, '<?= csrf_token() ?>': '<?= csrf_hash() ?>' })
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Eliminado', 'La transacción fue eliminada con éxito.', 'success').then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Error al eliminar.', 'error');
                    }
                });
        }

        document.getElementById('ep-btn-delete-action').addEventListener('click', () => {
            triggerDeletePayment(document.getElementById('ep-id').value);
        });

        document.getElementById('ep-btn-save-action').addEventListener('click', () => {
            const id = document.getElementById('ep-id').value;
            const amount = document.getElementById('ep-monto').value;
            const date = document.getElementById('ep-fecha').value;
            const cat = document.getElementById('ep-categoria').value;
            const desc = document.getElementById('ep-descripcion').value;
            const method = document.getElementById('ep-metodo').value;
            const fileInput = document.getElementById('ep-adjunto-input');

            closeEditPayment();
            closePaymentDetail();

            Swal.fire({
                title: 'Guardando...',
                text: 'Actualizando transacción',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData();
            formData.append('id', id);
            formData.append('amount', amount);
            formData.append('due_date', date);
            formData.append('category_id', cat);
            formData.append('description', desc);
            formData.append('payment_method', method);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            if (fileInput.files && fileInput.files.length > 0) {
                formData.append('attachment', fileInput.files[0]);
            }

            fetch('<?= base_url('admin/finanzas/transaccion/update') ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
                .then(r => r.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('Guardado', 'La transacción fue actualizada.', 'success').then(() => window.location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Error al actualizar.', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Ocurrió un error al contactar al servidor.', 'error');
                });
        });

        window.openEditPayment = function (data) {
            document.getElementById('ep-id').value = data.id;
            document.getElementById('ep-tipo').value = data.type;
            document.getElementById('ep-monto').value = data.amountRaw;

            // Build the Custom Dropdown List based on data.type (ingreso/egreso)
            const type = data.type; // "ingreso" or "egreso"
            const dbType = (type === 'egreso') ? 'expense' : 'income';
            buildCategoryDropdown(dbType);

            // Set current category
            if (data.categoryId) {
                setEpCat(data.categoryId, data.category);
            } else {
                setEpCat('', 'Seleccionar categoría');
            }

            // map method
            let methodVal = 'transferencia';
            const rawMethod = (data.method || '').toLowerCase();
            if (rawMethod.includes('efectivo')) methodVal = 'efectivo';
            if (rawMethod.includes('cheque')) methodVal = 'cheque';
            if (rawMethod.includes('stripe')) methodVal = 'stripe';
            if (rawMethod === 'n/a' || !rawMethod) methodVal = 'N/A';
            document.getElementById('ep-metodo').value = methodVal;

            epDatePicker.setDate(data.dateRaw);
            document.getElementById('ep-descripcion').value = data.desc;

            // Reset file inputs
            document.getElementById('ep-adjunto-input').value = '';
            document.getElementById('ep-adjunto-preview').textContent = '';

            document.getElementById('editPaymentOverlay').style.display = 'flex';
        };

        window.closeEditPayment = function () {
            document.getElementById('editPaymentOverlay').style.display = 'none';
        }

        document.getElementById('ep-adjunto-input').addEventListener('change', function () {
            const preview = document.getElementById('ep-adjunto-preview');
            if (this.files && this.files.length > 0) {
                preview.textContent = 'Archivo seleccionado: ' + this.files[0].name;
                preview.style.color = '#10b981';
            } else {
                preview.textContent = '';
            }
        });

        // --- Custom Category Dropdown Variables & JS ---
        const sysCategories = [
            <?php foreach ($categories as $c): ?>
                                                                                    {
                    id: "<?= esc($c['id']) ?>",
                    name: "<?= esc($c['name']) ?>",
                    type: "<?= esc($c['type'] ?? '') ?>"
                },
            <?php endforeach; ?>
        ];

        const catIcons = {
            'Salario del Personal': 'bi-people',
            'Mantenimiento y Reparaciones': 'bi-wrench',
            'Mantenimiento': 'bi-wrench',
            'Servicios Públicos': 'bi-lightning',
            'Suministros': 'bi-box',
            'Servicios Profesionales': 'bi-briefcase',
            'Servicios': 'bi-briefcase',
            'Seguro': 'bi-shield',
            'Otro Gasto': 'bi-graph-down-arrow',
            'Cuota de Mantenimiento': 'bi-currency-dollar',
            'Cargo por Mora': 'bi-hourglass-split',
            'Cargo de Reserva de Amenidad': 'bi-calendar2-event',
            'Multa de Amenidad': 'bi-exclamation-triangle',
            'Multa de Estacionamiento': 'bi-p-square',
            'Multa de Mascota': 'bi-emoji-heart-eyes',
            'Multa por Infracción': 'bi-shield-exclamation',
            'Otro Ingreso': 'bi-file-earmark-text'
        };

        function getIconForCat(name) {
            return catIcons[name] || 'bi-tag';
        }

        function buildCategoryDropdown(type) {
            const listEl = document.getElementById('epCatList');
            const headerEl = document.getElementById('epCatDropdownHeader');
            listEl.innerHTML = '';

            if (type === 'expense') {
                headerEl.textContent = 'Categorías de Gastos';
            } else {
                headerEl.textContent = 'Categorías de Ingresos';
            }

            const filtered = sysCategories.filter(c => c.type === type);
            filtered.forEach(c => {
                const icon = getIconForCat(c.name);
                const el = document.createElement('div');
                el.className = 'ep-cat-option';
                el.dataset.id = c.id;
                el.dataset.name = c.name;
                el.innerHTML = `
                    <i class="bi ${icon}"></i>
                    <span>${c.name}</span>
                    <i class="bi bi-check2 ep-cat-option-check"></i>
                `;
                el.addEventListener('click', () => {
                    setEpCat(c.id, c.name);
                    closeEpCatDropdown();
                });
                listEl.appendChild(el);
            });
        }

        window.toggleEpCat = function () {
            const db = document.getElementById('epCatDropdown');
            const trigger = document.getElementById('epCatTrigger');
            if (db.classList.contains('open')) {
                closeEpCatDropdown();
            } else {
                db.classList.add('open');
                trigger.classList.add('active');
            }
        };

        window.closeEpCatDropdown = function () {
            const db = document.getElementById('epCatDropdown');
            const trigger = document.getElementById('epCatTrigger');
            if (db) db.classList.remove('open');
            if (trigger) trigger.classList.remove('active');
        };

        function setEpCat(id, name) {
            document.getElementById('ep-categoria').value = id;
            if (!id) {
                document.getElementById('epCatLabel').textContent = 'Seleccionar categoría';
                document.getElementById('epCatIcon').innerHTML = '<i class="bi bi-tag"></i>';
                return;
            }

            document.getElementById('epCatLabel').textContent = name;
            const icon = getIconForCat(name);
            document.getElementById('epCatIcon').innerHTML = `<i class="bi ${icon}"></i>`;

            // update selection state
            document.querySelectorAll('#epCatList .ep-cat-option').forEach(el => {
                if (el.dataset.id === String(id)) el.classList.add('selected');
                else el.classList.remove('selected');
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#epCatDropdown') && !e.target.closest('#epCatTrigger')) {
                closeEpCatDropdown();
            }
        });

    });

    function closePaymentDetail() {
        document.getElementById('paymentDetailOverlay').style.display = 'none';
    }

    function openLightboxImg(src) {
        document.getElementById('lightboxPdf').style.display = 'none';
        document.getElementById('lightboxImg').src = src;
        document.getElementById('lightboxImg').style.display = 'block';
        document.getElementById('lightboxOverlay').style.display = 'flex';
    }

    function openLightboxPdf(src) {
        document.getElementById('lightboxImg').style.display = 'none';
        document.getElementById('lightboxPdf').src = src;
        document.getElementById('lightboxPdf').style.display = 'block';
        document.getElementById('lightboxOverlay').style.display = 'flex';
    }

    function closeLightbox() {
        document.getElementById('lightboxOverlay').style.display = 'none';
        document.getElementById('lightboxImg').src = '';
        document.getElementById('lightboxPdf').src = '';
    }

    function descargarReporte() {
        let month = document.getElementById('monthPicker').value;
        if (!month) {
            month = '<?= esc($selectedMonth) ?>';
        }
        window.open('<?= base_url('admin/finanzas/reporte-mensual') ?>?month=' + month, '_blank');
    }

    // ═══════════════════════════════════════════
    // ═══ BULK DELETE (Selección Múltiple) ═══
    // ═══════════════════════════════════════════

    const selectAllCb = document.getElementById('selectAllCheckbox');
    const btnBulkDelete = document.getElementById('btnBulkDelete');
    const bulkCountEl = document.getElementById('bulkCount');

    function getVisibleCheckboxes() {
        // Get only visible (not hidden by filters) row checkboxes
        // Also exclude checkboxes inside hidden tbody (e.g. tbody-agrupada when lista is active)
        const allCbs = document.querySelectorAll('.row-checkbox');
        return Array.from(allCbs).filter(cb => {
            const row = cb.closest('tr');
            const tbody = cb.closest('tbody');
            if (!row || row.style.display === 'none' || row.classList.contains('hidden')) return false;
            if (tbody && tbody.style.display === 'none') return false;
            return true;
        });
    }

    function getSelectedIds() {
        const ids = [];
        document.querySelectorAll('.row-checkbox:checked').forEach(cb => {
            const tbody = cb.closest('tbody');
            if (tbody && tbody.style.display === 'none') return;
            const id = cb.dataset.id;
            if (id) ids.push(id);
        });
        return [...new Set(ids)]; // Deduplicate in case same ID appears in both views
    }

    function updateBulkUI() {
        const ids = getSelectedIds();
        const count = ids.length;

        if (count > 0) {
            btnBulkDelete.style.display = 'inline-flex';
            bulkCountEl.textContent = count;
        } else {
            btnBulkDelete.style.display = 'none';
        }

        // Update select-all checkbox state
        const visible = getVisibleCheckboxes();
        const checkedCount = visible.filter(cb => cb.checked).length;
        if (visible.length > 0 && checkedCount === visible.length) {
            selectAllCb.checked = true;
            selectAllCb.indeterminate = false;
        } else if (checkedCount > 0) {
            selectAllCb.checked = false;
            selectAllCb.indeterminate = true;
        } else {
            selectAllCb.checked = false;
            selectAllCb.indeterminate = false;
        }

        // Update row highlight
        document.querySelectorAll('.row-checkbox').forEach(cb => {
            const row = cb.closest('tr');
            if (row) {
                if (cb.checked) row.classList.add('row-selected');
                else row.classList.remove('row-selected');
            }
        });
    }

    // Select All checkbox
    selectAllCb.addEventListener('change', function () {
        const checked = this.checked;
        getVisibleCheckboxes().forEach(cb => {
            cb.checked = checked;
        });
        updateBulkUI();
    });

    // Individual checkboxes via event delegation
    document.addEventListener('change', function (e) {
        if (e.target.classList.contains('row-checkbox')) {
            updateBulkUI();
        }
    });

    // Open confirmation modal
    function openBulkDeleteModal() {
        const count = getSelectedIds().length;
        if (count === 0) return;

        document.getElementById('bulkDeleteCount').textContent = count;
        document.getElementById('bulkDeleteCountBtn').textContent = count;

        const overlay = document.getElementById('bulkDeleteModalOverlay');
        overlay.style.display = 'flex';
        // Force reflow for animation
        void overlay.offsetWidth;
        overlay.classList.add('open');
    }

    function closeBulkDeleteModal() {
        const overlay = document.getElementById('bulkDeleteModalOverlay');
        overlay.classList.remove('open');
        setTimeout(() => { overlay.style.display = 'none'; }, 200);
    }

    // Confirm bulk delete
    document.getElementById('btnConfirmBulkDelete').addEventListener('click', function () {
        const ids = getSelectedIds();
        if (ids.length === 0) return;

        closeBulkDeleteModal();

        // Show loading state on button
        btnBulkDelete.disabled = true;
        btnBulkDelete.innerHTML = '<i class="bi bi-arrow-repeat" style="animation: spin 0.8s linear infinite;"></i> Eliminando...';

        fetch('<?= base_url('admin/finanzas/transaccion/bulk-delete') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            },
            body: new URLSearchParams({
                ids: JSON.stringify(ids),
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            })
        })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    showMovToast('Registros eliminados', data.message || `${ids.length} transacción(es) eliminada(s).`, 'success');
                    // Remove rows from DOM with animation
                    ids.forEach(id => {
                        const cb = document.querySelector(`.row-checkbox[data-id="${id}"]`);
                        if (cb) {
                            const row = cb.closest('tr');
                            if (row) {
                                row.style.transition = 'opacity 0.3s, transform 0.3s';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(-20px)';
                                setTimeout(() => row.remove(), 300);
                            }
                        }
                    });
                    // After animation, reload to update summaries
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showMovToast('Error', data.message || 'Error al eliminar.', 'error');
                    btnBulkDelete.disabled = false;
                    updateBulkUI();
                }
            })
            .catch(() => {
                showMovToast('Error', 'Problema de conexión.', 'error');
                btnBulkDelete.disabled = false;
                updateBulkUI();
            });
    });

    // Toast Notification System
    function showMovToast(title, msg, type) {
        const toast = document.getElementById('movToast');
        const icon = toast.querySelector('.toast-icon i');

        document.getElementById('movToastTitle').textContent = title;
        document.getElementById('movToastMsg').textContent = msg;

        if (type === 'error') {
            toast.classList.add('toast-error');
            icon.className = 'bi bi-x-lg';
        } else {
            toast.classList.remove('toast-error');
            icon.className = 'bi bi-check-lg';
        }

        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    }

    // Add spin keyframe
    if (!document.getElementById('spinKeyframe')) {
        const style = document.createElement('style');
        style.id = 'spinKeyframe';
        style.textContent = '@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    }

</script>

<?= $this->endSection() ?>