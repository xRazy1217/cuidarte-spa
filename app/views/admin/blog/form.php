<?php if (!empty($ok)): ?>
<div class="alert alert-success">✅ Artículo guardado correctamente.</div>
<?php endif; ?>

<div class="page-header">
    <h1><?= $articulo ? '✏️ Editar Artículo' : '+ Nuevo Artículo' ?></h1>
    <a href="<?= BASE_URL ?>/admin/blog" class="btn btn-secondary">← Volver</a>
</div>

<form method="POST" enctype="multipart/form-data">
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start">

        <div>
            <div class="admin-card">
                <div class="admin-card-header"><h2>Contenido</h2></div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Título *</label>
                        <input type="text" name="titulo" class="form-control" required
                               value="<?= htmlspecialchars($articulo['titulo'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Resumen</label>
                        <textarea name="resumen" class="form-control" rows="3"><?= htmlspecialchars($articulo['resumen'] ?? '') ?></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Contenido *</label>
                        <textarea name="contenido" class="form-control" rows="12"><?= htmlspecialchars($articulo['contenido'] ?? '') ?></textarea>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header"><h2>SEO</h2></div>
                <div class="admin-card-body form-grid">
                    <div class="form-group">
                        <label class="form-label">Meta título</label>
                        <input type="text" name="meta_title" class="form-control"
                               value="<?= htmlspecialchars($articulo['meta_title'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta descripción</label>
                        <input type="text" name="meta_desc" class="form-control"
                               value="<?= htmlspecialchars($articulo['meta_desc'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="admin-card">
                <div class="admin-card-header"><h2>Imagen</h2></div>
                <div class="admin-card-body" style="text-align:center">
                    <img id="blogImgPreview"
                         src="<?= !empty($articulo['imagen']) ? BASE_URL . $articulo['imagen'] : BASE_URL . '/public/images/placeholder.svg' ?>"
                         class="img-preview" style="width:100%;height:160px;object-fit:cover;margin-bottom:1rem">
                    <input type="file" name="imagen" class="form-control" accept="image/*" data-preview="blogImgPreview">
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header"><h2>Opciones</h2></div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label">Categoría</label>
                        <input type="text" name="categoria" class="form-control"
                               placeholder="Ej: Aromaterapia"
                               value="<?= htmlspecialchars($articulo['categoria'] ?? '') ?>">
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="publicado" id="publicado" value="1"
                               <?= ($articulo['publicado'] ?? 0) ? 'checked' : '' ?>>
                        <label for="publicado" class="form-label" style="margin:0">Publicar artículo</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.9rem">
                💾 Guardar artículo
            </button>
        </div>

    </div>
</form>
