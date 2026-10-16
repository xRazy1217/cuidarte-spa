<?php
require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../../models/OtrosModels.php';
require_once __DIR__ . '/../../models/Mailer.php';

class AdminReservasController extends Controller {
    private $rm;
    private $sm;

    public function __construct() {
        $this->requireAdmin();
        $this->rm = new ReservaModel();
        $this->sm = new ServicioModel();
    }

    // RESERVAS
    public function index() {
        $estado = $_GET['estado'] ?? 'pendiente_confirmacion';
        $page = (int)($_GET['page'] ?? 1);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $reservas = $this->rm->getPorEstado($estado, $limit, $offset);

        $this->render('admin/reservas_lista', [
            'reservas' => $reservas,
            'estado_actual' => $estado,
            'page' => $page,
            'pageTitle' => 'Gestionar Reservas | Admin',
        ]);
    }

    public function ver($id) {
        $reserva = $this->rm->getConServicio((int)$id);
        if (!$reserva) {
            $this->redirect('/admin/reservas?error=no_encontrada');
        }

        $this->render('admin/reserva_detalle', [
            'reserva' => $reserva,
            'pageTitle' => 'Detalle Reserva | Admin',
        ]);
    }

    public function actualizar($id) {
        if (!$this->isPost()) {
            $this->redirect('/admin/reservas');
        }

        $estado = $_POST['estado'] ?? 'pendiente_confirmacion';
        $notas_admin = $this->sanitize($_POST['notas_admin'] ?? '');
        $fecha_sugerida = $_POST['fecha_sugerida'] ?? null;

        if (!in_array($estado, ['pendiente_confirmacion', 'confirmada', 'rechazada', 'cancelada'])) {
            $this->redirect('/admin/reservas?error=estado_invalido');
        }

        $reserva = $this->rm->getConServicio((int)$id);
        if (!$reserva) {
            $this->redirect('/admin/reservas?error=no_encontrada');
        }

        $this->rm->actualizarEstado($id, $estado, $notas_admin, $fecha_sugerida);

        // Enviar email según el estado
        $this->enviarEmailSegunEstado($reserva, $estado, $notas_admin, $fecha_sugerida);

        $this->redirect('/admin/reservas?success=actualizada');
    }

    private function enviarEmailSegunEstado($reserva, $estado, $notas_admin, $fecha_sugerida) {
        $email = $reserva['email_cliente'];
        $nombre = $reserva['nombre_cliente'];
        $servicio = $reserva['servicio_nombre'];
        $mailer = new Mailer();

        if ($estado === 'confirmada') {
            $asunto = 'Tu sesión ha sido confirmada - ' . SITE_NAME;
            $cuerpo = "<h2>¡Tu sesión está confirmada!</h2>";
            $cuerpo .= "<p>Hola <strong>$nombre</strong>,</p>";
            $cuerpo .= "<p>Tu sesión de <strong>$servicio</strong> ha sido confirmada.</p>";
            if ($notas_admin) {
                $cuerpo .= "<p><strong>Detalles:</strong> $notas_admin</p>";
            }
            $cuerpo .= "<p>Saludos,<br>" . SITE_NAME . "</p>";
        } elseif ($estado === 'rechazada') {
            $asunto = 'Información sobre tu solicitud de reserva - ' . SITE_NAME;
            $cuerpo = "<h2>Información sobre tu solicitud</h2>";
            $cuerpo .= "<p>Hola <strong>$nombre</strong>,</p>";
            $cuerpo .= "<p>Lamentablemente no podemos atender tu solicitud de reserva para la fecha solicitada.</p>";
            if ($notas_admin) {
                $cuerpo .= "<p><strong>Motivo:</strong> $notas_admin</p>";
            }
            if ($fecha_sugerida) {
                $cuerpo .= "<p><strong>Te sugerimos estas fechas alternativas:</strong> " . date('d/m/Y', strtotime($fecha_sugerida)) . "</p>";
            }
            $cuerpo .= "<p>Puedes intentar reservar nuevamente con otras fechas.</p>";
            $cuerpo .= "<p>Saludos,<br>" . SITE_NAME . "</p>";
        }

        if (isset($asunto) && isset($cuerpo)) {
            $mailer->enviar($email, $asunto, $cuerpo, true);
        }
    }

    // SERVICIOS
    public function servicios() {
        $servicios = $this->sm->all();

        $this->render('admin/servicios_lista', [
            'servicios' => $servicios,
            'pageTitle' => 'Gestionar Servicios | Admin',
        ]);
    }

    public function crear_servicio() {
        if ($this->isPost()) {
            $this->guardar_servicio();
            return;
        }

        $this->render('admin/servicio_form', [
            'servicio' => null,
            'pageTitle' => 'Nuevo Servicio | Admin',
        ]);
    }

    public function editar_servicio($id) {
        $servicio = $this->sm->find((int)$id);
        if (!$servicio) {
            $this->redirect('/admin/servicios?error=no_encontrado');
        }

        if ($this->isPost()) {
            $this->guardar_servicio($id);
            return;
        }

        $this->render('admin/servicio_form', [
            'servicio' => $servicio,
            'pageTitle' => 'Editar Servicio | Admin',
        ]);
    }

    private function guardar_servicio($id = null) {
        $nombre = $this->sanitize($_POST['nombre'] ?? '');
        $descripcion = $this->sanitize($_POST['descripcion'] ?? '');
        $duracion = (int)($_POST['duracion_minutos'] ?? 60);
        $precio = (float)($_POST['precio'] ?? 0);
        $precio_presencial = (float)($_POST['precio_presencial'] ?? 0);
        $modalidad = in_array($_POST['modalidad'] ?? '', ['online', 'presencial', 'ambas'])
                     ? $_POST['modalidad'] : 'ambas';
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (!$nombre || $precio <= 0 || $duracion <= 0) {
            $this->redirect('/admin/servicios?error=datos_incompletos');
        }

        $data = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'duracion_minutos' => $duracion,
            'precio' => $precio,
            'precio_presencial' => $precio_presencial ?: null,
            'modalidad' => $modalidad,
            'activo' => $activo,
        ];

        if ($id) {
            $this->sm->update($id, $data);
            $this->redirect('/admin/servicios?success=actualizado');
        } else {
            $slug = $this->generarSlug($nombre);
            $data['slug'] = $slug;
            $this->sm->insert($data);
            $this->redirect('/admin/servicios?success=creado');
        }
    }

    public function eliminar_servicio($id) {
        $servicio = $this->sm->find((int)$id);
        if (!$servicio) {
            $this->redirect('/admin/servicios?error=no_encontrado');
        }

        $this->sm->delete($id);
        $this->redirect('/admin/servicios?success=eliminado');
    }

    private function generarSlug($texto) {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $texto)));
        return trim($slug, '-');
    }
}
