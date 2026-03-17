<section class="wc-section" style="min-height:80vh">
    <div class="container" style="max-width:800px">
        <div class="mb-4">
            <h2 style="font-size:1.8rem">📅 Mis Reservas</h2>
            <p style="color:var(--wc-text-soft)">Tus sesiones agendadas</p>
        </div>

        <?php if (empty($reservas)): ?>
        <div class="wc-card text-center p-5">
            <div style="font-size:3.5rem;margin-bottom:1rem">📅</div>
            <h5 style="color:var(--wc-blue-dark)">Aún no tienes reservas</h5>
            <p style="color:var(--wc-text-soft);margin-bottom:1.5rem">Reserva una sesión y comienza tu camino al bienestar</p>
            <a href="<?= BASE_URL ?>/reservar" class="btn-wc-primary">Reservar ahora</a>
        </div>
        <?php else: ?>
        <?php
        $estados = [
            'pendiente'  => ['#f39c12', 'Pendiente'],
            'confirmada' => ['#27ae60', 'Confirmada'],
            'cancelada'  => ['#e74c3c', 'Cancelada'],
        ];
        foreach ($reservas as $r):
            $est = $estados[$r['estado']] ?? ['#999', ucfirst($r['estado'])];
        ?>
        <div class="wc-card mb-3">
            <div class="wc-card-body">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem">
                    <div>
                        <span style="font-weight:700;font-size:1rem"><?= htmlspecialchars($r['servicio_nombre']) ?></span>
                        <span class="wc-badge <?= $r['modalidad']==='online' ? 'wc-badge-blue' : 'wc-badge-pink' ?>" style="margin-left:0.5rem">
                            <?= $r['modalidad'] === 'online' ? '💻 Online' : '🏠 Presencial' ?>
                        </span>
                    </div>
                    <span style="background:<?= $est[0] ?>20;color:<?= $est[0] ?>;padding:0.3rem 0.9rem;border-radius:20px;font-size:0.85rem;font-weight:600"><?= $est[1] ?></span>
                </div>
                <div style="margin-top:0.75rem;padding-top:0.75rem;border-top:1px solid rgba(180,160,140,0.15);display:flex;gap:2rem;flex-wrap:wrap;font-size:0.9rem;color:var(--wc-text-soft)">
                    <span>📆 <?= date('d/m/Y', strtotime($r['fecha'])) ?></span>
                    <span>🕐 <?= substr($r['hora_inicio'],0,5) ?></span>
                    <span style="font-weight:700;color:#1a1a1a">$<?= number_format($r['precio'] ?? $r['monto'] ?? 0, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
