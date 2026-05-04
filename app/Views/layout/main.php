<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AxisCondo - Panel</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            background-color: #EEF1F9;
        }

        .sidebar {
            height: 100vh;
            background: #1D4C9D;
            border-right: none;
            display: flex;
            flex-direction: column;
            width: 260px;
            flex-shrink: 0;
            z-index: 1050;
            position: relative;
        }

        .sidebar-brand-box {
            padding: 1rem 0.6rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
        }

        .condo-logo {
            width: 34px;
            height: 34px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: none;
        }

        .condo-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            overflow: hidden;
            flex: 1;
        }

        .condo-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #ffffff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
            margin-bottom: 0.1rem;
        }

        .condo-city {
            font-size: 0.65rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.55);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .menu-container {
            flex-grow: 1;
            overflow-y: auto;
            padding: 1.5rem 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, 0.15) transparent;
        }

        .menu-label {
            font-size: 0.7rem;
            color: rgba(255, 255, 255, 0.4);
            text-transform: uppercase;
            font-weight: 600;
            padding: 0.5rem 1.75rem;
            margin-top: 0.5rem;
            letter-spacing: 0.8px;
        }

        .nav-item {
            margin-bottom: 0.2rem;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            padding: 0.6rem 1.75rem;
            transition: all 0.2s ease-in-out;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            font-weight: 500;
            border-left: 3px solid transparent;
            text-decoration: none;
            border-radius: 0;
        }

        .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .nav-link.active-main {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
            border-left: 3px solid #ffffff;
            font-weight: 700;
        }

        .nav-link.active {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.15);
            border-left: 3px solid #ffffff;
            font-weight: 700;
        }

        .sidebar .bi {
            font-size: 1.15rem;
            margin-right: 0.85rem;
            width: 20px;
            text-align: center;
        }

        .sidebar .chevron {
            margin-left: auto;
            margin-right: 0;
            font-size: 0.75rem;
            transition: transform 0.3s ease;
        }

        /* Estilo para los submenús */
        .submenu {
            background: rgba(0, 0, 0, 0.08);
            padding-left: 0;
            list-style: none;
            margin: 0;
        }

        .submenu li {
            position: relative;
        }

        .submenu .nav-link {
            padding: 0.5rem 1.75rem 0.5rem 2.8rem;
            font-size: 0.85rem;
            border-left: 3px solid transparent;
            background: transparent;
            position: relative;
        }

        .submenu .nav-link .bi-circle {
            font-size: 0.4rem;
            margin-right: 0.75rem;
            width: auto;
        }

        .submenu .nav-link:hover {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.08);
        }

        .submenu .nav-link.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: #ffffff !important;
            border-left: 3px solid #ffffff;
            font-weight: 700;
        }

        /* Top Header Styles */
        .top-header {
            height: 70px;
            /* Header alto y limpio */
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 1.5rem;
            z-index: 1040;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.25rem;
        }

        .theme-toggle {
            color: #1e293b;
            font-size: 1.2rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .theme-toggle:hover {
            background: #f1f5f9;
        }

        .notification-icon {
            position: relative;
            color: #1e293b;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background 0.2s;
        }

        .notification-icon:hover {
            background: #f1f5f9;
        }

        .notification-icon .badge {
            position: absolute;
            top: 2px;
            right: 2px;
            font-size: 0.5rem;
            padding: 0.25em 0.4em;
        }

        .header-divider {
            height: 35px;
            width: 1px;
            background: #e2e8f0;
            margin: 0 0.25rem;
        }

        .header-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background 0.2s;
        }

        .header-profile:hover {
            background: #f1f5f9;
        }

        .header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: #e2e8f0;
            color: #0d6efd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .header-user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .header-user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
        }

        .header-user-role {
            font-size: 0.75rem;
            color: #64748b;
        }

        .main-wrapper {
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
            background-color: transparent;
            flex-grow: 1;
        }

        .content-scrollable {
            flex-grow: 1;
            overflow-y: auto;
            padding: 2rem;
            scrollbar-width: thin;
        }

        /* --- KOTI GLOBAL COMPONENTES (Recuperados) --- */
        .koti-header {
            background: #2f3a4d;
            color: white;
            padding: 1.5rem 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
        }

        .koti-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.25rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            height: 100%;
        }

        .koti-card-green {
            background: #f0fdf4;
            border-color: #dcfce7;
        }

        .koti-card-purple {
            background: #faf5ff;
            border-color: #f3e8ff;
        }

        .func-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 100%;
            transition: transform 0.2s;
        }

        .func-card:hover {
            border-color: #cbd5e1;
        }

        /* Condo Selector Dropdown Animation */
        @keyframes condoDropdownIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>

