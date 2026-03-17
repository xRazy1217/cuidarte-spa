<h4 style="font-weight:700;margin-bottom:1.5rem">📅 Mis Reservas</h4>

<?php if (empty($reservas)): ?>
    <div style="background:white;border-radius:16px;padding:3rem;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,0.06)">
        <div style="font-size:3rem;margin-bottom:1rem">📅</div>
        <h5>Aún no tienes reservas</h5>
        <p style="color:var(--wc-text-soft)">Reserva una sesión y comienza tu camino al bienestar</p>
        <button class="btn-wc-primary" onclick="abrirReserva(null)">Reservar ahora</button>
    </div>
<?php else: ?>
    <?php
    $estados = ['pendiente'=>['#f39c12','Pendiente'],'confirmada'=>['#27ae60','Confirmada'],'cancelada'=>['#e74c3c','Cancelada']];
    foreach ($reservas as $r):
        $est = $estados[$r['estado']] ?? ['#999', ucfirst($r['estado'])];
    ?>
    <div style="background:white;border-radius:16px;padding:1.5rem;margin-bottom:1rem;box-shadow:0 2px 12px rgba(0,0,0,0.06)">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem">
            <div>
                <span style="font-weight:700"><?= htmlspecialchars($r['servicio_nombre']) ?></span>
                <span class="wc-badge <?= $r['modalidad']==='online' ? 'wc-badge-blue' : 'wc-badge-pink' ?>" style="margin-left:0.5rem">
                    <?= $r['modalidad'] === 'online' ? '💻 Online' : '🏠 Presencial' ?>
                </span>
            </div>
            <span style="background:<?= $est[0] ?>20;color:<?= $est[0] ?>;padding:0.3rem 0.9rem;border-radius:20px;font-size:0.85rem;font-weight:600">
                <?= $est[1] ?>
            </span>
        </div>
        <div style="margin-top:0.75rem;display:flex;gap:2rem;flex-wrap:wrap;font-size:0.9rem;color:var(--wc-text-soft)">
            <span>📆 <?= date('d/m/Y', strtotime($r['fecha'])) ?></span>
            <span>🕐 <?= substr($r['hora_inicio'],0,5) ?></span>
            <span style="font-weight:600;color:var(--wc-pink)">$<?= number_format($r['precio'] ?? $r['monto'] ?? 0, 0, ',', '.') ?></span>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>
