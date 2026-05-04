<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?= $this->section('styles') ?>
<style>
    .sticky-management-header {
        position: sticky;
        top: 0;
        z-index: 100;
        background: #f8f9fa;
        padding-top: 1rem;
        padding-bottom: 0.5rem;
    }

    .filter-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .search-input-group {
        position: relative;
    }

    .search-input-group i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input-group input {
        padding-left: 2.5rem;
        border-radius: 0.5rem;
        border: 1px solid #e2e8f0;
        width: 300px;
    }

    .axis-table-container {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .axis-table th {
        background: #f8fafc;
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .axis-table td {
        padding: 0.75rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
        font-size: 0.85rem;
    }

    .resident-avatar {
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 0.85rem;
        margin-right: 0.75rem;
    }

    .type-link {
        color: #0ea5e9;
        text-decoration: none;
        background: #f0f9ff;
        padding: 0.25rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .type-link i {
        font-size: 0.7rem;
        margin-right: 0.35rem;
    }

    .type-link.tenant {
        color: #8b5cf6;
        background: #f3e8ff;
    }

    .role-pill {
        background: #3F67AC;
        color: white;
        padding: 0.15rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.7rem;
        font-weight: 500;
    }

    .nav-pills-premium .nav-link {
        color: #3F67AC;
        border-radius: 0.5rem;
        padding: 0.35rem 0.85rem;
        font-size: 0.85rem;
        font-weight: 500;
        background: #f8fafc;
        border: 1px solid transparent;
        margin-right: 0.25rem;
        transition: all 0.2s;
    }

    .nav-pills-premium .nav-link:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
        color: #1e293b !important;
    }

    .nav-pills-premium .nav-link.active {
        background: white;
        color: #1e293b !important;
        border-color: #cbd5e1;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .count-badge {
        background: #e2e8f0;
        color: #3F67AC;
        padding: 0.1rem 0.4rem;
        border-radius: 1rem;
        font-size: 0.7rem;
        margin-left: 0.25rem;
        font-weight: 600;
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

    .premium-main-container {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        overflow: hidden;
    }

    .premium-filter-bar {
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
    }

    /* Modal Invitar Residente - Diseño Premium */
    .axis-modal-content {
        border: none;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .axis-modal-header {
        border-bottom: none;
        padding: 1.5rem 1.5rem 0.5rem 1.5rem;
        position: relative;
    }

    .axis-modal-header .btn-close {
        position: absolute;
        top: 1.5rem !important;
        right: 1.5rem !important;
        margin: 0 !important;
        font-size: 0.8rem;
    }

    .modal-tab-nav {
        background: #f8fafc;
        border-radius: 8px;
        padding: 4px;
        display: flex;
        margin-bottom: 24px;
        border: 1px solid #f1f5f9;
    }

    .modal-tab-btn {
        flex: 1;
        border: none;
        background: transparent;
        padding: 10px 16px;
        border-radius: 6px;
        color: #64748b;
        font-weight: 500;
        font-size: 0.9rem;
        transition: all 0.2s;
    }

    .modal-tab-btn.active {
        background: white;
        color: #1e293b;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }

    .form-label-premium {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 6px;
    }

    .form-label-optional {
        color: #94a3b8;
        font-weight: 400;
        font-size: 0.75rem;
        margin-left: 4px;
    }

    .axis-pill-input {
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        box-shadow: none;
        color: #334155;
    }

    .axis-pill-input::placeholder {
        color: #94a3b8;
    }

    .axis-pill-input:focus {
        border-color: #cbd5e1;
        box-shadow: 0 0 0 3px rgba(203, 213, 225, 0.2);
    }

    .custom-select-wrapper {
        position: relative;
    }

    .axis-select-trigger {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.6rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        font-size: 0.9rem;
        color: #334155;
        background: white;
    }

    .info-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
    }

    .info-item {
        display: flex;
        margin-bottom: 1.25rem;
    }

    .info-item:last-child {
        margin-bottom: 0;
    }

    .info-icon {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e2e8f0;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
        font-size: 0.95rem;
    }

    .info-icon.bg-light-success,
    .info-icon.text-success {
        background: #f1f5f9;
        /* In the image it's just a light grey circle with a grey checkmark, actually not green */
        color: #64748b;
    }

    .info-text h6 {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 4px;
        margin-top: 2px;
    }

    .info-text p {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0;
        line-height: 1.4;
    }

    .btn-send-invite-custom {
        background-color: #94a3b8;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
    }

    .btn-send-invite-custom:hover {
        background-color: #64748b;
        color: white;
    }

    .btn-cancel-custom {
        background: white;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        border-radius: 6px;
        font-weight: 500;
        padding: 0.5rem 1.25rem;
    }

    /* Fix Select Dropdown & Inputs (Image 1) */
    .axis-select-dropdown {
        border-radius: 6px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        margin-top: 4px;
        padding-top: 4px;
        background: white;
    }

    .axis-select-search input {
        border: none;
        border-bottom: 1px solid #e2e8f0;
        border-radius: 0;
        padding: 0.5rem 2rem 0.5rem 2.5rem;
        font-size: 0.9rem;
        box-shadow: none !important;
        width: 100%;
        color: #334155;
    }

    .axis-select-search {
        position: relative;
    }

    .axis-select-search::before {
        content: "\F52A";
        /* bi-search */
        font-family: bootstrap-icons !important;
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    /* Step 1: Upload Box (Image 2) */
    .drop-zone-premium {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
        background: white;
    }

    .drop-zone-premium:hover {
        border-color: #94a3b8;
        background: #f8fafc;
    }

    .drop-zone-icon-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #334155;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        margin: 0 auto 1.5rem auto;
    }

    /* Steps Progress (Image 3 & 4) */
    .steps-nav {
        display: flex;
        justify-content: space-between;
        position: relative;
        margin-bottom: 2rem;
        padding: 0 2rem;
    }

    .step-progress-line {
        position: absolute;
        top: 14px;
        left: 4rem;
        right: 4rem;
        height: 2px;
        background: #e2e8f0;
        z-index: 1;
    }

    .step-progress-line-fill {
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        background: #10b981;
        transition: width 0.3s ease;
    }

    .step-item {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: white;
        padding: 0 10px;
    }

    .step-circle {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: white;
        border: 2px solid #e2e8f0;
        color: #94a3b8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .step-item.active .step-circle {
        border-color: #10b981;
        color: #10b981;
    }

    .step-item.completed .step-circle {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }

    .step-label {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
    }

    .step-item.active .step-label,
    .step-item.completed .step-label {
        color: #1e293b;
    }

    /* Premium Unified Table (Step 2) */
    .review-table-wrapper {
        max-height: 420px;
        overflow-y: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        /* pale background for the whole table container */
        padding: 0;
    }

    .review-table {
        border-collapse: collapse !important;
        margin-bottom: 0;
        width: 100%;
    }

    .review-table th,
    .review-table td {
        border: none !important;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .review-table tr:last-child td {
        border-bottom: none !important;
        /* hide last row border */
    }

    .review-table thead th {
        background: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
        padding: 0.6rem 1rem;
        position: sticky;
        top: 0;
        z-index: 10;
        border-bottom: 1px solid #e2e8f0 !important;
    }

    .review-table tbody tr {
        background: #f8fafc;
        transition: background 0.15s ease;
    }

    .review-table tbody tr:hover {
        background: #f1f5f9;
    }

    .review-table tbody td {
        padding: 0.4rem 0.5rem;
        vertical-align: middle;
        font-size: 0.85rem;
        white-space: nowrap;
        /* keep content on one line so inputs don't stretch vertically */
    }

    .custom-checkbox-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .custom-checkbox-wrapper input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #4b5563;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Inputs in table */
    .input-with-icon {
        display: flex;
        align-items: center;
        width: 100%;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .input-with-icon:focus-within {
        background: white;
        box-shadow: 0 0 0 1px #cbd5e1;
    }

    .input-with-icon i.bi-pen {
        color: #cbd5e1;
        font-size: 0.75rem;
        margin-left: 6px;
    }

    .review-table input[type="text"] {
        border: none;
        background: transparent;
        width: 100%;
        color: #3F67AC;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.35rem 0.25rem;
        outline: none;
        min-width: 60px;
    }

    /* Phone Wrapper Premium */
    .phone-input-wrapper {
        display: flex;
        align-items: center;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        overflow: hidden;
        width: 100%;
        max-width: 140px;
    }

    .phone-flag-box {
        display: flex;
        align-items: center;
        padding: 0.35rem 0.4rem;
        border-right: 1px solid #e2e8f0;
        background: white;
        cursor: default;
    }

    .phone-input-wrapper input {
        border: none;
        padding: 0.35rem 0.4rem;
        outline: none;
        font-weight: 500;
        color: #3F67AC;
        font-size: 0.85rem;
        width: 100%;
        min-width: 80px;
    }

    /* Custom Pickers */
    .unit-picker-trigger,
    .review-select {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.35rem 0.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        background: white;
        font-size: 0.85rem;
        color: #3F67AC;
        font-weight: 500;
        width: 100%;
        min-width: 80px;
        white-space: nowrap;
        outline: none;
    }

    .review-select {
        width: auto;
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath fill-rule='evenodd' d='M3.646 5.646a.5.5 0 0 1 .708 0L8 9.293l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708zm0 4.708a.5.5 0 0 0 .708 0L8 6.707l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        padding-right: 1.75rem;
    }

    .unit-picker-trigger:hover,
    .review-select:hover {
        border-color: #cbd5e1;
    }

    .review-select:focus {
        border-color: #cbd5e1;
        outline: none;
    }

    .unit-picker-opt {
        padding: 0.4rem 0.75rem;
        cursor: pointer;
        border-radius: 4px;
        font-size: 0.85rem;
        color: #334155;
        display: flex;
        align-items: center;
    }

    .unit-picker-opt:hover {
        background: #f1f5f9;
    }

    /* Buttons & Radio Cards */
    .btn-next-custom {
        background-color: #334155;
        color: white;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
    }

    .btn-next-custom:hover {
        background-color: #1e293b;
        color: white;
    }

    .radio-card {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: flex-start;
        cursor: pointer;
        margin-bottom: 1rem;
        transition: all 0.2s;
    }

    .radio-card:hover {
        border-color: #cbd5e1;
    }

    .radio-card.selected {
        border-color: #10b981;
        background: white;
    }

    .radio-card input[type="radio"] {
        margin-top: 4px;
        margin-right: 12px;
        accent-color: #10b981;
    }

    .radio-card-body h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 4px;
    }

    .radio-card-body p {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0;
        line-height: 1.4;
    }

    .import-summary-banner {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 1rem;
        text-align: center;
        margin-bottom: 24px;
        font-weight: 500;
        color: #1e293b;
        background: #f8fafc;
    }

    .btn-import-final {
        width: 100%;
        background-color: #334155;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.75rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-import-final:hover {
        background-color: #1e293b;
    }
</style>
<?= $this->endSection() ?>

<div class="row">
    <div class="col-12 px-2 px-md-4">




        <!-- ── Hero ── -->
        <div class="cc-hero">
            <div class="cc-hero-left">
                <h2 class="cc-hero-title">Residentes</h2>
                <div class="cc-hero-divider"></div>
                <div class="cc-hero-breadcrumb">
                    <i class="bi bi-people"></i>
                    <i class="bi bi-chevron-right"></i>
                    Gestionar miembros de la comunidad
                </div>
            </div>
            <div class="cc-hero-right">

                <button class="cc-hero-btn" data-bs-toggle="modal" data-bs-target="#inviteModal">
                    <i class="bi bi-plus-lg me-2"></i> Invitar Residente
                </button>

            </div>
        </div>
        <!-- ── END Hero ── -->


        <!-- CONTENEDOR PRINCIPAL BLANCO -->
        <div class="premium-main-container mb-4">

            <!-- BARRA DE HERRAMIENTAS (Adentro del contenedor) -->
            <div class="premium-filter-bar">
                <div class="d-flex align-items-center gap-2">
                    <div class="search-input-group">
                        <i class="bi bi-search" style="font-size:0.85rem"></i>
                        <input type="text" class="form-control form-control-sm border shadow-none" id="res-search"
                            placeholder="Buscar residentes..."
                            style="width:220px; font-size:0.85rem; border-radius:6px; padding:0.35rem 0.5rem 0.35rem 2.25rem;">
                    </div>
                    <button class="btn btn-white border rounded-2 px-2 py-1 shadow-none" onclick="location.reload()"
                        title="Actualizar">
                        <i class="bi bi-arrow-clockwise text-muted"></i>
                    </button>
                </div>

                <div class="nav nav-pills nav-pills-premium" id="res-filters">
                    <button class="nav-link active" data-filter="all">
                        Todos <span class="count-badge"><?= $counts['all'] ?></span>
                    </button>
                    <button class="nav-link" data-filter="owner">
                        <i class="bi bi-house-door me-1"></i> Propietarios <span
                            class="count-badge"><?= $counts['owner'] ?></span>
                    </button>
                    <button class="nav-link" data-filter="tenant">
                        <i class="bi bi-person me-1"></i> Inquilinos <span
                            class="count-badge"><?= $counts['tenant'] ?></span>
                    </button>
                </div>


            </div>

            <!-- TABLA DE RESULTADOS -->
            <div class="table-responsive">
                <table class="table mb-0 axis-table" id="residents-table">
                    <thead>
                        <tr>
                            <th style="width: 28%;">Nombre <i class="bi bi-arrow-up text-muted small ms-1"></i></th>
                            <th style="width: 25%;">Correo <i class="bi bi-arrow-down-up text-muted small ms-1"></i>
                            </th>
                            <th style="width: 15%;">Teléfono <i class="bi bi-arrow-down-up text-muted small ms-1"></i>
                            </th>
                            <th style="width: 10%;">Unidad <i class="bi bi-arrow-down-up text-muted small ms-1"></i>
                            </th>
                            <th style="width: 12%;">Tipo <i class="bi bi-arrow-down-up text-muted small ms-1"></i></th>
                            <th style="width: 10%;">Roles <i class="bi bi-arrow-down-up text-muted small ms-1"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($residents as $res): ?>
                            <tr class="res-row" data-type="<?= $res['type'] ?>" data-user-id="<?= $res['user_id'] ?>"
                                style="cursor: pointer;">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="resident-avatar" style="overflow: hidden; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                            <?php if (!empty($res['avatar'])): ?>
                                                <img src="<?= base_url('media/image/avatars/' . $res['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php else: ?>
                                                <?= strtoupper(substr($res['first_name'], 0, 1)) ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="fw-bold"><?= esc($res['first_name'] . ' ' . $res['last_name']) ?></div>
                                    </div>
                                </td>
                                <td class="text-secondary"><?= esc($res['email']) ?></td>
                                <td class="text-secondary">
                                    <?= !empty($res['phone']) ? esc($res['phone']) : '<span class="text-muted">—</span>' ?>
                                </td>
                                <td>
                                    <span class="text-dark fw-500"><?= $res['unit_name'] ?></span>
                                </td>
                                <td>
                                    <?php if ($res['type'] === 'owner'): ?>
                                        <a href="#" class="type-link"><i class="bi bi-house-door"></i> Propietario</a>
                                    <?php else: ?>
                                        <a href="#" class="type-link tenant"><i class="bi bi-person"></i> Inquilino</a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="role-pill">Residente</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal Invitar Reutilizado -->
<?= $this->include('admin/modals/invite_resident_modal') ?>

<!-- Modal Gestionar Residente -->
<?= $this->include('admin/modals/manage_resident_modal') ?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('res-search');
        const filterButtons = document.querySelectorAll('#res-filters .nav-link');
        const rows = document.querySelectorAll('.res-row');
        let currentFilter = 'all';

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const name = row.querySelector('.fw-bold').textContent.toLowerCase();
                const email = row.querySelector('.text-secondary').textContent.toLowerCase();
                const type = row.getAttribute('data-type');

                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
                const matchesFilter = currentFilter === 'all' || type === currentFilter;

                if (matchesSearch && matchesFilter) {
                    row.classList.remove('d-none');
                } else {
                    row.classList.add('d-none');
                }
            });
        }

        searchInput.addEventListener('input', filterTable);

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                currentFilter = btn.getAttribute('data-filter');
                filterTable();
            });
        });

        // ==========================================
        // MANAGE RESIDENT MODAL LOGIC
        // ==========================================
        const manageResModalObj = new bootstrap.Modal(document.getElementById('manageResidentModal'));
        const confirmRemoveUnitModalObj = new bootstrap.Modal(document.getElementById('confirmRemoveUnitModal'));
        const confirmRemoveCommunityModalObj = new bootstrap.Modal(document.getElementById('confirmRemoveCommunityModal'));

        let profileUserId = null;
        let profileName = '';
        let targetRemoveResidentId = null;

        // 1. Click row -> Open Manage Modal
        rows.forEach(row => {
            row.addEventListener('click', function () {
                const userId = this.getAttribute('data-user-id');
                if (userId) openManageModal(userId);
            });
        });

        window.openManageModal = function (userId) {
            profileUserId = userId;
            document.getElementById('mr-name-display').textContent = 'Cargando...';
            document.getElementById('mr-email-display').textContent = '...';
            document.getElementById('mr-phone-header-display').textContent = '...';
            document.getElementById('mr-phone-list').textContent = '...';
            document.getElementById('mr-member-since-display').textContent = 'Cargando...';
            document.getElementById('mr-avatar-initials').innerHTML = '-';
            document.getElementById('mr-units-container').innerHTML = '<div class="text-center text-muted small py-3">Cargando unidades...</div>';

            manageResModalObj.show();

            fetch(`<?= base_url('admin/residentes/profile') ?>/${userId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderManageProfile(data);
                    } else {
                        alert(data.message || 'Error al cargar el perfil.');
                        manageResModalObj.hide();
                    }
                })
                .catch(e => { console.error(e); alert('Error de conexión.'); manageResModalObj.hide(); });
        }

        function renderManageProfile(data) {
            const user = data.user;
            const assignments = data.assignments;
            profileName = user.first_name + ' ' + user.last_name;

            document.getElementById('mr-name-display').textContent = profileName;
            document.getElementById('mr-email-display').textContent = user.email;
            
            const avatarEl = document.getElementById('mr-avatar-initials');
            if (user.avatar) {
                avatarEl.style.overflow = 'hidden';
                avatarEl.style.padding = '0';
                avatarEl.innerHTML = `<img src="<?= base_url('media/image/avatars/') ?>${user.avatar}" alt="Avatar" style="width:100%; height:100%; object-fit:cover;">`;
            } else {
                avatarEl.style.overflow = 'visible';
                avatarEl.innerHTML = user.first_name.charAt(0).toUpperCase();
            }

            const phoneStr = user.phone ? user.phone : 'Sin teléfono';
            document.getElementById('mr-phone-header-display').textContent = phoneStr;
            document.getElementById('mr-phone-list').textContent = phoneStr;

            const statusEl = document.getElementById('mr-status-display');
            if (user.status === 'active') {
                statusEl.textContent = 'Activo';
                statusEl.className = 'badge bg-success bg-opacity-10 text-success rounded-pill px-2 py-1 border border-success border-opacity-10';
            } else {
                statusEl.textContent = 'Inactivo';
                statusEl.className = 'badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2 py-1 border border-secondary border-opacity-10';
            }

            if (user.created_at) {
                const dateObj = new Date(user.created_at);
                const options = { year: 'numeric', month: 'short', day: 'numeric' };
                document.getElementById('mr-member-since-display').textContent = dateObj.toLocaleDateString('es-ES', options);
            } else {
                document.getElementById('mr-member-since-display').textContent = 'Desconocido';
            }

            const container = document.getElementById('mr-units-container');
            container.innerHTML = '';

            if (assignments.length === 0) {
                container.innerHTML = '<div class="text-center text-muted small py-3">Sin unidades asignadas.</div>';
            } else {
                assignments.forEach(assign => {
                    const isOwner = assign.type === 'owner';
                    container.insertAdjacentHTML('beforeend', `
                        <div class="mr-unit-item">
                            <div class="mr-unit-name">
                                ${assign.unit_number || '<i>Sin Asignar</i>'}
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div style="position: relative;">
                                    <i class="bi bi-${isOwner ? 'house-door' : 'key'} mr-role-icon"></i>
                                    <select class="mr-role-select" onchange="changeUnitRole(${assign.resident_id}, this.value)">
                                        <option value="owner" ${isOwner ? 'selected' : ''}>Propietario</option>
                                        <option value="tenant" ${!isOwner ? 'selected' : ''}>Inquilino</option>
                                    </select>
                                </div>
                                <button class="mr-btn-remove-unit" onclick="promptRemoveUnit(${assign.resident_id}, '${assign.unit_number}', ${assignments.length === 1})" title="Quitar de unidad">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        </div>
                    `);
                });
            }
        }

        window.changeUnitRole = function (residentId, newRole) {
            let fd = new FormData();
            fd.append('resident_id', residentId);
            fd.append('role', newRole);
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url("admin/residentes/cambiar-rol") ?>', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Error al cambiar rol', confirmButtonColor: '#6366f1' });
                    } else {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Rol actualizado exitosamente', showConfirmButton: false, timer: 2500 });
                    }
                }).catch(e => console.error(e));
        }

        // 2. Remove Unit Flow
        window.promptRemoveUnit = function (residentId, unitName, isLastUnit) {
            targetRemoveResidentId = residentId;
            if (isLastUnit) {
                // Warning that removing last unit removes from community completely
                document.getElementById('mr-confirm-community-text').textContent = `¿Está seguro que desea remover a ${profileName} de su última unidad? Al hacerlo, perderá todo acceso a esta comunidad.`;
                manageResModalObj.hide();
                confirmRemoveCommunityModalObj.show();
            } else {
                doRemoveUnitOnly();
            }
        }

        window.doRemoveUnitOnly = function () {
            if (!targetRemoveResidentId) return;
            let fd = new FormData();
            fd.append('resident_id', targetRemoveResidentId);
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url("admin/residentes/remover-unidad") ?>', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        confirmRemoveUnitModalObj.hide();
                        window.location.reload(); // Reload to refresh directory
                    } else alert(data.message || 'Error al remover');
                }).catch(e => console.error(e));
        }

        // 3. Remove Community Flow
        document.getElementById('btn-trigger-remove-community').addEventListener('click', function () {
            manageResModalObj.hide();
            document.getElementById('mr-confirm-community-text').textContent = `¿Está seguro que desea remover a ${profileName} de esta comunidad?`;
            confirmRemoveCommunityModalObj.show();
        });

        window.doRemoveCommunity = function () {
            if (!profileUserId) return;
            let fd = new FormData();
            fd.append('user_id', profileUserId);
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url("admin/residentes/remover-comunidad") ?>', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        confirmRemoveCommunityModalObj.hide();
                        confirmRemoveUnitModalObj.hide();
                        window.location.reload(); // Reload to update table
                    } else alert(data.message || 'Error al remover de la comunidad');
                }).catch(e => console.error(e));
        }

        // 4. Update Phone Logic
        const editPhoneModalObj = new bootstrap.Modal(document.getElementById('editPhoneModal'));

        document.getElementById('mr-btn-add-phone').addEventListener('click', function () {
            if (!profileUserId) return;
            let currentPhone = document.getElementById('mr-phone-list').textContent.trim();
            if (currentPhone === 'Sin teléfono') currentPhone = '';
            document.getElementById('mr-phone-input-field').value = currentPhone;
            editPhoneModalObj.show();
        });

        document.getElementById('mr-btn-save-phone').addEventListener('click', function () {
            if (!profileUserId) return;
            const phoneVal = document.getElementById('mr-phone-input-field').value.trim();
            if (!phoneVal) {
                Swal.fire({ icon: 'warning', title: 'Atención', text: '¡Debes ingresar un número!', confirmButtonColor: '#1e293b' });
                return;
            }

            let fd = new FormData();
            fd.append('user_id', profileUserId);
            fd.append('phone', phoneVal);
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url("admin/residentes/actualizar-telefono") ?>', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        editPhoneModalObj.hide();
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Teléfono guardado', showConfirmButton: false, timer: 2500 });
                        document.getElementById('mr-phone-header-display').textContent = phoneVal;
                        document.getElementById('mr-phone-list').textContent = phoneVal;

                        // update main directory table dynamically too
                        const row = document.querySelector(`.res-row[data-user-id="${profileUserId}"]`);
                        if (row) row.querySelector('td:nth-child(3)').textContent = phoneVal;
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Error al guardar.' });
                    }
                }).catch(e => console.error(e));
        });

        // Handle Manage Res Modal re-show if secondary modals are cancelled
        document.getElementById('confirmRemoveUnitModal').addEventListener('hidden.bs.modal', function () {
            if (!profileUserId) return; // if it was explicitly cleared maybe
            // Could re-show if needed but usually cancelling is fine
        });
        document.getElementById('confirmRemoveCommunityModal').addEventListener('hidden.bs.modal', function () {
            // manageResModalObj.show();
        });

        // ==========================================
        // MANAGE RESIDENT UNIT PICKER LOGIC
        // ==========================================
        const availableUnits = <?= json_encode(array_values($units ?? [])) ?>;
        const pickerTrigger = document.querySelector('#mr-add-unit-picker .unit-picker-trigger');
        const pickerPanel = document.querySelector('#mr-add-unit-picker .unit-picker-panel');
        const pickerSearch = document.querySelector('#mr-add-unit-picker .unit-search-field');
        const pickerOptions = document.getElementById('mr-available-units-list');
        let currentPrimaryResidentId = null;

        // Populate options based on filter
        function renderPickerOptions(filter = '') {
            pickerOptions.innerHTML = '';
            const lowerFilter = filter.toLowerCase();
            const filtered = availableUnits.filter(u => u.unit_number.toLowerCase().includes(lowerFilter));

            if (filtered.length === 0) {
                pickerOptions.innerHTML = '<div class="text-muted p-2 small text-center">No units found</div>';
                return;
            }

            filtered.forEach(u => {
                const div = document.createElement('div');
                div.className = 'unit-picker-opt';
                div.textContent = u.unit_number;
                div.onclick = function (e) {
                    e.stopPropagation();
                    selectUnit(u.id);
                };
                pickerOptions.appendChild(div);
            });
        }

        // Toggle panel
        pickerTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            const isVisible = pickerPanel.style.display === 'block';

            // hide all other panels if any
            document.querySelectorAll('.unit-picker-panel').forEach(p => p.style.display = 'none');

            if (!isVisible) {
                pickerPanel.style.display = 'block';
                pickerSearch.value = '';
                renderPickerOptions();
                pickerSearch.focus();
            }
        });

        // Search typing
        pickerSearch.addEventListener('input', function (e) {
            renderPickerOptions(e.target.value);
        });

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('#mr-add-unit-picker')) {
                if (pickerPanel) pickerPanel.style.display = 'none';
            }
        });

        function selectUnit(unitId) {
            pickerPanel.style.display = 'none';
            if (!currentPrimaryResidentId) {
                Swal.fire({ icon: 'warning', title: 'Aviso', text: 'No se pudo determinar el registro a actualizar.', confirmButtonColor: '#6366f1' });
                return;
            }

            let fd = new FormData();
            fd.append('resident_id', currentPrimaryResidentId);
            fd.append('unit_id', unitId);
            fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            fetch('<?= base_url("admin/residentes/cambiar-unidad") ?>', { method: 'POST', body: fd })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Unidad actualizada', showConfirmButton: false, timer: 2500 });
                        // Refresh profile to show new unit
                        openManageModal(profileUserId);
                        // Also might want to reload the page in background to update directory
                        setTimeout(() => window.location.reload(), 1500);
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: data.message || 'Error al cambiar unidad', confirmButtonColor: '#6366f1' });
                    }
                }).catch(e => console.error(e));
        }

        // We need to capture the primary resident id from the profile render
        // Modifying renderManageProfile hook:
        const originalRenderManageProfile = renderManageProfile;
        renderManageProfile = function (data) {
            originalRenderManageProfile(data);
            if (data.assignments && data.assignments.length > 0) {
                currentPrimaryResidentId = data.assignments[0].resident_id; // we update the first/main one when "Cambiar" is clicked
            } else {
                currentPrimaryResidentId = null;
            }
        };

    });
</script>

<?= $this->endSection() ?>