<section class="wc-section wc-bg-watercolor" style="min-height:80vh;display:flex;align-items:center">
    <div class="container" style="max-width:460px">
        <div class="text-center mb-4">
            <h1 style="font-size:2rem">Crear Cuenta</h1>
            <p style="color:var(--wc-text-soft)">Únete a Cuidarte Spa 🌿</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="wc-alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="p-4" style="background:white;border-radius:20px;box-shadow:0 8px 30px rgba(74,144,217,0.12)">
            <form method="POST" action="<?= BASE_URL ?>/mi-cuenta/registrarse">
                <div class="mb-3">
                    <label class="wc-form-label">Nombre completo</label>
                    <input type="text" name="nombre" class="wc-form-control" required
                           placeholder="Tu nombre"
                           value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="wc-form-label">Email</label>
                    <input type="email" name="email" class="wc-form-control" required
                           placeholder="tu@email.com"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="mb-4">
                    <label class="wc-form-label">Contraseña</label>
                    <input type="password" name="password" class="wc-form-control" required placeholder="Mínimo 6 caracteres">
                </div>
                <button type="submit" class="btn-wc-primary w-100 text-center d-block" style="padding:0.9rem">
                    Crear cuenta
                </button>
            </form>
            <hr style="margin:1.25rem 0;border-color:rgba(0,0,0,0.08)">
            <div class="text-center" style="font-size:0.9rem;color:var(--wc-text-soft)">
                ¿Ya tienes cuenta?
                <a href="<?= BASE_URL ?>/mi-cuenta/ingresar" style="color:var(--wc-blue-dark);font-weight:700">Ingresa aquí</a>
            </div>
        </div>

        <div class="text-center mt-3" style="font-size:0.9rem">
            <a href="<?= BASE_URL ?>/" style="color:var(--wc-text-soft)">← Volver a la tienda</a>
        </div>
    </div>
</section>
