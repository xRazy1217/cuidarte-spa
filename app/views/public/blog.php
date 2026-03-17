<!-- BANNER -->
<section class="wc-page-banner">
    <img src="<?= BASE_URL ?>/public/images/banner/Nosotros.png" alt="Blog">
    <div class="wc-page-banner-content">
        <h1>Blog de Bienestar ✍️</h1>
        <p>Consejos y conocimiento sobre aromaterapia y crianza consciente</p>
    </div>
</section>

<section class="wc-section">
    <div class="container">
        <?php if (empty($articulos)): ?>
        <div class="text-center py-5" style="color:var(--wc-text-soft)">
            <div style="font-size:4rem;margin-bottom:1rem">✍️</div>
            <h3>Próximamente nuevos artículos</h3>
            <p>Estamos preparando contenido valioso para ti</p>
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($articulos as $a): ?>
            <div class="col-md-6 col-lg-4">
                <div class="wc-card">
                    <div class="wc-card-img">
                        <img src="<?= $a['imagen'] ? BASE_URL.'/public/uploads/blog/'.htmlspecialchars($a['imagen']) : BASE_URL.'/public/images/placeholder.svg' ?>"
                             alt="<?= htmlspecialchars($a['titulo']) ?>">
                    </div>
                    <div class="wc-card-body">
                        <?php if ($a['categoria']): ?>
                        <span class="wc-badge wc-badge-blue mb-2"><?= htmlspecialchars($a['categoria']) ?></span>
                        <?php endif; ?>
                        <h3 class="wc-card-title"><?= htmlspecialchars($a['titulo']) ?></h3>
                        <p class="wc-card-text"><?= htmlspecialchars($a['resumen']) ?></p>
                        <small style="color:var(--wc-text-soft)">📅 <?= date('d/m/Y', strtotime($a['created_at'])) ?></small>
                    </div>
                    <div class="wc-card-footer">
                        <a href="<?= BASE_URL ?>/articulo/<?= $a['slug'] ?>" class="btn-wc-outline w-100 text-center d-block">Leer artículo →</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (($total_paginas ?? 1) > 1): ?>
        <div class="d-flex justify-content-center gap-2 mt-5">
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
            <a href="<?= BASE_URL ?>/blog?pagina=<?= $i ?>"
               class="<?= $i == ($pagina_actual ?? 1) ? 'btn-wc-primary' : 'btn-wc-outline' ?>">
                <?= $i ?>
            </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>
</section>
