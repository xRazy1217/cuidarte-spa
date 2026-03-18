<div class="page-header">
    <h1>💌 Mensaje #<?= $mensaje['id'] ?></h1>
    <a href="<?= BASE_URL ?>/admin/mensajes" class="btn btn-secondary">← Volver</a>
</div>

<?php if ($mensaje): ?>
<div class="admin-card" style="max-width:700px">
    <div class="admin-card-header"><h2>Detalle del mensaje</h2></div>
    <div class="admin-card-body" style="line-height:2;font-size:0.95rem">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($mensaje['nombre']) ?></p>
        <p><strong>Email:</strong> <a href="mailto:<?= htmlspecialchars($mensaje['email']) ?>" style="color:var(--aqua)"><?= htmlspecialchars($mensaje['email']) ?></a></p>
        <p><strong>Fecha:</strong> <?= date('d/m/Y H:i', strtotime($mensaje['created_at'])) ?></p>
        <hr style="border-color:rgba(255,255,255,0.1);margin:1rem 0">
        <p><strong>Mensaje:</strong></p>
        <div style="background:var(--bg-dark);border-radius:10px;padding:1.25rem;color:var(--text-light);line-height:1.8;white-space:pre-wrap"><?= htmlspecialchars($mensaje['mensaje']) ?></div>
    </div>
    <div class="admin-card-body" style="padding-top:0;display:flex;gap:0.75rem">
        <a href="mailto:<?= htmlspecialchars($mensaje['email']) ?>" class="btn btn-primary">📧 Responder por email</a>
        <form method="POST" action="<?= BASE_URL ?>/admin/mensajes/eliminar/<?= $mensaje['id'] ?>" onsubmit="return confirm('¿Eliminar este mensaje?')">
            <button type="submit" class="btn btn-danger">Eliminar</button>
        </form>
    </div>
</div>
<?php endif; ?>
