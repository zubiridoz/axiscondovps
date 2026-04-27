<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    /* variables y base = Premium SaaS */
    :root {
        --fin-bg: #EEF1F9;
        --fin-card-bg: #ffffff;
        --fin-text-main: #1e293b;
        --fin-text-muted: #64748b;
        --fin-border: #e2e8f0;
        --fin-primary: #232d3f;
        /* Match main sidebar */
        --fin-success: #10b981;
        --fin-warning: #f59e0b;
        --fin-danger: #ef4444;
        --fin-border-radius: 12px;
        --fin-shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.05);
        --fin-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    body {
        margin: 0;
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

    /* KPIs Row */
    .kpi-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .kpi-card {
        background: var(--fin-card-bg);
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-border-radius);
        padding: 1.5rem;
        position: relative;
        box-shadow: var(--fin-shadow-sm);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .kpi-title {
        color: var(--fin-text-muted);
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }

    .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        color: var(--fin-text-main);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .kpi-subtitle {
        font-size: 0.75rem;
        color: var(--fin-text-muted);
        margin-top: 0.25rem;
    }

    .kpi-icon {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        font-size: 1.25rem;
    }

    .kpi-card.ok .kpi-value,
    .kpi-card.ok .kpi-icon {
        color: var(--fin-success);
    }

    .kpi-card.debt-count .kpi-value,
    .kpi-card.debt-count .kpi-icon {
        color: var(--fin-danger);
    }

    .kpi-card.debt-money .kpi-value,
    .kpi-card.debt-money .kpi-icon {
        color: var(--fin-danger);
    }

    /* Controls Row */
    .controls-row {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .btn-custom {
        padding: 0.6rem 1.2rem;
        font-size: 0.875rem;
        font-weight: 500;
        border-radius: 6px;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s;
    }

    .btn-toggle {
        background: var(--fin-card-bg);
        color: var(--fin-text-main);
        border-color: var(--fin-border);
    }

    .btn-toggle:hover {
        background: #f1f5f9;
    }

    .btn-action {
        background: var(--fin-text-muted);
        color: white;
    }

    .btn-action:hover {
        background: var(--fin-text-main);
    }

    /* Views Container */
    .view-container {
        background: var(--fin-card-bg);
        border: 1px solid var(--fin-border);
        border-radius: var(--fin-border-radius);
        padding: 2rem;
        box-shadow: var(--fin-shadow-sm);
        min-height: 400px;
    }

    .view-header {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--fin-primary);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* ===== Grid View (Cuadrícula) ===== */
    #gridView {
        display: block;
    }

    .tower-group {
        margin-bottom: 2rem;
    }

    .tower-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--fin-primary);
        text-transform: uppercase;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        border-bottom: 1px dashed var(--fin-border);
        padding-bottom: 0.5rem;
    }

    .tower-count {
        background: #f1f5f9;
        color: var(--fin-text-muted);
        font-size: 0.75rem;
        padding: 0.1rem 0.5rem;
        border-radius: 99px;
        text-transform: lowercase;
    }

    .grid-units {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1rem;
    }

    .unit-card {
        border-radius: 8px;
        padding: 1rem;
        position: relative;
        transition: all 0.2s ease-in-out;
        cursor: pointer;
    }

    .unit-card.debt-card {
        background: #fffbeb;
        border: 1px solid #fde68a;
    }

    .unit-card.debt-card:hover {
        background: #fef08a;
        /* Amarillo más fuerte */
        border-color: #facc15;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .unit-card.ok-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #10b981;
    }

    .unit-card.ok-card:hover {
        background: #f1f5f9;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .unit-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .unit-card-name {
        font-weight: 600;
        color: var(--fin-text-main);
        font-size: 0.9rem;
    }

    .unit-card-floor {
        font-size: 0.75rem;
        color: var(--fin-text-muted);
    }

    .unit-card-debt {
        font-size: 1rem;
        font-weight: 600;
        color: var(--fin-danger);
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .unit-card-debt i {
        font-size: 0.85rem;
        color: var(--fin-warning);
    }

    /* ===== Table View (Tabla) ===== */
    #tableView {
        display: none;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom th {
        text-align: left;
        padding: 1rem;
        font-size: 0.85rem;
        color: var(--fin-text-muted);
        border-bottom: 1px solid var(--fin-border);
        font-weight: 500;
        background: #f8fafc;
    }

    .table-custom th i {
        font-size: 0.7rem;
        margin-left: 0.2rem;
    }

    .table-custom td {
        padding: 1rem;
        font-size: 0.9rem;
        color: var(--fin-text-main);
        border-bottom: 1px solid var(--fin-border);
        vertical-align: middle;
    }

    .table-custom tbody tr:hover {
        background: #f8fafc;
    }

    .td-unit {
        font-weight: 600;
    }

    .td-floor {
        font-size: 0.75rem;
        color: var(--fin-text-muted);
        margin-left: 0.5rem;
    }

    .td-debt {
        color: var(--fin-danger);
        font-weight: 600;
    }

    .td-action {
        color: var(--fin-text-muted);
        cursor: pointer;
        text-align: center;
    }

    .td-action:hover {
        color: var(--fin-primary);
    }

    @media (max-width: 900px) {
        .kpi-row {
            grid-template-columns: 1fr;
        }
    }

    /* Modal Resumen Financiero */
    .rf-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .5);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity .2s;
    }

    .rf-modal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    .rf-modal {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        width: 850px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, .2);
        transform: translateY(-10px);
        transition: transform .2s;
        position: relative;
    }

    .rf-modal-overlay.open .rf-modal {
        transform: translateY(0);
    }

    .rf-modal-close {
        position: absolute;
        top: 1.5rem;
        right: 1.5rem;
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: var(--fin-text-muted);
    }

    .rf-modal h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0 0 1.5rem;
        color: var(--fin-text-main);
    }

    .rf-kpis {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .rf-kpi-card {
        border: 1px solid var(--fin-border);
        border-radius: 8px;
        padding: 1.25rem;
    }

    .rf-kpi-label {
        font-size: .8rem;
        color: var(--fin-text-muted);
        margin-bottom: .4rem;
    }

    .rf-kpi-value {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: .25rem;
        line-height: 1.2;
    }

    .rf-kpi-sub {
        font-size: .75rem;
        display: flex;
        align-items: center;
        gap: .35rem;
    }

    .rf-success .rf-kpi-value {
        color: var(--fin-success);
    }

    .rf-success .rf-kpi-sub {
        color: var(--fin-success);
    }

    .rf-danger .rf-kpi-value {
        color: var(--fin-danger);
    }

    .rf-danger .rf-kpi-sub {
        color: var(--fin-danger);
    }

    .rf-neutral .rf-kpi-value {
        color: var(--fin-text-main);
    }

    .rf-neutral .rf-kpi-sub {
        color: var(--fin-text-muted);
    }

    .rf-tabs-header {
        display: inline-flex;
        background: #f8fafc;
        border-radius: 8px;
        padding: .25rem;
        margin-bottom: 1.5rem;
    }

    .rf-tab-btn {
        background: transparent;
        border: 1px solid transparent;
        color: var(--fin-text-muted);
        padding: .5rem 1.25rem;
        border-radius: 6px;
        font-size: .85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .rf-tab-btn.active {
        background: white;
        border-color: var(--fin-primary);
        color: var(--fin-text-main);
        box-shadow: 0 1px 2px rgba(0, 0, 0, .05);
    }

    .rf-tab-pane {
        display: none;
    }

    .rf-tab-pane.active {
        display: block;
    }

    .rf-table-wrapper {
        border: 1px solid var(--fin-border);
        border-radius: 8px;
        overflow: hidden;
    }

    .rf-table {
        width: 100%;
        border-collapse: collapse;
        font-size: .85rem;
    }

    .rf-table th {
        background: #f8fafc;
        color: var(--fin-text-muted);
        font-weight: 500;
        padding: .85rem 1rem;
        text-align: left;
        border-bottom: 1px solid var(--fin-border);
    }

    .rf-table td {
        padding: .85rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        color: var(--fin-text-main);
        vertical-align: middle;
    }

    .rf-table tr:last-child td {
        border-bottom: none;
    }

    .badge-cargo {
        background: #fee2e2;
        color: #b91c1c;
        padding: .2rem .6rem;
        border-radius: 12px;
        font-size: .7rem;
        font-weight: 600;
    }

    .badge-pago {
        background: #d1fae5;
        color: #065f46;
        padding: .2rem .6rem;
        border-radius: 12px;
        font-size: .7rem;
        font-weight: 600;
    }

    .badge-completed {
        background: #475569;
        color: white;
        padding: .25rem .75rem;
        border-radius: 20px;
        font-size: .75rem;
        font-weight: 500;
    }

    .text-danger {
        color: #ef4444 !important;
    }

    .text-success {
        color: #10b981 !important;
    }

    .rf-modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: .75rem;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid var(--fin-border);
    }

    .btn-cerrar {
        background: white;
        border: 1px solid var(--fin-border);
        color: var(--fin-text-main);
        padding: .5rem 1.2rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: .85rem;
        font-weight: 500;
    }

    .btn-mora {
        background: var(--fin-warning);
        border: none;
        color: white;
        padding: .5rem 1.2rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: .85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: .4rem;
    }

    /* Premium Tooltips for Action Icons */
    .action-icons {
        white-space: nowrap;
    }

    .action-icons i {
        position: relative;
        display: inline-block;
        padding: 4px;
        outline: none;
        transition: color 0.2s;
    }

    .action-icons i:hover {
        color: var(--fin-primary) !important;
    }

    .action-icons i.text-danger:hover {
        color: var(--fin-danger) !important;
    }

    /* Force SweetAlert2 above RF modal (z-index: 9999) */
    .swal2-container {
        z-index: 10100 !important;
    }

    .action-btn[data-tooltip]::after {
        content: attr(data-tooltip);
        opacity: 0;
        visibility: hidden;
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translate(-50%, 5px);
        background: #1e293b;
        color: #fff;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 500;
        white-space: nowrap;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        z-index: 100;
        transition: all 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        pointer-events: none;
    }

    .action-btn[data-tooltip]:hover::after {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, 0);
    }

    /* Edit Transaction Modal (mismo estilo que Movimientos) */
    .ep-select,
    .ep-input {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #334155;
        outline: none;
        transition: border-color 0.2s;
        background: #fff;
        box-sizing: border-box;
        font-family: inherit;
    }

    .ep-select:focus,
    .ep-input:focus {
        border-color: #3b82f6;
    }

    .ep-btn-cancel {
        padding: 0.55rem 1.25rem;
        background: #fff;
        border: 1px solid #cbd5e1;
        color: #334155;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .ep-btn-cancel:hover {
        background: #f8fafc;
    }

    .ep-btn-save {
        padding: 0.55rem 1.5rem;
        background: #475569;
        border: none;
        color: #fff;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .ep-btn-save:hover {
        background: #334155;
    }

    .ep-btn-delete {
        padding: 0.55rem 1.25rem;
        background: #ef4444;
        border: none;
        color: #fff;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
    }

    .ep-btn-delete:hover {
        background: #dc2626;
    }

    /* Custom Premium Category Dropdown */
    .rf-custom-select {
        width: 100%;
        padding: 0.6rem 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #334155;
        background: #fff;
        cursor: pointer;
        display: flex;
        align-items: center;
        box-sizing: border-box;
        transition: all 0.2s;
    }

    .rf-custom-select:hover {
        border-color: #94a3b8;
    }

    .rf-custom-select.active {
        border-color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
    }

    .rf-custom-chevron {
        margin-left: auto;
        color: #94a3b8;
        font-size: 0.8rem;
        transition: transform 0.2s;
    }

    .rf-custom-select.active .rf-custom-chevron {
        transform: rotate(180deg);
    }

    .rf-custom-dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        right: 0;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        display: none;
        overflow: hidden;
        transform: translateY(-5px);
        opacity: 0;
        transition: all 0.2s;
        min-width: 100%;
    }

    .rf-custom-dropdown.open {
        display: block;
        transform: translateY(0);
        opacity: 1;
    }

    .rf-dropdown-header {
        background: #f8fafc;
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
    }

    .rf-cat-option {
        padding: 0.6rem 1rem;
        cursor: pointer;
        font-size: 0.85rem;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        transition: background 0.2s;
    }

    .rf-cat-option:hover {
        background: #f1f5f9;
    }

    .rf-cat-option.selected {
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 500;
    }

    .rf-cat-option i {
        font-size: 0.95rem;
        opacity: 0.7;
    }

    .rf-cat-option.selected i {
        opacity: 1;
        color: #3b82f6;
    }

    .rf-cat-option-check {
        margin-left: auto;
        opacity: 0;
        color: #3b82f6;
        font-size: 1rem;
        transition: opacity 0.2s;
    }

    .rf-cat-option.selected .rf-cat-option-check {
        opacity: 1;
    }

    /* Delete Confirmation Modal */
    .delete-warning-box {
        background: #fff5f5;
        border: 1px solid #feb2b2;
        border-radius: 8px;
        padding: 1rem;
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }

    .delete-details-box {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.25rem;
        margin-bottom: 1.25rem;
    }

    .delete-details-row {
        display: flex;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .delete-details-label {
        font-weight: 700;
        width: 110px;
        color: #1e293b;
    }

    .delete-details-value {
        color: #1e293b;
        flex: 1;
    }

    .text-danger-soft {
        color: #e53e3e;
        font-size: 0.82rem;
        margin-bottom: 1.5rem;
    }

    /* Toast Notification */
    .rf-toast {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 11000;
        background: white;
        border-radius: 10px;
        padding: 1rem 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, .15);
        display: flex;
        align-items: center;
        gap: .75rem;
        transform: translateX(120%);
        transition: transform .35s cubic-bezier(.22, 1, .36, 1);
        border-left: 4px solid var(--fin-success);
        max-width: 380px;
    }

    .rf-toast.show {
        transform: translateX(0);
    }

    .rf-toast .toast-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #d1fae5;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #059669;
        font-size: .9rem;
        flex-shrink: 0;
    }

    .rf-toast .toast-body {
        flex: 1;
    }

    .rf-toast .toast-title {
        font-weight: 600;
        font-size: .88rem;
        color: var(--fin-text-main);
    }

    .rf-toast .toast-msg {
        font-size: .78rem;
        color: var(--fin-text-muted);
        margin-top: 1px;
    }

    /* Modal Exportación */
    .exp-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s ease;
        backdrop-filter: blur(2px);
    }

    .exp-modal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    .exp-modal {
        background: white;
        border-radius: 12px;
        padding: 2.2rem;
        width: 500px;
        max-width: 95vw;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        transform: translateY(15px);
        transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        position: relative;
    }

    .exp-modal-overlay.open .exp-modal {
        transform: translateY(0);
    }

    .exp-close {
        position: absolute;
        top: 1.25rem;
        right: 1.25rem;
        background: transparent;
        border: none;
        font-size: 1.2rem;
        color: var(--fin-text-muted);
        cursor: pointer;
        transition: color 0.2s;
    }

    .exp-close:hover {
        color: var(--fin-danger);
    }

    .exp-modal h3 {
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--fin-text-main);
        margin: 0 0 0.4rem 0;
    }

    .exp-desc {
        font-size: 0.85rem;
        color: var(--fin-text-muted);
        margin: 0 0 1.5rem 0;
        line-height: 1.4;
    }

    .exp-summary {
        margin-bottom: 1.5rem;
    }

    .exp-summary-title {
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--fin-text-main);
        margin-bottom: 0.75rem;
    }

    .exp-summary-row {
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        color: var(--fin-text-main);
    }

    .exp-summary-row .lbl {
        font-weight: 500;
        margin-right: 0.25rem;
        color: var(--fin-text-main);
    }

    .exp-summary-row .val {
        color: var(--fin-text-muted);
    }

    .exp-form label {
        display: block;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--fin-text-main);
        margin-bottom: 0.5rem;
    }

    .exp-select-wrapper {
        position: relative;
        margin-bottom: 2rem;
    }

    .exp-select-wrapper select {
        width: 100%;
        padding: 0.7rem 1rem;
        appearance: none;
        border: 1px solid var(--fin-border);
        border-radius: 8px;
        font-size: 0.9rem;
        color: var(--fin-text-main);
        outline: none;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        transition: border-color 0.2s;
    }

    .exp-select-wrapper select:focus {
        border-color: var(--fin-primary);
    }

    .exp-select-wrapper::after {
        content: '\F282';
        font-family: 'bootstrap-icons';
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--fin-text-muted);
        pointer-events: none;
        font-size: 0.8rem;
    }

    .exp-actions {
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
    }

    .btn-cancelar {
        background: white;
        border: 1px solid var(--fin-border);
        color: var(--fin-text-main);
        padding: 0.55rem 1.25rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
    }

    .btn-cancelar:hover {
        background: #f8fafc;
    }

    .btn-exportar {
        background: #334155;
        border: 1px solid #334155;
        color: white;
        padding: 0.55rem 1.5rem;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .btn-exportar:hover {
        background: #1e293b;
        border-color: #1e293b;
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
                    <h2 class="cc-hero-title">Morosidad</h2>
                    <i class="bi bi-chevron-right"></i>
                    Unidades con pagos vencidos

                </div>
            </div>
            <div class="toolbar-right">



                <button class="btn-custom btn-toggle" id="btnToggleView">
                    <i class="bi bi-list-task"></i> <span id="lblToggle">Vista de Tabla</span>
                </button>
                <button class="cc-hero-btn" onclick="openExportModal()">
                    <i class="bi bi-download"></i> Exportar Morosidad
                </button>
            </div>
        </div>
        <!-- ── END Hero ── -->


        <!-- KPIs Row -->
        <div class="kpi-row">
            <div class="kpi-card ok">
                <i class="bi bi-check-circle kpi-icon"></i>
                <div class="kpi-title">Unidades al Corriente</div>
                <div class="kpi-value"><?= esc($kpis['units_ok']) ?></div>
                <div class="kpi-subtitle">Unidades al día con sus pagos</div>
            </div>

            <div class="kpi-card debt-count">
                <i class="bi bi-exclamation-triangle kpi-icon"></i>
                <div class="kpi-title">Total de Unidades Morosas</div>
                <div class="kpi-value"><?= esc($kpis['units_debt']) ?></div>
                <div class="kpi-subtitle">Unidades con pagos morosos</div>
            </div>

            <div class="kpi-card debt-money">
                <i class="bi bi-currency-dollar kpi-icon text-danger" style="font-weight:bold; font-style:normal;">$</i>
                <div class="kpi-title">Monto Total Moroso</div>
                <div class="kpi-value">MX$<?= number_format($kpis['total_overdue'], 2) ?></div>
                <div class="kpi-subtitle">Total pendiente para unidades morosas</div>
            </div>
        </div>



        <!-- Views Container -->
        <div class="view-container">

            <!-- GRID VIEW -->
            <div id="gridView">
                <div class="view-header">Estado de Pago de Todas las Unidades</div>

                <?php if (empty($grouped_units)): ?>
                    <p class="text-muted">No existen unidades morosas actualmente.</p>
                <?php else: ?>
                    <?php foreach ($grouped_units as $towerName => $units): ?>

                        <?php
                        // Mostrar todas las torres, cuenten cuántas están en deuda
                        $countDebt = 0;
                        foreach ($units as $u) {
                            if ($u['debt_vencida'] > 0.01) {
                                $countDebt++;
                            }
                        }
                        ?>

                        <div class="tower-group">
                            <div class="tower-title">
                                <?= esc($towerName) ?> <span class="tower-count" <?= $countDebt == 0 ? 'style="background:#f0fdf4; color:#16a34a; border: 1px solid #bbf7d0;"' : '' ?>><?= $countDebt ?> morosas</span>
                            </div>
                            <div class="grid-units">
                                <?php foreach ($units as $u): ?>
                                    <?php if ($u['debt_vencida'] > 0.01): ?>
                                        <div class="unit-card debt-card" onclick="openUnitModal(<?= $u['id'] ?>)">
                                            <div class="unit-card-header">
                                                <span class="unit-card-name"><?= esc($u['label']) ?></span>
                                                <span class="unit-card-floor">Piso <?= esc($u['floor']) ?></span>
                                            </div>
                                            <div class="unit-card-debt">
                                                <i class="bi bi-exclamation-circle"></i> $<?= number_format($u['debt'], 2) ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="unit-card ok-card" onclick="openUnitModal(<?= $u['id'] ?>)">
                                            <div class="unit-card-header">
                                                <span class="unit-card-name"><?= esc($u['label']) ?></span>
                                                <span class="unit-card-floor">Piso <?= esc($u['floor']) ?></span>
                                            </div>
                                            <div class="unit-card-debt" style="color: #10b981; font-weight: 600; font-size: 0.95rem;">
                                                <?php if ($u['debt'] < -0.01): ?>
                                                    <span style="color: #059669"><i class="bi bi-star-fill"></i>
                                                        MX$<?= number_format(abs($u['debt']), 2) ?> (A favor)</span>
                                                <?php elseif ($u['debt'] > 0.01): ?>
                                                    <span style="color: #0284c7"><i class="bi bi-info-circle-fill"></i>
                                                        MX$<?= number_format($u['debt'], 2) ?> (Al corriente)</span>
                                                <?php else: ?>
                                                    <i class="bi bi-check-circle-fill"></i> Sin adeudos
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>

                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- TABLE VIEW -->
            <div id="tableView">
                <div class="view-header">Detalles de Unidades Morosas</div>

                <table class="table-custom">
                    <thead>
                        <tr>
                            <th style="width: 50%;">Unidad <i class="bi bi-arrow-up"></i></th>
                            <th style="width: 30%; text-align:center;">Saldo Pendiente <i
                                    class="bi bi-arrow-down-up"></i></th>
                            <th style="width: 20%; text-align:center;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($flat_units)): ?>
                            <tr>
                                <td colspan="3" class="text-muted text-center">No hay datos</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($flat_units as $u): ?>
                                <tr>
                                    <td>
                                        <span class="td-unit"><?= esc($u['label']) ?></span>
                                        <span class="td-floor">Piso <?= esc($u['floor']) ?></span>
                                    </td>
                                    <!-- Display the amount due or in favor for ALL units, color coded based on their status, this list prints all units -->
                                    <?php if ($u['debt_vencida'] > 0.01): ?>
                                        <td class="td-debt" style="text-align:center;">
                                            MX$<?= number_format($u['debt'], 2) ?>
                                        </td>
                                    <?php elseif ($u['debt'] > 0.01): ?>
                                        <td style="text-align:center; color: #0284c7; font-weight: 600; font-size: 0.9rem;">
                                            <i class="bi bi-info-circle-fill me-1"></i> MX$<?= number_format($u['debt'], 2) ?>
                                        </td>
                                    <?php elseif ($u['debt'] <= -0.01): ?>
                                        <td style="text-align:center; color: #059669; font-weight: 600; font-size: 0.9rem;">
                                            <i class="bi bi-star-fill me-1"></i> MX$<?= number_format(abs($u['debt']), 2) ?> (A
                                            favor)
                                        </td>
                                    <?php else: ?>
                                        <td style="text-align:center; color: #10b981; font-weight: 600; font-size: 0.9rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i> Sin adeudos
                                        </td>
                                    <?php endif; ?>
                                    <td class="td-action">
                                        <i class="bi bi-eye" style="cursor:pointer;"
                                            onclick="openUnitModal(<?= $u['id'] ?>)"></i>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

<!-- Modal Resumen Financiero -->
<div class="rf-modal-overlay" id="rfModal">
    <div class="rf-modal">
        <button class="rf-modal-close" onclick="closeUnitModal()">
            <i class="bi bi-x"></i>
        </button>
        <h3 id="rfModalTitle">Resumen Financiero: Unidad --</h3>

        <div class="rf-kpis">
            <div class="rf-kpi-card rf-success">
                <div class="rf-kpi-label">Total Pagado</div>
                <div class="rf-kpi-value" id="rfKpiPaid">$0.00</div>
                <div class="rf-kpi-sub">
                    <i class="bi bi-arrow-up-right"></i> <span id="rfKpiPaidSub">0 pagos realizados</span>
                </div>
            </div>
            <div class="rf-kpi-card rf-danger" id="rfCardPending">
                <div class="rf-kpi-label">Saldo Pendiente</div>
                <div class="rf-kpi-value" id="rfKpiPending">$0.00</div>
                <div class="rf-kpi-sub" id="rfKpiPendingSub">
                    <i class="bi bi-exclamation-circle"></i> Pago requerido
                </div>
            </div>
            <div class="rf-kpi-card rf-neutral">
                <div class="rf-kpi-label">Cuota Mensual</div>
                <div class="rf-kpi-value" id="rfKpiFee">$0.00</div>
                <div class="rf-kpi-sub">Tarifa actual</div>
            </div>
            <div class="rf-kpi-card rf-neutral">
                <div class="rf-kpi-label">Próximo Pago Vence</div>
                <div class="rf-kpi-value" id="rfKpiDue">--</div>
                <div class="rf-kpi-sub">
                    <i class="bi bi-calendar"></i> <span id="rfKpiDueSub">-- días</span>
                </div>
            </div>
        </div>

        <div class="rf-tabs-header">
            <button class="rf-tab-btn active" onclick="switchRfTab('resumen')">Resumen</button>
            <button class="rf-tab-btn" onclick="switchRfTab('historial')">Historial de Pagos</button>
        </div>

        <div class="rf-tab-pane active" id="rfTab-resumen">
            <div class="rf-table-wrapper">
                <table class="rf-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Saldo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="rfBodyResumen">
                        <!-- Dynamic -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rf-tab-pane" id="rfTab-historial">
            <div class="rf-table-wrapper">
                <table class="rf-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Método de Pago</th>
                        </tr>
                    </thead>
                    <tbody id="rfBodyHistorial">
                        <!-- Dynamic -->
                    </tbody>
                </table>
            </div>
        </div>

        <div class="rf-modal-footer">

            <button class="btn-cerrar" onclick="closeUnitModal()">Cerrar</button>
        </div>
    </div>
</div>

<!-- Modal Exportar Reporte de Morosidad -->
<div class="exp-modal-overlay" id="expModal">
    <div class="exp-modal">
        <button class="exp-close" onclick="closeExportModal()">
            <i class="bi bi-x"></i>
        </button>
        <h3>Exportar Reporte de Morosidad</h3>
        <p class="exp-desc">Exportar un reporte de todas las unidades con o sin pagos morosos en formato CSV o PDF</p>

        <div class="exp-summary">
            <div class="exp-summary-title">Resumen del Reporte</div>
            <div class="exp-summary-row">
                <span class="lbl">Total de Unidades Morosas:</span>
                <span class="val"><?= esc($kpis['units_debt']) ?></span>
            </div>
            <div class="exp-summary-row">
                <span class="lbl">Monto Total:</span>
                <span class="val">MX$<?= number_format($kpis['total_overdue'], 2) ?></span>
            </div>
        </div>

        <div class="exp-form">
            <label>Formato de Exportación</label>
            <div class="exp-select-wrapper">
                <select id="exportFormat">
                    <option value="csv">Excel (CSV)</option>
                    <option value="pdf">Documento PDF</option>
                </select>
            </div>
        </div>

        <div class="exp-actions">
            <button class="btn-cancelar" onclick="closeExportModal()">Cancelar</button>
            <button class="btn-exportar" onclick="submitExport()">Exportar</button>
        </div>
    </div>
</div>

<!-- Modal Editar Transacción (estilo Movimientos Mensuales) -->
<div id="rfEditOverlay"
    style="display:none; position:fixed; inset:0; z-index:10050; background:rgba(0,0,0,.45); justify-content:center; align-items:center;">
    <div
        style="background:#fff; border-radius:12px; width:520px; max-width:95vw; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,.2); padding:2rem;">
        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h3 style="margin:0; font-size:1.15rem; font-weight:600; color:#1e293b;" id="rfEditTitle">Editar Transacción
            </h3>
            <button type="button" onclick="closeRfEdit()"
                style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.1rem;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <form id="rfEditForm">
            <input type="hidden" id="rf-edit-id">

            <!-- Categoría (Custom Dropdown Premium) -->
            <div style="font-size:0.8rem; color:#475569; margin-bottom:0.4rem;">Categoría</div>
            <div style="position:relative; margin-bottom:1.25rem;">
                <input type="hidden" id="rf-edit-cat">
                <div class="rf-custom-select" id="rfCatTrigger" onclick="toggleRfCat()">
                    <span id="rfCatIcon"><i class="bi bi-tag"></i></span>
                    <span id="rfCatLabel"
                        style="flex:1; margin-left:8px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">Seleccionar
                        categoría</span>
                    <i class="bi bi-chevron-down rf-custom-chevron"></i>
                </div>
                <div class="rf-custom-dropdown" id="rfCatDropdown">
                    <div class="rf-dropdown-header" id="rfCatDropdownHeader">Categorías</div>
                    <div id="rfCatList"></div>
                </div>
            </div>

            <!-- Monto y Fecha (2 columnas) -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
                <div>
                    <div style="font-size:0.8rem; color:#475569; margin-bottom:0.4rem;">Monto</div>
                    <div style="position:relative;">
                        <span
                            style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#64748b; font-size:0.9rem;">$</span>
                        <input type="number" id="rf-edit-amount" class="ep-input" style="padding-left:1.75rem;"
                            step="0.01">
                    </div>
                </div>
                <div>
                    <div style="font-size:0.8rem; color:#475569; margin-bottom:0.4rem;">Fecha de Transacción</div>
                    <div style="position:relative;">
                        <span
                            style="position:absolute; left:0.75rem; top:50%; transform:translateY(-50%); color:#64748b;"><i
                                class="bi bi-calendar3"></i></span>
                        <input type="text" id="rf-edit-date" class="ep-input"
                            style="padding-left:2.25rem; cursor:pointer; background:#fff;" readonly
                            placeholder="Seleccionar Fecha">
                    </div>
                </div>
            </div>

            <!-- Descripción -->
            <div style="font-size:0.8rem; color:#475569; margin-bottom:0.4rem;">Descripción</div>
            <div style="margin-bottom:1.5rem;">
                <textarea id="rf-edit-desc" class="ep-input" rows="3" style="resize:vertical;"></textarea>
            </div>

            <!-- Adjuntos -->
            <div style="font-size:0.8rem; color:#475569; margin-bottom:0.4rem;"><i class="bi bi-paperclip"></i> Adjuntos
            </div>
            <div style="margin-bottom:2rem;">
                <input type="file" id="rf-edit-file" style="display:none;" accept="image/*,.pdf">
                <div onclick="document.getElementById('rf-edit-file').click()"
                    style="border:2px dashed #e2e8f0; border-radius:10px; padding:1.5rem; text-align:center; cursor:pointer; transition:border-color .2s;"
                    onmouseover="this.style.borderColor='#94a3b8'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <div style="color:#94a3b8; font-size:1.5rem; margin-bottom:0.4rem;"><i
                            class="bi bi-cloud-arrow-up"></i></div>
                    <div style="font-size:0.82rem; color:#475569;">Arrastre y suelte el archivo aquí, o haga clic para
                        seleccionar</div>
                    <div style="font-size:0.72rem; color:#94a3b8; margin-top:0.25rem;">Soportado: JPG, PNG, PDF (máx
                        10MB)</div>
                </div>
                <div id="rf-edit-file-preview" style="margin-top:0.5rem; font-size:0.8rem; color:#10b981;"></div>
            </div>

            <!-- Footer -->
            <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
                <button type="button" onclick="closeRfEdit()" class="ep-btn-cancel">Cancelar</button>
                <button type="button" id="rfEditSaveBtn" class="ep-btn-save">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notification -->
<div class="rf-toast" id="rfToast">
    <div class="toast-icon"><i class="bi bi-check-lg"></i></div>
    <div class="toast-body">
        <div class="toast-title" id="rfToastTitle">Éxito</div>
        <div class="toast-msg" id="rfToastMsg">Operación completada.</div>
    </div>
</div>

<!-- Modal Eliminar Transacción (Premium) -->
<div id="rfDeleteOverlay"
    style="display:none; position:fixed; inset:0; z-index:10050; background:rgba(0,0,0,.45); justify-content:center; align-items:center;">
    <div
        style="background:#fff; border-radius:12px; width:460px; max-width:95vw; box-shadow:0 20px 60px rgba(0,0,0,.2); padding:2rem;">
        <!-- Header -->
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
            <div style="display:flex; align-items:center; gap:0.75rem;">
                <i class="bi bi-exclamation-triangle" style="color:#f59e0b; font-size:1.25rem;"></i>
                <h3 style="margin:0; font-size:1.1rem; font-weight:600; color:#1e293b;">Eliminar</h3>
            </div>
            <button type="button" onclick="closeRfDelete()"
                style="background:none; border:none; color:#94a3b8; cursor:pointer; font-size:1.1rem;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Warning Box -->
        <div class="delete-warning-box">
            <i class="bi bi-info-circle" style="color:#e53e3e; font-size:1.1rem; margin-top:2px;"></i>
            <p style="margin:0; color:#c53030; font-size:0.88rem; line-height:1.4;">
                Esta acción no se puede deshacer. El saldo de la unidad se recalculará después de la eliminación.
            </p>
        </div>

        <!-- Details Label -->
        <div style="font-size:0.85rem; color:#64748b; margin-bottom:0.75rem; font-weight:500;">Detalles de la
            Transacción:</div>

        <!-- Details Box -->
        <div class="delete-details-box">
            <div class="delete-details-row">
                <div class="delete-details-label">Fecha:</div>
                <div class="delete-details-value" id="rfDelDate">--</div>
            </div>
            <div class="delete-details-row">
                <div class="delete-details-label">Descripción:</div>
                <div class="delete-details-value" id="rfDelDesc">--</div>
            </div>
            <div class="delete-details-row">
                <div class="delete-details-label">Monto:</div>
                <div class="delete-details-value" id="rfDelAmount">--</div>
            </div>
            <div class="delete-details-row">
                <div class="delete-details-label">Tipo:</div>
                <div class="delete-details-value" id="rfDelType">--</div>
            </div>
            <div class="delete-details-row">
                <div class="delete-details-label">Categoría:</div>
                <div class="delete-details-value" id="rfDelCat">--</div>
            </div>
        </div>

        <p class="text-danger-soft">
            Eliminar esta transacción la eliminará permanentemente del libro mayor de la cuenta y actualizará el saldo
            de la unidad.
        </p>

        <!-- Footer -->
        <div style="display:flex; justify-content:flex-end; gap:0.75rem;">
            <button type="button" onclick="closeRfDelete()" class="ep-btn-cancel">Cancelar</button>
            <button type="button" id="rfDeleteConfirmBtn" class="ep-btn-delete">Eliminar</button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const btnToggle = document.getElementById('btnToggleView');
        const lblToggle = document.getElementById('lblToggle');
        const iconToggle = btnToggle.querySelector('i');
        const gridView = document.getElementById('gridView');
        const tableView = document.getElementById('tableView');

        let isGrid = true;

        btnToggle.addEventListener('click', function (e) {
            e.preventDefault();
            if (isGrid) {
                // Cambiar a Tabla
                gridView.style.display = 'none';
                tableView.style.display = 'block';
                lblToggle.textContent = 'Vista de Cuadrícula';
                iconToggle.className = 'bi bi-grid-3x3-gap';
                isGrid = false;
            } else {
                // Cambiar a Cuadricula
                tableView.style.display = 'none';
                gridView.style.display = 'block';
                lblToggle.textContent = 'Vista de Tabla';
                iconToggle.className = 'bi bi-list-task';
                isGrid = true;
            }
        });
    });

    const BASE_URL = '<?= rtrim(base_url(), '/') ?>';
    let currentUnitId = null;

    const rfModal = document.getElementById('rfModal');

    function switchRfTab(tabId) {
        document.querySelectorAll('.rf-tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.rf-tab-pane').forEach(p => p.classList.remove('active'));
        event.currentTarget.classList.add('active');
        document.getElementById('rfTab-' + tabId).classList.add('active');
    }

    function closeUnitModal() {
        rfModal.classList.remove('open');
    }

    function openUnitModal(unitId) {
        currentUnitId = unitId;
        // Fetch data
        fetch(`<?= base_url('admin/finanzas/morosidad/api-unit-summary') ?>/${unitId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    populateRfModal(data.data);
                    rfModal.classList.add('open');
                } else {
                    alert('Error: ' + data.error);
                }
            })
            .catch(err => alert('Error fetching data'));
    }

    function populateRfModal(data) {
        const formatMXN = v => 'MX$' + parseFloat(v).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        const formatDate = dateStr => {
            const d = new Date(dateStr);
            const rawDate = d.toLocaleDateString('es-MX', { day: '2-digit', month: 'short', year: 'numeric' });
            return rawDate.replace(/\./g, '');
        };

        document.getElementById('rfModalTitle').textContent = `Resumen Financiero: Unidad ${data.unit_number}`;

        // KPIs
        document.getElementById('rfKpiPaid').textContent = formatMXN(data.total_paid_month);
        document.getElementById('rfKpiPaidSub').textContent = `${data.paid_count_month} pagos realizados`;

        const pendingCard = document.getElementById('rfCardPending');
        const pendingSub = document.getElementById('rfKpiPendingSub');
        const saldoVal = parseFloat(data.saldo_pendiente);

        // Modificar título del KPI
        const pendingValueEl = document.getElementById('rfKpiPending');
        const pendingLabelEl = pendingCard.querySelector('.rf-kpi-label');
        if (saldoVal < -0.01) {
            pendingLabelEl.textContent = 'Saldo a Favor';
            pendingValueEl.textContent = formatMXN(Math.abs(saldoVal));
            pendingCard.className = 'rf-kpi-card rf-success';
            pendingSub.innerHTML = '<i class="bi bi-star-fill"></i> Saldo a favor';
            pendingValueEl.style.color = 'var(--fin-success)';
        } else if (saldoVal > 0.01) {
            pendingLabelEl.textContent = 'Saldo Pendiente';
            pendingValueEl.textContent = formatMXN(saldoVal);
            pendingCard.className = 'rf-kpi-card rf-danger';
            pendingSub.innerHTML = '<i class="bi bi-exclamation-circle"></i> Pago requerido';
            pendingValueEl.style.color = '';
        } else {
            pendingLabelEl.textContent = 'Saldo Pendiente';
            pendingValueEl.textContent = formatMXN(0);
            pendingCard.className = 'rf-kpi-card rf-success';
            pendingSub.innerHTML = '<i class="bi bi-check-circle"></i> Al corriente';
            pendingValueEl.style.color = 'var(--fin-text-main)';
        }

        document.getElementById('rfKpiFee').textContent = formatMXN(data.cuota_mensual);
        document.getElementById('rfKpiDue').textContent = data.next_due_date;
        document.getElementById('rfKpiDueSub').textContent = `${data.days_left} días`;

        // Resumen Table
        const tbodyResumen = document.getElementById('rfBodyResumen');
        tbodyResumen.innerHTML = '';
        if (data.resumen.length === 0) {
            tbodyResumen.innerHTML = '<tr><td colspan="6" style="text-align:center;color:#64748b;">No hay transacciones este mes</td></tr>';
        } else {
            data.resumen.forEach(t => {
                const isCargo = t.type === 'charge';
                const montoClass = isCargo ? 'text-danger' : 'text-success';
                const badge = isCargo ? '<span class="badge-cargo">Cargo</span>' : '<span class="badge-pago">Pago</span>';

                let runningBalHtml = '';
                const rb = parseFloat(t.running_balance);
                if (rb < -0.01) {
                    runningBalHtml = `<span style="color:#059669; font-weight:600;">${formatMXN(Math.abs(rb))} <small>(A favor)</small></span>`;
                } else if (rb > 0.01) {
                    runningBalHtml = `<span class="text-danger">${formatMXN(rb)}</span>`;
                } else {
                    runningBalHtml = `<span>${formatMXN(0)}</span>`;
                }

                const tr = document.createElement('tr');
                const safeDate = t.due_date ? t.due_date.substring(0, 10) : t.created_at.substring(0, 10);
                tr.innerHTML = `
                    <td>${formatDate(t.created_at)}</td>
                    <td>${t.description}</td>
                    <td>${badge}</td>
                    <td class="${montoClass}">${formatMXN(t.amount)}</td>
                    <td>${runningBalHtml}</td>
                    <td class="action-icons">
                        <i class="bi bi-pencil action-btn" data-tooltip="Editar Transacción"
                           data-action="edit" data-tid="${t.id}" data-ttype="${t.type}"
                           data-tamount="${t.amount}" data-tdate="${safeDate}"
                           data-tdesc="${(t.description || '').replace(/"/g, '&quot;')}" data-tcat="${t.category_id || ''}"
                           style="cursor:pointer; color:var(--fin-text-muted); margin-right:8px;"></i>
                        <i class="bi bi-trash action-btn" data-tooltip="Eliminar"
                           data-action="delete" data-tid="${t.id}" data-ttype="${t.type}"
                           data-tamount="${t.amount}" data-tdate="${safeDate}"
                           data-tdesc="${(t.description || '').replace(/"/g, '&quot;')}" data-tcatname="${t.category_name || ''}"
                           style="cursor:pointer; color:#ef4444;"></i>
                    </td>
                `;
                tbodyResumen.appendChild(tr);
            });
        }

        // Historial Table
        const tbodyHistorial = document.getElementById('rfBodyHistorial');
        tbodyHistorial.innerHTML = '';
        if (data.historial.length === 0) {
            tbodyHistorial.innerHTML = '<tr><td colspan="4" style="text-align:center;color:#64748b;">No hay pagos este mes</td></tr>';
        } else {
            data.historial.forEach(t => {
                const tr = document.createElement('tr');
                const paymentMethod = t.payment_method ? t.payment_method : '—';
                tr.innerHTML = `
                    <td>${formatDate(t.created_at)}</td>
                    <td>${formatMXN(t.amount)}</td>
                    <td><span class="badge-completed">Completado</span></td>
                    <td>${paymentMethod}</td>
                `;
                tbodyHistorial.appendChild(tr);
            });
        }
    }

    // Event delegation para botones de acción en la tabla de Resumen
    document.getElementById('rfBodyResumen').addEventListener('click', function (e) {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        const action = btn.dataset.action;
        if (action === 'edit') {
            editTransRf(btn.dataset.tid, btn.dataset.ttype, btn.dataset.tamount, btn.dataset.tdate, btn.dataset.tdesc, btn.dataset.tcat);
        } else if (action === 'delete') {
            deleteTransRf(btn.dataset.tid, btn.dataset.ttype, btn.dataset.tamount, btn.dataset.tdate, btn.dataset.tdesc, btn.dataset.tcatname);
        }
    });

    // Modal Exportar Handle
    function openExportModal() {
        document.getElementById('expModal').classList.add('open');
    }
    function closeExportModal() {
        document.getElementById('expModal').classList.remove('open');
    }

    // Flatpickr para el campo de fecha del modal de edición
    const rfEditDatePicker = flatpickr('#rf-edit-date', {
        locale: 'es',
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'j \\de F \\de Y',
        disableMobile: true,
        appendTo: document.getElementById('rfEditOverlay')
    });

    // ── Acciones de Transacciones (Editar / Eliminar) ──
    let rfDeleteId = null;

    window.deleteTransRf = function (id, type, amount, dateStr, desc, catName) {
        rfDeleteId = id;
        // Formatear fecha legible
        const d = new Date(dateStr + 'T12:00:00');
        const months = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        const dateFmt = d.getDate() + ' de ' + months[d.getMonth()] + ' de ' + d.getFullYear();
        // Formatear monto
        const amtFmt = (type === 'charge' ? '-' : '') + 'MX$' + parseFloat(amount).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        document.getElementById('rfDelDate').textContent = dateFmt;
        document.getElementById('rfDelDesc').textContent = desc || '—';
        document.getElementById('rfDelAmount').textContent = amtFmt;
        document.getElementById('rfDelType').textContent = type === 'charge' ? 'Cargo' : 'Pago';
        document.getElementById('rfDelCat').textContent = catName || '—';

        document.getElementById('rfDeleteOverlay').style.display = 'flex';
    }

    function closeRfDelete() {
        document.getElementById('rfDeleteOverlay').style.display = 'none';
        rfDeleteId = null;
    }

    document.getElementById('rfDeleteConfirmBtn').addEventListener('click', function () {
        if (!rfDeleteId) return;
        closeRfDelete();

        const formData = new URLSearchParams();
        formData.append('id', rfDeleteId);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        fetch('<?= base_url('admin/finanzas/transaccion/delete') ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    rfShowToast('Registro eliminado', 'La transacción se eliminó correctamente.', 'success');
                    if (currentUnitId) openUnitModal(currentUnitId);
                } else {
                    rfShowToast('Error', data.message || 'Error al eliminar.', 'error');
                }
            }).catch(() => rfShowToast('Error', 'Problema de conexión.', 'error'));
    });

    // ── Custom Category Dropdown para Edición ──
    const rfSysCategories = [
        <?php if (!empty($categories)):
            foreach ($categories as $c): ?>
                                                                                                                        { id: "<?= esc($c['id']) ?>", name: "<?= esc($c['name']) ?>", type: "<?= esc($c['type'] ?? '') ?>" },
            <?php endforeach; endif; ?>
    ];

    const rfCatIcons = {
        'Salario del Personal': 'bi-people',
        'Mantenimiento y Reparaciones': 'bi-wrench',
        'Mantenimiento': 'bi-wrench',
        'Servicios Públicos': 'bi-lightning',
        'Suministros': 'bi-box',
        'Servicios Profesionales': 'bi-briefcase',
        'Servicios': 'bi-briefcase',
        'Seguro': 'bi-shield',
        'Otro Gasto': 'bi-graph-down-arrow',
        'Cuota de Mantenimiento': 'bi-currency-dollar',
        'Cargo por Mora': 'bi-hourglass-split',
        'Cargo de Reserva de Amenidad': 'bi-calendar2-event',
        'Multa de Amenidad': 'bi-exclamation-triangle',
        'Multa de Estacionamiento': 'bi-p-square',
        'Multa de Mascota': 'bi-emoji-heart-eyes',
        'Multa por Infracción': 'bi-shield-exclamation',
        'Otro Ingreso': 'bi-file-earmark-text'
    };

    function getRfIconForCat(name) {
        return rfCatIcons[name] || 'bi-tag';
    }

    function buildRfCategoryDropdown(dbType) {
        const listEl = document.getElementById('rfCatList');
        const headerEl = document.getElementById('rfCatDropdownHeader');
        listEl.innerHTML = '';
        headerEl.textContent = dbType === 'expense' ? 'Categorías de Gastos' : 'Categorías de Ingresos';

        const filtered = rfSysCategories.filter(c => c.type === dbType);
        filtered.forEach(c => {
            const icon = getRfIconForCat(c.name);
            const el = document.createElement('div');
            el.className = 'rf-cat-option';
            el.dataset.id = c.id;
            el.dataset.name = c.name;
            el.innerHTML = `
                <i class="bi ${icon}"></i>
                <span>${c.name}</span>
                <i class="bi bi-check2 rf-cat-option-check"></i>
            `;
            el.addEventListener('click', () => {
                setRfCat(c.id, c.name);
                closeRfCatDropdown();
            });
            listEl.appendChild(el);
        });
    }

    window.toggleRfCat = function () {
        const dd = document.getElementById('rfCatDropdown');
        const trigger = document.getElementById('rfCatTrigger');
        if (dd.classList.contains('open')) {
            closeRfCatDropdown();
        } else {
            dd.classList.add('open');
            trigger.classList.add('active');
        }
    };

    function closeRfCatDropdown() {
        const dd = document.getElementById('rfCatDropdown');
        const trigger = document.getElementById('rfCatTrigger');
        if (dd) dd.classList.remove('open');
        if (trigger) trigger.classList.remove('active');
    }

    function setRfCat(id, name) {
        document.getElementById('rf-edit-cat').value = id;
        if (!id) {
            document.getElementById('rfCatLabel').textContent = 'Seleccionar categoría';
            document.getElementById('rfCatIcon').innerHTML = '<i class="bi bi-tag"></i>';
            return;
        }
        document.getElementById('rfCatLabel').textContent = name;
        const icon = getRfIconForCat(name);
        document.getElementById('rfCatIcon').innerHTML = `<i class="bi ${icon}"></i>`;

        document.querySelectorAll('#rfCatList .rf-cat-option').forEach(el => {
            if (el.dataset.id === String(id)) el.classList.add('selected');
            else el.classList.remove('selected');
        });
    }

    // Cerrar dropdown al hacer clic fuera
    document.addEventListener('click', function (e) {
        if (!e.target.closest('#rfCatDropdown') && !e.target.closest('#rfCatTrigger')) {
            closeRfCatDropdown();
        }
    });

    // Abrir modal de edición (formato Movimientos Mensuales)
    window.editTransRf = function (id, type, amount, dateStr, desc, catId) {
        document.getElementById('rf-edit-id').value = id;
        document.getElementById('rf-edit-amount').value = amount;
        rfEditDatePicker.setDate(dateStr);
        document.getElementById('rf-edit-desc').value = desc || '';

        // Filtrar categorías por tipo: charge -> income, payment -> expense
        const dbType = (type === 'charge') ? 'income' : 'expense';
        buildRfCategoryDropdown(dbType);

        // Setear categoría actual
        if (catId) {
            const found = rfSysCategories.find(c => c.id === String(catId));
            if (found) {
                setRfCat(found.id, found.name);
            } else {
                setRfCat('', 'Seleccionar categoría');
            }
        } else {
            setRfCat('', 'Seleccionar categoría');
        }

        document.getElementById('rfEditTitle').textContent = 'Editar Transacción';
        document.getElementById('rf-edit-file').value = '';
        document.getElementById('rf-edit-file-preview').textContent = '';
        document.getElementById('rfEditOverlay').style.display = 'flex';
    }

    function closeRfEdit() {
        document.getElementById('rfEditOverlay').style.display = 'none';
    }

    // Preview de archivo
    document.getElementById('rf-edit-file').addEventListener('change', function () {
        const preview = document.getElementById('rf-edit-file-preview');
        if (this.files && this.files.length > 0) {
            preview.textContent = 'Archivo seleccionado: ' + this.files[0].name;
        } else {
            preview.textContent = '';
        }
    });

    // Guardar edición
    document.getElementById('rfEditSaveBtn').addEventListener('click', function () {
        const id = document.getElementById('rf-edit-id').value;
        const amount = document.getElementById('rf-edit-amount').value;
        const date = document.getElementById('rf-edit-date').value;
        const cat = document.getElementById('rf-edit-cat').value;
        const desc = document.getElementById('rf-edit-desc').value;
        const fileInput = document.getElementById('rf-edit-file');

        if (!amount) { rfShowToast('Error', 'El monto es requerido.', 'error'); return; }
        if (!date) { rfShowToast('Error', 'La fecha es requerida.', 'error'); return; }

        closeRfEdit();

        const formData = new FormData();
        formData.append('id', id);
        formData.append('amount', amount);
        formData.append('due_date', date);
        formData.append('category_id', cat);
        formData.append('description', desc);
        formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

        if (fileInput.files && fileInput.files.length > 0) {
            formData.append('attachment', fileInput.files[0]);
        }

        fetch('<?= base_url('admin/finanzas/transaccion/update') ?>', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        })
            .then(r => r.json())
            .then(data => {
                if (data.status === 'success') {
                    rfShowToast('Cambios guardados', 'La transacción se actualizó correctamente.', 'success');
                    if (currentUnitId) openUnitModal(currentUnitId);
                } else {
                    rfShowToast('Error', data.message || 'Error al actualizar.', 'error');
                }
            }).catch(() => rfShowToast('Error', 'Problema de conexión.', 'error'));
    });

    // Toast Notification System
    function rfShowToast(title, msg, type) {
        const toast = document.getElementById('rfToast');
        const icon = toast.querySelector('.toast-icon');
        const iconEl = icon.querySelector('i');
        if (type === 'success') {
            toast.style.borderLeftColor = 'var(--fin-success)';
            icon.style.background = '#d1fae5';
            icon.style.color = '#059669';
            iconEl.className = 'bi bi-check-lg';
        } else {
            toast.style.borderLeftColor = 'var(--fin-danger)';
            icon.style.background = '#fee2e2';
            icon.style.color = '#dc2626';
            iconEl.className = 'bi bi-x-lg';
        }
        document.getElementById('rfToastTitle').textContent = title;
        document.getElementById('rfToastMsg').textContent = msg;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    }
    function submitExport() {
        const format = document.getElementById('exportFormat').value;
        const url = '<?= base_url('admin/finanzas/morosidad/export') ?>?format=' + format;
        window.open(url, '_blank');
        closeExportModal();
    }
</script>
<?= $this->endSection() ?>