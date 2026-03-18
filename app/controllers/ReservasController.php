<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/OtrosModels.php';

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

    public function horarios() {
        $servicio_id = (int)($_GET['servicio_id'] ?? 0);
        $fecha       = $_GET['fecha'] ?? '';
        if (!$servicio_id || !$fecha) $this->json([]);
        $this->json($this->rm->getHorarios($servicio_id, $fecha));
    }

    private function procesar() {
        $horario_id  = (int)($_POST['horario_id'] ?? 0);
        $servicio_id = (int)($_POST['servicio_id'] ?? 0);
        $modalidad   = in_array($_POST['modalidad'] ?? '', ['online', 'presencial'])
                       ? $_POST['modalidad'] : 'online';
        $nombre      = $this->sanitize($_POST['nombre'] ?? '');
        $email       = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $telefono    = $this->sanitize($_POST['telefono'] ?? '');
        $notas       = $this->sanitize($_POST['notas'] ?? '');

        if (!$horario_id || !$nombre || !$email) {
            $this->redirect('/reservar?error=datos_incompletos');
        }

        $servicio = $this->sm->find($servicio_id);
        $monto    = $this->sm->getPrecio($servicio_id, $modalidad);

        $id = $this->rm->insert([
            'servicio_id'    => $servicio_id,
            'horario_id'     => $horario_id,
            'modalidad'      => $modalidad,
            'nombre_cliente' => $nombre,
            'email_cliente'  => $email,
            'telefono'       => $telefono,
            'notas'          => $notas,
            'estado'         => 'pendiente',
            'monto'          => $monto,
        ]);

        $this->rm->bloquearHorario($horario_id);

        // Redirigir a Flow para pago
        require_once __DIR__ . '/../models/Flow.php';
        $concepto = 'Reserva #' . str_pad($id, 6, '0', STR_PAD_LEFT) . ' - ' . ($servicio['nombre'] ?? '') . ' - ' . SITE_NAME;
        $flow = Flow::crearOrden($id, $monto, $email, $concepto, 'reserva');

        if (!empty($flow['url']) && !empty($flow['token'])) {
            header('Location: ' . $flow['url'] . '?token=' . $flow['token']);
            exit;
        } else {
            // Si Flow falla redirigir a confirmación igual
            $this->redirect('/reservar/confirmacion/' . $id);
        }
    }

    public function confirmacion($id) {
        $db = getDB();
        $st = $db->prepare(
            "SELECT r.*, s.nombre as servicio_nombre, s.duracion_minutos,
                    h.fecha, h.hora_inicio, h.hora_fin
             FROM reservas r
             JOIN servicios s ON r.servicio_id = s.id
             JOIN horarios_disponibles h ON r.horario_id = h.id
             WHERE r.id = ? LIMIT 1"
        );
        $st->execute([(int)$id]);
        $reserva = $st->fetch();
        $this->render('reserva_confirmacion', [
            'reserva'   => $reserva,
            'pageTitle' => 'Reserva Confirmada | ' . SITE_NAME,
        ]);
    }

    private function enviarConfirmacion($email, $nombre, $servicio, $modalidad, $monto) {
        $asunto = 'Reserva confirmada - ' . SITE_NAME;
        $msg    = "Hola $nombre,\n\nTu reserva para \"$servicio\" ($modalidad) ha sido recibida."
                . "\nMonto: $" . number_format($monto, 0, ',', '.')
                . "\n\nTe contactaremos para confirmar el horario.\n\n" . SITE_NAME;
        @mail($email, $asunto, $msg, 'From: ' . config('email_contacto'));
    }
}
