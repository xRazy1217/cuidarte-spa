<!-- BANNER -->
<section class="wc-page-banner">
    <img src="<?= BASE_URL ?>/public/images/banner/Nosotros.png" alt="Productos">
    <div class="wc-page-banner-content">
        <h1>Nuestros Productos 🌿</h1>
        <p>Mezclas de aromaterapia con aceites esenciales dōTerra</p>
    </div>
</section>

<!-- PRODUCTOS -->
<section class="wc-section">
    <div class="container">

        <!-- Filtro categorías -->
        <div class="text-center mb-4 d-flex flex-wrap gap-2 justify-content-center">
            <a href="<?= BASE_URL ?>/productos"
               class="<?= !$cat_activa ? 'btn-wc-primary' : 'btn-wc-outline' ?>">
                Todos
            </a>
            <?php foreach ($categorias as $cat): ?>
            <a href="<?= BASE_URL ?>/productos?cat=<?= $cat['id'] ?>"
               class="<?= $cat_activa == $cat['id'] ? 'btn-wc-primary' : 'btn-wc-outline' ?>">
                <?= htmlspecialchars($cat['nombre']) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <?php if (empty($productos)): ?>
        <div class="text-center py-5" style="color:var(--wc-text-soft)">
            <div style="font-size:4rem;margin-bottom:1rem">🌿</div>
            <h3>No hay productos disponibles</h3>
            <p>Pronto tendremos nuevos productos para ti</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($productos as $p): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="wc-card">
                    <div class="wc-card-img">
                        <img src="<?= $p['imagen_principal'] ? BASE_URL.htmlspecialchars($p['imagen_principal']) : BASE_URL.'/public/images/placeholder.svg' ?>"
                             alt="<?= htmlspecialchars($p['nombre']) ?>">
                    </div>
                    <div class="wc-card-body">
                        <h3 class="wc-card-title"><?= htmlspecialchars($p['nombre']) ?></h3>
                        <p class="wc-card-text"><?= htmlspecialchars($p['descripcion_corta']) ?></p>
                        <div class="wc-card-price">Desde $<?= number_format($p['precio_desde'],0,',','.') ?></div>
                    </div>
                    <div class="wc-card-footer">
                        <a href="<?= BASE_URL ?>/producto/<?= $p['slug'] ?>" class="btn-wc-primary w-100 text-center d-block">Ver Detalles</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- CTA -->
<section class="wc-section-sm wc-bg-watercolor">
    <div class="container text-center" style="max-width:700px">
        <h2 style="font-size:1.8rem;margin-bottom:1rem">¿Necesitas ayuda para elegir?</h2>
        <p style="color:var(--wc-text-soft);margin-bottom:2rem">Contáctanos y te ayudamos a encontrar la mezcla perfecta para tus necesidades</p>
        <a href="<?= BASE_URL ?>/contacto" class="btn-wc-primary">Contactar</a>
    </div>
</section>
