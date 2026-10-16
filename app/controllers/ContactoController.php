<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Mailer.php';

class ContactoController extends Controller {
    public function index() {
        $enviado = false;
        $error = '';

        if ($this->isPost()) {
            $nombre  = $this->sanitize($_POST['nombre'] ?? '');
            $email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $mensaje = $this->sanitize($_POST['mensaje'] ?? '');

            if ($nombre && $email && $mensaje) {
                getDB()->prepare(
                    "INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (?,?,?)"
                )->execute([$nombre, $email, $mensaje]);

                $to = config('email_contacto');
                $asunto = "Contacto desde " . SITE_NAME . " - $nombre";
                $cuerpo = "<h3>Nuevo mensaje de contacto</h3>";
                $cuerpo .= "<p><strong>Nombre:</strong> $nombre</p>";
                $cuerpo .= "<p><strong>Email:</strong> $email</p>";
                $cuerpo .= "<p><strong>Mensaje:</strong></p>";
                $cuerpo .= "<p>" . nl2br(htmlspecialchars($mensaje)) . "</p>";
                
                $mailer = new Mailer();
                $mailer->enviar($to, $asunto, $cuerpo, true);
                $enviado = true;
            } else {
                $error = 'Por favor completa todos los campos.';
            }
        }

        $this->render('contacto', [
            'enviado'   => $enviado,
            'error'     => $error,
            'pageTitle' => 'Contacto | ' . SITE_NAME,
        ]);
    }
}
