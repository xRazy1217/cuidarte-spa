<?php
/**
 * Script para enviar recordatorios de reservas
 * Ejecutar cada 30 minutos con cron: */30 * * * * php /ruta/a/cron_recordatorios.php
 */

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/models/Mailer.php';

$db = getDB();

// Obtener reservas que necesitan recordatorio en 2 horas
$ahora = new DateTime('now', new DateTimeZone('America/Santiago'));
$en_2_horas = clone $ahora;
$en_2_horas->add(new DateInterval('PT2H'));
$en_2_horas->add(new DateInterval('PT5M')); // +5 min de margen

$en_2_horas_menos = clone $en_2_horas;
$en_2_horas_menos->sub(new DateInterval('PT10M')); // -10 min de margen

$fecha_inicio = $en_2_horas_menos->format('Y-m-d H:i:s');
$fecha_fin = $en_2_horas->format('Y-m-d H:i:s');

$query = "
    SELECT r.*, s.nombre as servicio_nombre, s.duracion_minutos,
           h.fecha, h.hora_inicio, h.hora_fin
    FROM reservas r
    JOIN servicios s ON r.servicio_id = s.id
    JOIN horarios_disponibles h ON r.horario_id = h.id
    WHERE r.estado = 'confirmada'
    AND CONCAT(h.fecha, ' ', h.hora_inicio) BETWEEN ? AND ?
    AND NOT EXISTS (
        SELECT 1 FROM recordatorios_enviados 
        WHERE reserva_id = r.id AND tipo = 'recordatorio_2h'
    )
";

$st = $db->prepare($query);
$st->execute([$fecha_inicio, $fecha_fin]);
$reservas = $st->fetchAll();

$mailer = new Mailer();
$enviados = 0;

foreach ($reservas as $r) {
    $email = $r['email_cliente'];
    $nombre = $r['nombre_cliente'];
    $servicio = $r['servicio_nombre'];
    $fecha = $r['fecha'];
    $hora = $r['hora_inicio'];
    $modalidad = ucfirst($r['modalidad']);
    $duracion = $r['duracion_minutos'];
    $monto = $r['monto'];
    $reserva_id = $r['id'];
    
    // Formatear fecha
    $fecha_obj = new DateTime($fecha);
    $fecha_formateada = $fecha_obj->format('d/m/Y');
    
    // Crear asunto
    $asunto = "⏰ Recordatorio: Tu sesión en 2 horas - " . SITE_NAME;
    
    // Crear cuerpo HTML
    $cuerpo = "<h2>¡Tu sesión comienza en 2 horas!</h2>";
    $cuerpo .= "<p>Hola <strong>$nombre</strong>,</p>";
    $cuerpo .= "<p>Te recordamos que tienes una sesión programada:</p>";
    $cuerpo .= "<div style='background:#f5f5f5;padding:15px;border-radius:8px;margin:15px 0'>";
    $cuerpo .= "<p><strong>Servicio:</strong> $servicio</p>";
    $cuerpo .= "<p><strong>Fecha:</strong> $fecha_formateada</p>";
    $cuerpo .= "<p><strong>Hora:</strong> $hora (Duración: $duracion minutos)</p>";
    $cuerpo .= "<p><strong>Modalidad:</strong> $modalidad</p>";
    $cuerpo .= "<p><strong>Monto:</strong> \$" . number_format($monto, 0, ',', '.') . "</p>";
    $cuerpo .= "</div>";
    
    // Instrucciones según modalidad
    if ($r['modalidad'] === 'online') {
        $cuerpo .= "<h3>Instrucciones para sesión online:</h3>";
        $cuerpo .= "<ul>";
        $cuerpo .= "<li>Asegúrate de tener una conexión a internet estable</li>";
        $cuerpo .= "<li>Busca un lugar tranquilo y cómodo</li>";
        $cuerpo .= "<li>Ten a mano los aceites esenciales si es necesario</li>";
        $cuerpo .= "<li>Nos contactaremos por el medio acordado 5 minutos antes</li>";
        $cuerpo .= "</ul>";
    } else {
        $cuerpo .= "<h3>Instrucciones para sesión presencial:</h3>";
        $cuerpo .= "<ul>";
        $cuerpo .= "<li>Llega 10 minutos antes de la hora programada</li>";
        $cuerpo .= "<li>Ubica nuestro local en La Serena</li>";
        $cuerpo .= "<li>Si tienes algún inconveniente, contáctanos de inmediato</li>";
        $cuerpo .= "</ul>";
    }
    
    $cuerpo .= "<h3>¿Necesitas cambiar tu cita?</h3>";
    $cuerpo .= "<p>Si necesitas reprogramar o tienes dudas, contáctanos:</p>";
    $cuerpo .= "<p><strong>Teléfono:</strong> " . config('telefono') . "</p>";
    $cuerpo .= "<p><strong>Email:</strong> " . config('email_contacto') . "</p>";
    
    $cuerpo .= "<p style='margin-top:20px;color:#666;font-size:0.9em'>";
    $cuerpo .= "Saludos,<br>" . SITE_NAME;
    $cuerpo .= "</p>";
    
    // Enviar email
    if ($mailer->enviar($email, $asunto, $cuerpo, true)) {
        // Registrar que se envió
        $db->prepare(
            "INSERT INTO recordatorios_enviados (reserva_id, tipo) VALUES (?, 'recordatorio_2h')"
        )->execute([$reserva_id]);
        $enviados++;
        echo "[OK] Recordatorio enviado a $email para reserva #$reserva_id\n";
    } else {
        echo "[ERROR] No se pudo enviar recordatorio a $email para reserva #$reserva_id\n";
    }
}

echo "\n=== Resumen ===\n";
echo "Recordatorios enviados: $enviados\n";
echo "Hora de ejecución: " . $ahora->format('Y-m-d H:i:s') . "\n";
