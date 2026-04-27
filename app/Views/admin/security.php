<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<!-- Flatpickr Premium Calendars -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<!-- TomSelect Searchable Dropdown -->
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<!-- html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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

    /* ── end Hero ── */

    .flatpickr-calendar {
        z-index: 99999 !important;
    }

    .bg-slate-dark {
        background-color: #2a3547 !important;
    }

    .nav-pills-custom {
        background: #f8f9fc;
        border-radius: 50px;
        padding: 4px;
        border: 1px solid #eef1f6;
    }

    .nav-pills-custom .nav-link {
        border-radius: 50px;
        color: #64748b !important;
        font-weight: 500;
        padding: 8px 16px;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        cursor: pointer;
        border: none;
        background: transparent !important;
    }

    .nav-pills-custom .nav-link:hover,
    .nav-pills-custom .nav-link:focus {
        color: #1e293b !important;
        background-color: rgba(30, 41, 59, 0.05) !important;
    }

    .nav-pills-custom .nav-link.active {
        background-color: #ffffff !important;
        color: #1e293b !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        font-weight: 600;
    }

    .date-control {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: stretch;
    }

    .date-control .btn-arrow {
        border: none;
        background: transparent;
        padding: 6px 12px;
        color: #64748b;
        transition: all 0.2s;
    }

    .date-control .btn-arrow:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .date-control .date-display {
        border-left: 1px solid #e2e8f0;
        border-right: 1px solid #e2e8f0;
        padding: 6px 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: #334155;
        font-weight: 500;
    }

    .filters-section {
        border-bottom: 1px solid #e2e8f0;
    }

    .filter-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }

    .form-control-custom,
    .form-select-custom {
        font-size: 0.875rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-shadow: none;
    }

    .form-control-custom:focus,
    .form-select-custom:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }

    .icon-input-wrapper {
        position: relative;
    }

    .icon-input-wrapper i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .icon-input-wrapper input {
        padding-left: 36px;
    }

    .stat-card {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        background: #ffffff;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .stat-title {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
        margin: 0;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .empty-state {
        border: 1px dashed #cbd5e1;
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
        background: #fafbfc;
    }

    .empty-state-icon {
        width: 72px;
        height: 72px;
        background: #e2e8f0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px auto;
        font-size: 28px;
        color: #64748b;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .empty-state-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 8px;
    }

    .empty-state-desc {
        font-size: 0.875rem;
        color: #64748b;
        max-width: 400px;
        margin: 0 auto;
    }

    /* Estilos Selectable Cards Modal */
    .scard-radio {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .scard-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 16px 8px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
        color: #475569;
        font-size: 0.8rem;
        height: 100%;
        text-align: center;
    }

    .scard-label i {
        font-size: 1.25rem;
        color: #64748b;
    }

    .scard-label:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }

    .scard-radio:checked+.scard-label {
        border-color: #1e293b;
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 600;
        box-shadow: 0 0 0 1px #1e293b;
    }

    .scard-radio:checked+.scard-label i {
        color: #1e293b;
    }

    /* Estilos QR Premium */
    .filter-pill {
        border: none;
        font-size: 0.85rem;
        background: #ffffff;
        color: #475569;
        font-weight: 500;
        transition: all 0.2s;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        padding-right: 28px;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%2364748b' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right .75rem center;
        background-size: 16px 12px;
    }

    .filter-pill-wrap {
        border-radius: 50px;
        border: 1px solid #e2e8f0;
        padding: 4px 12px;
        background: white;
        display: inline-flex;
        align-items: center;
        transition: border-color 0.2s;
        gap: 4px;
    }

    .filter-pill-wrap:hover {
        border-color: #94a3b8;
    }

    .filter-pill:focus {
        outline: none;
    }

    .filter-pill-label {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        margin-right: 2px;
    }

    .table-qr-row>td {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .table-qr-row:hover>td {
        background-color: #f1f5f9 !important;
    }

    .table-access-row>td {
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .table-access-row:hover>td {
        background-color: #f8fafc !important;
    }

    .table-access-row:focus-visible {
        outline: 2px solid #3b82f6;
        outline-offset: -2px;
    }

    .access-detail-dialog {
        max-width: 980px;
    }

    #accessDetailModal .modal-dialog {
        opacity: 0;
        transform: translateY(14px) scale(0.96);
        transition: transform 0.28s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.28s ease;
    }

    #accessDetailModal.show .modal-dialog {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .access-detail-backdrop.modal-backdrop.show {
        background: rgba(15, 23, 42, 0.28);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }

    .access-detail-modal-content {
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
        display: flex;
        flex-direction: column;
        max-height: min(90vh, 860px);
    }

    .access-detail-header {
        padding: 20px 24px 14px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdff 100%);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }

    .access-detail-badge {
        display: inline-flex;
        align-items: center;
        border-radius: 999px;
        padding: 4px 10px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.02em;
    }

    .access-detail-badge.adentro {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .access-detail-badge.salio {
        background: #f1f5f9;
        color: #334155;
        border: 1px solid #e2e8f0;
    }

    .access-detail-scroll {
        overflow-y: auto;
        padding: 18px 24px 8px;
    }

    .access-detail-scroll::-webkit-scrollbar {
        width: 10px;
    }

    .access-detail-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 20px;
        border: 2px solid #f8fafc;
    }

    .access-detail-scroll::-webkit-scrollbar-track {
        background: #f8fafc;
    }

    .access-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .access-detail-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 14px 16px;
        background: #ffffff;
    }

    .access-detail-card .label {
        font-size: 0.72rem;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 6px;
        font-weight: 700;
    }

    .access-detail-card .value {
        color: #0f172a;
        font-size: 0.92rem;
        font-weight: 600;
        word-break: break-word;
    }

    .access-photos-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
        margin-top: 14px;
    }

    .access-photo-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 12px;
        background: #ffffff;
    }

    .access-photo-card img {
        width: 100%;
        height: 240px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }

    .access-photo-title {
        font-size: 0.8rem;
        color: #334155;
        font-weight: 700;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .access-photo-link {
        font-size: 0.72rem;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
        border: 1px solid #dbeafe;
        background: #eff6ff;
        border-radius: 999px;
        padding: 3px 9px;
    }

    .access-photo-link:hover {
        color: #1d4ed8;
        border-color: #bfdbfe;
        background: #dbeafe;
    }

    .access-no-photo {
        border: 1px dashed #cbd5e1;
        border-radius: 14px;
        background: #f8fafc;
        color: #64748b;
        font-size: 0.82rem;
        padding: 18px 14px;
        text-align: center;
        margin-top: 14px;
    }

    .access-detail-sticky {
        position: sticky;
        bottom: 0;
        z-index: 2;
        border-top: 1px solid #e2e8f0;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.88) 0%, #ffffff 34%);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        padding: 14px 24px calc(14px + env(safe-area-inset-bottom, 0px));
    }

    .access-primary-btn {
        width: 100%;
        border: none;
        border-radius: 12px;
        padding: 12px 16px;
        background: linear-gradient(135deg, #1f2937, #111827);
        color: #ffffff;
        font-size: 0.92rem;
        font-weight: 600;
        letter-spacing: 0.01em;
    }

    .access-primary-btn:hover {
        background: linear-gradient(135deg, #111827, #0f172a);
    }

    @media (max-width: 991.98px) {
        .access-detail-dialog {
            max-width: calc(100vw - 20px);
            margin: 10px auto;
        }

        .access-detail-grid,
        .access-photos-grid {
            grid-template-columns: 1fr;
        }

        .access-photo-card img {
            height: 210px;
        }
    }

    .qr-badge-active {
        background-color: #10b981;
        color: #ffffff;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.70rem;
        border: none;
        display: inline-block;
    }

    .qr-badge-expired {
        background-color: #ef4444;
        color: #ffffff;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.70rem;
        border: none;
        display: inline-block;
    }

    .qr-badge-used {
        background-color: #64748b;
        color: #ffffff;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.70rem;
        border: none;
        display: inline-block;
    }

    .qr-badge-revoked {
        background-color: #dc2626;
        color: #ffffff;
        font-weight: 600;
        padding: 4px 14px;
        border-radius: 50px;
        font-size: 0.70rem;
        border: none;
        display: inline-block;
    }

    .qr-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e2e8f0;
        color: #64748b;
        font-size: 1rem;
        border: none;
    }

    .th-sortable {
        color: #64748b !important;
        font-weight: 500 !important;
        font-size: 0.75rem !important;
        text-transform: none !important;
        cursor: pointer;
        transition: color 0.2s;
        white-space: nowrap;
    }

    .th-sortable:hover {
        color: #1e293b !important;
    }

    .th-sortable i {
        font-size: 0.65rem;
        color: #cbd5e1;
        transition: color 0.2s;
        vertical-align: middle;
        margin-left: 4px;
    }

    .th-sortable:hover i {
        color: #64748b;
    }

    .th-sortable.active {
        color: #1e293b !important;
        font-weight: 600 !important;
    }

    .th-sortable.active i {
        color: #3b82f6;
    }

    /* Custom Modal Detail Drawer Style */
    .qr-detail-modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
    }

    .qr-img-box {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px;
        display: inline-block;
        background: white;
        margin-bottom: 0;
    }

    .qr-img-box img {
        max-width: 140px;
    }

    .qr-section-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: none;
        margin-bottom: 12px;
    }

    .btn-download-qr {
        border: 1px solid #cbd5e1;
        color: #334155;
        background: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 10px 24px;
        transition: all 0.2s;
        font-size: 0.85rem;
    }

    .btn-download-qr:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .device-cred-dialog {
        max-width: 860px;
    }

    .device-cred-modal-content {
        border: 1px solid #dbe1ea;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.22);
    }

    .device-cred-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        padding: 18px 20px 14px;
        border-bottom: 1px solid #e2e8f0;
        background: #ffffff;
    }

    .device-cred-title-wrap {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .device-cred-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #dbeafe;
        color: #2563eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .device-cred-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .device-cred-subtitle {
        font-size: 0.86rem;
        color: #64748b;
    }

    .device-cred-body {
        max-height: min(78vh, 760px);
        overflow-y: auto;
        padding: 10px 20px 18px;
    }

    .device-cred-body::-webkit-scrollbar {
        width: 8px;
    }

    .device-cred-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 20px;
    }

    .device-cred-body::-webkit-scrollbar-track {
        background: #f8fafc;
    }

    .device-cred-section {
        padding: 14px 0;
        border-bottom: 1px solid #e2e8f0;
    }

    .device-cred-section:last-child {
        border-bottom: none;
        padding-bottom: 4px;
    }

    .device-cred-heading {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 1.02rem;
        color: #0f172a;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .device-cred-heading i {
        color: #2563eb;
        font-size: 1rem;
    }

    .device-cred-text {
        font-size: 0.94rem;
        color: #334155;
        line-height: 1.55;
        margin: 0;
    }

    .device-cred-capabilities {
        margin-top: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 18px;
    }

    .device-cred-capabilities span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.86rem;
        color: #0f172a;
        font-weight: 500;
    }

    .device-cred-capabilities i {
        color: #334155;
    }

    .device-cred-steps {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 10px;
    }

    .device-cred-step {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: #334155;
        font-size: 0.94rem;
        line-height: 1.45;
    }

    .device-cred-step-num {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #2563eb;
        color: #ffffff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.74rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .device-cred-info-box {
        margin-top: 8px;
        border: 1px solid #93c5fd;
        background: #eff6ff;
        border-radius: 8px;
        padding: 12px 14px;
    }

    .device-cred-info-line {
        display: flex;
        gap: 8px;
        color: #1e3a8a;
        font-size: 0.92rem;
        line-height: 1.5;
    }

    .device-cred-info-line i {
        color: #2563eb;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .device-cred-practices {
        margin: 0;
        padding: 0;
        list-style: none;
        display: grid;
        gap: 8px;
    }

    .device-cred-practices li {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        color: #334155;
        font-size: 0.94rem;
        line-height: 1.45;
    }

    .device-cred-practices i {
        color: #16a34a;
        margin-top: 2px;
    }

    .device-cred-warning {
        margin-top: 2px;
        border: 1px solid #facc15;
        background: #fffbeb;
        border-radius: 8px;
        padding: 12px 14px;
        color: #92400e;
        font-size: 0.92rem;
        line-height: 1.5;
        display: flex;
        align-items: flex-start;
        gap: 8px;
    }

    .device-cred-warning i {
        color: #d97706;
        margin-top: 2px;
        flex-shrink: 0;
    }

    @media (max-width: 767.98px) {
        .device-cred-dialog {
            max-width: calc(100vw - 18px);
            margin: 10px auto;
        }

        .device-cred-body {
            padding: 8px 14px 14px;
        }

        .device-cred-header {
            padding: 14px;
        }
    }

    .device-mgmt-dialog {
        max-width: 460px;
    }

    .device-mgmt-dialog-wide {
        max-width: 520px;
    }

    .device-mgmt-content {
        border: 1px solid #d9e2ec;
        border-radius: 12px;
        box-shadow: 0 20px 45px rgba(15, 23, 42, 0.2);
    }

    .device-mgmt-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.08rem;
        font-weight: 700;
        color: #0f172a;
    }

    .device-mgmt-title i {
        color: #475569;
    }

    .device-mgmt-input {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 0.95rem;
        padding: 10px 12px;
        box-shadow: none;
    }

    .device-mgmt-input:focus {
        border-color: #334155;
        box-shadow: 0 0 0 3px rgba(71, 85, 105, 0.14);
    }

    .device-mgmt-card {
        border: 1px solid #dbe4ee;
        border-radius: 10px;
        background: #fbfdff;
        padding: 12px 14px;
    }

    .device-mgmt-label {
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 4px;
        font-weight: 600;
    }

    .device-mgmt-value {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #f8fafc;
        padding: 9px 10px;
        font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
        font-size: 0.9rem;
        color: #0f172a;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .device-mgmt-muted-note {
        font-size: 0.78rem;
        color: #64748b;
    }

    .device-mgmt-primary {
        background: #475569;
        border-color: #475569;
        color: #ffffff;
        font-weight: 600;
    }

    .device-mgmt-primary:hover {
        background: #334155;
        border-color: #334155;
        color: #ffffff;
    }

    .device-mgmt-primary:disabled {
        background: #94a3b8;
        border-color: #94a3b8;
        color: #f8fafc;
    }

    .device-mgmt-outline {
        border: 1px solid #cbd5e1;
        color: #334155;
        font-weight: 600;
    }

    .device-mgmt-outline:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        color: #0f172a;
    }

    .js-device-card:focus-visible {
        outline: 2px solid #334155;
        outline-offset: 1px;
    }

    /* DatePicker custom styles */
    .flatpickr-calendar {
        border-radius: 12px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1) !important;
        border: 1px solid #e2e8f0 !important;
        font-family: inherit !important;
    }

    .flatpickr-day.selected {
        background: #334155 !important;
        border-color: #334155 !important;
    }

    .flatpickr-day.inRange {
        background: rgba(51, 65, 85, 0.05) !important;
        border-color: transparent !important;
        box-shadow: none !important;
    }

    .flatpickr-day.startRange {
        background: #334155 !important;
        border-color: #334155 !important;
        color: white !important;
    }

    .flatpickr-day.endRange {
        background: #334155 !important;
        border-color: #334155 !important;
        color: white !important;
    }

    /* Toast Notification */
    .qr-toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }

    .qr-toast {
        pointer-events: auto;
        min-width: 380px;
        max-width: 460px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 12px 40px rgba(15, 23, 42, 0.18), 0 2px 8px rgba(15, 23, 42, 0.08);
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 18px;
        transform: translateX(120%);
        opacity: 0;
        transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.4s ease;
        position: relative;
        overflow: hidden;
    }

    .qr-toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .qr-toast.hiding {
        transform: translateX(120%);
        opacity: 0;
    }

    .qr-toast-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        flex-shrink: 0;
    }

    .qr-toast-icon.success {
        background: #dcfce7;
        color: #16a34a;
    }

    .qr-toast-icon.error {
        background: #fee2e2;
        color: #dc2626;
    }

    .qr-toast-body {
        flex: 1;
        min-width: 0;
    }

    .qr-toast-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 2px;
    }

    .qr-toast-message {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.4;
    }

    .qr-toast-close {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1.1rem;
        cursor: pointer;
        padding: 0;
        line-height: 1;
        transition: color 0.2s;
        flex-shrink: 0;
    }

    .qr-toast-close:hover {
        color: #334155;
    }

    .qr-toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        border-radius: 0 0 12px 12px;
        transition: width linear;
    }

    .qr-toast-progress.success {
        background: linear-gradient(90deg, #22c55e, #16a34a);
    }

    .qr-toast-progress.error {
        background: linear-gradient(90deg, #f87171, #dc2626);
    }

    .qr-toast-actions {
        display: flex;
        gap: 8px;
        margin-top: 8px;
    }

    .qr-toast-actions .btn-toast {
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 6px;
        cursor: pointer;
        border: none;
        transition: background 0.2s;
    }

    .qr-toast-actions .btn-toast-primary {
        background: #1e293b;
        color: #fff;
    }

    .qr-toast-actions .btn-toast-primary:hover {
        background: #0f172a;
    }
</style>



<!-- ── Hero ── -->
<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">Seguridad</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-shield-check"></i>
            <i class="bi bi-chevron-right"></i>
            Control de acceso y gestión de seguridad
        </div>
    </div>
    <div id="header-action-area">
        <button id="header-btn-v-qr" type="button" data-open-qr-modal="1"
            class="btn border-white border-opacity-25 text-white shadow-sm fw-medium px-4 header-dynamic-control d-none"
            style="background: #238B71; font-size: 0.85rem;"><i class="bi bi-plus fs-6 me-1"></i> Generar
            Código QR</button>
        <button id="header-btn-v-staff"
            class="btn border-white border-opacity-25 text-white shadow-sm fw-medium px-4 header-dynamic-control d-none"
            style="background: #238B71; font-size: 0.85rem;"><i class="bi bi-plus fs-6 me-1"></i> Agregar
            Personal</button>
        <button id="header-btn-v-dispositivos"
            class="btn border-white border-opacity-25 text-white shadow-sm fw-medium px-4 header-dynamic-control d-none"
            style="background: #238B71; font-size: 0.85rem;"><i class="bi bi-info-circle fs-6 me-1"></i>
            Más Info</button>
    </div>
</div>
<!-- ── END Hero ── -->



<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">

        <!-- Controles Superiores -->
        <?php
        $mesesES = ['January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'];
        $todayDateStr = date('d') . ' de ' . strtolower($mesesES[date('F')]) . ' de ' . date('Y');
        $axisBrandView = $axisBrand ?? 'AxisCondo';
        $axisDomainView = $axisDomain ?? 'axiscondo.mx';
        $securityDevicesList = $securityDevices ?? [];
        $deviceStatsView = $deviceStats ?? [
            'total' => count($securityDevicesList),
            'active' => count(array_filter($securityDevicesList, static fn($d) => ($d['status'] ?? 'active') === 'active'))
        ];
        $staffMembersList = $staffMembers ?? [];
        ?>
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
            <!-- Tabs -->
            <ul class="nav nav-pills nav-pills-custom mb-0" id="securityTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active d-flex align-items-center gap-2" id="v-entradas-tab"
                        data-bs-toggle="pill" data-bs-target="#v-entradas" type="button" role="tab"
                        aria-selected="true">
                        <i class="bi bi-shield-check"></i> Entradas/Salidas
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2" id="v-qr-tab" data-bs-toggle="pill"
                        data-bs-target="#v-qr" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-qr-code"></i> Códigos QR
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2" id="v-staff-tab" data-bs-toggle="pill"
                        data-bs-target="#v-staff" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-people"></i> Staff
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2" id="v-dispositivos-tab"
                        data-bs-toggle="pill" data-bs-target="#v-dispositivos" type="button" role="tab"
                        aria-selected="false">
                        <i class="bi bi-phone"></i> Dispositivos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link d-flex align-items-center gap-2" id="v-alertas-tab" data-bs-toggle="pill"
                        data-bs-target="#v-alertas" type="button" role="tab" aria-selected="false">
                        <i class="bi bi-bell"></i> Alertas
                    </button>
                </li>
            </ul>

            <!-- Controles dinámicos de la derecha por pestaña -->
            <?php
            function getFormattedDateEsp($dStr, $mesesES)
            {
                if (!$dStr)
                    return '';
                $ts = strtotime($dStr);
                return date('d', $ts) . ' ' . substr($mesesES[date('F', $ts)], 0, 3) . ' ' . date('Y', $ts);
            }
            $startDisplay = isset($startDate) ? $startDate : date('Y-m-d');
            $endDisplay = isset($endDate) ? $endDate : date('Y-m-d');
            $accDateStr = ($startDisplay === $endDisplay) ? getFormattedDateEsp($startDisplay, $mesesES) : getFormattedDateEsp($startDisplay, $mesesES) . ' a ' . getFormattedDateEsp($endDisplay, $mesesES);

            $qrStartDisplay = isset($qrStartDate) ? $qrStartDate : date('Y-m-d');
            $qrEndDisplay = isset($qrEndDate) ? $qrEndDate : date('Y-m-d');
            $qrDateStr = ($qrStartDisplay === $qrEndDisplay) ? getFormattedDateEsp($qrStartDisplay, $mesesES) : getFormattedDateEsp($qrStartDisplay, $mesesES) . ' a ' . getFormattedDateEsp($qrEndDisplay, $mesesES);
            ?>
            <div id="tab-controls-right" class="d-flex align-items-center gap-2">

                <!-- Entradas Controls -->
                <div class="date-control shadow-sm position-relative tab-dynamic-control" id="ctrl-v-entradas">
                    <button class="btn-arrow" title="Día Anterior" id="btn-acc-prev-day"><i
                            class="bi bi-chevron-left"></i></button>
                    <div class="date-display text-nowrap" style="cursor: pointer;" id="acc-date-wrapper">
                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                        <span id="acc-date-text" class="fw-medium text-dark user-select-none"><?= $accDateStr ?></span>
                        <input type="text" id="acc-date-picker"
                            style="position:absolute; opacity:0; width:0; height:0; border:0; padding:0; outline:none; pointer-events:none;">
                    </div>
                    <button class="btn-arrow" title="Día Siguiente" id="btn-acc-next-day"><i
                            class="bi bi-chevron-right"></i></button>
                </div>

                <!-- QR Controls -->
                <div class="date-control shadow-sm position-relative tab-dynamic-control d-none" id="ctrl-v-qr">
                    <button class="btn-arrow" title="Día Anterior" id="btn-qr-prev-day"><i
                            class="bi bi-chevron-left"></i></button>
                    <div class="date-display text-nowrap" style="cursor: pointer;" id="qr-date-wrapper">
                        <i class="bi bi-calendar3 me-1 text-primary"></i>
                        <span id="qr-date-text" class="fw-medium text-dark user-select-none"><?= $qrDateStr ?></span>
                        <input type="text" id="qr-date-picker"
                            style="position:absolute; opacity:0; width:0; height:0; border:0; padding:0; outline:none; pointer-events:none;">
                    </div>
                    <button class="btn-arrow" title="Día Siguiente" id="btn-qr-next-day"><i
                            class="bi bi-chevron-right"></i></button>
                </div>

                <!-- Staff Controls -->
                <button class="btn tab-dynamic-control d-none" id="ctrl-v-staff"
                    style="background: #238B71; color: white; font-weight: 500; border-radius: 6px; padding: 6px 16px; font-size: 0.85rem;"><i
                        class="bi bi-plus border-white border"></i> Agregar Personal</button>

                <!-- Dispositivos Controls -->
                <button class="btn tab-dynamic-control d-none" id="ctrl-v-dispositivos"
                    style="background: #238B71; color: white; font-weight: 500; border-radius: 6px; padding: 6px 16px; font-size: 0.85rem;"><i
                        class="bi bi-plus border-white border"></i> Agregar Dispositivo</button>

                <!-- Alertas Controls -->
                <div class="d-flex gap-2 tab-dynamic-control d-none" id="ctrl-v-alertas">
                    <button class="btn border fw-bold text-dark px-3" style="font-size:0.85rem; border-radius: 8px;"><i
                            class="bi bi-plus me-1"></i> Nueva Alerta</button>
                    <button class="btn fw-bold text-white px-3"
                        style="background-color: #8b5cf6; font-size:0.85rem; border-radius: 8px;"><i
                            class="bi bi-stars me-1"></i> Crear con IA</button>
                </div>

            </div>
        </div>

        <div class="tab-content" id="securityTabsContent">

            <!-- ===================== PESTAÑA: ENTRADAS / SALIDAS ===================== -->
            <div class="tab-pane fade show active" id="v-entradas" role="tabpanel" aria-labelledby="v-entradas-tab">
                <div class="filters-section pb-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-funnel text-muted"></i> Filtros y Busqueda
                        </h6>
                        <button class="btn btn-sm btn-light border-0 text-muted"><i
                                class="bi bi-chevron-up"></i></button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="filter-label">Buscar</label>
                            <div class="icon-input-wrapper">
                                <i class="bi bi-search"></i>
                                <input type="text" id="acc-search-input" class="form-control form-control-custom"
                                    placeholder="Buscar por placa, visitante, unidad...">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="filter-label">Propósito</label>
                            <select id="acc-filter-purpose" class="form-select form-select-custom">
                                <option value="Todos">Todos</option>
                                <option value="Visita">Visita</option>
                                <option value="Residente">Residente</option>
                                <option value="Proveedor de servicios">Proveedor / Servicio</option>
                                <option value="Entrega de comida">Entrega de Comida</option>
                                <option value="Entrega de paquetería">Paquetería</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="filter-label">Estado</label>
                            <select id="acc-filter-status" class="form-select form-select-custom">
                                <option value="Todos">Todos</option>
                                <option value="adentro">Actualmente Adentro</option>
                                <option value="salio">Salió</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="filter-label">Vehículo</label>
                            <select id="acc-filter-vehicle" class="form-select form-select-custom">
                                <option value="Todos">Todos</option>
                                <option value="Auto">Auto</option>
                                <option value="Motocicleta">Motocicleta</option>
                                <option value="Bicicleta">Bicicleta</option>
                                <option value="Sin vehículo">Sin Vehículo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6 col-lg-3">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">Total de Entradas Hoy</div>
                                <h3 class="stat-value"><?= $stats['total_entradas'] ?? '0' ?></h3>
                            </div>
                            <div class="stat-icon bg-primary bg-opacity-10 text-primary"><i
                                    class="bi bi-calendar-check"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">Actualmente Adentro</div>
                                <h3 class="stat-value"><?= $stats['adentro'] ?? '0' ?></h3>
                            </div>
                            <div class="stat-icon bg-success bg-opacity-10 text-success"><i
                                    class="bi bi-box-arrow-in-right"></i></div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">Con Vehículos</div>
                                <h3 class="stat-value"><?= $stats['con_vehiculo'] ?? '0' ?></h3>
                            </div>
                            <div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-car-front"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-lg-3">
                        <div class="stat-card">
                            <div>
                                <div class="stat-title">Total de Registros</div>
                                <h3 class="stat-value"><?= $stats['total_registros'] ?? '0' ?></h3>
                            </div>
                            <div class="stat-icon" style="background-color: #e0e7ff; color: #4f46e5;"><i
                                    class="bi bi-card-checklist"></i></div>
                        </div>
                    </div>
                </div>

                <?php if (empty($accessLogs)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-clipboard-check"></i></div>
                        <h4 class="empty-state-title">No se encontraron registros de acceso.</h4>
                        <p class="empty-state-desc">Los registros de acceso aparecerán aquí una vez que los visitantes
                            ingresen al predio.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive border rounded-3 mt-4">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                            <thead class="table-light text-secondary">
                                <tr>
                                    <th class="ps-4 border-0 py-3">Visitante <i class="bi bi-arrow-up-down ms-1"
                                            style="font-size:0.7rem;"></i></th>
                                    <th class="border-0 py-3">Propósito <i class="bi bi-arrow-up-down ms-1"
                                            style="font-size:0.7rem;"></i></th>
                                    <th class="border-0 py-3">Fecha <i class="bi bi-arrow-down ms-1"
                                            style="font-size:0.7rem;"></i></th>
                                    <th class="border-0 py-3">Hora</th>
                                    <th class="border-0 py-3">Estado <i class="bi bi-arrow-up-down ms-1"
                                            style="font-size:0.7rem;"></i></th>
                                    <th class="border-0 py-3">Vehículo</th>
                                    <th class="border-0 py-3">Placa</th>
                                    <th class="border-0 py-3 rounded-end">Unidad <i class="bi bi-arrow-up-down ms-1"
                                            style="font-size:0.7rem;"></i></th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php foreach ($accessLogs as $al): ?>
                                    <?php
                                    $initial = strtoupper(substr($al['visitor_name'], 0, 1));
                                    $isInside = empty($al['exit_time']);
                                    $entryTime = date('H:i', strtotime($al['created_at']));
                                    $exitTime = $isInside ? 'Active' : date('H:i', strtotime($al['exit_time']));
                                    $isToday = date('Y-m-d', strtotime($al['created_at'])) === date('Y-m-d');

                                    // Colores de Avatar basados en inicial o nombre
                                    $colors = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4'];
                                    $avatarColor = $colors[ord($initial) % count($colors)];
                                    ?>
                                    <tr class="table-access-row" role="button" tabindex="0"
                                        data-entry-id="<?= (int) $al['id'] ?>" data-visitor="<?= esc($al['visitor_name']) ?>"
                                        data-unit="<?= esc($al['unit_number'] ?? 'N/A') ?>"
                                        data-plate="<?= esc($al['plate_number'] ?? '-') ?>"
                                        data-entry-date="<?= $isToday ? 'Hoy' : date('d M Y', strtotime($al['created_at'])) ?>"
                                        data-entry-time="<?= esc($entryTime) ?>" data-exit-time="<?= esc($exitTime) ?>"
                                        data-gate="<?= esc($al['gate_number'] ?? 'Caseta Principal') ?>"
                                        data-notes="<?= esc($al['notes'] ?? '') ?>"
                                        data-photo-id="<?= esc($al['photo_url'] ?? '') ?>"
                                        data-photo-plate="<?= esc($al['photo_plate_url'] ?? '') ?>"
                                        data-photo-exit="<?= esc($al['exit_photo_url'] ?? '') ?>"
                                        data-search="<?= strtolower(esc($al['visitor_name']) . ' ' . esc($al['unit_number'] ?? 'N/A') . ' ' . esc($al['plate_number'] ?? '')) ?>"
                                        data-purpose="<?= esc($al['visit_type'] ?? 'Visita') ?>"
                                        data-status="<?= $isInside ? 'adentro' : 'salio' ?>"
                                        data-vehicle="<?= esc($al['vehicle_type'] ?? 'Sin vehículo') ?>">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-sm"
                                                    style="width: 32px; height: 32px; background: <?= $avatarColor ?>15; color: <?= $avatarColor ?>; font-size: 0.8rem; border: 1px solid <?= $avatarColor ?>30;">
                                                    <?= $initial ?>
                                                </div>
                                                <div class="fw-semibold text-dark"><?= esc($al['visitor_name']) ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge rounded-pill bg-light text-secondary border px-2 py-1 fw-medium"
                                                style="font-size: 0.75rem;">
                                                <i class="bi bi-person me-1"></i> <?= esc($al['visit_type'] ?? 'Visita') ?>
                                            </span>
                                        </td>
                                        <td class="text-secondary">
                                            <?= $isToday ? 'Hoy' : date('d M Y', strtotime($al['created_at'])) ?>
                                        </td>
                                        <td class="text-dark fw-medium">
                                            <?= $entryTime ?> - <?= $exitTime ?>
                                        </td>
                                        <td>
                                            <?php if ($isInside): ?>
                                                <span class="badge bg-success border-0 px-2 py-1 rounded-pill"
                                                    style="font-size: 0.7rem; background: #10b981 !important;">
                                                    <i class="bi bi-arrow-right-short me-1"></i> Adentro
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-secondary border px-2 py-1 rounded-pill"
                                                    style="font-size: 0.7rem;">
                                                    <i class="bi bi-box-arrow-right me-1"></i> Salió
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1 text-secondary">
                                                <?php if (($al['visitor_type'] ?? 'pedestrian') == 'vehicle'): ?>
                                                    <i class="bi bi-car-front text-dark"></i> Auto
                                                <?php else: ?>
                                                    Ninguno
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="text-secondary">
                                            <?= !empty($al['plate_number']) ? esc($al['plate_number']) : '-' ?>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-dark"><?= esc($al['unit_number'] ?? 'N/A') ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Footer / Paginación -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-secondary small">Resultados por página:</span>
                            <select class="form-select form-select-sm border-0 bg-light rounded-3 px-3 fw-medium"
                                style="width: auto; cursor: pointer;">
                                <option>25</option>
                                <option>50</option>
                                <option>100</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span
                                class="text-secondary small"><?= count($accessLogs) > 0 ? '1-' . count($accessLogs) : '0' ?>
                                de <?= count($accessLogs) ?></span>
                            <div class="btn-group shadow-sm bg-white rounded-3 border">
                                <button class="btn btn-sm btn-light border-0 px-2 text-secondary"><i
                                        class="bi bi-chevron-double-left"></i></button>
                                <button class="btn btn-sm btn-light border-0 px-2 text-secondary"><i
                                        class="bi bi-chevron-left"></i></button>
                                <button class="btn btn-sm btn-light border-0 px-3 text-dark fw-bold" disabled>1</button>
                                <button class="btn btn-sm btn-light border-0 px-2 text-secondary"><i
                                        class="bi bi-chevron-right"></i></button>
                                <button class="btn btn-sm btn-light border-0 px-2 text-secondary"><i
                                        class="bi bi-chevron-double-right"></i></button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ===================== PESTAÑA: CODIGOS QR ===================== -->
            <div class="tab-pane fade" id="v-qr" role="tabpanel" aria-labelledby="v-qr-tab">

                <!-- Barra de Filtros y Busqueda
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <div class="icon-input-wrapper shadow-sm border"
                            style="border-radius: 50px; background: white;">
                            <i class="bi bi-search" style="left: 16px; color: #94a3b8;"></i>
                            <input type="text" id="qr-search-input"
                                class="form-control border-0 bg-transparent shadow-none"
                                placeholder="Buscar códigos QR..."
                                style="padding-left: 42px; border-radius: 50px; width: 250px; font-size: 0.85rem;"
                                autocomplete="off">
                        </div>

                        <div class="filter-pill-wrap shadow-sm">
                            <span class="filter-pill-label">TIPO</span>
                            <select class="filter-pill" id="qr-filter-type">
                                <option value="Todos">Todos</option>
                                <option value="Una entrada">Una entrada</option>
                                <option value="QR temporal">Temporal</option>
                            </select>
                        </div>

                        <div class="filter-pill-wrap shadow-sm">
                            <span class="filter-pill-label">PROPÓSITO</span>
                            <select class="filter-pill" id="qr-filter-purpose">
                                <option value="Todos">Todos</option>
                                <option value="Familia">Familia</option>
                                <option value="Amigo">Amigo</option>
                                <option value="Entrega a domicilio">Entrega / Delivery</option>
                                <option value="Proveedor de servicios">Servicios</option>
                                <option value="Fiesta">Fiesta / Evento</option>
                                <option value="Empleado">Empleado</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="filter-pill-wrap shadow-sm">
                            <span class="filter-pill-label">VEHÍCULO</span>
                            <select class="filter-pill" id="qr-filter-vehicle">
                                <option value="Todos">Todos</option>
                                <option value="Sin vehículo">Sin vehículo</option>
                                <option value="Auto">Auto</option>
                                <option value="Motocicleta">Motocicleta</option>
                            </select>
                        </div>

                        <div class="filter-pill-wrap shadow-sm">
                            <span class="filter-pill-label">ESTADO</span>
                            <select class="filter-pill" id="qr-filter-status">
                                <option value="Todos">Todos</option>
                                <option value="Activos">Activos</option>
                                <option value="Expirados">Expirados</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Listado QR -->
                <?php if (!empty($qrCodes)): ?>
                    <div class="table-responsive bg-white rounded-4 border shadow-sm">
                        <table class="table align-middle mb-0" id="qrDataTable">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 border-0 py-3 th-sortable active" data-sort="visitante"
                                        style="width: 25%;">Visitante <i class="bi bi-arrow-down-up"></i></th>
                                    <th class="border-0 py-3 th-sortable" data-sort="tipo_pase" style="width: 15%;">Tipo de
                                        Pase <i class="bi bi-arrow-down-up"></i></th>
                                    <th class="border-0 py-3 th-sortable" data-sort="proposito" style="width: 15%;">
                                        Propósito <i class="bi bi-arrow-down-up"></i></th>
                                    <th class="border-0 py-3 th-sortable" data-sort="vehiculo" style="width: 15%;">Vehículo
                                    </th>
                                    <th class="border-0 py-3 th-sortable" data-sort="valido_desde">Válido Desde <i
                                            class="bi bi-arrow-down"></i></th>
                                    <th class="border-0 py-3 th-sortable" data-sort="valido_hasta">Válido Hasta <i
                                            class="bi bi-arrow-down-up"></i></th>
                                    <th class="border-0 py-3 th-sortable">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="border-top-0">
                                <?php foreach ($qrCodes as $qr): ?>
                                    <?php
                                    // Calcular estado visual
                                    $now = new DateTime();
                                    $validUntil = new DateTime($qr['valid_until']);
                                    $isExpired = $validUntil < $now;

                                    // Determinar estado visual basado en el status real del QR
                                    $qrStatus = $qr['status'] ?? 'active';
                                    if ($qrStatus === 'used') {
                                        $statusClass = 'qr-badge-used';
                                        $statusLabel = 'Usado';
                                    } elseif ($qrStatus === 'revoked') {
                                        $statusClass = 'qr-badge-revoked';
                                        $statusLabel = 'Revocado';
                                    } elseif ($isExpired) {
                                        $statusClass = 'qr-badge-expired';
                                        $statusLabel = 'Expirado';
                                    } else {
                                        $statusClass = 'qr-badge-active';
                                        $statusLabel = 'Activo';
                                    }

                                    // Formateo de fechas para la tabla - Quitar "de" y usar mayúsculas
                                    setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'Spanish');
                                    $df = new DateTime($qr['valid_from']);
                                    $dateStrFrom = $df->format('d M Y');
                                    $timeStrFrom = $df->format('H:i');

                                    $dateStrTo = $validUntil->format('d M Y');
                                    $timeStrTo = $validUntil->format('H:i');

                                    // Tipos y propósitos mezclados en layout
                                    $tipoDuracion = $qr['usage_limit'] == 1 ? 'Una entrada' : 'QR temporal';

                                    // Datos ocultos para JS modal
                                    $jsonData = htmlspecialchars(json_encode([
                                        'visitor_name' => $qr['visitor_name'],
                                        'unit_number' => $qr['unit_number'] ?: '-',
                                        'visit_type' => $qr['visit_type'] ?: 'Visita',
                                        'vehicle_type' => $qr['vehicle_type'] ?: 'Sin vehículo',
                                        'time_type' => $tipoDuracion,
                                        'valid_from' => $dateStrFrom . ' ' . $timeStrFrom,
                                        'valid_until' => $dateStrTo . ' ' . $timeStrTo,
                                        'status' => $statusLabel,
                                        'token' => $qr['token']
                                    ]), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <tr class="table-qr-row border-bottom" style="border-color: #f1f5f9;"
                                        onclick="openQrDetail(this)" data-json="<?= $jsonData ?>"
                                        data-search="<?= strtolower($qr['visitor_name'] . ' ' . $qr['unit_number']) ?>"
                                        data-type="<?= $tipoDuracion ?>" data-purpose="<?= $qr['visit_type'] ?>"
                                        data-vehicle="<?= $qr['vehicle_type'] ?>" data-status="<?= $statusLabel ?>">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="qr-avatar"><i class="bi bi-person text-secondary"></i></div>
                                                <div class="fw-medium text-dark"
                                                    style="font-size: 0.85rem; letter-spacing: -0.2px;">
                                                    <?= esc($qr['visitor_name']) ?>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-secondary" style="font-size: 0.82rem;">
                                            <?= $tipoDuracion ?>
                                        </td>
                                        <td class="text-secondary" style="font-size: 0.82rem;">
                                            <?= esc($qr['visit_type']) ?>
                                        </td>
                                        <td class="text-secondary" style="font-size: 0.82rem;">
                                            <?= esc($qr['vehicle_type'] === 'Sin vehículo' ? 'Sin vehículo' : 'Auto') ?>
                                        </td>
                                        <td>
                                            <div class="text-dark" style="font-size: 0.82rem; margin-bottom: 2px;">
                                                <?= $dateStrFrom ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.70rem;"><?= $timeStrFrom ?></div>
                                        </td>
                                        <td>
                                            <div class="text-dark" style="font-size: 0.82rem; margin-bottom: 2px;">
                                                <?= $dateStrTo ?>
                                            </div>
                                            <div class="text-muted" style="font-size: 0.70rem;"><?= $timeStrTo ?></div>
                                        </td>
                                        <td>
                                            <span class="<?= $statusClass ?>"><?= $statusLabel ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <!-- Paginación Premium Inferior -->
                        <div
                            class="d-flex justify-content-between align-items-center p-3 border-top bg-light rounded-bottom-4">
                            <div class="d-flex align-items-center gap-2">
                                <span class="text-secondary" style="font-size:0.8rem;">Resultados por página:</span>
                                <select class="form-select form-select-sm border-0 bg-white shadow-sm rounded-3 fw-medium"
                                    style="width: auto; cursor: pointer;">
                                    <option>20</option>
                                    <option>50</option>
                                </select>
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-secondary fw-medium" style="font-size:0.8rem;">1-<?= count($qrCodes) ?> de
                                    <?= count($qrCodes) ?></span>
                                <div class="btn-group shadow-sm bg-white rounded-3 border">
                                    <button class="btn btn-sm btn-light border-0 px-2 text-secondary"><i
                                            class="bi bi-chevron-double-left"></i></button>
                                    <button class="btn btn-sm btn-light border-0 px-2 text-secondary"><i
                                            class="bi bi-chevron-left"></i></button>
                                    <button class="btn btn-sm btn-light border-0 px-3 text-dark fw-bold" disabled>1</button>
                                    <button class="btn btn-sm btn-light border-0 px-2 text-secondary"><i
                                            class="bi bi-chevron-right"></i></button>
                                    <button class="btn btn-sm btn-light border-0 px-2 text-secondary"><i
                                            class="bi bi-chevron-double-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="empty-state text-center bg-white shadow-sm border rounded-4 border-light p-5"
                        id="qr-empty-state">
                        <div class="empty-state-icon"
                            style="background: #f8fafc; border: 1px solid #e2e8f0; width: 64px; height: 64px; font-size: 24px;">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h5 class="empty-state-title fs-5">No se encontraron códigos QR</h5>
                        <p class="empty-state-desc">Su búsqueda o filtros no produjeron resultados, o no hay QRs activos
                            generados.</p>
                        <button id="btn-open-qr-empty" type="button" data-open-qr-modal="1" class="btn btn-dark px-4 mt-3"
                            style="border-radius: 8px;"><i class="bi bi-plus me-1"></i>
                            Generar Qr</button>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ===================== PESTAÑA: STAFF ===================== -->
            <div class="tab-pane fade" id="v-staff" role="tabpanel" aria-labelledby="v-staff-tab">
                <div class="filters-section pb-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-funnel text-muted"></i> Filtros y Búsqueda
                        </h6>
                        <button class="btn btn-sm btn-light border-0 text-muted"><i
                                class="bi bi-chevron-up"></i></button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4">
                            <label class="filter-label">Buscar</label>
                            <div class="icon-input-wrapper">
                                <i class="bi bi-search"></i>
                                <input id="staff-search-input" type="text" class="form-control form-control-custom"
                                    placeholder="Buscar personal...">
                            </div>
                        </div>
                    </div>
                </div>

                <?php $staffCount = count($staffMembersList); ?>

                <div id="staff-empty-state" class="empty-state border-0 <?= $staffCount > 0 ? 'd-none' : '' ?>"
                    style="padding: 100px 20px;">
                    <div class="empty-state-icon text-muted"
                        style="background: #f8fafc; border-radius: 50%; width: 80px; height: 80px; font-size: 32px; border: 1px solid #e2e8f0; opacity: 0.8;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4 class="empty-state-title">Aún no hay personal</h4>
                    <p class="empty-state-desc" style="max-width:350px;">Agrega personal para llevar un registro de tu
                        personal de seguridad, mantenimiento y otros.</p>
                </div>

                <div class="row g-3 <?= $staffCount === 0 ? 'd-none' : '' ?>" id="staff-list-grid">
                    <?php foreach ($staffMembersList as $sm): ?>
                        <?php
                        $staffFullName = esc(trim(($sm['first_name'] ?? '') . ' ' . ($sm['last_name'] ?? '')));
                        $staffTypeLabel = match ($sm['staff_type'] ?? 'other') {
                            'security' => 'Seguridad',
                            'maintenance' => 'Mantenimiento',
                            default => 'Otro'
                        };
                        $staffTypeBg = match ($sm['staff_type'] ?? 'other') {
                            'security' => '#2a3547',
                            'maintenance' => '#0ea5e9',
                            default => '#64748b'
                        };
                        $photoSrc = !empty($sm['photo_url']) ? base_url($sm['photo_url']) : '';
                        $searchIdx = strtolower(trim(($sm['first_name'] ?? '') . ' ' . ($sm['last_name'] ?? '') . ' ' . $staffTypeLabel));
                        ?>
                        <div class="col-md-6 col-lg-4 staff-card-col" data-staff-search="<?= esc($searchIdx, 'attr') ?>">
                            <div class="card border shadow-sm rounded-4 text-center js-staff-card position-relative"
                                role="button" tabindex="0" data-staff-id="<?= esc((string) ($sm['id'] ?? 0), 'attr') ?>"
                                data-staff-first="<?= esc($sm['first_name'] ?? '', 'attr') ?>"
                                data-staff-last="<?= esc($sm['last_name'] ?? '', 'attr') ?>"
                                data-staff-type="<?= esc($sm['staff_type'] ?? 'other', 'attr') ?>"
                                data-staff-device="<?= esc((string) ($sm['device_id'] ?? ''), 'attr') ?>"
                                data-staff-device-email="<?= esc($sm['device_email'] ?? '', 'attr') ?>"
                                data-staff-photo="<?= esc($photoSrc, 'attr') ?>"
                                style="border-color: #e2e8f0 !important; padding: 28px 16px 20px;">

                                <div class="dropdown position-absolute" style="top: 12px; right: 12px;">
                                    <button type="button" class="btn btn-sm btn-link text-muted p-0"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border"
                                        style="min-width:170px; border-radius:10px; padding:6px;">
                                        <li><button type="button"
                                                class="dropdown-item d-flex align-items-center gap-2 rounded-2 text-danger js-staff-delete-btn"
                                                style="font-size:0.85rem; padding:8px 12px;"
                                                data-staff-id="<?= esc((string) ($sm['id'] ?? 0), 'attr') ?>"
                                                data-staff-name="<?= esc($staffFullName, 'attr') ?>">
                                                <i class="bi bi-trash3"></i> Eliminar
                                            </button></li>
                                    </ul>
                                </div>

                                <div class="mx-auto mb-3"
                                    style="width:80px; height:80px; border-radius:50%; overflow:hidden; background:#f1f5f9; border:3px solid #e2e8f0; display:flex; align-items:center; justify-content:center;">
                                    <?php if ($photoSrc): ?>
                                        <img src="<?= $photoSrc ?>" alt="<?= $staffFullName ?>"
                                            style="width:100%; height:100%; object-fit:cover;">
                                    <?php else: ?>
                                        <i class="bi bi-person fs-2 text-muted" style="opacity:0.5;"></i>
                                    <?php endif; ?>
                                </div>
                                <h6 class="fw-bold text-dark mb-2" style="font-size:0.95rem; letter-spacing:-0.2px;">
                                    <i class="bi bi-person-badge text-muted me-1" style="font-size:0.8rem;"></i>
                                    <?= $staffFullName ?>
                                </h6>
                                <span class="badge rounded-pill text-white"
                                    style="background:<?= $staffTypeBg ?>; padding:4px 12px; font-size:0.72rem; font-weight:600;"><?= $staffTypeLabel ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>


            <!-- ===================== PESTANA: DISPOSITIVOS ===================== -->
            <div class="tab-pane fade" id="v-dispositivos" role="tabpanel" aria-labelledby="v-dispositivos-tab">
                <div class="filters-section pb-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold text-dark d-flex align-items-center gap-2">
                            <i class="bi bi-funnel text-muted"></i> Filtros y Busqueda
                        </h6>
                        <button class="btn btn-sm btn-light border-0 text-muted"><i
                                class="bi bi-chevron-up"></i></button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-4">
                            <label class="filter-label">Buscar</label>
                            <div class="icon-input-wrapper">
                                <i class="bi bi-search"></i>
                                <input id="devices-search-input" type="text" class="form-control form-control-custom"
                                    placeholder="Buscar dispositivos...">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="stat-card px-4" style="align-items: center;">
                            <div class="d-flex flex-column">
                                <div class="stat-title text-muted">Total de Dispositivos</div>
                                <h3 id="devices-total-count" class="stat-value">
                                    <?= esc((string) ($deviceStatsView['total'] ?? 0)) ?>
                                </h3>
                            </div>
                            <div class="stat-icon"
                                style="background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0; border-radius: 50%; width: 42px; height: 42px;">
                                <i class="bi bi-phone"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card px-4" style="align-items: center;">
                            <div class="d-flex flex-column">
                                <div class="stat-title text-muted">Dispositivos Activos</div>
                                <h3 id="devices-active-count" class="stat-value">
                                    <?= esc((string) ($deviceStatsView['active'] ?? 0)) ?>
                                </h3>
                            </div>
                            <div class="stat-icon"
                                style="background: transparent; color: #10b981; border: 1px solid #86efac; border-radius: 50%; width: 42px; height: 42px;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <?php $devicesCount = count($securityDevicesList); ?>

                <div id="devices-empty-state" class="empty-state border-0 <?= $devicesCount > 0 ? 'd-none' : '' ?>"
                    style="padding: 70px 20px;">
                    <div class="empty-state-icon text-muted"
                        style="background: #f8fafc; border-radius: 50%; width: 80px; height: 80px; font-size: 32px; border: 1px solid #e2e8f0; opacity: 0.85;">
                        <i class="bi bi-phone"></i>
                    </div>
                    <h4 class="empty-state-title">Aun no hay dispositivos</h4>
                    <p class="empty-state-desc" style="max-width: 380px;">
                        Agrega un dispositivo para generar credenciales de acceso a la Aplicacion PWA de
                        <?= esc($axisBrandView) ?>.
                    </p>
                </div>

                <div class="row g-3 <?= $devicesCount === 0 ? 'd-none' : '' ?>" id="devices-list-grid">
                    <?php foreach ($securityDevicesList as $device): ?>
                        <?php
                        $createdRaw = $device['created_at'] ?? '';
                        $createdLabel = $createdRaw ? date('M d, Y H:i', strtotime($createdRaw)) : '-';
                        $isActive = ($device['status'] ?? 'active') === 'active';
                        $searchIndex = strtolower(trim(($device['name'] ?? '') . ' ' . ($device['email'] ?? '')));
                        ?>
                        <div class="col-md-6 col-lg-4 device-card-col" data-device-search="<?= esc($searchIndex, 'attr') ?>"
                            data-device-status="<?= esc($device['status'] ?? 'active', 'attr') ?>">
                            <div class="card border-0 shadow-sm rounded-4 js-device-card" role="button" tabindex="0"
                                data-device-id="<?= esc((string) ($device['id'] ?? 0), 'attr') ?>"
                                data-device-name="<?= esc($device['name'] ?? 'Dispositivo', 'attr') ?>"
                                data-device-email="<?= esc($device['email'] ?? '', 'attr') ?>"
                                data-device-status="<?= esc($device['status'] ?? 'active', 'attr') ?>"
                                data-device-created="<?= esc($createdRaw, 'attr') ?>"
                                style="border: 1px solid #f1f5f9 !important;">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="d-flex gap-3">
                                            <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border"
                                                style="width: 44px; height: 44px; background: #f8fafc !important;">
                                                <i class="bi bi-phone text-muted fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark" style="font-size:0.95rem">
                                                    <?= esc($device['name'] ?? 'Dispositivo') ?>
                                                </h6>
                                                <span class="text-muted"
                                                    style="font-family:monospace; font-size:0.75rem;"><?= esc($device['email'] ?? '') ?></span>
                                            </div>
                                        </div>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-sm btn-link text-muted p-0" tabindex="-1"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border"
                                                style="min-width: 180px; border-radius: 10px; overflow: hidden; padding: 6px;">
                                                <li><button type="button"
                                                        class="dropdown-item d-flex align-items-center gap-2 rounded-2 js-device-copy-email"
                                                        style="font-size: 0.85rem; padding: 8px 12px;"
                                                        data-email="<?= esc($device['email'] ?? '', 'attr') ?>">
                                                        <i class="bi bi-clipboard text-muted"></i> Copiar Email
                                                    </button></li>
                                                <li>
                                                    <hr class="dropdown-divider my-1">
                                                </li>
                                                <li><button type="button"
                                                        class="dropdown-item d-flex align-items-center gap-2 rounded-2 text-danger js-device-delete"
                                                        style="font-size: 0.85rem; padding: 8px 12px;"
                                                        data-device-id="<?= esc((string) ($device['id'] ?? 0), 'attr') ?>"
                                                        data-device-name="<?= esc($device['name'] ?? 'Dispositivo', 'attr') ?>">
                                                        <i class="bi bi-trash3"></i> Eliminar Dispositivo
                                                    </button></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="mt-4 mb-2 d-flex align-items-center gap-2">
                                        <span class="fw-bold" style="font-size:0.75rem">Dispositivo</span>
                                        <span class="badge rounded-pill"
                                            style="background-color: <?= $isActive ? '#10b981' : '#94a3b8' ?>; padding: 4px 10px; font-weight: 600;"><?= $isActive ? 'Activo' : 'Inactivo' ?></span>
                                    </div>
                                    <hr class="my-3 text-muted opacity-25">
                                    <div class="d-flex align-items-center gap-1 text-muted" style="font-size:0.75rem">
                                        <i class="bi bi-calendar3"></i> Creado: <?= esc($createdLabel) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- ===================== PESTANA: ALERTAS ===================== -->
            <div class="tab-pane fade" id="v-alertas" role="tabpanel" aria-labelledby="v-alertas-tab">
                <style>
                    .alert-toggle-box {
                        border: 1px solid #e2e8f0;
                        border-radius: 12px;
                        padding: 16px;
                        margin-bottom: 12px;
                        display: flex;
                        justify-content: space-between;
                        align-items: center;
                        background: #fff;
                        transition: border-color 0.2s;
                    }

                    .alert-toggle-box:hover {
                        border-color: #cbd5e1;
                    }

                    .alert-icon-box {
                        width: 40px;
                        height: 40px;
                        background: #f8fafc;
                        border-radius: 8px;
                        border: 1px solid #e2e8f0;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        font-size: 1.125rem;
                        color: #64748b;
                        margin-right: 16px;
                    }

                    .phone-mockup {
                        width: 250px;
                        border: 8px solid #1e293b;
                        border-radius: 36px;
                        background: #fff;
                        position: relative;
                        padding: 16px;
                        margin: 0 auto;
                        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
                        display: flex;
                        flex-direction: column;
                        overflow: hidden;
                        height: 480px;
                    }

                    .phone-notch {
                        position: absolute;
                        top: 0;
                        left: 50%;
                        transform: translateX(-50%);
                        width: 80px;
                        height: 16px;
                        background: #1e293b;
                        border-bottom-left-radius: 12px;
                        border-bottom-right-radius: 12px;
                        z-index: 10;
                    }

                    .mockup-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 12px;
                        margin-top: 10px;
                    }

                    .mockup-btn {
                        height: 80px;
                        border-radius: 16px;
                        color: white;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;
                        text-align: center;
                        gap: 6px;
                        border: none;
                        font-size: 0.65rem;
                        font-weight: 500;
                        font-family: monospace;
                    }

                    .alert-header-text {
                        max-width: 400px;
                    }
                </style>
                <div class="row g-4 mt-1">
                    <div class="col-lg-7">
                        <div class="mb-4 pt-1">
                            <h5 class="fw-bold text-dark mb-1">Alertas Comunitarias</h5>
                            <p class="text-muted small alert-header-text">Configura los tipos de alerta que los guardias
                                de seguridad pueden enviar a todos los residentes.</p>
                        </div>
                        <h6 class="text-muted"
                            style="font-size:0.7rem; font-weight:600; letter-spacing:0.5px; margin-bottom:16px;">
                            PREDETERMINADOS</h6>

                        <div class="alert-toggle-box">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-box"><i class="bi bi-trash"></i></div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark" style="font-size:0.9rem">Camión de basura</h6>
                                    <div class="text-muted" style="font-size:0.75rem">¡Ingresa camión de basura!</div>
                                </div>
                            </div>
                            <div class="form-check form-switch m-0"><input class="form-check-input"
                                    style="width:2.5rem;height:1.25rem" type="checkbox" checked></div>
                        </div>

                        <div class="alert-toggle-box">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-box"><i class="bi bi-recycle"></i></div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark" style="font-size:0.9rem">Reciclaje</h6>
                                    <div class="text-muted" style="font-size:0.75rem">¡Ingresa servicio de reciclaje!
                                    </div>
                                </div>
                            </div>
                            <div class="form-check form-switch m-0"><input class="form-check-input"
                                    style="width:2.5rem;height:1.25rem" type="checkbox" checked></div>
                        </div>

                        <div class="alert-toggle-box">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-box"><i class="bi bi-droplet"></i></div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark" style="font-size:0.9rem">Pipa de agua</h6>
                                    <div class="text-muted" style="font-size:0.75rem">¡Ingresa pipa de agua!</div>
                                </div>
                            </div>
                            <div class="form-check form-switch m-0"><input class="form-check-input"
                                    style="width:2.5rem;height:1.25rem" type="checkbox" checked></div>
                        </div>

                        <div class="alert-toggle-box">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-box" style="color:#64748b"><i class="bi bi-fire"></i></div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark" style="font-size:0.9rem">Gas LP</h6>
                                    <div class="text-muted" style="font-size:0.75rem">¡Ingresa camión de Gas LP!</div>
                                </div>
                            </div>
                            <div class="form-check form-switch m-0"><input class="form-check-input"
                                    style="width:2.5rem;height:1.25rem" type="checkbox" checked></div>
                        </div>

                        <div class="alert-toggle-box">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-box"><i class="bi bi-lightning"></i></div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark" style="font-size:0.9rem">Recibo de luz (CFE)</h6>
                                    <div class="text-muted" style="font-size:0.75rem">¡Llegó el recibo de luz! Pasa a
                                        recogerlo.</div>
                                </div>
                            </div>
                            <div class="form-check form-switch m-0"><input class="form-check-input"
                                    style="width:2.5rem;height:1.25rem" type="checkbox" checked></div>
                        </div>

                        <div class="alert-toggle-box">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-box"><i class="bi bi-receipt"></i></div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-dark" style="font-size:0.9rem">Recibo de agua</h6>
                                    <div class="text-muted" style="font-size:0.75rem">¡Llegó el recibo de agua! Pasa a
                                        recogerlo.</div>
                                </div>
                            </div>
                            <div class="form-check form-switch m-0"><input class="form-check-input"
                                    style="width:2.5rem;height:1.25rem" type="checkbox" checked></div>
                        </div>

                        <div class="alert-toggle-box opacity-75 mt-4" style="background:#fafbfc">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-box"><i class="bi bi-lightning-slash"></i></div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-muted" style="font-size:0.9rem">Corte de luz</h6>
                                    <div class="text-muted" style="font-size:0.75rem">Corte de luz en la comunidad</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size:0.7rem; color:#d97706; font-weight:600;">Inactivo</span>
                                <div class="form-check form-switch m-0"><input
                                        class="form-check-input bg-secondary border-secondary"
                                        style="width:2.5rem;height:1.25rem; opacity:0.3" type="checkbox" disabled></div>
                            </div>
                        </div>

                        <div class="alert-toggle-box opacity-75" style="background:#fafbfc">
                            <div class="d-flex align-items-center">
                                <div class="alert-icon-box"><i class="bi bi-droplet-half"></i></div>
                                <div>
                                    <h6 class="mb-1 fw-bold text-muted" style="font-size:0.9rem">Corte de agua</h6>
                                    <div class="text-muted" style="font-size:0.75rem">Corte de agua en la comunidad
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size:0.7rem; color:#d97706; font-weight:600;">Inactivo</span>
                                <div class="form-check form-switch m-0"><input
                                        class="form-check-input bg-secondary border-secondary"
                                        style="width:2.5rem;height:1.25rem; opacity:0.3" type="checkbox" disabled></div>
                            </div>
                        </div>

                    </div>
                    <div class="col-lg-5 ps-lg-4">
                        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 pt-1">
                            <h6 class="text-muted mb-0"
                                style="font-size:0.7rem; font-weight:600; letter-spacing:0.5px;">PERSONALIZADOS</h6>
                        </div>

                        <div class="empty-state py-5 px-3 mb-5"
                            style="border: 1px dashed #e2e8f0; background: #ffffff; border-radius: 12px;">
                            <div class="text-center text-muted mb-3">
                                <i class="bi bi-stars fs-3" style="color: #a78bfa;"></i>
                            </div>
                            <h6 class="fw-bold mb-2" style="font-size:0.95rem;">No hay alertas personalizadas.</h6>
                            <p class="text-muted small mb-0">Crea una nueva alerta manualmente o con IA.</p>
                        </div>

                        <!-- Phone Mockup -->
                        <div class="mt-4 pt-4 text-center">
                            <h6 class="text-muted mb-4"
                                style="font-size:0.7rem; font-weight:600; letter-spacing:0.5px;">VISTA PREVIA DEL
                                GUARDIA</h6>
                            <div class="phone-mockup">
                                <div class="phone-notch"></div>
                                <div class="d-flex justify-content-between px-2 pt-2 fw-medium"
                                    style="font-size:10px; color:#334155;">
                                    <span>9:41</span>
                                    <span><i class="bi bi-battery-full me-1"></i>100%</span>
                                </div>
                                <div class="text-start mt-4 mb-2 px-2 fw-bold text-dark fs-6"
                                    style="font-family:-apple-system, BlinkMacSystemFont;">Alertas</div>

                                <div class="mockup-grid px-1">
                                    <div class="mockup-btn" style="background-color: #3b82f6;"><i
                                            class="bi bi-trash fs-4 mb-1"></i> Camión de<br>basura</div>
                                    <div class="mockup-btn" style="background-color: #10b981;"><i
                                            class="bi bi-recycle fs-4 mb-1"></i> Reciclaje</div>
                                    <div class="mockup-btn" style="background-color: #f59e0b;"><i
                                            class="bi bi-droplet fs-4 mb-1"></i> Pipa de agua</div>
                                    <div class="mockup-btn" style="background-color: #ef4444;"><i
                                            class="bi bi-fire fs-4 mb-1"></i> Gas LP</div>
                                    <div class="mockup-btn" style="background-color: #8b5cf6;"><i
                                            class="bi bi-lightning fs-4 mb-1"></i> Recibo de luz<br>(CFE)</div>
                                    <div class="mockup-btn" style="background-color: #06b6d4;"><i
                                            class="bi bi-receipt fs-4 mb-1"></i> Recibo de<br>agua</div>
                                </div>
                                <!-- Home Indicator -->
                                <div
                                    style="position:absolute; bottom:8px; left:50%; transform:translateX(-50%); width:35%; height:4px; background:#d4d4d8; border-radius:20px;">
                                </div>
                            </div>
                            <div class="mt-4 text-muted" style="font-size:0.8rem;">Así ven los guardias las alertas en
                                la app móvil</div>
                        </div>
                    </div>
                </div>
            </div>

        </div> <!-- /.tab-content -->

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabEls = document.querySelectorAll('button[data-bs-toggle="pill"]');
        const headerActionArea = document.getElementById('header-action-area');
        const tabControlsRight = document.getElementById('tab-controls-right');
        const headerDevicesInfoBtn = document.getElementById('header-btn-v-dispositivos');
        const deviceInfoModalEl = document.getElementById('deviceCredentialsInfoModal');

        const startDatePHP = '<?= $startDate ?? date("Y-m-d") ?>';
        const endDatePHP = '<?= $endDate ?? date("Y-m-d") ?>';
        const qrStartDatePHP = '<?= $qrStartDate ?? date("Y-m-d") ?>';
        const qrEndDatePHP = '<?= $qrEndDate ?? date("Y-m-d") ?>';

        if (deviceInfoModalEl && headerDevicesInfoBtn && window.bootstrap && window.bootstrap.Modal) {
            const deviceInfoModal = window.bootstrap.Modal.getOrCreateInstance(deviceInfoModalEl);
            headerDevicesInfoBtn.addEventListener('click', function () {
                deviceInfoModal.show();
            });
        }

        function updateActionButtons(targetId) {
            // Clean dynamic visibility
            document.querySelectorAll('.tab-dynamic-control').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.header-dynamic-control').forEach(el => el.classList.add('d-none'));

            // Activate current control panels natively
            const tName = targetId.replace('#', '');
            const rightCtrl = document.getElementById('ctrl-' + tName);
            if (rightCtrl) rightCtrl.classList.remove('d-none');

            const headerCtrl = document.getElementById('header-btn-' + tName);
            if (headerCtrl) headerCtrl.classList.remove('d-none');
        }

        // Initialize Native Calendar Instances globally (Only ONCE via flatpickr directly to elements)
        const accInput = document.getElementById("acc-date-picker");
        const accDatePickerInstance = accInput ? flatpickr(accInput, {
            locale: "es",
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: (startDatePHP === endDatePHP) ? startDatePHP : [startDatePHP, endDatePHP],
            onChange: function (selectedDates) {
                if (selectedDates.length === 2) {
                    const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const e = flatpickr.formatDate(selectedDates[1], "Y-m-d");
                    const url = new URL(window.location.href);
                    url.searchParams.set('start', s);
                    url.searchParams.set('end', e);
                    url.hash = 'v-entradas';
                    window.location.href = url.toString();
                } else if (selectedDates.length === 1 && this.isOpen === false) {
                    const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const url = new URL(window.location.href);
                    url.searchParams.set('start', s);
                    url.searchParams.set('end', s);
                    url.hash = 'v-entradas';
                    window.location.href = url.toString();
                }
            },
            onClose: function (selectedDates) {
                if (selectedDates.length === 1) {
                    const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const url = new URL(window.location.href);
                    url.searchParams.set('start', s);
                    url.searchParams.set('end', s);
                    url.hash = 'v-entradas';
                    window.location.href = url.toString();
                }
            }
        }) : null;

        document.getElementById('acc-date-wrapper')?.addEventListener('click', (e) => {
            e.stopPropagation();
            if (accDatePickerInstance) accDatePickerInstance.open();
        });

        document.getElementById('btn-acc-prev-day')?.addEventListener('click', () => {
            let d = new Date(startDatePHP + "T12:00:00Z");
            d.setUTCDate(d.getUTCDate() - 1);
            let s = d.toISOString().split('T')[0];
            const url = new URL(window.location.href);
            url.searchParams.set('start', s);
            url.searchParams.set('end', s);
            url.hash = 'v-entradas';
            window.location.href = url.toString();
        });

        document.getElementById('btn-acc-next-day')?.addEventListener('click', () => {
            let d = new Date(endDatePHP + "T12:00:00Z");
            d.setUTCDate(d.getUTCDate() + 1);
            let s = d.toISOString().split('T')[0];
            const url = new URL(window.location.href);
            url.searchParams.set('start', s);
            url.searchParams.set('end', s);
            url.hash = 'v-entradas';
            window.location.href = url.toString();
        });

        // Initialize Native QR Calendar
        const qrInput = document.getElementById("qr-date-picker");
        const qrDatePickerInstance = qrInput ? flatpickr(qrInput, {
            locale: "es",
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: (qrStartDatePHP === qrEndDatePHP) ? qrStartDatePHP : [qrStartDatePHP, qrEndDatePHP],
            onChange: function (selectedDates) {
                if (selectedDates.length === 2) {
                    const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const e = flatpickr.formatDate(selectedDates[1], "Y-m-d");
                    const url = new URL(window.location.href);
                    url.searchParams.set('qstart', s);
                    url.searchParams.set('qend', e);
                    url.hash = 'v-qr';
                    window.location.href = url.toString();
                } else if (selectedDates.length === 1 && this.isOpen === false) {
                    const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const url = new URL(window.location.href);
                    url.searchParams.set('qstart', s);
                    url.searchParams.set('qend', s);
                    url.hash = 'v-qr';
                    window.location.href = url.toString();
                }
            },
            onClose: function (selectedDates) {
                if (selectedDates.length === 1) {
                    const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const url = new URL(window.location.href);
                    url.searchParams.set('qstart', s);
                    url.searchParams.set('qend', s);
                    url.hash = 'v-qr';
                    window.location.href = url.toString();
                }
            }
        }) : null;

        document.getElementById('qr-date-wrapper')?.addEventListener('click', (e) => {
            e.stopPropagation();
            if (qrDatePickerInstance) qrDatePickerInstance.open();
        });

        document.getElementById('btn-qr-prev-day')?.addEventListener('click', () => {
            let d = new Date(qrStartDatePHP + "T12:00:00Z");
            d.setUTCDate(d.getUTCDate() - 1);
            let s = d.toISOString().split('T')[0];
            const url = new URL(window.location.href);
            url.searchParams.set('qstart', s);
            url.searchParams.set('qend', s);
            url.hash = 'v-qr';
            window.location.href = url.toString();
        });

        document.getElementById('btn-qr-next-day')?.addEventListener('click', () => {
            let d = new Date(qrEndDatePHP + "T12:00:00Z");
            d.setUTCDate(d.getUTCDate() + 1);
            let s = d.toISOString().split('T')[0];
            const url = new URL(window.location.href);
            url.searchParams.set('qstart', s);
            url.searchParams.set('qend', s);
            url.hash = 'v-qr';
            window.location.href = url.toString();
        });

        // Escuchar cambios de tab
        tabEls.forEach(function (el) {
            el.addEventListener('shown.bs.tab', function (event) {
                updateActionButtons(event.target.getAttribute('data-bs-target'));
            });
        });

        // Inicializar con la tab desde URL si existe
        if (window.location.hash) {
            const hashBtn = document.querySelector(`button[data-bs-target="${window.location.hash}"]`);
            if (hashBtn) {
                const tab = new bootstrap.Tab(hashBtn);
                tab.show();
            } else {
                // Fallback a la tab activa actual del HTML
                const activeTab = document.querySelector('button[data-bs-toggle="pill"].active');
                if (activeTab) updateActionButtons(activeTab.getAttribute('data-bs-target'));
            }
        } else {
            // Inicializar con la tab activa actual del HTML
            const activeTab = document.querySelector('button[data-bs-toggle="pill"].active');
            if (activeTab) updateActionButtons(activeTab.getAttribute('data-bs-target'));
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addDeviceBtn = document.getElementById('ctrl-v-dispositivos');
        const addDeviceModalEl = document.getElementById('addDeviceModal');
        const generatedModalEl = document.getElementById('deviceCredentialsGeneratedModal');
        const detailModalEl = document.getElementById('deviceDetailModal');

        const addDeviceInput = document.getElementById('add-device-name');
        const addDeviceError = document.getElementById('add-device-error');
        const addDeviceSubmitBtn = document.getElementById('btn-confirm-create-device');

        const generatedTitle = document.getElementById('generated-credentials-title');
        const generatedEmail = document.getElementById('generated-cred-email');
        const generatedPassword = document.getElementById('generated-cred-password');
        const copyGeneratedEmailBtn = document.getElementById('btn-copy-generated-email');
        const copyGeneratedPasswordBtn = document.getElementById('btn-copy-generated-password');
        const copyGeneratedBothBtn = document.getElementById('btn-copy-generated-both');

        const detailIdInput = document.getElementById('device-detail-id');
        const detailName = document.getElementById('device-detail-name');
        const detailCreated = document.getElementById('device-detail-created');
        const detailEmail = document.getElementById('device-detail-email');
        const detailCopyEmailBtn = document.getElementById('btn-copy-detail-email');
        const detailResetBtn = document.getElementById('btn-reset-device-password');
        const editNameToggleBtn = document.getElementById('btn-open-edit-device-name');
        const editNameWrap = document.getElementById('device-edit-name-wrap');
        const editNameInput = document.getElementById('device-edit-name-input');
        const editNameError = document.getElementById('device-edit-name-error');
        const saveNameBtn = document.getElementById('btn-save-device-name');
        const cancelNameBtn = document.getElementById('btn-cancel-device-name');

        const devicesGrid = document.getElementById('devices-list-grid');
        const devicesEmptyState = document.getElementById('devices-empty-state');
        const devicesSearchInput = document.getElementById('devices-search-input');
        const devicesTotalCount = document.getElementById('devices-total-count');
        const devicesActiveCount = document.getElementById('devices-active-count');
        const emptyStateTitle = devicesEmptyState ? devicesEmptyState.querySelector('.empty-state-title') : null;
        const emptyStateDesc = devicesEmptyState ? devicesEmptyState.querySelector('.empty-state-desc') : null;

        if (!window.bootstrap || !window.bootstrap.Modal) return;

        const addDeviceModal = addDeviceModalEl ? window.bootstrap.Modal.getOrCreateInstance(addDeviceModalEl) : null;
        const generatedModal = generatedModalEl ? window.bootstrap.Modal.getOrCreateInstance(generatedModalEl) : null;
        const detailModal = detailModalEl ? window.bootstrap.Modal.getOrCreateInstance(detailModalEl) : null;

        const createUrl = <?= json_encode(base_url('admin/seguridad/dispositivos/crear')) ?>;
        const renameUrl = <?= json_encode(base_url('admin/seguridad/dispositivos/actualizar-nombre')) ?>;
        const resetPasswordUrl = <?= json_encode(base_url('admin/seguridad/dispositivos/restablecer-password')) ?>;
        const deleteDeviceUrl = <?= json_encode(base_url('admin/seguridad/dispositivos/eliminar')) ?>;

        const defaultEmptyTitle = emptyStateTitle ? emptyStateTitle.textContent : 'Aun no hay dispositivos';
        const defaultEmptyDesc = emptyStateDesc ? emptyStateDesc.textContent : '';
        let generatedCache = { email: '', password: '' };

        function isValidDeviceName(value) {
            const normalized = String(value || '').trim();
            return normalized.length >= 2 && normalized.length <= 40;
        }

        function setCreateButtonState() {
            if (!addDeviceSubmitBtn || !addDeviceInput) return;
            addDeviceSubmitBtn.disabled = !isValidDeviceName(addDeviceInput.value);
        }

        function setInlineError(target, message) {
            if (!target) return;
            const text = String(message || '').trim();
            target.textContent = text;
            target.classList.toggle('d-none', text === '');
        }

        function normalizeText(value) {
            return String(value ?? '').replace(/[<>&\"']/g, (ch) => {
                if (ch === '<') return '&lt;';
                if (ch === '>') return '&gt;';
                if (ch === '&') return '&amp;';
                if (ch === '"') return '&quot;';
                return '&#39;';
            });
        }

        function formatCreatedDate(raw) {
            if (!raw) return '-';
            const dt = new Date(raw.replace(' ', 'T'));
            if (Number.isNaN(dt.getTime())) return raw;
            const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            const dd = String(dt.getDate()).padStart(2, '0');
            const month = months[dt.getMonth()];
            const yy = dt.getFullYear();
            const hh = String(dt.getHours()).padStart(2, '0');
            const mm = String(dt.getMinutes()).padStart(2, '0');
            return `${month} ${dd}, ${yy} ${hh}:${mm}`;
        }

        function getAllDeviceCols() {
            if (!devicesGrid) return [];
            return Array.from(devicesGrid.querySelectorAll('.device-card-col'));
        }

        function updateDeviceStatsFromDom() {
            const cols = getAllDeviceCols();
            const active = cols.filter((col) => (col.dataset.deviceStatus || 'active') === 'active').length;
            if (devicesTotalCount) devicesTotalCount.textContent = String(cols.length);
            if (devicesActiveCount) devicesActiveCount.textContent = String(active);
        }

        function updateEmptyStateForSearch(visibleCount, totalCount) {
            if (!devicesEmptyState) return;
            const hasSearch = devicesSearchInput && devicesSearchInput.value.trim() !== '';

            if (visibleCount > 0) {
                devicesEmptyState.classList.add('d-none');
                return;
            }

            devicesEmptyState.classList.remove('d-none');

            if (hasSearch && totalCount > 0) {
                if (emptyStateTitle) emptyStateTitle.textContent = 'Sin resultados';
                if (emptyStateDesc) emptyStateDesc.textContent = 'No encontramos dispositivos para ese criterio de búsqueda.';
            } else {
                if (emptyStateTitle) emptyStateTitle.textContent = defaultEmptyTitle;
                if (emptyStateDesc) emptyStateDesc.textContent = defaultEmptyDesc;
            }
        }

        function applyDeviceSearch() {
            const term = String(devicesSearchInput?.value || '').trim().toLowerCase();
            const cols = getAllDeviceCols();
            let visible = 0;

            cols.forEach((col) => {
                const haystack = String(col.dataset.deviceSearch || '').toLowerCase();
                const match = term === '' || haystack.includes(term);
                col.classList.toggle('d-none', !match);
                if (match) visible++;
            });

            if (devicesGrid) {
                devicesGrid.classList.toggle('d-none', cols.length === 0);
            }
            updateEmptyStateForSearch(visible, cols.length);
        }

        async function copyToClipboard(value) {
            const text = String(value || '');
            if (!text) return;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                await navigator.clipboard.writeText(text);
            } else {
                const temp = document.createElement('textarea');
                temp.value = text;
                temp.style.position = 'fixed';
                temp.style.opacity = '0';
                document.body.appendChild(temp);
                temp.select();
                document.execCommand('copy');
                document.body.removeChild(temp);
            }

            if (window.Swal && window.Swal.fire) {
                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Copiado',
                    showConfirmButton: false,
                    timer: 1200
                });
            }
        }

        async function postDeviceForm(url, payload) {
            const formData = new FormData();
            Object.keys(payload).forEach((key) => {
                formData.append(key, payload[key]);
            });

            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            let data = {};
            try {
                data = await response.json();
            } catch (err) {
                data = {};
            }
            if (typeof data.status === 'undefined') {
                data.status = response.status;
            }
            return data;
        }

        function renderDeviceCard(device) {
            const wrapper = document.createElement('div');
            wrapper.className = 'col-md-6 col-lg-4 device-card-col';
            wrapper.dataset.deviceStatus = device.status || 'active';
            wrapper.dataset.deviceSearch = `${String(device.name || '').toLowerCase()} ${String(device.email || '').toLowerCase()}`.trim();

            const status = (device.status || 'active') === 'active' ? 'Activo' : 'Inactivo';
            const statusColor = (device.status || 'active') === 'active' ? '#10b981' : '#94a3b8';
            const createdLabel = formatCreatedDate(device.created_at || '');

            wrapper.innerHTML = `
                <div class="card border-0 shadow-sm rounded-4 js-device-card"
                    role="button"
                    tabindex="0"
                    data-device-id="${normalizeText(device.id)}"
                    data-device-name="${normalizeText(device.name || 'Dispositivo')}"
                    data-device-email="${normalizeText(device.email || '')}"
                    data-device-status="${normalizeText(device.status || 'active')}"
                    data-device-created="${normalizeText(device.created_at || '')}"
                    style="border: 1px solid #f1f5f9 !important;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex gap-3">
                                <div class="rounded-3 bg-light d-flex align-items-center justify-content-center border"
                                    style="width: 44px; height: 44px; background: #f8fafc !important;">
                                    <i class="bi bi-phone text-muted fs-5"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark" style="font-size:0.95rem">${normalizeText(device.name || 'Dispositivo')}</h6>
                                    <span class="text-muted" style="font-family:monospace; font-size:0.75rem;">${normalizeText(device.email || '')}</span>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button type="button" class="btn btn-sm btn-link text-muted p-0" tabindex="-1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border" style="min-width: 180px; border-radius: 10px; overflow: hidden; padding: 6px;">
                                    <li><button type="button" class="dropdown-item d-flex align-items-center gap-2 rounded-2 js-device-copy-email" style="font-size: 0.85rem; padding: 8px 12px;" data-email="${normalizeText(device.email || '')}">
                                        <i class="bi bi-clipboard text-muted"></i> Copiar Email
                                    </button></li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li><button type="button" class="dropdown-item d-flex align-items-center gap-2 rounded-2 text-danger js-device-delete" style="font-size: 0.85rem; padding: 8px 12px;" data-device-id="${normalizeText(device.id)}" data-device-name="${normalizeText(device.name || 'Dispositivo')}">
                                        <i class="bi bi-trash3"></i> Eliminar Dispositivo
                                    </button></li>
                                </ul>
                            </div>
                        </div>
                        <div class="mt-4 mb-2 d-flex align-items-center gap-2">
                            <span class="fw-bold" style="font-size:0.75rem">Dispositivo</span>
                            <span class="badge rounded-pill" style="background-color: ${statusColor}; padding: 4px 10px; font-weight: 600;">${status}</span>
                        </div>
                        <hr class="my-3 text-muted opacity-25">
                        <div class="d-flex align-items-center gap-1 text-muted" style="font-size:0.75rem">
                            <i class="bi bi-calendar3"></i> Creado: ${normalizeText(createdLabel)}
                        </div>
                    </div>
                </div>
            `;

            const card = wrapper.querySelector('.js-device-card');
            if (card) bindDeviceCard(card);
            return wrapper;
        }

        function findCardByDeviceId(deviceId) {
            const selector = `.js-device-card[data-device-id="${String(deviceId)}"]`;
            return document.querySelector(selector);
        }

        function upsertDeviceCard(device) {
            if (!devicesGrid || !device || !device.id) return;

            const existingCard = findCardByDeviceId(device.id);
            const newCol = renderDeviceCard(device);

            if (existingCard) {
                const existingCol = existingCard.closest('.device-card-col');
                if (existingCol) {
                    existingCol.replaceWith(newCol);
                }
            } else {
                devicesGrid.prepend(newCol);
            }

            devicesGrid.classList.remove('d-none');
            updateDeviceStatsFromDom();
            applyDeviceSearch();
        }

        function populateGeneratedCredentials(email, password, title) {
            if (generatedTitle) generatedTitle.innerHTML = `<i class="bi bi-shield-check"></i> ${normalizeText(title || 'Credenciales de Dispositivo Generadas')}`;
            if (generatedEmail) generatedEmail.textContent = email || '-';
            if (generatedPassword) generatedPassword.textContent = password || '-';
            generatedCache = { email: email || '', password: password || '' };
        }

        function openDetailModalFromCard(card) {
            if (!detailModal || !card) return;
            const deviceId = card.dataset.deviceId || '';
            const name = card.dataset.deviceName || 'Dispositivo';
            const email = card.dataset.deviceEmail || '';
            const created = card.dataset.deviceCreated || '';

            if (detailIdInput) detailIdInput.value = deviceId;
            if (detailName) detailName.textContent = name;
            if (detailEmail) detailEmail.textContent = email;
            if (detailCreated) detailCreated.textContent = formatCreatedDate(created);
            if (editNameInput) editNameInput.value = name;
            if (editNameWrap) editNameWrap.classList.add('d-none');
            setInlineError(editNameError, '');

            detailModal.show();
        }

        function bindDeviceCard(card) {
            card.addEventListener('click', (event) => {
                if (event.target.closest('button')) return;
                openDetailModalFromCard(card);
            });

            card.addEventListener('keydown', (event) => {
                if (event.key !== 'Enter' && event.key !== ' ') return;
                event.preventDefault();
                openDetailModalFromCard(card);
            });
        }

        function updateCardNameInDom(deviceId, newName) {
            const card = findCardByDeviceId(deviceId);
            if (!card) return;

            card.dataset.deviceName = newName;
            const titleEl = card.querySelector('h6');
            if (titleEl) titleEl.textContent = newName;

            const email = card.dataset.deviceEmail || '';
            const col = card.closest('.device-card-col');
            if (col) {
                col.dataset.deviceSearch = `${String(newName).toLowerCase()} ${String(email).toLowerCase()}`.trim();
            }
            applyDeviceSearch();
        }

        document.querySelectorAll('.js-device-card').forEach((card) => bindDeviceCard(card));
        updateDeviceStatsFromDom();
        applyDeviceSearch();

        devicesSearchInput?.addEventListener('input', applyDeviceSearch);

        addDeviceInput?.addEventListener('input', () => {
            setInlineError(addDeviceError, '');
            setCreateButtonState();
        });

        addDeviceModalEl?.addEventListener('shown.bs.modal', () => {
            addDeviceInput?.focus();
            setCreateButtonState();
        });

        addDeviceBtn?.addEventListener('click', () => {
            if (!addDeviceModal) return;
            if (addDeviceInput) addDeviceInput.value = '';
            setInlineError(addDeviceError, '');
            setCreateButtonState();
            addDeviceModal.show();
        });

        addDeviceSubmitBtn?.addEventListener('click', async () => {
            if (!addDeviceInput || !addDeviceModal || !generatedModal) return;

            const deviceName = addDeviceInput.value.trim();
            if (!isValidDeviceName(deviceName)) {
                setInlineError(addDeviceError, 'El nombre debe tener entre 2 y 40 caracteres.');
                return;
            }

            const originalText = addDeviceSubmitBtn.textContent;
            addDeviceSubmitBtn.disabled = true;
            addDeviceSubmitBtn.textContent = 'Generando...';
            setInlineError(addDeviceError, '');

            try {
                const result = await postDeviceForm(createUrl, { device_name: deviceName });
                if ((result.status === 201 || result.status === 200) && result.data && result.data.device && result.data.credentials) {
                    upsertDeviceCard(result.data.device);
                    addDeviceModal.hide();
                    addDeviceInput.value = '';

                    populateGeneratedCredentials(
                        result.data.credentials.email,
                        result.data.credentials.password,
                        'Credenciales de Dispositivo Generadas'
                    );
                    generatedModal.show();
                    return;
                }

                setInlineError(addDeviceError, result.message || 'No se pudo crear el dispositivo.');
            } catch (error) {
                setInlineError(addDeviceError, 'Error de red. Intenta de nuevo.');
            } finally {
                addDeviceSubmitBtn.textContent = originalText || 'Agregar Dispositivo';
                setCreateButtonState();
            }
        });

        copyGeneratedEmailBtn?.addEventListener('click', async () => {
            await copyToClipboard(generatedCache.email);
        });
        copyGeneratedPasswordBtn?.addEventListener('click', async () => {
            await copyToClipboard(generatedCache.password);
        });
        copyGeneratedBothBtn?.addEventListener('click', async () => {
            const both = `Email: ${generatedCache.email}\nContraseña: ${generatedCache.password}`;
            await copyToClipboard(both);
        });

        detailCopyEmailBtn?.addEventListener('click', async () => {
            await copyToClipboard(detailEmail?.textContent || '');
        });

        editNameToggleBtn?.addEventListener('click', () => {
            if (!editNameWrap) return;
            editNameWrap.classList.toggle('d-none');
            if (!editNameWrap.classList.contains('d-none')) {
                editNameInput?.focus();
                editNameInput?.select();
            }
        });

        cancelNameBtn?.addEventListener('click', () => {
            if (editNameWrap) editNameWrap.classList.add('d-none');
            setInlineError(editNameError, '');
        });

        saveNameBtn?.addEventListener('click', async () => {
            const deviceId = parseInt(detailIdInput?.value || '0', 10);
            const newName = (editNameInput?.value || '').trim();

            if (!deviceId) {
                setInlineError(editNameError, 'No se encontró el dispositivo.');
                return;
            }
            if (!isValidDeviceName(newName)) {
                setInlineError(editNameError, 'El nombre debe tener entre 2 y 40 caracteres.');
                return;
            }

            saveNameBtn.disabled = true;
            const original = saveNameBtn.textContent;
            saveNameBtn.textContent = 'Guardando...';
            setInlineError(editNameError, '');

            try {
                const result = await postDeviceForm(renameUrl, {
                    device_id: String(deviceId),
                    device_name: newName
                });

                if ((result.status === 200) && result.data && result.data.device) {
                    if (detailName) detailName.textContent = result.data.device.name || newName;
                    if (editNameWrap) editNameWrap.classList.add('d-none');
                    updateCardNameInDom(deviceId, result.data.device.name || newName);
                } else {
                    setInlineError(editNameError, result.message || 'No se pudo actualizar el nombre.');
                }
            } catch (error) {
                setInlineError(editNameError, 'Error de red. Intenta de nuevo.');
            } finally {
                saveNameBtn.disabled = false;
                saveNameBtn.textContent = original || 'Guardar';
            }
        });

        detailResetBtn?.addEventListener('click', async () => {
            const deviceId = parseInt(detailIdInput?.value || '0', 10);
            if (!deviceId || !detailModal || !generatedModal) return;

            detailResetBtn.disabled = true;
            const original = detailResetBtn.innerHTML;
            detailResetBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Restableciendo...';

            try {
                const result = await postDeviceForm(resetPasswordUrl, { device_id: String(deviceId) });
                if ((result.status === 200) && result.data && result.data.credentials) {
                    detailModal.hide();
                    populateGeneratedCredentials(
                        result.data.credentials.email,
                        result.data.credentials.password,
                        'Contrasena Restablecida'
                    );
                    generatedModal.show();
                } else {
                    if (window.Swal && window.Swal.fire) {
                        window.Swal.fire('Error', result.message || 'No se pudo restablecer la contraseña.', 'error');
                    }
                }
            } catch (error) {
                if (window.Swal && window.Swal.fire) {
                    window.Swal.fire('Error', 'Error de red. Intenta de nuevo.', 'error');
                }
            } finally {
                detailResetBtn.disabled = false;
                detailResetBtn.innerHTML = original;
            }
        });

        // =====================================================
        // DEVICE CONTEXT MENU: Copy Email & Delete
        // =====================================================
        function bindDeviceContextActions(scope) {
            const root = scope || document;

            root.querySelectorAll('.js-device-copy-email').forEach(btn => {
                btn.addEventListener('click', async (e) => {
                    e.stopPropagation();
                    const email = btn.dataset.email || '';
                    if (email) await copyToClipboard(email);
                });
            });

            root.querySelectorAll('.js-device-delete').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const deviceId = btn.dataset.deviceId || '';
                    const deviceName = btn.dataset.deviceName || 'Dispositivo';
                    showDeleteDeviceConfirmation(deviceId, deviceName);
                });
            });
        }

        function showDeleteDeviceConfirmation(deviceId, deviceName) {
            if (!window.Swal || !window.Swal.fire) return;

            window.Swal.fire({
                title: 'Eliminar Dispositivo',
                html: `<span style="color:#64748b;">¿Estás seguro de que quieres eliminar este dispositivo? Esto revocará el acceso inmediatamente.</span>`,
                icon: 'warning',
                iconColor: '#ef4444',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#f1f5f9',
                reverseButtons: true,
                focusCancel: true,
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'fw-semibold px-4',
                    cancelButton: 'fw-semibold px-4 text-dark'
                }
            }).then(async (result) => {
                if (!result.isConfirmed) return;
                await executeDeleteDevice(deviceId, deviceName);
            });
        }

        async function executeDeleteDevice(deviceId, deviceName) {
            try {
                const result = await postDeviceForm(deleteDeviceUrl, {
                    device_id: String(deviceId)
                });

                if (result.status === 200) {
                    // Remove card from DOM
                    const card = findCardByDeviceId(deviceId);
                    if (card) {
                        const col = card.closest('.device-card-col');
                        if (col) {
                            col.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                            col.style.opacity = '0';
                            col.style.transform = 'scale(0.95)';
                            setTimeout(() => {
                                col.remove();
                                updateDeviceStatsFromDom();
                                const remaining = getAllDeviceCols();
                                if (remaining.length === 0) {
                                    if (devicesGrid) devicesGrid.classList.add('d-none');
                                    if (devicesEmptyState) {
                                        devicesEmptyState.classList.remove('d-none');
                                        if (emptyStateTitle) emptyStateTitle.textContent = defaultEmptyTitle;
                                        if (emptyStateDesc) emptyStateDesc.textContent = defaultEmptyDesc;
                                    }
                                }
                                applyDeviceSearch();
                            }, 300);
                        }
                    }

                    // Close detail modal if open
                    if (detailModal && detailIdInput && detailIdInput.value === String(deviceId)) {
                        detailModal.hide();
                    }

                    // Show success toast
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Dispositivo eliminado',
                        text: deviceName + ' ha sido eliminado correctamente.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    window.Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error',
                        text: result.message || 'No se pudo eliminar el dispositivo.',
                        showConfirmButton: false,
                        timer: 3500
                    });
                }
            } catch (error) {
                window.Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error de red',
                    text: 'No se pudo conectar con el servidor. Intenta de nuevo.',
                    showConfirmButton: false,
                    timer: 3500
                });
            }
        }

        // Bind context actions on initial DOM cards
        bindDeviceContextActions(devicesGrid);

        // Override bindDeviceCard to also bind context actions on new cards
        const _originalBindDeviceCard = bindDeviceCard;
        bindDeviceCard = function (card) {
            _originalBindDeviceCard(card);
            const col = card.closest('.device-card-col');
            if (col) bindDeviceContextActions(col);
        };
    });
