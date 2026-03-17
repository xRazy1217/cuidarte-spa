<?php
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../models/OtrosModels.php';

class VentasController extends AdminController {
    public function index() {
        $estado = $this->sanitize($_GET['estado'] ?? '');
        $pm = new PedidoModel();
        $where = $estado ? "estado=?" : "1";
        $params = $estado ? [$estado] : [];
        $this->render('ventas/index', [
            'pageTitle' => 'Ventas | Admin',
            'pedidos'   => $pm->all($where, $params),
            'estado'    => $estado,
        ]);
    }

    public function detalle($id) {
        $pedido = (new PedidoModel())->getConDetalle($id);
        $this->render('ventas/detalle', ['pageTitle' => 'Pedido #'.$id.' | Admin', 'pedido' => $pedido]);
    }

    public function estado($id) {
        if ($this->isPost()) {
            $estado = $this->sanitize($_POST['estado'] ?? '');
            (new PedidoModel())->update($id, ['estado' => $estado]);
        }
        $this->redirect('/admin/ventas/detalle/' . $id);
    }

    public function reembolso($id) {
        if (!$this->isPost()) $this->redirect('/admin/ventas/detalle/' . $id);

        $db = getDB();
        $pedido = $db->prepare("SELECT * FROM pedidos WHERE id=? LIMIT 1");
        $pedido->execute([(int)$id]);
        $p = $pedido->fetch();

        if (!$p || $p['estado'] !== 'pagado') $this->redirect('/admin/ventas/detalle/' . $id);

        require_once __DIR__ . '/../../models/Flow.php';
        $resultado = Flow::reembolsar($p['flow_token'], $p['total'], 'Reembolso pedido #' . str_pad($id, 6, '0', STR_PAD_LEFT));

        if (!empty($resultado['flowRefundOrder'])) {
            $db->prepare("UPDATE pedidos SET estado='reembolsado', solicitud_reembolso=0 WHERE id=?")->execute([(int)$id]);
        }

        $this->redirect('/admin/ventas/detalle/' . $id);
    }
}
