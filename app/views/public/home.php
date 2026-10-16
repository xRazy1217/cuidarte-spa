<!-- HERO -->
<section class="wc-hero">
    <div class="wc-hero-slider">
        <div class="wc-hero-slide active">
            <img src="<?= BASE_URL ?>/public/images/banner/<?= htmlspecialchars(config('banner_hero_1')) ?>" alt="Aromaterapia">
        </div>
        <div class="wc-hero-slide">
            <img src="<?= BASE_URL ?>/public/images/banner/<?= htmlspecialchars(config('banner_hero_2')) ?>" alt="Bienestar">
        </div>
        <div class="wc-hero-slide">
            <img src="<?= BASE_URL ?>/public/images/banner/<?= htmlspecialchars(config('banner_hero_3')) ?>" alt="Aceites Esenciales">
        </div>
        <div class="wc-hero-slide">
            <img src="<?= BASE_URL ?>/public/images/banner/<?= htmlspecialchars(config('banner_hero_4')) ?>" alt="CuidArte">
        </div>
    </div>
    <div class="wc-hero-content" style="display:none"></div>
    <div class="wc-slider-dots">
        <button class="wc-dot active"></button>
        <button class="wc-dot"></button>
        <button class="wc-dot"></button>
        <button class="wc-dot"></button>
    </div>
</section>

<!-- AUTOCUIDADO -->
<section class="wc-section wc-bg-watercolor">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 style="font-size:2.2rem;margin-bottom:1rem"><?= htmlspecialchars(config('home_aromaterapia_titulo')) ?></h2>
                <p style="color:var(--wc-text-soft);font-size:1.1rem;line-height:1.8;margin-bottom:1.5rem">
                    El autocuidado es la práctica consciente de cuidar nuestro bienestar físico, mental, emocional y social. Implica acciones y hábitos que cada persona puede realizar para enfrentar el estrés y prevenir el deterioro de su salud.
                </p>
                <p style="color:var(--wc-text-soft);font-size:1.05rem;line-height:1.8;margin-bottom:1.5rem">
                    Se trata de adoptar prácticas sostenidas en el tiempo que favorezcan la calidad de vida y la resiliencia ante situaciones difíciles.
                </p>
                <p style="color:var(--wc-blue-dark);font-size:1.05rem;font-style:italic;font-weight:600;line-height:1.8;margin-bottom:2rem">
                    "El autocuidado es un gesto de amor hacia uno mismo. Es la capacidad de detenerse, escucharse y atender las propias necesidades con respeto y presencia."
                </p>
                <a href="<?= BASE_URL ?>/nosotros" class="btn-wc-outline">Conoce más sobre nosotros</a>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <?php
                    $valores = [
                        ['flor.jpeg','Amor y Compasión','Acompañamos con mirada amorosa hacia la crianza y el bienestar familiar.','wc-icon-pink'],
                        ['mariposa.jpeg','Naturaleza','Soluciones naturales y respetuosas para el bienestar integral.','wc-icon-blue'],
                        ['mandala.jpeg','Respeto Infancia','Inspirados en Pedagogía Waldorf y Pikler para el desarrollo infantil.','wc-icon-yellow'],
                        ['mariposa_cafe.jpeg','Comunidad','Caminamos junto a otras mujeres generando redes de apoyo y contención.','wc-icon-purple'],
                    ];
                    foreach ($valores as $v): ?>
                    <div class="col-6">
                        <div class="text-center p-3" style="background:white;border-radius:16px;box-shadow:0 3px 15px rgba(0,0,0,0.06)">
                            <div class="wc-value-icon <?= $v[3] ?>" style="overflow:hidden;padding:0;display:flex;align-items:center;justify-content:center">
                                <img src="<?= BASE_URL ?>/public/images/<?= $v[0] ?>" alt="<?= $v[1] ?>" style="width:100%;height:100%;object-fit:cover;object-position:center;border-radius:50%">
                            </div>
                            <h5 style="font-size:0.95rem;color:var(--wc-blue-dark);margin-bottom:0.3rem"><?= $v[1] ?></h5>
                            <p style="font-size:0.82rem;color:var(--wc-text-soft);margin:0"><?= $v[2] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SERVICIOS -->
