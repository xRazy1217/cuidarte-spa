<section class="wc-section">
    <div class="container">
        <div class="wc-section-title">
            <h1>🛒 Carrito de Compras</h1>
        </div>

        <?php if (isset($_GET['error']) && $_GET['error'] === 'stock'): ?>
        <div class="wc-alert-error">⚠️ Uno o más productos no tienen stock suficiente. Ajusta las cantidades.</div>
        <?php endif; ?>

        <?php if (empty($carrito)): ?>
            <div class="text-center py-5">
                <div style="font-size:4rem;margin-bottom:1rem">🛒</div>
                <h2 style="color:var(--wc-text-soft);margin-bottom:1rem">Tu carrito está vacío</h2>
                <p style="color:var(--wc-text-soft);margin-bottom:2rem">Agrega productos para comenzar tu compra</p>
                <a href="<?= BASE_URL ?>/productos" class="btn-wc-primary">Ver Productos</a>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <?php
                    $subtotal = 0;
                    foreach ($carrito as $item):
                        $itemTotal = $item['precio'] * $item['cantidad'];
                        $subtotal += $itemTotal;
                    ?>
                    <div class="wc-cart-item">
                        <div class="wc-cart-item-img">
                            <img src="<?= $item['imagen'] ? BASE_URL.htmlspecialchars($item['imagen']) : BASE_URL.'/public/images/placeholder.svg' ?>"
                                 alt="<?= htmlspecialchars($item['producto_nombre']) ?>">
                        </div>
                        <div>
                            <a href="<?= BASE_URL ?>/producto/<?= $item['slug'] ?>" style="font-weight:700;color:var(--wc-blue-dark);font-size:1.05rem">
                                <?= htmlspecialchars($item['producto_nombre']) ?>
                            </a>
                            <p style="color:var(--wc-text-soft);font-size:0.9rem;margin:0.25rem 0"><?= htmlspecialchars($item['variante_nombre']) ?></p>
                            <p style="color:var(--wc-pink);font-weight:700;font-size:1.1rem;margin:0">$<?= number_format($item['precio'],0,',','.') ?></p>
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <input type="number" value="<?= $item['cantidad'] ?>" min="1" max="10"
                                       onchange="updateCartQuantity(<?= $item['variante_id'] ?>, this.value)"
                                       class="wc-form-control" style="width:75px;padding:0.4rem 0.6rem">
                                <button onclick="removeFromCart(<?= $item['variante_id'] ?>)" class="btn-wc-outline" style="padding:0.4rem 1rem;font-size:0.85rem">Eliminar</button>
                            </div>
                        </div>
                        <div style="text-align:right;font-weight:700;font-size:1.2rem;color:var(--wc-blue-dark);white-space:nowrap">
                            $<?= number_format($itemTotal,0,',','.') ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="col-lg-4">
                    <div class="wc-cart-summary">
                        <h3 style="margin-bottom:1.5rem">Resumen del Pedido</h3>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span><strong>$<?= number_format($subtotal,0,',','.') ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3" style="color:var(--wc-text-soft)">
                            <span>Envío</span><span>A calcular</span>
                        </div>
                        <hr style="border-color:rgba(74,144,217,0.2)">
                        <div class="d-flex justify-content-between mb-4">
                            <strong style="font-size:1.1rem">Total</strong>
                            <strong style="font-size:1.3rem;color:var(--wc-pink)">$<?= number_format($subtotal,0,',','.') ?></strong>
                        </div>
                        <a href="<?= BASE_URL ?>/checkout" class="btn-wc-primary d-block text-center mb-2" style="padding:0.9rem">Proceder al Pago</a>
                        <a href="<?= BASE_URL ?>/productos" class="btn-wc-outline d-block text-center" style="padding:0.9rem">Seguir Comprando</a>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
