<?= $this->extend('layout/main') ?>

<?= $this->section('styles') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
$rawPolls = is_array($polls ?? null) ? $polls : [];
$nowTs = time();

$categoryLabels = [
    'mantenimiento' => 'Mantenimiento',
    'eventos' => 'Eventos',
    'reglas' => 'Reglas',
    'finanzas' => 'Finanzas',
    'general' => 'General',
    'emergencia' => 'Emergencia',
];

$resolveCategory = static function (string $title, string $description): string {
    $text = strtolower(trim($title . ' ' . $description));

    if (str_contains($text, 'manten') || str_contains($text, 'fuga') || str_contains($text, 'elevador') || str_contains($text, 'luz')) {
        return 'mantenimiento';
    }

    if (str_contains($text, 'evento') || str_contains($text, 'fiesta') || str_contains($text, 'convivio')) {
        return 'eventos';
    }

    if (str_contains($text, 'regla') || str_contains($text, 'norma') || str_contains($text, 'reglamento')) {
        return 'reglas';
    }

    if (str_contains($text, 'finanza') || str_contains($text, 'cuota') || str_contains($text, 'pago') || str_contains($text, 'presupuesto')) {
        return 'finanzas';
    }

    if (str_contains($text, 'emergencia') || str_contains($text, 'urgente') || str_contains($text, 'seguridad')) {
        return 'emergencia';
    }

    return 'general';
};

$normalized = [];
$statusCounts = ['all' => 0, 'active' => 0, 'draft' => 0, 'closed' => 0];
$categoryCounts = array_fill_keys(array_keys($categoryLabels), 0);

