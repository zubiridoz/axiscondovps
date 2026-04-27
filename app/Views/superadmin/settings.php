<?php $this->extend('layout/main') ?>

<?php $this->section('styles') ?>
<style>
    :root { --sa-dark: #1C2434; --sa-muted: #475569; --sa-border: #e2e8f0; --sa-bg: #f8fafc; }

    .sa-settings-card {
        background: #ffffff; border: 1px solid var(--sa-border); border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04); overflow: hidden; margin-bottom: 1.5rem;
    }
    .sa-settings-card .card-header-sa {
        padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; gap: 0.75rem;
    }
    .sa-settings-card .card-header-sa h6 { font-weight: 700; margin: 0; color: var(--sa-dark); font-size: 0.95rem; }
    .sa-settings-card .card-header-sa .header-icon {
        width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem;
    }
    .sa-settings-card .card-body-sa { padding: 1.5rem; }

    .sa-form-group { margin-bottom: 1.25rem; }
    .sa-form-group label {
        display: block; font-size: 0.75rem; font-weight: 600; color: var(--sa-muted);
        text-transform: uppercase; letter-spacing: 0.4px; margin-bottom: 0.4rem;
    }
    .sa-form-group input {
        width: 100%; padding: 0.6rem 0.85rem; border: 1px solid var(--sa-border); border-radius: 8px;
        font-size: 0.875rem; color: var(--sa-dark); transition: border-color 0.2s, box-shadow 0.2s;
        background: #fff; outline: none;
    }
    .sa-form-group input:focus { border-color: var(--sa-muted); box-shadow: 0 0 0 3px rgba(71,85,105,0.1); }

    .sa-btn {
        display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.2rem;
        border-radius: 8px; font-size: 0.8rem; font-weight: 600; cursor: pointer;
        border: none; transition: all 0.2s;
    }
    .sa-btn-primary { background: var(--sa-dark); color: #fff; }
    .sa-btn-primary:hover { background: #0f172a; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(28,36,52,0.25); }
    .sa-btn-danger { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
    .sa-btn-danger:hover { background: #fecaca; }
    .sa-btn-outline { background: #fff; color: var(--sa-muted); border: 1px solid var(--sa-border); }
    .sa-btn-outline:hover { background: var(--sa-bg); border-color: #cbd5e1; }
    .sa-btn-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }

    .sa-avatar-upload {
        width: 96px; height: 96px; border-radius: 16px; position: relative; overflow: hidden;
        cursor: pointer; transition: all 0.2s; border: 2px solid var(--sa-border);
    }
    .sa-avatar-upload:hover { border-color: var(--sa-muted); }
    .sa-avatar-upload img { width: 100%; height: 100%; object-fit: cover; }
    .sa-avatar-upload .avatar-placeholder {
        width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;
        background: var(--sa-dark); color: #fff; font-size: 2rem; font-weight: 700;
    }
    .sa-avatar-upload .avatar-overlay {
        position: absolute; inset: 0; background: rgba(28,36,52,0.6); display: flex;
        align-items: center; justify-content: center; opacity: 0; transition: opacity 0.2s;
    }
    .sa-avatar-upload:hover .avatar-overlay { opacity: 1; }

    .sa-admin-row {
        display: flex; align-items: center; gap: 1rem; padding: 0.85rem 0;
        border-bottom: 1px solid #f1f5f9; transition: background 0.15s;
    }
    .sa-admin-row:last-child { border-bottom: none; }
    .sa-admin-row:hover { background: var(--sa-bg); margin: 0 -1.5rem; padding-left: 1.5rem; padding-right: 1.5rem; }
    .sa-admin-row .admin-avatar {
        width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
    }

    .sa-tabs { display: flex; gap: 0; border-bottom: 1px solid var(--sa-border); margin-bottom: 1.5rem; }
    .sa-tab {
        padding: 0.75rem 1.25rem; font-size: 0.85rem; font-weight: 600; color: var(--sa-muted);
        border-bottom: 2px solid transparent; cursor: pointer; transition: all 0.2s; background: none; border-top: none; border-left: none; border-right: none;
    }
    .sa-tab:hover { color: var(--sa-dark); }
    .sa-tab.active { color: var(--sa-dark); border-bottom-color: var(--sa-dark); }

    .sa-tab-content { display: none; }
    .sa-tab-content.active { display: block; animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(6px); } to { opacity: 1; transform: translateY(0); } }

    .sa-empty { text-align: center; padding: 2rem; color: #94a3b8; font-size: 0.85rem; }
</style>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<!-- Header -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex align-items-center gap-3">
            <div style="width:48px;height:48px;border-radius:12px;background:var(--sa-dark);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-gear-fill" style="color:#fff;font-size:1.3rem;margin:0;"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0" style="color:var(--sa-dark);">Configuración</h4>
                <p class="mb-0" style="font-size:0.85rem;color:var(--sa-muted);">Perfil y gestión de Super Administradores</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="sa-tabs">
    <button class="sa-tab active" onclick="switchTab('profile')"><i class="bi bi-person me-1"></i> Mi Perfil</button>
    <button class="sa-tab" onclick="switchTab('security')"><i class="bi bi-shield-lock me-1"></i> Seguridad</button>
    <button class="sa-tab" onclick="switchTab('admins')"><i class="bi bi-people me-1"></i> Super Admins</button>
</div>

<!-- Tab: Mi Perfil -->
<div class="sa-tab-content active" id="tab-profile">
    <div class="row">
        <div class="col-lg-8">
            <div class="sa-settings-card">
                <div class="card-header-sa">
                    <div class="header-icon" style="background:#f1f5f9;color:var(--sa-muted);"><i class="bi bi-person-circle" style="margin:0;"></i></div>
                    <h6>Información Personal</h6>
                </div>
                <div class="card-body-sa">
                    <div class="d-flex align-items-start gap-4 mb-4">
                        <!-- Avatar -->
                        <div>
                            <div class="sa-avatar-upload" onclick="document.getElementById('avatarInput').click()">
                                <?php if (!empty($me['avatar'])): ?>
                                    <img src="<?= base_url('superadmin/settings/avatar/' . $me['avatar']) ?>" alt="Avatar" id="avatarPreview">
                                <?php else: ?>
                                    <div class="avatar-placeholder" id="avatarPlaceholder"><?= strtoupper(substr($me['first_name'] ?? 'S', 0, 1)) ?></div>
                                <?php endif; ?>
                                <div class="avatar-overlay">
                                    <i class="bi bi-camera-fill" style="color:#fff;font-size:1.3rem;margin:0;"></i>
                                </div>
                            </div>
                            <input type="file" id="avatarInput" accept="image/*" style="display:none" onchange="uploadAvatar(this)">
                            <div style="font-size:0.7rem;color:#94a3b8;text-align:center;margin-top:0.5rem;">Click para cambiar</div>
                        </div>
                        <!-- Form -->
                        <div style="flex:1;">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="sa-form-group">
                                        <label>Nombre</label>
                                        <input type="text" id="profileFirstName" value="<?= esc($me['first_name'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="sa-form-group">
                                        <label>Apellido</label>
                                        <input type="text" id="profileLastName" value="<?= esc($me['last_name'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="sa-form-group">
                                <label>Email</label>
                                <input type="email" id="profileEmail" value="<?= esc($me['email'] ?? '') ?>">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="sa-btn sa-btn-primary" onclick="saveProfile()">
                            <i class="bi bi-check2" style="margin:0;"></i> Guardar Cambios
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="sa-settings-card">
                <div class="card-header-sa">
                    <div class="header-icon" style="background:#dbeafe;color:#2563eb;"><i class="bi bi-info-circle" style="margin:0;"></i></div>
                    <h6>Información</h6>
                </div>
                <div class="card-body-sa">
                    <div style="font-size:0.8rem;color:var(--sa-muted);line-height:1.6;">
                        <p><strong>Rol:</strong> Super Administrador</p>
                        <p><strong>Acceso:</strong> Panel SaaS Global</p>
                        <p><strong>Miembro desde:</strong><br><?= date('d/m/Y', strtotime($me['created_at'] ?? 'now')) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tab: Seguridad -->
<div class="sa-tab-content" id="tab-security">
    <div class="row">
        <div class="col-lg-6">
            <div class="sa-settings-card">
                <div class="card-header-sa">
                    <div class="header-icon" style="background:#fef3c7;color:#d97706;"><i class="bi bi-key" style="margin:0;"></i></div>
                    <h6>Cambiar Contraseña</h6>
                </div>
                <div class="card-body-sa">
                    <div class="sa-form-group">
                        <label>Contraseña Actual</label>
                        <input type="password" id="currentPassword" placeholder="••••••••">
                    </div>
                    <div class="sa-form-group">
                        <label>Nueva Contraseña</label>
                        <input type="password" id="newPassword" placeholder="Mínimo 8 caracteres">
                    </div>
                    <div class="sa-form-group">
                        <label>Confirmar Contraseña</label>
                        <input type="password" id="confirmPassword" placeholder="••••••••">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button class="sa-btn sa-btn-primary" onclick="changePassword()">
                            <i class="bi bi-shield-check" style="margin:0;"></i> Actualizar Contraseña
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="sa-settings-card">
                <div class="card-header-sa">
                    <div class="header-icon" style="background:#fee2e2;color:#dc2626;"><i class="bi bi-exclamation-triangle" style="margin:0;"></i></div>
                    <h6>Zona de Seguridad</h6>
                </div>
                <div class="card-body-sa">
                    <div style="font-size:0.85rem;color:var(--sa-muted);line-height:1.7;">
                        <p><i class="bi bi-shield-fill-check me-1" style="color:#16a34a;"></i> Autenticación por contraseña activa</p>
                        <p><i class="bi bi-lock-fill me-1" style="color:#2563eb;"></i> Acceso restringido a panel SuperAdmin</p>
                        <p><i class="bi bi-journal-text me-1" style="color:#d97706;"></i> Todas las acciones son auditadas</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tab: Super Admins -->
<div class="sa-tab-content" id="tab-admins">
    <div class="sa-settings-card">
        <div class="card-header-sa" style="justify-content:space-between;">
            <div class="d-flex align-items-center gap-2">
                <div class="header-icon" style="background:#ede9fe;color:#7c3aed;"><i class="bi bi-people-fill" style="margin:0;"></i></div>
                <h6>Super Administradores</h6>
            </div>
            <button class="sa-btn sa-btn-outline" onclick="toggleAddForm()">
                <i class="bi bi-plus-lg" style="margin:0;"></i> Agregar
            </button>
        </div>
        <div class="card-body-sa">
            <!-- Add Form (hidden) -->
            <div id="addAdminForm" style="display:none;margin-bottom:1.5rem;padding:1.25rem;background:var(--sa-bg);border-radius:10px;border:1px solid var(--sa-border);">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="sa-form-group" style="margin:0;">
                            <label>Nombre</label>
                            <input type="text" id="newAdminFirstName" placeholder="Nombre">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="sa-form-group" style="margin:0;">
                            <label>Apellido</label>
                            <input type="text" id="newAdminLastName" placeholder="Apellido">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="sa-form-group" style="margin:0;">
                            <label>Email</label>
                            <input type="email" id="newAdminEmail" placeholder="admin@email.com">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="sa-form-group" style="margin:0;">
                            <label>Contraseña</label>
                            <input type="password" id="newAdminPassword" placeholder="Mínimo 8 chars">
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button class="sa-btn sa-btn-outline" onclick="toggleAddForm()">Cancelar</button>
                    <button class="sa-btn sa-btn-primary" onclick="addAdmin()">
                        <i class="bi bi-person-plus" style="margin:0;"></i> Crear Super Admin
                    </button>
                </div>
            </div>
            <!-- Admin List -->
            <div id="adminList">
                <div class="text-center py-3"><div class="spinner-border spinner-border-sm text-secondary" role="status"></div></div>
            </div>
        </div>
    </div>
</div>

<script>
const BASE = '<?= base_url() ?>';
const MY_ID = <?= (int) session()->get('user_id') ?>;

// ── Tabs ──
function switchTab(tab) {
    document.querySelectorAll('.sa-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sa-tab-content').forEach(c => c.classList.remove('active'));
    event.target.closest('.sa-tab').classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
    if (tab === 'admins') loadAdmins();
}

// ── Profile ──
function saveProfile() {
    const data = new FormData();
    data.append('first_name', document.getElementById('profileFirstName').value);
    data.append('last_name', document.getElementById('profileLastName').value);
    data.append('email', document.getElementById('profileEmail').value);

    fetch(`${BASE}/superadmin/settings/update-profile`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: d.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
        } else {
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.message, showConfirmButton: false, timer: 3000 });
        }
    }).catch(() => Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Error de conexión', showConfirmButton: false, timer: 3000 }));
}

// ── Avatar ──
function uploadAvatar(input) {
    if (!input.files[0]) return;
    const data = new FormData();
    data.append('avatar', input.files[0]);

    fetch(`${BASE}/superadmin/settings/upload-avatar`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            const container = document.querySelector('.sa-avatar-upload');
            const placeholder = document.getElementById('avatarPlaceholder');
            if (placeholder) placeholder.remove();
            let img = document.getElementById('avatarPreview');
            if (!img) {
                img = document.createElement('img');
                img.id = 'avatarPreview';
                container.insertBefore(img, container.firstChild);
            }
            img.src = d.url;
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: d.message, showConfirmButton: false, timer: 2000, timerProgressBar: true });
        } else {
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.message, showConfirmButton: false, timer: 3000 });
        }
    });
}

