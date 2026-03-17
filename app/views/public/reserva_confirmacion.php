<section class="wc-section">
    <div class="container" style="max-width: 700px; text-align: center;">

        <div style="background: var(--wc-blue-pale); padding: 3rem; border-radius: 20px; margin: 3rem 0; border: 1px solid rgba(74,144,217,0.15);">

            <div style="width: 100px; height: 100px; background: linear-gradient(135deg, var(--wc-blue), var(--wc-pink)); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 2rem; color: white; font-size: 3rem; box-shadow: 0 8px 25px rgba(74,144,217,0.3);">
                ✓
            </div>

            <h1 style="color: var(--wc-blue-dark); margin-bottom: 1rem;">¡Reserva Confirmada!</h1>

            <p style="color: var(--wc-text-soft); font-size: 1.1rem; margin-bottom: 2rem;">
                Hemos recibido tu solicitud de reserva. Te enviaremos un correo de confirmación con todos los detalles.
            </p>

            <?php if ($reserva): ?>
                <div style="background: white; padding: 2rem; border-radius: 15px; text-align: left; margin: 2rem 0; box-shadow: 0 5px 20px rgba(74,144,217,0.1);">
                    <h3 style="margin-bottom: 1.5rem; font-family: 'Pacifico', cursive; color: var(--wc-blue-dark);">Detalles de tu reserva</h3>

                    <div style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid var(--wc-blue-pale);">
                        <strong>Número de reserva:</strong>
                        <span style="color: var(--wc-pink); font-weight: 700;">#<?= str_pad($reserva['id'], 6, '0', STR_PAD_LEFT) ?></span>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <strong>Nombre:</strong> <?= htmlspecialchars($reserva['nombre_cliente']) ?>
                    </div>

                    <div style="margin-bottom: 1rem;">
                        <strong>Email:</strong> <?= htmlspecialchars($reserva['email_cliente']) ?>
                    </div>

                    <?php if ($reserva['telefono']): ?>
                    <div style="margin-bottom: 1rem;">
                        <strong>Teléfono:</strong> <?= htmlspecialchars($reserva['telefono']) ?>
                    </div>
                    <?php endif; ?>

                    <div>
                        <strong>Estado:</strong>
                        <span class="wc-badge wc-badge-blue"><?= ucfirst($reserva['estado']) ?></span>
                    </div>
                </div>
            <?php endif; ?>

            <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="<?= BASE_URL ?>/" class="btn-wc-primary">Volver al Inicio</a>
                <a href="<?= BASE_URL ?>/servicios" class="btn-wc-outline">Ver Servicios</a>
            </div>
        </div>

        <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: 0 5px 20px rgba(74,144,217,0.1); border: 1px solid rgba(74,144,217,0.1);">
            <h3 style="margin-bottom: 1rem; font-family: 'Pacifico', cursive; color: var(--wc-blue-dark);">¿Necesitas ayuda?</h3>
            <p style="color: var(--wc-text-soft); margin-bottom: 1.5rem;">
                Si tienes alguna pregunta sobre tu reserva, no dudes en contactarnos
            </p>
            <a href="<?= BASE_URL ?>/contacto" class="btn-wc-outline">Contactar</a>
        </div>

    </div>
</section>
