<div class="admin-container">
    <div class="admin-header">
        <h1>Detalle Reserva #<?= $reserva['id'] ?></h1>
        <a href="<?= BASE_URL ?>/admin/reservas" class="btn btn-secondary">Volver</a>
    </div>

    <div class="reserva-detalle">
        <div class="info-section">
            <h3>Información del Cliente</h3>
            <p><strong>Nombre:</strong> <?= htmlspecialchars($reserva['nombre_cliente']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($reserva['email_cliente']) ?></p>
            <p><strong>Teléfono:</strong> <?= htmlspecialchars($reserva['telefono'] ?? 'N/A') ?></p>
        </div>

        <div class="info-section">
            <h3>Información de la Reserva</h3>
            <p><strong>Servicio:</strong> <?= htmlspecialchars($reserva['servicio_nombre']) ?></p>
            <p><strong>Duración:</strong> <?= $reserva['duracion_minutos'] ?> minutos</p>
            <p><strong>Modalidad:</strong> <?= ucfirst($reserva['modalidad']) ?></p>
            <p><strong>Monto:</strong> $<?= number_format($reserva['monto'], 0, ',', '.') ?></p>
            <p><strong>Fecha Creada:</strong> <?= date('d/m/Y H:i', strtotime($reserva['created_at'])) ?></p>
        </div>

        <?php if ($reserva['notas']): ?>
            <div class="info-section">
                <h3>Notas del Cliente</h3>
                <p><?= nl2br(htmlspecialchars($reserva['notas'])) ?></p>
            </div>
        <?php endif; ?>

        <div class="info-section">
            <h3>Estado Actual</h3>
            <p>
                <span class="badge badge-<?= $reserva['estado'] ?>">
                    <?= ucfirst($reserva['estado']) ?>
                </span>
            </p>
        </div>

        <form method="POST" class="form-actualizar">
            <div class="form-group">
                <label for="estado">Cambiar Estado:</label>
                <select name="estado" id="estado" required>
                    <option value="pendiente" <?= $reserva['estado'] === 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
                    <option value="confirmada" <?= $reserva['estado'] === 'confirmada' ? 'selected' : '' ?>>Confirmada</option>
                    <option value="cancelada" <?= $reserva['estado'] === 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notas_admin">Notas/Motivo:</label>
                <textarea name="notas_admin" id="notas_admin" rows="4" placeholder="Ej: Confirmada para el 25 de marzo a las 14:00"><?= htmlspecialchars($reserva['notas'] ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <a href="<?= BASE_URL ?>/admin/reservas" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
.admin-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.reserva-detalle {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.info-section {
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.info-section:last-of-type {
    border-bottom: none;
}

.info-section h3 {
    margin-top: 0;
    color: #333;
    font-size: 16px;
}

.info-section p {
    margin: 8px 0;
    color: #666;
}

.form-actualizar {
    margin-top: 30px;
    padding-top: 20px;
    border-top: 2px solid #eee;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: inherit;
    font-size: 14px;
    box-sizing: border-box;
}

.form-group textarea {
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: #4A90A4;
    color: white;
}

.btn-primary:hover {
    background: #2C5F6F;
}

.btn-secondary {
    background: #e9ecef;
    color: #333;
}

.btn-secondary:hover {
    background: #ddd;
}

.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: 600;
}

.badge-pendiente {
    background: #fff3cd;
    color: #856404;
}

.badge-confirmada {
    background: #d4edda;
    color: #155724;
}

.badge-cancelada {
    background: #f8d7da;
    color: #721c24;
}
</style>
