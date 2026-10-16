<div class="confirmacion-container">
    <div class="confirmacion-card">
        <div class="confirmacion-icon">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none">
                <circle cx="40" cy="40" r="38" stroke="#4A90A4" stroke-width="2"/>
                <path d="M30 40L36 46L50 32" stroke="#4A90A4" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
            </svg>
        </div>

        <h1>Tu reserva está en espera de confirmación</h1>
        
        <div class="info-box">
            <p>Hola <strong><?= htmlspecialchars($reserva['nombre_cliente']) ?></strong>,</p>
            <p>Hemos recibido tu solicitud de reserva. Nos contactaremos pronto para confirmar la disponibilidad y los detalles de tu sesión.</p>
        </div>

        <div class="detalles">
            <h3>Detalles de tu reserva</h3>
            <div class="detalle-item">
                <span class="label">Servicio:</span>
                <span class="valor"><?= htmlspecialchars($reserva['servicio_nombre']) ?></span>
            </div>
            <div class="detalle-item">
                <span class="label">Duración:</span>
                <span class="valor"><?= $reserva['duracion_minutos'] ?> minutos</span>
            </div>
            <div class="detalle-item">
                <span class="label">Modalidad:</span>
                <span class="valor"><?= ucfirst($reserva['modalidad']) ?></span>
            </div>
            <div class="detalle-item">
                <span class="label">Monto:</span>
                <span class="valor">$<?= number_format($reserva['monto'], 0, ',', '.') ?></span>
            </div>
            <div class="detalle-item">
                <span class="label">Estado:</span>
                <span class="valor badge">En espera de confirmación</span>
            </div>
        </div>

        <div class="info-box info-email">
            <p>📧 Te enviaremos un email a <strong><?= htmlspecialchars($reserva['email_cliente']) ?></strong> cuando confirmemos tu reserva.</p>
        </div>

        <div class="acciones">
            <a href="<?= BASE_URL ?>/" class="btn btn-primary">Volver al inicio</a>
            <a href="<?= BASE_URL ?>/servicios" class="btn btn-secondary">Ver más servicios</a>
        </div>
    </div>
</div>

<style>
.confirmacion-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    background: linear-gradient(135deg, #E8F4F8 0%, #B8D4E3 100%);
}

.confirmacion-card {
    background: white;
    border-radius: 12px;
    padding: 40px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    text-align: center;
}

.confirmacion-icon {
    margin-bottom: 30px;
    display: flex;
    justify-content: center;
}

.confirmacion-card h1 {
    color: #2C5F6F;
    font-size: 24px;
    margin-bottom: 20px;
    font-family: 'Playfair Display', serif;
}

.info-box {
    background: #F0F8FB;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    text-align: left;
    border-left: 4px solid #4A90A4;
}

.info-box p {
    margin: 10px 0;
    color: #333;
    line-height: 1.6;
}

.detalles {
    background: #F9F9F9;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    text-align: left;
}

.detalles h3 {
    color: #2C5F6F;
    font-size: 16px;
    margin-bottom: 15px;
    font-family: 'Playfair Display', serif;
}

.detalle-item {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.detalle-item:last-child {
    border-bottom: none;
}

.detalle-item .label {
    font-weight: 600;
    color: #666;
}

.detalle-item .valor {
    color: #333;
}

.badge {
    display: inline-block;
    background: #fff3cd;
    color: #856404;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.info-email {
    background: #E8F4F8;
    border-left-color: #7FB3D5;
}

.acciones {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn {
    flex: 1;
    display: inline-block;
    padding: 12px 20px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary {
    background: #4A90A4;
    color: white;
}

.btn-primary:hover {
    background: #2C5F6F;
    transform: translateY(-2px);
}

.btn-secondary {
    background: #E8F4F8;
    color: #4A90A4;
    border: 2px solid #4A90A4;
}

.btn-secondary:hover {
    background: #D0E8F0;
}

@media (max-width: 600px) {
    .confirmacion-card {
        padding: 25px;
    }

    .confirmacion-card h1 {
        font-size: 20px;
    }

    .acciones {
        flex-direction: column;
    }
}
</style>
