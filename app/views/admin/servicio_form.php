<div class="admin-container">
    <div class="admin-header">
        <h1><?= $servicio ? 'Editar Servicio' : 'Nuevo Servicio' ?></h1>
        <a href="<?= BASE_URL ?>/admin/servicios" class="btn btn-secondary">Volver</a>
    </div>

    <form method="POST" class="form-servicio" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre del Servicio *</label>
            <input type="text" name="nombre" id="nombre" required value="<?= htmlspecialchars($servicio['nombre'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" rows="5"><?= htmlspecialchars($servicio['descripcion'] ?? '') ?></textarea>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="duracion_minutos">Duración (minutos) *</label>
                <input type="number" name="duracion_minutos" id="duracion_minutos" required min="15" value="<?= $servicio['duracion_minutos'] ?? 60 ?>">
            </div>

            <div class="form-group">
                <label for="precio">Precio Online ($) *</label>
                <input type="number" name="precio" id="precio" required min="0" step="1000" value="<?= $servicio['precio'] ?? '' ?>">
            </div>

            <div class="form-group">
                <label for="precio_presencial">Precio Presencial ($)</label>
                <input type="number" name="precio_presencial" id="precio_presencial" min="0" step="1000" value="<?= $servicio['precio_presencial'] ?? '' ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="modalidad">Modalidad *</label>
            <select name="modalidad" id="modalidad" required>
                <option value="ambas" <?= ($servicio['modalidad'] ?? 'ambas') === 'ambas' ? 'selected' : '' ?>>Ambas (Online y Presencial)</option>
                <option value="online" <?= ($servicio['modalidad'] ?? '') === 'online' ? 'selected' : '' ?>>Solo Online</option>
                <option value="presencial" <?= ($servicio['modalidad'] ?? '') === 'presencial' ? 'selected' : '' ?>>Solo Presencial</option>
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen del Servicio</label>
            <input type="file" name="imagen" id="imagen" accept="image/*">
            <?php if ($servicio && $servicio['imagen']): ?>
            <div style="margin-top:10px">
                <p style="font-size:0.9rem;color:#666">Imagen actual:</p>
                <?php
                    if (strpos($servicio['imagen'], 'data:') === 0 || strlen($servicio['imagen']) > 500) {
                        $imgSrc = 'data:image/jpeg;base64,' . $servicio['imagen'];
                    } elseif (str_starts_with($servicio['imagen'], '/')) {
                        $imgSrc = BASE_URL . htmlspecialchars($servicio['imagen']);
                    } else {
                        $imgSrc = BASE_URL.'/public/uploads/services/'.htmlspecialchars($servicio['imagen']);
                    }
                ?>
                <img src="<?= $imgSrc ?>" alt="Imagen actual" style="width:150px;height:150px;object-fit:cover;border-radius:8px">
            </div>
            <?php endif; ?>
        </div>

        <div class="form-group checkbox">
            <input type="checkbox" name="activo" id="activo" <?= ($servicio['activo'] ?? 1) ? 'checked' : '' ?>>
            <label for="activo">Servicio Activo</label>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $servicio ? 'Guardar Cambios' : 'Crear Servicio' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/servicios" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<style>
.admin-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.form-servicio {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-family: inherit;
    font-size: 14px;
    box-sizing: border-box;
}

.form-group textarea {
    resize: vertical;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 15px;
}

.form-group.checkbox {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.form-group.checkbox input {
    width: auto;
    margin-right: 10px;
}

.form-group.checkbox label {
    margin-bottom: 0;
    display: inline;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    cursor: pointer;
    border: none;
}

.btn-primary {
    background: #4A90A4;
    color: white;
}

.btn-secondary {
    background: #e9ecef;
    color: #333;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>
