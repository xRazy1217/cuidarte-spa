<section class="wc-section wc-bg-watercolor" style="min-height:80vh;display:flex;align-items:center">
    <div class="container" style="max-width:460px">
        <div class="text-center mb-4">
            <h1 style="font-size:2rem">Mi Cuenta</h1>
            <p style="color:var(--wc-text-soft)">Ingresa para ver tus pedidos y reservas 🦋</p>
        </div>

        <?php if (!empty($error)): ?>
        <div class="wc-alert-error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($_GET['activada'])): ?>
        <div class="wc-alert-success">✅ ¡Cuenta activada! Ya puedes ingresar.</div>
        <?php endif; ?>

        <div class="p-4" style="background:white;border-radius:20px;box-shadow:0 8px 30px rgba(74,144,217,0.12)">
            <form method="POST" action="<?= BASE_URL ?>/mi-cuenta/ingresar<?= isset($_GET['next']) ? '?next='.htmlspecialchars($_GET['next']) : '' ?>">
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
            <div class="text-center mt-3" style="color:var(--wc-text-soft);font-size:0.9rem">
                ¿Compraste como invitada? Revisa tu email para activar tu cuenta.
            </div>
            <div class="text-center mt-2" style="font-size:0.9rem">
                <a href="<?= BASE_URL ?>/mi-cuenta/recuperar" style="color:var(--wc-blue)">¿Olvidaste tu contraseña?</a>
            </div>
            <hr style="margin:1.25rem 0;border-color:rgba(0,0,0,0.08)">
            <div class="text-center" style="font-size:0.9rem;color:var(--wc-text-soft)">
                ¿No tienes cuenta?
                <a href="<?= BASE_URL ?>/mi-cuenta/registrarse" style="color:var(--wc-blue-dark);font-weight:700">Regístrate aquí</a>
            </div>
        </div>

        <div class="text-center mt-3" style="font-size:0.9rem">
            <a href="<?= BASE_URL ?>/" style="color:var(--wc-text-soft)">← Volver a la tienda</a>
        </div>
    </div>
</section>
