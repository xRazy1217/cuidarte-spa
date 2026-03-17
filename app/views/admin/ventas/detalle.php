<?php
$estados = ['pendiente','pagado','enviado','entregado','cancelado','reembolsado'];
$badges  = ['pendiente'=>'badge-warning','pagado'=>'badge-success','enviado'=>'badge-info','entregado'=>'badge-success','cancelado'=>'badge-danger'];
?>

<div class="page-header">
    <h1>Pedido #<?= $pedido['id'] ?></h1>
    <a href="<?= BASE_URL ?>/admin/ventas" class="btn btn-secondary">← Volver</a>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start">

    <div>
        <div class="admin-card">
            <div class="admin-card-header"><h2>Productos del pedido</h2></div>
            <div style="overflow-x:auto">
                <table class="admin-table">
                    <thead><tr><th>Producto</th><th>Variante</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    <?php foreach ($pedido['detalles'] ?? [] as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['producto_nombre'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($d['variante_nombre'] ?? '-') ?></td>
                        <td><?= $d['cantidad'] ?></td>
                        <td>$<?= number_format($d['precio_unitario'], 0, ',', '.') ?></td>
                        <td><strong>$<?= number_format($d['subtotal'], 0, ',', '.') ?></strong></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="admin-card-body" style="text-align:right">
                <?php if ($pedido['descuento'] ?? 0): ?>
                <p style="color:var(--text-light)">Descuento: -$<?= number_format($pedido['descuento'], 0, ',', '.') ?></p>
                <?php endif; ?>
                <p style="font-size:1.4rem;font-weight:700;color:var(--aqua)">
                    Total: $<?= number_format($pedido['total'], 0, ',', '.') ?>
                </p>
            </div>
        </div>
    </div>

    <div>
        <div class="admin-card">
            <div class="admin-card-header"><h2>Cliente</h2></div>
            <div class="admin-card-body">
                <p><strong>Nombre:</strong> <?= htmlspecialchars($pedido['nombre_cliente'] ?? '-') ?></p>
                <p style="margin-top:0.5rem"><strong>Email:</strong> <?= htmlspecialchars($pedido['email'] ?? '-') ?></p>
                <p style="margin-top:0.5rem"><strong>Teléfono:</strong> <?= htmlspecialchars($pedido['telefono'] ?? '-') ?></p>
                <p style="margin-top:0.5rem"><strong>Dirección:</strong> <?= htmlspecialchars($pedido['direccion'] ?? '-') ?></p>
                <p style="margin-top:0.5rem"><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></p>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header"><h2>Estado del pedido</h2></div>
            <div class="admin-card-body">
                <p style="margin-bottom:1rem">
                    Estado actual: <span class="badge <?= $badges[$pedido['estado']] ?? 'badge-secondary' ?>"><?= $pedido['estado'] ?></span>
                </p>
                <form method="POST" action="<?= BASE_URL ?>/admin/ventas/estado/<?= $pedido['id'] ?>">
                    <div class="form-group">
                        <select name="estado" class="form-control">
                            <?php foreach ($estados as $e): ?>
                            <option value="<?= $e ?>" <?= $pedido['estado'] === $e ? 'selected' : '' ?>><?= ucfirst($e) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                        Actualizar estado
                    </button>
                </form>
            </div>
        </div>

        <?php if ($pedido['solicitud_reembolso']): ?>
        <div class="admin-card" style="border:2px solid #f39c12">
            <div class="admin-card-header" style="background:#fff8e1"><h2>⚠️ Solicitud de Reembolso</h2></div>
            <div class="admin-card-body">
                <p style="margin-bottom:1rem;color:#666"><strong>Motivo:</strong><br><?= nl2br(htmlspecialchars($pedido['motivo_reembolso'] ?? '-')) ?></p>
                <?php if ($pedido['estado'] === 'pagado' && $pedido['flow_token']): ?>
                <button onclick="confirmarReembolso()" class="btn btn-danger" style="width:100%;justify-content:center">
                    💸 Procesar Reembolso por Flow
                </button>
                <?php else: ?>
                <p style="color:#999;font-size:0.85rem">Este pedido no puede reembolsarse por Flow (sin token o ya procesado).</p>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?></div>

<!-- Modal confirmación reembolso -->
<div id="modalReembolso" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.6);z-index:9999;align-items:center;justify-content:center">
    <div style="background:white;border-radius:12px;padding:2rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,0.3)">
        <h4 style="margin-bottom:0.75rem">⚠️ Confirmar Reembolso</h4>
        <p style="color:#666;margin-bottom:0.5rem">Estás a punto de reembolsar:</p>
        <p style="font-size:1.3rem;font-weight:700;margin-bottom:1.25rem">$<?= number_format($pedido['total'],0,',','.') ?> CLP</p>
        <p style="color:#e74c3c;font-size:0.9rem;margin-bottom:1.5rem">Esta acción no se puede deshacer. ¿Estás seguro?</p>
        <div style="display:flex;gap:0.75rem">
            <form method="POST" action="<?= BASE_URL ?>/admin/ventas/reembolso/<?= $pedido['id'] ?>" style="flex:1">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <button type="submit" class="btn btn-danger" style="width:100%;justify-content:center">Sí, reembolsar</button>
            </form>
            <button onclick="document.getElementById('modalReembolso').style.display='none'" class="btn btn-secondary" style="flex:1;justify-content:center">Cancelar</button>
        </div>
    </div>
</div>

<script>
function confirmarReembolso() {
    document.getElementById('modalReembolso').style.display = 'flex';
}
</script>
