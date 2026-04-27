<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
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

    /* ── PERIOD TABS ── */
    .period-tabs {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
    }

    .period-tab {
        background: none;
        border: none;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .period-tab.active,
    .period-tab:hover {
        color: #1e293b;
        background: rgba(255, 255, 255, 0.15);
    }

    /* ── 8 STAT CARDS ── */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .stat-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.15rem 1.25rem;
        position: relative;
        transition: box-shadow 0.2s;
    }

    .stat-box:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .stat-title {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.35rem;
    }

    .stat-value {
        font-size: 1.55rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.2;
    }

    .stat-icon {
        position: absolute;
        top: 1rem;
        right: 1rem;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .stat-icon.ic-blue {
        background: #eff6ff;
        color: #3b82f6;
    }

    .stat-icon.ic-green {
        background: #f0fdf4;
        color: #10b981;
    }

    .stat-icon.ic-gray {
        background: #f8fafc;
        color: #94a3b8;
    }

    .stat-icon.ic-red {
        background: #fef2f2;
        color: #ef4444;
    }

    /* ── CHART PANELS ── */
    .chart-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
    }

    .chart-panel-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.25rem;
    }

    .chart-panel-subtitle {
        font-size: 0.78rem;
        color: #94a3b8;
        margin-bottom: 1.25rem;
    }

    .empty-chart-state {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    /* ── HEATMAP ── */
    .heatmap-grid {
        display: grid;
        grid-template-columns: 40px repeat(12, 1fr);
        gap: 3px;
    }

    .heatmap-label {
        font-size: 0.7rem;
        color: #64748b;
        display: flex;
        align-items: center;
    }

    .heatmap-cell {
        aspect-ratio: 2.5;
        border-radius: 3px;
        background: #f1f5f9;
        min-height: 14px;
    }

    .heatmap-cell.hm-1 {
        background: #bae6fd;
    }

    .heatmap-cell.hm-2 {
        background: #7dd3fc;
    }

    .heatmap-cell.hm-3 {
        background: #38bdf8;
    }

    .heatmap-cell.hm-4 {
        background: #0ea5e9;
    }

    .heatmap-cell.hm-5 {
        background: #0284c7;
    }

    .heatmap-legend {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: flex-end;
        margin-top: 0.5rem;
        font-size: 0.68rem;
        color: #94a3b8;
    }

    .heatmap-legend .hm-swatch {
        width: 14px;
        height: 14px;
        border-radius: 2px;
    }

    /* ── TABLE ── */
    .koti-table th {
        background: transparent;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .koti-table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.85rem;
    }

    .koti-table tr:last-child td {
        border-bottom: none;
    }

    .koti-table tr:hover td {
        background: #f8fafc;
    }

    /* ── BACK BUTTON ── */
    .back-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.1);
        color: #ffffff;
        text-decoration: none;
        margin-right: 0.75rem;
        transition: all 0.2s;
        flex-shrink: 0;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        color: #ffffff;
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Chart.js para las gráficas -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
// Prepare PHP data for JS
$trendDates = [];
$trendApproved = [];
$trendPending = [];
$trendRejected = [];

if (!empty($trendData)) {
    // Get unique dates
    $dateMap = [];
    foreach ($trendData as $t) {
        $date = $t['date'];
        if (!isset($dateMap[$date]))
            $dateMap[$date] = ['approved' => 0, 'pending' => 0, 'rejected' => 0];
        $dateMap[$date][$t['status']] = (int) $t['total'];
    }
    foreach ($dateMap as $d => $vals) {
        $trendDates[] = date('M d', strtotime($d));
        $trendApproved[] = $vals['approved'];
        $trendPending[] = $vals['pending'];
        $trendRejected[] = $vals['rejected'];
    }
}

// Amenity names and data for bar chart
$amenityNames = [];
$amenityApproved = [];
$amenityPending2 = [];
$amenityRejected2 = [];

if (!empty($byAmenity)) {
    $amenityMap = [];
    foreach ($byAmenity as $ba) {
        $name = $ba['name'] ?? 'Desconocido';
        if (!isset($amenityMap[$name]))
            $amenityMap[$name] = ['approved' => 0, 'pending' => 0, 'rejected' => 0];
        $amenityMap[$name][$ba['status']] = (int) $ba['total'];
    }
    foreach ($amenityMap as $name => $vals) {
        $amenityNames[] = $name;
        $amenityApproved[] = $vals['approved'];
        $amenityPending2[] = $vals['pending'];
        $amenityRejected2[] = $vals['rejected'];
    }
}

