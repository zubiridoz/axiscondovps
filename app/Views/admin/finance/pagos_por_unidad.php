<?= $this->extend('layout/main') ?>
<?= $this->section('content') ?>
<style>
    :root {
        --fin-bg: #f8fafc;
        --fin-card-bg: #ffffff;
        --fin-text-main: #1e293b;
        --fin-text-muted: #64748b;
        --fin-border: #e2e8f0;
        --fin-primary: #232d3f;
        --fin-success: #10b981;
        --fin-danger: #ef4444;
        --fin-warning: #f59e0b;
        --fin-br: 6px;
        --fin-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
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

    .btn-config {
        background: rgba(255, 255, 255, .1);
        border: 1px solid rgba(255, 255, 255, .2);
        color: white;
        padding: .5rem 1rem;
        border-radius: var(--fin-br);
        font-size: .85rem;
        cursor: pointer;
        transition: background .2s;
    }

    .btn-config:hover {
        background: rgba(255, 255, 255, .2);
    }

    .ppu-toolbar {
        background: var(--fin-card-bg);
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-br);
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        box-shadow: var(--fin-shadow-sm);
    }

    .toolbar-left {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .toolbar-control {
        height: 38px;
        padding: 0 .75rem;
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-br);
        font-size: .85rem;
        color: var(--fin-text-main);
        background: white;
    }

    .toolbar-control:focus {
        outline: none;
        border-color: #3b82f6;
    }

    .input-search {
        min-width: 250px;
        padding-left: 2.2rem !important;
    }

    .filter-count {
        font-size: .8rem;
        color: var(--fin-text-muted);
    }

    .btn-primary-ppu {
        background: #334155;
        border: none;
        color: white;
        padding: 0 1rem;
        height: 38px;
        border-radius: var(--fin-br);
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: .4rem;
        transition: background .2s;
    }

    .btn-primary-ppu:hover {
        background: #1e293b;
    }

    .ppu-content-box {
        background: var(--fin-card-bg);
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-br);
        box-shadow: var(--fin-shadow-sm);
        overflow: hidden;
        margin-bottom: 2rem;
    }

    .ppu-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 900px;
    }

    .ppu-table th,
    .ppu-table td {
        padding: .9rem 1.25rem;
        text-align: left;
        font-size: .85rem;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }

    .ppu-table th {
        color: var(--fin-text-muted);
        font-weight: 500;
        background: #f8fafc;
    }

    .ppu-table td {
        color: var(--fin-text-main);
        vertical-align: middle;
    }

    .ppu-table tbody tr {
        cursor: pointer;
        transition: background .15s;
    }

    .ppu-table tbody tr:hover {
        background: #f0f7ff;
    }

    .unit-link {
        color: #3b82f6;
        font-weight: 600;
        font-size: .85rem;
        text-decoration: none;
    }

    .unit-link:hover {
        text-decoration: underline;
    }

    .badge-status {
        padding: .25rem .6rem;
        border-radius: 12px;
        font-size: .72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: .3rem;
    }

    .badge-ok {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-moroso {
        background: #fee2e2;
        color: #991b1b;
    }

    .text-danger-bold {
        color: var(--fin-danger);
        font-weight: 600;
    }

    .ppu-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .85rem 1.5rem;
        border-top: 1px solid var(--fin-border);
    }

    .pag-left select {
        padding: .2rem;
        border: 1px solid var(--fin-border);
        border-radius: 4px;
        font-size: .8rem;
    }

    .pag-right {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: .8rem;
        color: var(--fin-text-muted);
    }

    .pag-arrows {
        display: flex;
        gap: .5rem;
    }

    .pag-arrows button {
        background: transparent;
        border: none;
        color: var(--fin-text-muted);
        cursor: pointer;
        padding: .2rem .4rem;
        border-radius: 4px;
    }

    .pag-arrows button:hover {
        background: #f1f5f9;
    }

    .date-picker-group {
        display: inline-flex;
        align-items: center;
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-br);
        overflow: hidden;
        height: 38px;
    }

    .date-picker-btn {
        background: white;
        border: none;
        padding: 0 .75rem;
        height: 100%;
        display: flex;
        align-items: center;
        cursor: pointer;
        color: var(--fin-text-muted);
    }

    .date-picker-btn:hover {
        background: #f1f5f9;
    }

    .date-picker-val {
        padding: 0 1rem;
        font-size: .85rem;
        font-weight: 500;
        color: var(--fin-text-main);
        border-left: 1px solid var(--fin-border);
        border-right: 1px solid var(--fin-border);
        height: 100%;
        display: flex;
        align-items: center;
    }

    /* ── Premium Action Tooltips ── */
    .action-icons {
        display: flex;
        align-items: center;
        gap: 0;
    }

    .action-icons .act-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        cursor: pointer;
        transition: all .2s ease;
        color: var(--fin-text-muted);
        font-size: .95rem;
    }

    .action-icons .act-btn:hover {
        background: #f1f5f9;
        color: #3b82f6;
    }

    .action-icons .act-btn.act-pay:hover {
        color: var(--fin-success);
    }

    .action-icons .act-btn.act-mora:hover {
        color: var(--fin-warning);
    }

    .action-icons .act-btn.act-download:hover {
        color: #6366f1;
    }

    /* Tooltip */
    .act-btn::after {
        content: attr(data-tooltip);
        position: absolute;
        bottom: calc(100% + 8px);
        left: 50%;
        transform: translateX(-50%);
        background: var(--fin-primary);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: .72rem;
        font-weight: 500;
        white-space: nowrap;
        pointer-events: none;
        opacity: 0;
        transition: opacity .2s ease, transform .2s ease;
        transform: translateX(-50%) translateY(4px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .15);
        letter-spacing: .01em;
        z-index: 10;
    }

    .act-btn::before {
        content: '';
        position: absolute;
        bottom: calc(100% + 4px);
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: var(--fin-primary);
        pointer-events: none;
        opacity: 0;
        transition: opacity .2s ease;
        z-index: 10;
    }

    .act-btn:hover::after {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }

    .act-btn:hover::before {
        opacity: 1;
    }

    /* ── Modal Overlay ── */
    .mora-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, .45);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: none;
        align-items: center;
        justify-content: center;
        animation: fadeIn .2s ease;
    }

    .mora-overlay.active {
        display: flex;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px) scale(.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .mora-modal {
        background: white;
        border-radius: 12px;
        width: 100%;
        max-width: 500px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, .25);
        animation: slideUp .3s ease;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
    }

    /* Modal header */
    .mora-modal-header {
        padding: 1.5rem 1.75rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .mora-modal-header h3 {
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--fin-text-main);
        margin: 0;
    }

    .mora-close {
        background: none;
        border: none;
        color: var(--fin-text-muted);
        cursor: pointer;
        font-size: 1.2rem;
        padding: 4px;
        border-radius: 6px;
        transition: all .15s;
        line-height: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mora-close:hover {
        background: #f1f5f9;
        color: var(--fin-text-main);
    }

    /* Unit info box */
    .mora-unit-info {
        background: #f8fafc;
        border: 1px solid var(--fin-border);
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin: 0 1.75rem 1.25rem;
    }

    .mora-unit-info .unit-name {
        font-weight: 600;
        font-size: .92rem;
        color: var(--fin-text-main);
        margin-bottom: .15rem;
    }

    .mora-unit-info .unit-residents {
        font-size: .8rem;
        color: var(--fin-text-muted);
    }

    /* Config info rows */
    .mora-config-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: .35rem 0;
        font-size: .82rem;
    }

    .mora-config-row .cfg-label {
        color: var(--fin-text-muted);
    }

    .mora-config-row .cfg-value {
        font-weight: 600;
        color: var(--fin-text-main);
    }

    .mora-config-row .cfg-badge {
        background: #e2e8f0;
        color: #3F67AC;
        font-size: .68rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 4px;
        letter-spacing: .04em;
    }

    /* Modal body */
    .mora-modal-body {
        padding: 0 1.75rem 1.5rem;
    }

    /* Warning alert */
    .mora-alert {
        background: #fefce8;
        border: 1px solid #fde68a;
        border-radius: 8px;
        padding: .75rem 1rem;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: flex-start;
        gap: .6rem;
        font-size: .8rem;
        color: #92400e;
        line-height: 1.5;
    }

    .mora-alert i {
        font-size: .9rem;
        color: #d97706;
        margin-top: 1px;
        flex-shrink: 0;
    }

    /* Form group */
    .mora-form-group {
        margin-bottom: 1.25rem;
    }

    .mora-label {
        display: block;
        font-size: .82rem;
        font-weight: 600;
        color: var(--fin-text-main);
        margin-bottom: .4rem;
    }

    .mora-label .required {
        color: var(--fin-danger);
    }

    .mora-select,
    .mora-input {
        width: 100%;
        height: 42px;
        padding: 0 .85rem;
        border: 1px solid var(--fin-border);
        border-radius: 8px;
        font-size: .85rem;
        color: var(--fin-text-main);
        background: white;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
    }

    .mora-select:focus,
    .mora-input:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    .mora-textarea {
        width: 100%;
        min-height: 80px;
        padding: .75rem .85rem;
        border: 1px solid var(--fin-border);
        border-radius: 8px;
        font-size: .85rem;
        color: var(--fin-text-main);
        background: white;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        resize: vertical;
        font-family: inherit;
    }

    .mora-textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
    }

    /* Toggle switch */
    .toggle-row {
        display: flex;
        align-items: center;
        gap: .75rem;
        margin-bottom: 1rem;
        cursor: pointer;
        user-select: none;
    }

    .toggle-switch {
        position: relative;
        width: 40px;
        height: 22px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        display: none;
    }

    .toggle-slider {
        position: absolute;
        inset: 0;
        background: #cbd5e1;
        border-radius: 22px;
        transition: background .25s;
        cursor: pointer;
    }

    .toggle-slider::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        left: 3px;
        top: 3px;
        background: white;
        border-radius: 50%;
        transition: transform .25s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
    }

    .toggle-switch input:checked+.toggle-slider {
        background: #3b82f6;
    }

    .toggle-switch input:checked+.toggle-slider::after {
        transform: translateX(18px);
    }

    .toggle-text {
        font-size: .85rem;
        color: var(--fin-text-main);
        font-weight: 500;
    }

    /* Amount display */
    .mora-amount-box {
        background: #fafbfc;
        border: 1px solid var(--fin-border);
        border-radius: 8px;
        padding: .85rem 1rem;
        margin-bottom: 1.25rem;
    }

    .mora-amount-label {
        font-size: .75rem;
        color: var(--fin-text-muted);
        margin-bottom: .25rem;
    }

    .mora-amount-value {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--fin-text-main);
    }

    .mora-amount-input {
        width: 100%;
        border: none;
        background: transparent;
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--fin-text-main);
        outline: none;
        padding: 0;
    }

    /* Modal footer */
    .mora-modal-footer {
        padding: 0 1.75rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
    }

    .btn-mora-cancel {
        background: white;
        border: 1px solid var(--fin-border);
        color: var(--fin-text-main);
        padding: .55rem 1.25rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all .15s;
    }

    .btn-mora-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .btn-mora-apply {
        background: #334155;
        border: none;
        color: white;
        padding: .55rem 1.5rem;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    .btn-mora-apply:hover {
        background: #1e293b;
    }

    .btn-mora-apply:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .btn-mora-apply .spinner {
        display: none;
        width: 14px;
        height: 14px;
        border: 2px solid rgba(255, 255, 255, .3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin .6s linear infinite;
    }

    .btn-mora-apply.loading .spinner {
        display: inline-block;
    }

    .btn-mora-apply.loading .btn-text {
        display: none;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Success toast */
    .mora-toast {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 10000;
        background: white;
        border-radius: 10px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
        display: flex;
        align-items: center;
        gap: .75rem;
        transform: translateX(120%);
        transition: transform .35s cubic-bezier(.22, 1, .36, 1);
        border-left: 4px solid var(--fin-success);
        max-width: 380px;
    }

    .mora-toast.show {
        transform: translateX(0);
    }

    .mora-toast .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #d1fae5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #059669;
        font-size: .9rem;
        flex-shrink: 0;
    }

    .mora-toast .toast-body {
        flex: 1;
    }

    .mora-toast .toast-title {
        font-weight: 600;
        font-size: .88rem;
        color: var(--fin-text-main);
    }

    .mora-toast .toast-msg {
        font-size: .78rem;
        color: var(--fin-text-muted);
        margin-top: 1px;
    }
</style>

<div class="ppu-container">

    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <h2 class="cc-hero-title">Finanzas</h2>
            <div class="cc-hero-divider"></div>
            <div class="cc-hero-breadcrumb">
                <i class="bi bi-credit-card"></i>
                <i class="bi bi-chevron-right"></i>
                <h2 class="cc-hero-title">Pagos por Unidad</h2>
                <i class="bi bi-chevron-right"></i>
                Seguimientos de pagos individuales
            </div>
        </div>
        <div class="toolbar-right">

            <button class="cc-hero-btn"
                onclick="window.location.href='<?= base_url('admin/finanzas/nuevo-registro') ?>'"><i
                    class="bi bi-plus"></i> Nuevo Registro</button>
        </div>
    </div>

    <!-- ── Hero fin── -->





    <!-- Toolbar -->
    <div class="ppu-toolbar">
        <div class="toolbar-left">
            <div class="position-relative">
                <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"
                    style="font-size:.85rem;"></i>
                <input type="text" id="searchInput" class="toolbar-control input-search"
                    placeholder="Buscar por número de unidad">
            </div>
            <select id="filterEstado" class="toolbar-control" style="width: 140px;">
                <option value="">Todos</option>
                <option value="Sin adeudos">Sin adeudos</option>
                <option value="Al corriente">Al corriente</option>
                <option value="Moroso">Moroso</option>
            </select>
            <span class="filter-count" id="countLabel"><?= count($records) ?> unidades</span>
        </div>

    </div>

    <!-- Table -->
    <div class="ppu-content-box">
        <div class="table-responsive">
            <table class="ppu-table" id="unidadesTable">
                <thead>
                    <tr>
                        <th style="width:40px;"><input type="checkbox" id="selectAll"></th>
                        <th>Unidad <i class="bi bi-arrow-down-up ms-1" style="font-size:.7rem;"></i></th>
                        <th>Cuota de Mantenimiento</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Estado</th>
                        <th>Saldo Pendiente</th>
                        <th>Comprobante</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($records)): ?>
                        <?php foreach ($records as $rec): ?>
                            <tr onclick="window.location.href='<?= base_url('admin/finanzas/pagos-por-unidad/' . $rec['hash_id']) ?>'"
                                data-search="<?= esc(strtolower($rec['unidad'])) ?>" data-estado="<?= esc($rec['estado']) ?>">
                                <td onclick="event.stopPropagation()"><input type="checkbox"></td>
                                <td>
                                    <a class="unit-link"
                                        href="<?= base_url('admin/finanzas/pagos-por-unidad/' . $rec['hash_id']) ?>"
                                        onclick="event.stopPropagation()">
                                        <?= esc($rec['unidad']) ?>
                                    </a>
                                    <?php if (($rec['pending_vouchers'] ?? 0) > 0): ?>
                                        <span class="badge bg-warning rounded-pill ms-1" style="font-size:.6rem; font-weight:600; vertical-align:middle;" title="<?= $rec['pending_vouchers'] ?> comprobante(s) pendiente(s)"><?= $rec['pending_vouchers'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>MX$<?= number_format($rec['cuota'], 2) ?></td>
                                <td><?= esc($rec['vencimiento']) ?></td>
                                <td>
                                    <?php if ($rec['estado'] === 'Sin adeudos'): ?>
                                        <span class="badge-status badge-ok"><i class="bi bi-check-circle-fill"></i> Sin
                                            adeudos</span>
                                    <?php elseif ($rec['estado'] === 'A favor'): ?>
                                        <span class="badge-status badge-ok" style="background:#d1fae5; color:#065f46;"><i
                                                class="bi bi-star-fill"></i> A favor</span>
                                    <?php elseif ($rec['estado'] === 'Al corriente'): ?>
                                        <span class="badge-status badge-ok" style="background:#e0f2fe; color:#0284c7;"><i class="bi bi-info-circle-fill"></i> Al corriente</span>
                                    <?php else: ?>
                                        <span class="badge-status badge-moroso"><i class="bi bi-exclamation-circle-fill"></i>
                                            Moroso</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($rec['saldo'] > 0): ?>
                                        <span class="text-danger-bold">MX$<?= number_format($rec['saldo'], 2) ?></span>
                                    <?php elseif ($rec['saldo'] < 0): ?>
                                        <span style="color:#059669; font-weight:600;">MX$<?= number_format(abs($rec['saldo']), 2) ?>
                                            (A favor)</span>
                                    <?php else: ?>
                                        <span style="color:var(--fin-text-muted);">MX$0.00</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center" onclick="event.stopPropagation()">
                                    <i class="bi bi-file-earmark" style="color:#cbd5e1; font-size:1rem; cursor:pointer;"></i>
                                </td>
                                <td class="action-icons" onclick="event.stopPropagation()">

                                    <span class="act-btn act-pay" data-tooltip="Registrar Pago"
                                        onclick="window.location.href='<?= base_url('admin/finanzas/nuevo-registro') ?>'">
                                        <i class="bi bi-currency-dollar"></i>
                                    </span>
                                    <span class="act-btn act-mora" data-tooltip="Aplicar Cargo por Mora"
                                        onclick="openMoraModal('<?= $rec['hash_id'] ?>', '<?= esc($rec['unidad']) ?>')">
                                        <i class="bi bi-exclamation-circle"></i>
                                    </span>
                                    <span class="act-btn act-download" data-tooltip="Descargar Estado de Cuenta"
                                        onclick="window.location.href='<?= base_url('admin/finanzas/estado-de-cuenta/' . $rec['hash_id']) ?>'">
                                        <i class="bi bi-download"></i>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No se encontraron unidades.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="ppu-pagination">
            <div class="pag-left"
                style="display:flex;align-items:center;gap:.5rem;font-size:.8rem;color:var(--fin-text-muted);">
                Resultados por página:
                <select>
                    <option>200</option>
                    <option>100</option>
                    <option>50</option>
                </select>
            </div>
            <div class="pag-right">
                <span id="pagInfo">1-<?= count($records) ?> de <?= count($records) ?></span>
                <div class="pag-arrows">
                    <button><i class="bi bi-chevron-double-left"></i></button>
                    <button><i class="bi bi-chevron-left"></i></button>
                    <button><i class="bi bi-chevron-right"></i></button>
                    <button><i class="bi bi-chevron-double-right"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════ MODAL: Aplicar Cargo por Mora ═══════ -->
<div class="mora-overlay" id="moraOverlay">
    <div class="mora-modal" id="moraModal">
        <div class="mora-modal-header">
            <h3>Aplicar Cargo por Mora</h3>
            <button class="mora-close" onclick="closeMoraModal()">&times;</button>
        </div>

        <!-- Unit info -->
        <div class="mora-unit-info">
            <div class="unit-name" id="moraUnitName">Unidad: —</div>
            <div class="unit-residents" id="moraUnitResidents">Residentes: —</div>
        </div>

        <div class="mora-modal-body">
            <!-- Warning: no config -->
            <div class="mora-alert" id="moraNoConfigAlert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>El monto del cargo por mora no está configurado. Por favor, use un monto personalizado.</span>
            </div>

            <!-- Período de Facturación -->
            <div class="mora-form-group">
                <label class="mora-label">Período de Facturación <span class="required">*</span></label>
                <select class="mora-select" id="moraPeriodo">
                    <option value="" disabled>Cargando períodos...</option>
                </select>
            </div>

            <!-- Toggle custom amount -->
            <label class="toggle-row" id="moraToggleRow">
                <div class="toggle-switch">
                    <input type="checkbox" id="moraCustomToggle">
                    <span class="toggle-slider"></span>
                </div>
                <span class="toggle-text">Usar monto personalizado</span>
            </label>

            <!-- Amount display / input -->
            <div class="mora-amount-box" id="moraAmountBox" style="display:none;">
                <div class="mora-amount-label">Monto a aplicar:</div>
                <input type="text" class="mora-amount-input" id="moraAmountInput" value="0.00" placeholder="0.00"
                    inputmode="decimal">
            </div>

            <!-- Motivo -->
            <div class="mora-form-group">
                <label class="mora-label">Motivo</label>
                <textarea class="mora-textarea" id="moraMotivo"
                    placeholder="Motivo opcional para la aplicación manual del cargo por mora"></textarea>
            </div>
        </div>

        <div class="mora-modal-footer">
            <button class="btn-mora-cancel" onclick="closeMoraModal()">Cancelar</button>
            <button class="btn-mora-apply" id="btnApplyMora" onclick="applyMoraCharge()">
                <span class="spinner"></span>
                <span class="btn-text">Aplicar</span>
            </button>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div class="mora-toast" id="moraToast">
    <div class="toast-icon"><i class="bi bi-check-lg"></i></div>
    <div class="toast-body">
        <div class="toast-title" id="toastTitle">Cargo aplicado</div>
        <div class="toast-msg" id="toastMsg">El cargo por mora ha sido registrado exitosamente.</div>
    </div>
</div>

<script>
    const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
    let currentUnitId = null;

    // ── Select All ──
    document.getElementById('selectAll').addEventListener('change', function () {
        document.querySelectorAll('#unidadesTable tbody input[type="checkbox"]').forEach(cb => cb.checked = this.checked);
    });

    // ── Live Search + Filter ──
    function applyFilters() {
        const search = document.getElementById('searchInput').value.toLowerCase();
        const estado = document.getElementById('filterEstado').value;
        let visible = 0;
        document.querySelectorAll('#unidadesTable tbody tr').forEach(row => {
            const matchSearch = !search || (row.dataset.search && row.dataset.search.includes(search));
            const matchEstado = !estado || row.dataset.estado === estado;
            const show = matchSearch && matchEstado;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        document.getElementById('countLabel').textContent = visible + ' unidades';
        document.getElementById('pagInfo').textContent = '1-' + visible + ' de ' + visible;
    }
    document.getElementById('searchInput').addEventListener('input', applyFilters);
    document.getElementById('filterEstado').addEventListener('change', applyFilters);

    // ── Mora Modal ──
    function openMoraModal(unitId, unitNumber) {
        currentUnitId = unitId;
        document.getElementById('moraUnitName').textContent = 'Unidad: ' + unitNumber;
        document.getElementById('moraUnitResidents').textContent = 'Cargando...';
        document.getElementById('moraAmountInput').value = '0.00';
        document.getElementById('moraMotivo').value = '';
        document.getElementById('moraCustomToggle').checked = false;
        document.getElementById('moraAmountBox').style.display = 'none';
        document.getElementById('moraNoConfigAlert').style.display = 'flex';

        // Reset button
        const btn = document.getElementById('btnApplyMora');
        btn.classList.remove('loading');
        btn.disabled = false;

        document.getElementById('moraOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';

        // Fetch unit data (residents + pending periods)
        fetch(BASE_URL + '/admin/finanzas/mora/unit-info/' + unitId)
            .then(r => r.json())
            .then(data => {
                if (data.residents) {
                    document.getElementById('moraUnitResidents').textContent = 'Residentes: ' + data.residents;
                } else {
                    document.getElementById('moraUnitResidents').textContent = 'Residentes: —';
                }

                // Populate periods
                const sel = document.getElementById('moraPeriodo');
                sel.innerHTML = '';
                if (data.periods && data.periods.length > 0) {
                    data.periods.forEach((p, i) => {
                        const opt = document.createElement('option');
                        opt.value = p.value;
                        opt.textContent = p.label;
                        if (i === 0) opt.selected = true;
                        sel.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'Sin períodos pendientes';
                    opt.disabled = true;
                    opt.selected = true;
                    sel.appendChild(opt);
                }
            })
            .catch(() => {
                document.getElementById('moraUnitResidents').textContent = 'Residentes: —';
            });
    }

    // Toggle custom amount field
    document.getElementById('moraCustomToggle').addEventListener('change', function () {
        const box = document.getElementById('moraAmountBox');
        const alert = document.getElementById('moraNoConfigAlert');
        if (this.checked) {
            box.style.display = 'block';
            alert.style.display = 'none';
        } else {
            box.style.display = 'none';
            alert.style.display = 'flex';
        }
    });

    function closeMoraModal() {
        document.getElementById('moraOverlay').classList.remove('active');
        document.body.style.overflow = '';
        currentUnitId = null;
    }

    // Close on overlay click
    document.getElementById('moraOverlay').addEventListener('click', function (e) {
        if (e.target === this) closeMoraModal();
    });

    // ESC key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && document.getElementById('moraOverlay').classList.contains('active')) {
            closeMoraModal();
        }
    });

    // Format amount input
    document.getElementById('moraAmountInput').addEventListener('focus', function () {
        if (this.value === '0.00') this.value = '';
    });
    document.getElementById('moraAmountInput').addEventListener('blur', function () {
        let val = parseFloat(this.value.replace(/[^0-9.]/g, ''));
        if (isNaN(val) || val <= 0) val = 0;
        this.value = val.toFixed(2);
    });

    // ── Apply Charge ──
    function applyMoraCharge() {
        const amount = parseFloat(document.getElementById('moraAmountInput').value.replace(/[^0-9.]/g, ''));
        const periodo = document.getElementById('moraPeriodo').value;
        const motivo = document.getElementById('moraMotivo').value.trim();

        if (!periodo) {
            showToastError('Seleccione un período de facturación');
            return;
        }
        if (!amount || amount <= 0) {
            showToastError('Ingrese un monto válido mayor a $0');
            return;
        }

        const btn = document.getElementById('btnApplyMora');
        btn.classList.add('loading');
        btn.disabled = true;

        fetch(BASE_URL + '/admin/finanzas/mora/aplicar', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                unit_id: currentUnitId,
                amount: amount,
                period: periodo,
                motivo: motivo
            })
        })
            .then(r => r.json())
            .then(data => {
                btn.classList.remove('loading');
                btn.disabled = false;
                if (data.success) {
                    closeMoraModal();
                    showToast('Cargo aplicado exitosamente', 'Se aplicó un cargo por mora de MX$' + amount.toFixed(2) + ' a la unidad.');
                    // Refresh after a short delay
                    setTimeout(() => window.location.reload(), 1800);
                } else {
                    showToastError(data.message || 'Error al aplicar el cargo');
                }
            })
            .catch(() => {
                btn.classList.remove('loading');
                btn.disabled = false;
                showToastError('Error de conexión. Intente de nuevo.');
            });
    }

    // ── Toast ──
    function showToast(title, msg) {
        const toast = document.getElementById('moraToast');
        toast.style.borderLeftColor = 'var(--fin-success)';
        toast.querySelector('.toast-icon').style.background = '#d1fae5';
        toast.querySelector('.toast-icon').style.color = '#059669';
        toast.querySelector('.toast-icon i').className = 'bi bi-check-lg';
        document.getElementById('toastTitle').textContent = title;
        document.getElementById('toastMsg').textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    }

    function showToastError(msg) {
        const toast = document.getElementById('moraToast');
        toast.style.borderLeftColor = 'var(--fin-danger)';
        toast.querySelector('.toast-icon').style.background = '#fee2e2';
        toast.querySelector('.toast-icon').style.color = '#dc2626';
        toast.querySelector('.toast-icon i').className = 'bi bi-x-lg';
        document.getElementById('toastTitle').textContent = 'Error';
        document.getElementById('toastMsg').textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    }
</script>

<?= $this->endSection() ?>