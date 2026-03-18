<?php $subtotal = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito)); ?>

<section class="wc-section wc-bg-watercolor">
<div class="container" style="max-width:1100px">

    <div class="wc-section-title mb-4">
        <h1>Finalizar Compra</h1>
    </div>

    <?php if (isset($_GET['error'])): ?>
    <div class="wc-alert-error">
        <?php if ($_GET['error'] === 'pago'): ?>
            ❌ Tu pago fue rechazado o cancelado. Por favor intenta nuevamente.
        <?php else: ?>
            Por favor completa todos los campos requeridos.
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- FORMULARIO -->
        <div class="col-lg-7">
            <form method="POST" action="<?= BASE_URL ?>/checkout" id="checkoutForm">

                <!-- Datos de contacto -->
                <div class="wc-checkout-card mb-3">
                    <div class="wc-checkout-card-header">
                        <span class="wc-checkout-step">1</span>
                        <h3>Datos de Contacto</h3>
                    </div>
                    <div class="wc-checkout-card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="wc-form-label">Nombre completo *</label>
                                <input type="text" name="nombre" class="wc-form-control" required
                                       value="<?= ($_SESSION['usuario']['rol'] ?? '') === 'cliente' ? htmlspecialchars($_SESSION['usuario']['nombre']) : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="wc-form-label">Email *</label>
                                <input type="email" name="email" class="wc-form-control" required
                                       value="<?= ($_SESSION['usuario']['rol'] ?? '') === 'cliente' ? htmlspecialchars($_SESSION['usuario']['email']) : '' ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="wc-form-label">Teléfono *</label>
                                <input type="tel" name="telefono" class="wc-form-control" required placeholder="+56 9 XXXX XXXX">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dirección -->
                <div class="wc-checkout-card mb-3">
                    <div class="wc-checkout-card-header">
                        <span class="wc-checkout-step">2</span>
                        <h3>Dirección de Envío</h3>
                    </div>
                    <div class="wc-checkout-card-body">
                        <label class="wc-form-label">Dirección completa *</label>
                        <textarea name="direccion" class="wc-form-control" rows="3" required
                                  placeholder="Calle, número, comuna, región"></textarea>
                    </div>
                </div>

                <!-- Pago y cupón -->
                <div class="wc-checkout-card mb-4">
                    <div class="wc-checkout-card-header">
                        <span class="wc-checkout-step">3</span>
                        <h3>Pago y Descuentos</h3>
                    </div>
                    <div class="wc-checkout-card-body">
                        <div style="background:var(--wc-blue-pale);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.25rem;border-left:4px solid var(--wc-blue)">
                            <strong style="color:var(--wc-blue-dark)">🏦 Transferencia Bancaria</strong>
                            <p style="margin:0.25rem 0 0;color:var(--wc-text-soft);font-size:0.9rem">Recibirás los datos bancarios por correo para completar tu pago.</p>
                        </div>
                        <label class="wc-form-label">¿Tienes un cupón de descuento?</label>
                        <div class="d-flex gap-2">
                            <input type="text" name="cupon" id="cuponInput" class="wc-form-control" placeholder="Código de cupón">
                            <button type="button" onclick="validarCupon()" class="btn-wc-outline" style="white-space:nowrap;padding:0.85rem 1.25rem">Aplicar</button>
                        </div>
                        <div id="cuponMessage" class="mt-2" style="font-size:0.9rem"></div>
                    </div>
                </div>

                <button type="submit" class="btn-wc-primary d-block w-100 text-center" style="padding:1rem;font-size:1.05rem">
                    ✅ Confirmar Pedido
                </button>
            </form>
        </div>

        <!-- RESUMEN -->
        <div class="col-lg-5">
            <div class="wc-cart-summary" style="position:sticky;top:100px">
                <h3 style="margin-bottom:1.25rem">🛒 Tu Pedido</h3>

                <?php foreach ($carrito as $item):
                    $itemTotal = $item['precio'] * $item['cantidad'];
                    $img = $item['imagen']
                        ? BASE_URL.'/public/uploads/products/'.htmlspecialchars($item['imagen'])
                        : BASE_URL.'/public/images/placeholder.svg';
                ?>
                <div style="display:grid;grid-template-columns:55px 1fr auto;gap:0.75rem;align-items:center;padding:0.75rem 0;border-bottom:1px solid rgba(74,144,217,0.15)">
                    <img src="<?= $img ?>" alt="" style="width:55px;height:55px;object-fit:cover;border-radius:8px;background:var(--wc-blue-pale)">
                    <div>
                        <div style="font-weight:700;font-size:0.9rem;color:var(--wc-blue-dark);line-height:1.3"><?= htmlspecialchars($item['producto_nombre']) ?></div>
                        <div style="font-size:0.8rem;color:var(--wc-text-soft)"><?= htmlspecialchars($item['variante_nombre']) ?> × <?= $item['cantidad'] ?></div>
                    </div>
                    <strong style="color:var(--wc-pink);white-space:nowrap">$<?= number_format($itemTotal,0,',','.') ?></strong>
                </div>
                <?php endforeach; ?>

                <div class="d-flex justify-content-between mt-3 mb-1" style="color:var(--wc-text-soft)">
                    <span>Subtotal</span><span id="subtotalDisplay">$<?= number_format($subtotal,0,',','.') ?></span>
                </div>
                <div class="d-flex justify-content-between mb-1" id="descuentoRow" style="display:none!important;color:#27ae60">
                    <span>Descuento</span><span id="descuentoDisplay"></span>
                </div>
                <div class="d-flex justify-content-between mb-3" style="color:var(--wc-text-soft)">
                    <span>Envío</span><span>A calcular</span>
                </div>
                <hr style="border-color:rgba(74,144,217,0.2)">
                <div class="d-flex justify-content-between" style="font-size:1.15rem;font-weight:700">
                    <span>Total</span>
                    <span id="totalDisplay" style="color:var(--wc-pink)">$<?= number_format($subtotal,0,',','.') ?></span>
                </div>

                <div style="background:var(--wc-blue-pale);border-radius:12px;padding:1rem;margin-top:1.25rem;font-size:0.85rem;color:var(--wc-text-soft);text-align:center">
                    🔒 Compra segura — Recibirás confirmación por email
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<style>
.wc-checkout-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(74,144,217,0.08);
    border: 1px solid rgba(74,144,217,0.1);
    overflow: hidden;
}
.wc-checkout-card-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(74,144,217,0.1);
    background: var(--wc-blue-pale);
}
.wc-checkout-card-header h3 { margin: 0; font-size: 1rem; color: var(--wc-blue-dark); }
.wc-checkout-step {
    width: 28px; height: 28px;
    background: linear-gradient(135deg, var(--wc-blue), var(--wc-pink));
    color: white; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 0.85rem; flex-shrink: 0;
}
.wc-checkout-card-body { padding: 1.5rem; }
</style>

