<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/OtrosModels.php';

class ServiciosController extends Controller {
    public function index() {
        $this->render('servicios', [
            'servicios' => (new ServicioModel())->getActivos(),
            'pageTitle' => 'Servicios | ' . SITE_NAME,
        ]);
    }
}
