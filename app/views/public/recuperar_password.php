<section class="wc-section wc-bg-watercolor" style="min-height:80vh;display:flex;align-items:center">
    <div class="container" style="max-width:460px">
        <div class="text-center mb-4">
            <h1 style="font-size:1.8rem">Recuperar Contraseña</h1>
            <p style="color:var(--wc-text-soft)">Te enviaremos un enlace a tu correo</p>
        </div>

        <?php if (!empty($ok)): ?>
            <div class="wc-alert-success">✅ <?= htmlspecialchars($ok) ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="wc-alert-error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (empty($ok)): ?>
        <div class="p-4" style="background:white;border-radius:20px;box-shadow:0 8px 30px rgba(74,144,217,0.12)">
            <form method="POST">
                <div class="mb-4">
                    <label class="wc-form-label">Email de tu cuenta *</label>
                    <input type="email" name="email" class="wc-form-control" required placeholder="tu@email.com" autofocus>
                </div>
                <button type="submit" class="btn-wc-primary d-block w-100 text-center" style="padding:0.9rem">
                    Enviar enlace
                </button>
            </form>
        </div>
        <?php endif; ?>

        <div class="text-center mt-3" style="font-size:0.9rem">
            <a href="<?= BASE_URL ?>/mi-cuenta/ingresar" style="color:var(--wc-text-soft)">← Volver al login</a>
        </div>
    </div>
</section>
