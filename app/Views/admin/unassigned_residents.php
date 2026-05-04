<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?><?= $this->section('styles') ?>
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

    .cc-hero-btndark {
        background: #1D4C9D;
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


    .badge-count-premium {
        background: #1D4C9D;
        color: white;
        padding: 0.2rem 0.6rem;
        border-radius: 2rem;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .premium-main-container {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        overflow: hidden;
    }

    .premium-filter-bar {
        padding: 1rem 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
    }

    .search-input-group {
        position: relative;
    }

    .search-input-group i {
        position: absolute;
        left: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input-group input {
        padding-left: 2.25rem;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        width: 250px;
        font-size: 0.85rem;
    }

    .axis-table th {
        background: #f8fafc;
        color: #94a3b8;
        font-weight: 600;
        font-size: 0.75rem;
        padding: 0.75rem 1.25rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .axis-table td {
        padding: 0.75rem 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
        font-size: 0.85rem;
    }

    .resident-avatar {
        width: 32px;
        height: 32px;
        background: #f1f5f9;
        color: #64748b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        font-size: 0.85rem;
        margin-right: 0.75rem;
    }

    .type-link {
        color: #0ea5e9;
        text-decoration: none;
        background: #f0f9ff;
        padding: 0.2rem 0.6rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }

    .type-link.tenant {
        color: #8b5cf6;
        background: #f3e8ff;
    }

    .btn-assign-premium {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        color: #3F67AC;
        padding: 0.35rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-assign-premium:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #1e293b;
    }

    /* Modal Styling */
    .axis-modal-content {
        border-radius: 12px;
        border: none;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .axis-modal-header {
        padding: 1.5rem 1.5rem 0.5rem;
    }

    .axis-modal-body {
        padding: 1.5rem;
    }

    .axis-modal-footer {
        padding: 0 1.5rem 1.5rem;
        border: none;
    }

    /* Unit Picker (Stripe Style) */
    .unit-picker-trigger {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.6rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        background: white;
        transition: all 0.2s;
    }

    .unit-picker-trigger:hover {
        border-color: #cbd5e1;
    }

    .unit-picker-trigger.open {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .unit-picker-dropdown {
        position: absolute;
        z-index: 1050;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-top: 4px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        width: 100%;
        display: none;
        overflow: hidden;
    }

    .unit-picker-dropdown.show {
        display: block;
    }

    .unit-picker-search {
        padding: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .unit-picker-search input {
        width: 100%;
        border: none;
        padding: 6px 12px;
        font-size: 0.9rem;
        outline: none;
    }

    .unit-picker-options {
        max-height: 200px;
        overflow-y: auto;
    }

    .unit-picker-option {
        padding: 8px 16px;
        cursor: pointer;
        font-size: 0.9rem;
        color: #3F67AC;
        transition: all 0.1s;
    }

    .unit-picker-option:hover {
        background: #f8fafc;
        color: #1e293b;
    }

    .unit-picker-option.selected {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 500;
    }
</style>
<?= $this->endSection() ?>

<!-- Toast -->
<div id="toastNotification" class="toast-notification"></div>

<div class="row">
    <div class="col-12 px-2 px-md-4 mt-2">



        <!-- ── Hero ── -->
        <div class="cc-hero">
            <div class="cc-hero-left">
                <h2 class="cc-hero-title">Residentes Por Asignar</h2>
                <div class="cc-hero-divider"></div>
                <div class="cc-hero-breadcrumb">
                    <i class="bi bi-people"></i>
                    <i class="bi bi-chevron-right"></i>
                    Residentes registrados que aún no tienen unidad asignada
                </div>
            </div>
            <div class="badge-count-premium">
                <?= count($unassigned) ?> pendiente<?= count($unassigned) !== 1 ? 's' : '' ?>
            </div>
        </div>
        <!-- ── END Hero ── -->



        <div class="premium-main-container mb-4">
            <!-- BARRA DE FILTROS -->
            <div class="premium-filter-bar">
                <div class="d-flex align-items-center gap-2">
                    <div class="search-input-group">
                        <i class="bi bi-search" style="font-size:0.85rem"></i>
                        <input type="text" class="form-control form-control-sm border shadow-none" id="searchInput"
                            placeholder="Buscar residentes..." oninput="filterTable()">
                    </div>
                    <button class="btn btn-white border rounded-2 px-2 py-1 shadow-none" onclick="location.reload()"
                        title="Actualizar">
                        <i class="bi bi-arrow-clockwise text-muted"></i>
                    </button>
                </div>
            </div>

            <?php if (count($unassigned) > 0): ?>
                <!-- TABLA DE RESIDENTES SIN UNIDAD -->
                <div class="table-responsive">
                    <table class="table mb-0 axis-table" id="unassignedTable">
                        <thead>
                            <tr>
                                <th style="width:50px;">#</th>
                                <th>Residente</th>
                                <th>Email</th>
                                <th>Teléfono</th>
                                <th>Tipo</th>
                                <th>Registrado</th>
                                <th style="width:120px; text-align:center;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($unassigned as $i => $r): ?>
                                <tr class="res-row"
                                    data-search="<?= strtolower($r['first_name'] . ' ' . $r['last_name'] . ' ' . $r['email']) ?>">
                                    <td class="text-muted"><?= $i + 1 ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="resident-avatar" style="overflow: hidden; background-color: #f1f5f9; display: flex; align-items: center; justify-content: center;">
                                                <?php if (!empty($r['avatar'])): ?>
                                                    <img src="<?= base_url('media/image/avatars/' . $r['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                                <?php else: ?>
                                                    <?= strtoupper(substr($r['first_name'], 0, 1)) ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="fw-bold"><?= esc($r['first_name'] . ' ' . $r['last_name'] ?? '') ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-secondary"><?= esc($r['email']) ?></td>
                                    <td class="text-secondary"><?= esc($r['phone'] ?? '-') ?></td>
                                    <td>
                                        <span class="type-link <?= $r['type'] === 'owner' ? '' : 'tenant' ?>">
                                            <i class="bi bi-<?= $r['type'] === 'owner' ? 'house-door' : 'person' ?> me-1"></i>
                                            <?= $r['type'] === 'owner' ? 'Propietario' : 'Inquilino' ?>
                                        </span>
                                    </td>
                                    <td class="text-muted" style="font-size:0.85rem;">
                                        <?= $r['resident_since'] ? date('d/m/Y', strtotime($r['resident_since'])) : '-' ?>
                                    </td>
                                    <td class="text-center">
                                        <button class="btn-assign-premium"
                                            onclick="openAssignModal(<?= $r['resident_id'] ?>, '<?= esc($r['first_name']) ?> <?= esc($r['last_name'] ?? '') ?>')">
                                            <i class="bi bi-building-add"></i> Asignar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <!-- EMPTY STATE PREMIUM -->
                <div class="empty-state-wrapper py-5 px-3">
                    <div class="empty-state-icon-circle mx-auto mb-4"
                        style="width: 72px; height: 72px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; color: #d97706;">
                        <i class="bi bi-building"></i>
                    </div>
                    <h3
                        style="font-weight: 600; color: #1e293b; margin-bottom: 0.75rem; text-align: center; font-size: 1.5rem;">
                        Todos los residentes asignados</h3>
                    <p
                        style="color: #64748b; text-align: center; max-width: 600px; margin: 0 auto 3rem; font-size: 0.95rem; line-height: 1.6;">
                        ¡Buen trabajo! Todos tus residentes están actualmente asignados a unidades. Los residentes por
                        asignar aparecerán aquí cuando necesiten ser vinculados con sus unidades.
                    </p>

                    <!-- Cards container -->
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-4 mx-auto"
                        style="max-width: 800px; margin-bottom: 3rem;">

                        <!-- Card 1 -->
                        <div class="info-card d-flex"
                            style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 1.5rem; flex: 1;">
                            <div class="icon-box flex-shrink-0"
                                style="width: 32px; height: 32px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-right: 1rem; font-size: 1.1rem;">
                                <i class="bi bi-person-fill-gear"></i>
                            </div>
                            <div>
                                <h6
                                    style="color: #1e293b; font-weight: 600; font-size: 0.95rem; margin-bottom: 0.5rem; margin-top: 0.25rem;">
                                    Asignaciones flexibles</h6>
                                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 0; line-height: 1.5;">Gestiona
                                    fácilmente las relaciones residente-unidad desde la vista de detalles del residente.</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="info-card d-flex"
                            style="background: #fafafa; border: 1px solid #fde68a; border-radius: 12px; padding: 1.5rem; flex: 1; background: linear-gradient(to right, #fffbeb, #fafafa);">
                            <div class="icon-box flex-shrink-0"
                                style="width: 32px; height: 32px; background: #fef3c7; color: #f59e0b; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-right: 1rem; font-size: 1.1rem;">
                                <i class="bi bi-arrow-right"></i>
                            </div>
                            <div>
                                <h6
                                    style="color: #1e293b; font-weight: 600; font-size: 0.95rem; margin-bottom: 0.5rem; margin-top: 0.25rem;">
                                    Múltiples unidades</h6>
                                <p style="color: #64748b; font-size: 0.85rem; margin-bottom: 0; line-height: 1.5;">Los
                                    residentes pueden ser asignados a múltiples unidades si poseen o administran más de una
                                    propiedad.</p>
                            </div>
                        </div>

                    </div>

                    <div class="text-center">
                        <p style="color: #94a3b8; font-size: 0.75rem; font-style: italic;">Consejo: Haz clic en cualquier
                            residente para ver sus detalles y gestionar asignaciones de unidad</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- MODAL ASIGNAR UNIDAD -->
    <div class="modal fade" id="assignModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content axis-modal-content">
                <div class="modal-header axis-modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-building-add text-primary me-2"></i>Asignar Unidad
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body axis-modal-body">
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">Asignando unidad a: <strong
                            id="assignResidentName" class="text-dark"></strong></p>
                    <input type="hidden" id="assignResidentId">

                    <div class="mb-3 position-relative">
                        <label class="form-label text-secondary fw-600 mb-2" style="font-size: 0.85rem;">Selecciona la
                            Unidad</label>
                        <input type="hidden" id="assignUnitId">
                        <div class="unit-picker-trigger" id="assign-select-trigger">
                            <span id="assign-select-text" class="text-muted">Seleccionar Unidad</span>
                            <i class="bi bi-chevron-expand text-muted"></i>
                        </div>
                        <div class="unit-picker-dropdown" id="assign-select-dropdown">
                            <div class="unit-picker-search">
                                <input type="text" id="assign-search-input" placeholder="Buscar unidad..."
                                    autocomplete="off">
                            </div>
                            <div class="unit-picker-options" id="assign-options-list">
                                <?php foreach ($units as $u): ?>
                                    <div class="unit-picker-option" data-value="<?= $u['id'] ?>"
                                        data-text="<?= esc($u['unit_number']) ?>">
                                        <?= esc($u['unit_number']) ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer axis-modal-footer">
                    <button type="button" class="btn btn-light border px-4 fw-600" data-bs-dismiss="modal"
                        style="font-size: 0.85rem;">Cancelar</button>
                    <button type="button" class="btn px-4 text-white fw-600" id="btnConfirmAssign"
                        style="background: #1e293b; border: none; border-radius: 6px; font-size: 0.85rem;"
                        onclick="confirmAssign()">
                        <i class="bi bi-check-lg me-1"></i> Confirmar Asignación
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('#unassignedTable tbody tr').forEach(row => {
                const text = row.getAttribute('data-search') || '';
                row.style.display = text.includes(q) ? '' : 'none';
            });
        }

        function openAssignModal(residentId, name) {
            document.getElementById('assignResidentId').value = residentId;
            document.getElementById('assignResidentName').textContent = name;
            // Reset custom select
            document.getElementById('assignUnitId').value = '';
            document.getElementById('assign-select-text').textContent = 'Seleccionar Unidad';
            document.getElementById('assign-select-text').classList.add('text-muted');
            document.getElementById('assign-select-dropdown').classList.remove('show');
            document.getElementById('assign-select-trigger').classList.remove('open');
            document.getElementById('assign-search-input').value = '';
            document.querySelectorAll('#assign-options-list .unit-picker-option').forEach(o => {
                o.style.display = 'block';
                o.classList.remove('selected');
            });
            new bootstrap.Modal(document.getElementById('assignModal')).show();
        }

        // --- Searchable Dropdown Logic ---
        (function () {
            const trigger = document.getElementById('assign-select-trigger');
            const dropdown = document.getElementById('assign-select-dropdown');
            const searchInput = document.getElementById('assign-search-input');
            const hiddenInput = document.getElementById('assignUnitId');
            const triggerText = document.getElementById('assign-select-text');
            const optionsList = document.getElementById('assign-options-list');

            if (!trigger) return;

            trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                const isOpen = dropdown.classList.toggle('show');
                trigger.classList.toggle('open', isOpen);
                if (isOpen) {
                    searchInput.value = '';
                    searchInput.focus();
                    // Reset filter
                    optionsList.querySelectorAll('.unit-picker-option').forEach(o => o.style.display = 'block');
                }
            });

            searchInput.addEventListener('input', (e) => {
                const term = e.target.value.toLowerCase();
                let visibleCount = 0;
                optionsList.querySelectorAll('.unit-picker-option').forEach(opt => {
                    const text = opt.getAttribute('data-text').toLowerCase();
                    const show = text.includes(term);
                    opt.style.display = show ? 'block' : 'none';
                    if (show) visibleCount++;
                });
                // Show/hide empty message
                let emptyMsg = optionsList.querySelector('.unit-picker-empty');
                if (visibleCount === 0) {
                    if (!emptyMsg) {
                        emptyMsg = document.createElement('div');
                        emptyMsg.className = 'unit-picker-empty';
                        emptyMsg.style.padding = '8px 16px';
                        emptyMsg.style.fontSize = '0.85rem';
                        emptyMsg.style.color = '#94a3b8';
                        emptyMsg.textContent = 'No se encontraron unidades';
                        optionsList.appendChild(emptyMsg);
                    }
                    emptyMsg.style.display = 'block';
                } else if (emptyMsg) {
                    emptyMsg.style.display = 'none';
                }
            });

            // Prevent closing when clicking inside search
            searchInput.addEventListener('click', (e) => e.stopPropagation());

            optionsList.addEventListener('click', (e) => {
                const opt = e.target.closest('.unit-picker-option');
                if (!opt) return;
                const val = opt.getAttribute('data-value');
                const text = opt.getAttribute('data-text');
                hiddenInput.value = val;
                triggerText.textContent = text;
                triggerText.classList.remove('text-muted');
                optionsList.querySelectorAll('.unit-picker-option').forEach(o => o.classList.remove('selected'));
                opt.classList.add('selected');
                dropdown.classList.remove('show');
                trigger.classList.remove('open');
            });

            // Close on outside click
            document.addEventListener('click', (e) => {
                if (!trigger.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('show');
                    trigger.classList.remove('open');
                }
            });
        })();

        async function confirmAssign() {
            const residentId = document.getElementById('assignResidentId').value;
            const unitId = document.getElementById('assignUnitId').value;

            if (!unitId) {
                showToast('Selecciona una unidad.', 'error');
                return;
            }

            const btn = document.getElementById('btnConfirmAssign');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Asignando...';

            try {
                const formData = new FormData();
                formData.append('resident_id', residentId);
                formData.append('unit_id', unitId);

                const res = await fetch('<?= base_url("admin/residentes/asignar-unidad") ?>', {
                    method: 'POST',
                    body: formData
                });

                const result = await res.json();

                if (result.success) {
                    bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
                    showToast('¡Unidad asignada exitosamente!', 'success');
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showToast(result.message || 'Error al asignar.', 'error');
                }
            } catch (err) {
                showToast('Error de conexión.', 'error');
            }

            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-1"></i> Confirmar Asignación';
        }

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toastNotification');
            toast.className = `toast-notification toast-${type} show`;
            toast.textContent = message;
            setTimeout(() => toast.classList.remove('show'), 3500);
        }
    </script>

    <?= $this->endSection() ?>