<div class="admin-container">
    <div class="admin-header">
        <h1>Gestionar Servicios</h1>
        <a href="<?= BASE_URL ?>/admin/servicios/crear" class="btn btn-primary">+ Nuevo Servicio</a>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">
            <?php 
                $msg = [
                    'creado' => 'Servicio creado correctamente',
                    'actualizado' => 'Servicio actualizado correctamente',
                    'eliminado' => 'Servicio eliminado correctamente'
                ];
                echo $msg[$_GET['success']] ?? 'Operación completada';
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Error: <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <table class="admin-table">
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Duración</th>
                <th>Precio Online</th>
                <th>Precio Presencial</th>
                <th>Modalidad</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($servicios as $s): ?>
                <tr>
                    <td>
                        <?php if ($s['imagen']): ?>
                            <?php
                                if (strpos($s['imagen'], 'data:') === 0 || strlen($s['imagen']) > 500) {
                                    $imgSrc = 'data:image/jpeg;base64,' . $s['imagen'];
                                } elseif (str_starts_with($s['imagen'], '/')) {
                                    $imgSrc = BASE_URL . htmlspecialchars($s['imagen']);
                                } else {
                                    $imgSrc = BASE_URL.'/public/uploads/services/'.htmlspecialchars($s['imagen']);
                                }
                            ?>
                            <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($s['nombre']) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:4px">
                        <?php else: ?>
                            <span style="color:#999">Sin imagen</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= htmlspecialchars($s['nombre']) ?></strong></td>
                    <td><?= $s['duracion_minutos'] ?> min</td>
                    <td>$<?= number_format($s['precio'], 0, ',', '.') ?></td>
                    <td><?= $s['precio_presencial'] ? '$' . number_format($s['precio_presencial'], 0, ',', '.') : '-' ?></td>
                    <td><?= ucfirst($s['modalidad']) ?></td>
                    <td>
                        <span class="badge <?= $s['activo'] ? 'badge-success' : 'badge-danger' ?>">
                            <?= $s['activo'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/servicios/editar/<?= $s['id'] ?>" class="btn btn-sm btn-info">Editar</a>
                        <a href="<?= BASE_URL ?>/admin/servicios/eliminar/<?= $s['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if (empty($servicios)): ?>
        <p class="text-center">No hay servicios registrados</p>
    <?php endif; ?>
</div>

<style>
.admin-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.admin-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.admin-table th {
    background: #f5f5f5;
    padding: 12px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #ddd;
}

.admin-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: #4A90A4;
    color: white;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

.alert {
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-danger {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.text-center {
    text-align: center;
    padding: 20px;
    color: #666;
}
</style>
