<div class="page-header">
    <h1>👤 <?= htmlspecialchars($cliente['nombre']) ?></h1>
    <a href="<?= BASE_URL ?>/admin/clientes" class="btn btn-secondary">← Volver</a>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:1.5rem;align-items:start">

    <!-- Info cliente -->
    <div>
        <div class="admin-card">
            <div class="admin-card-header"><h2>Información</h2></div>
            <div class="admin-card-body" style="font-size:0.9rem;line-height:2">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($cliente['nombre']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></p>
                <p><strong>Registro:</strong> <?= date('d/m/Y H:i', strtotime($cliente['created_at'])) ?></p>
                <p><strong>Estado:</strong>
                    <span class="badge <?= $cliente['activo'] ? 'badge-success' : 'badge-warning' ?>">
                        <?= $cliente['activo'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="admin-card" style="margin-top:1rem">
            <div class="admin-card-header"><h2>Resumen</h2></div>
            <div class="admin-card-body" style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;text-align:center">
                <div style="background:var(--bg-dark);border-radius:10px;padding:1rem">
                    <div style="font-size:1.8rem;font-weight:800;color:var(--aqua)"><?= count($pedidos) ?></div>
                    <div style="font-size:0.8rem;color:var(--text-light)">Pedidos</div>
                </div>
                <div style="background:var(--bg-dark);border-radius:10px;padding:1rem">
                    <div style="font-size:1.8rem;font-weight:800;color:var(--aqua)"><?= count($reservas) ?></div>
                    <div style="font-size:0.8rem;color:var(--text-light)">Reservas</div>
                </div>
                <div style="background:var(--bg-dark);border-radius:10px;padding:1rem;grid-column:span 2">
                    <div style="font-size:1.5rem;font-weight:800;color:var(--aqua)">
                        $<?= number_format(array_sum(array_column(array_filter($pedidos, fn($p) => $p['estado'] === 'pagado'), 'total')), 0, ',', '.') ?>
                    </div>
                    <div style="font-size:0.8rem;color:var(--text-light)">Total gastado</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pedidos y reservas -->
    <div>
        <div class="admin-card">
            <div class="admin-card-header"><h2>Pedidos</h2></div>
            <div style="overflow-x:auto">
                <table class="admin-table">
                    <thead><tr><th>#</th><th>Total</th><th>Estado</th><th>Fecha</th></tr></thead>
                    <tbody>
                    <?php if (empty($pedidos)): ?>
                        <tr><td colspan="4" style="text-align:center;padding:1.5rem;color:var(--text-light)">Sin pedidos</td></tr>
                    <?php else: foreach ($pedidos as $p):
                        $badges = ['pendiente'=>'badge-warning','pagado'=>'badge-success','enviado'=>'badge-info','entregado'=>'badge-success','cancelado'=>'badge-danger','reembolsado'=>'badge-secondary'];
                    ?>
                        <tr>
                            <td><a href="<?= BASE_URL ?>/admin/ventas/detalle/<?= $p['id'] ?>" style="color:var(--aqua)">#<?= $p['id'] ?></a></td>
                            <td>$<?= number_format($p['total'], 0, ',', '.') ?></td>
                            <td><span class="badge <?= $badges[$p['estado']] ?? 'badge-secondary' ?>"><?= $p['estado'] ?></span></td>
                            <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="admin-card" style="margin-top:1rem">
            <div class="admin-card-header"><h2>Reservas</h2></div>
            <div style="overflow-x:auto">
                <table class="admin-table">
                    <thead><tr><th>#</th><th>Servicio</th><th>Fecha</th><th>Estado</th></tr></thead>
                    <tbody>
                    <?php if (empty($reservas)): ?>
                        <tr><td colspan="4" style="text-align:center;padding:1.5rem;color:var(--text-light)">Sin reservas</td></tr>
                    <?php else: foreach ($reservas as $r):
                        $rb = ['pendiente'=>'badge-warning','confirmada'=>'badge-success','cancelada'=>'badge-danger'];
                    ?>
                        <tr>
                            <td>#<?= $r['id'] ?></td>
                            <td><?= htmlspecialchars($r['servicio_nombre']) ?></td>
                            <td><?= date('d/m/Y', strtotime($r['fecha'])) ?> <?= substr($r['hora_inicio'], 0, 5) ?></td>
                            <td><span class="badge <?= $rb[$r['estado']] ?? 'badge-secondary' ?>"><?= $r['estado'] ?></span></td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
