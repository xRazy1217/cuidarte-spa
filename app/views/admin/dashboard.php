<?php
$estados = ['pendiente'=>'badge-warning','pagado'=>'badge-success','enviado'=>'badge-info','cancelado'=>'badge-danger'];
$estadosR = ['pendiente'=>'badge-warning','confirmada'=>'badge-success','cancelada'=>'badge-danger'];
?>

<div class="page-header">
    <h1>📊 Dashboard</h1>
    <span style="color:var(--text-light);font-size:0.9rem"><?= date('d/m/Y') ?></span>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-info">
            <h3>$<?= number_format($ventas_hoy ?? 0, 0, ',', '.') ?></h3>
            <p>Ventas hoy</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-info">
            <h3><?= $total_pedidos ?? 0 ?></h3>
            <p>Total pedidos</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🌿</div>
        <div class="stat-info">
            <h3><?= $total_productos ?? 0 ?></h3>
            <p>Productos activos</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">✍️</div>
        <div class="stat-info">
            <h3><?= $total_blog ?? 0 ?></h3>
            <p>Artículos publicados</p>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">

    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Pedidos recientes</h2>
            <a href="<?= BASE_URL ?>/admin/ventas" class="btn btn-secondary btn-sm">Ver todos</a>
        </div>
        <div style="overflow-x:auto">
            <table class="admin-table">
                <thead><tr><th>#</th><th>Cliente</th><th>Total</th><th>Estado</th></tr></thead>
                <tbody>
                <?php if (!empty($pedidos_recientes)): foreach ($pedidos_recientes as $p): ?>
                <tr>
                    <td><a href="<?= BASE_URL ?>/admin/ventas/detalle/<?= $p['id'] ?>">#<?= $p['id'] ?></a></td>
                    <td><?= htmlspecialchars($p['nombre_cliente'] ?? $p['email'] ?? '-') ?></td>
                    <td>$<?= number_format($p['total'], 0, ',', '.') ?></td>
                    <td><span class="badge <?= $estados[$p['estado']] ?? 'badge-secondary' ?>"><?= $p['estado'] ?></span></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" style="text-align:center;color:var(--text-light)">Sin pedidos aún</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-header">
            <h2>Reservas recientes</h2>
            <a href="<?= BASE_URL ?>/admin/reservas" class="btn btn-secondary btn-sm">Ver todas</a>
        </div>
        <div style="overflow-x:auto">
            <table class="admin-table">
                <thead><tr><th>#</th><th>Cliente</th><th>Fecha</th><th>Estado</th></tr></thead>
                <tbody>
                <?php if (!empty($reservas_recientes)): foreach ($reservas_recientes as $r): ?>
                <tr>
                    <td>#<?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['nombre_cliente'] ?? '-') ?></td>
                    <td><?= date('d/m/Y', strtotime($r['fecha'])) ?></td>
                    <td><span class="badge <?= $estadosR[$r['estado']] ?? 'badge-secondary' ?>"><?= $r['estado'] ?></span></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" style="text-align:center;color:var(--text-light)">Sin reservas aún</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<div class="admin-card">
    <div class="admin-card-header"><h2>Accesos rápidos</h2></div>
    <div class="admin-card-body" style="display:flex;gap:1rem;flex-wrap:wrap">
        <a href="<?= BASE_URL ?>/admin/productos/crear" class="btn btn-primary">+ Nuevo producto</a>
        <a href="<?= BASE_URL ?>/admin/blog/crear" class="btn btn-primary">+ Nuevo artículo</a>
        <a href="<?= BASE_URL ?>/admin/cupones/crear" class="btn btn-primary">+ Nuevo cupón</a>
        <a href="<?= BASE_URL ?>/admin/reservas/horarios" class="btn btn-secondary">+ Agregar horario</a>
    </div>
</div>
