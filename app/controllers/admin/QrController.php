<?php
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../models/ProductoModel.php';
require_once __DIR__ . '/../../models/OtrosModels.php';

class QrController extends AdminController {
    public function index() {
        $productos = (new ProductoModel())->getAllAdmin();
        $servicios = (new ServicioModel())->getActivos();
        $this->render('qr/index', [
            'pageTitle' => 'Centro QR | Admin',
            'productos' => $productos,
            'servicios' => $servicios,
        ]);
    }
}
