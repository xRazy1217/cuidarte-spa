<!-- Banner -->
<div class="wc-page-banner">
    <?php $mainImg = !empty($imagenes) ? $imagenes[0]['ruta'] : $producto['imagen_principal']; ?>
    <?php if ($mainImg): ?>
        <img src="<?= BASE_URL . htmlspecialchars($mainImg) ?>" alt="<?= htmlspecialchars($producto['nombre']) ?>">
    <?php else: ?>
        <img src="<?= BASE_URL ?>/public/images/banner/productos.svg" alt="Producto">
    <?php endif; ?>
    <div class="wc-page-banner-content">
        <h1><?= htmlspecialchars($producto['nombre']) ?></h1>
        <?php if ($producto['categoria_nombre']): ?>
            <span class="wc-badge wc-badge-blue"><?= htmlspecialchars($producto['categoria_nombre']) ?></span>
        <?php endif; ?>
    </div>
</div>

<section class="wc-section">
    <div class="container">
        <div class="row g-5 align-items-start">

            <!-- Galería -->
            <div class="col-md-5">
                <div class="wc-product-main-img">
                    <?php if ($mainImg): ?>
                        <img src="<?= BASE_URL . htmlspecialchars($mainImg) ?>"
                             alt="<?= htmlspecialchars($producto['nombre']) ?>" id="mainProductImg">
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>/public/images/placeholder.svg"
                             alt="<?= htmlspecialchars($producto['nombre']) ?>">
                    <?php endif; ?>
                </div>

                <?php if (!empty($imagenes) && count($imagenes) > 1): ?>
                <div class="d-flex gap-2 mt-3 flex-wrap">
                    <?php foreach ($imagenes as $idx => $img): ?>
                        <div class="wc-thumb <?= $idx === 0 ? 'active' : '' ?>">
                            <img src="<?= BASE_URL . htmlspecialchars($img['ruta']) ?>"
                                 alt="<?= htmlspecialchars($producto['nombre']) ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Info -->
            <div class="col-md-7">
                <h2 style="color:var(--wc-blue-dark); margin-bottom:0.75rem;">
                    <?= htmlspecialchars($producto['nombre']) ?>
                </h2>

                <p style="color:var(--wc-text-soft); font-size:1.1rem; margin-bottom:1.5rem;">
                    <?= htmlspecialchars($producto['descripcion_corta']) ?>
                </p>

                <?php if (!empty($variantes)): ?>
                    <div class="wc-card-price mb-3" id="productPrice">
                        $<?= number_format($variantes[0]['precio'], 0, ',', '.') ?>
                    </div>

                    <h6 style="font-weight:700; color:var(--wc-blue-dark); margin-bottom:0.75rem;">Selecciona tu formato:</h6>
                    <div class="mb-4">
                        <?php foreach ($variantes as $idx => $v): ?>
                            <?php if ($v['activo'] && $v['stock'] > 0): ?>
                                <div class="wc-variant <?= $idx === 0 ? 'active' : '' ?>"
                                     data-id="<?= $v['id'] ?>"
                                     data-price="<?= $v['precio'] ?>">
                                    <strong><?= htmlspecialchars($v['nombre']) ?></strong>
                                    — $<?= number_format($v['precio'], 0, ',', '.') ?>
                                    <?php if ($v['stock'] < 10): ?>
                                        <span style="color:var(--wc-pink); font-size:0.85rem;"> (Quedan <?= $v['stock'] ?>)</span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>

                    <input type="hidden" id="variantId" value="<?= $variantes[0]['id'] ?>">

                    <div class="d-flex align-items-center gap-3 mb-4">
                        <label style="font-weight:700; color:var(--wc-blue-dark);">Cantidad:</label>
                        <input type="number" id="cantidad" value="1" min="1" max="10"
                               class="wc-form-control" style="max-width:90px;">
                    </div>

                    <button onclick="addToCart(document.getElementById('variantId').value, document.getElementById('cantidad').value)"
                            class="btn-wc-primary" style="width:100%; padding:1rem; font-size:1.1rem;">
                        🛒 Agregar al Carrito
                    </button>

                <?php else: ?>
                    <p style="color:var(--wc-pink); font-weight:600;">Sin stock disponible</p>
                <?php endif; ?>

                <!-- Descripción -->
                <div style="margin-top:2.5rem; padding-top:2rem; border-top:2px solid var(--wc-blue-soft);">
                    <h5 style="color:var(--wc-blue-dark); margin-bottom:1rem;">Descripción</h5>
                    <div style="line-height:1.8; color:var(--wc-text-soft);">
                        <?= nl2br(htmlspecialchars($producto['descripcion'])) ?>
                    </div>
                </div>

                <!-- Sello calidad -->
                <div style="margin-top:1.5rem; padding:1.25rem; background:var(--wc-blue-pale); border-radius:12px; border-left:4px solid var(--wc-blue);">
                    <h6 style="color:var(--wc-blue-dark); margin-bottom:0.4rem;">🌿 Aceites Esenciales dōTerra</h6>
                    <p style="color:var(--wc-text-soft); font-size:0.9rem; margin:0;">
                        Trabajamos exclusivamente con aceites esenciales de grado terapéutico certificado CPTG®, garantizando la más alta calidad y pureza.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Relacionados -->
<section class="wc-section wc-bg-watercolor">
    <div class="container">
        <div class="wc-section-title">
            <h2>También te puede interesar</h2>
        </div>
        <div class="row g-4">
            <?php
            $pm = new ProductoModel();
            $relacionados = $pm->getDestacados();
            foreach ($relacionados as $r):
                if ($r['id'] == $producto['id']) continue;
            ?>
            <div class="col-md-4">
                <div class="wc-card h-100">
                    <div class="wc-card-img">
                        <img src="<?= $r['imagen_principal']
                            ? BASE_URL . htmlspecialchars($r['imagen_principal'])
                            : BASE_URL . '/public/images/placeholder.svg' ?>"
                             alt="<?= htmlspecialchars($r['nombre']) ?>">
                    </div>
                    <div class="wc-card-body">
                        <div class="wc-card-title"><?= htmlspecialchars($r['nombre']) ?></div>
                        <p class="wc-card-text"><?= htmlspecialchars($r['descripcion_corta']) ?></p>
                        <?php if ($r['precio_desde']): ?>
                            <div class="wc-card-price">$<?= number_format($r['precio_desde'], 0, ',', '.') ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="wc-card-footer">
                        <a href="<?= BASE_URL ?>/producto/<?= $r['slug'] ?>" class="btn-wc-outline" style="width:100%; display:block; text-align:center;">
                            Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
