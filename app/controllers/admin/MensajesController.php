<?php
require_once __DIR__ . '/AdminController.php';

class MensajesController extends AdminController {
    public function index() {
        $mensajes = getDB()->query(
            "SELECT * FROM mensajes_contacto ORDER BY created_at DESC"
        )->fetchAll();

        $this->render('mensajes/index', [
            'pageTitle' => 'Mensajes | Admin',
            'mensajes'  => $mensajes,
        ]);
    }

    public function ver($id) {
        $db = getDB();
        $db->prepare("UPDATE mensajes_contacto SET leido=1 WHERE id=?")->execute([(int)$id]);
        $mensaje = $db->prepare("SELECT * FROM mensajes_contacto WHERE id=? LIMIT 1");
        $mensaje->execute([(int)$id]);
        $this->render('mensajes/detalle', [
            'pageTitle' => 'Mensaje | Admin',
            'mensaje'   => $mensaje->fetch(),
        ]);
    }

    public function eliminar($id) {
        if ($this->isPost()) {
            getDB()->prepare("DELETE FROM mensajes_contacto WHERE id=?")->execute([(int)$id]);
        }
        $this->redirect('/admin/mensajes');
    }
}