</script>

<script>
    // =====================================================
    // STAFF MANAGEMENT MODULE
    // =====================================================
    document.addEventListener('DOMContentLoaded', function () {
        // --- URLs ---
        const staffCreateUrl = <?= json_encode(base_url('admin/seguridad/staff/crear')) ?>;
        const staffUpdateUrl = <?= json_encode(base_url('admin/seguridad/staff/actualizar')) ?>;
        const staffDeleteUrl = <?= json_encode(base_url('admin/seguridad/staff/eliminar')) ?>;
        const baseAssetUrl = <?= json_encode(rtrim(base_url(), '/')) ?>;

        // --- DOM Elements ---
        const addStaffBtn = document.getElementById('ctrl-v-staff');
        const headerAddStaffBtn = document.getElementById('header-btn-v-staff');
        const addStaffModalEl = document.getElementById('addStaffModal');
        const profileModalEl = document.getElementById('staffProfileModal');
        const staffGrid = document.getElementById('staff-list-grid');
        const staffEmptyState = document.getElementById('staff-empty-state');
        const staffSearchInput = document.getElementById('staff-search-input');

        if (!window.bootstrap || !window.bootstrap.Modal) return;

        const addStaffModal = addStaffModalEl ? window.bootstrap.Modal.getOrCreateInstance(addStaffModalEl) : null;
        const profileModal = profileModalEl ? window.bootstrap.Modal.getOrCreateInstance(profileModalEl) : null;

        // --- Helpers ---
        const TYPE_LABELS = { security: 'Seguridad', maintenance: 'Mantenimiento', other: 'Otro' };
        const TYPE_COLORS = { security: '#2a3547', maintenance: '#0ea5e9', other: '#64748b' };

        function staffTypeLabel(t) { return TYPE_LABELS[t] || 'Otro'; }
        function staffTypeBg(t) { return TYPE_COLORS[t] || '#64748b'; }

        function esc(s) {
            const d = document.createElement('div');
            d.textContent = s || '';
            return d.innerHTML;
        }

        async function postStaffForm(url, formData) {
            const resp = await fetch(url, { method: 'POST', body: formData });
            return resp.json();
        }

        function toast(icon, title, text) {
            if (!window.Swal) return;
            window.Swal.fire({
                toast: true, position: 'top-end', icon, title, text,
                showConfirmButton: false, timer: 3000, timerProgressBar: true
            });
        }

        // --- Photo preview in Add modal ---
        const photoInput = document.getElementById('staff-photo-input');
        const photoPreview = document.getElementById('staff-photo-preview');
        if (photoInput && photoPreview) {
            photoInput.addEventListener('change', () => {
                const file = photoInput.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        photoPreview.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // --- ID Document label in Add modal ---
        const idDocInput = document.getElementById('staff-id-doc-input');
        const idDocPreview = document.getElementById('staff-id-doc-preview');
        if (idDocInput && idDocPreview) {
            idDocInput.addEventListener('change', () => {
                const name = idDocInput.files[0]?.name || '';
                if (name) {
                    idDocPreview.innerHTML = `<i class="bi bi-file-earmark-check text-success"></i> <span>${esc(name)}</span>`;
                }
            });
        }

        // --- Conditional device section ---
        const addTypeSelect = document.getElementById('staff-add-type');
        const deviceSection = document.getElementById('staff-device-section');
        function toggleDeviceSection() {
            if (!addTypeSelect || !deviceSection) return;
            deviceSection.style.display = addTypeSelect.value === 'security' ? 'block' : 'none';
        }
        if (addTypeSelect) {
            addTypeSelect.addEventListener('change', toggleDeviceSection);
            toggleDeviceSection();
        }

        // --- Open Add Modal ---
        function openAddModal() {
            if (!addStaffModal) return;
            // Reset form
            document.getElementById('addStaffForm')?.reset();
            if (photoPreview) {
                photoPreview.innerHTML = '<i class="bi bi-cloud-arrow-up text-muted" style="font-size:1.8rem; opacity:.6;"></i><span class="text-muted" style="font-size:0.68rem; margin-top:2px;">Subir foto del empleado</span>';
            }
            if (idDocPreview) {
                idDocPreview.innerHTML = '<i class="bi bi-cloud-arrow-up"></i> <span>Subir Documento de Identidad</span>';
            }
            const errEl = document.getElementById('staff-add-error');
            if (errEl) errEl.textContent = '';
            toggleDeviceSection();
            addStaffModal.show();
        }
        if (addStaffBtn) addStaffBtn.addEventListener('click', openAddModal);
        if (headerAddStaffBtn) headerAddStaffBtn.addEventListener('click', openAddModal);

        // --- Submit Add Staff ---
        const confirmAddBtn = document.getElementById('btn-confirm-add-staff');
        if (confirmAddBtn) {
            confirmAddBtn.addEventListener('click', async () => {
                const errEl = document.getElementById('staff-add-error');
                if (errEl) errEl.textContent = '';

                const firstName = document.getElementById('staff-add-first')?.value.trim() || '';
                const lastName = document.getElementById('staff-add-last')?.value.trim() || '';
                if (firstName.length < 2 || lastName.length < 2) {
                    if (errEl) errEl.textContent = 'Nombre y apellido son obligatorios (mín. 2 caracteres).';
                    return;
                }

                confirmAddBtn.disabled = true;
                const orig = confirmAddBtn.innerHTML;
                confirmAddBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

                try {
                    const fd = new FormData();
                    fd.append('first_name', firstName);
                    fd.append('last_name', lastName);
                    fd.append('staff_type', document.getElementById('staff-add-type')?.value || 'security');

                    const deviceSel = document.getElementById('staff-add-device');
                    if (deviceSel && deviceSel.value) fd.append('device_id', deviceSel.value);

                    const pf = document.getElementById('staff-photo-input');
                    if (pf?.files[0]) fd.append('photo', pf.files[0]);

                    const idf = document.getElementById('staff-id-doc-input');
                    if (idf?.files[0]) fd.append('id_document', idf.files[0]);

                    const result = await postStaffForm(staffCreateUrl, fd);

                    if (result.status === 201 && result.data?.staff) {
                        addStaffModal.hide();
                        const newCard = renderStaffCard(result.data.staff);
                        if (staffGrid) {
                            staffGrid.classList.remove('d-none');
                            staffGrid.insertAdjacentHTML('afterbegin', newCard);
                            bindStaffCardEvents(staffGrid.querySelector('.staff-card-col'));
                        }
                        if (staffEmptyState) staffEmptyState.classList.add('d-none');
                        toast('success', 'Personal agregado', firstName + ' ' + lastName + ' fue registrado correctamente.');
                    } else {
                        if (errEl) errEl.textContent = result.message || 'Error al crear el personal.';
                    }
                } catch (e) {
                    if (errEl) errEl.textContent = 'Error de red. Intenta de nuevo.';
                } finally {
                    confirmAddBtn.disabled = false;
                    confirmAddBtn.innerHTML = orig;
                }
            });
        }

        // --- Render Staff Card HTML ---
        function renderStaffCard(s) {
            const name = esc((s.first_name || '') + ' ' + (s.last_name || ''));
            const type = s.staff_type || 'other';
            const label = staffTypeLabel(type);
            const bg = staffTypeBg(type);
            const photo = s.photo_url ? (baseAssetUrl + '/' + s.photo_url) : '';
            const searchIdx = ((s.first_name || '') + ' ' + (s.last_name || '') + ' ' + label).toLowerCase();
            const photoHtml = photo
                ? `<img src="${photo}" alt="${name}" style="width:100%; height:100%; object-fit:cover;">`
                : '<i class="bi bi-person fs-2 text-muted" style="opacity:0.5;"></i>';

            return `
            <div class="col-md-6 col-lg-4 staff-card-col" data-staff-search="${esc(searchIdx)}">
                <div class="card border shadow-sm rounded-4 text-center js-staff-card position-relative"
                    role="button" tabindex="0"
                    data-staff-id="${esc(String(s.id || 0))}"
                    data-staff-first="${esc(s.first_name || '')}"
                    data-staff-last="${esc(s.last_name || '')}"
                    data-staff-type="${esc(type)}"
                    data-staff-device="${esc(String(s.device_id || ''))}"
                    data-staff-device-email="${esc(s.device_email || '')}"
                    data-staff-photo="${esc(photo)}"
                    style="border-color: #e2e8f0 !important; padding: 28px 16px 20px;">

                    <div class="dropdown position-absolute" style="top: 12px; right: 12px;">
                        <button type="button" class="btn btn-sm btn-link text-muted p-0" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border" style="min-width:170px; border-radius:10px; padding:6px;">
                            <li><button type="button" class="dropdown-item d-flex align-items-center gap-2 rounded-2 text-danger js-staff-delete-btn" style="font-size:0.85rem; padding:8px 12px;"
                                data-staff-id="${esc(String(s.id || 0))}"
                                data-staff-name="${name}">
                                <i class="bi bi-trash3"></i> Eliminar
                            </button></li>
                        </ul>
                    </div>

                    <div class="mx-auto mb-3" style="width:80px; height:80px; border-radius:50%; overflow:hidden; background:#f1f5f9; border:3px solid #e2e8f0; display:flex; align-items:center; justify-content:center;">
                        ${photoHtml}
                    </div>
                    <h6 class="fw-bold text-dark mb-2" style="font-size:0.95rem; letter-spacing:-0.2px;">
                        <i class="bi bi-person-badge text-muted me-1" style="font-size:0.8rem;"></i> ${name}
                    </h6>
                    <span class="badge rounded-pill text-white" style="background:${bg}; padding:4px 12px; font-size:0.72rem; font-weight:600;">${esc(label)}</span>
                </div>
            </div>`;
        }

        // --- Bind events to a staff card col ---
        function bindStaffCardEvents(col) {
            if (!col) return;
            // Card click → open profile
            const card = col.querySelector('.js-staff-card');
            if (card) {
                card.addEventListener('click', (e) => {
                    if (e.target.closest('.dropdown')) return;
                    openProfileModal(card);
                });
            }
            // Delete btn
            col.querySelectorAll('.js-staff-delete-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    confirmDeleteStaff(btn.dataset.staffId, btn.dataset.staffName);
                });
            });
        }

        // --- Bind all existing cards ---
        document.querySelectorAll('#staff-list-grid .staff-card-col').forEach(bindStaffCardEvents);

        // --- Search ---
        if (staffSearchInput) {
            staffSearchInput.addEventListener('input', () => {
                const q = staffSearchInput.value.trim().toLowerCase();
                document.querySelectorAll('#staff-list-grid .staff-card-col').forEach(col => {
                    const idx = col.dataset.staffSearch || '';
                    col.style.display = !q || idx.includes(q) ? '' : 'none';
                });
            });
        }

        // --- Profile Modal ---
        let _profileStaffId = null;

        function openProfileModal(card) {
            if (!profileModal || !card) return;

            const id = card.dataset.staffId || '';
            const first = card.dataset.staffFirst || '';
            const last = card.dataset.staffLast || '';
            const type = card.dataset.staffType || 'other';
            const photo = card.dataset.staffPhoto || '';
            const deviceEmail = card.dataset.staffDeviceEmail || '';
            const deviceId = card.dataset.staffDevice || '';

            _profileStaffId = id;

            // Fill view
            document.getElementById('staff-profile-name').textContent = first + ' ' + last;
            const badge = document.getElementById('staff-profile-type-badge');
            badge.textContent = staffTypeLabel(type);
            badge.style.background = staffTypeBg(type);
            document.getElementById('staff-profile-first').textContent = first;
            document.getElementById('staff-profile-last').textContent = last;

            const photoWrap = document.getElementById('staff-profile-photo-wrap');
            if (photo) {
                photoWrap.innerHTML = `<img src="${photo}" style="width:100%; height:100%; object-fit:cover;">`;
            } else {
                photoWrap.innerHTML = '<i class="bi bi-person fs-1 text-muted" style="opacity:0.4;"></i>';
            }

            // Device row
            const deviceRow = document.getElementById('staff-profile-device-row');
            const deviceEmailEl = document.getElementById('staff-profile-device-email');
            if (deviceEmail && type === 'security') {
                deviceRow.style.display = 'flex';
                deviceRow.classList.remove('d-none');
                deviceEmailEl.textContent = deviceEmail;
            } else {
                deviceRow.style.display = 'none';
            }

            // Ensure view mode
            showProfileViewMode();

            // Fill edit form values
            document.getElementById('staff-edit-id').value = id;
            document.getElementById('staff-edit-first').value = first;
            document.getElementById('staff-edit-last').value = last;
            document.getElementById('staff-edit-type').value = type;
            document.getElementById('staff-edit-device').value = deviceId;
            toggleEditDeviceSection();
            const editErr = document.getElementById('staff-edit-error');
            if (editErr) editErr.textContent = '';

            profileModal.show();
        }

        function showProfileViewMode() {
            document.getElementById('staff-profile-info')?.classList.remove('d-none');
            document.getElementById('staff-profile-edit-form')?.classList.add('d-none');
            document.getElementById('staff-profile-view-actions')?.classList.remove('d-none');
            const ea = document.getElementById('staff-profile-edit-actions');
            if (ea) { ea.classList.add('d-none'); ea.style.display = ''; }
        }

        function showProfileEditMode() {
            document.getElementById('staff-profile-info')?.classList.add('d-none');
            document.getElementById('staff-profile-edit-form')?.classList.remove('d-none');
            document.getElementById('staff-profile-view-actions')?.classList.add('d-none');
            const ea = document.getElementById('staff-profile-edit-actions');
            if (ea) { ea.classList.remove('d-none'); ea.style.display = 'flex'; }
        }

        // Edit type → device section
        const editTypeSelect = document.getElementById('staff-edit-type');
        const editDeviceSection = document.getElementById('staff-edit-device-section');
        function toggleEditDeviceSection() {
            if (!editTypeSelect || !editDeviceSection) return;
            editDeviceSection.style.display = editTypeSelect.value === 'security' ? 'block' : 'none';
        }
        if (editTypeSelect) editTypeSelect.addEventListener('change', toggleEditDeviceSection);

        // Edit button
        document.getElementById('btn-edit-staff')?.addEventListener('click', showProfileEditMode);
        document.getElementById('btn-cancel-edit-staff')?.addEventListener('click', showProfileViewMode);

        // Save edit
        document.getElementById('btn-save-edit-staff')?.addEventListener('click', async () => {
            const errEl = document.getElementById('staff-edit-error');
            if (errEl) errEl.textContent = '';

            const staffId = document.getElementById('staff-edit-id')?.value || '';
            const firstName = document.getElementById('staff-edit-first')?.value.trim() || '';
            const lastName = document.getElementById('staff-edit-last')?.value.trim() || '';
            const staffType = document.getElementById('staff-edit-type')?.value || 'other';

            if (firstName.length < 2 || lastName.length < 2) {
                if (errEl) errEl.textContent = 'Nombre y apellido son obligatorios (mín. 2 caracteres).';
                return;
            }

            const saveBtn = document.getElementById('btn-save-edit-staff');
            saveBtn.disabled = true;
            const orig = saveBtn.innerHTML;
            saveBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Guardando...';

            try {
                const fd = new FormData();
                fd.append('staff_id', staffId);
                fd.append('first_name', firstName);
                fd.append('last_name', lastName);
                fd.append('staff_type', staffType);

                const deviceSel = document.getElementById('staff-edit-device');
                if (deviceSel && deviceSel.value) fd.append('device_id', deviceSel.value);

                const pf = document.getElementById('staff-edit-photo-input');
                if (pf?.files[0]) fd.append('photo', pf.files[0]);

                const result = await postStaffForm(staffUpdateUrl, fd);

                if (result.status === 200 && result.data?.staff) {
                    const s = result.data.staff;
                    // Update the card in the DOM
                    const card = document.querySelector(`.js-staff-card[data-staff-id="${staffId}"]`);
                    if (card) {
                        card.dataset.staffFirst = s.first_name || '';
                        card.dataset.staffLast = s.last_name || '';
                        card.dataset.staffType = s.staff_type || 'other';
                        card.dataset.staffDevice = String(s.device_id || '');
                        card.dataset.staffDeviceEmail = s.device_email || '';
                        if (s.photo_url) card.dataset.staffPhoto = baseAssetUrl + '/' + s.photo_url;

                        // Update visual elements
                        const nameEl = card.querySelector('h6');
                        if (nameEl) nameEl.innerHTML = '<i class="bi bi-person-badge text-muted me-1" style="font-size:0.8rem;"></i> ' + esc(s.first_name + ' ' + s.last_name);
                        const badgeEl = card.querySelector('.badge');
                        if (badgeEl) {
                            badgeEl.textContent = staffTypeLabel(s.staff_type);
                            badgeEl.style.background = staffTypeBg(s.staff_type);
                        }
                        if (s.photo_url) {
                            const imgWrap = card.querySelector('div[style*="border-radius:50%"]');
                            if (imgWrap) imgWrap.innerHTML = `<img src="${baseAssetUrl}/${s.photo_url}" style="width:100%; height:100%; object-fit:cover;">`;
                        }

                        // Update search index
                        const col = card.closest('.staff-card-col');
                        if (col) col.dataset.staffSearch = (s.first_name + ' ' + s.last_name + ' ' + staffTypeLabel(s.staff_type)).toLowerCase();

                        // Update delete button data attributes
                        const delBtn = col?.querySelector('.js-staff-delete-btn');
                        if (delBtn) delBtn.dataset.staffName = s.first_name + ' ' + s.last_name;
                    }

                    // Update profile view
                    openProfileModal(card || document.querySelector(`.js-staff-card[data-staff-id="${staffId}"]`));
                    showProfileViewMode();
                    toast('success', 'Personal actualizado', s.first_name + ' ' + s.last_name + ' fue actualizado correctamente.');
                } else {
                    if (errEl) errEl.textContent = result.message || 'Error al actualizar.';
                }
            } catch (e) {
                if (errEl) errEl.textContent = 'Error de red. Intenta de nuevo.';
            } finally {
                saveBtn.disabled = false;
                saveBtn.innerHTML = orig;
            }
        });

        // --- Delete Staff ---
        function confirmDeleteStaff(staffId, staffName) {
            if (!window.Swal) return;
            window.Swal.fire({
                title: 'Eliminar Personal',
                html: `<span style="color:#64748b;">¿Estás seguro de que quieres eliminar a <strong>${esc(staffName)}</strong>?</span>`,
                icon: 'warning',
                iconColor: '#ef4444',
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#f1f5f9',
                reverseButtons: true,
                focusCancel: true,
                customClass: { popup: 'rounded-4', confirmButton: 'fw-semibold px-4', cancelButton: 'fw-semibold px-4 text-dark' }
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                try {
                    const fd = new FormData();
                    fd.append('staff_id', String(staffId));
                    const res = await postStaffForm(staffDeleteUrl, fd);

                    if (res.status === 200) {
                        const card = document.querySelector(`.js-staff-card[data-staff-id="${staffId}"]`);
                        if (card) {
                            const col = card.closest('.staff-card-col');
                            if (col) {
                                col.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                                col.style.opacity = '0';
                                col.style.transform = 'scale(0.95)';
                                setTimeout(() => {
                                    col.remove();
                                    if (!staffGrid?.querySelector('.staff-card-col')) {
                                        staffGrid?.classList.add('d-none');
                                        staffEmptyState?.classList.remove('d-none');
                                    }
                                }, 300);
                            }
                        }
                        // Close profile modal if open
                        if (profileModal && _profileStaffId === String(staffId)) {
                            profileModal.hide();
                        }
                        toast('success', 'Personal eliminado', staffName + ' fue eliminado correctamente.');
                    } else {
                        toast('error', 'Error', res.message || 'No se pudo eliminar.');
                    }
                } catch (e) {
                    toast('error', 'Error de red', 'No se pudo conectar con el servidor.');
                }
            });
        }
    });
