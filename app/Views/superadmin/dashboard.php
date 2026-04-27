<?php $this->extend('layout/main') ?>

<?php $this->section('styles') ?>
<style>
    .sa-metric-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.5rem;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
    }
    .sa-metric-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.08); }
    .sa-metric-card .metric-icon {
        width: 44px; height: 44px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; margin-bottom: 0.75rem;
    }
    .sa-metric-card .metric-value { font-size: 1.75rem; font-weight: 700; color: #0f172a; }
    .sa-metric-card .metric-label { font-size: 0.75rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }

    .sa-table-card {
        background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden;
    }
    .sa-table-card .card-header-sa {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between;
    }
    .sa-table thead th {
        background: #f8fafc; font-size: 0.7rem; font-weight: 600; color: #64748b;
        text-transform: uppercase; letter-spacing: 0.5px; padding: 0.75rem 1rem; border-bottom: 1px solid #e2e8f0;
    }
    .sa-table tbody td { padding: 0.85rem 1rem; vertical-align: middle; font-size: 0.875rem; border-bottom: 1px solid #f1f5f9; }
    .sa-table tbody tr { transition: background 0.15s; }
    .sa-table tbody tr:hover { background: #f8fafc; }

    .condo-avatar {
        width: 36px; height: 36px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.8rem; flex-shrink: 0;
    }
    .badge-status {
        font-size: 0.68rem; font-weight: 600; padding: 0.3em 0.7em;
        border-radius: 6px; text-transform: uppercase; letter-spacing: 0.3px;
    }
    .badge-active { background: #dcfce7; color: #166534; }
    .badge-suspended { background: #fef3c7; color: #92400e; }
    .badge-deleted { background: #fee2e2; color: #991b1b; }

    .sa-btn-action {
        width: 32px; height: 32px; border-radius: 6px; border: 1px solid #e2e8f0;
        background: #fff; display: inline-flex; align-items: center; justify-content: center;
        cursor: pointer; transition: all 0.15s; font-size: 0.8rem; color: #475569;
    }
    .sa-btn-action:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .sa-btn-action.danger:hover { background: #fef2f2; border-color: #fca5a5; color: #dc2626; }
    .sa-btn-action.warning:hover { background: #fffbeb; border-color: #fcd34d; color: #d97706; }
    .sa-btn-action.success:hover { background: #f0fdf4; border-color: #86efac; color: #16a34a; }
    .sa-btn-action:disabled { opacity: 0.35; cursor: not-allowed; pointer-events: none; }

    .metric-mini { display: inline-flex; align-items: center; gap: 3px; font-size: 0.75rem; color: #64748b; background: #f8fafc; padding: 2px 8px; border-radius: 4px; margin-right: 4px; }

    .sa-detail-modal .modal-content { border: none; border-radius: 16px; box-shadow: 0 25px 60px rgba(0,0,0,0.15); }
    .sa-detail-modal .modal-header { border-bottom: 1px solid #f1f5f9; padding: 1.25rem 1.5rem; }
    .sa-detail-modal .modal-body { padding: 1.5rem; }
    .detail-label { font-size: 0.7rem; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 0.25rem; }
    .detail-value { font-size: 0.9rem; font-weight: 500; color: #1e293b; }

    @keyframes fadeInRow { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    .sa-table tbody tr { animation: fadeInRow 0.3s ease forwards; }
</style>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#3b82f6,#1d4ed8);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-shield-lock-fill" style="color:#fff;font-size:1.3rem;margin:0;"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0" style="color:#0f172a;">SuperAdmin Panel</h4>
                <p class="text-muted mb-0" style="font-size:0.85rem;">Centro de control global — AxisCondo SaaS</p>
            </div>
            <span class="badge" style="background:linear-gradient(135deg,#3b82f6,#6366f1);color:#fff;font-size:0.7rem;padding:0.4em 0.8em;border-radius:6px;margin-left:0.5rem;">v2.0</span>
        </div>
    </div>
</div>

<!-- Métricas -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="sa-metric-card">
            <div class="metric-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-building-check" style="margin:0;"></i></div>
            <div class="metric-value"><?= esc($metrics['active_condominiums']) ?></div>
            <div class="metric-label">Condominios Activos</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sa-metric-card">
            <div class="metric-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-people-fill" style="margin:0;"></i></div>
            <div class="metric-value"><?= esc($metrics['total_users']) ?></div>
            <div class="metric-label">Usuarios Totales</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sa-metric-card">
            <div class="metric-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-pause-circle-fill" style="margin:0;"></i></div>
            <div class="metric-value"><?= esc($metrics['suspended_condominiums']) ?></div>
            <div class="metric-label">Suspendidos</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="sa-metric-card">
            <div class="metric-icon" style="background:#ede9fe;color:#7c3aed;"><i class="bi bi-credit-card-fill" style="margin:0;"></i></div>
            <div class="metric-value"><?= esc($metrics['active_subscriptions']) ?></div>
            <div class="metric-label">Suscripciones SaaS</div>
        </div>
    </div>
</div>

<!-- Revenue Cards -->
<div class="row g-3 mb-4">
    <div class="col-12">
        <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.75rem;">
            <div style="width:6px;height:20px;border-radius:3px;background:linear-gradient(180deg,#22c55e,#16a34a);"></div>
            <h6 class="fw-bold mb-0" style="color:#0f172a;font-size:0.85rem;">Ingresos del SaaS</h6>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sa-metric-card" style="border-left:3px solid #22c55e;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div class="metric-icon" style="background:#dcfce7;color:#16a34a;"><i class="bi bi-graph-up-arrow" style="margin:0;"></i></div>
                <span style="font-size:0.65rem;font-weight:600;color:#16a34a;background:#dcfce7;padding:0.2em 0.6em;border-radius:4px;text-transform:uppercase;">Mensual</span>
            </div>
            <div class="metric-value" style="font-size:2rem;">$<?= number_format($revenue['mrr'] ?? 0, 2) ?></div>
            <div class="metric-label" style="margin-top:0.25rem;">MRR — Ingresos Recurrentes</div>
            <div style="margin-top:0.75rem;font-size:0.75rem;color:#64748b;">
                <i class="bi bi-info-circle" style="margin-right:3px;"></i> Calculado desde planes activos
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sa-metric-card" style="border-left:3px solid #3b82f6;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div class="metric-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-cash-stack" style="margin:0;"></i></div>
                <span style="font-size:0.65rem;font-weight:600;color:#2563eb;background:#dbeafe;padding:0.2em 0.6em;border-radius:4px;text-transform:uppercase;"><?= $revenue['months_active'] ?? 1 ?> meses</span>
            </div>
            <div class="metric-value" style="font-size:2rem;">$<?= number_format($revenue['total_billed'] ?? 0, 2) ?></div>
            <div class="metric-label" style="margin-top:0.25rem;">Total Facturado Estimado</div>
            <div style="margin-top:0.75rem;font-size:0.75rem;color:#64748b;">
                <i class="bi bi-info-circle" style="margin-right:3px;"></i> Estimado acumulado desde el inicio
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="sa-metric-card" style="border-left:3px solid #8b5cf6;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div class="metric-icon" style="background:#ede9fe;color:#7c3aed;"><i class="bi bi-patch-check-fill" style="margin:0;"></i></div>
                <span style="font-size:0.65rem;font-weight:600;color:#7c3aed;background:#ede9fe;padding:0.2em 0.6em;border-radius:4px;text-transform:uppercase;">Activas</span>
            </div>
            <div class="metric-value" style="font-size:2rem;"><?= $revenue['paid_subscriptions'] ?? 0 ?></div>
            <div class="metric-label" style="margin-top:0.25rem;">Suscripciones con Plan</div>
            <div style="margin-top:0.75rem;font-size:0.75rem;color:#64748b;">
                <i class="bi bi-info-circle" style="margin-right:3px;"></i> Condominios con plan asignado y activo
            </div>
        </div>
    </div>
</div>

<!-- Tabla de Condominios -->
<div class="sa-table-card">
    <div class="card-header-sa">
        <h6 class="fw-bold mb-0" style="color:#0f172a;"><i class="bi bi-grid-3x3-gap me-2" style="color:#3b82f6;"></i>Gestión de Condominios</h6>
        <span class="badge" style="background:#f1f5f9;color:#475569;font-size:0.75rem;"><?= count($condominiums) ?> registros</span>
    </div>
    <div class="table-responsive">
        <table class="table sa-table mb-0">
            <thead>
                <tr>
                    <th>Condominio</th>
                    <th>Administrador</th>
                    <th>Plan</th>
                    <th>Estado</th>
                    <th>Métricas</th>
                    <th>Creado</th>
                    <th style="text-align:right;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($condominiums)): ?>
                    <tr><td colspan="7" class="text-center text-muted py-4">No hay condominios registrados.</td></tr>
                <?php else: ?>
                    <?php foreach ($condominiums as $c):
                        $initial = strtoupper(substr($c['name'] ?? 'C', 0, 2));
                        $status = $c['status'] ?? 'active';
                        $badgeClass = $status === 'active' ? 'badge-active' : ($status === 'suspended' ? 'badge-suspended' : 'badge-deleted');
                        $statusLabel = $status === 'active' ? 'Active' : ($status === 'suspended' ? 'Suspended' : 'Deleted');
                        $adminName = trim(($c['admin_first_name'] ?? '') . ' ' . ($c['admin_last_name'] ?? ''));
                        $adminEmail = $c['admin_email'] ?? '';
                        $colors = ['#3b82f6','#8b5cf6','#ec4899','#f59e0b','#10b981','#06b6d4'];
                        $bgColor = $colors[$c['id'] % count($colors)];
                    ?>
                    <tr id="condo-row-<?= $c['id'] ?>">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="condo-avatar" style="background:<?= $bgColor ?>20;color:<?= $bgColor ?>;"><?= esc($initial) ?></div>
                                <div>
                                    <div class="fw-semibold" style="color:#0f172a;"><?= esc($c['name']) ?></div>
                                    <?php if (!empty($c['address'])): ?>
                                        <div style="font-size:0.75rem;color:#94a3b8;"><i class="bi bi-geo-alt" style="font-size:0.65rem;margin-right:2px;"></i><?= esc(mb_strimwidth($c['address'], 0, 50, '...')) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($adminName && $adminName !== ' '): ?>
                                <div class="fw-medium" style="color:#1e293b;"><?= esc($adminName) ?></div>
                                <div style="font-size:0.75rem;color:#94a3b8;"><?= esc($adminEmail) ?></div>
                            <?php else: ?>
                                <span style="font-size:0.8rem;color:#cbd5e1;">Sin asignar</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($c['plan_name'])): ?>
                                <span class="badge" style="background:#f8fafc; border: 1px solid #e2e8f0; color:#334155; font-size:0.75rem;"><i class="bi bi-gem me-1" style="color:#f59e0b;"></i><?= esc($c['plan_name']) ?></span>
                            <?php else: ?>
                                <span style="font-size:0.75rem; color:#94a3b8;">Sin Plan</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge-status <?= $badgeClass ?>" id="badge-<?= $c['id'] ?>"><?= $statusLabel ?></span>
                        </td>
                        <td>
                            <span class="metric-mini"><i class="bi bi-building" style="font-size:0.65rem;margin:0;"></i> <?= (int)($c['total_units'] ?? 0) ?></span>
                            <span class="metric-mini"><i class="bi bi-people" style="font-size:0.65rem;margin:0;"></i> <?= (int)($c['total_residents'] ?? 0) ?></span>
                        </td>
                        <td><span style="font-size:0.8rem;color:#64748b;"><?= date('Y-m-d', strtotime($c['created_at'])) ?></span></td>
                        <td>
                            <div class="d-flex gap-1 justify-content-end" id="actions-<?= $c['id'] ?>">
                                <button class="sa-btn-action" title="Ver detalle" onclick="viewDetail(<?= $c['id'] ?>)"><i class="bi bi-eye" style="margin:0;"></i></button>
                                <?php if ($status === 'active'): ?>
                                    <button class="sa-btn-action warning" title="Suspender" onclick="suspendCondo(<?= $c['id'] ?>, '<?= esc(addslashes($c['name'])) ?>')"><i class="bi bi-pause-circle" style="margin:0;"></i></button>
                                <?php elseif ($status === 'suspended'): ?>
                                    <button class="sa-btn-action success" title="Reactivar" onclick="activateCondo(<?= $c['id'] ?>, '<?= esc(addslashes($c['name'])) ?>')"><i class="bi bi-play-circle" style="margin:0;"></i></button>
                                <?php endif; ?>
                                <button class="sa-btn-action danger" title="Eliminar" onclick="deleteCondo(<?= $c['id'] ?>, '<?= esc(addslashes($c['name'])) ?>')"><i class="bi bi-trash3" style="margin:0;"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Detalle -->
<div class="modal fade sa-detail-modal" id="detailModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="fw-bold mb-0"><i class="bi bi-building me-2" style="color:#3b82f6;"></i>Detalle del Condominio</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailModalBody">
                <div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
const BASE = '<?= base_url() ?>';

function suspendCondo(id, name) {
    Swal.fire({
        title: '¿Suspender condominio?',
        html: `<p style="color:#475569;">Estás a punto de suspender <strong>${name}</strong>.</p><div style="background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;padding:12px;margin-top:12px;font-size:0.85rem;color:#92400e;"><i class="bi bi-exclamation-triangle me-1"></i> Esta acción bloqueará el acceso a <strong>todos los usuarios</strong> del condominio (admins y residentes).</div>`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#d97706', cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sí, suspender', cancelButtonText: 'Cancelar'
    }).then((r) => {
        if (r.isConfirmed) doAction(id, 'suspend');
    });
}

function activateCondo(id, name) {
    Swal.fire({
        title: '¿Reactivar condominio?',
        html: `<p style="color:#475569;">Reactivarás <strong>${name}</strong>. Todos sus usuarios recuperarán el acceso.</p>`,
        icon: 'question', showCancelButton: true,
        confirmButtonColor: '#16a34a', cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sí, reactivar', cancelButtonText: 'Cancelar'
    }).then((r) => {
        if (r.isConfirmed) doAction(id, 'activate');
    });
}

function deleteCondo(id, name) {
    Swal.fire({
        title: '¿Eliminar condominio?',
        html: `<p style="color:#475569;">Vas a eliminar <strong>${name}</strong>. Esta acción es irreversible desde el panel.</p><div style="background:#fee2e2;border:1px solid #fca5a5;border-radius:8px;padding:12px;margin-top:12px;font-size:0.85rem;color:#991b1b;"><i class="bi bi-exclamation-octagon me-1"></i> Se eliminará el condominio y se bloqueará el acceso a todos sus usuarios.</div>`,
        icon: 'error', showCancelButton: true,
        confirmButtonColor: '#dc2626', cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Eliminar permanentemente', cancelButtonText: 'Cancelar',
        input: 'text', inputPlaceholder: 'Escribe ELIMINAR para confirmar',
        inputValidator: (v) => { if (v !== 'ELIMINAR') return 'Debes escribir ELIMINAR para confirmar'; }
    }).then((r) => {
        if (r.isConfirmed) doAction(id, 'delete');
    });
}

function doAction(id, action) {
    fetch(`${BASE}/superadmin/condominiums/${id}/${action}`, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
            if (action === 'delete') {
                const row = document.getElementById(`condo-row-${id}`);
                if (row) { row.style.transition = 'all 0.4s ease'; row.style.opacity = '0'; row.style.transform = 'translateX(30px)'; setTimeout(() => row.remove(), 400); }
            } else {
                updateRowStatus(id, data.new_status);
            }
        } else {
            Swal.fire({ icon: 'error', title: 'Error', text: data.message });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Error', text: 'Error de conexión' }));
}

function updateRowStatus(id, newStatus) {
    const badge = document.getElementById(`badge-${id}`);
    const actions = document.getElementById(`actions-${id}`);
    if (!badge) return;

    // Update badge
    badge.className = 'badge-status ' + (newStatus === 'active' ? 'badge-active' : 'badge-suspended');
    badge.textContent = newStatus === 'active' ? 'Active' : 'Suspended';

    // Rebuild action buttons
    const name = document.querySelector(`#condo-row-${id} td .fw-semibold`)?.textContent || '';
    const eName = name.replace(/'/g, "\\'");
    let btns = `<button class="sa-btn-action" title="Ver detalle" onclick="viewDetail(${id})"><i class="bi bi-eye" style="margin:0;"></i></button>`;
    if (newStatus === 'active') {
        btns += `<button class="sa-btn-action warning" title="Suspender" onclick="suspendCondo(${id}, '${eName}')"><i class="bi bi-pause-circle" style="margin:0;"></i></button>`;
    } else {
        btns += `<button class="sa-btn-action success" title="Reactivar" onclick="activateCondo(${id}, '${eName}')"><i class="bi bi-play-circle" style="margin:0;"></i></button>`;
    }
    btns += `<button class="sa-btn-action danger" title="Eliminar" onclick="deleteCondo(${id}, '${eName}')"><i class="bi bi-trash3" style="margin:0;"></i></button>`;
    if (actions) actions.innerHTML = btns;
}

function viewDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('detailModal'));
    document.getElementById('detailModalBody').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';
    modal.show();

    fetch(`${BASE}/superadmin/condominiums/${id}/detail`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { document.getElementById('detailModalBody').innerHTML = `<div class="alert alert-danger">${data.message}</div>`; return; }
        const d = data.data;
        const c = d.condominium;
        const st = c.status === 'active' ? 'badge-active' : 'badge-suspended';
        const adminsHtml = d.admins.length ? d.admins.map(a => `<div class="d-flex align-items-center gap-2 mb-2"><div style="width:32px;height:32px;border-radius:8px;background:#dbeafe;color:#2563eb;display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;">${(a.first_name||'?')[0]}</div><div><div class="fw-medium" style="font-size:0.85rem;">${a.first_name} ${a.last_name}</div><div style="font-size:0.75rem;color:#94a3b8;">${a.email} · ${a.role_name}</div></div></div>`).join('') : '<span class="text-muted" style="font-size:0.85rem;">Sin administradores asignados</span>';

        const p = d.plan;
        const cycleLabel = c.billing_cycle === 'yearly' ? 'Anual' : 'Mensual';
        const cost = p ? (c.billing_cycle === 'yearly' ? p.price_yearly : p.price_monthly) : 0;
        const isManual = (c.payment_method || 'stripe') === 'manual';
        const pmBadge = isManual
            ? '<span class="badge" style="background:#fef3c7;color:#92400e;font-size:0.68rem;">💵 Pago Manual</span>'
            : '<span class="badge" style="background:#dbeafe;color:#2563eb;font-size:0.68rem;">💳 Stripe</span>';

        // Generar tarjeta de suscripción si tiene plan
        let planHtml = '';
        if (p) {
            planHtml = `
            <div style="background: linear-gradient(to right, #f8fafc, #f1f5f9); border: 1px solid #e2e8f0; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="detail-label mb-0" style="color:#3b82f6;"><i class="bi bi-gem me-1"></i> Suscripción Activa</div>
                    <div class="d-flex gap-1">${pmBadge}<span class="badge" style="background:#f1f5f9;color:#475569;font-size:0.68rem;">${cycleLabel}</span></div>
                </div>
                <div class="d-flex justify-content-between align-items-end">
                    <div>
                        <div class="fw-bold" style="font-size:1.1rem; color:#0f172a;">${p.name}</div>
                        <div style="font-size:0.8rem; color:#64748b; margin-top:2px;">
                            ${c.plan_expires_at ? `Vence: ${c.plan_expires_at.substring(0, 10)}` : 'Sin fecha de vencimiento'}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold" style="font-size:1.2rem; color:#16a34a;">$${parseFloat(cost).toLocaleString('es-MX',{minimumFractionDigits:2})}</div>
                        <div style="font-size:0.7rem; color:#94a3b8;">${c.billing_cycle === 'yearly' ? '/año' : '/mes'}</div>
                    </div>
                </div>
                ${isManual ? `<div style="margin-top:0.75rem;"><button onclick="recordManualPayment(${c.id},'${p.name.replace(/'/g,"\\'")}','${c.billing_cycle}',${cost})" style="width:100%;padding:0.55rem;border-radius:8px;border:none;background:#1C2434;color:#fff;font-weight:700;font-size:0.85rem;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:0.4rem;" onmouseover="this.style.background='#334155';this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 12px rgba(28,36,52,0.2)';" onmouseout="this.style.background='#1C2434';this.style.transform='';this.style.boxShadow='none';"><i class="bi bi-cash-coin" style="margin:0;"></i> Registrar Pago Manual</button></div>` : ''}
            </div>`;
        } else {
            planHtml = `
            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; text-align: center;">
                <div style="color:#94a3b8; font-size:0.85rem;"><i class="bi bi-info-circle me-1"></i> Condominio sin plan asignado</div>
            </div>`;
        }

        // Historial de pagos (se carga asincrónamente para condominios manuales)
        const paymentHistoryHtml = isManual ? `
            <div class="detail-label mt-3">Historial de Pagos</div>
            <div id="paymentHistoryContainer" style="margin-top:0.5rem;">
                <div class="text-center py-2"><div class="spinner-border spinner-border-sm text-secondary" role="status"></div></div>
            </div>` : '';

        document.getElementById('detailModalBody').innerHTML = `
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="detail-label">Nombre</div><div class="detail-value">${c.name}</div>
                    <div class="detail-label mt-3">Dirección</div><div class="detail-value">${c.address || 'Sin dirección'}</div>
                    <div class="detail-label mt-3">Estado</div><div><span class="badge-status ${st}">${c.status.toUpperCase()}</span></div>
                    <div class="detail-label mt-3">Creado</div><div class="detail-value">${c.created_at}</div>
                    ${c.suspended_at ? `<div class="detail-label mt-3">Suspendido</div><div class="detail-value" style="color:#d97706;">${c.suspended_at}</div>` : ''}
                </div>
                <div class="col-md-7">
                    ${planHtml}
                    <div class="detail-label">Métricas</div>
                    <div class="d-flex gap-3 mt-1 mb-3">
                        <div style="background:#f8fafc;border-radius:8px;padding:12px 16px;flex:1;text-align:center;">
                            <div style="font-size:1.25rem;font-weight:700;color:#0f172a;">${d.metrics.total_units}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;">Unidades</div>
                        </div>
                        <div style="background:#f8fafc;border-radius:8px;padding:12px 16px;flex:1;text-align:center;">
                            <div style="font-size:1.25rem;font-weight:700;color:#0f172a;">${d.metrics.total_residents}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;">Residentes</div>
                        </div>
                        <div style="background:#f8fafc;border-radius:8px;padding:12px 16px;flex:1;text-align:center;">
                            <div style="font-size:1.25rem;font-weight:700;color:#0f172a;">${d.metrics.total_tickets}</div>
                            <div style="font-size:0.7rem;color:#94a3b8;text-transform:uppercase;">Tickets</div>
                        </div>
                    </div>
                    <div class="detail-label">Administradores</div>
                    <div class="mt-1">${adminsHtml}</div>
                    ${d.last_activity ? `<div class="detail-label mt-3">Última Actividad</div><div class="detail-value">${d.last_activity}</div>` : ''}
                    ${paymentHistoryHtml}
                </div>
            </div>`;

        // Cargar historial de pagos si es manual
        if (isManual) loadPaymentHistory(c.id);
    })
    .catch(() => { document.getElementById('detailModalBody').innerHTML = '<div class="alert alert-danger">Error al cargar los datos</div>'; });
}

// ── Registrar Pago Manual via SweetAlert ──
function recordManualPayment(condoId, planName, cycle, suggestedAmount) {
    const cycleLabel = cycle === 'yearly' ? 'anual' : 'mensual';
    
    Swal.fire({
        target: document.getElementById('detailModal'),
        title: '💰 Registrar Pago Manual',
        html: `
            <div style="text-align:left;">
                <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:0.75rem;margin-bottom:1rem;font-size:0.85rem;color:#475569;">
                    <strong>${planName}</strong> — Facturación ${cycleLabel}
                </div>
                <div style="margin-bottom:0.75rem;">
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;margin-bottom:0.25rem;">Monto (MXN)</label>
                    <input type="number" id="swalAmount" class="swal2-input" style="margin:0;width:100%;" step="0.01" min="0" value="${suggestedAmount}">
                </div>
                <div style="margin-bottom:0.75rem;">
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;margin-bottom:0.25rem;">Tipo de Pago</label>
                    <select id="swalPaymentType" class="swal2-select" style="margin:0;width:100%;padding:0.5rem;border:1px solid #d1d5db;border-radius:8px;">
                        <option value="transfer">Transferencia</option>
                        <option value="cash">Efectivo</option>
                        <option value="deposit">Depósito Bancario</option>
                    </select>
                </div>
                <div style="margin-bottom:0.75rem;">
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;margin-bottom:0.25rem;">Referencia</label>
                    <input type="text" id="swalReference" class="swal2-input" style="margin:0;width:100%;" placeholder="Ej: REF-20260421-001">
                </div>
                <div style="margin-bottom:0.75rem;">
                    <label style="display:block;font-size:0.75rem;font-weight:600;color:#64748b;text-transform:uppercase;margin-bottom:0.25rem;">Notas (opcional)</label>
                    <textarea id="swalNotes" class="swal2-textarea" style="margin:0;width:100%;min-height:60px;" placeholder="Notas adicionales..."></textarea>
                </div>
                <div style="margin-top:1rem;padding-top:1rem;border-top:1px dashed #cbd5e1;">
                    <div style="font-size:0.75rem;font-weight:700;color:#0f172a;margin-bottom:0.5rem;"><i class="bi bi-calendar-event me-1"></i> Ajuste manual de fechas (Solo Migración)</div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                        <div>
                            <label style="display:block;font-size:0.7rem;font-weight:600;color:#64748b;margin-bottom:0.25rem;">Inicio del período</label>
                            <input type="date" id="swalPeriodStart" class="swal2-input" style="margin:0;width:100%;font-size:0.85rem;">
                        </div>
                        <div>
                            <label style="display:block;font-size:0.7rem;font-weight:600;color:#64748b;margin-bottom:0.25rem;">Fin del período</label>
                            <input type="date" id="swalPeriodEnd" class="swal2-input" style="margin:0;width:100%;font-size:0.85rem;">
                        </div>
                    </div>
                    <div style="font-size:0.7rem;color:#94a3b8;margin-top:0.35rem;">* Dejar en blanco para cálculo automático</div>
                </div>
            </div>`,
        showCancelButton: true,
        confirmButtonText: '<i class="bi bi-check2-circle"></i> Registrar Pago',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#16a34a',
        cancelButtonColor: '#94a3b8',
        focusConfirm: false,
        preConfirm: () => {
            const amount = document.getElementById('swalAmount').value;
            if (!amount || parseFloat(amount) <= 0) {
                Swal.showValidationMessage('El monto debe ser mayor a 0');
                return false;
            }
            return {
                amount: amount,
                payment_type: document.getElementById('swalPaymentType').value,
                reference: document.getElementById('swalReference').value,
                notes: document.getElementById('swalNotes').value,
                period_start: document.getElementById('swalPeriodStart').value,
                period_end: document.getElementById('swalPeriodEnd').value,
            };
        }
    }).then((result) => {
        if (!result.isConfirmed) return;
        const v = result.value;
        const fd = new FormData();
        fd.append('condominium_id', condoId);
        fd.append('amount', v.amount);
        fd.append('payment_type', v.payment_type);
        fd.append('reference', v.reference);
        fd.append('notes', v.notes);
        if (v.period_start) fd.append('period_start', v.period_start);
        if (v.period_end) fd.append('period_end', v.period_end);

        fetch(`${BASE}/superadmin/payments/record`, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                Swal.fire({ target: document.getElementById('detailModal'), toast: true, position: 'top-end', icon: 'success', title: d.message, showConfirmButton: false, timer: 4000, timerProgressBar: true });
                // Refrescar modal de detalle automáticamente
                setTimeout(() => viewDetail(condoId), 300);
            } else {
                Swal.fire({ target: document.getElementById('detailModal'), icon: 'error', title: 'Error', text: d.message });
            }
        })
        .catch(() => Swal.fire({ target: document.getElementById('detailModal'), icon: 'error', title: 'Error', text: 'Error de conexión' }));
    });
}

// ── Cargar Historial de Pagos ──
function loadPaymentHistory(condoId) {
    fetch(`${BASE}/superadmin/payments/${condoId}/history`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        const container = document.getElementById('paymentHistoryContainer');
        if (!container) return;
        if (!d.success || !d.payments.length) {
            container.innerHTML = '<div style="font-size:0.8rem;color:#94a3b8;padding:0.5rem 0;">Sin pagos registrados aún.</div>';
            return;
        }
        const typeLabels = { cash: 'Efectivo', transfer: 'Transferencia', deposit: 'Depósito' };
        const typeIcons = { cash: 'bi-cash', transfer: 'bi-arrow-left-right', deposit: 'bi-bank' };
        container.innerHTML = `
            <div style="max-height:200px;overflow-y:auto;border:1px solid #e2e8f0;border-radius:8px;">
                <table style="width:100%;font-size:0.78rem;border-collapse:collapse;">
                    <thead><tr style="background:#f8fafc;">
                        <th style="padding:0.4rem 0.6rem;text-align:left;font-weight:600;color:#64748b;border-bottom:1px solid #e2e8f0;">Fecha</th>
                        <th style="padding:0.4rem 0.6rem;text-align:left;font-weight:600;color:#64748b;border-bottom:1px solid #e2e8f0;">Monto</th>
                        <th style="padding:0.4rem 0.6rem;text-align:left;font-weight:600;color:#64748b;border-bottom:1px solid #e2e8f0;">Tipo</th>
                        <th style="padding:0.4rem 0.6rem;text-align:left;font-weight:600;color:#64748b;border-bottom:1px solid #e2e8f0;">Período</th>
                        <th style="padding:0.4rem 0.6rem;text-align:left;font-weight:600;color:#64748b;border-bottom:1px solid #e2e8f0;">Ref.</th>
                    </tr></thead>
                    <tbody>
                        ${d.payments.map(p => `<tr style="border-bottom:1px solid #f1f5f9;">
                            <td style="padding:0.4rem 0.6rem;color:#475569;">${p.created_at ? p.created_at.substring(0,10) : '—'}</td>
                            <td style="padding:0.4rem 0.6rem;font-weight:600;color:#16a34a;">$${parseFloat(p.amount).toLocaleString('es-MX',{minimumFractionDigits:2})}</td>
                            <td style="padding:0.4rem 0.6rem;"><i class="bi ${typeIcons[p.payment_type] || 'bi-receipt'}" style="margin-right:3px;"></i>${typeLabels[p.payment_type] || p.payment_type}</td>
                            <td style="padding:0.4rem 0.6rem;color:#64748b;">${p.period_start} → ${p.period_end}</td>
                            <td style="padding:0.4rem 0.6rem;color:#94a3b8;">${p.reference || '—'}</td>
                        </tr>`).join('')}
                    </tbody>
                </table>
            </div>`;
    })
    .catch(() => {
        const container = document.getElementById('paymentHistoryContainer');
        if (container) container.innerHTML = '<div style="font-size:0.8rem;color:#dc2626;">Error al cargar historial</div>';
    });
}
</script>
<?php $this->endSection() ?>

