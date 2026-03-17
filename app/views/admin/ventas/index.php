<?php
$estados = ['pendiente'=>'badge-warning','pagado'=>'badge-success','enviado'=>'badge-info','entregado'=>'badge-success','cancelado'=>'badge-danger'];
?>

<div class="page-header">
    <h1>💰 Ventas</h1>
</div>

<div class="admin-card" style="margin-bottom:1rem">
    <div class="admin-card-body" style="padding:1rem">
        <form method="GET" style="display:flex;gap:1rem;align-items:center;flex-wrap:wrap">
            <label style="font-weight:600;font-size:0.9rem">Filtrar por estado:</label>
            <?php foreach ([''=>'Todos','pendiente'=>'Pendiente','pagado'=>'Pagado','enviado'=>'Enviado','cancelado'=>'Cancelado'] as $val => $label): ?>
            <a href="<?= BASE_URL ?>/admin/ventas<?= $val ? '?estado='.$val : '' ?>"
               class="btn btn-sm <?= $estado === $val ? 'btn-primary' : 'btn-secondary' ?>">
                <?= $label ?>
            </a>
            <?php endforeach; ?>
        </form>
    </div>
</div>

<div class="admin-card">
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($pedidos)): ?>
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-light)">No hay pedidos aún</td></tr>
            <?php else: foreach ($pedidos as $p): ?>
                <tr>
                    <td><strong>#<?= $p['id'] ?></strong></td>
                    <td><?= htmlspecialchars($p['nombre_cliente'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($p['email_cliente'] ?? '-') ?></td>
                    <td><strong>$<?= number_format($p['total'], 0, ',', '.') ?></strong></td>
                    <td><span class="badge <?= $estados[$p['estado']] ?? 'badge-secondary' ?>"><?= $p['estado'] ?></span></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/ventas/detalle/<?= $p['id'] ?>" class="btn btn-warning btn-sm">Ver detalle</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
