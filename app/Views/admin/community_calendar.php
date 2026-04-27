<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$rawEvents = is_array($calendarEvents ?? null) ? $calendarEvents : [];
$eventsJsonRaw = json_encode($rawEvents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
<div id="cc-events-data" style="display:none"
    data-events="<?= htmlspecialchars($eventsJsonRaw, ENT_QUOTES, 'UTF-8') ?>"></div>

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


    /* ── Shell & Toolbar ── */
    .cc-shell {
        border: 1px solid #d9e1eb;
        border-radius: .6rem;
        background: #fff;
        padding: .9rem .9rem 1rem
    }

    .cc-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        flex-wrap: wrap;
        margin-bottom: .85rem
    }

    .cc-left,
    .cc-right {
        display: flex;
        align-items: center;
        gap: .4rem;
        flex-wrap: wrap
    }

    .cc-btn {
        border: 1px solid #d0d8e2;
        background: #fff;
        color: #0f172a;
        border-radius: .42rem;
        font-size: .92rem;
        line-height: 1;
        padding: .54rem .72rem;
        display: inline-flex;
        align-items: center;
        gap: .38rem;
        text-decoration: none;
        cursor: pointer
    }

    .cc-btn:hover {
        background: #f8fafc;
        border-color: #c3cfde;
        color: #0f172a
    }

    .cc-btn.primary {
        border-color: #4b5f78;
        background: #4b5f78;
        color: #fff;
        font-weight: 600;
        padding-inline: .9rem
    }

    .cc-btn.primary:hover {
        background: #41556f;
        border-color: #41556f
    }

    .cc-month-label {
        font-size: 1.02rem;
        font-weight: 650;
        color: #0f172a;
        padding: 0 .65rem;
        text-transform: lowercase;
        min-width: 145px
    }

    /* ── Toggle group ── */
    .cc-toggle-group {
        border: 1px solid #d0d8e2;
        border-radius: .42rem;
        overflow: hidden;
        display: inline-flex
    }

    .cc-toggle-btn {
        border: none;
        background: #fff;
        color: #57708f;
        font-size: .9rem;
        padding: .5rem .82rem;
        display: inline-flex;
        align-items: center;
        gap: .35rem;
        cursor: pointer
    }

    .cc-toggle-btn.active {
        background: #eef2f7;
        color: #0f172a;
        font-weight: 600
    }

    .cc-toggle-btn:not(:last-child) {
        border-right: 1px solid #d0d8e2
    }

    /* ── Calendar Grid ── */
    .cc-weekdays {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: .3rem;
        margin-bottom: .35rem
    }

    .cc-weekday {
        text-align: center;
        color: #57708f;
        font-size: .88rem;
        font-weight: 600;
        padding: .35rem 0
    }

    .cc-grid {
        display: grid;
        grid-template-columns: repeat(7, minmax(0, 1fr));
        gap: .26rem
    }

    .cc-day {
        border: 1px solid #d9e1eb;
        border-radius: .36rem;
        min-height: 103px;
        padding: .38rem .4rem;
        display: flex;
        flex-direction: column;
        background: #fff;
        transition: border-color .2s, box-shadow .2s;
        cursor: pointer
    }

    .cc-day:hover {
        border-color: #b7c8dc;
        box-shadow: 0 2px 6px rgba(15, 23, 42, .08);
        background: #F1F5F9
    }

    .cc-day.outside {
        background: #f7fafc;
        color: #8ea2b9
    }

    .cc-day.today {
        border-color: #4b5f78;
        box-shadow: inset 0 0 0 1px #4b5f78
    }

    .cc-day.selected {
        border-color: #4b5f78;
        box-shadow: inset 0 0 0 1px #4b5f78, 0 2px 8px rgba(15, 23, 42, .1)
    }

    .cc-day-num {
        font-size: .92rem;
        font-weight: 600;
        margin-bottom: .28rem;
        color: #0f172a
    }

    .cc-day.outside .cc-day-num {
        color: #7d93ac
    }

    .cc-events {
        display: flex;
        flex-direction: column;
        gap: .18rem;
        min-height: 0
    }

    .cc-event-chip {
        border: 1px solid #d7e0ec;
        border-radius: .3rem;
        font-size: .69rem;
        line-height: 1.2;
        padding: .16rem .28rem .16rem .22rem;
        color: #334155;
        display: flex;
        align-items: center;
        gap: .22rem;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
        background: #fff;
        cursor: pointer
    }

    .cc-event-chip:hover {
        background: #eef2f7
    }

    .cc-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        flex-shrink: 0
    }

    .cc-more {
        font-size: .68rem;
        color: #57708f;
        margin-top: .08rem;
        font-weight: 600
    }

    /* ── List View ── */
    .cc-list {
        border-top: 1px solid #e2e8f0;
        padding-top: .75rem
    }

    .cc-list-empty {
        border: 1px dashed #d4deea;
        border-radius: .5rem;
        min-height: 240px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: #57708f;
        background: #fbfdff
    }

    .cc-list-empty i {
        display: block;
        font-size: 2rem;
        margin-bottom: .6rem;
        color: #8ea2b9
    }

    .cc-list-table {
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #e2e8f0;
        border-radius: .5rem;
        overflow: hidden
    }

    .cc-list-table th {
        background: #f8fafc;
        color: #57708f;
        font-size: .76rem;
        font-weight: 600;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        padding: .6rem .72rem
    }

    .cc-list-table td {
        border-bottom: 1px solid #edf2f7;
        padding: .58rem .72rem;
        font-size: .86rem;
        color: #334155
    }

    .cc-list-table tr:last-child td {
        border-bottom: none
    }

    .cc-list-table tr {
        cursor: pointer;
        transition: background .15s
    }

    .cc-list-table tr:hover {
        background: #f8fafc
    }

    /* ── Modal Overlay ── */
    .cc-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .45);
        z-index: 9000;
        display: none;
        align-items: center;
        justify-content: center;
        animation: ccFadeIn .2s
    }

    .cc-modal-overlay.show {
        display: flex
    }

    @keyframes ccFadeIn {
        from {
            opacity: 0
        }

        to {
            opacity: 1
        }
    }

    .cc-modal {
        background: #fff;
        border-radius: .65rem;
        box-shadow: 0 20px 60px rgba(15, 23, 42, .22);
        width: 480px;
        max-width: 95vw;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        animation: ccSlideUp .25s ease
    }

    .cc-modal.expanded {
        width: 780px
    }

    @keyframes ccSlideUp {
        from {
            transform: translateY(20px);
            opacity: 0
        }

        to {
            transform: translateY(0);
            opacity: 1
        }
    }

    .cc-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 1.25rem 1.35rem .5rem
    }

    .cc-modal-header h3 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: #0f172a
    }

    .cc-modal-header p {
        margin: .15rem 0 0;
        font-size: .82rem;
        color: #64748b
    }

    .cc-modal-close {
        background: none;
        border: none;
        font-size: 1.2rem;
        color: #94a3b8;
        cursor: pointer;
        padding: .25rem
    }

    .cc-modal-close:hover {
        color: #0f172a
    }

    .cc-modal-body {
        padding: 0 1.35rem 1rem;
        display: flex;
        gap: 1.5rem
    }

    .cc-modal-left {
        flex: 1;
        min-width: 0
    }

    .cc-modal-right {
        width: 260px;
        flex-shrink: 0;
        display: none;
        border-left: 1px solid #e2e8f0;
        padding-left: 1.25rem
    }

    .cc-modal.expanded .cc-modal-right {
        display: block
    }

    .cc-modal-footer {
        padding: .75rem 1.35rem 1.15rem;
        display: flex;
        justify-content: flex-end;
        gap: .5rem;
        border-top: 1px solid #edf2f7
    }

    .cc-gear-btn {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1rem;
        cursor: pointer;
        padding: .25rem;
        margin-left: .5rem;
        transition: color .2s
    }

    .cc-gear-btn:hover,
    .cc-gear-btn.active {
        color: #4b5f78
    }

    /* ── Form elements ── */
    .cc-form-group {
        margin-bottom: .85rem
    }

    .cc-form-group label {
        display: block;
        font-size: .82rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: .3rem
    }

    .cc-form-group input[type="text"],
    .cc-form-group input[type="date"],
    .cc-form-group textarea,
    .cc-form-group select {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: .38rem;
        padding: .48rem .6rem;
        font-size: .88rem;
        color: #0f172a;
        font-family: inherit;
        outline: none;
        transition: border-color .2s
    }

    .cc-form-group input:focus,
    .cc-form-group textarea:focus,
    .cc-form-group select:focus {
        border-color: #4b5f78;
        box-shadow: 0 0 0 3px rgba(75, 95, 120, .1)
    }

    .cc-form-group textarea {
        resize: vertical;
        min-height: 68px
    }

    .cc-form-row {
        display: flex;
        gap: .75rem
    }

    .cc-form-row .cc-form-group {
        flex: 1
    }

    .cc-time-group {
        display: flex;
        align-items: center;
        gap: .3rem
    }

    .cc-time-group select {
        width: auto;
        min-width: 52px;
        padding: .48rem .35rem
    }

    /* ── Toggle Switch ── */
    .cc-toggle {
        display: flex;
        align-items: center;
        gap: .6rem;
        padding: .55rem 0;
        font-size: .88rem;
        color: #334155;
        cursor: pointer
    }

    .cc-toggle-track {
        width: 38px;
        height: 20px;
        border-radius: 10px;
        background: #cbd5e1;
        position: relative;
        transition: background .2s;
        flex-shrink: 0
    }

    .cc-toggle-track::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        top: 2px;
        left: 2px;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .15)
    }

    .cc-toggle input {
        display: none
    }

    .cc-toggle input:checked+.cc-toggle-track {
        background: #4b5f78
    }

    .cc-toggle input:checked+.cc-toggle-track::after {
        transform: translateX(18px)
    }

    /* ── Advanced Options ── */
    .cc-adv-section {
        margin-bottom: .85rem
    }

    .cc-adv-title {
        font-size: .82rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: .5rem
    }

    .cc-adv-subtitle {
        font-size: .78rem;
        font-weight: 600;
        color: #57708f;
        margin-bottom: .35rem;
        display: flex;
        align-items: center;
        justify-content: space-between
    }

    .cc-add-btn {
        background: none;
        border: none;
        color: #4b5f78;
        font-size: .78rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: .2rem
    }

    .cc-add-btn:hover {
        color: #334155
    }

    .cc-chip-container {
        display: flex;
        flex-wrap: wrap;
        gap: .3rem;
        border: 1px solid #d0d8e2;
        border-radius: .38rem;
        padding: .35rem .45rem;
        min-height: 36px;
        cursor: text
    }

    .cc-chip {
        background: #eef2f7;
        border-radius: .25rem;
        padding: .15rem .35rem;
        font-size: .76rem;
        color: #334155;
        display: inline-flex;
        align-items: center;
        gap: .2rem
    }

    .cc-chip-remove {
        background: none;
        border: none;
        font-size: .8rem;
        color: #94a3b8;
        cursor: pointer;
        padding: 0;
        line-height: 1
    }

    .cc-chip-remove:hover {
        color: #ef4444
    }

    .cc-chip-dropdown {
        position: absolute;
        background: #fff;
        border: 1px solid #d0d8e2;
        border-radius: .38rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
        max-height: 180px;
        overflow-y: auto;
        z-index: 9999;
        width: 100%
    }

    .cc-chip-dropdown-item {
        padding: .4rem .6rem;
        font-size: .82rem;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center
    }

    .cc-chip-dropdown-item:hover {
        background: #ffffffff
    }

    .cc-chip-dropdown-item small {
        color: #94a3b8;
        font-size: .7rem
    }

    .cc-reminder-card {
        border: 1px solid #e2e8f0;
        border-radius: .4rem;
        padding: .6rem .7rem;
        margin-bottom: .5rem;
        position: relative
    }

    .cc-reminder-card .cc-reminder-remove {
        position: absolute;
        top: .35rem;
        right: .4rem;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: .85rem
    }

    .cc-reminder-card .cc-reminder-remove:hover {
        color: #ef4444
    }

    .cc-reminder-time {
        font-size: .72rem;
        color: #64748b;
        margin-top: .3rem;
        display: flex;
        align-items: center;
        gap: .25rem
    }

    /* ── Popover Detail ── */
    .cc-popover {
        position: absolute;
        background: #fff;
        border: 1px solid #d9e1eb;
        border-radius: .55rem;
        box-shadow: 0 12px 36px rgba(15, 23, 42, .18);
        width: 320px;
        z-index: 8000;
        padding: 1rem 1.15rem;
        animation: ccSlideUp .2s ease
    }

    .cc-popover-close {
        position: absolute;
        top: .6rem;
        right: .7rem;
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 1rem;
        cursor: pointer
    }

    .cc-popover-close:hover {
        color: #0f172a
    }

    .cc-popover h4 {
        margin: 0 0 .7rem;
        font-size: 1rem;
        font-weight: 700;
        color: #0f172a;
        padding-right: 1.5rem
    }

    .cc-popover-info {
        background: #f8fafc;
        border-radius: .38rem;
        padding: .55rem .65rem;
        margin-bottom: .55rem;
        display: flex;
        flex-wrap: wrap;
        gap: .5rem
    }

    .cc-popover-info-item {
        flex: 1;
        min-width: 100px
    }

    .cc-popover-info-item .label {
        font-size: .7rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: .25rem;
        margin-bottom: .12rem
    }

    .cc-popover-info-item .value {
        font-size: .82rem;
        color: #0f172a
    }

    .cc-popover-desc {
        background: #f8fafc;
        border-radius: .38rem;
        padding: .55rem .65rem;
        margin-bottom: .55rem
    }

    .cc-popover-desc .label {
        font-size: .7rem;
        color: #64748b;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: .25rem;
        margin-bottom: .12rem
    }

    .cc-popover-desc .value {
        font-size: .82rem;
        color: #334155
    }

    .cc-popover-actions {
        display: flex;
        gap: .4rem;
        margin-top: .75rem;
        align-items: center
    }

    .cc-popover-actions .cc-btn {
        font-size: .82rem;
        padding: .42rem .65rem
    }

    .cc-btn.danger {
        border-color: #fecaca;
        background: #fff;
        color: #dc2626;
        font-weight: 600
    }

    .cc-btn.danger:hover {
        background: #fef2f2;
        border-color: #fca5a5
    }

    .cc-popover-actions .spacer {
        flex: 1
    }

    /* ── Location icon ── */
    .cc-loc-wrap {
        position: relative
    }

    .cc-loc-wrap input {
        padding-left: 1.8rem
    }

    .cc-loc-wrap .cc-loc-icon {
        position: absolute;
        left: .55rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: .9rem
    }

    @media(max-width:992px) {
        .cc-day {
            min-height: 88px
        }

        .cc-event-chip {
            font-size: .65rem
        }
    }

    @media(max-width:768px) {
        .cc-weekday {
            font-size: .76rem
        }

        .cc-day {
            min-height: 72px;
            padding: .28rem
        }

        .cc-event-chip {
            display: none
        }

        .cc-more {
            display: block
        }

        .cc-modal.expanded {
            width: 95vw
        }

        .cc-modal-body {
            flex-direction: column
        }

        .cc-modal-right {
            width: 100%;
            border-left: none;
            border-top: 1px solid #e2e8f0;
            padding-left: 0;
            padding-top: .75rem
        }
    }
