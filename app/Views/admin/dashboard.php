<?php $this->extend('layout/main') ?>

<?php $this->section('styles') ?>
<style>
    /* ── Greeting Hero ── */
    .greeting-hero {
        background: linear-gradient(135deg, #f0f4fb 0%, #e6edf8 35%, #dce5f4 60%, #edf2fa 100%);
        border-radius: 16px;
        padding: 2rem 2.25rem 1.75rem;
        margin-bottom: 1.75rem;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(29, 76, 157, 0.08);
    }

    .greeting-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(29, 76, 157, 0.06) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .greeting-hero::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -10%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(29, 76, 157, 0.04) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .greeting-top-bar {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .live-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
        border-radius: 20px;
        padding: 0.3rem 0.75rem;
        font-size: 0.7rem;
        font-weight: 700;
        color: #059669;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        animation: livePulse 2s ease-in-out infinite;
    }

    @keyframes livePulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.85); }
    }

    .greeting-meta {
        font-size: 0.78rem;
        font-weight: 500;
        color: #8b8fa3;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .greeting-meta-dot {
        width: 3px;
        height: 3px;
        border-radius: 50%;
        background: #c4c7d4;
    }

    .greeting-title {
        position: relative;
        z-index: 1;
        margin: 0 0 0.5rem;
        font-size: 2rem;
        font-weight: 800;
        color: #1e293b;
        line-height: 1.2;
        letter-spacing: -0.5px;
    }

    .greeting-name {
        background: linear-gradient(135deg, #1D4C9D 0%, #2a62c4 50%, #3F67AC 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .greeting-subtitle {
        position: relative;
        z-index: 1;
        font-size: 0.92rem;
        color: #64748b;
        margin: 0;
        font-weight: 400;
        line-height: 1.5;
    }

    @media (max-width: 576px) {
        .greeting-hero {
            padding: 1.5rem 1.25rem 1.25rem;
        }
        .greeting-title {
            font-size: 1.5rem;
        }
    }

    /* ── end Greeting Hero ── */


    /* KPI Modern Cards Style (Match User Request) */
    .kpi-modern-card {
        background: #ffffff;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 110px;
        position: relative;
        border: 1px solid #f1f5f9;
        transition: transform 0.2s;
    }

    .kpi-modern-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .kpi-modern-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
    }

    .kpi-modern-title {
        font-size: 0.85rem;
        font-weight: 500;
        color: #64748b;
    }

    .kpi-modern-perc {
        font-size: 0.75rem;
        font-weight: 600;
    }

    .kpi-modern-perc.positive {
        color: #10b981;
    }

    .kpi-modern-perc.negative {
        color: #ef4444;
    }

    .kpi-modern-body {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        width: 100%;
        margin-top: auto;
    }

    .kpi-modern-value {
        font-size: 1.7rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1;
    }

    .kpi-modern-chart {
        width: 80px;
        height: 40px;
    }

    /* Func Cards */
    .func-card-new {
        position: relative;
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        text-decoration: none;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .func-card-new:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }

    .func-card-blue {
        background: #eff6ff;
        border: 1px solid #dbeafe;
    }

    .func-card-yellow {
        background: #fffaf0;
        border: 1px solid #fef3c7;
    }

    .func-card-green {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
    }

    .func-card-purple {
        background: #faf5ff;
        border: 1px solid #f3e8ff;
    }

    .func-card-red {
        background: #fef2f2;
        border: 1px solid #fee2e2;
    }

    .func-card-pink {
        background: #fdf2f8;
        border: 1px solid #fce7f3;
    }

    .func-card-coral {
        background: #fff1f2;
        border: 1px solid #ffe4e6;
    }

    /* Rose */
    .func-card-indigo {
        background: #eef2ff;
        border: 1px solid #e0e7ff;
    }

    .fc-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 1rem;
        background: transparent;
    }

    .fc-icon.c-blue {
        color: #3b82f6;
    }

    .fc-icon.c-yellow {
        color: #d97706;
    }

    .fc-icon.c-green {
        color: #10b981;
    }

    .fc-icon.c-purple {
        color: #8b5cf6;
    }

    .fc-icon.c-red {
        color: #ef4444;
    }

    .fc-icon.c-pink {
        color: #db2777;
    }

    .fc-icon.c-coral {
        color: #e11d48;
    }

    .fc-icon.c-indigo {
        color: #6366f1;
    }

    .fc-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .fc-desc {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 1.5rem;
        line-height: 1.4;
    }

    .fc-stat-box {
        background: rgba(255, 255, 255, 0.6);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-top: auto;
    }

    .fc-stat-label {
        font-size: 0.7rem;
        color: #64748b;
        margin-bottom: 0.2rem;
        display: block;
        font-weight: 500;
    }

    .fc-stat-val {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
    }

    .fc-stat-val.c-blue {
        color: #3b82f6;
    }

    .fc-stat-val.c-yellow {
        color: #d97706;
    }

    .fc-stat-val.c-green {
        color: #10b981;
    }

    .fc-stat-val.c-purple {
        color: #8b5cf6;
    }

    .fc-stat-val.c-red {
        color: #ef4444;
    }

    .fc-stat-val.c-pink {
        color: #db2777;
    }

    .fc-stat-val.c-coral {
        color: #e11d48;
    }

    .fc-stat-val.c-indigo {
        color: #6366f1;
    }

    .hover-arrow {
        position: absolute;
        right: 1.25rem;
        top: 1.25rem;
        color: rgba(0, 0, 0, 0.15);
        font-size: 1.15rem;
        transition: transform 0.2s, color 0.2s;
    }

    .func-card-new:hover .hover-arrow {
        transform: translateX(3px);
        color: rgba(0, 0, 0, 0.4);
    }
