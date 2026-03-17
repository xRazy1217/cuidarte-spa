<section class="wc-section" style="min-height:80vh">
    <div class="container" style="max-width:680px">
        <div class="mb-4">
            <h2 style="font-size:1.8rem">✏️ Mi Perfil</h2>
            <p style="color:var(--wc-text-soft)">Hola, <?= htmlspecialchars($_SESSION['usuario']['nombre'] ?? '') ?> 👋</p>
        </div>

        <?php if ($ok): ?>
            <div class="wc-alert-success">✅ <?= htmlspecialchars($ok) ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="wc-alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="wc-card">
            <div class="wc-card-body p-4">
                <form method="POST">
                    <div class="mb-3">
                        <label class="wc-form-label">Nombre completo</label>
                        <input type="text" name="nombre" class="wc-form-control" value="<?= htmlspecialchars($user['nombre']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="wc-form-label">Email</label>
                        <input type="email" class="wc-form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled style="opacity:0.6">
                    </div>
                    <hr style="border-color:var(--wc-blue-soft);margin-bottom:1.5rem">
                    <h6 style="font-weight:700;color:var(--wc-blue-dark);margin-bottom:1rem">Cambiar contraseña <small style="font-weight:400;color:var(--wc-text-soft)">(opcional)</small></h6>
                    <div class="mb-3">
                        <label class="wc-form-label">Contraseña actual</label>
                        <input type="password" name="password_actual" class="wc-form-control">
                    </div>
                    <div class="mb-4">
                        <label class="wc-form-label">Nueva contraseña</label>
                        <input type="password" name="password_nueva" class="wc-form-control" minlength="6">
                    </div>
                    <button type="submit" class="btn-wc-primary">Guardar cambios</button>
                </form>
            </div>
        </div>
    </div>
</section>
