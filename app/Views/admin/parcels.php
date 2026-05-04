<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$allParcels = is_array($parcels ?? null) ? $parcels : [];

$resolveStatus = static function ($rawStatus): array {
    $status = strtolower((string) $rawStatus);

    if (in_array($status, ['pending', 'at_gate'], true)) {
        return [
            'key' => 'pending',
            'label' => 'En caseta',
            'icon' => 'bi-clock-history',
            'class' => 'bg-warning-subtle text-warning-emphasis border-warning-subtle',
        ];
    }

    if (in_array($status, ['delivered', 'delivered_to_resident'], true)) {
        return [
            'key' => 'delivered',
            'label' => 'Entregado',
            'icon' => 'bi-check-circle',
            'class' => 'bg-success-subtle text-success-emphasis koti-card-green-subtle',
        ];
    }

    if ($status === 'returned') {
        return [
            'key' => 'returned',
            'label' => 'Devuelto',
            'icon' => 'bi-arrow-counterclockwise',
            'class' => 'bg-danger-subtle text-danger-emphasis border-danger-subtle',
        ];
    }

    return [
        'key' => 'unknown',
        'label' => 'Sin clasificar',
        'icon' => 'bi-question-circle',
        'class' => 'bg-secondary-subtle text-secondary-emphasis border-secondary-subtle',
    ];
};

$request = service('request');
$viewMode = strtolower((string) $request->getGet('vista'));
$isHistory = $viewMode === 'historial';

$selectedMonth = (string) $request->getGet('mes');
if (!preg_match('/^\d{4}-\d{2}$/', $selectedMonth)) {
    $selectedMonth = date('Y-m');
}
$selectedMonthTs = strtotime($selectedMonth . '-01');
if (!$selectedMonthTs) {
    $selectedMonthTs = strtotime(date('Y-m-01'));
    $selectedMonth = date('Y-m', $selectedMonthTs);
}

$monthNamesEs = [
    1 => 'enero',
    2 => 'febrero',
    3 => 'marzo',
    4 => 'abril',
    5 => 'mayo',
    6 => 'junio',
    7 => 'julio',
    8 => 'agosto',
    9 => 'septiembre',
    10 => 'octubre',
    11 => 'noviembre',
    12 => 'diciembre',
];
$historyMonthLabel = ($monthNamesEs[(int) date('n', $selectedMonthTs)] ?? date('F', $selectedMonthTs)) . ' ' . date('Y', $selectedMonthTs);
$prevMonth = date('Y-m', strtotime($selectedMonth . '-01 -1 month'));
$nextMonth = date('Y-m', strtotime($selectedMonth . '-01 +1 month'));

$baseParcelUrl = base_url('admin/paqueteria');
$historyDefaultUrl = $baseParcelUrl . '?vista=historial&mes=' . date('Y-m');
$historyPrevUrl = $baseParcelUrl . '?vista=historial&mes=' . $prevMonth;
$historyNextUrl = $baseParcelUrl . '?vista=historial&mes=' . $nextMonth;
$backToMainUrl = $baseParcelUrl;

$today = date('Y-m-d');
$stats = ['total' => 0, 'pending' => 0, 'delivered_today' => 0, 'attention' => 0];

$normalized = [];
$timeAgo = static function ($dateStr): string {
    if (!$dateStr)
        return 'Sin fecha';
    $diff = time() - strtotime($dateStr);
    if ($diff < 60)
        return 'hace un momento';
    if ($diff < 3600)
        return 'hace ' . floor($diff / 60) . ' minutos';
    if ($diff < 86400)
        return 'hace ' . floor($diff / 3600) . ' hora' . (floor($diff / 3600) > 1 ? 's' : '');
    return 'hace ' . floor($diff / 86400) . ' día' . (floor($diff / 86400) > 1 ? 's' : '');
};

foreach ($allParcels as $parcel) {
    $statusMeta = $resolveStatus($parcel['status'] ?? '');
    $createdRaw = (string) ($parcel['created_at'] ?? '');
    $createdTs = $createdRaw !== '' ? strtotime($createdRaw) : false;

    $deliveredRaw = (string) ($parcel['delivered_at'] ?? ($parcel['updated_at'] ?? ''));
    $deliveredTs = $deliveredRaw !== '' ? strtotime($deliveredRaw) : false;

    if ($statusMeta['key'] === 'pending') {
        $stats['total']++;
        $stats['pending']++;
    }

    if ($statusMeta['key'] === 'delivered' && $deliveredTs && date('Y-m-d', $deliveredTs) === $today) {
        $stats['delivered_today']++;
    }

    if ($statusMeta['key'] === 'returned' || ($statusMeta['key'] === 'pending' && $createdTs && (time() - $createdTs) > 172800)) {
        $stats['attention']++;
    }

    $normalized[] = [
        'id' => (int) ($parcel['id'] ?? 0),
        'status' => $statusMeta,
        'unit_number' => trim((string) ($parcel['unit_number'] ?? 'Sin unidad')),
        'section_name' => trim((string) ($parcel['section_name'] ?? '')),
        'courier' => trim((string) ($parcel['courier'] ?? 'Sin transportista')),
        'quantity' => (int) ($parcel['quantity'] ?? 1),
        'parcel_type' => trim((string) ($parcel['parcel_type'] ?? 'Paquete')),
        'time_ago' => $timeAgo($createdRaw),
        'created_at' => $createdRaw,
        'created_month' => $createdTs ? date('Y-m', $createdTs) : '',
        'picked_up_name' => trim((string) ($parcel['picked_up_name'] ?? '')),
        'delivered_at' => $deliveredRaw,
        'resident_names' => trim((string) ($parcel['resident_names'] ?? 'Sin asignar')),
        'search' => strtolower(implode(' ', [
            $parcel['unit_number'] ?? '',
            $parcel['courier'] ?? '',
            $parcel['section_name'] ?? '',
            $parcel['picked_up_name'] ?? '',
            $statusMeta['label'],
        ])),
    ];
}

