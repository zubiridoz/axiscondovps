<!-- HTML and Scripts for Modals -->
<style>
    /* Category Select Buttons */
    .cat-btn {
        border: 1px solid #d0d8e2;
        background: #fff;
        border-radius: 0.5rem;
        padding: 0.8rem 0.5rem;
        text-align: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.4rem;
    }

    .cat-btn.active {
        border-color: #3b82f6;
        background: rgba(59, 130, 246, 0.05);
        color: #3b82f6;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .cat-btn i {
        font-size: 1.5rem;
    }

    .cat-btn span {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .priority-select option[value="low"] {
        color: #10b981;
        font-weight: bold;
    }

    .priority-select option[value="medium"] {
        color: #f59e0b;
        font-weight: bold;
    }

    .priority-select option[value="high"] {
        color: #f97316;
        font-weight: bold;
    }

    .priority-select option[value="critical"] {
        color: #ef4444;
        font-weight: bold;
    }

    .dropzone-area {
        border: 2px dashed #cbd5e1;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        color: #64748b;
        cursor: pointer;
        background: #faf8f9;
    }

    .dropzone-area:hover {
        background: #f1f5f9;
        border-color: #94a3b8;
    }

    .preview-img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 0.4rem;
        border: 1px solid #e2e8f0;
    }

    .preview-video {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 0.4rem;
        border: 1px solid #e2e8f0;
        background: #000;
    }

    .detail-pnl-left {
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        padding: 1.5rem;
        background: #fff;
    }

    .detail-pnl-right {
        background: #fffcf0;
        border: 1px solid #fde68a;
        border-radius: 0.5rem;
        padding: 1.5rem;
    }

    .ticket-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
</style>

