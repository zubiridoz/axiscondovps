
    document.addEventListener('DOMContentLoaded', function () {
        const tabEls = document.querySelectorAll('button[data-bs-toggle="pill"]');
        const headerActionArea = document.getElementById('header-action-area');
        const tabControlsRight = document.getElementById('tab-controls-right');

        const startDatePHP = '<?= $startDate ?? date("Y-m-d") ?>';
        const endDatePHP = '<?= $endDate ?? date("Y-m-d") ?>';
        const qrStartDatePHP = '<?= $qrStartDate ?? date("Y-m-d") ?>';
        const qrEndDatePHP = '<?= $qrEndDate ?? date("Y-m-d") ?>';

        function updateActionButtons(targetId) {
            // Clean dynamic visibility
            document.querySelectorAll('.tab-dynamic-control').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.header-dynamic-control').forEach(el => el.classList.add('d-none'));
            
            // Activate current control panels natively
            const tName = targetId.replace('#', '');
            const rightCtrl = document.getElementById('ctrl-' + tName);
            if(rightCtrl) rightCtrl.classList.remove('d-none');
            
            const headerCtrl = document.getElementById('header-btn-' + tName);
            if(headerCtrl) headerCtrl.classList.remove('d-none');
        }

        // Initialize Native Calendar Instances globally (Only ONCE via flatpickr directly to elements)
        const accInput = document.getElementById("acc-date-picker");
        const accDatePickerInstance = accInput ? flatpickr(accInput, {
            locale: "es",
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: (startDatePHP === endDatePHP) ? startDatePHP : [startDatePHP, endDatePHP],
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const e = flatpickr.formatDate(selectedDates[1], "Y-m-d");
                    const url = new URL(window.location.href);
                    url.searchParams.set('start', s);
                    url.searchParams.set('end', e);
                    url.hash = 'v-entradas';
                    window.location.href = url.toString();
                } else if (selectedDates.length === 1 && this.isOpen === false) {
                     const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                     const url = new URL(window.location.href);
                     url.searchParams.set('start', s);
                     url.searchParams.set('end', s);
                     url.hash = 'v-entradas';
                     window.location.href = url.toString();
                }
            },
            onClose: function(selectedDates) {
                 if (selectedDates.length === 1) {
                     const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                     const url = new URL(window.location.href);
                     url.searchParams.set('start', s);
                     url.searchParams.set('end', s);
                     url.hash = 'v-entradas';
                     window.location.href = url.toString();
                 }
            }
        }) : null;

        document.getElementById('acc-date-wrapper')?.addEventListener('click', (e) => {
            e.stopPropagation();
            if(accDatePickerInstance) accDatePickerInstance.open();
        });

        document.getElementById('btn-acc-prev-day')?.addEventListener('click', () => {
            let d = new Date(startDatePHP + "T12:00:00Z");
            d.setUTCDate(d.getUTCDate() - 1);
            let s = d.toISOString().split('T')[0];
            const url = new URL(window.location.href);
            url.searchParams.set('start', s);
            url.searchParams.set('end', s);
            url.hash = 'v-entradas';
            window.location.href = url.toString();
        });

        document.getElementById('btn-acc-next-day')?.addEventListener('click', () => {
            let d = new Date(endDatePHP + "T12:00:00Z");
            d.setUTCDate(d.getUTCDate() + 1);
            let s = d.toISOString().split('T')[0];
            const url = new URL(window.location.href);
            url.searchParams.set('start', s);
            url.searchParams.set('end', s);
            url.hash = 'v-entradas';
            window.location.href = url.toString();
        });

        // Initialize Native QR Calendar
        const qrInput = document.getElementById("qr-date-picker");
        const qrDatePickerInstance = qrInput ? flatpickr(qrInput, {
            locale: "es",
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: (qrStartDatePHP === qrEndDatePHP) ? qrStartDatePHP : [qrStartDatePHP, qrEndDatePHP],
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                    const e = flatpickr.formatDate(selectedDates[1], "Y-m-d");
                    const url = new URL(window.location.href);
                    url.searchParams.set('qstart', s);
                    url.searchParams.set('qend', e);
                    url.hash = 'v-qr';
                    window.location.href = url.toString();
                } else if (selectedDates.length === 1 && this.isOpen === false) {
                     const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                     const url = new URL(window.location.href);
                     url.searchParams.set('qstart', s);
                     url.searchParams.set('qend', s);
                     url.hash = 'v-qr';
                     window.location.href = url.toString();
                }
            },
            onClose: function(selectedDates) {
                 if (selectedDates.length === 1) {
                     const s = flatpickr.formatDate(selectedDates[0], "Y-m-d");
                     const url = new URL(window.location.href);
                     url.searchParams.set('qstart', s);
                     url.searchParams.set('qend', s);
                     url.hash = 'v-qr';
                     window.location.href = url.toString();
                 }
            }
        }) : null;

        document.getElementById('qr-date-wrapper')?.addEventListener('click', (e) => {
            e.stopPropagation();
            if(qrDatePickerInstance) qrDatePickerInstance.open();
        });

        document.getElementById('btn-qr-prev-day')?.addEventListener('click', () => {
            let d = new Date(qrStartDatePHP + "T12:00:00Z");
            d.setUTCDate(d.getUTCDate() - 1);
            let s = d.toISOString().split('T')[0];
            const url = new URL(window.location.href);
            url.searchParams.set('qstart', s);
            url.searchParams.set('qend', s);
            url.hash = 'v-qr';
            window.location.href = url.toString();
        });

        document.getElementById('btn-qr-next-day')?.addEventListener('click', () => {
            let d = new Date(qrEndDatePHP + "T12:00:00Z");
            d.setUTCDate(d.getUTCDate() + 1);
            let s = d.toISOString().split('T')[0];
            const url = new URL(window.location.href);
            url.searchParams.set('qstart', s);
            url.searchParams.set('qend', s);
            url.hash = 'v-qr';
            window.location.href = url.toString();
        });

        // Escuchar cambios de tab
        tabEls.forEach(function (el) {
            el.addEventListener('shown.bs.tab', function (event) {
                updateActionButtons(event.target.getAttribute('data-bs-target'));
            });
        });

        // Inicializar con la tab desde URL si existe
        if (window.location.hash) {
            const hashBtn = document.querySelector(`button[data-bs-target="${window.location.hash}"]`);
            if (hashBtn) {
                const tab = new bootstrap.Tab(hashBtn);
                tab.show();
            } else {
                // Fallback a la tab activa actual del HTML
                const activeTab = document.querySelector('button[data-bs-toggle="pill"].active');
                if (activeTab) updateActionButtons(activeTab.getAttribute('data-bs-target'));
            }
        } else {
            // Inicializar con la tab activa actual del HTML
            const activeTab = document.querySelector('button[data-bs-toggle="pill"].active');
            if (activeTab) updateActionButtons(activeTab.getAttribute('data-bs-target'));
        }
    });

