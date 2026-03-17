<h4 style="font-weight:700;margin-bottom:1.5rem">👤 Mi Perfil</h4>

<?php if ($ok): ?>
    <div class="wc-alert-success" style="background:#d4edda;border-left:4px solid #28a745;color:#155724;padding:1rem;border-radius:10px;margin-bottom:1.5rem">✅ <?= htmlspecialchars($ok) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="wc-alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div style="background:white;border-radius:16px;padding:2rem;box-shadow:0 2px 12px rgba(0,0,0,0.06);max-width:600px">
    <form method="POST">
        <div class="mb-3">
            <label class="wc-form-label">Nombre completo</label>
            <input type="text" name="nombre" class="wc-form-control" value="<?= htmlspecialchars($user['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="wc-form-label">Email</label>
            <input type="email" class="wc-form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled style="opacity:0.6">
        </div>

        <hr style="border-color:var(--wc-blue-soft);margin:1.5rem 0">
        <h6 style="font-weight:700;color:var(--wc-blue-dark);margin-bottom:1rem">Cambiar contraseña <small style="font-weight:400;color:var(--wc-text-soft)">(opcional)</small></h6>

        <div class="mb-3">
            <label class="wc-form-label">Contraseña actual</label>
            <input type="password" name="password_actual" class="wc-form-control">
        </div>
        <div class="mb-3">
            <label class="wc-form-label">Nueva contraseña</label>
            <input type="password" name="password_nueva" class="wc-form-control" minlength="6">
        </div>

        <button type="submit" class="btn-wc-primary">Guardar cambios</button>
    </form>
</div>