$mainRows = array_values(array_filter($normalized, fn($item) => $item['status']['key'] === 'pending'));
$historyRows = array_values(array_filter($normalized, fn($item) => $item['status']['key'] === 'delivered' && $item['created_month'] === $selectedMonth));

// Calendar data for history view
$selYear = (int) date('Y', $selectedMonthTs);
$selMonth = (int) date('n', $selectedMonthTs);
$daysInMonth = (int) date('t', $selectedMonthTs);
$firstDayOfWeek = (int) date('w', $selectedMonthTs); // 0=Sun

// Build set of days that had parcels
$parcelDays = [];
foreach ($historyRows as $row) {
    if (!empty($row['created_at'])) {
        $d = (int) date('j', strtotime($row['created_at']));
        $parcelDays[$d] = ($parcelDays[$d] ?? 0) + 1;
    }
}

// Pagination
$perPage = 15;
$currentPage = max(1, (int) ($request->getGet('page') ?? 1));
$activeRows = $isHistory ? $historyRows : $mainRows;
$totalRows = count($activeRows);
$totalPages = max(1, (int) ceil($totalRows / $perPage));
$currentPage = min($currentPage, $totalPages);
$offset = ($currentPage - 1) * $perPage;
$pageRows = array_slice($activeRows, $offset, $perPage);
?>