foreach ($rawPolls as $poll) {
    $title = trim((string) ($poll['title'] ?? 'Sin titulo'));
    $description = trim((string) ($poll['description'] ?? ''));
    $startTs = strtotime((string) ($poll['start_date'] ?? '')) ?: 0;
    $endTs = strtotime((string) ($poll['end_date'] ?? '')) ?: 0;
    $isActive = (int) ($poll['is_active'] ?? 0) === 1;

    $statusKey = 'draft';
    if ($endTs > 0 && $endTs < $nowTs) {
        $statusKey = 'closed';
    } elseif ($isActive && $startTs > 0 && ($endTs === 0 || $endTs >= $nowTs) && $startTs <= $nowTs) {
        $statusKey = 'active';
    } elseif (!$isActive && $startTs > $nowTs) {
        $statusKey = 'draft';
    } elseif ($isActive && $startTs === 0 && ($endTs === 0 || $endTs >= $nowTs)) {
        $statusKey = 'active';
    } elseif (!$isActive && $endTs > 0 && $endTs >= $nowTs) {
        $statusKey = 'draft';
    }

    $statusMeta = [
        'active' => ['label' => 'Activo', 'class' => 'bg-success-subtle text-success-emphasis koti-card-green-subtle', 'icon' => 'bi-record-circle'],
        'draft' => ['label' => 'Borrador', 'class' => 'bg-secondary-subtle text-secondary-emphasis border-secondary-subtle', 'icon' => 'bi-file-earmark'],
        'closed' => ['label' => 'Cerrado', 'class' => 'bg-dark-subtle text-dark-emphasis border-dark-subtle', 'icon' => 'bi-lock'],
    ][$statusKey];

    $categoryKey = $resolveCategory($title, $description);
    $categoryLabel = $categoryLabels[$categoryKey] ?? 'General';

    $votesEstimate = (int) ($poll['total_votes'] ?? 0);

    $normalized[] = [
        'id' => (int) ($poll['id'] ?? 0),
        'hash_id' => !empty($poll['hash_id']) ? $poll['hash_id'] : ($poll['id'] ?? ''),
        'title' => $title,
        'description' => $description,
        'status_key' => $statusKey,
        'status_label' => $statusMeta['label'],
        'status_class' => $statusMeta['class'],
        'status_icon' => $statusMeta['icon'],
        'category_key' => $categoryKey,
        'category_label' => $categoryLabel,
        'start_label' => $startTs > 0 ? date('d M Y', $startTs) : '--',
        'end_label' => $endTs > 0 ? date('d M Y', $endTs) : '--',
        'votes' => $votesEstimate,
        'search' => strtolower(trim(implode(' ', [$title, $description, $categoryLabel, $statusMeta['label']]))),
    ];

    $statusCounts['all']++;
    $statusCounts[$statusKey]++;
    if (isset($categoryCounts[$categoryKey])) {
        $categoryCounts[$categoryKey]++;
    }
}
?>

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


    .polls-layout {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 1.2rem;
        margin-top: 1.2rem;
    }

    .polls-sidebar {
        border: 1px solid #d9e1eb;
        border-radius: 0.6rem;
        background: #fff;
        display: flex;
        flex-direction: column;
        min-height: 610px;
    }

    .polls-sidebar-body {
        padding: 0.9rem 0.75rem 0.55rem;
        flex: 1;
    }

    .polls-filter-title {
        color: #57708f;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        margin: 0 0 0.45rem 0.25rem;
        font-weight: 600;
    }

    .polls-filter-group {
        margin-bottom: 1rem;
    }

    .polls-filter-item {
        width: 100%;
        border: none;
        background: transparent;
        border-radius: 0.45rem;
        color: #0f172a;
        font-size: 0.95rem;
        padding: 0.46rem 0.58rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .polls-filter-item .left {
        display: inline-flex;
        align-items: center;
        gap: 0.55rem;
    }

    .polls-filter-item .count {
        color: #64748b;
        font-size: 0.83rem;
    }

    .polls-filter-item.active {
        background: #eef2f7;
        color: #1e293b;
    }

    .polls-filter-item:hover {
        background: #f8fafc;
    }

    .polls-divider {
        border-top: 1px solid #e2e8f0;
        margin: 0.75rem 0.25rem;
    }

    .polls-sidebar-footer {
        border-top: 1px solid #e2e8f0;
        padding: 0.75rem;
        background: #fafcff;
        border-bottom-left-radius: 0.6rem;
        border-bottom-right-radius: 0.6rem;
    }

    .polls-stats-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 0.45rem;
        color: #0f172a;
    }

    .polls-stat-line {
        display: flex;
        justify-content: space-between;
        font-size: 0.8rem;
        color: #57708f;
        margin-bottom: 0.35rem;
    }

    .polls-main {
        min-width: 0;
    }

    .polls-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.9rem;
        margin-bottom: 0.85rem;
        flex-wrap: wrap;
    }

    .polls-search-wrap {
        position: relative;
        width: 100%;
        max-width: 380px;
    }

    .polls-search-wrap i {
        position: absolute;
        left: 0.7rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .polls-search {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: 0.45rem;
        background: #fff;
        font-size: 0.9rem;
        color: #334155;
        padding: 0.52rem 0.78rem 0.52rem 2rem;
    }

    .polls-search:focus {
        outline: none;
        border-color: #93a5bc;
        box-shadow: 0 0 0 4px rgba(147, 165, 188, 0.14);
    }

    .polls-create-btn {
        border: none;
        background: #4c627f;
        color: #fff;
        border-radius: 0.42rem;
        padding: 0.56rem 0.95rem;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .polls-create-btn:hover {
        background: #405571;
    }

    .polls-canvas {
        border: 1px solid #d9e1eb;
        border-radius: 0.6rem;
        background: #fff;
        min-height: 590px;
        padding: 1.1rem;
    }

    .polls-empty {
        min-height: 500px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        border: 1px dashed #dfe7f1;
        border-radius: 0.55rem;
        background: #fbfdff;
    }

    .polls-empty-icon {
        width: 68px;
        height: 68px;
        border-radius: 50%;
        background: #edf2f8;
        color: #657b96;
        margin: 0 auto 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
    }

    .polls-empty h3 {
        font-size: 2rem;
        line-height: 1;
        margin-bottom: 0.65rem;
    }

    .polls-empty p {
        color: #57708f;
        max-width: 420px;
        margin: 0 auto;
        font-size: 1.05rem;
    }

    .polls-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 0.9rem;
    }

    .poll-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.6rem;
        background: #fff;
        padding: 0.95rem;
    }

    .poll-card:hover {
        border-color: #cad6e4;
        box-shadow: 4px 8px 16px rgba(15, 23, 42, 0.08);
    }

    .poll-card h4 {
        font-size: 1.04rem;
        margin: 0;
        color: #0f172a;
        font-weight: 650;
    }

    .poll-card p {
        color: #4b5f78;
        font-size: 0.88rem;
        margin: 0.45rem 0 0.8rem;
        min-height: 42px;
    }

    .poll-meta {
        color: #64748b;
        font-size: 0.8rem;
        display: grid;
        gap: 0.15rem;
    }

    .poll-badge {
        border: 1px solid;
        border-radius: 999px;
        padding: 0.18rem 0.55rem;
        font-size: 0.74rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .poll-category-tag {
        background: #eef2f7;
        color: #334155;
        border-radius: 999px;
        font-size: 0.74rem;
        font-weight: 600;
        padding: 0.16rem 0.5rem;
    }

    .poll-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.75rem;
    }

    .poll-actions .btn-lite {
        border: 1px solid #d0d8e2;
        border-radius: 0.38rem;
        background: #fff;
        color: #1e293b;
        font-size: 0.82rem;
        padding: 0.32rem 0.62rem;
    }

    .poll-actions .btn-lite:hover {
        background: #f8fafc;
    }

    @media (max-width: 1100px) {
        .polls-layout {
            grid-template-columns: 1fr;
        }

        .polls-sidebar {
            min-height: unset;
        }

        .polls-canvas {
            min-height: 440px;
        }
    }
</style>

<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">Encuestas</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-check2-square"></i>
            <i class="bi bi-chevron-right"></i>
            Encuestas para tu comunidad
        </div>
    </div>
    <div class="cc-hero-right">
        <button type="button" class="cc-hero-btn" data-bs-toggle="modal" data-bs-target="#createPollModal">
            <i class="bi bi-plus-lg"></i> Crear Encuesta
        </button>
    </div>
</div>

