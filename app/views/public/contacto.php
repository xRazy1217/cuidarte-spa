<!-- BANNER -->
<section class="wc-page-banner">
    <img src="<?= BASE_URL ?>/public/images/banner/Nosotros.png" alt="Contacto">
    <div class="wc-page-banner-content">
        <h1>Contáctanos 💌</h1>
        <p>Estamos aquí para acompañarte</p>
    </div>
</section>

<section class="wc-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-7">
                <h2 style="font-size:1.8rem;margin-bottom:1.5rem">Envíanos un mensaje</h2>

                <?php if (!empty($enviado)): ?>
                <div class="wc-alert-success">✅ ¡Mensaje enviado! Te responderemos pronto.</div>
                <?php endif; ?>
                <?php if (!empty($error)): ?>
                <div class="wc-alert-error">❌ <?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/contacto">
                    <div class="mb-3">
                        <label class="wc-form-label">Nombre *</label>
                        <input type="text" name="nombre" class="wc-form-control" required placeholder="Tu nombre">
                    </div>
                    <div class="mb-3">
                        <label class="wc-form-label">Email *</label>
                        <input type="email" name="email" class="wc-form-control" required placeholder="tu@email.com">
                    </div>
                    <div class="mb-4">
                        <label class="wc-form-label">Mensaje *</label>
                        <textarea name="mensaje" class="wc-form-control" rows="6" required placeholder="¿En qué podemos ayudarte?"></textarea>
                    </div>
                    <button type="submit" class="btn-wc-primary w-100 text-center d-block" style="padding:1rem">
                        Enviar Mensaje 💌
                    </button>
                </form>
            </div>

            <div class="col-lg-5">
                <div class="p-4 mb-4" style="background:white;border-radius:20px;box-shadow:0 5px 25px rgba(0,0,0,0.07)">
                    <h3 style="font-size:1.3rem;color:var(--wc-blue-dark);margin-bottom:1.5rem">Cuidarte Spa 🦋</h3>
                    <div class="mb-3">
                        <strong style="color:var(--wc-blue)">📧 Email</strong><br>
                        <a href="mailto:<?= config('email_contacto') ?>" style="color:var(--wc-text-soft)">
                            <?= config('email_contacto') ?: 'contacto@cuidartespa.cl' ?>
                        </a>
                    </div>
                    <div class="mb-3">
                        <strong style="color:var(--wc-pink)">📱 WhatsApp</strong><br>
                        <a href="https://wa.me/<?= preg_replace('/\D/','',(config('telefono') ?: '+56900000000')) ?>"
                           target="_blank" style="color:var(--wc-text-soft)">
                            <?= config('telefono') ?: '+56 9 XXXX XXXX' ?>
                        </a>
                    </div>
                    <?php if (config('direccion')): ?>
                    <div class="mb-3">
                        <strong style="color:var(--wc-purple)">📍 Dirección</strong><br>
                        <span style="color:var(--wc-text-soft)"><?= nl2br(htmlspecialchars(config('direccion'))) ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="p-4 mb-4" style="background:linear-gradient(135deg,var(--wc-blue-pale),var(--wc-pink-pale));border-radius:20px">
                    <h4 style="font-size:1.1rem;color:var(--wc-blue-dark);margin-bottom:1rem">Redes Sociales</h4>
                    <div class="d-flex gap-2">
                        <a href="https://instagram.com/<?= config('instagram') ?>" target="_blank" class="btn-wc-pink" style="flex:1;text-align:center">📸 Instagram</a>
                        <a href="https://facebook.com/<?= config('facebook') ?>" target="_blank" class="btn-wc-primary" style="flex:1;text-align:center">Facebook</a>
                    </div>
                </div>

                <div class="p-4" style="background:white;border-radius:20px;box-shadow:0 5px 25px rgba(0,0,0,0.07)">
                    <h4 style="font-size:1.1rem;color:var(--wc-blue-dark);margin-bottom:1rem">Horario de Atención</h4>
                    <p style="color:var(--wc-text-soft);line-height:2;margin:0">
                        <strong>Lunes a Viernes:</strong> 9:00 - 18:00<br>
                        <strong>Sábado:</strong> 10:00 - 14:00<br>
                        <strong>Domingo:</strong> Cerrado
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
