<?php if (isset($_GET['ok'])): ?>
<div class="alert alert-success">✅ Categoría guardada correctamente.</div>
<?php endif; ?>

<div class="page-header">
    <h1>🏷️ Categorías</h1>
    <a href="<?= BASE_URL ?>/admin/categorias/crear" class="btn btn-primary">+ Nueva categoría</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr><th>#</th><th>Nombre</th><th>Slug</th><th>Descripción</th><th>Estado</th><th>Acciones</th></tr>
            </thead>
            <tbody>
            <?php if (empty($categorias)): ?>
                <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--text-light)">No hay categorías.</td></tr>
            <?php else: foreach ($categorias as $c): ?>
                <tr>
                    <td><?= $c['id'] ?></td>
                    <td><strong><?= htmlspecialchars($c['nombre']) ?></strong></td>
                    <td><code><?= htmlspecialchars($c['slug']) ?></code></td>
                    <td><?= htmlspecialchars(substr($c['descripcion'] ?? '', 0, 60)) ?></td>
                    <td><?= $c['activo'] ? '<span class="badge badge-success">Activa</span>' : '<span class="badge badge-secondary">Inactiva</span>' ?></td>
                    <td style="white-space:nowrap">
                        <a href="<?= BASE_URL ?>/admin/categorias/editar/<?= $c['id'] ?>" class="btn btn-warning btn-sm">✏️</a>
                        <a href="<?= BASE_URL ?>/admin/categorias/eliminar/<?= $c['id'] ?>"
                           class="btn btn-danger btn-sm"
                           data-confirm="¿Eliminar esta categoría?">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
