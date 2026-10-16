<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/OtrosModels.php';

class ServiciosController extends Controller {
    public function index() {
        $this->render('servicios', [
            'servicios' => (new ServicioModel())->getActivos(),
            'pageTitle' => 'Terapia RAI, Aromaterapia y Crianza Consciente en La Serena | ' . SITE_NAME,
            'pageDesc'  => 'Sesiones online y presenciales de Terapia RAI, Aromaterapia, Guía en Crianza Waldorf y Círculos de Autocuidado en La Serena, Chile.',
        ]);
    }

    public function consultaFono() {
        if (!$this->isPost()) $this->redirect('/servicios');

        $nombre   = $this->sanitize($_POST['nombre'] ?? '');
        $email    = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $telefono = $this->sanitize($_POST['telefono'] ?? '');
        $servicio = $this->sanitize($_POST['servicio'] ?? '');
        $mensaje  = $this->sanitize($_POST['mensaje'] ?? '');

        if (!$nombre || !$email || !$servicio) $this->redirect('/servicios#fonoaudiologia');

        $texto = "Consulta Fonoaudiología\nServicio: $servicio\nTeléfono: $telefono\n\n$mensaje";

        getDB()->prepare(
            "INSERT INTO mensajes_contacto (nombre, email, mensaje) VALUES (?,?,?)"
        )->execute([$nombre, $email, $texto]);

        $to = config('email_contacto');
        @mail($to, "Consulta Fonoaudiología - $nombre | " . SITE_NAME,
            "Nombre: $nombre\nEmail: $email\nTeléfono: $telefono\nServicio: $servicio\n\nMensaje:\n$mensaje",
            "From: $email"
        );

        $this->redirect('/servicios?fono_ok=1#fonoaudiologia');
    }
}
