<?php if (isset($_GET['eliminado'])): ?>
<div class="alert alert-success">Producto desactivado correctamente.</div>
<?php endif; ?>

<div class="page-header">
    <h1>🌿 Productos</h1>
    <a href="<?= BASE_URL ?>/admin/productos/crear" class="btn btn-primary">+ Nuevo producto</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Variantes</th>
                    <th>Destacado</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($productos)): ?>
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-light)">No hay productos. <a href="<?= BASE_URL ?>/admin/productos/crear">Crear el primero</a></td></tr>
            <?php else: foreach ($productos as $p): ?>
                <tr>
                    <td>
                        <img src="<?= $p['imagen_principal'] ? BASE_URL . $p['imagen_principal'] : BASE_URL . '/public/images/placeholder.svg' ?>"
                             class="img-preview" style="width:50px;height:50px" alt="">
                    </td>
                    <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                    <td><?= htmlspecialchars($p['categoria_nombre'] ?? '-') ?></td>
                    <td><?= $p['total_variantes'] ?? 0 ?></td>
                    <td><?= $p['destacado'] ? '<span class="badge badge-success">Sí</span>' : '<span class="badge badge-secondary">No</span>' ?></td>
                    <td><?= $p['activo'] ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>' ?></td>
                    <td style="white-space:nowrap">
                        <a href="<?= BASE_URL ?>/admin/productos/editar/<?= $p['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <a href="<?= BASE_URL ?>/admin/productos/eliminar/<?= $p['id'] ?>"
                           class="btn btn-danger btn-sm"
                           data-confirm="¿Desactivar este producto?">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