</style>
<?php $this->endSection() ?>

<?php $this->section('content') ?>

<?php
$__db = \Config\Database::connect();
$__condoId = \App\Services\TenantService::getInstance()->getTenantId();
$__condo = $__db->table('condominiums')->where('id', $__condoId)->get()->getRowArray();

$__subStatus = $__condo['subscription_status'] ?? 'active';
$__graceUntil = $__condo['grace_until'] ?? null;
$__stripeSubId = $__condo['stripe_subscription_id'] ?? null;
$__expiresAt = $__condo['plan_expires_at'] ?? null;
$__globalStatus = $__condo['status'] ?? 'active';

$__isSuspended = ($__globalStatus === 'suspended' || in_array($__subStatus, ['suspended', 'canceled']));
$__isPastDue = ($__subStatus === 'past_due' && $__graceUntil && strtotime($__graceUntil) >= time());
$__isTrialExpired = (!$__stripeSubId && $__expiresAt && strtotime($__expiresAt) < time());

if ($__isTrialExpired) {
    $__isSuspended = true;
}
?>

<?php if ($__isSuspended): ?>
<!-- ── PANTALLA DE BLOQUEO ── -->
<div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.95); z-index: 1000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(8px);">
    <div style="background: white; border-radius: 12px; padding: 3rem 2rem; max-width: 500px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);">
        <?php if ($__isTrialExpired): ?>
            <div style="background: #fef2f2; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="bi bi-clock-history" style="font-size: 2rem; color: #ef4444;"></i>
            </div>
            <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 1rem;">Prueba Expirada</h3>
            <p style="color: #64748b; font-size: 1.05rem; margin-bottom: 2rem; line-height: 1.5;">
                El periodo de prueba de tu comunidad ha expirado. Por favor, selecciona un plan para continuar utilizando todas las funciones.
            </p>
        <?php else: ?>
            <div style="background: #fff1f2; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="bi bi-credit-card" style="font-size: 2rem; color: #e11d48;"></i>
            </div>
            <h3 style="font-weight: 700; color: #1e293b; margin-bottom: 1rem;">Suscripción Suspendida</h3>
            <p style="color: #64748b; font-size: 1.05rem; margin-bottom: 2rem; line-height: 1.5;">
                Tu suscripción ha sido suspendida por falta de pago o cancelación. Actualiza tu método de pago para restaurar el acceso.
            </p>
        <?php endif; ?>
        <a href="<?= base_url('admin/configuracion') ?>" class="btn w-100" style="background: #e11d48; color: white; font-weight: 600; padding: 0.75rem; border-radius: 8px; font-size: 1.05rem; transition: background 0.2s;">
            <i class="bi bi-gear-fill me-2"></i> Ir a Configuración
        </a>
        <div style="margin-top: 1rem; font-size: 0.8rem; color: #94a3b8;">Pago seguro con Stripe.</div>
    </div>
