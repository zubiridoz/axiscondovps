<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | AxisCondo</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Inter',sans-serif;background:#e8eef6;color:#334155;height:100vh;overflow:hidden;display:flex;align-items:center;justify-content:center}

        .login-card{display:flex;width:94vw;max-width:1050px;height:92vh;max-height:740px;border-radius:24px;overflow:hidden;box-shadow:0 25px 60px rgba(29,76,157,.25),0 8px 24px rgba(0,0,0,.1)}

        .left-panel{width:48%;background:linear-gradient(135deg,#1D4C9D 0%,#153A7A 40%,#0E2A5C 100%);position:relative;display:flex;flex-direction:column;justify-content:center;padding:3.5rem;overflow:hidden;color:#fff}

        .left-panel .shape{position:absolute;border-radius:50%;opacity:.08;background:#fff}
        .left-panel .shape-1{width:350px;height:350px;top:-80px;left:-80px}
        .left-panel .shape-2{width:250px;height:250px;bottom:-60px;right:-40px}
        .left-panel .shape-3{width:180px;height:180px;bottom:30%;left:55%;opacity:.05}

        .left-panel .wave-lines{position:absolute;top:0;left:0;width:100%;height:100%;opacity:.06;pointer-events:none}

        .dot-grid{position:absolute;top:60px;right:40px;display:grid;grid-template-columns:repeat(4,8px);gap:8px;opacity:.25}
        .dot-grid span{width:6px;height:6px;background:#fff;border-radius:50%}

        .cross{position:absolute;color:rgba(255,255,255,.2);font-size:1.5rem;font-weight:300}
        .cross-1{top:50px;left:60px}
        .cross-2{bottom:120px;left:45%}

        .circle-outline{position:absolute;width:30px;height:30px;border:2px solid rgba(255,255,255,.15);border-radius:50%}
        .circle-outline-1{top:140px;right:120px}
        .circle-outline-2{bottom:80px;left:30px}

        .left-panel h1{font-size:2.4rem;font-weight:800;line-height:1.15;margin-bottom:1rem;position:relative;z-index:2}
        .left-panel p{font-size:1rem;color:rgba(255,255,255,.7);line-height:1.7;position:relative;z-index:2;max-width:340px}

        .left-features{margin-top:2.5rem;position:relative;z-index:2}
        .left-features .feat{display:flex;align-items:center;margin-bottom:1rem;font-size:.88rem;font-weight:500;color:rgba(255,255,255,.85)}
        .left-features .feat-icon{width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;margin-right:.75rem;font-size:.95rem;flex-shrink:0}

        .right-panel{width:52%;background:#fff;display:flex;align-items:center;justify-content:center;position:relative;overflow-y:auto}
        .form-container{width:100%;max-width:420px;padding:2.5rem 2rem}

        .mobile-logo{display:none;font-size:2rem;font-weight:800;color:#1D4C9D;text-align:center;margin-bottom:1.5rem}

        .form-title{font-size:1.5rem;font-weight:800;color:#0f172a;margin-bottom:.25rem}
        .form-subtitle{font-size:.88rem;color:#64748b;margin-bottom:1.75rem;line-height:1.6}

        .form-label{font-size:.8rem;font-weight:600;color:#3F67AC;margin-bottom:.35rem;text-transform:uppercase;letter-spacing:.5px}
        .input-icon-wrap{position:relative}
        .input-icon-wrap i.field-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#94a3b8;font-size:1rem;pointer-events:none}
        .input-icon-wrap .form-control{padding-left:42px}
        .form-control{font-size:.9rem;padding:.78rem 1rem;border-radius:12px;border:1.5px solid #e2e8f0;color:#0f172a;transition:all .2s;background:#f8fafc}
        .form-control:focus{border-color:#1D4C9D;box-shadow:0 0 0 3px rgba(29,76,157,.1);background:#fff}

        .btn-primary{background:linear-gradient(135deg,#1D4C9D,#2960B8);border:none;font-weight:700;font-size:.95rem;padding:.85rem;border-radius:12px;width:100%;transition:all .3s;box-shadow:0 4px 14px rgba(29,76,157,.35)}
        .btn-primary:hover{background:linear-gradient(135deg,#163D80,#1D4C9D);transform:translateY(-1px);box-shadow:0 6px 20px rgba(29,76,157,.4)}

        .back-link{font-size:.88rem;color:#1D4C9D;text-decoration:none;font-weight:600;display:inline-flex;align-items:center}
        .back-link:hover{color:#163D80;text-decoration:underline}

        .lock-icon-wrap{text-align:center;margin-bottom:1.5rem}
        .lock-icon-circle{width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#e0edff,#f0f7ff);display:inline-flex;align-items:center;justify-content:center}
        .lock-icon-circle i{font-size:1.6rem;color:#1D4C9D}

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
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
        <div class="shape shape-3"></div>

        <div class="dot-grid">
            <span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span>
            <span></span><span></span><span></span><span></span>
        </div>

        <div class="cross cross-1">+</div>
        <div class="cross cross-2">+</div>

        <div class="circle-outline circle-outline-1"></div>
        <div class="circle-outline circle-outline-2"></div>

        <svg class="wave-lines" viewBox="0 0 500 600" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M-50 400C50 350 150 450 250 380S450 300 550 350" stroke="#fff" stroke-width="2" fill="none"/>
            <path d="M-50 430C80 380 160 470 270 410S460 330 560 370" stroke="#fff" stroke-width="1.5" fill="none"/>
            <path d="M-30 200C60 170 130 230 220 200S380 150 480 190" stroke="#fff" stroke-width="1.5" fill="none"/>
        </svg>

        <div style="margin-bottom:auto"></div>
        <h1>Recupera tu acceso</h1>
        <p>Te enviaremos un enlace seguro a tu correo electrónico para restablecer tu contraseña.</p>
        <div class="left-features">
            <div class="feat"><div class="feat-icon"><i class="bi bi-shield-check"></i></div>Enlace seguro de un solo uso</div>
            <div class="feat"><div class="feat-icon"><i class="bi bi-clock-history"></i></div>Expira en 30 minutos</div>
            <div class="feat"><div class="feat-icon"><i class="bi bi-envelope-check"></i></div>Confirmación por correo</div>
        </div>
    </div>

    <!-- ══ Panel Derecho ══ -->
    <div class="right-panel">
        <div class="form-container">

            <div class="mobile-logo">AxisCondo</div>

            <div class="lock-icon-wrap">
                <div class="lock-icon-circle">
                    <i class="bi bi-shield-lock"></i>
                </div>
            </div>

            <h2 class="form-title" style="text-align:center">¿Olvidaste tu contraseña?</h2>
            <p class="form-subtitle" style="text-align:center">Ingresa el correo electrónico asociado a tu cuenta de administrador y te enviaremos un enlace para restablecerla.</p>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger px-3 py-2 border-0 mb-3" style="font-size:.85rem;border-radius:10px">
                    <i class="bi bi-exclamation-triangle me-1"></i><?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success px-3 py-2 border-0 mb-3" style="font-size:.85rem;border-radius:10px">
                    <i class="bi bi-check-circle me-1"></i><?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('password/forgot') ?>" method="POST" id="forgotForm">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label class="form-label">Correo Electrónico</label>
                    <div class="input-icon-wrap">
                        <i class="bi bi-envelope field-icon"></i>
                        <input type="email" name="email" class="form-control" placeholder="tu@correo.com" required autofocus>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="bi bi-send me-2"></i>Enviar Enlace de Recuperación
                </button>
            </form>

            <div class="mt-4 text-center">
                <a href="<?= base_url('login') ?>" class="back-link">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Iniciar Sesión
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Prevenir doble envío del formulario
    document.getElementById('forgotForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Enviando...';
    });
</script>
</body>
</html>
