<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'Admin | ' . SITE_NAME) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">
</head>
<body class="admin-body">

<div class="admin-wrapper">
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <span>Cuidarte</span> <small>Admin</small>
        </div>
        <nav class="sidebar-nav">
            <?php
            $current = $_SERVER['REQUEST_URI'];
            $links = [
                ['url' => '/admin',                  'icon' => '📊', 'label' => 'Dashboard'],
                ['url' => '/admin/productos',         'icon' => '🌿', 'label' => 'Productos'],
                ['url' => '/admin/categorias',        'icon' => '🏷️', 'label' => 'Categorías'],
                ['url' => '/admin/ventas',            'icon' => '💰', 'label' => 'Ventas'],
                ['url' => '/admin/reservas',          'icon' => '📅', 'label' => 'Reservas'],
                ['url' => '/admin/blog',              'icon' => '✍️', 'label' => 'Blog'],
                ['url' => '/admin/cupones',           'icon' => '🎟️', 'label' => 'Cupones'],
                ['url' => '/admin/qr',                'icon' => '📱', 'label' => 'Centro QR'],
            ];
            foreach ($links as $l):
                $active = strpos($current, $l['url']) !== false ? 'active' : '';
            ?>
            <a href="<?= BASE_URL . $l['url'] ?>" class="sidebar-link <?= $active ?>">
                <span class="sidebar-icon"><?= $l['icon'] ?></span>
                <span><?= $l['label'] ?></span>
            </a>
            <?php endforeach; ?>
        </nav>
        <div class="sidebar-footer">
            <a href="<?= BASE_URL ?>/" class="sidebar-link" target="_blank">🌐 Ver sitio</a>
            <a href="<?= BASE_URL ?>/logout" class="sidebar-link sidebar-logout">🚪 Salir</a>
        </div>
    </aside>

    <div class="admin-main">
        <header class="admin-header">
            <button class="sidebar-toggle" id="sidebarToggle">☰</button>
            <div class="admin-header-right">
                <span>👤 <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? 'Admin') ?></span>
            </div>
        </header>
        <div class="admin-content">
            <?= $content ?>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/admin.js"></script>
<script>
// Inyectar CSRF token en todos los formularios POST
document.querySelectorAll('form[method="post"], form[method="POST"]').forEach(f => {
    if (!f.querySelector('[name="csrf_token"]')) {
        const i = document.createElement('input');
        i.type = 'hidden'; i.name = 'csrf_token';
        i.value = '<?= csrfToken() ?>';
        f.appendChild(i);
    }
});
</script>
</body>
</html>
