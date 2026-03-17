<h4 style="font-weight:700;margin-bottom:1.5rem">📦 Mis Pedidos</h4>

<?php if (empty($pedidos)): ?>
    <div style="background:white;border-radius:16px;padding:3rem;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,0.06)">
        <div style="font-size:3rem;margin-bottom:1rem">📦</div>
        <h5>Aún no tienes pedidos</h5>
        <p style="color:var(--wc-text-soft)">Explora nuestra tienda y encuentra algo que te guste</p>
        <a href="<?= BASE_URL ?>/productos" class="btn-wc-primary">Ver productos</a>
    </div>
<?php else: ?>
    <?php
    $estados = ['pendiente'=>['#f39c12','Pendiente'],'pagado'=>['#27ae60','Pagado'],'enviado'=>['#3498db','Enviado'],'entregado'=>['#27ae60','Entregado'],'cancelado'=>['#e74c3c','Cancelado']];
    foreach ($pedidos as $p):
        $est = $estados[$p['estado']] ?? ['#999', ucfirst($p['estado'])];
    ?>
    <div style="background:white;border-radius:16px;padding:1.5rem;margin-bottom:1rem;box-shadow:0 2px 12px rgba(0,0,0,0.06)">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem">
            <div>
                <span style="font-weight:700;font-size:1rem">Pedido #<?= str_pad($p['id'],6,'0',STR_PAD_LEFT) ?></span>
                <span style="color:var(--wc-text-soft);font-size:0.85rem;margin-left:0.75rem">
                    <?= date('d/m/Y H:i', strtotime($p['created_at'])) ?>
                </span>
            </div>
            <span style="background:<?= $est[0] ?>20;color:<?= $est[0] ?>;padding:0.3rem 0.9rem;border-radius:20px;font-size:0.85rem;font-weight:600">
                <?= $est[1] ?>
            </span>
        </div>
        <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f0f4f8;display:flex;justify-content:space-between;align-items:center">
            <div style="color:var(--wc-text-soft);font-size:0.9rem">
                📍 <?= htmlspecialchars($p['direccion'] ?? 'Sin dirección') ?>
            </div>
            <div style="font-weight:700;font-size:1.1rem;color:var(--wc-pink)">
                $<?= number_format($p['total'],0,',','.') ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
