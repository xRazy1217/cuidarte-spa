<!-- BANNER -->
<section class="wc-page-banner">
    <img src="<?= BASE_URL ?>/public/images/banner/Nosotros.png" alt="Servicios">
    <div class="wc-page-banner-content">
        <h1>Nuestros Servicios</h1>
        <p>Sesiones terapéuticas online y presenciales para tu bienestar</p>
    </div>
</section>

<!-- SERVICIOS - CARDS CON IMAGEN -->
<section class="wc-section">
    <div class="container">
        <?php if (empty($servicios)): ?>
        <div class="text-center py-5" style="color:var(--wc-text-soft)">
            <div style="font-size:4rem;margin-bottom:1rem">📋</div>
            <h3>Próximamente nuevos servicios</h3>
            <p>Estamos preparando experiencias únicas para ti</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($servicios as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="servicio-card" onclick="abrirDetalleServicio(<?= $s['id'] ?>)">
                    <div class="servicio-card-image">
                        <?php
                            if (!$s['imagen']) {
                                $imgSrc = BASE_URL.'/public/images/placeholder.svg';
                            } elseif (strpos($s['imagen'], 'data:') === 0 || strlen($s['imagen']) > 500) {
                                // Es base64
                                $imgSrc = 'data:image/jpeg;base64,' . $s['imagen'];
                            } elseif (str_starts_with($s['imagen'], '/')) {
                                // Es ruta absoluta
                                $imgSrc = BASE_URL . htmlspecialchars($s['imagen']);
                            } else {
                                // Es nombre de archivo
                                $imgSrc = BASE_URL.'/public/uploads/services/'.htmlspecialchars($s['imagen']);
                            }
                        ?>
                        <img src="<?= $imgSrc ?>"
                             alt="<?= htmlspecialchars($s['nombre']) ?>">
                        <div class="servicio-card-overlay">
                            <span class="servicio-card-cta">Ver detalles</span>
                        </div>
                    </div>
                    <div class="servicio-card-info">
                        <h3 class="servicio-card-nombre"><?= htmlspecialchars($s['nombre']) ?></h3>
                        <p class="servicio-card-precio" style="display:none">$<?= number_format($s['precio'], 0, ',', '.') ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- MODAL - DETALLE SERVICIO + FORMULARIO RESERVA -->
<div id="modalReservaCompleto" class="modal-overlay" style="display:none;">
    <div class="modal-content-dual">
        <button class="modal-close" onclick="cerrarReservaCompleto()">✕</button>
        
        <div class="modal-detalle-lado">
            <div id="detalleServicioContent">
                <!-- Se llena con JavaScript -->
            </div>
        </div>
        
        <div class="modal-formulario-lado">
            <h2>Solicitar Reserva</h2>
            <p class="modal-subtitle">Completa el formulario para solicitar tu sesión</p>

            <form method="POST" action="<?= BASE_URL ?>/reservar" id="formReserva">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
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
                    <button type="button" class="btn-wc-secondary" onclick="cerrarReservaCompleto()">Cancelar</button>
                </div>

                <p class="form-info">
                    ℹ️ Tu solicitud será revisada por nuestro equipo. Te contactaremos para confirmar la disponibilidad.
                </p>
            </form>
        </div>
    </div>
</div>

<!-- CÓMO FUNCIONA -->
<section class="wc-section wc-bg-watercolor">
    <div class="container">
        <div class="wc-section-title">
            <h2>¿Cómo funciona?</h2>
            <p>Proceso simple para reservar tu sesión</p>
        </div>
        <div class="row g-4 text-center">
            <?php
            $pasos = [
                ['1','Elige tu servicio','Selecciona el servicio que más se adapte a tus necesidades','wc-icon-blue'],
                ['2','Elige fecha','Escoge la fecha que mejor te acomode','wc-icon-pink'],
                ['3','Realiza reserva','Confirma tu reserva de forma segura','wc-icon-yellow'],
                ['4','Confirmación','Recibirás un correo con todos los detalles de tu sesión','wc-icon-purple'],
            ];
            foreach ($pasos as $p): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="wc-value-icon <?= $p[3] ?>"><?= $p[0] ?></div>
                <h5 style="font-size:1rem;color:var(--wc-blue-dark);margin-bottom:0.5rem"><?= $p[1] ?></h5>
                <p style="color:var(--wc-text-soft);font-size:0.9rem"><?= $p[2] ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="wc-section-sm text-center">
    <div class="container" style="max-width:700px">
        <h2 style="font-size:1.8rem;margin-bottom:1rem">¿Tienes dudas?</h2>
        <p style="color:var(--wc-text-soft);margin-bottom:2rem">Contáctanos y te ayudamos a elegir el servicio ideal</p>
        <a href="<?= BASE_URL ?>/contacto" class="btn-wc-primary">Contactar</a>
    </div>
</section>

<style>
.servicio-card {
    cursor: pointer;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.08);
}

.servicio-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
}

.servicio-card-info {
    padding: 1rem 1.25rem;
}

.servicio-card-nombre {
    font-size: 1rem;
    color: #2C5F6F;
    margin-bottom: 0.25rem;
    font-family: 'Playfair Display', serif;
}

