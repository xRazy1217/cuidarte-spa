<!-- BANNER -->
<section class="wc-page-banner">
    <img src="<?= BASE_URL ?>/public/images/banner/Nosotros.png" alt="Propósito">
    <div class="wc-page-banner-content">
        <h1>Nuestro Propósito 🌸</h1>
        <p>Acompañarte en tu camino hacia el bienestar integral</p>
    </div>
</section>

<!-- QUÉ ES -->
<section class="wc-section">
    <div class="container" style="max-width:900px">
        <div class="wc-section-title">
            <h2>¿Qué es la Aromaterapia?</h2>
        </div>
        <p style="color:var(--wc-text-soft);font-size:1.1rem;line-height:1.9;margin-bottom:1.5rem">
            La aromaterapia es una terapia natural que utiliza aceites esenciales puros extraídos de plantas para apoyar el bienestar físico, emocional y mental. A través del olfato y la piel, sus aromas ayudan a equilibrar el sistema nervioso, favorecer la calma y acompañar procesos de autocuidado.
        </p>
        <p style="color:var(--wc-text-soft);font-size:1.1rem;line-height:1.9">
            Los aceites esenciales son compuestos aromáticos volátiles que se extraen de diferentes partes de las plantas: flores, hojas, raíces, cortezas y frutos. Cada aceite posee propiedades terapéuticas únicas utilizadas durante miles de años en diferentes culturas.
        </p>
    </div>
</section>

<!-- BENEFICIOS -->
<section class="wc-section wc-bg-watercolor">
    <div class="container">
        <div class="wc-section-title">
            <h2>Beneficios</h2>
        </div>
        <div class="row g-4">
            <?php
            $beneficios = [
                ['🧘','Bienestar Emocional','Reduce el estrés, la ansiedad y promueve estados de calma y equilibrio emocional.','wc-icon-pink'],
                ['💪','Salud Física','Apoya el sistema inmunológico, mejora la respiración y alivia molestias físicas.','wc-icon-blue'],
                ['🧠','Claridad Mental','Mejora la concentración, la memoria y favorece la claridad de pensamiento.','wc-icon-yellow'],
                ['😴','Descanso Profundo','Favorece el sueño reparador y ayuda a establecer rutinas de descanso saludables.','wc-icon-purple'],
            ];
            foreach ($beneficios as $b): ?>
            <div class="col-sm-6 col-lg-3">
                <div class="text-center p-4" style="background:white;border-radius:20px;box-shadow:0 5px 20px rgba(0,0,0,0.06);height:100%">
                    <div class="wc-value-icon <?= $b[3] ?>"><?= $b[0] ?></div>
                    <h4 style="font-size:1.1rem;color:var(--wc-blue-dark);margin-bottom:0.5rem"><?= $b[1] ?></h4>
                    <p style="color:var(--wc-text-soft);font-size:0.9rem;margin:0"><?= $b[2] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FILOSOFÍA -->
<section class="wc-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h2 style="font-size:2rem;margin-bottom:1.5rem">Nuestra Filosofía</h2>
                <p style="color:var(--wc-text-soft);line-height:1.9;margin-bottom:1rem">
                    En <strong>Cuidarte Spa</strong> creemos que el autocuidado es un acto de amor propio. Trabajamos con aceites esenciales <strong>dōTerra</strong> de grado terapéutico certificado, acompañando a madres y cuidadoras en su camino hacia el bienestar integral.
                </p>
                <p style="color:var(--wc-text-soft);line-height:1.9;margin-bottom:2rem">
                    Nuestras mezclas están diseñadas con intención y conocimiento, pensadas para acompañar diferentes momentos de tu vida: desde la calma en momentos de estrés, hasta el apoyo en procesos de crianza consciente.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="<?= BASE_URL ?>/productos" class="btn-wc-primary">Ver Productos</a>
                    <a href="<?= BASE_URL ?>/servicios" class="btn-wc-outline">Ver Servicios</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4" style="background:linear-gradient(135deg,var(--wc-blue-pale),var(--wc-pink-pale));border-radius:24px">
                    <?php
                    $valores = [
                        ['🌿','Naturaleza y Naturalidad','Soluciones naturales y respetuosas para el bienestar integral'],
                        ['💙','Cuidado y Amor','Mirada amorosa hacia la crianza y el bienestar familiar'],
                        ['👶','Respeto por la Infancia','Inspirados en Pedagogía Waldorf y Pikler'],
                        ['🤝','Comunidad','Redes de apoyo, escucha y contención entre mujeres'],
                        ['⚖️','Bienestar Integral','Equilibrio del cuerpo, emociones y lo mental'],
                    ];
                    foreach ($valores as $v): ?>
                    <div class="d-flex gap-3 align-items-start mb-3">
                        <span style="font-size:1.5rem"><?= $v[0] ?></span>
                        <div>
                            <strong style="color:var(--wc-blue-dark)"><?= $v[1] ?></strong>
                            <p style="color:var(--wc-text-soft);font-size:0.9rem;margin:0"><?= $v[2] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- USOS COMUNES -->
