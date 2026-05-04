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



    .btn-ia {
        background: rgba(255, 255, 255, 0.1);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 0.25rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: border 0.2s;
    }

    .btn-ia:hover {
        border-color: white;
        color: white;
    }

    .nr-card {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .nr-card-title {
        font-weight: 600;
        font-size: 1.15rem;
        color: #1e293b;
        margin-bottom: 1.5rem;
    }

    .nr-label {
        font-size: 0.85rem;
        font-weight: 500;
        color: #3F67AC;
        margin-bottom: 0.5rem;
        display: block;
    }

    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .koti-select-trigger {
        border: 1px solid #e2e8f0;
        padding: 0.5rem 1rem;
        border-radius: 6px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        background: #fff;
        font-size: 0.85rem;
    }

    .koti-select-dropdown {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .koti-select-option {
        padding: 0.6rem 1rem;
        cursor: pointer;
        font-size: 0.85rem;
    }

    .koti-select-option:hover {
        background: #f1f5f9;
    }

    .mode-group {
        display: flex;
        gap: 1rem;
    }

    .mode-box {
        flex: 1;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 1rem;
        cursor: pointer;
        text-align: left;
        position: relative;
        background: #fff;
    }

    .mode-box.active {
        background: #238b71ff;
        border-color: #238b71ff;
        color: white;
    }

    .mode-title {
        font-weight: 600;
        font-size: 0.9rem;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .mode-desc {
        font-size: 0.8rem;
        color: #64748b;
    }

    .mode-box.active .mode-title,
    .mode-box.active .mode-desc {
        color: white;
    }

    .mode-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .mode-box.active .mode-icon {
        color: rgba(255, 255, 255, 0.7);
    }

    .input-icon-wrapper {
        position: relative;
        width: 100%;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .nr-control {
        display: block;
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .input-icon-wrapper .nr-control {
        padding-left: 2.5rem;
    }

    textarea.nr-control {
        padding: 0.6rem 1rem;
    }

    .nr-hint {
        font-size: 0.75rem;
        color: #94a3b8;
        display: block;
        margin-top: 0.4rem;
    }

    .btn-outline-dashed {
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #333;
        padding: 0.75rem;
        border-radius: 6px;
        width: 100%;
        font-weight: 500;
        font-size: 0.9rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-outline-dashed:hover {
        background: #f8fafc;
    }

    .nr-container {
        display: flex;
        gap: 2rem;
        transition: all 0.3s ease;
    }

    .form-container {
        flex: 2;
        width: 66%;
        transition: all 0.3s ease;
    }

    .sidebar-container {
        flex: 1;
        width: 34%;
        background: #fff;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        max-height: 80vh;
        overflow: hidden;
        position: sticky;
        top: 1rem;
        transition: all 0.3s ease;
    }

    .nr-container.sidebar-hidden .sidebar-container {
        display: none !important;
    }

    .nr-container.sidebar-hidden .form-container {
        width: 100%;
        flex: 1;
    }

    .sidebar-header {
        padding: 1.5rem 1.5rem 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        background: #fff;
    }

    .units-list-container {
        flex: 1;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .units-search-bar {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }

    .btn-select-all {
        white-space: nowrap;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 0.4rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 500;
        color: #3F67AC;
        cursor: pointer;
    }

    .btn-select-all:hover {
        background: #f8fafc;
    }

    .units-search-bar input {
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 0.4rem 0.4rem 0.4rem 2rem;
        font-size: 0.8rem;
    }

    .units-search-bar .input-icon-wrapper i {
        left: 0.75rem;
    }

    .units-list {
        padding: 1.5rem;
    }

    .section-group {
        margin-bottom: 1.5rem;
    }

    .section-header {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 0.5rem;
    }

    .section-badge {
        background: transparent;
        padding: 0;
        border-radius: 0;
        font-size: 0.75rem;
        margin-left: 0.5rem;
        color: #64748b;
    }

    .section-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.5rem 1rem;
    }

    .unit-item-row {
        display: flex;
        align-items: center;
        padding: 0;
        cursor: pointer;
    }

    .unit-checkbox {
        margin-right: 0.5rem;
        transform: scale(1.1);
    }

    .unit-name {
        font-weight: 500;
        font-size: 0.8rem;
        color: #333;
    }

    .unit-fee {
        font-size: 0.8rem;
        color: #64748b;
        margin-left: 0.25rem;
    }

    .nr-footer {
        position: fixed;
        bottom: 0;
        left: 260px;
        right: 0;
        background: #fff;
        border-top: 1px solid #e2e8f0;
        padding: 1rem 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        z-index: 1000;
        box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.05);
    }

    .footer-btn {
        padding: 0.6rem 1.5rem;
        border-radius: 6px;
        font-weight: 600;
        cursor: pointer;
    }

    .btn-cancel {
        background: transparent;
        border: 1px solid #e2e8f0;
        color: #64748b;
    }

    .btn-submit {
        background: #f52c25ff;
        border: none;
        color: #fff;
    }

    /* PC Filters */
    .pc-filters-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        background: #fff;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }

    .pc-filter-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .pc-filter-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #64748b;
    }

    .pc-pill {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        background: #f1f5f9;
        color: #3F67AC;
        cursor: pointer;
        transition: 0.2s;
        border: 1px solid transparent;
    }

    .pc-pill:hover {
        background: #e2e8f0;
    }

    .pc-pill.active {
        background: #3b82f6;
        color: white;
        border-color: #3b82f6;
    }

    /* PC Groups */
    .pc-section-group {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .pc-section-header {
        padding: 1rem;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
        cursor: pointer;
    }

    .pc-section-header-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .pc-section-toggle {
        font-size: 0.9rem;
        color: #64748b;
        transition: 0.3s;
    }

    .pc-section-toggle.collapsed {
        transform: rotate(-90deg);
    }

    .pc-section-title {
        font-weight: 600;
        font-size: 0.95rem;
        color: #1e293b;
    }

    .pc-section-count {
        background: #e2e8f0;
        padding: 0.1rem 0.5rem;
        border-radius: 12px;
        font-size: 0.75rem;
        color: #3F67AC;
    }

    .pc-select-group-btn {
        border: 1px solid #cbd5e1;
        background: #fff;
        color: #3F67AC;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        cursor: pointer;
        font-weight: 500;
    }

    .pc-select-group-btn:hover {
        background: #f1f5f9;
    }

    /* PC Month */
    .pc-month-label {
        padding: 0.75rem 1rem;
        background: #fff;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.85rem;
        font-weight: 600;
        color: #3F67AC;
    }

    .pc-month-label i {
        margin-right: 0.5rem;
        color: #94a3b8;
    }

    /* Pending Charge Row */
    .pending-charge-row {
        display: flex;
        align-items: center;
        padding: 1rem;
        border-bottom: 1px solid #f1f5f9;
        background: #fff;
        transition: 0.2s;
        gap: 1rem;
    }

    .pending-charge-row:last-child {
        border-bottom: none;
    }

    .pending-charge-row.selected {
        background: #f8fafc;
    }

    .pt-checkbox {
        transform: scale(1.1);
        margin: 0;
        cursor: pointer;
    }

    .pt-info {
        flex: 1;
    }

    .pt-title {
        font-size: 0.85rem;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .pt-debt {
        font-size: 0.75rem;
        color: #ef4444;
        font-weight: 500;
    }

    .pt-input-wrapper {
        display: flex;
        align-items: center;
        position: relative;
        width: 140px;
    }

    .pt-input-wrapper span {
        position: absolute;
        left: 0.75rem;
        color: #64748b;
        font-size: 0.85rem;
    }

    .pt-input {
        padding-left: 1.5rem !important;
        height: 36px;
        font-size: 0.85rem;
        text-align: right;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        width: 100%;
        outline: none;
    }

    .pt-input:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        border-color: #e2e8f0;
    }

    .btn-submit:hover {
        background: #238B71;
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
                    <h2 class="cc-hero-title">Nuevo Registro</h2>
                    <i class="bi bi-chevron-right"></i>
                    Registros de Ingresos y Gastos
                </div>
            </div>

        </div>


        <div class="nr-container">
            <!-- COLUMNA IZQUIERDA: Formulario -->
            <div class="form-container">

                <!-- Tarjeta 1: Tipo -->
                <div class="nr-card">
                    <div class="nr-card-title">Tipo</div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="nr-label">Tipo de Transacción <i class="bi bi-info-circle"></i></label>
                            <input type="hidden" id="transType" name="transType" value="income">
                            <div class="custom-select-wrapper w-100">
                                <div class="koti-select-trigger" id="dropdownTipo">
                                    <span id="txtTipo" class="text-dark"><i
                                            class="bi bi-currency-dollar me-2 text-dark"></i>Ingreso</span>
                                    <i class="bi bi-chevron-down text-muted small"></i>
                                </div>
                                <div class="koti-select-dropdown" id="menuTipo"
                                    style="display:none; position:absolute; z-index:100; width:100%; top:calc(100% + 5px);">
                                    <div class="koti-select-options">
                                        <div class="koti-select-option d-flex justify-content-between align-items-center bg-light"
                                            data-value="income"
                                            onclick="selectKotiOption('transType', 'txtTipo', 'Ingreso', 'bi-currency-dollar text-dark', this)">
                                            <span><i class="bi bi-currency-dollar me-2 text-dark"></i>Ingreso</span>
                                            <i class="bi bi-check2 text-dark"></i>
                                        </div>
                                        <div class="koti-select-option" data-value="expense"
                                            onclick="selectKotiOption('transType', 'txtTipo', 'Gasto', 'bi-graph-down-arrow text-dark', this)">
                                            <span><i class="bi bi-graph-down-arrow me-2 text-dark"></i>Gasto</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="nr-label">Categoría <i class="bi bi-info-circle"></i></label>
                            <input type="hidden" id="transCategory" name="transCategory" value="">
                            <div class="custom-select-wrapper w-100">
                                <div class="koti-select-trigger" id="dropdownCategoria">
                                    <span id="txtCategoria" class="text-dark"><i
                                            class="bi bi-currency-dollar me-2 text-dark"></i>Cuota de
                                        Mantenimiento</span>
                                    <i class="bi bi-chevron-down text-muted small"></i>
                                </div>
                                <div class="koti-select-dropdown shadow-sm" id="menuCategoria"
                                    style="display:none; position:absolute; z-index:100; width:100%; top:calc(100% + 5px);">
                                    <div class="p-2 border-bottom">
                                        <input type="text" id="catSearchInput" class="form-control form-control-sm"
                                            placeholder="Buscar o crear nueva...">
                                    </div>
                                    <div class="koti-select-options pb-2 pt-1"
                                        style="max-height: 250px; overflow-y: auto;" id="catOptionsContainer">
                                        <?php if (empty($categories)): ?>
                                            <div class="p-2 text-muted text-center" style="font-size:0.85rem;"
                                                id="emptyCatMsg">No hay categorías</div>
                                        <?php else: ?>
                                            <?php foreach ($categories as $index => $cat): ?>
                                                <?php
                                                $name = strtolower($cat['name']);
                                                $icon = 'bi-tag'; // Default
                                                if (strpos($name, 'mora') !== false)
                                                    $icon = 'bi-exclamation-circle';
                                                elseif (strpos($name, 'reserva') !== false)
                                                    $icon = 'bi-calendar-check';
                                                elseif (strpos($name, 'multa de amenidad') !== false)
                                                    $icon = 'bi-exclamation-triangle';
                                                elseif (strpos($name, 'estacionamiento') !== false)
                                                    $icon = 'bi-car-front';
                                                elseif (strpos($name, 'mascota') !== false)
                                                    $icon = 'bi-bug'; // closest to paw in standard bootstrap
                                                elseif (strpos($name, 'infracción') !== false || strpos($name, 'infraccion') !== false)
                                                    $icon = 'bi-slash-circle';
                                                elseif (strpos($name, 'otro ingreso') !== false)
                                                    $icon = 'bi-cash';
                                                elseif (strpos($name, 'salario') !== false || strpos($name, 'personal') !== false)
                                                    $icon = 'bi-people';
                                                elseif (strpos($name, 'mantenimiento') !== false)
                                                    $icon = 'bi-wrench';
                                                elseif (strpos($name, 'públicos') !== false || strpos($name, 'publicos') !== false)
                                                    $icon = 'bi-lightning';
                                                elseif (strpos($name, 'suministros') !== false)
                                                    $icon = 'bi-box';
                                                elseif (strpos($name, 'profesionales') !== false || strpos($name, 'servicios') !== false)
                                                    $icon = 'bi-bag';
                                                elseif (strpos($name, 'seguro') !== false)
                                                    $icon = 'bi-shield';
                                                elseif (strpos($name, 'otro') !== false)
                                                    $icon = 'bi-graph-down';
                                                if ($name === 'cuota de mantenimiento')
                                                    $icon = 'bi-currency-dollar';
                                                ?>
                                                <div class="koti-select-option d-flex justify-content-between align-items-center <?= $index === 0 ? 'bg-light' : '' ?> cat-option"
                                                    data-value="<?= esc($cat['id']) ?>" data-type="<?= esc($cat['type']) ?>"
                                                    data-name="<?= esc($cat['name']) ?>"
                                                    onclick="selectKotiOption('transCategory', 'txtCategoria', '<?= htmlspecialchars(addslashes($cat['name']), ENT_QUOTES) ?>', '<?= $icon ?> text-dark', this)">
                                                    <span><i
                                                            class="bi <?= $icon ?> me-2 text-dark"></i><?= esc($cat['name']) ?></span>
                                                    <?php if ($index === 0): ?><i
                                                            class="bi bi-check2 text-dark"></i><?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta 2: Modo de Transacción -->
                <div class="nr-card">
                    <div class="nr-card-title">Modo de Transacción</div>
                    <div class="mode-group" id="modeGroup">
                        <!-- Option 1 -->
                        <div class="mode-box active" data-mode="charge">
                            <div class="mode-title">Crear Cargo</div>
                            <div class="mode-desc">Solo crear cargo</div>
                            <i class="bi bi-info-circle mode-icon"></i>
                        </div>
                        <!-- Option 2 -->
                        <div class="mode-box" data-mode="payment">
                            <div class="mode-title">Registrar Pago</div>
                            <div class="mode-desc">Pago recibido</div>
                            <i class="bi bi-info-circle mode-icon"></i>
                        </div>
                        <!-- Option 3 -->
                        <div class="mode-box" data-mode="both">
                            <div class="mode-title">Cargo y Pago</div>
                            <div class="mode-desc">Crear cargo y pago</div>
                            <i class="bi bi-info-circle mode-icon"></i>
                        </div>
                    </div>
                    <input type="hidden" id="transMode" name="transMode" value="charge">
                </div>

                <!-- Tarjeta 3: Detalles del Pago -->
                <div class="nr-card">
                    <div class="nr-card-title">Detalles del Pago</div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-8" id="colMonto">
                            <label class="nr-label">Monto</label>
                            <div class="input-icon-wrapper">
                                <i class="bi bi-currency-dollar"></i>
                                <input type="number" class="nr-control"
                                    placeholder="Dejar vacío para usar la cuota de cada unidad" step="0.01">
                            </div>
                            <span class="nr-hint">Dejar vacío para usar la cuota de cada unidad</span>
                        </div>
                        <div class="col-md-4" id="colMetodoPago" style="display: none;">
                            <label class="nr-label">Método de Pago</label>
                            <div class="custom-select-wrapper w-100">
                                <div class="koti-select-trigger" id="dropdownMetodoPago">
                                    <span id="txtMetodoPago" class="text-dark">Transferencia Bancaria</span>
                                    <i class="bi bi-chevron-down text-muted small"></i>
                                </div>
                                <div class="koti-select-dropdown" id="menuMetodoPago"
                                    style="display:none; position:absolute; z-index:100; width:100%; top:calc(100% + 5px);">
                                    <div class="koti-select-options">
                                        <div class="koti-select-option bg-light" data-value="transferencia"
                                            onclick="selectKotiOptionSimple('paymentMethod', 'txtMetodoPago', 'Transferencia Bancaria', this)">
                                            <span>Transferencia Bancaria</span>
                                            <i class="bi bi-check2 text-dark"></i>
                                        </div>
                                        <div class="koti-select-option" data-value="efectivo"
                                            onclick="selectKotiOptionSimple('paymentMethod', 'txtMetodoPago', 'Efectivo', this)">
                                            <span>Efectivo</span>
                                        </div>
                                        <div class="koti-select-option" data-value="cheque"
                                            onclick="selectKotiOptionSimple('paymentMethod', 'txtMetodoPago', 'Cheque', this)">
                                            <span>Cheque</span>
                                        </div>
                                        <div class="koti-select-option" data-value="stripe"
                                            onclick="selectKotiOptionSimple('paymentMethod', 'txtMetodoPago', 'Stripe', this)">
                                            <span>Stripe</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="paymentMethod" name="paymentMethod" value="transferencia">
                        </div>
                        <div class="col-md-4" id="colFecha">
                            <label class="nr-label" id="lblFecha">Fecha de Vencimiento</label>
                            <input type="text" id="premiumDatePicker" class="nr-control bg-white"
                                placeholder="Seleccionar Fecha" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>

                    <!-- NUEVA SECCIÓN DE CUOTAS PENDIENTES -->
                    <div id="pendingChargesContainer"
                        style="display:none; margin-bottom: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem;">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <label class="nr-label mb-0"><i class="bi bi-wallet2 text-success me-1"></i> Cargos
                                Pendientes</label>
                            <span class="badge bg-success" id="pendingTotalBadge">Monto aplicado: $0.00</span>
                        </div>
                        <p class="text-muted" style="font-size: 0.8rem; margin-top:-0.5rem; margin-bottom:1rem;">
                            Seleccione los cargos a pagar y defina el monto a abonar en cada uno si es un pago
                            parcial.
                        </p>

                        <!-- Filtros -->
                        <div id="pendingFiltersBar" class="pc-filters-bar" style="display:none;"></div>

                        <div id="pendingChargesList" class="d-flex flex-column gap-2">
                            <!-- Los cargos se inyectarán vía AJAX -->
                            <div class="text-center text-muted py-3" id="pendingLoading" style="display:none;">
                                <div class="spinner-border spinner-border-sm text-secondary" role="status"></div>
                                Carga
                                en curso...
                            </div>
                            <div class="text-center text-muted py-2" id="pendingEmpty"
                                style="display:none; font-size:0.85rem;">
                                No presenta cargos vencidos.
                            </div>
                        </div>
                    </div>
                    <!-- FIN NVA SECCION -->

                    <div>
                        <label class="nr-label">Descripción</label>
                        <textarea class="nr-control" rows="3"
                            placeholder="ej., Cuota de mantenimiento mensual <?= date('M Y') ?>"></textarea>
                    </div>
                </div>

                <!-- Tarjeta 4: Adjuntos -->
                <div class="nr-card">
                    <div class="nr-card-title">Adjuntos</div>
                    <input type="file" id="attachmentInput" style="display:none;" accept="image/*,.pdf" multiple>
                    <button type="button" class="btn-outline-dashed"
                        onclick="document.getElementById('attachmentInput').click()">
                        <i class="bi bi-image"></i> Agregar Recibo o Factura
                    </button>
                    <div id="attachmentPreview" class="attachment-preview-container"></div>
                    <span class="nr-hint">Suba imágenes de recibos o facturas (máx 5MB cada una)</span>
                </div>

            </div>

            <!-- COLUMNA DERECHA: Seleccionar Unidades -->
            <div class="sidebar-container">

                <div class="sidebar-header">
                    <div>
                        <div class="nr-card-title mb-0">Seleccionar Unidades <i
                                class="bi bi-info-circle text-muted fs-6" style="cursor:help;"></i></div>
                        <span class="nr-hint" id="lblSelCount">0 unidades seleccionadas</span>
                    </div>
                </div>

                <div class="units-list-container">
                    <div class="units-search-bar">
                        <div class="input-icon-wrapper flex-grow-1" style="display:flex;">
                            <i class="bi bi-search" style="left: 0.5rem; font-size:0.8rem;"></i>
                            <input type="text" id="filterUnits" placeholder="Buscar"
                                style="padding-left:1.8rem; width:100%;">
                        </div>
                        <button class="btn-select-all" id="btnSelectAll">Seleccionar Todos</button>
                    </div>

                    <div class="units-list" id="unitsList">
                        <?php if (empty($groupedUnits)): ?>
                            <div class="p-3 text-muted text-center" style="font-size:0.85rem;">No hay unidades
                                configuradas.
                            </div>
                        <?php else: ?>
                            <?php foreach ($groupedUnits as $secName => $groupUnits): ?>
                                <div class="section-group">
                                    <div class="section-header">
                                        <?= esc($secName) ?>
                                        <span class="section-badge"><?= count($groupUnits) ?></span>
                                    </div>
                                    <div class="section-grid">
                                        <?php foreach ($groupUnits as $u): ?>
                                            <?php $fee = (float) $u['maintenance_fee']; ?>
                                            <label class="unit-item-row">
                                                <input type="checkbox" class="unit-checkbox" value="<?= $u['id'] ?>">
                                                <span class="unit-name"><?= esc($u['label']) ?></span>
                                                <span class="unit-fee">($<?= number_format($fee, 2) ?>)</span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Sticky Footer -->
<div class="nr-footer">
    <button class="footer-btn btn-cancel">Cancelar</button>
    <button class="footer-btn btn-submit" id="btnSubmitFinal">
        <i class="bi bi-save"></i> Crear Cargo
    </button>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    // --- Koti-Select Global Handlers ---
    function selectKotiOption(hiddenId, textId, textVal, iconClass, elem) {
        document.getElementById(hiddenId).value = elem.getAttribute('data-value');
        document.getElementById(textId).innerHTML = `<i class="bi ${iconClass} me-2 text-dark"></i>${textVal}`;

        const options = elem.closest('.koti-select-options').querySelectorAll('.koti-select-option');
        options.forEach(opt => {
            opt.classList.remove('bg-light');
            const check = opt.querySelector('.bi-check2');
            if (check) check.remove();
        });

        elem.classList.add('bg-light');
        elem.insertAdjacentHTML('beforeend', '<i class="bi bi-check2 text-dark"></i>');

        if (hiddenId === 'transType') {
            filterCategories(elem.getAttribute('data-value'));
            toggleGastoLayout(elem.getAttribute('data-value'));
        }
    }

    function toggleGastoLayout(type) {
        const nrContainer = document.querySelector('.nr-container');
        const modeCard = document.getElementById('modeGroup').closest('.nr-card');
        const btnSF = document.getElementById('btnSubmitFinal');
        const colMonto = document.getElementById('colMonto');
        const colMetodoPago = document.getElementById('colMetodoPago');
        const colFecha = document.getElementById('colFecha');
        const lblFecha = document.getElementById('lblFecha');

        if (type === 'expense') {
            nrContainer.classList.add('sidebar-hidden');
            modeCard.style.display = 'none';
            btnSF.innerHTML = '<i class="bi bi-save"></i> Crear Gasto';
            if (colMonto) colMonto.className = 'col-md-5';
            if (colMetodoPago) {
                colMetodoPago.style.display = 'block';
                colMetodoPago.className = 'col-md-4';
            }
            if (colFecha) colFecha.className = 'col-md-3';
            if (lblFecha) lblFecha.innerText = 'Fecha de Pago';

            const checkboxes = document.querySelectorAll('.unit-checkbox');
            checkboxes.forEach(cb => cb.checked = false);
            document.getElementById('lblSelCount').innerText = '0 unidades seleccionadas';
            const btnAll = document.getElementById('btnSelectAll');
            if (btnAll) {
                btnAll.textContent = 'Seleccionar Todos';
                window.allSelected = false;
            }
        } else {
            nrContainer.classList.remove('sidebar-hidden');
            modeCard.style.display = 'block';
            const transMode = document.getElementById('transMode').value;
            const modeBtn = document.querySelector(`.mode-box[data-mode="${transMode}"]`);
            if (modeBtn) modeBtn.click();
        }
    }


    function selectKotiOptionSimple(hiddenId, textId, textVal, elem) {
        document.getElementById(hiddenId).value = elem.getAttribute('data-value');
        document.getElementById(textId).innerText = textVal;

        const options = elem.closest('.koti-select-options').querySelectorAll('.koti-select-option');
        options.forEach(opt => {
            opt.classList.remove('bg-light');
            const check = opt.querySelector('.bi-check2');
            if (check) check.remove();
        });

        elem.classList.add('bg-light');
        elem.insertAdjacentHTML('beforeend', '<i class="bi bi-check2 text-dark"></i>');
    }

    function filterCategories(type) {
        const catOptions = document.querySelectorAll('.cat-option');
        const currentCatId = document.getElementById('transCategory').value;
        let firstVisible = null;
        let currentStillVisible = false;

        // Reset all options first
        catOptions.forEach(opt => {
            opt.classList.remove('bg-light');
            const check = opt.querySelector('.bi-check2');
            if (check) check.remove();

            const catType = opt.getAttribute('data-type');
            if (catType === type || catType === 'both') {
                opt.style.setProperty('display', 'flex', 'important');
                if (!firstVisible) firstVisible = opt;
                if (opt.getAttribute('data-value') === currentCatId) {
                    currentStillVisible = true;
                    opt.classList.add('bg-light');
                    opt.insertAdjacentHTML('beforeend', '<i class="bi bi-check2 text-dark"></i>');
                }
            } else {
                opt.style.setProperty('display', 'none', 'important');
            }
        });

        // If current is gone, or nothing selected, pick first visible
        if (!currentStillVisible && firstVisible) {
            firstVisible.click();
        }
    }

    document.addEventListener('click', function (e) {
        if (!e.target || typeof e.target.closest !== 'function') return;

        const isDropdownTipo = e.target.closest('#dropdownTipo');
        const isDropdownCat = e.target.closest('#dropdownCategoria');
        const isDropdownPago = e.target.closest('#dropdownMetodoPago');

        if (isDropdownTipo) {
            document.getElementById('menuTipo').style.display = document.getElementById('menuTipo').style.display === 'none' ? 'block' : 'none';
            document.getElementById('menuCategoria').style.display = 'none';
            if (document.getElementById('menuMetodoPago')) document.getElementById('menuMetodoPago').style.display = 'none';
        } else if (isDropdownCat) {
            document.getElementById('menuCategoria').style.display = document.getElementById('menuCategoria').style.display === 'none' ? 'block' : 'none';
            document.getElementById('menuTipo').style.display = 'none';
            if (document.getElementById('menuMetodoPago')) document.getElementById('menuMetodoPago').style.display = 'none';
        } else if (isDropdownPago) {
            document.getElementById('menuMetodoPago').style.display = document.getElementById('menuMetodoPago').style.display === 'none' ? 'block' : 'none';
            document.getElementById('menuTipo').style.display = 'none';
            document.getElementById('menuCategoria').style.display = 'none';
        } else {
            const dTipo = document.getElementById('menuTipo');
            const dCat = document.getElementById('menuCategoria');
            const dPago = document.getElementById('menuMetodoPago');
            if (dTipo) dTipo.style.display = 'none';
            if (dCat) dCat.style.display = 'none';
            if (dPago) dPago.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', function () {

        try {
            filterCategories('income');
        } catch (err) {
            console.error("Error filterCategories", err);
        }

        // --- Lógica del buscador de categorías / Crear Nueva Categoría ---
        const catSearch = document.getElementById('catSearchInput');
        const catOptionsContainer = document.getElementById('catOptionsContainer');
        if (catSearch && catOptionsContainer) {
            catSearch.addEventListener('keyup', function (e) {
                const val = this.value.toLowerCase().trim();
                const options = catOptionsContainer.querySelectorAll('.cat-option');
                let foundAny = false;
                const currentType = document.getElementById('transType').value;

                options.forEach(opt => {
                    const text = opt.textContent.toLowerCase();
                    const catType = opt.getAttribute('data-type');
                    if (!catType || catType === currentType || catType === 'both') {
                        if (text.includes(val)) {
                            opt.style.display = 'flex';
                            foundAny = true;
                        } else {
                            opt.style.display = 'none';
                        }
                    }
                });

                const createBtn = document.getElementById('btnCreateCat');
                const emptyMsg = document.getElementById('emptyCatMsg');
                if (emptyMsg) emptyMsg.style.display = 'none';

                if (!foundAny && val.length > 0) {
                    if (!createBtn) {
                        catOptionsContainer.insertAdjacentHTML('beforeend', `<div class="koti-select-option d-flex justify-content-between align-items-center text-primary" id="btnCreateCat" onclick="selectNewCategory()"><span><i class="bi bi-plus-circle me-2"></i>Crear categoría: <b id="lblNewCat"></b></span></div>`);
                    }
                    document.getElementById('lblNewCat').innerText = this.value;
                    document.getElementById('btnCreateCat').style.display = 'flex';
                } else {
                    if (createBtn) createBtn.style.display = 'none';
                    if (!foundAny && emptyMsg) emptyMsg.style.display = 'block';
                }
            });

            catSearch.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const createBtn = document.getElementById('btnCreateCat');
                    if (createBtn && createBtn.style.display !== 'none') {
                        createBtn.click();
                    }
                }
            });
        }

        window.selectNewCategory = function () {
            const val = document.getElementById('catSearchInput').value.trim();
            document.getElementById('transCategory').value = 'NEW:' + val;
            document.getElementById('txtCategoria').innerHTML = `<i class="bi bi-tag me-2 text-dark"></i>${val}`;

            const options = catOptionsContainer.querySelectorAll('.koti-select-option');
            options.forEach(opt => {
                opt.classList.remove('bg-light');
                const check = opt.querySelector('.bi-check2');
                if (check) check.remove();
            });

            const dCat = document.getElementById('menuCategoria');
            if (dCat) dCat.style.display = 'none';
        };


        try {
            if (typeof flatpickr !== 'undefined') {
                flatpickr("#premiumDatePicker", {
                    locale: "es",
                    altInput: true,
                    altFormat: "j \\d\\e F \\d\\e Y",
                    dateFormat: "Y-m-d",
                    defaultDate: "today",
                    disableMobile: true
                });
            } else {
                console.error("Flatpickr is not loaded via CDN.");
            }
        } catch (err) {
            console.error("Flatpickr Error", err);
        }

        // 1. Selector de Modo de Transacción
        const modeBoxes = document.querySelectorAll('.mode-box');
        const transModeInput = document.getElementById('transMode');

        modeBoxes.forEach(box => {
            box.addEventListener('click', function () {
                // Quitar active de todas
                modeBoxes.forEach(b => b.classList.remove('active'));
                // Agregar active a la clickeada
                this.classList.add('active');

                // Actualizar hidden input y boton enviar
                const modeName = this.getAttribute('data-mode');
                transModeInput.value = modeName;

                var btnSF = document.getElementById('btnSubmitFinal');
                const colMonto = document.getElementById('colMonto');
                const colMetodoPago = document.getElementById('colMetodoPago');
                const colFecha = document.getElementById('colFecha');
                const lblFecha = document.getElementById('lblFecha');

                if (modeName === 'charge') {
                    if (btnSF) btnSF.innerHTML = '<i class="bi bi-save"></i> Crear Cargo';
                    if (colMonto) colMonto.className = 'col-md-8';
                    if (colMetodoPago) colMetodoPago.style.display = 'none';
                    if (colFecha) colFecha.className = 'col-md-4';
                    if (lblFecha) lblFecha.innerText = 'Fecha de Vencimiento';
                } else {
                    if (modeName === 'payment') {
                        if (btnSF) btnSF.innerHTML = '<i class="bi bi-save"></i> Registrar Pago';
                    } else {
                        if (btnSF) btnSF.innerHTML = '<i class="bi bi-save"></i> Guardar Transacción';
                    }
                    if (colMonto) colMonto.className = 'col-md-5';
                    if (colMetodoPago) {
                        colMetodoPago.style.display = 'block';
                        colMetodoPago.className = 'col-md-4';
                    }
                    if (colFecha) colFecha.className = 'col-md-3';
                    if (lblFecha) lblFecha.innerText = 'Fecha de Pago';
                }
                if (typeof checkPendingVisibility === 'function') checkPendingVisibility();
            });
        });

        // --- Lógica de Adjuntos Múltiples ---
        const attachmentInput = document.getElementById('attachmentInput');
        const attachmentPreview = document.getElementById('attachmentPreview');
        let fileTransfer = new DataTransfer();

        if (attachmentInput) {
            attachmentInput.addEventListener('change', function () {
                const files = Array.from(this.files);
                let hasError = false;

                files.forEach((file) => {
                    // Validación individual de 5MB
                    if (file.size > 5 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Archivo muy pesado',
                            text: `El archivo ${file.name} excede los 5MB permitidos. No se adjuntará.`,
                            confirmButtonColor: '#3b82f6'
                        });
                        hasError = true;
                        return; // Skip this file
                    }

                    // Evitar duplicados por nombre y tamaño (opcional, básico)
                    let isDuplicate = false;
                    for (let i = 0; i < fileTransfer.items.length; i++) {
                        const existingFile = fileTransfer.items[i].getAsFile();
                        if (existingFile.name === file.name && existingFile.size === file.size) {
                            isDuplicate = true;
                            break;
                        }
                    }

                    if (!isDuplicate) {
                        fileTransfer.items.add(file);
                    }
                });

                // Sincronizar input con transfer object
                this.files = fileTransfer.files;
                renderAttachments();
            });

            function renderAttachments() {
                attachmentPreview.innerHTML = '';
                Array.from(fileTransfer.files).forEach((file, index) => {
                    const badge = document.createElement('div');
                    badge.className = 'attachment-badge';
                    const icon = file.type.includes('image') ? 'bi-image' : 'bi-file-earmark-pdf';
                    badge.innerHTML = `
                    <i class="bi ${icon}"></i>
                    <span>${file.name}</span>
                    <i class="bi bi-x-circle remove-attachment" data-index="${index}"></i>
                `;
                    attachmentPreview.appendChild(badge);
                });
            }

            // Eliminar adjunto individual
            attachmentPreview.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-attachment')) {
                    const indexToRemove = parseInt(e.target.getAttribute('data-index'), 10);
                    const newTransfer = new DataTransfer();
                    Array.from(fileTransfer.files).forEach((file, index) => {
                        if (index !== indexToRemove) {
                            newTransfer.items.add(file);
                        }
                    });
                    fileTransfer = newTransfer;
                    attachmentInput.files = fileTransfer.files;
                    renderAttachments();
                }
            });
        }

        // 2. Buscador en la Lista de Unidades
        const filterInput = document.getElementById('filterUnits');
        const unitRows = document.querySelectorAll('.unit-item-row');

        if (filterInput) {
            filterInput.addEventListener('keyup', function () {
                const val = this.value.toLowerCase();
                const groups = document.querySelectorAll('.section-group');

                groups.forEach(group => {
                    const rows = group.querySelectorAll('.unit-item-row');
                    let visibleRows = 0;
                    rows.forEach(row => {
                        const text = row.querySelector('.unit-name').textContent.toLowerCase();
                        if (text.includes(val)) {
                            row.style.display = 'flex';
                            visibleRows++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (visibleRows === 0) {
                        group.style.display = 'none';
                    } else {
                        group.style.display = 'block';
                    }
                });
            });
        }

        // 3. Seleccionar Todos / Contar Seleccionados
        const btnSelectAll = document.getElementById('btnSelectAll');
        const checkboxes = document.querySelectorAll('.unit-checkbox');
        const lblCount = document.getElementById('lblSelCount');
        let allSelected = false;

        function updateCount() {
            if (!checkboxes) return;
            const checked = document.querySelectorAll('.unit-checkbox:checked').length;
            lblCount.textContent = `${checked} unidades seleccionadas`;
        }

        if (btnSelectAll) {
            btnSelectAll.addEventListener('click', function () {
                allSelected = !allSelected;
                // Solo afectar a los que están visibles en la búsqueda
                unitRows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const cb = row.querySelector('.unit-checkbox');
                        if (cb) cb.checked = allSelected;
                    }
                });

                this.textContent = allSelected ? 'Deseleccionar Todos' : 'Seleccionar Todos';
                updateCount();
                if (typeof checkPendingVisibility === 'function') checkPendingVisibility();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                updateCount();
                if (typeof checkPendingVisibility === 'function') checkPendingVisibility();
            });
        });

        // --- 3.5. Lógica de Cuotas Pendientes ---
        const pendingContainer = document.getElementById('pendingChargesContainer');
        const pendingList = document.getElementById('pendingChargesList');
        const pendingTotalBadge = document.getElementById('pendingTotalBadge');
        const pendingFiltersBar = document.getElementById('pendingFiltersBar');
        const amountInputGlobal = document.querySelector('#colMonto input[type="number"]');
        let loadedCharges = [];
        let pcActiveFilters = { section: 'all', month: 'all', year: 'all' };

        window.checkPendingVisibility = function () {
            const checked = document.querySelectorAll('.unit-checkbox:checked');
            const modeInput = document.getElementById('transMode');
            const mode = modeInput ? modeInput.value : 'charge';

            if (mode === 'payment' && checked.length > 0) {
                const unitIds = Array.from(checked).map(c => c.value).join(',');
                pendingContainer.style.display = 'block';
                fetchPendingCharges(unitIds);
            } else {
                pendingContainer.style.display = 'none';
                pendingFiltersBar.style.display = 'none';
                pendingList.innerHTML = '<div class="text-center text-muted py-3" id="pendingLoading" style="display:none;"><div class="spinner-border spinner-border-sm text-secondary" role="status"></div> Carga en curso...</div><div class="text-center text-muted py-2" id="pendingEmpty" style="display:none; font-size:0.85rem;">No presenta cargos vencidos.</div>';
                loadedCharges = [];
                pcActiveFilters = { section: 'all', month: 'all', year: 'all' };
                calculatePendingTotal();
            }
        };

        function fetchPendingCharges(unitIds) {
            Array.from(pendingList.children).forEach(c => c.style.display = 'none');
            const loadingEl = document.getElementById('pendingLoading');
            if (loadingEl) loadingEl.style.display = 'block';

            const fd = new FormData();
            fd.append('unitIds', unitIds);

            fetch(`<?= site_url('admin/finanzas/nuevo-registro/api/pending-charges') ?>`, {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(res => {
                    Array.from(pendingList.children).forEach(c => c.style.display = 'none');
                    if (res.status === 'success' && res.data.length > 0) {
                        loadedCharges = res.data;
                        pcActiveFilters = { section: 'all', month: 'all', year: 'all' };
                        buildPendingFilters();
                        renderPendingCharges();
                    } else {
                        loadedCharges = [];
                        pendingFiltersBar.style.display = 'none';
                        const emptyEl = document.getElementById('pendingEmpty');
                        if (emptyEl) emptyEl.style.display = 'block';
                    }
                    calculatePendingTotal();
                }).catch(err => console.error(err));
        }

        const mesesNombres = { 1: 'Enero', 2: 'Febrero', 3: 'Marzo', 4: 'Abril', 5: 'Mayo', 6: 'Junio', 7: 'Julio', 8: 'Agosto', 9: 'Septiembre', 10: 'Octubre', 11: 'Noviembre', 12: 'Diciembre' };

        function buildPendingFilters() {
            const sections = [...new Set(loadedCharges.map(c => c.section_name))].sort();
            const years = [...new Set(loadedCharges.map(c => c.year))].sort();
            const months = [...new Set(loadedCharges.map(c => c.month_num))].sort((a, b) => a - b);

            let html = '';

            // Sección filter
            if (sections.length > 1) {
                html += '<div class="pc-filter-group"><span class="pc-filter-label">Sección:</span>';
                html += `<span class="pc-pill active" data-filter="section" data-val="all">Todas</span>`;
                sections.forEach(s => {
                    html += `<span class="pc-pill" data-filter="section" data-val="${s}">${s}</span>`;
                });
                html += '</div>';
            }

            // Año filter
            if (years.length > 1) {
                html += '<div class="pc-filter-group"><span class="pc-filter-label">Año:</span>';
                html += `<span class="pc-pill active" data-filter="year" data-val="all">Todos</span>`;
                years.forEach(y => {
                    html += `<span class="pc-pill" data-filter="year" data-val="${y}">${y}</span>`;
                });
                html += '</div>';
            }

            // Mes filter
            if (months.length > 1) {
                html += '<div class="pc-filter-group"><span class="pc-filter-label">Mes:</span>';
                html += `<span class="pc-pill active" data-filter="month" data-val="all">Todos</span>`;
                months.forEach(m => {
                    html += `<span class="pc-pill" data-filter="month" data-val="${m}">${mesesNombres[m]}</span>`;
                });
                html += '</div>';
            }

            if (html) {
                pendingFiltersBar.innerHTML = html;
                pendingFiltersBar.style.display = 'flex';

                pendingFiltersBar.querySelectorAll('.pc-pill').forEach(pill => {
                    pill.addEventListener('click', function () {
                        const filterType = this.getAttribute('data-filter');
                        const filterVal = this.getAttribute('data-val');
                        pcActiveFilters[filterType] = filterVal === 'all' ? 'all' : (filterType === 'year' || filterType === 'month' ? parseInt(filterVal) : filterVal);

                        // Update pill active states within same group
                        this.closest('.pc-filter-group').querySelectorAll('.pc-pill').forEach(p => p.classList.remove('active'));
                        this.classList.add('active');

                        renderPendingCharges();
                        calculatePendingTotal();
                    });
                });
            } else {
                pendingFiltersBar.style.display = 'none';
            }
        }

        function getFilteredCharges() {
            return loadedCharges.filter(c => {
                if (pcActiveFilters.section !== 'all' && c.section_name !== pcActiveFilters.section) return false;
                if (pcActiveFilters.year !== 'all' && c.year !== pcActiveFilters.year) return false;
                if (pcActiveFilters.month !== 'all' && c.month_num !== pcActiveFilters.month) return false;
                return true;
            });
        }

        function renderPendingCharges() {
            const filtered = getFilteredCharges();

            if (filtered.length === 0) {
                pendingList.innerHTML = '<div class="text-center text-muted py-2" style="font-size:0.85rem;">No hay cargos que coincidan con los filtros.</div>';
                return;
            }

            // Group: Section → Month/Year
            const grouped = {};
            filtered.forEach(c => {
                const sec = c.section_name;
                const monthKey = `${c.year}-${String(c.month_num).padStart(2, '0')}`;
                const monthLabel = mesesNombres[c.month_num] + ' ' + c.year;
                if (!grouped[sec]) grouped[sec] = {};
                if (!grouped[sec][monthKey]) grouped[sec][monthKey] = { label: monthLabel, charges: [] };
                grouped[sec][monthKey].charges.push(c);
            });

            let html = '';
            const sortedSections = Object.keys(grouped).sort();

            sortedSections.forEach(sec => {
                const monthKeys = Object.keys(grouped[sec]).sort();
                let sectionChargeCount = 0;
                monthKeys.forEach(mk => sectionChargeCount += grouped[sec][mk].charges.length);
                const secId = 'pcSec_' + sec.replace(/\s+/g, '_');

                html += `<div class="pc-section-group" id="${secId}">`;
                html += `<div class="pc-section-header" onclick="togglePcSection('${secId}')">`;
                html += `<div class="pc-section-header-left">`;
                html += `<i class="bi bi-building pc-section-toggle" id="${secId}_toggle"></i>`;
                html += `<span class="pc-section-title">${sec}</span>`;
                html += `<span class="pc-section-count">${sectionChargeCount}</span>`;
                html += `</div>`;
                html += `<button type="button" class="pc-select-group-btn" onclick="event.stopPropagation(); toggleSelectGroup('${secId}')">Seleccionar todo</button>`;
                html += `</div>`;
                html += `<div class="pc-section-body" id="${secId}_body">`;

                monthKeys.forEach(mk => {
                    const group = grouped[sec][mk];
                    const mkId = secId + '_' + mk.replace('-', '');
                    html += `<div class="pc-month-label">`;
                    html += `<i class="bi bi-calendar3"></i> ${group.label}`;
                    html += `<button type="button" class="pc-select-group-btn" onclick="toggleSelectMonth('${mkId}')">Seleccionar</button>`;
                    html += `</div>`;

                    group.charges.forEach(c => {
                        html += `
                    <div class="pending-charge-row" id="pcRow_${c.id}" data-section="${sec}" data-month-group="${mkId}" data-sec-group="${secId}">
                        <input type="checkbox" class="pt-checkbox pc-checkbox-core" id="pcCheck_${c.id}" value="${c.id}" data-max="${c.debt_remaining}">
                        <div class="pt-info">
                            <div class="pt-title">${c.display_label}</div>
                            <div class="pt-debt">Pendiente: $${parseFloat(c.debt_remaining).toLocaleString('en-US', { minimumFractionDigits: 2 })}</div>
                        </div>
                        <div class="pt-input-wrapper">
                            <span>$</span>
                            <input type="number" class="pt-input form-control pc-input-val" id="pcInput_${c.id}" max="${c.debt_remaining}" step="0.01" value="${c.debt_remaining}" disabled>
                        </div>
                    </div>`;
                    });
                });

                html += `</div></div>`; // close body + section-group
            });

            pendingList.innerHTML = html;
            bindPendingChargeEvents();
        }

        function bindPendingChargeEvents() {
            pendingList.querySelectorAll('.pc-checkbox-core').forEach(cb => {
                cb.addEventListener('change', function () {
                    const id = this.value;
                    const row = document.getElementById('pcRow_' + id);
                    const input = document.getElementById('pcInput_' + id);
                    if (this.checked) {
                        row.classList.add('selected');
                        input.disabled = false;
                    } else {
                        row.classList.remove('selected');
                        input.disabled = true;
                        input.value = this.getAttribute('data-max');
                    }
                    calculatePendingTotal();
                });
            });

            pendingList.querySelectorAll('.pc-input-val').forEach(inp => {
                inp.addEventListener('input', function () {
                    let val = parseFloat(this.value) || 0;
                    const max = parseFloat(this.getAttribute('max')) || 0;
                    if (val > max) this.value = max;
                    if (val < 0) this.value = 0;
                    calculatePendingTotal();
                });
            });
        }

        // Toggle section collapse
        window.togglePcSection = function (secId) {
            const body = document.getElementById(secId + '_body');
            const toggle = document.getElementById(secId + '_toggle');
            if (body.style.display === 'none') {
                body.style.display = '';
                toggle.classList.remove('collapsed');
            } else {
                body.style.display = 'none';
                toggle.classList.add('collapsed');
            }
        };

        // Toggle select all in a section
        window.toggleSelectGroup = function (secId) {
            const rows = pendingList.querySelectorAll(`[data-sec-group="${secId}"]`);
            const checkboxes = [];
            rows.forEach(r => { const cb = r.querySelector('.pc-checkbox-core'); if (cb) checkboxes.push(cb); });
            const allChecked = checkboxes.every(cb => cb.checked);
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
                cb.dispatchEvent(new Event('change'));
            });
        };

        // Toggle select all in a month group
        window.toggleSelectMonth = function (mkId) {
            const rows = pendingList.querySelectorAll(`[data-month-group="${mkId}"]`);
            const checkboxes = [];
            rows.forEach(r => { const cb = r.querySelector('.pc-checkbox-core'); if (cb) checkboxes.push(cb); });
            const allChecked = checkboxes.every(cb => cb.checked);
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
                cb.dispatchEvent(new Event('change'));
            });
        };

        function calculatePendingTotal() {
            let total = 0;
            pendingList.querySelectorAll('.pc-checkbox-core:checked').forEach(cb => {
                const input = document.getElementById('pcInput_' + cb.value);
                if (input) total += (parseFloat(input.value) || 0);
            });

            pendingTotalBadge.textContent = 'Monto aplicado: $' + total.toLocaleString('en-US', { minimumFractionDigits: 2 });
            if (total > 0) {
                amountInputGlobal.value = total.toFixed(2);
            } else if (pendingContainer.style.display === 'block') {
                amountInputGlobal.value = '';
            }
        }

        // --- 4. Submit + SweetAlert Confirmation ---
        var btnSubmitFinal = document.getElementById('btnSubmitFinal');
        if (btnSubmitFinal) {
            btnSubmitFinal.addEventListener('click', function () {
                const checkedUnits = Array.from(document.querySelectorAll('.unit-checkbox:checked')).map(cb => cb.value);
                const tipoTransaccion = document.getElementById('transType') ? document.getElementById('transType').value : 'income';

                if (tipoTransaccion !== 'expense' && checkedUnits.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No hay unidades',
                        text: 'Debes seleccionar al menos una unidad para procesar el registro.',
                        confirmButtonColor: '#3b82f6'
                    });
                    return;
                }

                const categoriaId = document.getElementById('transCategory') ? document.getElementById('transCategory').value : '';
                const destinoFinanciero = document.getElementById('destino_financiero') ? document.getElementById('destino_financiero').value : 'fondo_reserva';
                const transModeInput = document.getElementById('transMode');
                const transMode = transModeInput ? transModeInput.value : 'charge';

                const tipoTexto = (tipoTransaccion === 'income') ? 'Ingreso' : 'Gasto';
                const categoriaTexto = document.getElementById('txtCategoria') ? document.getElementById('txtCategoria').textContent.trim() : 'Sin Categoría';
                const numAdjuntos = (attachmentInput && attachmentInput.files) ? attachmentInput.files.length : 0;

                // Amount and Date
                const amountInput = document.querySelector('input[type="number"]');
                const amountVal = amountInput ? amountInput.value : '';

                const dateInput = document.getElementById('premiumDatePicker');
                const dateVal = dateInput ? dateInput.value : '';
                // Formatear fecha para el modal (e.g. 13 de marzo de 2026)
                let dateFormatted = dateVal;
                try {
                    const d = new Date(dateVal + 'T12:00:00');
                    const options = { day: 'numeric', month: 'long', year: 'numeric' };
                    dateFormatted = d.toLocaleDateString('es-ES', options);
                } catch (e) { }

                const descInput = document.querySelector('textarea');
                const descVal = descInput ? descInput.value : '';

                // Unidades y Cálculos
                let totalGeneral = 0;
                let unitsHtml = '';

                // Construir mapa de monto por unidad desde cargos pendientes seleccionados
                // Mapear por unit_id (no por label) para mayor robustez
                const unitAmountMapById = {};
                if (transMode === 'payment' && document.getElementById('pendingChargesContainer').style.display === 'block') {
                    document.querySelectorAll('.pc-checkbox-core:checked').forEach(pcCb => {
                        const chargeId = pcCb.value;
                        const input = document.getElementById('pcInput_' + chargeId);
                        const pcAmount = parseFloat(input.value) || 0;
                        // Buscar el unit_id en loadedCharges
                        const chargeData = loadedCharges.find(c => String(c.id) === String(chargeId));
                        if (chargeData && chargeData.unit_id) {
                            const uid = String(chargeData.unit_id);
                            if (!unitAmountMapById[uid]) unitAmountMapById[uid] = 0;
                            unitAmountMapById[uid] += pcAmount;
                        }
                    });
                }

                const selectedUnitsInfo = Array.from(document.querySelectorAll('.unit-checkbox:checked')).map(cb => {
                    const unitId = cb.value;
                    const row = cb.closest('.unit-item-row');
                    const name = row.querySelector('.unit-name').textContent.trim();
                    const feeText = row.querySelector('.unit-fee').textContent.trim();
                    const unitFee = parseFloat(feeText.replace(/[^\d.]/g, '')) || 0;

                    let finalMonto;
                    if (transMode === 'payment' && Object.keys(unitAmountMapById).length > 0) {
                        // En modo pago con cargos pendientes, usar solo el monto de cargos seleccionados para esta unidad
                        finalMonto = unitAmountMapById[unitId] || 0;
                    } else {
                        finalMonto = amountVal ? parseFloat(amountVal) : unitFee;
                    }
                    totalGeneral += finalMonto;

                    return `
                    <div style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem;">
                        <span style="color: #3F67AC;">${name}</span>
                        <span style="font-weight: 600; color: #1e293b;">$${finalMonto.toLocaleString('en-US', { minimumFractionDigits: 2 })}</span>
                    </div>
                `;
                });

                const modoTexto = (transMode === 'charge') ? 'Crear un Cargo' : (transMode === 'payment' ? 'Registrar un Pago' : 'Cargo y Pago simultáneo');

                Swal.fire({
                    title: '<span style="font-size: 1.25rem; font-weight: 700; color: #1e293b;">Confirmar Creación de Cargos</span>',
                    html: `
                    <p style="color: #64748b; font-size: 0.85rem; margin-top: -10px; margin-bottom: 20px;">Por favor revise los detalles antes de crear los cargos</p>
                    
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; text-align: left; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                        <h6 style="font-weight: 700; font-size: 1.1rem; margin-bottom: 1.2rem; color: #1e293b;">Resumen</h6>
                        
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <span style="color: #64748b;">Tipo de Transacción:</span>
                                <span style="font-weight: 500; color: #1e293b;">${tipoTexto}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <span style="color: #64748b;">Categoría:</span>
                                <span style="font-weight: 500; color: #1e293b;">${categoriaTexto}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <span style="color: #64748b;">Fecha de Vencimiento:</span>
                                <span style="font-weight: 500; color: #1e293b;">${dateFormatted}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <span style="color: #64748b;">Recibos Adjuntos:</span>
                                <span style="font-weight: 500; color: #1e293b;">${numAdjuntos}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                                <span style="color: #64748b;">Unidades Seleccionadas:</span>
                                <span style="font-weight: 500; color: #1e293b;">${tipoTransaccion === 'expense' ? 'Condominio General' : checkedUnits.length}</span>
                            </div>
                            
                            <div style="margin-top: 0.5rem; padding-top: 1rem; border-top: 2px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 700; color: #1e293b; font-size: 0.95rem;">Monto Total:</span>
                                <span style="font-weight: 700; color: #10b981; font-size: 1.1rem;">$${tipoTransaccion === 'expense' ? parseFloat(amountVal || 0).toLocaleString('en-US', { minimumFractionDigits: 2 }) : totalGeneral.toLocaleString('en-US', { minimumFractionDigits: 2 })}</span>
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 1.5rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; display: ${tipoTransaccion === 'expense' ? 'none' : 'block'};">
                        <div style="display: flex; justify-content: space-between; padding: 0.6rem 1rem; background: #f1f5f9; color: #3F67AC; font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.025em;">
                            <span>Unidad</span>
                            <span>Monto</span>
                        </div>
                        <div style="max-height: 150px; overflow-y: auto; padding: 0 1rem;">
                            ${selectedUnitsInfo.join('')}
                        </div>
                    </div>
                `,
                    width: '500px',
                    showCancelButton: true,
                    confirmButtonColor: '#238b71ff',
                    cancelButtonColor: '#ffffff',
                    confirmButtonText: '<i class="bi bi-save me-2"></i> Confirmar y Crear Cargos',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    customClass: {
                        container: 'premium-swal-container',
                        popup: 'premium-swal-popup',
                        confirmButton: 'premium-swal-confirm',
                        cancelButton: 'premium-swal-cancel'
                    },
                    buttonsStyling: false,
                    didOpen: () => {
                        // Estilos adicionales para los botones de Swal (ya que quitamos styling default)
                        const confirmBtn = Swal.getConfirmButton();
                        const cancelBtn = Swal.getCancelButton();

                        confirmBtn.style.padding = '0.75rem 1.5rem';
                        confirmBtn.style.borderRadius = '8px';
                        confirmBtn.style.fontWeight = '600';
                        confirmBtn.style.fontSize = '0.9rem';
                        confirmBtn.style.backgroundColor = '#238b71ff';
                        confirmBtn.style.color = '#fff';
                        confirmBtn.style.border = 'none';
                        confirmBtn.style.cursor = 'pointer';
                        confirmBtn.style.marginLeft = '10px';

                        cancelBtn.style.padding = '0.75rem 1.5rem';
                        cancelBtn.style.borderRadius = '8px';
                        cancelBtn.style.fontWeight = '600';
                        cancelBtn.style.fontSize = '0.9rem';
                        cancelBtn.style.backgroundColor = '#fff';
                        cancelBtn.style.color = '#1e293b';
                        cancelBtn.style.border = '1px solid #cbd5e1';
                        cancelBtn.style.cursor = 'pointer';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {

                        // Show loading
                        Swal.fire({
                            title: 'Procesando...',
                            text: 'Generando los registros financieros',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        const formData = new FormData();
                        formData.append('transType', tipoTransaccion);
                        formData.append('categoryId', categoriaId);
                        formData.append('destino', destinoFinanciero);
                        formData.append('transMode', transMode);
                        formData.append('amount', amountVal);
                        formData.append('date', dateVal);
                        formData.append('description', descVal);

                        const paymentMethod = document.getElementById('paymentMethod') ? document.getElementById('paymentMethod').value : '';
                        if (transMode !== 'charge' || tipoTransaccion === 'expense') {
                            formData.append('paymentMethod', paymentMethod);
                        }

                        // Append unit IDs array
                        formData.append('unitIds', JSON.stringify(checkedUnits));

                        // Lógica de Pagos a Cuotas Múltiples
                        let paid_charges = [];
                        if (document.getElementById('pendingChargesContainer').style.display === 'block') {
                            document.querySelectorAll('.pc-checkbox-core:checked').forEach(cb => {
                                const input = document.getElementById('pcInput_' + cb.value);
                                paid_charges.push({
                                    charge_id: cb.value,
                                    amount: parseFloat(input.value) || 0
                                });
                            });
                        }
                        formData.append('paidCharges', JSON.stringify(paid_charges));

                        // Append multiple attachments if they exist
                        if (attachmentInput && attachmentInput.files.length > 0) {
                            Array.from(attachmentInput.files).forEach(file => {
                                formData.append('attachments[]', file);
                            });
                        }

                        fetch('<?= site_url('admin/finanzas/guardar-registro') ?>', {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === 'success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Registros Creados!',
                                        text: data.message,
                                        confirmButtonColor: '#3b82f6'
                                    }).then(() => {
                                        // Limpiar checks
                                        checkboxes.forEach(cb => cb.checked = false);
                                        updateCount();
                                        // Opcionalmente recargar o limpiar form
                                        if (amountInput) amountInput.value = '';
                                        if (descInput) descInput.value = '';
                                        window.location.href = '<?= base_url('admin/finanzas/panel') ?>';
                                    });
                                } else {
                                    Swal.fire('Error', data.message || 'Error al procesar.', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire('Error', 'Ocurrió un error en la solicitud.', 'error');
                            });
                    }
                });
            });
        }

    });
</script>
<?= $this->endSection() ?>