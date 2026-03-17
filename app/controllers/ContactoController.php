<?php
require_once __DIR__ . '/Controller.php';

class ContactoController extends Controller {
    public function index() {
        $enviado = false;
        $error = '';

        if ($this->isPost()) {
            $nombre  = $this->sanitize($_POST['nombre'] ?? '');
            $email   = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $mensaje = $this->sanitize($_POST['mensaje'] ?? '');

            if ($nombre && $email && $mensaje) {
                $to = config('email_contacto');
                $asunto = "Contacto desde " . SITE_NAME . " - $nombre";
                $body = "Nombre: $nombre\nEmail: $email\n\nMensaje:\n$mensaje";
                @mail($to, $asunto, $body, "From: $email");
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