// ── Password ──
function changePassword() {
    const data = new FormData();
    data.append('current_password', document.getElementById('currentPassword').value);
    data.append('new_password', document.getElementById('newPassword').value);
    data.append('confirm_password', document.getElementById('confirmPassword').value);

    fetch(`${BASE}/superadmin/settings/update-password`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: d.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
            document.getElementById('currentPassword').value = '';
            document.getElementById('newPassword').value = '';
            document.getElementById('confirmPassword').value = '';
        } else {
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.message, showConfirmButton: false, timer: 3000 });
        }
    });
}

// ── Admins ──
function toggleAddForm() {
    const f = document.getElementById('addAdminForm');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
}

function loadAdmins() {
    fetch(`${BASE}/superadmin/settings/list-admins`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        if (!d.success) return;
        const list = document.getElementById('adminList');
        if (d.admins.length === 0) {
            list.innerHTML = '<div class="sa-empty">No hay Super Administradores registrados.</div>';
            return;
        }
        const colors = ['#1C2434', '#7c3aed', '#2563eb', '#d97706', '#dc2626'];
        list.innerHTML = d.admins.map((a, i) => {
            const initial = (a.first_name || '?')[0].toUpperCase();
            const isMe = parseInt(a.id) === MY_ID;
            const bg = colors[i % colors.length];
            return `
            <div class="sa-admin-row">
                <div class="admin-avatar" style="background:${bg}22;color:${bg};">${initial}</div>
                <div style="flex:1;min-width:0;">
                    <div class="fw-semibold" style="font-size:0.9rem;color:#0f172a;">${a.first_name} ${a.last_name || ''}${isMe ? ' <span style="font-size:0.7rem;background:#dcfce7;color:#166534;padding:2px 8px;border-radius:4px;margin-left:6px;">Tú</span>' : ''}</div>
                    <div style="font-size:0.75rem;color:#94a3b8;">${a.email} · Desde ${a.created_at ? a.created_at.split(' ')[0] : 'N/A'}</div>
                </div>
                ${!isMe ? `<button class="sa-btn sa-btn-danger" style="font-size:0.75rem;padding:0.35rem 0.75rem;" onclick="removeAdmin(${a.assignment_id}, '${a.first_name}')"><i class="bi bi-trash3" style="margin:0;"></i></button>` : ''}
            </div>`;
        }).join('');
    });
}

