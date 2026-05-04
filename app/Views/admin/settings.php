<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$community = is_array($community ?? null) ? $community : [];
$community = array_merge([
    'id' => 0,
    'name' => 'Comunidad',
    'initial' => 'C',
    'timezone' => 'America/Mexico_City',
    'timezone_label' => 'Mexico City (GMT-6)',
    'street' => 'Sin definir',
    'city' => 'Sin definir',
    'state' => 'Sin definir',
    'postal_code' => 'Sin definir',
    'country' => 'Mexico',
    'logo' => null,
    'cover_image' => null,
], $community);
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

    .cfg-layout {
        display: grid;
        grid-template-columns: 190px minmax(0, 1fr);
        gap: 1.25rem;
        align-items: start;
    }

    .cfg-sidebar {
        padding: 0.3rem 0.2rem 0.1rem;
    }

    .cfg-nav-group-label {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #94a3b8;
        font-weight: 600;
        padding: 0.55rem 0.58rem 0.22rem;
        margin-top: 0.1rem;
    }

    .cfg-nav-link {
        width: 100%;
        border: none;
        background: transparent;
        color: #57708f;
        font-size: 0.87rem;
        text-align: left;
        border-radius: 0.45rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.47rem 0.58rem;
        transition: all 0.15s;
        cursor: pointer;
    }

    .cfg-nav-link.active {
        background: #eef2f7;
        color: #334155;
        font-weight: 600;
    }

    .cfg-nav-link:hover {
        background: #f1f5f9;
    }

    .cfg-nav-divider {
        border-top: 1px solid #e2e8f0;
        margin: 0.56rem 0;
    }

    .cfg-main h3 {
        margin: 0;
        color: #0f172a;
        font-size: 1.35rem;
        line-height: 1;
        font-weight: 700;
    }

    .cfg-main .subtitle {
        margin: 0.35rem 0 0.95rem;
        color: #57708f;
        font-size: 0.92rem;
    }

    .cfg-card {
        width: min(100%, 640px);
        border: 1px solid #e2e8f0;
        border-radius: 0.65rem;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04), 0 4px 12px rgba(15, 23, 42, 0.03);
    }

    .cfg-cover {
        height: 168px;
        background: linear-gradient(135deg, #3b4b63 0%, #1f2d45 80%);
        position: relative;
        cursor: pointer;
        overflow: hidden;
    }

    .cfg-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cfg-cover-action {
        position: absolute;
        right: 0.62rem;
        top: 0.62rem;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(6px);
        color: #3F67AC;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
    }

    .cfg-cover-action:hover {
        background: #fff;
        transform: scale(1.08);
    }

    .cfg-profile {
        display: flex;
        align-items: center;
        gap: 0.88rem;
        padding: 0 1.05rem;
        margin-top: -40px;
    }

    .cfg-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }

    .cfg-avatar {
        width: 84px;
        height: 84px;
        border-radius: 0.75rem;
        border: 3px solid #ffffff;
        background: #f1f5f9;
        color: #526b86;
        font-size: 1.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        overflow: hidden;
    }

    .cfg-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cfg-avatar-action {
        position: absolute;
        right: -4px;
        bottom: -5px;
        width: 28px;
        height: 28px;
        border: 2px solid #fff;
        border-radius: 999px;
        background: #4b5f78;
        color: #fff;
        font-size: 0.68rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .cfg-avatar-action:hover {
        background: #334155;
        transform: scale(1.1);
    }

    .cfg-identity {
        min-width: 0;
        flex: 1;
        padding-top: 42px;
    }

    .cfg-name {
        font-size: 1.22rem;
        line-height: 1;
        color: #0f172a;
        font-weight: 700;
        margin-bottom: 0.3rem;
    }

    .cfg-zone {
        color: #64748b;
        font-size: 0.88rem;
        display: inline-flex;
        align-items: center;
        gap: 0.38rem;
    }

    .cfg-edit-btn {
        margin-left: auto;
        margin-top: 42px;
        border: 1px solid #e2e8f0;
        border-radius: 0.45rem;
        background: #fff;
        color: #334155;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.4rem 0.78rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.15s;
    }

    .cfg-edit-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
    }

    .cfg-upload-note {
        color: #94a3b8;
        font-size: 0.76rem;
        padding: 0.5rem 1.1rem 0;
        display: flex;
        gap: 1rem;
    }

    .cfg-upload-note span {
        cursor: pointer;
        transition: color 0.15s;
    }

    .cfg-upload-note span:hover {
        color: #3F67AC;
        text-decoration: underline;
    }

    .cfg-divider {
        border-top: 1px solid #e2e8f0;
        margin: 0.72rem 1rem 0.85rem;
    }

    .cfg-section-head {
        padding: 0 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.8rem;
        margin-bottom: 0.72rem;
    }

    .cfg-section-head h4 {
        margin: 0;
        color: #0f172a;
        font-size: 1rem;
        line-height: 1;
        font-weight: 700;
    }

    .cfg-address-box {
        margin: 0 1rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 0.9rem 0.8rem;
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0.95rem 1.35rem;
    }

    .cfg-field-label {
        display: block;
        color: #94a3b8;
        font-size: 0.78rem;
        font-weight: 500;
        margin-bottom: 0.12rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .cfg-field-value {
        margin: 0;
        color: #1e293b;
        font-size: 0.92rem;
        font-weight: 500;
        line-height: 1.3;
    }

    .cfg-uppercase {
        text-transform: uppercase;
    }

    /* ─── Modal Styles ─── */
    .cfg-modal .modal-content {
        border: none;
        border-radius: 0.75rem;
        box-shadow: 0 20px 60px rgba(15, 23, 42, 0.18), 0 4px 16px rgba(15, 23, 42, 0.08);
    }

    .cfg-modal .modal-header {
        border-bottom: none;
        padding: 1.5rem 1.5rem 0.5rem;
    }

    .cfg-modal .modal-header .modal-title {
        font-weight: 700;
        font-size: 1.15rem;
        color: #0f172a;
    }

    .cfg-modal .modal-header .cfg-modal-subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 0.2rem;
    }

    .cfg-modal .modal-body {
        padding: 0.75rem 1.5rem 1.25rem;
    }

    .cfg-modal .modal-footer {
        border-top: none;
        padding: 0 1.5rem 1.5rem;
    }

    .cfg-modal .btn-close {
        opacity: 0.5;
    }

    .cfg-modal label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.3rem;
    }

    .cfg-modal .form-control,
    .cfg-modal .form-select {
        border: 1px solid #e2e8f0;
        border-radius: 0.45rem;
        font-size: 0.9rem;
        padding: 0.55rem 0.75rem;
        color: #1e293b;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .cfg-modal .form-control:focus,
    .cfg-modal .form-select:focus {
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(100, 116, 139, 0.1);
    }

    .cfg-modal .form-text {
        color: #94a3b8;
        font-size: 0.78rem;
    }

    .cfg-modal .tz-icon {
        color: #64748b;
    }

    .btn-cfg-primary {
        background: #334155;
        border: none;
        color: #fff;
        font-weight: 600;
        font-size: 0.88rem;
        border-radius: 0.45rem;
        padding: 0.5rem 1.2rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.15s;
    }

    .btn-cfg-primary:hover {
        background: #1e293b;
        color: #fff;
    }

    .btn-cfg-cancel {
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #334155;
        font-weight: 600;
        font-size: 0.88rem;
        border-radius: 0.45rem;
        padding: 0.5rem 1.2rem;
        transition: all 0.15s;
    }

    .btn-cfg-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    /* Toast notification */
    .cfg-toast {
        position: fixed;
        bottom: 1.5rem;
        right: 1.5rem;
        background: #0f172a;
        color: #fff;
        padding: 0.75rem 1.2rem;
        border-radius: 0.5rem;
        font-size: 0.88rem;
        font-weight: 500;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        animation: cfgToastIn 0.3s ease-out;
    }

    .cfg-toast.success {
        background: #059669;
    }

    .cfg-toast.error {
        background: #dc2626;
    }

    @keyframes cfgToastIn {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ─── Administradores Panel ─── */
    .cfg-tab-panel {
        display: none;
    }

    .cfg-tab-panel.active {
        display: block;
    }

    .admins-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        margin-bottom: 1.1rem;
    }

    .admins-header h3 {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .admins-header h3 .bi-info-circle {
        font-size: 0.85rem;
        color: #94a3b8;
        cursor: help;
    }

    .admins-header .subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin: 0.25rem 0 0;
    }

    .btn-add-admin {
        background: #334155;
        border: none;
        color: #fff;
        font-weight: 600;
        font-size: 0.85rem;
        border-radius: 0.45rem;
        padding: 0.5rem 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-add-admin:hover {
        background: #1e293b;
    }

    .admin-list {
        width: min(100%, 700px);
        border: 1px solid #e2e8f0;
        border-radius: 0.65rem;
        background: #fff;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    }

    .admin-row {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.85rem 1.1rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.12s;
    }

    .admin-row:last-child {
        border-bottom: none;
    }

    .admin-row:hover {
        background: #f8fafc;
    }

    .admin-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e0e7ff;
        color: #4f46e5;
        font-size: 0.92rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .admin-avatar.color-1 {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .admin-avatar.color-2 {
        background: #fce7f3;
        color: #db2777;
    }

    .admin-avatar.color-3 {
        background: #d1fae5;
        color: #059669;
    }

    .admin-avatar.color-4 {
        background: #fef3c7;
        color: #d97706;
    }

    .admin-avatar.color-5 {
        background: #e0f2fe;
        color: #0284c7;
    }

    .admin-info {
        flex: 1;
        min-width: 0;
    }

    .admin-info .admin-name {
        font-weight: 600;
        font-size: 0.92rem;
        color: #0f172a;
        line-height: 1.2;
    }

    .admin-info .admin-email {
        font-size: 0.82rem;
        color: #64748b;
        line-height: 1.3;
    }

    .admin-tags {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        flex-shrink: 0;
    }

    .admin-role-badge {
        background: #f1f5f9;
        color: #3F67AC;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.22rem 0.65rem;
        border-radius: 0.35rem;
        border: 1px solid #e2e8f0;
    }

    .btn-remove-admin {
        width: 34px;
        height: 34px;
        border-radius: 0.4rem;
        border: 1px solid #fecaca;
        background: #fff;
        color: #ef4444;
        font-size: 0.88rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
        flex-shrink: 0;
    }

    .btn-remove-admin:hover {
        background: #fef2f2;
        border-color: #f87171;
    }

    .admin-empty {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #94a3b8;
    }

    .admin-empty i {
        font-size: 2.2rem;
        margin-bottom: 0.5rem;
        display: block;
    }

    .admin-loading {
        text-align: center;
        padding: 2.5rem 1rem;
        color: #94a3b8;
    }

    /* Password field */
    .pwd-input-wrap {
        position: relative;
    }

    /* Account / Profile Tabs */
    .account-premium-card {
        background: #ffffff;
        border: 1px solid #e1e4e8;
        border-radius: 0.65rem;
        padding: 2.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }

    .account-avatar-wrap {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 1.25rem;
    }

    .account-avatar-wrap .cfg-avatar {
        width: 150px;
        height: 150px;
        font-size: 3rem;
        font-weight: 400;
        background: #f8fafc;
        color: #0f172a;
        border: 2px solid #e2e8f0;
    }

    .account-upload-btn {
        position: absolute;
        bottom: 4px;
        right: 4px;
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #334155;
        color: #fff;
        border: 3px solid #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .account-upload-btn:hover {
        background: #1e293b;
        transform: scale(1.05);
    }

    .account-upload-text {
        font-size: 0.82rem;
        color: #94a3b8;
        line-height: 1.4;
    }

    .account-form-grid {
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 3.5rem;
        align-items: start;
        margin-top: 2rem;
    }

    .account-form-wrap {
        max-width: 460px;
    }

    .account-security-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.55rem;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .account-security-card h4 {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .account-security-card p {
        color: #64748b;
        font-size: 0.88rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 760px) {
        .account-form-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }
    }

    .pwd-input-wrap .form-control {
        padding-right: 5.5rem;
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 0.03em;
    }

    .pwd-actions {
        position: absolute;
        right: 0.35rem;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        gap: 0.2rem;
    }

    .pwd-actions button {
        border: none;
        background: transparent;
        color: #64748b;
        font-size: 0.85rem;
        width: 28px;
        height: 28px;
        border-radius: 0.3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s;
    }

    .pwd-actions button:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .pwd-strength {
        height: 3px;
        border-radius: 2px;
        background: #e2e8f0;
        margin-top: 0.35rem;
        overflow: hidden;
    }

    .pwd-strength-bar {
        height: 100%;
        border-radius: 2px;
        transition: width 0.3s, background 0.3s;
        width: 0;
    }

    @media (max-width: 1180px) {
        .cfg-layout {
            grid-template-columns: 1fr;
        }

        .cfg-sidebar {
            border: 1px solid #e2e8f0;
            border-radius: 0.55rem;
            background: #fff;
            padding: 0.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 0.25rem;
        }

        .cfg-nav-group-label {
            display: none;
        }
    }

    @media (max-width: 760px) {
        .cfg-profile {
            flex-wrap: wrap;
            margin-top: -30px;
            padding-bottom: 0.45rem;
        }

        .cfg-identity,
        .cfg-edit-btn {
            margin-top: 0;
            padding-top: 0;
        }

        .cfg-upload-note {
            flex-direction: column;
            gap: 0.2rem;
        }

        .cfg-address-box {
            grid-template-columns: 1fr;
        }

        .admin-row {
            flex-wrap: wrap;
        }

        .admin-tags {
            margin-left: calc(40px + 0.85rem);
        }
    }
</style>



<!-- ── Hero ── -->
<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">Configuraci&oacute;n</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-gear"></i>
            <i class="bi bi-chevron-right"></i>
            Configuraciones generales
        </div>
    </div>

</div>
<!-- ── END Hero ── -->


<div class="cfg-layout">
    <aside class="cfg-sidebar">
        <div class="cfg-nav-group-label"><?= esc(strtoupper($community['name'])) ?></div>
        <button type="button" class="cfg-nav-link active" data-tab="general"><i class="bi bi-buildings"></i>
            General</button>
        <button type="button" class="cfg-nav-link" data-tab="admins"><i class="bi bi-people"></i>
            Administradores</button>
        <button type="button" class="cfg-nav-link" data-tab="sections"><i class="bi bi-layers"></i> Secciones</button>

        <div class="cfg-nav-divider"></div>

        <div class="cfg-nav-group-label"
            style="display:flex; align-items:center; gap:0.5rem; justify-content:space-between; cursor:pointer;"
            onclick="document.getElementById('prefsSubmenu').style.display = document.getElementById('prefsSubmenu').style.display === 'none' ? 'flex' : 'none';">
            <span><i class="bi bi-sliders me-1"></i> Preferencias</span>
            <i class="bi bi-chevron-down"></i>
        </div>
        <div id="prefsSubmenu" style="display:flex; flex-direction:column; margin-left:1.2rem; margin-top:0.3rem;">
            <button type="button" class="cfg-nav-link" style="padding-top:0.3rem; padding-bottom:0.3rem;"
                data-tab="wallAccess">Anuncios y Muro</button>
            <button type="button" class="cfg-nav-link" style="padding-top:0.3rem; padding-bottom:0.3rem;"
                data-tab="paymentReminders">Recordatorios de Pago</button>
            <button type="button" class="cfg-nav-link" style="padding-top:0.3rem; padding-bottom:0.3rem;"
                data-tab="financialAccess">Acceso Financiero</button>
            <button type="button" class="cfg-nav-link" style="padding-top:0.3rem; padding-bottom:0.3rem;"
                data-tab="delinquencyRestrictions">Restricciones por Morosidad</button>
            <button type="button" class="cfg-nav-link" style="padding-top:0.3rem; padding-bottom:0.3rem;"
                disabled>Firma</button>
            <button type="button" class="cfg-nav-link" style="padding-top:0.3rem; padding-bottom:0.3rem;"
                disabled>Alertas Comunitarias</button>
        </div>

        <div class="cfg-nav-divider"></div>
        <button type="button" class="cfg-nav-link" data-tab="financeSettings"><i class="bi bi-wallet2"></i>
            Finanzas</button>

        <div class="cfg-nav-divider"></div>

        <?php if (session()->get('is_owner')): ?>
        <button type="button" class="cfg-nav-link" data-tab="subscription"><i class="bi bi-credit-card"></i> Suscripción</button>
        <button type="button" class="cfg-nav-link" data-tab="advanced"><i class="bi bi-shield"></i> Avanzado</button>
        <?php endif; ?>

        <div class="cfg-nav-divider"></div>
        <div class="cfg-nav-group-label">MI CUENTA</div>
        <button type="button" class="cfg-nav-link" data-tab="profile"><i class="bi bi-person"></i> Perfil</button>
        <button type="button" class="cfg-nav-link" data-tab="security"><i class="bi bi-lock"></i> Seguridad</button>
    </aside>

    <section class="cfg-main">
        <!-- ═══ TAB: General ═══ -->
        <div class="cfg-tab-panel active" id="tabGeneral">
            <h3>Configuraci&oacute;n de la Comunidad</h3>
            <p class="subtitle">Administra el perfil, direcci&oacute;n y zona horaria de tu comunidad</p>

            <article class="cfg-card">
                <!-- Cover Image -->
                <div class="cfg-cover" id="coverArea">
                    <?php if ($community['cover_image']): ?>
                        <img src="<?= base_url('api/v1/assets/condominiums/' . $community['id'] . '/' . $community['cover_image']) ?>" alt="Portada"
                            id="coverPreview">
                    <?php endif; ?>
                    <button type="button" class="cfg-cover-action" id="btnUploadCover" aria-label="Cambiar portada">
                        <i class="bi bi-image"></i>
                    </button>
                    <input type="file" id="inputCover" accept="image/*" hidden>
                </div>

                <!-- Profile Row -->
                <div class="cfg-profile">
                    <div class="cfg-avatar-wrap">
                        <div class="cfg-avatar" id="avatarDisplay">
                            <?php if ($community['logo']): ?>
                                <img src="<?= base_url('api/v1/assets/condominiums/' . $community['id'] . '/' . $community['logo']) ?>" alt="Logo"
                                    id="logoPreview">
                            <?php else: ?>
                                <span id="logoInitial"><?= esc($community['initial']) ?></span>
                            <?php endif; ?>
                        </div>
                        <button type="button" class="cfg-avatar-action" id="btnUploadLogo" aria-label="Cambiar logo">
                            <i class="bi bi-camera-fill"></i>
                        </button>
                        <input type="file" id="inputLogo" accept="image/*" hidden>
                    </div>

                    <div class="cfg-identity">
                        <div class="cfg-name" id="displayName"><?= esc($community['name']) ?></div>
                        <div class="cfg-zone">
                            <i class="bi bi-globe-americas"></i>
                            <span id="displayTimezone"><?= esc($community['timezone_label']) ?></span>
                        </div>
                    </div>

                    <button type="button" class="cfg-edit-btn" id="btnEditInfo">
                        <i class="bi bi-pencil-square"></i> Editar
                    </button>
                </div>

                <div class="cfg-upload-note">
                    <span id="linkUploadLogo">Clic para subir logo</span>
                    <span id="linkUploadCover">Clic para subir foto de portada</span>
                </div>

                <div class="cfg-divider"></div>

                <!-- Address Section -->
                <div class="cfg-section-head">
                    <h4>Direcci&oacute;n</h4>
                    <button type="button" class="cfg-edit-btn" id="btnEditAddress" style="margin-top:0;">
                        <i class="bi bi-pencil-square"></i> Editar
                    </button>
                </div>

                <div class="cfg-address-box">
                    <div>
                        <span class="cfg-field-label">Calle</span>
                        <p class="cfg-field-value cfg-uppercase" id="displayStreet"><?= esc($community['street']) ?></p>
                    </div>
                    <div></div>
                    <div>
                        <span class="cfg-field-label">Ciudad</span>
                        <p class="cfg-field-value cfg-uppercase" id="displayCity"><?= esc($community['city']) ?></p>
                    </div>
                    <div>
                        <span class="cfg-field-label">Estado</span>
                        <p class="cfg-field-value cfg-uppercase" id="displayState"><?= esc($community['state']) ?></p>
                    </div>
                    <div>
                        <span class="cfg-field-label">C&oacute;digo Postal</span>
                        <p class="cfg-field-value" id="displayPostalCode"><?= esc($community['postal_code']) ?></p>
                    </div>
                    <div>
                        <span class="cfg-field-label">Pa&iacute;s</span>
                        <p class="cfg-field-value cfg-uppercase" id="displayCountry"><?= esc($community['country']) ?>
                        </p>
                    </div>
                </div>
            </article>
        </div>

        <!-- ═══ TAB: Administradores ═══ -->
        <div class="cfg-tab-panel" id="tabAdmins">
            <div class="admins-header">
                <div>
                    <h3>Administradores <i class="bi bi-info-circle"
                            title="Los administradores solo podr&aacute;n gestionar este condominio. Un mismo correo puede administrar m&uacute;ltiples condominios."></i>
                    </h3>
                    <p class="subtitle">Administrar administradores de la aplicaci&oacute;n y sus roles</p>
                </div>
                <?php if (session()->get('is_owner')): ?>
                <button type="button" class="btn-add-admin" id="btnAddAdmin">
                    <i class="bi bi-plus-lg"></i> Agregar
                </button>
                <?php endif; ?>
            </div>

            <div class="admin-list" id="adminListContainer">
                <div class="admin-loading">
                    <span class="spinner-border spinner-border-sm me-2"></span> Cargando administradores...
                </div>
            </div>
        </div>

        <!-- ═══ TAB: Secciones ═══ -->
        <div class="cfg-tab-panel" id="tabSections">
            <div class="admins-header">
                <div>
                    <h3>Secciones <i class="bi bi-info-circle"
                            title="Organiza las unidades por torres, bloques o calles"></i></h3>
                    <p class="subtitle" style="margin-top: 0.15rem; color: #64748b; font-size: 0.9rem;">Organiza las
                        unidades por torres, bloques o calles</p>
                </div>
                <button type="button" class="btn-add-admin" id="btnAddSection" onclick="openSectionModal()">
                    <i class="bi bi-plus-lg"></i> Agregar Secci&oacute;n
                </button>
            </div>

            <div class="section-list mt-4">
                <?php if (empty($sections)): ?>
                    <div class="text-center p-5 text-muted border rounded bg-white">
                        <i class="bi bi-layers" style="font-size: 2rem; color: #cbd5e1;"></i>
                        <p class="mt-2 mb-0">No hay secciones registradas. Agrega tu primera secci&oacute;n.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($sections as $sec): ?>
                        <?php
                        $secUnitCount = 0;
                        foreach ($units as $u) {
                            if ($u['section_id'] == $sec['id'])
                                $secUnitCount++;
                        }
                        ?>
                        <div class="d-flex align-items-center justify-content-between mb-3 bg-white border"
                            style="padding: 1.25rem 1.5rem; border-radius: 0.55rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                            <div class="d-flex align-items-center gap-3">
                                <div
                                    style="background: #f1f5f9; padding: 0.65rem 0.85rem; border-radius: 0.5rem; color: #64748b;">
                                    <i class="bi bi-building"></i>
                                </div>
                                <div>
                                    <h4 style="margin: 0; font-size: 1.05rem; font-weight: 500; color: #1e293b;">
                                        <?= esc($sec['name']) ?>
                                    </h4>
                                    <div class="text-muted" style="font-size: 0.85rem; margin-top: 0.15rem;">N&uacute;mero de
                                        Unidades: <?= $secUnitCount ?></div>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-light border-0"
                                    onclick="openSectionModal(<?= $sec['id'] ?>, '<?= esc(addslashes($sec['name'])) ?>')"
                                    style="padding: 0.4rem 0.6rem; border-radius: 0.35rem;" title="Editar">
                                    <i class="bi bi-pencil" style="font-size: 0.95rem; color: #334155;"></i>
                                </button>
                                <button type="button" class="btn btn-sm border-0"
                                    onclick="confirmDeleteSection(<?= $sec['id'] ?>, '<?= esc(addslashes($sec['name'])) ?>')"
                                    style="padding: 0.4rem 0.6rem; border-radius: 0.35rem; background: #fef2f2; color: #ef4444;"
                                    title="Eliminar">
                                    <i class="bi bi-trash" style="font-size: 0.95rem;"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- ═══ TAB: Anuncios y Muro ═══ -->
        <div class="cfg-tab-panel" id="tabWallAccess">
            <div class="admins-header" style="margin-bottom: 0.5rem;">
                <div>
                    <h3 style="color:#2563eb; font-size:1.1rem; display:flex; align-items:center; gap:0.5rem;"><i
                            class="bi bi-megaphone" style="font-size:1.3rem;"></i> Anuncios y Muro</h3>
                    <p style="color:#64748b; font-size:0.95rem; margin-top:0.3rem;">Estos ajustes afectan cómo los
                        residentes interactúan con las publicaciones en la app móvil</p>
                </div>
            </div>

            <div
                style="border: 1px solid #e2e8f0; border-radius: 0.65rem; background:#fff; overflow:hidden; margin-bottom: 1.5rem;">
                <!-- Allow resident posts -->
                <div
                    style="padding: 1.25rem; display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid #e2e8f0;">
                    <div>
                        <span style="font-weight:600; color:#0f172a; display:block;">Permitir que los residentes creen
                            publicaciones</span>
                        <span style="color:#64748b; font-size:0.85rem;">Cuando está habilitado, los residentes pueden
                            crear publicaciones en el muro de la comunidad</span>
                    </div>
                    <div class="form-check form-switch" style="font-size: 1.25rem; margin:0;">
                        <input class="form-check-input" type="checkbox" id="wallAllowPosts" style="cursor:pointer;"
                            onchange="saveWallPrefs()" <?= $community['allow_resident_posts'] ? 'checked' : '' ?>>
                    </div>
                </div>

                <!-- Allow comments -->
                <div
                    style="padding: 1.25rem; display:flex; justify-content:space-between; align-items:center; border-bottom: 1px solid #e2e8f0;">
                    <div>
                        <span style="font-weight:600; color:#0f172a; display:block;">Permitir comentarios en
                            publicaciones</span>
                        <span style="color:#64748b; font-size:0.85rem;">Cuando está deshabilitado, los usuarios no
                            podrán comentar en las publicaciones. Si hay comentarios existentes, se ocultarán.</span>
                    </div>
                    <div class="form-check form-switch" style="font-size: 1.25rem; margin:0;">
                        <input class="form-check-input" type="checkbox" id="wallAllowComments" style="cursor:pointer;"
                            onchange="saveWallPrefs()" <?= $community['allow_post_comments'] ? 'checked' : '' ?>>
                    </div>
                </div>

                <!-- Always email notifications -->
                <div style="padding: 1.25rem; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <span style="font-weight:600; color:#0f172a; display:flex; align-items:center; gap:0.4rem;">
                            <i class="bi bi-envelope" style="color:#3b82f6;"></i> Enviar siempre por correo
                        </span>
                        <span style="color:#64748b; font-size:0.85rem;">Cuando está activado, todas las publicaciones de
                            administradores se envían automáticamente por correo a todos los residentes</span>
                    </div>
                    <div class="form-check form-switch" style="font-size: 1.25rem; margin:0;">
                        <input class="form-check-input" type="checkbox" id="wallAlwaysEmail" style="cursor:pointer;"
                            onchange="saveWallPrefs()" <?= $community['always_email_posts'] ? 'checked' : '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ TAB: Acceso Financiero ═══ -->
        <div class="cfg-tab-panel" id="tabFinancialAccess">
            <div class="admins-header" style="margin-bottom: 0.5rem;">
                <div>
                    <h3 style="color:#eab308; font-size:1.1rem; display:flex; align-items:center; gap:0.5rem;"><i
                            class="bi bi-currency-dollar" style="font-size:1.3rem;"></i> Acceso Financiero</h3>
                    <p style="color:#64748b; font-size:0.95rem; margin-top:0.3rem;">Controla quién puede ver los datos
                        financieros en tu comunidad</p>
                </div>
            </div>

            <div
                style="background-color: #fefce8; border: 1px solid #facc15; border-radius: 0.5rem; padding: 1rem; display:flex; gap:0.8rem; align-items:flex-start; margin-bottom: 1.5rem;">
                <i class="bi bi-info-circle" style="color: #ca8a04; font-size: 1.1rem;"></i>
                <p style="margin:0; font-size:0.85rem; color:#854d0e;">
                    Los propietarios siempre pueden ver los datos financieros de su(s) unidad(es) (saldo, cargos,
                    pagos). Esta configuración controla si también pueden acceder a la pestaña "Comunidad" en la app
                    móvil y descargar reportes financieros de la comunidad.
                </p>
            </div>

            <div
                style="border: 1px solid #e2e8f0; border-radius: 0.65rem; background:#fff; overflow:hidden; margin-bottom: 1.5rem;">
                <div style="padding: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                    <div style="margin-bottom:0.8rem;">
                        <span style="font-weight:600; color:#0f172a;">Acceso financiero de propietarios <i
                                class="bi bi-info-circle text-muted" style="font-size:0.8rem;"></i></span>
                        <div style="color:#64748b; font-size:0.85rem; margin-top:0.15rem;">Controla si los propietarios
                            pueden ver las finanzas de la comunidad</div>
                    </div>
                    <select class="form-select" id="finOwnerAccess" style="max-width:300px;"
                        onchange="saveFinancialPrefs()">
                        <option value="unit_community" <?= $community['owner_financial_access'] === 'unit_community' ? 'selected' : '' ?>>Unidad + Comunidad</option>
                        <option value="unit_only" <?= $community['owner_financial_access'] === 'unit_only' ? 'selected' : '' ?>>Solo Unidad</option>
                    </select>
                </div>

                <div style="padding: 1.25rem;">
                    <div style="margin-bottom:0.8rem;">
                        <span style="font-weight:600; color:#0f172a;">Acceso financiero de inquilinos <i
                                class="bi bi-info-circle text-muted" style="font-size:0.8rem;"></i></span>
                        <div style="color:#64748b; font-size:0.85rem; margin-top:0.15rem;">Controla qué datos
                            financieros pueden ver los inquilinos</div>
                    </div>
                    <select class="form-select" id="finTenantAccess" style="max-width:300px;"
                        onchange="saveFinancialPrefs()">
                        <option value="none" <?= $community['tenant_financial_access'] === 'none' ? 'selected' : '' ?>>Sin
                            acceso</option>
                        <option value="unit_only" <?= $community['tenant_financial_access'] === 'unit_only' ? 'selected' : '' ?>>Solo unidad</option>
                        <option value="unit_community" <?= $community['tenant_financial_access'] === 'unit_community' ? 'selected' : '' ?>>Unidad + Comunidad</option>
                    </select>
                </div>
            </div>

            <div style="border: 1px solid #e2e8f0; border-radius: 0.65rem; background:#fff; overflow:hidden;">
                <div style="padding: 1.25rem; display:flex; justify-content:space-between; align-items:center;"
                    id="wrapDelinquentUnits">
                    <div>
                        <span style="font-weight:600; color:#0f172a; display:block;">Mostrar lista de unidades
                            morosas</span>
                        <span style="color:#64748b; font-size:0.85rem;">Mostrar una lista de unidades morosas a todos
                            los residentes</span>
                    </div>
                    <div class="form-check form-switch" style="font-size: 1.25rem; margin:0;">
                        <input class="form-check-input" type="checkbox" role="switch" id="finDelinquentUnits"
                            <?= $community['show_delinquent_units'] ? 'checked' : '' ?>
                            onchange="toggleDelinquentAmounts(); saveFinancialPrefs()">
                    </div>
                </div>

                <div style="padding: 1.25rem; border-top: 1px solid #e2e8f0; display: <?= $community['show_delinquent_units'] ? 'flex' : 'none' ?>; justify-content:space-between; align-items:center;"
                    id="wrapDelinquentAmounts">
                    <div>
                        <span style="font-weight:600; color:#0f172a; display:block;">Mostrar montos de morosidad</span>
                        <span style="color:#64748b; font-size:0.85rem;">Mostrar los montos específicos adeudados en la
                            lista de unidades morosas</span>
                    </div>
                    <div class="form-check form-switch" style="font-size: 1.25rem; margin:0;">
                        <input class="form-check-input" type="checkbox" role="switch" id="finDelinquentAmounts"
                            <?= $community['show_delinquency_amounts'] ? 'checked' : '' ?>
                            onchange="saveFinancialPrefs()">
                    </div>
                </div>
            </div>

            <!-- ── Aprobación de Pagos ── -->
            <div style="border: 1px solid #e2e8f0; border-radius: 0.65rem; background:#fff; overflow:hidden; margin-bottom: 1.5rem;">
                <div style="padding: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                    <div style="display:flex; align-items:center; gap:0.6rem;">
                        <i class="bi bi-check2-circle" style="color:#6366f1; font-size:1.1rem;"></i>
                        <span style="font-weight:600; color:#0f172a; font-size:0.95rem;">Pagos Residente</span>
                    </div>
                </div>
                <div style="padding: 1.25rem;">
                    <div style="margin-bottom:0.8rem;">
                        <span style="font-weight:600; color:#0f172a;">Aprobación de Pagos <i class="bi bi-info-circle text-muted" style="font-size:0.8rem;" title="Define si los comprobantes subidos por los residentes se aprueban manualmente por un administrador o automáticamente."></i></span>
                        <div style="color:#64748b; font-size:0.85rem; margin-top:0.15rem;">Selecciona el modo de validación para los pagos reportados desde la App.</div>
                    </div>
                    <select class="form-select" id="finPaymentApprovalMode" style="max-width:300px;" onchange="saveFinancialPrefs()">
                        <option value="manual" <?= $community['payment_approval_mode'] === 'manual' ? 'selected' : '' ?>>Aprobación Manual</option>
                        <option value="automatic" <?= $community['payment_approval_mode'] === 'automatic' ? 'selected' : '' ?>>Aprobación Automática</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- ═══ TAB: Recordatorios de Pago ═══ -->
        <div class="cfg-tab-panel" id="tabPaymentReminders">
            <div class="admins-header" style="margin-bottom: 0.5rem;">
                <div>
                    <h3 style="color:#f59e0b; font-size:1.1rem; display:flex; align-items:center; gap:0.5rem;"><i
                            class="bi bi-bell" style="font-size:1.3rem;"></i> Recordatorios de Pago</h3>
                    <p style="color:#64748b; font-size:0.95rem; margin-top:0.3rem;">Configura notificaciones automáticas
                        para recordar a los residentes sobre el pago de cuotas de mantenimiento.<br>Los recordatorios se
                        envían a las 10 AM en la zona horaria de tu comunidad.</p>
                    0 10 * * * php /ruta/absoluta/a/tu/proyecto/spark reminders:send > /dev/null 2>&1
                </div>
            </div>

            <div
                style="border: 1px solid #e2e8f0; border-radius: 0.65rem; background:#fff; overflow:hidden; margin-bottom: 1.5rem; padding: 1.25rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
                    <div>
                        <span style="font-weight:600; color:#0f172a; font-size:0.95rem;">Recordatorios
                            Configurados</span>
                    </div>
                    <div style="display:flex; align-items:center; gap: 1rem;">
                        <span style="color:#64748b; font-size:0.85rem;"
                            id="reminderCount"><?= count($payment_reminders ?? []) ?>/5</span>
                        <button type="button" class="btn-cfg-cancel"
                            style="padding: 0.35rem 0.8rem; display:flex; align-items:center; gap:0.4rem; font-size:0.85rem;"
                            onclick="openPaymentReminderModal()">
                            <i class="bi bi-plus-lg"></i> Agregar Recordatorio
                        </button>
                    </div>
                </div>

                <div id="paymentRemindersList" style="display:flex; flex-direction:column; gap:0.75rem;">
                    <?php if (empty($payment_reminders)): ?>
                        <div
                            style="text-align:center; padding: 2rem; color:#94a3b8; border: 1px dashed #cbd5e1; border-radius:0.5rem;">
                            No hay recordatorios configurados.
                        </div>
                    <?php else: ?>
                        <?php foreach ($payment_reminders as $reminder): ?>
                            <?php
                            // Format trigger text
                            $triggerText = '';
                            switch ($reminder['trigger_type']) {
                                case 'start_of_month':
                                    $triggerText = "Día " . $reminder['trigger_value'] . " de cada mes";
                                    break;
                                case 'days_before_due':
                                    $triggerText = $reminder['trigger_value'] . " días antes del vencimiento";
                                    break;
                                case 'due_date':
                                    $triggerText = "El día del vencimiento";
                                    break;
                                case 'days_after_due':
                                    $triggerText = $reminder['trigger_value'] . " días después del vencimiento";
                                    break;
                                case 'specific_day':
                                    $triggerText = "Día " . $reminder['trigger_value'] . " del mes";
                                    break;
                            }
                            ?>
                            <div style="border: 1px solid <?= $reminder['is_active'] ? '#cbd5e1' : '#e2e8f0' ?>; border-radius: 0.5rem; padding: 0.85rem 1.1rem; display:flex; justify-content:space-between; align-items:center; cursor:pointer; transition:all 0.2s; <?= $reminder['is_active'] ? '' : 'opacity:0.6;' ?>"
                                class="reminder-item"
                                onclick="openPaymentReminderModal(<?= $reminder['id'] ?>, '<?= esc(addslashes($reminder['trigger_type'])) ?>', <?= $reminder['trigger_value'] ?>, '<?= esc(addslashes($reminder['message_title'])) ?>', '<?= esc(addslashes($reminder['message_body'])) ?>', event)">
                                <div style="display:flex; align-items:center; gap:0.85rem;">
                                    <i class="bi <?= $reminder['trigger_type'] === 'due_date' ? 'bi-calendar-event' : 'bi-bell' ?>"
                                        style="color: <?= $reminder['trigger_type'] === 'due_date' ? '#f59e0b' : '#3b82f6' ?>; font-size:1.1rem;"></i>
                                    <div>
                                        <span
                                            style="color:#1e293b; font-size:0.9rem; font-weight:500;"><?= $triggerText ?></span>
                                        <span style="color:#64748b; font-size:0.9rem;"> -
                                            <?= esc($reminder['message_title']) ?></span>
                                    </div>
                                </div>
                                <div class="form-check form-switch" style="font-size: 1.1rem; margin:0;"
                                    onclick="event.stopPropagation()">
                                    <input class="form-check-input" type="checkbox" style="cursor:pointer;"
                                        <?= $reminder['is_active'] ? 'checked' : '' ?>
                                        onchange="togglePaymentReminder(<?= $reminder['id'] ?>, this.checked)">
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ═══ TAB: Restricciones por Morosidad ═══ -->
        <div class="cfg-tab-panel" id="tabDelinquencyRestrictions">
            <div class="admins-header">
                <div>
                    <h3><i class="bi bi-exclamation-triangle" style="color:#a855f7; margin-right:0.5rem;"></i>
                        Restricciones por Morosidad</h3>
                    <p class="subtitle">Configura qué funciones clave serán bloqueadas en la app para los residentes que
                        presenten saldos pendientes o morosidad.</p>
                </div>
            </div>

            <div
                style="border: 1px solid #e2e8f0; border-radius: 0.65rem; background:#fff; overflow:hidden; margin-bottom: 1.5rem; padding: 1.25rem;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 1rem;">
                    <div>
                        <span style="font-weight:600; color:#0f172a; font-size:0.95rem;">Restricciones Activas</span>
                    </div>
                </div>

                <form id="formDelinquencyRestrictions" style="display:flex; flex-direction:column; gap:0.75rem;">
                    <div style="border: 1px solid <?= !empty($community['restrict_qr_delinquent']) ? '#cbd5e1' : '#e2e8f0' ?>; border-radius: 0.5rem; padding: 0.85rem 1.1rem; display:flex; justify-content:space-between; align-items:center; transition:all 0.2s;"
                        id="rowRestrictQr">
                        <div style="display:flex; align-items:center; gap:0.85rem;">
                            <i class="bi bi-qr-code-scan" style="color: #6366f1; font-size:1.1rem;"></i>
                            <div>
                                <span style="color:#1e293b; font-size:0.9rem; font-weight:500;">Desactivar códigos QR
                                    para unidades morosas</span>
                                <br><span style="color:#64748b; font-size:0.85rem;">Prevenir la generación de pases para
                                    invitar personas</span>
                            </div>
                        </div>
                        <div class="form-check form-switch" style="font-size: 1.1rem; margin:0;">
                            <input class="form-check-input" type="checkbox" id="restrictQr"
                                <?= !empty($community['restrict_qr_delinquent']) ? 'checked' : '' ?>
                                style="cursor:pointer;" onchange="saveDelinquencyRestrictions()">
                        </div>
                    </div>

                    <div style="border: 1px solid <?= !empty($community['restrict_amenities_delinquent']) ? '#cbd5e1' : '#e2e8f0' ?>; border-radius: 0.5rem; padding: 0.85rem 1.1rem; display:flex; justify-content:space-between; align-items:center; transition:all 0.2s;"
                        id="rowRestrictAmenities">
                        <div style="display:flex; align-items:center; gap:0.85rem;">
                            <i class="bi bi-calendar-check" style="color: #f59e0b; font-size:1.1rem;"></i>
                            <div>
                                <span style="color:#1e293b; font-size:0.9rem; font-weight:500;">Desactivar reservas de
                                    amenidades</span>
                                <br><span style="color:#64748b; font-size:0.85rem;">Prevenir reservas para áreas comunes
                                    en unidades morosas</span>
                            </div>
                        </div>
                        <div class="form-check form-switch" style="font-size: 1.1rem; margin:0;">
                            <input class="form-check-input" type="checkbox" id="restrictAmenities"
                                <?= !empty($community['restrict_amenities_delinquent']) ? 'checked' : '' ?>
                                style="cursor:pointer;" onchange="saveDelinquencyRestrictions()">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ═══ TAB: Configuración de Finanzas ═══ -->
        <div class="cfg-tab-panel" id="tabFinanceSettings">
            <div class="admins-header" style="margin-bottom: 0.5rem;">
                <div>
                    <h3 style="color:#10b981; font-size:1.1rem; display:flex; align-items:center; gap:0.5rem;">
                        <i class="bi bi-wallet2" style="font-size:1.3rem;"></i> Configuración de Finanzas
                    </h3>
                    <p style="color:#64748b; font-size:0.95rem; margin-top:0.3rem;">Configura los ajustes financieros de
                        tu comunidad, datos bancarios y políticas de recargos.</p>
                </div>
            </div>

            <!-- ── Datos Bancarios ── -->
            <div
                style="border: 1px solid #e2e8f0; border-radius: 0.65rem; background:#fff; overflow:hidden; margin-bottom: 1.5rem;">
                <div style="padding: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <div style="display:flex; align-items:center; gap:0.6rem;">
                            <i class="bi bi-bank" style="color:#3b82f6; font-size:1.1rem;"></i>
                            <span style="font-weight:600; color:#0f172a; font-size:0.95rem;">Detalles Bancarios</span>
                        </div>
                    </div>
                    <p style="color:#64748b; font-size:0.85rem; margin:0.4rem 0 0 0;">Esta información bancaria es donde
                        los residentes pueden transferir sus cuotas de mantenimiento. También aparecerá en los estados
                        de cuenta descargados.</p>
                </div>
                <div style="padding: 1.25rem;">
                    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:1.5rem; margin-bottom:1rem;">
                        <div>
                            <span
                                style="color:#64748b; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:500;">Nombre
                                de Banco</span>
                            <div style="color:#0f172a; font-weight:600; font-size:0.95rem; margin-top:0.25rem;"
                                id="displayBankName">
                                <?= esc($community['bank_name']) ?: '<span style="color:#94a3b8;">No establecido</span>' ?>
                            </div>
                        </div>
                        <div>
                            <span
                                style="color:#64748b; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:500;">CLABE
                                SPEI</span>
                            <div style="color:#10b981; font-weight:600; font-size:0.95rem; margin-top:0.25rem; display:flex; align-items:center; gap:0.4rem;"
                                id="displayBankClabe">
                                <?php if (!empty($community['bank_clabe'])): ?>
                                    <?= esc($community['bank_clabe']) ?>
                                    <i class="bi bi-clipboard" style="color:#94a3b8; cursor:pointer; font-size:0.8rem;"
                                        onclick="navigator.clipboard.writeText('<?= esc($community['bank_clabe']) ?>'); showToast('CLABE copiada');"
                                        title="Copiar CLABE"></i>
                                <?php else: ?>
                                    <span style="color:#94a3b8;">No establecido</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div>
                            <span
                                style="color:#64748b; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:500;">RFC</span>
                            <div style="color:#0f172a; font-weight:600; font-size:0.95rem; margin-top:0.25rem; text-transform: uppercase;"
                                id="displayBankRfc">
                                <?= esc($community['bank_rfc']) ?: '<span style="color:#94a3b8;">No establecido</span>' ?>
                            </div>
                        </div>
                        <div>
                            <span
                                style="color:#64748b; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:500;">Tarjeta Bancaria</span>
                            <div style="color:#0f172a; font-weight:600; font-size:0.95rem; margin-top:0.25rem;"
                                id="displayBankCard">
                                <?= esc($community['bank_card']) ?: '<span style="color:#94a3b8;">No establecido</span>' ?>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <button type="button" class="btn-cfg-cancel"
                            style="padding:0.35rem 0.8rem; font-size:0.85rem; display:inline-flex; align-items:center; gap:0.4rem;"
                            onclick="openBankDetailsModal()">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                    </div>
                </div>
            </div>

            <!-- ── Configuración de Pagos ── -->
            <div
                style="border: 1px solid #e2e8f0; border-radius: 0.65rem; background:#fff; overflow:hidden; margin-bottom: 1.5rem;">
                <div style="padding: 1.25rem; border-bottom: 1px solid #e2e8f0;">
                    <div style="display:flex; align-items:center; gap:0.6rem;">
                        <i class="bi bi-credit-card-2-front" style="color:#10b981; font-size:1.1rem;"></i>
                        <span style="font-weight:600; color:#0f172a; font-size:0.95rem;">Configuración de Cuotas</span>
                    </div>
                </div>
                <div style="padding: 1.25rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.5rem; margin-bottom:1rem;">
                        <div>
                            <span
                                style="color:#64748b; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:500;">Moneda</span>
                            <div style="color:#0f172a; font-weight:600; font-size:0.95rem; margin-top:0.25rem;"
                                id="displayCurrency">
                                <?= $community['currency'] === 'USD' ? 'USD (US Dollar)' : 'MXN (Mexican Peso)' ?>
                            </div>
                        </div>
                        <div>
                            <span
                                style="color:#64748b; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:500; display:flex; align-items:center; gap:0.3rem;">
                                Fecha límite de pago
                                <i class="bi bi-info-circle" style="font-size:0.7rem; color:#94a3b8;"
                                    title="Los residentes deben pagar antes del día seleccionado. Después serán considerados morosos."></i>
                            </span>
                            <div style="color:#0f172a; font-weight:600; font-size:0.95rem; margin-top:0.25rem;"
                                id="displayDueDay">
                                Día <?= $community['billing_due_day'] ?> del mes
                            </div>
                        </div>
                        <div>
                            <span
                                style="color:#64748b; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; font-weight:500; display:flex; align-items:center; gap:0.3rem;">
                                Mes de Inicio de Facturación
                                <i class="bi bi-lock" style="font-size:0.65rem; color:#94a3b8;"
                                    title="Este valor se estableció al activar la facturación y no se puede cambiar."></i>
                                <i class="bi bi-info-circle" style="font-size:0.7rem; color:#94a3b8;"
                                    title="Fecha en la que se empezaron a generar cargos automáticos."></i>
                            </span>
                            <div style="color:#0f172a; font-weight:600; font-size:0.95rem; margin-top:0.25rem;"
                                id="displayBillingStart">
                                <?php if ($community['billing_start_date']): ?>
                                    <?php
                                    $months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
                                    $startDate = new \DateTime($community['billing_start_date']);
                                    echo $months[(int) $startDate->format('n') - 1] . ' de ' . $startDate->format('Y');
                                    ?>
                                <?php else: ?>
                                    <span style="color:#94a3b8;">No configurado</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <button type="button" class="btn-cfg-cancel"
                            style="padding:0.35rem 0.8rem; font-size:0.85rem; display:inline-flex; align-items:center; gap:0.4rem;"
                            onclick="openPaymentConfigModal()">
                            <i class="bi bi-pencil"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══ TAB: Perfil ═══ -->
        <div class="cfg-tab-panel" id="tabProfile">
            <article class="account-premium-card">
                <h3 style="font-size: 1.25rem; font-weight: 500; color: #0f172a; margin-bottom: 0;">Informaci&oacute;n
                    Personal</h3>

                <div class="account-form-grid">
                    <!-- Left: Avatar -->
                    <div class="text-center">
                        <div class="account-avatar-wrap">
                            <div class="cfg-avatar" id="myAvatarDisplay">
                                <?php if ($me['avatar']): ?>
                                    <img src="<?= base_url('media/image/avatars/' . $me['avatar']) ?>" alt="Avatar"
                                        id="myAvatarPreview">
                                <?php else: ?>
                                    <span id="myLogoInitial"><?= esc($me['initial']) ?></span>
                                <?php endif; ?>
                            </div>
                            <button type="button" class="account-upload-btn" id="btnMyAvatar"
                                aria-label="Subir foto de perfil">
                                <i class="bi bi-camera"></i>
                            </button>
                            <input type="file" id="inputMyAvatar" accept="image/*" hidden>
                        </div>
                        <span class="account-upload-text d-block mt-2">Hacer clic para subir imagen<br>de perfil</span>
                    </div>

                    <!-- Right: Form -->
                    <div class="account-form-wrap">
                        <form id="formMyProfile">
                            <div class="mb-4">
                                <label for="inputMyName" class="form-label"
                                    style="font-size: 0.85rem; font-weight: 500;">Nombre</label>
                                <input type="text" class="form-control" id="inputMyName"
                                    value="<?= esc($me['first_name']) ?>" required>
                            </div>
                            <div class="mb-4">
                                <label for="inputMyLastName" class="form-label"
                                    style="font-size: 0.85rem; font-weight: 500;">Apellido</label>
                                <input type="text" class="form-control" id="inputMyLastName"
                                    value="<?= esc($me['last_name']) ?>">
                            </div>
                            <div class="mb-4">
                                <label for="inputMyEmail" class="form-label"
                                    style="font-size: 0.85rem; font-weight: 500;">Correo Electr&oacute;nico</label>
                                <input type="email" class="form-control bg-light" id="inputMyEmail"
                                    value="<?= esc($me['email']) ?>" readonly disabled>
                            </div>
                            <div class="mb-4">
                                <label for="inputMyPhone" class="form-label"
                                    style="font-size: 0.85rem; font-weight: 500;">Tel&eacute;fono</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="bi bi-telephone text-muted"
                                            style="font-size: 0.9rem;"></i></span>
                                    <input type="text" class="form-control" id="inputMyPhone"
                                        value="<?= esc($me['phone']) ?>" placeholder="+52 55 ...">
                                </div>
                            </div>
                            <!-- Language excluded as requested -->
                            <div class="text-start mt-2">
                                <button type="submit" class="btn-cfg-primary" id="btnSaveMyProfile">
                                    <i class="bi bi-save"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </article>
        </div>

        <!-- ═══ TAB: Seguridad ═══ -->
        <div class="cfg-tab-panel" id="tabSecurity">
            <article class="account-premium-card">
                <h3 style="font-size: 1.25rem; font-weight: 500; color: #0f172a; margin-bottom: 2rem;">Seguridad</h3>

                <div style="max-width: 460px;">
                    <h4 style="font-size: 1.05rem; font-weight: 500; color: #1e293b; margin-bottom: 0.25rem;">Cambiar
                        Contrase&ntilde;a</h4>
                    <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">Actualiza tu contrase&ntilde;a
                        para mantener tu cuenta segura.</p>

                    <form id="formChangePwd">
                        <div class="mb-4">
                            <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Contrase&ntilde;a
                                Actual <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="inputPwdCurrent" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Nueva
                                Contrase&ntilde;a <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="inputPwdNew" required minlength="8"
                                placeholder="Mínimo 8 caracteres">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Confirmar Nueva
                                Contrase&ntilde;a <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="inputPwdConfirm" required minlength="8">
                        </div>
                        <div class="text-start mt-2">
                            <button type="submit" class="btn-cfg-primary" id="btnSavePwd">
                                <i class="bi bi-shield-lock"></i> Actualizar Contrase&ntilde;a
                            </button>
                        </div>
                    </form>
                </div>
            </article>
        </div>

        <!-- ═══ TAB: Suscripción ═══ -->
        <div class="cfg-tab-panel" id="tabSubscription">
            <h3>Suscripción</h3>
            <p class="subtitle">Gestiona el plan de tu condominio y ciclo de facturación</p>

            <article style="margin-bottom: 2rem;">

                <div id="subscriptionContent">
                    <div style="text-align:center;padding:3rem;color:#94a3b8;">
                        <div class="spinner-border spinner-border-sm" role="status"></div> 
                        <span class="ms-2">Cargando información del plan...</span>
                    </div>
                </div>
            </article>
        </div>

        <!-- ═══ TAB: Avanzado ═══ -->
        <div class="cfg-tab-panel" id="tabAdvanced">
            <h3>Avanzado</h3>
            <p class="subtitle">Configuraciones avanzadas y acciones irreversibles.</p>

            <article class="cfg-card" style="border: 1px solid #fecaca; background: #fff;">
                <div style="padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem; color: #dc2626; margin-bottom: 0.5rem;">
                        <i class="bi bi-exclamation-triangle" style="font-size: 1.25rem;"></i>
                        <h4 style="margin: 0; font-size: 1.1rem; font-weight: 600;">Zona de Peligro</h4>
                    </div>
                    <p style="color: #64748b; font-size: 0.9rem; margin-bottom: 1.5rem;">Acciones irreversibles que afectan a toda la comunidad.</p>
                    
                    <div style="border-top: 1px solid #f1f5f9; margin-bottom: 1.5rem;"></div>
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <strong style="color: #0f172a; font-size: 0.95rem; display: block; margin-bottom: 0.2rem;">Eliminar comunidad</strong>
                            <span style="color: #64748b; font-size: 0.85rem;">Elimina permanentemente esta comunidad y todos sus datos.</span>
                        </div>
                        <button type="button" class="btn" onclick="promptDeleteCommunity('<?= esc(addslashes($community['name'])) ?>')" style="background: #ef4444; color: white; font-weight: 500; font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 0.375rem; border: none; display: flex; align-items: center; gap: 0.4rem; transition: background 0.2s;">
                            <i class="bi bi-trash3" style="margin:0;"></i> Eliminar
                        </button>
                    </div>
                </div>
            </article>
        </div>

    </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('modals') ?>

<!-- ═══════════════════════════════════════════════ -->
<!-- Modal: Agregar/Editar Sección                   -->
<!-- ═══════════════════════════════════════════════ -->
<div class="modal fade cfg-modal" id="modalSection" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:550px;">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="modal-title" id="modalSectionTitle">Agregar Secci&oacute;n</h5>
                        <p class="cfg-modal-subtitle mb-0" id="modalSectionSubtitle">Agrega una nueva secci&oacute;n
                            para organizar tus unidades.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="formSection">
                    <input type="hidden" id="inputSectionId" value="">

                    <div class="mb-4">
                        <label for="inputSectionName" style="font-weight: 500; font-size: 0.9rem;">Nombre de la
                            Secci&oacute;n <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="inputSectionName"
                            placeholder="ej., Torre A, Bloque 1, Calle Principal" required>
                    </div>

                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <label style="font-weight: 500; font-size: 0.9rem; margin: 0;">Unidades asignadas (<span
                                id="countSelectedUnits">0</span>)</label>
                    </div>

                    <div class="d-flex gap-2 mb-2">
                        <div class="input-group" style="flex-grow: 1;">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" class="form-control" id="searchSectionUnits"
                                placeholder="Buscar unidades...">
                        </div>
                        <button type="button" class="btn btn-light border" id="btnSelectAllUnits"
                            style="font-size: 0.85rem; padding: 0.35rem 0.75rem;">Seleccionar todo</button>
                        <button type="button" class="btn btn-light border" id="btnDeselectAllUnits"
                            style="font-size: 0.85rem; padding: 0.35rem 0.75rem;">Deseleccionar</button>
                    </div>

                    <p class="text-muted mb-3" style="font-size: 0.8rem;">Tip: Utiliza el buscador para filtrar
                        rápidamente.</p>

                    <div class="unit-list-container border rounded bg-white p-3"
                        style="height: 250px; overflow-y: auto;">
                        <div id="sectionUnitsWrapper" class="row g-2">
                            <!-- Units injected via JS -->
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer bg-light border-top-0 d-flex justify-content-end p-3">
                <button type="button" class="btn btn-light bg-white border" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" form="formSection" class="btn-cfg-primary" id="btnSaveSection">
                    <i class="bi bi-floppy"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════ -->
<!-- Modal: Eliminar Sección                         -->
<!-- ═══════════════════════════════════════════════ -->
<div class="modal fade cfg-modal" id="modalDeleteSection" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
        <div class="modal-content">
            <div class="modal-body p-4">
                <h5 class="mb-3" style="font-weight: 500; color: #0f172a;">Eliminar Secci&oacute;n</h5>
                <p id="deleteSectionMsg" style="color: #3F67AC; font-size: 0.95rem;">¿Est&aacute;s seguro que deseas
                    eliminar esta secci&oacute;n? Las unidades asignadas tendr&aacute;n su secci&oacute;n eliminada.</p>
                <input type="hidden" id="deleteSectionId">

                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="btnConfirmDeleteSection">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════ -->
<!-- Modal: Agregar Administrador                   -->
<!-- ═══════════════════════════════════════════════ -->
<div class="modal fade cfg-modal" id="modalAddAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="modal-title">Agregar Administrador</h5>
                        <p class="cfg-modal-subtitle mb-0">El administrador podr&aacute; gestionar este condominio. Si
                            no tiene cuenta, se crear&aacute; autom&aacute;ticamente.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="formAddAdmin">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="inputAdminFirstName">Nombre <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="inputAdminFirstName" placeholder="Nombre"
                                required>
                        </div>
                        <div class="col-6">
                            <label for="inputAdminLastName">Apellido</label>
                            <input type="text" class="form-control" id="inputAdminLastName" placeholder="Apellido">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="inputAdminEmail">Correo electr&oacute;nico <span
                                class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="inputAdminEmail" placeholder="admin@ejemplo.com"
                            required>
                        <div class="form-text mt-1">Si ya existe un usuario con este correo, se vincular&aacute; como
                            administrador de esta comunidad.</div>
                    </div>
                    <div class="mb-2">
                        <label for="inputAdminPassword" class="d-flex align-items-center justify-content-between">
                            <span>Contrase&ntilde;a <span class="text-danger">*</span></span>
                            <button type="button" class="btn btn-sm text-primary p-0 border-0 bg-transparent"
                                style="font-size:0.78rem;font-weight:600;" id="btnGeneratePassword">
                                <i class="bi bi-magic"></i> Generar
                            </button>
                        </label>
                        <div class="pwd-input-wrap">
                            <input type="password" class="form-control" id="inputAdminPassword"
                                placeholder="M&iacute;nimo 8 caracteres" required minlength="8">
                            <div class="pwd-actions">
                                <button type="button" id="btnTogglePwd" title="Mostrar/ocultar"><i
                                        class="bi bi-eye"></i></button>
                                <button type="button" id="btnCopyPwd" title="Copiar"><i
                                        class="bi bi-clipboard"></i></button>
                            </div>
                        </div>
                        <div class="pwd-strength">
                            <div class="pwd-strength-bar" id="pwdStrengthBar"></div>
                        </div>
                        <div class="form-text mt-1" id="pwdHelpText">Letras, n&uacute;meros y al menos un
                            car&aacute;cter especial</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-cfg-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-cfg-primary" id="btnSaveAdmin">
                    <i class="bi bi-person-plus"></i> Agregar
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ═══════════════════════════════════════════════ -->
<!-- Modal: Agregar/Editar Recordatorio de Pago    -->
<!-- ═══════════════════════════════════════════════ -->
<div class="modal fade cfg-modal" id="modalPaymentReminder" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:500px;">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="modal-title" id="modalPaymentReminderTitle">Agregar Recordatorio</h5>
                        <p class="cfg-modal-subtitle mb-0">Configura cuándo y qué tipo de notificación de recordatorio
                            recibirán los residentes.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="formPaymentReminder">
                    <input type="hidden" id="inputReminderId" value="">

                    <div class="row g-3 mb-3">
                        <div class="col-8">
                            <label for="inputReminderTrigger">Cuándo Enviar</label>
                            <select class="form-select" id="inputReminderTrigger"
                                onchange="updateReminderTriggerValueLabel()">
                                <option value="start_of_month">Inicio del Mes</option>
                                <option value="days_before_due">Días antes del vencimiento</option>
                                <option value="due_date">El día del vencimiento</option>
                                <option value="days_after_due">Días después del vencimiento</option>
                                <option value="specific_day">Día específico del mes</option>
                            </select>
                        </div>
                        <div class="col-4">
                            <label id="labelReminderTriggerValue" for="inputReminderTriggerValue">Día del Mes</label>
                            <input type="number" class="form-control" id="inputReminderTriggerValue" value="1" min="1"
                                max="31">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="inputReminderPreset">Tipo de Mensaje</label>
                        <select class="form-select" id="inputReminderPreset" onchange="applyReminderPreset()">
                            <option value="custom">-- Personalizado --</option>
                            <option value="1">Recordatorio inicio de mes</option>
                            <option value="2">Recordatorio fecha limite inminente</option>
                            <option value="3">Recordatorio ultimo dia</option>
                            <option value="4">Recordatorio de atraso suave</option>
                            <option value="5">Recordatorio de atraso urgente</option>
                        </select>
                    </div>

                    <div class="mb-2">
                        <label for="inputReminderMessageBody" class="d-flex align-items-center gap-1">
                            <i class="bi bi-chat-left-text text-muted"></i> Mensaje
                        </label>
                        <div
                            style="border: 1px solid #e2e8f0; border-radius: 0.45rem; padding: 0.85rem; background: #f8fafc;">
                            <input type="text" id="inputReminderMessageTitle"
                                class="form-control border-0 bg-transparent fw-bold p-0 mb-2"
                                style="font-size: 0.95rem; color:#0f172a;" placeholder="Título del Mensaje" required>
                            <textarea id="inputReminderMessageBody" class="form-control border-0 bg-transparent p-0"
                                style="font-size: 0.85rem; color:#3F67AC; resize:none;" rows="3"
                                placeholder="Cuerpo del mensaje..." required></textarea>
                        </div>
                        <div class="form-text mt-1">Usa {x} en el mensaje para que se reemplace por el número de días
                            que configures.</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-danger" id="btnDeleteReminder"
                    style="display:none; font-size:0.85rem; font-weight:600; border-radius:0.45rem; padding:0.4rem 0.8rem;"
                    onclick="deletePaymentReminder()"><i class="bi bi-trash"></i> Eliminar</button>
                <div class="d-flex gap-2 ms-auto">
                    <button type="button" class="btn-cfg-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-cfg-primary" id="btnSaveReminder">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════ -->
<!-- Modal: Editar Información (Nombre + Zona)      -->
<!-- ═══════════════════════════════════════════════ -->
<div class="modal fade cfg-modal" id="modalEditInfo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="modal-title">Editar Informaci&oacute;n</h5>
                        <p class="cfg-modal-subtitle mb-0">Actualiza el nombre y zona horaria de tu comunidad.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="formEditInfo">
                    <div class="mb-3">
                        <label for="inputInfoName">Nombre de la Comunidad</label>
                        <input type="text" class="form-control" id="inputInfoName"
                            value="<?= esc($community['name']) ?>" required maxlength="150">
                    </div>
                    <div class="mb-2">
                        <label for="selectTimezone" class="d-flex align-items-center gap-1">
                            <i class="bi bi-globe tz-icon"></i> Zona Horaria
                        </label>
                        <select class="form-select" id="selectTimezone">
                            <option value="America/Mexico_City" <?= $community['timezone'] === 'America/Mexico_City' ? 'selected' : '' ?>>Mexico City (GMT-6)</option>
                            <option value="America/Cancun" <?= $community['timezone'] === 'America/Cancun' ? 'selected' : '' ?>>Cancun (GMT-5)</option>
                            <option value="America/Monterrey" <?= $community['timezone'] === 'America/Monterrey' ? 'selected' : '' ?>>Monterrey (GMT-6)</option>
                            <option value="America/Tijuana" <?= $community['timezone'] === 'America/Tijuana' ? 'selected' : '' ?>>Tijuana (GMT-8)</option>
                            <option value="America/Hermosillo" <?= $community['timezone'] === 'America/Hermosillo' ? 'selected' : '' ?>>Hermosillo (GMT-7)</option>
                            <option value="America/Mazatlan" <?= $community['timezone'] === 'America/Mazatlan' ? 'selected' : '' ?>>Mazatlan (GMT-7)</option>
                            <option value="America/Chihuahua" <?= $community['timezone'] === 'America/Chihuahua' ? 'selected' : '' ?>>Chihuahua (GMT-6)</option>
                            <option value="America/Bogota" <?= $community['timezone'] === 'America/Bogota' ? 'selected' : '' ?>>Bogota (GMT-5)</option>
                            <option value="America/Lima" <?= $community['timezone'] === 'America/Lima' ? 'selected' : '' ?>>Lima (GMT-5)</option>
                            <option value="America/Santiago" <?= $community['timezone'] === 'America/Santiago' ? 'selected' : '' ?>>Santiago (GMT-4)</option>
                            <option value="America/Buenos_Aires" <?= $community['timezone'] === 'America/Buenos_Aires' ? 'selected' : '' ?>>Buenos Aires (GMT-3)</option>
                            <option value="America/New_York" <?= $community['timezone'] === 'America/New_York' ? 'selected' : '' ?>>New York (GMT-5)</option>
                            <option value="America/Chicago" <?= $community['timezone'] === 'America/Chicago' ? 'selected' : '' ?>>Chicago (GMT-6)</option>
                            <option value="America/Los_Angeles" <?= $community['timezone'] === 'America/Los_Angeles' ? 'selected' : '' ?>>Los Angeles (GMT-8)</option>
                            <option value="Europe/Madrid" <?= $community['timezone'] === 'Europe/Madrid' ? 'selected' : '' ?>>Madrid (GMT+1)</option>
                        </select>
                        <div class="form-text mt-1">Se usa para per&iacute;odos de facturaci&oacute;n, notificaciones y
                            reportes</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-cfg-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-cfg-primary" id="btnSaveInfo">
                    <i class="bi bi-check2"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════ -->
<!-- Modal: Editar Dirección                        -->
<!-- ═══════════════════════════════════════════════ -->
<div class="modal fade cfg-modal" id="modalEditAddress" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:520px;">
        <div class="modal-content">
            <div class="modal-header d-block">
                <div class="d-flex align-items-start justify-content-between">
                    <div>
                        <h5 class="modal-title">Editar Direcci&oacute;n</h5>
                        <p class="cfg-modal-subtitle mb-0">Actualiza la informaci&oacute;n de direcci&oacute;n de la
                            comunidad a continuaci&oacute;n.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
            </div>
            <div class="modal-body">
                <form id="formEditAddress">
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="inputCountry" class="d-flex align-items-center gap-1">
                                Pa&iacute;s <i class="bi bi-info-circle text-muted" style="font-size:0.72rem;"
                                    title="País de la comunidad"></i>
                            </label>
                            <input type="text" class="form-control" id="inputCountry"
                                value="<?= esc($community['country']) ?>">
                        </div>
                        <div class="col-6">
                            <label for="inputState" class="d-flex align-items-center gap-1">
                                Estado/Provincia <i class="bi bi-info-circle text-muted" style="font-size:0.72rem;"
                                    title="Estado o provincia"></i>
                            </label>
                            <input type="text" class="form-control" id="inputState"
                                value="<?= esc($community['state'] !== 'Sin definir' ? $community['state'] : '') ?>">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label for="inputCity">Ciudad</label>
                            <input type="text" class="form-control" id="inputCity"
                                value="<?= esc($community['city'] !== 'Sin definir' ? $community['city'] : '') ?>">
                        </div>
                        <div class="col-6">
                            <label for="inputPostalCode">C&oacute;digo Postal</label>
                            <input type="text" class="form-control" id="inputPostalCode"
                                value="<?= esc($community['postal_code'] !== 'Sin definir' ? $community['postal_code'] : '') ?>">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="inputStreet">Calle</label>
                        <input type="text" class="form-control" id="inputStreet"
                            value="<?= esc($community['street'] !== 'Sin definir' ? $community['street'] : '') ?>">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-end gap-2">
                <button type="button" class="btn-cfg-cancel" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn-cfg-primary" id="btnSaveAddress">
                    <i class="bi bi-floppy"></i> Guardar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ Modal: Editar Datos Bancarios ═══ -->
<div class="modal fade" id="modalBankDetails" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cfg-modal-content"
            style="border:none; border-radius:1rem; overflow:hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
            <div class="modal-header cfg-modal-header"
                style="padding: 1.5rem 2rem; background: #fff; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h5 class="modal-title"
                        style="font-weight: 700; color: #0f172a; font-size: 1.25rem; letter-spacing: -0.02em;">Editar
                        Datos Bancarios</h5>
                    <p class="cfg-modal-subtitle mb-0" style="color: #64748b; font-size: 0.9rem; margin-top: 0.25rem;">
                        Información para transferencias de residentes.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"
                    style="background-size: 0.8rem;"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <form id="formBankDetails">
                    <div class="mb-4">
                        <label for="inputBankName"
                            style="display: block; font-weight: 600; color: #334155; font-size: 0.85rem; margin-bottom: 0.5rem;">Nombre
                            del Banco</label>
                        <div style="position: relative;">
                            <i class="bi bi-bank"
                                style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                            <input type="text" class="form-control" id="inputBankName"
                                placeholder="Ej: BBVA, Banorte, HSBC" value="<?= esc($community['bank_name'] ?? '') ?>"
                                style="padding-left: 2.75rem; height: 3rem; border-radius: 0.6rem; border: 1px solid #e2e8f0; font-size: 0.95rem;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="inputBankClabe"
                            style="display: block; font-weight: 600; color: #334155; font-size: 0.85rem; margin-bottom: 0.5rem;">CLABE
                            SPEI (18 dígitos)</label>
                        <div style="position: relative;">
                            <i class="bi bi-hash"
                                style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                            <input type="text" class="form-control" id="inputBankClabe"
                                placeholder="000 000 0000000000 0" maxlength="18"
                                value="<?= esc($community['bank_clabe'] ?? '') ?>"
                                style="padding-left: 2.75rem; height: 3rem; border-radius: 0.6rem; border: 1px solid #e2e8f0; font-size: 0.95rem; letter-spacing: 0.05em;">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="inputBankRfc"
                            style="display: block; font-weight: 600; color: #334155; font-size: 0.85rem; margin-bottom: 0.5rem;">RFC
                            del Condominio</label>
                        <div style="position: relative;">
                            <i class="bi bi-person-badge"
                                style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                            <input type="text" class="form-control" id="inputBankRfc" placeholder="RFC" maxlength="13"
                                value="<?= esc($community['bank_rfc'] ?? '') ?>"
                                style="padding-left: 2.75rem; height: 3rem; border-radius: 0.6rem; border: 1px solid #e2e8f0; font-size: 0.95rem; text-transform: uppercase;">
                        </div>
                    </div>

                    <div class="mt-4 mb-0">
                        <label for="inputBankCard"
                            style="display: block; font-weight: 600; color: #334155; font-size: 0.85rem; margin-bottom: 0.5rem;">Número de Tarjeta (16 dígitos)</label>
                        <div style="position: relative;">
                            <i class="bi bi-credit-card"
                                style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8;"></i>
                            <input type="text" class="form-control" id="inputBankCard"
                                placeholder="0000 0000 0000 0000" maxlength="16"
                                value="<?= esc($community['bank_card'] ?? '') ?>"
                                style="padding-left: 2.75rem; height: 3rem; border-radius: 0.6rem; border: 1px solid #e2e8f0; font-size: 0.95rem; letter-spacing: 0.05em;">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer"
                style="padding: 1.25rem 2rem; background: #f8fafc; border-top: 1px solid #f1f5f9; gap: 0.75rem;">
                <button type="button" class="btn-cfg-cancel" data-bs-dismiss="modal"
                    style="font-weight: 600; padding: 0.6rem 1.2rem;">Cancelar</button>
                <button type="button" class="btn-cfg-primary" id="btnSaveBankDetails"
                    style="font-weight: 600; padding: 0.6rem 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-radius: 0.5rem;">
                    <i class="bi bi-floppy"></i> Guardar Cambios
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ═══ Modal: Editar Configuración de Pagos ═══ -->
<div class="modal fade" id="modalPaymentConfig" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
        <div class="modal-content cfg-modal-content"
            style="border:none; border-radius:1rem; overflow:hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
            <div class="modal-header cfg-modal-header"
                style="padding: 1.5rem 2rem; background: #fff; border-bottom: 1px solid #f1f5f9;">
                <div>
                    <h5 class="modal-title"
                        style="font-weight: 700; color: #0f172a; font-size: 1.25rem; letter-spacing: -0.02em;">
                        Configuración de Pagos</h5>
                    <p class="cfg-modal-subtitle mb-0" style="color: #64748b; font-size: 0.9rem; margin-top: 0.25rem;">
                        Define moneda y fechas de vencimiento.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="padding: 2rem;">
                <form id="formPaymentConfig">
                    <div class="mb-4">
                        <label for="selectCurrency"
                            style="display: block; font-weight: 600; color: #334155; font-size: 0.85rem; margin-bottom: 0.5rem;">Moneda
                            de Operación</label>
                        <select class="form-select" id="selectCurrency"
                            style="height: 3rem; border-radius: 0.6rem; border: 1px solid #e2e8f0; font-size: 0.95rem; cursor: pointer;">
                            <option value="MXN" <?= ($community['currency'] ?? 'MXN') === 'MXN' ? 'selected' : '' ?>>MXN -
                                Peso Mexicano</option>
                            <option value="USD" <?= ($community['currency'] ?? 'MXN') === 'USD' ? 'selected' : '' ?>>USD -
                                Dólar Estadounidense</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="selectDueDay"
                            style="display: flex; align-items: center; gap: 0.4rem; font-weight: 600; color: #334155; font-size: 0.85rem; margin-bottom: 0.5rem;">
                            <i class="bi bi-calendar-event" style="color:#64748b;"></i> Día Límite de Pago
                        </label>
                        <select class="form-select" id="selectDueDay"
                            style="height: 3rem; border-radius: 0.6rem; border: 1px solid #e2e8f0; font-size: 0.95rem; cursor: pointer;">
                            <?php for ($d = 1; $d <= 28; $d++): ?>
                                <option value="<?= $d ?>" <?= (int) ($community['billing_due_day'] ?? 15) === $d ? 'selected' : '' ?>>Día <?= $d ?> de cada mes</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <div
                        style="background: #f0f9ff; border: 1px solid #e0f2fe; border-radius: 0.75rem; padding: 1rem; display: flex; gap: 0.75rem;">
                        <i class="bi bi-info-circle-fill"
                            style="color: #0ea5e9; font-size: 1.1rem; margin-top: 0.1rem;"></i>
                        <p style="color: #0369a1; font-size: 0.85rem; margin: 0; line-height: 1.4;">
                            Los residentes que no paguen antes de este día serán marcados automáticamente como
                            <strong>morosos</strong> en el sistema.
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer"
                style="padding: 1.25rem 2rem; background: #f8fafc; border-top: 1px solid #f1f5f9; gap: 0.75rem;">
                <button type="button" class="btn-cfg-cancel" data-bs-dismiss="modal"
                    style="font-weight: 600; padding: 0.6rem 1.2rem;">Cancelar</button>
                <button type="button" class="btn-cfg-primary" id="btnSavePaymentConfig"
                    style="font-weight: 600; padding: 0.6rem 1.5rem; display: flex; align-items: center; gap: 0.5rem; border-radius: 0.5rem;">
                    <i class="bi bi-floppy"></i> Guardar Configuración
                </button>
            </div>
        </div>
    </div>
</div>



<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const BASE = '<?= base_url("admin/configuracion") ?>';

        // ───────────── Utility: Toast ─────────────
        function showToast(message, type = 'success') {
            const existing = document.querySelector('.cfg-toast');
            if (existing) existing.remove();
            const toast = document.createElement('div');
            toast.className = `cfg-toast ${type}`;
            toast.innerHTML = `<i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i> ${message}`;
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateY(12px)'; }, 2800);
            setTimeout(() => toast.remove(), 3200);
        }

        // ───────────── Edit Info Modal ─────────────
        const modalInfoEl = document.getElementById('modalEditInfo');
        const modalInfo = bootstrap.Modal.getOrCreateInstance(modalInfoEl);

        document.getElementById('btnEditInfo').addEventListener('click', () => modalInfo.show());

        document.getElementById('btnSaveInfo').addEventListener('click', async () => {
            const name = document.getElementById('inputInfoName').value.trim();
            const timezone = document.getElementById('selectTimezone').value;

            if (!name) { showToast('El nombre es obligatorio', 'error'); return; }

            const btn = document.getElementById('btnSaveInfo');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            try {
                const fd = new FormData();
                fd.append('name', name);
                fd.append('timezone', timezone);

                const resp = await fetch(`${BASE}/update-info`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    // Update displays
                    document.getElementById('displayName').textContent = data.name;
                    document.getElementById('displayTimezone').textContent = data.timezone_label;

                    // Update sidebar
                    const sidebarNameEl = document.getElementById('sidebarCondoName');
                    if (sidebarNameEl) sidebarNameEl.textContent = data.name;

                    // Update avatar initial if no logo
                    const logoInitial = document.getElementById('logoInitial');
                    if (logoInitial) logoInitial.textContent = data.initial;

                    const sidebarInitialEl = document.getElementById('sidebarLogoInitial');
                    if (sidebarInitialEl) sidebarInitialEl.textContent = data.initial;

                    modalInfo.hide();
                    showToast('Informaci\u00f3n actualizada correctamente');
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                }
            } catch (e) {
                showToast('Error de conexi\u00f3n', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check2"></i> Guardar';
            }
        });

        // ───────────── Edit Address Modal ─────────────
        const modalAddrEl = document.getElementById('modalEditAddress');
        const modalAddr = bootstrap.Modal.getOrCreateInstance(modalAddrEl);

        document.getElementById('btnEditAddress').addEventListener('click', () => modalAddr.show());

        document.getElementById('btnSaveAddress').addEventListener('click', async () => {
            const btn = document.getElementById('btnSaveAddress');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            try {
                const fd = new FormData();
                fd.append('country', document.getElementById('inputCountry').value.trim());
                fd.append('state', document.getElementById('inputState').value.trim());
                fd.append('city', document.getElementById('inputCity').value.trim());
                fd.append('postal_code', document.getElementById('inputPostalCode').value.trim());
                fd.append('street', document.getElementById('inputStreet').value.trim());

                const resp = await fetch(`${BASE}/update-address`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    document.getElementById('displayStreet').textContent = data.street;
                    document.getElementById('displayCity').textContent = data.city;
                    document.getElementById('displayState').textContent = data.state;
                    document.getElementById('displayPostalCode').textContent = data.postal_code;
                    document.getElementById('displayCountry').textContent = data.country;

                    // Update sidebar city
                    const sidebarCityEl = document.getElementById('sidebarCondoCity');
                    if (sidebarCityEl) sidebarCityEl.textContent = data.city;

                    modalAddr.hide();
                    showToast('Direcci\u00f3n actualizada correctamente');
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                }
            } catch (e) {
                showToast('Error de conexi\u00f3n', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-floppy"></i> Guardar';
            }
        });

        // ───────────── Logo Upload ─────────────
        const inputLogo = document.getElementById('inputLogo');
        document.getElementById('btnUploadLogo').addEventListener('click', () => inputLogo.click());
        document.getElementById('linkUploadLogo').addEventListener('click', () => inputLogo.click());

        inputLogo.addEventListener('change', async () => {
            const file = inputLogo.files[0];
            if (!file) return;

            const fd = new FormData();
            fd.append('logo', file);

            try {
                const resp = await fetch(`${BASE}/upload-logo`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    const avatarDisplay = document.getElementById('avatarDisplay');
                    avatarDisplay.innerHTML = `<img src="${data.url}" alt="Logo" id="logoPreview">`;

                    // Update sidebar logo
                    const sidebarLogoInitial = document.getElementById('sidebarLogoInitial');
                    if (sidebarLogoInitial) {
                        const img = document.createElement('img');
                        img.src = data.url;
                        img.alt = 'Logo';
                        img.className = 'condo-logo border';
                        img.style.objectFit = 'cover';
                        img.id = 'sidebarLogoImg';
                        sidebarLogoInitial.replaceWith(img);
                    }
                    const sidebarLogoImg = document.getElementById('sidebarLogoImg');
                    if (sidebarLogoImg) {
                        sidebarLogoImg.src = data.url;
                    }

                    showToast('Logo actualizado correctamente');
                } else {
                    showToast(data.message || 'Error al subir', 'error');
                }
            } catch (e) {
                showToast('Error de conexi\u00f3n', 'error');
            }
            inputLogo.value = '';
        });

        // ───────────── Cover Upload ─────────────
        const inputCover = document.getElementById('inputCover');
        document.getElementById('btnUploadCover').addEventListener('click', (e) => { e.stopPropagation(); inputCover.click(); });
        document.getElementById('linkUploadCover').addEventListener('click', () => inputCover.click());

        inputCover.addEventListener('change', async () => {
            const file = inputCover.files[0];
            if (!file) return;

            const fd = new FormData();
            fd.append('cover', file);

            try {
                const resp = await fetch(`${BASE}/upload-cover`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    const coverArea = document.getElementById('coverArea');
                    let existingImg = document.getElementById('coverPreview');
                    if (!existingImg) {
                        existingImg = document.createElement('img');
                        existingImg.id = 'coverPreview';
                        existingImg.alt = 'Portada';
                        coverArea.insertBefore(existingImg, coverArea.firstChild);
                    }
                    existingImg.src = data.url;

                    showToast('Portada actualizada correctamente');
                } else {
                    showToast(data.message || 'Error al subir', 'error');
                }
            } catch (e) {
                showToast('Error de conexi\u00f3n', 'error');
            }
            inputCover.value = '';
        });

        // ═══════════════════════════════════════════════════
        //  TAB SWITCHING
        // ═══════════════════════════════════════════════════
        const tabMap = {
            'general': 'tabGeneral',
            'admins': 'tabAdmins',
            'sections': 'tabSections',
            'profile': 'tabProfile',
            'security': 'tabSecurity',
            'financialAccess': 'tabFinancialAccess',
            'wallAccess': 'tabWallAccess',
            'paymentReminders': 'tabPaymentReminders',
            'delinquencyRestrictions': 'tabDelinquencyRestrictions',
            'financeSettings': 'tabFinanceSettings',
            'subscription': 'tabSubscription',
            'advanced': 'tabAdvanced'
        };

        document.querySelectorAll('.cfg-nav-link[data-tab]').forEach(btn => {
            btn.addEventListener('click', () => {
                const tabKey = btn.dataset.tab;
                if (!tabMap[tabKey]) return;

                // Update sidebar active state
                document.querySelectorAll('.cfg-nav-link').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');

                // Show correct tab panel
                document.querySelectorAll('.cfg-tab-panel').forEach(p => p.classList.remove('active'));
                const panel = document.getElementById(tabMap[tabKey]);
                if (panel) panel.classList.add('active');

                // Lazy-load admins on first click
                if (tabKey === 'admins' && !adminsLoaded) {
                    loadAdmins();
                }
                // Lazy-load subscription on first click
                if (tabKey === 'subscription' && !subscriptionLoaded) {
                    loadSubscription();
                }
            });
        });

        // ═══════════════════════════════════════════════════
        //  ADMINISTRADORES MODULE
        // ═══════════════════════════════════════════════════
        let adminsLoaded = false;
        const adminContainer = document.getElementById('adminListContainer');
        const ADMIN_COLORS = ['color-1', 'color-2', 'color-3', 'color-4', 'color-5'];

        function getInitial(firstName, lastName) {
            return ((firstName || '').charAt(0) + (lastName || '').charAt(0)).toUpperCase() || '?';
        }

        function renderAdminRow(admin, index) {
            const colorClass = ADMIN_COLORS[index % ADMIN_COLORS.length];
            const initial = getInitial(admin.first_name, admin.last_name);
            const fullName = [admin.first_name, admin.last_name].filter(Boolean).join(' ');
            const isOwner = parseInt(admin.is_owner) === 1;
            const isCurrentUserOwner = <?= session()->get('is_owner') ? 'true' : 'false' ?>;

            // Badge: "Fundador" para owner, "Administrador" para co-admin
            const badgeHtml = isOwner
                ? `<span class="admin-role-badge" style="background:#eff6ff;color:#1d4ed8;border-color:#bfdbfe;">Fundador</span>`
                : `<span class="admin-role-badge">Administrador</span>`;

            // Solo el owner puede ver botones de eliminar, y no puede eliminarse a sí mismo (owner)
            const deleteBtn = (!isOwner && isCurrentUserOwner)
                ? `<button type="button" class="btn-remove-admin" data-id="${admin.assignment_id}" title="Eliminar administrador">
                        <i class="bi bi-trash3"></i>
                   </button>`
                : '';

            return `
            <div class="admin-row" data-id="${admin.assignment_id}">
                <div class="admin-avatar ${colorClass}">${initial}</div>
                <div class="admin-info">
                    <div class="admin-name">${fullName}</div>
                    <div class="admin-email">${admin.email}</div>
                </div>
                <div class="admin-tags">
                    ${badgeHtml}
                    ${deleteBtn}
                </div>
            </div>
        `;
        }

        async function loadAdmins() {
            adminContainer.innerHTML = '<div class="admin-loading"><span class="spinner-border spinner-border-sm me-2"></span> Cargando administradores...</div>';

            try {
                const resp = await fetch(`${BASE}/admins`);
                const data = await resp.json();

                if (data.success && data.admins.length > 0) {
                    adminContainer.innerHTML = data.admins.map((a, i) => renderAdminRow(a, i)).join('');
                    bindRemoveButtons();
                } else if (data.success) {
                    adminContainer.innerHTML = '<div class="admin-empty"><i class="bi bi-people"></i> No hay administradores registrados.</div>';
                } else {
                    adminContainer.innerHTML = '<div class="admin-empty"><i class="bi bi-exclamation-triangle"></i> Error al cargar.</div>';
                }

                adminsLoaded = true;
            } catch (e) {
                adminContainer.innerHTML = '<div class="admin-empty"><i class="bi bi-wifi-off"></i> Error de conexi\u00f3n.</div>';
            }
        }

        function bindRemoveButtons() {
            adminContainer.querySelectorAll('.btn-remove-admin').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const assignmentId = btn.dataset.id;
                    const row = btn.closest('.admin-row');
                    const name = row.querySelector('.admin-name').textContent;

                    const result = await Swal.fire({
                        title: '\u00bfEliminar administrador?',
                        html: `<b>${name}</b> ya no podr\u00e1 administrar esta comunidad.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'S\u00ed, eliminar',
                        cancelButtonText: 'Cancelar',
                    });

                    if (!result.isConfirmed) return;

                    try {
                        const fd = new FormData();
                        fd.append('assignment_id', assignmentId);

                        const resp = await fetch(`${BASE}/admins/remove`, { method: 'POST', body: fd });
                        const data = await resp.json();

                        if (data.success) {
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';
                            row.style.transition = 'all 0.3s';
                            setTimeout(() => {
                                row.remove();
                                if (!adminContainer.querySelector('.admin-row')) {
                                    adminContainer.innerHTML = '<div class="admin-empty"><i class="bi bi-people"></i> No hay administradores registrados.</div>';
                                }
                            }, 300);
                            showToast('Administrador eliminado correctamente');
                        } else {
                            showToast(data.message || 'Error al eliminar', 'error');
                        }
                    } catch (e) {
                        showToast('Error de conexi\u00f3n', 'error');
                    }
                });
            });
        }

        // ───────── Add Admin Modal ─────────
        const modalAddAdminEl = document.getElementById('modalAddAdmin');
        const modalAddAdmin = bootstrap.Modal.getOrCreateInstance(modalAddAdminEl);

        document.getElementById('btnAddAdmin').addEventListener('click', () => {
            document.getElementById('inputAdminFirstName').value = '';
            document.getElementById('inputAdminLastName').value = '';
            document.getElementById('inputAdminEmail').value = '';
            document.getElementById('inputAdminPassword').value = '';
            document.getElementById('inputAdminPassword').type = 'password';
            document.getElementById('btnTogglePwd').innerHTML = '<i class="bi bi-eye"></i>';
            updatePwdStrength('');
            modalAddAdmin.show();
        });

        // ───────── Password helpers ─────────
        function generatePassword(length = 12) {
            const upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const lower = 'abcdefghijklmnopqrstuvwxyz';
            const digits = '0123456789';
            const special = '!@#$%&*?';
            const all = upper + lower + digits + special;

            let pwd = '';
            pwd += upper[Math.floor(Math.random() * upper.length)];
            pwd += lower[Math.floor(Math.random() * lower.length)];
            pwd += digits[Math.floor(Math.random() * digits.length)];
            pwd += special[Math.floor(Math.random() * special.length)];

            for (let i = pwd.length; i < length; i++) {
                pwd += all[Math.floor(Math.random() * all.length)];
            }

            return pwd.split('').sort(() => Math.random() - 0.5).join('');
        }

        function updatePwdStrength(pwd) {
            const bar = document.getElementById('pwdStrengthBar');
            const help = document.getElementById('pwdHelpText');
            if (!pwd) { bar.style.width = '0%'; bar.style.background = '#e2e8f0'; help.textContent = 'Letras, n\u00fameros y al menos un car\u00e1cter especial'; return; }

            let score = 0;
            if (pwd.length >= 8) score++;
            if (pwd.length >= 12) score++;
            if (/[A-Z]/.test(pwd)) score++;
            if (/[0-9]/.test(pwd)) score++;
            if (/[^A-Za-z0-9]/.test(pwd)) score++;

            const levels = [
                { w: '20%', c: '#ef4444', t: 'Muy d\u00e9bil' },
                { w: '40%', c: '#f97316', t: 'D\u00e9bil' },
                { w: '60%', c: '#eab308', t: 'Aceptable' },
                { w: '80%', c: '#22c55e', t: 'Fuerte' },
                { w: '100%', c: '#059669', t: 'Muy fuerte' },
            ];
            const lvl = levels[Math.min(score, levels.length) - 1] || levels[0];
            bar.style.width = lvl.w;
            bar.style.background = lvl.c;
            help.textContent = lvl.t;
        }

        document.getElementById('inputAdminPassword').addEventListener('input', (e) => {
            updatePwdStrength(e.target.value);
        });

        document.getElementById('btnGeneratePassword').addEventListener('click', () => {
            const pwd = generatePassword(14);
            const input = document.getElementById('inputAdminPassword');
            input.value = pwd;
            input.type = 'text';
            document.getElementById('btnTogglePwd').innerHTML = '<i class="bi bi-eye-slash"></i>';
            updatePwdStrength(pwd);
        });

        document.getElementById('btnTogglePwd').addEventListener('click', () => {
            const input = document.getElementById('inputAdminPassword');
            const btn = document.getElementById('btnTogglePwd');
            if (input.type === 'password') {
                input.type = 'text';
                btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
            } else {
                input.type = 'password';
                btn.innerHTML = '<i class="bi bi-eye"></i>';
            }
        });

        document.getElementById('btnCopyPwd').addEventListener('click', () => {
            const val = document.getElementById('inputAdminPassword').value;
            if (val) {
                navigator.clipboard.writeText(val).then(() => showToast('Contrase\u00f1a copiada'));
            }
        });

        document.getElementById('btnSaveAdmin').addEventListener('click', async () => {
            const firstName = document.getElementById('inputAdminFirstName').value.trim();
            const lastName = document.getElementById('inputAdminLastName').value.trim();
            const email = document.getElementById('inputAdminEmail').value.trim();
            const password = document.getElementById('inputAdminPassword').value;

            if (!firstName) { showToast('El nombre es obligatorio', 'error'); return; }
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { showToast('Email inv\u00e1lido', 'error'); return; }
            if (!password || password.length < 8) { showToast('La contrase\u00f1a debe tener al menos 8 caracteres', 'error'); return; }

            const btn = document.getElementById('btnSaveAdmin');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Agregando...';

            try {
                const fd = new FormData();
                fd.append('first_name', firstName);
                fd.append('last_name', lastName);
                fd.append('email', email);
                fd.append('password', password);

                const resp = await fetch(`${BASE}/admins/add`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    modalAddAdmin.hide();
                    // Reload list to get correct indices for colors
                    await loadAdmins();
                    showToast('Administrador agregado correctamente');
                } else {
                    showToast(data.message || 'Error al agregar', 'error');
                }
            } catch (e) {
                showToast('Error de conexi\u00f3n', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-person-plus"></i> Agregar';
            }
        });

        // ───────── PERFIL Y SEGURIDAD ─────────

        // My Avatar Upload
        document.getElementById('btnMyAvatar').addEventListener('click', () => {
            document.getElementById('inputMyAvatar').click();
        });

        document.getElementById('inputMyAvatar').addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const btn = document.getElementById('btnMyAvatar');
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width:1rem;height:1rem;"></span>';
            btn.disabled = true;

            try {
                const fd = new FormData();
                fd.append('avatar', file);

                const resp = await fetch(`${BASE}/upload-avatar`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    const display = document.getElementById('myAvatarDisplay');
                    // Replace everything inside with the new image
                    display.innerHTML = `<img src="${data.url}" alt="Avatar" id="myAvatarPreview">`;
                    showToast('Foto de perfil actualizada');
                } else {
                    showToast(data.message || 'Error al actualizar', 'error');
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
            } finally {
                e.target.value = '';
                btn.innerHTML = originalIcon;
                btn.disabled = false;
            }
        });

        // Profile Form
        document.getElementById('formMyProfile').addEventListener('submit', async (e) => {
            e.preventDefault();

            const firstName = document.getElementById('inputMyName').value.trim();
            const lastName = document.getElementById('inputMyLastName').value.trim();
            const phone = document.getElementById('inputMyPhone').value.trim();

            if (!firstName) { showToast('El nombre es obligatorio', 'error'); return; }

            const btn = document.getElementById('btnSaveMyProfile');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            try {
                const fd = new FormData();
                fd.append('first_name', firstName);
                fd.append('last_name', lastName);
                fd.append('phone', phone);

                const resp = await fetch(`${BASE}/update-profile`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    showToast('Perfil actualizado exitosamente');
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        // Password Update Form
        document.getElementById('formChangePwd').addEventListener('submit', async (e) => {
            e.preventDefault();

            const curPwd = document.getElementById('inputPwdCurrent').value;
            const newPwd = document.getElementById('inputPwdNew').value;
            const confPwd = document.getElementById('inputPwdConfirm').value;

            if (newPwd.length < 8) { showToast('La nueva contraseña debe tener al menos 8 caracteres', 'error'); return; }
            if (newPwd !== confPwd) { showToast('La confirmación de contraseña no coincide', 'error'); return; }

            const btn = document.getElementById('btnSavePwd');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            try {
                const fd = new FormData();
                fd.append('current_password', curPwd);
                fd.append('new_password', newPwd);
                fd.append('confirm_password', confPwd);

                const resp = await fetch(`${BASE}/update-password`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    showToast('Contraseña cambiada exitosamente');
                    document.getElementById('formChangePwd').reset();
                } else {
                    showToast(data.message || 'Error al cambiar contraseña', 'error');
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
            } finally {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        });

        // ═══════════════════════════════════════════════════
        // SECCIONES (Torres/Bloques)
        // ═══════════════════════════════════════════════════
        const sectionsObj = <?= json_encode($sections) ?>;
        const unitsObj = <?= json_encode($units) ?>;
        let editingSectionId = null;

        window.openSectionModal = function (id = null, name = '') {
            editingSectionId = id;
            document.getElementById('inputSectionId').value = id || '';
            document.getElementById('inputSectionName').value = name;
            document.getElementById('modalSectionTitle').textContent = id ? 'Editar Sección' : 'Agregar Sección';
            document.getElementById('modalSectionSubtitle').textContent = id ? 'Actualiza los detalles de la sección a continuación.' : 'Agrega una nueva sección para organizar tus unidades.';
            document.getElementById('searchSectionUnits').value = '';

            renderSectionUnits();

            const modalEl = document.getElementById('modalSection');
            const modalObj = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalObj.show();
        };

        window.confirmDeleteSection = function (id, name) {
            document.getElementById('deleteSectionId').value = id;
            document.getElementById('deleteSectionMsg').innerHTML = `¿Estás seguro que deseas eliminar "<strong>${name}</strong>"? Las unidades asignadas a esta sección tendrán su sección eliminada.`;

            const modalEl = document.getElementById('modalDeleteSection');
            const modalObj = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalObj.show();
        };

        document.getElementById('btnConfirmDeleteSection').addEventListener('click', async () => {
            const id = document.getElementById('deleteSectionId').value;
            const btn = document.getElementById('btnConfirmDeleteSection');
            const ogText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Borrando...';

            try {
                const fd = new FormData();
                fd.append('id', id);
                const resp = await fetch(`${BASE}/sections/delete`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    sessionStorage.setItem('cfgToast', 'Sección eliminada exitosamente');
                    window.location.href = `${BASE}?tab=sections`;
                } else {
                    showToast(data.message || 'Error al eliminar', 'error');
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
            } finally {
                btn.disabled = false;
                btn.innerHTML = ogText;
            }
        });

        function renderSectionUnits(filter = '') {
            const wrapper = document.getElementById('sectionUnitsWrapper');
            filter = filter.toLowerCase();

            let html = '';
            let selectedCount = 0;

            unitsObj.forEach(u => {
                if (filter && u.unit_number.toLowerCase().indexOf(filter) === -1) return;

                let isChecked = editingSectionId && Number(u.section_id) === Number(editingSectionId);
                if (isChecked) selectedCount++;

                let subText = '';
                if (u.section_id && Number(u.section_id) !== Number(editingSectionId)) {
                    const sec = sectionsObj.find(s => Number(s.id) === Number(u.section_id));
                    if (sec) {
                        subText = `<span class="badge bg-light text-muted fw-normal border ms-auto" style="font-size: 0.65rem; padding: 0.35em 0.5em; letter-spacing: 0.5px;">${sec.name.toUpperCase()}</span>`;
                    }
                }

                html += `
            <div class="col-6">
                <div class="form-check d-flex align-items-center mb-1">
                    <input class="form-check-input section-unit-cb me-2" type="checkbox" value="${u.id}" id="chku_${u.id}" ${isChecked ? 'checked' : ''} style="cursor: pointer; width: 1.1rem; height: 1.1rem;">
                    <label class="form-check-label d-flex align-items-center w-100" for="chku_${u.id}" style="cursor: pointer; font-size: 0.85rem; color: #3F67AC;">
                        ${u.unit_number} ${subText}
                    </label>
                </div>
            </div>`;
            });

            wrapper.innerHTML = html || '<div class="col-12 text-center text-muted py-3" style="font-size: 0.85rem;">No hay unidades que coincidan</div>';
            document.getElementById('countSelectedUnits').textContent = selectedCount;

            document.querySelectorAll('.section-unit-cb').forEach(cb => {
                cb.addEventListener('change', countSelectedUnits);
            });
        }

        function countSelectedUnits() {
            const checked = document.querySelectorAll('.section-unit-cb:checked').length;
            document.getElementById('countSelectedUnits').textContent = checked;
        }

        document.getElementById('searchSectionUnits').addEventListener('input', (e) => {
            renderSectionUnits(e.target.value);
        });

        document.getElementById('btnSelectAllUnits').addEventListener('click', () => {
            document.querySelectorAll('.section-unit-cb').forEach(cb => cb.checked = true);
            countSelectedUnits();
        });

        document.getElementById('btnDeselectAllUnits').addEventListener('click', () => {
            document.querySelectorAll('.section-unit-cb').forEach(cb => cb.checked = false);
            countSelectedUnits();
        });

        document.getElementById('formSection').addEventListener('submit', async (e) => {
            e.preventDefault();

            const btn = document.getElementById('btnSaveSection');
            const ogText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            try {
                const checkedBoxes = Array.from(document.querySelectorAll('.section-unit-cb:checked')).map(cb => cb.value);

                const fd = new FormData();
                fd.append('id', document.getElementById('inputSectionId').value);
                fd.append('name', document.getElementById('inputSectionName').value);
                fd.append('unit_ids', JSON.stringify(checkedBoxes));

                const resp = await fetch(`${BASE}/sections/save`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    sessionStorage.setItem('cfgToast', 'Sección guardada exitosamente');
                    window.location.href = `${BASE}?tab=sections`;
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                    btn.disabled = false;
                    btn.innerHTML = ogText;
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
                btn.disabled = false;
                btn.innerHTML = ogText;
            }
        });

        // ==========================================
        // PREFERENCIAS FINANCIERAS
        // ==========================================
        window.toggleDelinquentAmounts = function () {
            const isChecked = document.getElementById('finDelinquentUnits').checked;
            const amountsWrap = document.getElementById('wrapDelinquentAmounts');
            if (isChecked) {
                amountsWrap.style.display = 'flex';
            } else {
                amountsWrap.style.display = 'none';
            }
        };

        window.saveFinancialPrefs = async function () {
            try {
                const fd = new FormData();
                fd.append('owner_financial_access', document.getElementById('finOwnerAccess').value);
                fd.append('tenant_financial_access', document.getElementById('finTenantAccess').value);
                fd.append('show_delinquent_units', document.getElementById('finDelinquentUnits').checked ? 1 : 0);
                fd.append('show_delinquency_amounts', document.getElementById('finDelinquentAmounts').checked ? 1 : 0);
                
                const approvalModeEl = document.getElementById('finPaymentApprovalMode');
                if(approvalModeEl) {
                    fd.append('payment_approval_mode', approvalModeEl.value);
                }

                const resp = await fetch(`${BASE}/financial-access`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    showToast('Preferencias actualizadas correctamente');
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
            }
        };

        // ==========================================
        // PREFERENCIAS MURO Y ANUNCIOS
        // ==========================================
        window.saveWallPrefs = async function () {
            try {
                const fd = new FormData();
                fd.append('allow_resident_posts', document.getElementById('wallAllowPosts').checked ? 1 : 0);
                fd.append('allow_post_comments', document.getElementById('wallAllowComments').checked ? 1 : 0);
                fd.append('always_email_posts', document.getElementById('wallAlwaysEmail').checked ? 1 : 0);

                const resp = await fetch(`${BASE}/wall-access`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    showToast('Preferencias de muro actualizadas');
                } else {
                    showToast(data.message || 'Error al guardar configuración', 'error');
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
            }
        };

        // ==========================================
        // RESTRICCIONES POR MOROSIDAD
        // ==========================================
        window.saveDelinquencyRestrictions = async function () {
            try {
                const restrictQr = document.getElementById('restrictQr').checked ? 1 : 0;
                const restrictAmenities = document.getElementById('restrictAmenities').checked ? 1 : 0;

                const fd = new FormData();
                fd.append('restrict_qr_delinquent', restrictQr);
                fd.append('restrict_amenities_delinquent', restrictAmenities);

                const resp = await fetch(`${BASE}/delinquency-restrictions`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    const rowQr = document.getElementById('rowRestrictQr');
                    if (rowQr) rowQr.style.borderColor = restrictQr ? '#cbd5e1' : '#e2e8f0';

                    const rowAmenities = document.getElementById('rowRestrictAmenities');
                    if (rowAmenities) rowAmenities.style.borderColor = restrictAmenities ? '#cbd5e1' : '#e2e8f0';

                    showToast(data.message || 'Restricciones actualizadas correctamente');
                } else {
                    showToast(data.message || 'Error al actualizar', 'error');
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
            }
        };
        // ==========================================
        // CONFIGURACIÓN DE FINANZAS
        // ==========================================
        window.openBankDetailsModal = function () {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalBankDetails'));
            modal.show();
        };

        window.openPaymentConfigModal = function () {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPaymentConfig'));
            modal.show();
        };

        // Save Bank Details
        document.getElementById('btnSaveBankDetails').addEventListener('click', async () => {
            const btn = document.getElementById('btnSaveBankDetails');
            const ogText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            try {
                const fd = new FormData();
                fd.append('bank_name', document.getElementById('inputBankName').value.trim());
                fd.append('bank_clabe', document.getElementById('inputBankClabe').value.trim());
                fd.append('bank_rfc', document.getElementById('inputBankRfc').value.trim());
                fd.append('bank_card', document.getElementById('inputBankCard').value.trim());

                const resp = await fetch(`${BASE}/bank-details`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    const bankName = document.getElementById('inputBankName').value.trim();
                    const bankClabe = document.getElementById('inputBankClabe').value.trim();
                    const bankRfc = document.getElementById('inputBankRfc').value.trim();
                    const bankCard = document.getElementById('inputBankCard').value.trim();

                    document.getElementById('displayBankName').innerHTML = bankName || '<span style="color:#94a3b8;">No establecido</span>';

                    if (bankClabe) {
                        document.getElementById('displayBankClabe').innerHTML = `${bankClabe} <i class="bi bi-clipboard" style="color:#94a3b8; cursor:pointer; font-size:0.8rem;" onclick="navigator.clipboard.writeText('${bankClabe}'); showToast('CLABE copiada');" title="Copiar CLABE"></i>`;
                    } else {
                        document.getElementById('displayBankClabe').innerHTML = '<span style="color:#94a3b8;">No establecido</span>';
                    }

                    document.getElementById('displayBankRfc').innerHTML = bankRfc ? bankRfc.toUpperCase() : '<span style="color:#94a3b8;">No establecido</span>';
                    document.getElementById('displayBankCard').innerHTML = bankCard || '<span style="color:#94a3b8;">No establecido</span>';

                    bootstrap.Modal.getInstance(document.getElementById('modalBankDetails')).hide();
                    showToast(data.message || 'Datos bancarios actualizados');
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                }
            } catch (e) {
                showToast('Error de conexión', 'error');
            }

            btn.disabled = false;
            btn.innerHTML = ogText;
        });

        // Save Payment Config
        document.getElementById('btnSavePaymentConfig').addEventListener('click', async () => {
            const btn = document.getElementById('btnSavePaymentConfig');
            const ogText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            try {
                const currency = document.getElementById('selectCurrency').value;
                const dueDay = document.getElementById('selectDueDay').value;

                const fd = new FormData();
                fd.append('currency', currency);
                fd.append('billing_due_day', dueDay);

                const resp = await fetch(`${BASE}/payment-config`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    const currencyLabel = currency === 'USD' ? 'USD (US Dollar)' : 'MXN (Mexican Peso)';
                    document.getElementById('displayCurrency').textContent = currencyLabel;
                    document.getElementById('displayDueDay').textContent = `Día ${dueDay} del mes`;

                    bootstrap.Modal.getInstance(document.getElementById('modalPaymentConfig')).hide();
                    showToast(data.message || 'Configuración de pagos actualizada');
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                }
            } catch (e) {
                showToast('Error de conexión', 'error');
            }

            btn.disabled = false;
            btn.innerHTML = ogText;
        });

        // ==========================================
        // RECORDATORIOS DE PAGO
        // ==========================================
        window.openPaymentReminderModal = function (id = '', trigger = 'start_of_month', value = 1, title = '', body = '', event = null) {
            if (event) event.stopPropagation();

            document.getElementById('inputReminderId').value = id;
            document.getElementById('inputReminderTrigger').value = trigger;
            document.getElementById('inputReminderTriggerValue').value = value;
            document.getElementById('inputReminderMessageTitle').value = title;
            document.getElementById('inputReminderMessageBody').value = body;
            document.getElementById('inputReminderPreset').value = 'custom';

            if (id) {
                document.getElementById('modalPaymentReminderTitle').textContent = 'Editar Recordatorio';
                document.getElementById('btnDeleteReminder').style.display = 'block';
                document.getElementById('btnSaveReminder').innerHTML = '<i class="bi bi-save"></i> Guardar Cambios';
            } else {
                document.getElementById('modalPaymentReminderTitle').textContent = 'Agregar Recordatorio';
                document.getElementById('btnDeleteReminder').style.display = 'none';
                document.getElementById('btnSaveReminder').innerHTML = 'Agregar';
            }

            updateReminderTriggerValueLabel();

            const modalEl = document.getElementById('modalPaymentReminder');
            const modalObj = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            modalObj.show();
        };

        window.updateReminderTriggerValueLabel = function () {
            const trigger = document.getElementById('inputReminderTrigger').value;
            const valueInput = document.getElementById('inputReminderTriggerValue');
            const label = document.getElementById('labelReminderTriggerValue');

            if (trigger === 'start_of_month') {
                label.textContent = 'Día del Mes';
                valueInput.min = 1; valueInput.max = 31;
                valueInput.disabled = false;
            } else if (trigger === 'days_before_due') {
                label.textContent = 'Número de Días';
                valueInput.min = 1; valueInput.max = 30;
                valueInput.disabled = false;
            } else if (trigger === 'due_date') {
                label.textContent = 'N/A';
                valueInput.value = 0;
                valueInput.disabled = true;
            } else if (trigger === 'days_after_due') {
                label.textContent = 'Número de Días';
                valueInput.min = 1; valueInput.max = 30;
                valueInput.disabled = false;
            } else if (trigger === 'specific_day') {
                label.textContent = 'Día del Mes';
                valueInput.min = 1; valueInput.max = 31;
                valueInput.disabled = false;
            }
        };

        window.applyReminderPreset = function () {
            const preset = document.getElementById('inputReminderPreset').value;
            const title = document.getElementById('inputReminderMessageTitle');
            const body = document.getElementById('inputReminderMessageBody');

            switch (preset) {
                case '1':
                    title.value = 'Recordatorio Mensual de Cuota';
                    body.value = '¡Es inicio de mes! Recuerda pagar tu cuota de mantenimiento antes de la fecha de vencimiento.';
                    break;
                case '2':
                    title.value = 'Pago Por Vencer';
                    body.value = 'Recordatorio amigable: Tu cuota de mantenimiento vence en {x} días.';
                    break;
                case '3':
                    title.value = 'Pago Vence Hoy';
                    body.value = 'Tu cuota de mantenimiento vence hoy. Por favor realiza tu pago para evitar cargos por mora.';
                    break;
                case '4':
                    title.value = 'Pago Atrasado';
                    body.value = 'Hola, notamos que tu pago de mantenimiento tiene {x} días de atraso. Por favor regulariza tu situación lo antes posible.';
                    break;
                case '5':
                    title.value = 'URGENTE: Cuota Vencida';
                    body.value = 'Tu cuota de mantenimiento está vencida. Evita restricciones en los servicios de la comunidad regularizando tu pago de inmediato.';
                    break;
            }
        };

        document.getElementById('btnSaveReminder').addEventListener('click', async () => {
            const btn = document.getElementById('btnSaveReminder');
            const ogText = btn.innerHTML;

            const title = document.getElementById('inputReminderMessageTitle').value.trim();
            const body = document.getElementById('inputReminderMessageBody').value.trim();

            if (!title || !body) {
                showToast('Debes ingresar un título y mensaje', 'error');
                return;
            }

            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            try {
                const fd = new FormData();
                fd.append('id', document.getElementById('inputReminderId').value);
                fd.append('trigger_type', document.getElementById('inputReminderTrigger').value);
                fd.append('trigger_value', document.getElementById('inputReminderTriggerValue').value || 0);
                fd.append('message_title', title);
                fd.append('message_body', body);

                const resp = await fetch(`${BASE}/payment-reminders/save`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    sessionStorage.setItem('cfgToast', data.message);
                    window.location.href = `${BASE}?tab=paymentReminders`;
                } else {
                    showToast(data.message || 'Error al guardar', 'error');
                    btn.disabled = false;
                    btn.innerHTML = ogText;
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
                btn.disabled = false;
                btn.innerHTML = ogText;
            }
        });

        window.togglePaymentReminder = async function (id, isActive) {
            try {
                const fd = new FormData();
                fd.append('id', id);
                fd.append('is_active', isActive ? 1 : 0);

                const resp = await fetch(`${BASE}/payment-reminders/toggle`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (!data.success) {
                    showToast(data.message || 'Error al actualizar', 'error');
                    // Reload to reset state
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    const item = document.querySelector(`.reminder-item[onclick*="${id},"]`);
                    if (item) {
                        item.style.opacity = isActive ? '1' : '0.6';
                        item.style.borderColor = isActive ? '#cbd5e1' : '#e2e8f0';
                    }
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
            }
        };

        window.deletePaymentReminder = async function () {
            if (!confirm('¿Estás seguro de eliminar este recordatorio?')) return;

            const btn = document.getElementById('btnDeleteReminder');
            const ogText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>...';

            try {
                const fd = new FormData();
                fd.append('id', document.getElementById('inputReminderId').value);

                const resp = await fetch(`${BASE}/payment-reminders/delete`, { method: 'POST', body: fd });
                const data = await resp.json();

                if (data.success) {
                    sessionStorage.setItem('cfgToast', 'Recordatorio eliminado');
                    window.location.href = `${BASE}?tab=paymentReminders`;
                } else {
                    showToast(data.message || 'Error al eliminar', 'error');
                    btn.disabled = false;
                    btn.innerHTML = ogText;
                }
            } catch (err) {
                showToast('Error de conexión', 'error');
                btn.disabled = false;
                btn.innerHTML = ogText;
            }
        };

        // Check for pending toast messages (e.g. after reload)
        const pendingToast = sessionStorage.getItem('cfgToast');
        if (pendingToast) {
            showToast(pendingToast);
            sessionStorage.removeItem('cfgToast');
        }

        // ═══════════════════════════════════════════════════
        //  SUBSCRIPTION MODULE
        // ═══════════════════════════════════════════════════
        let subscriptionLoaded = false;

        async function loadSubscription() {
            const container = document.getElementById('subscriptionContent');
            try {
                const resp = await fetch(`${BASE}/subscription`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await resp.json();
                if (!data.success) {
                    container.innerHTML = '<div style="text-align:center;padding:3rem;color:#ef4444;"><i class="bi bi-exclamation-triangle" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>Error al cargar</div>';
                    return;
                }
                subscriptionLoaded = true;
                renderSubscription(data);
            } catch (e) {
                container.innerHTML = '<div style="text-align:center;padding:3rem;color:#ef4444;"><i class="bi bi-wifi-off" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>Error de conexión</div>';
            }
        }

        function renderSubscription(data) {
            const container = document.getElementById('subscriptionContent');
            const cp = data.current_plan;
            const plans = data.plans || [];
            const unitCount = data.unit_count || 0;
            const cycle = data.billing_cycle || 'monthly';
            const expires = data.plan_expires_at;
            const paymentMethod = data.payment_method || 'stripe';
            const subStatus = data.subscription_status || 'active';
            const graceUntil = data.grace_until || null;
            const stripeSubId = data.stripe_subscription_id || null;

            let html = '';
            
            const isSuspended = (subStatus === 'suspended' || subStatus === 'canceled');
            const isPastDue = (subStatus === 'past_due' && graceUntil && new Date(graceUntil).getTime() >= new Date().getTime());
            const isTrialExpired = (!stripeSubId && expires && new Date(expires).getTime() < new Date().getTime());

            // Si está suspendido por falta de pago
            if (isSuspended || isTrialExpired) {
                html += `
                <div style="background:#fff1f2;border:1px solid #fda4af;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;display:flex;align-items:flex-start;gap:1rem;">
                    <div style="width:48px;height:48px;border-radius:12px;background:#ffe4e6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-x-circle-fill" style="color:#e11d48;font-size:1.5rem;margin:0;"></i>
                    </div>
                    <div>
                        <h4 style="margin:0 0 0.5rem 0;color:#9f1239;font-weight:700;font-size:1.1rem;">Suscripción Suspendida</h4>
                        <p style="margin:0 0 1rem 0;color:#be123c;font-size:0.95rem;line-height:1.5;">Tu cuenta ha sido suspendida. Actualiza tu método de pago en Stripe para restaurar el acceso a las funciones de AxisCondo.</p>
                        <button type="button" onclick="openBillingPortal()" style="background:#e11d48;color:white;border:none;border-radius:8px;padding:0.6rem 1.25rem;font-weight:600;cursor:pointer;transition:background 0.2s;">
                            Actualizar pago en Stripe
                        </button>
                    </div>
                </div>
                `;
            } else if (isPastDue) {
                const daysLeft = Math.max(0, Math.ceil((new Date(graceUntil).getTime() - new Date().getTime()) / 86400000));
                html += `
                <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:1.5rem;margin-bottom:1.5rem;display:flex;align-items:flex-start;gap:1rem;">
                    <div style="width:48px;height:48px;border-radius:12px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-exclamation-triangle-fill" style="color:#d97706;font-size:1.5rem;margin:0;"></i>
                    </div>
                    <div>
                        <h4 style="margin:0 0 0.5rem 0;color:#92400e;font-weight:700;font-size:1.1rem;">Pago Pendiente</h4>
                        <p style="margin:0 0 1rem 0;color:#b45309;font-size:0.95rem;line-height:1.5;">Tu último pago ha fallado. Tienes ${daysLeft} día(s) de gracia antes de que tu cuenta sea suspendida. Actualiza tu método de pago.</p>
                        <button type="button" onclick="openBillingPortal()" style="background:#d97706;color:white;border:none;border-radius:8px;padding:0.6rem 1.25rem;font-weight:600;cursor:pointer;transition:background 0.2s;">
                            Reintentar pago en Stripe
                        </button>
                    </div>
                </div>
                `;
            }

            // ── Si es pago manual, mostrar aviso elegante ──
            if (paymentMethod === 'manual' && cp) {
                const price = cycle === 'yearly' ? parseFloat(cp.price_yearly) : parseFloat(cp.price_monthly);
                const cycleLabel = cycle === 'yearly' ? 'anual' : 'mensual';
                const expDate = expires ? new Date(expires).toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Sin definir';
                const maxUnits = cp.max_units || '∞';
                const usagePercent = Math.min(100, (unitCount / parseInt(cp.max_units)) * 100);

                html += `
                <div style="background:linear-gradient(135deg,#1D4C9D 0%,#334155 100%);border-radius:12px;padding:1.75rem;color:#fff;margin-bottom:1.5rem;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;position:relative;z-index:1;">
                        <div>
                            <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;font-size:0.75rem;opacity:0.7;text-transform:uppercase;letter-spacing:0.5px;"><i class="bi bi-credit-card-2-front" style="margin:0;"></i> Plan Actual</div>
                            <div style="display:flex;align-items:baseline;gap:1rem;flex-wrap:wrap;">
                                <h2 style="margin:0;font-weight:800;font-size:1.6rem;">${cp.name}</h2>
                                <div style="font-size:1.3rem;font-weight:700;">$${price.toLocaleString('es-MX',{minimumFractionDigits:2})} <span style="font-size:0.8rem;font-weight:400;opacity:0.7;">/${cycleLabel}</span></div>
                            </div>
                        </div>
                        <span style="background:rgba(251,191,36,0.2);color:#fbbf24;font-size:0.7rem;font-weight:700;padding:0.35rem 0.75rem;border-radius:6px;">💵 PAGO MANUAL</span>
                    </div>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1.25rem;">
                        <div style="background:rgba(255,255,255,0.08);border-radius:8px;padding:0.65rem 0.85rem;">
                            <div style="font-size:0.65rem;opacity:0.6;text-transform:uppercase;margin-bottom:0.15rem;">Unidades</div>
                            <div style="font-weight:700;font-size:1.05rem;">${unitCount} <span style="font-weight:400;opacity:0.6;font-size:0.75rem;">de ${maxUnits}</span></div>
                        </div>
                        <div style="background:rgba(255,255,255,0.08);border-radius:8px;padding:0.65rem 0.85rem;">
                            <div style="font-size:0.65rem;opacity:0.6;text-transform:uppercase;margin-bottom:0.15rem;">Facturación</div>
                            <div style="font-weight:700;font-size:1.05rem;">${cycle === 'yearly' ? 'Anual' : 'Mensual'}</div>
                        </div>
                        <div style="background:rgba(255,255,255,0.08);border-radius:8px;padding:0.65rem 0.85rem;">
                            <div style="font-size:0.65rem;opacity:0.6;text-transform:uppercase;margin-bottom:0.15rem;">Vence</div>
                            <div style="font-weight:600;font-size:0.85rem;">${expDate}</div>
                        </div>
                    </div>
                    <div style="margin-top:0.75rem;">
                        <div style="background:rgba(255,255,255,0.15);border-radius:4px;height:4px;">
                            <div style="background:#22c55e;border-radius:4px;height:100%;width:${usagePercent}%;transition:width 0.5s;"></div>
                        </div>
                        <div style="font-size:0.7rem;opacity:0.5;margin-top:0.2rem;">${Math.round(usagePercent)}% de capacidad utilizada</div>
                    </div>
                </div>
                <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:12px;padding:1.25rem;display:flex;align-items:flex-start;gap:1rem;">
                    <div style="width:40px;height:40px;border-radius:10px;background:#fef3c7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-briefcase-fill" style="color:#d97706;font-size:1.1rem;margin:0;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700;color:#92400e;font-size:0.95rem;margin-bottom:0.25rem;">Facturación Administrada</div>
                        <div style="font-size:0.85rem;color:#a16207;line-height:1.5;">
                            Tu plan es gestionado directamente por el equipo de AxisCondo. Para cambios de plan, renovaciones o consultas sobre tu facturación, contacta a tu ejecutivo de cuenta.
                        </div>
                        <a href="mailto:soporte@axiscondo.mx" style="display:inline-flex;align-items:center;gap:0.4rem;margin-top:0.75rem;padding:0.5rem 1rem;background:#1D4C9D;color:#fff;border-radius:8px;font-weight:600;font-size:0.85rem;text-decoration:none;transition:all 0.2s;" onmouseover="this.style.background='#334155';" onmouseout="this.style.background='#1D4C9D';">
                            <i class="bi bi-envelope" style="margin:0;"></i> soporte@axiscondo.mx
                        </a>
                    </div>
                </div>`;
                container.innerHTML = html;
                return;
            }

            // ── Current Plan Card ──
            const price = cp ? (cycle === 'yearly' ? parseFloat(cp.price_yearly) : parseFloat(cp.price_monthly)) : 0;
            const cycleLabel = cp ? (cycle === 'yearly' ? 'anual' : 'mensual') : 'mes';
            const cycleText = cp ? (cycle === 'yearly' ? 'Anual' : 'Mensual') : 'No activa';
            const expDate = expires ? new Date(expires).toLocaleDateString('es-MX', { day: 'numeric', month: 'long', year: 'numeric' }) : 'Sin definir';
            const maxUnits = cp ? cp.max_units : '∞';
            const usagePercent = cp ? Math.min(100, (unitCount / cp.max_units) * 100) : 0;
            const planName = cp ? cp.name : 'Sin plan asignado';

            html += `
            <div style="background:linear-gradient(135deg,#1D4C9D 0%,#334155 100%);border-radius:12px;padding:1.75rem;color:#fff;margin-bottom:1.5rem;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-20px;right:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>
                <div style="position:absolute;bottom:-30px;right:40px;width:140px;height:140px;border-radius:50%;background:rgba(255,255,255,0.03);"></div>
                <div style="display:flex;justify-content:space-between;align-items:flex-start;position:relative;z-index:1;">
                    <div>
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;font-size:0.75rem;opacity:0.7;text-transform:uppercase;letter-spacing:0.5px;"><i class="bi bi-credit-card-2-front" style="margin:0;"></i> Plan Actual</div>
                        <div style="display:flex;align-items:baseline;gap:1rem;flex-wrap:wrap;">
                            <h2 style="margin:0;font-weight:800;font-size:1.6rem;">${planName}</h2>
                            ${cp ? `<div style="font-size:1.3rem;font-weight:700;">$${price.toLocaleString('es-MX',{minimumFractionDigits:2})} <span style="font-size:0.8rem;font-weight:400;opacity:0.7;">/${cycleLabel}</span></div>` : `<div style="font-size:0.9rem;opacity:0.7;font-weight:500;">Selecciona un plan abajo para continuar</div>`}
                        </div>
                    </div>
                    ${cp ? `
                    <button type="button" id="btnBillingPortal" onclick="openBillingPortal()" style="background:#6366f1;color:#fff;border:none;padding:0.75rem 1.5rem;border-radius:10px;font-weight:700;font-size:0.95rem;display:inline-flex;align-items:center;gap:0.6rem;cursor:pointer;box-shadow:0 4px 14px rgba(99,102,241,0.4);transition:all 0.25s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.background='#4f46e5';this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 20px rgba(99,102,241,0.5)';" onmouseout="this.style.background='#6366f1';this.style.transform='';this.style.boxShadow='0 4px 14px rgba(99,102,241,0.4)';">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-2"/><path d="M22 12v.01"/><path d="M22 16v.01"/><path d="M22 8v.01"/></svg>
                        Gestionar Facturación
                    </button>
                    ` : ''}
                </div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-top:1.25rem;">
                    <div style="background:rgba(255,255,255,0.08);border-radius:8px;padding:0.65rem 0.85rem;">
                        <div style="font-size:0.65rem;opacity:0.6;text-transform:uppercase;margin-bottom:0.15rem;">Unidades</div>
                        <div style="font-weight:700;font-size:1.05rem;">${unitCount} <span style="font-weight:400;opacity:0.6;font-size:0.75rem;">de ${maxUnits}</span></div>
                    </div>
                    <div style="background:rgba(255,255,255,0.08);border-radius:8px;padding:0.65rem 0.85rem;">
                        <div style="font-size:0.65rem;opacity:0.6;text-transform:uppercase;margin-bottom:0.15rem;">Facturación</div>
                        <div style="font-weight:700;font-size:1.05rem;">${cycleText}</div>
                    </div>
                    <div style="background:rgba(255,255,255,0.08);border-radius:8px;padding:0.65rem 0.85rem;">
                        <div style="font-size:0.65rem;opacity:0.6;text-transform:uppercase;margin-bottom:0.15rem;">Vence</div>
                        <div style="font-weight:600;font-size:0.85rem;">${expDate}</div>
                    </div>
                </div>
                <div style="margin-top:0.75rem;">
                    <div style="background:rgba(255,255,255,0.15);border-radius:4px;height:4px;">
                        <div style="background:${cp ? '#22c55e' : 'rgba(255,255,255,0.3)'};border-radius:4px;height:100%;width:${cp ? usagePercent : 100}%;transition:width 0.5s;"></div>
                    </div>
                    <div style="font-size:0.7rem;opacity:0.5;margin-top:0.2rem;">${cp ? `${Math.round(usagePercent)}% de capacidad utilizada` : 'Capacidad ilimitada temporal (requiere plan)'}</div>
                </div>
            </div>`;

            // ── Available Plans Grid ──
            if (plans.length > 0) {
                html += '<h4 style="font-weight:700;color:#1e293b;font-size:1.05rem;margin-bottom:1rem;"><i class="bi bi-grid-3x3-gap me-1" style="margin:0;"></i> Planes Disponibles</h4>';
                html += '<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(250px,1fr));gap:1rem;">';

                plans.forEach(p => {
                    const isCurrent = cp && cp.id == p.id;
                    const canDowngrade = unitCount <= parseInt(p.max_units);
                    const priceM = parseFloat(p.price_monthly);
                    const priceY = parseFloat(p.price_yearly);
                    const isUpgrade = cp ? (parseInt(p.max_units) > parseInt(cp.max_units)) : true;

                    html += `
                    <div style="border:${isCurrent ? '2px solid #1D4C9D' : '1px solid #e2e8f0'};border-radius:12px;padding:1.25rem;background:${isCurrent ? '#f8fafc' : '#fff'};position:relative;transition:all 0.2s;${!isCurrent ? 'cursor:pointer;' : ''}${!isCurrent ? 'box-shadow:0 1px 3px rgba(0,0,0,0.04);' : 'box-shadow:0 2px 8px rgba(28,36,52,0.08);'}" ${!isCurrent ? `onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 4px 12px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.04)'"` : ''}>
                        ${isCurrent ? '<div style="position:absolute;top:0.75rem;right:0.75rem;background:#1D4C9D;color:#fff;font-size:0.65rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:4px;text-transform:uppercase;">Actual</div>' : ''}
                        <div style="font-weight:700;color:#1e293b;font-size:1.1rem;margin-bottom:0.25rem;">${p.name}</div>
                        <div style="font-size:0.8rem;color:#64748b;margin-bottom:0.75rem;">${p.min_units} – ${p.max_units} unidades</div>
                        <div style="display:flex;gap:1rem;margin-bottom:1rem;">
                            <div><span style="font-weight:800;font-size:1.35rem;color:#1e293b;">$${priceM.toLocaleString('es-MX',{minimumFractionDigits:2})}</span><span style="font-size:0.75rem;color:#94a3b8;">/mes</span></div>
                            <div style="border-left:1px solid #e2e8f0;padding-left:1rem;"><span style="font-weight:700;font-size:1rem;color:#3F67AC;">$${priceY.toLocaleString('es-MX',{minimumFractionDigits:2})}</span><span style="font-size:0.75rem;color:#94a3b8;">/año</span></div>
                        </div>
                        ${!isCurrent ? `
                            ${canDowngrade ? `
                                <div style="display:flex;gap:0.5rem;">
                                    <button onclick="changePlan(${p.id},'monthly','${p.name.replace(/'/g,"\\\\'")}')" style="flex:1;padding:0.6rem;border-radius:8px;border:1px solid ${isUpgrade ? '#1D4C9D' : '#e2e8f0'};background:${isUpgrade ? '#1D4C9D' : '#fff'};color:${isUpgrade ? '#fff' : '#3F67AC'};font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.2s;" onmouseover="${isUpgrade ? "this.style.background='#334155';this.style.boxShadow='0 4px 12px rgba(28,36,52,0.15)';" : "this.style.background='#f8fafc';this.style.borderColor='#cbd5e1';this.style.boxShadow='0 2px 6px rgba(0,0,0,0.05)';this.style.color='#1e293b';"}" onmouseout="${isUpgrade ? "this.style.background='#1D4C9D';this.style.boxShadow='none';" : "this.style.background='#fff';this.style.borderColor='#e2e8f0';this.style.boxShadow='none';this.style.color='#3F67AC';"}">${isUpgrade ? '↑ Upgrade' : '↓ Cambiar'} Mensual</button>
                                    <button onclick="changePlan(${p.id},'yearly','${p.name.replace(/'/g,"\\\\'")}')" style="flex:1;padding:0.6rem;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#3F67AC;font-weight:600;font-size:0.85rem;cursor:pointer;transition:all 0.2s;" onmouseover="this.style.background='#f8fafc';this.style.borderColor='#cbd5e1';this.style.boxShadow='0 2px 6px rgba(0,0,0,0.05)';this.style.color='#1e293b';" onmouseout="this.style.background='#fff';this.style.borderColor='#e2e8f0';this.style.boxShadow='none';this.style.color='#3F67AC';">Anual</button>
                                </div>
                            ` : `
                                <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:0.5rem 0.75rem;font-size:0.78rem;color:#991b1b;display:flex;align-items:center;gap:0.35rem;">
                                    <i class="bi bi-exclamation-circle" style="margin:0;"></i> Tienes ${unitCount} unidades (máx: ${p.max_units})
                                </div>
                            `}
                        ` : `
                            <div style="font-size:0.85rem;color:#059669;display:flex;align-items:center;gap:0.4rem;margin-bottom:0.75rem;font-weight:600;background:#ecfdf5;padding:0.4rem 0.75rem;border-radius:6px;width:fit-content;"><i class="bi bi-check-circle-fill" style="margin:0;"></i> Plan activo (${cycle === 'yearly' ? 'Anual' : 'Mensual'})</div>
                            ${cycle === 'monthly' ? `<button onclick="changePlan(${p.id},'yearly','${p.name.replace(/'/g,"\\\\'")}')" style="width:100%;padding:0.6rem;border-radius:8px;border:none;background:#f8fafc;color:#1D4C9D;font-weight:700;font-size:0.85rem;cursor:pointer;transition:all 0.2s;box-shadow:inset 0 0 0 1px #1D4C9D;" onmouseover="this.style.background='#1D4C9D';this.style.color='#fff';this.style.boxShadow='0 4px 12px rgba(28,36,52,0.15)';" onmouseout="this.style.background='#f8fafc';this.style.color='#1D4C9D';this.style.boxShadow='inset 0 0 0 1px #1D4C9D';">↑ Cambiar a Anual</button>` : ''}
                        `}
                    </div>`;
                });

                html += '</div>';
            }

            // The old billing portal block was moved to the header card for higher visibility.

            container.innerHTML = html;
        }

        window.changePlan = async function(planId, cycle, planName) {
            const result = await Swal.fire({
                title: '\u00bfCambiar de plan?',
                html: `<p style="color:#64748b;">Cambiar\u00e1s al plan <strong>${planName}</strong> con facturaci\u00f3n <strong>${cycle === 'yearly' ? 'anual' : 'mensual'}</strong>.</p>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1D4C9D',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'S\u00ed, cambiar',
                cancelButtonText: 'Cancelar',
            });

            if (!result.isConfirmed) return;

            try {
                const fd = new FormData();
                fd.append('plan_id', planId);
                fd.append('billing_cycle', cycle);

                Swal.fire({
                    title: 'Procesando...',
                    text: 'Conectando con la pasarela de pagos',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                const resp = await fetch(`${BASE}/change-plan`, { 
                    method: 'POST', 
                    body: fd,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await resp.json();

                if (data.success) {
                    if (data.url) {
                        window.location.href = data.url;
                    } else {
                        Swal.close();
                        showToast(data.message);
                        subscriptionLoaded = false;
                        loadSubscription();
                    }
                } else {
                    Swal.close();
                    showToast(data.message || 'Error al procesar el plan', 'error');
                }
            } catch (e) {
                Swal.close();
                showToast('Error de conexi\u00f3n', 'error');
            }
        };

        window.openBillingPortal = async function() {
            const btn = document.getElementById('btnBillingPortal');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div> Conectando...';
            }

            try {
                const resp = await fetch(`${BASE}/billing-portal`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                const data = await resp.json();

                if (data.success && data.url) {
                    window.location.href = data.url;
                } else {
                    showToast(data.message || 'Error al conectar con Stripe', 'error');
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Gestionar en Stripe';
                    }
                }
            } catch (e) {
                showToast('Error de conexi\u00f3n con el servidor', 'error');
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg> Gestionar en Stripe';
                }
            }
        };

        window.promptDeleteCommunity = function(communityName) {
            Swal.fire({
                title: 'Eliminar Comunidad',
                html: `
                    <div style="text-align: left;">
                        <p style="color: #dc2626; font-size: 0.95rem; margin-bottom: 1rem;">
                            La comunidad será archivada y todos sus datos serán eliminados permanentemente después de 90 días.
                        </p>
                        <div style="background: #fef2f2; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem;">
                            <p style="color: #991b1b; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Se eliminará permanentemente:</p>
                            <ul style="color: #ef4444; font-size: 0.85rem; margin-bottom: 0; padding-left: 1.25rem;">
                                <li>Todos los residentes y sus datos</li>
                                <li>Historial de pagos y transacciones</li>
                                <li>Anuncios y publicaciones</li>
                                <li>Documentos y archivos</li>
                                <li>Reservas de amenidades</li>
                                <li>Toda la configuración</li>
                                <li>Tu suscripción activa será cancelada</li>
                            </ul>
                        </div>
                        <label style="font-size: 0.9rem; font-weight: 500; color: #1e293b; margin-bottom: 0.5rem; display: block;">
                            Para confirmar, escribe el nombre de la comunidad: <strong>${communityName}</strong>
                        </label>
                    </div>
                `,
                icon: 'warning',
                iconColor: '#ef4444',
                input: 'text',
                inputPlaceholder: 'Nombre de la comunidad',
                inputAttributes: {
                    autocapitalize: 'off',
                    autocomplete: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Eliminar Comunidad',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#fca5a5',
                cancelButtonColor: '#64748b',
                didOpen: () => {
                    const confirmButton = Swal.getConfirmButton();
                    const input = Swal.getInput();
                    
                    // Inicialmente deshabilitado
                    confirmButton.disabled = true;
                    
                    // Escuchar cambios en el input
                    input.addEventListener('input', () => {
                        if (input.value === communityName) {
                            confirmButton.disabled = false;
                            confirmButton.style.backgroundColor = '#ef4444'; // Rojo fuerte
                        } else {
                            confirmButton.disabled = true;
                            confirmButton.style.backgroundColor = '#fca5a5'; // Rojo claro
                        }
                    });
                },
                preConfirm: (value) => {
                    if (value !== communityName) {
                        Swal.showValidationMessage('El nombre no coincide');
                        return false;
                    }
                    return value;
                }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        Swal.fire({
                            title: 'Eliminando...',
                            text: 'Por favor espera',
                            allowOutsideClick: false,
                            didOpen: () => { Swal.showLoading(); }
                        });

                        const fd = new FormData();
                        fd.append('confirm_name', result.value);

                        const resp = await fetch(`${BASE}/delete-community`, {
                            method: 'POST',
                            body: fd,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await resp.json();

                        if (data.success) {
                            showToast(data.message, 'success');
                            setTimeout(() => {
                                window.location.href = '<?= base_url('auth/select-tenant') ?>';
                            }, 1500);
                        } else {
                            Swal.fire('Error', data.message || 'No se pudo eliminar la comunidad', 'error');
                        }
                    } catch (e) {
                        Swal.fire('Error', 'Error de conexión', 'error');
                    }
                }
            });
        };

    });
</script>
<?= $this->endSection() ?>