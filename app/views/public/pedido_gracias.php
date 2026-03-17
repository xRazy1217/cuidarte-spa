<section class="wc-section wc-bg-watercolor">
<div class="container" style="max-width:900px">

    <div class="text-center mb-4" style="background:white;border-radius:20px;padding:3rem;box-shadow:0 8px 30px rgba(74,144,217,0.1)">
        <div style="width:90px;height:90px;background:linear-gradient(135deg,var(--wc-blue),var(--wc-pink));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:white;font-size:2.5rem;box-shadow:0 8px 25px rgba(74,144,217,0.3)">✓</div>
        <h1 style="color:var(--wc-blue-dark);margin-bottom:0.75rem">¡Pedido Recibido!</h1>
        <p style="color:var(--wc-text-soft);font-size:1.1rem;margin-bottom:0.5rem">Gracias por tu compra. Hemos recibido tu pedido correctamente.</p>
        <p style="color:var(--wc-text-soft)">Te enviaremos un correo con los detalles de pago y seguimiento.</p>
    </div>

    <?php if ($pedido): ?>
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div style="background:white;border-radius:16px;padding:2rem;box-shadow:0 4px 20px rgba(74,144,217,0.08)">
                <h3 style="margin-bottom:1.5rem;color:var(--wc-blue-dark)">Productos</h3>
                <?php foreach ($pedido['items'] as $item): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:0.85rem 0;border-bottom:1px solid var(--wc-blue-soft)">
                    <div>
                        <div style="font-weight:700;color:var(--wc-blue-dark)"><?= htmlspecialchars($item['producto_nombre']) ?></div>
                        <small style="color:var(--wc-text-soft)"><?= htmlspecialchars($item['variante_nombre']) ?> × <?= $item['cantidad'] ?></small>
                    </div>
                    <strong style="color:var(--wc-pink)">$<?= number_format($item['precio_unitario'] * $item['cantidad'],0,',','.') ?></strong>
                </div>
                <?php endforeach; ?>
                <div style="display:flex;justify-content:space-between;margin-top:1.25rem;font-size:1.2rem;font-weight:700">
                    <span>Total</span>
                    <span style="color:var(--wc-pink)">$<?= number_format($pedido['total'],0,',','.') ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 4px 20px rgba(74,144,217,0.08);margin-bottom:1rem">
                <h5 style="color:var(--wc-blue-dark);margin-bottom:1rem">Detalles del Pedido</h5>
                <div style="font-size:0.9rem;color:var(--wc-text-soft);line-height:2">
                    <div><strong>N°</strong> <span style="color:var(--wc-blue);font-weight:700">#<?= str_pad($pedido['id'],6,'0',STR_PAD_LEFT) ?></span></div>
                    <div><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></div>
                    <div><strong>Estado:</strong> <span style="color:var(--wc-blue)"><?= ucfirst($pedido['estado']) ?></span></div>
                    <div><strong>Email:</strong> <?= htmlspecialchars($pedido['email_cliente']) ?></div>
                </div>
            </div>
            <div style="background:var(--wc-blue-pale);border-radius:16px;padding:1.5rem">
                <h6 style="color:var(--wc-blue-dark);margin-bottom:0.75rem">📋 Próximos pasos</h6>
                <ol style="color:var(--wc-text-soft);font-size:0.85rem;line-height:2;padding-left:1.25rem;margin:0">
                    <li>Recibirás los datos bancarios por correo</li>
                    <li>Realiza la transferencia</li>
                    <li>Te notificamos cuando se procese</li>
                </ol>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="<?= BASE_URL ?>/" class="btn-wc-primary">Volver al Inicio</a>
        <a href="<?= BASE_URL ?>/productos" class="btn-wc-outline">Seguir Comprando</a>
    </div>
</div>
</section>
