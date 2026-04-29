<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | AxisCondo</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#e8eef6;color:#334155;height:100vh;overflow:hidden;display:flex;align-items:center;justify-content:center}

        /* ── Contenedor Principal Card ── */
        .login-card{display:flex;width:94vw;max-width:1050px;height:92vh;max-height:740px;border-radius:24px;overflow:hidden;box-shadow:0 25px 60px rgba(29,76,157,.25),0 8px 24px rgba(0,0,0,.1)}

        /* ── Panel Izquierdo ── */
        .left-panel{width:48%;background:linear-gradient(135deg,#1D4C9D 0%,#153A7A 40%,#0E2A5C 100%);position:relative;display:flex;flex-direction:column;justify-content:center;padding:3.5rem;overflow:hidden;color:#fff}

        /* Formas decorativas orgánicas */
        .left-panel .shape{position:absolute;border-radius:50%;opacity:.08;background:#fff}
        .left-panel .shape-1{width:350px;height:350px;top:-80px;left:-80px}
        .left-panel .shape-2{width:250px;height:250px;bottom:-60px;right:-40px}
        .left-panel .shape-3{width:180px;height:180px;bottom:30%;left:55%;opacity:.05}

        /* Líneas onduladas decorativas SVG */
        .left-panel .wave-lines{position:absolute;top:0;left:0;width:100%;height:100%;opacity:.06;pointer-events:none}

        /* Puntos decorativos */
        .dot-grid{position:absolute;top:60px;right:40px;display:grid;grid-template-columns:repeat(4,8px);gap:8px;opacity:.25}
        .dot-grid span{width:6px;height:6px;background:#fff;border-radius:50%}

        /* Cruz decorativa */
        .cross{position:absolute;color:rgba(255,255,255,.2);font-size:1.5rem;font-weight:300}
        .cross-1{top:50px;left:60px}
        .cross-2{bottom:120px;left:45%}

        /* Círculo outline */
        .circle-outline{position:absolute;width:30px;height:30px;border:2px solid rgba(255,255,255,.15);border-radius:50%}
        .circle-outline-1{top:140px;right:120px}
        .circle-outline-2{bottom:80px;left:30px}

        .left-panel .brand{font-size:1.1rem;font-weight:700;letter-spacing:.5px;opacity:.85;margin-bottom:auto;position:relative;z-index:2}
        .left-panel h1{font-size:2.4rem;font-weight:800;line-height:1.15;margin-bottom:1rem;position:relative;z-index:2}
        .left-panel p{font-size:1rem;color:rgba(255,255,255,.7);line-height:1.7;position:relative;z-index:2;max-width:340px}

        .left-features{margin-top:2.5rem;position:relative;z-index:2}
        .left-features .feat{display:flex;align-items:center;margin-bottom:1rem;font-size:.88rem;font-weight:500;color:rgba(255,255,255,.85)}
        .left-features .feat-icon{width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;margin-right:.75rem;font-size:.95rem;flex-shrink:0}

        /* ── Panel Derecho ── */
        .right-panel{width:52%;background:#fff;display:flex;align-items:center;justify-content:center;position:relative;overflow-y:auto}
        .form-container{width:100%;max-width:420px;padding:2.5rem 2rem}

        .mobile-logo{display:none;font-size:2rem;font-weight:800;color:#1D4C9D;text-align:center;margin-bottom:1.5rem}

        /* Tabs */
        .auth-tabs{background:#f1f5f9;border-radius:12px;padding:4px;display:flex;margin-bottom:2rem}
        .auth-tab{flex:1;text-align:center;padding:.6rem;font-size:.85rem;font-weight:600;border-radius:10px;color:#64748b;text-decoration:none;transition:all .25s ease}
        .auth-tab.active{background:#1D4C9D;color:#fff;box-shadow:0 2px 8px rgba(29,76,157,.3)}

        .form-title{font-size:1.5rem;font-weight:800;color:#0f172a;margin-bottom:.25rem}
        .form-subtitle{font-size:.88rem;color:#64748b;margin-bottom:1.75rem}

        /* Inputs con ícono */
        .form-label{font-size:.8rem;font-weight:600;color:#475569;margin-bottom:.35rem;text-transform:uppercase;letter-spacing:.5px}
        .input-icon-wrap{position:relative}
        .input-icon-wrap i.field-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:1rem;pointer-events:none}
        .input-icon-wrap .form-control{padding-left:42px}
        .form-control{font-size:.9rem;padding:.78rem 1rem;border-radius:12px;border:1.5px solid #e2e8f0;color:#0f172a;transition:all .2s;background:#f8fafc}
        .form-control:focus{border-color:#1D4C9D;box-shadow:0 0 0 3px rgba(29,76,157,.1);background:#fff}

        .password-toggle{position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#94a3b8;cursor:pointer;z-index:2}
        .input-group-relative{position:relative}

        /* Botones */
        .btn-primary{background:linear-gradient(135deg,#1D4C9D,#2960B8);border:none;font-weight:700;font-size:.95rem;padding:.85rem;border-radius:12px;width:100%;transition:all .3s;box-shadow:0 4px 14px rgba(29,76,157,.35)}
        .btn-primary:hover{background:linear-gradient(135deg,#163D80,#1D4C9D);transform:translateY(-1px);box-shadow:0 6px 20px rgba(29,76,157,.4)}

        .btn-resident-toggle{background:#f0f7ff;color:#1D4C9D;border:1.5px solid #bdd4f0;font-weight:600;font-size:.9rem;padding:.75rem;border-radius:12px;transition:all .2s;text-decoration:none;display:flex;align-items:center;justify-content:center}
        .btn-resident-toggle:hover{background:#e0edff;color:#1D4C9D;border-color:#8bb8e8}

        .btn-resident-submit{background:linear-gradient(135deg,#1D4C9D,#2960B8);color:#fff;border:none;font-weight:700;font-size:.95rem;padding:.85rem;border-radius:12px;width:100%;transition:all .3s;box-shadow:0 4px 14px rgba(29,76,157,.35)}
        .btn-resident-submit:hover{transform:translateY(-1px);box-shadow:0 6px 20px rgba(29,76,157,.4)}

        .forgot-link{font-size:.82rem;color:#1D4C9D;text-decoration:none;font-weight:600}
        .forgot-link:hover{color:#163D80;text-decoration:underline}

        .terms-text{font-size:.72rem;color:#94a3b8;text-align:center;margin-top:1.25rem}
        .terms-text a{color:#1D4C9D;font-weight:600}

        .terms-checkbox{font-size:.78rem;color:#64748b;display:flex;align-items:flex-start;margin-bottom:1.25rem}
        .terms-checkbox input{margin-top:.25rem;margin-right:.5rem;accent-color:#1D4C9D}
        .terms-checkbox a{color:#1D4C9D;font-weight:600}

        .divider-text{display:flex;align-items:center;margin:1.5rem 0;font-size:.8rem;color:#94a3b8;font-weight:500}
        .divider-text::before,.divider-text::after{content:'';flex:1;height:1px;background:#e2e8f0}
        .divider-text span{padding:0 1rem}

        /* ── Responsive ── */
        @media(max-width:991px){
            body{background:#fff;align-items:flex-start;overflow-y:auto;height:auto;min-height:100vh}
            .login-card{flex-direction:column;width:100%;max-width:100%;height:auto;max-height:none;border-radius:0;box-shadow:none}
            .left-panel{display:none!important}
            .right-panel{width:100%;min-height:100vh}
            .form-container{padding:2.5rem 1.5rem;max-width:480px;margin:0 auto}
            .mobile-logo{display:block}
        }

        @media(max-width:480px){
            .form-container{padding:2rem 1.25rem}
            .form-title{font-size:1.3rem}
        }
    </style>
</head>
<body>

<div class="login-card">
    <!-- ══ Panel Izquierdo ══ -->
    <div class="left-panel">
        <!-- Formas decorativas -->
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>

        <!-- Puntos grid -->
        <div class="dot-grid">
            <span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span>
        </div>

        <!-- Cruces decorativas -->
        <div class="cross cross-1">+</div>
        <div class="cross cross-2">+</div>

        <!-- Círculos outline -->
        <div class="circle-outline circle-outline-1"></div>
        <div class="circle-outline circle-outline-2"></div>

        <!-- Ondas SVG -->
        <svg class="wave-lines" viewBox="0 0 500 600" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M-50 400C50 350 150 450 250 380S450 300 550 350" stroke="#fff" stroke-width="2" fill="none"/>
            <path d="M-50 430C80 380 160 470 270 410S460 330 560 370" stroke="#fff" stroke-width="1.5" fill="none"/>
            <path d="M-30 200C60 170 130 230 220 200S380 150 480 190" stroke="#fff" stroke-width="1.5" fill="none"/>
        </svg>

        <!-- Contenido Login -->
        <div class="brand" id="left-login-content">
            <div style="margin-bottom:auto"></div>
            <h1>¡Bienvenido de nuevo!</h1>
            <p>Accede a tu panel de administración para gestionar tu comunidad de forma inteligente y segura.</p>
            <div class="left-features">
                <div class="feat"><div class="feat-icon"><i class="bi bi-buildings"></i></div>Gestión de Propiedades</div>
                <div class="feat"><div class="feat-icon"><i class="bi bi-people"></i></div>Portal de Residentes</div>
                <div class="feat"><div class="feat-icon"><i class="bi bi-shield-lock"></i></div>Seguridad y Acceso</div>
            </div>
        </div>

        <!-- Contenido Registro -->
        <div class="brand" id="left-register-content" style="display:none;">
            <div style="margin-bottom:auto"></div>
            <h1>Únete a AxisCondo</h1>
            <p>Comienza hoy a transformar la administración de tu condominio con herramientas profesionales.</p>
            <div class="left-features">
                <div class="feat"><div class="feat-icon"><i class="bi bi-lightning"></i></div>Configuración en minutos</div>
                <div class="feat"><div class="feat-icon"><i class="bi bi-grid-3x3-gap"></i></div>Herramientas integrales</div>
                <div class="feat"><div class="feat-icon"><i class="bi bi-lock"></i></div>Seguridad bancaria</div>
            </div>
        </div>
    </div>

    <!-- ══ Panel Derecho ══ -->
    <div class="right-panel">
        <div class="form-container">

            <div class="mobile-logo">AxisCondo</div>

            <!-- Tabs -->
            <div class="auth-tabs">
                <a href="javascript:void(0)" class="auth-tab active" id="tab-login" onclick="switchTab('login')">Iniciar Sesión</a>
                <a href="javascript:void(0)" class="auth-tab" id="tab-register" onclick="switchTab('register')">Crear Cuenta</a>
            </div>

            <!-- ═══ FORMULARIO 1: INICIAR SESIÓN ═══ -->
            <div id="form-login-section">
                <h2 class="form-title">Iniciar Sesión</h2>
                <p class="form-subtitle">Accede a tu cuenta de administrador o residente</p>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger px-3 py-2 border-0 mb-3" style="font-size:.85rem;border-radius:10px"><?= session()->getFlashdata('error') ?></div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success px-3 py-2 border-0 mb-3" style="font-size:.85rem;border-radius:10px"><?= session()->getFlashdata('success') ?></div>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-envelope field-icon"></i>
                            <input type="email" name="email" class="form-control" required value="admin@demo.com">
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Contraseña</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-lock field-icon"></i>
                            <div class="input-group-relative" style="width:100%">
                                <input type="password" name="password" id="login_password" class="form-control" style="padding-left:42px" required value="password123">
                                <i class="bi bi-eye password-toggle" onclick="togglePassword('login_password', this)"></i>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mb-4">
                        <a href="#" class="forgot-link">¿Olvidaste tu Contraseña?</a>
                    </div>

                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>

                    <div class="divider-text"><span>¿Eres residente con invitación?</span></div>

                    <a href="javascript:void(0)" onclick="switchTab('resident-register')" class="btn-resident-toggle w-100">
                        <i class="bi bi-person-check me-2" style="font-size:1.1rem"></i> Activar cuenta de Residente
                    </a>

                    <div class="terms-text">
                        Al iniciar sesión, aceptas nuestra <a href="#" class="text-decoration-none">Política de Privacidad</a>
                    </div>
                </form>
            </div>

            <!-- ═══ FORMULARIO 2: CREAR CUENTA ═══ -->
            <div id="form-register-section" style="display:none;">
                <h2 class="form-title">Crear una Cuenta</h2>
                <p class="form-subtitle">Únete a la comunidad AxisCondo hoy</p>

                <form action="<?= base_url('register') ?>" method="POST" id="registerForm">
                    <?= csrf_field() ?>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-envelope field-icon"></i>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <div class="input-group-relative">
                            <input type="password" class="form-control" name="password" id="reg_password" required>
                            <i class="bi bi-eye password-toggle" onclick="togglePassword('reg_password', this)"></i>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirmar Contraseña</label>
                        <div class="input-group-relative">
                            <input type="password" class="form-control" id="reg_confirm_password" required>
                            <i class="bi bi-eye password-toggle" onclick="togglePassword('reg_confirm_password', this)"></i>
                        </div>
                    </div>
                    <label class="terms-checkbox">
                        <input type="checkbox" required>
                        <span>Acepto los <a href="#" class="text-decoration-none">Términos y Condiciones</a> & <a href="#" class="text-decoration-none">Política de Privacidad</a></span>
                    </label>
                    <button type="submit" class="btn btn-primary mt-2">Crear Cuenta</button>
                </form>
            </div>

            <!-- ═══ FORMULARIO 3: ACTIVAR RESIDENTE ═══ -->
            <div id="form-resident-register-section" style="display:none;">
                <h2 class="form-title">Únete a tu Comunidad</h2>
                <p class="form-subtitle">Ingresa tu código de invitación y crea una contraseña para tu portal de residente.</p>

                <form action="<?= base_url('register-resident') ?>" method="POST" id="residentRegisterForm">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Código de Invitación</label>
                        <div class="input-icon-wrap">
                            <i class="bi bi-ticket-perforated field-icon"></i>
                            <input type="text" class="form-control" name="token" placeholder="Ej. ABCD-1234" required>
                        </div>
                        <small class="text-muted" style="font-size:.72rem">El código que recibiste por correo electrónico.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Crear Contraseña</label>
                        <div class="input-group-relative">
                            <input type="password" class="form-control" name="password" id="res_password" required>
                            <i class="bi bi-eye password-toggle" onclick="togglePassword('res_password', this)"></i>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirmar Contraseña</label>
                        <div class="input-group-relative">
                            <input type="password" class="form-control" id="res_confirm_password" required>
                            <i class="bi bi-eye password-toggle" onclick="togglePassword('res_confirm_password', this)"></i>
                        </div>
                    </div>
                    <label class="terms-checkbox">
                        <input type="checkbox" required>
                        <span>Acepto los <a href="#" class="text-decoration-none">Términos y Condiciones</a></span>
                    </label>
                    <button type="submit" class="btn-resident-submit mt-2">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Activar Cuenta e Ingresar
                    </button>
                    <div class="mt-4 text-center">
                        <a href="javascript:void(0)" onclick="switchTab('login')" class="forgot-link" style="font-size:.88rem">
                            <i class="bi bi-arrow-left me-1"></i> Volver a Iniciar Sesión
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") { input.type = "text"; icon.classList.replace("bi-eye","bi-eye-slash"); }
        else { input.type = "password"; icon.classList.replace("bi-eye-slash","bi-eye"); }
    }

    function switchTab(tab) {
        document.getElementById('tab-login').classList.toggle('active', tab === 'login');
        document.getElementById('tab-register').classList.toggle('active', tab === 'register');
        document.getElementById('form-login-section').style.display = (tab === 'login') ? 'block' : 'none';
        document.getElementById('form-register-section').style.display = (tab === 'register') ? 'block' : 'none';
        document.getElementById('form-resident-register-section').style.display = (tab === 'resident-register') ? 'block' : 'none';
        document.getElementById('left-login-content').style.display = (tab === 'login') ? 'block' : 'none';
        document.getElementById('left-register-content').style.display = (tab !== 'login') ? 'block' : 'none';
    }

    document.getElementById('registerForm').addEventListener('submit', function (e) {
        const pass = document.getElementById('reg_password').value;
        const confirm = document.getElementById('reg_confirm_password').value;
        if (pass !== confirm) { e.preventDefault(); alert('Las contraseñas no coinciden.'); }
        else if (pass.length < 6) { e.preventDefault(); alert('La contraseña debe tener al menos 6 caracteres.'); }
    });

    document.getElementById('residentRegisterForm').addEventListener('submit', function (e) {
        const pass = document.getElementById('res_password').value;
        const confirm = document.getElementById('res_confirm_password').value;
        if (pass !== confirm) { e.preventDefault(); alert('Las contraseñas no coinciden.'); }
        else if (pass.length < 6) { e.preventDefault(); alert('La contraseña debe tener al menos 6 caracteres.'); }
    });

    // Abrir pestaña de residente automáticamente si viene desde el correo
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');
        if (token || window.location.hash === '#activar') {
            switchTab('resident-register');
            if (token) {
                const tokenInput = document.querySelector('#residentRegisterForm input[name="token"]');
                if (tokenInput) tokenInput.value = token;
            }
        }
    });
</script>
</body>
</html>