</div>
<?php endif; ?>

<?php if ($__isPastDue && !$__isSuspended): ?>
<!-- ── BANNER PAST DUE ── -->
<?php
$daysLeft = max(0, ceil((strtotime($__graceUntil) - time()) / 86400));
?>
<div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 1rem 1.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
    <div style="display: flex; align-items: center; gap: 1rem;">
        <i class="bi bi-exclamation-triangle-fill" style="color: #d97706; font-size: 1.5rem;"></i>
        <div>
            <h6 style="color: #92400e; font-weight: 700; margin: 0; font-size: 1rem;">Tu suscripción tiene un pago pendiente</h6>
            <p style="color: #b45309; margin: 0; font-size: 0.9rem;">Tienes <?= $daysLeft ?> día(s) de gracia para regularizar tu cuenta antes de que sea suspendida.</p>
        </div>
    </div>
    <a href="<?= base_url('admin/configuracion') ?>" class="btn" style="background: #d97706; color: white; font-weight: 600; padding: 0.5rem 1rem; border-radius: 6px; font-size: 0.9rem;">
        Actualizar pago
    </a>
</div>
<?php endif; ?>

<!-- ── Greeting Hero ── -->
<?php
// Determine greeting based on Mexico City time (America/Mexico_City)
$__mxTz = new \DateTimeZone('America/Mexico_City');
$__mxNow = new \DateTime('now', $__mxTz);
$__mxHour = (int) $__mxNow->format('G');
$__monthNames = [
    1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
    5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
    9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
];
$__mxMonth = $__monthNames[(int) $__mxNow->format('n')];
$__mxYear = $__mxNow->format('Y');

if ($__mxHour >= 5 && $__mxHour < 12) {
    $__greeting = 'Buenos días';
} elseif ($__mxHour >= 12 && $__mxHour < 19) {
    $__greeting = 'Buenas tardes';
} else {
    $__greeting = 'Buenas noches';
}
?>
<div class="greeting-hero" id="greetingHero">
    <div class="greeting-top-bar">
        <div class="live-badge">
            <span class="live-dot"></span>
            EN VIVO
        </div>
        <div class="greeting-meta">
            <span class="greeting-meta-dot"></span>
            <?= esc($__mxMonth . ' ' . $__mxYear) ?>
            <span class="greeting-meta-dot"></span>
            <?= esc($condo_name ?? 'Comunidad') ?>
        </div>
    </div>
    <h1 class="greeting-title"><?= esc($__greeting) ?>, <span class="greeting-name"><?= esc($admin_first_name ?? 'Administrador') ?></span></h1>
    <p class="greeting-subtitle">Todo está al día. Aquí está el resumen del condominio.</p>
</div>
<!-- ── END Greeting Hero ── -->


