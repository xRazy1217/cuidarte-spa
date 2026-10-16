<?php
require_once __DIR__ . '/AdminController.php';

class TextosController extends AdminController {

    private function getTextos() {
        $rows = getDB()->query("SELECT clave, valor FROM configuracion ORDER BY clave")->fetchAll();
        $cfg = [];
        foreach ($rows as $r) $cfg[$r['clave']] = $r['valor'];
        return $cfg;
    }

    public function index() {
        $this->render('textos/index', [
            'pageTitle' => 'Textos Mágicos | Admin',
            'textos'    => $this->getTextos(),
        ]);
    }

    public function guardar() {
        if (!$this->isPost()) $this->redirect('/admin/textos');

        $db = getDB();
        foreach ($_POST as $clave => $valor) {
            if ($clave === 'csrf_token') continue;
            $clave = preg_replace('/[^a-z0-9_]/', '', $clave);
            $db->prepare("UPDATE configuracion SET valor=? WHERE clave=?")->execute([trim($valor), $clave]);
        }

        $this->redirect('/admin/textos?ok=1');
    }

    public function subirImagen() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->redirect('/admin/textos');
        if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') $this->redirect('/login');

        $clave = preg_replace('/[^a-z0-9_]/', '', $_POST['clave'] ?? '');
        $file  = $_FILES['imagen'] ?? null;

        if (!$clave || !$file || $file['error'] !== UPLOAD_ERR_OK) {
            $this->redirect('/admin/textos?error=archivo');
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $this->redirect('/admin/textos?error=tipo');
        }

        $nombre  = bin2hex(random_bytes(8)) . '.' . $ext;
        $destino = realpath(__DIR__ . '/../../../public/images/banner') . DIRECTORY_SEPARATOR . $nombre;

        if (!move_uploaded_file($file['tmp_name'], $destino)) {
            $this->redirect('/admin/textos?error=mover');
        }

        getDB()->prepare("UPDATE configuracion SET valor=? WHERE clave=?")->execute([$nombre, $clave]);
        $this->redirect('/admin/textos?ok=1');
    }

    public function restablecer() {
        if (!$this->isPost()) $this->redirect('/admin/textos');
        getDB()->query("UPDATE configuracion c JOIN configuracion_default d ON c.clave=d.clave SET c.valor=d.valor");
        $this->redirect('/admin/textos?restablecido=1');
    }
}
