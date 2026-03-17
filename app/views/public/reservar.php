<!-- Banner -->
<div class="wc-page-banner">
    <img src="<?= BASE_URL ?>/public/images/banner/reservar.svg" alt="Reservar">
    <div class="wc-page-banner-content">
        <h1>Reserva tu Sesión</h1>
        <p>Elige el servicio y horario que mejor se ajuste a ti</p>
        <button class="btn-wc-primary mt-3" onclick="abrirReserva(<?= isset($_GET['servicio']) ? (int)$_GET['servicio'] : 'null' ?>)">
            📅 Reservar ahora
        </button>
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
                        <button onclick="abrirReserva(<?= $s['id'] ?>)" class="btn-wc-pink" style="width:100%">
                            📅 Reservar este servicio
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
