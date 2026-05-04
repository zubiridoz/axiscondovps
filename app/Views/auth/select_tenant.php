<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Seleccionar Condominio<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body p-5">
                    <div class="text-center mb-4">

                        <h3 class="fw-bold">Selecciona tu Condominio</h3>
                        <p class="text-muted">Tienes acceso a múltiples condominios. Por favor, selecciona a cuál deseas
                            ingresar.</p>
                    </div>

                    <div class="list-group list-group-flush mt-4">
                        <?php if (isset($tenants) && is_array($tenants)): ?>
                            <?php foreach ($tenants as $tenant): ?>
                                <?php
                                $tenantId = $tenant['condominium_id'] ?? null;
                                $tenantName = $tenant['condominium_name'] ?? 'Super Admin Global';
                                $roleName = $tenant['role_name'] ?? 'Usuario';
                                $logo = $tenant['logo'] ?? null;
                                $initial = strtoupper(substr($tenantName, 0, 1));
                                ?>
                                <a href="<?= base_url('auth/select-tenant/' . ($tenantId ? $tenantId : '0')) ?>"
                                    class="list-group-item list-group-item-action d-flex align-items-center p-3 rounded-3 mb-2 border">
                                    <?php if (!empty($logo)): ?>
                                        <img src="<?= base_url('api/v1/public/image/' . $logo) ?>" class="rounded me-3 shadow-sm"
                                            style="width: 48px; height: 48px; object-fit: cover;" alt="<?= esc($tenantName) ?>">
                                    <?php else: ?>
                                        <div class="bg-light rounded p-3 me-3 d-flex align-items-center justify-content-center shadow-sm"
                                            style="width: 48px; height: 48px;">
                                            <span class="fw-bold fs-5 text-secondary"><?= esc($initial) ?></span>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 fw-bold"><?= esc($tenantName) ?></h6>
                                        <small class="text-muted"><i class="fas fa-user-shield me-1"></i>
                                            <?= esc($roleName) ?></small>
                                    </div>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                                    <i class="bi bi-buildings" style="font-size: 2.5rem; color: #94a3b8;"></i>
                                </div>
                                <h4 style="font-weight: 700; color: #1e293b; margin-bottom: 0.5rem;">No tienes comunidades activas</h4>
                                <p style="color: #64748b; font-size: 0.95rem; margin-bottom: 2rem; max-width: 300px; margin-left: auto; margin-right: auto;">
                                    Actualmente no estás asignado a ninguna comunidad o han sido eliminadas.
                                </p>
                                <a href="<?= base_url('admin/onboarding') ?>" class="btn px-4 py-2" style="background: #1D4C9D; color: white; font-weight: 600; border-radius: 0.5rem; transition: background 0.2s;">
                                    <i class="bi bi-plus-lg me-2"></i> Crear nueva comunidad
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>