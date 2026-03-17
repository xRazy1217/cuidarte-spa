<!-- BANNER -->
<section class="wc-page-banner">
    <img src="<?= BASE_URL ?>/public/images/banner/Nosotros.png" alt="Servicios">
    <div class="wc-page-banner-content">
        <h1>Nuestros Servicios 💆</h1>
        <p>Sesiones terapéuticas online y presenciales para tu bienestar</p>
    </div>
</section>

<!-- SERVICIOS -->
<section class="wc-section">
    <div class="container">
        <?php if (empty($servicios)): ?>
        <div class="text-center py-5" style="color:var(--wc-text-soft)">
            <div style="font-size:4rem;margin-bottom:1rem">💆</div>
            <h3>Próximamente nuevos servicios</h3>
            <p>Estamos preparando experiencias únicas para ti</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($servicios as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="wc-card">
                    <div class="wc-card-img">
                        <img src="<?= $s['imagen'] ? BASE_URL.'/public/uploads/services/'.htmlspecialchars($s['imagen']) : BASE_URL.'/public/images/placeholder.svg' ?>"
                             alt="<?= htmlspecialchars($s['nombre']) ?>">
                    </div>
                    <div class="wc-card-body">
                        <div class="mb-2">
                            <span class="wc-badge wc-badge-blue">🖥️ Online</span>
                            <span class="wc-badge wc-badge-pink">🏠 Presencial</span>
                        </div>
                        <h3 class="wc-card-title"><?= htmlspecialchars($s['nombre']) ?></h3>
                        <p class="wc-card-text"><?= htmlspecialchars($s['descripcion']) ?></p>
                        <div style="color:var(--wc-text-soft);font-size:0.9rem;margin-bottom:0.5rem">
                            ⏱️ <?= $s['duracion_minutos'] ?> minutos
                        </div>
                        <div class="d-flex gap-3 align-items-center flex-wrap">
                            <div>
                                <small style="color:var(--wc-text-soft)">Online</small><br>
                                <span class="wc-card-price" style="font-size:1.2rem">$<?= number_format($s['precio'],0,',','.') ?></span>
                            </div>
                            <?php if (!empty($s['precio_presencial'])): ?>
                            <div>
                                <small style="color:var(--wc-text-soft)">Presencial</small><br>
                                <span class="wc-card-price" style="font-size:1.2rem;color:var(--wc-purple)">$<?= number_format($s['precio_presencial'],0,',','.') ?></span>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="wc-card-footer">
                        <a href="#" onclick="abrirReserva(<?= $s['id'] ?>);return false" class="btn-wc-pink w-100 text-center d-block">📅 Reservar Ahora</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

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
                ['🔍','1. Elige tu servicio','Selecciona el servicio que más se adapte a tus necesidades','wc-icon-blue'],
                ['📅','2. Elige horario','Escoge la fecha y hora disponible que mejor te acomode','wc-icon-pink'],
                ['💳','3. Realiza el pago','Paga de forma segura para confirmar tu reserva','wc-icon-yellow'],
                ['📧','4. Confirmación','Recibirás un correo con todos los detalles de tu sesión','wc-icon-purple'],
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
