<section class="wc-section wc-bg-watercolor" style="min-height:80vh;display:flex;align-items:center">
    <div class="container" style="max-width:460px">
        <div class="text-center mb-4">
            <h1 style="font-size:2rem">Iniciar Sesión</h1>
            <p style="color:var(--wc-text-soft)">Accede a tu cuenta de Cuidarte Spa 🦋</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="wc-alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['registro'])): ?>
        <div class="wc-alert-success">✅ Registro exitoso. Ahora puedes iniciar sesión.</div>
        <?php endif; ?>

        <div class="p-4" style="background:white;border-radius:20px;box-shadow:0 8px 30px rgba(74,144,217,0.12)">
            <form method="POST" action="<?= BASE_URL ?>/login">
                <div class="mb-3">
                    <label class="wc-form-label">Email</label>
                    <input type="email" name="email" class="wc-form-control" required
                           placeholder="tu@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label class="wc-form-label">Contraseña</label>
                    <input type="password" name="password" class="wc-form-control" required placeholder="••••••••">
                </div>
                <button type="submit" class="btn-wc-primary w-100 text-center d-block" style="padding:0.9rem">
                    Ingresar
                </button>
            </form>
            <div class="text-center mt-3" style="color:var(--wc-text-soft);font-size:0.95rem">
                ¿No tienes cuenta?
                <a href="<?= BASE_URL ?>/registro" style="color:var(--wc-blue);font-weight:700">Regístrate</a>
            </div>
        </div>
    </div>
</section>