</style>

<!-- ── Hero ── -->
<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">Calendario</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-calendar3"></i>
            <i class="bi bi-chevron-right"></i>
            Calendario Comunitario
        </div>
    </div>
    <div class="cc-hero-right">
        <button type="button" class="cc-hero-btn" id="cc-create-btn">
            <i class="bi bi-plus-lg"></i> Crear Evento
        </button>
    </div>
</div>

<!-- ── Shell ── -->
<div class="cc-shell">
    <div class="cc-toolbar">
        <div class="cc-left">
            <button type="button" class="cc-btn" id="cc-prev-btn"><i class="bi bi-chevron-left"></i></button>
            <button type="button" class="cc-btn" id="cc-today-btn">Today</button>
            <button type="button" class="cc-btn" id="cc-next-btn"><i class="bi bi-chevron-right"></i></button>
            <div class="cc-month-label" id="cc-month-label">mes</div>
        </div>
        <div class="cc-right">
            <div class="cc-toggle-group" role="group">
                <button type="button" class="cc-toggle-btn active" id="cc-view-calendar"><i class="bi bi-calendar3"></i>
                    Calendario</button>
                <button type="button" class="cc-toggle-btn" id="cc-view-list"><i class="bi bi-list"></i> Lista</button>
            </div>
            <button type="button" class="cc-btn" id="cc-refresh-btn" title="Actualizar"><i
                    class="bi bi-arrow-clockwise"></i></button>
        </div>
    </div>
    <div id="cc-calendar-view">
        <div class="cc-weekdays">
            <div class="cc-weekday">Lun</div>
            <div class="cc-weekday">Mar</div>
            <div class="cc-weekday">Mié</div>
            <div class="cc-weekday">Jue</div>
            <div class="cc-weekday">Vie</div>
            <div class="cc-weekday">Sáb</div>
            <div class="cc-weekday">Dom</div>
        </div>
        <div class="cc-grid" id="cc-grid"></div>
    </div>
    <div id="cc-list-view" class="cc-list d-none">
        <div id="cc-list-empty" class="cc-list-empty d-none">
            <div><i class="bi bi-calendar-x"></i>No hay eventos para este mes.</div>
        </div>
        <div id="cc-list-wrap" class="table-responsive">
            <table class="cc-list-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Evento</th>
                        <th>Ubicación</th>
                        <th>Tipo</th>
                    </tr>
                </thead>
                <tbody id="cc-list-body"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- ── Popover container (injected dynamically) ── -->
