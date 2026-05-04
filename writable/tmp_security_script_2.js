
    let fpStart, fpEnd;

    // Tom Select initialization
    new TomSelect("#select-unidad", {
        create: false,
        sortField: { field: "text", direction: "asc" }
    });

    // Visibility toggles
    function toggleVehicleField() {
        const isAuto = document.getElementById('vveh_auto').checked;
        const isMoto = document.getElementById('vveh_moto').checked;
        const plateWrapper = document.getElementById('qr_plate_wrapper');

        if (isAuto || isMoto) {
            plateWrapper.style.display = 'block';
        } else {
            plateWrapper.style.display = 'none';
        }
    }

    function toggleqrTimeField() {
        const isTemporal = document.getElementById('vtime_range').checked;
        const datesRow = document.getElementById('qr_dates_row');

        if (isTemporal) {
            datesRow.innerHTML = `
                <div class="col-md-6">
                    <label class="form-label small fw-medium text-dark">Fecha inicio</label>
                    <div class="icon-input-wrapper">
                        <i class="bi bi-calendar" style="z-index:10"></i>
                        <input type="text" id="f_start" class="form-control form-control-custom bg-white" placeholder="Selecciona..." style="border-radius: 6px;">
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-medium text-dark">Fecha fin</label>
                    <div class="icon-input-wrapper">
                        <i class="bi bi-calendar" style="z-index:10"></i>
                        <input type="text" id="f_end" class="form-control form-control-custom bg-white" placeholder="Selecciona..." style="border-radius: 6px;">
                    </div>
                </div>
            `;
            // Initialize flatpickr on the new inputs
            fpStart = flatpickr("#f_start", { locale: "es", altInput: true, altFormat: "d \\d\\e F \\d\\e Y", dateFormat: "Y-m-d", defaultDate: new Date() });
            let tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            fpEnd = flatpickr("#f_end", { locale: "es", altInput: true, altFormat: "d \\d\\e F \\d\\e Y", dateFormat: "Y-m-d", defaultDate: tomorrow });
        } else {
            datesRow.innerHTML = `
                <div class="col-md-12">
                    <label class="form-label small fw-medium text-dark">Fecha de entrada</label>
                    <div class="icon-input-wrapper">
                        <i class="bi bi-calendar" style="z-index:10"></i>
                        <input type="text" id="f_single" class="form-control form-control-custom bg-white" placeholder="Selecciona..." style="border-radius: 6px;">
                    </div>
                </div>
            `;
            fpStart = flatpickr("#f_single", { locale: "es", altInput: true, altFormat: "d \\d\\e F \\d\\e Y", dateFormat: "Y-m-d", defaultDate: new Date() });
        }
    }

    // Modal Events to bind the dynamically generated QR button
    const qrModalObj = document.getElementById('newQrModal');
    if (qrModalObj) {
        qrModalObj.addEventListener('show.bs.modal', function () {
            toggleVehicleField();
            toggleqrTimeField();
        });
    }

    // Attach click listener to dynamic header buttons using event delegation
    document.body.addEventListener('click', function (e) {
        // Find if they clicked the strictly generated button
        if (e.target.closest('#btn-trigger-qr')) {
            const modal = new bootstrap.Modal(document.getElementById('newQrModal'));
            modal.show();
        }
    });

    // Handle AJAX Submission
    document.getElementById('btn-save-qr').addEventListener('click', function () {
        const visitType = document.querySelector('input[name="qr_visit_type"]:checked').value;
        const vehicle = document.querySelector('input[name="qr_vehicle"]:checked').value;
        const timeType = document.querySelector('input[name="qr_time_type"]:checked').value;

        let validFrom = '';
        let validUntil = '';
        if (timeType === 'Una entrada') {
            validFrom = document.getElementById('f_single').value;
        } else {
            validFrom = document.getElementById('f_start').value;
            validUntil = document.getElementById('f_end').value;
        }

        const visitorName = document.querySelector('input[placeholder="Ingrese el nombre"]').value;
        const unitId = document.getElementById('select-unidad').value;
        const vehiclePlate = document.getElementById('vehiculo_placa').value;

        if (!visitorName || !validFrom) {
            Swal.fire('AtenciÃ³n', 'Nombre del visitante y fecha son obligatorios', 'warning');
            return;
        }

        // Bloquear boton
        const btnSave = this;
        const originalText = btnSave.innerHTML;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Generando...';
        btnSave.disabled = true;

        const formData = new FormData();
        formData.append('qr_visit_type', visitType); // Mapped to visit_type in controller? No wait!
        formData.append('visit_type', visitType);
        formData.append('vehicle_type', vehicle);
        formData.append('qr_time_type', timeType);
        formData.append('valid_from', validFrom);
        formData.append('valid_until', validUntil);
        formData.append('visitor_name', visitorName);
        formData.append('unit_id', unitId);
        formData.append('vehicle_plate', vehiclePlate);

        fetch('<?= base_url('admin/seguridad/generar-qr') ?>', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;

                if (data.status === 201) {
                    bootstrap.Modal.getInstance(document.getElementById('newQrModal')).hide();

                    Swal.fire({
                        title: 'QR Generado',
                        text: 'El acceso ha sido registrado y el pase virtual creado.',
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-qr-code"></i> Ver Pase',
                        cancelButtonText: 'Cerrar',
                        confirmButtonColor: '#3b4d63'
                    }).then((res) => {
                        if (res.isConfirmed) {
                            window.open(data.url, '_blank');
                        }
                    });

                    // Limpiar formulario manual o `.reset()` si existiera un form
                    document.querySelector('input[placeholder="Ingrese el nombre"]').value = '';
                    document.getElementById('vehiculo_placa').value = '';
                } else {
                    Swal.fire('Error', data.message || 'Error al generar', 'error');
                }
            })
            .catch(error => {
                btnSave.innerHTML = originalText;
                btnSave.disabled = false;
                Swal.fire('Error', 'Problema de red, intente de nuevo.', 'error');
            });
    });

    // Filtros de Data Table QR (Frontend Side)
    function applyQrFilters() {
        const searchVal = document.getElementById('qr-search-input').value.toLowerCase();
        const typeVal = document.getElementById('qr-filter-type').value;
        const purposeVal = document.getElementById('qr-filter-purpose').value;
        const vehicleVal = document.getElementById('qr-filter-vehicle').value;
        const statusVal = document.getElementById('qr-filter-status').value;

        const rows = document.querySelectorAll('.table-qr-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowSearch = row.getAttribute('data-search');
            const rowType = row.getAttribute('data-type');
            const rowPurpose = row.getAttribute('data-purpose');
            const rowVehicle = row.getAttribute('data-vehicle');
            const rowStatus = row.getAttribute('data-status'); // "Activo" o "Expirado"

            let match = true;
            if (searchVal && !rowSearch.includes(searchVal)) match = false;
            if (typeVal !== 'Todos' && rowType !== typeVal) match = false;
            if (purposeVal !== 'Todos' && rowPurpose !== purposeVal) match = false;
            if (vehicleVal !== 'Todos' && rowVehicle !== vehicleVal) match = false;

            if (statusVal !== 'Todos') {
                if (statusVal === 'Activos' && rowStatus !== 'Activo') match = false;
                if (statusVal === 'Expirados' && rowStatus !== 'Expirado') match = false;
            }

            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        const emptyState = document.getElementById('qr-empty-state');
        const dataTable = document.getElementById('qrDataTable');
        if (dataTable) {
            if (visibleCount === 0) {
                dataTable.closest('.table-responsive').classList.add('d-none');
                if (emptyState) emptyState.classList.remove('d-none');
            } else {
                dataTable.closest('.table-responsive').classList.remove('d-none');
                if (emptyState) emptyState.classList.add('d-none');
            }
        }
    }

    if (document.getElementById('qr-search-input')) {
        document.getElementById('qr-search-input').addEventListener('input', applyQrFilters);
        document.getElementById('qr-filter-type').addEventListener('change', applyQrFilters);
        document.getElementById('qr-filter-purpose').addEventListener('change', applyQrFilters);
        document.getElementById('qr-filter-vehicle').addEventListener('change', applyQrFilters);
        document.getElementById('qr-filter-status').addEventListener('change', applyQrFilters);
    }

    // QR Detail Modal Trigger
    window.openQrDetail = function (rowElement) {
        const data = JSON.parse(rowElement.getAttribute('data-json'));
        const modalEl = document.getElementById('qrDetailModal');
        const modalContent = document.getElementById('qr-modal-body-content');

        // Formateo de fechas para que diga "20 de marzo de 2026"
        const meses = { 'Jan': 'enero', 'Feb': 'febrero', 'Mar': 'marzo', 'Apr': 'abril', 'May': 'mayo', 'Jun': 'junio', 'Jul': 'julio', 'Aug': 'agosto', 'Sep': 'septiembre', 'Oct': 'octubre', 'Nov': 'noviembre', 'Dec': 'diciembre' };

        let dateFromFormatted = data.valid_from.slice(0, -6).replace(/ (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) /, (match, p1) => ` de ${meses[p1]} de `);
        let timeFrom = data.valid_from.slice(-5);
        let validFromFull = `${dateFromFormatted} Â· ${timeFrom}`;

        let dateUntilFormatted = data.valid_until.slice(0, -6).replace(/ (Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec) /, (match, p1) => ` de ${meses[p1]} de `);
        let timeUntil = data.valid_until.slice(-5);
        let validUntilFull = `${dateUntilFormatted} Â· ${timeUntil}`;

        let statusBadge = data.status === 'Activo' ? '<span class="qr-badge-active">Activo</span>' : '<span class="qr-badge-expired">Expirado</span>';
        document.getElementById('qr-modal-status-badge').innerHTML = statusBadge;

        const html = `
            <!-- Wrapper para Exportar a Imagen -->
            <div id="qr-export-wrapper" style="background-color: #ffffff; width: 100%; max-width: 420px; margin: 0 auto; position: relative; font-family: -apple-system, BlinkMacSystemFont, 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
                
                <!-- TOP BANNER AxisCondo -->
                <div style="background-color: #1e3a5f; color: #ffffff; text-align: center; padding: 10px 0; font-weight: 800; font-size: 0.85rem; letter-spacing: 1.5px; text-transform: uppercase;">
                    AXISCONDO
                </div>
                
                <!-- Cuerpo de la Tarjeta -->
                <div style="padding: 24px 28px;">
                    
                    <!-- Header Secundario -->
                    <div class="d-flex align-items-center mb-4">
                        <div style="width: 46px; height: 46px; border-radius: 50%; border: 1px solid #cbd5e1; display: flex; align-items: center; justify-content: center; margin-right: 14px;">
                            <i class="bi bi-buildings" style="font-size: 1.4rem; color: #1e3a5f;"></i>
                        </div>
                        <div style="font-size: 1.4rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px;">Acceso Autorizado</div>
                    </div>

                    <!-- CÃ³digo QR Centrado -->
                    <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px; margin-bottom: 28px; text-align: center; background: #ffffff;">
                        <img src="<?= base_url('qr') ?>/${data.token}?qr_only=1" alt="QR Code" crossorigin="anonymous" class="img-fluid" onerror="this.onerror=null; this.src='https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' + encodeURIComponent('<?= base_url('qr') ?>/${data.token}');" style="max-width: 250px; width: 100%;">
                    </div>

                    <!-- Datos Estructurados -->
                    <div style="text-align: left;">
                         <!-- Botonera Externa al Canvas -->
            <div class="px-4 pb-4 pt-3 mx-auto" style="max-width: 420px;">
                <button class="btn w-100 fw-medium bg-white mb-3" style="border: 1px solid #cbd5e1; border-radius: 8px; padding: 12px; color: #1e3a5f; font-size: 0.95rem;" onclick="downloadQrCard('${data.token}', '${data.visitor_name}')">
                    <i class="bi bi-download me-2" style="color: #64748b; font-size: 1.1rem; vertical-align: text-bottom;"></i> Descargar CÃ³digo QR
                </button>
              
            </div>
                        <!-- PROPIETARIO -->
                        <div style="font-size: 1.05rem; font-weight: 800; color: #334155; margin-bottom: 12px; text-transform: uppercase;">PROPIETARIO</div>
                        <div style="margin-bottom: 6px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">ðŸ¡</span> Unidad ${data.unit_number}
                        </div>
                        <div style="margin-bottom: 24px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">ðŸ‘¤</span> Visitante: ${data.visitor_name}
                        </div>

                        <!-- EVENTO -->
                        <div style="font-size: 1.05rem; font-weight: 800; color: #334155; margin-bottom: 12px; text-transform: uppercase;">EVENTO</div>
                        <div style="margin-bottom: 6px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">ðŸŽ‰</span> ${data.visit_type}
                        </div>
                        <div style="margin-bottom: 24px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">ðŸš—</span> VehÃ­culo: ${data.vehicle_type}
                        </div>

                        <!-- FECHAS DE ACCESO -->
                        <div style="font-size: 1.05rem; font-weight: 800; color: #334155; margin-bottom: 12px; text-transform: uppercase;">FECHAS DE ACCESO</div>
                        <div style="margin-bottom: 6px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">ðŸ“…</span> Entrada: ${validFromFull}
                        </div>
                        <div style="margin-bottom: 6px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">ðŸ“…</span> Salida: ${validUntilFull}
                        </div>
                        <div style="margin-bottom: 12px; display: flex; align-items: center; font-size: 1.15rem; color: #0f172a; font-weight: 600;">
                            <span style="font-size: 1.2rem; margin-right: 10px;">ðŸŽ«</span> Tipo: ${data.time_type}
                        </div>
                    </div>
                </div>
            </div>
              <div class="text-end">
                    <button type="button" class="btn btn-light border px-4 py-2 font-sans" data-bs-dismiss="modal" style="border-radius: 8px; color:#3F67AC; border-color:#e2e8f0; font-weight: 500; font-size: 0.85rem;"><i class="bi bi-x"></i> Cerrar</button>
                </div>
           
        `;

        modalContent.innerHTML = html;
        const bsModal = new bootstrap.Modal(modalEl);
        bsModal.show();
    };

    window.downloadQrCard = function (token, name) {
        const element = document.getElementById('qr-export-wrapper');

        // Bloquear boton momentaneamente
        const btn = event.currentTarget;
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generando...';
        btn.disabled = true;

        html2canvas(element, { backgroundColor: '#f8fafc', scale: 2, useCORS: true, allowTaint: false }).then(canvas => {
            const link = document.createElement('a');
            link.download = `QR_Acceso_${name.replace(/\s+/g, '_')}.png`;
            link.href = canvas.toDataURL("image/png");
            link.click();

            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }).catch(err => {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
            Swal.fire('Error', 'No se pudo generar la imagen.', 'error');
        });
    };

    // ==========================================
    // Filtros de Entradas / Salidas
    // ==========================================
    function applyAccessFilters() {
        const searchVal = document.getElementById('acc-search-input').value.toLowerCase();
        const purposeVal = document.getElementById('acc-filter-purpose').value;
        const statusVal = document.getElementById('acc-filter-status').value;
        const vehicleVal = document.getElementById('acc-filter-vehicle').value;

        const rows = document.querySelectorAll('.table-access-row');
        let visibleCount = 0;

        rows.forEach(row => {
            const rowSearch = row.getAttribute('data-search');
            let rowPurpose = row.getAttribute('data-purpose');
            const rowStatus = row.getAttribute('data-status');
            let rowVehicle = row.getAttribute('data-vehicle');
            
            // Normalize generic matching for the dropdown 
            if (rowPurpose === '' || rowPurpose === null) rowPurpose = 'Visita';
            if (rowVehicle === '' || rowVehicle === null) rowVehicle = 'Sin vehÃ­culo';

            let match = true;
            if (searchVal && !rowSearch.includes(searchVal)) match = false;
            if (purposeVal !== 'Todos' && !rowPurpose.includes(purposeVal)) {
                // Allow "Proveedor/Servicio" to match "Proveedor de servicios"
                if (purposeVal === 'Proveedor de servicios' && rowPurpose !== 'Proveedor de servicios') match = false;
                else if (purposeVal !== 'Proveedor de servicios' && rowPurpose !== purposeVal) match = false;
            }
            if (statusVal !== 'Todos' && rowStatus !== statusVal) match = false;
            if (vehicleVal !== 'Todos' && rowVehicle !== vehicleVal) match = false;

            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });
        
        // Esconder tabla si no hay resultados visuales (opcional, dejamos solo vacia la tabla visualmente)
    }

    if (document.getElementById('acc-search-input')) {
        document.getElementById('acc-search-input').addEventListener('input', applyAccessFilters);
        document.getElementById('acc-filter-purpose').addEventListener('change', applyAccessFilters);
        document.getElementById('acc-filter-status').addEventListener('change', applyAccessFilters);
        document.getElementById('acc-filter-vehicle').addEventListener('change', applyAccessFilters);
    }

    // ==========================================
    // Modal detalle para Entradas / Salidas
    // ==========================================
    const accessDetailModalEl = document.getElementById('accessDetailModal');
    const accessDetailModal = accessDetailModalEl ? new bootstrap.Modal(accessDetailModalEl) : null;
    const accessBaseUrl = '<?= rtrim(base_url(), '/') ?>';

    function resolveAccessMediaUrl(rawPath) {
        if (!rawPath) return '';
        if (/^https?:\/\//i.test(rawPath)) return rawPath;
        return `${accessBaseUrl}/${String(rawPath).replace(/^\/+/, '')}`;
    }

    function setAccessText(id, value, fallback = '-') {
        const el = document.getElementById(id);
        if (!el) return;
        const normalized = String(value ?? '').trim();
        el.textContent = normalized !== '' ? normalized : fallback;
    }

    function setAccessPhoto(cardId, imgId, linkId, rawPath) {
        const card = document.getElementById(cardId);
        const img = document.getElementById(imgId);
        const link = document.getElementById(linkId);
        if (!card || !img || !link) return false;

        const mediaUrl = resolveAccessMediaUrl(rawPath);
        if (!mediaUrl) {
            card.classList.add('d-none');
            img.removeAttribute('src');
            link.removeAttribute('href');
            return false;
        }

        card.classList.remove('d-none');
        img.src = mediaUrl;
        link.href = mediaUrl;
        img.onerror = () => {
            card.classList.add('d-none');
        };
        return true;
    }

    function openAccessDetailModal(row) {
        if (!accessDetailModal || !row) return;

        const isInside = row.dataset.status === 'adentro';
        const badge = document.getElementById('access-detail-badge');
        if (badge) {
            badge.className = `access-detail-badge ${isInside ? 'adentro' : 'salio'}`;
            badge.textContent = isInside ? 'Actualmente adentro' : 'Salida registrada';
        }

        setAccessText('access-detail-entry-id', `#${row.dataset.entryId || '-'}`);
        setAccessText('access-detail-visitor', row.dataset.visitor || '-');
        setAccessText('access-detail-purpose', row.dataset.purpose || 'Visita');
        setAccessText('access-detail-unit', row.dataset.unit || 'N/A');
        setAccessText('access-detail-vehicle', row.dataset.vehicle || 'Sin vehiculo');
        setAccessText('access-detail-plate', row.dataset.plate || '-');
        setAccessText('access-detail-gate', row.dataset.gate || 'Caseta Principal');

        const entryDate = row.dataset.entryDate || '-';
        const entryTime = row.dataset.entryTime || '-';
        setAccessText('access-detail-entry-time', `${entryDate} Â· ${entryTime}`);

        const exitTime = row.dataset.exitTime && row.dataset.exitTime !== 'Active'
            ? row.dataset.exitTime
            : 'Sin salida registrada';
        setAccessText('access-detail-exit-time', exitTime);

        const notes = row.dataset.notes || '';
        setAccessText('access-detail-notes', notes, 'Sin notas registradas.');

        const hasIdPhoto = setAccessPhoto('access-photo-id-card', 'access-photo-id-img', 'access-photo-id-link', row.dataset.photoId);
        const hasPlatePhoto = setAccessPhoto('access-photo-plate-card', 'access-photo-plate-img', 'access-photo-plate-link', row.dataset.photoPlate);
        const noPhoto = document.getElementById('access-no-photo-message');
        if (noPhoto) {
            noPhoto.classList.toggle('d-none', hasIdPhoto || hasPlatePhoto);
        }

        accessDetailModal.show();
    }

    document.querySelectorAll('.table-access-row').forEach((row) => {
        row.addEventListener('click', () => openAccessDetailModal(row));
        row.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                openAccessDetailModal(row);
            }
        });
    });

    if (accessDetailModalEl) {
        accessDetailModalEl.addEventListener('shown.bs.modal', () => {
            const backdrops = document.querySelectorAll('.modal-backdrop');
            const lastBackdrop = backdrops[backdrops.length - 1];
            if (lastBackdrop) {
                lastBackdrop.classList.add('access-detail-backdrop');
            }
        });

        accessDetailModalEl.addEventListener('hidden.bs.modal', () => {
            document.querySelectorAll('.modal-backdrop.access-detail-backdrop').forEach((el) => {
                el.classList.remove('access-detail-backdrop');
            });
        });
    }
    
    // ==========================================
    // Flatpickr para Rango de Fechas
    // ==========================================

