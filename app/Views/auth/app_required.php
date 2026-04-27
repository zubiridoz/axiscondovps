<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Descarga la App | AxisCondo</title>
    <link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">

    <!-- Hojas de estilo universales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Fuentes de Google -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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

        /* Lado derecho (Aviso) */
        .right-panel {
            width: 55%;
            background-color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 500px;
            padding: 3rem;
            text-align: center;
        }

        .icon-container {
            width: 80px;
            height: 80px;
            background: rgba(37, 99, 235, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: #2563eb;
            font-size: 2.5rem;
        }

        .form-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1rem;
        }

        .form-subtitle {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 2.5rem;
            line-height: 1.6;
        }

        .store-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2.5rem;
        }

        .store-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 300px;
            background-color: #0f172a;
            color: #ffffff;
            padding: 0.8rem 1.5rem;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .store-btn:hover {
            background-color: #1e293b;
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .store-btn i {
            font-size: 1.8rem;
            margin-right: 1rem;
        }

        .store-text {
            text-align: left;
        }

        .store-text small {
            display: block;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 2px;
        }

        .store-text strong {
            display: block;
            font-size: 1.1rem;
            font-weight: 600;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            font-size: 0.95rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #0f172a;
        }

        .back-link i {
            margin-right: 0.5rem;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .split-layout {
                flex-direction: column-reverse;
                height: auto;
                min-height: 100vh;
            }

            .left-panel,
            .right-panel {
                width: 100%;
                height: auto;
            }

            .left-panel {
                padding: 2rem 1.5rem;
            }
            
            .left-content h1 {
                font-size: 1.5rem;
            }
            
            .left-content img {
                display: none !important;
            }

            .form-container {
                padding: 2rem 1.5rem;
            }
            
            .icon-container {
                width: 60px;
                height: 60px;
                font-size: 2rem;
                margin-bottom: 1.5rem;
            }
            
            .form-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="split-layout">
        <!-- Panel Izquierdo -->
        <div class="left-panel">
            <div class="left-content">
                <h1>Tu Comunidad en tu Bolsillo</h1>
                <p>La experiencia completa de AxisCondo para Residentes y Guardias está optimizada exclusivamente para dispositivos móviles.</p>
                <img src="<?= base_url('assets/images/app-mockup.png') ?>" alt="AxisCondo App" style="max-width: 100%; opacity: 0.8; margin-top: 2rem;" onerror="this.style.display='none'">
            </div>
        </div>

        <!-- Panel Derecho -->
        <div class="right-panel">
            <div class="form-container">
                <div class="icon-container">
                    <i class="bi bi-phone"></i>
                </div>
                
                <h2 class="form-title">Descarga la App</h2>
                <p class="form-subtitle">
                    Por motivos de seguridad y para brindarte la mejor experiencia, el acceso para residentes y guardias requiere el uso de nuestra aplicación móvil oficial.
                </p>

                <div class="store-buttons">
                    <a href="#" class="store-btn">
                        <i class="bi bi-apple"></i>
                        <div class="store-text">
                            <small>Descárgalo en la</small>
                            <strong>App Store</strong>
                        </div>
                    </a>
                    <a href="#" class="store-btn" style="background-color: #1a73e8;">
                        <i class="bi bi-google-play"></i>
                        <div class="store-text">
                            <small>DISPONIBLE EN</small>
                            <strong>Google Play</strong>
                        </div>
                    </a>
                </div>

                <a href="<?= base_url('login') ?>" class="back-link">
                    <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>

</body>
</html>
