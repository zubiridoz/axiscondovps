<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AxisCondo — Plataforma Inteligente de Gestión Condominial</title>
    <meta name="description"
        content="Administra tu condominio con tecnología de clase mundial. Finanzas, seguridad, comunicación y más en una sola plataforma.">
    <link rel="icon" href="<?= base_url('favicon.ico') ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('landing/css/landing.css') ?>">
</head>

<body>

    <!-- ╔══════════════════════════════════════╗
     ║          NAVIGATION BAR             ║
     ╚══════════════════════════════════════╝ -->
    <nav class="ln-nav" id="main-nav">
        <div class="container">
            <a href="#" class="ln-nav-logo">
                <img src="<?= base_url('landing/img/logo-icon.png') ?>" alt="AxisCondo"
                    onerror="this.style.display='none'">
                AxisCondo
            </a>
            <div class="ln-nav-links" id="nav-links">
                <a href="#features">Funcionalidades</a>
                <a href="#showcase">Plataforma</a>
                <a href="#benefits">Beneficios</a>
                <a href="<?= base_url('login') ?>" class="ln-nav-cta">Iniciar Sesión</a>
            </div>
            <button class="ln-nav-toggle" id="nav-toggle" aria-label="Menú">
                <span></span><span></span><span></span>
            </button>
        </div>
    </nav>

    <!-- ╔══════════════════════════════════════╗
     ║             HERO SECTION            ║
     ╚══════════════════════════════════════╝ -->
    <section class="ln-hero" id="hero">
        <div class="ln-hero-blob ln-hero-blob--1"></div>
        <div class="ln-hero-blob ln-hero-blob--2"></div>
        <div class="container">
            <div class="ln-hero-content">
                <div class="ln-hero-badge">
                    <i class="bi bi-shield-check"></i>
                    <span>Plataforma SaaS</span> · Segura & Confiable
                </div>
                <h1>
                    Gestión condominial<br>
                    <span class="highlight">inteligente y moderna</span>
                </h1>
                <p class="ln-hero-sub">
                    Simplifica la administración de tu condominio con herramientas avanzadas de finanzas, seguridad,
                    comunicación y reservas — todo en un solo lugar.
                </p>
                <div class="ln-hero-actions">
                    <a href="<?= base_url('login') ?>" class="btn-primary">
                        <i class="bi bi-rocket-takeoff"></i> Probar Sistema
                    </a>
                    <a href="#features" class="btn-outline">
                        <i class="bi bi-play-circle"></i> Conocer más
                    </a>
                </div>
                <div class="ln-hero-stats">
                    <div class="ln-hero-stat">
                        <strong data-count="500">0</strong>
                        <span>Unidades activas</span>
                    </div>
                    <div class="ln-hero-stat">
                        <strong data-count="98">0%</strong>
                        <span>Satisfacción</span>
                    </div>
                    <div class="ln-hero-stat">
                        <strong data-count="24">0/7</strong>
                        <span>Soporte</span>
                    </div>
                </div>
            </div>
            <div class="ln-hero-visual">
                <div class="ln-hero-img-wrap">
                    <img src="<?= base_url('landing/img/dashboard-mockup.png') ?>" alt="Dashboard CondomiNet">
                </div>
                <div class="ln-hero-float-badge ln-hero-float-badge--1">
                    <div class="icon icon-green"><i class="bi bi-check-lg"></i></div>
                    <div><strong>Pago recibido</strong><br><small style="color: #64748b;">$1,250.00 MXN</small></div>
                </div>
                <div class="ln-hero-float-badge ln-hero-float-badge--2">
                    <div class="icon icon-blue"><i class="bi bi-shield-lock"></i></div>
                    <div><strong>Acceso QR</strong><br><small style="color: #64748b;">Verificado</small></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ╔══════════════════════════════════════╗
     ║          FEATURES SECTION           ║
     ╚══════════════════════════════════════╝ -->
    <section class="ln-features" id="features">
        <div class="container">
            <div class="ln-features-header reveal">
                <div class="section-label"><i class="bi bi-grid-3x3-gap"></i> Funcionalidades</div>
                <h2 class="section-title">Todo lo que necesitas para<br>administrar tu condominio</h2>
                <p class="section-desc">Una suite completa de herramientas diseñadas específicamente para la gestión
                    moderna de comunidades residenciales.</p>
            </div>
            <div class="ln-features-grid">
                <div class="ln-feature-card reveal">
                    <div class="ln-feature-icon fi-blue"><i class="bi bi-cash-stack"></i></div>
                    <h3>Finanzas y Cobranza</h3>
                    <p>Control total de cuotas, pagos, estados de cuenta y morosidad. Recibos automáticos y reportes en
                        tiempo real.</p>
                </div>
                <div class="ln-feature-card reveal">
                    <div class="ln-feature-icon fi-green"><i class="bi bi-qr-code-scan"></i></div>
                    <h3>Acceso con QR</h3>
                    <p>Sistema de acceso inteligente con códigos QR para visitantes. Registro de entradas y salidas en
                        tiempo real.</p>
                </div>
                <div class="ln-feature-card reveal">
                    <div class="ln-feature-icon fi-purple"><i class="bi bi-megaphone"></i></div>
                    <h3>Muro Comunitario</h3>
                    <p>Publica anuncios, comparte archivos y mantén a todos los residentes informados con un feed tipo
                        red social.</p>
                </div>
                <div class="ln-feature-card reveal">
                    <div class="ln-feature-icon fi-amber"><i class="bi bi-calendar2-event"></i></div>
                    <h3>Reserva de Amenidades</h3>
                    <p>Gestiona salones, albercas y canchas con un sistema de reservas inteligente con horarios y
                        aprobaciones.</p>
                </div>
                <div class="ln-feature-card reveal">
                    <div class="ln-feature-icon fi-rose"><i class="bi bi-ticket-perforated"></i></div>
                    <h3>Reportes y Tickets</h3>
                    <p>Los residentes reportan problemas desde su app. Seguimiento completo con conversación, archivos y
                        estados.</p>
                </div>
                <div class="ln-feature-card reveal">
                    <div class="ln-feature-icon fi-sky"><i class="bi bi-box-seam"></i></div>
                    <h3>Paquetería</h3>
                    <p>Registro de paquetes entrantes con notificaciones automáticas al residente y confirmación de
                        entrega.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ╔══════════════════════════════════════╗
     ║         SHOWCASE SECTION            ║
     ╚══════════════════════════════════════╝ -->
    <section class="ln-showcase" id="showcase">
        <div class="container">
            <div class="ln-showcase-images reveal">
                <div class="ln-showcase-dashboard">
                    <img src="<?= base_url('landing/img/dashboard-mockup.png') ?>" alt="Panel de Administración">
                </div>
                <div class="ln-showcase-mobile">
                    <img src="<?= base_url('landing/img/mobile-mockup.png') ?>" alt="App Residente">
                </div>
            </div>
            <div class="ln-showcase-content reveal">
                <div class="section-label"><i class="bi bi-display"></i> Plataforma</div>
                <h2 class="section-title">Dos experiencias, una sola plataforma</h2>
                <p class="section-desc">Panel web para administradores y app móvil para residentes. Diseñados para
                    trabajar juntos de forma fluida.</p>
                <ul class="check-list">
                    <li>
                        <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                        <div><strong>Panel de Administración</strong> — Dashboard completo con métricas, finanzas,
                            seguridad y gestión de residentes en tiempo real.</div>
                    </li>
                    <li>
                        <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                        <div><strong>App para Residentes</strong> — Los residentes pagan cuotas, generan QR de acceso,
                            reportan problemas y reservan amenidades desde su celular.</div>
                    </li>
                    <li>
                        <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                        <div><strong>App para Caseta</strong> — El personal de seguridad valida accesos QR, registra
                            visitantes y gestiona paquetería en un solo dispositivo.</div>
                    </li>
                    <li>
                        <div class="check-icon"><i class="bi bi-check-lg"></i></div>
                        <div><strong>Notificaciones Push</strong> — Alertas instantáneas de pagos, accesos, anuncios y
                            más directamente al celular del residente.</div>
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <!-- ╔══════════════════════════════════════╗
     ║         BENEFITS SECTION            ║
     ╚══════════════════════════════════════╝ -->
    <section class="ln-benefits" id="benefits">
        <div class="container">
            <div class="ln-benefits-header reveal">
                <div class="section-label"><i class="bi bi-star"></i> Beneficios</div>
                <h2 class="section-title">¿Por qué elegir CondomiNet?</h2>
                <p class="section-desc">Tecnología diseñada para resolver los problemas reales de la administración
                    condominial moderna.</p>
            </div>
            <div class="ln-benefits-grid">
                <div class="ln-benefit-card reveal">
                    <div class="ln-benefit-icon"><i class="bi bi-lightning-charge"></i></div>
                    <h3>Implementación Rápida</h3>
                    <p>Tu condominio estará operando en menos de 24 horas. Sin instalaciones complicadas ni hardware
                        especial.</p>
                </div>
                <div class="ln-benefit-card reveal">
                    <div class="ln-benefit-icon"><i class="bi bi-shield-lock"></i></div>
                    <h3>Seguridad Total</h3>
                    <p>Datos cifrados, acceso por roles y auditoría completa. Tu información siempre protegida.</p>
                </div>
                <div class="ln-benefit-card reveal">
                    <div class="ln-benefit-icon"><i class="bi bi-graph-up-arrow"></i></div>
                    <h3>Reducción de Morosidad</h3>
                    <p>Hasta un 60% menos morosidad gracias a recordatorios automáticos y pagos digitales simplificados.
                    </p>
                </div>
                <div class="ln-benefit-card reveal">
                    <div class="ln-benefit-icon"><i class="bi bi-headset"></i></div>
                    <h3>Soporte Dedicado</h3>
                    <p>Equipo de soporte especializado disponible para ayudarte con cualquier duda o configuración.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ╔══════════════════════════════════════╗
     ║           CTA SECTION               ║
     ╚══════════════════════════════════════╝ -->
    <section class="ln-cta" id="cta">
        <div class="container">
            <div class="ln-cta-box reveal">
                <div class="section-label"><i class="bi bi-rocket-takeoff"></i> Comienza hoy</div>
                <h2 class="section-title">¿Listo para modernizar tu condominio?</h2>
                <p class="section-desc">Únete a los administradores que ya transformaron su gestión condominial con
                    CondomiNet.</p>
                <div class="ln-cta-actions">
                    <a href="<?= base_url('login') ?>" class="btn-primary-solid">
                        <i class="bi bi-box-arrow-in-right"></i> Acceder al Sistema
                    </a>
                    <a href="#features" class="btn-outline"
                        style="color: var(--gray-500); border-color: var(--gray-300);">
                        <i class="bi bi-info-circle"></i> Ver funcionalidades
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- ╔══════════════════════════════════════╗
     ║              FOOTER                 ║
     ╚══════════════════════════════════════╝ -->
    <footer class="ln-footer">
        <div class="container">
            <div class="ln-footer-grid">
                <div class="ln-footer-brand">
                    <div class="ln-nav-logo" style="color: #fff;">
                        <img src="<?= base_url('landing/img/logo-icon.png') ?>" alt="CondomiNet"
                            onerror="this.style.display='none'" style="width:32px; height:32px; border-radius:8px;">
                        AxisCondo
                    </div>
                    <p>Plataforma integral de gestión condominial. Simplifica la administración, mejora la comunicación
                        y automatiza los procesos de tu comunidad.</p>
                </div>
                <div>
                    <h4>Plataforma</h4>
                    <ul>
                        <li><a href="#features">Funcionalidades</a></li>
                        <li><a href="#showcase">Panel Admin</a></li>
                        <li><a href="#showcase">App Residente</a></li>
                        <li><a href="#benefits">Beneficios</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Módulos</h4>
                    <ul>
                        <li><a href="#features">Finanzas</a></li>
                        <li><a href="#features">Seguridad QR</a></li>
                        <li><a href="#features">Amenidades</a></li>
                        <li><a href="#features">Paquetería</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Soporte</h4>
                    <ul>
                        <li><a href="<?= base_url('login') ?>">Iniciar Sesión</a></li>
                        <li><a href="mailto:soporte@condominet.com">Contacto</a></li>
                        <li><a href="#">Documentación</a></li>
                        <li><a href="#">Términos</a></li>
                    </ul>
                </div>
            </div>
            <div class="ln-footer-bar">
                <span>&copy; <?= date('Y') ?> CondomiNet. Todos los derechos reservados.</span>
                <div class="ln-footer-social">
                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script src="<?= base_url('landing/js/landing.js') ?>"></script>
</body>

</html>