<div id="cc-popover-container"></div>

<!-- ── Modal: Crear/Editar Evento ── -->
<div class="cc-modal-overlay" id="cc-modal-overlay">
    <div class="cc-modal" id="cc-modal">
        <div class="cc-modal-header">
            <div>
                <h3 id="cc-modal-title">Crear Evento</h3>
                <p>Agrega un nuevo evento al calendario comunitario</p>
            </div>
            <div style="display:flex;align-items:center">
                <button type="button" class="cc-gear-btn" id="cc-gear-btn" title="Opciones avanzadas"><i
                        class="bi bi-gear"></i></button>
                <button type="button" class="cc-modal-close" id="cc-modal-close">&times;</button>
            </div>
        </div>
        <div class="cc-modal-body">
            <!-- LEFT: main form -->
            <div class="cc-modal-left">
                <div class="cc-form-group"><label>Título *</label><input type="text" id="cc-f-title"
                        placeholder="Posada navideña en el salón de eventos"></div>
                <label class="cc-toggle"><input type="checkbox" id="cc-f-allday"><span class="cc-toggle-track"></span>
                    Evento de Todo el Día</label>
                <div class="cc-form-row">
                    <div class="cc-form-group"><label>Fecha de Inicio *</label><input type="date" id="cc-f-start-date">
                    </div>
                    <div class="cc-form-group" id="cc-start-time-group"><label>Hora de Inicio *</label>
                        <div class="cc-time-group">
                            <i class="bi bi-clock" style="color:#94a3b8;font-size:.85rem"></i>
                            <select id="cc-f-start-hour"></select> : <select id="cc-f-start-min">
                                <option value="00">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                            <select id="cc-f-start-ampm">
                                <option>AM</option>
                                <option>PM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="cc-form-row">
                    <div class="cc-form-group"><label>Fecha de Fin *</label><input type="date" id="cc-f-end-date"></div>
                    <div class="cc-form-group" id="cc-end-time-group"><label>Hora de Fin *</label>
                        <div class="cc-time-group">
                            <i class="bi bi-clock" style="color:#94a3b8;font-size:.85rem"></i>
                            <select id="cc-f-end-hour"></select> : <select id="cc-f-end-min">
                                <option value="00">00</option>
                                <option value="15">15</option>
                                <option value="30">30</option>
                                <option value="45">45</option>
                            </select>
                            <select id="cc-f-end-ampm">
                                <option>AM</option>
                                <option>PM</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="cc-form-group"><label><i class="bi bi-geo-alt" style="color:#64748b"></i> Ubicación <i
                            class="bi bi-info-circle" style="font-size:.7rem;color:#94a3b8"
                            title="Opcional"></i></label>
                    <div class="cc-loc-wrap"><span class="cc-loc-icon"><i class="bi bi-geo-alt"></i></span><input
                            type="text" id="cc-f-location" placeholder="Ubicación" style="padding-left:1.8rem"></div>
                </div>
                <div class="cc-form-group"><label>Descripción</label><textarea id="cc-f-description"
                        placeholder="Descripción"></textarea></div>
            </div>
            <!-- RIGHT: advanced options -->
            <div class="cc-modal-right" id="cc-modal-right">
                <div class="cc-adv-title">Opciones avanzadas</div>
                <label class="cc-toggle"><input type="checkbox" id="cc-f-internal"><span class="cc-toggle-track"></span>
                    Evento interno (solo staff)</label>
                <div class="cc-adv-section">
                    <div class="cc-adv-subtitle">Recordatorios <button type="button" class="cc-add-btn"
                            id="cc-add-reminder"><i class="bi bi-plus"></i> Agregar</button></div>
                    <div style="position:relative;margin-bottom:.6rem">
                        <div class="cc-adv-subtitle" style="margin-bottom:.25rem">Destinatarios</div>
                        <div class="cc-chip-container" id="cc-recipients-container"><input type="text"
                                id="cc-recipients-search" placeholder="Buscar..."
                                style="border:none;outline:none;font-size:.8rem;flex:1;min-width:60px;padding:0"></div>
                        <div class="cc-chip-dropdown" id="cc-recipients-dropdown" style="display:none"></div>
                    </div>
                    <div id="cc-reminders-list"></div>
                </div>
            </div>
        </div>
        <div class="cc-modal-footer">
            <button type="button" class="cc-btn" id="cc-modal-cancel">Cancelar</button>
            <button type="button" class="cc-btn primary" id="cc-modal-submit">Crear</button>
        </div>
    </div>
