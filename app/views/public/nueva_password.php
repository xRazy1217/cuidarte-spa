<section class="wc-section wc-bg-watercolor" style="min-height:80vh;display:flex;align-items:center">
    <div class="container" style="max-width:460px">
        <div class="text-center mb-4">
            <h1 style="font-size:1.8rem">Nueva Contraseña</h1>
            <p style="color:var(--wc-text-soft)">Elige una contraseña segura</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="wc-alert-error">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <div class="p-4" style="background:white;border-radius:20px;box-shadow:0 8px 30px rgba(74,144,217,0.12)">
            <form method="POST">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
                <div class="mb-4">
                    <label class="wc-form-label">Nueva contraseña *</label>
                    <input type="password" name="password" class="wc-form-control" minlength="6" required autofocus placeholder="Mínimo 6 caracteres">
                </div>
                <button type="submit" class="btn-wc-primary d-block w-100 text-center" style="padding:0.9rem">
                    Guardar contraseña
                </button>
            </form>
        </div>
    </div>
</section>
