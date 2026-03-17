<?php
$badges = ['pendiente'=>'badge-warning','confirmada'=>'badge-success','cancelada'=>'badge-danger'];
?>

<div class="page-header">
    <h1>📅 Reservas</h1>
    <a href="<?= BASE_URL ?>/admin/reservas/horarios" class="btn btn-primary">+ Agregar horario</a>
</div>

<div class="admin-card">
    <div style="overflow-x:auto">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($reservas)): ?>
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-light)">No hay reservas aún</td></tr>
            <?php else: foreach ($reservas as $r): ?>
                <tr>
                    <td>#<?= $r['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($r['nombre_cliente'] ?? '-') ?></strong><br>
                        <small style="color:var(--text-light)"><?= htmlspecialchars($r['email'] ?? '') ?></small>
                    </td>
                    <td><?= htmlspecialchars($r['servicio_nombre'] ?? '-') ?></td>
                    <td><?= date('d/m/Y', strtotime($r['fecha'])) ?></td>
                    <td><?= substr($r['hora_inicio'] ?? '', 0, 5) ?></td>
                    <td><span class="badge <?= $badges[$r['estado']] ?? 'badge-secondary' ?>"><?= $r['estado'] ?></span></td>
                    <td>
                        <form method="POST" action="<?= BASE_URL ?>/admin/reservas/estado/<?= $r['id'] ?>" style="display:flex;gap:0.5rem">
                            <select name="estado" class="form-control" style="padding:0.3rem;font-size:0.8rem">
                                <option value="pendiente" <?= $r['estado']==='pendiente'?'selected':'' ?>>Pendiente</option>
                                <option value="confirmada" <?= $r['estado']==='confirmada'?'selected':'' ?>>Confirmada</option>
                                <option value="cancelada" <?= $r['estado']==='cancelada'?'selected':'' ?>>Cancelada</option>
                            </select>
                            <button type="submit" class="btn btn-primary btn-sm">✓</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>
