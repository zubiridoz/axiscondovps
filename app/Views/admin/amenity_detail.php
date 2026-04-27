<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">
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
    /* ── WIZARD LAYOUT ── */
    .wz-header {
        background: #2f3a4d;
        color: #ffffff;
        padding: 1.25rem 2rem;
        border-radius: 0.5rem;
        margin-bottom: 0;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .wz-header-back {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        border: none;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.2s;
        text-decoration: none;
        font-size: 1rem;
    }

    .wz-header-back:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #fff;
    }

    .wz-header-info h2 {
        font-size: 1.15rem;
        font-weight: 700;
        margin: 0;
    }

    .wz-header-info p {
        font-size: 0.82rem;
        color: rgba(255, 255, 255, 0.6);
        margin: 0;
    }

    /* ── STEPPER ── */
    .wz-stepper {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem 0 1rem;
        gap: 0;
    }

    .wz-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        min-width: 140px;
    }

    .wz-step-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        font-weight: 700;
        border: 2px solid #cbd5e1;
        color: #94a3b8;
        background: #ffffff;
        transition: all 0.3s;
        position: relative;
        z-index: 2;
    }

    .wz-step.active .wz-step-circle {
        background: #2f3a4d;
        border-color: #2f3a4d;
        color: #ffffff;
    }

    .wz-step.completed .wz-step-circle {
        background: #2f3a4d;
        border-color: #2f3a4d;
        color: #ffffff;
    }

    .wz-step-label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 0.5rem;
        text-align: center;
        white-space: nowrap;
    }

    .wz-step.active .wz-step-label,
    .wz-step.completed .wz-step-label {
        color: #1e293b;
        font-weight: 600;
    }

    .wz-step-line {
        flex: 1;
        height: 2px;
        background: #e2e8f0;
        margin: 0 -10px;
        position: relative;
        top: -18px;
        z-index: 1;
        min-width: 60px;
    }

    .wz-step-line.completed {
        background: #2f3a4d;
    }

    /* ── WIZARD PANELS ── */
    .wz-panel {
        display: none;
        animation: fadeInUp 0.3s ease;
    }

    .wz-panel.active {
        display: block;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(12px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .wz-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.75rem;
        margin-bottom: 1.25rem;
    }

    .wz-card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .wz-card-subtitle {
        font-size: 0.82rem;
        color: #64748b;
        margin-bottom: 1.5rem;
    }

    /* ── FORM CONTROLS ── */
    .wz-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .wz-label .required {
        color: #ef4444;
    }

    .wz-label .info-icon {
        color: #94a3b8;
        font-size: 0.75rem;
        cursor: help;
    }

    .wz-input {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.88rem;
        width: 100%;
        color: #334155;
        background: #ffffff;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .wz-input:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15);
    }

    .wz-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .wz-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        font-size: 0.88rem;
        width: 100%;
        color: #334155;
        background: #ffffff;
        cursor: pointer;
        appearance: auto;
    }

    .wz-select:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.15);
    }

    /* ── IMAGE UPLOAD ── */
    .wz-image-upload {
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        padding: 2.5rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s;
        background: #fafbfc;
        position: relative;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .wz-image-upload:hover {
        border-color: #94a3b8;
        background: #f1f5f9;
    }

    .wz-image-upload .upload-icon {
        font-size: 2.5rem;
        color: #94a3b8;
        margin-bottom: 0.75rem;
    }

    .wz-image-upload .upload-text {
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
    }

    .wz-image-upload .upload-hint {
        font-size: 0.72rem;
        color: #94a3b8;
        margin-top: 0.25rem;
    }

    .wz-image-preview {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        display: none;
    }

    .wz-image-upload.has-image .upload-icon,
    .wz-image-upload.has-image .upload-text,
    .wz-image-upload.has-image .upload-hint {
        display: none;
    }

    .wz-image-upload.has-image .wz-image-preview {
        display: block;
    }

    .wz-image-upload.has-image {
        border-style: solid;
        padding: 0.5rem;
    }

    /* ── TOGGLE CARD ── */
    .wz-toggle-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.15rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .wz-toggle-card .toggle-info h6 {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .wz-toggle-card .toggle-info p {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0.25rem 0 0;
        line-height: 1.4;
    }

    .wz-toggle-card .toggle-info .toggle-icon {
        color: #64748b;
        font-size: 1rem;
    }

    /* ── PREMIUM SWITCH ── */
    .premium-switch {
        position: relative;
        width: 48px;
        height: 26px;
    }

    .premium-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .premium-switch .slider {
        position: absolute;
        cursor: pointer;
        inset: 0;
        background: #cbd5e1;
        border-radius: 26px;
        transition: 0.3s;
    }

    .premium-switch .slider:before {
        content: "";
        position: absolute;
        height: 20px;
        width: 20px;
        left: 3px;
        bottom: 3px;
        background: #ffffff;
        border-radius: 50%;
        transition: 0.3s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
    }

    .premium-switch input:checked+.slider {
        background: #2f3a4d;
    }

    .premium-switch input:checked+.slider:before {
        transform: translateX(22px);
    }

    /* ── SECTION DIVIDER ── */
    .wz-section-title {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 1.5rem 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* ── COST TOGGLE INLINE ── */
    .wz-cost-toggle {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .wz-cost-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    .wz-cost-input {
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .wz-cost-input .currency {
        font-size: 0.9rem;
        font-weight: 600;
        color: #64748b;
    }

    /* ── SCHEDULE ROW ── */
    .wz-schedule-row {
        display: flex;
        align-items: center;
        gap: 0.85rem;
        padding: 0.85rem 1rem;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 0.5rem;
        transition: background 0.2s;
    }

    .wz-schedule-row.disabled {
        background: #f8fafc;
        opacity: 0.5;
    }

    .wz-schedule-row .day-name {
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        min-width: 80px;
    }

    .wz-schedule-row .time-selects {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex: 1;
    }

    .wz-schedule-row .time-sep {
        font-size: 0.82rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .wz-schedule-row .duration {
        font-size: 0.78rem;
        color: #94a3b8;
        font-weight: 500;
        min-width: 40px;
    }

    .wz-time-select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.45rem 0.65rem;
        font-size: 0.82rem;
        color: #334155;
        background: #ffffff;
        width: 120px;
    }

    /* ── REVIEW CARDS ── */
    .wz-review-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .wz-review-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .wz-review-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .wz-review-card-header h6 {
        font-size: 0.82rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .wz-edit-link {
        font-size: 0.78rem;
        color: #64748b;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        cursor: pointer;
        transition: color 0.2s;
    }

    .wz-edit-link:hover {
        color: #1e293b;
    }

    .wz-review-img {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 1rem;
        background: #f1f5f9;
    }

    .wz-review-label {
        font-size: 0.68rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .wz-review-value {
        font-size: 0.88rem;
        color: #1e293b;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .wz-review-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.82rem;
        color: #1e293b;
        margin-bottom: 0.4rem;
    }

    .wz-review-item i {
        color: #10b981;
        font-size: 0.85rem;
    }

    /* ── DAY PILLS ── */
    .wz-day-pills {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
        margin-bottom: 0.75rem;
    }

    .wz-day-pill {
        padding: 0.45rem 0.75rem;
        border-radius: 8px;
        font-size: 0.72rem;
        font-weight: 600;
        text-align: center;
        min-width: 70px;
    }

    .wz-day-pill.enabled {
        background: #2f3a4d;
        color: #ffffff;
    }

    .wz-day-pill.disabled-day {
        background: #f1f5f9;
        color: #94a3b8;
    }

    .wz-day-pill .pill-day {
        display: block;
    }

    .wz-day-pill .pill-time {
        font-size: 0.65rem;
        font-weight: 400;
        opacity: 0.8;
    }

    /* ── DOCUMENTS ── */
    .wz-doc-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.85rem 1rem;
        background: #fffbeb;
        border: 1px solid #fef3c7;
        border-radius: 10px;
        margin-bottom: 0.5rem;
    }

    .wz-doc-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #fef2f2;
        color: #ef4444;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .wz-doc-info {
        flex: 1;
    }

    .wz-doc-info .doc-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: #1e293b;
    }

    .wz-doc-info .doc-meta {
        font-size: 0.72rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .wz-doc-status {
        font-size: 0.68rem;
        font-weight: 600;
        color: #f59e0b;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .wz-doc-actions {
        display: flex;
        gap: 0.35rem;
    }

    .wz-doc-actions button {
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 4px;
        transition: all 0.15s;
        font-size: 0.85rem;
    }

    .wz-doc-actions button:hover {
        color: #1e293b;
        background: #f1f5f9;
    }

    .wz-doc-actions .btn-delete:hover {
        color: #ef4444;
        background: #fef2f2;
    }

    .wz-add-doc-btn {
        background: none;
        border: none;
        color: #2563eb;
        font-size: 0.82rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.35rem;
        transition: color 0.2s;
    }

    .wz-add-doc-btn:hover {
        color: #1d4ed8;
    }

    /* ── NAVIGATION BUTTONS ── */
    .wz-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 0;
    }

    .wz-btn-prev {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 0.55rem 1.25rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .wz-btn-prev:hover {
        border-color: #cbd5e1;
        color: #1e293b;
    }

    .wz-btn-next {
        background: #1e293b;
        border: none;
        color: #ffffff;
        padding: 0.55rem 1.5rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .wz-btn-next:hover {
        background: #334155;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
    }

    .wz-btn-next:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .wz-btn-create {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: #ffffff;
        padding: 0.65rem 2rem;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .wz-btn-create:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    @media (max-width: 768px) {
        .wz-review-grid {
            grid-template-columns: 1fr;
        }

        .wz-stepper {
            gap: 0;
        }

        .wz-step {
            min-width: 80px;
        }

        .wz-step-label {
            font-size: 0.6rem;
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
$isEdit = $isEdit ?? false;
$a = $amenity ?? [];
$dayNames = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
$dayAbbr = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM'];

// Build schedule map from existing data
$schedMap = [];
foreach (($schedules ?? []) as $s) {
    $schedMap[(int) $s['day_of_week']] = $s;
}
?>


<!-- ═══ HERO ═══ -->
<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">
            <?= $isEdit ? 'Editar Amenidad' : 'Crear Nueva Amenidad' ?>
        </h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-calendar-check"></i>
            <i class="bi bi-chevron-right" style="font-size:.65rem;color:#94a3b8"></i>
            <?= $isEdit ? 'Modifique los detalles de esta amenidad' : 'Ingrese la información básica sobre esta amenidad' ?>

        </div>
    </div>

</div>

<!-- STEPPER -->
<div class="wz-stepper">
    <div class="wz-step active" data-step="1">
        <div class="wz-step-circle">1</div>
        <div class="wz-step-label">Información Básica</div>
    </div>
    <div class="wz-step-line" data-line="1"></div>
    <div class="wz-step" data-step="2">
        <div class="wz-step-circle">2</div>
        <div class="wz-step-label">Configuración de Reservas</div>
    </div>
    <div class="wz-step-line" data-line="2"></div>
    <div class="wz-step" data-step="3">
        <div class="wz-step-circle">3</div>
        <div class="wz-step-label">Horario Semanal</div>
    </div>
    <div class="wz-step-line" data-line="3"></div>
    <div class="wz-step" data-step="4">
        <div class="wz-step-circle">4</div>
        <div class="wz-step-label">Revisión y Documentos</div>
    </div>
</div>

<!-- ╔══════════════════════════════════════╗ -->
<!-- ║  PASO 1 — INFORMACIÓN BÁSICA        ║ -->
<!-- ╚══════════════════════════════════════╝ -->
<div class="wz-panel active" id="panel-1">
    <div class="wz-card">
        <div class="wz-card-title">Información Básica</div>
        <div class="wz-card-subtitle">Agregue los detalles esenciales de su amenidad</div>
        <div class="row g-4">
            <div class="col-md-5">
                <label class="wz-label"><span style="color:#2563eb;">Imagen de la Amenidad</span></label>
                <div class="wz-image-upload <?= !empty($a['image']) ? 'has-image' : '' ?>" id="imageUploadZone"
                    onclick="document.getElementById('wizImageFile').click()">
                    <i class="bi bi-image-alt upload-icon"></i>
                    <div class="upload-text">Agregar Imagen</div>
                    <div class="upload-hint">PNG, JPG up to 5MB</div>
                    <img class="wz-image-preview" id="wizImagePreview"
                        src="<?= !empty($a['image']) ? base_url('admin/amenidades/imagen/' . $a['image']) : '' ?>"
                        alt="">
                    <input type="file" id="wizImageFile" accept="image/*" style="display:none"
                        onchange="previewWizImage(this)">
                </div>
            </div>
            <div class="col-md-7">
                <div class="mb-3">
                    <label class="wz-label">Nombre <span class="required">*</span></label>
                    <input type="text" class="wz-input" id="wizName"
                        placeholder="e.j. Piscina, Gimnasio, Sala de Reuniones" value="<?= esc($a['name'] ?? '') ?>">
                </div>
                <div>
                    <label class="wz-label">Descripción</label>
                    <textarea class="wz-input wz-textarea" id="wizDescription"
                        placeholder="Describa la amenidad, sus características y reglas..."><?= esc($a['description'] ?? '') ?></textarea>
                </div>
            </div>
        </div>
    </div>
    <div class="wz-nav">
        <div></div>
        <button class="cc-hero-btn" onclick="goToStep(2)">Siguiente <i class="bi bi-chevron-right"></i></button>
    </div>
</div>

<!-- ╔══════════════════════════════════════╗ -->
<!-- ║  PASO 2 — CONFIGURACIÓN DE RESERVAS ║ -->
<!-- ╚══════════════════════════════════════╝ -->
<div class="wz-panel" id="panel-2">
    <div class="wz-card">
        <div class="wz-card-title">Configuración de Reservas</div>
        <div class="wz-card-subtitle">Configure cómo los residentes pueden reservar esta amenidad</div>

        <!-- Toggle: Puede ser reservada -->
        <div class="wz-toggle-card">
            <div class="toggle-info">
                <h6>Puede ser reservada</h6>
                <p>Controla si los usuarios pueden hacer reservas para esta amenidad. Las amenidades que son reservables
                    (cancha de tenis, cancha de pádel, etc.) deben configurarse como reservables, las amenidades que no
                    son reservables (piscina, gimnasio, etc.) deben configurarse como no reservables.</p>
            </div>
            <label class="premium-switch">
                <input type="checkbox" id="wizIsReservable" <?= ($a['is_reservable'] ?? 1) == 1 ? 'checked' : '' ?>
                    onchange="toggleReservableSection()">
                <span class="slider"></span>
            </label>
        </div>

        <div id="reservableSection" style="<?= ($a['is_reservable'] ?? 1) == 0 ? 'display:none' : '' ?>">
            <!-- REGLAS DE RESERVA -->
            <div class="wz-section-title">REGLAS DE RESERVA</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="wz-label">Intervalo de Reserva <i class="bi bi-info-circle info-icon"
                            title="Duración de cada bloque de reserva"></i></label>
                    <select class="wz-select" id="wizInterval">
                        <?php
                        $intervals = ['1' => '1 hora', '2' => '2 horas', '3' => '3 horas', '4' => '4 horas', '5' => '5 horas', '6' => '6 horas', 'full_day' => 'Día completo'];
                        $currentInterval = $a['reservation_interval'] ?? '1';
                        foreach ($intervals as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $currentInterval == $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Máximo de Reservas Activas <i class="bi bi-info-circle info-icon"
                            title="Número máximo de reservas activas que un residente puede tener a la vez"></i></label>
                    <select class="wz-select" id="wizMaxReservations">
                        <?php
                        $maxRes = ['1' => '1 reserva', '2' => '2 reservas', '3' => '3 reservas', '4' => '4 reservas', '5' => '5 reservas', '6' => '6 reservas', 'unlimited' => 'Ilimitadas'];
                        $currentMax = $a['max_active_reservations'] ?? 'unlimited';
                        foreach ($maxRes as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $currentMax == $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="wz-label">Tiene costo? <i class="bi bi-info-circle info-icon"
                            title="Si tiene costo, se cargará una cuota por cada reserva"></i></label>
                    <div class="wz-cost-toggle mt-1">
                        <label class="premium-switch">
                            <input type="checkbox" id="wizHasCost" <?= ($a['has_cost'] ?? 0) == 1 ? 'checked' : '' ?>
                                onchange="toggleCostField()">
                            <span class="slider"></span>
                        </label>
                        <span class="wz-cost-label"
                            id="costLabel"><?= ($a['has_cost'] ?? 0) == 1 ? 'Sí' : 'No' ?></span>
                        <div class="wz-cost-input" id="costInputWrap"
                            style="<?= ($a['has_cost'] ?? 0) == 0 ? 'display:none' : '' ?>">
                            <span class="currency">$</span>
                            <input type="number" class="wz-input" id="wizPrice"
                                value="<?= esc($a['price'] ?? '0.00') ?>" step="0.01" min="0" style="width:100px;">
                        </div>
                    </div>
                </div>
            </div>

            <!-- CONFIGURACIÓN DE APROBACIÓN -->
            <div class="wz-section-title">CONFIGURACIÓN DE APROBACIÓN</div>
            <div class="wz-toggle-card">
                <div class="toggle-info">
                    <h6><i class="bi bi-shield-check toggle-icon"></i> Requiere Aprobación Manual</h6>
                    <p>Cuando está habilitado, todas las solicitudes de reserva deben ser aprobadas por un administrador
                        antes de ser confirmadas. El usuario será notificado una vez que su solicitud sea aprobada o
                        rechazada.</p>
                </div>
                <label class="premium-switch">
                    <input type="checkbox" id="wizRequiresApproval" <?= ($a['requires_approval'] ?? 0) == 1 ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
            </div>

            <!-- PERÍODO DE DISPONIBILIDAD -->
            <div class="wz-section-title">PERÍODO DE DISPONIBILIDAD</div>
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <label class="wz-label">Reservable Desde <i class="bi bi-info-circle info-icon"
                            title="La fecha desde la cual se puede reservar esta amenidad"></i></label>
                    <div class="position-relative">
                        <input type="text" class="wz-input" id="wizAvailableFrom" placeholder="Seleccionar fecha..."
                            value="<?= esc($a['available_from'] ?? date('Y-m-d')) ?>" readonly style="cursor:pointer;">
                    </div>
                </div>
                <div class="col-md-7">
                    <label class="wz-label">Días Bloqueados <i class="bi bi-info-circle info-icon"
                            title="Fechas en que la amenidad no puede ser reservada"></i></label>
                    <div class="position-relative">
                        <input type="text" class="wz-input" id="wizBlockedDates" placeholder="Sin fechas bloqueadas"
                            value="<?php
                            $bd = $a['blocked_dates'] ?? '';
                            if ($bd) {
                                $arr = json_decode($bd, true);
                                echo is_array($arr) ? implode(', ', $arr) : '';
                            }
                            ?>" readonly style="cursor:pointer;">
                    </div>
                </div>
            </div>

            <!-- MENSAJE DE RESERVA -->
            <div class="mb-3">
                <label class="wz-label">Mensaje de Reserva <span class="text-muted"
                        style="font-weight:400;font-size:0.72rem;">opcional</span></label>
                <textarea class="wz-input wz-textarea" id="wizReservationMessage" rows="3"
                    placeholder="Mensaje opcional para mostrar al reservar"
                    style="min-height:70px;"><?= esc($a['reservation_message'] ?? '') ?></textarea>
            </div>
        </div>
    </div>
    <div class="wz-nav">
        <button class="cc-hero-btn" onclick="goToStep(1)"><i class="bi bi-chevron-left"></i> Anterior</button>
        <button class="cc-hero-btn" onclick="goToStep(3)">Siguiente <i class="bi bi-chevron-right"></i></button>
    </div>
</div>

<!-- ╔══════════════════════════════════════╗ -->
<!-- ║  PASO 3 — HORARIO SEMANAL           ║ -->
<!-- ╚══════════════════════════════════════╝ -->
<div class="wz-panel" id="panel-3">
    <div class="wz-card">
        <div class="wz-card-title">Horario Semanal</div>
        <div class="wz-card-subtitle">Configure las horas disponibles para cada día de la semana</div>

        <?php for ($d = 0; $d < 7; $d++):
            $sched = $schedMap[$d] ?? null;
            $enabled = $sched ? (int) $sched['is_enabled'] : ($d < 5 ? 1 : 0);
            $openT = $sched ? substr($sched['open_time'], 0, 5) : '09:00';
            $closeT = $sched ? substr($sched['close_time'], 0, 5) : '18:00';
            ?>
            <div class="wz-schedule-row <?= $enabled ? '' : 'disabled' ?>" id="schedRow<?= $d ?>">
                <label class="premium-switch">
                    <input type="checkbox" class="sched-toggle" data-day="<?= $d ?>" <?= $enabled ? 'checked' : '' ?>
                        onchange="toggleScheduleDay(<?= $d ?>)">
                    <span class="slider"></span>
                </label>
                <div class="day-name"><?= $dayNames[$d] ?></div>
                <div class="time-selects">
                    <select class="wz-time-select sched-open" data-day="<?= $d ?>" id="schedOpen<?= $d ?>"
                        onchange="updateDuration(<?= $d ?>)" <?= $enabled ? '' : 'disabled' ?>>
                        <?= generateTimeOptions($openT) ?>
                    </select>
                    <span class="time-sep">a</span>
                    <select class="wz-time-select sched-close" data-day="<?= $d ?>" id="schedClose<?= $d ?>"
                        onchange="updateDuration(<?= $d ?>)" <?= $enabled ? '' : 'disabled' ?>>
                        <?= generateTimeOptions($closeT) ?>
                    </select>
                </div>
                <span class="duration" id="schedDuration<?= $d ?>"><?= calcDuration($openT, $closeT) ?></span>
            </div>
        <?php endfor; ?>
    </div>
    <div class="wz-nav">
        <button class="cc-hero-btn" onclick="goToStep(2)"><i class="bi bi-chevron-left"></i> Anterior</button>
        <button class="cc-hero-btn" onclick="goToStep(4)">Siguiente <i class="bi bi-chevron-right"></i></button>
    </div>
</div>

<!-- ╔══════════════════════════════════════╗ -->
<!-- ║  PASO 4 — REVISIÓN Y DOCUMENTOS     ║ -->
<!-- ╚══════════════════════════════════════╝ -->
<div class="wz-panel" id="panel-4">
    <div class="wz-card" style="padding:1rem 1.75rem;">
        <div class="wz-card-title">Revisión y Documentos</div>
        <div class="wz-card-subtitle">Revise los detalles de su amenidad y agregue documentos</div>
    </div>

    <!-- Review Grid -->
    <div class="wz-review-grid">
        <!-- INFO BÁSICA -->
        <div class="wz-review-card">
            <div class="wz-review-card-header">
                <h6><i class="bi bi-info-circle"></i> INFORMACIÓN BÁSICA</h6>
                <a class="wz-edit-link" onclick="goToStep(1)"><i class="bi bi-pencil"></i> Editar</a>
            </div>
            <img class="wz-review-img" id="reviewImage"
                src="<?= !empty($a['image']) ? base_url('admin/amenidades/imagen/' . $a['image']) : '' ?>"
                style="<?= empty($a['image']) ? 'display:none' : '' ?>">
            <div class="wz-review-label">NOMBRE</div>
            <div class="wz-review-value" id="reviewName"><?= esc($a['name'] ?? '') ?></div>
            <div class="wz-review-label">DESCRIPCIÓN</div>
            <div class="wz-review-value" id="reviewDescription"><?= esc($a['description'] ?? '') ?></div>
        </div>

        <!-- CONFIG RESERVAS -->
        <div class="wz-review-card">
            <div class="wz-review-card-header">
                <h6><i class="bi bi-gear"></i> CONFIGURACIÓN DE RESERVAS</h6>
                <a class="wz-edit-link" onclick="goToStep(2)"><i class="bi bi-pencil"></i> Editar</a>
            </div>
            <div id="reviewReservationConfig">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>

    <!-- HORARIO SEMANAL REVIEW -->
    <div class="wz-review-card" style="margin-bottom:1.25rem;">
        <div class="wz-review-card-header">
            <h6><i class="bi bi-calendar-week"></i> HORARIO SEMANAL</h6>
            <a class="wz-edit-link" onclick="goToStep(3)"><i class="bi bi-pencil"></i> Editar</a>
        </div>
        <div class="wz-day-pills" id="reviewSchedulePills">
            <!-- Populated by JS -->
        </div>
        <div class="text-muted" style="font-size:0.78rem;" id="reviewActiveDays"></div>
    </div>

    <!-- DOCUMENTOS -->
    <div class="wz-review-card" style="margin-bottom:1.25rem;">
        <div class="wz-review-card-header">
            <h6><i class="bi bi-file-earmark-text"></i> DOCUMENTOS <span class="text-muted"
                    style="font-size:0.68rem; font-weight:400;">(opcional)</span></h6>
            <button class="wz-add-doc-btn" onclick="document.getElementById('wizDocInput').click()">
                <i class="bi bi-plus-lg"></i> Agregar Documento
            </button>
        </div>
        <p class="text-muted" style="font-size:0.78rem; margin-bottom:1rem;">Agregar reglas, regulaciones o lineamientos
            para esta amenidad. Los documentos serán visibles para todos los residentes.</p>
        <input type="file" id="wizDocInput" accept=".pdf,.doc,.docx,.jpg,.png,.jpeg" multiple style="display:none"
            onchange="handleDocUpload(this)">
        <div id="documentsContainer">
            <?php foreach (($documents ?? []) as $doc): ?>
                <div class="wz-doc-item" data-existing-id="<?= $doc['id'] ?>">
                    <div class="wz-doc-icon"><i class="bi bi-file-earmark-pdf"></i></div>
                    <div class="wz-doc-info">
                        <div class="doc-name"><?= esc($doc['title']) ?></div>
                        <div class="doc-meta">
                            <span><?= number_format(($doc['file_size'] ?? 0) / 1048576, 2) ?> MB</span>
                        </div>
                    </div>
                    <div class="wz-doc-actions">
                        <button class="btn-delete" onclick="removeDocument(this, <?= $doc['id'] ?>)" title="Eliminar"><i
                                class="bi bi-trash"></i></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- SUBMIT -->
    <div class="wz-nav">
        <button class="wz-btn-prev" onclick="goToStep(3)"><i class="bi bi-chevron-left"></i> Anterior</button>
        <button class="wz-btn-create" onclick="submitWizard()" id="wizSubmitBtn">
            <i class="bi bi-check-circle"></i> <?= $isEdit ? 'Guardar Cambios' : 'Crear Amenidad' ?>
        </button>
    </div>
</div>

<?php
// Helper functions for time options
function generateTimeOptions($selected = '09:00')
{
    $options = '';
    for ($h = 0; $h < 24; $h++) {
        for ($m = 0; $m < 60; $m += 30) {
            $time = sprintf('%02d:%02d', $h, $m);
            $display = date('g:i A', strtotime($time));
            $sel = ($time === $selected) ? 'selected' : '';
            $options .= "<option value=\"{$time}\" {$sel}>{$display}</option>";
        }
    }
    return $options;
}

function calcDuration($open, $close)
{
    $openMin = intval(substr($open, 0, 2)) * 60 + intval(substr($open, 3, 2));
    $closeMin = intval(substr($close, 0, 2)) * 60 + intval(substr($close, 3, 2));
    $diff = $closeMin - $openMin;
    if ($diff <= 0)
        return '';
    $hours = floor($diff / 60);
    $mins = $diff % 60;
    return '(' . ($mins > 0 ? "{$hours}h{$mins}m" : "{$hours}h") . ')';
}
?>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    const BASE = '<?= base_url() ?>';
    const IS_EDIT = <?= $isEdit ? 'true' : 'false' ?>;
    const HASH_ID = '<?= esc($a['hash_id'] ?? '') ?>';
    const DAY_NAMES = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
    const DAY_ABBR = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM'];

    let currentStep = 1;
    let newDocuments = []; // { file, title }

    // ══════ STEPPER NAVIGATION ══════
    function goToStep(step) {
        // Validate step 1
        if (step > 1 && currentStep === 1) {
            const name = document.getElementById('wizName').value.trim();
            if (!name) {
                Swal.fire({ icon: 'warning', title: 'Campo requerido', text: 'El nombre de la amenidad es requerido', confirmButtonColor: '#1e293b' });
                return;
            }
        }

        // Update review data when going to step 4
        if (step === 4) populateReview();

        currentStep = step;

        // Update panels
        document.querySelectorAll('.wz-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('panel-' + step).classList.add('active');

        // Update stepper circles
        document.querySelectorAll('.wz-step').forEach(s => {
            const sStep = parseInt(s.dataset.step);
            s.classList.remove('active', 'completed');
            if (sStep === step) s.classList.add('active');
            else if (sStep < step) s.classList.add('completed');
        });

        // Update stepper lines
        document.querySelectorAll('.wz-step-line').forEach(l => {
            const lStep = parseInt(l.dataset.line);
            l.classList.toggle('completed', lStep < step);
        });

        // Update completed step circles to checkmarks
        document.querySelectorAll('.wz-step.completed .wz-step-circle').forEach(c => {
            c.innerHTML = '<i class="bi bi-check-lg" style="font-size:0.9rem;"></i>';
        });
        document.querySelectorAll('.wz-step:not(.completed) .wz-step-circle').forEach(c => {
            const sStep = parseInt(c.parentElement.dataset.step);
            c.textContent = sStep;
        });

        // Update header subtitle
        const subtitles = {
            1: IS_EDIT ? 'Modifique los detalles de esta amenidad' : 'Ingrese la información básica sobre esta amenidad',
            2: 'Configure las opciones de reserva para esta amenidad',
            3: 'Defina el horario semanal de disponibilidad',
            4: 'Verifique toda la información antes de guardar'
        };
        document.querySelector('.wz-header-info p').textContent = subtitles[step];

        // Scroll to top
        document.querySelector('.content-scrollable').scrollTo({ top: 0, behavior: 'smooth' });
    }

    // ══════ IMAGE PREVIEW ══════
    function previewWizImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('wizImagePreview');
                preview.src = e.target.result;
                document.getElementById('imageUploadZone').classList.add('has-image');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ══════ TOGGLE SECTIONS ══════
    function toggleReservableSection() {
        const checked = document.getElementById('wizIsReservable').checked;
        document.getElementById('reservableSection').style.display = checked ? '' : 'none';
    }

    function toggleCostField() {
        const checked = document.getElementById('wizHasCost').checked;
        document.getElementById('costInputWrap').style.display = checked ? '' : 'none';
        document.getElementById('costLabel').textContent = checked ? 'Sí' : 'No';
    }

    // ══════ SCHEDULE ══════
    function toggleScheduleDay(day) {
        const cb = document.querySelector(`.sched-toggle[data-day="${day}"]`);
        const row = document.getElementById('schedRow' + day);
        const openSel = document.getElementById('schedOpen' + day);
        const closeSel = document.getElementById('schedClose' + day);

        if (cb.checked) {
            row.classList.remove('disabled');
            openSel.disabled = false;
            closeSel.disabled = false;
        } else {
            row.classList.add('disabled');
            openSel.disabled = true;
            closeSel.disabled = true;
        }
    }

    function updateDuration(day) {
        const openSel = document.getElementById('schedOpen' + day);
        const closeSel = document.getElementById('schedClose' + day);
        const durEl = document.getElementById('schedDuration' + day);

        const [oh, om] = openSel.value.split(':').map(Number);
        const [ch, cm] = closeSel.value.split(':').map(Number);
        const diff = (ch * 60 + cm) - (oh * 60 + om);

        if (diff <= 0) { durEl.textContent = ''; return; }
        const h = Math.floor(diff / 60);
        const m = diff % 60;
        durEl.textContent = '(' + (m > 0 ? h + 'h' + m + 'm' : h + 'h') + ')';
    }

    // ══════ DOCUMENTS ══════
    function handleDocUpload(input) {
        const container = document.getElementById('documentsContainer');
        for (const file of input.files) {
            const idx = newDocuments.length;
            const title = file.name.replace(/\.[^/.]+$/, '');
            newDocuments.push({ file, title });

            const sizeMB = (file.size / 1048576).toFixed(2);
            const ext = file.name.split('.').pop().toLowerCase();
            const iconClass = ext === 'pdf' ? 'bi-file-earmark-pdf' : (ext.match(/^(jpg|jpeg|png|gif)$/) ? 'bi-file-earmark-image' : 'bi-file-earmark-text');

            const div = document.createElement('div');
            div.className = 'wz-doc-item';
            div.dataset.newIdx = idx;
            div.innerHTML = `
            <div class="wz-doc-icon"><i class="bi ${iconClass}"></i></div>
            <div class="wz-doc-info">
                <input type="text" class="wz-input doc-title-input" value="${title}" 
                       style="padding:0.3rem 0.5rem; font-size:0.82rem; font-weight:600; border:1px solid transparent; background:transparent;"
                       onfocus="this.style.borderColor='#e2e8f0'; this.style.background='#fff';"
                       onblur="this.style.borderColor='transparent'; this.style.background='transparent'; newDocuments[${idx}].title = this.value;">
                <div class="doc-meta">
                    <span>${sizeMB} MB</span>
                    <span class="wz-doc-status"><i class="bi bi-circle-fill" style="font-size:0.4rem;"></i> Pendiente</span>
                </div>
            </div>
            <div class="wz-doc-actions">
                <button class="btn-delete" onclick="removeNewDocument(this, ${idx})" title="Eliminar"><i class="bi bi-trash"></i></button>
            </div>
        `;
            container.appendChild(div);
        }
        input.value = '';
    }

    function removeNewDocument(btn, idx) {
        newDocuments[idx] = null;
        btn.closest('.wz-doc-item').remove();
    }

    function removeDocument(btn, existingId) {
        Swal.fire({
            title: '¿Eliminar documento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626',
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const r = await fetch(BASE + 'admin/amenidades/documento/eliminar/' + existingId, {
                        method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const j = await r.json();
                    if (j.status === 200) {
                        btn.closest('.wz-doc-item').remove();
                    }
                } catch (e) { }
            }
        });
    }

    // ══════ POPULATE REVIEW ══════
    function populateReview() {
        // Info básica
        document.getElementById('reviewName').textContent = document.getElementById('wizName').value || 'Sin nombre';
        document.getElementById('reviewDescription').textContent = document.getElementById('wizDescription').value || 'Sin descripción';

        const imgPreview = document.getElementById('wizImagePreview');
        const reviewImg = document.getElementById('reviewImage');
        if (imgPreview.src && document.getElementById('imageUploadZone').classList.contains('has-image')) {
            reviewImg.src = imgPreview.src;
            reviewImg.style.display = '';
        } else {
            reviewImg.style.display = 'none';
        }

        // Configuración reservas
        const isReservable = document.getElementById('wizIsReservable').checked;
        const configDiv = document.getElementById('reviewReservationConfig');

        if (!isReservable) {
            configDiv.innerHTML = '<div class="wz-review-item"><i class="bi bi-x-circle" style="color:#ef4444;"></i> No reservable</div>';
        } else {
            const intervalText = document.getElementById('wizInterval').options[document.getElementById('wizInterval').selectedIndex].text;
            const availFrom = document.getElementById('wizAvailableFrom').value;
            const requiresApproval = document.getElementById('wizRequiresApproval').checked;
            const hasCost = document.getElementById('wizHasCost').checked;
            const price = document.getElementById('wizPrice').value;

            let html = '<div class="wz-review-item"><i class="bi bi-check-circle"></i> Reservas habilitadas</div>';
            html += `<div class="wz-review-item"><i class="bi bi-clock"></i> ${intervalText}</div>`;
            if (availFrom) html += `<div class="wz-review-item"><i class="bi bi-calendar-event"></i> Desde ${formatDateDisplay(availFrom)}</div>`;
            if (hasCost) html += `<div class="wz-review-item"><i class="bi bi-currency-dollar"></i> Costo: $${parseFloat(price).toFixed(2)}</div>`;
            if (requiresApproval) html += '<div class="wz-review-item"><i class="bi bi-shield-check"></i> Requiere aprobación manual</div>';
            configDiv.innerHTML = html;
        }

        // Horario semanal
        const pillsDiv = document.getElementById('reviewSchedulePills');
        let pillsHtml = '';
        let activeDays = 0;

        for (let d = 0; d < 7; d++) {
            const enabled = document.querySelector(`.sched-toggle[data-day="${d}"]`).checked;
            if (enabled) {
                activeDays++;
                const open = document.getElementById('schedOpen' + d).value;
                const close = document.getElementById('schedClose' + d).value;
                pillsHtml += `<div class="wz-day-pill enabled"><span class="pill-day">${DAY_ABBR[d]}</span><span class="pill-time">${open}-${close}</span></div>`;
            } else {
                pillsHtml += `<div class="wz-day-pill disabled-day"><span class="pill-day">${DAY_ABBR[d]}</span></div>`;
            }
        }

        pillsDiv.innerHTML = pillsHtml;
        document.getElementById('reviewActiveDays').textContent = activeDays + ' día(s) activo(s)';
    }

    function formatDateDisplay(dateStr) {
        if (!dateStr) return '';
        try {
            const d = new Date(dateStr + 'T12:00:00');
            const months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
            return `${d.getDate()} de ${months[d.getMonth()]}, ${d.getFullYear()}`;
        } catch (e) { return dateStr; }
    }

    // ══════ SUBMIT WIZARD ══════
    async function submitWizard() {
        const btn = document.getElementById('wizSubmitBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Guardando...';

        const fd = new FormData();

        // Step 1
        fd.append('name', document.getElementById('wizName').value);
        fd.append('description', document.getElementById('wizDescription').value);
        const imageFile = document.getElementById('wizImageFile').files[0];
        if (imageFile) fd.append('image', imageFile);

        // Step 2
        fd.append('is_reservable', document.getElementById('wizIsReservable').checked ? 1 : 0);
        fd.append('reservation_interval', document.getElementById('wizInterval').value);
        fd.append('max_active_reservations', document.getElementById('wizMaxReservations').value);
        fd.append('has_cost', document.getElementById('wizHasCost').checked ? 1 : 0);
        fd.append('price', document.getElementById('wizPrice').value || '0');
        fd.append('requires_approval', document.getElementById('wizRequiresApproval').checked ? 1 : 0);
        fd.append('available_from', document.getElementById('wizAvailableFrom').value || '');

        // Blocked dates
        const blockedInput = document.getElementById('wizBlockedDates').value;
        if (blockedInput) {
            const dates = blockedInput.split(',').map(d => d.trim()).filter(d => d);
            fd.append('blocked_dates', JSON.stringify(dates));
        }

        fd.append('reservation_message', document.getElementById('wizReservationMessage').value);

        // Step 3 - Schedule
        const schedule = [];
        for (let d = 0; d < 7; d++) {
            schedule.push({
                day_of_week: d,
                is_enabled: document.querySelector(`.sched-toggle[data-day="${d}"]`).checked ? 1 : 0,
                open_time: document.getElementById('schedOpen' + d).value,
                close_time: document.getElementById('schedClose' + d).value,
            });
        }
        fd.append('schedule', JSON.stringify(schedule));

        // Step 4 - Documents
        const docTitles = [];
        newDocuments.forEach((doc, i) => {
            if (doc) {
                fd.append('documents[]', doc.file);
                docTitles.push(doc.title);
            }
        });
        docTitles.forEach(t => fd.append('document_titles[]', t));

        const url = IS_EDIT
            ? BASE + 'admin/amenidades/actualizar-wizard/' + HASH_ID
            : BASE + 'admin/amenidades/crear-wizard';

        try {
            const r = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const j = await r.json();

            if (j.status === 201 || j.status === 200) {
                Swal.fire({
                    icon: 'success',
                    title: IS_EDIT ? '¡Amenidad Actualizada!' : '¡Amenidad Creada!',
                    text: j.message,
                    timer: 2000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                });
                setTimeout(() => {
                    window.location.href = BASE + 'admin/amenidades';
                }, 1800);
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: j.error || 'Error desconocido', confirmButtonColor: '#1e293b' });
                btn.disabled = false;
                btn.innerHTML = `<i class="bi bi-check-circle"></i> ${IS_EDIT ? 'Guardar Cambios' : 'Crear Amenidad'}`;
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Error de conexión', text: err.message, confirmButtonColor: '#1e293b' });
            btn.disabled = false;
            btn.innerHTML = `<i class="bi bi-check-circle"></i> ${IS_EDIT ? 'Guardar Cambios' : 'Crear Amenidad'}`;
        }
    }

    // ══════ INIT ══════
    document.addEventListener('DOMContentLoaded', function () {
        // Flatpickr — Available From
        flatpickr('#wizAvailableFrom', {
            locale: 'es',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'F j, Y',
            minDate: 'today',
            disableMobile: true,
        });

        // Flatpickr — Blocked Dates (multiple)
        flatpickr('#wizBlockedDates', {
            locale: 'es',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'M j, Y',
            mode: 'multiple',
            conjunction: ', ',
            disableMobile: true,
        });

        // Init durations
        for (let d = 0; d < 7; d++) updateDuration(d);
    });
</script>
<?= $this->endSection() ?>