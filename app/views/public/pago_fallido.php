<section class="wc-section wc-bg-watercolor">
<div class="container" style="max-width:560px">
    <div style="background:white;border-radius:20px;padding:3rem;box-shadow:0 8px 30px rgba(0,0,0,0.08);text-align:center">

        <div style="width:90px;height:90px;background:linear-gradient(135deg,#e74c3c,#c0392b);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:white;font-size:2.5rem;box-shadow:0 8px 25px rgba(231,76,60,0.3)">✗</div>

        <h1 style="color:#c0392b;margin-bottom:0.75rem">Pago no completado</h1>
        <p style="color:var(--wc-text-soft);font-size:1.05rem;margin-bottom:0.5rem">
            <?php if (($tipo ?? '') === 'reserva'): ?>
                Tu reserva no pudo ser procesada porque el pago fue cancelado o rechazado.
            <?php else: ?>
                Tu pedido no pudo ser procesado porque el pago fue cancelado o rechazado.
            <?php endif; ?>
        </p>
        <p style="color:var(--wc-text-soft);font-size:0.9rem;margin-bottom:2rem">
            No se realizó ningún cargo. Puedes intentarlo nuevamente.
        </p>

        <?php if (!empty($id)): ?>
        <div style="background:#fff5f5;border:1px solid #fecaca;border-radius:12px;padding:1rem;margin-bottom:1.5rem;font-size:0.9rem;color:#c0392b">
            <?php if (($tipo ?? '') === 'reserva'): ?>
                Reserva #<?= str_pad($id, 6, '0', STR_PAD_LEFT) ?> — pendiente de pago
            <?php else: ?>
                Pedido #<?= str_pad($id, 6, '0', STR_PAD_LEFT) ?> — pendiente de pago
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div style="display:flex;flex-direction:column;gap:0.75rem">
            <?php if (($tipo ?? '') === 'reserva'): ?>
                <a href="<?= BASE_URL ?>/reservar" class="btn-wc-primary" style="justify-content:center">↩ Volver a reservar</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/checkout" class="btn-wc-primary" style="justify-content:center">↩ Volver al checkout</a>
            <?php endif; ?>
            <a href="<?= BASE_URL ?>/contacto" class="btn-wc-outline" style="justify-content:center">¿Necesitas ayuda?</a>
        </div>

    </div>
</div>
</section>