<style>
    .parcel-header {
        background: linear-gradient(135deg, #2f3a4d 0%, #243246 100%);
        border-radius: 0.6rem;
        padding: 1.5rem;
        color: #fff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.16);
    }

    .parcel-header p {
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 0;
        font-size: 0.92rem;
    }

    .parcel-history-btn {
        background: rgba(255, 255, 255, 0.13);
        color: #fff;
        border: 1px solid rgba(255, 255, 255, 0.16);
        font-size: 0.82rem;
        font-weight: 600;
    }

    .parcel-history-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        border-color: rgba(255, 255, 255, 0.28);
        color: #fff;
    }

    .parcel-history-back {
        width: 28px;
        height: 28px;
        border-radius: 0.45rem;
        border: 1px solid #1D4C9D;
        background: #1D4C9D;
        color: rgba(255, 255, 255, 0.95);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .parcel-history-back:hover {
        color: #1D4C9D;
        background: rgba(141, 141, 141, 0.24)
    }

    .parcel-stat-card {
        border: 1px solid #d9e1eb;
        border-radius: 0.6rem;
        background: #fff;
        padding: 1rem 1.2rem;
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }

    .parcel-stat-card:hover {
        border-color: #c8d3df;
        box-shadow: 6px 8px 16px rgba(15, 23, 42, 0.12);
        transform: translateY(-1px);
    }

    .parcel-stat-label {
        font-size: 0.82rem;
        color: #64748b;
        margin-bottom: 0.35rem;
        font-weight: 500;
    }

    .parcel-stat-value {
        margin: 0;
        color: #0f172a;
        font-size: 2rem;
        line-height: 1;
        font-weight: 700;
    }

    .parcel-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .parcel-panel {
        border: 1px solid #d9e1eb;
        border-radius: 0.6rem;
    }

    .parcel-search-wrap {
        position: relative;
        max-width: 430px;
        width: 100%;
    }

    .parcel-search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.9rem;
    }

    .parcel-search {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: 0.45rem;
        padding: 0.55rem 0.85rem 0.55rem 2rem;
        font-size: 0.88rem;
        color: #334155;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .parcel-search:focus {
        outline: none;
        border-color: #94a3b8;
        box-shadow: 0 0 0 4px rgba(148, 163, 184, 0.14);
    }

    .parcel-refresh-btn {
        width: 38px;
        height: 38px;
        border: 1px solid #d0d8e2;
        border-radius: 0.45rem;
        background: #fff;
        color: #334155;
    }

    .parcel-refresh-btn:hover {
        background: #f8fafc;
        border-color: #c5d0dc;
    }

    .parcel-table thead th {
        font-size: 0.74rem;
        color: #64748b;
        font-weight: 600;
        letter-spacing: 0.02em;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.85rem 0.9rem;
    }

    .parcel-table tbody td {
        border-bottom: 1px solid #eef2f7;
        color: #334155;
        font-size: 0.86rem;
        vertical-align: middle;
        padding: 0.9rem;
    }

    .parcel-table tbody tr {
        cursor: pointer;
        transition: background 0.15s;
    }

    .parcel-table tbody tr:hover td {
        background: #f8fafc;
    }

    .parcel-table {
        overflow: visible !important;
    }

    .table-responsive {
        overflow: visible !important;
    }

    .parcel-actions-dropdown {
        position: relative;
    }

    .parcel-actions-dropdown .dropdown-menu {
        min-width: 220px;
        box-shadow: 0 10px 32px rgba(0, 0, 0, 0.15);
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        z-index: 1050;
        position: absolute;
    }

    .parcel-actions-dropdown .dropdown-item {
        font-size: 0.86rem;
        padding: 0.6rem 1rem;
    }

    .parcel-actions-dropdown .dropdown-item:hover {
        background: #f1f5f9;
    }

    .parcel-empty {
        min-height: 350px;
        border: 1px dashed #d9e1eb;
        border-radius: 0.75rem;
        background: #fbfdff;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .parcel-empty-icon {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        border: 1px solid #f4d090;
        color: #d97706;
        background: #fffdf8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .parcel-empty-title {
        color: #0f172a;
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .parcel-empty-desc {
        color: #3F67AC;
        max-width: 480px;
        margin: 0 auto;
        font-size: 0.9rem;
    }

    /* Detail Modal */
    .parcel-detail-modal .modal-dialog {
        max-width: 780px;
    }

    .parcel-detail-modal .modal-content {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }

    .parcel-detail-modal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.2rem 1.5rem;
    }

    .parcel-detail-modal .modal-body {
        padding: 1.5rem;
    }

    .detail-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        padding: 1.2rem;
        margin-bottom: 1rem;
    }

    .detail-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 1rem;
    }

    .detail-card-title i {
        color: #3b82f6;
    }

    .detail-label {
        font-size: 0.72rem;
        font-weight: 600;
        color: #3b82f6;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        margin-bottom: 2px;
    }

    .detail-value {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
    }

    .detail-value-sm {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0f172a;
    }

    /* Timeline */
    .timeline-list {
        position: relative;
        padding-left: 28px;
    }

    .timeline-list::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        background: #e2e8f0;
    }

    .tl-item {
        position: relative;
        margin-bottom: 18px;
    }

    .tl-item:last-child {
        margin-bottom: 0;
    }

    .tl-dot {
        position: absolute;
        left: -22px;
        top: 3px;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        border: 2px solid;
    }

    .tl-dot.received {
        background: #dbeafe;
        border-color: #3b82f6;
    }

    .tl-dot.waiting {
        background: #fef3c7;
        border-color: #f59e0b;
    }

    .tl-dot.delivered {
        background: #dcfce7;
        border-color: #22c55e;
    }

    .tl-date {
        font-size: 0.78rem;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .tl-event {
        font-size: 0.88rem;
        font-weight: 600;
        color: #0f172a;
    }

    /* Photo thumbnail + lightbox */
    .parcel-photo-thumb {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 0.6rem;
        cursor: pointer;
        transition: transform 0.2s;
        border: 1px solid #e2e8f0;
    }

    .parcel-photo-thumb:hover {
        transform: scale(1.02);
    }

    .photo-overlay-text {
        position: absolute;
        bottom: 8px;
        left: 0;
        right: 0;
        text-align: center;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.9);
        background: rgba(0, 0, 0, 0.5);
        padding: 4px 8px;
        border-radius: 4px;
        width: fit-content;
        margin: 0 auto;
        pointer-events: none;
    }

    /* Receipt footer */
    .modal-receipt-footer {
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        border-radius: 0 0 0.75rem 0.75rem;
        padding: 1rem 1.5rem;
    }

    .tracking-code {
        font-family: 'SFMono-Regular', 'Consolas', monospace;
        font-size: 0.82rem;
        color: #64748b;
    }

    /* Calendar */
    .parcel-calendar {
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }

    .parcel-cal-header {
        background: #f8fafc;
        padding: 0.8rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e2e8f0;
    }

    .parcel-cal-header .cal-title {
        font-weight: 700;
        color: #0f172a;
        font-size: 0.95rem;
        text-transform: capitalize;
    }

    .parcel-cal-nav {
        width: 30px;
        height: 30px;
        border-radius: 0.4rem;
        border: 1px solid #d0d8e2;
        background: #fff;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: background 0.15s;
    }

    .parcel-cal-nav:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .parcel-cal-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
    }

    .parcel-cal-day-label {
        text-align: center;
        font-size: 0.7rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        padding: 8px 4px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
    }

    .parcel-cal-cell {
        text-align: center;
        padding: 8px 4px;
        font-size: 0.82rem;
        color: #334155;
        position: relative;
        min-height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-bottom: 1px solid #f8fafc;
    }

    .parcel-cal-cell.empty {
        color: transparent;
    }

    .parcel-cal-cell.today {
        font-weight: 700;
        color: #fff;
    }

    .parcel-cal-cell.today::before {
        content: '';
        position: absolute;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #3b82f6;
        z-index: 0;
    }

    .parcel-cal-cell.today span {
        position: relative;
        z-index: 1;
    }

    .parcel-cal-cell.has-parcels::after {
        content: '';
        position: absolute;
        bottom: 4px;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #f59e0b;
    }

    .parcel-cal-cell.has-parcels.today::after {
        background: #fbbf24;
    }

    .pagination-wrapper {
        font-size: 0.85rem;
    }

    /* Confirm Deliver Modal */
    .confirm-deliver-modal .modal-dialog {
        max-width: 460px;
    }

    .confirm-deliver-modal .modal-content {
        border-radius: 0.75rem;
        border: none;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.18);
    }

    .confirm-deliver-modal .modal-body {
        padding: 1.5rem;
    }

    .confirm-deliver-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }

    .confirm-deliver-title .warning-icon {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: #fef3c7;
        color: #d97706;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .confirm-deliver-desc {
        font-size: 0.86rem;
        color: #3F67AC;
        line-height: 1.55;
        margin-bottom: 1rem;
    }

    .confirm-deliver-info {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        padding: 1rem 1.1rem;
        margin-bottom: 1rem;
    }

    .confirm-deliver-info .info-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0.4rem;
        font-size: 0.88rem;
        color: #334155;
    }

    .confirm-deliver-info .info-row:last-child {
        margin-bottom: 0;
    }

    .confirm-deliver-info .info-row i {
        color: #64748b;
        font-size: 0.82rem;
        width: 16px;
        text-align: center;
    }

    .confirm-deliver-info .info-row strong {
        color: #0f172a;
        font-weight: 600;
    }

    .confirm-deliver-warning {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        color: #92400e;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 0.5rem;
        padding: 0.6rem 0.9rem;
        margin-bottom: 1.2rem;
    }

    .confirm-deliver-warning i {
        color: #d97706;
    }

    .confirm-deliver-actions {
        display: flex;
        gap: 0.6rem;
        justify-content: flex-end;
    }

    .confirm-deliver-actions .btn-cancel {
        background: #fff;
        border: 1px solid #d0d8e2;
        color: #334155;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.55rem 1.3rem;
        font-size: 0.88rem;
    }

    .confirm-deliver-actions .btn-cancel:hover {
        background: #f8fafc;
        border-color: #c5d0dc;
    }

    .confirm-deliver-actions .btn-confirm {
        background: linear-gradient(135deg, #2f3a4d 0%, #243246 100%);
        color: #fff;
        border: none;
        font-weight: 600;
        border-radius: 0.5rem;
        padding: 0.55rem 1.3rem;
        font-size: 0.88rem;
    }

    .confirm-deliver-actions .btn-confirm:hover {
        background: linear-gradient(135deg, #243246 0%, #1a2535 100%);
    }

    /* Timeline Summary */
    .tl-summary {
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        padding: 1rem 1.2rem;
        margin-top: 1rem;
    }

    .tl-summary-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.85rem;
    }

    .tl-summary-grid {
        display: flex;
        gap: 1.5rem;
        align-items: center;
    }

    .tl-summary-item {
        text-align: center;
    }

    .tl-summary-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #3b82f6;
        line-height: 1.2;
    }

    .tl-summary-label {
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 500;
        margin-top: 2px;
    }

    .tl-summary-check {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #dcfce7;
    }

    .tl-summary-check i {
        color: #22c55e;
        font-size: 1rem;
    }

    @media (max-width: 991px) {
        .parcel-header .d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 1rem;
        }

        .parcel-detail-modal .modal-dialog {
            max-width: 95%;
        }
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

    /* ── end Hero ── */
