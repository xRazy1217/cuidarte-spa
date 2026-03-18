<?php
require_once __DIR__ . '/AdminController.php';

class ClientesController extends AdminController {
    public function index() {
        $db = getDB();
        $clientes = $db->query(
            "SELECT u.*,
                COUNT(DISTINCT p.id) as total_pedidos,
                COALESCE(SUM(CASE WHEN p.estado='pagado' THEN p.total ELSE 0 END), 0) as total_gastado,
                MAX(p.created_at) as ultima_compra
             FROM usuarios u
             LEFT JOIN pedidos p ON p.usuario_id = u.id
             WHERE u.rol = 'cliente'
             GROUP BY u.id
             ORDER BY u.created_at DESC"
        )->fetchAll();

        $this->render('clientes/index', [
            'pageTitle' => 'Clientes | Admin',
            'clientes'  => $clientes,
        ]);
    }

    public function detalle($id) {
        $db = getDB();
        $cliente = $db->prepare("SELECT * FROM usuarios WHERE id=? AND rol='cliente' LIMIT 1");
        $cliente->execute([(int)$id]);
        $cliente = $cliente->fetch();
        if (!$cliente) $this->redirect('/admin/clientes');

        $pedidos = $db->prepare("SELECT * FROM pedidos WHERE usuario_id=? ORDER BY created_at DESC");
        $pedidos->execute([(int)$id]);

        $reservas = $db->prepare(
            "SELECT r.*, s.nombre as servicio_nombre, h.fecha, h.hora_inicio
             FROM reservas r
             JOIN servicios s ON r.servicio_id = s.id
             JOIN horarios_disponibles h ON r.horario_id = h.id
             WHERE r.email_cliente = ?
             ORDER BY r.created_at DESC"
        );
        $reservas->execute([$cliente['email']]);

        $this->render('clientes/detalle', [
            'pageTitle' => 'Cliente: ' . $cliente['nombre'] . ' | Admin',
            'cliente'   => $cliente,
            'pedidos'   => $pedidos->fetchAll(),
            'reservas'  => $reservas->fetchAll(),
        ]);
    }
}