function addAdmin() {
    const data = new FormData();
    data.append('first_name', document.getElementById('newAdminFirstName').value);
    data.append('last_name', document.getElementById('newAdminLastName').value);
    data.append('email', document.getElementById('newAdminEmail').value);
    data.append('password', document.getElementById('newAdminPassword').value);

    fetch(`${BASE}/superadmin/settings/add-admin`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(r => r.json())
    .then(d => {
        if (d.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: d.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
            toggleAddForm();
            ['newAdminFirstName','newAdminLastName','newAdminEmail','newAdminPassword'].forEach(id => document.getElementById(id).value = '');
            loadAdmins();
        } else {
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.message, showConfirmButton: false, timer: 3000 });
        }
    });
}

function removeAdmin(assignmentId, name) {
    Swal.fire({
        title: '¿Eliminar Super Admin?',
        html: `<p style="color:var(--sa-muted);">Se eliminará a <strong>${name}</strong> como Super Administrador.</p>`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#dc2626', cancelButtonColor: '#94a3b8',
        confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar'
    }).then(r => {
        if (!r.isConfirmed) return;
        const data = new FormData();
        data.append('assignment_id', assignmentId);
        fetch(`${BASE}/superadmin/settings/remove-admin`, { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(d => {
            if (d.success) {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: d.message, showConfirmButton: false, timer: 3000, timerProgressBar: true });
                loadAdmins();
            } else {
                Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: d.message, showConfirmButton: false, timer: 3000 });
            }
        });
    });
}

// Auto-load admins tab data
document.addEventListener('DOMContentLoaded', () => {
    const hash = window.location.hash.replace('#', '');
    if (['profile', 'security', 'admins'].includes(hash)) {
        document.querySelectorAll('.sa-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.sa-tab-content').forEach(c => c.classList.remove('active'));
        document.querySelectorAll('.sa-tab')[hash === 'profile' ? 0 : hash === 'security' ? 1 : 2].classList.add('active');
        document.getElementById('tab-' + hash).classList.add('active');
        if (hash === 'admins') loadAdmins();
    }
});
</script>
<?php $this->endSection() ?>
