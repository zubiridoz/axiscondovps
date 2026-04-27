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

    .cc-hero-btndark {
        background: #1C2434;
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

    .cc-hero-btndark:hover {
        background: #3a4864ff;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    /* ── end Hero ── */

    /* ── KPI STAT CARDS ── */
    .am-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .am-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 1.15rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        transition: box-shadow 0.2s;
    }

    .am-stat-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .am-stat-label {
        font-size: 0.78rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.35rem;
    }

    .am-stat-value {
        font-size: 1.65rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }

    .am-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .am-stat-icon.blue {
        background: #eff6ff;
        color: #3b82f6;
    }

    .am-stat-icon.green {
        background: #f0fdf4;
        color: #10b981;
    }

    .am-stat-icon.amber {
        background: #fffbeb;
        color: #f59e0b;
    }

    .am-stat-icon.emerald {
        background: #ecfdf5;
        color: #059669;
    }

    /* ── FILTERS & CONTROLS ── */
    .am-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .am-controls-left {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .am-search-box {
        position: relative;
    }

    .am-search-box i {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .am-search-box input {
        padding: 0.5rem 0.85rem 0.5rem 2.25rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        width: 200px;
        background: #ffffff;
        color: #334155;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .am-search-box input:focus {
        border-color: #cbd5e1;
        box-shadow: 0 0 0 3px rgba(203, 213, 225, 0.2);
    }

    .am-filter-pills {
        display: flex;
        gap: 0.25rem;
        background: #f8fafc;
        padding: 3px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .am-filter-pill {
        padding: 0.35rem 0.85rem;
        border: none;
        background: transparent;
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .am-filter-pill.active,
    .am-filter-pill:hover {
        background: #ffffff;
        color: #1e293b;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
    }

    .btn-new-amenity {
        background: #1e293b;
        color: #ffffff;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-new-amenity:hover {
        background: #334155;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(30, 41, 59, 0.2);
    }

    /* ── AMENITY CARDS GRID ── */
    .am-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
    }

    .am-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.2s, box-shadow 0.2s;
        cursor: pointer;
        position: relative;
    }

    .am-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .am-card-image {
        width: 100%;
        height: 160px;
        object-fit: cover;
        background: #1e293b;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .am-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .am-card-image .am-placeholder-icon {
        font-size: 2.5rem;
        color: rgba(255, 255, 255, 0.3);
    }

    .am-card-body {
        padding: 1rem 1.15rem;
    }

    .am-card-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .am-card-desc {
        font-size: 0.78rem;
        color: #64748b;
        line-height: 1.4;
        margin-bottom: 0.75rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .am-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .am-card-badges {
        display: flex;
        gap: 0.4rem;
    }

    .am-badge {
        padding: 0.2rem 0.55rem;
        border-radius: 1rem;
        font-size: 0.68rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    .am-badge.active {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #dcfce7;
    }

    .am-badge.inactive {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fee2e2;
    }

    .am-badge.reservable {
        background: #eff6ff;
        color: #2563eb;
        border: 1px solid #dbeafe;
    }

    .am-badge.capacity {
        background: #f8fafc;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .am-card-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.8rem;
    }

    /* ── EMPTY STATE ── */
    .am-empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
    }

    .am-empty-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f0fdf4;
        color: #10b981;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin: 0 auto 1.5rem auto;
    }

    .am-empty-title {
        font-size: 1.15rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .am-empty-desc {
        font-size: 0.85rem;
        color: #64748b;
        max-width: 400px;
        margin: 0 auto 1.5rem auto;
        line-height: 1.5;
    }

    /* ── MODAL PREMIUM ── */
    .am-modal .modal-content {
        border: none;
        border-radius: 12px;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    .am-modal .modal-header {
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    .am-modal .modal-body {
        padding: 1.5rem;
    }

    .am-modal .modal-footer {
        border-top: 1px solid #f1f5f9;
        padding: 1rem 1.5rem;
    }

    .am-modal .form-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #334155;
        margin-bottom: 0.4rem;
    }

    .am-modal .form-control,
    .am-modal .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }

    .am-modal .form-control:focus,
    .am-modal .form-select:focus {
        border-color: #94a3b8;
        box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.2);
    }

    .am-image-upload {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafbfc;
    }

    .am-image-upload:hover {
        border-color: #94a3b8;
        background: #f1f5f9;
    }

    .am-image-upload i {
        font-size: 1.75rem;
        color: #94a3b8;
        margin-bottom: 0.5rem;
    }

    .am-image-preview {
        max-width: 100%;
        max-height: 180px;
        border-radius: 8px;
        object-fit: cover;
        margin-top: 0.75rem;
    }

    @media (max-width: 768px) {
        .am-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .am-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">


        <!-- ── Hero ── -->
        <div class="cc-hero">
            <div class="cc-hero-left">
                <h2 class="cc-hero-title">Amenidades</h2>
                <div class="cc-hero-divider"></div>
                <div class="cc-hero-breadcrumb">
                    <i class="bi bi-calendar-check"></i>
                    <i class="bi bi-chevron-right" style="font-size:.65rem;color:#94a3b8"></i>
                    Gestionar instalaciones y espacios comunitarios
                </div>
            </div>
            <div class="cc-hero-right">

                <a href="<?= base_url('admin/amenidades/estadisticas') ?>" class="cc-hero-btndark"
                    style="text-decoration:none;">

                    <i class="bi bi-bar-chart-line me-2"></i> Ver Estadísticas
                </a>
                <a href="<?= base_url('admin/amenidades/nueva') ?>" class="cc-hero-btn" style="text-decoration:none;">
                    <i class="bi bi-plus-lg"></i> Nueva Amenidad
                </a>
            </div>

        </div>



        <!-- KPI CARDS -->
        <div class="am-stats-grid">
            <div class="am-stat-card">
                <div>
                    <div class="am-stat-label">Total de Amenidades</div>
                    <div class="am-stat-value"><?= $totalAmenities ?? 0 ?></div>
                </div>
                <div class="am-stat-icon blue">
                    <i class="bi bi-building"></i>
                </div>
            </div>
            <div class="am-stat-card">
                <div>
                    <div class="am-stat-label">Reservas Este Mes</div>
                    <div class="am-stat-value"><?= $reservationsThisMonth ?? 0 ?></div>
                </div>
                <div class="am-stat-icon green">
                    <i class="bi bi-calendar-event"></i>
                </div>
            </div>
            <div class="am-stat-card">
                <div>
                    <div class="am-stat-label">Aprobaciones Pendientes</div>
                    <div class="am-stat-value"><?= $pendingApprovals ?? 0 ?></div>
                </div>
                <div class="am-stat-icon amber">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="am-stat-card">
                <div>
                    <div class="am-stat-label">Ingresos Generados</div>
                    <div class="am-stat-value">$<?= number_format($revenue ?? 0, 0) ?></div>
                </div>
                <div class="am-stat-icon emerald">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>

        <!-- CONTROLS -->
        <div class="am-controls">
            <div class="am-controls-left">
                <div class="am-search-box">
                    <i class="bi bi-search"></i>
                    <input type="text" id="amenitySearch" placeholder="Buscar amenidades..."
                        oninput="filterAmenities()">
                </div>
                <div class="am-filter-pills">
                    <button class="am-filter-pill active" onclick="setFilter('all', this)">Todas</button>
                    <button class="am-filter-pill" onclick="setFilter('reservable', this)">Reservables</button>
                    <button class="am-filter-pill" onclick="setFilter('no-reservable', this)">No Reservables</button>
                </div>
            </div>

        </div>

        <!-- AMENITIES GRID -->
        <?php if (empty($amenities)): ?>
            <div class="am-empty-state">
                <div class="am-empty-icon">
                    <i class="bi bi-gift"></i>
                </div>
                <div class="am-empty-title">No Hay Amenidades Aún</div>
                <p class="am-empty-desc">
                    Comience creando su primera amenidad para ayudar a los residentes a reservar instalaciones del
                    condominio.
                </p>
                <a href="<?= base_url('admin/amenidades/nueva') ?>" class="btn-new-amenity mx-auto"
                    style="text-decoration:none;">
                    <i class="bi bi-plus-lg"></i> Crear Primera Amenidad
                </a>
            </div>
        <?php else: ?>
            <div class="am-grid" id="amenitiesGrid">
                <?php foreach ($amenities as $a): ?>
                    <div class="am-card" data-name="<?= esc(strtolower($a['name'])) ?>"
                        data-reservable="<?= $a['is_reservable'] ?? 1 ?>"
                        onclick="window.location.href='<?= base_url('admin/amenidades/detalle/' . ($a['hash_id'] ?? '')) ?>'">
                        <div class="am-card-image">
                            <?php if (!empty($a['image'])): ?>
                                <img src="<?= base_url('admin/amenidades/imagen/' . $a['image']) ?>" alt="<?= esc($a['name']) ?>">
                            <?php else: ?>
                                <i class="bi bi-image am-placeholder-icon"></i>
                            <?php endif; ?>
                        </div>
                        <div class="am-card-body">
                            <div class="am-card-name"><?= esc($a['name']) ?></div>
                            <div class="am-card-desc"><?= esc($a['description'] ?? 'Sin descripción') ?></div>
                            <div class="am-card-footer">
                                <div class="am-card-badges">
                                    <?php if ($a['is_active']): ?>
                                        <span class="am-badge active"><i class="bi bi-check-circle"></i> Activa</span>
                                    <?php else: ?>
                                        <span class="am-badge inactive"><i class="bi bi-x-circle"></i> Inactiva</span>
                                    <?php endif; ?>
                                    <?php if ($a['is_reservable'] ?? 1): ?>
                                        <span class="am-badge reservable"><i class="bi bi-calendar-check"></i></span>
                                    <?php endif; ?>
                                    <?php if ($a['capacity']): ?>
                                        <span class="am-badge capacity"><i class="bi bi-people"></i> <?= $a['capacity'] ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('modals') ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    let currentFilter = 'all';

    function setFilter(type, el) {
        currentFilter = type;
        document.querySelectorAll('.am-filter-pill').forEach(p => p.classList.remove('active'));
        el.classList.add('active');
        filterAmenities();
    }

    function filterAmenities() {
        const q = document.getElementById('amenitySearch').value.toLowerCase();
        document.querySelectorAll('.am-card').forEach(card => {
            const name = card.dataset.name;
            const reservable = card.dataset.reservable;

            let matchFilter = true;
            if (currentFilter === 'reservable') matchFilter = reservable === '1';
            if (currentFilter === 'no-reservable') matchFilter = reservable === '0';

            const matchSearch = name.includes(q);
            card.style.display = (matchFilter && matchSearch) ? '' : 'none';
        });
    }
</script>
<?= $this->endSection() ?>