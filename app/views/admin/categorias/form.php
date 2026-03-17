<div class="page-header">
    <h1><?= $categoria ? '✏️ Editar Categoría' : '+ Nueva Categoría' ?></h1>
    <a href="<?= BASE_URL ?>/admin/categorias" class="btn btn-secondary">← Volver</a>
</div>

<div class="admin-card" style="max-width:500px">
    <div class="admin-card-body">
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Nombre *</label>
                <input type="text" name="nombre" class="form-control" required
                       value="<?= htmlspecialchars($categoria['nombre'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"><?= htmlspecialchars($categoria['descripcion'] ?? '') ?></textarea>
            </div>
            <?php if ($categoria): ?>
            <div class="form-group form-check">
                <input type="checkbox" name="activo" id="activo" value="1"
                       <?= ($categoria['activo'] ?? 1) ? 'checked' : '' ?>>
                <label for="activo" class="form-label" style="margin:0">Categoría activa</label>
            </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:1rem">
                💾 Guardar
            </button>
        </form>
    </div>
</div>