</script>

<!-- Modal Info Credenciales de Dispositivo -->
<div class="modal fade" id="deviceCredentialsInfoModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered device-cred-dialog">
        <div class="modal-content device-cred-modal-content">
            <div class="device-cred-header">
                <div class="device-cred-title-wrap">
                    <div class="device-cred-icon">
                        <i class="bi bi-info-circle"></i>
                    </div>
                    <div>
                        <div class="device-cred-title">Acerca de las Credenciales de Dispositivo</div>
                        <div class="device-cred-subtitle">Aprende c&oacute;mo usar las credenciales de dispositivo para
                            el personal de seguridad</div>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="device-cred-body">
                <div class="device-cred-section">
                    <div class="device-cred-heading">
                        <i class="bi bi-shield"></i> &iquest;Qu&eacute; son las Credenciales de Dispositivo?
                    </div>
                    <p class="device-cred-text">
                        Las credenciales de dispositivo son cuentas de inicio de sesi&oacute;n especiales que permiten a
                        tu personal de seguridad acceder a la Aplicaci&oacute;n PWA de AxisCondo. Estas credenciales
                        habilitan al personal para gestionar el control de acceso, escanear c&oacute;digos QR y manejar
                        entregas de paquetes en tu propiedad.
                    </p>
                    <div class="device-cred-capabilities">
                        <span><i class="bi bi-qr-code-scan"></i> Escaneo de C&oacute;digos QR</span>
                        <span><i class="bi bi-box-seam"></i> Gesti&oacute;n de Paquetes</span>
                        <span><i class="bi bi-circle"></i> Control de Acceso</span>
                    </div>
                </div>

                <div class="device-cred-section">
                    <div class="device-cred-heading">
                        <i class="bi bi-phone"></i> C&oacute;mo Funciona
                    </div>
                    <ul class="device-cred-steps">
                        <li class="device-cred-step">
                            <span class="device-cred-step-num">1</span>
                            <span>Haz clic en &lsquo;Generar Dispositivo&rsquo; para crear nuevas credenciales para un
                                miembro del personal de seguridad</span>
                        </li>
                        <li class="device-cred-step">
                            <span class="device-cred-step-num">2</span>
                            <span>El sistema genera autom&aacute;ticamente un email y contrase&ntilde;a
                                &uacute;nicos</span>
                        </li>
                        <li class="device-cred-step">
                            <span class="device-cred-step-num">3</span>
                            <span>Comparte estas credenciales de forma segura con tu miembro del personal de
                                seguridad</span>
                        </li>
                        <li class="device-cred-step">
                            <span class="device-cred-step-num">4</span>
                            <span>El personal puede iniciar sesi&oacute;n en la Aplicaci&oacute;n PWA de AxisCondo
                                usando estas credenciales</span>
                        </li>
                    </ul>
                </div>

                <div class="device-cred-section">
                    <div class="device-cred-heading">
                        <i class="bi bi-bezier2"></i> Entendiendo las Credenciales
                    </div>
                    <div class="device-cred-info-box">
                        <div class="device-cred-info-line">
                            <i class="bi bi-info-circle"></i>
                            <span>AxisCondo genera autom&aacute;ticamente credenciales de inicio de sesi&oacute;n
                                &uacute;nicas para cada dispositivo. Estas credenciales no corresponden a direcciones de
                                email reales, son identificadores virtuales creados espec&iacute;ficamente para
                                simplificar la configuraci&oacute;n de dispositivos m&oacute;viles.</span>
                        </div>
                        <div class="device-cred-info-line mt-2">
                            <i class="bi bi-info-circle d-md-none" style="opacity:0;"></i>
                            <span>Este enfoque permite a los administradores configurar r&aacute;pidamente los
                                tel&eacute;fonos del personal de seguridad sin necesidad de crear cuentas de email
                                reales o gestionar contrase&ntilde;as complejas.</span>
                        </div>
                    </div>
                </div>

                <div class="device-cred-section">
                    <div class="device-cred-heading">
                        <i class="bi bi-check2-circle text-success"></i> Mejores Pr&aacute;cticas
                    </div>
                    <ul class="device-cred-practices">
                        <li><i class="bi bi-check-circle"></i> Genera un conjunto de credenciales por
                            tel&eacute;fono/dispositivo, no por persona</li>
                        <li><i class="bi bi-check-circle"></i> Comparte las credenciales de forma segura (nunca por
                            email o canales p&uacute;blicos)</li>
                        <li><i class="bi bi-check-circle"></i> Elimina las credenciales del dispositivo inmediatamente
                            cuando un dispositivo ya no est&eacute; en uso</li>
                        <li><i class="bi bi-check-circle"></i> Revisa regularmente los dispositivos activos y elimina
                            las credenciales no utilizadas</li>
                    </ul>

                    <div class="device-cred-warning">
                        <i class="bi bi-exclamation-circle"></i>
                        <span><strong>Importante:</strong> Eliminar un dispositivo revoca inmediatamente el acceso a la
                            Aplicaci&oacute;n PWA de AxisCondo. El miembro del personal ya no podr&aacute; escanear
                            c&oacute;digos QR ni gestionar paquetes.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL: AGREGAR PERSONAL ============ -->
