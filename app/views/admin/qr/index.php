<div class="page-header">
    <h1>📱 Centro QR</h1>
</div>

<div class="admin-card" style="margin-bottom:2rem">
    <div class="admin-card-header"><h2>QR de Productos</h2></div>
    <div class="admin-card-body">
        <?php if (empty($productos)): ?>
            <p style="color:var(--text-light)">No hay productos activos.</p>
        <?php else: ?>
        <div class="qr-grid">
            <?php foreach ($productos as $p): ?>
            <div class="qr-card">
                <canvas id="qr-p-<?= $p['id'] ?>"
                        data-qr-url="<?= BASE_URL ?>/producto/<?= $p['slug'] ?>"
                        data-qr-canvas="qr-p-<?= $p['id'] ?>"></canvas>
                <h4><?= htmlspecialchars($p['nombre']) ?></h4>
                <a href="<?= BASE_URL ?>/producto/<?= $p['slug'] ?>"
                   class="btn btn-secondary btn-sm" target="_blank">Ver producto</a>
                <button onclick="downloadQR('qr-p-<?= $p['id'] ?>','<?= htmlspecialchars($p['nombre']) ?>')"
                        class="btn btn-primary btn-sm" style="margin-top:0.5rem">⬇️ Descargar</button>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header"><h2>QR de Servicios</h2></div>
    <div class="admin-card-body">
        <?php if (empty($servicios)): ?>
            <p style="color:var(--text-light)">No hay servicios activos.</p>
        <?php else: ?>
        <div class="qr-grid">
            <?php foreach ($servicios as $s): ?>
            <div class="qr-card">
                <canvas id="qr-s-<?= $s['id'] ?>"
                        data-qr-url="<?= BASE_URL ?>/reservar?servicio=<?= $s['id'] ?>"
                        data-qr-canvas="qr-s-<?= $s['id'] ?>"></canvas>
                <h4><?= htmlspecialchars($s['nombre']) ?></h4>
                <button onclick="downloadQR('qr-s-<?= $s['id'] ?>','<?= htmlspecialchars($s['nombre']) ?>')"
                        class="btn btn-primary btn-sm">⬇️ Descargar</button>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
function downloadQR(canvasId, nombre) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return;
    const link = document.createElement('a');
    link.download = 'qr-' + nombre.toLowerCase().replace(/\s+/g, '-') + '.png';
    link.href = canvas.toDataURL();
    link.click();
}
</script>