<body>

    <div class="d-flex" style="height: 100vh; overflow: hidden;">

        <!-- Sidebar -->
        <?php if (strpos(uri_string(), 'auth/select-tenant') !== 0): ?>
        <aside class="sidebar">
            <?php
            // Fetch community data for sidebar display
            $__currentCondoId = \App\Services\TenantService::getInstance()->getTenantId();
            $__isSuperAdmin = ($__currentCondoId === 0 || $__currentCondoId === null) && (session()->get('current_condominium_id') === 0);

            if ($__isSuperAdmin) {
                // SuperAdmin Global: mostrar branding SaaS, no datos de condominio
                $__sidebarName = 'AxisCondo';
                $__sidebarInitial = 'AC';
                $__sidebarCity = 'SaaS Platform';
                $__sidebarLogo = null;
                $__userCondos = [];
                $__hasMultipleCondos = false;
            } else {
                $__sidebarCondoModel = new \App\Models\Tenant\CondominiumModel();
                $__sidebarCondo = $__sidebarCondoModel->first() ?? [];
                $__sidebarName = trim((string) ($__sidebarCondo['name'] ?? 'Comunidad'));
                if ($__sidebarName === '')
                    $__sidebarName = 'Comunidad';
                $__sidebarInitial = strtoupper(substr($__sidebarName, 0, 2));
                // Parse city from address
                $__sidebarAddr = trim((string) ($__sidebarCondo['address'] ?? ''));
                $__sidebarAddrParts = array_values(array_filter(array_map('trim', explode(',', $__sidebarAddr)), fn($i) => $i !== ''));
                $__sidebarCity = $__sidebarAddrParts[1] ?? 'Sin definir';
                $__sidebarLogo = $__sidebarCondo['logo'] ?? null;

                // ── Cargar condominios del usuario para el selector ──
                $__userId = session()->get('user_id') ?? (session()->get('user')['id'] ?? null);
                $__userCondos = [];
                $__db = \Config\Database::connect();

                if ($__userId) {
                    // Usuario logueado: solo sus condominios
                    $__userCondos = $__db->table('user_condominium_roles AS ucr')
                        ->select('c.id, c.name, c.logo, c.address')
                        ->join('condominiums AS c', 'c.id = ucr.condominium_id')
                        ->where('ucr.user_id', $__userId)
                        ->where('c.deleted_at IS NULL')
                        ->orderBy('c.name', 'ASC')
                        ->get()
                        ->getResultArray();
                } else {
                    // Sin login web (dev mode): todos los condominios activos
                    $__userCondos = $__db->table('condominiums')
                        ->select('id, name, logo, address')
                        ->where('deleted_at IS NULL')
                        ->orderBy('name', 'ASC')
                        ->get()
                        ->getResultArray();
                }
                $__userCondosCount = count($__userCondos);
                $__hasMultipleCondos = $__userCondosCount > 1;
                $__canCreateCondo = true; // Admins can always create more
            }
            ?>

            <!-- Sidebar Brand / Condo Selector -->
            <div class="sidebar-brand-box position-relative" id="condoSelectorTrigger"
                style="cursor: pointer;" onclick="toggleCondoSelector()">
                <?php if ($__sidebarLogo): ?>
                    <img src="<?= base_url('api/v1/public/image/' . $__sidebarLogo) ?>" alt="Logo" class="condo-logo"
                        style="object-fit:cover;">
                <?php else: ?>
                    <div class="condo-logo"><?= esc($__sidebarInitial) ?></div>
                <?php endif; ?>
                <div class="condo-info" style="min-width: 0;">
                    <span class="condo-name" title="<?= esc($__sidebarName) ?>"><?= esc($__sidebarName) ?></span>
                    <span class="condo-city" title="<?= esc($__sidebarCity) ?>"><?= esc($__sidebarCity) ?></span>
                </div>
                <div
                    style="background: rgba(255,255,255,0.12); border-radius: 6px; padding: 4px 6px; margin-left: auto; flex-shrink: 0;">
                    <i class="bi bi-chevron-down"
                        style="color: #ffffff; font-size: 0.85rem; transition: transform 0.2s; display: block;"
                        id="condoChevron"></i>
                </div>
            </div>

            <?php if (!$__isSuperAdmin): ?>
                <!-- ── Dropdown de condominios ── -->
                <div id="condoSelectorDropdown" style="
                display: none;
                background: #ffffff;
                border-radius: 10px;
                box-shadow: 0 12px 40px rgba(0,0,0,0.15), 0 4px 12px rgba(0,0,0,0.1);
                position: absolute;
                left: 12px;
                right: 12px;
                top: 78px;
                z-index: 9999;
                overflow: hidden;
                animation: condoDropdownIn 0.15s ease-out;
            ">
                    <div style="max-height: 260px; overflow-y: auto; padding: 6px;">
                        <?php foreach ($__userCondos as $__uc):
                            $__ucName = trim($__uc['name'] ?? 'Sin nombre');
                            $__ucInitial = strtoupper(substr($__ucName, 0, 1));
                            $__ucAddr = trim($__uc['address'] ?? '');
                            $__ucParts = array_map('trim', explode(',', $__ucAddr));
                            $__ucCity = $__ucParts[1] ?? '';
                            $__ucIsActive = ((int) $__uc['id']) === ((int) $__currentCondoId);
                            ?>
                            <a href="<?= base_url('admin/switch-condo/' . $__uc['id']) ?>"
                                class="d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-2"
                                style="transition: background 0.15s; <?= $__ucIsActive ? 'background: #f0f7ff;' : '' ?>"
                                onmouseover="this.style.background='<?= $__ucIsActive ? '#e8f1fd' : '#f8fafc' ?>'"
                                onmouseout="this.style.background='<?= $__ucIsActive ? '#f0f7ff' : 'transparent' ?>'">
                                <!-- Logo or Initial badge -->
                                <?php if (!empty($__uc['logo'])): ?>
                                    <img src="<?= base_url('api/v1/public/image/' . $__uc['logo']) ?>"
                                        style="width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0; object-fit: cover; box-shadow: 0 0 10px rgba(0,0,0,0.1);"
                                        alt="<?= esc($__ucName) ?>">
                                <?php else: ?>
                                    <div style="
                                width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
                                display: flex; align-items: center; justify-content: center;
                                font-weight: 700; font-size: 0.8rem;
                                background: <?= $__ucIsActive ? '#3b82f6' : '#e2e8f0' ?>;
                                color: <?= $__ucIsActive ? '#ffffff' : '#475569' ?>;
                            "><?= esc($__ucInitial) ?></div>
                                <?php endif; ?>
                                <!-- Name + city -->
                                <div style="flex: 1; min-width: 0;">
                                    <div
                                        style="font-size: 0.85rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        <?= esc($__ucName) ?>
                                    </div>
                                    <?php if ($__ucCity): ?>
                                        <div style="font-size: 0.7rem; color: #94a3b8;"><?= esc($__ucCity) ?></div>
                                    <?php endif; ?>
                                </div>
                                <?php if ($__ucIsActive): ?>
                                    <div style="width: 8px; height: 8px; border-radius: 50%; background: #10b981; flex-shrink: 0;">
                                    </div>
                                <?php endif; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <!-- Separador + Crear Nueva Sociedad (Solo Fundadores) -->
                    <?php if (session()->get('is_owner')): ?>
                    <div style="border-top: 1px solid #f1f5f9; padding: 6px;">
                        <a href="<?= base_url('admin/onboarding') ?>"
                            class="d-flex align-items-center gap-3 text-decoration-none px-3 py-2 rounded-2"
                            style="transition: background 0.15s;" onmouseover="this.style.background='#f0fdf4'"
                            onmouseout="this.style.background='transparent'">
                            <div style="
                            width: 34px; height: 34px; border-radius: 8px; flex-shrink: 0;
                            display: flex; align-items: center; justify-content: center;
                            background: #f0fdf4; border: 1px solid rgba(16,185,129,0.2);
                        "><i class="bi bi-plus-lg" style="color: #10b981; font-size: 1rem; margin: 0;"></i></div>
                            <span style="font-size: 0.85rem; font-weight: 600; color: #10b981;">Crear Nueva Sociedad</span>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Menú Principal -->
            <div class="menu-container">
                <ul class="nav flex-column w-100" id="menu">
                    <?php if (strpos(uri_string(), 'admin') === 0): ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/dashboard') ?>"
                                class="nav-link <?= strpos(uri_string(), 'admin/dashboard') === 0 ? 'active-main' : '' ?>">
                                <i class="bi bi-house-door"></i> Dashboard
                            </a>
                        </li>

                        <!-- Amenidades Dropdown -->
                        <?php
                        $__pendingBookingsCount = 0;
                        $__amenBadgeTenantId = \App\Services\TenantService::getInstance()->getTenantId();
                        if ($__amenBadgeTenantId) {
                            $__pendingBookingsCount = (new \App\Models\Tenant\BookingModel())
                                ->where('condominium_id', $__amenBadgeTenantId)
                                ->where('status', 'pending')
                                ->countAllResults();
                        }
                        ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/amenidades') ?>"
                                class="nav-link d-flex align-items-center <?= strpos(uri_string(), 'amenidades') !== false ? 'active-main' : '' ?>"
                                data-bs-target="#amenidadesSub">
                                <i class="bi bi-gift"></i> Amenidades
                                <?php if ($__pendingBookingsCount > 0): ?>
                                    <span class="badge bg-warning rounded-pill ms-auto me-1"
                                        style="font-size:0.6rem;"><?= $__pendingBookingsCount ?></span>
                                <?php endif; ?>
                                <i class="bi bi-chevron-left chevron"></i>
                            </a>
                            <ul class="collapse <?= strpos(uri_string(), 'amenidades') !== false ? 'show' : '' ?> submenu"
                                id="amenidadesSub" data-bs-parent="#menu">
                                <li><a href="<?= base_url('admin/amenidades') ?>"
                                        class="nav-link <?= uri_string() == 'admin/amenidades' ? 'active' : '' ?>">
                                        <i class="bi bi-circle"></i> Directorio</a>
                                </li>
                                <li><a href="<?= base_url('admin/amenidades/reservas') ?>"
                                        class="nav-link <?= uri_string() == 'admin/amenidades/reservas' ? 'active' : '' ?>">
                                        <i class="bi bi-circle"></i> Reservas
                                        <?php if ($__pendingBookingsCount > 0): ?>
                                            <span class="badge bg-warning rounded-pill ms-auto"
                                                style="font-size:0.6rem;"><?= $__pendingBookingsCount ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <li><a href="<?= base_url('admin/amenidades/estadisticas') ?>"
                                        class="nav-link <?= uri_string() == 'admin/amenidades/estadisticas' ? 'active' : '' ?>">
                                        <i class="bi bi-circle"></i> Estadísticas</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/anuncios') ?>"
                                class="nav-link <?= strpos(uri_string(), 'admin/anuncios') === 0 ? 'active-main' : '' ?>">
                                <i class="bi bi-megaphone"></i> Anuncios
                            </a>
                        </li>
                        <?php
                        $__calEventsCount = 0;
                        $__calBadgeTenantId = \App\Services\TenantService::getInstance()->getTenantId();
                        if ($__calBadgeTenantId) {
                            $__calStartMonth = date('Y-m-01 00:00:00');
                            $__calEndMonth = date('Y-m-t 23:59:59');
                            $__calEventsCount = (new \App\Models\Tenant\CalendarEventModel())
                                ->where('start_datetime >=', $__calStartMonth)
                                ->where('start_datetime <=', $__calEndMonth)
                                ->countAllResults();
                        }
                        ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/calendario') ?>"
                                class="nav-link d-flex align-items-center <?= strpos(uri_string(), 'admin/calendario') === 0 ? 'active-main' : '' ?>">
                                <i class="bi bi-calendar3"></i> Calendario
                                <?php if ($__calEventsCount > 0): ?>
                                    <span class="badge bg-primary rounded-pill ms-auto"
                                        style="font-size:0.6rem;"><?= $__calEventsCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/documentos') ?>"
                                class="nav-link <?= strpos(uri_string(), 'admin/documentos') === 0 ? 'active-main' : '' ?>">
                                <i class="bi bi-file-earmark-text"></i> Documentos
                            </a>
                        </li>
                        <?php
                        $__activePollsCount = 0;
                        $__pollBadgeTenantId = \App\Services\TenantService::getInstance()->getTenantId();
                        if ($__pollBadgeTenantId) {
                            $__activePolls = (new \App\Models\Tenant\PollModel())
                                ->where('condominium_id', $__pollBadgeTenantId)
                                ->where('is_active', 1)
                                ->findAll();

                            $__nowTs = time();
                            foreach ($__activePolls as $__p) {
                                $__hasEndDate = !empty($__p['end_date']) && $__p['end_date'] !== '0000-00-00 00:00:00';
                                $__endTs = $__hasEndDate ? strtotime((string) $__p['end_date']) : 0;
                                if (!$__hasEndDate || $__endTs > $__nowTs) {
                                    $__activePollsCount++;
                                }
                            }
                        }
                        ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/encuestas') ?>"
                                class="nav-link d-flex align-items-center <?= strpos(uri_string(), 'admin/encuestas') === 0 ? 'active-main' : '' ?>">
                                <i class="bi bi-check2-square"></i> Encuestas
                                <?php if ($__activePollsCount > 0): ?>
                                    <span class="badge bg-success rounded-pill ms-auto"
                                        style="font-size:0.6rem;"><?= $__activePollsCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <!-- Finanzas Dropdown -->
                        <?php
                        $__pendingVouchersCount = 0;
                        $__finBadgeTenantId = \App\Services\TenantService::getInstance()->getTenantId();
                        if ($__finBadgeTenantId) {
                            $__pendingVouchersCount = (new \App\Models\Tenant\PaymentModel())
                                ->where('condominium_id', $__finBadgeTenantId)
                                ->where('status', 'pending')
                                ->where('deleted_at IS NULL')
                                ->countAllResults();
                        }
                        ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/finanzas/panel') ?>"
                                class="nav-link d-flex align-items-center <?= strpos(uri_string(), 'finanzas') !== false ? 'active-main' : '' ?>"
                                data-bs-target="#finanzasSub">
                                <i class="bi bi-credit-card"></i> Finanzas
                                <?php if ($__pendingVouchersCount > 0): ?>
                                    <span class="badge bg-warning rounded-pill ms-auto me-1"
                                        style="font-size:0.6rem;"><?= $__pendingVouchersCount ?></span>
                                <?php endif; ?>
                                <i class="bi bi-chevron-left chevron"></i>
                            </a>
                            <ul class="collapse <?= strpos(uri_string(), 'finanzas') !== false ? 'show' : '' ?> submenu"
                                id="finanzasSub" data-bs-parent="#menu">
                                <li><a href="<?= base_url('admin/finanzas/panel') ?>"
                                        class="nav-link <?= uri_string() == 'admin/finanzas/panel' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Panel
                                        de Control</a></li>
                                <li><a href="<?= base_url('admin/finanzas/nuevo-registro') ?>"
                                        class="nav-link <?= uri_string() == 'admin/finanzas/nuevo-registro' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Nuevo
                                        Registro</a></li>
                                <li><a href="<?= base_url('admin/finanzas/movimientos') ?>"
                                        class="nav-link <?= uri_string() == 'admin/finanzas/movimientos' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Movimientos
                                        Mensuales</a></li>
                                <li><a href="<?= base_url('admin/finanzas/pagos-por-unidad') ?>"
                                        class="nav-link <?= strpos(uri_string(), 'admin/finanzas/pagos-por-unidad') === 0 ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Pagos
                                        por Unidad
                                        <?php if ($__pendingVouchersCount > 0): ?>
                                            <span class="badge bg-warning rounded-pill ms-auto"
                                                style="font-size:0.6rem;"><?= $__pendingVouchersCount ?></span>
                                        <?php endif; ?>
                                    </a></li>
                                <li><a href="<?= base_url('admin/finanzas/morosidad') ?>"
                                        class="nav-link <?= uri_string() == 'admin/finanzas/morosidad' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Morosidad</a>
                                </li>
                                <li><a href="<?= base_url('admin/finanzas/extraordinarias') ?>"
                                        class="nav-link <?= strpos(uri_string(), 'admin/finanzas/extraordinarias') === 0 ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Cuotas
                                        Extraordinarias</a></li>
                                <li><a href="<?= base_url('admin/finanzas/historicos') ?>"
                                        class="nav-link <?= uri_string() == 'admin/finanzas/historicos' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Registros
                                        Históricos</a></li>
                            </ul>
                        </li>

                        <?php
                        $__pendingParcelsCount = 0;
                        $__parcelBadgeTenantId = \App\Services\TenantService::getInstance()->getTenantId();
                        if ($__parcelBadgeTenantId) {
                            $__pendingParcelsCount = (new \App\Models\Tenant\ParcelModel())
                                ->where('condominium_id', $__parcelBadgeTenantId)
                                ->whereNotIn('status', ['delivered', 'delivered_to_resident'])
                                ->countAllResults();
                        }
                        ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/paqueteria') ?>"
                                class="nav-link d-flex align-items-center <?= strpos(uri_string(), 'admin/paqueteria') === 0 ? 'active-main' : '' ?>">
                                <i class="bi bi-box-seam"></i> Paquetería
                                <?php if ($__pendingParcelsCount > 0): ?>
                                    <span class="badge bg-warning rounded-pill ms-auto"
                                        style="font-size:0.6rem;"><?= $__pendingParcelsCount ?></span>
                                <?php endif; ?>
                            </a>
                        </li>

                        <!-- Residentes Dropdown -->
                        <li class="nav-item">
                            <a href="<?= base_url('admin/residentes') ?>"
                                class="nav-link d-flex align-items-center <?= strpos(uri_string(), 'residentes') !== false ? 'active-main' : '' ?>"
                                data-bs-target="#residentesSub">
                                <i class="bi bi-people"></i> Residentes
                                <i class="bi bi-chevron-left chevron"></i>
                            </a>
                            <ul class="collapse <?= strpos(uri_string(), 'residentes') !== false ? 'show' : '' ?> submenu"
                                id="residentesSub" data-bs-parent="#menu">
                                <li><a href="<?= base_url('admin/residentes') ?>"
                                        class="nav-link <?= uri_string() == 'admin/residentes' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Directorio</a>
                                </li>
                                <li><a href="<?= base_url('admin/residentes/por-asignar') ?>"
                                        class="nav-link <?= uri_string() == 'admin/residentes/por-asignar' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Por
                                        Asignar</a></li>
                                <li><a href="<?= base_url('admin/residentes/invitaciones') ?>"
                                        class="nav-link <?= uri_string() == 'admin/residentes/invitaciones' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Invitaciones</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/seguridad') ?>"
                                class="nav-link <?= (strpos(uri_string(), 'seguridad') !== false || strpos(uri_string(), 'security') !== false) ? 'active-main' : '' ?>">
                                <i class="bi bi-shield-check"></i> Seguridad
                            </a>
                        </li>

                        <!-- Tickets Dropdown -->
                        <?php
                        $__badgeTenantId = \App\Services\TenantService::getInstance()->getTenantId();
                        $__activeTicketsCount = 0;
                        if ($__badgeTenantId) {
                            $__activeTicketsCount = (new \App\Models\Tenant\TicketModel())
                                ->where('condominium_id', $__badgeTenantId)
                                ->whereNotIn('status', ['resolved', 'closed'])
                                ->countAllResults();
                        }
                        ?>
                        <li class="nav-item">
                            <a href="<?= base_url('admin/tickets') ?>"
                                class="nav-link d-flex align-items-center <?= strpos(uri_string(), 'tickets') !== false ? 'active-main' : '' ?>"
                                data-bs-target="#ticketsSub">
                                <i class="bi bi-exclamation-circle"></i> Tickets
                                <?php if ($__activeTicketsCount > 0): ?>
                                    <span class="badge bg-danger rounded-circle p-1 ms-2"
                                        style="font-size:0.6rem;"><?= esc((string) $__activeTicketsCount) ?></span>
                                <?php endif; ?>
                                <i class="bi bi-chevron-left chevron"></i>
                            </a>
                            <ul class="collapse <?= strpos(uri_string(), 'tickets') !== false ? 'show' : '' ?> submenu"
                                id="ticketsSub" data-bs-parent="#menu">
                                <li><a href="<?= base_url('admin/tickets') ?>"
                                        class="nav-link <?= uri_string() == 'admin/tickets' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Lista</a>
                                </li>
                                <li><a href="<?= base_url('admin/tickets/panel') ?>"
                                        class="nav-link <?= uri_string() == 'admin/tickets/panel' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Panel</a>
                                </li>
                                <li><a href="<?= base_url('admin/tickets/metricas') ?>"
                                        class="nav-link <?= uri_string() == 'admin/tickets/metricas' ? 'active' : '' ?>"><i
                                            class="bi bi-circle"></i> Metricas</a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/unidades') ?>"
                                class="nav-link <?= strpos(uri_string(), 'unidades') !== false ? 'active-main' : '' ?>">
                                <i class="bi bi-building"></i> Unidades
                            </a>
                        </li>

                        <div class="menu-label mt-3">AJUSTES Y OTROS</div>

                        <li class="nav-item">
                            <a href="<?= base_url('admin/configuracion') ?>"
                                class="nav-link <?= strpos(uri_string(), 'admin/configuracion') === 0 ? 'active-main' : '' ?>">
                                <i class="bi bi-gear"></i> Configuración
                            </a>
                        </li>
                        
                       
                    <?php else: ?>
                        <!-- MÓDULOS DEL SUPER ADMIN -->
                        <li class="nav-item">
                            <a href="<?= base_url('superadmin/dashboard') ?>"
                                class="nav-link <?= strpos(uri_string(), 'superadmin/dashboard') !== false ? 'active-main' : '' ?>">
                                <i class="bi bi-speedometer2"></i> Dashboard SaaS
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('superadmin/settings') ?>"
                                class="nav-link <?= strpos(uri_string(), 'superadmin/settings') !== false ? 'active-main' : '' ?>">
                                <i class="bi bi-gear"></i> Configuración
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('superadmin/plans') ?>"
                                class="nav-link <?= strpos(uri_string(), 'superadmin/plans') !== false ? 'active-main' : '' ?>">
                                <i class="bi bi-credit-card-2-front"></i> Planes
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="text-center p-3" style="font-size: 0.7rem; color: rgba(255, 255, 255, 0.35);">
                AXISCONDO v1.1.0
            </div>
        </aside>
        <?php endif; ?>

        <!-- Main Wrapper (Header + Content) -->
        <main class="main-wrapper">

            <?php
            $___userModel = new \App\Models\Core\UserModel();
            $___currentUser = $___userModel->find(session()->get('user_id')) ?? [];
            $___currentFName = trim((string) ($___currentUser['first_name'] ?? 'Usuario'));
            $___currentLName = trim((string) ($___currentUser['last_name'] ?? ''));
            $___currentFullName = trim($___currentFName . ' ' . $___currentLName);
            $___currentEmail = $___currentUser['email'] ?? '';
            $___currentAvatar = $___currentUser['avatar'] ?? null;
            $___currentInitial = strtoupper(substr($___currentFName, 0, 1));
            ?>
            <!-- Top Header Ocupando el Right Space -->
            <header class="top-header">
                <div class="header-actions">

                    <div class="theme-toggle">
                        <i class="bi bi-moon"></i>
                    </div>

                    <div class="notification-icon" onclick="openNotificationsModal()" style="cursor: pointer;">
                        <i class="bi bi-bell"></i>
                        <span class="badge bg-danger d-none" id="global-notifications-badge">0</span>
                    </div>

                    <div class="header-divider"></div>

                    <div class="header-profile dropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php if ($___currentAvatar): ?>
                            <?php if ($__isSuperAdmin ?? false): ?>
                                <img src="<?= base_url('superadmin/settings/avatar/' . $___currentAvatar) ?>" alt="Avatar"
                                    class="header-avatar shadow-sm">
                            <?php else: ?>
                                <img src="<?= base_url('admin/configuracion/avatar/' . $___currentAvatar) ?>" alt="Avatar"
                                    class="header-avatar shadow-sm">
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="header-avatar text-white bg-primary shadow-sm"><?= esc($___currentInitial) ?></div>
                        <?php endif; ?>

                        <div class="header-user-info ms-1">
                            <span class="header-user-name"><?= esc($___currentFullName) ?></span>
                            <span class="header-user-role"><?= esc($___currentEmail) ?></span>
                        </div>
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 rounded-3">
                        <?php if (strpos(uri_string(), 'auth/select-tenant') !== 0): ?>
                            <?php if ($__isSuperAdmin ?? false): ?>
                                <li><a class="dropdown-item py-2" href="<?= base_url('superadmin/settings#profile') ?>"><i
                                            class="bi bi-person me-2"></i> Mi Perfil</a></li>
                                <li><a class="dropdown-item py-2" href="<?= base_url('superadmin/settings#admins') ?>"><i
                                            class="bi bi-gear me-2"></i> Configuración</a></li>
                            <?php else: ?>
                                <li><a class="dropdown-item py-2" href="#"
                                        onclick="if(window.location.href.includes('configuracion')){ document.querySelector('[data-tab=\'profile\']').click(); } else { window.location.href='<?= base_url('admin/configuracion?tab=profile') ?>'; }"><i
                                            class="bi bi-person me-2"></i> Mi Perfil</a></li>
                                <li><a class="dropdown-item py-2" href="<?= base_url('admin/configuracion') ?>"><i
                                            class="bi bi-gear me-2"></i> Configuración</a></li>
                            <?php endif; ?>
                        <?php endif; ?>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger py-2" href="<?= base_url('logout') ?>"><i
                                    class="bi bi-box-arrow-right me-2"></i> Cerrar sesión</a></li>
                    </ul>
                </div>
            </header>

            <!-- Scrollable Page Content -->
            <div class="content-scrollable">
                <?= $this->renderSection('content') ?>
            </div>
        </main>

    </div>

    <!-- Zona segura para desplegar Modales de Bootstrap fuera de contenedores con overflow -->
    <?= $this->renderSection('modals') ?>

    <!-- Modal Global de Notificaciones -->
    <div class="modal fade" id="globalNotificationsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 650px;">
            <div class="modal-content"
                style="border-radius: 16px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
                <div class="modal-header" style="border-bottom: 1px solid #e2e8f0; padding: 1.25rem 1.75rem;">
                    <div class="d-flex align-items-center gap-3">
                        <div
                            style="width: 48px; height: 48px; background: #3b82f6; border-radius: 12px; display: flex; align-items: center; justify-content: center; position: relative;">
                            <i class="bi bi-bell-fill text-white fs-5"></i>
                            <span
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger d-none"
                                style="font-size: 0.65rem;" id="modal-notif-count-badge">0</span>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark" style="font-size: 1.15rem;">Notificaciones</h5>
                            <p class="mb-0 text-secondary" style="font-size: 0.8rem;" id="modal-notif-subtitle">0 total,
                                0 no leídas</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div
                    style="background: #f8fafc; padding: 1rem 1.75rem; border-bottom: 1px solid #e2e8f0; display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
                    <div class="input-group input-group-sm" style="flex: 1; min-width: 200px;">
                        <span class="input-group-text bg-white border-end-0 border-light-subtle rounded-start-3"><i
                                class="bi bi-search text-muted"></i></span>
                        <input type="text" class="form-control border-start-0 border-light-subtle rounded-end-3"
                            placeholder="Buscar notificaciones..." style="font-size: 0.85rem;"
                            onkeyup="filterNotifications(this.value)">
                    </div>
                    <select class="form-select form-select-sm border-light-subtle text-secondary"
                        style="width: 140px; border-radius: 6px; font-size: 0.85rem;" id="notif-modal-filter"
                        onchange="renderNotifications()">
                        <option value="all">Todas</option>
                        <option value="unread">No leídas</option>
                    </select>
                    <button class="btn btn-sm text-white d-flex align-items-center gap-2"
                        style="background-color: #2B3548; font-size: 0.8rem; font-weight: 500; border-radius: 6px;"
                        onclick="markAllNotificationsAsRead()"><i class="bi bi-check2-all"></i> Marcar todas como
                        leídas</button>
                </div>

                <div class="modal-body p-0" style="max-height: 480px; overflow-y: auto;">
                    <div id="notifications-list-container">
                        <div class="p-4 text-center text-muted" style="font-size: 0.85rem;">
                            Cargando notificaciones...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Manejador para rotar la flecha de los submenus
            var collapseElements = document.querySelectorAll('.collapse.submenu');
            collapseElements.forEach(function (el) {
                el.addEventListener('show.bs.collapse', function () {
                    var toggle = document.querySelector('[data-bs-target="#' + el.id + '"] .chevron');
                    if (toggle) toggle.style.transform = 'rotate(-90deg)';
                });
                el.addEventListener('hide.bs.collapse', function () {
                    var toggle = document.querySelector('[data-bs-target="#' + el.id + '"] .chevron');
                    if (toggle) toggle.style.transform = 'rotate(0deg)';
                });

                // Si ya está abierto al cargar
                if (el.classList.contains('show')) {
                    var toggle = document.querySelector('[data-bs-target="#' + el.id + '"] .chevron');
                    if (toggle) toggle.style.transform = 'rotate(-90deg)';
                }
            });
        });

        // --- Lógica de Notificaciones UI Premium ---
        let globalNotifications = [];
        let globalNotificationsFilterSearch = '';

        function loadGlobalNotifications() {
            fetch('<?= base_url("admin/notifications") ?>', {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        globalNotifications = data.notifications;
                        updateNotificationBadges(data.unread_count, data.total_count);
                        renderNotifications();
                    }
                })
                .catch(err => console.error("Error loading notifications", err));
        }

        function updateNotificationBadges(unread, total) {
            const outBadge = document.getElementById('global-notifications-badge');
            const inBadge = document.getElementById('modal-notif-count-badge');
            const subTitle = document.getElementById('modal-notif-subtitle');

            if (outBadge) {
                outBadge.textContent = unread;
                if (unread > 0) outBadge.classList.remove('d-none');
                else outBadge.classList.add('d-none');
            }
            if (inBadge) {
                inBadge.textContent = unread;
                if (unread > 0) inBadge.classList.remove('d-none');
                else inBadge.classList.add('d-none');
            }
            if (subTitle) {
                subTitle.textContent = `${total} total, ${unread} no leídas`;
            }

            const selAll = document.querySelector('#notif-modal-filter option[value="all"]');
            if (selAll) selAll.textContent = `Todas ${total}`;
        }

        function filterNotifications(query) {
            globalNotificationsFilterSearch = query.toLowerCase();
            renderNotifications();
        }

        function renderNotifications() {
            const container = document.getElementById('notifications-list-container');
            const filterType = document.getElementById('notif-modal-filter').value;

            if (!container) return;

            const filtered = globalNotifications.filter(n => {
                if (filterType === 'unread' && n.read) return false;
                if (globalNotificationsFilterSearch !== '') {
                    return (n.title.toLowerCase().includes(globalNotificationsFilterSearch) ||
                        n.body.toLowerCase().includes(globalNotificationsFilterSearch));
                }
                return true;
            });

            if (filtered.length === 0) {
                container.innerHTML = `<div class="p-5 text-center text-muted" style="font-size: 0.85rem;"><i class="bi bi-box-seam fs-2 mb-2 d-block"></i>No se encontraron notificaciones.</div>`;
                return;
            }

            let html = '';
            filtered.forEach(n => {
                const isUnread = !n.read;
                const bgClass = isUnread ? 'bg-primary' : '';
                const bgLight = isUnread ? 'style="background-color: #f0f9ff;"' : 'style="background-color: #ffffff;"';
                const dotHtml = isUnread ? `<div style="width: 6px; height: 6px; background-color: #ef4444; border-radius: 50%; margin-right: 6px;"></div>` : '';

                // Determinar ícono según el tipo
                let iconHtml = '<i class="bi bi-bell text-primary fs-5"></i>';
                if (n.type === 'payment_status' || n.title.includes('Comprobante')) {
                    iconHtml = '<i class="bi bi-file-earmark-text text-success fs-5"></i>';
                } else if (n.type === 'reservation' || n.type === 'amenidad' || n.title.includes('Reserva')) {
                    iconHtml = '<i class="bi bi-calendar-check text-info fs-5"></i>';
                } else if (n.type === 'poll_activity' || n.title.includes('voto') || n.title.includes('encuesta')) {
                    iconHtml = '<i class="bi bi-bar-chart-line text-primary fs-5"></i>';
                } else if (n.type === 'ticket' || n.title.includes('Reporte') || n.title.includes('Ticket')) {
                    iconHtml = '<i class="bi bi-exclamation-circle text-danger fs-5"></i>';
                } else if (n.type === 'calendar_event_new' || n.type === 'calendar_event' || n.title.includes('evento') || n.title.includes('calendario')) {
                    iconHtml = '<i class="bi bi-calendar-event text-primary fs-5"></i>';
                }

                let wrapperStart = n.action_url ? `<a href="${n.action_url}" class="text-decoration-none notification-link" style="display: block; transition: background-color 0.2s;">` : `<div style="display: block;">`;
                let wrapperEnd = n.action_url ? `</a>` : `</div>`;

                // Efecto hover mejorado si es clickeable
                let itemClasses = "notification-item d-flex p-3 border-bottom";
                if (n.action_url) itemClasses += " hover-bg-light";

                html += `
                ${wrapperStart}
                <div class="${itemClasses}" ${bgLight}>
                    <div style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                         ${iconHtml}
                    </div>
                    <div class="ms-3 flex-grow-1">
                        <div class="d-flex align-items-center mb-1">
                            ${dotHtml}
                            <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.9rem;">${n.title}</h6>
                            <span class="ms-2 text-muted" style="font-size: 0.75rem;">${n.time_ago}</span>
                        </div>
                        <p class="mb-0 text-secondary lh-sm" style="font-size: 0.85rem;">${n.body}</p>
                    </div>
                </div>
                ${wrapperEnd}`;
            });
            container.innerHTML = html;
        }

        function openNotificationsModal() {
            var myModal = new bootstrap.Modal(document.getElementById('globalNotificationsModal'));
            myModal.show();
            // Cargar on open
            loadGlobalNotifications();
        }

        function markAllNotificationsAsRead() {
            fetch('<?= base_url("admin/notifications/mark-all-read") ?>', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Update frontend state
                        globalNotifications.forEach(n => n.read = true);
                        let total = globalNotifications.length;
                        updateNotificationBadges(0, total);
                        renderNotifications();
                    }
                });
        }

        // Cargar en segundo plano inicialmente
        document.addEventListener('DOMContentLoaded', function () {
            loadGlobalNotifications();
        });

        // ── Condo Selector Toggle ──
        function toggleCondoSelector() {
            const dd = document.getElementById('condoSelectorDropdown');
            const chev = document.getElementById('condoChevron');
            if (!dd) return;
            const isOpen = dd.style.display !== 'none';
            dd.style.display = isOpen ? 'none' : 'block';
            if (chev) chev.style.transform = isOpen ? '' : 'rotate(180deg)';
        }

        // Close dropdown on outside click
        document.addEventListener('click', function (e) {
            const dd = document.getElementById('condoSelectorDropdown');
            const trigger = document.getElementById('condoSelectorTrigger');
            if (!dd || !trigger) return;
            if (!trigger.contains(e.target) && !dd.contains(e.target)) {
                dd.style.display = 'none';
                const chev = document.getElementById('condoChevron');
                if (chev) chev.style.transform = '';
            }
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>