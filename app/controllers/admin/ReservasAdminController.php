<?php
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../models/OtrosModels.php';

class ReservasAdminController extends AdminController {

    public function index() {
        $this->render('reservas/index', [
            'pageTitle' => 'Reservas | Admin',
            'reservas'  => (new ReservaModel())->getRecientes(50),
        ], 'admin');
    }

    public function estado($id) {
        if ($this->isPost()) {
            $estado = $this->sanitize($_POST['estado'] ?? '');
            if (in_array($estado, ['pendiente', 'confirmada', 'cancelada'])) {
                (new ReservaModel())->update($id, ['estado' => $estado]);
            }
        }
        $this->redirect('/admin/reservas');
    }

    public function horarios() {
        $servicios = (new ServicioModel())->getActivos();
        $db = getDB();

        if ($this->isPost()) {
            $tipo        = $_POST['tipo'] ?? 'unico';
            $servicio_id = (int)$_POST['servicio_id'];
            $hora_inicio = $_POST['hora_inicio'];
            $hora_fin    = $_POST['hora_fin'];
            $cupo        = max(1, (int)($_POST['cupo_maximo'] ?? 1));

            if ($tipo === 'unico') {
                // Single date
                $this->insertarHorario($db, $servicio_id, $_POST['fecha'], $hora_inicio, $hora_fin, $cupo);
            } else {
                // Date range + selected weekdays
                $dias     = $_POST['dias'] ?? [];
                $inicio   = new DateTime($_POST['fecha_inicio']);
                $fin      = new DateTime($_POST['fecha_fin']);
                $interval = new DateInterval('P1D');
                $period   = new DatePeriod($inicio, $interval, $fin->modify('+1 day'));

                foreach ($period as $dia) {
                    if (in_array($dia->format('N'), $dias)) { // 1=Mon … 7=Sun
                        $this->insertarHorario($db, $servicio_id, $dia->format('Y-m-d'), $hora_inicio, $hora_fin, $cupo);
                    }
                }
            }
            $this->redirect('/admin/reservas/horarios?ok=1');
        }

        // List upcoming horarios
        $horarios = $db->query(
            "SELECT h.*, s.nombre as servicio_nombre
             FROM horarios_disponibles h
             JOIN servicios s ON h.servicio_id = s.id
             WHERE h.fecha >= CURDATE()
             ORDER BY h.fecha, h.hora_inicio
             LIMIT 100"
        )->fetchAll();

        $this->render('reservas/horarios', [
            'pageTitle' => 'Horarios | Admin',
            'servicios' => $servicios,
            'horarios'  => $horarios,
            'ok'        => isset($_GET['ok']),
        ], 'admin');
    }

    public function eliminarHorario($id) {
        getDB()->prepare("DELETE FROM horarios_disponibles WHERE id=?")->execute([$id]);
        $this->redirect('/admin/reservas/horarios');
    }

    private function insertarHorario($db, $servicio_id, $fecha, $hora_inicio, $hora_fin, $cupo) {
        $db->prepare(
            "INSERT INTO horarios_disponibles (servicio_id, fecha, hora_inicio, hora_fin, cupo_maximo, cupos_ocupados, disponible)
             VALUES (?, ?, ?, ?, ?, 0, 1)"
        )->execute([$servicio_id, $fecha, $hora_inicio, $hora_fin, $cupo]);
    }
}
