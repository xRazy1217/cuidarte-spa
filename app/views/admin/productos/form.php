<?php if (!empty($ok)): ?>
<div class="wc-alert-success">✅ Producto guardado correctamente.</div>
<?php endif; ?>

<div class="page-header">
    <h1><?= $producto ? '✏️ Editar Producto' : '+ Nuevo Producto' ?></h1>
    <a href="<?= BASE_URL ?>/admin/productos" class="btn btn-secondary">← Volver</a>
</div>

<form method="POST" enctype="multipart/form-data">

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem;align-items:start">

        <div>
            <div class="admin-card">
                <div class="admin-card-header"><h2>Información general</h2></div>
                <div class="admin-card-body">
                    <div class="form-grid">
                        <div class="form-group form-full">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="wc-form-control" required
                                   value="<?= htmlspecialchars($producto['nombre'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Categoría</label>
                            <select name="categoria_id" class="wc-form-control">
                                <option value="">Sin categoría</option>
                                <?php foreach ($categorias as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= ($producto['categoria_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Descripción corta</label>
                            <input type="text" name="descripcion_corta" class="wc-form-control"
                                   value="<?= htmlspecialchars($producto['descripcion_corta'] ?? '') ?>">
                        </div>
                        <div class="form-group form-full">
                            <label class="form-label">Descripción completa</label>
                            <textarea name="descripcion" class="wc-form-control" rows="5"><?= htmlspecialchars($producto['descripcion'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header">
                    <h2>Variantes y precios</h2>
                    <button type="button" id="addVariante" class="btn btn-secondary btn-sm">+ Agregar variante</button>
                </div>
                <div class="admin-card-body">
                    <div style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:0.5rem;margin-bottom:0.5rem;font-size:0.8rem;font-weight:600;color:var(--text-light)">
                        <span>Nombre</span><span>Precio ($)</span><span>Stock</span><span></span>
                    </div>
                    <div id="variantesContainer">
                        <?php if (!empty($variantes)): foreach ($variantes as $v): ?>
                        <div class="variante-row">
                            <div class="form-group" style="margin:0">
                                <input type="text" name="variante_nombre[]" class="wc-form-control"
                                       value="<?= htmlspecialchars($v['nombre']) ?>" required>
                            </div>
                            <div class="form-group" style="margin:0">
                                <input type="number" name="variante_precio[]" class="wc-form-control"
                                       value="<?= $v['precio'] ?>" required>
                            </div>
                            <div class="form-group" style="margin:0">
                                <input type="number" name="variante_stock[]" class="wc-form-control"
                                       value="<?= $v['stock'] ?>">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-variante">✕</button>
                        </div>
                        <?php endforeach; else: ?>
                        <div class="variante-row">
                            <div class="form-group" style="margin:0">
                                <input type="text" name="variante_nombre[]" class="wc-form-control" placeholder="Ej: Roll On 10ml" required>
                            </div>
                            <div class="form-group" style="margin:0">
                                <input type="number" name="variante_precio[]" class="wc-form-control" placeholder="10000" required>
                            </div>
                            <div class="form-group" style="margin:0">
                                <input type="number" name="variante_stock[]" class="wc-form-control" placeholder="0" value="0">
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-variante">✕</button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card-header"><h2>SEO</h2></div>
                <div class="admin-card-body form-grid">
                    <div class="form-group">
                        <label class="form-label">Meta título</label>
                        <input type="text" name="meta_title" class="wc-form-control"
                               value="<?= htmlspecialchars($producto['meta_title'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Meta descripción</label>
                        <input type="text" name="meta_desc" class="wc-form-control"
                               value="<?= htmlspecialchars($producto['meta_desc'] ?? '') ?>">
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="admin-card">
                <div class="admin-card-header"><h2>Imagen principal</h2></div>
                <div class="admin-card-body" style="text-align:center">
                    <img id="mainImgPreview"
                         src="<?= !empty($producto['imagen_principal']) ? BASE_URL . $producto['imagen_principal'] : BASE_URL . '/public/images/placeholder.svg' ?>"
                         style="width:100%;height:180px;object-fit:cover;border-radius:10px;margin-bottom:1rem">
                    <input type="file" name="imagen_principal" class="wc-form-control"
                           accept="image/*" data-preview="mainImgPreview">
                </div>
            </div>

            <?php if ($producto): ?>
            <div class="admin-card">
                <div class="admin-card-header"><h2>Imágenes adicionales</h2></div>
                <div class="admin-card-body">
                    <?php if (!empty($imagenes)): ?>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.5rem;margin-bottom:1rem">
                        <?php foreach ($imagenes as $img): ?>
                        <img src="<?= BASE_URL . $img['ruta'] ?>" style="width:100%;height:80px;object-fit:cover;border-radius:8px">
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    <input type="file" name="imagenes[]" class="wc-form-control" accept="image/*" multiple>
                    <small style="color:var(--text-light)">Puedes subir varias a la vez</small>
                </div>
            </div>
            <?php endif; ?>

            <div class="admin-card">
                <div class="admin-card-header"><h2>Opciones</h2></div>
                <div class="admin-card-body">
                    <div class="form-group form-check">
                        <input type="checkbox" name="activo" id="activo" value="1"
                               <?= ($producto['activo'] ?? 1) ? 'checked' : '' ?>>
                        <label for="activo" class="form-label" style="margin:0">Producto activo</label>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" name="destacado" id="destacado" value="1"
                               <?= ($producto['destacado'] ?? 0) ? 'checked' : '' ?>>
                        <label for="destacado" class="form-label" style="margin:0">Destacar en inicio</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:0.9rem">
                💾 Guardar producto
            </button>
        </div>

    </div>
</form>
