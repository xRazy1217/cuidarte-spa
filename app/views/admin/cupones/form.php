<div class="page-header">
    <h1><?= $cupon ? '✏️ Editar Cupón' : '+ Nuevo Cupón' ?></h1>
    <a href="<?= BASE_URL ?>/admin/cupones" class="btn btn-secondary">← Volver</a>
</div>

<div class="admin-card" style="max-width:600px">
    <div class="admin-card-body">
        <form method="POST">
            <div class="form-group">
                <label class="form-label">Código *</label>
                <input type="text" name="codigo" class="form-control" required
                       style="text-transform:uppercase;font-family:monospace"
                       placeholder="Ej: BIENVENIDA20"
                       value="<?= htmlspecialchars($cupon['codigo'] ?? '') ?>">
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Tipo de descuento *</label>
                    <select name="tipo" class="form-control" required>
                        <option value="porcentaje" <?= ($cupon['tipo'] ?? '') === 'porcentaje' ? 'selected' : '' ?>>Porcentaje (%)</option>
                        <option value="monto" <?= ($cupon['tipo'] ?? '') === 'monto' ? 'selected' : '' ?>>Monto fijo ($)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Valor *</label>
                    <input type="number" name="valor" class="form-control" required min="1"
                           placeholder="Ej: 20"
                           value="<?= $cupon['valor'] ?? '' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Usos máximos</label>
                    <input type="number" name="uso_maximo" class="form-control" min="1"
                           value="<?= $cupon['uso_maximo'] ?? 1 ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Fecha de vencimiento</label>
                    <input type="date" name="fecha_expiracion" class="form-control"
                           value="<?= $cupon['fecha_expiracion'] ?? '' ?>">
                </div>
            </div>
            <?php if ($cupon): ?>
            <div class="form-group form-check">
                <input type="checkbox" name="activo" id="activo" value="1"
                       <?= ($cupon['activo'] ?? 1) ? 'checked' : '' ?>>
                <label for="activo" class="form-label" style="margin:0">Cupón activo</label>
            </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:1rem">
                💾 Guardar cupón
            </button>
        </form>
    </div>
</div>