<script>
const SUBTOTAL = <?= $subtotal ?>;
let descuentoActual = 0;

function validarCupon() {
    const codigo = document.getElementById('cuponInput').value.trim();
    if (!codigo) return;
    const csrf = document.querySelector('[name="csrf_token"]')?.value || '';
    fetch(`${BASE_URL}/carrito/validar-cupon`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `codigo=${encodeURIComponent(codigo)}&subtotal=${SUBTOTAL}&csrf_token=${encodeURIComponent(csrf)}`
    })
    .then(r => r.json())
    .then(data => {
        const msg = document.getElementById('cuponMessage');
        if (data.success) {
            descuentoActual = data.descuento;
            const fmt = n => Math.round(n).toLocaleString('es-CL');
            msg.innerHTML = `<span style="color:#27ae60">✓ Cupón aplicado: -$${fmt(descuentoActual)}</span>`;
            document.getElementById('descuentoRow').style.cssText = 'display:flex!important';
            document.getElementById('descuentoDisplay').textContent = `-$${fmt(descuentoActual)}`;
            document.getElementById('totalDisplay').textContent = `$${fmt(SUBTOTAL - descuentoActual)}`;
        } else {
            msg.innerHTML = `<span style="color:var(--wc-pink)">✗ ${data.error}</span>`;
        }
    })
    .catch(() => document.getElementById('cuponMessage').innerHTML = '<span style="color:var(--wc-pink)">✗ Error de conexión</span>');
}
</script>