</style>

<?php if ($isHistory): ?>
    <!-- ========================================== -->
    <!-- HISTORY VIEW                               -->
    <!-- ========================================== -->


    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <a href="<?= esc($backToMainUrl) ?>" class="parcel-history-back" aria-label="Regresar">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="cc-hero-title">Historial de Paquetes</h2>
            <div class="cc-hero-divider"></div>
            <div class="cc-hero-breadcrumb">
                <i class="bi bi-box-seam"></i>
                <i class="bi bi-chevron-right"></i>
                Consulta el historial de entregas por mes
            </div>
        </div>

    </div>


    <!-- Premium Calendar -->
    <div class="parcel-calendar">
        <div class="parcel-cal-header">
            <a href="<?= esc($historyPrevUrl) ?>" class="parcel-cal-nav"><i class="bi bi-chevron-left"></i></a>
            <span class="cal-title"><?= esc($historyMonthLabel) ?></span>
            <a href="<?= esc($historyNextUrl) ?>" class="parcel-cal-nav"><i class="bi bi-chevron-right"></i></a>
        </div>
        <div class="parcel-cal-grid">
            <?php foreach (['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'] as $dl): ?>
                <div class="parcel-cal-day-label"><?= $dl ?></div>
            <?php endforeach; ?>
            <?php for ($i = 0; $i < $firstDayOfWeek; $i++): ?>
                <div class="parcel-cal-cell empty">&nbsp;</div>
            <?php endfor; ?>
            <?php for ($d = 1; $d <= $daysInMonth; $d++):
                $isToday = ($d == (int) date('j') && $selMonth == (int) date('n') && $selYear == (int) date('Y'));
                $hasParcels = isset($parcelDays[$d]);
                $classes = 'parcel-cal-cell';
                if ($isToday)
                    $classes .= ' today';
                if ($hasParcels)
                    $classes .= ' has-parcels';
                ?>
                <div class="<?= $classes ?>" <?= $hasParcels ? 'title="' . $parcelDays[$d] . ' paquete(s)"' : '' ?>>
                    <span><?= $d ?></span>
                </div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Search -->
    <div class="card shadow-sm parcel-panel">
        <div class="card-body p-3 p-lg-4">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <div class="parcel-search-wrap">
                    <i class="bi bi-search"></i>
                    <input id="parcel-search" type="text" class="parcel-search"
                        placeholder="Buscar por unidad, transportista o residente...">
                </div>
                <button id="parcel-refresh" type="button"
                    class="parcel-refresh-btn d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>

            <?php if (empty($pageRows)): ?>
                <div class="parcel-empty">
                    <div>
                        <div class="parcel-empty-icon"><i class="bi bi-box-seam"></i></div>
                        <h2 class="parcel-empty-title">Sin registros</h2>
                        <p class="parcel-empty-desc">No hay paquetes registrados para este mes.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive" id="parcel-table-wrap">
                    <table class="table parcel-table mb-0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="form-check-input" disabled></th>
                                <th>Estado</th>
                                <th>Unidad <i class="bi bi-arrow-down-up small"></i></th>
                                <th>Transportista <i class="bi bi-arrow-down-up small"></i></th>
                                <th>Fecha de Llegada <i class="bi bi-arrow-down small"></i></th>
                                <th>Cantidad <i class="bi bi-arrow-down-up small"></i></th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pageRows as $row): ?>
                                <tr class="parcel-row" data-id="<?= $row['id'] ?>" data-search="<?= esc($row['search']) ?>">
                                    <td><input type="checkbox" class="form-check-input"></td>
                                    <td>
                                        <span class="badge border rounded-pill px-2 py-1 <?= esc($row['status']['class']) ?>">
                                            <i
                                                class="bi <?= esc($row['status']['icon']) ?> me-1"></i><?= esc($row['status']['label']) ?>
                                        </span>
                                    </td>
                                    <td><span class="fw-semibold"><?= esc($row['unit_number']) ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="bi bi-box-seam text-secondary"></i>
                                            <?= esc($row['courier']) ?>
                                        </div>
                                    </td>
                                    <td><?= esc($row['time_ago']) ?></td>
                                    <td><span class="text-primary fw-semibold"><?= $row['quantity'] ?></span></td>
                                    <td>
                                        <div class="parcel-actions-dropdown dropdown">
                                            <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:;"
                                                        onclick="openParcelDetail(<?= $row['id'] ?>)"><i
                                                            class="bi bi-eye me-2 text-primary"></i>Ver detalles</a></li>
                                                <li><a class="dropdown-item"
                                                        href="<?= base_url('admin/paqueteria/comprobante/' . $row['id']) ?>"
                                                        target="_blank"><i class="bi bi-download me-2 text-secondary"></i>Descargar
                                                        comprobante</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3 pagination-wrapper">
                    <span class="text-secondary">Mostrando <?= $offset + 1 ?>-<?= min($offset + $perPage, $totalRows) ?> de
                        <?= $totalRows ?></span>
                    <?php if ($totalPages > 1): ?>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                        href="<?= $baseParcelUrl ?>?vista=historial&mes=<?= $selectedMonth ?>&page=1">«</a>
                                </li>
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                        href="<?= $baseParcelUrl ?>?vista=historial&mes=<?= $selectedMonth ?>&page=<?= $currentPage - 1 ?>">‹</a>
                                </li>
                                <li class="page-item disabled"><span class="page-link">Página <?= $currentPage ?> de
                                        <?= $totalPages ?></span></li>
                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                        href="<?= $baseParcelUrl ?>?vista=historial&mes=<?= $selectedMonth ?>&page=<?= $currentPage + 1 ?>">›</a>
                                </li>
                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link"
                                        href="<?= $baseParcelUrl ?>?vista=historial&mes=<?= $selectedMonth ?>&page=<?= $totalPages ?>">»</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