.servicio-card-precio {
    font-size: 0.9rem;
    font-weight: 700;
    color: #4A90A4;
    margin: 0;
}

.servicio-card-image {
    position: relative;
    width: 100%;
    padding-bottom: 120%;
    overflow: hidden;
    background: #f0f0f0;
}

.servicio-card-image img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.servicio-card:hover .servicio-card-image img {
    transform: scale(1.05);
}

.servicio-card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(74, 144, 164, 0.85);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.servicio-card:hover .servicio-card-overlay {
    opacity: 1;
}

.servicio-card-cta {
    color: white;
    font-weight: 600;
    font-size: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

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

.modal-content-dual {
    background: white;
    border-radius: 12px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    padding: 40px;
    max-width: 1000px;
    width: 100%;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
    position: relative;
    animation: slideIn 0.3s ease;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-detalle-lado {
    border-right: 1px solid #eee;
    padding-right: 20px;
}

.modal-detalle-lado h2 {
    color: #2C5F6F;
    font-family: 'Playfair Display', serif;
    margin-bottom: 15px;
    font-size: 22px;
}

.modal-detalle-lado .servicio-detalle-descripcion {
    color: #666;
    line-height: 1.6;
    margin: 20px 0;
    white-space: pre-wrap;
    word-wrap: break-word;
    font-size: 14px;
}

.modal-detalle-lado .servicio-detalle-item {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #eee;
}

.modal-detalle-lado .servicio-detalle-item:last-child {
    border-bottom: none;
}

.modal-detalle-lado .servicio-detalle-label {
    font-weight: 600;
    color: #666;
    font-size: 13px;
}

.modal-detalle-lado .servicio-detalle-valor {
    color: #333;
    font-size: 13px;
}

.modal-detalle-lado .servicio-detalle-precio {
    font-size: 18px;
    font-weight: 600;
    color: #4A90A4;
}

.modal-formulario-lado {
    padding-left: 20px;
}

.modal-formulario-lado h2 {
    color: #2C5F6F;
    font-family: 'Playfair Display', serif;
    margin-bottom: 10px;
    font-size: 20px;
}

.modal-formulario-lado .modal-subtitle {
    color: #999;
    margin-bottom: 20px;
    font-size: 13px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #333;
    font-size: 13px;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-family: inherit;
    font-size: 13px;
    box-sizing: border-box;
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
    margin-top: 20px;
}

.btn-wc-primary,
.btn-wc-secondary {
    flex: 1;
    padding: 10px 15px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    font-size: 13px;
}

.btn-wc-primary {
    background: #4A90A4;
    color: white;
}

.btn-wc-primary:hover {
    background: #2C5F6F;
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
    margin-top: 12px;
    padding: 10px;
    background: #F0F8FB;
    border-left: 3px solid #4A90A4;
    border-radius: 4px;
    font-size: 12px;
    color: #666;
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

@media (max-width: 900px) {
    .modal-content-dual {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .modal-detalle-lado {
        border-right: none;
        border-bottom: 1px solid #eee;
        padding-right: 0;
        padding-bottom: 20px;
    }

    .modal-formulario-lado {
        padding-left: 0;
    }
}
</style>

<script>
const serviciosData = <?= json_encode($servicios) ?>;

function abrirDetalleServicio(servicioId) {
    const servicio = serviciosData.find(s => s.id == servicioId);
    if (!servicio) return;

    const html = `
        <h2>${servicio.nombre}</h2>
        <p class="servicio-detalle-descripcion">${servicio.descripcion.replace(/\n/g, '<br>')}</p>
        
        <div class="servicio-detalle-item">
            <span class="servicio-detalle-label">Duración:</span>
            <span class="servicio-detalle-valor">${servicio.duracion_minutos} minutos</span>
        </div>
        
        <div class="servicio-detalle-item">
            <span class="servicio-detalle-label">Modalidad:</span>
            <span class="servicio-detalle-valor">${servicio.modalidad.charAt(0).toUpperCase() + servicio.modalidad.slice(1)}</span>
        </div>
        
        <div class="servicio-detalle-item">
            <span class="servicio-detalle-label">Precio Online:</span>
            <span class="servicio-detalle-precio">$${new Intl.NumberFormat('es-CL').format(servicio.precio)}</span>
        </div>
        
        ${servicio.precio_presencial ? `
        <div class="servicio-detalle-item">
            <span class="servicio-detalle-label">Precio Presencial:</span>
            <span class="servicio-detalle-precio">$${new Intl.NumberFormat('es-CL').format(servicio.precio_presencial)}</span>
        </div>
        ` : ''}
    `;

    document.getElementById('detalleServicioContent').innerHTML = html;
    document.getElementById('servicio_id').value = servicioId;
    document.getElementById('modalReservaCompleto').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    
    const hoy = new Date().toISOString().split('T')[0];
    document.getElementById('fecha').min = hoy;
}

function cerrarReservaCompleto() {
    document.getElementById('modalReservaCompleto').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('formReserva').reset();
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modalReservaCompleto');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            cerrarReservaCompleto();
        }
    });
});
</script>