<div class="modal fade" id="addStaffModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold text-dark" style="font-size:1.1rem;">Agregar personal</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2">
                <form id="addStaffForm" enctype="multipart/form-data">
                    <!-- Photo Upload -->
                    <div class="text-center mb-4">
                        <label for="staff-photo-input" class="d-inline-block" role="button" style="cursor:pointer;">
                            <div id="staff-photo-preview"
                                class="mx-auto d-flex flex-column align-items-center justify-content-center"
                                style="width:100px; height:100px; border-radius:12px; border:2px dashed #cbd5e1; background:#f8fafc; overflow:hidden;">
                                <i class="bi bi-cloud-arrow-up text-muted" style="font-size:1.8rem; opacity:.6;"></i>
                                <span class="text-muted" style="font-size:0.68rem; margin-top:2px;">Subir foto del
                                    empleado</span>
                            </div>
                        </label>
                        <input type="file" id="staff-photo-input" name="photo" accept="image/*" class="d-none">
                    </div>

                    <!-- Name Fields -->
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark" style="font-size:0.85rem;">Nombre</label>
                            <input id="staff-add-first" type="text" name="first_name" class="form-control"
                                placeholder="Ingresa el nombre" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold text-dark" style="font-size:0.85rem;">Apellido</label>
                            <input id="staff-add-last" type="text" name="last_name" class="form-control"
                                placeholder="Ingresa el apellido" required>
                        </div>
                    </div>

                    <!-- Staff Type -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark" style="font-size:0.85rem;">Tipo de
                            Personal</label>
                        <select id="staff-add-type" name="staff_type" class="form-select">
                            <option value="security">Seguridad</option>
                            <option value="maintenance">Mantenimiento</option>
                            <option value="other">Otro</option>
                        </select>
                    </div>

                    <!-- Device Link (conditional, only for security) -->
                    <div id="staff-device-section" class="mb-3">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1"
                            style="font-size:0.85rem;">
                            Inicio de Sesión en App <i class="bi bi-info-circle text-muted" style="font-size:0.75rem;"
                                title="Vincular con un dispositivo de seguridad para iniciar sesión en la PWA de guardia"></i>
                        </label>
                        <select id="staff-add-device" name="device_id" class="form-select">
                            <option value="">Sin cuenta de inicio de sesión</option>
                            <?php foreach ($securityDevicesList as $sd): ?>
                                <option value="<?= esc((string) $sd['id'], 'attr') ?>"><?= esc($sd['email']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- ID Document (optional) -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold text-dark d-flex align-items-center gap-1"
                            style="font-size:0.85rem;">
                            Documento de Identidad (opcional) <i class="bi bi-info-circle text-muted"
                                style="font-size:0.75rem;"></i>
                        </label>
                        <label for="staff-id-doc-input" class="d-block" role="button" style="cursor:pointer;">
                            <div id="staff-id-doc-preview"
                                class="d-flex align-items-center justify-content-center gap-2 text-muted"
                                style="border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; background:#f8fafc; font-size:0.85rem;">
                                <i class="bi bi-cloud-arrow-up"></i> <span>Subir Documento de Identidad</span>
                            </div>
                        </label>
                        <input type="file" id="staff-id-doc-input" name="id_document" accept="image/*,.pdf"
                            class="d-none">
                    </div>

                    <div id="staff-add-error" class="text-danger mb-2" style="font-size:0.8rem; min-height:20px;"></div>
                </form>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal"
                    style="border-radius:8px; font-size:0.85rem;">Cancelar</button>
                <button type="button" id="btn-confirm-add-staff" class="btn text-white px-4"
                    style="background:#2a3547; border-radius:8px; font-size:0.85rem; font-weight:600;">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- ============ MODAL: PERFIL DEL PERSONAL ============ -->
<div class="modal fade" id="staffProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <h5 class="modal-title fw-bold text-dark" style="font-size:1.05rem;">Perfil del Personal</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-3 pb-2 text-center">
                <!-- Photo -->
                <div id="staff-profile-photo-wrap" class="mx-auto mb-3"
                    style="width:110px; height:110px; border-radius:50%; overflow:hidden; background:#f1f5f9; border:3px solid #e2e8f0; display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-person fs-1 text-muted" style="opacity:0.4;"></i>
                </div>
                <h5 id="staff-profile-name" class="fw-bold text-dark mb-1" style="font-size:1.15rem;"></h5>
                <span id="staff-profile-type-badge" class="badge rounded-pill text-white mb-4"
                    style="padding:5px 16px; font-size:0.78rem; font-weight:600;"></span>

                <!-- Info Card (view mode) -->
                <div id="staff-profile-info" class="text-start border rounded-3 p-3 mb-3" style="background:#fafbfc;">
                    <h6 class="fw-bold d-flex align-items-center gap-2 mb-3" style="font-size:0.88rem;">
                        <i class="bi bi-person-badge text-primary"></i> Información Personal
                    </h6>
                    <div class="row">
                        <div class="col-6">
                            <span class="text-muted" style="font-size:0.72rem;">Nombre</span>
                            <div id="staff-profile-first" class="fw-semibold text-dark" style="font-size:0.9rem;"></div>
                        </div>
                        <div class="col-6">
                            <span class="text-muted" style="font-size:0.72rem;">Apellido</span>
                            <div id="staff-profile-last" class="fw-semibold text-dark" style="font-size:0.9rem;"></div>
                        </div>
                    </div>
                    <div class="row mt-3" id="staff-profile-device-row" style="display:none !important;">
                        <div class="col-12">
                            <span class="text-muted" style="font-size:0.72rem;">Inicio de Sesión</span>
                            <div id="staff-profile-device-email" class="fw-semibold text-dark"
                                style="font-size:0.85rem; font-family:monospace;"></div>
                        </div>
                    </div>
                </div>

                <!-- Edit Form (hidden by default) -->
                <div id="staff-profile-edit-form" class="text-start d-none">
                    <form id="editStaffForm" enctype="multipart/form-data">
                        <input type="hidden" id="staff-edit-id" name="staff_id">
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark"
                                    style="font-size:0.85rem;">Nombre</label>
                                <input id="staff-edit-first" type="text" name="first_name" class="form-control"
                                    required>
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold text-dark"
                                    style="font-size:0.85rem;">Apellido</label>
                                <input id="staff-edit-last" type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark" style="font-size:0.85rem;">Tipo de
                                Personal</label>
                            <select id="staff-edit-type" name="staff_type" class="form-select">
                                <option value="security">Seguridad</option>
                                <option value="maintenance">Mantenimiento</option>
                                <option value="other">Otro</option>
                            </select>
                        </div>
                        <div id="staff-edit-device-section" class="mb-3">
                            <label class="form-label fw-semibold text-dark" style="font-size:0.85rem;">Inicio de Sesión
                                en App</label>
                            <select id="staff-edit-device" name="device_id" class="form-select">
                                <option value="">Sin cuenta de inicio de sesión</option>
                                <?php foreach ($securityDevicesList as $sd): ?>
                                    <option value="<?= esc((string) $sd['id'], 'attr') ?>"><?= esc($sd['email']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-dark" style="font-size:0.85rem;">Foto</label>
                            <input type="file" id="staff-edit-photo-input" name="photo" accept="image/*"
                                class="form-control form-control-sm">
                        </div>
                        <div id="staff-edit-error" class="text-danger mb-2" style="font-size:0.8rem; min-height:18px;">
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0 d-flex justify-content-between">
                <div id="staff-profile-view-actions">
                    <button type="button" id="btn-edit-staff"
                        class="btn btn-outline-warning px-3 d-flex align-items-center gap-2"
                        style="border-radius:8px; font-size:0.85rem;">
                        <i class="bi bi-pencil"></i> Editar
                    </button>
                </div>
                <div id="staff-profile-edit-actions" class="d-none gap-2" style="display:flex !important;">
                    <button type="button" id="btn-cancel-edit-staff" class="btn btn-light px-3"
                        style="border-radius:8px; font-size:0.85rem;">Cancelar</button>
                    <button type="button" id="btn-save-edit-staff" class="btn text-white px-4"
                        style="background:#2a3547; border-radius:8px; font-size:0.85rem; font-weight:600;">Guardar
                        Cambios</button>
                </div>
                <button type="button" class="btn btn-light px-3 d-flex align-items-center gap-2" data-bs-dismiss="modal"
                    style="border-radius:8px; font-size:0.85rem;">
                    <i class="bi bi-x-lg"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Dispositivo -->
<div class="modal fade" id="addDeviceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered device-mgmt-dialog">
        <div class="modal-content device-mgmt-content">
            <div class="modal-header border-0 pb-2 px-4 pt-4">
                <h5 class="modal-title device-mgmt-title"><i class="bi bi-plus-lg"></i> Agregar Dispositivo</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-1 pb-3">
                <label for="add-device-name" class="form-label fw-semibold text-dark mb-2">Nombre del
                    Dispositivo</label>
                <input id="add-device-name" type="text" class="form-control device-mgmt-input" maxlength="40"
                    placeholder="Ej: Caseta, Entrada Principal">
                <div class="device-mgmt-muted-note mt-2">Un nombre para identificar este dispositivo (2-40 caracteres).
                </div>
                <div id="add-device-error" class="text-danger small mt-2 d-none"></div>
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn device-mgmt-primary px-4" id="btn-confirm-create-device"
                    disabled>Agregar Dispositivo</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Credenciales Generadas -->
<div class="modal fade" id="deviceCredentialsGeneratedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered device-mgmt-dialog-wide">
        <div class="modal-content device-mgmt-content">
            <div class="modal-header border-0 pb-2 px-4 pt-4">
                <h5 class="modal-title device-mgmt-title" id="generated-credentials-title"><i
                        class="bi bi-shield-check"></i> Credenciales de Dispositivo Generadas</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-2">
                <div class="device-mgmt-card mb-3" style="background:#eff6ff; border-color:#dbeafe;">
                    <div class="d-flex align-items-start gap-2 text-primary-emphasis" style="font-size:0.9rem;">
                        <i class="bi bi-phone mt-1"></i>
                        <span>Estas credenciales permiten al personal de seguridad acceder a la Aplicaci&oacute;n PWA de
                            <?= esc($axisBrandView) ?>.</span>
                    </div>
                </div>

                <div class="device-mgmt-card">
                    <div class="fw-semibold mb-3 text-secondary">Credenciales de Acceso</div>

                    <div class="device-mgmt-label">Email</div>
                    <div class="device-mgmt-value mb-3">
                        <span id="generated-cred-email">-</span>
                        <button type="button" class="btn btn-sm device-mgmt-outline" id="btn-copy-generated-email"
                            title="Copiar email">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>

                    <div class="device-mgmt-label">Contrase&ntilde;a</div>
                    <div class="device-mgmt-value mb-3">
                        <span id="generated-cred-password">-</span>
                        <button type="button" class="btn btn-sm device-mgmt-outline" id="btn-copy-generated-password"
                            title="Copiar contraseña">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>

                    <button type="button" class="btn device-mgmt-outline w-100" id="btn-copy-generated-both">
                        <i class="bi bi-copy me-1"></i> Copiar Ambas Credenciales
                    </button>
                </div>

                <div class="device-mgmt-card mt-3" style="background:#f8fafc;">
                    <div class="fw-semibold text-dark mb-2">Pr&oacute;ximos Pasos</div>
                    <div class="text-secondary" style="font-size:0.9rem;">
                        Comparte estas credenciales de forma segura con tu personal. Podr&aacute;n iniciar sesi&oacute;n
                        en la Aplicaci&oacute;n PWA de <?= esc($axisBrandView) ?>.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button type="button" class="btn device-mgmt-primary w-100" data-bs-dismiss="modal"
                    id="btn-close-generated-modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle de Dispositivo -->
<div class="modal fade" id="deviceDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered device-mgmt-dialog-wide">
        <div class="modal-content device-mgmt-content">
            <div class="modal-header border-0 pb-2 px-4 pt-4">
                <h5 class="modal-title device-mgmt-title"><i class="bi bi-phone"></i> Detalles del Dispositivo</h5>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-2">
                <input type="hidden" id="device-detail-id" value="">

                <div class="device-mgmt-card mb-3">
                    <div class="d-flex justify-content-between align-items-start gap-2">
                        <div class="w-100">
                            <div class="device-mgmt-label">Nombre del Dispositivo</div>
                            <div id="device-detail-name" class="fw-semibold text-dark">-</div>
                        </div>
                        <button type="button" class="btn btn-sm device-mgmt-outline" id="btn-open-edit-device-name">
                            <i class="bi bi-pencil me-1"></i> Editar nombre
                        </button>
                    </div>

                    <div id="device-edit-name-wrap" class="mt-3 d-none">
                        <input id="device-edit-name-input" type="text" class="form-control device-mgmt-input"
                            maxlength="40">
                        <div id="device-edit-name-error" class="text-danger small mt-2 d-none"></div>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" class="btn device-mgmt-primary btn-sm px-3"
                                id="btn-save-device-name">Guardar</button>
                            <button type="button" class="btn btn-light btn-sm px-3"
                                id="btn-cancel-device-name">Cancelar</button>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-1 text-muted mt-3" style="font-size:0.78rem;">
                        <i class="bi bi-calendar3"></i> Creado: <span id="device-detail-created">-</span>
                    </div>
                </div>

                <div class="device-mgmt-card mb-3">
                    <div class="fw-semibold mb-3 text-secondary">Credenciales de Acceso</div>
                    <div class="device-mgmt-label">Email</div>
                    <div class="device-mgmt-value mb-3">
                        <span id="device-detail-email">-</span>
                        <button type="button" class="btn btn-sm device-mgmt-outline" id="btn-copy-detail-email"
                            title="Copiar email">
                            <i class="bi bi-copy"></i>
                        </button>
                    </div>
                    <button type="button" class="btn device-mgmt-outline w-100" id="btn-reset-device-password">
                        <i class="bi bi-key me-1"></i> Restablecer Contrase&ntilde;a
                    </button>
                    <div class="device-mgmt-muted-note mt-2">La contrase&ntilde;a solo se muestra una vez al crear o
                        restablecer.</div>
                </div>

                <div class="device-mgmt-card" style="background:#eff6ff; border-color:#dbeafe;">
                    <div class="text-secondary" style="font-size:0.85rem;">
                        Usa estas credenciales para iniciar sesi&oacute;n en la Aplicaci&oacute;n PWA de
                        <?= esc($axisBrandView) ?>.
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-2">
                <button type="button" class="btn device-mgmt-primary w-100" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle QR Premium -->
<div class="modal fade" id="qrDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content qr-detail-modal-content" style="background-color: #ffffff;">
            <div class="modal-header border-0 pb-0 px-4 pt-4 pb-2" style="position: relative;">
                <h5 class="modal-title font-sans-body text-dark mb-0"
                    style="font-size: 1.1rem; letter-spacing: -0.3px;">Detalles del Código QR</h5>
                <div class="d-flex align-items-center ms-auto" style="gap: 15px;">
                    <span id="qr-modal-status-badge"></span>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>
            <div class="modal-body px-0 pb-4 text-center">
                <div id="qr-modal-body-content" class="w-100 px-4 pb-2 text-start">
                    <!-- Dinámico con JS -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalle Entrada / Salida -->
<div class="modal fade" id="accessDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered access-detail-dialog">
        <div class="modal-content access-detail-modal-content">
            <div class="access-detail-header">
                <div>
                    <h5 class="mb-1 fw-bold text-dark" style="letter-spacing:-0.02em;">Detalle de visita</h5>
                    <div class="text-secondary" style="font-size:0.85rem;">
                        Registro <span id="access-detail-entry-id" class="fw-semibold text-dark">#-</span>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span id="access-detail-badge" class="access-detail-badge adentro">Adentro</span>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>

            <div class="access-detail-scroll">
                <div class="access-detail-grid">
                    <div class="access-detail-card">
                        <div class="label">Visitante</div>
                        <div class="value" id="access-detail-visitor">-</div>
                    </div>
                    <div class="access-detail-card">
                        <div class="label">Tipo de visita</div>
                        <div class="value" id="access-detail-purpose">-</div>
                    </div>
                    <div class="access-detail-card">
                        <div class="label">Unidad</div>
                        <div class="value" id="access-detail-unit">-</div>
                    </div>
                    <div class="access-detail-card">
                        <div class="label">Vehiculo</div>
                        <div class="value" id="access-detail-vehicle">-</div>
                    </div>

                    <div class="access-detail-card">
                        <div class="label">Entrada</div>
                        <div class="value" id="access-detail-entry-time">-</div>
                    </div>
                    <div class="access-detail-card">
                        <div class="label">Salida</div>
                        <div class="value" id="access-detail-exit-time">-</div>
                    </div>
                </div>

                <div class="access-detail-card mt-3">
                    <div class="label">Notas</div>
                    <div class="value" id="access-detail-notes">Sin notas registradas.</div>
                </div>

                <div class="access-photos-grid">
                    <div class="access-photo-card d-none" id="access-photo-id-card">
                        <div class="access-photo-title">
                            <span>Identificacion</span>
                            <a id="access-photo-id-link" class="access-photo-link" href="#" target="_blank"
                                rel="noopener">Abrir</a>
                        </div>
                        <img id="access-photo-id-img" alt="Foto de identificacion" loading="lazy">
                    </div>

                    <div class="access-photo-card d-none" id="access-photo-plate-card">
                        <div class="access-photo-title">
                            <span>Placas</span>
                            <a id="access-photo-plate-link" class="access-photo-link" href="#" target="_blank"
                                rel="noopener">Abrir</a>
                        </div>
                        <img id="access-photo-plate-img" alt="Foto de placas" loading="lazy">
                    </div>

                    <div class="access-photo-card d-none" id="access-photo-exit-card">
                        <div class="access-photo-title">
                            <span>Evidencia de Salida</span>
                            <a id="access-photo-exit-link" class="access-photo-link" href="#" target="_blank"
                                rel="noopener" style="color:#ef4444;">Abrir</a>
                        </div>
                        <img id="access-photo-exit-img" alt="Foto de evidencia de salida" loading="lazy">
                    </div>
                </div>

                <div class="access-no-photo d-none" id="access-no-photo-message">
                    No hay fotografias registradas para esta visita.
                </div>
            </div>

            <div class="access-detail-sticky">
                <button type="button" class="access-primary-btn" data-bs-dismiss="modal">
                    Cerrar detalle
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nuevo QR -->
<div class="modal fade" id="newQrModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow rounded-4" style="background-color: #fbfcfd;">
            <div class="modal-header border-0 pb-1 px-4 pt-4">
                <h5 class="modal-title fw-bold text-dark">Nuevo QR</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4">

                <!-- Tipo de visita -->
                <h6 class="text-dark fw-medium mb-3" style="font-size:0.95rem;">Tipo de visita</h6>
                <div class="row g-2 mb-4">
                    <div class="col-4 col-md-3">
                        <input type="radio" name="qr_visit_type" id="vtype_family" value="Familia" class="scard-radio"
                            checked>
                        <label for="vtype_family" class="scard-label"><i class="bi bi-people"></i> Familia</label>
                    </div>
                    <div class="col-4 col-md-3">
                        <input type="radio" name="qr_visit_type" id="vtype_friend" value="Amigo" class="scard-radio">
                        <label for="vtype_friend" class="scard-label"><i class="bi bi-person"></i> Amigo</label>
                    </div>
                    <div class="col-4 col-md-3">
                        <input type="radio" name="qr_visit_type" id="vtype_food" value="Entrega de comida"
                            class="scard-radio">
                        <label for="vtype_food" class="scard-label"><i class="bi bi-box2-heart"></i> Entrega de
                            comida</label>
                    </div>
                    <div class="col-4 col-md-3">
                        <input type="radio" name="qr_visit_type" id="vtype_delivery" value="Entrega a domicilio"
                            class="scard-radio">
                        <label for="vtype_delivery" class="scard-label"><i class="bi bi-box"></i> Entrega a
                            domicilio</label>
                    </div>
                    <div class="col-4 col-md-3">
                        <input type="radio" name="qr_visit_type" id="vtype_service" value="Proveedor de servicios"
                            class="scard-radio">
                        <label for="vtype_service" class="scard-label"><i class="bi bi-tools"></i> Proveedor de
                            servicios</label>
                    </div>
                    <div class="col-4 col-md-3">
                        <input type="radio" name="qr_visit_type" id="vtype_party" value="Fiesta" class="scard-radio">
                        <label for="vtype_party" class="scard-label"><i class="bi bi-stars"></i> Fiesta</label>
                    </div>
                    <div class="col-4 col-md-3">
                        <input type="radio" name="qr_visit_type" id="vtype_employee" value="Empleado"
                            class="scard-radio">
                        <label for="vtype_employee" class="scard-label"><i class="bi bi-person-badge"></i>
                            Empleado</label>
                    </div>
                    <div class="col-4 col-md-3">
                        <input type="radio" name="qr_visit_type" id="vtype_other" value="Otro" class="scard-radio">
                        <label for="vtype_other" class="scard-label"><i class="bi bi-three-dots"></i> Otro</label>
                    </div>
                </div>

                <!-- Vehículo -->
                <h6 class="text-dark fw-medium mb-3" style="font-size:0.95rem;">Vehículo</h6>
                <div class="row g-2 mb-4">
                    <div class="col-4">
                        <input type="radio" name="qr_vehicle" id="vveh_none" value="Sin vehículo" class="scard-radio"
                            checked onchange="toggleVehicleField()">
                        <label for="vveh_none" class="scard-label"><i class="bi bi-person-walking"></i> Sin
                            vehículo</label>
                    </div>
                    <div class="col-4">
                        <input type="radio" name="qr_vehicle" id="vveh_auto" value="Auto" class="scard-radio"
                            onchange="toggleVehicleField()">
                        <label for="vveh_auto" class="scard-label"><i class="bi bi-car-front"></i> Auto</label>
                    </div>
                    <div class="col-4">
                        <input type="radio" name="qr_vehicle" id="vveh_moto" value="Motocicleta" class="scard-radio"
                            onchange="toggleVehicleField()">
                        <label for="vveh_moto" class="scard-label"><i class="bi bi-bicycle"></i> Motocicleta</label>
                    </div>
                </div>

                <!-- Tipo de QR -->
                <h6 class="text-dark fw-medium mb-3" style="font-size:0.95rem;">Tipo de QR</h6>
                <div class="row g-2 mb-4">
                    <div class="col-6">
                        <input type="radio" name="qr_time_type" id="vtime_single" value="Una entrada"
                            class="scard-radio" checked onchange="toggleqrTimeField()">
                        <label for="vtime_single" class="scard-label padding-y-lg" style="padding: 24px 8px;"><i
                                class="bi bi-clock"></i> Una entrada</label>
                    </div>
                    <div class="col-6">
                        <input type="radio" name="qr_time_type" id="vtime_range" value="QR temporal" class="scard-radio"
                            onchange="toggleqrTimeField()">
                        <label for="vtime_range" class="scard-label padding-y-lg" style="padding: 24px 8px;"><i
                                class="bi bi-calendar-event"></i> QR temporal</label>
                    </div>
                </div>

                <!-- Detalles del Visitante -->
                <h6 class="text-dark fw-medium mb-3" style="font-size:0.95rem;">Detalles del Visitante</h6>

                <!-- Date Row (Dynamic) -->
                <div class="row g-3 mb-3" id="qr_dates_row">
                    <!-- Default state inside Single Entry, injected via JS -->
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-12">
                        <label class="form-label small fw-medium text-dark">Nombre del visitante</label>
                        <div class="icon-input-wrapper">
                            <i class="bi bi-person" style="z-index: 10;"></i>
                            <input type="text" class="form-control form-control-custom bg-white"
                                placeholder="Ingrese el nombre" style="border-radius: 6px;">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-medium text-dark">Unidad <span
                                class="text-muted fw-normal">(opcional)</span></label>
                        <!-- Wrapper para simular el ícono si TomSelect lo pisa, o simplemente custom form styling -->
                        <div style="position:relative;">
                            <select id="select-unidad" class="form-select form-select-custom bg-white"
                                placeholder="Sin unidad">
                                <option value="">Sin unidad</option>
                                <?php if (!empty($units)): ?>
                                    <?php foreach ($units as $u): ?>
                                        <option value="<?= esc($u['id']) ?>"><?= esc($u['unit_number']) ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6" id="qr_plate_wrapper" style="display: none;">
                        <label class="form-label small fw-medium text-dark">Placa del vehículo <span
                                class="text-muted fw-normal">(opcional)</span></label>
                        <div class="icon-input-wrapper">
                            <i class="bi bi-credit-card-2-front" style="z-index: 10;"></i>
                            <input type="text" id="vehiculo_placa" class="form-control form-control-custom bg-white"
                                placeholder="N/A" style="border-radius: 6px;">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light bg-white border px-4"
                    data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="btn-save-qr" class="btn text-white px-4"
                    style="background-color: #3b4d63; font-weight: 500;">Generar QR</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts Premium -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

<script>
    let fpStart, fpEnd;

    // Tom Select initialization
    new TomSelect("#select-unidad", {
        create: false,
        sortField: { field: "text", direction: "asc" }
    });

    // Visibility toggles
    function toggleVehicleField() {
        const isAuto = document.getElementById('vveh_auto').checked;
        const isMoto = document.getElementById('vveh_moto').checked;
        const plateWrapper = document.getElementById('qr_plate_wrapper');

        if (isAuto || isMoto) {
            plateWrapper.style.display = 'block';
        } else {
            plateWrapper.style.display = 'none';
        }
    }

    function toggleqrTimeField() {
        const isTemporal = document.getElementById('vtime_range').checked;
        const datesRow = document.getElementById('qr_dates_row');

        if (isTemporal) {
            datesRow.innerHTML = `
                <div class="col-md-6">
                    <label class="form-label small fw-medium text-dark">Fecha inicio</label>
                    <div class="icon-input-wrapper">
                        <i class="bi bi-calendar" style="z-index:10"></i>
                        <input type="text" id="f_start" class="form-control form-control-custom bg-white" placeholder="Selecciona..." style="border-radius: 6px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium text-dark">Fecha fin</label>
                    <div class="icon-input-wrapper">
                        <i class="bi bi-calendar" style="z-index:10"></i>
                        <input type="text" id="f_end" class="form-control form-control-custom bg-white" placeholder="Selecciona..." style="border-radius: 6px;">
                    </div>
                </div>
            `;
            // Initialize flatpickr on the new inputs
            fpStart = flatpickr("#f_start", { locale: "es", altInput: true, altFormat: "d \\d\\e F \\d\\e Y", dateFormat: "Y-m-d", defaultDate: new Date() });
            let tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            fpEnd = flatpickr("#f_end", { locale: "es", altInput: true, altFormat: "d \\d\\e F \\d\\e Y", dateFormat: "Y-m-d", defaultDate: tomorrow });
        } else {
            datesRow.innerHTML = `
                <div class="col-md-12">
                    <label class="form-label small fw-medium text-dark">Fecha de entrada</label>
                    <div class="icon-input-wrapper">
                        <i class="bi bi-calendar" style="z-index:10"></i>
                        <input type="text" id="f_single" class="form-control form-control-custom bg-white" placeholder="Selecciona..." style="border-radius: 6px;">
                    </div>
                </div>
            `;
            fpStart = flatpickr("#f_single", { locale: "es", altInput: true, altFormat: "d \\d\\e F \\d\\e Y", dateFormat: "Y-m-d", defaultDate: new Date() });
        }
    }

    // Modal Events to bind the dynamically generated QR button
    const qrModalObj = document.getElementById('newQrModal');
    if (qrModalObj) {
        qrModalObj.addEventListener('show.bs.modal', function () {
            toggleVehicleField();
            toggleqrTimeField();
        });
    }

    // Abrir modal QR desde header y empty-state
    function openNewQrModal() {
        const modalEl = document.getElementById('newQrModal');
        if (!modalEl || !window.bootstrap || !window.bootstrap.Modal) return;
        const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    document.querySelectorAll('[data-open-qr-modal="1"]').forEach((btn) => {
        btn.addEventListener('click', openNewQrModal);
    });

    // Handle AJAX Submission
    document.getElementById('btn-save-qr').addEventListener('click', function () {
        const visitType = document.querySelector('input[name="qr_visit_type"]:checked').value;
        const vehicle = document.querySelector('input[name="qr_vehicle"]:checked').value;
        const timeType = document.querySelector('input[name="qr_time_type"]:checked').value;

        let validFrom = '';
        let validUntil = '';
        if (timeType === 'Una entrada') {
            validFrom = document.getElementById('f_single').value;
        } else {
            validFrom = document.getElementById('f_start').value;
            validUntil = document.getElementById('f_end').value;
        }

        const visitorName = document.querySelector('input[placeholder="Ingrese el nombre"]').value;
        const unitId = document.getElementById('select-unidad').value;
        const vehiclePlate = document.getElementById('vehiculo_placa').value;

        if (!visitorName || !validFrom) {
            Swal.fire('Atención', 'Nombre del visitante y fecha son obligatorios', 'warning');
            return;
        }

        // Bloquear boton
        const btnSave = this;
        const originalText = btnSave.innerHTML;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generando...';
        btnSave.disabled = true;

        const formData = new FormData();
        formData.append('qr_visit_type', visitType); // Mapped to visit_type in controller? No wait!
        formData.append('visit_type', visitType);
        formData.append('vehicle_type', vehicle);
        formData.append('qr_time_type', timeType);
        formData.append('valid_from', validFrom);
        formData.append('valid_until', validUntil);
        formData.append('visitor_name', visitorName);
        formData.append('unit_id', unitId);
        formData.append('vehicle_plate', vehiclePlate);

        fetch('<?= base_url('admin/seguridad/generar-qr') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;

                if (data.status === 201) {
                    bootstrap.Modal.getInstance(document.getElementById('newQrModal')).hide();

                    // Limpiar formulario
                    document.querySelector('input[placeholder="Ingrese el nombre"]').value = '';
                    document.getElementById('vehiculo_placa').value = '';

                    // Show premium Toast notification
                    showQrToast('success', 'QR Generado exitosamente', 'El acceso ha sido registrado y el pase virtual creado.', data.url);

                    // Reload page with QR tab active after a short delay
                    setTimeout(function () {
                        var currentUrl = new URL(window.location.href);
                        currentUrl.hash = 'v-qr';
                        window.location.href = currentUrl.toString();
                        window.location.reload();
                    }, 2000);

                } else {
                    showQrToast('error', 'Error al generar', data.message || 'No se pudo crear el código QR.');
                }
            })
            .catch(error => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;
                showQrToast('error', 'Error de red', 'Problema de conexión, intente de nuevo.');
            });
    });

    // Filtros de Data Table QR (Frontend Side)
    function applyQrFilters() {
        const searchVal = document.getElementById('qr-search-input').value.toLowerCase();
        const typeVal = document.getElementById('qr-filter-type').value;
        const purposeVal = document.getElementById('qr-filter-purpose').value;
        const vehicleVal = document.getElementById('qr-filter-vehicle').value;
        const statusVal = document.getElementById('qr-filter-status').value;

        const rows = document.querySelectorAll('.table-qr-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowSearch = row.getAttribute('data-search');
            const rowType = row.getAttribute('data-type');
            const rowPurpose = row.getAttribute('data-purpose');
            const rowVehicle = row.getAttribute('data-vehicle');
            const rowStatus = row.getAttribute('data-status'); // "Activo" o "Expirado"

            let match = true;
            if (searchVal && !rowSearch.includes(searchVal)) match = false;
            if (typeVal !== 'Todos' && rowType !== typeVal) match = false;
            if (purposeVal !== 'Todos' && rowPurpose !== purposeVal) match = false;
            if (vehicleVal !== 'Todos' && rowVehicle !== vehicleVal) match = false;

            if (statusVal !== 'Todos') {
                if (statusVal === 'Activos' && rowStatus !== 'Activo') match = false;
                if (statusVal === 'Expirados' && rowStatus !== 'Expirado') match = false;
            }

            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        const emptyState = document.getElementById('qr-empty-state');
        const dataTable = document.getElementById('qrDataTable');
        if (dataTable) {
            if (visibleCount === 0) {
                dataTable.closest('.table-responsive').classList.add('d-none');
                if (emptyState) emptyState.classList.remove('d-none');
            } else {
                dataTable.closest('.table-responsive').classList.remove('d-none');
                if (emptyState) emptyState.classList.add('d-none');
            }
        }
    }

    if (document.getElementById('qr-search-input')) {
        document.getElementById('qr-search-input').addEventListener('input', applyQrFilters);
        document.getElementById('qr-filter-type').addEventListener('change', applyQrFilters);
        document.getElementById('qr-filter-purpose').addEventListener('change', applyQrFilters);
        document.getElementById('qr-filter-vehicle').addEventListener('change', applyQrFilters);
        document.getElementById('qr-filter-status').addEventListener('change', applyQrFilters);
    }

    // QR Detail Modal Trigger
    window.openQrDetail = function (rowElement) {
        const data = JSON.parse(rowElement.getAttribute('data-json'));
        const modalEl = document.getElementById('qrDetailModal');
        const modalContent = document.getElementById('qr-modal-body-content');

        // Formateo de fechas para que diga "20 de marzo de 2026"
        const meses = { 'Jan': 'enero', 'Feb': 'febrero', 'Mar': 'marzo', 'Apr': 'abril', 'May': 'mayo', 'Jun': 'junio', 'Jul': 'julio', 'Aug': 'agosto', 'Sep': 'septiembre', 'Oct': 'octubre', 'Nov': 'noviembre', 'Dec': 'diciembre' };

        let dateFromFormatted = data.valid_from.slice(0, -6).replace(/ (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) /, (match, p1) => ` de ${meses[p1]} de `);
        let timeFrom = data.valid_from.slice(-5);
        let validFromFull = `${dateFromFormatted} · ${timeFrom}`;

        let dateUntilFormatted = data.valid_until.slice(0, -6).replace(/ (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) /, (match, p1) => ` de ${meses[p1]} de `);
        let timeUntil = data.valid_until.slice(-5);
        let validUntilFull = `${dateUntilFormatted} · ${timeUntil}`;

        let statusBadge = data.status === 'Activo' ? '<span class="qr-badge-active">Activo</span>' : '<span class="qr-badge-expired">Expirado</span>';
        document.getElementById('qr-modal-status-badge').innerHTML = statusBadge;

        const html = `
            <!-- Wrapper para Exportar a Imagen -->
            <div id="qr-export-wrapper" style="background-color: #ffffff; width: 100%; max-width: 420px; margin: 0 auto; position: relative; font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                
                <!-- TOP BANNER AxisCondo -->
                <div style="background-color: #1e3a5f; color: #ffffff; text-align: center; padding: 10px 0; font-weight: 800; font-size: 0.85rem; letter-spacing: 1.5px; text-transform: uppercase;">
                    AXISCONDO
                </div>
                
                <!-- Cuerpo de la Tarjeta -->
                <div style="padding: 24px 28px;">
                    
                    <!-- Header Secundario -->
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 46px; height: 46px; border-radius: 50%; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; margin-right: 14px;">
                            <i class="bi bi-buildings" style="font-size: 1.4rem; color: #1e3a5f;"></i>
                        </div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px;">Acceso Autorizado</div>
                    </div>

                    <!-- Código QR Centrado -->
                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin-bottom: 28px; text-align: center; background: #ffffff;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(data.token)}" alt="QR Code" crossorigin="anonymous" class="img-fluid" style="max-width: 250px; width: 100%;">
                    </div>

                    <!-- Datos Estructurados -->
                    <div style="text-align: left;">
                         <!-- Botonera Externa al Canvas -->
            <div class="px-4 pb-4 pt-3 mx-auto" style="max-width: 420px;">
                <button class="btn w-100 fw-medium bg-white mb-3" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; color: #1e3a5f; font-size: 0.95rem;" onclick="downloadQrCard('${data.token}', '${data.visitor_name}')">
                    <i class="bi bi-download me-2" style="color: #64748b; font-size: 1.1rem; vertical-align: text-bottom;"></i> Descargar Código QR
                </button>
              
            </div>
                        <!-- PROPIETARIO -->
                        <div style="font-size: 1.05rem; font-weight: 800; color: #334155; margin-bottom: 12px; text-transform: uppercase;">PROPIETARIO</div>
                        <div style="margin-bottom: 6px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">🏡</span> Unidad ${data.unit_number}
                        </div>
                        <div style="margin-bottom: 24px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">👤</span> Visitante: ${data.visitor_name}
                        </div>

                        <!-- EVENTO -->
                        <div style="font-size: 1.05rem; font-weight: 800; color: #334155; margin-bottom: 12px; text-transform: uppercase;">EVENTO</div>
                        <div style="margin-bottom: 6px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">🎉</span> ${data.visit_type}
                        </div>
                        <div style="margin-bottom: 24px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">🚗</span> Vehículo: ${data.vehicle_type}
                        </div>

                        <!-- FECHAS DE ACCESO -->
                        <div style="font-size: 1.05rem; font-weight: 800; color: #334155; margin-bottom: 12px; text-transform: uppercase;">FECHAS DE ACCESO</div>
                        <div style="margin-bottom: 6px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">📅</span> Entrada: ${validFromFull}
                        </div>
                        <div style="margin-bottom: 6px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">📅</span> Salida: ${validUntilFull}
                        </div>
                        <div style="margin-bottom: 12px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">🎫</span> Tipo: ${data.time_type}
                        </div>
                    </div>
                </div>
            </div>
              <div class="text-end">
                    <button type="button" class="btn btn-light border px-4 py-2 font-sans" data-bs-dismiss="modal" style="border-radius: 8px; color:#475569; border-color:#e2e8f0; font-weight: 500; font-size: 0.85rem;"><i class="bi bi-x"></i> Cerrar</button>
                </div>
           
        `;

        modalContent.innerHTML = html;
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    };

    window.downloadQrCard = function (token, name) {
        const element = document.getElementById('qr-export-wrapper');

        // Bloquear boton momentaneamente
        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generando...';
        btn.disabled = true;

        html2canvas(element, { backgroundColor: '#f8fafc', scale: 2, useCORS: true, allowTaint: false }).then(canvas => {
            const link = document.createElement('a');
            link.download = `QR_Acceso_${name.replace(/\s+/g, '_')}.png`;
            link.href = canvas.toDataURL("image/png");
            link.click();

            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }).catch(err => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            Swal.fire('Error', 'No se pudo generar la imagen.', 'error');
        });
    };

    // ==========================================
    // Filtros de Entradas / Salidas
    // ==========================================
    function applyAccessFilters() {
        const searchVal = document.getElementById('acc-search-input').value.toLowerCase();
        const purposeVal = document.getElementById('acc-filter-purpose').value;
        const statusVal = document.getElementById('acc-filter-status').value;
        const vehicleVal = document.getElementById('acc-filter-vehicle').value;

        const rows = document.querySelectorAll('.table-access-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowSearch = row.getAttribute('data-search');
            let rowPurpose = row.getAttribute('data-purpose');
            const rowStatus = row.getAttribute('data-status');
            let rowVehicle = row.getAttribute('data-vehicle');

            // Normalize generic matching for the dropdown 
            if (rowPurpose === '' || rowPurpose === null) rowPurpose = 'Visita';
            if (rowVehicle === '' || rowVehicle === null) rowVehicle = 'Sin vehículo';

            let match = true;
            if (searchVal && !rowSearch.includes(searchVal)) match = false;
            if (purposeVal !== 'Todos' && !rowPurpose.includes(purposeVal)) {
                // Allow "Proveedor/Servicio" to match "Proveedor de servicios"
                if (purposeVal === 'Proveedor de servicios' && rowPurpose !== 'Proveedor de servicios') match = false;
                else if (purposeVal !== 'Proveedor de servicios' && rowPurpose !== purposeVal) match = false;
            }
            if (statusVal !== 'Todos' && rowStatus !== statusVal) match = false;
            if (vehicleVal !== 'Todos' && rowVehicle !== vehicleVal) match = false;

            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        // Esconder tabla si no hay resultados visuales (opcional, dejamos solo vacia la tabla visualmente)
    }

    if (document.getElementById('acc-search-input')) {
        document.getElementById('acc-search-input').addEventListener('input', applyAccessFilters);
        document.getElementById('acc-filter-purpose').addEventListener('change', applyAccessFilters);
        document.getElementById('acc-filter-status').addEventListener('change', applyAccessFilters);
        document.getElementById('acc-filter-vehicle').addEventListener('change', applyAccessFilters);
    }

    // ==========================================
    // Modal detalle para Entradas / Salidas
    // ==========================================
    const accessDetailModalEl = document.getElementById('accessDetailModal');
    const accessDetailModal = (accessDetailModalEl && window.bootstrap && window.bootstrap.Modal)
        ? new window.bootstrap.Modal(accessDetailModalEl)
        : null;
    const accessBaseUrl = '<?= rtrim(base_url(), '/') ?>';

    function resolveAccessMediaUrl(rawPath) {
        if (!rawPath) return '';
        const normalized = String(rawPath).trim();
        if (!normalized) return '';

        if (/^https?:\/\//i.test(normalized)) return normalized;
        if (normalized.includes('/api/v1/security/photo/')) {
            return `${accessBaseUrl}/${normalized.replace(/^\/+/, '')}`;
        }

        const fileName = normalized.split('/').pop().split('\\').pop();
        if (!fileName) return '';
        return `${accessBaseUrl}/api/v1/security/photo/${encodeURIComponent(fileName)}`;
    }

    function setAccessText(id, value, fallback = '-') {
        const el = document.getElementById(id);
        if (!el) return;
        const normalized = String(value ?? '').trim();
        el.textContent = normalized !== '' ? normalized : fallback;
    }

    function setAccessPhoto(cardId, imgId, linkId, rawPath) {
        const card = document.getElementById(cardId);
        const img = document.getElementById(imgId);
        const link = document.getElementById(linkId);
        if (!card || !img || !link) return false;

        const mediaUrl = resolveAccessMediaUrl(rawPath);
        if (!mediaUrl) {
            card.classList.add('d-none');
            img.removeAttribute('src');
            link.removeAttribute('href');
            return false;
        }

        card.classList.remove('d-none');
        img.src = mediaUrl;
        link.href = mediaUrl;
        img.onerror = () => {
            card.classList.add('d-none');
        };
        return true;
    }

    function openAccessDetailModal(row) {
        if (!accessDetailModal || !row) return;

        const isInside = row.dataset.status === 'adentro';
        const badge = document.getElementById('access-detail-badge');
        if (badge) {
            badge.className = `access-detail-badge ${isInside ? 'adentro' : 'salio'}`;
            badge.textContent = isInside ? 'Actualmente adentro' : 'Salida registrada';
        }

        setAccessText('access-detail-entry-id', `#${row.dataset.entryId || '-'}`);
        setAccessText('access-detail-visitor', row.dataset.visitor || '-');
        setAccessText('access-detail-purpose', row.dataset.purpose || 'Visita');
        setAccessText('access-detail-unit', row.dataset.unit || 'N/A');
        setAccessText('access-detail-vehicle', row.dataset.vehicle || 'Sin vehiculo');
        setAccessText('access-detail-plate', row.dataset.plate || '-');
        setAccessText('access-detail-gate', row.dataset.gate || 'Caseta Principal');

        const entryDate = row.dataset.entryDate || '-';
        const entryTime = row.dataset.entryTime || '-';
        setAccessText('access-detail-entry-time', `${entryDate} · ${entryTime}`);

        const exitTime = row.dataset.exitTime && row.dataset.exitTime !== 'Active'
            ? row.dataset.exitTime
            : 'Sin salida registrada';
        setAccessText('access-detail-exit-time', exitTime);

        const notes = row.dataset.notes || '';
        setAccessText('access-detail-notes', notes, 'Sin notas registradas.');

        const hasIdPhoto = setAccessPhoto('access-photo-id-card', 'access-photo-id-img', 'access-photo-id-link', row.dataset.photoId);
        const hasPlatePhoto = setAccessPhoto('access-photo-plate-card', 'access-photo-plate-img', 'access-photo-plate-link', row.dataset.photoPlate);
        const hasExitPhoto = setAccessPhoto('access-photo-exit-card', 'access-photo-exit-img', 'access-photo-exit-link', row.dataset.photoExit);
        const noPhoto = document.getElementById('access-no-photo-message');
        if (noPhoto) {
            noPhoto.classList.toggle('d-none', hasIdPhoto || hasPlatePhoto || hasExitPhoto);
        }

        accessDetailModal.show();
    }

    document.querySelectorAll('.table-access-row').forEach((row) => {
        row.addEventListener('click', () => openAccessDetailModal(row));
        row.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openAccessDetailModal(row);
            }
        });
    });

    if (accessDetailModalEl) {
        accessDetailModalEl.addEventListener('shown.bs.modal', () => {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            const lastBackdrop = backdrops[backdrops.length - 1];
            if (lastBackdrop) {
                lastBackdrop.classList.add('access-detail-backdrop');
            }
        });

        accessDetailModalEl.addEventListener('hidden.bs.modal', () => {
            document.querySelectorAll('.modal-backdrop.access-detail-backdrop').forEach((el) => {
                el.classList.remove('access-detail-backdrop');
            });
        });
    }

    // ==========================================
    // Flatpickr para Rango de Fechas
    // ==========================================