<div class="polls-layout">
    <aside class="polls-sidebar">
        <div class="polls-sidebar-body">
            <div class="polls-filter-group">
                <div class="polls-filter-title">Por estado</div>
                <button type="button" class="polls-filter-item active" data-filter-status="all">
                    <span class="left"><i class="bi bi-grid"></i> Todas las Encuestas</span>
                    <span class="count"><i class="bi bi-check2"></i></span>
                </button>
                <button type="button" class="polls-filter-item" data-filter-status="active">
                    <span class="left"><i class="bi bi-record-circle"></i> Activo</span>
                    <span class="count"><?= esc((string) $statusCounts['active']) ?></span>
                </button>
                <button type="button" class="polls-filter-item" data-filter-status="draft">
                    <span class="left"><i class="bi bi-file-earmark"></i> Borrador</span>
                    <span class="count"><?= esc((string) $statusCounts['draft']) ?></span>
                </button>
                <button type="button" class="polls-filter-item" data-filter-status="closed">
                    <span class="left"><i class="bi bi-lock"></i> Cerrado</span>
                    <span class="count"><?= esc((string) $statusCounts['closed']) ?></span>
                </button>
            </div>

            <div class="polls-divider"></div>

            <div class="polls-filter-group">
                <div class="polls-filter-title">Por categoria</div>
                <?php foreach ($categoryLabels as $key => $label): ?>
                    <button type="button" class="polls-filter-item" data-filter-category="<?= esc($key) ?>">
                        <span class="left">
                            <i
                                class="bi <?= $key === 'mantenimiento' ? 'bi-wrench' : ($key === 'eventos' ? 'bi-calendar-event' : ($key === 'reglas' ? 'bi-clipboard-check' : ($key === 'finanzas' ? 'bi-wallet2' : ($key === 'emergencia' ? 'bi-exclamation-triangle' : 'bi-globe')))) ?>"></i>
                            <?= esc($label) ?>
                        </span>
                        <span class="count"><?= esc((string) ($categoryCounts[$key] ?? 0)) ?></span>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="polls-sidebar-footer">
            <div class="polls-stats-title"><i class="bi bi-bar-chart-line me-1"></i> Estadisticas</div>
            <div class="polls-stat-line"><span>Total de
                    Encuestas</span><strong><?= esc((string) $totalPolls) ?></strong></div>
            <div class="polls-stat-line"><span>Activas</span><strong><?= esc((string) $activePolls) ?></strong></div>
            <div class="polls-stat-line"><span>Total de
                    Votos</span><strong><?= esc((string) $totalSystemVotes) ?></strong></div>
        </div>
    </aside>

    <section class="polls-main">


        <div class="polls-canvas">
            <?php if (empty($normalized)): ?>
                <div class="polls-empty" id="polls-empty-state">
                    <div>
                        <div class="polls-empty-icon"><i class="bi bi-bar-chart"></i></div>
                        <h3>No se encontraron encuestas</h3>
                        <p>Crea tu primera encuesta para involucrar a tu comunidad y recopilar comentarios valiosos.</p>
                    </div>
                </div>
            <?php else: ?>
                <div class="polls-list" id="poll-list">
                    <?php foreach ($normalized as $poll): ?>
                        <article class="poll-card poll-item" data-status="<?= esc($poll['status_key']) ?>"
                            data-category="<?= esc($poll['category_key']) ?>" data-search="<?= esc($poll['search']) ?>">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <h4><?= esc($poll['title']) ?></h4>
                                <span class="poll-category-tag"><?= esc($poll['category_label']) ?></span>
                            </div>

                            <p><?= esc($poll['description'] !== '' ? $poll['description'] : 'Sin descripcion registrada.') ?>
                            </p>

                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="poll-badge <?= esc($poll['status_class']) ?>">
                                    <i class="bi <?= esc($poll['status_icon']) ?>"></i><?= esc($poll['status_label']) ?>
                                </span>
                                <small class="text-secondary"><?= esc((string) $poll['votes']) ?> votos</small>
                            </div>

                            <div class="poll-meta">
                                <span><i class="bi bi-play-circle me-1"></i>Inicio: <?= esc($poll['start_label']) ?></span>
                                <span><i class="bi bi-stop-circle me-1"></i>Cierre: <?= esc($poll['end_label']) ?></span>
                            </div>

                            <div class="poll-actions">
                                <a href="<?= base_url('/admin/encuestas/detalles/' . $poll['hash_id']) ?>"
                                    class="btn-lite text-decoration-none"><i class="bi bi-eye me-1"></i> Ver</a>

                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <div class="polls-empty d-none" id="polls-filter-empty">
                    <div>
                        <div class="polls-empty-icon"><i class="bi bi-search"></i></div>
                        <h3>Sin resultados</h3>
                        <p>No hay encuestas que coincidan con los filtros seleccionados.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <!-- Create Poll Modal -->
    <div class="modal fade" id="createPollModal" tabindex="-1" aria-labelledby="createPollModalLabel" aria-hidden="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0">
                    <div class="w-100">
                        <h5 class="modal-title fw-bold" id="createPollModalLabel">Crear Encuesta</h5>
                        <p class="text-secondary small mb-0">Involucra a tu audiencia con encuestas interactivas</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">

                    <!-- Wizard Nav -->
                    <div
                        class="poll-wizard-nav d-flex justify-content-between align-items-center mb-4 position-relative">
                        <div class="poll-wizard-line position-absolute top-50 start-0 end-0 translate-middle-y z-0"
                            style="height: 2px; background: #e2e8f0;"></div>
                        <div class="poll-step-item text-center z-1 position-relative" data-target="step-content">
                            <div class="poll-step-circle active" id="circle-step-1">1</div>
                            <div class="poll-step-label small fw-semibold mt-1">Contenido</div>
                        </div>
                        <div class="poll-step-item text-center z-1 position-relative" data-target="step-config">
                            <div class="poll-step-circle" id="circle-step-2">2</div>
                            <div class="poll-step-label small fw-semibold mt-1 text-muted">Configuración</div>
                        </div>
                        <div class="poll-step-item text-center z-1 position-relative" data-target="step-review">
                            <div class="poll-step-circle" id="circle-step-3">3</div>
                            <div class="poll-step-label small fw-semibold mt-1 text-muted">Revisar y Publicar</div>
                        </div>
                    </div>

                    <form id="createPollForm">
                        <!-- Step 1: Contenido -->
                        <div class="poll-step" id="step-content">
                            <div class="card premium-card mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-semibold">Tu Pregunta</label>
                                    <textarea id="pollQuestion" class="form-control premium-input" rows="3"
                                        placeholder="¿Cuando desean que se haga la junta de la mesa administrativa?"
                                        required></textarea>
                                </div>
                            </div>

                            <div class="card premium-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label fw-semibold mb-0">Opciones de Encuesta</label>
                                        <span class="small text-muted"><span id="optionCountDisplay">2</span>
                                            opciones</span>
                                    </div>
                                    <div id="pollOptionsContainer">
                                        <div class="input-group mb-2 poll-option-row">
                                            <span class="input-group-text premium-addon border-end-0 text-muted"><i
                                                    class="bi bi-list"></i> 1.</span>
                                            <input type="text"
                                                class="form-control premium-input border-start-0 ps-0 poll-option-input"
                                                placeholder="Sábado 4 de Abril" required>
                                            <button class="btn btn-outline-danger border-start-0 remove-option-btn"
                                                type="button" disabled><i class="bi bi-x"></i></button>
                                        </div>
                                        <div class="input-group mb-2 poll-option-row">
                                            <span class="input-group-text premium-addon border-end-0 text-muted"><i
                                                    class="bi bi-list"></i> 2.</span>
                                            <input type="text"
                                                class="form-control premium-input border-start-0 ps-0 poll-option-input"
                                                placeholder="Sábado 11 de Abril" required>
                                            <button class="btn btn-outline-danger border-start-0 remove-option-btn"
                                                type="button" disabled><i class="bi bi-x"></i></button>
                                        </div>
                                    </div>
                                    <button type="button"
                                        class="btn btn-light w-100 mt-2 border text-primary btn-premium"
                                        id="btnAddOption">
                                        <i class="bi bi-plus"></i> Agregar Opción
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Configuración -->
                        <div class="poll-step d-none" id="step-config">
                            <div class="card premium-card mb-3">
                                <div class="card-body">
                                    <label class="form-label fw-semibold"><i class="bi bi-clock me-1"></i>
                                        Duración</label>
                                    <div class="btn-group duration-pills w-100 flex-wrap" role="group"
                                        aria-label="Poll Duration">
                                        <input type="radio" class="btn-check" name="pollDuration" id="dur1" value="1"
                                            autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="dur1">1 Día</label>

                                        <input type="radio" class="btn-check" name="pollDuration" id="dur3" value="3"
                                            autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="dur3">3 Días</label>

                                        <input type="radio" class="btn-check" name="pollDuration" id="dur7" value="7"
                                            autocomplete="off" checked>
                                        <label class="btn btn-outline-secondary" for="dur7">1 Semana</label>

                                        <input type="radio" class="btn-check" name="pollDuration" id="dur14" value="14"
                                            autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="dur14">2 Semanas</label>

                                        <input type="radio" class="btn-check" name="pollDuration" id="durCustom"
                                            value="custom" autocomplete="off">
                                        <label class="btn btn-outline-secondary" for="durCustom">Personalizado</label>
                                    </div>
                                    <div class="text-end mt-2 text-muted small" id="pollEndsText">Termina en 7 días
                                    </div>

                                    <div id="customDateContainer" class="d-none mt-3">
                                        <div class="row align-items-center">
                                            <div class="col-6">
                                                <label class="small text-muted">Fecha de inicio</label>
                                                <input type="text" class="form-control form-control-sm premium-input"
                                                    id="pollStartDate" placeholder="Selecciona la fecha">
                                            </div>
                                            <div class="col-6">
                                                <label class="small text-muted">Fecha final</label>
                                                <input type="text" class="form-control form-control-sm premium-input"
                                                    id="pollEndDate" placeholder="Selecciona la fecha">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-4 pt-3 border-top">
                                        <label class="form-label fw-semibold"><i class="bi bi-tag me-1"></i>
                                            Categoría</label>
                                        <select class="form-select premium-input" id="pollCategory">
                                            <option value="General" selected>General</option>
                                            <option value="Mantenimiento">Mantenimiento</option>
                                            <option value="Eventos">Eventos</option>
                                            <option value="Reglas">Reglas</option>
                                            <option value="Finanzas">Finanzas</option>
                                            <option value="Emergencia">Emergencia</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Revisar -->
                        <div class="poll-step d-none" id="step-review">

                            <div class="card premium-card mb-3">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="fw-bold mb-0">Vista previa</h6>
                                        <button type="button"
                                            class="btn btn-sm btn-link text-decoration-none text-muted p-0 review-edit-btn"
                                            data-step="1"><i class="bi bi-pencil"></i> Editar</button>
                                    </div>
                                    <p class="fw-semibold mb-2" id="previewQuestion"></p>
                                    <div id="previewOptions" class="mb-3">
                                        <!-- mock bars here -->
                                    </div>
                                    <div class="d-flex gap-3 small text-muted">
                                        <span><i class="bi bi-ui-radios me-1"></i> <span id="previewOptCount">2</span>
                                            opciones</span>
                                        <span><i class="bi bi-calendar me-1"></i> <span id="previewDurText">7
                                                días</span></span>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <div class="card premium-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold fs-6">Contenido</span>
                                                <button type="button"
                                                    class="btn btn-sm btn-link text-decoration-none text-muted p-0 review-edit-btn"
                                                    data-step="1"><i class="bi bi-pencil"></i> Editar</button>
                                            </div>
                                            <p class="small text-truncate mb-1 text-secondary" id="summaryQuestion"></p>
                                            <p class="small text-muted mb-0"><span id="summaryOptCount">2</span>
                                                opciones</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="card premium-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold fs-6">Configuración</span>
                                                <button type="button"
                                                    class="btn btn-sm btn-link text-decoration-none text-muted p-0 review-edit-btn"
                                                    data-step="2"><i class="bi bi-pencil"></i> Editar</button>
                                            </div>
                                            <p class="small mb-1 text-secondary" id="summaryDateRange"></p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="small text-muted mb-0" id="summaryDurText"></p>
                                                <span class="badge bg-light text-secondary border"
                                                    id="summaryCategoryText">General</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card premium-card mb-3">
                                <div class="card-body d-flex align-items-center gap-3">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center"
                                        style="width: 40px; height: 40px;">
                                        <i class="bi bi-people text-secondary fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">Audiencia estimada</div>
                                        <div class="text-muted small">~<?= $totalResidents ?? 0 ?> residentes en
                                            <?= $totalUnits ?? 0 ?> unidades
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card border border-light-subtle mb-3 shadow-sm">
                                <div class="card-body pb-2">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-bell text-muted small"></i> <span
                                            class="small text-muted fw-semibold">Vista previa de notificación</span>
                                    </div>
                                    <p class="small text-secondary m-0" id="previewNotificationText"></p>
                                </div>
                            </div>

                            <div class="mb-2">
                                <label class="fw-bold small mb-2">Opciones de publicación</label>

                                <label
                                    class="card border koti-card-purple shadow-sm mb-2 cursor-pointer publish-option-label active"
                                    for="optPublish">
                                    <div class="card-body p-3 d-flex align-items-center gap-3">
                                        <input type="radio" name="publishStatus" id="optPublish" value="1"
                                            class="form-check-input mt-0" checked>
                                        <div>
                                            <div class="fw-semibold small"><i class="bi bi-send d-none"></i> Publicar
                                                ahora</div>
                                            <div class="small text-muted">La encuesta se activará inmediatamente y se
                                                notificará a los residentes</div>
                                        </div>
                                    </div>
                                </label>

                                <label
                                    class="card border border-light-subtle shadow-sm cursor-pointer publish-option-label"
                                    for="optDraft">
                                    <div class="card-body p-3 d-flex align-items-center gap-3">
                                        <input type="radio" name="publishStatus" id="optDraft" value="0"
                                            class="form-check-input mt-0">
                                        <div>
                                            <div class="fw-semibold small"><i class="bi bi-file-earmark d-none"></i>
                                                Guardar borrador</div>
                                            <div class="small text-muted">La encuesta no será visible para los
                                                residentes</div>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light border-top-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary px-4 d-none" id="btnPrevStep"><i
                            class="bi bi-chevron-left me-1 small"></i> Atrás</button>
                    <div class="ms-auto" id="btnNextContainer">
                        <button type="button" class="btn btn-primary px-4 bg-slate border-0" id="btnNextStep"
                            style="background-color: #4b5f78;">Siguiente <i
                                class="bi bi-chevron-right ms-1 small"></i></button>
                        <button type="button" class="btn btn-primary px-4 border-0 d-none" id="btnSubmitPoll"
                            style="background-color: #4b5f78;">
                            <i class="bi bi-send me-1"></i> Publicar ahora
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Wizard Navbar */
        .poll-wizard-nav {
            padding: 0 1rem;
        }

        .poll-wizard-line {
            height: 2px;
            background: #e2e8f0;
            transition: background 0.3s ease;
        }

        .poll-step-circle {
            width: 42px;
            height: 42px;
            line-height: 38px;
            background: #fff;
            border: 2px solid #cbd5e1;
            color: #94a3b8;
            border-radius: 50%;
            margin: 0 auto;
            font-weight: 700;
            font-size: 1.1rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 0 0 0 rgba(75, 95, 120, 0);
        }

        .poll-step-circle.active {
            background: #4b5f78;
            border-color: #4b5f78;
            color: #fff;
            transform: scale(1.1);
            box-shadow: 0 0 0 6px rgba(75, 95, 120, 0.15);
        }

        .poll-step-circle.completed {
            background: #10b981;
            border-color: #10b981;
            color: transparent;
            position: relative;
        }

        .poll-step-circle.completed::after {
            content: '\F633';
            font-family: "bootstrap-icons";
            position: absolute;
            left: 50%;
            top: 50%;
            translate: -50% -50%;
            color: #fff;
            font-size: 1.3rem;
        }

        /* Premium Inputs */
        .premium-input {
            transition: all 0.3s ease;
            border-color: #cbd5e1;
        }

        .premium-input:focus {
            border-color: #4b5f78;
            box-shadow: 0 0 0 3px rgba(75, 95, 120, 0.1);
        }

        .input-group-text.premium-addon {
            background-color: #f8fafc;
            border-color: #cbd5e1;
            transition: all 0.3s ease;
        }

        .poll-option-row:focus-within .premium-addon {
            border-color: #4b5f78;
            color: #4b5f78 !important;
        }

        /* Premium Cards & Hover */
        .premium-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e2e8f0;
        }

        .premium-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025) !important;
            border-color: #cbd5e1;
        }

        /* Radio Pills for Duration */
        .duration-pills .btn-outline-secondary {
            border-color: #cbd5e1;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 0.25rem;
        }

        .duration-pills .btn-outline-secondary:hover {
            background-color: #f1f5f9;
            color: #334155;
            transform: translateY(-1px);
        }

        .duration-pills .btn-check:checked+.btn-outline-secondary {
            background-color: #4b5f78;
            border-color: #4b5f78;
            color: #fff;
            box-shadow: 0 4px 6px -1px rgba(75, 95, 120, 0.2);
        }

        /* Publish Options */
        .publish-option-label {
            transition: all 0.3s ease;
            border-color: #e2e8f0;
            border-radius: 10px;
        }

        .publish-option-label:hover {
            border-color: #94a3b8;
            background-color: #f8fafc;
            transform: translateY(-2px);
        }

        .publish-option-label.active {
            border-color: #4b5f78 !important;
            background-color: #f8fafc;
            box-shadow: 0 4px 12px rgba(75, 95, 120, 0.1);
        }

        /* Buttons */
        .btn-premium {
            transition: all 0.3s ease;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-premium:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Flatpickr Premium Override */
        .flatpickr-calendar {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
            border-radius: 12px !important;
            border: none !important;
        }

        .flatpickr-day.selected {
            background: #4b5f78 !important;
            border-color: #4b5f78 !important;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .bg-slate {
            background-color: #4b5f78 !important;
        }

        /* Mock Bar Colors */
        .mock-bar-item {
            margin-bottom: 0.6rem;
        }

        .mock-bar-bg {
            background: #f1f5f9;
            border-radius: 6px;
            height: 32px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 0 10px;
            font-size: 0.85rem;
            color: #334155;
        }

        .mock-bar-fill {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 6px;
        }

        .mock-bar-fill.c1 {
            background: #3b82f6;
        }

        .mock-bar-fill.c2 {
            background: #10b981;
        }

        .mock-bar-fill.c3 {
            background: #f59e0b;
        }

        .mock-bar-fill.c4 {
            background: #ef4444;
        }

        .mock-bar-fill.c5 {
            background: #8b5cf6;
        }

        /* Toast Verification */
        .toast-container {
            z-index: 1060;
        }
    </style>

    <!-- Custom Toast -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="surveyToastResult" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header border-0 bg-white">
                <i class="bi bi-check-circle-fill text-success fs-5 me-2"></i>
                <strong class="me-auto text-dark" id="toastTitle">Encuesta Creada</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-white text-secondary" id="toastMsg">
                La encuesta se ha guardado exitosamente.
            </div>
            <div class="progress" style="height: 3px; border-radius: 0;">
                <div class="progress-bar bg-success" id="toastProgress" role="progressbar" style="width: 100%;"></div>
            </div>
        </div>
    </div>

    <!-- Custom Toast Error -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="surveyToastError" class="toast border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header border-0 bg-white">
                <i class="bi bi-x-circle-fill text-danger fs-5 me-2"></i>
                <strong class="me-auto text-dark">Error al procesar</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-white text-secondary" id="toastErrorMsg">
                Ocurrió un error.
            </div>
        </div>
    </div>

<?= $this->section('scripts') ?>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // [Existing filters script omitted for brevity but they are kept safe]

            // --- WIZARD LOGIC ---
            let currentStep = 1;
            const totalSteps = 3;
            const modalEl = document.getElementById('createPollModal');
            const bsModal = new bootstrap.Modal(modalEl);

            const btnNext = document.getElementById('btnNextStep');
            const btnPrev = document.getElementById('btnPrevStep');
            const btnSubmit = document.getElementById('btnSubmitPoll');
            const step1 = document.getElementById('step-content');
            const step2 = document.getElementById('step-config');
            const step3 = document.getElementById('step-review');

            const pollQuestion = document.getElementById('pollQuestion');
            const optionsContainer = document.getElementById('pollOptionsContainer');
            const btnAddOption = document.getElementById('btnAddOption');

            // Date handling
            const radiosDuration = document.querySelectorAll('input[name="pollDuration"]');
            const customDateContainer = document.getElementById('customDateContainer');
            const pollStartDate = document.getElementById('pollStartDate');
            const pollEndDate = document.getElementById('pollEndDate');
            const pollEndsText = document.getElementById('pollEndsText');

            // Setup initial dates using flatpickr
            const today = new Date();
            let targetEndDate = new Date(today);
            targetEndDate.setDate(targetEndDate.getDate() + 7);

            let fpStart, fpEnd;
            if (typeof flatpickr !== 'undefined') {
                fpStart = flatpickr(pollStartDate, { dateFormat: "Y-m-d", defaultDate: "today", locale: "es" });
                fpEnd = flatpickr(pollEndDate, { dateFormat: "Y-m-d", defaultDate: targetEndDate, locale: "es" });
            } else {
                // Fallback
                const yyyy = today.getFullYear();
                const mm = String(today.getMonth() + 1).padStart(2, '0');
                const dd = String(today.getDate()).padStart(2, '0');
                pollStartDate.value = `${yyyy}-${mm}-${dd}`;
                pollEndDate.value = `${targetEndDate.getFullYear()}-${String(targetEndDate.getMonth() + 1).padStart(2, '0')}-${String(targetEndDate.getDate()).padStart(2, '0')}`;
            }

            radiosDuration.forEach(radio => {
                radio.addEventListener('change', function () {
                    if (this.value === 'custom') {
                        customDateContainer.classList.remove('d-none');
                        pollEndsText.classList.add('d-none');
                    } else {
                        customDateContainer.classList.add('d-none');
                        pollEndsText.classList.remove('d-none');
                        const days = parseInt(this.value);
                        pollEndsText.innerText = `Termina en ${days} día${days > 1 ? 's' : ''}`;

                        let endD = new Date(pollStartDate.value || today);
                        endD.setDate(endD.getDate() + days);

                        if (fpEnd) {
                            fpEnd.setDate(endD);
                        } else {
                            pollEndDate.value = `${endD.getFullYear()}-${String(endD.getMonth() + 1).padStart(2, '0')}-${String(endD.getDate()).padStart(2, '0')}`;
                        }
                    }
                });
            });

            // Publish Options UI
            const pubLabels = document.querySelectorAll('.publish-option-label');
            pubLabels.forEach(lbl => {
                lbl.addEventListener('click', function () {
                    pubLabels.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                    const isDraft = document.getElementById('optDraft').checked;
                    btnSubmit.innerHTML = isDraft ? '<i class="bi bi-file-earmark me-1"></i> Guardar borrador' : '<i class="bi bi-send me-1"></i> Publicar ahora';
                });
            });

            const updateStepUI = () => {
                // Hide all
                [step1, step2, step3].forEach(s => s.classList.add('d-none'));

                // Circles Update
                for (let i = 1; i <= 3; i++) {
                    const c = document.getElementById('circle-step-' + i);
                    c.classList.remove('active', 'completed');
                    if (i < currentStep) c.classList.add('completed');
                    else if (i === currentStep) c.classList.add('active');
                }

                // Show active
                if (currentStep === 1) step1.classList.remove('d-none');
                else if (currentStep === 2) step2.classList.remove('d-none');
                else if (currentStep === 3) step3.classList.remove('d-none');

                // Buttons
                if (currentStep === 1) {
                    btnPrev.classList.add('d-none');
                    btnNext.classList.remove('d-none');
                    btnSubmit.classList.add('d-none');
                } else if (currentStep === 3) {
                    btnPrev.classList.remove('d-none');
                    btnNext.classList.add('d-none');
                    btnSubmit.classList.remove('d-none');
                    prepareReviewStep();
                } else {
                    btnPrev.classList.remove('d-none');
                    btnNext.classList.remove('d-none');
                    btnSubmit.classList.add('d-none');
                }
            };

            const showErrorToast = (msg) => {
                document.getElementById('toastErrorMsg').innerText = msg;
                new bootstrap.Toast(document.getElementById('surveyToastError')).show();
            };

            const validateStep1 = () => {
                if (!pollQuestion.value.trim()) { showErrorToast("Debe ingresar la pregunta."); return false; }
                let validOpts = 0;
                document.querySelectorAll('.poll-option-input').forEach(i => { if (i.value.trim()) validOpts++; });
                if (validOpts < 2) { showErrorToast("Debe proporcionar al menos 2 opciones."); return false; }
                return true;
            };

            btnNext.addEventListener('click', () => {
                if (currentStep === 1 && !validateStep1()) return;
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStepUI();
                }
            });

            btnPrev.addEventListener('click', () => {
                if (currentStep > 1) {
                    currentStep--;
                    updateStepUI();
                }
            });

            // Edit links in Review
            document.querySelectorAll('.review-edit-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    currentStep = parseInt(this.getAttribute('data-step'));
                    updateStepUI();
                });
            });

            // Dynamic Options
            const renumberOptions = () => {
                const rows = document.querySelectorAll('.poll-option-row');
                document.getElementById('optionCountDisplay').innerText = rows.length;
                rows.forEach((r, idx) => {
                    r.querySelector('.input-group-text').innerHTML = `<i class="bi bi-list"></i> ${idx + 1}.`;
                    r.querySelector('.remove-option-btn').disabled = (rows.length <= 2);
                });
            };

            btnAddOption.addEventListener('click', () => {
                const div = document.createElement('div');
                div.className = 'input-group mb-2 poll-option-row';
                div.innerHTML = `
                <span class="input-group-text premium-addon bg-white border-end-0 text-muted"></span>
                <input type="text" class="form-control premium-input border-start-0 ps-0 poll-option-input" placeholder="Nueva Opción" required>
                <button class="btn btn-outline-danger border-start-0 remove-option-btn" type="button"><i class="bi bi-x"></i></button>
            `;
                optionsContainer.appendChild(div);

                div.querySelector('.remove-option-btn').addEventListener('click', function () {
                    if (document.querySelectorAll('.poll-option-row').length > 2) {
                        div.remove();
                        renumberOptions();
                    }
                });
                renumberOptions();
            });

            // Setup existing remove buttons
            document.querySelectorAll('.remove-option-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    if (document.querySelectorAll('.poll-option-row').length > 2) {
                        this.closest('.poll-option-row').remove();
                        renumberOptions();
                    }
                });
            });

            // Prepare Review Step
            const prepareReviewStep = () => {
                const question = pollQuestion.value.trim();
                document.getElementById('previewQuestion').innerText = question;
                document.getElementById('summaryQuestion').innerText = question;
                document.getElementById('previewNotificationText').innerText = `Nueva encuesta: ${question}`;

                const categorySelect = document.getElementById('pollCategory');
                document.getElementById('summaryCategoryText').innerText = categorySelect.options[categorySelect.selectedIndex].text;

                const opts = Array.from(document.querySelectorAll('.poll-option-input')).map(i => i.value.trim()).filter(i => i);
                document.getElementById('previewOptCount').innerText = opts.length;
                document.getElementById('summaryOptCount').innerText = opts.length;

                const previewOptContainer = document.getElementById('previewOptions');
                previewOptContainer.innerHTML = '';
                opts.forEach((op, idx) => {
                    const colors = ['c1', 'c2', 'c3', 'c4', 'c5'];
                    const colClass = colors[idx % colors.length];
                    previewOptContainer.innerHTML += `
                    <div class="mock-bar-item">
                        <div class="mock-bar-bg"><div class="mock-bar-fill ${colClass}"></div>${op}</div>
                    </div>
                `;
                });

                // Duration
                const customMode = document.getElementById('durCustom').checked;
                let start = pollStartDate.value;
                let end = pollEndDate.value;
                let d1 = new Date(start); let d2 = new Date(end);
                const diffTime = Math.abs(d2 - d1);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                let durText = `${diffDays} día${diffDays > 1 ? 's' : ''}`;
                document.getElementById('previewDurText').innerText = durText;
                document.getElementById('summaryDurText').innerText = durText;

                const formatDate = (dateStr) => {
                    const d = new Date(dateStr);
                    const formatter = new Intl.DateTimeFormat('es', { month: 'short', day: 'numeric' });
                    return formatter.format(d).replace('.', '');
                };

                document.getElementById('summaryDateRange').innerText = `${formatDate(start)} - ${formatDate(end)}`;
            };

            // Form Submit via AJAX
            btnSubmit.addEventListener('click', () => {
                const btnOriginalText = btnSubmit.innerHTML;
                btnSubmit.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Procesando...`;
                btnSubmit.disabled = true;

                const question = pollQuestion.value.trim();
                const opts = Array.from(document.querySelectorAll('.poll-option-input')).map(i => i.value.trim()).filter(i => i);
                let start = pollStartDate.value;
                if (!start.includes(":")) start += " 00:00:00";
                let end = pollEndDate.value;
                if (!end.includes(":")) end += " 23:59:59";

                const categoryVal = document.getElementById('pollCategory').value;
                const isActive = document.getElementById('optPublish').checked ? 1 : 0;

                fetch('<?= base_url("/admin/encuestas/crear") ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                    body: JSON.stringify({
                        title: question,
                        options: opts,
                        start_date: start,
                        end_date: end,
                        category: categoryVal,
                        is_active: isActive
                    })
                })
                    .then(res => res.json())
                    .then(data => {
                        btnSubmit.innerHTML = btnOriginalText;
                        btnSubmit.disabled = false;

                        if (data.status === 201) {
                            bsModal.hide();

                            // Show custom toast premium
                            const t = new bootstrap.Toast(document.getElementById('surveyToastResult'));
                            t.show();

                            // Toast auto progress reload
                            let pb = document.getElementById('toastProgress');
                            pb.style.transition = 'width 2s linear';
                            setTimeout(() => pb.style.width = '0%', 100);

                            setTimeout(() => window.location.reload(), 2000);
                        } else {
                            let errMsg = data.error || 'Ocurrió un problema guardando la encuesta.';
                            if (data.details) errMsg += ' ' + JSON.stringify(data.details);
                            showErrorToast('Error: ' + errMsg);
                        }
                    })
                    .catch(err => {
                        btnSubmit.innerHTML = btnOriginalText;
                        btnSubmit.disabled = false;
                        showErrorToast('Error de conexión o de servidor.');
                    });
            });

            // Ends modal create logic
        });
    </script>

    <?php if (!empty($normalized)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var statusFilter = 'all';
                var categoryFilter = '';
                var searchInput = document.getElementById('poll-search');
                var rows = Array.from(document.querySelectorAll('.poll-item'));
                var list = document.getElementById('poll-list');
                var emptyFiltered = document.getElementById('polls-filter-empty');

                var statusButtons = Array.from(document.querySelectorAll('[data-filter-status]'));
                var categoryButtons = Array.from(document.querySelectorAll('[data-filter-category]'));

                var applyFilters = function () {
                    var term = (searchInput ? searchInput.value : '').trim().toLowerCase();
                    var visible = 0;

                    rows.forEach(function (row) {
                        var rowStatus = row.getAttribute('data-status') || '';
                        var rowCategory = row.getAttribute('data-category') || '';
                        var rowSearch = (row.getAttribute('data-search') || '').toLowerCase();

                        var statusMatch = statusFilter === 'all' || rowStatus === statusFilter;
                        var categoryMatch = categoryFilter === '' || rowCategory === categoryFilter;
                        var searchMatch = term === '' || rowSearch.indexOf(term) !== -1;

                        var show = statusMatch && categoryMatch && searchMatch;
                        row.style.display = show ? '' : 'none';
                        if (show) {
                            visible += 1;
                        }
                    });

                    if (visible === 0) {
                        list.classList.add('d-none');
                        emptyFiltered.classList.remove('d-none');
                    } else {
                        list.classList.remove('d-none');
                        emptyFiltered.classList.add('d-none');
                    }
                };

                statusButtons.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        statusFilter = btn.getAttribute('data-filter-status') || 'all';
                        statusButtons.forEach(function (item) {
                            item.classList.remove('active');
                        });
                        btn.classList.add('active');
                        applyFilters();
                    });
                });

                categoryButtons.forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        var current = btn.getAttribute('data-filter-category') || '';
                        categoryFilter = categoryFilter === current ? '' : current;
                        categoryButtons.forEach(function (item) {
                            item.classList.remove('active');
                        });
                        if (categoryFilter !== '') {
                            btn.classList.add('active');
                        }
                        applyFilters();
                    });
                });

                if (searchInput) {
                    searchInput.addEventListener('input', applyFilters);
                }
            });
        </script>
    <?php endif; ?>
<?= $this->endSection() ?>
<?= $this->endSection() ?>