<!-- Top Cards -->
<div class="row g-4 mb-5">
    <!-- Unidades -->
    <div class="col-md-4">
        <div class="kpi-modern-card">
            <div class="kpi-modern-header">
                <span class="kpi-modern-title">Unidades (<?= esc($condo_name ?? 'Comunidad') ?>)</span>
                <span class="kpi-modern-perc positive">+ 4.1% &uarr;</span>
            </div>
            <div class="kpi-modern-body">
                <span class="kpi-modern-value"><?= number_format($metrics['total_units'] ?? 0) ?></span>
                <div class="kpi-modern-chart">
                    <!-- Smooth Pink line chart SVG -->
                    <svg viewBox="0 0 100 40" preserveAspectRatio="none" style="width:100%; height:100%;">
                        <path d="M 0,30 C 15,30 25,10 40,20 C 55,28 65,15 80,25 C 90,32 95,25 100,28" fill="none"
                            stroke="#ec4899" stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Residentes -->
    <div class="col-md-4">
        <div class="kpi-modern-card">
            <div class="kpi-modern-header">
                <span class="kpi-modern-title">Residentes Activos</span>
                <span class="kpi-modern-perc positive">+ 12.5% &uarr;</span>
            </div>
            <div class="kpi-modern-body">
                <span class="kpi-modern-value"><?= number_format($metrics['active_residents'] ?? 0) ?></span>
                <div class="kpi-modern-chart">
                    <!-- Blue Bar chart SVG -->
                    <svg viewBox="0 0 100 40" preserveAspectRatio="none" style="width:100%; height:100%;">
                        <rect x="0" y="22" width="12" height="18" fill="#3b82f6" rx="2" />
                        <rect x="22" y="10" width="12" height="30" fill="#3b82f6" rx="2" />
                        <rect x="44" y="28" width="12" height="12" fill="#3b82f6" rx="2" />
                        <rect x="66" y="5" width="12" height="35" fill="#3b82f6" rx="2" />
                        <rect x="88" y="15" width="12" height="25" fill="#3b82f6" rx="2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <!-- Eventos -->
    <div class="col-md-4">
        <div class="kpi-modern-card">
            <div class="kpi-modern-header">
                <span class="kpi-modern-title">Eventos del Mes</span>
                <span class="kpi-modern-perc negative">- 2.4% &darr;</span>
            </div>
            <div class="kpi-modern-body">
                <span class="kpi-modern-value"><?= number_format($metrics['events_month'] ?? 0) ?></span>
                <div class="kpi-modern-chart">
                    <!-- Smooth Green line chart SVG -->
                    <svg viewBox="0 0 100 40" preserveAspectRatio="none" style="width:100%; height:100%;">
                        <path d="M 0,25 C 20,25 30,35 50,15 C 70,-5 80,20 100,5" fill="none" stroke="#10b981"
                            stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Grid de Funciones -->
