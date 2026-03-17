<section class="section" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container" style="max-width: 450px;">

        <div style="text-align: center; margin-bottom: 2rem;">
            <h1 style="font-size: 2rem;">Crear Cuenta</h1>
            <p style="color: var(--text-gray);">Únete a la comunidad Cuidarte Spa</p>
        </div>

        <?php if (!empty($error)): ?>
        <div style="background: #fee; border-left: 4px solid #e74c3c; padding: 1rem; margin-bottom: 1.5rem; border-radius: 8px;">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <div style="background: white; padding: 2.5rem; border-radius: 15px; box-shadow: 0 5px 20px var(--shadow);">
            <form method="POST" action="<?= BASE_URL ?>/registro">
                <div class="form-group">
                    <label class="form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="form-control" required
                           placeholder="Tu nombre"
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required
                           placeholder="tu@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Contraseña</label>
                    <input type="password" name="password" class="form-control" required
                           placeholder="Mínimo 6 caracteres">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.9rem; justify-content: center; margin-top: 0.5rem;">
                    Crear Cuenta
                </button>
            </form>

            <div style="text-align: center; margin-top: 1.5rem; color: var(--text-gray); font-size: 0.95rem;">
                ¿Ya tienes cuenta?
                <a href="<?= BASE_URL ?>/login" style="color: var(--aqua-blue); font-weight: 600;">Inicia sesión</a>
            </div>
        </div>

    </div>
</section>