<!-- Nuevo Modal Nuevo Ticket -->
<div class="modal fade" id="modalNewTicket" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="bi bi-brightness-high me-2"></i>Nuevo Ticket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pb-4">
                <form id="frmNewTicket" enctype="multipart/form-data">
                    <input type="hidden" name="category" id="nt-category" value="Otros">

                    <label class="form-label fw-semibold text-secondary-emphasis small mb-2">Categoría</label>
                    <div class="row g-2 mb-4">
                        <?php
                        $cats = [
                            ['label' => 'Amenidades', 'icon' => 'bi-building'],
                            ['label' => 'Seguridad', 'icon' => 'bi-shield-check'],
                            ['label' => 'Mantenimiento', 'icon' => 'bi-tools'],
                            ['label' => 'Mascotas', 'icon' => 'bi-bug'], /* icon just for mock */
                            ['label' => 'Ruido', 'icon' => 'bi-volume-up'],
                            ['label' => 'Vecinos', 'icon' => 'bi-people'],
                            ['label' => 'Servicios', 'icon' => 'bi-plugin'],
                            ['label' => 'Otro', 'icon' => 'bi-three-dots']
                        ];
                        foreach ($cats as $c): ?>
                            <div class="col-3 col-md-3">
                                <div class="cat-btn" onclick="selectCategory(this, '<?= $c['label'] ?>')">
                                    <i class="bi <?= $c['icon'] ?>"></i>
                                    <span><?= $c['label'] ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary-emphasis small">Prioridad</label>
                            <select class="form-select priority-select" name="priority">
                                <option value="low">🟢 Bajo</option>
                                <option value="medium" selected>🟡 Medio</option>
                                <option value="high">🟠 Alto</option>
                                <option value="critical">🔴 Crítico</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary-emphasis small">Asignar A
                                (opcional)</label>
                            <select class="form-select assign-select" name="assigned_to" id="nt-assigned">
                                <option value="">Seleccionar...</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-secondary-emphasis small">Fecha Límite</label>
                            <input type="text" class="form-control" name="due_date" id="nt-duedate"
                                placeholder="dd / mm / aaaa">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold text-secondary-emphasis small">Descripción</label>
                        <textarea class="form-control" name="description" rows="3"
                            placeholder="El elevador no se encuentra en funcionamiento..." required></textarea>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary-emphasis small">Etiquetas
                                (opcional)</label>
                            <input type="text" class="form-control" name="tags"
                                placeholder="Ingrese nombre de etiqueta">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-secondary-emphasis small">Ubicación
                                (opcional)</label>
                            <input type="text" class="form-control" name="location"
                                placeholder="Ingrese la ubicación (ej. Área común)">
                        </div>
                    </div>

                    <label class="form-label fw-semibold text-secondary-emphasis small mb-2">Agregar fotos y videos (max
                        7)</label>
                    <div class="dropzone-area mb-2" onclick="document.getElementById('nt-file').click()">
                        <i class="bi bi-upload fs-2 mb-2 d-block"></i>
                        <div class="fw-semibold text-dark">Haz clic para subir fotos o videos</div>
                        <small class="text-secondary">o arrastra y suelta archivos aquí</small>
                        <div class="small mt-2" style="font-size:0.7rem;">Máx. 7 archivos. Imágenes: 5MB. Videos (MP4,
                            MOV, WebM): 100MB</div>
                    </div>
                    <input type="file" id="nt-file" class="d-none" multiple
                        accept="image/*,video/mp4,video/quicktime,video/webm">

                    <div id="nt-preview" class="d-flex flex-wrap gap-2 mt-2"></div>
                </form>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-primary px-4 w-100" id="btnSaveNewTicket">Añadir Ticket</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Report -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" style="overflow:hidden;">
            <!-- Dark Header -->
            <div class="tickets-hero rounded-0 border-0 d-flex justify-content-between align-items-center py-3">
                <div>
                    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <button class="btn btn-sm btn-dark text-white rounded-circle" data-bs-dismiss="modal"
                            style="width:28px;height:28px;padding:0;"><i class="bi bi-arrow-left"></i></button>
                        Reporte <span id="dt-hash">#</span>
                    </h3>
                    <p class="mb-0 text-white-50" id="dt-desc-title" style="margin-left: 36px;"></p>
                </div>
            </div>

            <div class="modal-body bg-light p-4">
                <div class="row g-4">
                    <!-- Left Panel -->
                    <div class="col-lg-8">
                        <div class="detail-pnl-left">
                            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                                <h5 class="fw-bold m-0 text-primary-emphasis"><i
                                        class="bi bi-tag text-primary me-2"></i>Reporte</h5>
                                <span class="badge bg-light text-secondary border"><i class="bi bi-clock me-1"></i>
                                    <span id="dt-date"></span></span>
                            </div>

                            <div class="row mb-4">
                                <div class="col-4">
                                    <small class="text-secondary fw-semibold d-block">REPORTADO POR</small>
                                    <div class="d-flex align-items-center gap-2 mt-1">
                                        <span class="tickets-avatar text-bg-primary" id="dt-avatar"></span>
                                        <span class="fw-medium text-dark" id="dt-reporter"></span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <small class="text-secondary fw-semibold d-block">UNIDAD</small>
                                    <span class="text-dark mt-1 d-block" id="dt-unit"></span>
                                </div>
                                <div class="col-4">
                                    <small class="text-secondary fw-semibold d-block">CATEGORÍA</small>
                                    <span class="text-dark mt-1 d-block" id="dt-cat"></span>
                                </div>
                            </div>

                            <div class="border rounded p-3 bg-light mb-4">
                                <small class="text-secondary fw-semibold d-block mb-1"><i
                                        class="bi bi-file-text me-1"></i>DESCRIPCIÓN</small>
                                <p class="mb-0 text-dark" id="dt-desc"></p>
                            </div>

                            <div>
                                <small class="text-secondary fw-semibold d-block mb-2"><i
                                        class="bi bi-paperclip me-1"></i>ADJUNTOS (<span
                                        id="dt-media-count">0</span>)</small>
                                <div class="d-flex flex-wrap gap-2" id="dt-media-container"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Panel -->
                    <div class="col-lg-4">
                        <div class="detail-pnl-right mb-3" id="actions-panel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="fw-semibold text-warning-emphasis"><i
                                        class="bi bi-exclamation-circle text-warning me-1"></i> Pendiente de
                                    Clasificación</span>
                                <small class="text-danger fw-semibold" style="font-size:0.7rem;">Acción
                                    Requerida</small>
                            </div>

                            <form id="frmUpdateDetails">
                                <input type="hidden" name="ticket_id" id="dt-id">
                                <div class="row g-2 mb-3">
                                    <div class="col-5">
                                        <label class="form-label small text-secondary-emphasis fw-medium">Prioridad
                                            P</label>
                                        <select class="form-select form-select-sm priority-select" name="priority"
                                            id="dt-prio">
                                            <option value="low">🟢 Bajo</option>
                                            <option value="medium">🟡 Medio</option>
                                            <option value="high">🟠 Alto</option>
                                            <option value="critical">🔴 Crítico</option>
                                        </select>
                                    </div>
                                    <div class="col-7">
                                        <label class="form-label small text-secondary-emphasis fw-medium">Asignar a:
                                            A</label>
                                        <select class="form-select form-select-sm assign-select" name="assigned_to"
                                            id="dt-assign">
                                            <option value="">Seleccionar...</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="button"
                                        class="btn btn-dark w-100 fw-medium d-flex justify-content-center align-items-center gap-2"
                                        id="btnStartWork">
                                        <i class="bi bi-play-circle"></i> Comenzar Trabajo
                                    </button>
                                </div>
                                <div id="divSaveCancel" class="d-none d-flex gap-2 mt-2">
                                    <button type="button" class="btn btn-light border flex-fill text-muted fw-bold"
                                        id="btnCancelEdit">Cancelar</button>
                                    <button type="button" class="btn btn-primary flex-fill fw-bold"
                                        id="btnSaveEdit">Guardar</button>
                                </div>
                            </form>
                        </div>

                        <div class="detail-pnl-left mt-3">
                            <small class="text-secondary fw-semibold d-block mb-1"><i
                                    class="bi bi-clock-history me-1"></i>Historial de Reportes</small>
                            <p class="mb-0 text-muted small" id="dt-history-text">Primer reporte de este residente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast UI -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 11000">
    <div id="premiumToast" class="toast align-items-center text-bg-primary border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2" id="premiumToastBody">
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>
</div>