<?php else: ?>
    <!-- ========================================== -->
    <!-- MAIN VIEW (PENDING PARCELS)                -->
    <!-- ========================================== -->

    <!-- ── Hero ── -->
    <div class="cc-hero">
        <div class="cc-hero-left">
            <h2 class="cc-hero-title">Paquetería</h2>
            <div class="cc-hero-divider"></div>
            <div class="cc-hero-breadcrumb">
                <i class="bi bi-box-seam"></i>
                <i class="bi bi-chevron-right"></i>
                Entregas y seguimiento de paquetes
            </div>
        </div>
        <div class="cc-hero-right">
            <a href="<?= esc($historyDefaultUrl) ?>" class="cc-hero-btn">
                <i class="bi bi-clock-history me-1"></i> Ver Historial
            </a>

        </div>
    </div>
    <!-- ── END Hero ── -->




    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="parcel-stat-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="parcel-stat-label">Total de Paquetes</p>
                    <p class="parcel-stat-value"><?= esc((string) $stats['total']) ?></p>
                </div>
                <span class="parcel-stat-icon bg-primary-subtle text-primary-emphasis"><i class="bi bi-box-seam"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="parcel-stat-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="parcel-stat-label">Pendientes de Recogida</p>
                    <p class="parcel-stat-value"><?= esc((string) $stats['pending']) ?></p>
                </div>
                <span class="parcel-stat-icon bg-warning-subtle text-warning-emphasis"><i
                        class="bi bi-clock-history"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="parcel-stat-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="parcel-stat-label">Entregados Hoy</p>
                    <p class="parcel-stat-value"><?= esc((string) $stats['delivered_today']) ?></p>
                </div>
                <span class="parcel-stat-icon bg-success-subtle text-success-emphasis"><i
                        class="bi bi-check-circle"></i></span>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="parcel-stat-card d-flex justify-content-between align-items-center">
                <div>
                    <p class="parcel-stat-label">Necesitan Atención</p>
                    <p class="parcel-stat-value"><?= esc((string) $stats['attention']) ?></p>
                </div>
                <span class="parcel-stat-icon bg-danger-subtle text-danger-emphasis"><i
                        class="bi bi-exclamation-triangle"></i></span>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm parcel-panel">
        <div class="card-body p-3 p-lg-4">
            <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                <div class="parcel-search-wrap">
                    <i class="bi bi-search"></i>
                    <input id="parcel-search" type="text" class="parcel-search"
                        placeholder="Buscar paquetes por número de unidad o transportista...">
                </div>
                <button id="parcel-refresh" type="button"
                    class="parcel-refresh-btn d-inline-flex align-items-center justify-content-center">
                    <i class="bi bi-arrow-clockwise"></i>
                </button>
            </div>

            <?php if (empty($pageRows)): ?>
                <div class="parcel-empty">
                    <div>
                        <div class="parcel-empty-icon"><i class="bi bi-box-seam"></i></div>
                        <h2 class="parcel-empty-title">Sin paquetes pendientes</h2>
                        <p class="parcel-empty-desc">No hay paquetes esperando recogida. Los paquetes aparecerán aquí cuando
                            lleguen a caseta.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="table-responsive" id="parcel-table-wrap">
                    <table class="table parcel-table mb-0">
                        <thead>
                            <tr>
                                <th><input type="checkbox" class="form-check-input" disabled></th>
                                <th>Estado</th>
                                <th>Unidad <i class="bi bi-arrow-down-up small"></i></th>
                                <th>Transportista <i class="bi bi-arrow-down-up small"></i></th>
                                <th>Fecha de Llegada <i class="bi bi-arrow-down small"></i></th>
                                <th>Cantidad <i class="bi bi-arrow-down-up small"></i></th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pageRows as $row): ?>
                                <tr class="parcel-row" data-id="<?= $row['id'] ?>" data-search="<?= esc($row['search']) ?>"
                                    data-unit="<?= esc($row['unit_number']) ?>" data-courier="<?= esc($row['courier']) ?>"
                                    data-timeago="<?= esc($row['time_ago']) ?>">
                                    <td><input type="checkbox" class="form-check-input"></td>
                                    <td>
                                        <span class="badge border rounded-pill px-2 py-1 <?= esc($row['status']['class']) ?>">
                                            <i
                                                class="bi <?= esc($row['status']['icon']) ?> me-1"></i><?= esc($row['status']['label']) ?>
                                        </span>
                                    </td>
                                    <td><span class="fw-semibold"><?= esc($row['unit_number']) ?></span></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <i class="bi bi-box-seam text-secondary"></i>
                                            <?= esc($row['courier']) ?>
                                        </div>
                                    </td>
                                    <td><?= esc($row['time_ago']) ?></td>
                                    <td><span class="text-primary fw-semibold"><?= $row['quantity'] ?></span></td>
                                    <td>
                                        <div class="parcel-actions-dropdown dropdown">
                                            <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="javascript:;"
                                                        onclick="openParcelDetail(<?= $row['id'] ?>)"><i
                                                            class="bi bi-eye me-2 text-primary"></i>Ver detalles</a></li>
                                                <li><a class="dropdown-item" href="javascript:;"
                                                        onclick="showDeliverConfirm(<?= $row['id'] ?>, this)"><i
                                                            class="bi bi-check-circle me-2 text-success"></i>Marcar como
                                                        Entregado</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item" href="javascript:;"
                                                        onclick="sendReminder(<?= $row['id'] ?>)"><i
                                                            class="bi bi-bell me-2 text-warning"></i>Enviar Recordatorio al
                                                        Residente</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div id="parcel-filter-empty" class="parcel-empty d-none">
                    <div>
                        <div class="parcel-empty-icon"><i class="bi bi-search"></i></div>
                        <h2 class="parcel-empty-title">Sin resultados</h2>
                        <p class="parcel-empty-desc">No encontramos coincidencias con la búsqueda actual.</p>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3 pagination-wrapper">
                    <span class="text-secondary">Mostrando <?= $offset + 1 ?>-<?= min($offset + $perPage, $totalRows) ?> de
                        <?= $totalRows ?></span>
                    <?php if ($totalPages > 1): ?>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $baseParcelUrl ?>?page=1">«</a>
                                </li>
                                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $baseParcelUrl ?>?page=<?= $currentPage - 1 ?>">‹</a>
                                </li>
                                <li class="page-item disabled"><span class="page-link">Página <?= $currentPage ?> de
                                        <?= $totalPages ?></span></li>
                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $baseParcelUrl ?>?page=<?= $currentPage + 1 ?>">›</a>
                                </li>
                                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="<?= $baseParcelUrl ?>?page=<?= $totalPages ?>">»</a>
                                </li>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- ========================================== -->
