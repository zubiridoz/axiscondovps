<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Completar Registro | AxisCondo</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">

    <!-- Hojas de estilo universales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Fuentes de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS personalizado -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow: hidden;
        }

        .split-layout {
            display: flex;
            height: 100vh;
        }

        /* Lado izquierdo (Panel azul oscuro) */
        .left-panel {
            background: radial-gradient(circle at top left, #2c4251, #0f172a 80%);
            color: #ffffff;
            width: 45%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 4rem;
            position: relative;
            overflow: hidden;
        }

        /* Formas difuminadas en el fondo azul */
        .left-panel::before,
        .left-panel::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            filter: blur(60px);
            z-index: 0;
        }

        .left-panel::before {
            width: 400px;
            height: 400px;
            top: -100px;
            left: -100px;
        }

        .left-panel::after {
            width: 500px;
            height: 500px;
            bottom: -150px;
            right: -100px;
        }

        .left-content {
            position: relative;
            z-index: 1;
            max-width: 400px;
            text-align: center;
        }

        .left-content h1 {
            font-size: 2.25rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .left-content p {
            font-size: 1rem;
            color: #94a3b8;
            margin-bottom: 3rem;
            line-height: 1.6;
        }

        .feature-list {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .feature-item {
            display: flex;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .feature-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            font-size: 1.1rem;
        }

        /* Lado derecho (Formulario) */
        .right-panel {
            width: 55%;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-y: auto;
        }

        .header-controls {
            position: absolute;
            top: 2rem;
            right: 2rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-container {
            width: 100%;
            max-width: 480px;
            padding: 2rem;
        }

        /* Toggle tabs */
        .auth-tabs {
            background-color: #f1f5f9;
            border-radius: 0.5rem;
            padding: 0.25rem;
            display: flex;
            margin-bottom: 2rem;
        }

        .auth-tab {
            flex: 1;
            text-align: center;
            padding: 0.6rem;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 0.4rem;
            color: #64748b;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .auth-tab.active {
            background-color: #ffffff;
            color: #0f172a;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 1.5rem;
        }

        /* Form elements */
        .form-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 0.4rem;
        }

        .form-control {
            font-size: 0.9rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e2e8f0;
            color: #0f172a;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .form-control:disabled {
            background-color: #f8fafc;
        }

        .btn-primary {
            background-color: #475569; /* Gris oscuro / slate */
            border-color: #475569;
            font-weight: 600;
            font-size: 0.95rem;
            padding: 0.75rem;
            border-radius: 0.5rem;
            width: 100%;
            transition: all 0.2s ease;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: #334155;
            border-color: #334155;
        }

        .terms-checkbox {
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .terms-checkbox input {
            margin-top: 0.2rem;
            margin-right: 0.5rem;
        }

        /* Ocultando placeholder para match exacto */
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            cursor: pointer;
        }

        .input-group-relative {
            position: relative;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .split-layout {
                flex-direction: column;
                height: auto;
                min-height: 100vh;
            }
            .left-panel, .right-panel {
                width: 100%;
                height: auto;
            }
            .left-panel {
                padding: 3rem 2rem;
            }
            .form-container {
                padding: 3rem 1.5rem;
            }
            .header-controls {
                position: relative;
                top: 0;
                right: 0;
                text-align: right;
                padding: 1rem 1.5rem 0;
                width: 100%;
                background: white;
            }
        }
    </style>
</head>
<body>

    <div class="split-layout">
        <!-- Panel Izquierdo -->
        <div class="left-panel">
            <div class="left-content">
                <h1>Únete a AxisCondo Hoy</h1>
                <p>Comienza a gestionar tus propiedades con nuestra plataforma administrativa integral</p>
                
                <ul class="feature-list">
                    <li class="feature-item">
                        <div class="feature-icon"><i class="bi bi-gear"></i></div>
                        Configuración Rápida y Fácil
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon"><i class="bi bi-grid"></i></div>
                        Herramientas de Gestión Integrales
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                        Control de Acceso Seguro
                    </li>
                </ul>
            </div>
        </div>

        <!-- Panel Derecho -->
        <div class="right-panel">
            <div class="header-controls d-none d-lg-block">
                <span>Español <i class="bi bi-chevron-down ms-1" style="font-size: 0.8rem;"></i></span>
            </div>

            <div class="form-container">
                <!-- Selectores tipo tab -->
                <div class="auth-tabs">
                    <a href="<?= base_url('login') ?>" class="auth-tab">Iniciar Sesión</a>
                    <a href="#" class="auth-tab active">Crear Cuenta</a>
                </div>

                <div class="text-center mb-4">
                    <h2 class="form-title">Crear una Cuenta</h2>
                    <p class="form-subtitle">Únete a la comunidad de AxisCondo hoy</p>
                </div>

                <?php if (session()->has('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2"></i>
                        <?= session('error') ?>
                    </div>
                <?php endif; ?>

                <?php
                    // Dividir nombre y apellido de la invitación
                    $nameParts = explode(' ', $invitation['name'], 2);
                    $firstName = $nameParts[0];
                    $lastName = $nameParts[1] ?? '';
                ?>

                <form action="<?= base_url('invite/' . esc($invitation['token']) . '/register') ?>" method="POST">
                    <?= csrf_field() ?>
                    
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Nombre</label>
                            <input type="text" class="form-control" name="first_name" value="<?= esc($firstName) ?>" readonly disabled>
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label class="form-label">Apellido</label>
                            <input type="text" class="form-control" name="last_name" value="<?= esc($lastName) ?>" readonly disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" name="email" value="<?= esc($invitation['email']) ?>" readonly disabled>
                        <div class="form-text text-muted" style="font-size:0.75rem;">
                            Estos campos vienen pre-asignados de tu invitación.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Contraseña</label>
                        <div class="input-group-relative">
                            <input type="password" class="form-control" name="password" id="password" required>
                            <i class="bi bi-eye password-toggle" onclick="togglePassword('password', this)"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Confirmar Contraseña</label>
                        <div class="input-group-relative">
                            <input type="password" class="form-control" id="confirm_password" required>
                            <i class="bi bi-eye password-toggle" onclick="togglePassword('confirm_password', this)"></i>
                        </div>
                    </div>

                    <label class="terms-checkbox">
                        <input type="checkbox" required>
                        <span>Acepto los <a href="#" class="text-decoration-none text-primary">Términos y Condiciones</a> & <a href="#" class="text-decoration-none text-primary">Política de Privacidad</a></span>
                    </label>

                    <button type="submit" class="btn btn-primary d-block w-100" id="btnSubmit">
                        Crear Cuenta
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            }
        }

        // Validación simple en el cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if (pass !== confirm) {
                e.preventDefault();
                alert('Las contraseñas no coinciden.');
            } else if (pass.length < 6) {
                e.preventDefault();
                alert('La contraseña debe tener al menos 6 caracteres.');
            }
        });
    </script>
</body>
</html>
