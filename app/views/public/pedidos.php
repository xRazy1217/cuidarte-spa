<section class="wc-section" style="min-height:80vh">
    <div class="container" style="max-width:800px">
        <div class="mb-4">
            <h2 style="font-size:1.8rem">📦 Mis Compras</h2>
            <p style="color:var(--wc-text-soft)">Historial de todos tus pedidos</p>
        </div>

        <?php if (empty($pedidos)): ?>
        <div class="wc-card text-center p-5">
            <div style="font-size:3.5rem;margin-bottom:1rem">📦</div>
            <h5 style="color:var(--wc-blue-dark)">Aún no tienes pedidos</h5>
            <p style="color:var(--wc-text-soft);margin-bottom:1.5rem">Explora nuestra tienda y encuentra algo que te guste</p>
            <a href="<?= BASE_URL ?>/productos" class="btn-wc-primary">Ver productos</a>
        </div>
        <?php else: ?>
        <?php
        $estados = [
            'pendiente'    => ['#f39c12', 'Pendiente'],
            'pagado'       => ['#27ae60', 'Pagado'],
            'enviado'      => ['#3498db', 'Enviado'],
            'entregado'    => ['#27ae60', 'Entregado'],
            'cancelado'    => ['#e74c3c', 'Cancelado'],
            'reembolsado'  => ['#8e44ad', 'Reembolsado'],
        ];
        foreach ($pedidos as $p):
            $est = $estados[$p['estado']] ?? ['#999', ucfirst($p['estado'])];
        ?>
        <div class="wc-card mb-3">
            <div class="wc-card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem">
                    <div>
                        <span style="font-weight:700;font-size:1rem">Pedido #<?= str_pad($p['id'],6,'0',STR_PAD_LEFT) ?></span>
                        <span style="color:var(--wc-text-soft);font-size:0.85rem;margin-left:0.75rem"><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></span>
                    </div>
                    <span style="background:<?= $est[0] ?>20;color:<?= $est[0] ?>;padding:0.3rem 0.9rem;border-radius:20px;font-size:0.85rem;font-weight:600"><?= $est[1] ?></span>
                </div>
                <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid rgba(180,160,140,0.15);display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem">
                    <span style="color:var(--wc-text-soft);font-size:0.9rem">📍 <?= htmlspecialchars($p['direccion'] ?? 'Sin dirección') ?></span>
                    <span style="font-weight:700;font-size:1.1rem;color:#1a1a1a">$<?= number_format($p['total'],0,',','.') ?></span>
                </div>
                <?php if ($p['estado'] === 'pagado' && !$p['solicitud_reembolso']): ?>
                <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid rgba(180,160,140,0.15)">
                    <button onclick="abrirReembolso(<?= $p['id'] ?>)" class="btn-wc-outline" style="font-size:0.85rem;padding:0.4rem 1rem">↩ Solicitar reembolso</button>
                </div>
                <?php elseif ($p['solicitud_reembolso']): ?>
                <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid rgba(180,160,140,0.15)">
                    <span style="font-size:0.85rem;color:#f39c12">⏳ Reembolso en revisión</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- Modal solicitud reembolso -->
<div id="modalReembolso" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center">
    <div style="background:white;border-radius:16px;padding:2rem;max-width:460px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.2)">
        <h4 style="margin-bottom:0.5rem;color:var(--wc-blue-dark)">↩ Solicitar Reembolso</h4>
        <p style="color:var(--wc-text-soft);font-size:0.9rem;margin-bottom:1.25rem">Cuéntanos el motivo de tu solicitud. El equipo la revisará y te contactará.</p>
        <form id="formReembolso" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
            <textarea name="motivo" rows="4" class="wc-form-control" placeholder="Describe el motivo del reembolso..." required style="margin-bottom:1rem"></textarea>
            <div style="display:flex;gap:0.75rem">
                <button type="submit" class="btn-wc-primary" style="flex:1;justify-content:center">Enviar solicitud</button>
                <button type="button" onclick="cerrarReembolso()" class="btn-wc-outline" style="flex:1;justify-content:center">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirReembolso(id) {
    document.getElementById('formReembolso').action = BASE_URL + '/mi-cuenta/solicitar-reembolso/' + id;
    const m = document.getElementById('modalReembolso');
    m.style.display = 'flex';
}
function cerrarReembolso() {
    document.getElementById('modalReembolso').style.display = 'none';
}
</script>