</script>

<script>
    (() => {
        if (window.__accessDetailSafeInit) return;
        window.__accessDetailSafeInit = true;

        const modalEl = document.getElementById('accessDetailModal');
        if (!modalEl) return;

        const accessBaseUrl = '<?= rtrim(base_url(), '/') ?>';
        let manualBackdrop = null;

        const byId = (id) => document.getElementById(id);
        const text = (id, value, fallback = '-') => {
            const el = byId(id);
            if (!el) return;
            const normalized = String(value ?? '').trim();
            el.textContent = normalized !== '' ? normalized : fallback;
        };

        const mediaUrl = (rawPath) => {
            if (!rawPath) return '';
            const normalized = String(rawPath).trim();
            if (!normalized) return '';

            if (/^https?:\/\//i.test(normalized)) return normalized;
            if (normalized.includes('/api/v1/security/photo/')) {
                return `${accessBaseUrl}/${normalized.replace(/^\/+/, '')}`;
            }

            const fileName = normalized.split('/').pop().split('\\').pop();
            if (!fileName) return '';
            return `${accessBaseUrl}/api/v1/security/photo/${encodeURIComponent(fileName)}`;
        };

        const setPhoto = (cardId, imgId, linkId, rawPath) => {
            const card = byId(cardId);
            const img = byId(imgId);
            const link = byId(linkId);
            if (!card || !img || !link) return false;

            const url = mediaUrl(rawPath);
            if (!url) {
                card.classList.add('d-none');
                img.removeAttribute('src');
                link.removeAttribute('href');
                return false;
            }

            card.classList.remove('d-none');
            img.src = url;
            link.href = url;
            img.onerror = () => card.classList.add('d-none');
            return true;
        };

        const getBootstrapModal = () => {
            if (window.bootstrap && window.bootstrap.Modal) {
                return window.bootstrap.Modal.getOrCreateInstance(modalEl);
            }
            return null;
        };

        const showManualModal = () => {
            modalEl.style.display = 'block';
            modalEl.classList.add('show');
            modalEl.setAttribute('aria-modal', 'true');
            modalEl.removeAttribute('aria-hidden');
            document.body.classList.add('modal-open');

            if (!manualBackdrop) {
                manualBackdrop = document.createElement('div');
                manualBackdrop.className = 'modal-backdrop fade show access-detail-backdrop';
                document.body.appendChild(manualBackdrop);
            }
        };

        const hideManualModal = () => {
            modalEl.classList.remove('show');
            modalEl.style.display = 'none';
            modalEl.removeAttribute('aria-modal');
            modalEl.setAttribute('aria-hidden', 'true');
            document.body.classList.remove('modal-open');

            if (manualBackdrop && manualBackdrop.parentNode) {
                manualBackdrop.parentNode.removeChild(manualBackdrop);
            }
            manualBackdrop = null;
        };

        const openDetail = (row) => {
            if (!row) return;

            const isInside = row.dataset.status === 'adentro';
            const badge = byId('access-detail-badge');
            if (badge) {
                badge.className = `access-detail-badge ${isInside ? 'adentro' : 'salio'}`;
                badge.textContent = isInside ? 'Actualmente adentro' : 'Salida registrada';
            }

            text('access-detail-entry-id', `#${row.dataset.entryId || '-'}`);
            text('access-detail-visitor', row.dataset.visitor || '-');
            text('access-detail-purpose', row.dataset.purpose || 'Visita');
            text('access-detail-unit', row.dataset.unit || 'N/A');
            text('access-detail-vehicle', row.dataset.vehicle || 'Sin vehiculo');
            text('access-detail-plate', row.dataset.plate || '-');
            text('access-detail-gate', row.dataset.gate || 'Caseta Principal');

            const entryDate = row.dataset.entryDate || '-';
            const entryTime = row.dataset.entryTime || '-';
            text('access-detail-entry-time', `${entryDate} - ${entryTime}`);

            const exitTime = row.dataset.exitTime && row.dataset.exitTime !== 'Active'
                ? row.dataset.exitTime
                : 'Sin salida registrada';
            text('access-detail-exit-time', exitTime);

            text('access-detail-notes', row.dataset.notes || '', 'Sin notas registradas.');

            const hasIdPhoto = setPhoto('access-photo-id-card', 'access-photo-id-img', 'access-photo-id-link', row.dataset.photoId);
            const hasPlatePhoto = setPhoto('access-photo-plate-card', 'access-photo-plate-img', 'access-photo-plate-link', row.dataset.photoPlate);
            const hasExitPhoto = setPhoto('access-photo-exit-card', 'access-photo-exit-img', 'access-photo-exit-link', row.dataset.photoExit);
            const noPhoto = byId('access-no-photo-message');
            if (noPhoto) noPhoto.classList.toggle('d-none', hasIdPhoto || hasPlatePhoto || hasExitPhoto);

            const modal = getBootstrapModal();
            if (modal) {
                modal.show();
            } else {
                showManualModal();
            }
        };

        document.addEventListener('click', (event) => {
            const row = event.target.closest('.table-access-row');
            if (!row) return;
            openDetail(row);
        });

        document.addEventListener('keydown', (event) => {
            const row = event.target.closest('.table-access-row');
            if (!row) return;
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openDetail(row);
            }
        });

        modalEl.addEventListener('click', (event) => {
            const clickedDismiss = event.target.closest('[data-bs-dismiss="modal"]');
            const clickedBackdrop = event.target === modalEl;
            if (!clickedDismiss && !clickedBackdrop) return;

            const modal = getBootstrapModal();
            if (!modal) {
                hideManualModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key !== 'Escape') return;
            if (!modalEl.classList.contains('show')) return;

            const modal = getBootstrapModal();
            if (!modal) {
                hideManualModal();
            }
        });

        modalEl.addEventListener('shown.bs.modal', () => {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            const lastBackdrop = backdrops[backdrops.length - 1];
            if (lastBackdrop) {
                lastBackdrop.classList.add('access-detail-backdrop');
            }
        });

        modalEl.addEventListener('hidden.bs.modal', () => {
            document.querySelectorAll('.modal-backdrop.access-detail-backdrop').forEach((el) => {
                el.classList.remove('access-detail-backdrop');
            });
        });
    })();
