<?php if (isset($_GET['eliminado'])): ?>
<div class="alert alert-success">Artículo eliminado.</div>
<?php endif; ?>

<div class="page-header">
    <h1>✍️ Blog</h1>
    <a href="<?= BASE_URL ?>/admin/blog/crear" class="btn btn-primary">+ Nuevo artículo</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Imagen</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Publicado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($articulos)): ?>
                <tr><td colspan="6" style="text-align:center;padding:3rem;color:var(--text-light)">No hay artículos. <a href="<?= BASE_URL ?>/admin/blog/crear">Crear el primero</a></td></tr>
            <?php else: foreach ($articulos as $a): ?>
                <tr>
                    <td>
                        <img src="<?= $a['imagen'] ? BASE_URL . $a['imagen'] : BASE_URL . '/public/images/placeholder.svg' ?>"
                             class="img-preview" style="width:50px;height:50px" alt="">
                    </td>
                    <td><strong><?= htmlspecialchars($a['titulo']) ?></strong></td>
                    <td><?= htmlspecialchars($a['categoria'] ?? '-') ?></td>
                    <td><?= $a['publicado'] ? '<span class="badge badge-success">Sí</span>' : '<span class="badge badge-secondary">No</span>' ?></td>
                    <td><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
                    <td style="white-space:nowrap">
                        <a href="<?= BASE_URL ?>/admin/blog/editar/<?= $a['id'] ?>" class="btn btn-warning btn-sm">✏️ Editar</a>
                        <a href="<?= BASE_URL ?>/admin/blog/eliminar/<?= $a['id'] ?>"
                           class="btn btn-danger btn-sm"
                           data-confirm="¿Eliminar este artículo?">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