<section class="wc-section wc-bg-watercolor">
    <div class="container" style="max-width:900px">
        <div class="wc-section-title">
            <h2>Usos Comunes de la Aromaterapia Científica</h2>
            <p>Con respaldo de estudios para apoyar la salud física y emocional</p>
        </div>
        <div class="row g-3">
            <?php
            $usos = [
                ['😴','Relajación y sueño','Favorece el descanso profundo y reparador.'],
                ['🧘','Estrés y ansiedad','Alivia la tensión y promueve la calma interior.'],
                ['🌬️','Sistema respiratorio','Apoya la respiración libre y despejada.'],
                ['🧠','Concentración','Mejora el foco y la claridad mental.'],
                ['💪','Molestias musculares','Acompaña el alivio mediante aplicación tópica segura.'],
                ['✨','Bienestar general','Fortalece el equilibrio físico y emocional cotidiano.'],
            ];
            foreach ($usos as $u): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="d-flex gap-3 align-items-start p-3" style="background:white;border-radius:14px;box-shadow:0 3px 12px rgba(0,0,0,0.05);height:100%">
                    <span style="font-size:1.8rem;line-height:1"><?= $u[0] ?></span>
                    <div>
                        <strong style="color:var(--wc-blue-dark);font-size:0.95rem"><?= $u[1] ?></strong>
                        <p style="color:var(--wc-text-soft);font-size:0.85rem;margin:0"><?= $u[2] ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CÓMO ES UNA SESIÓN -->
<section class="wc-section">
    <div class="container" style="max-width:900px">
        <div class="wc-section-title">
            <h2>¿Cómo es una Sesión de Aromaterapia?</h2>
            <p>Un espacio de escucha, diagnóstico aromático y aplicación consciente</p>
        </div>
        <div class="row g-4">
            <?php
            $pasos = [
                ['1','💬','Acogida e Historial','La sesión comienza con una conversación tranquila sobre el motivo de consulta: estrés, ansiedad, sueño, cansancio, emociones o molestias físicas. Se consideran hábitos, estado emocional, estilo de vida y posibles contraindicaciones.'],
                ['2','🌸','Selección Aromática','A partir de lo conversado se seleccionan aceites esenciales adecuados. Puedes olerlos, percibir cuál te resuena más y se explica cómo actúan. En este momento se puede preparar una mezcla personalizada en roll on, bruma o inhalador.'],
                ['3','🤲','Aplicación y Cierre','Se puede realizar inhalación guiada, aplicación tópica suave, pequeño masaje aromático o ejercicio de respiración consciente. Al cerrar, se entregan recomendaciones para continuar el autocuidado en casa.'],
            ];
            foreach ($pasos as $p): ?>
            <div class="col-md-4">
                <div class="text-center p-4" style="background:linear-gradient(135deg,var(--wc-blue-pale),var(--wc-pink-pale));border-radius:20px;height:100%">
                    <div style="width:48px;height:48px;background:linear-gradient(135deg,var(--wc-blue),var(--wc-pink));border-radius:50%;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:1.2rem;margin:0 auto 0.75rem"><?= $p[0] ?></div>
                    <div style="font-size:2rem;margin-bottom:0.5rem"><?= $p[1] ?></div>
                    <h5 style="color:var(--wc-blue-dark);font-size:1rem;margin-bottom:0.75rem"><?= $p[2] ?></h5>
                    <p style="color:var(--wc-text-soft);font-size:0.88rem;line-height:1.7;margin:0"><?= $p[3] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- NUESTRA COMUNIDAD -->
