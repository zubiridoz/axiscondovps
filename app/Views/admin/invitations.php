<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?><?= $this->section('styles') ?>
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

    /* INVITATIONS SPECIFIC BADGES */
    .badge-count-premium {
        background: #1D4C9D;
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-pill {
        padding: 0.2rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .status-pending {
        background: #fffbeb;
        color: #b45309;
        border: 1px solid #fef3c7;
    }

    .status-tosend {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #e0f2fe;
    }

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
        display: flex !important;
        align-items: center !important;
        width: 100%;
        border-radius: 4px;
        transition: all 0.2s;
        flex-wrap: nowrap !important;
    }

    .input-with-icon:focus-within {
        background: white;
        box-shadow: 0 0 0 1px #cbd5e1;
    }

    .input-with-icon i.bi-pen {
        color: #cbd5e1;
        font-size: 0.75rem;
        margin-left: 6px;
        flex-shrink: 0 !important;
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

    .unit-picker {
        position: relative;
    }

    .unit-picker-panel {
        display: none;
        position: absolute;
        top: 100%;
        min-width: 160px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        margin-top: 4px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        z-index: 1060;
    }

    .unit-picker-search {
        padding: 0.5rem;
        border-bottom: 1px solid #f1f5f9;
        background: white;
        border-radius: 0.6rem 0.6rem 0 0;
        position: relative;
    }

    .unit-picker-search input {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 0.4rem;
        padding: 0.35rem 0.5rem;
        font-size: 0.8rem;
    }

    .unit-picker-search input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.1);
    }

    .unit-picker-options {
        max-height: 160px;
        overflow-y: auto;
        border-radius: 0 0 0.6rem 0.6rem;
        background: white;
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
    <div class="col-12 px-2 px-md-4 mt-2">



        <!-- ── Hero ── -->
        <div class="cc-hero">
            <div class="cc-hero-left">
                <h2 class="cc-hero-title">Invitaciones Pendientes</h2>
                <div class="cc-hero-divider"></div>
                <div class="cc-hero-breadcrumb">
                    <i class="bi bi-people"></i>
                    <i class="bi bi-chevron-right"></i>
                    Invitaciones esperando a que los residentes acepeten y se registren
                </div>
            </div>
            <div class="cc-hero-right">
                <button type="button" class="cc-hero-btn" data-bs-toggle="modal" data-bs-target="#resendModal">
                    <i class="bi bi-send me-2"></i> Reenviar
                </button>

                <button class="cc-hero-btndark" data-bs-toggle="modal" data-bs-target="#inviteModal">
                    <i class="bi bi-plus-lg me-2"></i> Invitar Residente
                </button>
            </div>
        </div>
        <!-- ── END Hero ── -->



        <div class="premium-main-container mb-4">
            <!-- BARRA DE HERRAMIENTAS -->
            <div class="premium-filter-bar">
                <div class="d-flex align-items-center gap-2">
                    <div class="search-input-group">
                        <i class="bi bi-search" style="font-size:0.85rem"></i>
                        <input type="text" class="form-control form-control-sm border shadow-none" id="inv-search"
                            placeholder="Buscar residentes..."
                            style="width:220px; font-size:0.85rem; border-radius:6px; padding:0.35rem 0.5rem 0.35rem 2.25rem;">
                    </div>
                    <button class="btn btn-white border rounded-2 px-2 py-1 shadow-none" onclick="location.reload()"
                        title="Actualizar">
                        <i class="bi bi-arrow-clockwise text-muted"></i>
                    </button>
                    <span class="text-muted small fw-500 ms-2"></span>
                </div>

                <div class="d-flex gap-2">
                    <div class="badge-count-premium">

                        <span id="inv-count">
                            <?= $counts['pending'] ?>
                        </span>
                        pendiente
                        <?= $counts['pending'] !== 1 ? 's' : '' ?>
                    </div>
                </div>
            </div>

            <!-- TABLA DE INVITACIONES -->
            <div class="table-responsive">
                <table class="table mb-0 axis-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" class="form-check-input shadow-none"></th>
                            <th style="width: 25%;">Nombre <i class="bi bi-arrow-up-down text-muted small ms-1"></i>
                            </th>
                            <th style="width: 25%;">Contacto <i class="bi bi-arrow-up-down text-muted small ms-1"></i>
                            </th>
                            <th style="width: 10%;">Unidad <i class="bi bi-arrow-up-down text-muted small ms-1"></i>
                            </th>
                            <th style="width: 10%;">Tipo <i class="bi bi-arrow-up-down text-muted small ms-1"></i></th>
                            <th style="width: 15%;">Última Invitación <i
                                    class="bi bi-arrow-down text-muted small ms-1"></i></th>
                            <th style="width: 10%;">Estado</th>
                        </tr>
                    </thead>
                    <tbody id="inv-table-body">
                        <?php foreach ($invitations as $inv): ?>
                            <tr class="inv-row hover-row" style="cursor: pointer;" data-id="<?= $inv['id'] ?>"
                                data-name="<?= esc($inv['name']) ?>" data-email="<?= esc($inv['email']) ?>"
                                data-phone="<?= esc($inv['phone']) ?>" data-unit-id="<?= esc($inv['unit_id']) ?>"
                                data-unit="<?= esc($inv['unit']) ?>" data-role-raw="<?= esc($inv['role_raw']) ?>"
                                data-type="<?= esc($inv['type']) ?>"
                                data-last-invite="<?= esc($inv['last_invite_formatted']) ?>"
                                data-status-raw="<?= esc($inv['status_raw']) ?>" data-status="<?= esc($inv['status']) ?>"
                                onclick="openInvDetails(this)">
                                <td onclick="event.stopPropagation()"><input type="checkbox"
                                        class="form-check-input shadow-none"></td>
                                <td class="fw-bold"><?= esc($inv['name']) ?></td>
                                <td class="text-secondary">
                                    <div class="d-flex flex-column" style="font-size: 0.85rem;">
                                        <span><i class="bi bi-envelope me-1 text-muted"></i><?= esc($inv['email']) ?></span>
                                    </div>
                                </td>
                                <td><span class="text-dark fw-500"><?= $inv['unit'] ?></span></td>
                                <td class="text-secondary"><?= $inv['type'] ?></td>
                                <td class="text-muted"><?= $inv['last_invite'] ?></td>
                                <td>
                                    <?php if ($inv['status_raw'] === 'pending'): ?>
                                        <span class="status-pill status-pending"><i class="bi bi-clock-history"></i>
                                            Pendiente</span>
                                    <?php else: ?>
                                        <span class="status-pill status-tosend"><i class="bi bi-send-dash"></i> Por
                                            enviar</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modales Reutilizados -->
<?= $this->include('admin/modals/invite_resident_modal') ?>
<?= $this->include('admin/modals/resend_invitations_modal') ?>

<!-- Modal 1: Detalles de Invitación -->
<div class="modal fade" id="modalInvDetails" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0 mt-2 px-4">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">

                <div class="d-flex align-items-center mb-4">
                    <div class="resident-avatar me-3"
                        style="width: 50px; height: 50px; background: #334155; color: white;">
                        <i class="bi bi-envelope" style="font-size: 1.5rem;"></i>
                    </div>
                    <div>
                        <h5 id="inv-det-name" class="mb-0" style="font-weight: 500; font-size: 1.15rem;">Cargando...
                        </h5>
                        <div class="text-muted small"><i class="bi bi-envelope"></i> <span id="inv-det-email">...</span>
                        </div>
                    </div>
                </div>

                <hr style="border-color: #e2e8f0; margin: 1.5rem 0;">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <div class="text-dark fw-500 mb-1" style="font-size: 0.85rem;">Estado de Invitación <i
                                class="bi bi-info-circle text-muted ms-1" style="font-size: 0.75rem;"></i></div>
                        <span class="status-pill status-pending" id="inv-det-status">Pendiente</span>
                    </div>
                    <button class="btn btn-white border px-3 py-1"
                        style="border-radius: 0.5rem; font-weight: 500; font-size: 0.9rem;" id="btn-edit-inv">
                        <i class="bi bi-pencil-square me-1"></i> Editar Invitación
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <div class="text-dark fw-500 mb-1" style="font-size: 0.85rem;">Ultima Notificacion <i
                                class="bi bi-info-circle text-muted ms-1" style="font-size: 0.75rem;"></i></div>
                        <div class="text-dark" style="font-size: 0.9rem;" id="inv-det-date">...</div>
                    </div>
                    <button class="btn btn-white border px-3 py-1"
                        style="border-radius: 0.5rem; font-weight: 500; font-size: 0.9rem;" id="btn-resend-inv">
                        <i class="bi bi-send me-1"></i> Reenviar Invitación
                    </button>
                </div>

                <div class="mb-4">
                    <div class="text-dark fw-500 mb-1" style="font-size: 0.85rem;">Unidad <i
                            class="bi bi-info-circle text-muted ms-1" style="font-size: 0.75rem;"></i></div>
                    <div class="d-flex align-items-center mt-2">
                        <i class="bi bi-house-door text-muted me-2"></i>
                        <span class="text-dark fw-500 me-2" id="inv-det-unit" style="font-size: 0.9rem;">-</span>
                        <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill fw-500 px-2"
                            id="inv-det-role">-</span>
                    </div>
                </div>

                <hr style="border-color: #e2e8f0; margin: 1.5rem 0;">

                <div class="d-flex justify-content-end">
                    <button class="btn btn-white border border-danger text-danger px-4 py-2"
                        style="border-radius: 0.5rem; font-weight: 500; font-size: 0.9rem; background: #fef2f2;"
                        id="btn-del-inv">
                        <i class="bi bi-trash3 me-1"></i> Eliminar Invitación
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Modal 2: Editar Invitación -->
<div class="modal fade" id="modalInvEdit" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content" style="border-radius: 1rem; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-header border-0 pb-0 mt-2 px-4">
                <h5 class="modal-title" style="color: #1e293b; font-weight: 600; font-size: 1.1rem;">Editar Invitación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4 pt-2">
                <p class="text-muted mb-4" style="font-size: 0.85rem;">Actualice los detalles de la invitación a
                    continuación. Los cambios se guardarán inmediatamente.</p>

                <div class="mb-3">
                    <label class="form-label text-dark fw-500 mb-1" style="font-size: 0.85rem;">Nombre</label>
                    <input type="text" class="form-control shadow-none" id="inv-edit-name"
                        style="border-radius: 0.5rem; border-color: #94a3b8; border-width: 2px;">
                </div>

                <div class="row mb-1">
                    <div class="col-6 pe-2">
                        <label class="form-label text-dark fw-500 mb-1" style="font-size: 0.85rem;"><i
                                class="bi bi-envelope"></i> Correo Electrónico</label>
                        <input type="email" class="form-control shadow-none" id="inv-edit-email"
                            style="border-radius: 0.5rem;">
                    </div>
                    <div class="col-6 ps-2">
                        <label class="form-label text-dark fw-500 mb-1" style="font-size: 0.85rem;"><i
                                class="bi bi-chat-left"></i> Número de Teléfono</label>
                        <div class="phone-input-wrapper" style="max-width: 100%; border-radius: 0.5rem;">
                            <div class="phone-flag-box px-2 py-1">
                                🇲🇽 <i class="bi bi-chevron-expand ms-1 text-muted" style="font-size: 0.75rem;"></i>
                            </div>
                            <input type="text" id="inv-edit-phone" class="form-control border-0 shadow-none py-1"
                                placeholder="234 567 8900" style="font-size: 0.85rem;">
                        </div>
                    </div>
                </div>
                <div class="text-muted small mb-3">Se requiere al menos un método de contacto</div>

                <div class="row mb-4">
                    <div class="col-6 pe-2">
                        <label class="form-label text-dark fw-500 mb-1" style="font-size: 0.85rem;">Unidad</label>
                        <!-- Unit searchable dropdown -->
                        <div class="unit-picker" id="inv-edit-unit-picker" style="width: 100%;">
                            <button type="button" class="unit-picker-trigger" id="inv-edit-unit-str"
                                style="border-radius: 0.5rem;">
                                Seleccionar <i class="bi bi-chevron-expand ms-1"></i>
                            </button>
                            <div class="unit-picker-panel"
                                style="left: 0; right: auto; width: 100%; top: calc(100% + 4px);">
                                <div class="unit-picker-search">
                                    <i class="bi bi-search text-muted position-absolute"
                                        style="left: 12px; top: 12px; font-size: 0.8rem;"></i>
                                    <input type="text" placeholder="Buscar" class="unit-search-field"
                                        style="padding-left: 2rem;">
                                </div>
                                <div class="unit-picker-options" id="inv-edit-units-list">
                                    <!-- Populated in JS from available units -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 ps-2">
                        <label class="form-label text-dark fw-500 mb-1" style="font-size: 0.85rem;">Rol en la
                            Unidad</label>
                        <select class="form-select shadow-none" id="inv-edit-role"
                            style="border-radius: 0.5rem; font-size: 0.9rem;">
                            <option value="owner">Propietario</option>
                            <option value="tenant">Inquilino</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button class="btn btn-outline-secondary px-4 py-2"
                        style="border-radius: 0.5rem; font-size: 0.9rem;" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn px-4 py-2"
                        style="background-color: #3F67AC; color: white; border-radius: 0.5rem; font-weight: 500; font-size: 0.9rem; border: none;"
                        id="btn-save-inv-edit">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('inv-search');
        const rows = document.querySelectorAll('.inv-row');
        const countSpan = document.getElementById('inv-count');

        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            let visibleCount = 0;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(term)) {
                    row.style.display = 'table-row';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            countSpan.textContent = visibleCount;
        });

        // -------------------------
        // LOGICA DE MODALES DE INVITACION (DETALLE / EDITAR)
        // -------------------------
        let currentInvId = null;
        const availableUnits = <?= json_encode($units) ?>;
        const invEditUnitsList = document.getElementById('inv-edit-units-list');
        const invEditUnitStr = document.getElementById('inv-edit-unit-str');
        const invEditUnitPickerPanel = document.querySelector('#inv-edit-unit-picker .unit-picker-panel');
        let selectedUnitIdEdit = '';

        function renderEditUnits(search = '') {
            if (!invEditUnitsList) return;
            invEditUnitsList.innerHTML = '';

            // Default none option
            if (!search) {
                let optNone = document.createElement('div');
                optNone.className = 'unit-picker-opt text-muted';
                optNone.innerHTML = `Sin asignar`;
                optNone.onclick = () => { selectUnitEdit('', 'Sin asignar'); };
                invEditUnitsList.appendChild(optNone);
            }

            const filtered = availableUnits.filter(u => u.unit_number.toLowerCase().includes(search.toLowerCase()));
            filtered.forEach(u => {
                let opt = document.createElement('div');
                opt.className = 'unit-picker-opt';
                opt.innerHTML = u.unit_number;
                opt.onclick = () => { selectUnitEdit(u.id, u.unit_number); };
                invEditUnitsList.appendChild(opt);
            });
        }

        function selectUnitEdit(id, text) {
            selectedUnitIdEdit = id;
            invEditUnitStr.innerHTML = `${text} <i class="bi bi-chevron-expand ms-1"></i>`;
            invEditUnitPickerPanel.style.display = 'none';
        }

        const unitSearchField = document.querySelector('#inv-edit-unit-picker .unit-search-field');
        if (unitSearchField) {
            unitSearchField.addEventListener('input', (e) => {
                renderEditUnits(e.target.value);
            });
        }

        if (invEditUnitStr) {
            invEditUnitStr.addEventListener('click', (e) => {
                e.stopPropagation();
                renderEditUnits('');
                invEditUnitPickerPanel.style.display = invEditUnitPickerPanel.style.display === 'block' ? 'none' : 'block';
            });
        }

        document.addEventListener('click', () => {
            if (invEditUnitPickerPanel) invEditUnitPickerPanel.style.display = 'none';
        });

        // Row Click Function (Global for onclick)
        window.openInvDetails = function (tr) {
            currentInvId = tr.getAttribute('data-id');
            const unitText = tr.getAttribute('data-unit') && tr.getAttribute('data-unit') !== '-' ? tr.getAttribute('data-unit') : 'Sin asignar';

            document.getElementById('inv-det-name').textContent = tr.getAttribute('data-name');
            document.getElementById('inv-det-email').textContent = tr.getAttribute('data-email');

            // Status Badges mapping
            const stRaw = tr.getAttribute('data-status-raw');
            const stIcon = stRaw === 'pending' ? '<i class="bi bi-clock-history"></i>' : '<i class="bi bi-send-dash"></i>';
            const stClass = stRaw === 'pending' ? 'status-pending' : 'status-tosend';
            document.getElementById('inv-det-status').innerHTML = `${stIcon} ${tr.getAttribute('data-status')}`;
            document.getElementById('inv-det-status').className = `status-pill ${stClass}`;

            document.getElementById('inv-det-date').textContent = tr.getAttribute('data-last-invite');
            document.getElementById('inv-det-unit').textContent = unitText;
            document.getElementById('inv-det-role').textContent = tr.getAttribute('data-type');

            // Store details for Edit Modal later
            document.getElementById('inv-edit-name').value = tr.getAttribute('data-name');
            document.getElementById('inv-edit-email').value = tr.getAttribute('data-email');
            document.getElementById('inv-edit-phone').value = tr.getAttribute('data-phone') || '';
            document.getElementById('inv-edit-role').value = tr.getAttribute('data-role-raw') || 'owner';
            selectUnitEdit(tr.getAttribute('data-unit-id') || '', unitText);

            new bootstrap.Modal(document.getElementById('modalInvDetails')).show();
        };

        const btnEditInv = document.getElementById('btn-edit-inv');
        if (btnEditInv) {
            btnEditInv.onclick = function () {
                const detModal = bootstrap.Modal.getInstance(document.getElementById('modalInvDetails'));
                if (detModal) detModal.hide();
                new bootstrap.Modal(document.getElementById('modalInvEdit')).show();
            };
        }

        const btnSaveInvEdit = document.getElementById('btn-save-inv-edit');
        if (btnSaveInvEdit) {
            btnSaveInvEdit.onclick = function () {
                let fd = new FormData();
                fd.append('id', currentInvId);
                fd.append('name', document.getElementById('inv-edit-name').value);
                fd.append('email', document.getElementById('inv-edit-email').value);
                fd.append('phone', document.getElementById('inv-edit-phone').value);
                fd.append('unit_id', selectedUnitIdEdit);
                fd.append('role', document.getElementById('inv-edit-role').value);
                fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                fetch('<?= base_url("admin/residentes/invitaciones/actualizar") ?>', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(r => {
                        if (r.success) {
                            location.reload();
                        } else Swal.fire({ icon: 'error', text: r.message });
                    });
            };
        }

        const btnDelInv = document.getElementById('btn-del-inv');
        if (btnDelInv) {
            btnDelInv.onclick = function () {
                Swal.fire({
                    title: '¿Eliminar invitación?',
                    text: 'La pre-cuenta se eliminará y el usuario podrá ser invitado nuevamente en un futuro con el mismo correo.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((res) => {
                    if (res.isConfirmed) {
                        let fd = new FormData();
                        fd.append('id', currentInvId);
                        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
                        fetch('<?= base_url("admin/residentes/invitaciones/eliminar") ?>', { method: 'POST', body: fd })
                            .then(r => r.json())
                            .then(r => {
                                if (r.success) location.reload();
                                else Swal.fire({ icon: 'error', text: r.message });
                            });
                    }
                });
            };
        }

        const btnResendInv = document.getElementById('btn-resend-inv');
        if (btnResendInv) {
            btnResendInv.onclick = function (e) {
                const btn = e.currentTarget;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Enviando...`;
                btn.disabled = true;

                let fd = new FormData();
                fd.append('id', currentInvId);
                fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                fetch('<?= base_url("admin/residentes/invitaciones/reenviar") ?>', { method: 'POST', body: fd })
                    .then(r => r.json())
                    .then(r => {
                        btn.innerHTML = `<i class="bi bi-send me-1"></i> Reenviar Invitación`;
                        btn.disabled = false;
                        if (r.success) {
                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Invitación enviada de nuevo', showConfirmButton: false, timer: 3000 });

                            // Visual update for date formatting (approximate)
                            const dateRaw = new Date(r.invited_at_raw);
                            const options = { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
                            document.getElementById('inv-det-date').textContent = dateRaw.toLocaleDateString('es-ES', options);

                            // Close modal
                            setTimeout(() => {
                                location.reload();
                            }, 1500);

                        } else Swal.fire({ icon: 'error', text: r.message });
                    }).catch(() => {
                        btn.innerHTML = `<i class="bi bi-send me-1"></i> Reenviar Invitación`;
                        btn.disabled = false;
                    });
            };
        }
    });
</script>

<?= $this->endSection() ?>