<!-- DETAIL MODAL                               -->
<!-- ========================================== -->
<div class="modal fade parcel-detail-modal" id="parcelDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Detalles del Paquete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="parcelDetailBody">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>
            </div>
            <div class="modal-receipt-footer d-flex justify-content-between align-items-center" id="parcelDetailFooter"
                style="display:none!important;">
                <span class="tracking-code" id="detailTrackingCode"></span>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-warning rounded-pill px-3" id="btnModalDeliver"
                        style="display:none;">
                        <i class="bi bi-check-circle me-1"></i>Marcar como entregado
                    </button>
                    <a class="btn btn-sm btn-outline-secondary rounded-pill px-3" id="btnDownloadReceipt" href="#"
                        target="_blank">
                        <i class="bi bi-download me-1"></i>Descargar comprobante
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LIGHTBOX -->
<div class="modal fade" id="photoLightbox" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content"
            style="background: rgba(0,0,0,0.92); border: none; border-radius: 0.75rem; overflow: hidden;">
            <div class="d-flex justify-content-end p-3 pb-0">
                <button type="button" class="btn btn-sm d-flex align-items-center justify-content-center"
                    data-bs-dismiss="modal" aria-label="Cerrar"
                    style="width:36px; height:36px; border-radius:50%; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.25); color:#fff; font-size:1.1rem; transition: background 0.2s;"
                    onmouseover="this.style.background='rgba(255,255,255,0.3)'"
                    onmouseout="this.style.background='rgba(255,255,255,0.15)'">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            <div class="p-3 pt-2">
                <img id="lightboxImg" src="" class="w-100 rounded" alt="Paquete"
                    style="max-height:75vh; object-fit:contain;">
            </div>
        </div>
    </div>
</div>