<section class="wc-section wc-bg-watercolor">
    <div class="container">
        <div class="wc-section-title">
            <h2>Nuestros Servicios</h2>
            <p>Sesiones terapéuticas online y presenciales</p>
        </div>
        <div class="row g-4">
            <?php foreach (array_slice($servicios ?? [], 0, 3) as $s): ?>
            <div class="col-md-6 col-lg-4">
                <div class="wc-card">
                    <div class="wc-card-img">
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
                    </div>
                    <div class="wc-card-body">
                        <div class="mb-2">
                            <span class="wc-badge wc-badge-blue">Online</span>
                            <span class="wc-badge wc-badge-pink">Presencial</span>
                        </div>
                        <h3 class="wc-card-title"><?= htmlspecialchars($s['nombre']) ?></h3>
                        <p class="wc-card-text" style="display:none"><?= htmlspecialchars(substr($s['descripcion'],0,100)) ?>...</p>
                    </div>
                    <div class="wc-card-footer">
                        <a href="<?= BASE_URL ?>/servicios" class="btn-wc-pink w-100 text-center d-block">Ver más detalles →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>/servicios" class="btn-wc-outline">Ver todos los servicios →</a>
        </div>
    </div>
</section>

<!-- PRODUCTOS DESTACADOS -->
<section class="wc-section">
    <div class="container">
        <div class="wc-section-title">
            <h2>Productos Destacados</h2>
            <p>Mezclas de aromaterapia diseñadas para tu bienestar</p>
        </div>
        <div class="row g-4">
            <?php if (!empty($destacados)): foreach ($destacados as $p): ?>
            <div class="col-md-6 col-lg-4">
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
            <?php endforeach; else: ?>
            <div class="col-12 text-center py-5" style="color:var(--wc-text-soft)">
                <p>Pronto tendremos productos disponibles</p>
            </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>/productos" class="btn-wc-outline">Ver todos los productos →</a>
        </div>
    </div>
</section>

<!-- BLOG -->
<?php if (!empty($articulos)): ?>
<section class="wc-section">
    <div class="container">
        <div class="wc-section-title">
            <h2>Blog de Bienestar</h2>
            <p>Consejos y conocimiento sobre aromaterapia y crianza consciente</p>
        </div>
        <div class="row g-4">
            <?php foreach ($articulos as $a): ?>
            <div class="col-md-4">
                <div class="wc-card">
                    <div class="wc-card-img">
                        <img src="<?= $a['imagen'] ? BASE_URL.'/public/uploads/blog/'.htmlspecialchars($a['imagen']) : BASE_URL.'/public/images/placeholder.svg' ?>"
                             alt="<?= htmlspecialchars($a['titulo']) ?>">
                    </div>
                    <div class="wc-card-body">
                        <h3 class="wc-card-title"><?= htmlspecialchars($a['titulo']) ?></h3>
                        <p class="wc-card-text"><?= htmlspecialchars($a['resumen']) ?></p>
                    </div>
                    <div class="wc-card-footer">
                        <a href="<?= BASE_URL ?>/articulo/<?= $a['slug'] ?>" class="btn-wc-outline w-100 text-center d-block">Leer más →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA FINAL -->
<section class="wc-section" style="background:linear-gradient(135deg,var(--wc-blue-dark),#2d1b4e);color:white;text-align:center;padding:60px 20px">
    <div class="container">
        <h2 style="color:white;font-size:2.8rem;margin-bottom:1rem;font-family:'Playfair Display',serif;font-weight:700"><?= htmlspecialchars(config('home_cta_titulo')) ?></h2>
        <p style="opacity:0.9;font-size:1.15rem;margin-bottom:3rem;max-width:600px;margin-left:auto;margin-right:auto;line-height:1.8"><?= htmlspecialchars(config('home_cta_texto')) ?></p>
        <div class="d-flex gap-4 justify-content-center flex-wrap">
            <a href="<?= BASE_URL ?>/reservar" class="btn-wc-primary" style="padding:14px 40px;font-size:1.05rem;border-radius:8px;transition:all 0.3s ease;box-shadow:0 4px 15px rgba(0,0,0,0.2)" onmouseover="this.style.transform='translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,0.3)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'">📅 Reservar Sesión</a>
            <a href="<?= BASE_URL ?>/productos" class="btn-wc-secondary" style="padding:14px 40px;font-size:1.05rem;border-radius:8px;transition:all 0.3s ease;box-shadow:0 4px 15px rgba(0,0,0,0.2)" onmouseover="this.style.transform='translateY(-2px);box-shadow:0 6px 20px rgba(0,0,0,0.3)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 4px 15px rgba(0,0,0,0.2)'">🌿 Ver Productos</a>
        </div>
    </div>
</section>
