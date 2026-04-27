<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$mesesES = ['January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'];
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Flatpickr Calendar Premium Theme Override */
    .flatpickr-calendar {
        border-radius: 10px !important;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid #e2e8f0 !important;
        font-family: inherit !important;
    }

    .flatpickr-months .flatpickr-month {
        background: #ffffff !important;
        color: #1e293b !important;
        height: 40px !important;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months,
    .flatpickr-current-month input.cur-year {
        color: #1e293b !important;
        font-weight: 600 !important;
        font-size: 0.95rem !important;
    }

    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        fill: #64748b !important;
        color: #64748b !important;
    }

    .flatpickr-months .flatpickr-prev-month:hover,
    .flatpickr-months .flatpickr-next-month:hover {
        fill: #1e293b !important;
        color: #1e293b !important;
    }

    span.flatpickr-weekday {
        color: #64748b !important;
        font-weight: 600 !important;
        font-size: 0.75rem !important;
    }

    .flatpickr-day {
        border-radius: 6px !important;
        color: #1e293b !important;
        font-size: 0.85rem !important;
    }

    .flatpickr-day:hover {
        background: #f1f5f9 !important;
        border-color: #f1f5f9 !important;
    }

    .flatpickr-day.selected {
        background: #334155 !important;
        border-color: #334155 !important;
        color: #fff !important;
    }

    .flatpickr-day.today {
        border-color: #3b82f6 !important;
    }

    .flatpickr-day.today:hover {
        background: #3b82f6 !important;
        color: #fff !important;
        border-color: #3b82f6 !important;
    }

    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
        color: #cbd5e1 !important;
    }

    .billing-date-trigger {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 0.85rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #fff;
        cursor: pointer;
        font-size: 0.9rem;
        color: #64748b;
        width: fit-content;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .billing-date-trigger:hover {
        border-color: #94a3b8;
    }

    .billing-date-trigger.has-value {
        color: #1e293b;
        font-weight: 500;
    }

    .billing-date-trigger i {
        color: #64748b;
        font-size: 0.9rem;
    }

    .billing-date-wrapper {
        position: relative;
    }

    .billing-date-wrapper .flatpickr-calendar {
        position: relative !important;
        top: auto !important;
        left: auto !important;
        right: auto !important;
        margin-top: 8px;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* ========================================================================= */
    /* ESTILOS PREMIUM PARA EL MÓDULO DE FINANZAS                                */
    /* ========================================================================= */

    /* Contenedores y Tarjetas */
    .fc-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        border: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
        height: 100%;
    }

    .fc-header {
        background: #232d3f;
        border-radius: 8px;
        padding: 1.5rem 2rem;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .fc-header-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .fc-header-subtitle {
        font-size: 0.85rem;
        color: #94a3b8;
        margin: 0;
    }

    /* Onboarding (Fase 1) */
    .ob-container {
        max-width: 650px;
        margin: 2rem auto;
        padding: 2.5rem;
    }

    .ob-title-box {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        color: #1e293b;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .ob-title-icon {
        color: #f59e0b;
        /* Amarillo/naranja tipo engranaje */
        font-size: 1.25rem;
    }

    .ob-subtitle {
        color: #64748b;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .ob-progress-container {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .ob-progress-bar-bg {
        flex-grow: 1;
        height: 6px;
        background: #e2e8f0;
        border-radius: 3px;
        overflow: hidden;
    }

    .ob-progress-bar-fill {
        height: 100%;
        background: #3b82f6;
        /* Azul primario */
        width: 100%;
        border-radius: 3px;
    }

    .ob-progress-text {
        font-size: 0.75rem;
        color: #64748b;
        font-weight: 600;
    }

    /* Lista de requisitos Onboarding */
    .ob-req-list {
        list-style: none;
        padding: 0;
        margin: 0 0 2rem 0;
    }

    .ob-req-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .ob-req-icon {
        color: #10b981;
        /* Verde éxito */
        font-size: 1.1rem;
        margin-top: 0.1rem;
    }

    .ob-req-title {
        font-weight: 500;
        color: #1e293b;
        font-size: 0.95rem;
        margin-bottom: 0.2rem;
    }

    .ob-req-desc {
        color: #94a3b8;
        font-size: 0.8rem;
        line-height: 1.4;
    }

    .ob-collapse-btn {
        color: #3b82f6;
        background: none;
        border: none;
        padding: 0;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .btn-primary-custom {
        background: #334155;
        color: white;
        border: none;
        padding: 0.6rem 1.5rem;
        border-radius: 6px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: background 0.2s;
    }

    .btn-primary-custom:hover {
        background: #1e293b;
        color: white;
    }

    /* Modal Activación */
    .modal-fc-header {
        border-bottom: none;
        padding: 1.5rem 1.5rem 0.5rem 1.5rem;
    }

    .modal-fc-title {
        font-weight: 600;
        font-size: 1.1rem;
        color: #1e293b;
    }

    .modal-fc-subtitle {
        font-size: 0.85rem;
        color: #64748b;
    }

    .modal-fc-body {
        padding: 1.5rem;
    }

    .fc-form-label {
        font-weight: 500;
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 0.4rem;
    }

    .fc-form-control {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.9rem;
        color: #1e293b;
    }

    .fc-form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .fc-input-hint {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0.4rem;
    }

    .fc-summary-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }

    .fc-summary-title {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .fc-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .fc-summary-val {
        color: #1e293b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .fc-summary-val .bi-check-circle {
        color: #10b981;
    }

    .fc-info-alert {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        padding: 1rem;
        color: #1e40af;
        font-size: 0.8rem;
        display: flex;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .modal-fc-footer {
        border-top: none;
        padding: 0 1.5rem 1.5rem 1.5rem;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    /* Dashboard Métrica Cards (Fase 2) */
    .metric-card {
        position: relative;
        padding: 1.25rem 1.5rem;
    }

    .metric-title {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .metric-badge {
        position: absolute;
        top: 1.25rem;
        right: 1.5rem;
        font-size: 0.65rem;
        background: #f1f5f9;
        color: #64748b;
        padding: 0.2rem 0.5rem;
        border-radius: 4px;
        font-weight: 600;
    }

    .metric-badge-sub {
        position: absolute;
        top: 2.75rem;
        right: 1.5rem;
        font-size: 0.65rem;
        color: #cbd5e1;
    }

    .metric-val {
        font-size: 1.75rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .metric-val.income {
        color: #10b981;
    }

    .metric-val.expense {
        color: #1e293b;
    }

    .metric-val.overdue {
        color: #eab308;
    }

    /* Dashboard Widgets Inferiores */
    .widget-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
        display: block;
    }

    .widget-subtitle {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 0;
        margin-bottom: 1rem;
        display: block;
    }

    .placeholder-circle {
        width: 140px;
        height: 140px;
        border: 2px dashed #cbd5e1;
        border-radius: 50%;
        margin: 2rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 1rem;
    }

    .placeholder-circle span {
        font-size: 0.75rem;
        color: #94a3b8;
        line-height: 1.3;
    }

    /* Cobranza por Unidad List */
    .collection-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .collection-progress {
        height: 4px;
        background: #f1f5f9;
        border-radius: 2px;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .collection-progress-bar {
        height: 100%;
        background: #10b981;
        /* Default to full */
        width: 0%;
        transition: width 1s ease;
    }

    .unit-list {
        max-height: 200px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    /* Scrollbar */
    .unit-list::-webkit-scrollbar {
        width: 4px;
    }

    .unit-list::-webkit-scrollbar-track {
        background: transparent;
    }

    .unit-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 2px;
    }

    .unit-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .unit-item:last-child {
        border-bottom: none;
    }

    .unit-item-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.85rem;
        color: #475569;
        font-weight: 500;
    }

    .status-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-dot.ok {
        background: #10b981;
    }

    .status-dot.info {
        background: #0284c7;
    }

    .status-dot.debt {
        background: #ef4444;
    }

    .unit-item-amount {
        font-size: 0.8rem;
        font-weight: 600;
    }

    .unit-item-amount.debt {
        color: #ef4444;
    }

    .unit-item-amount.info {
        color: #0284c7;
    }

    .unit-item-amount.ok {
        color: #94a3b8;
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
</style>

<!-- ── Hero ── -->
<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">Finanzas</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-credit-card"></i>
            <i class="bi bi-chevron-right"></i>
            <?php if (!$is_billing_active): ?>
                Seguimiento de pagos y gestión financiera -
                <?= strtr(date('F Y', strtotime($selectedMonth . '-01')), $mesesES) ?>
            <?php else: ?>
                Seguimiento de pagos y gestión financiera —
                <?= strtr(date('F Y', strtotime($selectedMonth . '-01')), $mesesES) ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="cc-hero-right">
        <!-- Panel Actions -->
        <?php if ($is_billing_active): ?>
            <div>
                <div class="d-flex gap-2 align-items-center" style="position:relative;">
                    <!-- Prev Month -->
                    <a href="?month=<?= $prevMonth ?>" class="btn btn-sm btn-light bg-white border" title="Mes anterior">
                        <i class="bi bi-chevron-left text-muted"></i>
                    </a>

                    <!-- Month trigger -->
                    <div style="position:relative;">
                        <button id="monthPickerTrigger"
                            class="btn btn-sm bg-white border text-secondary d-flex align-items-center gap-2"
                            style="font-size:.85rem; font-weight:500; min-width:140px; justify-content:center;">
                            <i class="bi bi-calendar3"></i>
                            <span
                                id="monthPickerLabel"><?= strtr(date('F Y', strtotime($selectedMonth . '-01')), $mesesES) ?></span>
                            <i class="bi bi-chevron-down" style="font-size:.65rem; opacity:.6;"></i>
                        </button>
                        <!-- Invisible anchor for flatpickr -->
                        <input type="text" id="monthPickerInput"
                            style="position:absolute;top:100%;left:0;opacity:0;pointer-events:none;width:1px;height:1px;">
                    </div>

                    <!-- Next Month -->
                    <a href="?month=<?= $nextMonth ?>"
                        class="btn btn-sm btn-light bg-white border <?= $selectedMonth >= date('Y-m') ? 'disabled' : '' ?>"
                        title="Mes siguiente">
                        <i class="bi bi-chevron-right text-muted"></i>
                    </a>

                    <a href="?month=<?= date('Y-m') ?>" class="btn btn-sm btn-light bg-white border ms-1" title="Mes actual"
                        style="font-size:.75rem;">Hoy</a>


                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ========================================================= -->
<!-- FASE 1: ONBOARDING (Si el sistema no está activado)       -->
<!-- ========================================================= -->
<?php if (!$is_billing_active): ?>

    <div class="fc-card ob-container text-center">
        <div class="d-flex justify-content-center">
            <div class="ob-title-box">
                <i class="bi bi-gear-fill ob-title-icon"></i>
                Configuración del Sistema de Facturación Requerida
            </div>
        </div>
        <p class="ob-subtitle">Configure la facturación para rastrear automáticamente los pagos mensuales. Esta es una
            configuración que se realiza una sola vez.</p>

        <div class="ob-progress-container">
            <div class="ob-progress-bar-bg">
                <div class="ob-progress-bar-fill"></div>
            </div>
            <span class="ob-progress-text">100%</span>
        </div>

        <div class="text-start">
            <ul class="ob-req-list">
                <li class="ob-req-item">
                    <i class="bi bi-check-circle ob-req-icon"></i>
                    <div>
                        <div class="ob-req-title">Todas las unidades deben tener cuotas asignadas</div>
                        <div class="ob-req-desc">El sistema genera cargos mensuales basados en la cuota de cada unidad.
                        </div>
                    </div>
                </li>
                <li class="ob-req-item">
                    <i class="bi bi-check-circle ob-req-icon"></i>
                    <div>
                        <div class="ob-req-title">Se debe establecer la fecha de inicio de facturación</div>
                        <div class="ob-req-desc">El mes desde el cual el sistema rastrea y genera cargos.</div>
                    </div>
                </li>
                <li class="ob-req-item">
                    <i class="bi bi-check-circle ob-req-icon"></i>
                    <div>
                        <div class="ob-req-title">Se debe establecer la fecha de vencimiento de facturación</div>
                        <div class="ob-req-desc">El día de cada mes en que los residentes deben pagar.</div>
                    </div>
                </li>
            </ul>

            <button class="ob-collapse-btn"><i class="bi bi-chevron-right"></i> ¿Qué sucede después de activar?</button>
            <button class="ob-collapse-btn"><i class="bi bi-lightbulb"></i> Sobre la fecha de inicio</button>
        </div>

        <div class="text-end mt-4">
            <button type="button" class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#activateBillingModal">
                Activar Sistema de Facturación
            </button>
        </div>
    </div>

    <!-- Modal de Activación -->
    <div class="modal fade" id="activateBillingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 12px; border:none; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
                <div class="modal-header modal-fc-header">
                    <div>
                        <h5 class="modal-title modal-fc-title">Configuración del Sistema de Facturación</h5>
                        <p class="modal-fc-subtitle mb-0">Configure los parámetros y active el sistema. Esta acción se
                            realiza una sola vez.</p>
                    </div>
                    <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="activateBillingForm">
                    <div class="modal-body modal-fc-body">

                        <div class="mb-4">
                            <label class="fc-form-label">Fecha de Inicio de Facturación</label>
                            <div class="billing-date-wrapper">
                                <div class="billing-date-trigger" id="billingDateTrigger">
                                    <i class="bi bi-calendar3"></i>
                                    <span id="billingDateLabel">Fecha de Inicio de Facturación</span>
                                </div>
                                <input type="hidden" id="billingStartDate" required>
                            </div>
                            <div class="fc-input-hint">Seleccione la fecha en que comenzará la facturación de cuotas</div>
                        </div>

                        <div>
                            <label class="fc-form-label">Fecha de Vencimiento</label>
                            <select id="billingDueDay" class="form-select fc-form-control" required>
                                <option value="" disabled selected>Selecciona el día</option>
                                <?php for ($i = 1; $i <= 28; $i++): ?>
                                    <option value="<?= $i ?>">Día <?= $i ?> de cada mes</option>
                                <?php endfor; ?>
                            </select>
                            <div class="fc-input-hint">Seleccione el día del mes en que vencerán los pagos</div>
                        </div>

                        <!-- Summary Box -->
                        <div class="fc-summary-box">
                            <div class="fc-summary-title">Resumen de activación</div>
                            <div class="fc-summary-row">
                                <span>Todas las unidades deben tener cuotas asignadas</span>
                                <span
                                    class="fc-summary-val"><?= $stats_onboarding['units_with_fee'] ?>/<?= $stats_onboarding['total_units'] ?>
                                    <i class="bi bi-check-circle"></i></span>
                            </div>
                            <div class="fc-summary-row">
                                <span>Fecha de Inicio de Facturación</span>
                                <span class="fc-summary-val" id="summaryStartDate">-- <i
                                        class="bi bi-check-circle text-muted" id="iconStart"></i></span>
                            </div>
                            <div class="fc-summary-row">
                                <span>Fecha de Vencimiento</span>
                                <span class="fc-summary-val" id="summaryDueDate">-- <i class="bi bi-check-circle text-muted"
                                        id="iconDue"></i></span>
                            </div>
                        </div>

                        <!-- Info Alert -->
                        <div class="fc-info-alert">
                            <i class="bi bi-info-circle" style="font-size: 1.1rem;"></i>
                            <div>Una vez activado, el sistema de facturación generará períodos de pago recurrentes basados
                                en esta configuración.</div>
                        </div>

                    </div>

                    <div class="modal-footer modal-fc-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="font-weight: 500;">Cancelar</button>
                        <button type="submit" class="btn btn-primary-custom" id="btnActivateSubmit" disabled>Activar Sistema
                            de Facturación</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- FASE 2: DASHBOARD FINANCIERO                              -->
    <!-- ========================================================= -->
<?php else: ?>

    <?php if ($isBeforeBilling ?? false): ?>
        <!-- ── Estado vacío: mes anterior al inicio de facturación ── -->
        <div class="fc-card" style="text-align:center; padding: 3rem 2rem;">
            <div
                style="width:64px; height:64px; border-radius:50%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; margin: 0 auto 1.25rem;">
                <i class="bi bi-calendar-x" style="font-size:1.75rem; color:#94a3b8;"></i>
            </div>
            <h5 style="font-weight:700; color:#1e293b; margin-bottom:.5rem;">Sin registros para este período</h5>
            <p style="color:#64748b; font-size:.9rem; max-width:420px; margin:0 auto 1.5rem;">
                El mes seleccionado
                (<strong><?= strtr(date('F Y', strtotime(($selectedMonth ?? date('Y-m')) . '-01')), $mesesES) ?></strong>)
                es anterior al inicio de facturación. Los datos están disponibles desde
                <strong><?= strtr(date('F Y', strtotime(($billingStartMonth ?? date('Y-m')) . '-01')), $mesesES) ?></strong>.
            </p>
            <a href="?month=<?= $billingStartMonth ?? date('Y-m') ?>" class="btn btn-sm btn-dark">
                <i class="bi bi-arrow-right-circle me-1"></i> Ir al primer mes con datos
            </a>
        </div>

    <?php elseif ($isFutureMonth ?? false): ?>
        <!-- ── Mes futuro: mostrar widgets en cero ── -->
        <div class="fc-info-alert mb-4" style="border-radius:10px;">
            <i class="bi bi-clock-history" style="font-size:1.1rem;"></i>
            <div>Este mes aún no ha llegado. Se mostrarán los datos en cuanto estén disponibles.</div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="fc-card metric-card">
                    <div class="metric-title">Ingresos Totales</div>
                    <div class="metric-val income">MX$0.00</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fc-card metric-card">
                    <div class="metric-title">Gastos Totales</div>
                    <div class="metric-val expense">MX$0.00</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="fc-card metric-card">
                    <div class="metric-title">Monto Vencido</div>
                    <div class="metric-val overdue">MX$0.00</div>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- ── Dashboard completo ── -->
        <div class="row g-4 mb-4">
            <!-- Ingresos Totales -->
            <div class="col-md-4">
                <div class="fc-card metric-card">
                    <div class="metric-title">Ingresos Totales</div>
                    <div class="metric-badge">— Nuevo</div>
                    <div class="metric-badge-sub">vs mes anterior</div>
                    <div class="metric-val income">MX$<?= number_format($kpis['ingresos'], 2) ?></div>
                </div>
            </div>

            <!-- Gastos Totales -->
            <div class="col-md-4">
                <div class="fc-card metric-card">
                    <div class="metric-title">Gastos Totales</div>
                    <div class="metric-badge">— Nuevo</div>
                    <div class="metric-badge-sub">vs mes anterior</div>
                    <div class="metric-val expense">MX$<?= number_format($kpis['gastos'], 2) ?></div>
                </div>
            </div>

            <!-- Monto Vencido -->
            <div class="col-md-4">
                <div class="fc-card metric-card">
                    <div class="metric-title">Monto Vencido</div>
                    <div class="metric-badge">— Nuevo</div>
                    <div class="metric-badge-sub">vs mes anterior</div>
                    <div class="metric-val overdue">MX$<?= number_format($kpis['vencido'], 2) ?></div>
                </div>
            </div>
        </div>

        <!-- Gráfica de Tendencias -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="fc-card">
                    <div style="display:flex; flex-direction:column; gap:0.15rem; margin-bottom:1rem;">
                        <div class="widget-title" style="margin-bottom:0;">Tendencias Mensuales</div>
                        <span class="widget-subtitle">Ingresos, gastos y vencidos — últimos 6 meses</span>
                    </div>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="monthlyTrendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3 Columnas Inferiores -->
        <div class="row g-4">
            <!-- Ingresos por Categoría -->
            <div class="col-md-4">
                <div class="fc-card">
                    <div style="display:flex;flex-direction:column;gap:.1rem;margin-bottom:.75rem;">
                        <div class="widget-title" style="margin-bottom:0;">Ingresos por Categoría</div>
                        <span
                            class="widget-subtitle"><?= strtr(date('F Y', strtotime($selectedMonth . '-01')), $mesesES) ?></span>
                    </div>
                    <?php if (!empty($income_by_cat)): ?>
                        <div style="position:relative;height:160px;margin-bottom:.75rem;">
                            <canvas id="incomeCatChart"></canvas>
                        </div>
                        <ul style="list-style:none;padding:0;margin:0;font-size:.78rem;">
                            <?php foreach ($income_by_cat as $ci => $cat): ?>
                                <li
                                    style="display:flex;justify-content:space-between;align-items:center;padding:.3rem 0;border-bottom:1px solid #f8fafc;">
                                    <span style="display:flex;align-items:center;gap:.5rem;">
                                        <span
                                            style="width:9px;height:9px;border-radius:50%;background:var(--cat-color-<?= $ci ?>);display:inline-block;"></span>
                                        <?= esc($cat['name']) ?>
                                    </span>
                                    <strong>MX$<?= number_format((float) $cat['total'], 2) ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="placeholder-circle"><span>Sin ingresos este mes</span></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Gastos por Categoría -->
            <div class="col-md-4">
                <div class="fc-card">
                    <div style="display:flex;flex-direction:column;gap:.1rem;margin-bottom:.75rem;">
                        <div class="widget-title" style="margin-bottom:0;">Gastos por Categoría</div>
                        <span
                            class="widget-subtitle"><?= strtr(date('F Y', strtotime($selectedMonth . '-01')), $mesesES) ?></span>
                    </div>
                    <?php if (!empty($expense_by_cat)): ?>
                        <div style="position:relative;height:160px;margin-bottom:.75rem;">
                            <canvas id="expenseCatChart"></canvas>
                        </div>
                        <ul style="list-style:none;padding:0;margin:0;font-size:.78rem;">
                            <?php foreach ($expense_by_cat as $ci => $cat): ?>
                                <li
                                    style="display:flex;justify-content:space-between;align-items:center;padding:.3rem 0;border-bottom:1px solid #f8fafc;">
                                    <span style="display:flex;align-items:center;gap:.5rem;">
                                        <span
                                            style="width:9px;height:9px;border-radius:50%;background:var(--cat-color-<?= $ci ?>);display:inline-block;"></span>
                                        <?= esc($cat['name']) ?>
                                    </span>
                                    <strong>MX$<?= number_format((float) $cat['total'], 2) ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div class="placeholder-circle"><span>Sin gastos este mes</span></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Cobranza por Unidad -->
            <div class="col-md-4">
                <div class="fc-card" style="display:flex; flex-direction:column;">
                    <div class="widget-title">Cobranza por Unidad</div>

                    <div class="collection-stats">
                        <span id="collectionRatioText">0 / 20 al día</span>
                        <span>0%</span>
                    </div>
                    <div class="collection-progress">
                        <div class="collection-progress-bar" id="collectionProgressBar"></div>
                    </div>

                    <!-- Lista real iterando de BD -->
                    <div class="unit-list">
                        <?php
                        $unitsOk = 0;
                        $totalReal = 0;
                        if (isset($unit_debts) && is_array($unit_debts)):
                            $totalReal = count($unit_debts);
                            foreach ($unit_debts as $u) {
                                if ($u['debt'] <= 0)
                                    $unitsOk++;
                            }
                        endif;
                        ?>

                        <?php if (isset($unit_debts) && is_array($unit_debts)): ?>
                            <?php foreach ($unit_debts as $u): ?>
                                <?php
                                $statusClass = 'ok';
                                $statusAmountClass = 'ok';
                                $statusText = 'Al día';

                                if (isset($u['debt_vencida']) && $u['debt_vencida'] > 0.01) {
                                    $statusClass = 'debt';
                                    $statusAmountClass = 'debt';
                                    $statusText = 'MX$' . number_format($u['debt'], 2);
                                } elseif ($u['debt'] > 0.01) {
                                    $statusClass = 'info';
                                    $statusAmountClass = 'info';
                                    $statusText = 'MX$' . number_format($u['debt'], 2);
                                }
                                ?>
                                <div class="unit-item">
                                    <div class="unit-item-info">
                                        <i class="status-dot <?= $statusClass ?>"></i>
                                        Unidad <?= esc($u['label']) ?>
                                    </div>
                                    <div class="unit-item-amount <?= $statusAmountClass ?>">
                                        <?= $statusText ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-muted" style="font-size: 0.8rem; margin-top:2rem;">No hay unidades
                                registradas.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; // end isBeforeBilling / isFutureMonth / full dashboard ?>
<?php endif; // end is_billing_active else ?>


<!-- ========================================================= -->
<!-- JAVASCRIPT LOGIC                                          -->
<!-- ========================================================= -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        <?php if (!$is_billing_active): ?>
            /* LÓGICA ONBOARDING - FASE 1 */

            const inputStart = document.getElementById('billingStartDate');
            const inputDue = document.getElementById('billingDueDay');
            const sumStart = document.getElementById('summaryStartDate');
            const sumDue = document.getElementById('summaryDueDate');
            const iconStart = document.getElementById('iconStart');
            const iconDue = document.getElementById('iconDue');
            const btnSubmit = document.getElementById('btnActivateSubmit');
            const form = document.getElementById('activateBillingForm');
            const dateLabel = document.getElementById('billingDateLabel');
            const dateTrigger = document.getElementById('billingDateTrigger');

            // Initialize Flatpickr as popup calendar on trigger click
            const billingCalendar = flatpickr(dateTrigger, {
                locale: 'es',
                dateFormat: 'Y-m-d',
                defaultDate: null,
                static: true,
                onChange: function (selectedDates, dateStr) {
                    inputStart.value = dateStr;
                    const dateObj = selectedDates[0];
                    const monthNames = ["enero", "febrero", "marzo", "abril", "mayo", "junio", "julio", "agosto", "septiembre", "octubre", "noviembre", "diciembre"];
                    const formatted = `${dateObj.getDate()} de ${monthNames[dateObj.getMonth()]} de ${dateObj.getFullYear()}`;
                    dateLabel.textContent = formatted;
                    dateTrigger.classList.add('has-value');
                    updateSummary();
                }
            });

            function updateSummary() {
                let isValid = true;

                // Start Date
                if (inputStart.value) {
                    const parts = inputStart.value.split('-');
                    const dd = parseInt(parts[2]);
                    const mm = parseInt(parts[1]);
                    const yyyy = parts[0];
                    const monthNames = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"];
                    sumStart.innerHTML = `${String(dd).padStart(2, '0')} ${monthNames[mm - 1]} ${yyyy} <i class="bi bi-check-circle text-success shadow-none"></i>`;
                } else {
                    sumStart.innerHTML = `-- <i class="bi bi-check-circle text-muted"></i>`;
                    isValid = false;
                }

                // Due Date
                if (inputDue.value) {
                    sumDue.innerHTML = `Día ${inputDue.value} de cada mes <i class="bi bi-check-circle text-success shadow-none"></i>`;
                } else {
                    sumDue.innerHTML = `-- <i class="bi bi-check-circle text-muted"></i>`;
                    isValid = false;
                }

                btnSubmit.disabled = !isValid;
            }

            if (inputDue) inputDue.addEventListener('change', updateSummary);

            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const formData = new FormData();
                    formData.append('start_date', inputStart.value);
                    formData.append('due_day', inputDue.value);
                    // Si tienes protección CSRF activada, añade aquí el token:
                    // formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

                    Swal.fire({
                        title: 'Activando...',
                        text: 'Configurando el sistema de facturación automátizado.',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });

                    fetch('<?= base_url('admin/finanzas/activate-billing') ?>', {
                        method: 'POST',
                        body: formData
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Activado!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message || 'Error desconocido.', 'error');
                            }
                        })
                        .catch(err => {
                            Swal.fire('Error', 'Hubo un problema de conexión al servidor.', 'error');
                        });
                });
            }

        <?php else: ?>
            /* LÓGICA DASHBOARD - FASE 2 */

            // ── Month Picker ──
            const monthTrigger = document.getElementById('monthPickerTrigger');
            const monthInput = document.getElementById('monthPickerInput');

            if (monthTrigger && monthInput) {
                const picker = flatpickr(monthInput, {
                    locale: 'es',
                    plugins: [
                        new monthSelectPlugin({
                            shorthand: false,
                            dateFormat: 'Y-m',
                            altFormat: 'F Y',
                        })
                    ],
                    defaultDate: '<?= $selectedMonth ?>',
                    disableMobile: true,
                    onChange: function (selectedDates, dateStr) {
                        window.location.href = '?month=' + dateStr;
                    }
                });

                monthTrigger.addEventListener('click', function (e) {
                    e.preventDefault();
                    picker.open();
                });
            }

            // Paleta de colores compartida para los donuts de categoría
            const CAT_COLORS = ['#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#f97316', '#ec4899'];

            // Inyectar colores como variables CSS para el legend
            CAT_COLORS.forEach((c, i) => {
                document.documentElement.style.setProperty(`--cat-color-${i}`, c);
            });

            // 1. Tendencias Mensuales (datos reales del servidor)
            const ctx = document.getElementById('monthlyTrendsChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: <?= json_encode($trend_labels ?? []) ?>,
                        datasets: [
                            {
                                label: 'Ingresos',
                                data: <?= json_encode($trend_ingresos ?? []) ?>,
                                backgroundColor: '#10b981',
                                borderRadius: 4,
                                minBarLength: 5
                            },
                            {
                                label: 'Gastos',
                                data: <?= json_encode($trend_gastos ?? []) ?>,
                                backgroundColor: '#ef4444',
                                borderRadius: 4,
                                minBarLength: 5
                            },
                            {
                                label: 'Vencidos',
                                data: <?= json_encode($trend_vencidos ?? []) ?>,
                                backgroundColor: '#eab308',
                                borderRadius: 4,
                                minBarLength: 5
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => 'MX$' + v.toLocaleString('es-MX'),
                                    color: '#94a3b8', font: { size: 10 }
                                },
                                grid: { color: '#f8fafc' }
                            },
                            x: { ticks: { color: '#94a3b8', font: { size: 11 } }, grid: { display: false } }
                        },
                        plugins: {
                            legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, padding: 20, font: { size: 11 } } },
                            tooltip: {
                                callbacks: { label: c => ' MX$' + c.parsed.y.toLocaleString('es-MX', { minimumFractionDigits: 2 }) }
                            }
                        }
                    }
                });
            }

            // 2. Donut: Ingresos por Categoría
            const incomeCtx = document.getElementById('incomeCatChart');
            if (incomeCtx) {
                const incomeData = <?= json_encode(array_values(array_map(fn($c) => (float) $c['total'], $income_by_cat ?? []))) ?>;
                const incomeLabels = <?= json_encode(array_values(array_map(fn($c) => $c['name'], $income_by_cat ?? []))) ?>;
                new Chart(incomeCtx, {
                    type: 'doughnut',
                    data: { labels: incomeLabels, datasets: [{ data: incomeData, backgroundColor: CAT_COLORS, borderWidth: 2, borderColor: '#fff' }] },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '68%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: { label: c => ` ${c.label}: MX$${c.parsed.toLocaleString('es-MX', { minimumFractionDigits: 2 })}` }
                            }
                        }
                    }
                });
            }

            // 3. Donut: Gastos por Categoría
            const expenseCtx = document.getElementById('expenseCatChart');
            if (expenseCtx) {
                const expenseData = <?= json_encode(array_values(array_map(fn($c) => (float) $c['total'], $expense_by_cat ?? []))) ?>;
                const expenseLabels = <?= json_encode(array_values(array_map(fn($c) => $c['name'], $expense_by_cat ?? []))) ?>;
                new Chart(expenseCtx, {
                    type: 'doughnut',
                    data: { labels: expenseLabels, datasets: [{ data: expenseData, backgroundColor: CAT_COLORS, borderWidth: 2, borderColor: '#fff' }] },
                    options: {
                        responsive: true, maintainAspectRatio: false, cutout: '68%',
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: { label: c => ` ${c.label}: MX$${c.parsed.toLocaleString('es-MX', { minimumFractionDigits: 2 })}` }
                            }
                        }
                    }
                });
            }

            // 2. Adjust progress bar for "Cobranza por Unidad"
            <?php if (isset($unitsOk) && isset($totalReal)): ?>
                const total = <?= $totalReal ?>;
                const ok = <?= $unitsOk ?>;
                const pct = total > 0 ? Math.round((ok / total) * 100) : 0;

                const progBar = document.getElementById('collectionProgressBar');
                const txtLabel = document.getElementById('collectionRatioText');

                if (progBar) {
                    setTimeout(() => { progBar.style.width = pct + '%'; }, 300);
                    if (pct < 30) progBar.style.backgroundColor = '#ef4444';
                    else if (pct < 70) progBar.style.backgroundColor = '#eab308';
                }

                if (txtLabel) {
                    txtLabel.innerHTML = `${ok} / ${total} al día`;
                    txtLabel.nextElementSibling.innerHTML = pct + '%'; // adjust the 0% label
                }
            <?php endif; ?>

        <?php endif; ?>
    });
</script>

<?= $this->endSection() ?>