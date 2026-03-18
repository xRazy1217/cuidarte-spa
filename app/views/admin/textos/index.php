<?php $ok = isset($_GET['ok']); $restablecido = isset($_GET['restablecido']); ?>

<div class="page-header">
    <h1>✨ Textos Mágicos</h1>
    <form method="POST" action="<?= BASE_URL ?>/admin/textos/restablecer" onsubmit="return confirm('¿Restablecer todos los textos a sus valores originales? Esta acción no se puede deshacer.')">
        <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
        <button type="submit" class="btn btn-danger">↩ Restablecer por defecto</button>
    </form>
</div>

<?php if ($ok): ?>
<div class="alert alert-success" style="margin-bottom:1rem">✅ Cambios guardados correctamente.</div>
<?php elseif ($restablecido): ?>
<div class="alert alert-success" style="margin-bottom:1rem">✅ Textos restablecidos a sus valores originales.</div>
<?php endif; ?>

<form method="POST" action="<?= BASE_URL ?>/admin/textos/guardar">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

    <!-- TEXTOS HERO HOME -->
    <div class="admin-card" style="margin-bottom:1.5rem">
        <div class="admin-card-header"><h2>🏠 Página de Inicio — Hero</h2></div>
        <div class="admin-card-body" style="display:grid;gap:1rem">
            <div class="form-group">
                <label>Texto principal del hero</label>
                <textarea name="hero_texto" class="form-control" rows="2"><?= htmlspecialchars($textos['hero_texto'] ?? '') ?></textarea>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                <div class="form-group">
                    <label>Botón 1</label>
                    <input type="text" name="hero_btn1" class="form-control" value="<?= htmlspecialchars($textos['hero_btn1'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Botón 2</label>
                    <input type="text" name="hero_btn2" class="form-control" value="<?= htmlspecialchars($textos['hero_btn2'] ?? '') ?>">
                </div>
            </div>
        </div>
    </div>

    <!-- SECCIÓN AROMATERAPIA HOME -->
    <div class="admin-card" style="margin-bottom:1.5rem">
        <div class="admin-card-header"><h2>🌿 Página de Inicio — Sección Aromaterapia</h2></div>
        <div class="admin-card-body" style="display:grid;gap:1rem">
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="home_aromaterapia_titulo" class="form-control" value="<?= htmlspecialchars($textos['home_aromaterapia_titulo'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Párrafo 1</label>
                <textarea name="home_aromaterapia_p1" class="form-control" rows="3"><?= htmlspecialchars($textos['home_aromaterapia_p1'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Párrafo 2</label>
                <textarea name="home_aromaterapia_p2" class="form-control" rows="3"><?= htmlspecialchars($textos['home_aromaterapia_p2'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- CTA FINAL HOME -->
    <div class="admin-card" style="margin-bottom:1.5rem">
        <div class="admin-card-header"><h2>📣 Página de Inicio — CTA Final</h2></div>
        <div class="admin-card-body" style="display:grid;gap:1rem">
            <div class="form-group">
                <label>Título</label>
                <input type="text" name="home_cta_titulo" class="form-control" value="<?= htmlspecialchars($textos['home_cta_titulo'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Texto</label>
                <textarea name="home_cta_texto" class="form-control" rows="2"><?= htmlspecialchars($textos['home_cta_texto'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- NOSOTROS -->
    <div class="admin-card" style="margin-bottom:1.5rem">
        <div class="admin-card-header"><h2>💜 Página Nosotros</h2></div>
        <div class="admin-card-body" style="display:grid;gap:1rem">
            <div class="form-group">
                <label>Título banner</label>
                <input type="text" name="nosotros_banner_titulo" class="form-control" value="<?= htmlspecialchars($textos['nosotros_banner_titulo'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Subtítulo banner</label>
                <input type="text" name="nosotros_banner_subtitulo" class="form-control" value="<?= htmlspecialchars($textos['nosotros_banner_subtitulo'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Filosofía párrafo 1</label>
                <textarea name="nosotros_filosofia_p1" class="form-control" rows="3"><?= htmlspecialchars($textos['nosotros_filosofia_p1'] ?? '') ?></textarea>
            </div>
            <div class="form-group">
                <label>Filosofía párrafo 2</label>
                <textarea name="nosotros_filosofia_p2" class="form-control" rows="3"><?= htmlspecialchars($textos['nosotros_filosofia_p2'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- FOOTER Y REDES -->
    <div class="admin-card" style="margin-bottom:1.5rem">
        <div class="admin-card-header"><h2>🔗 Footer y Redes Sociales</h2></div>
        <div class="admin-card-body" style="display:grid;gap:1rem">
            <div class="form-group">
                <label>Texto footer</label>
                <input type="text" name="footer_texto" class="form-control" value="<?= htmlspecialchars($textos['footer_texto'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>URL Instagram</label>
                <input type="text" name="instagram_url" class="form-control" value="<?= htmlspecialchars($textos['instagram_url'] ?? '') ?>">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:1rem;font-size:1rem">
        💾 Guardar todos los cambios
    </button>
</form>

<!-- IMÁGENES BANNERS -->
<div class="admin-card" style="margin-top:1.5rem">
    <div class="admin-card-header"><h2>🖼️ Imágenes de Banners</h2></div>
    <div class="admin-card-body" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1.5rem">

        <?php
        $banners = [
            'banner_hero_1'  => 'Banner Hero 1',
            'banner_hero_2'  => 'Banner Hero 2',
            'banner_hero_3'  => 'Banner Hero 3',
            'banner_nosotros'=> 'Banner Nosotros',
        ];
        foreach ($banners as $clave => $label):
            $img = $textos[$clave] ?? '';
        ?>
        <div style="text-align:center">
            <p style="font-weight:600;margin-bottom:0.5rem"><?= $label ?></p>
            <img src="<?= BASE_URL ?>/public/images/banner/<?= htmlspecialchars($img) ?>"
                 style="width:100%;height:120px;object-fit:cover;border-radius:10px;margin-bottom:0.75rem;background:#eee">
            <form method="POST" action="<?= BASE_URL ?>/admin/textos/subir-imagen" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="clave" value="<?= $clave ?>">
                <input type="file" name="imagen" accept="image/*" class="form-control" style="margin-bottom:0.5rem" required>
                <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center">Cambiar imagen</button>
            </form>
        </div>
        <?php endforeach; ?>

    </div>
</div>
