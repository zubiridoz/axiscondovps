<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<?php
$documents = is_array($documents ?? null) ? $documents : [];
?>

<style>
    .docs-hero {
        background: linear-gradient(135deg, #3b4b63 0%, #1f2d45 100%);
        border-radius: 0.6rem;
        padding: 1.5rem 1.35rem;
        color: #ffffff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, 0.16);
    }

    .docs-hero h2 {
        margin: 0 0 0.3rem 0;
        font-weight: 700;
    }

    .docs-hero p {
        margin: 0;
        color: rgba(255, 255, 255, 0.82);
        font-size: 0.92rem;
    }

    .docs-layout {
        margin-top: 1.2rem;
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 0.9rem;
        min-height: 640px;
    }

    .docs-sidebar {
        border: 1px solid #d9e1eb;
        border-radius: 0.6rem;
        background: #ffffff;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .docs-sidebar-body {
        padding: 0.9rem 0.75rem 0.6rem;
        flex: 1;
    }

    .docs-link {
        width: 100%;
        background: transparent;
        border: none;
        text-align: left;
        color: #0f172a;
        font-size: 0.95rem;
        border-radius: 0.45rem;
        padding: 0.52rem 0.58rem;
        display: flex;
        align-items: center;
        gap: 0.56rem;
        cursor: pointer;
    }

    .docs-link.active {
        background: #1C2434;
        color: #ffffff;
        font-weight: 600;
    }

    .docs-link:hover {
        background: #f8fafc;
    }

    .docs-link.active:hover {
        background: #41556f;
    }

    .docs-group-title {
        color: #57708f;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        font-size: 0.74rem;
        font-weight: 600;
        margin: 0.88rem 0.25rem 0.48rem;
    }

    .docs-divider {
        border-top: 1px solid #d8e1ec;
        margin: 0 0.2rem;
    }

    .docs-sidebar-footer {
        border-top: 1px solid #d8e1ec;
        background: #fafcff;
        padding: 0.75rem;
    }

    .docs-storage-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: #0f172a;
        margin-bottom: 0.55rem;
    }

    .docs-storage-bar {
        height: 6px;
        border-radius: 999px;
        background: #dde6f0;
        overflow: hidden;
        margin-bottom: 0.45rem;
    }

    .docs-storage-fill {
        width: 0%;
        height: 100%;
        background: #1C2434;
    }

    .docs-storage-note {
        color: #57708f;
        font-size: 0.78rem;
    }

    .docs-main {
        min-width: 0;
    }

    .docs-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 0.8rem;
        flex-wrap: wrap;
        margin-bottom: 0.62rem;
    }

    .docs-search-wrap {
        position: relative;
        width: 100%;
        max-width: 360px;
    }

    .docs-search-wrap i {
        position: absolute;
        left: 0.7rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .docs-search {
        width: 100%;
        border: 1px solid #d0d8e2;
        border-radius: 0.45rem;
        background: #ffffff;
        padding: 0.52rem 0.76rem 0.52rem 2rem;
        font-size: 0.9rem;
        color: #334155;
    }

    .docs-search:focus {
        outline: none;
        border-color: #93a5bc;
        box-shadow: 0 0 0 4px rgba(147, 165, 188, 0.14);
    }

    .docs-action-set {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }

    .docs-view-btn {
        border: 1px solid #d0d8e2;
        border-radius: 0.38rem;
        background: #ffffff;
        color: #334155;
        width: 34px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .docs-view-btn.active {
        background: #1C2434;
        color: #ffffff;
        border-color: #1C2434;
    }

    .docs-new-btn {
        border: none;
        border-radius: 0.42rem;
        background: #1C2434;
        color: #ffffff;
        font-size: 0.94rem;
        font-weight: 600;
        padding: 0.5rem 0.95rem;
    }

    .docs-new-btn:hover {
        background: #41556f;
        color: #ffffff;
    }

    .docs-canvas {
        border: 1px dashed #d4deea;
        border-radius: 0.6rem;
        min-height: 360px;
        background: #ffffff;
        padding: 1.1rem;
    }

    .docs-empty {
        min-height: 334px;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    .docs-empty-icon {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        background: #edf2f8;
        color: #657b96;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1rem;
    }

    .docs-empty h3 {
        margin: 0 0 0.6rem;
        font-size: 2rem;
        line-height: 1;
    }

    .docs-empty p {
        margin: 0 auto 1rem;
        color: #57708f;
        max-width: 440px;
        font-size: 1.04rem;
    }

    .docs-upload-btn {
        border: none;
        border-radius: 0.45rem;
        background: #1C2434;
        color: #ffffff;
        font-weight: 600;
        padding: 0.58rem 1.15rem;
        min-width: 160px;
    }

    .docs-upload-btn:hover {
        background: #41556f;
    }

    .docs-drop-note {
        margin-top: 0.55rem;
        color: #57708f;
        font-size: 0.95rem;
    }

    .docs-file-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(290px, 1fr));
        gap: 0.8rem;
    }

    .docs-file-card {
        border: 1px solid #e2e8f0;
        border-radius: 0.55rem;
        padding: 0.9rem;
        background: #ffffff;
        position: relative;
    }

    .docs-file-card:hover {
        border-color: #cbd7e5;
        box-shadow: 4px 8px 16px rgba(15, 23, 42, 0.08);
    }

    .docs-file-title {
        color: #0f172a;
        font-size: 0.97rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
        word-break: break-all;
    }

    .docs-file-meta {
        color: #57708f;
        font-size: 0.82rem;
        margin-bottom: 0.65rem;
    }

    .docs-file-actions {
        display: flex;
        gap: 0.45rem;
    }

    .docs-file-actions button,
    .docs-file-actions a {
        border: 1px solid #d0d8e2;
        border-radius: 0.38rem;
        background: #ffffff;
        color: #1e293b;
        font-size: 0.8rem;
        padding: 0.3rem 0.55rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .docs-file-actions button:hover,
    .docs-file-actions a:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    /* Config form styling */
    .file-config-item {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1rem;
        margin-bottom: 0.8rem;
        background: #fff;
    }

    .docs-dropzone.dragover {
        background-color: #f1f5f9;
        border-color: #3b82f6 !important;
    }

    /* Analytics tabs - override global white hover from sidebar theme */
    .docs-main .nav-tabs .nav-link {
        color: #334155 !important;
        background: transparent !important;
        border: 1px solid transparent;
        border-bottom: none;
        padding: 0.5rem 1rem;
    }

    .docs-main .nav-tabs .nav-link:hover {
        color: #0f172a !important;
        background: #f1f5f9 !important;
        border-color: #e2e8f0 #e2e8f0 transparent;
    }

    /* List View Table */
    .docs-list-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    .docs-list-table thead th {
        background: #f8fafc;
        color: #57708f;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 0.6rem 0.75rem;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
        cursor: pointer;
        user-select: none;
    }

    .docs-list-table thead th:hover {
        color: #334155;
    }

    .docs-list-table thead th i {
        font-size: 0.65rem;
        margin-left: 3px;
    }

    .docs-list-table tbody tr {
        transition: background 0.15s;
    }

    .docs-list-table tbody tr:hover {
        background: #f8fafc;
    }

    .docs-list-table tbody td {
        padding: 0.65rem 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.88rem;
        color: #334155;
        vertical-align: middle;
    }

    .docs-list-table .list-file-name {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .docs-list-table .list-file-name .name-text {
        font-weight: 600;
        color: #0f172a;
    }

    .docs-list-table .list-file-name .name-sub {
        font-size: 0.75rem;
        color: #94a3b8;
    }

    .docs-list-table .list-badge {
        font-size: 0.72rem;
        padding: 0.22rem 0.55rem;
        border-radius: 999px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .docs-list-table .badge-admin {
        background: #fef3c7;
        color: #92400e;
    }

    .docs-list-table .badge-prop {
        background: #dbeafe;
        color: #1e40af;
    }

    .docs-list-table .badge-todos {
        background: #f0fdf4;
        color: #166534;
    }

    .docs-main .nav-tabs .nav-link.active {
        color: #10b981 !important;
        background: #fff !important;
        border-color: #dee2e6 #dee2e6 #fff;
        font-weight: 600;
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

<div class="cc-hero">
    <div class="cc-hero-left">
        <h2 class="cc-hero-title">Documentos</h2>
        <div class="cc-hero-divider"></div>
        <div class="cc-hero-breadcrumb">
            <i class="bi bi-file-earmark-text"></i>
            <i class="bi bi-chevron-right"></i>
            Archivos Comunidad
        </div>
    </div>



    <div class="dropdown d-inline-block">
        <button class="cc-hero-btn" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-plus-lg me-1"></i>
            Nuevo</button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-2">
            <li><a class="dropdown-item file-upload-trigger" href="#" onclick="openUploadModal()"><i
                        class="bi bi-upload text-secondary me-2"></i> Subir archivo</a></li>
            <li><a class="dropdown-item folder-create-trigger" href="#" onclick="createFolderPrompt()"><i
                        class="bi bi-folder-plus text-secondary me-2"></i> Crear Carpeta</a></li>
        </ul>
    </div>
</div>

<div class="docs-layout">
    <aside class="docs-sidebar">
        <div class="docs-sidebar-body">
            <?php
            $isAnalytics = isset($analyticsView) && $analyticsView;
            $af = $accessFilter ?? '';
            $fl = $filter ?? '';
            $noFilter = !$isAnalytics && empty($af) && empty($fl);
            ?>
            <a href="<?= base_url('admin/documentos') ?>"
                class="docs-link d-inline-block text-decoration-none <?= $noFilter ? 'active' : '' ?>"><i
                    class="bi bi-grid"></i> Inicio</a>

            <div class="docs-group-title">Acceso rápido</div>
            <div class="docs-divider"></div>
            <a href="?filter=recientes"
                class="docs-link d-inline-block text-decoration-none <?= $fl === 'recientes' ? 'active' : '' ?>"><i
                    class="bi bi-clock-history"></i> Recientes</a>
            <a href="?filter=destacados"
                class="docs-link d-inline-block text-decoration-none <?= $fl === 'destacados' ? 'active' : '' ?>"><i
                    class="bi bi-star"></i> Destacados</a>

            <div class="docs-group-title">Por nivel de acceso <i class="bi bi-info-circle"></i></div>
            <div class="docs-divider"></div>
            <a href="?access=admin"
                class="docs-link d-inline-block text-decoration-none <?= $af === 'admin' ? 'active' : '' ?>"><i
                    class="bi bi-lock"></i> Admin</a>
            <a href="?access=propietarios"
                class="docs-link d-inline-block text-decoration-none <?= $af === 'propietarios' ? 'active' : '' ?>"><i
                    class="bi bi-house-door"></i> Propietarios</a>
            <a href="?access=todos"
                class="docs-link d-inline-block text-decoration-none <?= $af === 'todos' ? 'active' : '' ?>"><i
                    class="bi bi-people"></i> Todos</a>

            <div class="docs-group-title">Perspectivas</div>
            <div class="docs-divider"></div>
            <a href="?view=analytics"
                class="docs-link d-inline-block text-decoration-none <?= $isAnalytics ? 'active' : '' ?>"><i
                    class="bi bi-bar-chart-line"></i> Analíticas</a>
        </div>
        <div class="docs-sidebar-footer">
            <div class="docs-storage-title"><i class="bi bi-hdd-stack me-1"></i> Almacenamiento</div>
            <?php
            $totalBytes = $totalStorageBytes ?? 0;
            $totalKB = round($totalBytes / 1024, 1);
            $totalMB = round($totalBytes / (1024 * 1024), 2);
            $storageDisplay = $totalMB >= 1 ? ($totalMB . ' MB') : ($totalKB . ' KB');
            ?>
            <div class="docs-storage-bar">
                <div class="docs-storage-fill" style="width: <?= min(100, max(0, ($totalMB / 1000) * 100)) ?>%"></div>
            </div>
            <div class="docs-storage-note"><?= $storageDisplay ?> de 1.0 GB usado</div>
        </div>
    </aside>

    <section class="docs-main">
        <?php if (isset($analyticsView) && $analyticsView): ?>
            <!-- ======================== ANALYTICS VIEW ======================== -->
            <?php $a = $analytics; ?>
            <div class="px-1 py-3">
                <h5 class="fw-bold text-dark mb-1">Analíticas</h5>
                <p class="text-secondary small mb-4">Ver análisis detallado y métricas de uso de archivos</p>

                <!-- KPI Cards Row 1 -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-secondary small fw-semibold mb-1">Total de Archivos</div>
                                        <div class="fs-3 fw-bold text-dark"><?= $a['totalFiles'] ?></div>
                                        <div class="text-muted" style="font-size:0.75rem;"><?= $a['totalStorageKB'] ?> KB
                                            almacenamiento total</div>
                                    </div>
                                    <div class="bg-light rounded-2 p-2"><i class="bi bi-files fs-5 text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-secondary small fw-semibold mb-1">Total de Vistas</div>
                                        <div class="fs-3 fw-bold text-dark"><?= $a['totalViews'] ?></div>
                                        <div class="text-muted" style="font-size:0.75rem;"><?= $a['uniqueViewers'] ?>
                                            visores únicos</div>
                                    </div>
                                    <div class="bg-light rounded-2 p-2"><i class="bi bi-eye fs-5 text-secondary"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-secondary small fw-semibold mb-1">Total de Descargas</div>
                                        <div class="fs-3 fw-bold text-dark"><?= $a['totalDownloads'] ?></div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            <?= $a['totalFiles'] > 0 ? round($a['totalDownloads'] / $a['totalFiles'], 1) : 0 ?>
                                            promedio por archivo
                                        </div>
                                    </div>
                                    <div class="bg-light rounded-2 p-2"><i class="bi bi-download fs-5 text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KPI Cards Row 2 -->
                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-secondary small fw-semibold mb-1">Archivos Compartidos</div>
                                        <div class="fs-3 fw-bold text-dark"><?= $a['sharedFiles'] ?></div>
                                        <div class="text-muted" style="font-size:0.75rem;">
                                            <?= $a['totalFiles'] > 0 ? round(($a['sharedFiles'] / $a['totalFiles']) * 100) : 0 ?>%
                                            del total
                                        </div>
                                    </div>
                                    <div class="bg-light rounded-2 p-2"><i class="bi bi-share fs-5 text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-secondary small fw-semibold mb-1">Actividad Reciente</div>
                                        <div class="fs-3 fw-bold text-dark"><?= $a['recentActivity'] ?></div>
                                        <div class="text-muted" style="font-size:0.75rem;">vistas en los últimos 30 días
                                        </div>
                                    </div>
                                    <div class="bg-light rounded-2 p-2"><i class="bi bi-graph-up fs-5 text-secondary"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm rounded-3 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <div class="text-secondary small fw-semibold mb-1">Almacenamiento Utilizado</div>
                                        <div class="fs-3 fw-bold text-dark"><?= $a['totalStorageKB'] ?> KB</div>
                                        <div class="text-muted" style="font-size:0.75rem;"><?= $a['storagePercent'] ?>% del
                                            límite</div>
                                    </div>
                                    <div class="bg-light rounded-2 p-2"><i class="bi bi-hdd fs-5 text-secondary"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabs -->
                <ul class="nav nav-tabs border-bottom mb-0" id="analyticsTabs" role="tablist">
                    <li class="nav-item"><a class="nav-link active fw-semibold text-dark" id="tab-resumen"
                            data-bs-toggle="tab" href="#resumen" role="tab">Resumen</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark" id="tab-popular" data-bs-toggle="tab"
                            href="#popular" role="tab">Popular</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark" id="tab-actividad" data-bs-toggle="tab"
                            href="#actividad" role="tab">Actividad</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold text-dark" id="tab-visores" data-bs-toggle="tab"
                            href="#visores" role="tab">Visores</a></li>
                </ul>

                <div class="tab-content pt-4">
                    <!-- TAB: Resumen -->
                    <div class="tab-pane fade show active" id="resumen" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-dark mb-1">Distribución por Categoría</h6>
                                        <p class="text-secondary small mb-3">Espacio usado por categoría</p>
                                        <div style="max-width: 280px; margin: 0 auto;">
                                            <canvas id="categoryDonutChart" height="280"></canvas>
                                        </div>
                                        <div class="d-flex flex-wrap gap-3 justify-content-center mt-3">
                                            <?php
                                            $catColors = ['#e07a5f', '#3d405b', '#81b29a', '#f2cc8f', '#6d6875', '#e5989b', '#b5838d'];
                                            $ci = 0;
                                            foreach ($a['categories'] as $cat): ?>
                                                <span class="d-flex align-items-center gap-1" style="font-size:0.8rem;">
                                                    <span
                                                        style="width:10px;height:10px;border-radius:50%;background:<?= $catColors[$ci % count($catColors)] ?>;display:inline-block;"></span>
                                                    <?= esc($cat['category'] ?? 'General') ?>
                                                </span>
                                                <?php $ci++; endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-dark mb-1">Almacenamiento por Categoría</h6>
                                        <p class="text-secondary small mb-3">Espacio usado por categoría</p>
                                        <canvas id="categoryBarChart" height="280"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Popular -->
                    <div class="tab-pane fade" id="popular" role="tabpanel">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-dark mb-1">Más Visto</h6>
                                        <p class="text-secondary small mb-3">Los 10 archivos más vistos</p>
                                        <?php if (empty($a['mostViewed'])): ?>
                                            <p class="text-muted text-center py-4">Sin datos aún</p>
                                        <?php else: ?>
                                            <?php $rank = 1;
                                            foreach ($a['mostViewed'] as $mv): ?>
                                                <div class="d-flex align-items-center py-2 <?= $rank > 1 ? 'border-top' : '' ?>">
                                                    <span class="text-secondary fw-bold me-3"
                                                        style="min-width:20px;"><?= $rank ?></span>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold text-dark" style="font-size:0.92rem;">
                                                            <?= esc($mv['name']) ?>
                                                        </div>
                                                        <div class="text-muted" style="font-size:0.75rem;"><i
                                                                class="bi bi-eye me-1"></i> <?= $mv['view_count'] ?> vistas
                                                            (<?= $mv['unique_viewers'] ?> únicos)</div>
                                                    </div>
                                                    <span class="badge bg-light text-secondary border"
                                                        style="font-size:0.75rem;"><?= esc($mv['category'] ?? 'General') ?></span>
                                                </div>
                                                <?php $rank++; endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm rounded-3">
                                    <div class="card-body">
                                        <h6 class="fw-bold text-dark mb-1">Más Descargado</h6>
                                        <p class="text-secondary small mb-3">Los 10 archivos más descargados</p>
                                        <?php if (empty($a['mostDownloaded'])): ?>
                                            <p class="text-muted text-center py-4">Sin datos aún</p>
                                        <?php else: ?>
                                            <?php $rank = 1;
                                            foreach ($a['mostDownloaded'] as $md): ?>
                                                <div class="d-flex align-items-center py-2 <?= $rank > 1 ? 'border-top' : '' ?>">
                                                    <span class="text-secondary fw-bold me-3"
                                                        style="min-width:20px;"><?= $rank ?></span>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-semibold text-dark" style="font-size:0.92rem;">
                                                            <?= esc($md['name']) ?>
                                                        </div>
                                                        <div class="text-muted" style="font-size:0.75rem;"><i
                                                                class="bi bi-download me-1"></i> <?= $md['download_count'] ?>
                                                            descargas</div>
                                                    </div>
                                                    <span class="badge bg-light text-secondary border"
                                                        style="font-size:0.75rem;"><?= esc($md['category'] ?? 'General') ?></span>
                                                </div>
                                                <?php $rank++; endforeach; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Actividad -->
                    <div class="tab-pane fade" id="actividad" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body">
                                <h6 class="fw-bold text-dark mb-1">Actividad Reciente</h6>
                                <p class="text-secondary small mb-3">Últimos 30 días</p>
                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <div class="card border rounded-3">
                                            <div class="card-body d-flex align-items-center gap-3 py-3">
                                                <i class="bi bi-eye fs-4 text-secondary"></i>
                                                <div>
                                                    <div class="text-secondary small">vistas</div>
                                                    <div class="fs-4 fw-bold text-dark"><?= $a['recentViews'] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card border rounded-3">
                                            <div class="card-body d-flex align-items-center gap-3 py-3">
                                                <i class="bi bi-download fs-4 text-success"></i>
                                                <div>
                                                    <div class="text-secondary small">descargas</div>
                                                    <div class="fs-4 fw-bold text-dark"><?= $a['recentDownloads'] ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <canvas id="activityChart" height="200"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: Visores -->
                    <div class="tab-pane fade" id="visores" role="tabpanel">
                        <div class="card border-0 shadow-sm rounded-3">
                            <div class="card-body">
                                <h6 class="fw-bold text-dark mb-1">Principales Visores</h6>
                                <p class="text-secondary small mb-3">Usuarios más activos</p>
                                <?php if (empty($a['topViewers'])): ?>
                                    <p class="text-muted text-center py-4">Sin datos aún</p>
                                <?php else: ?>
                                    <?php $rank = 1;
                                    foreach ($a['topViewers'] as $tv): ?>
                                        <div class="d-flex align-items-center py-3 <?= $rank > 1 ? 'border-top' : '' ?>">
                                            <span class="text-secondary fw-bold me-3" style="min-width:20px;"><?= $rank ?></span>
                                            <?php
                                            $uName = $tv['user_name'] ?? 'Usuario';
                                            $uEmail = $tv['user_email'] ?? '';
                                            $initials = '';
                                            $parts = explode(' ', $uName);
                                            foreach ($parts as $p) {
                                                $initials .= mb_strtolower(mb_substr($p, 0, 1));
                                            }
                                            ?>
                                            <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center me-3"
                                                style="width:38px;height:38px;font-size:0.85rem;font-weight:600;">
                                                <?= esc($initials) ?>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold text-dark" style="font-size:0.92rem;"><?= esc($uName) ?>
                                                </div>
                                                <div class="text-muted" style="font-size:0.75rem;"><?= esc($uEmail) ?></div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold text-primary"><?= $tv['view_count'] ?></div>
                                                <div class="text-muted" style="font-size:0.7rem;">vistas</div>
                                            </div>
                                        </div>
                                        <?php $rank++; endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <!-- ======================== FILES VIEW ======================== -->
            <div class="docs-toolbar">
                <div class="docs-search-wrap">
                    <i class="bi bi-search"></i>
                    <input class="docs-search" type="text" placeholder="Buscar archivos y carpetas..." id="docs-search">
                </div>
                <div class="docs-action-set">
                    <button class="docs-view-btn active" id="btn-grid-view" title="Vista cuadrícula"
                        onclick="switchView('grid')"><i class="bi bi-grid-3x3-gap"></i></button>
                    <button class="docs-view-btn" id="btn-list-view" title="Vista lista" onclick="switchView('list')"><i
                            class="bi bi-list"></i></button>


                </div>
            </div>

            <?php if (empty($documents)): ?>
                <div class="docs-canvas">
                    <div class="docs-empty">
                        <div>
                            <div class="docs-empty-icon"><i class="bi bi-upload"></i></div>
                            <h3>Aún no hay documentos</h3>
                            <p>Suba su primer documento para comenzar. Puede organizar archivos, reglas, regulaciones,
                                documentos financieros y más.</p>
                            <button class="docs-upload-btn" onclick="openUploadModal()"><i class="bi bi-upload me-1"></i> Subir
                                archivos</button>
                            <div class="docs-drop-note">o arrastra y suelta</div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <?php
                $folders = array_filter($documents, fn($d) => $d['type'] === 'folder');
                $files = array_filter($documents, fn($d) => $d['type'] === 'file');
                ?>

                <!-- ==================== GRID VIEW ==================== -->
                <div class="docs-canvas bg-transparent border-0 p-0" id="docs-grid-view">
                    <?php if (!empty($folders)): ?>
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-3 mt-2" style="letter-spacing: 0.5px;">
                                CARPETAS</h6>
                            <div class="docs-file-grid" id="docs-folders-grid">
                                <?php foreach ($folders as $doc): ?>
                                    <?php
                                    $name = trim((string) ($doc['name'] ?? 'Doc'));
                                    $search = strtolower(trim($name . ' ' . $doc['category'] . ' ' . $doc['access_level']));
                                    ?>
                                    <article
                                        class="docs-file-card docs-item d-flex flex-column align-items-center justify-content-center text-center py-4"
                                        data-search="<?= esc($search) ?>" style="min-height: 140px; cursor: pointer;"
                                        ondblclick="window.location.href='?folder=<?= $doc['hash_id'] ?>'">
                                        <?php if ($doc['is_starred'] == 1): ?>
                                            <div class="position-absolute" style="top: 10px; left: 10px;">
                                                <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                    style="width: 25px; height: 25px;">
                                                    <i class="bi bi-star-fill text-warning" style="font-size: 0.85rem;"></i>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div class="position-absolute dropdown" style="top: 10px; right: 10px;"
                                            onclick="event.stopPropagation()">
                                            <button class="btn btn-sm text-secondary border-0" data-bs-toggle="dropdown"><i
                                                    class="bi bi-three-dots-vertical"></i></button>
                                            <ul class="dropdown-menu shadow-sm border-0 py-1" style="font-size: 0.9rem;">
                                                <li><a class="dropdown-item py-2" href="#" onclick="toggleStar(<?= $doc['id'] ?>)"><i
                                                            class="bi bi-star text-secondary me-2"
                                                            style="font-size: 1.1rem; vertical-align: middle;"></i>
                                                        <?= $doc['is_starred'] == 1 ? 'Quitar Destacado' : 'Destacar' ?></a></li>
                                                <li><a class="dropdown-item py-2" href="#"
                                                        onclick="openMoveModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>')"><i
                                                            class="bi bi-box-arrow-in-right text-secondary me-2"
                                                            style="font-size: 1.1rem; vertical-align: middle;"></i> Mover</a></li>
                                                <li><a class="dropdown-item py-2" href="#"
                                                        onclick="openRenameModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>')"><i
                                                            class="bi bi-pencil text-secondary me-2"
                                                            style="font-size: 1.1rem; vertical-align: middle;"></i> Renombrar</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item py-2 text-danger" href="#"
                                                        onclick="deleteDocument(<?= $doc['id'] ?>)"><i
                                                            class="bi bi-trash text-danger me-2"
                                                            style="font-size: 1.1rem; vertical-align: middle;"></i> Eliminar</a></li>
                                            </ul>
                                        </div>
                                        <div class="bg-light rounded-3 d-inline-flex align-items-center justify-content-center mb-2"
                                            style="width: 54px; height: 44px;">
                                            <i class="bi bi-folder-fill fs-3 text-secondary"></i>
                                        </div>
                                        <div class="fw-bold text-dark w-100 px-3 text-truncate" style="font-size: 0.95rem;">
                                            <?= esc($name) ?>
                                        </div>
                                        <div class="text-muted small"><?= isset($doc['element_count']) ? $doc['element_count'] : 0 ?>
                                            elementos</div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($files)): ?>
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-3 mt-4" style="letter-spacing: 0.5px;">
                                ARCHIVOS</h6>
                            <div class="docs-file-grid" id="docs-files-grid">
                                <?php foreach ($files as $doc): ?>
                                    <?php
                                    $name = trim((string) ($doc['name'] ?? 'Doc'));
                                    $size = number_format(($doc['size_bytes'] ?? 0) / 1024, 2) . ' KB';
                                    $search = strtolower(trim($name . ' ' . $doc['category'] . ' ' . $doc['access_level']));

                                    $badgeClass = 'bg-light text-secondary';
                                    $badgeIcon = 'bi-people-fill';
                                    if ($doc['access_level'] === 'Solo Admins') {
                                        $badgeClass = 'bg-warning text-dark bg-opacity-25';
                                        $badgeIcon = 'bi-lock-fill';
                                    } elseif ($doc['access_level'] === 'Propietarios') {
                                        $badgeClass = 'bg-primary text-primary bg-opacity-10';
                                        $badgeIcon = 'bi-house-heart-fill';
                                    }
                                    ?>
                                    <article class="docs-file-card docs-item d-flex flex-column" data-search="<?= esc($search) ?>"
                                        style="min-height: 140px;">
                                        <div class="d-flex justify-content-between align-items-center mb-auto">
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if ($doc['is_starred'] == 1): ?>
                                                    <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 25px; height: 25px;">
                                                        <i class="bi bi-star-fill text-warning" style="font-size: 0.85rem;"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <span class="badge rounded-pill <?= $badgeClass ?> px-2 py-1 fw-semibold"
                                                    style="font-size: 0.75rem;">
                                                    <i class="bi <?= $badgeIcon ?> me-1"></i>
                                                    <?= esc($doc['access_level'] === 'Solo Admins' ? 'Admin' : ($doc['access_level'] === 'Propietarios' ? 'Admins y Prop.' : $doc['access_level'])) ?>
                                                </span>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm text-secondary border-0" data-bs-toggle="dropdown"><i
                                                        class="bi bi-three-dots-vertical"></i></button>
                                                <ul class="dropdown-menu shadow-sm border-0 py-1" style="font-size: 0.9rem;">
                                                    <li><a class="dropdown-item py-2" href="#"
                                                            onclick="openDetailModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>', '<?= esc($size) ?>', '<?= esc(addslashes($doc['category'])) ?>', '<?= esc(addslashes($doc['access_level'])) ?>')"><i
                                                                class="bi bi-eye text-secondary me-2"
                                                                style="font-size: 1.1rem; vertical-align: middle;"></i> Vista Previa</a>
                                                    </li>
                                                    <li><a class="dropdown-item py-2" href="#"
                                                            onclick="openShareModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>', '<?= esc(addslashes($doc['category'])) ?>', '<?= esc(addslashes($doc['access_level'])) ?>')"><i
                                                                class="bi bi-share text-secondary me-2"
                                                                style="font-size: 1.1rem; vertical-align: middle;"></i> Compartir</a>
                                                    </li>
                                                    <li><a class="dropdown-item py-2"
                                                            href="<?= base_url('admin/documentos/download/' . $doc['id']) ?>"><i
                                                                class="bi bi-download text-secondary me-2"
                                                                style="font-size: 1.1rem; vertical-align: middle;"></i> Descargar</a>
                                                    </li>
                                                    <li><a class="dropdown-item py-2" href="#"
                                                            onclick="toggleStar(<?= $doc['id'] ?>)"><i
                                                                class="bi bi-star text-secondary me-2"
                                                                style="font-size: 1.1rem; vertical-align: middle;"></i>
                                                            <?= $doc['is_starred'] == 1 ? 'Quitar Destacado' : 'Destacar' ?></a></li>
                                                    <li><a class="dropdown-item py-2" href="#"
                                                            onclick="openMoveModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>')"><i
                                                                class="bi bi-box-arrow-in-right text-secondary me-2"
                                                                style="font-size: 1.1rem; vertical-align: middle;"></i> Mover</a></li>
                                                    <li><a class="dropdown-item py-2" href="#"
                                                            onclick="openRenameModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>')"><i
                                                                class="bi bi-pencil text-secondary me-2"
                                                                style="font-size: 1.1rem; vertical-align: middle;"></i> Renombrar</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item py-2 text-danger" href="#"
                                                            onclick="deleteDocument(<?= $doc['id'] ?>)"><i
                                                                class="bi bi-trash text-danger me-2"
                                                                style="font-size: 1.1rem; vertical-align: middle;"></i> Eliminar</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="d-flex align-items-center gap-3 mt-4" style="cursor: pointer;"
                                            onclick="openDetailModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>', '<?= esc($size) ?>', '<?= esc(addslashes($doc['category'])) ?>', '<?= esc(addslashes($doc['access_level'])) ?>')">
                                            <i class="bi bi-file-earmark-pdf-fill fs-2 text-danger"></i>
                                            <div class="overflow-hidden">
                                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.95rem;">
                                                    <?= esc($name) ?>
                                                </div>
                                                <div class="text-muted text-uppercase"
                                                    style="font-size: 0.70rem; letter-spacing: 0.5px;">
                                                    FILE &bull; <?= esc($size) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- ==================== LIST VIEW ==================== -->
                <div class="d-none" id="docs-list-view">
                    <?php if (!empty($folders)): ?>
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-3 mt-2" style="letter-spacing: 0.5px;">
                                CARPETAS</h6>
                            <div class="bg-white rounded-3 border" style="overflow: hidden;">
                                <table class="docs-list-table">
                                    <thead>
                                        <tr>
                                            <th style="width:55%;">Nombre <i class="bi bi-arrow-up"></i></th>
                                            <th>Fecha <i class="bi bi-arrow-down-up"></i></th>
                                            <th style="width:80px;">Nuevo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($folders as $doc): ?>
                                            <?php $name = trim((string) ($doc['name'] ?? 'Carpeta')); ?>
                                            <tr style="cursor:pointer;" ondblclick="window.location.href='?folder=<?= $doc['hash_id'] ?>'">
                                                <td>
                                                    <div class="list-file-name">
                                                        <i class="bi bi-folder-fill text-secondary fs-5"></i>
                                                        <div>
                                                            <div class="name-text"><?= esc($name) ?></div>
                                                            <div class="name-sub">
                                                                <?= isset($doc['element_count']) ? $doc['element_count'] : 0 ?>
                                                                elementos
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted"><?= date('M j, Y', strtotime($doc['created_at'])) ?></td>
                                                <td class="text-end">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm text-secondary border-0" data-bs-toggle="dropdown"
                                                            onclick="event.stopPropagation()"><i
                                                                class="bi bi-three-dots-vertical"></i></button>
                                                        <ul class="dropdown-menu shadow-sm border-0 py-1" style="font-size: 0.9rem;">
                                                            <li><a class="dropdown-item py-2" href="#"
                                                                    onclick="toggleStar(<?= $doc['id'] ?>)"><i
                                                                        class="bi bi-star text-secondary me-2"></i>
                                                                    <?= $doc['is_starred'] == 1 ? 'Quitar Destacado' : 'Destacar' ?></a>
                                                            </li>
                                                            <li><a class="dropdown-item py-2" href="#"
                                                                    onclick="openMoveModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>')"><i
                                                                        class="bi bi-box-arrow-in-right text-secondary me-2"></i>
                                                                    Mover</a></li>
                                                            <li><a class="dropdown-item py-2" href="#"
                                                                    onclick="openRenameModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>')"><i
                                                                        class="bi bi-pencil text-secondary me-2"></i> Renombrar</a></li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item py-2 text-danger" href="#"
                                                                    onclick="deleteDocument(<?= $doc['id'] ?>)"><i
                                                                        class="bi bi-trash text-danger me-2"></i> Eliminar</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($files)): ?>
                        <div class="mb-4">
                            <h6 class="text-uppercase text-secondary small fw-bold mb-3 mt-4" style="letter-spacing: 0.5px;">
                                ARCHIVOS</h6>
                            <div class="bg-white rounded-3 border" style="overflow: hidden;">
                                <table class="docs-list-table">
                                    <thead>
                                        <tr>
                                            <th style="width:30%;">Nombre <i class="bi bi-arrow-up"></i></th>
                                            <th>Categoría <i class="bi bi-arrow-down-up"></i></th>
                                            <th>Permisos <i class="bi bi-arrow-down-up"></i></th>
                                            <th>Tamaño <i class="bi bi-arrow-down-up"></i></th>
                                            <th>Fecha <i class="bi bi-arrow-down-up"></i></th>
                                            <th style="width:70px;">Nuevo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($files as $doc): ?>
                                            <?php
                                            $name = trim((string) ($doc['name'] ?? 'Doc'));
                                            $size = number_format(($doc['size_bytes'] ?? 0) / 1024, 1) . ' KB';
                                            $badgeCls = 'badge-todos';
                                            $badgeIcon = 'bi-people-fill';
                                            $badgeLabel = 'Todos';
                                            if ($doc['access_level'] === 'Solo Admins') {
                                                $badgeCls = 'badge-admin';
                                                $badgeIcon = 'bi-lock-fill';
                                                $badgeLabel = 'Admin';
                                            } elseif ($doc['access_level'] === 'Propietarios') {
                                                $badgeCls = 'badge-prop';
                                                $badgeIcon = 'bi-house-heart-fill';
                                                $badgeLabel = 'Propietarios';
                                            }
                                            ?>
                                            <tr style="cursor:pointer;"
                                                onclick="openDetailModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>', '<?= esc($size) ?>', '<?= esc(addslashes($doc['category'])) ?>', '<?= esc(addslashes($doc['access_level'])) ?>')">
                                                <td>
                                                    <div class="list-file-name">
                                                        <i class="bi bi-file-earmark-pdf-fill text-danger fs-5"></i>
                                                        <div>
                                                            <div class="name-text">
                                                                <?= esc($name) ?>
                                                                <?php if ($doc['is_starred'] == 1): ?><i
                                                                        class="bi bi-star-fill text-warning ms-1"
                                                                        style="font-size:0.75rem;"></i><?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-muted"><?= esc($doc['category'] ?? 'General') ?></td>
                                                <td><span class="list-badge <?= $badgeCls ?>"><i class="bi <?= $badgeIcon ?>"></i>
                                                        <?= $badgeLabel ?></span></td>
                                                <td class="text-muted"><?= $size ?></td>
                                                <td class="text-muted"><?= date('M j, Y', strtotime($doc['created_at'])) ?></td>
                                                <td class="text-end" onclick="event.stopPropagation()">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm text-secondary border-0" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots-vertical"></i></button>
                                                        <ul class="dropdown-menu shadow-sm border-0 py-1" style="font-size: 0.9rem;">
                                                            <li><a class="dropdown-item py-2" href="#"
                                                                    onclick="openDetailModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>', '<?= esc($size) ?>', '<?= esc(addslashes($doc['category'])) ?>', '<?= esc(addslashes($doc['access_level'])) ?>')"><i
                                                                        class="bi bi-eye text-secondary me-2"></i> Vista Previa</a></li>
                                                            <li><a class="dropdown-item py-2" href="#"
                                                                    onclick="openShareModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>', '<?= esc(addslashes($doc['category'])) ?>', '<?= esc(addslashes($doc['access_level'])) ?>')"><i
                                                                        class="bi bi-share text-secondary me-2"></i> Compartir</a></li>
                                                            <li><a class="dropdown-item py-2"
                                                                    href="<?= base_url('admin/documentos/download/' . $doc['id']) ?>"><i
                                                                        class="bi bi-download text-secondary me-2"></i> Descargar</a>
                                                            </li>
                                                            <li><a class="dropdown-item py-2" href="#"
                                                                    onclick="toggleStar(<?= $doc['id'] ?>)"><i
                                                                        class="bi bi-star text-secondary me-2"></i>
                                                                    <?= $doc['is_starred'] == 1 ? 'Quitar Destacado' : 'Destacar' ?></a>
                                                            </li>
                                                            <li><a class="dropdown-item py-2" href="#"
                                                                    onclick="openMoveModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>')"><i
                                                                        class="bi bi-box-arrow-in-right text-secondary me-2"></i>
                                                                    Mover</a></li>
                                                            <li><a class="dropdown-item py-2" href="#"
                                                                    onclick="openRenameModal(<?= $doc['id'] ?>, '<?= esc(addslashes($name)) ?>')"><i
                                                                        class="bi bi-pencil text-secondary me-2"></i> Renombrar</a></li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item py-2 text-danger" href="#"
                                                                    onclick="deleteDocument(<?= $doc['id'] ?>)"><i
                                                                        class="bi bi-trash text-danger me-2"></i> Eliminar</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </section>
</div>

<!-- Upload Wizard Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header border-0 pb-1">
                <h5 class="modal-title fw-bold text-dark" id="uploadModalTitle">Subir Documentos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4 position-relative">
                <p class="text-secondary small mb-3" id="uploadModalDesc">Seleccione hasta 10 archivos para subir</p>

                <!-- Step 1: Dropzone -->
                <div id="step-1-dropzone">
                    <div class="docs-dropzone" id="file-dropzone"
                        style="border: 2px dashed #94a3b8; border-radius: 8px; padding: 60px 20px; text-align: center; cursor: pointer; transition: all 0.2s;">
                        <i class="bi bi-upload text-secondary" style="font-size: 2.5rem;"></i>
                        <h6 class="mt-3 mb-1 text-dark fw-bold">Haga clic para subir o arrastre y suelte</h6>
                        <p class="text-muted small mb-0">PDF, Images, Word, Excel (max 50MB each)</p>
                        <input type="file" id="hidden-file-input" multiple style="display:none;" accept="*/*">
                    </div>
                </div>

                <!-- Step 2: Configure files -->
                <div id="step-2-config" class="d-none">
                    <!-- Apply to all top bar -->
                    <div class="bg-light p-3 rounded mb-3 d-flex align-items-center justify-content-between gap-3">
                        <div class="text-secondary fw-semibold small d-flex align-items-center gap-1 text-nowrap"><i
                                class="bi bi-sliders"></i> Aplicar a todos:</div>
                        <div class="flex-grow-1">
                            <select class="form-select form-select-sm" id="bulk-category" onchange="applyBulk()">
                                <option value="">Categoría...</option>
                                <option value="Financiero">Financiero</option>
                                <option value="Legal y Contratos">Legal y Contratos</option>
                                <option value="Reglas y Regulaciones">Reglas y Regulaciones</option>
                                <option value="Recibos">Recibos</option>
                                <option value="Mantenimiento">Mantenimiento</option>
                                <option value="Actas de Reuniones">Actas de Reuniones</option>
                                <option value="General">General</option>
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <select class="form-select form-select-sm" id="bulk-access" onchange="applyBulk()">
                                <option value="">Acceso...</option>
                                <option value="Solo Admins">Solo Admins</option>
                                <option value="Propietarios">Admins y Propietarios</option>
                                <option value="Todos">Todos</option>
                            </select>
                        </div>
                    </div>
                    <!-- Dynamic list of items -->
                    <div id="config-files-container"
                        style="max-height: 400px; overflow-y: auto; overflow-x: hidden; padding-right: 5px;"></div>
                </div>

                <!-- Step 3: Progress/Success -->
                <div id="step-3-progress" class="d-none py-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold small text-dark" id="progress-text">Subiendo archivos</span>
                        <span class="fw-bold small text-dark" id="progress-ratio">0 / 0 completado</span>
                    </div>
                    <div class="progress mb-3" style="height: 6px; border-radius: 6px;">
                        <div class="progress-bar bg-primary" id="progress-bar-fill" role="progressbar"
                            style="width: 0%"></div>
                    </div>

                    <!-- Success Banner -->
                    <div id="success-alert" class="d-none">
                        <div class="border rounded p-3 bg-white mt-4 koti-card-green">
                            <div class="d-flex align-items-center gap-2 text-success fw-semibold">
                                <i class="bi bi-check-lg"></i> ¡Todos los archivos se subieron exitosamente!
                            </div>
                        </div>
                    </div>

                    <!-- Log list of files for UI -->
                    <div id="progress-log" class="d-flex flex-column gap-2 mt-3"
                        style="max-height: 250px; overflow-y: auto;"></div>
                </div>

            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4 d-flex justify-content-between" id="modal-footer-actions">
                <div>
                    <button type="button" class="btn btn-outline-secondary d-none border-0" id="btn-back"><i
                            class="bi bi-arrow-left"></i> Volver</button>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light border bg-white" data-bs-dismiss="modal"
                        id="btn-cancel">Cancelar</button>
                    <button type="button" class="btn btn-primary d-none fw-semibold shadow-sm" id="btn-upload-submit"
                        style="background:#3b4b63; border-color:#3b4b63;">Subir Archivos</button>
                    <button type="button" class="btn fw-semibold shadow-sm text-white d-none" id="btn-done"
                        style="background:#3b4b63; border-color:#3b4b63;"><i class="bi bi-check-lg"></i> Listo</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Vista Previa de Archivo -->
<div class="modal fade" id="fileDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pb-4 text-center">
                <i class="bi bi-file-earmark-pdf-fill fs-1 text-danger mb-2 d-inline-block"></i>
                <h6 class="fw-bold text-dark text-truncate mb-1" id="detailModalName">Documento</h6>
                <p class="text-muted small mb-3" id="detailModalMeta">Size • Category</p>
                <div class="d-flex flex-column gap-2">
                    <a href="#" id="detailModalDownloadBtn" class="btn btn-primary fw-semibold shadow-sm w-100"
                        style="background:#3b4b63; border-color:#3b4b63;"><i class="bi bi-download me-1"></i>
                        Descargar</a>
                    <button type="button" class="btn btn-light border bg-white w-100 fw-semibold"
                        id="detailModalShareBtn"><i class="bi bi-share me-1"></i> Compartir</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Compartir -->
<style>
    .share-access-option {
        border: 1px solid #d0d8e2;
        border-radius: 0.5rem;
        padding: 0.9rem;
        margin-bottom: 0.6rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: flex-start;
        gap: 0.8rem;
    }

    .share-access-option:hover {
        background: #f8fafc;
        border-color: #cbd7e5;
    }

    .share-access-option input[type="radio"] {
        margin-top: 0.25rem;
    }

    .share-access-option.selected {
        border-color: #3b4b63;
        background: #f4f6f8;
    }
</style>
<div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold text-dark">Compartir Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-0">
                <p class="text-secondary small mb-3">Controlar quién puede acceder a este documento</p>

                <div class="bg-light rounded p-3 mb-4 d-flex align-items-center gap-3">
                    <div class="bg-white rounded d-inline-flex align-items-center justify-content-center border"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-file-earmark-text is-2 text-dark"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="fw-bold text-dark text-truncate" id="shareFileName" style="font-size: 0.95rem;">-
                        </div>
                        <div class="text-muted small" id="shareFileCat">-</div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-1" style="font-size:0.95rem;">Acceso de la Comunidad</h6>
                <p class="text-muted small mb-3">Elija el nivel de acceso principal (jerárquico)</p>

                <label class="share-access-option" id="share-opt-admin">
                    <input class="form-check-input" type="radio" name="shareAccessLevel" value="Solo Admins"
                        onchange="updateShareSelection()">
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark" style="font-size:0.9rem;"><i class="bi bi-lock me-1"></i>
                            Solo Admins <span
                                class="badge bg-light text-secondary border float-end fw-normal">Predeterminado</span>
                        </div>
                        <div class="text-secondary" style="font-size:0.75rem;">Solo los administradores pueden acceder
                        </div>
                    </div>
                </label>

                <label class="share-access-option" id="share-opt-propietarios">
                    <input class="form-check-input" type="radio" name="shareAccessLevel" value="Propietarios"
                        onchange="updateShareSelection()">
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark" style="font-size:0.9rem;"><i class="bi bi-house me-1"></i>
                            Admins y Propietarios</div>
                        <div class="text-secondary" style="font-size:0.75rem;">Administradores y propietarios</div>
                    </div>
                </label>

                <label class="share-access-option" id="share-opt-todos">
                    <input class="form-check-input" type="radio" name="shareAccessLevel" value="Todos"
                        onchange="updateShareSelection()">
                    <div class="flex-grow-1">
                        <div class="fw-semibold text-dark" style="font-size:0.9rem;"><i class="bi bi-people me-1"></i>
                            Todos</div>
                        <div class="text-secondary" style="font-size:0.75rem;">Todos los residentes incluyendo
                            inquilinos</div>
                    </div>
                </label>

                <div class="alert mt-3" style="background:#f0f7fe; border: 1px solid #cbe0f8; color:#0f5298;">
                    <div class="d-flex gap-2">
                        <i class="bi bi-info-circle mt-1"></i>
                        <div>
                            <div class="fw-bold" style="font-size:0.85rem;">Los Administradores Siempre Tienen Acceso
                            </div>
                            <div style="font-size:0.75rem;">Los administradores siempre pueden acceder a todos los
                                documentos independientemente de la configuración de compartir.</div>
                        </div>
                    </div>
                </div>
                <!-- Hidden DOC ID for saving -->
                <input type="hidden" id="shareDocId" value="">
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light border bg-white" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary fw-semibold shadow-sm"
                    style="background:#3b4b63; border-color:#3b4b63;" onclick="saveShareAccess()">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Mover -->
<div class="modal fade" id="moveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold text-dark"><i class="bi bi-box-arrow-in-right me-2"></i> Mover a</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-0">
                <p class="text-secondary small mb-3">Elija un destino para "<span id="moveElementNameTitle"></span>"</p>

                <h6 class="fw-bold mb-2 text-secondary" style="font-size:0.8rem;">Elementos a mover</h6>
                <div class="bg-light rounded p-2 mb-4 d-flex align-items-center gap-2 border">
                    <i class="bi bi-file-earmark text-secondary"></i>
                    <span class="text-dark" id="moveElementNameBox" style="font-size:0.95rem;">-</span>
                </div>

                <div class="border rounded p-2" style="max-height: 250px; overflow-y: auto;">
                    <div class="list-group list-group-flush" id="folderListGroup">
                        <!-- Carga dinámica -->
                        <div class="text-center text-muted p-2">
                            <div class="spinner-border spinner-border-sm" role="status"></div>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="moveDocId" value="">
                <input type="hidden" id="moveSelectedParentId" value="">
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light border bg-white" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary fw-semibold shadow-sm text-white"
                    style="background:#94a3b8; border-color:#94a3b8;" id="btnMoveSubmit" onclick="performMove()"
                    disabled>Mover aquí</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Renombrar -->
<div class="modal fade" id="renameModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius:12px;">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold text-dark">Renombrar Archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 pt-0">
                <p class="text-secondary small mb-3">Ingrese un nuevo nombre para este elemento</p>

                <div class="bg-light rounded p-3 mb-4 d-flex align-items-center gap-3">
                    <div class="bg-white rounded d-inline-flex align-items-center justify-content-center border"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-file-text fs-4 text-dark"></i>
                    </div>
                    <div class="overflow-hidden">
                        <div class="text-secondary" style="font-size:0.75rem;">Nombre Actual</div>
                        <div class="fw-bold text-dark text-truncate" id="renameCurrentName" style="font-size: 0.95rem;">
                            -</div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="fw-bold text-dark mb-1 d-block" style="font-size:0.95rem;">Nuevo Nombre</label>
                    <input type="text" class="form-control" id="renameNewName" placeholder="Escriba el nuevo nombre">
                </div>
                <!-- Hidden DOC ID for saving -->
                <input type="hidden" id="renameDocId" value="">
            </div>
            <div class="modal-footer border-0 pt-0 px-4 pb-4">
                <button type="button" class="btn btn-light border bg-white" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary fw-semibold shadow-sm"
                    style="background:#3b4b63; border-color:#3b4b63;" onclick="performRename()">Renombrar</button>
            </div>
        </div>
    </div>
</div>

<!-- TEMPLATE PARA ARCHIVOS -->
<template id="tpl-file-config">
    <div class="file-config-item shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2 overflow-hidden w-100">
                <i class="bi bi-file-earmark-text fs-4 text-secondary"></i>
                <div class="overflow-hidden w-100">
                    <div class="fw-semibold text-dark text-truncate" style="font-size:0.9rem;" data-name-label></div>
                    <div class="text-muted" style="font-size:0.75rem;" data-size-label></div>
                </div>
            </div>
            <button class="btn btn-sm text-secondary border-0 remove-file-btn ms-2"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="mb-3">
            <label class="form-label small text-muted fw-semibold">Nombre del Documento</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control item-name" value="">
                <span class="input-group-text bg-light item-ext"></span>
            </div>
        </div>
        <div class="row g-2">
            <div class="col-6">
                <label class="form-label small text-muted fw-semibold text-nowrap">Seleccionar Categoría</label>
                <select class="form-select form-select-sm item-cat">
                    <option value="Financiero">Financiero</option>
                    <option value="Legal y Contratos">Legal y Contratos</option>
                    <option value="Reglas y Regulaciones">Reglas y Regulaciones</option>
                    <option value="Recibos">Recibos</option>
                    <option value="Mantenimiento">Mantenimiento</option>
                    <option value="Actas de Reuniones">Actas de Reuniones</option>
                    <option value="General" selected>General</option>
                </select>
            </div>
            <div class="col-6">
                <label class="form-label small text-muted fw-semibold">Acceso</label>
                <select class="form-select form-select-sm item-acc">
                    <option value="Solo Admins">Solo Admins</option>
                    <option value="Propietarios">Admins y Propietarios</option>
                    <option value="Todos" selected>Todos</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script>
    // Search filter logic
    document.addEventListener('DOMContentLoaded', function () {
        var input = document.getElementById('docs-search');
        var items = Array.from(document.querySelectorAll('.docs-item'));
        if (input && items.length > 0) {
            input.addEventListener('input', function () {
                var term = (input.value || '').trim().toLowerCase();
                items.forEach(function (item) {
                    var haystack = (item.dataset.search || '').toLowerCase();
                    item.style.display = term === '' || haystack.indexOf(term) !== -1 ? '' : 'none';
                });
            });
        }
    });

    // Create Folder
    function createFolderPrompt() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Nueva Carpeta',
                input: 'text',
                inputPlaceholder: 'Nombre de la carpeta',
                showCancelButton: true,
                confirmButtonText: 'Crear',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value || !value.trim()) return 'El nombre no puede estar vacío';
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const fd = new FormData();
                    fd.append('name', result.value);
                    fetch('<?= base_url("admin/documentos/folder") ?>', {
                        method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    }).then(r => r.json()).then(res => {
                        if (res.status === 201) {
                            window.location.reload();
                        } else {
                            if (typeof Swal !== 'undefined') {
                                Swal.fire('Error', res.error || res.message || 'Error desconocido', 'error');
                            } else {
                                alert('Error: ' + (res.error || res.message || 'Desconocido'));
                            }
                        }
                    }).catch(e => {
                        alert('Error de conexión al crear carpeta');
                        console.error(e);
                    });
                }
            });
        }
    }

    // Modal Wizard Logic
    let selectedFiles = [];
    let currentStep = 1;

    const modalEl = document.getElementById('uploadModal');
    let bsModal = null;

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof bootstrap !== 'undefined' && modalEl) {
            bsModal = new bootstrap.Modal(modalEl);
        }
    });

    const dropzone = document.getElementById('file-dropzone');
    const fileInput = document.getElementById('hidden-file-input');
    const step1 = document.getElementById('step-1-dropzone');
    const step2 = document.getElementById('step-2-config');
    const step3 = document.getElementById('step-3-progress');
    const btnBack = document.getElementById('btn-back');
    const btnCancel = document.getElementById('btn-cancel');
    const btnSubmit = document.getElementById('btn-upload-submit');
    const btnDone = document.getElementById('btn-done');
    const configContainer = document.getElementById('config-files-container');
    const tplFile = document.getElementById('tpl-file-config');

    function openUploadModal() {
        selectedFiles = [];
        resetModalState();
        bsModal.show();
    }

    function resetModalState() {
        currentStep = 1;
        step1.classList.remove('d-none');
        step2.classList.add('d-none');
        step3.classList.add('d-none');
        btnBack.classList.add('d-none');
        btnCancel.classList.remove('d-none');
        btnSubmit.classList.add('d-none');
        btnDone.classList.add('d-none');
        document.getElementById('uploadModalTitle').textContent = 'Subir Documentos';
        document.getElementById('uploadModalDesc').textContent = 'Seleccione hasta 10 archivos para subir';
        fileInput.value = '';
    }

    // Dropzone Events
    dropzone.addEventListener('click', () => fileInput.click());
    dropzone.addEventListener('dragover', (e) => { e.preventDefault(); dropzone.classList.add('dragover'); });
    dropzone.addEventListener('dragleave', () => dropzone.classList.remove('dragover'));
    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });
    fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

    function handleFiles(files) {
        if (files.length === 0) return;
        const limit = Math.min(files.length, 10);
        for (let i = 0; i < limit; i++) {
            selectedFiles.push(files[i]);
        }
        buildConfigStep();
    }

    // Step 2 logic
    function buildConfigStep() {
        if (selectedFiles.length === 0) {
            resetModalState();
            return;
        }

        currentStep = 2;
        document.getElementById('uploadModalTitle').textContent = 'Configurar Archivos';
        document.getElementById('uploadModalDesc').textContent = 'Establecer metadatos para cada archivo';
        step1.classList.add('d-none');
        step2.classList.remove('d-none');

        btnBack.classList.remove('d-none');
        btnSubmit.classList.remove('d-none');
        btnSubmit.textContent = `Subir ${selectedFiles.length} Archivos`;

        configContainer.innerHTML = '';
        selectedFiles.forEach((f, idx) => {
            const clone = tplFile.content.cloneNode(true);
            const parent = clone.querySelector('.file-config-item');
            parent.dataset.index = idx;

            // Extracción
            const parts = f.name.split('.');
            const ext = parts.length > 1 ? '.' + parts.pop() : '';
            const baseName = parts.join('.');

            clone.querySelector('[data-name-label]').textContent = f.name;
            clone.querySelector('[data-size-label]').textContent = (f.size / 1024).toFixed(2) + ' KB';
            clone.querySelector('.item-name').value = baseName;
            clone.querySelector('.item-ext').textContent = ext;

            clone.querySelector('.remove-file-btn').addEventListener('click', (e) => {
                const elIdx = parseInt(e.target.closest('.file-config-item').dataset.index);
                selectedFiles.splice(elIdx, 1);
                buildConfigStep(); // redraw
            });

            configContainer.appendChild(clone);
        });
    }

    btnBack.addEventListener('click', resetModalState);

    function applyBulk() {
        const cat = document.getElementById('bulk-category').value;
        const acc = document.getElementById('bulk-access').value;

        const items = configContainer.querySelectorAll('.file-config-item');
        items.forEach(it => {
            if (cat) it.querySelector('.item-cat').value = cat;
            if (acc) it.querySelector('.item-acc').value = acc;
        });
    }

    // Submit Logic
    btnSubmit.addEventListener('click', async () => {
        if (selectedFiles.length === 0) return;

        // Prepare data
        const configs = [];
        const items = configContainer.querySelectorAll('.file-config-item');
        items.forEach((it, idx) => {
            const baseName = it.querySelector('.item-name').value.trim();
            const ext = it.querySelector('.item-ext').textContent;
            configs.push({
                name: baseName + ext,
                category: it.querySelector('.item-cat').value,
                access: it.querySelector('.item-acc').value
            });
        });

        // Switch to Step 3
        currentStep = 3;
        step2.classList.add('d-none');
        step3.classList.remove('d-none');
        btnBack.classList.add('d-none');
        btnCancel.classList.add('d-none');
        btnSubmit.classList.add('d-none');

        document.getElementById('uploadModalTitle').textContent = 'Subiendo...';
        document.getElementById('uploadModalDesc').textContent = 'Por favor espere mientras se suben sus archivos';

        const barFill = document.getElementById('progress-bar-fill');
        const ratioText = document.getElementById('progress-ratio');
        const logBox = document.getElementById('progress-log');
        logBox.innerHTML = '';

        // Render initial UI logs
        selectedFiles.forEach(f => {
            logBox.innerHTML += `
               <div class="border rounded p-2 px-3 bg-light d-flex justify-content-between align-items-center">
                   <div class="d-flex align-items-center gap-2">
                       <i class="bi bi-file-earmark text-secondary"></i>
                       <span class="small fw-semibold text-dark">${f.name}</span>
                   </div>
                   <i class="bi bi-hourglass text-muted"></i>
               </div>`;
        });

        const fd = new FormData();
        selectedFiles.forEach(f => fd.append('files[]', f));
        fd.append('configs', JSON.stringify(configs));

        // Simulating upload progress bar for UX Premium feel
        let perc = 0;
        const inter = setInterval(() => {
            perc += 15;
            if (perc > 90) clearInterval(inter);
            barFill.style.width = Math.min(perc, 90) + '%';
            ratioText.textContent = `Subiendo...`;
        }, 150);

        try {
            const res = await fetch('<?= base_url("admin/documentos/upload") ?>', {
                method: 'POST',
                body: fd,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            clearInterval(inter);

            if (data.status === 201) {
                barFill.style.width = '100%';
                barFill.classList.remove('bg-primary');
                barFill.classList.add('bg-success');
                ratioText.textContent = `${selectedFiles.length} / ${selectedFiles.length} completado`;

                // Mark logs as checked
                const logItems = logBox.querySelectorAll('div > i.bi-hourglass');
                logItems.forEach(icon => {
                    icon.className = 'bi bi-check-lg text-success';
                    icon.parentElement.classList.remove('bg-light');
                    icon.parentElement.style.background = '#f0fdf4';
                    icon.parentElement.style.borderColor = '#bbf7d0';
                });

                document.getElementById('success-alert').classList.remove('d-none');

                btnDone.classList.remove('d-none');
                // Show close button
                btnCancel.classList.remove('d-none');
                btnCancel.textContent = 'Cerrar';
            } else {
                alert('Error al subir: ' + (data.error || data.message || 'Revisa la consola'));
                console.error(data);
                btnCancel.classList.remove('d-none');
            }

        } catch (e) {
            clearInterval(inter);
            alert('Hubo un error de conexión');
            console.error(e);
            btnCancel.classList.remove('d-none');
        }
    });

    btnDone.addEventListener('click', () => {
        window.location.reload();
    });

    // Detail Modal Logic
    let currentDetailModal = null;
    let currentShareModal = null;

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof bootstrap !== 'undefined') {
            const fdModalEl = document.getElementById('fileDetailModal');
            if (fdModalEl) currentDetailModal = new bootstrap.Modal(fdModalEl);

            const fsModalEl = document.getElementById('shareModal');
            if (fsModalEl) currentShareModal = new bootstrap.Modal(fsModalEl);
        }
    });

    function openDetailModal(id, name, size, category, accessLevel) {
        document.getElementById('detailModalName').textContent = name;
        document.getElementById('detailModalMeta').textContent = size + ' • ' + category;
        document.getElementById('detailModalDownloadBtn').href = '<?= base_url("admin/documentos/download/") ?>' + id;

        // Track view
        fetch(`<?= base_url("admin/documentos/track-view/") ?>${id}`, { method: 'POST' });

        const shareBtn = document.getElementById('detailModalShareBtn');
        shareBtn.onclick = () => {
            currentDetailModal.hide();
            setTimeout(() => {
                openShareModal(id, name, category, accessLevel);
            }, 300); // Wait for fade transition
        };

        currentDetailModal.show();
    }

    // Share Modal Logic
    function openShareModal(id, name, category, accessLevel) {
        document.getElementById('shareDocId').value = id;
        document.getElementById('shareFileName').textContent = name;
        document.getElementById('shareFileCat').textContent = category;

        // Check corresponding radio
        let valToCheck = accessLevel;
        // Make sure exact strings match or fallback
        const radio = document.querySelector(`input[name="shareAccessLevel"][value="${valToCheck}"]`);
        if (radio) {
            radio.checked = true;
        } else {
            // Default 
            document.querySelector(`input[name="shareAccessLevel"][value="Solo Admins"]`).checked = true;
        }
        updateShareSelection();

        currentShareModal.show();
    }

    function updateShareSelection() {
        document.querySelectorAll('.share-access-option').forEach(el => el.classList.remove('selected'));
        const checked = document.querySelector('input[name="shareAccessLevel"]:checked');
        if (checked) {
            checked.closest('.share-access-option').classList.add('selected');
        }
    }

    async function saveShareAccess() {
        const id = document.getElementById('shareDocId').value;
        const access = document.querySelector('input[name="shareAccessLevel"]:checked').value;
        const fd = new FormData();
        fd.append('access_level', access);

        try {
            const res = await fetch(`<?= base_url("admin/documentos/update-access/") ?>${id}`, {
                method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            if (data.status === 200) {
                currentShareModal.hide();
                if (typeof Toastify !== 'undefined') {
                    Toastify({
                        text: 'Nivel de acceso actualizado con éxito',
                        duration: 3000, gravity: 'bottom', position: 'center', backgroundColor: '#22c55e'
                    }).showToast();
                    setTimeout(() => window.location.reload(), 1000);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Nivel de acceso actualizado',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        background: '#fff',
                        color: '#1e293b'
                    }).then(() => window.location.reload());
                } else {
                    alert('Nivel de acceso actualizado');
                    window.location.reload();
                }
            } else {
                alert('No se pudo guardar: ' + (data.error || data.message));
            }
        } catch (e) {
            alert('Error de conexión');
            console.error(e);
        }
    }

    // Modal Variables for Actions
    let moveModal = null;
    let renameModal = null;

    document.addEventListener('DOMContentLoaded', () => {
        if (typeof bootstrap !== 'undefined') {
            const mvEl = document.getElementById('moveModal');
            if (mvEl) moveModal = new bootstrap.Modal(mvEl);

            const rnEl = document.getElementById('renameModal');
            if (rnEl) renameModal = new bootstrap.Modal(rnEl);
        }
    });

    // Star Toggle
    function toggleStar(id) {
        fetch(`<?= base_url("admin/documentos/toggle-star/") ?>${id}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.status === 200) {
                    window.location.reload();
                }
            });
    }

    // Move Logic
    function openMoveModal(id, name) {
        document.getElementById('moveDocId').value = id;
        document.getElementById('moveElementNameTitle').textContent = name;
        document.getElementById('moveElementNameBox').textContent = name;
        document.getElementById('btnMoveSubmit').disabled = true;
        document.getElementById('moveSelectedParentId').value = '';

        moveModal.show();

        // Cargar carpetas
        const listGroup = document.getElementById('folderListGroup');
        listGroup.innerHTML = '<div class="text-center text-muted p-2"><div class="spinner-border spinner-border-sm" role="status"></div></div>';

        fetch(`<?= base_url("admin/documentos/api/folders") ?>`)
            .then(r => r.json())
            .then(data => {
                if (data.status === 200 && data.folders) {
                    let html = `<a href="#" class="list-group-item list-group-item-action folder-move-item" data-id="root" onclick="selectMoveFolder(this, 'root')"><i class="bi bi-house me-2"></i> Documentos (raíz)</a>`;
                    data.folders.forEach(f => {
                        // Evitar que una carpeta se mueva a si misma
                        if (f.id != id) {
                            html += `<a href="#" class="list-group-item list-group-item-action folder-move-item border-0" data-id="${f.id}" onclick="selectMoveFolder(this, ${f.id})"><i class="bi bi-folder text-secondary me-2"></i> ${f.name}</a>`;
                        }
                    });
                    listGroup.innerHTML = html;
                }
            });
    }

    function selectMoveFolder(element, id) {
        document.querySelectorAll('.folder-move-item').forEach(el => el.classList.remove('bg-light', 'fw-bold'));
        element.classList.add('bg-light', 'fw-bold');
        document.getElementById('moveSelectedParentId').value = id;

        const btnSubmit = document.getElementById('btnMoveSubmit');
        btnSubmit.disabled = false;
        btnSubmit.style.background = '#3b4b63';
        btnSubmit.style.borderColor = '#3b4b63';
    }

    function performMove() {
        const docId = document.getElementById('moveDocId').value;
        const parentId = document.getElementById('moveSelectedParentId').value;

        const fd = new FormData();
        fd.append('parent_id', parentId);

        fetch(`<?= base_url("admin/documentos/move/") ?>${docId}`, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.status === 200) {
                    moveModal.hide();
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Elemento movido', showConfirmButton: false, timer: 1500, timerProgressBar: true });
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    alert(data.error);
                }
            });
    }

    // Rename Logic
    function openRenameModal(id, name) {
        document.getElementById('renameDocId').value = id;
        document.getElementById('renameCurrentName').textContent = name;
        document.getElementById('renameNewName').value = name;
        renameModal.show();
    }

    function performRename() {
        const docId = document.getElementById('renameDocId').value;
        const newName = document.getElementById('renameNewName').value;
        if (!newName.trim()) return;

        const fd = new FormData();
        fd.append('name', newName);

        fetch(`<?= base_url("admin/documentos/rename/") ?>${docId}`, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(data => {
                if (data.status === 200) {
                    renameModal.hide();
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Renombrado con éxito', showConfirmButton: false, timer: 1500, timerProgressBar: true });
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    alert(data.error);
                }
            });
    }

    // Delete Logic
    function deleteDocument(id) {
        Swal.fire({
            title: 'Eliminar Elemento',
            text: '¿Está seguro de que desea eliminar este elemento?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#fff',
            confirmButtonText: 'Eliminar',
            cancelButtonText: '<span class="text-dark">Cancelar</span>',
            customClass: {
                confirmButton: 'shadow-sm',
                cancelButton: 'border text-dark'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`<?= base_url("admin/documentos/delete/") ?>${id}`, { method: 'POST' })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 200) {
                            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Elemento eliminado', showConfirmButton: false, timer: 1500, timerProgressBar: true });
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            alert(data.error);
                        }
                    });
            }
        });
    }

    // ======================== VIEW TOGGLE ========================
    function switchView(mode) {
        const gridView = document.getElementById('docs-grid-view');
        const listView = document.getElementById('docs-list-view');
        const btnGrid = document.getElementById('btn-grid-view');
        const btnList = document.getElementById('btn-list-view');
        if (!gridView || !listView) return;

        if (mode === 'list') {
            gridView.classList.add('d-none');
            listView.classList.remove('d-none');
            btnGrid.classList.remove('active');
            btnList.classList.add('active');
        } else {
            listView.classList.add('d-none');
            gridView.classList.remove('d-none');
            btnList.classList.remove('active');
            btnGrid.classList.add('active');
        }
        try { localStorage.setItem('docs_view_mode', mode); } catch (e) { }
    }

    // Restore saved view mode
    document.addEventListener('DOMContentLoaded', () => {
        try {
            const saved = localStorage.getItem('docs_view_mode');
            if (saved === 'list') switchView('list');
        } catch (e) { }
    });

    // ======================== ANALYTICS CHARTS ========================
    <?php if (isset($analyticsView) && $analyticsView): ?>
            (function () {
                const chartScript = document.createElement('script');
                chartScript.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.6/dist/chart.umd.min.js';
                chartScript.onload = function () {
                    initAnalyticsCharts();
                };
                document.head.appendChild(chartScript);
            })();

        function initAnalyticsCharts() {
            const catColors = ['#e07a5f', '#3d405b', '#81b29a', '#f2cc8f', '#6d6875', '#e5989b', '#b5838d'];

            // Category distribution data from PHP
            const catLabels = <?= json_encode(array_map(fn($c) => $c['category'] ?? 'General', $analytics['categories'])) ?>;
            const catCounts = <?= json_encode(array_map(fn($c) => (int) $c['cnt'], $analytics['categories'])) ?>;
            const catSizes = <?= json_encode(array_map(fn($c) => round(($c['total_size'] ?? 0) / 1024, 1), $analytics['categories'])) ?>;

            // Donut Chart
            const donutCtx = document.getElementById('categoryDonutChart');
            if (donutCtx) {
                new Chart(donutCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: catLabels,
                        datasets: [{
                            data: catCounts,
                            backgroundColor: catColors.slice(0, catLabels.length),
                            borderWidth: 0,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        cutout: '65%',
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            }

            // Bar Chart
            const barCtx = document.getElementById('categoryBarChart');
            if (barCtx) {
                new Chart(barCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: catLabels,
                        datasets: [{
                            label: 'KB',
                            data: catSizes,
                            backgroundColor: catColors.slice(0, catLabels.length).map(c => c + 'CC'),
                            borderRadius: 6,
                            maxBarThickness: 60
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => ctx.parsed.y + ' KB'
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { callback: v => v + ' KB', font: { size: 11 } },
                                grid: { color: '#f0f0f0' }
                            },
                            x: {
                                ticks: { font: { size: 11 } },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Activity Chart
            const actViews = <?= json_encode($analytics['activityViews']) ?>;
            const actDownloads = <?= json_encode($analytics['activityDownloads']) ?>;

            // Merge all dates
            const allDates = [...new Set([...Object.keys(actViews), ...Object.keys(actDownloads)])].sort();
            const viewData = allDates.map(d => actViews[d] || 0);
            const dlData = allDates.map(d => actDownloads[d] || 0);
            const dateLabels = allDates.map(d => {
                const dt = new Date(d + 'T00:00:00');
                return dt.toLocaleDateString('es-MX', { month: 'short', day: 'numeric' });
            });

            const actCtx = document.getElementById('activityChart');
            if (actCtx) {
                new Chart(actCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: dateLabels,
                        datasets: [
                            {
                                label: 'Views',
                                data: viewData,
                                borderColor: '#3d405b',
                                backgroundColor: '#3d405b',
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                tension: 0,
                                fill: false
                            },
                            {
                                label: 'Downloads',
                                data: dlData,
                                borderColor: '#81b29a',
                                backgroundColor: '#81b29a',
                                pointRadius: 6,
                                pointHoverRadius: 8,
                                tension: 0,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: { usePointStyle: true, padding: 20, font: { size: 12 } }
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: { stepSize: 1, font: { size: 11 } },
                                grid: { color: '#f5f5f5' }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 11 } }
                            }
                        }
                    }
                });
            }
        }
    <?php endif; ?>
</script>
<?= $this->endSection() ?>