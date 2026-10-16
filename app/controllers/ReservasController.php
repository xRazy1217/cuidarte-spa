<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/OtrosModels.php';
require_once __DIR__ . '/../models/Mailer.php';

class ReservasController extends Controller {
    private $rm;
    private $sm;

    public function __construct() {
        $this->rm = new ReservaModel();
        $this->sm = new ServicioModel();
    }

    public function index($servicio_slug = null) {
        if ($this->isPost()) {
            $this->procesar();
            return;
        }

        $this->render('reservar', [
            'servicios' => $this->sm->getActivos(),
            'pageTitle' => 'Reservar Sesión | ' . SITE_NAME,
        ]);
    }

    private function procesar() {
        $servicio_id = (int)($_POST['servicio_id'] ?? 0);
        $fecha       = $_POST['fecha'] ?? '';
        $modalidad   = in_array($_POST['modalidad'] ?? '', ['online', 'presencial'])
                       ? $_POST['modalidad'] : 'online';
        $nombre      = $this->sanitize($_POST['nombre'] ?? '');
        $email       = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $telefono    = $this->sanitize($_POST['telefono'] ?? '');
        $notas       = $this->sanitize($_POST['notas'] ?? '');

        if (!$servicio_id || !$fecha || !$nombre || !$email) {
            $this->redirect('/reservar?error=datos_incompletos');
        }

        // Validar que la fecha no sea en el pasado
        if (strtotime($fecha) < strtotime('today')) {
            $this->redirect('/reservar?error=fecha_invalida');
        }

        $servicio = $this->sm->find($servicio_id);
        $monto    = $this->sm->getPrecio($servicio_id, $modalidad);

        $id = $this->rm->insert([
            'servicio_id'    => $servicio_id,
            'horario_id'     => null,
            'modalidad'      => $modalidad,
            'nombre_cliente' => $nombre,
            'email_cliente'  => $email,
            'telefono'       => $telefono,
            'notas'          => $notas,
            'estado'         => 'pendiente_confirmacion',
            'monto'          => $monto,
        ]);

        // Enviar email de confirmación de recepción
        $this->enviarConfirmacionRecepcion($email, $nombre, $servicio['nombre'], $fecha, $modalidad);

        $this->redirect('/reservar/confirmacion/' . $id);
    }

    public function confirmacion($id) {
        $reserva = $this->rm->getConServicio((int)$id);
        if (!$reserva) {
            $this->redirect('/reservar?error=no_encontrada');
        }

        $this->render('reserva_confirmacion', [
            'reserva'   => $reserva,
            'pageTitle' => 'Reserva en Espera | ' . SITE_NAME,
        ]);
    }

    private function enviarConfirmacionRecepcion($email, $nombre, $servicio, $fecha, $modalidad) {
        $asunto = 'Tu reserva está en espera de confirmación - ' . SITE_NAME;
        $cuerpo = "<h2>Hemos recibido tu solicitud de reserva</h2>";
        $cuerpo .= "<p>Hola <strong>$nombre</strong>,</p>";
        $cuerpo .= "<p>Tu solicitud de reserva ha sido recibida y está en espera de confirmación.</p>";
        $cuerpo .= "<ul>";
        $cuerpo .= "<li><strong>Servicio:</strong> $servicio</li>";
        $cuerpo .= "<li><strong>Fecha solicitada:</strong> " . date('d/m/Y', strtotime($fecha)) . "</li>";
        $cuerpo .= "<li><strong>Modalidad:</strong> " . ucfirst($modalidad) . "</li>";
        $cuerpo .= "</ul>";
        $cuerpo .= "<p>Nos contactaremos contigo pronto para confirmar la disponibilidad y los detalles de tu sesión.</p>";
        $cuerpo .= "<p>Saludos,<br>" . SITE_NAME . "</p>";
        
        $mailer = new Mailer();
        $mailer->enviar($email, $asunto, $cuerpo, true);
    }
}
