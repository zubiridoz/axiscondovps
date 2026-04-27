<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<style>
    /* ── DETAIL HEADER ── */
    .ad-header {
        background: #2f3a4d;
        color: #fff;
        padding: 1.25rem 2rem;
        border-radius: 12px;
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .ad-header-back {
        width: 38px; height: 38px;
        border-radius: 50%;
        background: rgba(255,255,255,0.1);
        border: none; color: #fff;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background 0.2s;
        text-decoration: none; font-size: 1.05rem;
    }
    .ad-header-back:hover { background: rgba(255,255,255,0.2); color: #fff; }
    .ad-header h2 { font-size: 1.3rem; font-weight: 700; margin: 0; }
    .ad-header p { font-size: 0.82rem; color: rgba(255,255,255,0.55); margin: 0; }

    /* ── LAYOUT ── */
    .ad-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 1.5rem;
        align-items: start;
    }
    @media (max-width: 992px) {
        .ad-layout { grid-template-columns: 1fr; }
    }

    /* ── IMAGE HERO ── */
    .ad-image-hero {
        width: 100%;
        height: 320px;
        border-radius: 14px;
        overflow: hidden;
        background: #1e293b;
        position: relative;
        margin-bottom: 1.5rem;
    }
    .ad-image-hero img {
        width: 100%; height: 100%;
        object-fit: cover;
    }
    .ad-image-placeholder {
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        height: 100%; color: #475569;
    }
    .ad-image-placeholder i { font-size: 4rem; margin-bottom: 0.5rem; }
    .ad-image-placeholder span { font-size: 0.88rem; }

    /* ── CARD SECTIONS ── */
    .ad-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .ad-card-title {
        font-size: 1.05rem; font-weight: 700; color: #1e293b;
        margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;
    }
    .ad-card-title i { color: #64748b; font-size: 1.1rem; }

    /* ── DESCRIPTION ── */
    .ad-description {
        font-size: 0.88rem; color: #475569; line-height: 1.7;
    }

    /* ── SIDEBAR ── */
    .ad-sidebar-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
    }
    .ad-sidebar-title {
        font-size: 1rem; font-weight: 700; color: #1e293b;
        margin-bottom: 1.15rem; padding-bottom: 0.65rem;
        border-bottom: 1px solid #f1f5f9;
    }

    /* ── RESERVATION INFO ── */
    .ad-info-row {
        display: flex; align-items: flex-start; gap: 0.85rem;
        padding: 0.7rem 0;
        border-bottom: 1px solid #f8fafc;
    }
    .ad-info-row:last-child { border-bottom: none; }
    .ad-info-icon {
        width: 38px; height: 38px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem; flex-shrink: 0;
    }
    .ad-info-icon.clock { background: #eff6ff; color: #3b82f6; }
    .ad-info-icon.users { background: #f0fdf4; color: #22c55e; }
    .ad-info-icon.calendar { background: #fefce8; color: #eab308; }
    .ad-info-icon.dollar { background: #fdf2f8; color: #ec4899; }
    .ad-info-icon.shield { background: #faf5ff; color: #a855f7; }
    .ad-info-label {
        font-size: 0.72rem; font-weight: 600; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 0.3px;
    }
    .ad-info-value {
        font-size: 0.9rem; font-weight: 600; color: #1e293b;
    }

    /* ── SCHEDULE TABLE ── */
    .ad-schedule-row {
        display: flex; justify-content: space-between; align-items: center;
        padding: 0.6rem 0;
        border-bottom: 1px solid #f8fafc;
    }
    .ad-schedule-row:last-child { border-bottom: none; }
    .ad-schedule-day {
        font-size: 0.88rem; font-weight: 600; color: #1e293b;
    }
    .ad-schedule-day.active { color: #2563eb; }
    .ad-schedule-day.disabled-day { color: #cbd5e1; }
    .ad-schedule-time {
        font-size: 0.82rem; color: #64748b; font-weight: 500;
    }
    .ad-schedule-time.active-t { color: #2563eb; font-weight: 600; }
    .ad-schedule-closed {
        font-size: 0.78rem; color: #cbd5e1; font-style: italic;
    }

    /* ── CUSTOM CALENDAR GRID ── */
    .cal-nav {
        display: flex; align-items: center; justify-content: space-between;
        padding: 0.5rem 0 1.25rem;
    }
    .cal-nav-btn {
        background: none; border: 1px solid #e2e8f0;
        width: 34px; height: 34px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; color: #64748b; font-size: 0.9rem;
        transition: all 0.2s;
    }
    .cal-nav-btn:hover { background: #f1f5f9; border-color: #cbd5e1; color: #1e293b; }
    .cal-month-title {
        font-size: 1rem; font-weight: 600; color: #1e293b;
        text-transform: capitalize;
    }
    .cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0;
    }
    .cal-grid-header {
        text-align: center; font-size: 0.75rem; font-weight: 600;
        color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;
        padding: 0 0 0.75rem;
    }
    .cal-day {
        text-align: center; padding: 0.85rem 0.25rem;
        font-size: 0.88rem; color: #334155;
        border-top: 1px solid #f1f5f9;
        position: relative;
        transition: background 0.15s;
    }
    .cal-day.outside { color: #cbd5e1; }
    .cal-day.today {
        font-weight: 700; color: #2563eb;
    }
    .cal-day.available {
        background: #f0fdf4;
        color: #16a34a;
        font-weight: 500;
    }
    .cal-day.reserved {
        background: #fefce8;
        color: #b45309;
        font-weight: 600;
    }
    .cal-day.blocked {
        background: #fff1f2;
        color: #e11d48;
        font-weight: 600;
    }
    .cal-legend {
        display: flex; gap: 1.5rem; justify-content: center;
        padding-top: 1.25rem;
        border-top: 1px solid #f1f5f9;
        margin-top: 0.25rem;
    }
    .cal-legend-item {
        display: flex; align-items: center; gap: 0.4rem;
        font-size: 0.75rem; color: #64748b;
    }
    .cal-legend-dot {
        width: 10px; height: 10px; border-radius: 4px;
    }
    .cal-legend-dot.av { background: #bbf7d0; }
    .cal-legend-dot.rv { background: #fef08a; }
    .cal-legend-dot.bl { background: #fecdd3; }

    /* ── DOCUMENTS ── */
    .ad-doc-item {
        display: flex; align-items: center; gap: 0.75rem;
        padding: 0.75rem 1rem; background: #f8fafc;
        border: 1px solid #e2e8f0; border-radius: 10px;
        margin-bottom: 0.5rem; cursor: pointer;
        transition: all 0.2s;
    }
    .ad-doc-item:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .ad-doc-icon {
        width: 40px; height: 40px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .ad-doc-icon.pdf { background: #fef2f2; color: #ef4444; }
    .ad-doc-icon.img { background: #eff6ff; color: #3b82f6; }
    .ad-doc-icon.doc { background: #f0fdf4; color: #22c55e; }
    .ad-doc-info { flex: 1; }
    .ad-doc-name { font-size: 0.85rem; font-weight: 600; color: #1e293b; }
    .ad-doc-meta { font-size: 0.72rem; color: #94a3b8; }

    /* ── META ── */
    .ad-meta {
        font-size: 0.75rem; color: #94a3b8; margin-top: 0.5rem;
    }

    /* ── ACTION BUTTONS ── */
    .ad-actions {
        display: flex; gap: 0.75rem; justify-content: flex-end;
        padding-top: 0.75rem; border-top: 1px solid #f1f5f9;
        margin-top: 0.75rem;
    }
    .ad-btn-edit {
        background: #fff; border: 1px solid #e2e8f0;
        padding: 0.55rem 1.25rem; border-radius: 8px;
        font-size: 0.85rem; font-weight: 600; color: #1e293b;
        cursor: pointer; transition: all 0.2s;
        text-decoration: none;
        display: flex; align-items: center; gap: 0.35rem;
    }
    .ad-btn-edit:hover { border-color: #94a3b8; background: #f8fafc; color: #1e293b; }
    .ad-btn-delete {
        background: #fef2f2; border: 1px solid #fee2e2;
        padding: 0.55rem 1.25rem; border-radius: 8px;
        font-size: 0.85rem; font-weight: 600; color: #dc2626;
        cursor: pointer; transition: all 0.2s;
        display: flex; align-items: center; gap: 0.35rem;
    }
    .ad-btn-delete:hover {
        background: #fee2e2; border-color: #fecaca;
    }

    /* Status badges */
    .ad-status-badge {
        display: inline-flex; align-items: center; gap: 0.3rem;
        padding: 0.25rem 0.65rem; border-radius: 20px;
        font-size: 0.72rem; font-weight: 600;
    }
    .ad-status-badge.active { background: #f0fdf4; color: #16a34a; }
    .ad-status-badge.inactive { background: #fef2f2; color: #dc2626; }
    .ad-status-badge.reservable { background: #eff6ff; color: #2563eb; }
    .ad-status-badge.approval { background: #faf5ff; color: #9333ea; }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<?php
    $a = $amenity ?? [];
    $dayNames = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
    
    // Build schedule map
    $schedMap = [];
    foreach (($schedules ?? []) as $s) {
        $schedMap[(int)$s['day_of_week']] = $s;
    }

    // Interval display
    $intervalMap = ['1'=>'1 hora','2'=>'2 horas','3'=>'3 horas','4'=>'4 horas','5'=>'5 horas','6'=>'6 horas','full_day'=>'Día completo'];
    $maxResMap   = ['1'=>'1 reserva','2'=>'2 reservas','3'=>'3 reservas','4'=>'4 reservas','5'=>'5 reservas','6'=>'6 reservas','unlimited'=>'Ilimitadas'];

    $intervalDisplay = $intervalMap[$a['reservation_interval'] ?? '1'] ?? '1 hora';
    $maxResDisplay   = $maxResMap[$a['max_active_reservations'] ?? 'unlimited'] ?? 'Ilimitadas';

    // Blocked dates
    $blockedArr = [];
    if (!empty($a['blocked_dates'])) {
        $decoded = json_decode($a['blocked_dates'], true);
        if (is_array($decoded)) $blockedArr = $decoded;
    }

    // Booked dates
    $bookedDates = [];
    foreach (($bookings ?? []) as $b) {
        $d = date('Y-m-d', strtotime($b['start_time']));
        $bookedDates[$d] = true;
    }
?>

<!-- HEADER -->
<div class="ad-header">
    <a href="<?= base_url('admin/amenidades') ?>" class="ad-header-back">
        <i class="bi bi-arrow-left"></i>
    </a>
    <div style="flex:1;">
        <h2><?= esc($a['name'] ?? 'Amenidad') ?></h2>
        <p>Detalles de la amenidad</p>
    </div>
    <div class="d-flex gap-2">
        <?php if (($a['is_active'] ?? 1) == 1): ?>
            <span class="ad-status-badge active"><i class="bi bi-check-circle-fill"></i> Activa</span>
        <?php else: ?>
            <span class="ad-status-badge inactive"><i class="bi bi-x-circle-fill"></i> Inactiva</span>
        <?php endif; ?>
        <?php if (($a['is_reservable'] ?? 1) == 1): ?>
            <span class="ad-status-badge reservable"><i class="bi bi-calendar-check"></i> Reservable</span>
        <?php endif; ?>
        <?php if (($a['requires_approval'] ?? 0) == 1): ?>
            <span class="ad-status-badge approval"><i class="bi bi-shield-check"></i> Aprobación</span>
        <?php endif; ?>
    </div>
</div>

<!-- LAYOUT 2 COLUMNS -->
<div class="ad-layout">
    <!-- LEFT COLUMN -->
    <div>
        <!-- IMAGE HERO -->
        <div class="ad-image-hero">
            <?php if (!empty($a['image'])): ?>
                <img src="<?= base_url('admin/amenidades/imagen/' . $a['image']) ?>" alt="<?= esc($a['name'] ?? '') ?>">
            <?php else: ?>
                <div class="ad-image-placeholder">
                    <i class="bi bi-image-alt"></i>
                    <span>Sin imagen</span>
                </div>
            <?php endif; ?>
        </div>

        <!-- DESCRIPTION -->
        <div class="ad-card">
            <div class="ad-card-title"><i class="bi bi-text-paragraph"></i> Descripción</div>
            <div class="ad-description">
                <?= nl2br(esc($a['description'] ?? 'Sin descripción disponible.')) ?>
            </div>
            <?php if (!empty($a['reservation_message'])): ?>
                <div style="margin-top:1rem; padding:0.85rem 1rem; background:#fffbeb; border:1px solid #fef3c7; border-radius:8px;">
                    <div style="font-size:0.72rem; font-weight:700; color:#d97706; text-transform:uppercase; margin-bottom:0.25rem;">
                        <i class="bi bi-chat-dots"></i> Mensaje de Reserva
                    </div>
                    <div style="font-size:0.85rem; color:#92400e;"><?= nl2br(esc($a['reservation_message'])) ?></div>
                </div>
            <?php endif; ?>
        </div>

        <!-- AVAILABILITY CALENDAR -->
        <div class="ad-card">
            <div class="ad-card-title" style="margin-bottom:0;">Ver Disponibilidad</div>
            <div class="cal-nav">
                <button class="cal-nav-btn" onclick="calPrev()" id="calPrevBtn"><i class="bi bi-chevron-left"></i></button>
                <span class="cal-month-title" id="calMonthTitle"></span>
                <button class="cal-nav-btn" onclick="calNext()" id="calNextBtn"><i class="bi bi-chevron-right"></i></button>
            </div>
            <div class="cal-grid" id="calGrid"></div>
            <div class="cal-legend">
                <div class="cal-legend-item"><div class="cal-legend-dot av"></div> Disponible</div>
                <div class="cal-legend-item"><div class="cal-legend-dot rv"></div> Reservado</div>
                <div class="cal-legend-item"><div class="cal-legend-dot bl"></div> Bloqueado</div>
            </div>
        </div>

        <!-- DOCUMENTS -->
        <?php if (!empty($documents)): ?>
        <div class="ad-card">
            <div class="ad-card-title"><i class="bi bi-file-earmark-text"></i> Documentos y Reglamentos</div>
            <?php foreach ($documents as $doc):
                $ext = strtolower(pathinfo($doc['filename'], PATHINFO_EXTENSION));
                $iconClass = 'doc';
                $icon = 'bi-file-earmark-text';
                if ($ext === 'pdf') { $iconClass = 'pdf'; $icon = 'bi-file-earmark-pdf'; }
                elseif (in_array($ext, ['jpg','jpeg','png','gif','webp'])) { $iconClass = 'img'; $icon = 'bi-file-earmark-image'; }
            ?>
            <a class="ad-doc-item" href="<?= base_url('admin/amenidades/documento/' . $doc['filename']) ?>" target="_blank">
                <div class="ad-doc-icon <?= $iconClass ?>"><i class="bi <?= $icon ?>"></i></div>
                <div class="ad-doc-info">
                    <div class="ad-doc-name"><?= esc($doc['title']) ?></div>
                    <div class="ad-doc-meta"><?= number_format(($doc['file_size'] ?? 0) / 1048576, 2) ?> MB</div>
                </div>
                <i class="bi bi-download" style="color:#94a3b8;"></i>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- RIGHT SIDEBAR -->
    <div>
        <!-- RESERVATION INFO -->
        <?php if (($a['is_reservable'] ?? 1) == 1): ?>
        <div class="ad-sidebar-card">
            <div class="ad-sidebar-title">Información de Reserva</div>

            <div class="ad-info-row">
                <div class="ad-info-icon clock"><i class="bi bi-clock"></i></div>
                <div>
                    <div class="ad-info-label">Intervalo de Reserva</div>
                    <div class="ad-info-value"><?= $intervalDisplay ?></div>
                </div>
            </div>

            <div class="ad-info-row">
                <div class="ad-info-icon users"><i class="bi bi-people"></i></div>
                <div>
                    <div class="ad-info-label">Máximo de Reservas Activas</div>
                    <div class="ad-info-value"><?= $maxResDisplay ?></div>
                </div>
            </div>

            <div class="ad-info-row">
                <div class="ad-info-icon calendar"><i class="bi bi-calendar-event"></i></div>
                <div>
                    <div class="ad-info-label">Período de Disponibilidad</div>
                    <div class="ad-info-value">
                        <?php
                        $from = $a['available_from'] ?? null;
                        if ($from) {
                            $dateObj = DateTime::createFromFormat('Y-m-d', $from);
                            echo $dateObj ? $dateObj->format('M d, Y') : $from;
                        } else {
                            echo 'Siempre';
                        }
                        ?> — Sin fecha de fin
                    </div>
                </div>
            </div>

            <?php if (($a['has_cost'] ?? 0) == 1 && !empty($a['price'])): ?>
            <div class="ad-info-row">
                <div class="ad-info-icon dollar"><i class="bi bi-currency-dollar"></i></div>
                <div>
                    <div class="ad-info-label">Costo por Reserva</div>
                    <div class="ad-info-value">$<?= number_format((float)$a['price'], 2) ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if (($a['requires_approval'] ?? 0) == 1): ?>
            <div class="ad-info-row">
                <div class="ad-info-icon shield"><i class="bi bi-shield-check"></i></div>
                <div>
                    <div class="ad-info-label">Aprobación</div>
                    <div class="ad-info-value">Requiere aprobación manual</div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- WEEKLY SCHEDULE -->
        <div class="ad-sidebar-card">
            <div class="ad-sidebar-title">Horario de Apertura</div>
            <?php for ($d = 0; $d < 7; $d++):
                $sched = $schedMap[$d] ?? null;
                $enabled = $sched ? (int)$sched['is_enabled'] : ($d < 5 ? 1 : 0);
                $openT = $sched ? substr($sched['open_time'], 0, 5) : '09:00';
                $closeT = $sched ? substr($sched['close_time'], 0, 5) : '18:00';
            ?>
            <div class="ad-schedule-row">
                <div class="ad-schedule-day <?= $enabled ? 'active' : 'disabled-day' ?>"><?= $dayNames[$d] ?></div>
                <?php if ($enabled): ?>
                    <div class="ad-schedule-time active-t">
                        <?= date('g:i A', strtotime($openT)) ?> - <?= date('g:i A', strtotime($closeT)) ?>
                    </div>
                <?php else: ?>
                    <div class="ad-schedule-closed">Cerrado</div>
                <?php endif; ?>
            </div>
            <?php endfor; ?>
        </div>

        <!-- META + ACTIONS -->
        <div class="ad-sidebar-card">
            <div class="ad-meta">
                <div><strong>Creado:</strong> <?= !empty($a['created_at']) ? date('M d, Y', strtotime($a['created_at'])) : 'N/A' ?></div>
                <div><strong>Última actualización:</strong> <?= !empty($a['updated_at']) ? date('M d, Y', strtotime($a['updated_at'])) : 'N/A' ?></div>
            </div>
            <div class="ad-actions">
                <a href="<?= base_url('admin/amenidades/editar/' . ($a['hash_id'] ?? '')) ?>" class="ad-btn-edit">
                    <i class="bi bi-pencil"></i> Editar Amenidad
                </a>
                <button class="ad-btn-delete" onclick="deleteAmenity()">
                    <i class="bi bi-trash"></i> Eliminar Amenidad
                </button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const BASE = '<?= base_url() ?>';
const AMENITY_ID = <?= (int)($a['id'] ?? 0) ?>;
const BLOCKED_DATES = <?= json_encode($blockedArr) ?>;
const BOOKED_DATES = <?= json_encode(array_keys($bookedDates)) ?>;

// ══════ CUSTOM GRID CALENDAR ══════
const MONTH_NAMES = [
    'enero','febrero','marzo','abril','mayo','junio',
    'julio','agosto','septiembre','octubre','noviembre','diciembre'
];
const DAY_HEADERS = ['D','L','M','M','J','V','S'];

let calYear, calMonth;

function initCalendar() {
    const now = new Date();
    calYear = now.getFullYear();
    calMonth = now.getMonth();
    renderCalendar();
}

function calPrev() { calMonth--; if (calMonth < 0) { calMonth = 11; calYear--; } renderCalendar(); }
function calNext() { calMonth++; if (calMonth > 11) { calMonth = 0; calYear++; } renderCalendar(); }

function pad(n) { return String(n).padStart(2, '0'); }

function renderCalendar() {
    // Update title
    document.getElementById('calMonthTitle').textContent =
        MONTH_NAMES[calMonth] + ' de ' + calYear;

    const grid = document.getElementById('calGrid');
    grid.innerHTML = '';

    // Day headers (Sun-Sat)
    DAY_HEADERS.forEach(d => {
        const hdr = document.createElement('div');
        hdr.className = 'cal-grid-header';
        hdr.textContent = d;
        grid.appendChild(hdr);
    });

    // Determine first day & total days
    const firstDay = new Date(calYear, calMonth, 1).getDay(); // 0=Sun
    const totalDays = new Date(calYear, calMonth + 1, 0).getDate();
    const prevMonthDays = new Date(calYear, calMonth, 0).getDate();

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    // Previous month fill
    for (let i = firstDay - 1; i >= 0; i--) {
        const cell = document.createElement('div');
        cell.className = 'cal-day outside';
        cell.textContent = prevMonthDays - i;
        grid.appendChild(cell);
    }

    // Current month days
    for (let d = 1; d <= totalDays; d++) {
        const cell = document.createElement('div');
        const dateStr = calYear + '-' + pad(calMonth + 1) + '-' + pad(d);
        const dateObj = new Date(calYear, calMonth, d);
        let cls = 'cal-day';

        // Check today
        if (dateObj.getTime() === today.getTime()) cls += ' today';

        // Status classes (priority: blocked > reserved > available)
        if (BLOCKED_DATES.includes(dateStr)) {
            cls += ' blocked';
        } else if (BOOKED_DATES.includes(dateStr)) {
            cls += ' reserved';
        } else if (dateObj >= today) {
            cls += ' available';
        }

        cell.className = cls;
        cell.textContent = d;
        grid.appendChild(cell);
    }

    // Next month fill (complete last row)
    const totalCells = firstDay + totalDays;
    const remaining = totalCells % 7 === 0 ? 0 : 7 - (totalCells % 7);
    for (let i = 1; i <= remaining; i++) {
        const cell = document.createElement('div');
        cell.className = 'cal-day outside';
        cell.textContent = i;
        grid.appendChild(cell);
    }
}

document.addEventListener('DOMContentLoaded', initCalendar);

// ══════ DELETE ══════
function deleteAmenity() {
    Swal.fire({
        title: '¿Eliminar Amenidad?',
        text: 'Esta acción no se puede deshacer. Las reservas asociadas permanecerán en el historial.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-trash me-1"></i> Eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#64748b',
        customClass: { popup: 'rounded-3' }
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const r = await fetch(BASE + 'admin/amenidades/eliminar/' + AMENITY_ID, {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const j = await r.json();
                if (j.status === 200) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Amenidad Eliminada',
                        text: j.message || 'La amenidad ha sido eliminada exitosamente.',
                        timer: 2000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                    });
                    setTimeout(() => {
                        window.location.href = BASE + 'admin/amenidades';
                    }, 1800);
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: j.error || 'No se pudo eliminar', confirmButtonColor: '#1e293b' });
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error de conexión', text: err.message, confirmButtonColor: '#1e293b' });
            }
        }
    });
}
</script>
<?= $this->endSection() ?>
