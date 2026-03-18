<div class="page-header">
    <h1>💌 Mensajes de Contacto</h1>
</div>

<div class="admin-card">
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Mensaje</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($mensajes)): ?>
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-light)">No hay mensajes aún</td></tr>
            <?php else: foreach ($mensajes as $m): ?>
                <tr style="<?= !$m['leido'] ? 'font-weight:700;background:rgba(74,144,164,0.05)' : '' ?>">
                    <td>#<?= $m['id'] ?></td>
                    <td><?= htmlspecialchars($m['nombre']) ?></td>
                    <td><?= htmlspecialchars($m['email']) ?></td>
                    <td style="max-width:250px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"><?= htmlspecialchars($m['mensaje']) ?></td>
                    <td>
                        <span class="badge <?= $m['leido'] ? 'badge-secondary' : 'badge-success' ?>">
                            <?= $m['leido'] ? 'Leído' : 'Nuevo' ?>
                        </span>
                    </td>
                    <td><?= date('d/m/Y H:i', strtotime($m['created_at'])) ?></td>
                    <td style="display:flex;gap:0.5rem">
                        <a href="<?= BASE_URL ?>/admin/mensajes/ver/<?= $m['id'] ?>" class="btn btn-warning btn-sm">Ver</a>
                        <form method="POST" action="<?= BASE_URL ?>/admin/mensajes/eliminar/<?= $m['id'] ?>" onsubmit="return confirm('¿Eliminar este mensaje?')">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
