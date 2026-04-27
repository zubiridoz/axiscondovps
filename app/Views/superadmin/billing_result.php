<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> — AxisCondo</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
            text-align: center;
            max-width: 400px;
            width: 90%;
        }
        .icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }
        .icon.success {
            background-color: #dcfce7;
            color: #16a34a;
        }
        .icon.cancel {
            background-color: #fee2e2;
            color: #dc2626;
        }
        .icon svg {
            width: 32px;
            height: 32px;
        }
        h1 {
            color: #0f172a;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 1rem;
        }
        p {
            color: #64748b;
            font-size: 0.95rem;
            line-height: 1.5;
            margin: 0 0 2rem;
        }
        .btn {
            display: inline-block;
            background-color: #1e293b;
            color: #fff;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }
        .btn:hover {
            background-color: #0f172a;
        }
    </style>
    <?php if ($status === 'success'): ?>
    <script>
        // Redirigir al dashboard después de 5 segundos
        setTimeout(() => {
            window.location.href = '<?= site_url('admin/configuracion') ?>';
        }, 5000);
    </script>
    <?php endif; ?>
</head>
<body>

<div class="card">
    <?php if ($status === 'success'): ?>
        <div class="icon success">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
        </div>
    <?php else: ?>
        <div class="icon cancel">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
        </div>
    <?php endif; ?>

    <h1><?= esc($title) ?></h1>
    <p><?= esc($message) ?></p>

    <a href="<?= site_url('admin/configuracion') ?>" class="btn">Volver al Panel</a>
</div>

</body>
</html>