// Heatmap data
$heatmapData = $heatmap ?? [];

// Time distribution
$timeDist = $timeDistribution ?? ['<1h' => 0, '1-4h' => 0, '4-12h' => 0, '12-24h' => 0, '1-2d' => 0, '>2d' => 0];
?>

<div class="row">
    <div class="col-12">


        <!-- ── Hero ── -->
        <div class="cc-hero">
            <div class="cc-hero-left">
                <h2 class="cc-hero-title">Estadísticas de Amenidades</h2>
                <div class="cc-hero-divider"></div>
                <div class="cc-hero-breadcrumb">
                    <i class="bi bi-calendar-check"></i>
                    <i class="bi bi-chevron-right" style="font-size:.65rem;color:#94a3b8"></i>
                    Información y métricas sobre las reservas de amenidades
                </div>
            </div>

        </div>



        <!-- TABS -->
        <div class="period-tabs">
            <a href="?period=week"
                class="period-tab text-decoration-none <?= ($period == 'week') ? 'active' : '' ?>">Semana</a>
            <a href="?period=month"
                class="period-tab text-decoration-none <?= ($period == 'month') ? 'active' : '' ?>">Mes</a>
            <a href="?period=quarter"
                class="period-tab text-decoration-none <?= ($period == 'quarter') ? 'active' : '' ?>">Trimestre</a>
            <a href="?period=year"
                class="period-tab text-decoration-none <?= ($period == 'year') ? 'active' : '' ?>">Anual</a>
        </div>
    </div>

    <!-- 8 STAT CARDS GRID -->
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-title">Total de Reservas</div>
            <div class="stat-value"><?= $totalBookings ?? 0 ?></div>
            <div class="stat-icon ic-blue"><i class="bi bi-calendar-event"></i></div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Tasa de Aprobación</div>
            <div class="stat-value"><?= $approvalRate ?? 0 ?>%</div>
            <div class="stat-icon ic-green"><i class="bi bi-check-circle"></i></div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Tiempo Promedio de Aprobación</div>
            <div class="stat-value"><?= esc($avgTimeDisplay ?? '0m') ?></div>
            <div class="stat-icon ic-green"><i class="bi bi-clock"></i></div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Ingresos Generados</div>
            <div class="stat-value"><?= ($totalRevenue ?? 0) > 0 ? '$' . number_format($totalRevenue, 0) : '-' ?>
            </div>
            <div class="stat-icon ic-green"><i class="bi bi-currency-dollar"></i></div>
        </div>

        <div class="stat-box">
            <div class="stat-title">Aprobaciones Pendientes</div>
            <div class="stat-value"><?= $pendingBookings ?? 0 ?></div>
            <div class="stat-icon ic-gray"><i class="bi bi-hourglass-split"></i></div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Tasa de Rechazo</div>
            <div class="stat-value"><?= $rejectionRate ?? 0 ?>%</div>
            <div class="stat-icon ic-red"><i class="bi bi-x-circle"></i></div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Más Popular</div>
            <div class="stat-value" style="font-size: 1.25rem;"><?= esc($mostPopular ?? '-') ?></div>
            <div class="stat-icon ic-blue"><i class="bi bi-star"></i></div>
        </div>
        <div class="stat-box">
            <div class="stat-title">Tasa de Utilización</div>
            <div class="stat-value"><?= $utilizationRate ?? 0 ?>%</div>
            <div class="stat-icon ic-blue"><i class="bi bi-percent"></i></div>
        </div>
    </div>

    <!-- CHARTS ROW 1 -->
    <div class="row mb-4">
        <div class="col-md-6 mb-4 mb-md-0">
            <div class="chart-panel h-100">
                <div class="chart-panel-title"><i class="bi bi-graph-up-arrow text-secondary"></i> Tendencias de
                    Reservas</div>
                <div class="chart-panel-subtitle">Volumen diario de reservas por estado</div>
                <div style="position: relative; height: 250px; width: 100%; flex-grow: 1;">
                    <canvas id="tendenciasChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="chart-panel h-100">
                <div class="chart-panel-title"><i class="bi bi-currency-dollar text-secondary"></i> Ingresos por
                    Amenidad</div>
                <div class="chart-panel-subtitle">Desglose de ingresos de reservas pagas</div>

                <?php if (($totalRevenue ?? 0) <= 0): ?>
                    <div class="empty-chart-state">
                        <div class="mb-2 text-dark fw-bold">Sin datos de ingresos</div>
                        <div>Los ingresos se generan de las reservas de amenidades pagas</div>
                    </div>
                <?php else: ?>
                    <div style="position: relative; height: 250px; width: 100%; flex-grow: 1;">
                        <canvas id="ingresosChart"></canvas>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- HEATMAP -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="chart-panel">
                <div class="chart-panel-title"><i class="bi bi-calendar-range text-secondary"></i> Horas Pico de
                    Reservas</div>
                <div class="chart-panel-subtitle">Densidad de reservas por día y hora</div>

                <!-- Headers de horas -->
                <div class="d-flex justify-content-between text-muted"
                    style="font-size: 0.7rem; margin-left: 40px; margin-right: 5%;">
                    <span>0:00</span><span>3:00</span><span>6:00</span><span>9:00</span><span>12:00</span><span>15:00</span><span>18:00</span><span>21:00</span>
                </div>

                <?php
                $dayLabels = ['', 'Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
                $hourSlots = [0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22];
                ?>
                <div class="heatmap-grid mb-3">
                    <?php for ($dow = 1; $dow <= 7; $dow++): ?>
                        <div class="heatmap-label"><?= $dayLabels[$dow] ?></div>
                        <?php foreach ($hourSlots as $hr): ?>
                            <?php
                            $count = $heatmapData[$dow][$hr] ?? 0;
                            $hmClass = '';
                            if ($count >= 5)
                                $hmClass = 'hm-5';
                            elseif ($count >= 4)
                                $hmClass = 'hm-4';
                            elseif ($count >= 3)
                                $hmClass = 'hm-3';
                            elseif ($count >= 2)
                                $hmClass = 'hm-2';
                            elseif ($count >= 1)
                                $hmClass = 'hm-1';
                            ?>
                            <div class="heatmap-cell <?= $hmClass ?>"
                                title="<?= $dayLabels[$dow] ?> <?= $hr ?>:00 - <?= $count ?> reservas"></div>
                        <?php endforeach; ?>
                    <?php endfor; ?>
                </div>

                <div class="heatmap-legend">
                    <span>Baja actividad</span>
                    <div class="hm-swatch" style="background:#f1f5f9;"></div>
                    <div class="hm-swatch" style="background:#bae6fd;"></div>
                    <div class="hm-swatch" style="background:#7dd3fc;"></div>
                    <div class="hm-swatch" style="background:#38bdf8;"></div>
                    <div class="hm-swatch" style="background:#0284c7;"></div>
                    <span>Alta actividad</span>
                </div>
            </div>
        </div>
    </div>

    <!-- CHARTS ROW 3 -->
    <div class="row mb-4">
        <div class="col-md-5 mb-4 mb-md-0">
            <div class="chart-panel h-100">
                <div class="chart-panel-title"><i class="bi bi-clock text-secondary pe-1"></i> Distribución de
                    Tiempo de Aprobación</div>
                <div class="chart-panel-subtitle">Qué tan rápido se aprueban las reservas</div>
                <div style="position: relative; height: 250px; width: 100%; flex-grow: 1;">
                    <canvas id="aprobacionChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-7">
            <div class="chart-panel h-100">
                <div class="chart-panel-title justify-content-between">
                    <div><i class="bi bi-bar-chart-fill text-secondary pe-1"></i> Reservas por Amenidad</div>
                    <i class="bi bi-chevron-up text-muted small pe-auto" style="cursor:pointer"></i>
                </div>
                <div style="position: relative; height: 290px; width: 100%; flex-grow: 1;">
                    <canvas id="reservasAmChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA RENDIMIENTO -->
    <div class="chart-panel">
        <div class="chart-panel-title border-bottom pb-3 mb-3"><i class="bi bi-table text-secondary"></i>
            Rendimiento de Amenidades</div>

        <div class="table-responsive">
            <table class="table koti-table mb-0 w-100 table-borderless">
                <thead>
                    <tr>
                        <th>Amenidad <i class="bi bi-arrow-down-up small ms-1"></i></th>
                        <th class="text-end">Total <i class="bi bi-arrow-down-up small ms-1"></i></th>
                        <th class="text-end">Aprobadas <i class="bi bi-arrow-down-up small ms-1"></i></th>
                        <th class="text-end">Pendientes</th>
                        <th class="text-end">Rechazadas</th>
                        <th class="text-end">% Aprobación <i class="bi bi-arrow-down-up small ms-1"></i></th>
                        <th class="text-end">Ingresos <i class="bi bi-arrow-down-up small ms-1"></i></th>
                        <th class="text-end pe-3">Tiempo Prom. <i class="bi bi-arrow-down-up small ms-1"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($performanceData)): ?>
                        <?php foreach ($performanceData as $p): ?>
                            <tr>
                                <td class="fw-medium text-dark"><?= esc($p['name']) ?></td>
                                <td class="text-end fw-bold"><?= $p['total'] ?></td>
                                <td class="text-end text-success"><?= $p['approved'] ?></td>
                                <td class="text-end text-warning"><?= $p['pending'] ?></td>
                                <td class="text-end text-danger"><?= $p['rejected'] ?></td>
                                <td class="text-end"><?= $p['approval_rate'] ?>%</td>
                                <td class="text-end"><?= $p['revenue'] > 0 ? '$' . number_format($p['revenue'], 0) : '-' ?>
                                </td>
                                <td class="text-end pe-3"><?= esc($p['avg_time'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">No hay datos de rendimiento disponibles.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // === Tendencias de Reservas ===
        const trendLabels = <?= json_encode($trendDates) ?>;
        const trendApproved = <?= json_encode($trendApproved) ?>;
        const trendPending = <?= json_encode($trendPending) ?>;
        const trendRejected = <?= json_encode($trendRejected) ?>;

        if (trendLabels.length > 0) {
            const ctxTendencias = document.getElementById('tendenciasChart').getContext('2d');
            new Chart(ctxTendencias, {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [
                        { label: 'Aprobadas', data: trendApproved, borderColor: '#10b981', backgroundColor: 'rgba(16, 185, 129, 0.1)', borderWidth: 2, fill: true, tension: 0.4, pointRadius: 0 },
                        { label: 'Pendientes', data: trendPending, borderColor: '#1e293b', backgroundColor: 'rgba(30, 41, 59, 0.05)', borderWidth: 2, fill: false, tension: 0.4, pointRadius: 0 },
                        { label: 'Rechazadas', data: trendRejected, borderColor: '#f59e0b', backgroundColor: 'rgba(245, 158, 11, 0.05)', borderWidth: 2, fill: false, tension: 0.4, pointRadius: 0 }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, font: { size: 10 } } } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        } else {
            document.getElementById('tendenciasChart').parentElement.innerHTML = '<div class="empty-chart-state"><div class="mb-2 text-dark fw-bold">Sin datos de tendencias</div><div>Los datos aparecerán cuando se registren reservas</div></div>';
        }

        // === Distribución de Tiempo de Aprobación ===
        const timeLabels = <?= json_encode(array_keys($timeDist)) ?>;
        const timeValues = <?= json_encode(array_values($timeDist)) ?>;

        const ctxAprob = document.getElementById('aprobacionChart').getContext('2d');
        new Chart(ctxAprob, {
            type: 'bar',
            data: {
                labels: timeLabels,
                datasets: [{
                    data: timeValues,
                    backgroundColor: '#2a9d8f',
                    borderRadius: 4,
                    maxBarThickness: 60
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { borderDash: [5, 5] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // === Reservas por Amenidad (Stacked Horizontal Bar) ===
        const amNames = <?= json_encode($amenityNames) ?>;
        const amApproved = <?= json_encode($amenityApproved) ?>;
        const amPending = <?= json_encode($amenityPending2) ?>;
        const amRejected = <?= json_encode($amenityRejected2) ?>;

        const ctxResAm = document.getElementById('reservasAmChart').getContext('2d');
        new Chart(ctxResAm, {
            type: 'bar',
            data: {
                labels: amNames,
                datasets: [
                    { label: 'Aprobadas', data: amApproved, backgroundColor: '#2a9d8f', borderRadius: 2, maxBarThickness: 24 },
                    { label: 'Pendientes', data: amPending, backgroundColor: '#1e293b', borderRadius: 2, maxBarThickness: 24 },
                    { label: 'Rechazadas', data: amRejected, backgroundColor: '#f59e0b', borderRadius: 2, maxBarThickness: 24 }
                ]
            },
            options: {
                indexAxis: 'y',
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, position: 'bottom', labels: { usePointStyle: true, boxWidth: 8, boxHeight: 8, font: { size: 10 } } }
                },
                scales: {
                    x: { stacked: true, beginAtZero: true },
                    y: { stacked: true, grid: { display: false } }
                }
            }
        });

        // Tabs function as normal links now, so UI script click handler is removed.
    });
</script>

<?= $this->endSection() ?>