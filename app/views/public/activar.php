<section class="wc-section wc-bg-watercolor" style="min-height:80vh;display:flex;align-items:center">
<div class="container" style="max-width:440px">
<div style="background:white;border-radius:20px;padding:2.5rem;box-shadow:0 10px 40px rgba(74,144,217,0.15)">

    <?php if (!empty($error)): ?>
        <div style="text-align:center">
            <div style="font-size:3rem;margin-bottom:1rem">❌</div>
            <h4 style="font-weight:700;color:var(--wc-blue-dark)">Link inválido</h4>
            <p style="color:var(--wc-text-soft)">Este link de activación no es válido o ya fue usado.</p>
            <a href="<?= BASE_URL ?>/mi-cuenta/ingresar" class="btn-wc-primary">Ir al login</a>
        </div>
    <?php else: ?>
        <div style="text-align:center;margin-bottom:2rem">
            <div style="font-size:3rem;margin-bottom:0.5rem">🦋</div>
            <h4 style="font-weight:800;color:var(--wc-blue-dark)">Hola, <?= htmlspecialchars($nombre ?? '') ?>!</h4>
            <p style="color:var(--wc-text-soft)">Crea tu contraseña para activar tu cuenta</p>
        </div>

        <?php if (!empty($error_pass)): ?>
            <div class="wc-alert-error">La contraseña debe tener al menos 6 caracteres.</div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="wc-form-label">Nueva contraseña *</label>
                <input type="password" name="password" class="wc-form-control" minlength="6" required autofocus>
            </div>
            <button type="submit" class="btn-wc-primary d-block w-100 text-center" style="padding:0.9rem">
                Activar mi cuenta
            </button>
        </form>
    <?php endif; ?>

</div>
</div>
</section>
