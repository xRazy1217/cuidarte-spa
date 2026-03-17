<?php if (isset($_GET['ok'])): ?>
<div class="alert alert-success">✅ Cupón guardado correctamente.</div>
<?php endif; ?>

<div class="page-header">
    <h1>🎟️ Cupones</h1>
    <a href="<?= BASE_URL ?>/admin/cupones/crear" class="btn btn-primary">+ Nuevo cupón</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Usos</th>
                    <th>Vence</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($cupones)): ?>
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-light)">No hay cupones. <a href="<?= BASE_URL ?>/admin/cupones/crear">Crear el primero</a></td></tr>
            <?php else: foreach ($cupones as $c): ?>
                <tr>
                    <td><strong style="font-family:monospace;font-size:1rem"><?= htmlspecialchars($c['codigo']) ?></strong></td>
                    <td><?= $c['tipo'] === 'porcentaje' ? 'Porcentaje' : 'Monto fijo' ?></td>
                    <td><?= $c['tipo'] === 'porcentaje' ? $c['valor'].'%' : '$'.number_format($c['valor'],0,',','.') ?></td>
                    <td><?= $c['uso_actual'] ?? 0 ?> / <?= $c['uso_maximo'] ?></td>
                    <td><?= $c['fecha_expiracion'] ? date('d/m/Y', strtotime($c['fecha_expiracion'])) : 'Sin vencimiento' ?></td>
                    <td><?= $c['activo'] ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>' ?></td>
                    <td style="white-space:nowrap">
                        <a href="<?= BASE_URL ?>/admin/cupones/editar/<?= $c['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                        <a href="<?= BASE_URL ?>/admin/cupones/eliminar/<?= $c['id'] ?>"
                           class="btn btn-danger btn-sm"
                           data-confirm="¿Eliminar este cupón?">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