<section class="wc-section">
    <div class="container">
        <div class="wc-section-title">
            <h2>Nuestra Comunidad 🤝</h2>
            <p>Momentos reales, personas reales — así vivimos Cuidarte Spa</p>
        </div>
        <div class="wc-community-grid">
            <div class="wc-community-item">
                <img src="<?= BASE_URL ?>/public/images/comunidad/foto3.jpeg" alt="Comunidad Cuidarte Spa">
            </div>
            <div class="wc-community-item">
                <img src="<?= BASE_URL ?>/public/images/comunidad/foto6.jpeg" alt="Comunidad Cuidarte Spa">
            </div>
            <div class="wc-community-item">
                <img src="<?= BASE_URL ?>/public/images/comunidad/foto7.jpeg" alt="Comunidad Cuidarte Spa">
            </div>
            <div class="wc-community-item">
                <img src="<?= BASE_URL ?>/public/images/comunidad/foto2.jpeg" alt="Comunidad Cuidarte Spa">
            </div>
            <div class="wc-community-item">
                <img src="<?= BASE_URL ?>/public/images/comunidad/foto5.jpeg" alt="Comunidad Cuidarte Spa">
            </div>
            <div class="wc-community-item">
                <img src="<?= BASE_URL ?>/public/images/comunidad/foto1.jpeg" alt="Comunidad Cuidarte Spa">
            </div>
        </div>
        <p class="text-center mt-2" style="color:var(--wc-text-soft);font-size:0.95rem">Síguenos en Instagram <a href="#" style="color:var(--wc-blue)">@cuidartespa</a> para más momentos ✨</p>
    </div>
</section>

<!-- LÍNEA DE PRODUCTOS -->
<section class="wc-section wc-bg-watercolor">
    <div class="container">
        <div class="wc-section-title">
            <h2>Nuestra Línea de Aromaterapia</h2>
            <p>Disponible en Roll On 10ml · Bruma 50ml · Inhalador Personal</p>
        </div>
        <div class="row g-2">
            <?php
            $mezclas = [
                ['🌿','Calma y Armonía','Una mezcla calmante que alivia el estrés y promueve la paz interior.','wc-badge-blue'],
                ['⚡','Energía y Vitalidad','Una fórmula revitalizante para aumentar la concentración y el ánimo.','wc-badge-yellow'],
                ['🛡️','Inmunidad Natural','Apoyo para fortalecer tus defensas naturales.','wc-badge-blue'],
                ['🌬️','Respiración Plena','Para aliviar la congestión y promover una respiración libre.','wc-badge-blue'],
                ['🌙','Serenidad Nocturna','Una mezcla relajante que favorece un descanso profundo y reparador.','wc-badge-purple'],
                ['👑','Gran Diosa','Efecto calmante durante el ciclo menstrual. Apoya el equilibrio hormonal y el bienestar en menopausia.','wc-badge-pink'],
                ['⭐','Estrella de Paz','Ayuda con irritabilidad, ira y malhumor en niños. Sobreestimulación y alteraciones del ánimo.','wc-badge-yellow'],
            ];
            foreach ($mezclas as $m): ?>
            <div class="col-sm-6 col-lg-4">
                <div class="wc-card h-100">
                    <div class="wc-card-body">
                        <div style="font-size:2rem;margin-bottom:0.5rem"><?= $m[0] ?></div>
                        <div class="wc-card-title"><?= $m[1] ?></div>
                        <p class="wc-card-text"><?= $m[2] ?></p>
                        <span class="wc-badge <?= $m[3] ?>"><?= $m[1] ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="<?= BASE_URL ?>/productos" class="btn-wc-primary">Ver todos los productos</a>
        </div>
    </div>
</section>