<script>
    // UI functions
    function selectCategory(el, catName) {
        document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('nt-category').value = catName;
    }

    function showToast(msg, type = 'primary') {
        const t = document.getElementById('premiumToast');
        t.className = `toast align-items-center text-white border-0 text-bg-${type}`;
        document.getElementById('premiumToastBody').innerHTML = `<i class="bi bi-info-circle me-1"></i> ${msg}`;
        new bootstrap.Toast(t).show();
    }

    // Media upload logic
    let filesToUpload = [];
    document.getElementById('nt-file').addEventListener('change', function (e) {
        const incomingFiles = Array.from(e.target.files);

        // validate lengths and sizes
        for (let f of incomingFiles) {
            if (filesToUpload.length >= 7) {
                alert('Máximo 7 archivos permitidos.');
                break;
            }
            if (f.type.startsWith('video/') && f.size > 100 * 1024 * 1024) {
                alert('El video ' + f.name + ' supera los 100MB.');
                continue;
            }
            if (f.type.startsWith('image/') && f.size > 5 * 1024 * 1024) {
                alert('La imagen ' + f.name + ' supera los 5MB.');
                continue;
            }
            filesToUpload.push(f);
        }

        // clear input so changing same file again works
        e.target.value = '';
        renderPreviews();
    });

    function renderPreviews() {
        const container = document.getElementById('nt-preview');
        container.innerHTML = '';
        filesToUpload.forEach((f, index) => {
            const div = document.createElement('div');
            div.className = 'position-relative';

            const isVideo = f.type.startsWith('video/');
            const url = URL.createObjectURL(f);

            let mediaHtml = '';
            if (isVideo) {
                mediaHtml = `<video src="${url}" class="preview-video"></video>`;
            } else {
                mediaHtml = `<img src="${url}" class="preview-img">`;
            }

            div.innerHTML = `
            ${mediaHtml}
            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle" style="width:24px;height:24px;padding:0" onclick="removeFile(${index})"><i class="bi bi-x"></i></button>
        `;
            container.appendChild(div);
        });
    }
    function removeFile(index) {
        filesToUpload.splice(index, 1);
        renderPreviews();
    }

    // Data Fetching
    function loadAssignees() {
        fetch('<?= base_url("admin/tickets/assignees") ?>')
            .then(r => r.json())
            .then(res => {
                if (res.status === 200) {
                    let html = '<option value="">Seleccionar...</option>';
                    if (res.admins.length > 0) {
                        html += '<optgroup label="Administradores">';
                        res.admins.forEach(a => html += `<option value="user_${a.id}">${a.first_name} ${a.last_name}</option>`);
                        html += '</optgroup>';
                    }
                    if (res.staff.length > 0) {
                        html += '<optgroup label="Staff">';
                        res.staff.forEach(s => html += `<option value="staff_${s.id}">${s.first_name} ${s.last_name} (${s.staff_type})</option>`);
                        html += '</optgroup>';
                    }
                    document.querySelectorAll('.assign-select').forEach(sel => sel.innerHTML = html);
                }
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadAssignees();

        // Initialize flatpickr on due_date with premium style
        if (typeof flatpickr !== 'undefined') {
            flatpickr("#nt-duedate", {
                dateFormat: "Y-m-d",
                altInput: true,
                altFormat: "d / m / Y",
                locale: "es",
                minDate: "today"
            });
        } else {
            // Load flatpickr dynamically if not present
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css';
            document.head.appendChild(link);

            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/flatpickr';
            script.onload = () => {
                const langScript = document.createElement('script');
                langScript.src = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js';
                langScript.onload = () => {
                    flatpickr("#nt-duedate", {
                        dateFormat: "Y-m-d",
                        altInput: true,
                        altFormat: "d / m / Y",
                        locale: "es",
                        minDate: "today"
                    });
                };
                document.head.appendChild(langScript);
            };
            document.head.appendChild(script);
        }

        const frm = document.getElementById('frmNewTicket');
        document.getElementById('btnSaveNewTicket').addEventListener('click', () => {
            if (!frm.checkValidity()) { frm.reportValidity(); return; }

            const btn = document.getElementById('btnSaveNewTicket');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';

            const fd = new FormData(frm);
            filesToUpload.forEach(f => fd.append('media[]', f));

            fetch('<?= base_url("admin/tickets/crear") ?>', {
                method: 'POST', body: fd
            }).then(r => r.json()).then(res => {
                if (res.status === 201) {
                    bootstrap.Modal.getInstance(document.getElementById('modalNewTicket')).hide();
                    showToast("Tu reporte ha sido enviado exitosamente.", "primary");
                    setTimeout(() => location.reload(), 1500);
                } else {
                    alert(res.error || 'Error al guardar');
                    btn.disabled = false;
                    btn.innerHTML = 'Añadir Ticket';
                }
            });
        });

        // Panel detail logic
        const dtPrio = document.getElementById('dt-prio');
        const dtAssign = document.getElementById('dt-assign');
        const divSaveCancel = document.getElementById('divSaveCancel');
        const btnStartWork = document.getElementById('btnStartWork');

        // Show cancel/save when editing the selectors
        const showSaveCancel = () => { divSaveCancel.classList.remove('d-none'); btnStartWork.classList.add('d-none'); };
        dtPrio.addEventListener('change', showSaveCancel);
        dtAssign.addEventListener('change', showSaveCancel);

        document.getElementById('btnCancelEdit').addEventListener('click', () => {
            // reload original values or just hide
            divSaveCancel.classList.add('d-none');
            btnStartWork.classList.remove('d-none');
        });

        document.getElementById('btnSaveEdit').addEventListener('click', () => saveDetails(false));
        btnStartWork.addEventListener('click', () => saveDetails(true));
    });

    function openTicketDetail(ticket) {
        document.getElementById('dt-hash').innerText = ticket.hash;
        document.getElementById('dt-desc-title').innerText = ticket.subject;
        document.getElementById('dt-date').innerText = ticket.created_at_label;
        document.getElementById('dt-avatar').innerText = ticket.reporter_initials;
        document.getElementById('dt-reporter').innerText = ticket.reporter;
        document.getElementById('dt-unit').innerText = ticket.unit_name || 'Sin unidad';
        document.getElementById('dt-cat').innerText = ticket.category;
        document.getElementById('dt-desc').innerText = ticket.description;

        // Panel assignments
        document.getElementById('dt-prio').value = ticket.priority_value;
        if (ticket.assigned_to_type && ticket.assigned_to_id) {
            document.getElementById('dt-assign').value = `${ticket.assigned_to_type}_${ticket.assigned_to_id}`;
        } else {
            document.getElementById('dt-assign').value = "";
        }
        document.getElementById('dt-id').value = ticket.id;

        // Reset buttons
        document.getElementById('divSaveCancel').classList.add('d-none');
        document.getElementById('btnStartWork').classList.remove('d-none');

        // Switch states
        const panel = document.getElementById('actions-panel');
        if (ticket.status === 'in_progress' || ticket.status === 'resolved' || ticket.status === 'closed') {
            panel.className = "detail-pnl-right mb-3 bg-light border";
            panel.querySelector('span.fw-semibold').innerHTML = `<span class="badge ${ticket.status_class}">${ticket.status_label}</span>`;
            panel.querySelector('small').innerText = '';
            document.getElementById('btnStartWork').style.display = 'none';
            document.getElementById('btnStartWork').classList.remove('d-flex');
        } else {
            panel.className = "detail-pnl-right mb-3";
            panel.querySelector('span.fw-semibold').innerHTML = `<i class="bi bi-exclamation-circle text-warning me-1"></i> Pendiente de Clasificación`;
            panel.querySelector('small').innerText = 'Acción Requerida';
            document.getElementById('btnStartWork').style.display = 'flex';
            document.getElementById('btnStartWork').classList.add('d-flex');
        }

        // Media
        const mc = document.getElementById('dt-media-container');
        mc.innerHTML = '';
        document.getElementById('dt-media-count').innerText = ticket.media_urls ? ticket.media_urls.length : 0;

        if (ticket.media_urls) {
            ticket.media_urls.forEach(url => {
                const isVideo = url.endsWith('.mp4') || url.endsWith('.mov') || url.endsWith('.webm');
                const fullUrl = '<?= base_url() ?>/' + url;
                if (isVideo) {
                    mc.innerHTML += `<video src="${fullUrl}" controls class="preview-video"></video>`;
                } else {
                    mc.innerHTML += `<a href="${fullUrl}" target="_blank"><img src="${fullUrl}" class="preview-img"></a>`;
                }
            });
        }

        new bootstrap.Modal(document.getElementById('modalDetail')).show();
    }

    function saveDetails(startWork = false) {
        const id = document.getElementById('dt-id').value;
        const fd = new FormData(document.getElementById('frmUpdateDetails'));
        if (startWork) fd.append('status', 'in_progress');

        fetch('<?= base_url("admin/tickets/update-details/") ?>' + id, { method: 'POST', body: fd })
            .then(r => r.json())
            .then(res => {
                if (res.status === 200) {
                    showToast("Actualizado correctamente", "success");
                    setTimeout(() => location.reload(), 1000);
                } else {
                    alert(res.error || 'Error al guardar');
                }
            });
    }
</script>