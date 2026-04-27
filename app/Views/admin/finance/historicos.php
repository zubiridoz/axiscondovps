<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<style>
    /* Premium SaaS Base Variables */
    :root {
        --fin-bg: #EEF1F9;
        --fin-card-bg: #ffffff;
        --fin-text-main: #1e293b;
        --fin-text-muted: #64748b;
        --fin-border: #e2e8f0;
        --fin-primary: #232d3f;
        --fin-success: #10b981;
        --fin-success-hover: #059669;
        --fin-border-radius: 6px;
        --fin-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --fin-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        --ext-modal-bg: rgba(15, 23, 42, 0.4);
    }

    body {
        background-color: var(--fin-bg);
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

    /* Info Cards (3 columns) */
    .hist-info-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2.5rem;
    }

    .hist-info-card {
        background: var(--fin-card-bg);
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-border-radius);
        padding: 1.5rem;
        box-shadow: var(--fin-shadow-sm);
        display: flex;
        flex-direction: column;
    }

    .hist-info-title {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--fin-text-main);
        margin-bottom: 0.75rem;
    }

    .hist-info-title i {
        color: var(--fin-success);
        font-size: 1.1rem;
    }

    .hist-info-desc {
        font-size: 0.8rem;
        color: var(--fin-text-muted);
        line-height: 1.5;
    }

    /* Main Action Card */
    .hist-action-wrapper {
        display: flex;
        justify-content: center;
    }

    .hist-action-card {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
        border-radius: var(--fin-border-radius);
        padding: 2.5rem 3rem;
        text-align: center;
        max-width: 600px;
        box-shadow: var(--fin-shadow-sm);
    }

    .hist-action-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--fin-text-main);
        margin-bottom: 1rem;
    }

    .hist-action-desc {
        font-size: 0.85rem;
        color: #475569;
        margin-bottom: 2rem;
        line-height: 1.6;
    }

    .btn-action-start {
        background-color: var(--fin-success);
        color: white;
        border: none;
        padding: 0.85rem 2rem;
        border-radius: var(--fin-border-radius);
        font-weight: 500;
        font-size: 0.95rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.2s, transform 0.1s;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
    }

    .btn-action-start:hover {
        background-color: var(--fin-success-hover);
        color: white;
        text-decoration: none;
    }

    .btn-action-start:active {
        transform: translateY(1px);
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
        border-radius: 10px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        max-height: 90vh;
    }

    .wiz-modal.modal-lg {
        width: 950px;
    }

    .wiz-modal.modal-md {
        width: 550px;
    }

    .wiz-header {
        padding: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--fin-border);
    }

    .wiz-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--fin-text-main);
        margin: 0;
    }

    .wiz-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        color: var(--fin-text-muted);
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
        background: var(--fin-border);
        border-radius: 3px;
        transition: background 0.3s;
    }

    .wiz-step-dot.active {
        background: var(--fin-success);
    }

    .wiz-body {
        padding: 1.5rem;
        overflow-y: auto;
        flex-grow: 1;
    }

    .wiz-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--fin-border);
        display: flex;
        justify-content: space-between;
        background: #f8fafc;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
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

    /* Step 1 Elements */
    .wiz-desc {
        font-size: 0.85rem;
        color: var(--fin-text-muted);
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-label {
        display: block;
        font-size: 0.85rem;
        color: var(--fin-text-main);
        margin-bottom: 0.4rem;
        font-weight: 500;
    }

    .form-select-row {
        display: flex;
        gap: 1rem;
    }

    .form-control {
        width: 100%;
        padding: 0.6rem 0.8rem;
        border: 1px solid var(--fin-border);
        border-radius: 6px;
        font-size: 0.9rem;
        color: var(--fin-text-main);
        box-sizing: border-box;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--fin-success);
        box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.1);
    }

    .info-alert {
        background: #fffbeb;
        border: 1px solid #fde68a;
        padding: 1rem;
        border-radius: 6px;
        margin-top: 1.5rem;
        display: flex;
        gap: 0.75rem;
        color: #b45309;
        font-size: 0.85rem;
        line-height: 1.5;
    }

    .info-alert i {
        font-size: 1.1rem;
    }

    /* Step 2 Elements: Table Matrix */
    .matrix-container {
        overflow-x: auto;
        border: 1px solid var(--fin-border);
        border-radius: 6px;
        margin-top: 1rem;
        max-height: 400px;
    }

    .matrix-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
        white-space: nowrap;
    }

    .matrix-table th,
    .matrix-table td {
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid var(--fin-border);
        border-right: 1px solid var(--fin-border);
        text-align: center;
    }

    .matrix-table th {
        background: #f1f5f9;
        font-weight: 600;
        color: var(--fin-text-main);
        position: sticky;
        top: 0;
        z-index: 2;
    }

    .matrix-table td.col-unit {
        position: sticky;
        left: 0;
        background: white;
        z-index: 1;
        text-align: left;
        font-weight: 500;
        width: 120px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.02);
    }

    .matrix-table th.col-unit {
        position: sticky;
        left: 0;
        z-index: 3;
        text-align: left;
        width: 120px;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.02);
    }

    .matrix-table td.cell-fee {
        cursor: pointer;
        color: var(--fin-success);
        background: #f0fdf4;
        font-weight: 500;
        transition: all 0.1s;
    }

    .matrix-table td.cell-fee:hover {
        background: #dcfce7;
    }

    .matrix-table td.cell-fee.skipped {
        text-decoration: line-through;
        color: #94a3b8;
        background: #f8fafc;
    }

    .btn-edit-unit {
        color: var(--fin-text-muted);
        cursor: pointer;
        float: right;
        margin-top: 2px;
    }

    .btn-edit-unit:hover {
        color: var(--fin-primary);
    }

    .matrix-toolbar {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
        align-items: center;
    }

    .badge-year {
        background: transparent;
        border: 1px solid transparent;
        color: var(--fin-text-muted);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: 0.2s;
    }

    .badge-year.active {
        background: #dcfce7;
        color: var(--fin-success);
        font-weight: 600;
    }

    .badge-year:hover:not(.active) {
        background: #f1f5f9;
    }

    /* Step 3 Elements */
    .summary-box {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        padding: 1.5rem;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
    }

    .summary-label {
        font-size: 0.8rem;
        color: var(--fin-text-muted);
        margin-bottom: 0.25rem;
    }

    .summary-val {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--fin-text-main);
    }

    .summary-val.total {
        color: var(--fin-success);
    }

    .details-box {
        margin-bottom: 2rem;
        font-size: 0.85rem;
        color: var(--fin-text-main);
        line-height: 1.6;
    }

    .confirm-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        padding: 1rem;
        border-radius: 6px;
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }

    .confirm-box input {
        margin-top: 0.2rem;
    }

    .confirm-box label {
        font-size: 0.85rem;
        color: var(--fin-text-main);
        margin: 0;
        cursor: pointer;
        line-height: 1.4;
    }

    /* Step 4: Success */
    .wiz-success {
        text-align: center;
        padding: 3rem 2rem;
    }

    .wiz-success-icon {
        font-size: 4rem;
        color: var(--fin-success);
        margin-bottom: 1rem;
    }

    .wiz-success h3 {
        color: var(--fin-success);
        margin: 0 0 0.5rem 0;
        font-size: 1.5rem;
    }

    .wiz-success p {
        color: var(--fin-text-muted);
        margin: 0;
        font-size: 0.95rem;
    }

    /* Fee Config Modal */
    .fee-seg-row {
        display: flex;
        gap: 0.75rem;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .btn-trash {
        color: #ef4444;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0.4rem;
        border-radius: 4px;
    }

    .btn-trash:hover {
        background: #fee2e2;
    }

    .btn-outline-add {
        width: 100%;
        padding: 0.6rem;
        background: white;
        border: 1px dashed var(--fin-border);
        border-radius: 6px;
        color: var(--fin-text-main);
        font-size: 0.85rem;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 0.5rem;
        margin-bottom: 1.5rem;
    }

    .btn-outline-add:hover {
        border-color: var(--fin-success);
        color: var(--fin-success);
        background: #f0fdf4;
    }

    .fee-apply-all {
        background: white;
        border: 1px solid var(--fin-border);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: background 0.2s, border-color 0.2s;
    }

    .fee-apply-all.checked-state {
        background: #f0fdf4;
        border-color: #a7f3d0;
    }

    .custom-checkbox {
        appearance: none;
        -webkit-appearance: none;
        background-color: #fff;
        margin: 0;
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid #cbd5e1;
        border-radius: 6px;
        display: grid;
        place-content: center;
        cursor: pointer;
        transition: background-color 0.2s, border-color 0.2s;
        flex-shrink: 0;
    }

    .custom-checkbox::before {
        content: "";
        width: 0.85rem;
        height: 0.85rem;
        transform: scale(0);
        transition: 120ms transform ease-in-out;
        background-color: white;
        transform-origin: center;
        clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
    }

    .custom-checkbox:checked {
        background-color: #475569;
        border-color: #475569;
    }

    .custom-checkbox:checked::before {
        transform: scale(1);
    }

    .fee-apply-all-texts {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .fee-apply-all-texts label {
        font-size: 0.95rem;
        font-weight: 500;
        color: #065f46;
        margin-bottom: 0.1rem;
        cursor: pointer;
    }

    .fee-apply-all-texts p {
        font-size: 0.8rem;
        color: #047857;
        margin: 0;
    }

    .fee-preview {
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        border-radius: 6px;
        padding: 1rem;
        font-size: 0.85rem;
        color: #1e3a8a;
    }

    .fee-preview-title {
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    @media (max-width: 992px) {
        .hist-info-container {
            grid-template-columns: 1fr;
        }

        .summary-box {
            grid-template-columns: 1fr;
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
                    <h2 class="cc-hero-title">Registros Históricos</h2>
                    <i class="bi bi-chevron-right"></i>
                    Historial de Cuotas HOA
                </div>
            </div>
        </div>
        <!-- ── END Hero ── -->



        <!-- 3 Info Columns -->
        <div class="hist-info-container">
            <div class="hist-info-card">
                <div class="hist-info-title"><i class="bi bi-calendar3"></i> Seleccionar Rango de Fechas</div>
                <div class="hist-info-desc">Elija los meses de inicio y fin para los cuales desea crear cargos
                    históricos</div>
            </div>
            <div class="hist-info-card">
                <div class="hist-info-title"><i class="bi bi-currency-dollar text-success"></i> Configurar Historial de
                    Cuotas</div>
                <div class="hist-info-desc">Defina cambios de cuota a lo largo del tiempo para cada unidad. Las cuotas
                    se prellenan con la cuota HOA actual</div>
            </div>
            <div class="hist-info-card">
                <div class="hist-info-title"><i class="bi bi-arrow-clockwise"></i> Crear Periodos Automáticamente</div>
                <div class="hist-info-desc">Los periodos de facturación se crearán automáticamente para cada mes en el
                    rango seleccionado</div>
            </div>
        </div>

        <!-- Start Action Card -->
        <div class="hist-action-wrapper">
            <div class="hist-action-card">
                <h3 class="hist-action-title">Iniciar Proceso de Registro</h3>
                <p class="hist-action-desc">
                    Este asistente lo guiará para crear cargos HOA históricos para todas las unidades de su condominio.
                    Use esta función al configurar un nuevo condominio que necesita importar datos históricos.
                </p>
                <button class="btn-action-start" onclick="openWizard()">
                    <i class="bi bi-arrow-clockwise"></i> Iniciar Asistente de Registro
                </button>
            </div>
        </div>
    </div>
</div>

<!-- WIZARD MODAL -->
<div class="wiz-overlay" id="mainWizard">
    <div class="wiz-modal" id="wizardBox">
        <div class="wiz-header">
            <h2 class="wiz-title">Asistente de Registro Histórico de Cuotas de Mantenimiento</h2>
            <button class="wiz-close" onclick="closeWizard()">&times;</button>
        </div>

        <div class="wiz-stepper">
            <div class="wiz-step-dot active" id="dot1"></div>
            <div class="wiz-step-dot" id="dot2"></div>
            <div class="wiz-step-dot" id="dot3"></div>
            <div class="wiz-step-dot" id="dot4"></div>
        </div>

        <div class="wiz-body">
            <!-- STEP 1: Fechas -->
            <div class="wiz-step-content active" id="step1">
                <p class="wiz-desc">Seleccione el rango de fechas para el cual desea crear cargos HOA históricos. El mes
                    final suele ser el mes actual.</p>

                <div class="form-group">
                    <label class="form-label">Mes de Inicio</label>
                    <div class="form-select-row">
                        <select class="form-control" id="st_month">
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                        <select class="form-control" id="st_year"></select>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <label class="form-label">Mes de Fin</label>
                    <div class="form-select-row">
                        <select class="form-control" id="en_month">
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                        <select class="form-control" id="en_year"></select>
                    </div>
                </div>

                <div class="mt-3" style="font-size:0.85rem; color:var(--fin-text-muted);" id="date_preview_txt"></div>

                <div class="info-alert">
                    <i class="bi bi-info-circle"></i>
                    <div>Si las unidades tienen fechas de inicio diferentes, seleccione la más antigua. En el siguiente
                        paso podrá excluir los meses que no apliquen para cada unidad.</div>
                </div>
            </div>

            <!-- STEP 2: Matriz -->
            <div class="wiz-step-content" id="step2">
                <p class="wiz-desc">Revise y configure la cuota HOA para cada unidad. Haga clic en el icono de lápiz
                    para definir cambios de cuota a lo largo del tiempo.<br>
                    <span id="matrix_desc_txt">Mostrando cuotas para X meses. Diferentes colores indican diferentes
                        montos de cuota.</span>
                </p>

                <div class="matrix-toolbar" id="year_filters">
                    <button class="badge-year active" onclick="filterYear('all')">Todos los Años</button>
                    <!-- Years injected dynamically -->
                </div>

                <div class="matrix-container">
                    <table class="matrix-table" id="matrix_table">
                        <thead>
                            <tr id="matrix_head">
                                <th class="col-unit">Unidad</th>
                                <!-- Months injected dynamically -->
                            </tr>
                        </thead>
                        <tbody id="matrix_body">
                            <!-- Rows injected dynamically -->
                        </tbody>
                    </table>
                </div>

                <div style="font-size:0.75rem; color:var(--fin-text-muted); margin-top:0.75rem;">
                    Haga clic en el icono de lápiz junto al nombre de la unidad para editar su historial de cuotas. Haga
                    clic en una celda para excluirla/incluirla.
                </div>
            </div>

            <!-- STEP 3: Resumen -->
            <div class="wiz-step-content" id="step3">
                <div class="summary-box">
                    <div class="summary-item">
                        <span class="summary-label">Meses</span>
                        <span class="summary-val" id="sum_months">0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Cargos a Crear</span>
                        <span class="summary-val" id="sum_charges">0</span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Monto Total</span>
                        <span class="summary-val total" id="sum_total">$0.00</span>
                    </div>
                </div>

                <div class="details-box">
                    <strong>Detalles</strong><br><br>
                    Rango de Fechas: <span id="sum_range_txt">enero 2025 - febrero 2026</span><br>
                    Unidades: <span id="sum_units_txt">0</span>
                </div>

                <div class="confirm-box">
                    <input type="checkbox" id="chk_confirm" onchange="toggleCreateBtn()">
                    <label for="chk_confirm" id="lbl_confirm">Entiendo que esto creará 0 cargos de HOA por un total de
                        $0.00. Esta acción no se puede deshacer fácilmente.</label>
                </div>
            </div>

            <!-- STEP 4: Exito -->
            <div class="wiz-step-content" id="step4">
                <div class="wiz-success">
                    <i class="bi bi-check-circle wiz-success-icon"></i>
                    <h3>¡Registro Completado!</h3>
                    <p id="succ_msg">Se crearon exitosamente 14 periodos de facturación y 168 cargos de HOA.</p>
                </div>
            </div>
        </div>

        <div class="wiz-footer">
            <button class="btn btn-light" id="btnBack" onclick="prevStep()"
                style="border:1px solid var(--fin-border); color:var(--fin-text-main); font-weight:500;">Atrás</button>
            <div style="margin-left:auto;">
                <button class="btn btn-success" id="btnNext" onclick="nextStep()"
                    style="background-color:var(--fin-success); border:none; padding:0.6rem 1.5rem; font-weight:500;">Siguiente
                    <i class="bi bi-chevron-right" style="font-size:0.8rem;"></i></button>
                <button class="btn btn-success" id="btnCreate" onclick="submitHistoricos()"
                    style="background-color:var(--fin-success); border:none; padding:0.6rem 1.5rem; font-weight:500; display:none;"
                    disabled>Crear Cargos</button>
                <button class="btn btn-success" id="btnDone" onclick="window.location.reload()"
                    style="background-color:var(--fin-success); border:none; padding:0.6rem 1.5rem; font-weight:500; display:none;">Listo</button>
            </div>
        </div>
    </div>
</div>

<!-- SUB-MODAL: CONFIGURAR CUOTAS -->
<div class="wiz-overlay" id="feeModal" style="z-index: 1060;">
    <div class="wiz-modal modal-md">
        <div class="wiz-header">
            <h2 class="wiz-title" id="fee_modal_title">Configurar Calendario de Cuotas (Todas las Unidades)</h2>
            <button class="wiz-close" onclick="closeFeeModal()">&times;</button>
        </div>
        <div class="wiz-body">

            <div class="fee-apply-all" id="box_apply_all">
                <input type="checkbox" id="chk_apply_all" class="custom-checkbox" onchange="toggleApplyAll()">
                <div class="fee-apply-all-texts">
                    <label for="chk_apply_all">Aplicar a todas las unidades</label>
                    <p>Usar este mismo calendario de cuotas para cada unidad del condominio</p>
                </div>
            </div>

            <p class="wiz-desc" style="margin-bottom:1rem;">Defina cambios de cuota a lo largo del tiempo. <span
                    id="fee_modal_target_txt">Este calendario se aplicará a TODAS las unidades.</span></p>

            <div class="form-group" style="background:#f8fafc; padding:1rem; border-radius:6px; margin-bottom:1.5rem;">
                Cuota HOA Actual: <strong id="fee_base_amount">$0.00</strong>
            </div>

            <div style="font-weight:500; font-size:0.9rem; margin-bottom:0.75rem;">Segmentos de Cuota</div>
            <div id="fee_segments_container">
                <!-- Segments Dynamic -->
            </div>

            <button class="btn-outline-add" onclick="addFeeSegment()">
                <i class="bi bi-plus"></i> Agregar Cambio de Cuota
            </button>

            <div class="fee-preview">
                <div class="fee-preview-title">Vista Previa</div>
                <div id="fee_preview_list"></div>
            </div>

        </div>
        <div class="wiz-footer">
            <button class="btn btn-light" onclick="closeFeeModal()"
                style="border:1px solid var(--fin-border); font-weight:500;">Cancelar</button>
            <button class="btn btn-success" onclick="saveFeeConfig()"
                style="background-color:var(--fin-success); border:none; font-weight:500;">Guardar</button>
        </div>
    </div>
</div>

<script>
    const unitsData = <?= json_encode($units) ?>;
    const monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    const monthNamesShort = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];

    let currentStep = 1;
    let monthsList = []; // Array of { value: '2025-01', label: 'ene 25', year: 2025, monthIdx: 0 }
    let matrix = {}; // matrix[unit_id][month_val] = { amount: 5000, skip: false }
    let globalActiveYear = 'all';

    // State for Fee Modal
    let editingUnitId = null; // null = all units bulk edit
    let tempSegments = []; // [{ fromValue: '2025-01', amount: 5000 }]

    function initDates() {
        const d = new Date();
        const y = d.getFullYear();
        const m = d.getMonth() + 1;

        // Populate years 5 years back to 1 year ahead
        let yHtml = '';
        for (let i = y - 5; i <= y + 1; i++) {
            yHtml += `<option value="${i}">${i}</option>`;
        }
        document.getElementById('st_year').innerHTML = yHtml;
        document.getElementById('en_year').innerHTML = yHtml;

        // Default: Start 1 year ago, End current month
        document.getElementById('en_year').value = y;
        document.getElementById('en_month').value = m;

        let sy = y - 1; let sm = m;
        document.getElementById('st_year').value = sy;
        document.getElementById('st_month').value = sm;
        updateDatePreview();
    }

    function updateDatePreview() {
        let sm = parseInt(document.getElementById('st_month').value);
        let sy = parseInt(document.getElementById('st_year').value);
        let em = parseInt(document.getElementById('en_month').value);
        let ey = parseInt(document.getElementById('en_year').value);

        let startStr = '01 ' + monthNames[sm - 1] + ' ' + sy;
        let endStr = '01 ' + monthNames[em - 1] + ' ' + ey;
        document.getElementById('date_preview_txt').innerText = `Desde ${startStr} hasta ${endStr}`;
    }

    document.getElementById('st_month').addEventListener('change', updateDatePreview);
    document.getElementById('st_year').addEventListener('change', updateDatePreview);
    document.getElementById('en_month').addEventListener('change', updateDatePreview);
    document.getElementById('en_year').addEventListener('change', updateDatePreview);

    function openWizard() {
        initDates();
        currentStep = 1;
        document.getElementById('wizardBox').classList.remove('modal-lg');
        updateWizardUI();
        document.getElementById('mainWizard').style.display = 'flex';
    }
    function closeWizard() {
        document.getElementById('mainWizard').style.display = 'none';
    }

    function nextStep() {
        if (currentStep === 1) {
            if (!generateMatrix()) return; // validation fails
            document.getElementById('wizardBox').classList.add('modal-lg');
        } else if (currentStep === 2) {
            calculateSummary();
            document.getElementById('wizardBox').classList.remove('modal-lg');
        }
        currentStep++;
        updateWizardUI();
    }

    function prevStep() {
        currentStep--;
        if (currentStep === 1) document.getElementById('wizardBox').classList.remove('modal-lg');
        if (currentStep === 2) document.getElementById('wizardBox').classList.add('modal-lg');
        updateWizardUI();
    }

    function updateWizardUI() {
        for (let i = 1; i <= 4; i++) {
            const c = document.getElementById('step' + i);
            const d = document.getElementById('dot' + i);
            if (i === currentStep) {
                if (c) c.classList.add('active');
                if (d) d.classList.add('active');
            } else {
                if (c) c.classList.remove('active');
                if (d) d.classList.remove('active');
            }
        }

        document.getElementById('btnBack').style.display = (currentStep === 1 || currentStep === 4) ? 'none' : 'block';
        document.getElementById('btnNext').style.display = (currentStep === 4 || currentStep === 3) ? 'none' : 'block';
        document.getElementById('btnCreate').style.display = (currentStep === 3) ? 'block' : 'none';
        document.getElementById('btnDone').style.display = (currentStep === 4) ? 'block' : 'none';
    }

    function generateMatrix() {
        let sm = parseInt(document.getElementById('st_month').value);
        let sy = parseInt(document.getElementById('st_year').value);
        let em = parseInt(document.getElementById('en_month').value);
        let ey = parseInt(document.getElementById('en_year').value);

        let fd = new Date(sy, sm - 1, 1);
        let td = new Date(ey, em - 1, 1);
        if (fd > td) {
            Swal.fire({ icon: 'error', title: 'Fechas Inválidas', text: 'El inicio debe ser anterior o igual al fin.' });
            return false;
        }

        monthsList = [];
        let curr = new Date(fd);
        let yearsSet = new Set();
        while (curr <= td) {
            let val = curr.getFullYear() + '-' + String(curr.getMonth() + 1).padStart(2, '0');
            let lbl = '01 ' + monthNamesShort[curr.getMonth()] + ' ' + curr.getFullYear();
            yearsSet.add(curr.getFullYear());
            monthsList.push({ value: val, label: lbl, year: curr.getFullYear(), idx: curr.getMonth() });
            curr.setMonth(curr.getMonth() + 1);
        }

        // Init matrix data
        matrix = {};
        unitsData.forEach(u => {
            matrix[u.id] = {};
            let baseFee = parseFloat(u.maintenance_fee) || 0;
            monthsList.forEach(m => {
                matrix[u.id][m.value] = { amount: baseFee, skip: false };
            });
        });

        // Build Year Filters
        let yf = document.getElementById('year_filters');
        yf.innerHTML = `<button class="badge-year active" onclick="filterYear('all')" id="yf_all">Todos los Años</button>`;
        if (yearsSet.size > 1) {
            Array.from(yearsSet).sort().forEach(y => {
                yf.innerHTML += `<button class="badge-year" onclick="filterYear(${y})" id="yf_${y}">${y}</button>`;
            });
            yf.style.display = 'flex';
        } else {
            yf.style.display = 'none'; // hide if only 1 year
        }
        globalActiveYear = 'all';

        renderTable();
        return true;
    }

    function filterYear(y) {
        globalActiveYear = y;
        document.querySelectorAll('.badge-year').forEach(b => b.classList.remove('active'));
        document.getElementById('yf_' + y).classList.add('active');
        renderTable();
    }

    function renderTable() {
        let head = document.getElementById('matrix_head');
        let body = document.getElementById('matrix_body');

        // Render th
        let thHtml = `<th class="col-unit">Unidad <i class="bi bi-pencil btn-edit-unit" onclick="openFeeModal('all')" title="Configurar todas"></i></th>`;
        monthsList.forEach(m => {
            if (globalActiveYear === 'all' || globalActiveYear === m.year) {
                thHtml += `<th>${m.label}</th>`;
            }
        });
        head.innerHTML = thHtml;

        // Render td
        let bodyHtml = '';
        unitsData.forEach(u => {
            bodyHtml += `<tr><td class="col-unit">${u.unit_number} <i class="bi bi-pencil btn-edit-unit" onclick="openFeeModal(${u.id})"></i></td>`;
            monthsList.forEach(m => {
                if (globalActiveYear === 'all' || globalActiveYear === m.year) {
                    let cell = matrix[u.id][m.value];
                    let cls = cell.skip ? 'cell-fee skipped' : 'cell-fee';
                    bodyHtml += `<td class="${cls}" onclick="toggleCell(${u.id}, '${m.value}')">$${cell.amount}</td>`;
                }
            });
            bodyHtml += `</tr>`;
        });
        body.innerHTML = bodyHtml;
    }

    function toggleCell(unitId, monthVal) {
        matrix[unitId][monthVal].skip = !matrix[unitId][monthVal].skip;
        renderTable();
    }

    // --- FEE SUB-MODAL LOGIC ---
    function openFeeModal(unitId) {
        editingUnitId = unitId;
        tempSegments = [];
        let baseFee = 0;

        let applyAllBox = document.getElementById('box_apply_all');
        let titleEl = document.getElementById('fee_modal_title');
        let targetTxt = document.getElementById('fee_modal_target_txt');

        if (unitId === 'all') {
            applyAllBox.style.display = 'flex';
            document.getElementById('chk_apply_all').checked = true;
            baseFee = 5000;
            if (unitsData.length > 0) baseFee = parseFloat(unitsData[0].maintenance_fee) || 0;
        } else {
            let u = unitsData.find(x => x.id == unitId); // Changed to loose equality ==
            applyAllBox.style.display = 'flex';
            document.getElementById('chk_apply_all').checked = false; // Como en la imagen
            baseFee = parseFloat(u.maintenance_fee) || 0;
        }

        toggleApplyAll(); // Applies correct text and class based on checkbox state
        document.getElementById('fee_base_amount').innerText = '$' + baseFee;

        // Build initial segments array from matrix if possible, but to simplify, we just set 1 initial segment
        // The first month
        tempSegments.push({ fromMonth: monthsList[0].value, amount: baseFee });

        renderFeeSegments();
        document.getElementById('feeModal').style.display = 'flex';
    }

    function toggleApplyAll() {
        let chk = document.getElementById('chk_apply_all').checked;
        let titleEl = document.getElementById('fee_modal_title');
        let box = document.getElementById('box_apply_all');
        let targetTxt = document.getElementById('fee_modal_target_txt');

        if (chk) {
            titleEl.innerText = 'Configurar Calendario de Cuotas (Todas las Unidades)';
            targetTxt.innerText = 'Defina cambios de cuota a lo largo del tiempo para todas las unidades. Cada segmento representa un monto de cuota a partir de un mes específico.';
            box.classList.add('checked-state');
        } else {
            if (editingUnitId && editingUnitId !== 'all') {
                let u = unitsData.find(x => x.id == editingUnitId);
                titleEl.innerText = `Editar Historial de Cuotas - ${u.unit_number}`;
                targetTxt.innerText = 'Defina cambios de cuota a lo largo del tiempo para esta unidad. Cada segmento representa un monto de cuota a partir de un mes específico.';
                box.classList.remove('checked-state');
            } else {
                // If it's opened globally but user unchecks? They should be forced to have it checked, or it just affects the title?
                // For safety:
                titleEl.innerText = 'Editar Historial de Cuotas - Todas las Unidades';
                targetTxt.innerText = 'Defina cambios de cuota a lo largo del tiempo.';
                box.classList.remove('checked-state');
            }
        }
    }

    function closeFeeModal() {
        document.getElementById('feeModal').style.display = 'none';
    }

    function generateMonthOptions(selectedVal) {
        let opts = '';
        monthsList.forEach(m => {
            let sel = (m.value === selectedVal) ? 'selected' : '';
            opts += `<option value="${m.value}" ${sel}>01 ${monthNames[m.idx]} ${m.year}</option>`;
        });
        return opts;
    }

    function renderFeeSegments() {
        let cont = document.getElementById('fee_segments_container');
        // Sort segments by date
        tempSegments.sort((a, b) => a.fromMonth.localeCompare(b.fromMonth));

        let html = '';
        tempSegments.forEach((seg, i) => {
            let startIndex = monthsList.findIndex(m => m.value === seg.fromMonth);
            let endIndex = (i < tempSegments.length - 1)
                ? monthsList.findIndex(m => m.value === tempSegments[i + 1].fromMonth)
                : monthsList.length;
            let monthsCount = endIndex - startIndex;

            html += `<div style="background:white; border:1px solid var(--fin-border); border-radius:6px; padding:1rem; margin-bottom:1rem;">
                <div class="fee-seg-row" style="margin-bottom:0;">
                    <div style="flex-grow:1;">
                        <select class="form-control" onchange="updateSegmentMonth(${i}, this.value)" ${i === 0 ? 'disabled' : ''}>
                            ${generateMonthOptions(seg.fromMonth)}
                        </select>
                    </div>
                    <div style="width:120px; position:relative;">
                        <span style="position:absolute; left:0.6rem; top:0.6rem; color:var(--fin-text-main);">$</span>
                        <input type="number" class="form-control" style="padding-left:1.5rem;" value="${seg.amount}" onchange="updateSegmentAmount(${i}, this.value)">
                    </div>
                    ${i > 0 ? `<button class="btn-trash" onclick="removeSegment(${i})"><i class="bi bi-trash"></i></button>` : `<div style="width:34px;"></div>`}
                </div>
                <div style="font-size:0.75rem; color:var(--fin-text-muted); margin-top:0.5rem; margin-left:0.2rem;">
                    Aplica a ${monthsCount} mes(es)
                </div>
            </div>`;
        });
        cont.innerHTML = html;
        updateFeePreview();
    }

    function updateSegmentMonth(idx, val) { tempSegments[idx].fromMonth = val; renderFeeSegments(); }
    function updateSegmentAmount(idx, val) { tempSegments[idx].amount = parseFloat(val) || 0; updateFeePreview(); }
    function removeSegment(idx) { tempSegments.splice(idx, 1); renderFeeSegments(); }

    function addFeeSegment() {
        // Find next available month
        let lastM = tempSegments[tempSegments.length - 1].fromMonth;
        let nextIdx = monthsList.findIndex(x => x.value === lastM) + 1;
        if (nextIdx >= monthsList.length) {
            Swal.fire('Info', 'Ya no hay más meses disponibles', 'info'); return;
        }
        tempSegments.push({ fromMonth: monthsList[nextIdx].value, amount: tempSegments[tempSegments.length - 1].amount });
        renderFeeSegments();
    }

    function updateFeePreview() {
        let prev = document.getElementById('fee_preview_list');
        let html = '';
        for (let i = 0; i < tempSegments.length; i++) {
            let startM = monthsList.find(x => x.value === tempSegments[i].fromMonth);
            let endVal = (i < tempSegments.length - 1) ? tempSegments[i + 1].fromMonth : '9999-99';

            // find the visually last month before next segment
            let lastM = null;
            for (let j = monthsList.findIndex(x => x.value === startM.value); j < monthsList.length; j++) {
                if (monthsList[j].value >= endVal) break;
                lastM = monthsList[j];
            }
            if (lastM) {
                html += `<div>$${tempSegments[i].amount} (${startM.label} - ${lastM.label})</div>`;
            }
        }
        prev.innerHTML = html;
    }

    function saveFeeConfig() {
        let applyAll = (editingUnitId === 'all') || document.getElementById('chk_apply_all').checked;
        let targets = applyAll ? unitsData.map(u => u.id) : [editingUnitId];

        // Apply segments mapping to the matrix logic
        targets.forEach(uid => {
            // reset all to last segment value basically
            let currentAmt = 0;
            monthsList.forEach(m => {
                // find active segment for this month
                let activeSeg = tempSegments[0];
                for (let i = 1; i < tempSegments.length; i++) {
                    if (m.value >= tempSegments[i].fromMonth) activeSeg = tempSegments[i];
                }
                matrix[uid][m.value].amount = activeSeg.amount;
            });
        });

        closeFeeModal();
        renderTable();
    }


    // --- STEP 3 LOGIC ---
    let totalChargesCount = 0;
    let totalAmountVal = 0;

    function calculateSummary() {
        let sm = parseInt(document.getElementById('st_month').value);
        let sy = parseInt(document.getElementById('st_year').value);
        let em = parseInt(document.getElementById('en_month').value);
        let ey = parseInt(document.getElementById('en_year').value);
        document.getElementById('sum_range_txt').innerText = `01 ${monthNames[sm - 1]} ${sy} - 01 ${monthNames[em - 1]} ${ey}`;
        document.getElementById('sum_units_txt').innerText = unitsData.length;
        document.getElementById('sum_months').innerText = monthsList.length;

        totalChargesCount = 0;
        totalAmountVal = 0;

        unitsData.forEach(u => {
            monthsList.forEach(m => {
                if (!matrix[u.id][m.value].skip) {
                    totalChargesCount++;
                    totalAmountVal += matrix[u.id][m.value].amount;
                }
            });
        });

        document.getElementById('sum_charges').innerText = totalChargesCount;
        let fmtAmt = totalAmountVal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('sum_total').innerText = '$' + fmtAmt;

        document.getElementById('lbl_confirm').innerText = `Entiendo que esto creará ${totalChargesCount} cargos de HOA por un total de $${fmtAmt}. Esta acción no se puede deshacer fácilmente.`;
        document.getElementById('chk_confirm').checked = false;
        toggleCreateBtn();
    }

    function toggleCreateBtn() {
        document.getElementById('btnCreate').disabled = !document.getElementById('chk_confirm').checked;
    }

    function submitHistoricos() {
        if (!document.getElementById('chk_confirm').checked) return;

        let payloadCharges = [];
        unitsData.forEach(u => {
            monthsList.forEach(m => {
                if (!matrix[u.id][m.value].skip) {
                    payloadCharges.push({
                        unit_id: u.id,
                        month: m.value,
                        amount: matrix[u.id][m.value].amount
                    });
                }
            });
        });

        let payload = {
            start_date: monthsList[0] ? monthsList[0].value : null,
            charges: payloadCharges
        };

        const btn = document.getElementById('btnCreate');
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Procesando...';
        btn.disabled = true;

        fetch('<?= base_url('admin/finanzas/historicos/generar') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(payload)
        })
            .then(res => res.json())
            .then(res => {
                if (res.status === 200) {
                    document.getElementById('succ_msg').innerText = `Se crearon exitosamente ${monthsList.length} periodos de facturación y ${res.count} cargos de HOA.`;
                    nextStep(); // go to step 4
                } else {
                    Swal.fire('Error', res.error || 'Ocurrió un error al procesar la solicitud.', 'error');
                    btn.innerHTML = 'Crear Cargos';
                    btn.disabled = false;
                }
            })
            .catch(err => {
                Swal.fire('Error', 'Error de red.', 'error');
                btn.innerHTML = 'Crear Cargos';
                btn.disabled = false;
            });
    }

</script>

<?= $this->endSection() ?>