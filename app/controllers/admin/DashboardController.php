<?php
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../models/OtrosModels.php';
require_once __DIR__ . '/../../models/ProductoModel.php';

class DashboardController extends AdminController {
    public function index() {
        $db = getDB();
        $pm = new PedidoModel();
        $bm = new BlogModel();
        $prm = new ProductoModel();

        $this->render('dashboard', [
            'pageTitle'       => 'Dashboard | Admin',
            'ventas_hoy'      => $pm->totalHoy(),
            'total_pedidos'   => $pm->count(),
            'total_productos' => $prm->count('activo=1'),
            'total_blog'      => $bm->count('publicado=1'),
            'pedidos_recientes' => $pm->getRecientes(5),
            'reservas_recientes' => (new ReservaModel())->getRecientes(5),
        ]);
    }
}
