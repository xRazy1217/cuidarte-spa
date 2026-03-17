<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? SITE_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <style>
        .cliente-sidebar {
            width: 240px;
            background: linear-gradient(180deg, var(--wc-blue-dark), #1a3a6b);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            padding: 2rem 0;
            z-index: 100;
        }
        .cliente-main { margin-left: 240px; min-height: 100vh; background: #f8fafc; }
        .cliente-nav-link {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.85rem 1.5rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.95rem;
        }
        .cliente-nav-link:hover, .cliente-nav-link.active {
            background: rgba(255,255,255,0.15);
            color: white;
        }
        .cliente-header {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .cliente-content { padding: 2rem; }
        @media (max-width: 768px) {
            .cliente-sidebar { display: none; }
            .cliente-main { margin-left: 0; }
        }
    </style>
</head>
<body>

<div class="cliente-sidebar">
    <div style="padding: 0 1.5rem 2rem; border-bottom: 1px solid rgba(255,255,255,0.1)">
        <a href="<?= BASE_URL ?>/" style="text-decoration:none">
            <div style="font-family:'Inter',sans-serif;font-weight:800;font-size:1.2rem;color:white">Cuidarte Spa 🦋</div>
            <div style="font-size:0.75rem;color:rgba(255,255,255,0.6);margin-top:0.25rem">Panel de Cliente</div>
        </div>
    </div>
    <nav style="margin-top:1rem">
        <?php
        $current = $_SERVER['REQUEST_URI'];
        $links = [
            ['/mi-cuenta',          '🏠', 'Inicio'],
            ['/mi-cuenta/pedidos',  '📦', 'Mis Pedidos'],
            ['/mi-cuenta/reservas', '📅', 'Mis Reservas'],
            ['/mi-cuenta/perfil',   '👤', 'Mi Perfil'],
        ];
        foreach ($links as $l):
            $active = strpos($current, $l[0]) !== false ? 'active' : '';
        ?>
        <a href="<?= BASE_URL . $l[0] ?>" class="cliente-nav-link <?= $active ?>">
            <span><?= $l[1] ?></span> <?= $l[2] ?>
        </a>
        <?php endforeach; ?>
    </nav>
    <div style="position:absolute;bottom:0;left:0;right:0;padding:1rem 0;border-top:1px solid rgba(255,255,255,0.1)">
        <a href="<?= BASE_URL ?>/" class="cliente-nav-link">🌐 Ver tienda</a>
        <a href="<?= BASE_URL ?>/mi-cuenta/logout" class="cliente-nav-link" style="color:rgba(255,100,100,0.8)">🚪 Salir</a>
    </div>
</div>

<div class="cliente-main">
    <div class="cliente-header">
        <span style="font-weight:600;color:#2d3748">
            Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'Cliente') ?> 👋
        </span>
        <a href="<?= BASE_URL ?>/carrito" class="btn-wc-primary" style="padding:0.4rem 1rem;font-size:0.9rem">
            🛒 Carrito
        </a>
    </div>
    <div class="cliente-content">
        <?php if (isset($_GET['activada'])): ?>
            <div class="wc-alert-success" style="background:#d4edda;border-left:4px solid #28a745;color:#155724;padding:1rem;border-radius:10px;margin-bottom:1.5rem">
                ✅ ¡Cuenta activada! Bienvenida a Cuidarte Spa.
            </div>
        <?php endif; ?>
        <?= $content ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>const BASE_URL = '<?= BASE_URL ?>';</script>
</body>
</html>