<div class="row g-4 d-flex align-items-stretch">
    <!-- Ingresos del mes (Antes Residentes) -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('admin/finanzas/movimientos') ?>"
            class="func-card-new func-card-green text-decoration-none">
            <i class="bi bi-arrow-right hover-arrow"></i>
            <div>
                <div class="fc-icon c-green"><i class="bi bi-wallet2"></i></div>
                <div class="fc-title">Ingresos</div>
                <div class="fc-desc">Monitorear liquidez y registros financieros del mes corriente</div>
            </div>
            <div class="fc-stat-box">
                <span class="fc-stat-label">Ingresos del mes</span>
                <span class="fc-stat-val c-green">MX$<?= number_format($metrics['income_month'] ?? 0, 2) ?></span>
            </div>
        </a>
    </div>
    <!-- Gastos del Mes -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('admin/finanzas/movimientos') ?>"
            class="func-card-new func-card-red text-decoration-none">
            <i class="bi bi-arrow-right hover-arrow"></i>
            <div>
                <div class="fc-icon c-red"><i class="bi bi-graph-down-arrow"></i></div>
                <div class="fc-title">Gastos</div>
                <div class="fc-desc">Visualizar pagos a proveedores y egresos del condominio</div>
            </div>
            <div class="fc-stat-box">
                <span class="fc-stat-label">Gastos del mes</span>
                <span class="fc-stat-val c-red">MX$
                    <?= number_format($metrics['gastos_month'] ?? 0, 2) ?>
                </span>
            </div>
        </a>
    </div>
    <!-- Paquetes -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('admin/paqueteria') ?>" class="func-card-new func-card-yellow text-decoration-none">
            <i class="bi bi-arrow-right hover-arrow"></i>
            <div>
                <div class="fc-icon c-yellow"><i class="bi bi-box-seam"></i></div>
                <div class="fc-title">Paquetes</div>
                <div class="fc-desc">Rastrear entregas, notificaciones y recogidas</div>
            </div>
            <div class="fc-stat-box">
                <span class="fc-stat-label">Pendientes de recogida</span>
                <span class="fc-stat-val c-yellow"><?= number_format($metrics['pending_packages'] ?? 0) ?></span>
            </div>
        </a>
    </div>



    <!-- Publicaciones -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('admin/anuncios') ?>" class="func-card-new func-card-purple text-decoration-none">
            <i class="bi bi-arrow-right hover-arrow"></i>
            <div>
                <div class="fc-icon c-purple"><i class="bi bi-megaphone"></i></div>
                <div class="fc-title">Publicaciones</div>
                <div class="fc-desc">Difundir mensajes y avisos importantes</div>
            </div>
            <div class="fc-stat-box">
                <span class="fc-stat-label">Este mes</span>
                <span class="fc-stat-val c-purple"><?= number_format($metrics['publications_month'] ?? 0) ?></span>
            </div>
        </a>
    </div>

    <!-- Tickets -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('admin/tickets') ?>" class="func-card-new func-card-red text-decoration-none">
            <i class="bi bi-arrow-right hover-arrow"></i>
            <div>
                <div class="fc-icon c-red"><i class="bi bi-exclamation-triangle"></i></div>
                <div class="fc-title">Tickets</div>
                <div class="fc-desc">Gestionar y rastrear solicitudes de mantenimiento e incidencias</div>
            </div>
            <div class="fc-stat-box">
                <span class="fc-stat-label">Tickets abiertos</span>
                <span class="fc-stat-val c-red">
                    <?= number_format($metrics['open_tickets'] ?? 0) ?>
                    <?php if (($metrics['open_tickets'] ?? 0) > 0): ?><i
                            class="bi bi-exclamation-triangle ms-1"></i><?php endif; ?>
                </span>
            </div>
        </a>
    </div>

    <!-- Reservas -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('admin/amenidades/reservas') ?>"
            class="func-card-new func-card-pink text-decoration-none">
            <i class="bi bi-arrow-right hover-arrow"></i>
            <div>
                <div class="fc-icon c-pink"><i class="bi bi-calendar-check"></i></div>
                <div class="fc-title">Reservas</div>
                <div class="fc-desc">Gestionar reservas de instalaciones y horarios</div>
            </div>
            <div class="fc-stat-box">
                <span class="fc-stat-label">Esta semana</span>
                <span class="fc-stat-val c-pink"><?= number_format($metrics['reservations_week'] ?? 0) ?></span>
            </div>
        </a>
    </div>

    <!-- Control de Acceso -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('admin/seguridad') ?>" class="func-card-new func-card-coral text-decoration-none">
            <i class="bi bi-arrow-right hover-arrow"></i>
            <div>
                <div class="fc-icon c-coral"><i class="bi bi-shield-check"></i></div>
                <div class="fc-title">Control de Acceso</div>
                <div class="fc-desc">Gestión de seguridad y permisos de entrada</div>
            </div>
            <div class="fc-stat-box">
                <span class="fc-stat-label">Entradas hoy</span>
                <span class="fc-stat-val c-coral"><?= number_format($metrics['today_visitors'] ?? 0) ?></span>
            </div>
        </a>
    </div>

    <!-- Códigos QR -->
    <div class="col-xl-3 col-lg-4 col-md-6">
        <a href="<?= base_url('admin/seguridad') ?>" class="func-card-new func-card-indigo text-decoration-none">
            <i class="bi bi-arrow-right hover-arrow"></i>
            <div>
                <div class="fc-icon c-indigo"><i class="bi bi-qr-code-scan"></i></div>
                <div class="fc-title">Códigos QR</div>
                <div class="fc-desc">Generar y gestionar códigos QR de acceso</div>
            </div>
            <div class="fc-stat-box d-flex justify-content-between align-items-center">
                <div>
                    <span class="fc-stat-label">Generados hoy</span>
                    <span class="fc-stat-val c-indigo"><?= number_format($metrics['qr_generated_today'] ?? 0) ?></span>
                </div>
                <!-- Extra insight -->
                <div class="text-end">
                    <span class="fc-stat-label" style="font-size:0.6rem;">Total Activos</span>
                    <span class="text-muted"
                        style="font-size:0.85rem; font-weight:600;"><?= number_format($metrics['qr_active'] ?? 0) ?></span>
                </div>
            </div>
        </a>
    </div>

</div>

<?php $this->endSection() ?>