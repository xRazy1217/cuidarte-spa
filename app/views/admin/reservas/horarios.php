<?php if (!empty($ok)): ?>
<div class="alert alert-success">✅ Horarios agregados correctamente.</div>
<?php endif; ?>

<div class="page-header">
    <h1>🕐 Horarios Disponibles</h1>
    <a href="<?= BASE_URL ?>/admin/reservas" class="btn btn-secondary">← Volver</a>
</div>

<div class="admin-card" style="max-width:700px;margin-bottom:2rem">
    <div class="admin-card-header"><h2>Agregar horarios</h2></div>
    <div class="admin-card-body">

        <!-- Tipo de creación -->
        <div class="form-group">
            <label class="form-label">Tipo de creación</label>
            <div style="display:flex;gap:1rem">
                <label style="cursor:pointer">
                    <input type="radio" name="tipoCreacion" value="unico" checked onchange="toggleTipo(this.value)"> Fecha única
                </label>
                <label style="cursor:pointer">
                    <input type="radio" name="tipoCreacion" value="rango" onchange="toggleTipo(this.value)"> Rango de fechas
                </label>
            </div>
        </div>

        <form method="POST">
            <input type="hidden" name="tipo" id="tipoInput" value="unico">

            <div class="form-group">
                <label class="form-label">Servicio *</label>
                <select name="servicio_id" class="form-control" required>
                    <option value="">Seleccionar servicio</option>
                    <?php foreach ($servicios as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Fecha única -->
            <div id="bloqueUnico">
                <div class="form-group">
                    <label class="form-label">Fecha *</label>
                    <input type="date" name="fecha" class="form-control" min="<?= date('Y-m-d') ?>">
                </div>
            </div>

            <!-- Rango de fechas -->
            <div id="bloqueRango" style="display:none">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Fecha inicio *</label>
                        <input type="date" name="fecha_inicio" class="form-control" min="<?= date('Y-m-d') ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Fecha fin *</label>
                        <input type="date" name="fecha_fin" class="form-control" min="<?= date('Y-m-d') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Días de la semana</label>
                    <div style="display:flex;gap:0.75rem;flex-wrap:wrap">
                        <?php
                        $dias = ['1'=>'Lun','2'=>'Mar','3'=>'Mié','4'=>'Jue','5'=>'Vie','6'=>'Sáb','7'=>'Dom'];
                        foreach ($dias as $num => $nombre): ?>
                        <label style="cursor:pointer;background:var(--aqua-light);padding:0.4rem 0.8rem;border-radius:8px">
                            <input type="checkbox" name="dias[]" value="<?= $num ?>"> <?= $nombre ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">Hora inicio *</label>
                    <input type="time" name="hora_inicio" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Hora fin *</label>
                    <input type="time" name="hora_fin" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Cupos máximos
                    <small style="color:var(--text-light);font-weight:400">(1 = individual, más = grupal/taller)</small>
                </label>
                <input type="number" name="cupo_maximo" class="form-control" value="1" min="1" max="50" style="max-width:120px">
            </div>

            <button type="submit" class="btn btn-primary">+ Agregar horario(s)</button>
        </form>
    </div>
</div>

<!-- Lista de horarios próximos -->
<div class="admin-card">
    <div class="admin-card-header"><h2>Próximos horarios</h2></div>
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Cupos</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($horarios)): ?>
                <tr><td colspan="6" style="text-align:center;padding:2rem;color:var(--text-light)">No hay horarios próximos</td></tr>
            <?php else: foreach ($horarios as $h): ?>
                <tr>
                    <td><?= htmlspecialchars($h['servicio_nombre']) ?></td>
                    <td><?= date('d/m/Y', strtotime($h['fecha'])) ?></td>
                    <td><?= substr($h['hora_inicio'], 0, 5) ?> – <?= substr($h['hora_fin'], 0, 5) ?></td>
                    <td>
                        <span style="color:<?= $h['cupos_ocupados'] >= $h['cupo_maximo'] ? 'var(--danger)' : 'var(--success)' ?>">
                            <?= $h['cupos_ocupados'] ?>/<?= $h['cupo_maximo'] ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!$h['disponible']): ?>
                            <span class="badge badge-danger">Lleno</span>
                        <?php else: ?>
                            <span class="badge badge-success">Disponible</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= BASE_URL ?>/admin/reservas/eliminarHorario/<?= $h['id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('¿Eliminar este horario?')">🗑</a>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function toggleTipo(val) {
    document.getElementById('tipoInput').value = val;
    document.getElementById('bloqueUnico').style.display  = val === 'unico'  ? 'block' : 'none';
    document.getElementById('bloqueRango').style.display  = val === 'rango'  ? 'block' : 'none';
}
</script>
