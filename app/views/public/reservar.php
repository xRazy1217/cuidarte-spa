<!-- Banner -->
<div class="wc-page-banner">
    <img src="<?= BASE_URL ?>/public/images/banner/reservar.svg" alt="Reservar">
    <div class="wc-page-banner-content">
        <h1>Reserva tu Sesión</h1>
        <p>Elige el servicio y la fecha que mejor se ajuste a ti</p>
    </div>
</div>

<!-- Servicios disponibles -->
<section class="wc-section">
    <div class="container">
        <div class="wc-section-title">
            <h2>Servicios Disponibles</h2>
            <p>Selecciona el servicio que más se adapte a ti</p>
        </div>
        <div class="row g-4">
            <?php foreach ($servicios as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="wc-card h-100">
                    <div class="wc-card-body">
                        <h4 class="wc-card-title"><?= htmlspecialchars($s['nombre']) ?></h4>
                        <p class="wc-card-text"><?= htmlspecialchars($s['descripcion']) ?></p>
                        <div style="color:var(--wc-text-soft);font-size:0.9rem;margin-bottom:0.75rem">
                            ⏱️ <?= $s['duracion_minutos'] ?> minutos
                        </div>
                        <div class="wc-card-price">$<?= number_format($s['precio'], 0, ',', '.') ?></div>
                    </div>
                    <div class="wc-card-footer">
                        <button onclick="abrirFormularioReserva(<?= $s['id'] ?>)" class="btn-wc-pink" style="width:100%">
                            📅 Reservar este servicio
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Modal de Formulario de Reserva -->
<div id="modalReserva" class="modal-overlay" style="display:none;">
    <div class="modal-content">
        <button class="modal-close" onclick="cerrarFormularioReserva()">✕</button>
        
        <h2>Solicitar Reserva</h2>
        <p class="modal-subtitle">Completa el formulario para solicitar tu sesión</p>

        <form method="POST" action="<?= BASE_URL ?>/reservar" id="formReserva">
            <input type="hidden" name="servicio_id" id="servicio_id" value="">

            <div class="form-group">
                <label for="nombre">Nombre Completo *</label>
                <input type="text" name="nombre" id="nombre" required placeholder="Tu nombre">
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" required placeholder="tu@email.com">
            </div>

            <div class="form-group">
                <label for="telefono">Teléfono</label>
                <input type="tel" name="telefono" id="telefono" placeholder="+56 9 XXXX XXXX">
            </div>

            <div class="form-group">
                <label for="fecha">Fecha Deseada *</label>
                <input type="date" name="fecha" id="fecha" required>
            </div>

            <div class="form-group">
                <label for="modalidad">Modalidad *</label>
                <select name="modalidad" id="modalidad" required>
                    <option value="">Selecciona una modalidad</option>
                    <option value="online">Online</option>
                    <option value="presencial">Presencial</option>
                </select>
            </div>

            <div class="form-group">
                <label for="notas">Notas Adicionales</label>
                <textarea name="notas" id="notas" rows="3" placeholder="Cuéntanos algo sobre ti o tus necesidades..."></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-wc-primary">Solicitar Reserva</button>
                <button type="button" class="btn-wc-secondary" onclick="cerrarFormularioReserva()">Cancelar</button>
            </div>

            <p class="form-info">
                ℹ️ Tu solicitud será revisada por nuestro equipo. Te contactaremos para confirmar la disponibilidad.
            </p>
        </form>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-content {
    background: white;
    border-radius: 12px;
    padding: 40px;
    max-width: 500px;
    width: 100%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #999;
    transition: color 0.3s;
}

.modal-close:hover {
    color: #333;
}

.modal-content h2 {
    color: #2C5F6F;
    font-family: 'Playfair Display', serif;
    margin-bottom: 10px;
    font-size: 24px;
}

.modal-subtitle {
    color: #999;
    margin-bottom: 25px;
    font-size: 14px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: inherit;
    font-size: 14px;
    box-sizing: border-box;
    transition: border-color 0.3s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #4A90A4;
    box-shadow: 0 0 0 3px rgba(74, 144, 164, 0.1);
}

.form-group textarea {
    resize: vertical;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.btn-wc-primary,
.btn-wc-secondary {
    flex: 1;
    padding: 12px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 14px;
}

.btn-wc-primary {
    background: #4A90A4;
    color: white;
}

.btn-wc-primary:hover {
    background: #2C5F6F;
    transform: translateY(-2px);
}

.btn-wc-secondary {
    background: #E8F4F8;
    color: #4A90A4;
    border: 2px solid #4A90A4;
}

.btn-wc-secondary:hover {
    background: #D0E8F0;
}

.form-info {
    margin-top: 15px;
    padding: 12px;
    background: #F0F8FB;
    border-left: 4px solid #4A90A4;
    border-radius: 4px;
    font-size: 13px;
    color: #666;
}

@media (max-width: 600px) {
    .modal-content {
        padding: 25px;
    }

    .modal-content h2 {
        font-size: 20px;
    }

    .form-actions {
        flex-direction: column;
    }
}
</style>

<script>
function abrirFormularioReserva(servicioId) {
    document.getElementById('servicio_id').value = servicioId;
    document.getElementById('modalReserva').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    // Establecer fecha mínima a hoy
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').min = hoy;
}

function cerrarFormularioReserva() {
    document.getElementById('modalReserva').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('formReserva').reset();
}

// Cerrar modal al hacer click fuera
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalReserva');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            cerrarFormularioReserva();
        }
    });
});
</script>
