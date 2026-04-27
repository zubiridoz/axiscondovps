<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
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
    .koti-header {
        background: #2f3a4d;
        color: white;
        padding: 1.5rem 2rem;
        border-radius: 0.5rem;
        margin-bottom: 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .koti-alert-warning {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 0.5rem;
        padding: 0.75rem 1.25rem;
        margin-top: 0.75rem;
        margin-left: 0.75rem;
        margin-bottom: 1rem;
        font-size: 0.82rem;
        color: #92400e;
    }

    .koti-alert-warning i {
        color: #d97706;
    }

    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
        background: white;
        padding: 1rem;
        border-radius: 8px;
    }

    .search-input-group,
    .search-box {
        position: relative;
    }

    .search-input-group i,
    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input-group input,
    .search-box input {
        padding-left: 2.5rem;
        padding-right: 1rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        width: 100%;
        outline: none;
        background: transparent;
    }

    .koti-table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .koti-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .koti-table td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
        color: #1e293b;
    }

    .type-link {
        color: #3b82f6;
        text-decoration: none;
        background: #eff6ff;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .role-pill {
        background: #f1f5f9;
        color: #64748b;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .koti-modal-content {
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }

    .koti-modal-header {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 1.5rem;
    }

    .koti-modal-body {
        background: #fff;
    }

    .koti-modal-footer {
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        padding: 1rem 1.5rem;
    }

    .create-tabs {
        display: flex;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .create-tab {
        padding: 0.75rem 1.5rem;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: 0.2s;
    }

    .create-tab:hover {
        color: #1e293b;
    }

    .create-tab.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
    }

    .side-info-panel {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1.5rem;
        height: 100%;
    }

    .info-item {
        display: flex;
        margin-bottom: 1.5rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .bg-light-blue {
        background: #e0f2fe;
        color: #0284c7;
    }

    .bg-light-green {
        background: #dcfce7;
        color: #16a34a;
    }

    .bg-light-purple {
        background: #f3e8ff;
        color: #9333ea;
    }

    .info-title {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .info-desc {
        font-size: 0.8rem;
        color: #64748b;
    }

    .res-list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 0.75rem;
        background: #fff;
        transition: 0.2s;
    }

    .res-list-item:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
    }

    .resident-initials {
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.9rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .res-action-btn {
        background: transparent;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s;
        cursor: pointer;
    }

    .res-action-btn:hover {
        background: #f1f5f9;
    }

    .res-action-btn.del:hover {
        background: #fef2f2;
    }

    /* Modal stacking fix: ensure modals appear above backdrop */
    #modalCsv,
    #modalMasivo {
        z-index: 1060 !important;
    }

    #modalCsv~.modal-backdrop,
    #modalMasivo~.modal-backdrop {
        z-index: 1055 !important;
    }

    /* ===== Importar/Exportar Modal Styles ===== */
    .csv-pill-tabs {
        gap: 2px;
    }

    .csv-pill-tab {
        background: transparent;
        border: 2px solid transparent;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .csv-pill-tab:hover {
        color: #334155;
    }

    .csv-pill-tab.active {
        background: #fff;
        color: #0f172a;
        border-color: #cbd5e1;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
    }

    .csv-step-badge {
        width: 28px;
        height: 28px;
        min-width: 28px;
        background: #059669;
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
        margin-top: 2px;
    }

    .csv-use-case-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        padding: 0.75rem;
        transition: border-color 0.2s ease;
    }

    .csv-use-case-card:hover {
        border-color: #94a3b8;
    }

    .csv-critical-warning {
        background: #c41818ff;
        border: 1px solid #991b1b;
        border-radius: 0.6rem;
        padding: 0.75rem;
        color: #ffffffff;
    }

    .csv-stat-card {
        padding: 0.75rem;
        border-radius: 0.6rem;
        border: 1px solid transparent;
    }

    .csv-stat-green {
        background: #f0fdf4;
        color: #166534;
        border-color: #bbf7d0;
    }

    .csv-stat-yellow {
        background: #fefce8;
        color: #854d0e;
        border-color: #fef08a;
    }

    .csv-stat-red {
        background: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .csv-stat-neutral {
        background: #f8fafc;
        color: #334155;
        border-color: #e2e8f0;
    }

    .csv-export-dot {
        width: 10px;
        height: 10px;
        min-width: 10px;
        background: #1e293b;
        border-radius: 50%;
        display: inline-block;
    }

    /* Preview table status badges */
    .csv-badge-new {
        background: #f0fdf4;
        color: #166534;
        border: 1px solid #bbf7d0;
        padding: 0.2rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .csv-badge-updated {
        background: #fefce8;
        color: #854d0e;
        border: 1px solid #fef08a;
        padding: 0.2rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .csv-badge-nochange {
        background: #f8fafc;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 0.2rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.7rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .csv-change-text {
        color: #059669;
        font-size: 0.72rem;
    }

    /* ============================
       MASS EDIT MODAL STYLES
       ============================ */
    .mass-edit-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .mass-edit-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        padding: 0.75rem 1rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .mass-edit-table tbody td {
        padding: 0.6rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
        color: #1e293b;
        vertical-align: middle;
        transition: background-color 0.25s ease, border-color 0.25s ease;
    }

    .mass-edit-table tbody tr {
        transition: background-color 0.25s ease;
    }

    .mass-edit-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .mass-edit-table tbody tr.me-row-modified {
        background-color: #fffbeb !important;
    }

    .mass-edit-table tbody tr.me-row-modified:hover {
        background-color: #fef9c3 !important;
    }

    /* Editable cell wrapper */
    .me-cell-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-height: 34px;
    }

    .me-cell-value {
        flex: 1;
    }

    .me-pencil-btn {
        background: none;
        border: none;
        color: #cbd5e1;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.2s ease;
        font-size: 0.82rem;
        flex-shrink: 0;
    }

    .me-pencil-btn:hover {
        color: #64748b;
        background: #f1f5f9;
    }

    /* Editable input state */
    .me-cell-input {
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.35rem 0.6rem;
        font-size: 0.85rem;
        color: #1e293b;
        width: 100%;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
        background: #fff;
    }

    .me-cell-input:focus {
        border-color: #94a3b8;
        box-shadow: 0 0 0 2px rgba(148, 163, 184, 0.15);
    }

    .me-cell-input.me-cell-dirty {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.12);
    }

    /* Modified badge */
    .me-badge-modified {
        background: #f59e0b;
        color: #fff;
        font-size: 0.68rem;
        font-weight: 600;
        padding: 0.2rem 0.65rem;
        border-radius: 1rem;
        white-space: nowrap;
        letter-spacing: 0.2px;
        animation: meBadgeFadeIn 0.3s ease;
    }

    @keyframes meBadgeFadeIn {
        from {
            opacity: 0;
            transform: scale(0.8);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Clipboard icon for bulk-applied cells */
    .me-applied-icon {
        color: #f59e0b;
        font-size: 0.78rem;
        animation: meBadgeFadeIn 0.3s ease;
    }

    /* Bulk action bar */
    .me-bulk-bar {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
        display: none;
        animation: meSlideDown 0.25s ease;
    }

    .me-bulk-bar.active {
        display: block;
    }

    @keyframes meSlideDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .me-bulk-count {
        font-weight: 700;
        color: #1e293b;
        font-size: 0.85rem;
    }

    .me-bulk-label {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .me-bulk-input {
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.3rem 0.6rem;
        font-size: 0.82rem;
        width: 120px;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .me-bulk-input:focus {
        border-color: #94a3b8;
    }

    .me-bulk-select {
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.3rem 0.6rem;
        font-size: 0.82rem;
        min-width: 140px;
        outline: none;
        background: #fff;
        color: #1e293b;
    }

    .me-apply-btn {
        background: none;
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.3rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .me-apply-btn:hover {
        border-color: #94a3b8;
        color: #334155;
        background: #f1f5f9;
    }

    .me-apply-btn.applied {
        background: #059669;
        border-color: #059669;
        color: #fff;
    }

    /* Footer status */
    .me-footer-status {
        font-size: 0.82rem;
        color: #94a3b8;
        transition: color 0.3s ease;
    }

    .me-footer-status.has-changes {
        color: #f59e0b;
        font-weight: 600;
    }

    /* Section select in table */
    .me-section-select {
        border: 1.5px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.35rem 0.6rem;
        font-size: 0.82rem;
        background: #fff;
        color: #1e293b;
        outline: none;
        min-width: 120px;
        transition: border-color 0.2s ease;
        cursor: pointer;
        appearance: auto;
    }

    .me-section-select:focus {
        border-color: #94a3b8;
    }

    .me-section-select.me-cell-dirty {
        border-color: #f59e0b !important;
        box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.12);
    }

    /* Checkbox styling */
    .me-checkbox {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #475569;
    }
</style>

<div class="sticky-management-header" id="management-header">



    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <h2 class="cc-hero-title">Unidades</h2>
            <div class="cc-hero-divider"></div>
            <div class="cc-hero-breadcrumb">
                <i class="bi bi-building"></i>
                <i class="bi bi-chevron-right"></i>
                Gestionar unidades
            </div>
        </div>
        <div class="cc-hero-right">
            <button type="button" class="cc-hero-btndark" onclick="return openModalSafely('modalCsv')">
                <i class="bi bi-file-earmark-arrow-up"></i> Importar/Exportar
            </button>
            <button class="cc-hero-btn" data-bs-toggle="modal" data-bs-target="#modalIndividual">
                <i class="bi bi-plus-lg"></i> Nueva Unidad
            </button>


        </div>
    </div>
    <!-- ── END Hero ── -->





    <?php
    $hasZeroFee = false;
    foreach ($units as $u) {
        if (empty($u['maintenance_fee']) || (float) $u['maintenance_fee'] <= 0) {
            $hasZeroFee = true;
            break;
        }
    }
    ?>
    <?php if ($hasZeroFee): ?>
        <div class="koti-alert-warning d-flex align-items-center">
            <i class="bi bi-exclamation-triangle-fill me-3 fs-5"></i>
            <div class="fw-medium">Algunas unidades no tienen cuotas de mantenimiento configuradas. <em>Por favor configure
                    las cuotas para todas las unidades para garantizar una facturación correcta.</em></div>
        </div>
    <?php endif; ?>

    <div class="filter-bar shadow-sm" style="margin-top: <?= $hasZeroFee ? '0' : '1rem' ?>;">
        <div class="filter-group d-flex gap-3">
            <div class="search-box mb-0 ms-0" style="background:#f1f5f9; border: 1px solid #e2e8f0; width: 300px;">
                <i class="bi bi-search text-muted me-2"></i>
                <input type="text" id="unitSearch" placeholder="Buscar unidades..." onkeyup="filterUnits()"
                    style="color:#1e293b;">
            </div>

            <div class="dropdown">
                <button class="btn btn-light border dropdown-toggle btn-sm d-flex align-items-center p-2"
                    style="background:white; min-width: 140px;" data-bs-toggle="dropdown" id="sectionFilterBtn">
                    <div class="text-start flex-grow-1">
                        <small class="text-muted d-block" style="font-size:0.6rem;">SECCIÓN</small>
                        <span class="fw-bold small" id="sectionFilterLabel">Todas</span>
                    </div>
                </button>
                <ul class="dropdown-menu shadow-sm border-0">
                    <li><a class="dropdown-item" href="#" onclick="setSectionFilter('Todas')">Todas</a></li>
                    <?php if (isset($sections)):
                        foreach ($sections as $s): ?>
                            <li><a class="dropdown-item" href="#"
                                    onclick="setSectionFilter('<?= esc($s['name']) ?>')"><?= esc($s['name']) ?></a></li>
                        <?php endforeach; endif; ?>
                </ul>
            </div>

            <div class="dropdown">
                <button class="btn btn-light border dropdown-toggle btn-sm d-flex align-items-center p-2"
                    style="background:white; min-width: 140px;" data-bs-toggle="dropdown" id="residentFilterBtn">
                    <div class="text-start flex-grow-1">
                        <small class="text-muted d-block" style="font-size:0.6rem;">RESIDENTES</small>
                        <span class="fw-bold small" id="residentFilterLabel">Todas</span>
                    </div>
                </button>
                <ul class="dropdown-menu shadow-sm border-0">
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"
                            onclick="setResidentFilter('Todas')"><i class="bi bi-grid small"></i> Todas</a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"
                            onclick="setResidentFilter('Sin Residentes')"><i class="bi bi-house small"></i> Sin
                            Residentes</a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"
                            onclick="setResidentFilter('Sin Propietarios')"><i class="bi bi-person-x small"></i> Sin
                            Propietarios</a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"
                            onclick="setResidentFilter('Sin Inquilinos')"><i class="bi bi-person-x small"></i> Sin
                            Inquilinos</a></li>
                </ul>
            </div>
        </div>

        <div class="filter-group d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="btn btn-koti-menu dropdown-toggle btn-sm rounded-pill px-3" type="button"
                    id="optionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Opciones
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="optionsDropdown"
                    style="font-size:0.85rem">
                    <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                            data-bs-target="#modalIndividual"><i class="bi bi-plus-lg text-muted me-2"></i> Crear
                            Unidad</a></li>
                    <li><a class="dropdown-item py-2" href="#" onclick="return openModalSafely('modalCsv')"><i
                                class="bi bi-file-earmark-arrow-up text-muted me-2"></i> Importar/Exportar</a></li>
                    <li><a class="dropdown-item py-2" href="#" onclick="return openModalSafely('modalMasivo')"><i
                                class="bi bi-pencil-square text-muted me-2"></i> Edición Masiva</a></li>
                </ul>
            </div>
            <button class="btn btn-outline-secondary bg-white btn-sm rounded-circle p-2"
                style="width:34px; height:34px; display:flex; align-items:center; justify-content:center;"
                onclick="location.reload();">
                <i class="bi bi-arrow-clockwise"></i>
            </button>
        </div>
    </div>
</div>

<style>
    .koti-table th {
        background: #f8fafc;
        color: #64748b;
        font-weight: 500;
        font-size: 0.75rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .koti-table td {
        padding: 0.75rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
        color: #1e293b;
    }

    .unit-row:hover td {
        background-color: #f8fafc !important;
        cursor: pointer;
    }

    .sort-icon {
        font-size: 0.7rem;
        margin-left: 0.25rem;
        opacity: 0.5;
    }
</style>
<div class="koti-table-container">
    <div class="koti-table table-responsive">
        <table class="table mb-0 w-100" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 50px;"><input class="form-check-input" type="checkbox" id="main-select-all"></th>
                    <th onclick="sortTable(1, 'text')" class="cursor-pointer">Sección <i
                            class="bi bi-arrow-down-up sort-icon" id="sort-icon-1"></i></th>
                    <th onclick="sortTable(2, 'text')" class="cursor-pointer">Nombre <i
                            class="bi bi-arrow-down-up sort-icon" id="sort-icon-2"></i></th>
                    <th onclick="sortTable(3, 'currency')" class="cursor-pointer">Cuota de Mantenimiento <i
                            class="bi bi-arrow-down-up sort-icon" id="sort-icon-3"></i></th>
                    <th class="text-center cursor-pointer" onclick="sortTable(4, 'number')"><i
                            class="bi bi-person sort-icon" id="sort-icon-4"></i></th>
                    <th onclick="sortTable(5, 'text')" class="cursor-pointer">Propietarios <i
                            class="bi bi-arrow-down-up sort-icon" id="sort-icon-5"></i></th>
                    <th onclick="sortTable(6, 'text')" class="cursor-pointer">Inquilinos <i
                            class="bi bi-arrow-down-up sort-icon" id="sort-icon-6"></i></th>
                </tr>
            </thead>
            <tbody id="units-table-body">
                <?php if (empty($units)): ?>
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No hay unidades registradas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($units as $u): ?>
                        <?php
                        $unitJson = htmlspecialchars(json_encode([
                            'id' => $u['id'],
                            'unit_number' => $u['unit_number'],
                            'type' => $u['type'],
                            'floor' => $u['floor'] ?? '',
                            'area' => $u['area'] ?? '',
                            'indiviso_percentage' => $u['indiviso_percentage'] ?? '',
                            'section_id' => $u['section_id'] ?? '',
                            'maintenance_fee' => $u['maintenance_fee'] ?? 0,
                            'fee_start_month' => $u['fee_start_month'] ?? '',
                            'occupancy_type' => $u['occupancy_type'] ?? 'owner_occupied',
                            'owners' => $u['owners'] ?? [],
                            'tenants' => $u['tenants'] ?? []
                        ]), ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr class="unit-row bg-white transition-all" data-section="<?= esc($u['section_name'] ?? 'N/A') ?>">
                            <td><input class="form-check-input" type="checkbox" value="<?= esc($u['id']) ?>"></td>
                            <td onclick='openEditModal(<?= $unitJson ?>)' class="text-secondary">
                                <?= esc($u['section_name'] ?? 'Sin sección') ?>
                            </td>
                            <td onclick='openEditModal(<?= $unitJson ?>)' class="text-dark fw-medium">
                                <?= esc($u['unit_number']) ?>
                            </td>
                            <td onclick='openEditModal(<?= $unitJson ?>)' class="text-dark">
                                $<?= number_format((float) ($u['maintenance_fee'] ?? 0), 2) ?></td>
                            <td onclick='openEditModal(<?= $unitJson ?>)' class="text-center text-dark">
                                <?= count($u['owners'] ?? []) + count($u['tenants'] ?? []) ?>
                            </td>
                            <td onclick='openEditModal(<?= $unitJson ?>)' class="text-dark small lh-sm">
                                <?php foreach ((array) ($u['owners'] ?? []) as $owner): ?>
                                    <div class="mb-1"><?= esc($owner['name']) ?></div>
                                <?php endforeach; ?>
                            </td>
                            <td onclick='openEditModal(<?= $unitJson ?>)' class="text-dark small lh-sm">
                                <?php foreach ((array) ($u['tenants'] ?? []) as $tenant): ?>
                                    <div class="mb-1"><?= esc($tenant['name']) ?></div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<div class="modal fade" id="modalIndividual" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content koti-modal-content">
            <div class="modal-header koti-modal-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="modal-title fw-bold">Crear Nueva Unidad</h5>
                    <small class="text-muted">Agrega una nueva unidad a tu propiedad con información básica y
                        configuración de cuotas.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('admin/unidades/crear') ?>" method="POST">
                <div class="modal-body koti-modal-body p-4">

                    <div class="create-tabs">
                        <div class="create-tab active" id="tab-info" onclick="switchCreateTab('info')">Info</div>
                        <div class="create-tab" id="tab-cuota" onclick="switchCreateTab('cuota')">Cuota</div>
                    </div>

                    <!-- VISTA INFO -->
                    <div id="view-info" class="row">
                        <div class="col-md-6 pe-md-4">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">Nombre de la Unidad *</label>
                                <input type="text" name="unit_number" class="form-control"
                                    placeholder="ej. 101, A-1, Suite 5" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">Tipo *</label>
                                <select name="type" class="form-select" required>
                                    <option value="apartment">Apartamento</option>
                                    <option value="house">Casa</option>
                                    <option value="lot">Lote</option>
                                    <option value="commercial">Local Comercial</option>
                                    <option value="office">Oficina</option>
                                    <option value="warehouse">Bodega</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">Área</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="area" class="form-control" placeholder="0">
                                    <span class="input-group-text bg-white bg-light text-muted">m²</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">% Indiviso</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" name="indiviso_percentage" class="form-control"
                                        placeholder="ej. 12.5">
                                    <span class="input-group-text bg-white bg-light text-muted">%</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">Sección</label>
                                <select name="section_id" class="form-select">
                                    <option value="">Sin sección</option>
                                    <?php foreach ($sections as $s): ?>
                                        <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 ps-md-4">
                            <div class="side-info-panel">
                                <div class="info-item">
                                    <div class="info-icon bg-light-blue"><i class="bi bi-info-circle"></i></div>
                                    <div>
                                        <div class="info-title">Comenzando</div>
                                        <div class="info-desc">Complete la información básica sobre esta unidad. Esto
                                            ayuda a los residentes y al personal a identificar propiedades dentro de su
                                            edificio o complejo.</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon bg-light-blue" style="background:#e0e7ff; color:#4f46e5;"><i
                                            class="bi bi-123"></i></div>
                                    <div>
                                        <div class="info-title">Nomenclatura de Unidad</div>
                                        <div class="info-desc">Elija un identificador claro y único. Use números (101,
                                            202), letras (A, B) o combinaciones (A-101) que coincidan con el sistema de
                                            su propiedad.</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon bg-light-green"><i class="bi bi-house-door"></i></div>
                                    <div>
                                        <div class="info-title">Tipo de Propiedad</div>
                                        <div class="info-desc">Seleccione el tipo de propiedad que mejor describe esta
                                            unidad. Esto afecta los campos disponibles como el número de piso.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- VISTA CUOTA -->
                    <div id="view-cuota" class="row d-none">
                        <div class="col-md-6 pe-md-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold text-dark small mb-1">Cuota de Mantenimiento</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white bg-light text-muted">$</span>
                                    <input type="number" step="0.01" name="maintenance_fee" class="form-control"
                                        placeholder="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-1">Fecha de Inicio de la Nueva
                                    Cuota</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white bg-light text-muted"><i
                                            class="bi bi-calendar3"></i></span>
                                    <input type="text" id="create-fee-start-month" name="fee_start_month"
                                        class="form-control" placeholder="Selecciona un mes..."
                                        value="<?= esc(date('Y-m')) ?>">
                                </div>
                                <div class="form-text mt-2 small text-muted">Esta fecha establece la fecha de inicio
                                    para la nueva cuota</div>
                            </div>
                        </div>

                        <div class="col-md-6 ps-md-4">
                            <div class="side-info-panel">
                                <div class="info-item">
                                    <div class="info-icon bg-light-blue"><i class="bi bi-info-circle"></i></div>
                                    <div>
                                        <div class="info-title">Cuotas Mensuales</div>
                                        <div class="info-desc">Configure los cargos recurrentes para esta unidad.
                                            Siempre puede ajustarlos más tarde si las circunstancias cambian.</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon bg-light-green"><i class="bi bi-currency-dollar"></i></div>
                                    <div>
                                        <div class="info-title">Monto de la Cuota</div>
                                        <div class="info-desc">Ingrese la cuota de mantenimiento mensual. Esto se usa
                                            típicamente para el mantenimiento de áreas comunes, amenidades y servicios
                                            del edificio. Deje en $0 si no aplica.</div>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon bg-light-purple"><i class="bi bi-calendar2-minus"></i></div>
                                    <div>
                                        <div class="info-title">Fecha de Inicio de Facturación</div>
                                        <div class="info-desc">Elija cuándo debe comenzar la facturación. Esto suele ser
                                            la fecha de mudanza o el inicio del próximo ciclo de facturación.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer koti-modal-footer d-flex justify-content-end">
                    <button type="button" class="btn btn-koti-light rounded-pill px-4 me-2"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btn-create-unit" class="btn btn-secondary rounded-pill px-4"
                        style="background-color: #94a3b8; border:none;">Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Unidad -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content koti-modal-content">
            <div class="modal-header koti-modal-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="modal-title fw-bold">Editar Unidad</h5>
                    <small class="text-muted">Actualiza la información de la unidad, administra residentes, agrega notas
                        o configura cuotas.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="<?= base_url('admin/unidades/editar') ?>" method="POST" id="formEditarUnidad">
                <div class="modal-body p-0">
                    <input type="hidden" name="id" id="edit-unit-id">
                    <input type="hidden" name="owners" id="hidden-owners">
                    <input type="hidden" name="tenants" id="hidden-tenants">
                    <div class="modal-body koti-modal-body p-4">

                        <div class="create-tabs">
                            <div class="create-tab active" id="tab-edit-info" onclick="switchEditTab('info')">Info</div>
                            <div class="create-tab" id="tab-edit-cuota" onclick="switchEditTab('cuota')">Cuota</div>
                            <div class="create-tab" id="tab-edit-residentes" onclick="switchEditTab('residentes')">
                                Residentes</div>
                            <div class="create-tab" id="tab-edit-notas" onclick="switchEditTab('notas')">Notas</div>
                            <div class="create-tab" id="tab-edit-eliminar" onclick="switchEditTab('eliminar')">Eliminar
                            </div>
                        </div>

                        <!-- VISTA INFO -->
                        <div id="view-edit-info" class="row">
                            <div class="col-md-6 pe-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small mb-1">Nombre de la Unidad *</label>
                                    <input type="text" name="unit_number" id="edit-unit-number" class="form-control"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small mb-1">Tipo *</label>
                                    <select name="type" id="edit-type" class="form-select" required>
                                        <option value="apartment">Apartamento</option>
                                        <option value="house">Casa</option>
                                        <option value="lot">Lote</option>
                                        <option value="commercial">Local Comercial</option>
                                        <option value="office">Oficina</option>
                                        <option value="warehouse">Bodega</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small mb-1">Piso</label>
                                    <input type="number" name="floor" id="edit-floor" class="form-control"
                                        placeholder="1">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small mb-1">Área</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="area" id="edit-area"
                                            class="form-control">
                                        <span class="input-group-text bg-white bg-light text-muted">m²</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small mb-1">% Indiviso</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="indiviso_percentage" id="edit-indiviso"
                                            class="form-control">
                                        <span class="input-group-text bg-white bg-light text-muted">%</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small mb-1">Sección</label>
                                    <select name="section_id" id="edit-section" class="form-select">
                                        <option value="">Sin sección</option>
                                        <?php foreach ($sections as $s): ?>
                                            <option value="<?= $s['id'] ?>"><?= esc($s['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6 ps-md-4">
                                <div class="side-info-panel">
                                    <div class="info-item">
                                        <div class="info-icon bg-light-blue"><i class="bi bi-info-circle"></i></div>
                                        <div>
                                            <div class="info-title">Comenzando</div>
                                            <div class="info-desc">Complete la información básica sobre esta unidad.
                                                Esto ayuda a los residentes a identificar propiedades dentro de su
                                                edificio.</div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-icon bg-light-blue" style="background:#e0e7ff; color:#4f46e5;">
                                            <i class="bi bi-building"></i>
                                        </div>
                                        <div>
                                            <div class="info-title">Nomenclatura de Unidad</div>
                                            <div class="info-desc">Elija un identificador claro y único. Use números o
                                                letras que coincidan con el sistema de su propiedad.</div>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-icon bg-light-green"><i class="bi bi-house-door"></i></div>
                                        <div>
                                            <div class="info-title">Tipo de Propiedad</div>
                                            <div class="info-desc">Seleccione el tipo de propiedad que mejor describe
                                                esta unidad.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- VISTA CUOTA -->
                        <div id="view-edit-cuota" class="row d-none">
                            <div class="col-md-6 pe-md-4">
                                <h6 class="fw-bold mb-4">Información Básica de Cuota</h6>
                                <div class="mb-4">
                                    <label class="form-label fw-bold text-dark small mb-1">Cuota de
                                        Mantenimiento</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white bg-light text-muted">$</span>
                                        <input type="number" step="0.01" name="maintenance_fee"
                                            id="edit-maintenance-fee" class="form-control" placeholder="0">
                                    </div>
                                    <div class="form-text mt-2 small text-muted">Este monto de cuota se cobrará a la
                                        unidad regularmente en la fecha de vencimiento especificada</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold text-dark small mb-1">Fecha de Inicio de la Nueva
                                        Cuota</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white bg-light text-muted"><i
                                                class="bi bi-calendar3"></i></span>
                                        <input type="text" id="edit-fee-start-month" name="fee_start_month"
                                            class="form-control" placeholder="Selecciona un mes...">
                                    </div>
                                    <div class="form-text mt-2 small text-muted">Esta fecha establece la fecha de inicio
                                        para la nueva cuota</div>
                                </div>
                            </div>
                        </div>

                        <!-- VISTA RESIDENTES -->
                        <div id="view-edit-residentes" class="d-none">
                            <div class="mb-4" style="max-width: 300px;">
                                <label class="form-label fw-bold text-dark small mb-1">Tipo de Ocupación</label>
                                <div class="d-flex align-items-center">
                                    <div class="input-group me-2">
                                        <span class="input-group-text bg-white"><i class="bi bi-house-door"
                                                id="occupancy-icon"></i></span>
                                        <select class="form-select" id="edit-occupancy-type" name="occupancy_type"
                                            onchange="checkOccupancyChange()">
                                            <option value="owner_occupied">Habitado por Propietario</option>
                                            <option value="long_term_rent">Renta a Largo Plazo</option>
                                            <option value="short_term_rent">Renta Temporal</option>
                                            <option value="vacant">Desocupado</option>
                                        </select>
                                    </div>
                                    <button type="button" class="btn btn-secondary px-3 d-none text-nowrap"
                                        id="btn-confirm-occupancy" style="background-color: #475569; border:none;"
                                        onclick="confirmOccupancyChange()">
                                        <i class="bi bi-check2"></i> Confirmar
                                    </button>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                                <span class="fw-bold text-dark"><i class="bi bi-house-door me-2"
                                        style="color: #64748b;"></i> Propietarios <i
                                        class="bi bi-info-circle text-muted ms-1 small" style="opacity:0.6;"></i></span>
                                <button type="button" class="btn btn-sm text-dark bg-white border shadow-sm"
                                    style="border-radius: 6px; font-weight: 500;"
                                    onclick="openModalAgregarInquilino('owner')"><i
                                        class="bi bi-plus text-muted me-1"></i> Agregar</button>
                            </div>
                            <div id="edit-owners-list">
                                <!-- JS content -->
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                                <span class="fw-bold text-dark"><i class="bi bi-person me-2"
                                        style="color: #64748b;"></i> Inquilinos <i
                                        class="bi bi-info-circle text-muted ms-1 small" style="opacity:0.6;"></i></span>
                                <button type="button" class="btn btn-sm text-dark bg-white border shadow-sm"
                                    style="border-radius: 6px; font-weight: 500;"
                                    onclick="openModalAgregarInquilino('tenant')"><i
                                        class="bi bi-plus text-muted me-1"></i> Agregar</button>
                            </div>
                            <div id="edit-tenants-list">
                                <!-- JS content -->
                            </div>
                        </div>

                        <!-- VISTA NOTAS -->
                        <div id="view-edit-notas" class="d-none">
                            <div id="unit-notes-container" style="max-height: 300px; overflow-y: auto;" class="mb-4">
                                <!-- JS content -->
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold text-dark small mb-2">Nueva nota</label>
                                <textarea id="new-unit-note" class="form-control" rows="3"
                                    placeholder="Escribe una nota sobre esta unidad..."></textarea>
                            </div>
                            <div class="text-end">
                                <button type="button" id="btn-add-unit-note" onclick="addUnitNote()"
                                    class="btn btn-secondary" style="background-color: #94a3b8; border:none;"><i
                                        class="bi bi-plus"></i> Agregar Nota</button>
                            </div>
                        </div>

                        <!-- VISTA ELIMINAR -->
                        <div id="view-edit-eliminar" class="d-none">
                            <h5 class="fw-bold text-danger mb-2">Eliminar Unidad</h5>
                            <p class="text-dark small mb-4">¿Está seguro que desea eliminar la unidad <span
                                    id="delete-unit-name" class="fw-bold"></span>? Esta acción no se puede deshacer.</p>

                            <div class="alert alert-danger"
                                style="background-color: #fef2f2; border-color: #fca5a5; color: #ef4444;">
                                <small>Esto eliminará permanentemente la unidad y todos los datos asociados incluyendo
                                    visitantes, paquetes, códigos QR, registros de acceso, transacciones financieras y
                                    auditorías de pago.</small>
                            </div>

                            <div class="mb-4 mt-4">
                                <label class="form-label fw-bold text-dark small mb-1">Escribe <span
                                        class="text-danger">ELIMINAR</span> para confirmar</label>
                                <input type="text" class="form-control" placeholder="ELIMINAR"
                                    id="delete-confirm-input">
                            </div>

                            <button type="button" onclick="confirmDeleteUnit()" class="btn btn-danger w-100"
                                style="background-color: #f87171; border:none;" id="btn-delete-unit" disabled>
                                <i class="bi bi-trash"></i> Eliminar Unidad
                            </button>
                        </div>

                    </div>
                    <div class="modal-footer koti-modal-footer d-flex justify-content-end">
                        <button type="button" class="btn btn-koti-light rounded-pill px-4 me-2"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btn-save-unit" class="btn btn-secondary rounded-pill px-4"
                            style="background-color: #94a3b8; border:none; transition: background-color 0.3s;">Guardar</button>
                    </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmar Desocupado -->
<div class="modal fade" id="modalConfirmarDesocupado" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3.5rem; color: #f59e0b;"></i>
                </div>
                <h5 class="fw-bold mb-3" style="color: #ea580c; font-size: 1.25rem;">¿Marcar Unidad como Desocupada?
                </h5>
                <p class="text-secondary small mb-4 px-2" style="line-height: 1.5;">Esto eliminará a todos los
                    propietarios e inquilinos de esta unidad. Esta acción no se puede deshacer.</p>
                <div class="d-flex flex-column gap-2 mt-2">
                    <button type="button" class="btn btn-danger w-100 rounded-3 py-2 fw-bold shadow-sm"
                        style="background-color: #ef4444; border:none;" onclick="executeDesocupar()">Sí, Marcar como
                        Desocupada</button>
                    <button type="button" class="btn btn-link text-muted fw-medium text-decoration-none small py-2"
                        data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmar Eliminar Unidad -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" aria-hidden="true" style="z-index: 1070;">
    <div class="modal-dialog modal-dialog-centered modal-sm" style="max-width: 400px;">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1.25rem;">
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <i class="bi bi-exclamation-triangle-fill" style="font-size: 3.5rem; color: #dc2626;"></i>
                </div>
                <h5 class="fw-bold mb-3" style="color: #991b1b; font-size: 1.25rem;">¿Eliminar Unidad Permanentemente?
                </h5>
                <p class="text-secondary small mb-4 px-2" style="line-height: 1.5;">Se eliminarán todos los datos
                    asociados (pagos, registros de acceso, residentes). Esta acción es irreversible.</p>
                <div class="d-flex flex-column gap-2 mt-2">
                    <button type="button" class="btn btn-danger w-100 rounded-3 py-2 fw-bold shadow-sm"
                        style="background-color: #dc2626; border:none;" onclick="executeEliminarUnit()">Sí, Eliminar
                        Unidad</button>
                    <button type="button" class="btn btn-link text-muted fw-medium text-decoration-none small py-2"
                        data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Inquilino / Propietario -->
<div class="modal fade" id="modalAgregarResidente" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content koti-modal-content">
            <div class="modal-header border-0 pb-0 d-flex justify-content-between">
                <div>
                    <h5 class="modal-title fw-bold" id="titleAgregarResidente">Agregar Residente</h5>
                    <small class="text-muted">Busca un usuario registrado o ingresa los datos manualmente.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 pt-3">
                <div class="d-flex bg-light p-1 rounded-3 mb-4">
                    <button type="button" id="tab-add-search" onclick="switchAddResidentTab('search')"
                        class="btn btn-white flex-fill fw-medium shadow-sm border border-secondary border-opacity-25 py-2 active"
                        style="font-size: 0.85rem;"><i class="bi bi-people me-1"></i> Buscar Usuarios</button>
                    <button type="button" id="tab-add-manual" onclick="switchAddResidentTab('manual')"
                        class="btn text-muted flex-fill fw-medium border-0 py-2" style="font-size: 0.85rem;"><i
                            class="bi bi-person me-1"></i> Ingresar Manualmente</button>
                </div>

                <!-- VISTA: BUSCAR USUARIO -->
                <div id="view-add-search">
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="inputSearchResident" class="form-control"
                            placeholder="Buscar usuarios por nombre o correo...">
                    </div>

                    <div class="text-center text-muted small py-4" id="search-resident-status">
                        Escribe al menos 2 caracteres para buscar
                    </div>

                    <!-- Mensaje de Error (Duplicados, etc) -->
                    <div id="search-resident-error" class="alert alert-danger d-none py-2 px-3 small mt-3 mb-0"
                        role="alert">
                    </div>

                    <!-- Resultados de Búsqueda de Ejemplo -->
                    <div id="search-resident-results" class="d-none" style="max-height: 250px; overflow-y: auto;">
                    </div>
                </div>

                <!-- VISTA: INGRESO MANUAL -->
                <div id="view-add-manual" class="d-none">
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark small mb-1">Nombre *</label>
                        <input type="text" id="manual-res-name" class="form-control" placeholder="Nombre completo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-medium text-dark small mb-1">Correo</label>
                        <input type="email" id="manual-res-email" class="form-control" placeholder="correo@ejemplo.com">
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-medium text-dark small mb-1">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white px-2">
                                <img src="https://flagcdn.com/w20/mx.png" width="20" alt="MX">
                                <i class="bi bi-chevron-expand ms-1 text-muted" style="font-size: 0.7rem;"></i>
                            </span>
                            <input type="text" id="manual-res-phone" class="form-control"
                                placeholder="+52 55 1234 5678">
                        </div>
                    </div>
                    <button type="button" id="btn-submit-manual-res" onclick="submitManualResident()"
                        class="btn btn-secondary w-100 py-2 fw-medium" style="background-color: #94a3b8; border:none;">
                        <i class="bi bi-person-plus me-1"></i> Agregar <span id="text-btn-manual-res">Propietario</span>
                    </button>
                </div>

            </div>
            <div class="modal-footer border-0 pt-0 d-flex justify-content-end" id="footer-add-resident">
                <button type="button" class="btn btn-outline-secondary rounded-2 px-4"
                    data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Importar/Exportar -->
<div class="modal fade" id="modalCsv" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <div class="modal-header border-0 pb-0 px-4 pt-4 d-flex justify-content-between align-items-start">
                <h5 class="modal-title fw-bold" style="font-size: 1.15rem;">Importar/Exportar Unidades</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body px-4 pb-4 pt-3">
                <!-- Pill Toggle Tabs -->
                <div class="csv-pill-tabs d-flex mb-4 p-1 rounded-3"
                    style="background: #f1f5f9; border: 1px solid #e2e8f0;">
                    <button type="button" class="csv-pill-tab active flex-fill" id="tab-import"
                        onclick="switchCsvTab('import')">Importar</button>
                    <button type="button" class="csv-pill-tab flex-fill" id="tab-export"
                        onclick="switchCsvTab('export')">Exportar</button>
                </div>

                <!-- ========== VISTA IMPORTAR ========== -->
                <div id="view-import">
                    <div id="import-upload-section">
                        <div class="row g-4">
                            <!-- Columna Izquierda: Cómo Funciona -->
                            <div class="col-md-7">
                                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;">Cómo Funciona</h6>

                                <!-- Paso 1 -->
                                <div class="d-flex align-items-start mb-3">
                                    <div class="csv-step-badge me-3 flex-shrink-0">1</div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">Descargar Plantilla
                                        </div>
                                        <p class="text-muted mb-2" style="font-size: 0.78rem; line-height: 1.4;">Obtén
                                            un archivo CSV de muestra con el formato correcto para importar unidades</p>
                                        <a href="<?= base_url('admin/unidades/export') ?>"
                                            class="btn btn-success btn-sm rounded-pill px-3 py-1 fw-medium"
                                            style="font-size: 0.8rem;">
                                            <i class="bi bi-download me-1"></i> Descargar Plantilla
                                        </a>
                                    </div>
                                </div>

                                <!-- Paso 2 -->
                                <div class="d-flex align-items-start mb-3">
                                    <div class="csv-step-badge me-3 flex-shrink-0">2</div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">Editar en Hoja de
                                            Cálculo</div>
                                        <p class="text-muted mb-0" style="font-size: 0.78rem; line-height: 1.4;">Abre en
                                            Excel/Sheets. Agrega, elimina o modifica unidades y cuotas mensuales según
                                            necesites</p>
                                    </div>
                                </div>

                                <!-- Paso 3 -->
                                <div class="d-flex align-items-start mb-4">
                                    <div class="csv-step-badge me-3 flex-shrink-0">3</div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.88rem;">Importar Cambios
                                        </div>
                                        <p class="text-muted mb-0" style="font-size: 0.78rem; line-height: 1.4;">Sube el
                                            archivo para reemplazar todas las unidades existentes. Es un reemplazo
                                            completo, no una fusión</p>
                                    </div>
                                </div>

                                <!-- Botón Seleccionar CSV -->
                                <div class="position-relative">
                                    <input type="file" id="csvFileInput" accept=".csv" class="d-none">
                                    <button type="button" class="btn btn-success rounded-pill px-4 py-2 fw-medium"
                                        style="font-size: 0.85rem;"
                                        onclick="document.getElementById('csvFileInput').click();">
                                        <i class="bi bi-upload me-2"></i> Seleccionar Archivo CSV
                                    </button>
                                    <span id="csv-file-name" class="text-muted small ms-2 d-none"></span>
                                </div>
                            </div>

                            <!-- Columna Derecha: Casos de Uso -->
                            <div class="col-md-5">
                                <h6 class="fw-bold text-dark mb-3" style="font-size: 0.9rem;">Casos de Uso Comunes</h6>

                                <div class="csv-use-case-card mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-plus-lg text-muted me-3" style="font-size: 1rem;"></i>
                                        <div>
                                            <div class="fw-semibold text-dark" style="font-size: 0.82rem;">Configuración
                                                Inicial</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Configurar todas las
                                                unidades al iniciar tu propiedad</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="csv-use-case-card mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-arrow-repeat text-muted me-3" style="font-size: 1rem;"></i>
                                        <div>
                                            <div class="fw-semibold text-dark" style="font-size: 0.82rem;">
                                                Actualizaciones Masivas</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Actualizar cuotas
                                                mensuales de múltiples unidades a la vez</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="csv-use-case-card mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-arrow-left-right text-muted me-3" style="font-size: 1rem;"></i>
                                        <div>
                                            <div class="fw-semibold text-dark" style="font-size: 0.82rem;">
                                                Reorganización</div>
                                            <div class="text-muted" style="font-size: 0.72rem;">Renombrar o
                                                reestructurar el sistema de numeración de unidades</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Advertencia Crítica -->
                                <div class="csv-critical-warning">
                                    <div class="d-flex align-items-start">
                                        <i class="bi bi-exclamation-circle-fill me-2 mt-1 flex-shrink-0"
                                            style="font-size: 1rem;"></i>
                                        <div>
                                            <div class="fw-bold mb-1" style="font-size: 0.8rem;">Advertencia Crítica
                                            </div>
                                            <div style="font-size: 0.72rem; line-height: 1.4;">Esto reemplaza todas las
                                                unidades. Las unidades existentes y sus datos (pagos, residentes,
                                                registros de acceso) serán eliminados permanentemente.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vista Previsualización -->
                    <div id="import-preview-section" class="d-none">
                        <h6 class="fw-bold text-dark mb-3" style="font-size: 0.95rem;">Resumen de Importación</h6>

                        <!-- Stats Cards -->
                        <div class="row g-2 mb-3">
                            <div class="col-3">
                                <div class="csv-stat-card csv-stat-green">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-plus-lg me-1" style="font-size: 0.7rem;"></i>
                                        <span style="font-size: 0.7rem;">Nuevas</span>
                                    </div>
                                    <div class="fw-bold" style="font-size: 1.3rem;" id="preview-new-count">0</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="csv-stat-card csv-stat-yellow">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-arrow-repeat me-1" style="font-size: 0.7rem;"></i>
                                        <span style="font-size: 0.7rem;">Actualizadas</span>
                                    </div>
                                    <div class="fw-bold" style="font-size: 1.3rem;" id="preview-updated-count">0</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="csv-stat-card csv-stat-red">
                                    <div class="d-flex align-items-center mb-1">
                                        <i class="bi bi-trash me-1" style="font-size: 0.7rem;"></i>
                                        <span style="font-size: 0.7rem;">A Eliminar</span>
                                    </div>
                                    <div class="fw-bold" style="font-size: 1.3rem;" id="preview-removed-count">0</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="csv-stat-card csv-stat-neutral">
                                    <div class="d-flex align-items-center mb-1">
                                        <span style="font-size: 0.85rem; line-height: 1;">—</span>
                                        <span class="ms-1" style="font-size: 0.7rem;">Total Final</span>
                                    </div>
                                    <div class="fw-bold" style="font-size: 1.3rem;" id="preview-total-count">0</div>
                                </div>
                            </div>
                        </div>

                        <div id="preview-remove-warning" class="alert alert-danger small py-2 d-none rounded-3">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <span id="preview-remove-text"></span>
                        </div>

                        <!-- Preview Table -->
                        <h6 class="fw-bold text-dark mb-2" style="font-size: 0.85rem;">Previsualización (<span
                                id="preview-preview-count">0</span> unidades)</h6>
                        <div class="table-responsive border rounded-3" style="max-height: 260px;">
                            <table class="table table-sm mb-0 align-middle">
                                <thead style="position: sticky; top: 0; background: #f8fafc; z-index: 10;">
                                    <tr class="small" style="color: #64748b; font-size: 0.75rem;">
                                        <th class="fw-semibold ps-3" style="min-width: 100px;">Estado</th>
                                        <th class="fw-semibold">Nombre</th>
                                        <th class="fw-semibold">Cuota HOA</th>
                                        <th class="fw-semibold">% Indiviso</th>
                                        <th class="fw-semibold">Sección</th>
                                        <th class="fw-semibold">Cambios</th>
                                    </tr>
                                </thead>
                                <tbody id="preview-table-body" class="small">
                                </tbody>
                            </table>
                        </div>

                        <!-- Actions -->
                        <form action="<?= base_url('admin/unidades/importar') ?>" method="POST"
                            enctype="multipart/form-data" class="mt-3" id="form-import-submit">
                            <input type="file" name="file_csv" id="hidden-file-input" class="d-none">
                            <div class="d-flex justify-content-end align-items-center gap-2">
                                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                                    onclick="resetImportView()">Cancelar</button>
                                <button type="submit" class="btn btn-dark rounded-pill px-4 fw-medium"
                                    id="btn-import-confirm">
                                    Importar <span id="btn-import-count">0</span> Unidades
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ========== VISTA EXPORTAR ========== -->
                <div id="view-export" class="d-none">
                    <h6 class="fw-bold text-dark mb-2" style="font-size: 0.95rem;">Qué Incluye la Exportación</h6>
                    <p class="text-muted mb-3" style="font-size: 0.82rem; line-height: 1.5;">Descarga todas las unidades
                        existentes con sus cuotas mensuales en un archivo CSV que puedes abrir en Excel o Google Sheets.
                    </p>

                    <div class="border rounded-3 p-3 mb-3">
                        <p class="fw-semibold text-dark mb-3" style="font-size: 0.82rem;">El archivo CSV contiene:</p>

                        <div class="d-flex align-items-start mb-3">
                            <span class="csv-export-dot me-3 mt-1 flex-shrink-0"></span>
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.82rem;">Nombre de Unidad</div>
                                <div class="text-muted" style="font-size: 0.72rem;">El identificador único de cada
                                    unidad (ej., 101, A-1, Suite 5)</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <span class="csv-export-dot me-3 mt-1 flex-shrink-0"></span>
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.82rem;">Cuota Mensual (Cuota HOA)
                                </div>
                                <div class="text-muted" style="font-size: 0.72rem;">El monto de la cuota de
                                    mantenimiento o HOA recurrente para cada unidad</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start mb-3">
                            <span class="csv-export-dot me-3 mt-1 flex-shrink-0"></span>
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.82rem;">% Indiviso</div>
                                <div class="text-muted" style="font-size: 0.72rem;">El porcentaje de propiedad indivisa
                                    asignado a cada unidad</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-start">
                            <span class="csv-export-dot me-3 mt-1 flex-shrink-0"></span>
                            <div>
                                <div class="fw-semibold text-dark" style="font-size: 0.82rem;">Sección</div>
                                <div class="text-muted" style="font-size: 0.72rem;">La torre, edificio o sección a la
                                    que pertenece la unidad</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center text-muted mb-3 px-1" style="font-size: 0.78rem;">
                        <i class="bi bi-info-circle me-2"></i>
                        Las unidades se ordenan automáticamente alfabéticamente por nombre en el archivo exportado
                    </div>

                    <div class="text-center">
                        <p class="text-muted mb-2" style="font-size: 0.82rem;">Se exportarán
                            <strong><?= count($units) ?></strong> unidades
                        </p>
                        <a href="<?= base_url('admin/unidades/export') ?>"
                            class="btn btn-dark rounded-pill px-4 py-2 fw-medium" style="font-size: 0.85rem;">
                            <i class="bi bi-download me-2"></i> Descargar CSV
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modal Footer: Cancelar -->
            <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex justify-content-end">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                    data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edición Masiva -->
<div class="modal fade" id="modalMasivo" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 1rem;">
            <!-- Header -->
            <div
                class="modal-header koti-modal-header border-0 d-flex justify-content-between align-items-start px-4 pt-4 pb-2">
                <div>
                    <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                        <i class="bi bi-pencil" style="color: #64748b;"></i> Edición Masiva de Unidades
                    </h5>
                    <small class="text-muted">Edita múltiples unidades a la vez. Haz clic en las celdas para editar, usa
                        Tab para navegar entre celdas. Mantén Shift y haz clic para seleccionar un rango.</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body -->
            <div class="modal-body px-4 pb-3 pt-2">
                <!-- Bulk Action Bar (hidden by default) -->
                <div class="me-bulk-bar" id="me-bulk-bar">
                    <div class="mb-2">
                        <span class="me-bulk-count" id="me-bulk-count">0</span>
                        <span class="me-bulk-label"> seleccionadas</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 flex-wrap">
                        <div class="d-flex align-items-center gap-2">
                            <span class="me-bulk-label">Cuota de Mantenimiento:</span>
                            <div class="d-flex align-items-center">
                                <span class="me-bulk-label me-1">$</span>
                                <input type="number" step="0.01" class="me-bulk-input" id="me-bulk-fee-input"
                                    placeholder="0.00">
                            </div>
                            <button type="button" class="me-apply-btn" id="me-apply-fee-btn"
                                onclick="meApplyBulk('fee')">
                                <i class="bi bi-check2"></i> Aplicar
                            </button>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="me-bulk-label">Sección:</span>
                            <select class="me-bulk-select" id="me-bulk-section-input">
                                <option value="">Seleccionar...</option>
                                <?php if (isset($sections)):
                                    foreach ($sections as $s): ?>
                                        <option value="<?= esc($s['id']) ?>" data-name="<?= esc($s['name']) ?>">
                                            <?= esc($s['name']) ?>
                                        </option>
                                    <?php endforeach; endif; ?>
                            </select>
                            <button type="button" class="me-apply-btn" id="me-apply-section-btn"
                                onclick="meApplyBulk('section')">
                                <i class="bi bi-check2"></i> Aplicar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="border rounded-3" style="max-height: 55vh; overflow-y: auto;">
                    <table class="mass-edit-table" id="me-table">
                        <thead>
                            <tr>
                                <th style="width: 42px;">
                                    <input type="checkbox" class="me-checkbox" id="me-select-all">
                                </th>
                                <th>Nombre ↕</th>
                                <th style="width: 44px;"></th>
                                <th>Cuota de Mantenimiento ↕</th>
                                <th style="width: 44px;"></th>
                                <th>Sección ↕</th>
                                <th style="width: 100px; text-align: center;"></th>
                            </tr>
                        </thead>
                        <tbody id="me-table-body">
                            <?php if (!empty($units)):
                                foreach ($units as $u): ?>
                                    <tr class="mass-edit-row" data-id="<?= esc($u['id']) ?>"
                                        data-original-name="<?= esc($u['unit_number']) ?>"
                                        data-original-fee="<?= esc($u['maintenance_fee'] ?? 0) ?>"
                                        data-original-section="<?= esc($u['section_id'] ?? '') ?>"
                                        data-original-section-name="<?= esc($u['section_name'] ?? '') ?>">
                                        <!-- Checkbox -->
                                        <td>
                                            <input type="checkbox" class="me-checkbox me-row-check"
                                                value="<?= esc($u['id']) ?>">
                                        </td>
                                        <!-- Nombre -->
                                        <td class="me-name-cell">
                                            <div class="me-cell-display" id="me-name-display-<?= esc($u['id']) ?>">
                                                <span class="me-cell-value fw-medium"><?= esc($u['unit_number']) ?></span>
                                            </div>
                                            <input type="text" class="me-cell-input d-none"
                                                id="me-name-input-<?= esc($u['id']) ?>" value="<?= esc($u['unit_number']) ?>"
                                                data-field="name" data-id="<?= esc($u['id']) ?>" onblur="meCellBlur(this)"
                                                onkeydown="meCellKeydown(event, this)">
                                        </td>
                                        <!-- Pencil for Name -->
                                        <td>
                                            <button type="button" class="me-pencil-btn"
                                                onclick="meActivateCell('<?= esc($u['id']) ?>', 'name')" title="Editar nombre">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                        <!-- Cuota -->
                                        <td class="me-fee-cell">
                                            <div class="me-cell-display" id="me-fee-display-<?= esc($u['id']) ?>">
                                                <span
                                                    class="me-cell-value">$<?= number_format((float) ($u['maintenance_fee'] ?? 0), 2) ?></span>
                                            </div>
                                            <input type="number" step="0.01" class="me-cell-input d-none"
                                                id="me-fee-input-<?= esc($u['id']) ?>"
                                                value="<?= esc($u['maintenance_fee'] ?? 0) ?>" data-field="fee"
                                                data-id="<?= esc($u['id']) ?>" onblur="meCellBlur(this)"
                                                onkeydown="meCellKeydown(event, this)">
                                        </td>
                                        <!-- Pencil for Fee -->
                                        <td>
                                            <button type="button" class="me-pencil-btn"
                                                onclick="meActivateCell('<?= esc($u['id']) ?>', 'fee')" title="Editar cuota">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                        </td>
                                        <!-- Sección (always a select) -->
                                        <td class="me-section-cell">
                                            <select class="me-section-select" id="me-section-input-<?= esc($u['id']) ?>"
                                                data-field="section" data-id="<?= esc($u['id']) ?>"
                                                onchange="meSectionChanged(this)">
                                                <option value="">Sin sección</option>
                                                <?php if (isset($sections)):
                                                    foreach ($sections as $s): ?>
                                                        <option value="<?= esc($s['id']) ?>" <?= ($u['section_id'] == $s['id']) ? 'selected' : '' ?>><?= esc($s['name']) ?></option>
                                                    <?php endforeach; endif; ?>
                                            </select>
                                        </td>
                                        <!-- Status Badge -->
                                        <td class="text-center me-status-cell" id="me-status-<?= esc($u['id']) ?>"></td>
                                    </tr>
                                <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer d-flex justify-content-between align-items-center px-4 py-3"
                style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                <span class="me-footer-status" id="me-footer-status">Sin cambios</span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4"
                        data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-secondary rounded-pill px-4 fw-medium disabled"
                        id="me-save-btn" onclick="meSaveAll()"
                        style="background-color: #94a3b8; border: none; transition: all 0.3s ease;">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function switchCsvTab(tab) {
        const tabImport = document.getElementById('tab-import');
        const tabExport = document.getElementById('tab-export');
        const viewImport = document.getElementById('view-import');
        const viewExport = document.getElementById('view-export');
        const modalFooter = document.querySelector('#modalCsv .modal-footer');

        if (tab === 'import') {
            tabImport.classList.add('active');
            tabExport.classList.remove('active');
            viewImport.classList.remove('d-none');
            viewExport.classList.add('d-none');
            // Show footer only when NOT in preview
            if (modalFooter) {
                const isPreview = !document.getElementById('import-preview-section').classList.contains('d-none');
                modalFooter.style.display = isPreview ? 'none' : '';
            }
        } else {
            tabExport.classList.add('active');
            tabImport.classList.remove('active');
            viewExport.classList.remove('d-none');
            viewImport.classList.add('d-none');
            if (modalFooter) modalFooter.style.display = 'none';
        }
    }

    function resetImportView() {
        document.getElementById('import-upload-section').classList.remove('d-none');
        document.getElementById('import-preview-section').classList.add('d-none');
        document.getElementById('csvFileInput').value = '';
        const fileNameEl = document.getElementById('csv-file-name');
        if (fileNameEl) { fileNameEl.classList.add('d-none'); fileNameEl.innerText = ''; }
        const modalFooter = document.querySelector('#modalCsv .modal-footer');
        if (modalFooter) modalFooter.style.display = '';
    }

    function switchCreateTab(tab) {
        if (tab === 'info') {
            document.getElementById('tab-info').classList.add('active');
            document.getElementById('tab-cuota').classList.remove('active');
            document.getElementById('view-info').classList.remove('d-none');
            document.getElementById('view-cuota').classList.add('d-none');
        } else {
            document.getElementById('tab-cuota').classList.add('active');
            document.getElementById('tab-info').classList.remove('active');
            document.getElementById('view-cuota').classList.remove('d-none');
            document.getElementById('view-info').classList.add('d-none');
        }
    }

    // Estado en memoria de los residentes y tipo de modal de agregar
    let currentOwners = [];
    let currentTenants = [];
    let modalAddResType = 'owner';
    let searchResTimeout = null;

    document.addEventListener("DOMContentLoaded", () => {
        // Interceptar el submit para inyectar JSON
        const form = document.getElementById("formEditarUnidad");
        if (form) {
            form.addEventListener('submit', function (e) {
                document.getElementById('hidden-owners').value = JSON.stringify(currentOwners);
                document.getElementById('hidden-tenants').value = JSON.stringify(currentTenants);
            });

            // Detectar cambios en inputs del form
            const inputs = form.querySelectorAll('input:not([type="hidden"]), select, textarea');
            inputs.forEach(input => {
                input.addEventListener('input', markAsModified);
                input.addEventListener('change', markAsModified);
            });
        }

        // Buscador de Usuarios
        const searchInput = document.getElementById('inputSearchResident');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                clearTimeout(searchResTimeout);
                const q = this.value.trim();

                const statusEl = document.getElementById('search-resident-status');
                const resultsEl = document.getElementById('search-resident-results');

                if (q.length < 2) {
                    statusEl.innerText = 'Escribe al menos 2 caracteres para buscar';
                    statusEl.classList.remove('d-none');
                    resultsEl.classList.add('d-none');
                    return;
                }

                statusEl.innerText = 'Buscando...';
                statusEl.classList.remove('d-none');
                resultsEl.classList.add('d-none');

                searchResTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch(`<?= base_url("admin/unidades/buscar-usuarios") ?>?q=${encodeURIComponent(q)}`);
                        const result = await response.json();

                        if (result.status === 200 && result.data.length > 0) {
                            statusEl.classList.add('d-none');
                            resultsEl.innerHTML = '';

                            result.data.forEach(user => {
                                let initials = user.name.substring(0, 2).toUpperCase();
                                let div = document.createElement('div');
                                div.className = 'resident-card mb-2 cursor-pointer border shadow-sm';
                                div.onclick = () => seleccionarResidenteMock(div, user.user_id, user.name, user.email);
                                div.innerHTML = `
                                    <div class="d-flex align-items-center">
                                        <div class="resident-initials" style="width:36px;height:36px;font-size:0.8rem;">${initials}</div>
                                        <div class="lh-1">
                                            <div class="fw-medium text-dark small mb-1">${user.name}</div>
                                            <div class="small text-muted" style="font-size: 0.75rem;">${user.email}</div>
                                        </div>
                                    </div>
                                    <i class="bi bi-person-plus text-muted" style="font-size: 1.2rem;"></i>
                                `;
                                resultsEl.appendChild(div);
                            });

                            resultsEl.classList.remove('d-none');
                        } else {
                            statusEl.innerText = 'No se encontraron usuarios';
                            statusEl.classList.remove('d-none');
                            resultsEl.classList.add('d-none');
                        }
                    } catch (err) {
                        statusEl.innerText = 'Error al buscar usuarios';
                    }
                }, 300);
            });
        }

        // Set default date logic
        if (typeof flatpickr !== 'undefined') {
            // This part needs to be attached to the flatpickr instance after it's initialized.
            // The openEditModal function is where flatpickr is initialized.
            // So, the onChange event listener should be added there.
        }
    });

    // Funciones para el Modal de Editar
    function markAsModified() {
        const btn = document.getElementById('btn-save-unit');
        if (btn) {
            btn.style.backgroundColor = '#1e293b'; // Dark color to indicate unsaved changes
            btn.innerHTML = 'Guardar Cambios <i class="bi bi-circle-fill ms-2 text-warning" style="font-size: 0.5rem;"></i>';
        }
    }

    function resetSaveButton() {
        const btn = document.getElementById('btn-save-unit');
        if (btn) {
            btn.style.backgroundColor = '#94a3b8'; // Original Slate color
            btn.innerHTML = 'Guardar';
        }
    }

    function switchEditTab(tab) {
        ['info', 'cuota', 'residentes', 'notas', 'eliminar'].forEach(t => {
            document.getElementById('tab-edit-' + t).classList.remove('active');
            document.getElementById('view-edit-' + t).classList.add('d-none');
        });
        document.getElementById('tab-edit-' + tab).classList.add('active');
        document.getElementById('view-edit-' + tab).classList.remove('d-none');
    }

    function renderResidentsUI() {
        const ownersList = document.getElementById('edit-owners-list');
        ownersList.innerHTML = '';
        if (currentOwners.length > 0) {
            let html = '<div class="res-list-container mb-4">';
            currentOwners.forEach((owner, idx) => {
                let initials = owner.name.substring(0, 2).toUpperCase();
                html += `
                <div class="res-list-item">
                    <div class="d-flex align-items-center">
                        <div class="resident-initials">${initials}</div>
                        <div class="lh-1">
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">${owner.name} <i class="bi bi-person ps-1" style="color: #16a34a; font-size: 1rem;"></i></div>
                            <div class="text-secondary" style="font-size: 0.8rem;"><i class="bi bi-envelope text-muted me-1" style="opacity:0.7;"></i> ${owner.email}</div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button type="button" onclick="moveResident(${idx}, 'owner')" class="res-action-btn move" title="Mover a Inquilinos"><i class="bi bi-arrow-left-right text-muted"></i></button>
                        <button type="button" onclick="removeResident(${idx}, 'owner')" class="res-action-btn del"><i class="bi bi-x text-danger" style="font-size:1.2rem; transform:scale(1.2);"></i></button>
                    </div>
                </div>`;
            });
            html += '</div>';
            ownersList.innerHTML = html;
        } else {
            ownersList.innerHTML = `
                <div class="bg-light rounded-3 p-4 text-center mb-4 border" style="border-color: #e2e8f0 !important;">
                    <i class="bi bi-person" style="font-size: 1.5rem; color: #cbd5e1;"></i>
                    <p class="text-muted small mt-2 mb-0">No hay propietarios asignados</p>
                </div>
            `;
        }

        const tenantsList = document.getElementById('edit-tenants-list');
        tenantsList.innerHTML = '';
        if (currentTenants.length > 0) {
            let html = '<div class="res-list-container mb-4">';
            currentTenants.forEach((tenant, idx) => {
                let initials = tenant.name.substring(0, 2).toUpperCase();
                html += `
                <div class="res-list-item">
                    <div class="d-flex align-items-center">
                        <div class="resident-initials">${initials}</div>
                        <div class="lh-1">
                            <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">${tenant.name} <i class="bi bi-person ps-1" style="color: #16a34a; font-size: 1rem;"></i></div>
                            <div class="text-secondary" style="font-size: 0.8rem;"><i class="bi bi-envelope text-muted me-1" style="opacity:0.7;"></i> ${tenant.email}</div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <button type="button" onclick="moveResident(${idx}, 'tenant')" class="res-action-btn move" title="Mover a Propietarios"><i class="bi bi-arrow-left-right text-muted"></i></button>
                        <button type="button" onclick="removeResident(${idx}, 'tenant')" class="res-action-btn del"><i class="bi bi-x text-danger" style="font-size:1.2rem; transform:scale(1.2);"></i></button>
                    </div>
                </div>`;
            });
            html += '</div>';
            tenantsList.innerHTML = html;
        } else {
            tenantsList.innerHTML = `
                <div class="bg-light rounded-3 p-4 text-center mb-4 border" style="border-color: #e2e8f0 !important;">
                    <i class="bi bi-people" style="font-size: 1.5rem; color: #cbd5e1;"></i>
                    <p class="text-muted small mt-2 mb-0">No hay inquilinos asignados</p>
                </div>
            `;
        }
    }

    function removeResident(index, role) {
        if (role === 'owner') {
            currentOwners.splice(index, 1);
        } else {
            currentTenants.splice(index, 1);
        }
        renderResidentsUI();
        markAsModified();
    }

    function moveResident(index, fromRole) {
        if (fromRole === 'owner') {
            let res = currentOwners.splice(index, 1)[0];
            currentTenants.push(res);
        } else {
            let res = currentTenants.splice(index, 1)[0];
            currentOwners.push(res);
        }
        renderResidentsUI();
        markAsModified();
    }

    function openEditModal(unitData) {
        document.getElementById('edit-unit-id').value = unitData.id;
        document.getElementById('edit-unit-number').value = unitData.unit_number;
        document.getElementById('edit-type').value = unitData.type || 'apartment';
        document.getElementById('edit-floor').value = unitData.floor || '';
        document.getElementById('edit-area').value = unitData.area || '';
        document.getElementById('edit-indiviso').value = unitData.indiviso_percentage || '';
        document.getElementById('edit-section').value = unitData.section_id || '';
        document.getElementById('edit-maintenance-fee').value = unitData.maintenance_fee || '';
        document.getElementById('delete-unit-name').innerText = unitData.unit_number;

        document.getElementById('edit-fee-start-month').value = unitData.fee_start_month || '<?= date('Y-m') ?>';

        // Inject scripts
        setTimeout(() => {
            if (typeof flatpickr !== 'undefined') {
                flatpickr("#edit-fee-start-month", {
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: true,
                            dateFormat: "Y-m",
                            altFormat: "F Y",
                        })
                    ],
                    locale: "es",
                    altInput: true,
                    defaultDate: unitData.fee_start_month || '<?= date('Y-m') ?>',
                    onChange: function () {
                        markAsModified();
                    }
                });
            }
        }, 100);

        // Ajustar ocupación tipo
        if (unitData.occupancy_type) {
            document.getElementById('edit-occupancy-type').value = unitData.occupancy_type;
        } else {
            document.getElementById('edit-occupancy-type').value = 'owner_occupied';
        }
        currentOccupancy = document.getElementById('edit-occupancy-type').value;
        checkOccupancyChange();

        // Reset states
        currentOwners = unitData.owners ? [...unitData.owners] : [];
        currentTenants = unitData.tenants ? [...unitData.tenants] : [];

        renderResidentsUI();

        switchEditTab('info');
        resetSaveButton();

        // Reset delete confirmation
        const deleteInput = document.getElementById('delete-confirm-input');
        const deleteBtn = document.getElementById('btn-delete-unit');
        deleteInput.value = '';
        deleteBtn.disabled = true;

        deleteInput.addEventListener('input', function () {
            deleteBtn.disabled = this.value !== 'ELIMINAR';
        });

        // Cargar Notas
        loadUnitNotes(unitData.id);

        const myModal = new bootstrap.Modal(document.getElementById('modalEditar'));
        myModal.show();
    }

    function confirmDeleteUnit() {
        const modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
        modal.show();
    }

    async function executeEliminarUnit() {
        const unitId = document.getElementById('edit-unit-id').value;
        const btn = document.querySelector('#modalConfirmarEliminar .btn-danger');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Eliminando...';

        try {
            const response = await fetch('<?= base_url("admin/unidades/eliminar-json") ?>/' + unitId, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();

            if (result.status === 200) {
                // Cerrar modal de confirmación
                bootstrap.Modal.getInstance(document.getElementById('modalConfirmarEliminar')).hide();
                // Cerrar modal de edición
                bootstrap.Modal.getInstance(document.getElementById('modalEditar')).hide();

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });

                await Toast.fire({
                    icon: 'success',
                    title: 'Unidad eliminada correctamente'
                });

                window.location.reload();
            } else {
                Swal.fire('Error', result.error || result.message || 'Error al eliminar la unidad', 'error');
                btn.disabled = false;
                btn.innerHTML = 'Sí, Eliminar Unidad';
            }
        } catch (err) {
            Swal.fire('Error', 'Error al conectar con el servidor', 'error');
            btn.disabled = false;
            btn.innerHTML = 'Sí, Eliminar Unidad';
        }
    }

    async function previewCSV() {
        const fileInput = document.getElementById('csvFileInput');
        if (!fileInput.files.length) return;

        // Show loading on select button
        const selectBtn = document.querySelector('#import-upload-section .btn-success');
        if (selectBtn) {
            selectBtn.disabled = true;
            selectBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Procesando...';
        }

        const formData = new FormData();
        formData.append('file_csv', fileInput.files[0]);

        try {
            const response = await fetch('<?= base_url("admin/unidades/importar-preview") ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok && result.status === 200) {
                // Fill stats
                document.getElementById('preview-new-count').innerText = result.stats.new;
                document.getElementById('preview-updated-count').innerText = result.stats.updated;
                document.getElementById('preview-removed-count').innerText = result.stats.removed;
                document.getElementById('preview-total-count').innerText = result.stats.total_after;
                document.getElementById('preview-remove-text').innerText = `${result.stats.removed} unidades existentes no están en el archivo importado y serán eliminadas permanentemente.`;

                if (result.stats.removed === 0) {
                    document.getElementById('preview-remove-warning').classList.add('d-none');
                } else {
                    document.getElementById('preview-remove-warning').classList.remove('d-none');
                }

                document.getElementById('preview-preview-count').innerText = result.stats.total_after;

                const tbody = document.getElementById('preview-table-body');
                tbody.innerHTML = '';

                result.preview.forEach(row => {
                    let badgeHtml = '';
                    let changesHtml = '—';

                    if (row.status === 'new') {
                        badgeHtml = '<span class="csv-badge-new"><i class="bi bi-plus-lg"></i> Nueva</span>';
                    } else if (row.status === 'updated') {
                        badgeHtml = '<span class="csv-badge-updated"><i class="bi bi-arrow-repeat"></i> Actualizada</span>';
                        // Build changes text from server data
                        if (row.changes && row.changes.length > 0) {
                            changesHtml = row.changes.map(c => `<span class="csv-change-text">${c}</span>`).join(', ');
                        }
                    } else {
                        badgeHtml = '<span class="csv-badge-nochange">— Sin cambio</span>';
                    }

                    let tr = `<tr>
                        <td class="ps-3 border-0">${badgeHtml}</td>
                        <td class="border-0 fw-medium">${row.name}</td>
                        <td class="border-0">$${row.fee}</td>
                        <td class="border-0">${row.indiviso}</td>
                        <td class="border-0">${row.section}</td>
                        <td class="border-0">${changesHtml}</td>
                    </tr>`;
                    tbody.innerHTML += tr;
                });

                // Switch views
                document.getElementById('import-upload-section').classList.add('d-none');
                document.getElementById('import-preview-section').classList.remove('d-none');

                // Hide modal footer in preview mode
                const modalFooter = document.querySelector('#modalCsv .modal-footer');
                if (modalFooter) modalFooter.style.display = 'none';

                // Update import button
                document.getElementById('btn-import-count').innerText = result.stats.total_after;
            } else {
                Swal.fire('Error', result.error || 'Ocurrió un error al previsualizar.', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Error de conexión al procesar CSV: ' + error, 'error');
        } finally {
            if (selectBtn) {
                selectBtn.disabled = false;
                selectBtn.innerHTML = '<i class="bi bi-upload me-2"></i> Seleccionar Archivo CSV';
            }
        }
    }

    // Auto-preview when CSV file is selected
    document.addEventListener('DOMContentLoaded', () => {
        const csvFile = document.getElementById('csvFileInput');
        if (csvFile) {
            csvFile.addEventListener('change', function () {
                if (this.files.length > 0) {
                    // Show filename
                    const nameEl = document.getElementById('csv-file-name');
                    if (nameEl) {
                        nameEl.innerText = this.files[0].name;
                        nameEl.classList.remove('d-none');
                    }
                    // Copy to hidden input for form submission
                    const dt = new DataTransfer();
                    dt.items.add(this.files[0]);
                    document.getElementById('hidden-file-input').files = dt.files;
                    // Auto-trigger preview
                    previewCSV();
                }
            });
        }
    });

    // Modal de Edición - Manejo de Tipo de Ocupación
    let currentOccupancy = 'owner_occupied';

    function checkOccupancyChange() {
        const select = document.getElementById('edit-occupancy-type');
        const icon = document.getElementById('occupancy-icon');
        const btn = document.getElementById('btn-confirm-occupancy');

        // Actualizar icono
        switch (select.value) {
            case 'owner_occupied': icon.className = 'bi bi-house-door'; break;
            case 'long_term_rent': icon.className = 'bi bi-key'; break;
            case 'short_term_rent': icon.className = 'bi bi-calendar-event'; break;
            case 'vacant': icon.className = 'bi bi-slash-circle'; break;
        }

        // Mostrar botón si cambió
        if (select.value !== currentOccupancy) {
            btn.classList.remove('d-none');
        } else {
            btn.classList.add('d-none');
        }
    }

    function confirmOccupancyChange() {
        const select = document.getElementById('edit-occupancy-type');
        if (select.value === 'vacant') {
            const warningModal = new bootstrap.Modal(document.getElementById('modalConfirmarDesocupado'));
            warningModal.show();
        } else {
            // Lógica para guardar otros estados si es necesario
            currentOccupancy = select.value;
            document.getElementById('btn-confirm-occupancy').classList.add('d-none');
        }
    }

    function executeDesocupar() {
        // Ejecutar desocupación en BD via AJAX o vaciar listas en UI
        currentOccupancy = 'vacant';
        document.getElementById('edit-occupancy-type').value = 'vacant';
        document.getElementById('btn-confirm-occupancy').classList.add('d-none');

        currentOwners = [];
        currentTenants = [];
        renderResidentsUI();
        markAsModified();

        bootstrap.Modal.getInstance(document.getElementById('modalConfirmarDesocupado')).hide();
    }

    // Función para abrir modal Agregar Inquilino
    function openModalAgregarInquilino(type) {
        modalAddResType = type;
        const typeText = type === 'tenant' ? 'Inquilino' : 'Propietario';
        document.getElementById('titleAgregarResidente').innerText = 'Agregar ' + typeText;

        const textBtn = document.getElementById('text-btn-manual-res');
        if (textBtn) { textBtn.innerText = typeText; }

        // Reset tabs y clear data
        document.getElementById('inputSearchResident').value = '';
        document.getElementById('search-resident-results').innerHTML = '';
        document.getElementById('search-resident-results').classList.add('d-none');
        document.getElementById('search-resident-status').innerText = 'Escribe al menos 2 caracteres para buscar';
        document.getElementById('search-resident-status').classList.remove('d-none');

        document.getElementById('manual-res-name').value = '';
        document.getElementById('manual-res-email').value = '';
        document.getElementById('manual-res-phone').value = '';

        // Reset manual button style
        const btnManual = document.getElementById('btn-submit-manual-res');
        if (btnManual) {
            btnManual.style.backgroundColor = '#94a3b8';
        }

        // Reset error message
        const errBox = document.getElementById('search-resident-error');
        if (errBox) {
            errBox.classList.add('d-none');
            errBox.innerText = '';
        }

        switchAddResidentTab('search');

        const modal = new bootstrap.Modal(document.getElementById('modalAgregarResidente'));
        modal.show();
    }

    // Función mock de búsqueda en UI
    document.querySelector('#modalAgregarResidente input').addEventListener('input', function () {
        if (this.value.length >= 2) {
            document.getElementById('search-resident-status').classList.add('d-none');
            document.getElementById('search-resident-results').classList.remove('d-none');
        } else {
            document.getElementById('search-resident-status').classList.remove('d-none');
            document.getElementById('search-resident-results').classList.add('d-none');
        }
    });

    // Función mock que se llama al hacer clic en buscar inquilino/propietario
    function seleccionarResidenteMock(el, id, name, email) {
        let newRes = {
            resident_id: null,
            user_id: id,
            name: name,
            email: email
        };

        // Verificar si ya existe en alguna de las listas
        const existsInOwners = currentOwners.find(o => o.user_id == id);
        const existsInTenants = currentTenants.find(o => o.user_id == id);

        const errBox = document.getElementById('search-resident-error');
        if (existsInOwners || existsInTenants) {
            if (errBox) {
                errBox.innerText = 'Este residente ya está asignado a la unidad en otra o misma categoría.';
                errBox.classList.remove('d-none');
            }
            return;
        }

        if (errBox) {
            errBox.classList.add('d-none');
            errBox.innerText = '';
        }

        if (modalAddResType === 'owner') {
            currentOwners.push(newRes);
        } else {
            currentTenants.push(newRes);
        }

        renderResidentsUI();
        markAsModified();
        bootstrap.Modal.getInstance(document.getElementById('modalAgregarResidente')).hide();
        document.querySelector('#modalAgregarResidente input').value = '';
        document.getElementById('search-resident-status').classList.remove('d-none');
        document.getElementById('search-resident-results').classList.add('d-none');
    }

    // Switch Modal Agregar Residente Tabs
    function switchAddResidentTab(tab) {
        const btnSearch = document.getElementById('tab-add-search');
        const btnManual = document.getElementById('tab-add-manual');
        const viewSearch = document.getElementById('view-add-search');
        const viewManual = document.getElementById('view-add-manual');
        const footer = document.getElementById('footer-add-resident');

        if (tab === 'search') {
            btnSearch.className = 'btn btn-white flex-fill fw-medium shadow-sm border border-secondary border-opacity-25 py-2 active';
            btnSearch.classList.remove('text-muted');

            btnManual.className = 'btn text-muted flex-fill fw-medium border-0 py-2';

            viewSearch.classList.remove('d-none');
            viewManual.classList.add('d-none');
            footer.classList.remove('d-none'); // Mostrar boton cancelar
        } else {
            btnManual.className = 'btn btn-white flex-fill fw-medium shadow-sm border border-secondary border-opacity-25 py-2 active';
            btnManual.classList.remove('text-muted');

            btnSearch.className = 'btn text-muted flex-fill fw-medium border-0 py-2';

            viewManual.classList.remove('d-none');
            viewSearch.classList.add('d-none');
            footer.classList.add('d-none'); // Esconder porque el manual tiene el suyo
        }
    }

    // Envio manual de residente a DB via Fetch
    async function submitManualResident() {
        const nameInput = document.getElementById('manual-res-name');
        const emailInput = document.getElementById('manual-res-email');
        const phoneInput = document.getElementById('manual-res-phone');

        const name = nameInput.value.trim();
        const email = emailInput.value.trim();
        const phone = phoneInput.value.trim();

        if (!name) {
            alert('El nombre es obligatorio');
            nameInput.focus();
            return;
        }

        const btn = document.getElementById('btn-submit-manual-res');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Guardando...';
        btn.disabled = true;

        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        formData.append('phone', phone);

        try {
            const response = await fetch('<?= base_url("admin/unidades/crear-usuario-manual") ?>', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();

            if (result.status === 200 && result.data) {
                // Verificar local si ya existe para Manual Form
                const existsInOwners = currentOwners.find(o => o.user_id == result.data.user_id);
                const existsInTenants = currentTenants.find(o => o.user_id == result.data.user_id);

                const errBox = document.getElementById('search-resident-error');
                if (existsInOwners || existsInTenants) {
                    if (errBox) {
                        errBox.innerText = 'Este residente ingresado ya está listado en la unidad actual.';
                        errBox.classList.remove('d-none');
                        // switch tab error alert if needed
                        switchAddResidentTab('search');
                    }
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                    return;
                }

                if (errBox) {
                    errBox.classList.add('d-none');
                    errBox.innerText = '';
                }

                // Agregar virtualmente a la lista de editar
                const mockCardDiv = document.createElement('div'); // Dummy para function ref
                seleccionarResidenteMock(mockCardDiv, result.data.user_id, result.data.name, result.data.email);

                // Limpiar inputs
                nameInput.value = '';
                emailInput.value = '';
                phoneInput.value = '';
                checkManualInputs();
            } else {
                alert(result.error || 'Ocurrió un error al crear el usuario');
            }
        } catch (err) {
            alert('Error de conexión al servidor');
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    // Función para oscurecer el botón de agregar manual cuando hay datos
    function checkManualInputs() {
        const name = document.getElementById('manual-res-name').value.trim();
        const email = document.getElementById('manual-res-email').value.trim();
        const phone = document.getElementById('manual-res-phone').value.trim();
        const btn = document.getElementById('btn-submit-manual-res');

        if (name || email || phone) {
            btn.style.backgroundColor = '#1e293b'; // Slate 800 (Oscuro)
            btn.style.transition = 'background-color 0.3s ease';
        } else {
            btn.style.backgroundColor = '#94a3b8';
        }
    }

    // Event listeners para los inputs manuales
    document.addEventListener('DOMContentLoaded', () => {
        const manualInputs = ['manual-res-name', 'manual-res-email', 'manual-res-phone'];
        manualInputs.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', checkManualInputs);
            }
        });
    });

    // --- SISTEMA DE NOTAS ---
    let unitNotes = [];

    async function loadUnitNotes(unitId) {
        const container = document.getElementById('unit-notes-container');
        container.innerHTML = '<div class="text-center py-4"><span class="spinner-border spinner-border-sm text-muted"></span></div>';

        try {
            const response = await fetch(`<?= base_url('admin/unidades/notas') ?>/${unitId}`);
            const result = await response.json();
            if (result.status === 200) {
                unitNotes = result.data;
                renderNotesUI();
            }
        } catch (err) {
            container.innerHTML = '<div class="alert alert-danger small">Error al cargar notas</div>';
        }
    }

    function renderNotesUI() {
        const container = document.getElementById('unit-notes-container');
        if (unitNotes.length === 0) {
            container.innerHTML = `
                <div class="alert alert-light border d-flex align-items-start text-muted small mb-0">
                    <i class="bi bi-info-circle me-2 mt-1"></i>
                    <div>No hay notas. Aquí puedes registrar mantenimiento, instrucciones de acceso, preferencias de inquilinos y más.</div>
                </div>
            `;
            return;
        }

        container.innerHTML = unitNotes.map(note => `
            <div class="card border-0 bg-light mb-3" style="border-radius: 12px;">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-2 border shadow-sm" style="width: 24px; height: 24px; font-size: 0.7rem; font-weight: bold;">
                                ${(note.first_name || 'A').substring(0, 1)}
                            </div>
                            <span class="fw-bold small text-dark">${note.first_name || 'Admin'} ${note.last_name || ''}</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small" style="font-size: 0.65rem;">${new Date(note.created_at).toLocaleString()}</span>
                            <button type="button" class="btn btn-link p-0 text-danger opacity-75 hover-opacity-100" onclick="deleteUnitNote(${note.id})" title="Eliminar nota">
                                <i class="bi bi-trash" style="font-size: 0.85rem;"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mb-0 text-secondary small" style="line-height: 1.5;">${note.note}</p>
                </div>
            </div>
        `).join('');
    }

    async function addUnitNote() {
        const textArea = document.getElementById('new-unit-note');
        const unitId = document.getElementById('edit-unit-id').value;
        const noteText = textArea.value.trim();
        const btn = document.getElementById('btn-add-unit-note');

        if (!noteText) return;

        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

        const formData = new FormData();
        formData.append('unit_id', unitId);
        formData.append('note', noteText);

        try {
            const response = await fetch('<?= base_url('admin/unidades/notas/agregar') ?>', {
                method: 'POST',
                body: formData
            });
            const result = await response.json();
            if (result.status === 200) {
                unitNotes.unshift(result.data);
                renderNotesUI();
                textArea.value = '';
                // No cerramos el modal, permitimos seguir añadiendo
            }
        } catch (err) {
            alert('Error al guardar nota');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-plus"></i> Agregar Nota';
        }
    }
    async function deleteUnitNote(noteId) {
        Swal.fire({
            title: '¿Eliminar nota?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            customClass: {
                confirmButton: 'rounded-pill px-4',
                cancelButton: 'rounded-pill px-4'
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const formData = new FormData();
                    formData.append('note_id', noteId);

                    const response = await fetch('<?= base_url('admin/unidades/notas/eliminar') ?>', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    if (result.status === 200) {
                        // Filtrar localmente y re-renderizar
                        unitNotes = unitNotes.filter(n => n.id !== noteId);
                        renderNotesUI();

                        // Premium Toast Notification
                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        Toast.fire({
                            icon: 'success',
                            title: 'Nota eliminada correctamente'
                        });
                    } else {
                        Swal.fire('Error', result.error || 'Error al eliminar la nota', 'error');
                    }
                } catch (err) {
                    Swal.fire('Error', 'Error al conectar con el servidor', 'error');
                }
            }
        });
    }

    // --- FIN SISTEMA DE NOTAS ---

    // --- SISTEMA DE FILTRADO DINÁMICO (UX EXPERT) ---
    let activeSection = 'Todas';
    let activeResidentFilter = 'Todas';

    function setSectionFilter(section) {
        activeSection = section;
        document.getElementById('sectionFilterLabel').innerText = section;
        filterUnits();
    }

    function setResidentFilter(filter) {
        activeResidentFilter = filter;
        document.getElementById('residentFilterLabel').innerText = filter;
        filterUnits();
    }

    function filterUnits() {
        const searchText = document.getElementById('unitSearch').value.toLowerCase();
        const rows = document.querySelectorAll('.unit-row');

        rows.forEach(row => {
            const unitText = row.innerText.toLowerCase();
            const rowSection = row.getAttribute('data-section');
            const ownersCount = parseInt(row.getAttribute('data-owners-count') || '0');
            const tenantsCount = parseInt(row.getAttribute('data-tenants-count') || '0');
            const totalRes = ownersCount + tenantsCount;

            // 1. Filtro por Búsqueda
            const matchesSearch = searchText === "" || unitText.includes(searchText);

            // 2. Filtro por Sección
            const matchesSection = activeSection === 'Todas' || rowSection === activeSection;

            // 3. Filtro por Residentes
            let matchesResident = true;
            if (activeResidentFilter === 'Sin Residentes') {
                matchesResident = (totalRes === 0);
            } else if (activeResidentFilter === 'Sin Propietarios') {
                matchesResident = (ownersCount === 0);
            } else if (activeResidentFilter === 'Sin Inquilinos') {
                matchesResident = (tenantsCount === 0);
            }

            // Aplicar visibilidad
            if (matchesSearch && matchesSection && matchesResident) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });

        // Opcional: Actualizar el contador de unidades visibles
        const visibleCount = Array.from(rows).filter(r => r.style.display !== "none").length;
        // Podríamos actualizar un label si existiera: e.g. document.getElementById('visibleUnitsCount').innerText = visibleCount;
    }
    // --- FIN SISTEMA DE FILTRADO ---

    // Función para oscurecer el botón de CREAR UNIDAD cuando hay datos
    function checkCreateInputs() {
        const modal = document.getElementById('modalIndividual');
        if (!modal) return;

        const inputs = modal.querySelectorAll('input[required], input[name="unit_number"], input[name="maintenance_fee"]');
        const btn = document.getElementById('btn-create-unit');
        if (!btn) return;

        let hasData = false;
        inputs.forEach(input => {
            if (input.value.trim() !== "" && input.value.trim() !== "0") {
                hasData = true;
            }
        });

        if (hasData) {
            btn.style.backgroundColor = '#1e293b'; // Slate 800 (Oscuro)
            btn.style.transition = 'background-color 0.3s ease';
        } else {
            btn.style.backgroundColor = '#94a3b8';
        }
    }

    // Sincronización de Cabeceras Sticky (UX Expert)
    function syncStickyHeader() {
        const header = document.getElementById('management-header');
        const tableHeaders = document.querySelectorAll('.koti-table thead th');

        if (header && tableHeaders.length > 0) {
            const headerHeight = header.offsetHeight;
            // Aplicar la altura como offset 'top' a cada cabecera de la tabla
            tableHeaders.forEach(th => {
                th.style.top = (headerHeight - 2) + 'px'; // -2 para compensar bordes y evitar gaps
            });

            // También ajustar el margen superior del contenedor de la tabla para evitar saltos
            const container = document.querySelector('.koti-table-container');
            if (container) {
                container.style.marginTop = '1rem';
            }
        }
    }

    // Inicializar listeners
    document.addEventListener('DOMContentLoaded', () => {
        // ...Existing modal code...
        syncStickyHeader();

        // Re-sincronizar cuando cambie el tamaño de la ventana (responsive)
        window.addEventListener('resize', syncStickyHeader);

        // Re-sincronizar si muta el contenido (ej. si cerramos una alerta)
        const observer = new MutationObserver(syncStickyHeader);
        const header = document.getElementById('management-header');
        if (header) {
            observer.observe(header, { childList: true, subtree: true, attributes: true });
        }

        const modalInd = document.getElementById('modalIndividual');
        if (modalInd) {
            modalInd.addEventListener('show.bs.modal', function () {
                // Reset botón
                const btn = document.getElementById('btn-create-unit');
                if (btn) btn.style.backgroundColor = '#94a3b8';

                // Init Flatpickr para este modal específicamente
                setTimeout(() => {
                    if (typeof flatpickr !== 'undefined') {
                        flatpickr("#create-fee-start-month", {
                            plugins: [
                                new monthSelectPlugin({
                                    shorthand: true,
                                    dateFormat: "Y-m",
                                    altFormat: "F Y",
                                })
                            ],
                            locale: "es",
                            altInput: true,
                            defaultDate: "<?= date('Y-m') ?>",
                            onChange: function () {
                                checkCreateInputs();
                            }
                        });
                    }
                }, 100);
            });

            // Listeners para los inputs del modal individual
            const inputs = modalInd.querySelectorAll('input, select');
            inputs.forEach(input => {
                input.addEventListener('input', checkCreateInputs);
                input.addEventListener('change', checkCreateInputs);
            });
        }
    });

    // Detectar éxito de importación para mostrar Toast Premium
    <?php if (session()->getFlashdata('swal_success')): ?>
        document.addEventListener('DOMContentLoaded', () => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
            Toast.fire({
                icon: 'success',
                title: '<?= session()->getFlashdata('swal_success') ?>'
            });
        });
    <?php endif; ?>

    // Detectar error de validación para mostrar Toast Premium
    <?php if (session()->getFlashdata('swal_error')): ?>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'error',
                title: 'Error de Validación',
                text: '<?= session()->getFlashdata('swal_error') ?>',
                confirmButtonColor: '#1e293b',
                confirmButtonText: 'Entendido',
                customClass: {
                    popup: 'premium-swal-popup'
                }
            });
        });
    <?php endif; ?>

    // Detectar límite de plan (Paywall SaaS)
    <?php if (session()->getFlashdata('plan_limit_error')): ?>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'warning',
                iconColor: '#f59e0b',
                title: 'Capacidad Máxima',
                html: '<div style="color: #475569; font-size: 0.95rem; margin-top: 0.5rem; line-height: 1.5;"><?= session()->getFlashdata('plan_limit_error') ?></div>',
                showCancelButton: true,
                confirmButtonColor: '#1C2434',
                cancelButtonColor: '#e2e8f0',
                confirmButtonText: '<i class="bi bi-star-fill text-warning me-2" style="font-size:0.9rem;"></i>Mejorar Plan',
                cancelButtonText: '<span style="color:#475569;">Más tarde</span>',
                reverseButtons: true,
                customClass: {
                    popup: 'premium-swal-popup',
                    confirmButton: 'px-4 py-2',
                    cancelButton: 'px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url("admin/configuracion#subscription") ?>';
                }
            });
        });
    <?php endif; ?>

    // Detectar éxito de edición/creación individual
    <?php if (session()->getFlashdata('success')): ?>
        document.addEventListener('DOMContentLoaded', () => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            Toast.fire({
                icon: 'success',
                title: '<?= session()->getFlashdata('success') ?>'
            });
        });
    <?php endif; ?>

    // Detectar error de edición/creación individual
    <?php if (session()->getFlashdata('error')): ?>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= session()->getFlashdata('error') ?>',
                confirmButtonColor: '#1e293b',
                confirmButtonText: 'Entendido'
            });
        });
    <?php endif; ?>

    function confirmImportCSV() {
        const fileInput = document.getElementById('csvFileInput');
        const fileInputReal = document.getElementById('csvFileInputReal');

        const dt = new DataTransfer();
        dt.items.add(fileInput.files[0]);
        fileInputReal.files = dt.files;

        document.getElementById('formImportCsvFinal').submit();
    }

    // --- SISTEMA DE EDICIÓN MASIVA AVANZADO ---

    /**
     * Activar edición de una celda (nombre o cuota)
     */
    function meActivateCell(id, field) {
        const display = document.getElementById(`me-${field}-display-${id}`);
        const input = document.getElementById(`me-${field}-input-${id}`);
        if (!display || !input) return;

        display.classList.add('d-none');
        input.classList.remove('d-none');
        input.focus();
        input.select();
    }

    /**
     * Al salir de una celda editada, verificar si cambió
     */
    function meCellBlur(input) {
        const field = input.dataset.field;
        const id = input.dataset.id;
        const row = input.closest('.mass-edit-row');
        const display = document.getElementById(`me-${field}-display-${id}`);

        if (!display || !row) return;

        const originalValue = row.dataset[`original${field.charAt(0).toUpperCase() + field.slice(1)}`] || '';
        const currentValue = input.value.trim();

        // Update display value
        if (field === 'fee') {
            const numVal = parseFloat(currentValue) || 0;
            display.querySelector('.me-cell-value').textContent = '$' + numVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } else {
            display.querySelector('.me-cell-value').textContent = currentValue;
        }

        // Show display, hide input
        display.classList.remove('d-none');
        input.classList.add('d-none');

        // Check if dirty
        const isDirty = (field === 'fee')
            ? (parseFloat(currentValue) || 0) !== (parseFloat(originalValue) || 0)
            : currentValue !== originalValue;

        if (isDirty) {
            input.classList.add('me-cell-dirty');
            meMarkRowModified(row);
        } else {
            input.classList.remove('me-cell-dirty');
            meCheckRowClean(row);
        }

        meUpdateFooterStatus();
    }

    /**
     * Keyboard handler: Enter commits, Escape cancels, Tab moves to next
     */
    function meCellKeydown(event, input) {
        if (event.key === 'Enter') {
            event.preventDefault();
            input.blur();
        } else if (event.key === 'Escape') {
            // Revert to original
            const field = input.dataset.field;
            const row = input.closest('.mass-edit-row');
            const originalKey = `original${field.charAt(0).toUpperCase() + field.slice(1)}`;
            input.value = row.dataset[originalKey] || '';
            input.blur();
        }
    }

    /**
     * Section dropdown changed
     */
    function meSectionChanged(select) {
        const id = select.dataset.id;
        const row = select.closest('.mass-edit-row');
        const originalSection = row.dataset.originalSection || '';
        const currentSection = select.value;

        const isDirty = currentSection !== originalSection;

        if (isDirty) {
            select.classList.add('me-cell-dirty');
            meMarkRowModified(row);
        } else {
            select.classList.remove('me-cell-dirty');
            meCheckRowClean(row);
        }

        meUpdateFooterStatus();
    }

    /**
     * Mark a row as modified (yellow bg + badge)
     */
    function meMarkRowModified(row) {
        row.classList.add('me-row-modified');
        const id = row.dataset.id;
        const statusCell = document.getElementById(`me-status-${id}`);
        if (statusCell && !statusCell.querySelector('.me-badge-modified')) {
            statusCell.innerHTML = '<span class="me-badge-modified">Modificado</span>';
        }
    }

    /**
     * Check if a row has no more dirty fields → remove modified state
     */
    function meCheckRowClean(row) {
        const id = row.dataset.id;
        const nameInput = document.getElementById(`me-name-input-${id}`);
        const feeInput = document.getElementById(`me-fee-input-${id}`);
        const sectionSelect = document.getElementById(`me-section-input-${id}`);

        const hasDirty = (nameInput && nameInput.classList.contains('me-cell-dirty'))
            || (feeInput && feeInput.classList.contains('me-cell-dirty'))
            || (sectionSelect && sectionSelect.classList.contains('me-cell-dirty'));

        if (!hasDirty) {
            row.classList.remove('me-row-modified');
            const statusCell = document.getElementById(`me-status-${id}`);
            if (statusCell) statusCell.innerHTML = '';
        }
    }

    /**
     * Update the footer status text and save button
     */
    function meUpdateFooterStatus() {
        const modifiedRows = document.querySelectorAll('.mass-edit-row.me-row-modified');
        const count = modifiedRows.length;
        const statusEl = document.getElementById('me-footer-status');
        const saveBtn = document.getElementById('me-save-btn');

        if (count === 0) {
            statusEl.textContent = 'Sin cambios';
            statusEl.classList.remove('has-changes');
            saveBtn.classList.add('disabled');
            saveBtn.style.backgroundColor = '#94a3b8';
        } else {
            statusEl.textContent = `${count} unidad(es) modificada(s)`;
            statusEl.classList.add('has-changes');
            saveBtn.classList.remove('disabled');
            saveBtn.style.backgroundColor = '#1e293b';
        }
    }

    // --- CHECKBOX & BULK SELECTION ---

    document.addEventListener('DOMContentLoaded', () => {
        // Select All checkbox
        const selectAll = document.getElementById('me-select-all');
        if (selectAll) {
            selectAll.addEventListener('change', () => {
                const isChecked = selectAll.checked;
                document.querySelectorAll('.me-row-check').forEach(cb => {
                    cb.checked = isChecked;
                });
                meUpdateBulkBar();
            });
        }

        // Individual checkboxes
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('me-row-check')) {
                meUpdateBulkBar();
                // Update select-all state
                const total = document.querySelectorAll('.me-row-check').length;
                const checked = document.querySelectorAll('.me-row-check:checked').length;
                const selectAllCb = document.getElementById('me-select-all');
                if (selectAllCb) {
                    selectAllCb.checked = checked === total;
                    selectAllCb.indeterminate = checked > 0 && checked < total;
                }
            }
        });

        // Reset modal state when opened
        const modalMasivo = document.getElementById('modalMasivo');
        if (modalMasivo) {
            modalMasivo.addEventListener('show.bs.modal', () => {
                meResetModal();
            });
        }
    });

    /**
     * Show/hide the bulk action bar based on selected checkboxes
     */
    function meUpdateBulkBar() {
        const checked = document.querySelectorAll('.me-row-check:checked').length;
        const bar = document.getElementById('me-bulk-bar');
        const countEl = document.getElementById('me-bulk-count');

        if (checked > 0) {
            bar.classList.add('active');
            countEl.textContent = checked;
        } else {
            bar.classList.remove('active');
        }
    }

    /**
     * Apply a bulk value to all selected rows
     */
    function meApplyBulk(field) {
        const checked = document.querySelectorAll('.me-row-check:checked');
        if (checked.length === 0) return;

        let appliedCount = 0;

        if (field === 'fee') {
            const bulkValue = document.getElementById('me-bulk-fee-input').value;
            if (bulkValue === '') return;
            const numVal = parseFloat(bulkValue) || 0;

            checked.forEach(cb => {
                const row = cb.closest('.mass-edit-row');
                const id = row.dataset.id;
                const input = document.getElementById(`me-fee-input-${id}`);
                const display = document.getElementById(`me-fee-display-${id}`);

                if (input && display) {
                    input.value = numVal;
                    display.querySelector('.me-cell-value').textContent = '$' + numVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                    // Add applied icon to display
                    if (!display.querySelector('.me-applied-icon')) {
                        const icon = document.createElement('i');
                        icon.className = 'bi bi-clipboard-check me-applied-icon';
                        icon.title = 'Aplicar a todas';
                        display.appendChild(icon);
                    }

                    // Check dirty
                    const originalValue = row.dataset.originalFee || '0';
                    if (numVal !== (parseFloat(originalValue) || 0)) {
                        input.classList.add('me-cell-dirty');
                        meMarkRowModified(row);
                    } else {
                        input.classList.remove('me-cell-dirty');
                        meCheckRowClean(row);
                    }
                    appliedCount++;
                }
            });

            // Turn apply button green
            const btn = document.getElementById('me-apply-fee-btn');
            if (btn && appliedCount > 0) {
                btn.classList.add('applied');
                btn.innerHTML = '<i class="bi bi-check2"></i> Aplicar';
                setTimeout(() => {
                    btn.classList.remove('applied');
                }, 2000);
            }
        }

        if (field === 'section') {
            const bulkSelect = document.getElementById('me-bulk-section-input');
            const bulkValue = bulkSelect.value;
            if (bulkValue === '') return;
            const selectedOption = bulkSelect.options[bulkSelect.selectedIndex];
            const sectionName = selectedOption.dataset.name || selectedOption.text;

            checked.forEach(cb => {
                const row = cb.closest('.mass-edit-row');
                const id = row.dataset.id;
                const select = document.getElementById(`me-section-input-${id}`);

                if (select) {
                    select.value = bulkValue;

                    // Check dirty
                    const originalSection = row.dataset.originalSection || '';
                    if (bulkValue !== originalSection) {
                        select.classList.add('me-cell-dirty');
                        meMarkRowModified(row);
                    } else {
                        select.classList.remove('me-cell-dirty');
                        meCheckRowClean(row);
                    }
                    appliedCount++;
                }
            });

            // Turn apply button green
            const btn = document.getElementById('me-apply-section-btn');
            if (btn && appliedCount > 0) {
                btn.classList.add('applied');
                btn.innerHTML = '<i class="bi bi-check2"></i> Aplicar';
                setTimeout(() => {
                    btn.classList.remove('applied');
                }, 2000);
            }
        }

        meUpdateFooterStatus();
    }

    /**
     * Reset modal to clean state
     */
    function meResetModal() {
        // Reset all inputs to original values
        document.querySelectorAll('.mass-edit-row').forEach(row => {
            const id = row.dataset.id;

            // Name
            const nameInput = document.getElementById(`me-name-input-${id}`);
            const nameDisplay = document.getElementById(`me-name-display-${id}`);
            if (nameInput && nameDisplay) {
                nameInput.value = row.dataset.originalName || '';
                nameInput.classList.remove('me-cell-dirty');
                nameInput.classList.add('d-none');
                nameDisplay.classList.remove('d-none');
                nameDisplay.querySelector('.me-cell-value').textContent = row.dataset.originalName || '';
                // Remove applied icons
                const appliedIcon = nameDisplay.querySelector('.me-applied-icon');
                if (appliedIcon) appliedIcon.remove();
            }

            // Fee
            const feeInput = document.getElementById(`me-fee-input-${id}`);
            const feeDisplay = document.getElementById(`me-fee-display-${id}`);
            if (feeInput && feeDisplay) {
                feeInput.value = row.dataset.originalFee || 0;
                feeInput.classList.remove('me-cell-dirty');
                feeInput.classList.add('d-none');
                feeDisplay.classList.remove('d-none');
                const numVal = parseFloat(row.dataset.originalFee) || 0;
                feeDisplay.querySelector('.me-cell-value').textContent = '$' + numVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                const appliedIcon = feeDisplay.querySelector('.me-applied-icon');
                if (appliedIcon) appliedIcon.remove();
            }

            // Section
            const sectionSelect = document.getElementById(`me-section-input-${id}`);
            if (sectionSelect) {
                sectionSelect.value = row.dataset.originalSection || '';
                sectionSelect.classList.remove('me-cell-dirty');
            }

            // Row state
            row.classList.remove('me-row-modified');
            const statusCell = document.getElementById(`me-status-${id}`);
            if (statusCell) statusCell.innerHTML = '';

            // Checkbox
            const cb = row.querySelector('.me-row-check');
            if (cb) cb.checked = false;
        });

        // Reset select all
        const selectAll = document.getElementById('me-select-all');
        if (selectAll) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        }

        // Reset bulk bar
        const bar = document.getElementById('me-bulk-bar');
        if (bar) bar.classList.remove('active');

        // Reset bulk inputs
        const bulkFee = document.getElementById('me-bulk-fee-input');
        if (bulkFee) bulkFee.value = '';
        const bulkSection = document.getElementById('me-bulk-section-input');
        if (bulkSection) bulkSection.value = '';

        // Reset apply buttons
        document.querySelectorAll('.me-apply-btn').forEach(btn => {
            btn.classList.remove('applied');
        });

        meUpdateFooterStatus();
    }

    /**
     * Save all modified rows to the backend
     */
    async function meSaveAll() {
        const modifiedRows = document.querySelectorAll('.mass-edit-row.me-row-modified');
        if (modifiedRows.length === 0) return;

        const btn = document.getElementById('me-save-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Guardando...';
        btn.disabled = true;

        const updates = [];
        modifiedRows.forEach(row => {
            const id = row.dataset.id;
            const update = { id: parseInt(id) };

            const nameInput = document.getElementById(`me-name-input-${id}`);
            if (nameInput && nameInput.classList.contains('me-cell-dirty')) {
                update.unit_number = nameInput.value.trim();
            }

            const feeInput = document.getElementById(`me-fee-input-${id}`);
            if (feeInput && feeInput.classList.contains('me-cell-dirty')) {
                update.maintenance_fee = parseFloat(feeInput.value) || 0;
            }

            const sectionSelect = document.getElementById(`me-section-input-${id}`);
            if (sectionSelect && sectionSelect.classList.contains('me-cell-dirty')) {
                update.section_id = sectionSelect.value || null;
            }

            updates.push(update);
        });

        try {
            const response = await fetch('<?= base_url("admin/unidades/masivo") ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ units: updates })
            });

            const result = await response.json();

            if (response.ok && result.status === 200) {
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalMasivo'));
                if (modal) modal.hide();

                // SweetAlert2 Toast
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });
                Toast.fire({
                    icon: 'success',
                    title: `${updates.length} unidad(es) actualizada(s) correctamente`
                });

                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.error || 'Ocurrió un error al guardar los cambios.',
                    confirmButtonColor: '#1e293b'
                });
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'No se pudo conectar con el servidor.',
                confirmButtonColor: '#1e293b'
            });
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    /**
     * openModalSafely – Abre modales de Importar/Exportar y Edición Masiva
     * sin conflictos de stacking context.
     */
    function openModalSafely(modalId) {
        if (typeof event !== 'undefined' && event && typeof event.preventDefault === 'function') {
            event.preventDefault();
        }

        const modalEl = document.getElementById(modalId);
        if (!modalEl) {
            console.warn('[openModalSafely] Modal no encontrado:', modalId);
            return false;
        }

        document.querySelectorAll('.dropdown-menu.show').forEach(dd => {
            const toggle = dd.previousElementSibling;
            if (toggle) {
                const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                if (bsDropdown) bsDropdown.hide();
            }
        });

        if (modalEl.parentElement !== document.body) {
            document.body.appendChild(modalEl);
        }

        const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
        bsModal.show();

        return false;
    }

    // Mover modales a body al cargar para evitar stacking context issues
    document.addEventListener('DOMContentLoaded', () => {
        ['modalCsv', 'modalMasivo'].forEach(id => {
            const el = document.getElementById(id);
            if (el && el.parentElement !== document.body) {
                document.body.appendChild(el);
            }
        });
    });
    // --- FIN EDICIÓN MASIVA ---

    // Funcionalidad de ordenamiento de columnas
    let currentSortColumn = -1;
    let currentSortOrder = 'asc';

    function sortTable(n, type) {
        const table = document.querySelector(".koti-table table");
        const tbody = table.querySelector("tbody");
        let rows = Array.from(tbody.querySelectorAll("tr.unit-row"));
        if (rows.length === 0) return;

        // Reset icons
        document.querySelectorAll('.sort-icon').forEach(icon => {
            if (icon.id.startsWith('sort-icon-')) {
                icon.className = 'bi bi-arrow-down-up sort-icon';
                icon.style.opacity = '0.5';
                icon.style.color = '';
            }
        });

        // Determine new order
        if (currentSortColumn === n) {
            currentSortOrder = currentSortOrder === 'asc' ? 'desc' : 'asc';
        } else {
            currentSortOrder = 'asc';
            currentSortColumn = n;
        }

        // Update active icon
        const activeIcon = document.getElementById('sort-icon-' + n);
        if (activeIcon) {
            activeIcon.className = currentSortOrder === 'asc' ? 'bi bi-arrow-up-short sort-icon' : 'bi bi-arrow-down-short sort-icon';
            activeIcon.style.opacity = '1';
            activeIcon.style.color = '#475569'; // Slate Grey Highlight color
        }

        rows.sort((rowA, rowB) => {
            let valA = rowA.getElementsByTagName("TD")[n].innerText.trim();
            let valB = rowB.getElementsByTagName("TD")[n].innerText.trim();

            if (type === 'currency' || type === 'number') {
                valA = parseFloat(valA.replace(/[^0-9.-]+/g, "")) || 0;
                valB = parseFloat(valB.replace(/[^0-9.-]+/g, "")) || 0;
            } else {
                valA = valA.toLowerCase();
                valB = valB.toLowerCase();
            }

            if (valA < valB) return currentSortOrder === 'asc' ? -1 : 1;
            if (valA > valB) return currentSortOrder === 'asc' ? 1 : -1;
            return 0;
        });

        rows.forEach(row => tbody.appendChild(row));
    }
</script>
<!-- Flatpickr Dependencies -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?= $this->endSection() ?>