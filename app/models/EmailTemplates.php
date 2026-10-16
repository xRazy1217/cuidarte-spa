<?php
/**
 * Clase EmailTemplates - Plantillas HTML para correos
 * Archivo: app/models/EmailTemplates.php
 */

class EmailTemplates {
    
    /**
     * Plantilla: Confirmación de registro
     */
    public static function registroConfirmacion($nombre, $enlace_activacion) {
        $sitio = SITE_NAME;
        $logo = BASE_URL . '/public/images/logo.jpeg';
        
        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: 'Lato', Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 8px; }
                .header { background: linear-gradient(135deg, #4A90A4, #2d1b4e); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
                .header img { max-width: 150px; margin-bottom: 10px; }
                .content { background: white; padding: 30px; }
                .button { display: inline-block; background: #4A90A4; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
                .footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 0.9em; color: #666; border-radius: 0 0 8px 8px; }
                .link { color: #4A90A4; text-decoration: none; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Bienvenido a $sitio</h1>
                </div>
                <div class='content'>
                    <p>Hola <strong>$nombre</strong>,</p>
                    <p>¡Gracias por registrarte en $sitio! Para completar tu registro, confirma tu correo electrónico haciendo clic en el botón de abajo:</p>
                    <center>
                        <a href='$enlace_activacion' class='button'>Confirmar mi correo</a>
                    </center>
                    <p>O copia este enlace en tu navegador:</p>
                    <p><a href='$enlace_activacion' class='link'>$enlace_activacion</a></p>
                    <p><small>Este enlace expira en 24 horas.</small></p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " $sitio. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Plantilla: Comprobante de compra
     */
    public static function comprobanteCompra($nombre, $numero_pedido, $items, $subtotal, $descuento, $total) {
        $sitio = SITE_NAME;
        $fecha = date('d/m/Y H:i');
        
        $items_html = '';
        foreach ($items as $item) {
            $subtotal_item = $item['precio'] * $item['cantidad'];
            $items_html .= "
            <tr>
                <td style='padding: 10px; border-bottom: 1px solid #eee;'>{$item['producto_nombre']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: center;'>{$item['variante_nombre']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: center;'>{$item['cantidad']}</td>
                <td style='padding: 10px; border-bottom: 1px solid #eee; text-align: right;'>\$" . number_format($subtotal_item, 0, ',', '.') . "</td>
            </tr>
            ";
        }

        $descuento_html = $descuento > 0 ? "
            <tr style='background: #f0f0f0;'>
                <td colspan='3' style='padding: 10px; text-align: right;'><strong>Descuento:</strong></td>
                <td style='padding: 10px; text-align: right; color: green;'><strong>-\$" . number_format($descuento, 0, ',', '.') . "</strong></td>
            </tr>
        " : '';

        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: 'Lato', Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 8px; }
                .header { background: linear-gradient(135deg, #4A90A4, #2d1b4e); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: white; padding: 30px; }
                .table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                .table th { background: #4A90A4; color: white; padding: 12px; text-align: left; }
                .total-row { background: #4A90A4; color: white; font-weight: bold; }
                .total-row td { padding: 15px; font-size: 1.1em; }
                .footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 0.9em; color: #666; border-radius: 0 0 8px 8px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>¡Compra Confirmada!</h1>
                    <p>Pedido #$numero_pedido</p>
                </div>
                <div class='content'>
                    <p>Hola <strong>$nombre</strong>,</p>
                    <p>Gracias por tu compra. Aquí está el detalle de tu pedido:</p>
                    
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Variante</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            $items_html
                            $descuento_html
                            <tr class='total-row'>
                                <td colspan='3' style='text-align: right;'>TOTAL:</td>
                                <td style='text-align: right;'>\$" . number_format($total, 0, ',', '.') . "</td>
                            </tr>
                        </tbody>
                    </table>

                    <p><strong>Fecha del pedido:</strong> $fecha</p>
                    <p>Nos contactaremos contigo pronto para coordinar el pago y envío.</p>
                    <p>Si tienes preguntas, no dudes en contactarnos.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " $sitio. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Plantilla: Confirmación de reserva
     */
    public static function confirmacionReserva($nombre, $numero_reserva, $servicio, $fecha, $hora, $modalidad, $monto) {
        $sitio = SITE_NAME;
        $fecha_formateada = date('d/m/Y', strtotime($fecha));
        
        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: 'Lato', Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 8px; }
                .header { background: linear-gradient(135deg, #4A90A4, #2d1b4e); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: white; padding: 30px; }
                .info-box { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4A90A4; }
                .footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 0.9em; color: #666; border-radius: 0 0 8px 8px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>¡Reserva Confirmada!</h1>
                    <p>Reserva #$numero_reserva</p>
                </div>
                <div class='content'>
                    <p>Hola <strong>$nombre</strong>,</p>
                    <p>Tu reserva ha sido confirmada. Aquí están los detalles:</p>
                    
                    <div class='info-box'>
                        <p><strong>Servicio:</strong> $servicio</p>
                        <p><strong>Fecha:</strong> $fecha_formateada</p>
                        <p><strong>Hora:</strong> $hora</p>
                        <p><strong>Modalidad:</strong> " . ucfirst($modalidad) . "</p>
                        <p><strong>Monto:</strong> \$" . number_format($monto, 0, ',', '.') . "</p>
                    </div>

                    <p>Nos contactaremos contigo pronto para confirmar los detalles finales.</p>
                    <p>Si necesitas cambiar tu cita, contáctanos lo antes posible.</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " $sitio. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    /**
     * Plantilla: Recordatorio de sesión
     */
    public static function recordatorioSesion($nombre, $servicio, $fecha, $hora, $modalidad, $duracion, $monto) {
        $sitio = SITE_NAME;
        $fecha_formateada = date('d/m/Y', strtotime($fecha));
        
        $instrucciones = $modalidad === 'online' ? "
            <h3>Instrucciones para sesión online:</h3>
            <ul>
                <li>Asegúrate de tener una conexión a internet estable</li>
                <li>Busca un lugar tranquilo y cómodo</li>
                <li>Ten a mano los aceites esenciales si es necesario</li>
                <li>Nos contactaremos por el medio acordado 5 minutos antes</li>
            </ul>
        " : "
            <h3>Instrucciones para sesión presencial:</h3>
            <ul>
                <li>Llega 10 minutos antes de la hora programada</li>
                <li>Ubica nuestro local en La Serena</li>
                <li>Si tienes algún inconveniente, contáctanos de inmediato</li>
            </ul>
        ";

        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <style>
                body { font-family: 'Lato', Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background: #f9f9f9; padding: 20px; border-radius: 8px; }
                .header { background: linear-gradient(135deg, #4A90A4, #2d1b4e); color: white; padding: 30px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: white; padding: 30px; }
                .info-box { background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4A90A4; }
                .footer { background: #f5f5f5; padding: 20px; text-align: center; font-size: 0.9em; color: #666; border-radius: 0 0 8px 8px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>⏰ Recordatorio: Tu sesión en 2 horas</h1>
                </div>
                <div class='content'>
                    <p>Hola <strong>$nombre</strong>,</p>
                    <p>Te recordamos que tienes una sesión programada:</p>
                    
                    <div class='info-box'>
                        <p><strong>Servicio:</strong> $servicio</p>
                        <p><strong>Fecha:</strong> $fecha_formateada</p>
                        <p><strong>Hora:</strong> $hora (Duración: $duracion minutos)</p>
                        <p><strong>Modalidad:</strong> " . ucfirst($modalidad) . "</p>
                        <p><strong>Monto:</strong> \$" . number_format($monto, 0, ',', '.') . "</p>
                    </div>

                    $instrucciones

                    <h3>¿Necesitas cambiar tu cita?</h3>
                    <p>Si necesitas reprogramar o tienes dudas, contáctanos:</p>
                    <p><strong>Teléfono:</strong> " . config('telefono') . "</p>
                    <p><strong>Email:</strong> " . config('email_contacto') . "</p>
                </div>
                <div class='footer'>
                    <p>&copy; " . date('Y') . " $sitio. Todos los derechos reservados.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }
}
