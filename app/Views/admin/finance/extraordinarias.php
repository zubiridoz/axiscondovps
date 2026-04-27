<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    :root {
        --ext-bg: #EEF1F9;
        --ext-card: #ffffff;
        --ext-text: #1e293b;
        --ext-muted: #64748b;
        --ext-border: #e2e8f0;
        --ext-primary: #232d3f;
        --ext-success: #10b981;
        --ext-blue: #3b82f6;
        --ext-purple: #8b5cf6;
        --ext-radius: 10px;
        --ext-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --ext-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        --ext-modal-bg: rgba(15, 23, 42, 0.4);
    }

    body {
        margin: 0;
        background-color: var(--ext-bg);
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

    /* Controls */
    .ext-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .ext-btn-date {
        background: white;
        border: 1px solid var(--ext-border);
        color: var(--ext-text);
        padding: 0.5rem 1rem;
        border-radius: 6px;
        font-size: 0.85rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .ext-btn-date i {
        color: var(--ext-muted);
    }

    .ext-btn-date:hover {
        background: #f1f5f9;
    }

    .ext-btn-success {
        background: var(--ext-success);
        border: none;
        color: white;
        padding: 0.6rem 1.25rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background 0.2s;
    }

    .ext-btn-success:hover {
        background: #059669;
    }

    .ext-btn-success[disabled] {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .ext-btn-outline {
        background: transparent;
        border: 1px solid var(--ext-border);
        color: var(--ext-text);
        padding: 0.6rem 1.25rem;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .ext-btn-outline:hover {
        background: #f1f5f9;
    }

    /* KPIs */
    .ext-kpi-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .ext-kpi-card {
        background: var(--ext-card);
        border: 1px solid var(--ext-border);
        border-radius: var(--ext-radius);
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: var(--ext-shadow-sm);
    }

    .ext-kpi-title {
        color: var(--ext-muted);
        font-size: 0.8rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .ext-kpi-value {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--ext-text);
        line-height: 1;
    }

    .ext-kpi-icon {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .icon-green {
        background: #d1fae5;
        color: #10b981;
    }

    .icon-blue {
        background: #dbeafe;
        color: #3b82f6;
    }

    .icon-purple {
        background: #f3e8ff;
        color: #8b5cf6;
    }

    /* Section Label */
    .ext-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ext-text);
        margin-bottom: 1rem;
    }

    /* Empty State Container */
    .ext-empty-state {
        background: var(--ext-card);
        border: 1px solid var(--ext-border);
        border-radius: var(--ext-radius);
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: var(--ext-shadow-sm);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .ext-empty-icon-wrapper {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .ext-empty-icon-circle {
        width: 72px;
        height: 72px;
        background: #d1fae5;
        /* Green very light */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--ext-success);
    }

    .ext-empty-icon-badge {
        position: absolute;
        top: -2px;
        right: -6px;
        background: var(--ext-success);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        border: 2px solid white;
    }

    .ext-empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--ext-text);
        margin: 0 0 0.5rem 0;
    }

    .ext-empty-state>p {
        color: var(--ext-muted);
        font-size: 0.9rem;
        max-width: 450px;
        margin: 0 auto 1.5rem auto;
        line-height: 1.5;
    }

    .ext-empty-divider {
        width: 400px;
        max-width: 100%;
        height: 1px;
        background: var(--ext-border);
        border: none;
        margin: 2.5rem 0 1.5rem 0;
    }

    .ext-empty-hint {
        color: var(--ext-muted);
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }

    .ext-empty-list {
        list-style: none;
        padding: 0;
        margin: 0;
        text-align: left;
        display: inline-block;
    }

    .ext-empty-list li {
        color: var(--ext-text);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        position: relative;
        padding-left: 1.25rem;
    }

    .ext-empty-list li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 6px;
        width: 6px;
        height: 6px;
        background: var(--ext-success);
        border-radius: 50%;
    }

    /* Active Fee Card Styling */
    .fee-card {
        background: var(--ext-card);
        border: 1px solid var(--ext-border);
        border-radius: var(--ext-radius);
        padding: 1.5rem;
        margin-bottom: 1rem;
        box-shadow: var(--ext-shadow-sm);
    }

    .fee-card-clickable {
        transition: all 0.2s;
    }

    .fee-card-clickable:hover {
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.15);
        border-color: rgba(16, 185, 129, 0.5);
        transform: translateY(-2px);
    }

    .fee-card-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .fee-card-title {
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--ext-text);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .fee-badge {
        background: #f1f5f9;
        color: var(--ext-muted);
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 4px;
        font-weight: 500;
        text-transform: uppercase;
    }

    .fee-card-subtitle {
        color: var(--ext-muted);
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }

    .fee-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr) auto;
        gap: 1rem;
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        align-items: center;
    }

    .fee-stat-item {
        display: flex;
        flex-direction: column;
    }

    .fee-stat-label {
        font-size: 0.7rem;
        color: var(--ext-muted);
        margin-bottom: 0.25rem;
    }

    .fee-stat-val {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--ext-text);
    }

    .fee-stat-val.text-green {
        color: var(--ext-success);
    }

    .fee-progress-row {
        margin-bottom: 1rem;
    }

    .fee-progress-label {
        font-size: 0.75rem;
        color: var(--ext-muted);
        margin-bottom: 0.4rem;
    }

    .fee-progress-bar {
        height: 6px;
        background: var(--ext-border);
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 0.4rem;
    }

    .fee-progress-fill {
        height: 100%;
        background: var(--ext-success);
    }

    .fee-progress-footer {
        display: flex;
        justify-content: flex-end;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--ext-text);
    }

    .fee-card-footer {
        display: flex;
        gap: 1.5rem;
        font-size: 0.75rem;
        color: var(--ext-muted);
        border-top: 1px solid var(--ext-border);
        padding-top: 1rem;
        margin-top: 0.5rem;
    }


    /* MODAL WIZARD STYLES */
    .wiz-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: var(--ext-modal-bg);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .wiz-modal {
        background: white;
        width: 600px;
        max-width: 95vw;
        border-radius: var(--ext-radius);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    .wiz-header {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--ext-border);
    }

    .wiz-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--ext-text);
        margin: 0;
    }

    .wiz-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: var(--ext-muted);
        cursor: pointer;
    }

    .wiz-stepper {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem;
    }

    .wiz-step-dot {
        width: 40px;
        height: 6px;
        background: var(--ext-border);
        border-radius: 3px;
        transition: background 0.3s;
    }

    .wiz-step-dot.active {
        background: var(--ext-success);
    }

    .wiz-body {
        padding: 1.5rem;
        overflow-y: auto;
        flex-grow: 1;
    }

    .wiz-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--ext-border);
        display: flex;
        justify-content: space-between;
        background: #f8fafc;
        border-bottom-left-radius: var(--ext-radius);
        border-bottom-right-radius: var(--ext-radius);
    }

    .wiz-step-content {
        display: none;
    }

    .wiz-step-content.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        color: var(--ext-text);
        margin-bottom: 0.4rem;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1px solid var(--ext-border);
        border-radius: 6px;
        font-size: 0.9rem;
        color: var(--ext-text);
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--ext-success);
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    /* Step 2 List */
    .unit-list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .unit-list-count {
        font-size: 0.85rem;
        color: var(--ext-muted);
    }

    .unit-list-actions {
        display: flex;
        gap: 0.5rem;
    }

    .unit-list-container {
        border: 1px solid var(--ext-border);
        border-radius: 6px;
        max-height: 300px;
        overflow-y: auto;
    }

    .unit-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--ext-border);
    }

    .unit-list-item:last-child {
        border-bottom: none;
    }

    .unit-list-item label {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        flex-grow: 1;
        margin: 0;
        font-size: 0.9rem;
        color: var(--ext-text);
    }

    .unit-meta {
        font-size: 0.75rem;
        color: var(--ext-muted);
    }

    /* Step 3 Confirmation */
    .conf-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .conf-box-item {
        display: flex;
        flex-direction: column;
    }

    .conf-box-label {
        font-size: 0.8rem;
        color: var(--ext-muted);
        margin-bottom: 0.25rem;
    }

    .conf-box-val {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--ext-text);
    }

    .conf-box-val.total {
        font-size: 1.5rem;
        color: var(--ext-success);
        font-weight: 700;
    }

    .conf-details p {
        margin: 0 0 0.5rem 0;
        font-size: 0.9rem;
        color: var(--ext-text);
    }

    .conf-details strong {
        font-weight: 600;
    }

    .conf-terms {
        background: #fffbeb;
        border: 1px solid #fde68a;
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .conf-terms label {
        font-size: 0.85rem;
        color: var(--ext-text);
        margin: 0;
        cursor: pointer;
    }

    /* Step 4 Success */
    .wiz-success {
        text-align: center;
        padding: 2rem;
    }

    .wiz-success-icon {
        font-size: 4rem;
        color: var(--ext-success);
        margin-bottom: 1rem;
    }

    .wiz-success h3 {
        color: var(--ext-success);
        margin: 0 0 0.5rem 0;
    }

    .wiz-success p {
        color: var(--ext-muted);
        margin: 0;
    }

    @media (max-width: 900px) {
        .ext-kpi-row {
            grid-template-columns: 1fr;
        }

        .ext-controls {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .fee-stats-grid {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<div class="row">
    <div class="col-12">


        <!-- ── Hero ── -->
        <div class="cc-hero">
            <div class="cc-hero-left">
                <h2 class="cc-hero-title">Finanzas</h2>
                <div class="cc-hero-divider"></div>
                <div class="cc-hero-breadcrumb">
                    <i class="bi bi-credit-card"></i>
                    <i class="bi bi-chevron-right"></i>
                    <h2 class="cc-hero-title">Cuotas Extraordinarias</h2>
                    <i class="bi bi-chevron-right"></i>
                    Cuotas extraordinarias para proyectos especiales
                </div>
            </div>
            <div class="toolbar-right">
                <button class="ext-btn-date">
                    <i class="bi bi-calendar"></i> Últimos 6 meses
                </button>
                <button class="cc-hero-btn" onclick="openWizard()">
                    <i class="bi bi-plus"></i> Crear Cuota Extraordinaria
                </button>

            </div>
        </div>

        <!-- ── Hero fin── -->




        <!-- KPIs -->
        <div class="ext-kpi-row">
            <div class="ext-kpi-card">
                <div>
                    <div class="ext-kpi-title">Cuotas Activas</div>
                    <div class="ext-kpi-value"><?= esc($kpis['active'] ?? 0) ?></div>
                </div>
                <div class="ext-kpi-icon icon-green">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
            </div>

            <div class="ext-kpi-card">
                <div>
                    <div class="ext-kpi-title">Total Esperado</div>
                    <div class="ext-kpi-value">$<?= number_format($kpis['expected'] ?? 0, 2) ?></div>
                </div>
                <div class="ext-kpi-icon icon-blue">
                    <i class="bi bi-currency-dollar" style="font-weight:bold;font-style:normal;">$</i>
                </div>
            </div>

            <div class="ext-kpi-card">
                <div>
                    <div class="ext-kpi-title">Total Recaudado</div>
                    <div class="ext-kpi-value">$<?= number_format($kpis['collected'] ?? 0, 2) ?></div>
                </div>
                <div class="ext-kpi-icon icon-purple">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>

        <div class="ext-section-title">Todas las Cuotas</div>

        <?php if (empty($fees)): ?>
            <!-- Empty State -->
            <div class="ext-empty-state">
                <div class="ext-empty-icon-wrapper">
                    <div class="ext-empty-icon-circle">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div class="ext-empty-icon-badge">
                        <i class="bi bi-plus" style="margin-top: 1px;"></i>
                    </div>
                </div>

                <h3>Aún No Hay Cuotas Extraordinarias</h3>
                <p>Crea tu primera cuota extraordinaria para cobrar a las unidades por proyectos especiales</p>

                <button class="ext-btn-success" onclick="openWizard()">
                    <i class="bi bi-plus"></i> Crear Cuota Extraordinaria
                </button>

                <hr class="ext-empty-divider">

                <div class="ext-empty-hint">Las cuotas extraordinarias son ideales para:</div>
                <ul class="ext-empty-list">
                    <li>Mejoras de capital y renovaciones</li>
                    <li>Reparaciones de emergencia y mantenimiento</li>
                    <li>Proyectos especiales y evaluaciones</li>
                </ul>
            </div>
        <?php else: ?>
            <!-- Generated Fees List -->
            <?php foreach ($fees as $fee): ?>
                <a href="<?= base_url('admin/finanzas/extraordinarias/detalle/' . $fee['id']) ?>" style="text-decoration:none;">
                    <div class="fee-card fee-card-clickable">
                        <div class="fee-card-header">
                            <div class="fee-card-title">
                                <?= esc($fee['title']) ?>
                                <span class="fee-badge">Pendiente</span>
                            </div>
                        </div>
                        <?php if (!empty($fee['description'])): ?>
                            <div class="fee-card-subtitle"><?= esc($fee['description']) ?></div>
                        <?php endif; ?>

                        <div class="fee-stats-grid">
                            <div class="fee-stat-item">
                                <span class="fee-stat-label">Monto por Unidad</span>
                                <span class="fee-stat-val">$<?= number_format($fee['amount'], 2) ?></span>
                            </div>
                            <div class="fee-stat-item">
                                <span class="fee-stat-label">Unidades Cargadas</span>
                                <span class="fee-stat-val"><?= $fee['units_loaded'] ?></span>
                            </div>
                            <div class="fee-stat-item">
                                <span class="fee-stat-label">Unidades Pagadas</span>
                                <span class="fee-stat-val text-green"><?= $fee['units_paid'] ?> /
                                    <?= $fee['units_loaded'] ?></span>
                            </div>
                            <div class="fee-stat-item">
                                <span class="fee-stat-label">Tasa de Recaudación</span>
                                <span class="fee-stat-val"><?= number_format($fee['collection_rate'], 1) ?>%</span>
                            </div>
                            <div style="display:flex; justify-content:flex-end;">
                                <i class="bi bi-chevron-right" style="color:var(--ext-muted); cursor:pointer;"></i>
                            </div>
                        </div>

                        <div class="fee-progress-row">
                            <div class="fee-progress-label">Progreso</div>
                            <div class="fee-progress-bar">
                                <div class="fee-progress-fill" style="width: <?= $fee['collection_rate'] ?>%"></div>
                            </div>
                            <div class="fee-progress-footer">
                                $<?= number_format($fee['collected_amount'], 2) ?> /
                                $<?= number_format($fee['expected_total'], 2) ?>
                            </div>
                        </div>

                        <div class="fee-card-footer">
                            <span><i class="bi bi-calendar-check"></i> Creado
                                <?= date('M jS, Y', strtotime($fee['created_at'])) ?></span>
                            <?php if ($fee['due_date']): ?>
                                <span><i class="bi bi-clock"></i> Vencimiento:
                                    <?= date('M jS, Y', strtotime($fee['due_date'])) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<!-- CREAR CUOTA EXTRAORDINARIA WIZARD MODAL -->
<div class="wiz-overlay" id="extWizard">
    <div class="wiz-modal">
        <div class="wiz-header">
            <h2 class="wiz-title">Crear Cuota Extraordinaria</h2>
            <button class="wiz-close" onclick="closeWizard()">&times;</button>
        </div>

        <div class="wiz-stepper">
            <div class="wiz-step-dot active" id="dot1"></div>
            <div class="wiz-step-dot" id="dot2"></div>
            <div class="wiz-step-dot" id="dot3"></div>
            <div class="wiz-step-dot" id="dot4"></div>
        </div>

        <div class="wiz-body">
            <!-- Step 1: Form -->
            <div class="wiz-step-content active" id="step1">
                <div class="form-group">
                    <label class="form-label">Título de la Cuota *</label>
                    <input type="text" id="w_title" class="form-control" placeholder="Instalación de Paneles Solares">
                </div>
                <div class="form-group">
                    <label class="form-label">Descripción</label>
                    <textarea id="w_desc" class="form-control" placeholder="Instalacion de Paneles para Casa Club"
                        maxlength="300"></textarea>
                    <div style="text-align:right; font-size:0.75rem; color:var(--ext-muted);">max 300</div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Monto por Unidad *</label>
                        <div style="position:relative;">
                            <span style="position:absolute; left:0.8rem; top:0.6rem; color:var(--ext-text);">$</span>
                            <input type="number" id="w_amount" class="form-control" style="padding-left:1.8rem;"
                                placeholder="100">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Categoría</label>
                        <select id="w_cat" class="form-control">
                            <option value="">Seleccione...</option>
                            <option value="1">Mejora de Capital</option>
                            <option value="2">Reparación de Emergencia</option>
                            <option value="3">Honorarios Legales</option>
                            <option value="4">Seguro</option>
                            <option value="5">Proyecto Especial</option>
                            <option value="6">Otro</option>
                        </select>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Fecha Inicio *</label>
                        <input type="text" id="w_start" class="form-control bg-white" placeholder="Seleccionar Fecha">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha de Vencimiento (opcional)</label>
                        <input type="text" id="w_end" class="form-control bg-white" placeholder="Seleccionar Fecha">
                    </div>
                </div>
            </div>

            <!-- Step 2: Units Selection -->
            <div class="wiz-step-content" id="step2">
                <div class="unit-list-header">
                    <div class="unit-list-count"><span id="w_unit_count">0</span> unidades seleccionadas</div>
                    <div class="unit-list-actions">
                        <button class="ext-btn-outline" style="padding:0.4rem 0.8rem;"
                            onclick="selectAllUnits(true)">Seleccionar Todas</button>
                        <button class="ext-btn-outline" style="padding:0.4rem 0.8rem; border:none;"
                            onclick="selectAllUnits(false)">Limpiar Todo</button>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" id="w_search" class="form-control" placeholder="Buscar..."
                        onkeyup="filterUnits()">
                </div>
                <div class="unit-list-container" id="w_unit_list">
                    <?php if (isset($units)): ?>
                        <?php foreach ($units as $u): ?>
                            <div class="unit-list-item unit-row">
                                <label>
                                    <input type="checkbox" class="unit-chk" value="<?= $u['id'] ?>" checked
                                        onchange="updateUnitCount()">
                                    <?= esc($u['unit_number']) ?>
                                </label>
                                <div class="unit-meta">0 Residentes</div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Step 3: Confirmation -->
            <div class="wiz-step-content" id="step3">
                <div class="conf-box">
                    <div class="conf-box-item">
                        <span class="conf-box-label">Monto por Unidad</span>
                        <span class="conf-box-val" id="c_unit_amount">$0</span>
                    </div>
                    <div class="conf-box-item">
                        <span class="conf-box-label">Unidades Cargadas</span>
                        <span class="conf-box-val" id="c_units">0</span>
                    </div>
                </div>
                <div
                    style="background:#f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 1rem 1.5rem; margin-top:-1rem; margin-bottom:1.5rem; border-top:none; border-top-left-radius:0; border-top-right-radius:0;">
                    <span class="conf-box-label">Monto Total de la Cuota</span><br>
                    <span class="conf-box-val total" id="c_total">$0</span>
                </div>

                <div class="ext-section-title" style="font-size:1rem; margin-bottom:0.5rem;">Detalles</div>
                <div class="conf-details">
                    <p><strong>Título de la Cuota:</strong> <span id="dt_title"></span></p>
                    <p><strong>Descripción:</strong> <span id="dt_desc"></span></p>
                    <p><strong>Categoría:</strong> <span id="dt_cat"></span></p>
                    <p><strong>Fecha Inicio:</strong> <span id="dt_start"></span></p>
                    <p><strong>Fecha de Vencimiento:</strong> <span id="dt_end"></span></p>
                </div>

                <div class="conf-terms">
                    <input type="checkbox" id="w_confirm_chk" onchange="toggleCreateBtn()">
                    <label for="w_confirm_chk" id="dt_confirm_txt">Confirmo la aplicación de esta cuota...</label>
                </div>
            </div>

            <!-- Step 4: Success -->
            <div class="wiz-step-content" id="step4">
                <div class="wiz-success">
                    <i class="bi bi-check-circle wiz-success-icon"></i>
                    <h3>Cuota Extraordinaria Aplicada</h3>
                    <p id="succ_msg">Se crearon exitosamente cuotas para X unidades.</p>
                </div>
            </div>
        </div>

        <div class="wiz-footer">
            <button class="ext-btn-outline" id="btnBack" onclick="prevStep()">Atrás</button>
            <button class="ext-btn-success" id="btnNext" onclick="nextStep()">Siguiente ></button>
            <button class="ext-btn-success" id="btnCreate" onclick="submitFee()" style="display:none;" disabled>Crear
                Cuota</button>
            <button class="ext-btn-success" id="btnDone" onclick="window.location.reload()"
                style="display:none;">Hecho</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
    let currentStep = 1;

    // Date defaults
    document.addEventListener('DOMContentLoaded', () => {
        try {
            if (typeof flatpickr !== 'undefined') {
                flatpickr("#w_start", {
                    locale: "es",
                    altInput: true,
                    altFormat: "j \\d\\e F \\d\\e Y",
                    dateFormat: "Y-m-d",
                    defaultDate: "today",
                    disableMobile: true
                });
                flatpickr("#w_end", {
                    locale: "es",
                    altInput: true,
                    altFormat: "j \\d\\e F \\d\\e Y",
                    dateFormat: "Y-m-d",
                    disableMobile: true
                });
            }
        } catch (err) {
            console.error("Flatpickr Error", err);
        }
        updateUnitCount();
    });

    function openWizard() {
        document.getElementById('extWizard').style.display = 'flex';
        currentStep = 1;
        showStep(1);
    }

    function closeWizard() {
        document.getElementById('extWizard').style.display = 'none';
    }

    function showStep(s) {
        document.querySelectorAll('.wiz-step-content').forEach((el, index) => {
            el.classList.toggle('active', index + 1 === s);
        });
        document.querySelectorAll('.wiz-step-dot').forEach((el, index) => {
            el.classList.toggle('active', index + 1 <= s);
        });

        // Buttons logic
        document.getElementById('btnBack').disabled = (s === 1 || s === 4);
        document.getElementById('btnBack').style.opacity = (s === 1 || s === 4) ? '0.5' : '1';

        if (s === 1 || s === 2) {
            document.getElementById('btnNext').style.display = 'block';
            document.getElementById('btnCreate').style.display = 'none';
            document.getElementById('btnDone').style.display = 'none';
        } else if (s === 3) {
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('btnCreate').style.display = 'block';
            document.getElementById('btnDone').style.display = 'none';
            populateConfirmation();
        } else if (s === 4) {
            document.getElementById('btnNext').style.display = 'none';
            document.getElementById('btnCreate').style.display = 'none';
            document.getElementById('btnDone').style.display = 'block';
        }
    }

    function nextStep() {
        if (currentStep === 1) {
            // Validation basic
            const title = document.getElementById('w_title').value.trim();
            const amt = document.getElementById('w_amount').value;
            if (!title || !amt || isNaN(amt) || amt <= 0) {
                alert("Por favor ingrese el título y un monto válido.");
                return;
            }
        } else if (currentStep === 2) {
            let selected = document.querySelectorAll('.unit-chk:checked').length;
            if (selected === 0) {
                alert("Seleccione al menos una unidad.");
                return;
            }
        }
        currentStep++;
        showStep(currentStep);
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            showStep(currentStep);
        }
    }

    function updateUnitCount() {
        let selected = document.querySelectorAll('.unit-chk:checked').length;
        let total = document.querySelectorAll('.unit-chk').length;
        document.getElementById('w_unit_count').innerText = selected + ' de ' + total;
    }

    function filterUnits() {
        let s = document.getElementById('w_search').value.toLowerCase();
        document.querySelectorAll('.unit-row').forEach(r => {
            let t = r.innerText.toLowerCase();
            if (t.includes(s)) r.style.display = 'flex';
            else r.style.display = 'none';
        });
    }

    function selectAllUnits(checked) {
        document.querySelectorAll('.unit-chk').forEach(c => {
            // only select visible ones during search? let's do all for simplicity
            c.checked = checked;
        });
        updateUnitCount();
    }

    function populateConfirmation() {
        const amt = parseFloat(document.getElementById('w_amount').value) || 0;
        const unitsCount = document.querySelectorAll('.unit-chk:checked').length;
        const total = amt * unitsCount;

        document.getElementById('c_unit_amount').innerText = `$${amt.toFixed(2)}`;
        document.getElementById('c_units').innerText = unitsCount;
        document.getElementById('c_total').innerText = `$${total.toFixed(2)}`;

        document.getElementById('dt_title').innerText = document.getElementById('w_title').value;
        document.getElementById('dt_desc').innerText = document.getElementById('w_desc').value;

        const catEl = document.getElementById('w_cat');
        document.getElementById('dt_cat').innerText = catEl.options[catEl.selectedIndex].text;

        document.getElementById('dt_start').innerText = document.getElementById('w_start').value;
        document.getElementById('dt_end').innerText = document.getElementById('w_end').value || 'Sin vencimiento';

        document.getElementById('dt_confirm_txt').innerText = `Confirmo la aplicación de esta cuota extraordinaria de $${amt.toFixed(2)} a ${unitsCount} unidades, totalizando $${total.toFixed(2)}`;

        toggleCreateBtn();
    }

    function toggleCreateBtn() {
        document.getElementById('btnCreate').disabled = !document.getElementById('w_confirm_chk').checked;
    }

    function submitFee() {
        const btn = document.getElementById('btnCreate');
        btn.disabled = true;
        btn.innerText = "Creando...";

        let unitIds = [];
        document.querySelectorAll('.unit-chk:checked').forEach(c => {
            unitIds.push(c.value);
        });

        const payload = {
            title: document.getElementById('w_title').value,
            description: document.getElementById('w_desc').value,
            amount: parseFloat(document.getElementById('w_amount').value),
            category_id: document.getElementById('w_cat').value || null,
            start_date: document.getElementById('w_start').value,
            due_date: document.getElementById('w_end').value || null,
            unit_ids: unitIds
        };

        fetch('<?= base_url('admin/finanzas/extraordinarias/crear') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                "X-Requested-With": "XMLHttpRequest"
            },
            body: JSON.stringify(payload)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('succ_msg').innerText = `Se crearon exitosamente cuotas para ${data.units_loaded} unidades.`;
                    currentStep = 4;
                    showStep(4);
                } else {
                    alert("Error: " + data.message);
                    btn.disabled = false;
                    btn.innerText = "Crear Cuota";
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Ocurrió un error al procesar la solicitud.');
                btn.disabled = false;
                btn.innerText = "Crear Cuota";
            });
    }
</script>

<?= $this->endSection() ?>