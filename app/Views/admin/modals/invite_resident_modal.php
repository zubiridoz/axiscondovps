

<!-- MODAL INVITAR RESIDENTE -->
<div class="modal fade" id="inviteModal" tabindex="-1" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content axis-modal-content">
            <div class="axis-modal-header border-0 pb-0">
                <h5 class="fw-bold mb-1">Invitar Residente</h5>
                <p class="text-muted small mb-0">Enviar una invitación para agregar un nuevo residente a la comunidad.
                </p>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            <div class="modal-body p-4">
                <!-- TABS INTERNAS -->
                <div class="modal-tab-nav">
                    <button class="modal-tab-btn active" data-tab="manual">
                        <i class="bi bi-person-plus me-2"></i> Agregar Residente
                    </button>
                    <button class="modal-tab-btn" data-tab="import">
                        <i class="bi bi-people me-2"></i> Importar Residentes
                    </button>
                </div>

                <!-- CONTENIDO TABS -->
                <div id="tab-manual-content">
                    <div class="row">
                        <div class="col-md-6">
                            <form id="invite-form">
                                <div class="mb-3">
                                    <label class="form-label-premium">Nombre</label>
                                    <input type="text" id="inv-first-name" class="form-control axis-pill-input" placeholder="John Doe">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label-premium"><i class="bi bi-envelope me-1"></i> Correo Electrónico</label>
                                    <input type="email" id="inv-email" class="form-control axis-pill-input" placeholder="john@example.com" required>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label-premium">Unidad</label>
                                    <input type="hidden" name="unit_id" id="invite-unit-id">
                                    <div class="custom-select-wrapper unit-picker" data-idx="manual">
                                        <div class="unit-picker-trigger" id="unit-select-trigger" data-idx="manual">
                                            <span class="unit-picker-text">Seleccionar Unidad</span>
                                            <i class="bi bi-chevron-expand text-muted"></i>
                                        </div>
                                        <div class="unit-picker-panel p-2 shadow border border-light" id="panel-manual" style="display:none; position:fixed; z-index:9999; max-height:260px; overflow-y:auto; background:white; border-radius:8px;">
                                            <div class="mb-2 position-relative">
                                                <i class="bi bi-search position-absolute text-muted" style="left:12px; top:50%; transform:translateY(-50%); font-size:0.8rem;"></i>
                                                <input type="text" class="form-control form-control-sm unit-search-field ps-4 bg-light border-0" placeholder="Buscar" autocomplete="off">
                                            </div>
                                            <div class="unit-picker-options">
                                                <?php foreach ($units as $u): ?>
                                                    <div class="unit-picker-opt" data-value="<?= $u['id'] ?>" data-text="<?= esc($u['unit_number']) ?>" data-idx="manual">
                                                        <i class="bi bi-check me-2 invisible"></i> <?= esc($u['unit_number']) ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div class="info-box h-100">
                                <div class="info-item">
                                    <div class="info-icon"><i class="bi bi-send"></i></div>
                                    <div class="info-text">
                                        <h6>¿Qué sucede después?</h6>
                                        <p>The invitation will be sent via email.</p>
                                    </div>
                                </div>
                                <div class="info-item">
                                    <div class="info-icon"><i class="bi bi-person-plus"></i></div>
                                    <div class="info-text">
                                        <h6>Asignación de unidad</h6>
                                        <p>Puede asignar una unidad ahora o más tarde desde la lista de residentes.</p>
                                    </div>
                                </div>
                                <div class="info-item mb-0">
                                    <div class="info-icon bg-light-success"><i class="bi bi-check-lg pt-1"></i></div>
                                    <div class="info-text">
                                        <h6>Acceso y permisos</h6>
                                        <p>Una vez que acepten, tendrán acceso a las funciones de la comunidad, anuncios
                                            y reservas de amenidades.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end mt-4">
                        <button class="btn btn-cancel-custom me-2"
                            data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-send-invite-custom" id="btn-send-invite">Enviar Invitación</button>
                    </div>
                </div>

                <div id="tab-import-content" class="d-none">
                    <!-- STEPS NAV -->
                    <div class="steps-nav" id="import-steps-nav">
                        <div class="step-progress-line" id="step-progress-line"></div>
                        <div class="step-item active" data-step="1">
                            <div class="step-circle">1</div>
                            <div class="step-label">Subir</div>
                        </div>
                        <div class="step-item" data-step="2">
                            <div class="step-circle">2</div>
                            <div class="step-label">Revisar</div>
                        </div>
                        <div class="step-item" data-step="3">
                            <div class="step-circle">3</div>
                            <div class="step-label">Notificar</div>
                        </div>
                    </div>

                    <!-- ========== STEP 1: SUBIR ========== -->
                    <div id="import-step-1">
                        <div class="drop-zone-premium mb-4" id="res-drop-zone">
                            <div class="drop-zone-icon-circle">
                                <i class="bi bi-upload"></i>
                            </div>
                            <h6 class="fw-bold mb-1">Arrastra un archivo o haz clic para seleccionar</h6>
                            <p class="text-muted small mb-0">Sube un archivo con la lista de residentes a invitar</p>
                            <input type="file" id="import-file-input" accept=".csv" style="display: none;">
                        </div>

                        <div class="text-center mb-4">
                            <div class="d-flex justify-content-center gap-3 text-muted small mb-3 opacity-75">
                                <span>CSV, XLSX, XLS</span>
                                <span>|</span>
                                <span>PDF, DOCX</span>
                                <span>|</span>
                                <span>Max 10MB</span>
                            </div>
                            <button type="button" class="btn btn-white border px-4 rounded-3 d-inline-flex align-items-center fw-600" style="font-size:0.85rem" id="btn-download-csv-template">
                                <i class="bi bi-file-earmark-text me-2"></i> Descargar plantilla CSV
                            </button>
                        </div>

                        <div class="text-end mt-4">
                            <button class="btn btn-cancel-custom" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>

                    <!-- ========== STEP 2: REVISAR ========== -->
                    <div id="import-step-2" class="d-none">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="search-input-group">
                                <i class="bi bi-search"></i>
                                <input type="text" class="form-control" id="review-search" placeholder="Buscar contactos..." style="width:220px;">
                            </div>
                            <div class="text-secondary small fw-500">
                                <span id="review-selected-count">0</span> de <span id="review-total-count">0</span> seleccionados
                            </div>
                        </div>
                        <div style="max-height: 280px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 0.75rem;">
                            <table class="review-table table mb-0" id="review-table">
                                <thead>
                                    <tr>
                                        <th style="width:40px;"><div class="custom-checkbox-wrapper"><input type="checkbox" id="review-select-all" checked></div></th>
                                        <th class="ps-2">Nombre</th>
                                        <th>Email</th>
                                        <th>Telefono</th>
                                        <th>Unidad</th>
                                        <th>Rol</th>
                                        <th style="width:40px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="review-table-body"></tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-cancel-custom" id="btn-back-to-step1">
                                <i class="bi bi-arrow-left me-1"></i> Volver
                            </button>
                            <div class="d-flex gap-2">
                                <button class="btn btn-cancel-custom" data-bs-dismiss="modal">Cancelar</button>
                                <button type="button" class="btn btn-next-custom" id="btn-go-to-step3">Siguiente <i class="bi bi-arrow-right ms-1"></i></button>
                            </div>
                        </div>
                    </div>

                    <!-- ========== STEP 3: NOTIFICAR ========== -->
                    <div id="import-step-3" class="d-none">
                        <div class="import-summary-banner">
                            Se importarán <span id="notify-count">0</span> residentes
                        </div>

                        <h6 class="fw-bold mb-3" style="font-size:0.9rem;">Opciones de notificación</h6>

                        <label class="radio-card selected" id="radio-card-none">
                            <input type="radio" name="notify_option" value="none" checked>
                            <div class="radio-card-body">
                                <h6><i class="bi bi-bell-slash me-1"></i> No notificar ahora</h6>
                                <p>Los residentes se agregarán a la lista de invitados pero no recibirán notificación. Podrás enviar las invitaciones después.</p>
                            </div>
                        </label>

                        <label class="radio-card" id="radio-card-all">
                            <input type="radio" name="notify_option" value="all">
                            <div class="radio-card-body">
                                <h6><i class="bi bi-bell me-1"></i> Notificar a todos</h6>
                                <p>Se enviará un email de invitación a todos los residentes importados.</p>
                            </div>
                        </label>

                        <button type="button" class="btn-import-final mt-3" id="btn-final-import">
                            Importar <span id="final-import-count">0</span> residentes
                        </button>

                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-cancel-custom" id="btn-back-to-step2">
                                <i class="bi bi-arrow-left me-1"></i> Volver
                            </button>
                            <button class="btn btn-cancel-custom" data-bs-dismiss="modal">Cancelar</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    <script>
    (function() {
        // ========================
        // STATE
        // ========================
        let importData = [];      // Array of row objects from CSV
        let cacheKey = null;      // Server cache key for confirmed import
        let currentStep = 1;

        // Available units from the condominium (populated from PHP)
        const availableUnits = [
            <?php foreach ($units as $u): ?>
            { id: <?= $u['id'] ?>, number: '<?= esc($u['unit_number']) ?>' },
            <?php endforeach; ?>
        ];

        // ========================
        // TAB SWITCHING
        // ========================
        document.addEventListener('click', function(e) {
            const tabBtn = e.target.closest('.modal-tab-btn');
            if (tabBtn) {
                document.querySelectorAll('.modal-tab-btn').forEach(b => b.classList.remove('active'));
                tabBtn.classList.add('active');
                const tab = tabBtn.getAttribute('data-tab');
                const tabManual = document.getElementById('tab-manual-content');
                const tabImport = document.getElementById('tab-import-content');
                if (tab === 'manual') {
                    if(tabManual) tabManual.classList.remove('d-none');
                    if(tabImport) tabImport.classList.add('d-none');
                } else {
                    if(tabManual) tabManual.classList.add('d-none');
                    if(tabImport) tabImport.classList.remove('d-none');
                    // Reset to step 1 when switching to import tab
                    goToStep(1);
                }
            }
        });

        // ========================
        // WIZARD NAVIGATION
        // ========================
        function goToStep(step) {
            currentStep = step;
            // Hide all steps
            document.getElementById('import-step-1').classList.add('d-none');
            document.getElementById('import-step-2').classList.add('d-none');
            document.getElementById('import-step-3').classList.add('d-none');
            // Show target
            document.getElementById('import-step-' + step).classList.remove('d-none');

            // Update step indicators
            const items = document.querySelectorAll('#import-steps-nav .step-item');
            items.forEach(item => {
                const s = parseInt(item.getAttribute('data-step'));
                item.classList.remove('active', 'completed');
                if (s < step) {
                    item.classList.add('completed');
                    item.querySelector('.step-circle').innerHTML = '<i class="bi bi-check-lg"></i>';
                } else if (s === step) {
                    item.classList.add('active');
                    item.querySelector('.step-circle').textContent = s;
                } else {
                    item.querySelector('.step-circle').textContent = s;
                }
            });

            // Progress line
            const progressLine = document.getElementById('step-progress-line');
            if (step === 1) progressLine.style.width = '0%';
            else if (step === 2) progressLine.style.width = '35%';
            else if (step === 3) progressLine.style.width = '70%';
        }

        // ========================
        // CSV TEMPLATE DOWNLOAD
        // ========================
        document.addEventListener('click', function(e) {
            if (e.target && (e.target.id === 'btn-download-csv-template' || e.target.closest('#btn-download-csv-template'))) {
                e.preventDefault();
                const csvContent = '# Completa los datos de cada residente,,,,# Roles: owner,tenant,admin\nnombre,correo,telefono,unidad,rol\n';
                const blob = new Blob(["\uFEFF" + csvContent], { type: 'text/csv;charset=utf-8;' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'Plantilla_Invitar_Residentes.csv';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                URL.revokeObjectURL(url);
            }
        });

        // ========================
        // DRAG & DROP + FILE INPUT
        // ========================
        function initDropZone() {
            const dropZone = document.getElementById('res-drop-zone');
            if (!dropZone) return;

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(ev => {
                dropZone.addEventListener(ev, e => { e.preventDefault(); e.stopPropagation(); }, false);
            });
            ['dragenter', 'dragover'].forEach(ev => {
                dropZone.addEventListener(ev, () => dropZone.classList.add('dragover'), false);
            });
            ['dragleave', 'drop'].forEach(ev => {
                dropZone.addEventListener(ev, () => dropZone.classList.remove('dragover'), false);
            });
            dropZone.addEventListener('drop', (e) => {
                if (e.dataTransfer.files.length > 0) handleFileUpload(e.dataTransfer.files[0]);
            });
        }

        document.addEventListener('click', function(e) {
            const dropZone = document.getElementById('res-drop-zone');
            if (dropZone && (e.target === dropZone || dropZone.contains(e.target))) {
                const fi = document.getElementById('import-file-input');
                if (fi && !e.target.closest('input[type=file]')) fi.click();
            }
        });

        function setupFileInput() {
            const fi = document.getElementById('import-file-input');
            if (fi && !fi.hasAttribute('data-bound')) {
                fi.addEventListener('change', (e) => {
                    if (e.target.files.length > 0) handleFileUpload(e.target.files[0]);
                });
                fi.setAttribute('data-bound', 'true');
            }
        }

        // ========================
        // FILE UPLOAD → STEP 2
        // ========================
        async function handleFileUpload(file) {
            if (!file) return;

            const dropZone = document.getElementById('res-drop-zone');
            const originalHTML = dropZone.innerHTML;
            dropZone.innerHTML = '<div class="spinner-border text-primary my-3"></div><h6 class="fw-bold">Procesando archivo...</h6>';

            const formData = new FormData();
            formData.append('file', file);
            formData.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');

            try {
                const response = await fetch('<?= base_url("admin/residentes/import") ?>', {
                    method: 'POST',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                    body: formData
                });
                const result = await response.json();

                if (response.ok && result.success && result.data.valid.length > 0) {
                    cacheKey = result.data.cache_key;
                    importData = result.data.valid;
                    renderReviewTable();
                    goToStep(2);
                } else if (response.ok && result.success && result.data.valid.length === 0) {
                    Swal.fire({ icon: 'warning', title: 'Sin datos válidos', text: 'El archivo no contiene filas válidas.', confirmButtonColor: '#6366f1' });
                    dropZone.innerHTML = originalHTML;
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: result.message || 'No se pudo leer el archivo.', confirmButtonColor: '#6366f1' });
                    dropZone.innerHTML = originalHTML;
                }
            } catch (err) {
                Swal.fire({ icon: 'error', title: 'Error de Red', text: 'No se pudo conectar con el servidor.', confirmButtonColor: '#6366f1' });
                dropZone.innerHTML = originalHTML;
            }
        }

        // ========================
        // STEP 2: REVIEW TABLE
        // ========================
        function renderReviewTable() {
            const tbody = document.getElementById('review-table-body');
            tbody.innerHTML = '';
            importData.forEach((row, i) => {
                const roleVal = row.role || 'owner';
                const isOwner = (roleVal === 'owner' || roleVal.toLowerCase().includes('propie'));
                const tr = document.createElement('tr');

                // Find matching unit
                const csvUnit = (row.unit || '').trim().toLowerCase();
                const matchedUnit = availableUnits.find(u => u.number.toLowerCase() === csvUnit);
                const unitDisplay = matchedUnit ? matchedUnit.number : 'Sin asignar';
                // Sync back if matched
                if (matchedUnit) row.unit = matchedUnit.number;

                tr.innerHTML = `
                    <td><div class="custom-checkbox-wrapper"><input type="checkbox" class="row-checkbox" checked data-idx="${i}"></div></td>
                    <td class="ps-2">
                        <div class="input-with-icon">
                            <input type="text" value="${escHtml(row.name)}" data-idx="${i}" data-field="name" placeholder="Nombre">
                            <i class="bi bi-pen"></i>
                        </div>
                    </td>
                    <td>
                        <div class="input-with-icon">
                            <input type="text" value="${escHtml(row.email)}" data-idx="${i}" data-field="email" placeholder="Correo">
                            <i class="bi bi-pen"></i>
                        </div>
                    </td>
                    <td>
                        <div class="phone-input-wrapper">
                            <div class="phone-flag-box">
                                <span class="phone-flag">🇲🇽</span>
                                <i class="bi bi-chevron-expand ms-1 text-muted" style="font-size:0.75rem;"></i>
                            </div>
                            <input type="text" value="${escHtml(row.phone || '')}" data-idx="${i}" data-field="phone" placeholder="+52 ...">
                        </div>
                    </td>
                    <td>
                        <div class="unit-picker custom-unit-picker" data-idx="${i}">
                            <div class="unit-picker-trigger" data-idx="${i}">
                                <span class="unit-picker-text">${escHtml(unitDisplay)}</span>
                                <i class="bi bi-chevron-expand chevron ms-1 text-muted"></i>
                            </div>
                            <div class="unit-picker-panel p-2 shadow border border-light" style="min-width:200px; max-height:260px; overflow-y:auto; border-radius:8px; display:none; background:white; position:fixed; z-index:9999;" id="panel-${i}">
                                <div class="mb-2 position-relative">
                                    <i class="bi bi-search position-absolute text-muted" style="left:12px; top:50%; transform:translateY(-50%); font-size:0.8rem;"></i>
                                    <input type="text" placeholder="Buscar..." class="form-control form-control-sm unit-search-field ps-4 bg-light border-0">
                                </div>
                                <div class="unit-picker-options">
                                    <div class="unit-picker-opt ${!matchedUnit ? 'selected fw-bold bg-light' : ''}" data-value="" data-idx="${i}">
                                        <i class="bi bi-check me-2 ${!matchedUnit ? '' : 'invisible'}"></i> Sin asignar
                                    </div>
                                    ${availableUnits.map(u => {
                                        const sel = (matchedUnit && matchedUnit.number === u.number) ? 'selected fw-bold bg-light' : '';
                                        const v = (matchedUnit && matchedUnit.number === u.number) ? '' : 'invisible';
                                        return '<div class="unit-picker-opt ' + sel + '" data-value="' + escHtml(u.number) + '" data-idx="' + i + '"><i class="bi bi-check me-2 ' + v + '"></i>' + escHtml(u.number) + '</div>';
                                    }).join('')}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <select data-idx="${i}" data-field="role" class="review-select">
                            <option value="Propietario" ${isOwner ? 'selected' : ''}>Propietario</option>
                            <option value="Inquilino" ${!isOwner ? 'selected' : ''}>Inquilino</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn-remove-row text-secondary p-1" data-idx="${i}" title="Eliminar"><i class="bi bi-trash"></i></button></td>
                `;
                tbody.appendChild(tr);
            });
            updateCounts();
        }

        function updateCounts() {
            let activeCount = 0;
            document.querySelectorAll('.row-checkbox').forEach(cb => {
                if (cb.checked) activeCount++;
            });
            const totalCount = importData.length;
            const checkedCount = activeCount;

            const el1_sel = document.getElementById('review-selected-count');
            const el1_tot = document.getElementById('review-total-count');
            if (el1_sel) el1_sel.textContent = checkedCount;
            if (el1_tot) el1_tot.textContent = totalCount;

            const el2 = document.getElementById('notify-count');
            const el3 = document.getElementById('final-import-count');
            if (el2) el2.textContent = checkedCount;
            if (el3) el3.textContent = checkedCount;
        }

        function escHtml(str) {
            const d = document.createElement('div');
            d.textContent = str || '';
            return d.innerHTML;
        }

        // Inline editing: sync changes back to importData
        document.addEventListener('change', function(e) {
            // Handle row checkboxes
            if (e.target.classList.contains('row-checkbox') || e.target.id === 'review-select-all') {
                if (e.target.id === 'review-select-all') {
                    const isChecked = e.target.checked;
                    document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = isChecked);
                } else {
                    const allChecked = document.querySelectorAll('.row-checkbox:not(:checked)').length === 0;
                    const selectAll = document.getElementById('review-select-all');
                    if (selectAll) selectAll.checked = allChecked;
                }
                updateCounts();
            }
        });

        document.addEventListener('input', function(e) {
            if (e.target.closest('.review-table') && e.target.tagName === 'INPUT') {
                const idx = parseInt(e.target.getAttribute('data-idx'));
                const field = e.target.getAttribute('data-field');
                if (!isNaN(idx) && field && importData[idx]) {
                    importData[idx][field] = e.target.value;
                }
            }
        });
        // Sync select changes (unit, role dropdowns)
        document.addEventListener('change', function(e) {
            if (e.target.closest('.review-table') && e.target.tagName === 'SELECT') {
                const idx = parseInt(e.target.getAttribute('data-idx'));
                const field = e.target.getAttribute('data-field');
                if (!isNaN(idx) && field && importData[idx]) {
                    importData[idx][field] = e.target.value;
                }
            }
        });

        // Remove row
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-remove-row');
            if (btn) {
                const idx = parseInt(btn.getAttribute('data-idx'));
                importData.splice(idx, 1);
                renderReviewTable();
            }
        });

        // ========================
        // UNIT PICKER LOGIC (APPENDBODY OVERFLOW FIX)
        // ========================
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.unit-picker-trigger');
            if (trigger) {
                e.stopPropagation();
                
                // Close all existing open panels
                document.querySelectorAll('.unit-picker-panel.open').forEach(p => {
                    p.classList.remove('open');
                    p.style.display = 'none';
                });

                const picker = trigger.closest('.unit-picker');
                const idx = trigger.getAttribute('data-idx');
                let panel = document.getElementById('panel-' + idx);
                
                if (!panel) return;

                // Move panel to body if it's not already
                if (panel.parentNode !== document.body) {
                    document.body.appendChild(panel);
                }

                // Toggle logic
                if (panel.classList.contains('open')) {
                    panel.classList.remove('open');
                    panel.style.display = 'none';
                } else {
                    panel.classList.add('open');
                    panel.style.display = 'block';

                    // Position calculating
                    const rect = trigger.getBoundingClientRect();
                    panel.style.top = (rect.bottom + window.scrollY + 4) + 'px';
                    panel.style.left = rect.left + 'px';
                    panel.style.width = Math.max(rect.width, 200) + 'px';
                    
                    const searchField = panel.querySelector('.unit-search-field');
                    if (searchField) { searchField.value = ''; searchField.focus(); }
                    panel.querySelectorAll('.unit-picker-opt').forEach(o => o.style.display = '');
                }
                return;
            }

            // Select an option
            const opt = e.target.closest('.unit-picker-opt');
            if (opt) {
                const idx = parseInt(opt.getAttribute('data-idx'));
                const value = opt.getAttribute('data-value');
                const panel = opt.closest('.unit-picker-panel');
                
                // Find matching trigger based on panel id
                const trIdx = panel.id.split('-')[1];
                const pickerTrigger = document.querySelector(`.unit-picker-trigger[data-idx="${trIdx}"]`);
                if (!pickerTrigger) return;
                
                const textEl = pickerTrigger.querySelector('.unit-picker-text');
                
                // Update display
                textEl.textContent = opt.getAttribute('data-text') || value || 'Sin asignar';

                // Update selected state and checks visually
                panel.querySelectorAll('.unit-picker-opt').forEach(o => {
                    o.classList.remove('selected', 'fw-bold', 'bg-light');
                    const icon = o.querySelector('.bi-check');
                    if (icon) icon.classList.add('invisible');
                });
                opt.classList.add('selected', 'fw-bold', 'bg-light');
                const thisIcon = opt.querySelector('.bi-check');
                if (thisIcon) thisIcon.classList.remove('invisible');

                // Sync to importData or hidden input
                if (trIdx === 'manual') {
                    const hiddenInput = document.getElementById('invite-unit-id');
                    if (hiddenInput) hiddenInput.value = value;
                } else if (!isNaN(idx) && importData[idx]) {
                    importData[idx].unit = value;
                }

                // Close panel
                panel.classList.remove('open');
                panel.style.display = 'none';
                return;
            }

            // If clicking inside the panel (e.g., the search box), do nothing!
            if (e.target.closest('.unit-picker-panel')) {
                return;
            }

            // Click outside closes all panels
            document.querySelectorAll('.unit-picker-panel.open').forEach(p => {
                p.classList.remove('open');
                p.style.display = 'none';
            });
        });

        // Search filter
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('unit-search-field')) {
                const term = e.target.value.toLowerCase();
                const panel = e.target.closest('.unit-picker-panel');
                panel.querySelectorAll('.unit-picker-opt').forEach(opt => {
                    // Manual tab uses data-text for the label, import table uses data-value or textContent
                    const text = (opt.getAttribute('data-text') || opt.getAttribute('data-value') || opt.textContent).toLowerCase();
                    opt.style.display = text.includes(term) ? 'block' : 'none';
                });
            }
        });

        // ========================
        // STEP 3: RADIO SELECTION
        // ========================
        document.addEventListener('change', function(e) {
            if (e.target.name === 'notify_option') {
                document.querySelectorAll('.radio-card').forEach(c => c.classList.remove('selected'));
                e.target.closest('.radio-card').classList.add('selected');
            }
        });

        // ========================
        // NAVIGATION BUTTONS
        // ========================
        document.addEventListener('click', function(e) {
            if (e.target.id === 'btn-back-to-step1' || e.target.closest('#btn-back-to-step1')) {
                // Restore drop zone
                const dz = document.getElementById('res-drop-zone');
                dz.innerHTML = '<div class="drop-zone-icon-circle"><i class="bi bi-upload"></i></div><h6 class="fw-bold mb-1">Arrastra un archivo o haz clic para seleccionar</h6><p class="text-muted small mb-0">Sube un archivo con la lista de residentes a invitar</p><input type="file" id="import-file-input" accept=".csv" style="display: none;">';
                setupFileInput();
                goToStep(1);
            }
            if (e.target.id === 'btn-go-to-step3' || e.target.closest('#btn-go-to-step3')) {
                if (importData.length === 0) {
                    Swal.fire({ icon: 'warning', title: 'Sin datos', text: 'No hay residentes para importar.', confirmButtonColor: '#6366f1' });
                    return;
                }
                updateCounts();
                goToStep(3);
            }
            if (e.target.id === 'btn-back-to-step2' || e.target.closest('#btn-back-to-step2')) {
                goToStep(2);
            }
        });

        // ========================
        // FINAL IMPORT
        // ========================
        document.addEventListener('click', async function(e) {
            if (e.target.id === 'btn-final-import' || e.target.closest('#btn-final-import')) {
                const notify = document.querySelector('input[name="notify_option"]:checked').value === 'all';
                const btn = document.getElementById('btn-final-import');

                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Importando...';

                try {
                    const response = await fetch('<?= base_url("admin/residentes/import/confirm") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                        },
                        body: JSON.stringify({ cache_key: cacheKey, notify: notify, rows: importData })
                    });

                    const result = await response.json();
                    if (response.ok && result.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Importación Exitosa',
                            text: 'Se importaron ' + (result.data.invited || importData.length) + ' residentes correctamente.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => window.location.reload());
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: result.message || 'Error al importar.', confirmButtonColor: '#6366f1' });
                    }
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Error de Red', text: 'No se pudo conectar.', confirmButtonColor: '#6366f1' });
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = 'Importar ' + importData.length + ' residentes';
                }
            }
        });

        // ========================
        // SINGLE INVITE (manual tab)
        // ========================
        async function submitSingleInvite() {
            const btn = document.getElementById('btn-send-invite');
            const phoneEl = document.getElementById('inv-phone');
            const data = {
                name: document.getElementById('inv-first-name').value,
                phone: phoneEl ? phoneEl.value : '',
                email: document.getElementById('inv-email').value,
                unit_id: document.getElementById('invite-unit-id').value,
                role: 'owner'
            };
            if (!data.name || !data.email) {
                Swal.fire({ icon: 'warning', title: 'Campos Incompletos', text: 'Por favor completa los campos obligatorios (*).', confirmButtonColor: '#6366f1' });
                return;
            }
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Enviando...';
            try {
                const response = await fetch('<?= base_url("admin/residentes/invite") ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', '<?= csrf_token() ?>': '<?= csrf_hash() ?>' },
                    body: JSON.stringify(data)
                });
                const result = await response.json();
                if (response.ok && result.success) {
                    Swal.fire({ icon: 'success', title: '¡Éxito!', text: 'Invitación enviada correctamente.', showConfirmButton: false, timer: 1500 }).then(() => window.location.reload());
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: result.message || 'Ocurrió un problema.', confirmButtonColor: '#6366f1' });
                }
            } catch (error) {
                Swal.fire({ icon: 'error', title: 'Error de Red', text: 'Error de conexión.', confirmButtonColor: '#6366f1' });
            } finally {
                btn.disabled = false;
                btn.innerHTML = 'Enviar Invitación';
            }
        }

        // ========================
        // BUTTON DELEGATION
        // ========================
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'btn-send-invite') {
                e.preventDefault();
                submitSingleInvite();
            }
        });

        // ========================
        // INIT
        // ========================
        initDropZone();
        setTimeout(setupFileInput, 100);
        document.addEventListener('DOMContentLoaded', () => { setupFileInput(); initDropZone(); });
    })();
    </script>