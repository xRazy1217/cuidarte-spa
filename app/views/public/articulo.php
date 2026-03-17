<?php
$imgUrl = !empty($articulo['imagen'])
    ? BASE_URL . htmlspecialchars($articulo['imagen'])
    : BASE_URL . '/public/images/banner/blog.svg';
?>

<!-- Banner -->
<div class="wc-page-banner">
    <img src="<?= $imgUrl ?>" alt="<?= htmlspecialchars($articulo['titulo']) ?>">
    <div class="wc-page-banner-content">
        <?php if ($articulo['categoria']): ?>
        <span class="wc-badge wc-badge-blue mb-2"><?= htmlspecialchars($articulo['categoria']) ?></span>
        <?php endif; ?>
        <h1><?= htmlspecialchars($articulo['titulo']) ?></h1>
        <p style="opacity:0.85">📅 <?= date('d/m/Y', strtotime($articulo['created_at'])) ?></p>
    </div>
</div>

<section class="wc-section">
    <div class="container" style="max-width:860px">
        <div style="background:white;border-radius:20px;padding:2.5rem;box-shadow:0 5px 25px rgba(74,144,217,0.08)">
            <div style="line-height:1.9;color:var(--wc-text);font-size:1.05rem">
                <?= nl2br(htmlspecialchars($articulo['contenido'])) ?>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="<?= BASE_URL ?>/blog" class="btn-wc-outline">← Volver al Blog</a>
        </div>
    </div>
</section>

<!-- Más artículos -->
<section class="wc-section wc-bg-watercolor">
    <div class="container">
        <div class="wc-section-title">
            <h2>Más Artículos</h2>
        </div>
        <div class="row g-4">
        <?php
        $bm = new BlogModel();
        $relacionados = $bm->getPublicados(4);
        $count = 0;
        foreach ($relacionados as $r):
            if ($r['id'] == $articulo['id']) continue;
            if ($count++ >= 3) break;
            $rImg = !empty($r['imagen'])
                ? BASE_URL . htmlspecialchars($r['imagen'])
                : BASE_URL . '/public/images/placeholder.svg';
        ?>
        <div class="col-md-4">
            <div class="wc-card">
                <div class="wc-card-img">
                    <img src="<?= $rImg ?>" alt="<?= htmlspecialchars($r['titulo']) ?>">
                </div>
                <div class="wc-card-body">
                    <h3 class="wc-card-title"><?= htmlspecialchars($r['titulo']) ?></h3>
                    <p class="wc-card-text"><?= htmlspecialchars($r['resumen']) ?></p>
                </div>
                <div class="wc-card-footer">
                    <a href="<?= BASE_URL ?>/articulo/<?= $r['slug'] ?>" class="btn-wc-outline w-100 text-center d-block">Leer artículo →</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        </div>
    </div>
</section>
