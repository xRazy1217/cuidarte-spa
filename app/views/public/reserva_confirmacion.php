<?php
$calendarUrl = '';
if ($reserva && !empty($reserva['fecha']) && !empty($reserva['hora_inicio'])) {
    $inicio = str_replace(['-', ':', ' '], '', $reserva['fecha'] . 'T' . $reserva['hora_inicio']);
    $fin    = str_replace(['-', ':', ' '], '', $reserva['fecha'] . 'T' . $reserva['hora_fin']);
    $titulo = urlencode(($reserva['servicio_nombre'] ?? 'Sesión') . ' - ' . SITE_NAME);
    $detalle = urlencode('Reserva #' . str_pad($reserva['id'], 6, '0', STR_PAD_LEFT) . ' | Modalidad: ' . ucfirst($reserva['modalidad'] ?? ''));
    $calendarUrl = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$titulo}&dates={$inicio}/{$fin}&details={$detalle}";
}
?>

<section class="wc-section wc-bg-watercolor">
<div class="container" style="max-width:700px">

    <div style="text-align:center;margin-bottom:2rem">
        <div style="width:90px;height:90px;background:linear-gradient(135deg,var(--wc-blue),var(--wc-pink));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;color:white;font-size:2.5rem;box-shadow:0 8px 25px rgba(74,144,217,0.3)">✓</div>
        <h1 style="color:var(--wc-blue-dark);margin-bottom:0.5rem">¡Pago Exitoso!</h1>
        <p style="color:var(--wc-text-soft);font-size:1.05rem">Tu reserva ha sido confirmada. Te enviamos los detalles por correo.</p>
    </div>

    <?php if ($reserva): ?>
    <div class="wc-card mb-3">
        <div class="wc-card-body">
            <h4 style="color:var(--wc-blue-dark);margin-bottom:1.25rem">📋 Detalles de tu reserva</h4>
            <div style="display:grid;gap:0.75rem;font-size:0.95rem">
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid rgba(180,160,140,0.15)">
                    <span style="color:var(--wc-text-soft)">N° Reserva</span>
                    <strong style="color:var(--wc-blue)">#<?= str_pad($reserva['id'], 6, '0', STR_PAD_LEFT) ?></strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid rgba(180,160,140,0.15)">
                    <span style="color:var(--wc-text-soft)">Servicio</span>
                    <strong><?= htmlspecialchars($reserva['servicio_nombre'] ?? '-') ?></strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid rgba(180,160,140,0.15)">
                    <span style="color:var(--wc-text-soft)">Fecha</span>
                    <strong><?= !empty($reserva['fecha']) ? date('d/m/Y', strtotime($reserva['fecha'])) : '-' ?></strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid rgba(180,160,140,0.15)">
                    <span style="color:var(--wc-text-soft)">Horario</span>
                    <strong><?= !empty($reserva['hora_inicio']) ? substr($reserva['hora_inicio'], 0, 5) . ' - ' . substr($reserva['hora_fin'], 0, 5) : '-' ?></strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid rgba(180,160,140,0.15)">
                    <span style="color:var(--wc-text-soft)">Modalidad</span>
                    <strong><?= ucfirst($reserva['modalidad'] ?? '-') ?></strong>
                </div>
                <div style="display:flex;justify-content:space-between;padding-bottom:0.75rem;border-bottom:1px solid rgba(180,160,140,0.15)">
                    <span style="color:var(--wc-text-soft)">Cliente</span>
                    <strong><?= htmlspecialchars($reserva['nombre_cliente'] ?? '-') ?></strong>
                </div>
                <div style="display:flex;justify-content:space-between">
                    <span style="color:var(--wc-text-soft)">Monto pagado</span>
                    <strong style="color:#1a1a1a;font-size:1.1rem">$<?= number_format($reserva['monto'] ?? 0, 0, ',', '.') ?></strong>
                </div>
            </div>
        </div>
    </div>

    <?php if ($calendarUrl): ?>
    <div class="wc-card mb-3" style="border:1px solid rgba(74,144,217,0.2);background:var(--wc-blue-pale)">
        <div class="wc-card-body" style="text-align:center">
            <div style="font-size:2rem;margin-bottom:0.5rem">📅</div>
            <h5 style="color:var(--wc-blue-dark);margin-bottom:0.5rem">¿Quieres recordarlo?</h5>
            <p style="color:var(--wc-text-soft);font-size:0.9rem;margin-bottom:1rem">Agrega tu sesión a Google Calendar y recibe un recordatorio automático.</p>
            <a href="<?= $calendarUrl ?>" target="_blank" class="btn-wc-primary" style="display:inline-flex;align-items:center;gap:0.5rem">
                📅 Agendar en Google Calendar
            </a>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>

    <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
        <a href="<?= BASE_URL ?>/" class="btn-wc-primary">Volver al Inicio</a>
        <a href="<?= BASE_URL ?>/servicios" class="btn-wc-outline">Ver Servicios</a>
    </div>

</div>
</section>