<!-- CONFIRM DELIVER MODAL -->
<div class="modal fade confirm-deliver-modal" id="confirmDeliverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="confirm-deliver-title">
                    <span class="warning-icon"><i class="bi bi-exclamation-triangle-fill"></i></span>
                    Marcar como entregado
                </div>
                <p class="confirm-deliver-desc">
                    Usar cuando el paquete ya fue entregado al residente pero seguridad no lo marcó correctamente. Al
                    ser una corrección administrativa, no se enviará notificación.
                </p>
                <div class="confirm-deliver-info">
                    <div class="info-row"><i class="bi bi-geo-alt"></i> Unidad: <strong id="confirmUnit">—</strong>
                    </div>
                    <div class="info-row"><i class="bi bi-truck"></i> Transportista: <strong
                            id="confirmCourier">—</strong></div>
                    <div class="info-row"><i class="bi bi-calendar-event"></i> Fecha de Llegada: <strong
                            id="confirmTimeAgo">—</strong></div>
                </div>
                <div class="confirm-deliver-warning">
                    <i class="bi bi-exclamation-triangle"></i>
                    No se enviará notificación al residente
                </div>
                <div class="confirm-deliver-actions">
                    <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn-confirm" id="btnConfirmDeliver">Marcar como entregado</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Search filter
        var searchInput = document.getElementById('parcel-search');
        var rows = Array.from(document.querySelectorAll('.parcel-row'));
        var tableWrap = document.getElementById('parcel-table-wrap');
        var emptyFiltered = document.getElementById('parcel-filter-empty');

        if (searchInput && rows.length > 0) {
            searchInput.addEventListener('input', function () {
                var term = (searchInput.value || '').trim().toLowerCase();
                var visible = 0;
                rows.forEach(function (row) {
                    var haystack = (row.dataset.search || '').toLowerCase();
                    var match = term === '' || haystack.indexOf(term) !== -1;
                    row.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                if (tableWrap && emptyFiltered) {
                    if (visible === 0) {
                        tableWrap.classList.add('d-none');
                        emptyFiltered.classList.remove('d-none');
                    } else {
                        tableWrap.classList.remove('d-none');
                        emptyFiltered.classList.add('d-none');
                    }
                }
            });
        }

        // Refresh
        var refreshBtn = document.getElementById('parcel-refresh');
        if (refreshBtn) refreshBtn.addEventListener('click', function () { window.location.reload(); });

        // Row click → open detail
        rows.forEach(function (row) {
            row.addEventListener('click', function (e) {
                if (e.target.closest('.dropdown, .btn, a, input')) return;
                openParcelDetail(row.dataset.id);
            });
        });
    });

    function openParcelDetail(id) {
        var modal = new bootstrap.Modal(document.getElementById('parcelDetailModal'));
        var body = document.getElementById('parcelDetailBody');
        var footer = document.getElementById('parcelDetailFooter');

        body.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';
        footer.style.display = 'none';
        modal.show();

        fetch('<?= base_url("admin/paqueteria/detalle") ?>/' + id)
            .then(r => r.json())
            .then(res => {
                if (res.status !== 200) {
                    body.innerHTML = '<div class="alert alert-danger">Error: ' + (res.error || 'No encontrado') + '</div>';
                    return;
                }
                var p = res.data;
                var isPending = ['pending', 'at_gate'].includes((p.status || '').toLowerCase());
                var isDelivered = ['delivered', 'delivered_to_resident'].includes((p.status || '').toLowerCase());

                // Format dates
                var createdFormatted = formatDateFull(p.created_at);
                var deliveredFormatted = p.delivered_at ? formatDateFull(p.delivered_at) : null;

                var statusBadge = isPending
                    ? '<span class="badge bg-warning-subtle text-warning-emphasis border-warning-subtle border rounded-pill px-2 py-1"><i class="bi bi-clock-history me-1"></i>En caseta</span>'
                    : '<span class="badge bg-success-subtle text-success-emphasis koti-card-green-subtle border rounded-pill px-2 py-1"><i class="bi bi-check-circle me-1"></i>Entregado</span>';

                var html = '<div class="row g-3">';

                // Left column
                html += '<div class="col-12 col-md-6">';
                html += '<div class="detail-card">';
                html += '<div class="detail-card-title"><i class="bi bi-person-badge"></i> Destinatario</div>';
                html += '<div class="row g-3">';
                html += '<div class="col-6"><div class="detail-label">UNIDAD</div><div class="detail-value">' + (p.unit_number || 'N/A') + '</div></div>';
                html += '<div class="col-6"><div class="detail-label">RESIDENTES</div><div class="detail-value-sm">' + (p.resident_names || 'Sin asignar') + '</div></div>';
                html += '</div></div>';

                html += '<div class="detail-card">';
                html += '<div class="detail-card-title"><i class="bi bi-box-seam"></i> Información del Paquete</div>';
                html += '<div class="mb-2"><div class="detail-label">STATUS</div>' + statusBadge + '</div>';
                html += '<div class="row g-2 mt-1">';
                html += '<div class="col-6"><div class="detail-label">TRANSPORTISTA</div><div class="detail-value-sm">' + (p.courier || 'N/A') + '</div></div>';
                html += '<div class="col-6"><div class="detail-label">TIPO</div><div class="detail-value-sm">' + (p.parcel_type || 'Paquete') + '</div></div>';
                html += '<div class="col-6"><div class="detail-label">CANTIDAD</div><div class="detail-value-sm">' + (p.quantity || 1) + '</div></div>';
                html += '<div class="col-6"><div class="detail-label">FECHA DE LLEGADA</div><div class="detail-value-sm" style="font-size:0.78rem;">' + createdFormatted + '</div></div>';
                html += '</div>';

                // 🔐 PIN de entrega
                if (p.delivery_pin) {
                    if (isPending) {
                        html += '<div class="mt-3 p-3" style="background:#fffbeb; border:1px solid #fde68a; border-radius:0.6rem;">';
                        html += '<div class="detail-label" style="color:#92400e;">🔐 PIN DE ENTREGA</div>';
                        html += '<div style="font-family:SFMono-Regular,Consolas,monospace; font-size:1.6rem; font-weight:800; color:#d97706; letter-spacing:0.35em; margin-top:4px;">' + p.delivery_pin + '</div>';
                        html += '<div style="font-size:0.72rem; color:#92400e; margin-top:4px;">El residente debe proporcionar este PIN al guardia para recoger</div>';
                        html += '</div>';
                    } else if (isDelivered) {
                        html += '<div class="mt-3 p-2" style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:0.5rem; display:flex; align-items:center; gap:8px;">';
                        html += '<i class="bi bi-shield-check" style="color:#16a34a; font-size:1rem;"></i>';
                        html += '<span style="font-size:0.82rem; font-weight:600; color:#166534;">PIN verificado ✓</span>';
                        html += '</div>';
                    }
                }

                html += '</div>';
                html += '</div>';

                // Right column - Timeline
                html += '<div class="col-12 col-md-6">';
                html += '<div class="detail-card" style="height:100%;">';
                html += '<div class="detail-card-title"><i class="bi bi-clock-history"></i> Línea de tiempo</div>';
                html += '<div class="timeline-list">';

                // Received event
                html += '<div class="tl-item"><span class="tl-dot received"></span>';
                html += '<div class="tl-date"><i class="bi bi-clock"></i> ' + createdFormatted + '</div>';
                html += '<div class="tl-event">Paquete recibido</div>';
                html += '</div>';

                // Photo
                if (p.photo_full_url) {
                    html += '<div class="tl-item"><span class="tl-dot received"></span>';
                    html += '<div class="position-relative d-inline-block mt-1">';
                    html += '<img src="' + p.photo_full_url + '" class="parcel-photo-thumb" onclick="openLightbox(\'' + p.photo_full_url + '\')" alt="Foto">';
                    html += '<div class="photo-overlay-text">Haz clic para ver en tamaño completo</div>';
                    html += '</div></div>';
                }

                // Waiting event
                if (isPending) {
                    html += '<div class="tl-item"><span class="tl-dot waiting"></span>';
                    html += '<div class="tl-date"><i class="bi bi-clock"></i> ' + createdFormatted + '</div>';
                    html += '<div class="tl-event">Esperando Recolección</div>';
                    html += '</div>';
                }

                // Delivered event
                if (isDelivered && deliveredFormatted) {
                    html += '<div class="tl-item"><span class="tl-dot delivered"></span>';
                    html += '<div class="tl-date"><i class="bi bi-clock"></i> ' + deliveredFormatted + '</div>';
                    html += '<div class="tl-event">Entregado a ' + (p.picked_up_name || 'residente') + '</div>';
                    html += '</div>';

                    // Signature
                    if (p.signature_full_url) {
                        html += '<div class="tl-item"><span class="tl-dot delivered"></span>';
                        html += '<div class="tl-event mb-1">Firma de recepción</div>';
                        html += '<img src="' + p.signature_full_url + '" style="max-width:200px; max-height:80px; border:1px solid #e2e8f0; border-radius:6px; padding:4px; background:#fff;" alt="Firma">';
                        html += '</div>';
                    }
                }

                html += '</div></div></div>';

                // Timeline Summary (only for delivered parcels)
                if (isDelivered && p.created_at && p.delivered_at) {
                    var waitMs = new Date(p.delivered_at).getTime() - new Date(p.created_at).getTime();
                    var waitStr = '';
                    if (waitMs < 0) waitMs = 0;
                    var totalMin = Math.floor(waitMs / 60000);
                    var hrs = Math.floor(totalMin / 60);
                    var mins = totalMin % 60;
                    if (hrs > 0) waitStr += hrs + 'h ';
                    waitStr += mins + 'm';
                    if (!waitStr) waitStr = '< 1m';

                    html += '<div class="col-12">';
                    html += '<div class="tl-summary">';
                    html += '<div class="tl-summary-title">Resumen de línea de tiempo</div>';
                    html += '<div class="tl-summary-grid">';
                    html += '<div class="tl-summary-item">';
                    html += '<div class="tl-summary-value">' + waitStr + '</div>';
                    html += '<div class="tl-summary-label">Tiempo de Espera</div>';
                    html += '</div>';
                    html += '<div class="tl-summary-item">';
                    html += '<div class="tl-summary-check"><i class="bi bi-check-lg"></i></div>';
                    html += '<div class="tl-summary-label">Completado</div>';
                    html += '</div>';
                    html += '</div></div></div>';
                }

                html += '</div>';

                body.innerHTML = html;

                // Footer
                var trackingCode = 'PKG-' + (p.id ? p.id.toString().padStart(6, '0') : '000000');
                document.getElementById('detailTrackingCode').textContent = trackingCode;
                document.getElementById('btnDownloadReceipt').href = '<?= base_url("admin/paqueteria/comprobante") ?>/' + p.id;

                var btnDeliver = document.getElementById('btnModalDeliver');
                btnDeliver.style.display = isPending ? 'inline-flex' : 'none';
                btnDeliver.onclick = function () {
                    bootstrap.Modal.getInstance(document.getElementById('parcelDetailModal')).hide();
                    setTimeout(function () { showDeliverConfirmDirect(p.id); }, 300);
                };

                footer.style.display = '';
                footer.style.cssText = '';
            })
            .catch(err => {
                body.innerHTML = '<div class="alert alert-danger">Error de conexión</div>';
                console.error(err);
            });
    }

    function openLightbox(url) {
        document.getElementById('lightboxImg').src = url;
        var lb = new bootstrap.Modal(document.getElementById('photoLightbox'));
        lb.show();
    }

    var _pendingDeliverId = null;

    function showDeliverConfirm(id, triggerEl) {
        _pendingDeliverId = id;
        // Find the row to get data attributes
        var row = triggerEl ? triggerEl.closest('tr.parcel-row') : null;
        document.getElementById('confirmUnit').textContent = row ? (row.dataset.unit || '—') : '—';
        document.getElementById('confirmCourier').textContent = row ? (row.dataset.courier || '—') : '—';
        document.getElementById('confirmTimeAgo').textContent = row ? (row.dataset.timeago || '—') : '—';

        var modal = new bootstrap.Modal(document.getElementById('confirmDeliverModal'));
        modal.show();
    }

    function markAsDelivered(id) {
        // Called from detail modal button (no confirm modal) 
        showDeliverConfirmDirect(id);
    }

    function showDeliverConfirmDirect(id) {
        _pendingDeliverId = id;
        // Fetch data for the confirm modal
        fetch('<?= base_url("admin/paqueteria/detalle") ?>/' + id)
            .then(r => r.json())
            .then(res => {
                if (res.status === 200) {
                    var p = res.data;
                    document.getElementById('confirmUnit').textContent = p.unit_number || '—';
                    document.getElementById('confirmCourier').textContent = p.courier || '—';
                    // Calculate time ago
                    if (p.created_at) {
                        var diff = Math.floor((Date.now() - new Date(p.created_at).getTime()) / 60000);
                        if (diff < 60) document.getElementById('confirmTimeAgo').textContent = 'hace ' + diff + ' minutos';
                        else if (diff < 1440) document.getElementById('confirmTimeAgo').textContent = 'hace ' + Math.floor(diff / 60) + ' hora' + (Math.floor(diff / 60) > 1 ? 's' : '');
                        else document.getElementById('confirmTimeAgo').textContent = 'hace ' + Math.floor(diff / 1440) + ' día' + (Math.floor(diff / 1440) > 1 ? 's' : '');
                    } else {
                        document.getElementById('confirmTimeAgo').textContent = '—';
                    }
                }
                var modal = new bootstrap.Modal(document.getElementById('confirmDeliverModal'));
                modal.show();
            })
            .catch(function () {
                // Fallback: show with minimal data
                var modal = new bootstrap.Modal(document.getElementById('confirmDeliverModal'));
                modal.show();
            });
    }

    document.getElementById('btnConfirmDeliver').addEventListener('click', function () {
        if (!_pendingDeliverId) return;
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Procesando...';

        fetch('<?= base_url("admin/paqueteria/marcar-entregado") ?>/' + _pendingDeliverId, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
            body: 'picked_up_name=Entrega+desde+administraci%C3%B3n'
        })
            .then(r => r.json())
            .then(res => {
                if (res.status === 200) {
                    bootstrap.Modal.getInstance(document.getElementById('confirmDeliverModal')).hide();
                    window.location.reload();
                } else {
                    btn.disabled = false;
                    btn.textContent = 'Marcar como entregado';
                    alert(res.error || 'Error al marcar como entregado');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.textContent = 'Marcar como entregado';
                alert('Error de conexión');
                console.error(err);
            });
    });

    function sendReminder(id) {
        alert('Funcionalidad de notificación próximamente. Se enviará un recordatorio push al residente.');
    }

    function formatDateFull(dateStr) {
        if (!dateStr) return '—';
        var d = new Date(dateStr);
        var days = ['domingo', 'lunes', 'martes', 'miércoles', 'jueves', 'viernes', 'sábado'];
        var months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        var h = d.getHours();
        var ampm = h >= 12 ? 'PM' : 'AM';
        h = h % 12 || 12;
        var min = d.getMinutes().toString().padStart(2, '0');
        return days[d.getDay()] + ', ' + months[d.getMonth()] + ' ' + d.getDate() + ', ' + d.getFullYear() + ', ' + h + ':' + min + ' ' + ampm;
    }
</script>
<?= $this->endSection() ?>