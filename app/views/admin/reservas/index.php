<div class="admin-container">
    <div class="admin-header">
        <h1>Gestionar Reservas</h1>
        <div class="filtros">
            <a href="?estado=pendiente_confirmacion" class="btn <?= ($estado_actual ?? 'pendiente_confirmacion') === 'pendiente_confirmacion' ? 'btn-primary' : 'btn-secondary' ?>">
                Pendientes
            </a>
            <a href="?estado=confirmada" class="btn <?= ($estado_actual ?? '') === 'confirmada' ? 'btn-primary' : 'btn-secondary' ?>">
                Confirmadas
            </a>
            <a href="?estado=rechazada" class="btn <?= ($estado_actual ?? '') === 'rechazada' ? 'btn-primary' : 'btn-secondary' ?>">
                Rechazadas
            </a>
            <a href="?estado=cancelada" class="btn <?= ($estado_actual ?? '') === 'cancelada' ? 'btn-primary' : 'btn-secondary' ?>">
                Canceladas
            </a>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success">Reserva actualizada correctamente</div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger">Error: <?= htmlspecialchars($_GET['error']) ?></div>
    <?php endif; ?>

    <table class="admin-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Cliente</th>
                <th>Servicio</th>
                <th>Fecha Solicitada</th>
                <th>Modalidad</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reservas)): foreach ($reservas as $r): ?>
                <tr>
                    <td>#<?= $r['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($r['nombre_cliente'] ?? '-') ?></strong><br>
                        <small><?= htmlspecialchars($r['email_cliente'] ?? '-') ?></small>
                    </td>
                    <td><?= htmlspecialchars($r['servicio_nombre'] ?? '-') ?></td>
                    <td><?= isset($r['created_at']) ? date('d/m/Y', strtotime($r['created_at'])) : '-' ?></td>
                    <td><?= ucfirst($r['modalidad'] ?? '-') ?></td>
                    <td>$<?= number_format($r['monto'] ?? 0, 0, ',', '.') ?></td>
                    <td>
                        <span class="badge badge-<?= str_replace('_', '-', $r['estado']) ?>">
                            <?= str_replace('_', ' ', ucfirst($r['estado'])) ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/reservas/ver/<?= $r['id'] ?>" class="btn btn-sm btn-info">Ver</a>
                    </td>
                </tr>
            <?php endforeach; else: ?>
                <tr>
                    <td colspan="8" style="text-align:center;color:#999">No hay reservas en este estado</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
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

.admin-header h1 {
    margin: 0;
}

.filtros {
    display: flex;
    gap: 10px;
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
    font-size: 13px;
}

.admin-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}

.badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
}

.badge-pendiente-confirmacion {
    background: #fff3cd;
    color: #856404;
}

.badge-confirmada {
    background: #d4edda;
    color: #155724;
}

.badge-rechazada {
    background: #f8d7da;
    color: #721c24;
}

.badge-cancelada {
    background: #e2e3e5;
    color: #383d41;
}

.btn {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: #4A90A4;
    color: white;
}

.btn-secondary {
    background: #e9ecef;
    color: #333;
}

.btn-info {
    background: #17a2b8;
    color: white;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 11px;
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
</style>
