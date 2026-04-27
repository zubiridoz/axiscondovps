<?php $this->extend('layout/main') ?>

<?php $this->section('styles') ?>
<style>
    :root { --pl-dark: #1C2434; --pl-muted: #475569; --pl-border: #e2e8f0; --pl-bg: #f8fafc; }

    .pl-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
    .pl-header-left { display: flex; align-items: center; gap: 0.75rem; }
    .pl-header-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--pl-dark); display: flex; align-items: center; justify-content: center; }
    .pl-header-icon i { color: #fff; font-size: 1.3rem; margin: 0; }
    .pl-header h4 { font-weight: 700; margin: 0; color: var(--pl-dark); }
    .pl-header p { margin: 0; font-size: 0.85rem; color: var(--pl-muted); }

    .pl-btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.2rem; border-radius: 8px; font-size: 0.8rem; font-weight: 600; cursor: pointer; border: none; transition: all 0.2s; }
    .pl-btn-primary { background: var(--pl-dark); color: #fff; }
    .pl-btn-primary:hover { background: #0f172a; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(28,36,52,0.25); }
    .pl-btn-outline { background: #fff; color: var(--pl-muted); border: 1px solid var(--pl-border); }
    .pl-btn-outline:hover { background: var(--pl-bg); }
    .pl-btn-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
    .pl-btn-danger:hover { background: #fecaca; }
    .pl-btn-assign { background: #dbeafe; color: #1d4ed8; border: 1px solid #93c5fd; }
    .pl-btn-assign:hover { background: #bfdbfe; }
    .pl-btn-sm { padding: 0.35rem 0.7rem; font-size: 0.75rem; }

    .pl-card { background: #fff; border: 1px solid var(--pl-border); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; }

    .pl-table { width: 100%; border-collapse: collapse; }
    .pl-table thead th { padding: 0.75rem 1rem; font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: var(--pl-muted); border-bottom: 1px solid var(--pl-border); background: var(--pl-bg); text-align: left; }
    .pl-table tbody td { padding: 0.85rem 1rem; font-size: 0.875rem; color: #1e293b; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .pl-table tbody tr:last-child td { border-bottom: none; }
    .pl-table tbody tr:hover { background: #fafbfc; }

    .pl-plan-name { font-weight: 700; color: var(--pl-dark); }
    .pl-plan-slug { font-size: 0.7rem; color: #94a3b8; }
    .pl-unit-range { display: inline-flex; align-items: center; gap: 0.3rem; background: #f1f5f9; padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.8rem; font-weight: 600; color: var(--pl-muted); }
    .pl-price { font-weight: 700; color: var(--pl-dark); }
    .pl-price-label { font-size: 0.7rem; color: #94a3b8; font-weight: 400; }
    .pl-badge { font-size: 0.7rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 4px; }
    .pl-badge-active { background: #dcfce7; color: #166534; }
    .pl-badge-inactive { background: #fee2e2; color: #991b1b; }
    .pl-condos-badge { background: #ede9fe; color: #6d28d9; font-size: 0.75rem; font-weight: 600; padding: 0.2rem 0.5rem; border-radius: 4px; }

    .pl-actions { display: flex; gap: 0.35rem; }
    .pl-empty { text-align: center; padding: 3rem 1rem; color: #94a3b8; }
    .pl-empty i { font-size: 2.5rem; margin-bottom: 0.5rem; display: block; }

    /* Modal */
    .pl-modal .modal-content { border: none; border-radius: 12px; box-shadow: 0 20px 60px rgba(15,23,42,0.18); }
    .pl-modal .modal-header { border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; }
    .pl-modal .modal-header h5 { font-weight: 700; color: var(--pl-dark); margin: 0; font-size: 1.05rem; }
    .pl-modal .modal-body { padding: 1.25rem 1.5rem; }
    .pl-modal .modal-footer { border-top: 1px solid #f1f5f9; padding: 1rem 1.5rem; }

    .pl-form-group { margin-bottom: 1rem; }
    .pl-form-group label { display: block; font-size: 0.75rem; font-weight: 600; color: var(--pl-muted); text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 0.35rem; }
    .pl-form-group input, .pl-form-group select, .pl-form-group textarea { width: 100%; padding: 0.55rem 0.75rem; border: 1px solid var(--pl-border); border-radius: 8px; font-size: 0.875rem; color: var(--pl-dark); transition: border-color 0.2s, box-shadow 0.2s; outline: none; background: #fff; }
    .pl-form-group input:focus, .pl-form-group select:focus, .pl-form-group textarea:focus { border-color: var(--pl-muted); box-shadow: 0 0 0 3px rgba(71,85,105,0.1); }
    .pl-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; }
</style>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<!-- Header -->
<div class="pl-header">
    <div class="pl-header-left">
        <div class="pl-header-icon"><i class="bi bi-credit-card-2-front-fill"></i></div>
        <div>
            <h4>Planes SaaS</h4>
            <p>Gestión de planes de suscripción por unidades</p>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button class="pl-btn pl-btn-assign" onclick="openAssignModal()"><i class="bi bi-link-45deg" style="margin:0;"></i> Asignar Plan</button>
        <button class="pl-btn pl-btn-primary" onclick="openCreateModal()"><i class="bi bi-plus-lg" style="margin:0;"></i> Nuevo Plan</button>
    </div>
</div>

<!-- Plans Table -->
<div class="pl-card">
    <table class="pl-table">
        <thead>
            <tr>
                <th>Plan</th>
                <th>Unidades</th>
                <th>Precio Mensual</th>
                <th>Precio Anual</th>
                <th>Condominios</th>
                <th>Estado</th>
                <th style="text-align:right;">Acciones</th>
            </tr>
        </thead>
        <tbody id="plansTableBody">
            <tr><td colspan="7" class="pl-empty"><div class="spinner-border spinner-border-sm text-secondary"></div></td></tr>
        </tbody>
    </table>
</div>

<!-- Modal: Crear/Editar Plan -->
<div class="modal fade pl-modal" id="planModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="planModalTitle">Nuevo Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="planEditId" value="">
                <div class="pl-form-group">
                    <label>Nombre del Plan</label>
                    <input type="text" id="planName" placeholder="Ej: Básico, Profesional, Enterprise">
                </div>
                <div class="pl-form-row">
                    <div class="pl-form-group">
                        <label>Min. Unidades</label>
                        <input type="number" id="planMinUnits" min="1" value="1">
                    </div>
                    <div class="pl-form-group">
                        <label>Max. Unidades</label>
                        <input type="number" id="planMaxUnits" min="1" value="50">
                    </div>
                </div>
                <div class="pl-form-row">
                    <div class="pl-form-group">
                        <label>Precio Mensual (MXN)</label>
                        <input type="number" id="planPriceMonthly" step="0.01" min="0" value="0">
                    </div>
                    <div class="pl-form-group">
                        <label>Precio Anual (MXN)</label>
                        <input type="number" id="planPriceYearly" step="0.01" min="0" value="0">
                    </div>
                </div>
                <div class="pl-form-row">
                    <div class="pl-form-group">
                        <label>Orden</label>
                        <input type="number" id="planSortOrder" min="0" value="0">
                    </div>
                    <div class="pl-form-group">
                        <label>Estado</label>
                        <select id="planIsActive">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                </div>
                <div class="pl-form-group">
                    <label>Características (opcional, una por línea)</label>
                    <textarea id="planFeatures" rows="3" placeholder="Gestión de unidades&#10;Portal de residentes&#10;Reportes financieros"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="pl-btn pl-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                <button class="pl-btn pl-btn-primary" onclick="savePlan()"><i class="bi bi-check2" style="margin:0;"></i> Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Asignar Plan -->
<div class="modal fade pl-modal" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Asignar Plan a Condominio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="pl-form-group">
                    <label>Condominio</label>
                    <select id="assignCondoId">
                        <option value="">Cargando...</option>
                    </select>
                </div>
                <div class="pl-form-group">
                    <label>Plan</label>
                    <select id="assignPlanId">
                        <option value="">Cargando...</option>
                    </select>
                </div>
                <div class="pl-form-group">
                    <label>Ciclo de Facturación</label>
                    <select id="assignCycle">
                        <option value="monthly">Mensual</option>
                        <option value="yearly">Anual</option>
                    </select>
                </div>
                <div class="pl-form-group">
                    <label>Método de Pago</label>
                    <select id="assignPaymentMethod">
                        <option value="stripe">💳 Stripe (Automático)</option>
                        <option value="manual">💵 Manual (Efectivo / Transferencia)</option>
                    </select>
                </div>
                <div id="manualPaymentInfo" style="display:none;padding:0.75rem;background:#fffbeb;border-radius:8px;border:1px solid #fcd34d;font-size:0.8rem;color:#92400e;margin-bottom:0.5rem;">
                    <i class="bi bi-info-circle me-1"></i> El condominio no usará Stripe. La vigencia se controlará al registrar cada pago manual.
                </div>
                <div id="assignInfo" style="display:none;padding:0.75rem;background:#f0f9ff;border-radius:8px;border:1px solid #bae6fd;font-size:0.8rem;color:#0369a1;margin-top:0.5rem;">
                </div>
            </div>
            <div class="modal-footer">
                <button class="pl-btn pl-btn-outline" data-bs-dismiss="modal">Cancelar</button>
                <button class="pl-btn pl-btn-primary" onclick="assignPlan()"><i class="bi bi-link-45deg" style="margin:0;"></i> Asignar</button>
            </div>
        </div>
    </div>
</div>

<script>
const BASE = '<?= base_url() ?>';

// ── Load plans ──
function loadPlans() {
    fetch(`${BASE}/superadmin/plans/list`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        const tbody = document.getElementById('plansTableBody');
        if (!d.success || d.plans.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="pl-empty"><i class="bi bi-credit-card"></i>No hay planes creados aún.<br><small>Haz clic en "Nuevo Plan" para comenzar.</small></td></tr>';
            return;
        }
        tbody.innerHTML = d.plans.map(p => `
            <tr>
                <td>
                    <div class="pl-plan-name">${p.name}</div>
                    <div class="pl-plan-slug">${p.slug || ''}</div>
                </td>
                <td><span class="pl-unit-range"><i class="bi bi-building" style="margin:0;font-size:0.7rem;"></i> ${p.min_units} – ${p.max_units}</span></td>
                <td><span class="pl-price">$${parseFloat(p.price_monthly).toLocaleString('es-MX', {minimumFractionDigits:2})}</span><span class="pl-price-label"> /mes</span></td>
                <td><span class="pl-price">$${parseFloat(p.price_yearly).toLocaleString('es-MX', {minimumFractionDigits:2})}</span><span class="pl-price-label"> /año</span></td>
                <td><span class="pl-condos-badge">${p.condos_count} condominios</span></td>
                <td><span class="pl-badge ${p.is_active == 1 ? 'pl-badge-active' : 'pl-badge-inactive'}">${p.is_active == 1 ? 'Activo' : 'Inactivo'}</span></td>
                <td>
                    <div class="pl-actions" style="justify-content:flex-end;">
                        <button class="pl-btn pl-btn-outline pl-btn-sm" onclick="openEditModal(${p.id})"><i class="bi bi-pencil" style="margin:0;"></i></button>
                        <button class="pl-btn pl-btn-danger pl-btn-sm" onclick="deletePlan(${p.id}, '${p.name}')"><i class="bi bi-trash3" style="margin:0;"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    });
}

// ── Create Modal ──
function openCreateModal() {
    document.getElementById('planEditId').value = '';
    document.getElementById('planModalTitle').textContent = 'Nuevo Plan';
    ['planName','planFeatures'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('planMinUnits').value = 1;
    document.getElementById('planMaxUnits').value = 50;
    document.getElementById('planPriceMonthly').value = 0;
    document.getElementById('planPriceYearly').value = 0;
    document.getElementById('planSortOrder').value = 0;
    document.getElementById('planIsActive').value = '1';
    new bootstrap.Modal(document.getElementById('planModal')).show();
}

// ── Edit Modal ──
function openEditModal(id) {
    fetch(`${BASE}/superadmin/plans/${id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;
        const p = d.plan;
        document.getElementById('planEditId').value = p.id;
        document.getElementById('planModalTitle').textContent = 'Editar Plan';
        document.getElementById('planName').value = p.name;
        document.getElementById('planMinUnits').value = p.min_units;
        document.getElementById('planMaxUnits').value = p.max_units;
        document.getElementById('planPriceMonthly').value = p.price_monthly;
        document.getElementById('planPriceYearly').value = p.price_yearly;
        document.getElementById('planSortOrder').value = p.sort_order;
        document.getElementById('planIsActive').value = p.is_active;
        document.getElementById('planFeatures').value = p.features || '';
        new bootstrap.Modal(document.getElementById('planModal')).show();
    });
}

// ── Save Plan ──
function savePlan() {
    const editId = document.getElementById('planEditId').value;
    const data = new FormData();
    data.append('name', document.getElementById('planName').value);
    data.append('min_units', document.getElementById('planMinUnits').value);
    data.append('max_units', document.getElementById('planMaxUnits').value);
    data.append('price_monthly', document.getElementById('planPriceMonthly').value);
    data.append('price_yearly', document.getElementById('planPriceYearly').value);
    data.append('sort_order', document.getElementById('planSortOrder').value);
    data.append('is_active', document.getElementById('planIsActive').value);
    data.append('features', document.getElementById('planFeatures').value);

    const url = editId ? `${BASE}/superadmin/plans/${editId}/update` : `${BASE}/superadmin/plans/store`;
    fetch(url, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        Swal.fire({ toast: true, position: 'top-end', icon: d.success ? 'success' : 'error', title: d.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        if (d.success) {
            bootstrap.Modal.getInstance(document.getElementById('planModal')).hide();
            loadPlans();
        }
    });
}

// ── Delete Plan ──
function deletePlan(id, name) {
    Swal.fire({
        title: '¿Eliminar plan?',
        html: `<p style="color:#64748b;">Se eliminará el plan <strong>${name}</strong>. Esta acción no se puede deshacer.</p>`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc2626', cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Eliminar', cancelButtonText: 'Cancelar'
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch(`${BASE}/superadmin/plans/${id}/delete`, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(d => {
            Swal.fire({ toast: true, position: 'top-end', icon: d.success ? 'success' : 'error', title: d.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
            if (d.success) loadPlans();
        });
    });
}

// ── Assign Modal ──
function openAssignModal() {
    const modal = new bootstrap.Modal(document.getElementById('assignModal'));
    modal.show();
    document.getElementById('assignInfo').style.display = 'none';

    // Load condominiums
    fetch(`${BASE}/superadmin/plans/condominiums`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        const sel = document.getElementById('assignCondoId');
        sel.innerHTML = '<option value="">Seleccionar condominio...</option>' +
            d.condominiums.map(c => `<option value="${c.id}" data-units="${c.unit_count}">${c.name} (${c.unit_count} uds)${c.plan_name ? ' — Plan: ' + c.plan_name : ''}</option>`).join('');
    });

    // Load plans
    fetch(`${BASE}/superadmin/plans/list`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        const sel = document.getElementById('assignPlanId');
        sel.innerHTML = '<option value="">Seleccionar plan...</option>' +
            d.plans.filter(p => p.is_active == 1).map(p => `<option value="${p.id}" data-max="${p.max_units}">${p.name} (${p.min_units}–${p.max_units} uds) — $${parseFloat(p.price_monthly).toFixed(2)}/mes</option>`).join('');
    });
}

// Update assign info when selections change
document.addEventListener('change', function(e) {
    if (e.target.id === 'assignCondoId' || e.target.id === 'assignPlanId') {
        const condoSel = document.getElementById('assignCondoId');
        const planSel = document.getElementById('assignPlanId');
        const info = document.getElementById('assignInfo');
        const condoOpt = condoSel.selectedOptions[0];
        const planOpt = planSel.selectedOptions[0];

        if (condoOpt && planOpt && condoOpt.value && planOpt.value) {
            const units = parseInt(condoOpt.dataset.units || 0);
            const maxUnits = parseInt(planOpt.dataset.max || 0);
            if (units > maxUnits) {
                info.style.display = 'block';
                info.style.background = '#fef2f2';
                info.style.borderColor = '#fca5a5';
                info.style.color = '#991b1b';
                info.innerHTML = `<i class="bi bi-exclamation-triangle me-1"></i> El condominio tiene <strong>${units}</strong> unidades pero el plan permite máximo <strong>${maxUnits}</strong>.`;
            } else {
                info.style.display = 'block';
                info.style.background = '#f0f9ff';
                info.style.borderColor = '#bae6fd';
                info.style.color = '#0369a1';
                info.innerHTML = `<i class="bi bi-check-circle me-1"></i> Compatible: ${units} de ${maxUnits} unidades.`;
            }
        } else {
            info.style.display = 'none';
        }
    }
});

// ── Assign Plan ──
function assignPlan() {
    const data = new FormData();
    data.append('condominium_id', document.getElementById('assignCondoId').value);
    data.append('plan_id', document.getElementById('assignPlanId').value);
    data.append('billing_cycle', document.getElementById('assignCycle').value);
    data.append('payment_method', document.getElementById('assignPaymentMethod').value);

    fetch(`${BASE}/superadmin/plans/assign`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        Swal.fire({ toast: true, position: 'top-end', icon: d.success ? 'success' : 'error', title: d.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        if (d.success) {
            bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
            loadPlans();
        }
    });
}

document.addEventListener('DOMContentLoaded', loadPlans);

// Toggle manual payment info based on payment method selection
document.addEventListener('change', function(e) {
    if (e.target.id === 'assignPaymentMethod') {
        const info = document.getElementById('manualPaymentInfo');
        info.style.display = e.target.value === 'manual' ? 'block' : 'none';
    }
});
</script>
<?php $this->endSection() ?>