</script>

<!-- Toast Container -->
<div id="qrToastContainer" class="qr-toast-container"></div>

<script>
    // Premium Toast Notification System
    function showQrToast(type, title, message, actionUrl) {
        var existing = document.querySelectorAll('.qr-toast');
        existing.forEach(function (t) { t.remove(); });

        var container = document.getElementById('qrToastContainer');

        var iconClass = type === 'success' ? 'bi-check-circle-fill' : 'bi-x-circle-fill';
        var toast = document.createElement('div');
        toast.className = 'qr-toast';

        var actionsHtml = '';
        if (type === 'success' && actionUrl) {
            actionsHtml = '<div class="qr-toast-actions">' +
                '<a href="' + actionUrl + '" target="_blank" class="btn-toast btn-toast-primary text-decoration-none"><i class="bi bi-qr-code me-1"></i>Ver Pase</a>' +
                '</div>';
        }

        toast.innerHTML =
            '<div class="qr-toast-icon ' + type + '"><i class="bi ' + iconClass + '"></i></div>' +
            '<div class="qr-toast-body">' +
            '<div class="qr-toast-title">' + title + '</div>' +
            '<div class="qr-toast-message">' + message + '</div>' +
            actionsHtml +
            '</div>' +
            '<button class="qr-toast-close" onclick="dismissQrToast(this.closest(\'.qr-toast\'))"><i class="bi bi-x-lg"></i></button>' +
            '<div class="qr-toast-progress ' + type + '" style="width:100%;"></div>';

        container.appendChild(toast);

        // Animate in
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                toast.classList.add('show');
            });
        });

        // Progress bar
        var progressBar = toast.querySelector('.qr-toast-progress');
        var duration = type === 'success' ? 4000 : 5000;
        progressBar.style.transitionDuration = duration + 'ms';
        setTimeout(function () {
            progressBar.style.width = '0%';
        }, 100);

        // Auto dismiss
        setTimeout(function () {
            dismissQrToast(toast);
        }, duration);
    }

    function dismissQrToast(toast) {
        if (!toast || toast.classList.contains('hiding')) return;
        toast.classList.add('hiding');
        toast.classList.remove('show');
        setTimeout(function () { toast.remove(); }, 400);
    }
</script>

<?= $this->endSection() ?>