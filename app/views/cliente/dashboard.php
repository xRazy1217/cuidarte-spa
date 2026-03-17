<div class="row g-4 mb-4">
    <div class="col-sm-4">
        <div style="background:linear-gradient(135deg,var(--wc-blue),var(--wc-pink));border-radius:16px;padding:1.5rem;color:white">
            <div style="font-size:2rem;margin-bottom:0.5rem">📦</div>
            <div style="font-size:1.8rem;font-weight:800"><?= count($pedidos) ?></div>
            <div style="opacity:0.85">Pedidos recientes</div>
        </div>
    </div>
    <div class="col-sm-4">
        <div style="background:linear-gradient(135deg,var(--wc-pink),var(--wc-purple));border-radius:16px;padding:1.5rem;color:white">
            <div style="font-size:2rem;margin-bottom:0.5rem">📅</div>
            <div style="font-size:1.8rem;font-weight:800"><?= count($reservas) ?></div>
            <div style="opacity:0.85">Reservas recientes</div>
        </div>
    </div>
    <div class="col-sm-4">
        <a href="<?= BASE_URL ?>/productos" style="text-decoration:none">
            <div style="background:linear-gradient(135deg,var(--wc-yellow),var(--wc-orange,#e8834a));border-radius:16px;padding:1.5rem;color:white">
                <div style="font-size:2rem;margin-bottom:0.5rem">🌿</div>
                <div style="font-size:1.1rem;font-weight:700">Ver productos</div>
                <div style="opacity:0.85;font-size:0.9rem">Explorar tienda</div>
            </div>
        </a>
    </div>
</div>

<!-- Últimos pedidos -->
<div style="background:white;border-radius:16px;padding:1.5rem;margin-bottom:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.06)">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem">
        <h5 style="margin:0;font-weight:700">Últimos pedidos</h5>
        <a href="<?= BASE_URL ?>/mi-cuenta/pedidos" style="color:var(--wc-blue);font-size:0.9rem">Ver todos →</a>
    </div>
    <?php if (empty($pedidos)): ?>
        <p style="color:var(--wc-text-soft);text-align:center;padding:1.5rem 0">Aún no tienes pedidos</p>
    <?php else: ?>
    <table style="width:100%;border-collapse:collapse;font-size:0.9rem">
        <thead><tr style="border-bottom:2px solid #f0f4f8">
            <th style="padding:0.5rem;text-align:left;color:var(--wc-text-soft)">Pedido</th>
            <th style="padding:0.5rem;text-align:left;color:var(--wc-text-soft)">Total</th>
            <th style="padding:0.5rem;text-align:left;color:var(--wc-text-soft)">Estado</th>
        </tr></thead>
        <tbody>
        <?php foreach ($pedidos as $p): ?>
        <tr style="border-bottom:1px solid #f0f4f8">
            <td style="padding:0.75rem 0.5rem">#<?= str_pad($p['id'],6,'0',STR_PAD_LEFT) ?></td>
            <td style="padding:0.75rem 0.5rem;font-weight:600">$<?= number_format($p['total'],0,',','.') ?></td>
            <td style="padding:0.75rem 0.5rem">
                <?php
                $colors = ['pendiente'=>'#f39c12','pagado'=>'#27ae60','enviado'=>'#3498db','entregado'=>'#27ae60','cancelado'=>'#e74c3c'];
                $color  = $colors[$p['estado']] ?? '#999';
                ?>
                <span style="background:<?= $color ?>20;color:<?= $color ?>;padding:0.2rem 0.7rem;border-radius:20px;font-size:0.8rem;font-weight:600">
                    <?= ucfirst($p['estado']) ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<!-- Últimas reservas -->
<div style="background:white;border-radius:16px;padding:1.5rem;box-shadow:0 2px 12px rgba(0,0,0,0.06)">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.25rem">
        <h5 style="margin:0;font-weight:700">Últimas reservas</h5>
        <a href="<?= BASE_URL ?>/mi-cuenta/reservas" style="color:var(--wc-blue);font-size:0.9rem">Ver todas →</a>
    </div>
    <?php if (empty($reservas)): ?>
        <p style="color:var(--wc-text-soft);text-align:center;padding:1.5rem 0">Aún no tienes reservas</p>
    <?php else: ?>
    <table style="width:100%;border-collapse:collapse;font-size:0.9rem">
        <thead><tr style="border-bottom:2px solid #f0f4f8">
            <th style="padding:0.5rem;text-align:left;color:var(--wc-text-soft)">Servicio</th>
            <th style="padding:0.5rem;text-align:left;color:var(--wc-text-soft)">Fecha</th>
            <th style="padding:0.5rem;text-align:left;color:var(--wc-text-soft)">Estado</th>
        </tr></thead>
        <tbody>
        <?php foreach ($reservas as $r): ?>
        <tr style="border-bottom:1px solid #f0f4f8">
            <td style="padding:0.75rem 0.5rem"><?= htmlspecialchars($r['servicio_nombre']) ?></td>
            <td style="padding:0.75rem 0.5rem"><?= date('d/m/Y', strtotime($r['fecha'])) ?> <?= substr($r['hora_inicio'],0,5) ?></td>
            <td style="padding:0.75rem 0.5rem">
                <?php $colors = ['pendiente'=>'#f39c12','confirmada'=>'#27ae60','cancelada'=>'#e74c3c']; $color = $colors[$r['estado']] ?? '#999'; ?>
                <span style="background:<?= $color ?>20;color:<?= $color ?>;padding:0.2rem 0.7rem;border-radius:20px;font-size:0.8rem;font-weight:600">
                    <?= ucfirst($r['estado']) ?>
                </span>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
