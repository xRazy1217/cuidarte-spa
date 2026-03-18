<div class="page-header">
    <h1>👥 Clientes</h1>
</div>

<div class="admin-card">
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Pedidos</th>
                    <th>Total gastado</th>
                    <th>Última compra</th>
                    <th>Estado</th>
                    <th>Registro</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($clientes)): ?>
                <tr><td colspan="9" style="text-align:center;padding:3rem;color:var(--text-light)">No hay clientes registrados</td></tr>
            <?php else: foreach ($clientes as $c): ?>
                <tr>
                    <td><strong>#<?= $c['id'] ?></strong></td>
                    <td><?= htmlspecialchars($c['nombre']) ?></td>
                    <td><?= htmlspecialchars($c['email']) ?></td>
                    <td><strong><?= $c['total_pedidos'] ?></strong></td>
                    <td><strong>$<?= number_format($c['total_gastado'], 0, ',', '.') ?></strong></td>
                    <td><?= $c['ultima_compra'] ? date('d/m/Y', strtotime($c['ultima_compra'])) : '—' ?></td>
                    <td>
                        <span class="badge <?= $c['activo'] ? 'badge-success' : 'badge-warning' ?>">
                            <?= $c['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/clientes/detalle/<?= $c['id'] ?>" class="btn btn-warning btn-sm">Ver detalle</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
