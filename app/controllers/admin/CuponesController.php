<?php
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../models/OtrosModels.php';

class CuponesController extends AdminController {
    private $cm;
    public function __construct() { $this->cm = new CuponModel(); }

    public function index() {
        $this->render('cupones/index', ['pageTitle' => 'Cupones | Admin', 'cupones' => $this->cm->all()]);
    }

    public function crear() {
        if ($this->isPost()) {
            $this->cm->insert([
                'codigo'           => strtoupper($this->sanitize($_POST['codigo'] ?? '')),
                'tipo'             => $_POST['tipo'] ?? 'porcentaje',
                'valor'            => (float)($_POST['valor'] ?? 0),
                'uso_maximo'       => (int)($_POST['uso_maximo'] ?? 1),
                'fecha_expiracion' => $_POST['fecha_expiracion'] ?: null,
                'activo'           => 1,
            ]);
            $this->redirect('/admin/cupones?ok=1');
        }
        $this->render('cupones/form', ['pageTitle' => 'Nuevo Cupón | Admin', 'cupon' => null]);
    }

    public function editar($id) {
        $cupon = $this->cm->find($id);
        if ($this->isPost()) {
            $this->cm->update($id, [
                'codigo'           => strtoupper($this->sanitize($_POST['codigo'] ?? '')),
                'tipo'             => $_POST['tipo'] ?? 'porcentaje',
                'valor'            => (float)($_POST['valor'] ?? 0),
                'uso_maximo'       => (int)($_POST['uso_maximo'] ?? 1),
                'fecha_expiracion' => $_POST['fecha_expiracion'] ?: null,
                'activo'           => isset($_POST['activo']) ? 1 : 0,
            ]);
            $this->redirect('/admin/cupones?ok=1');
        }
        $this->render('cupones/form', ['pageTitle' => 'Editar Cupón | Admin', 'cupon' => $cupon]);
    }

    public function eliminar($id) {
        $this->cm->delete($id);
        $this->redirect('/admin/cupones');
    }
}