</div>

<script>
    (function () {
        /* ───── Constants ───── */
        var BASE = '<?= base_url("admin/calendario") ?>';
        var monthNames = ['enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'];
        var dayNamesFull = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
        var reminderOptions = [{ v: 15, l: '15 minutos antes' }, { v: 30, l: '30 minutos antes' }, { v: 60, l: '1 hora antes' }, { v: 120, l: '2 horas antes' }, { v: 1440, l: '1 día antes' }];
        var eventColor = '#4b5f78', internalColor = '#8b5cf6';

        /* ───── State ───── */
        var events = [], eventIndex = {}, recipientsList = [], selectedRecipients = [], remindersState = [];
        var monthParam = new URLSearchParams(location.search).get('mes');
        var initialDate = new Date();
        if (monthParam && /^\d{4}-\d{2}$/.test(monthParam)) { var p = monthParam.split('-'); initialDate = new Date(+p[0], +p[1] - 1, 1) } else { initialDate = new Date(initialDate.getFullYear(), initialDate.getMonth(), 1) }
        var currentMonth = new Date(initialDate.getFullYear(), initialDate.getMonth(), 1);
        var selectedDay = dk(new Date()), todayKey = dk(new Date()), currentView = 'calendar', editingEventId = null;

        /* ───── DOM refs ───── */
        var $ = function (s) { return document.getElementById(s) };
        var gridEl = $('cc-grid'), listBody = $('cc-list-body'), listWrap = $('cc-list-wrap'), listEmpty = $('cc-list-empty');
        var calView = $('cc-calendar-view'), listView = $('cc-list-view');
        var btnCal = $('cc-view-calendar'), btnList = $('cc-view-list');
        var overlay = $('cc-modal-overlay'), modal = $('cc-modal');

        /* ───── Helpers ───── */
        function pad(n) { return n < 10 ? '0' + n : String(n) }
        function dk(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) }
        function pDate(v) { var d = new Date(v); return isNaN(d.getTime()) ? null : d }
        function fmt12(h) { var ampm = h >= 12 ? 'PM' : 'AM'; var hh = h % 12 || 12; return { h: hh, ampm: ampm } }
        function fmtDateLong(d) { return dayNamesFull[d.getDay()] + ', ' + d.getDate() + ' de ' + monthNames[d.getMonth()] + ' de ' + d.getFullYear() }

        /* ───── Build events from PHP data ───── */
        function loadEvents() {
            var raw = JSON.parse(document.getElementById('cc-events-data').getAttribute('data-events'));
            events = []; eventIndex = {};
            raw.forEach(function (e) {
                var s = pDate(e.start_datetime), en = pDate(e.end_datetime);
                if (!s) return;
                if (!en) en = new Date(s.getTime() + 3600000);
                var col = +e.is_internal ? internalColor : eventColor;
                var ev = {
                    id: +e.id, title: e.title || 'Evento', description: e.description || '', location: e.location || '', start: s, end: en, allDay: +e.all_day, isInternal: +e.is_internal, color: col,
                    timeLabel: pad(s.getHours()) + ':' + pad(s.getMinutes()), dayKey: dk(s),
                    reminder_count: +e.reminder_count || 0, recipient_count: +e.recipient_count || 0
                };
                events.push(ev);
                if (!eventIndex[ev.dayKey]) eventIndex[ev.dayKey] = [];
                eventIndex[ev.dayKey].push(ev);
            });
            Object.keys(eventIndex).forEach(function (k) { eventIndex[k].sort(function (a, b) { return a.start - b.start }) });
        }
        loadEvents();

        /* ───── Populate hour selects ───── */
        function fillHours() {
            ['cc-f-start-hour', 'cc-f-end-hour'].forEach(function (id) {
                var sel = $(id); sel.innerHTML = '';
                for (var i = 1; i <= 12; i++) { var o = document.createElement('option'); o.value = i; o.textContent = i; sel.appendChild(o) }
            });
        }
        fillHours();

        /* ───── Calendar rendering ───── */
        function updateUrl() { var p = new URLSearchParams(location.search); p.set('mes', currentMonth.getFullYear() + '-' + pad(currentMonth.getMonth() + 1)); history.replaceState({}, '', location.pathname + '?' + p) }
        function gridStart(d) { var f = new Date(d.getFullYear(), d.getMonth(), 1); var w = (f.getDay() + 6) % 7; var s = new Date(f); s.setDate(f.getDate() - w); return s }

        function renderHeader() { $('cc-month-label').textContent = monthNames[currentMonth.getMonth()] + ' ' + currentMonth.getFullYear() }

        function makeChip(ev) {
            var c = document.createElement('div'); c.className = 'cc-event-chip'; c.setAttribute('data-event-id', ev.id);
            var dot = document.createElement('span'); dot.className = 'cc-dot'; dot.style.backgroundColor = ev.color;
            var t = document.createElement('span'); t.textContent = ev.timeLabel + ' ' + ev.title;
            c.appendChild(dot); c.appendChild(t);
            c.addEventListener('click', function (e) { e.stopPropagation(); showPopover(ev, c) });
            return c;
        }

        function renderCalendar() {
            gridEl.innerHTML = ''; closePopover();
            var st = gridStart(currentMonth);
            for (var i = 0; i < 42; i++) {
                var cd = new Date(st); cd.setDate(st.getDate() + i); var ck = dk(cd);
                var cell = document.createElement('div'); cell.className = 'cc-day';
                if (cd.getMonth() !== currentMonth.getMonth()) cell.classList.add('outside');
                if (ck === todayKey) cell.classList.add('today');
                if (ck === selectedDay) cell.classList.add('selected');
                var num = document.createElement('div'); num.className = 'cc-day-num'; num.textContent = cd.getDate(); cell.appendChild(num);
                var ew = document.createElement('div'); ew.className = 'cc-events';
                var de = eventIndex[ck] || [];
                for (var j = 0; j < Math.min(2, de.length); j++)ew.appendChild(makeChip(de[j]));
                if (de.length > 2) { var m = document.createElement('div'); m.className = 'cc-more'; m.textContent = '+' + (de.length - 2) + ' más'; ew.appendChild(m) }
                cell.appendChild(ew);
                cell.addEventListener('click', (function (k) { return function () { 
                    selectedDay = k; 
                    renderCalendar(); 
                    openModal(false);
                    $('cc-f-start-date').value = k;
                    $('cc-f-end-date').value = k;
                } })(ck));
                gridEl.appendChild(cell);
            }
        }

        function renderList() {
            var me = events.filter(function (e) { return e.start.getMonth() === currentMonth.getMonth() && e.start.getFullYear() === currentMonth.getFullYear() }).sort(function (a, b) { return a.start - b.start });
            listBody.innerHTML = '';
            if (!me.length) { listWrap.classList.add('d-none'); listEmpty.classList.remove('d-none'); return }
            listWrap.classList.remove('d-none'); listEmpty.classList.add('d-none');
            me.forEach(function (ev) {
                var tr = document.createElement('tr');
                tr.innerHTML = '<td>' + pad(ev.start.getDate()) + '/' + pad(ev.start.getMonth() + 1) + '/' + ev.start.getFullYear() + '</td><td>' + ev.timeLabel + '</td><td>' + ev.title + '</td><td>' + (ev.location || '—') + '</td><td>' + (ev.isInternal ? '<span style="color:#8b5cf6;font-weight:600">Interno</span>' : 'Público') + '</td>';
                tr.addEventListener('click', function () { showPopover(ev, tr) });
                listBody.appendChild(tr);
            });
        }

        function syncView() {
            if (currentView === 'calendar') { calView.classList.remove('d-none'); listView.classList.add('d-none'); btnCal.classList.add('active'); btnList.classList.remove('active') }
            else { calView.classList.add('d-none'); listView.classList.remove('d-none'); btnCal.classList.remove('active'); btnList.classList.add('active') }
        }

        function renderAll() { renderHeader(); renderCalendar(); renderList(); syncView(); updateUrl() }

        /* ───── Popover ───── */
        var currentPopover = null;
        function closePopover() { if (currentPopover) { currentPopover.remove(); currentPopover = null } }
        function showPopover(ev, anchor) {
            closePopover();
            var pop = document.createElement('div'); pop.className = 'cc-popover';
            var startStr = fmtDateLong(ev.start);
            var timeStr = ev.allDay ? 'Todo el día' : pad(ev.start.getHours()) + ':' + pad(ev.start.getMinutes()) + ' - ' + pad(ev.end.getHours()) + ':' + pad(ev.end.getMinutes());
            var html = '<button class="cc-popover-close" id="cc-pop-close">&times;</button>';
            html += '<h4>' + ev.title + '</h4>';
            html += '<div class="cc-popover-info"><div class="cc-popover-info-item"><div class="label"><i class="bi bi-calendar3"></i> Fecha</div><div class="value">' + startStr + '</div></div>';
            html += '<div class="cc-popover-info-item"><div class="label"><i class="bi bi-clock"></i> Hora</div><div class="value">' + timeStr + '</div></div>';
            if (ev.location) html += '<div class="cc-popover-info-item" style="flex-basis:100%"><div class="label"><i class="bi bi-geo-alt"></i> Ubicación</div><div class="value">' + ev.location + '</div></div>';
            html += '</div>';
            if (ev.description) html += '<div class="cc-popover-desc"><div class="label"><i class="bi bi-file-text"></i> Descripción</div><div class="value">' + ev.description + '</div></div>';
            if (ev.reminder_count > 0) html += '<div class="cc-popover-desc"><div class="label"><i class="bi bi-bell"></i> Recordatorios</div><div class="value">' + ev.reminder_count + ' recordatorio(s) (' + ev.recipient_count + ' destinatario(s))</div></div>';
            html += '<div class="cc-popover-actions"><button class="cc-btn" id="cc-pop-edit"><i class="bi bi-pencil"></i> Editar</button><button class="cc-btn danger" id="cc-pop-delete"><i class="bi bi-trash"></i> Eliminar</button><span class="spacer"></span><button class="cc-btn" id="cc-pop-close2">Cerrar</button></div>';
            pop.innerHTML = html;

            // Position near anchor
            var rect = anchor.getBoundingClientRect();
            pop.style.position = 'fixed'; pop.style.top = Math.min(rect.bottom + 8, window.innerHeight - 400) + 'px';
            pop.style.left = Math.min(rect.left, window.innerWidth - 340) + 'px';
            document.body.appendChild(pop); currentPopover = pop;

            pop.querySelector('#cc-pop-close').onclick = closePopover;
            pop.querySelector('#cc-pop-close2').onclick = closePopover;
            pop.querySelector('#cc-pop-edit').onclick = function () { closePopover(); openEditModal(ev.id) };
            pop.querySelector('#cc-pop-delete').onclick = function () { closePopover(); deleteEvent(ev.id) };
        }
        document.addEventListener('click', function (e) { if (currentPopover && !currentPopover.contains(e.target) && !e.target.closest('.cc-event-chip')) closePopover() });

        /* ───── Modal ───── */
        function openModal(isEdit) {
            editingEventId = isEdit ? editingEventId : null;
            $('cc-modal-title').textContent = isEdit ? 'Editar Evento' : 'Crear Evento';
            $('cc-modal-submit').textContent = isEdit ? 'Guardar' : 'Crear';
            overlay.classList.add('show');
            if (!isEdit) resetForm();
        }
        function closeModal() { overlay.classList.remove('show'); modal.classList.remove('expanded'); $('cc-gear-btn').classList.remove('active') }

        function resetForm() {
            $('cc-f-title').value = ''; $('cc-f-description').value = ''; $('cc-f-location').value = '';
            $('cc-f-allday').checked = false; $('cc-f-internal').checked = false;
            var tomorrow = new Date(); tomorrow.setDate(tomorrow.getDate() + 1);
            $('cc-f-start-date').value = dk(tomorrow); $('cc-f-end-date').value = dk(tomorrow);
            $('cc-f-start-hour').value = '8'; $('cc-f-start-min').value = '00'; $('cc-f-start-ampm').value = 'AM';
            $('cc-f-end-hour').value = '9'; $('cc-f-end-min').value = '00'; $('cc-f-end-ampm').value = 'AM';
            toggleTimeInputs(false);
            selectedRecipients = []; remindersState = []; renderRecipientChips(); renderReminders();
        }

        function toggleTimeInputs(allDay) {
            $('cc-start-time-group').style.display = allDay ? 'none' : '';
            $('cc-end-time-group').style.display = allDay ? 'none' : '';
        }
        $('cc-f-allday').addEventListener('change', function () { toggleTimeInputs(this.checked) });

        /* Modal open/close */
        $('cc-create-btn').addEventListener('click', function () { openModal(false) });
        $('cc-modal-close').addEventListener('click', closeModal);
        $('cc-modal-cancel').addEventListener('click', closeModal);
        overlay.addEventListener('click', function (e) { if (e.target === overlay) closeModal() });

        /* Gear toggle */
        $('cc-gear-btn').addEventListener('click', function () {
            modal.classList.toggle('expanded'); this.classList.toggle('active');
            if (modal.classList.contains('expanded') && !recipientsList.length) loadRecipients();
        });

        /* ───── Recipients Chip Selector ───── */
        function loadRecipients() {
            fetch(BASE + '/recipientes').then(function (r) { return r.json() }).then(function (d) {
                if (d.status === 200) recipientsList = d.data;
            });
        }

        function renderRecipientChips() {
            var container = $('cc-recipients-container');
            container.querySelectorAll('.cc-chip').forEach(function (c) { c.remove() });
            var input = $('cc-recipients-search');
            selectedRecipients.forEach(function (r) {
                var chip = document.createElement('span'); chip.className = 'cc-chip';
                chip.innerHTML = r.name + ' <button class="cc-chip-remove" data-rid="' + r.id + '">&times;</button>';
                container.insertBefore(chip, input);
            });
            container.querySelectorAll('.cc-chip-remove').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var rid = this.getAttribute('data-rid');
                    selectedRecipients = selectedRecipients.filter(function (x) { return x.id !== rid });
                    renderRecipientChips();
                });
            });
        }

        $('cc-recipients-search').addEventListener('focus', function () { showRecipientDropdown('') });
        $('cc-recipients-search').addEventListener('input', function () { showRecipientDropdown(this.value) });
        document.addEventListener('click', function (e) { if (!e.target.closest('#cc-recipients-container') && !e.target.closest('#cc-recipients-dropdown')) $('cc-recipients-dropdown').style.display = 'none' });

        function showRecipientDropdown(q) {
            var dd = $('cc-recipients-dropdown'); dd.innerHTML = ''; dd.style.display = 'block';
            var selIds = selectedRecipients.map(function (x) { return x.id });
            var filtered = recipientsList.filter(function (r) { return selIds.indexOf(r.id) === -1 && r.name.toLowerCase().indexOf(q.toLowerCase()) >= 0 });
            if (!filtered.length) { dd.innerHTML = '<div style="padding:.5rem .6rem;color:#94a3b8;font-size:.82rem">Sin resultados</div>'; return }
            filtered.forEach(function (r) {
                var item = document.createElement('div'); item.className = 'cc-chip-dropdown-item';
                item.innerHTML = r.name + ' <small>' + r.type + '</small>';
                item.addEventListener('click', function () { selectedRecipients.push(r); renderRecipientChips(); dd.style.display = 'none'; $('cc-recipients-search').value = '' });
                dd.appendChild(item);
            });
        }

        /* ───── Reminders ───── */
        $('cc-add-reminder').addEventListener('click', function () { remindersState.push({ minutes_before: 30 }); renderReminders() });

        function renderReminders() {
            var list = $('cc-reminders-list'); list.innerHTML = '';
            remindersState.forEach(function (rem, idx) {
                var card = document.createElement('div'); card.className = 'cc-reminder-card';
                var label = 'Recordatorio ' + (idx + 1);
                var html = '<button class="cc-reminder-remove" data-idx="' + idx + '">&times;</button>';
                html += '<div style="font-size:.78rem;font-weight:600;color:#334155;margin-bottom:.35rem">' + label + '</div>';
                html += '<select class="cc-reminder-select" data-idx="' + idx + '" style="width:100%;border:1px solid #d0d8e2;border-radius:.38rem;padding:.4rem .5rem;font-size:.82rem">';
                reminderOptions.forEach(function (o) { html += '<option value="' + o.v + '"' + (rem.minutes_before === o.v ? ' selected' : '') + '>' + o.l + '</option>' });
                html += '</select>';
                // Calc reminder time
                var startVal = $('cc-f-start-date').value;
                if (startVal) {
                    var shour = +($('cc-f-start-hour').value || 8), smin = +($('cc-f-start-min').value || 0), sampm = $('cc-f-start-ampm').value;
                    if (sampm === 'PM' && shour < 12) shour += 12; if (sampm === 'AM' && shour === 12) shour = 0;
                    var sd = new Date(startVal + 'T' + pad(shour) + ':' + pad(smin) + ':00');
                    var rd = new Date(sd.getTime() - rem.minutes_before * 60000);
                    html += '<div class="cc-reminder-time"><i class="bi bi-clock"></i> ' + rd.getDate() + ' ' + monthNames[rd.getMonth()].substring(0, 3) + ' ' + rd.getFullYear() + ' ' + pad(rd.getHours()) + ':' + pad(rd.getMinutes()) + '</div>';
                }
                card.innerHTML = html; list.appendChild(card);
            });
            list.querySelectorAll('.cc-reminder-remove').forEach(function (b) { b.addEventListener('click', function () { remindersState.splice(+this.dataset.idx, 1); renderReminders() }) });
            list.querySelectorAll('.cc-reminder-select').forEach(function (s) { s.addEventListener('change', function () { remindersState[+this.dataset.idx].minutes_before = +this.value; renderReminders() }) });
        }

        /* ───── Submit (Create/Update) ───── */
        $('cc-modal-submit').addEventListener('click', function () {
            var title = $('cc-f-title').value.trim();
            if (!title) { Swal.fire({ toast: true, position: 'top-end', icon: 'warning', title: 'El título es obligatorio', showConfirmButton: false, timer: 2500 }); return }
            var fd = new FormData();
            fd.append('title', title);
            fd.append('description', $('cc-f-description').value);
            fd.append('location', $('cc-f-location').value);
            fd.append('all_day', $('cc-f-allday').checked ? 1 : 0);
            fd.append('is_internal', $('cc-f-internal').checked ? 1 : 0);
            fd.append('start_date', $('cc-f-start-date').value);
            fd.append('end_date', $('cc-f-end-date').value);
            fd.append('start_hour', $('cc-f-start-hour').value);
            fd.append('start_minute', $('cc-f-start-min').value);
            fd.append('start_ampm', $('cc-f-start-ampm').value);
            fd.append('end_hour', $('cc-f-end-hour').value);
            fd.append('end_minute', $('cc-f-end-min').value);
            fd.append('end_ampm', $('cc-f-end-ampm').value);
            if (remindersState.length) fd.append('reminders', JSON.stringify(remindersState));
            selectedRecipients.forEach(function (r) { fd.append('recipients[]', r.id) });

            var url = editingEventId ? BASE + '/actualizar/' + editingEventId : BASE + '/crear';
            fetch(url, { method: 'POST', body: fd }).then(function (r) { return r.json() }).then(function (d) {
                if (d.status === 201 || d.status === 200) {
                    closeModal();
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: d.message, showConfirmButton: false, timer: 2500 });
                    setTimeout(function () { location.reload() }, 800);
                } else {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.error || 'Error', showConfirmButton: false, timer: 3000 });
                }
            }).catch(function () { Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error de conexión', showConfirmButton: false, timer: 3000 }) });
        });

        /* ───── Edit ───── */
        function openEditModal(id) {
            editingEventId = id;
            fetch(BASE + '/evento/' + id).then(function (r) { return r.json() }).then(function (d) {
                if (d.status !== 200) return;
                var ev = d.data;
                $('cc-f-title').value = ev.title || '';
                $('cc-f-description').value = ev.description || '';
                $('cc-f-location').value = ev.location || '';
                $('cc-f-allday').checked = !!+ev.all_day;
                $('cc-f-internal').checked = !!+ev.is_internal;
                toggleTimeInputs(!!+ev.all_day);
                var s = pDate(ev.start_datetime), en = pDate(ev.end_datetime);
                if (s) { $('cc-f-start-date').value = dk(s); var sh = fmt12(s.getHours()); $('cc-f-start-hour').value = sh.h; $('cc-f-start-min').value = pad(s.getMinutes()); $('cc-f-start-ampm').value = sh.ampm }
                if (en) { $('cc-f-end-date').value = dk(en); var eh = fmt12(en.getHours()); $('cc-f-end-hour').value = eh.h; $('cc-f-end-min').value = pad(en.getMinutes()); $('cc-f-end-ampm').value = eh.ampm }
                // Reminders
                remindersState = (ev.reminders || []).map(function (r) { return { minutes_before: r.minutes_before } });
                // Recipients
                var allRecipientIds = [];
                (ev.reminders || []).forEach(function (r) { (r.recipients || []).forEach(function (rid) { if (allRecipientIds.indexOf(rid) === -1) allRecipientIds.push(rid) }) });
                // Need to load recipients list first
                if (!recipientsList.length) {
                    fetch(BASE + '/recipientes').then(function (r2) { return r2.json() }).then(function (d2) {
                        if (d2.status === 200) recipientsList = d2.data;
                        selectedRecipients = recipientsList.filter(function (r) { return allRecipientIds.indexOf(r.id) >= 0 });
                        renderRecipientChips(); renderReminders();
                    });
                } else {
                    selectedRecipients = recipientsList.filter(function (r) { return allRecipientIds.indexOf(r.id) >= 0 });
                    renderRecipientChips(); renderReminders();
                }
                // If event has reminders/internal, expand panel
                if (remindersState.length || +ev.is_internal) { modal.classList.add('expanded'); $('cc-gear-btn').classList.add('active') }
                openModal(true);
            });
        }

        /* ───── Delete ───── */
        function deleteEvent(id) {
            Swal.fire({ title: '¿Eliminar evento?', text: 'Esta acción no se puede deshacer', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc2626', cancelButtonText: 'Cancelar', confirmButtonText: 'Sí, eliminar' }).then(function (r) {
                if (!r.isConfirmed) return;
                fetch(BASE + '/eliminar/' + id, { method: 'POST' }).then(function (r) { return r.json() }).then(function (d) {
                    if (d.status === 200) {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: d.message, showConfirmButton: false, timer: 2500 });
                        setTimeout(function () { location.reload() }, 800);
                    } else {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.error || 'Error', showConfirmButton: false, timer: 3000 });
                    }
                });
            });
        }

        /* ───── Toolbar navigation ───── */
        $('cc-prev-btn').addEventListener('click', function () { currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() - 1, 1); renderAll() });
        $('cc-next-btn').addEventListener('click', function () { currentMonth = new Date(currentMonth.getFullYear(), currentMonth.getMonth() + 1, 1); renderAll() });
        $('cc-today-btn').addEventListener('click', function () { var n = new Date(); currentMonth = new Date(n.getFullYear(), n.getMonth(), 1); selectedDay = dk(n); renderAll() });
        $('cc-refresh-btn').addEventListener('click', function () { location.reload() });
        btnCal.addEventListener('click', function () { currentView = 'calendar'; syncView() });
        btnList.addEventListener('click', function () { currentView = 'list'; syncView() });

        /* ───── Init ───── */
        renderAll();
    })();
</script>
<?= $this->endSection() ?>