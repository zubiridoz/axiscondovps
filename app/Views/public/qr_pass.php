<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pase de Acceso QR - <?= esc($condominium['name'] ?? 'Condominio') ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .mobile-container {
            width: 100%;
            max-width: 480px;
            background-color: #ffffff;
            min-height: 100vh;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }
        .header-top {
            background-color: #1e3a5f;
            color: white;
            text-align: center;
            padding: 12px 0;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 1px;
        }
        .condo-header {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 24px 20px 16px 20px;
        }
        .condo-avatar {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0d2546, #21518f);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .condo-name {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1a202c;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .qr-wrapper {
            margin: 10px 40px;
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .qr-image {
            width: 100%;
            max-width: 280px;
            height: auto;
        }
        .info-section {
            padding: 12px 30px;
        }
        .info-title {
            font-size: 0.85rem;
            font-weight: 800;
            color: #3F67AC;
            margin-bottom: 12px;
            letter-spacing: 0.5px;
        }
        .info-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.05rem;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .info-item i {
            font-size: 1.15rem;
            color: #64748b;
            width: 24px;
            text-align: center;
        }
        /* Custom Icons to match image */
        .ic-house { color: #10b981; }
        .ic-user { color: #3b82f6; }
        .ic-gear { color: #64748b; }
        .ic-cal { color: #f43f5e; }
        hr.divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 10px 30px;
        }
        
        /* Optional valid state visually */
        .status-badge {
            text-align: center;
            margin-top: -15px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="mobile-container">
    <!-- Top Bar -->
    <div class="header-top">
        AxisCondo
    </div>

    <!-- Condo Name -->
    <div class="condo-header">
        <div class="condo-avatar"></div>
        <h1 class="condo-name"><?= esc($condominium['name'] ?? 'CONDOMINIO') ?></h1>
    </div>

    <?php if($isValidNow): ?>
        <div class="status-badge">
            <?php if($qr['status'] === 'renovado'): ?>
                <span class="badge rounded-pill px-3 py-2 border" style="background-color: #dbeafe; color: #1d4ed8; border-color: #93c5fd !important;"><i class="bi bi-arrow-repeat me-1"></i>PASE RENOVADO</span>
            <?php else: ?>
                <span class="badge rounded-pill px-3 py-2 border" style="background-color: #dcfce7; color: #166534; border-color: #86efac !important;"><i class="bi bi-check-circle me-1"></i>PASE VIGENTE</span>
            <?php endif; ?>
            <div class="mt-1">
                <span class="badge rounded-pill px-2 py-1" style="background-color: #f1f5f9; color: #64748b; font-size: 0.7rem; font-weight: 500;">
                    <?= $isSingleEntry ? '🎫 Una entrada' : '🔄 Pase temporal' ?>
                </span>
            </div>
        </div>
    <?php elseif(!empty($validityMessage) && !in_array($qr['status'], ['revoked', 'used', 'expired'])): ?>
        <div class="status-badge">
            <span class="badge rounded-pill px-3 py-2 border" style="background-color: #fef3c7; color: #92400e; border-color: #fcd34d !important;"><i class="bi bi-clock-history me-1"></i><?= esc($validityMessage) ?></span>
            <div class="mt-1">
                <span class="badge rounded-pill px-2 py-1" style="background-color: #f1f5f9; color: #64748b; font-size: 0.7rem; font-weight: 500;">
                    <?= $isSingleEntry ? '🎫 Una entrada' : '🔄 Pase temporal' ?>
                </span>
            </div>
        </div>
    <?php else: ?>
        <div class="status-badge">
            <span class="badge bg-danger rounded-pill px-3 py-2 border">
                <?php if($qr['status'] === 'used'): ?>
                    <i class="bi bi-x-circle me-1"></i>PASE UTILIZADO
                <?php else: ?>
                    <i class="bi bi-x-circle me-1"></i>PASE REVOCADO / EXPIRADO
                <?php endif; ?>
            </span>
            <?php if(!empty($validityMessage)): ?>
                <div class="text-muted mt-1" style="font-size: 0.75rem;"><?= esc($validityMessage) ?></div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- QR Code Graphic -->
    <div class="qr-wrapper">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=<?= urlencode($qrData) ?>" alt="QR Code" class="qr-image">
    </div>

    <!-- PROPIETARIO -->
    <div class="info-section">
        <div class="info-title">PROPIETARIO</div>
        <?php if($unit): ?>
        <div class="info-item">
            <i class="bi bi-house-door-fill ic-house text-success"></i>
            <span><?= esc($unit['unit_number']) ?></span>
        </div>
        <?php endif; ?>
        
        <div class="info-item">
            <i class="bi bi-person-fill ic-user text-primary"></i>
            <span><?= esc($owner['first_name'] ?? 'Admin') ?> <?= esc($owner['last_name'] ?? '') ?></span>
        </div>
    </div>

    <hr class="divider">

    <!-- VISITANTE -->
    <div class="info-section">
        <div class="info-title">VISITANTE</div>
        <div class="info-item">
            <i class="bi bi-person-fill ic-user" style="color:#64748b;"></i>
            <span><?= esc($qr['visitor_name']) ?></span>
        </div>
        <?php if(!empty($qr['visit_type'])): ?>
        <div class="info-item">
            <i class="bi bi-gear-fill ic-gear text-secondary"></i>
            <span><?= esc($qr['visit_type']) ?></span>
        </div>
        <?php endif; ?>
        <?php if(!empty($qr['vehicle_plate'])): ?>
        <div class="info-item">
            <i class="bi bi-car-front-fill text-dark"></i>
            <span><?= esc($qr['vehicle_plate']) ?> (<?= esc($qr['vehicle_type']) ?>)</span>
        </div>
        <?php endif; ?>
    </div>

    <hr class="divider">

    <!-- FECHAS DE ACCESO -->
    <?php 
        setlocale(LC_TIME, 'es_ES', 'Spanish_Spain', 'Spanish');
        $dateStart = new DateTime($qr['valid_from']);
        $dateEnd = new DateTime($qr['valid_until']);
        
        $months = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
        $dStartLabel = $dateStart->format('d') . ' de ' . $months[(int)$dateStart->format('m') - 1] . ' de ' . $dateStart->format('Y');
        $dEndLabel = $dateEnd->format('d') . ' de ' . $months[(int)$dateEnd->format('m') - 1] . ' de ' . $dateEnd->format('Y');
        
        $isSingleDay = ($dateStart->format('Y-m-d') === $dateEnd->format('Y-m-d'));
    ?>
    <div class="info-section pb-4">
        <div class="info-title">FECHAS DE ACCESO</div>
        <div class="info-item">
            <i class="bi bi-calendar-event-fill ic-cal text-danger"></i>
            <span>
                <?php if($isSingleDay): ?>
                    Entrada: <?= $dStartLabel ?>
                <?php else: ?>
                    <?= $dStartLabel ?> al <?= $dEndLabel ?>
                <?php endif; ?>
            </span>
        </div>
    </div>

</div>

</body>
</html>
