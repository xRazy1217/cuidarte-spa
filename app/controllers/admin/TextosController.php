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
        if (!$this->isPost()) $this->redirect('/admin/textos');

        $clave = preg_replace('/[^a-z0-9_]/', '', $_POST['clave'] ?? '');
        if (!$clave || empty($_FILES['imagen']['tmp_name'])) $this->redirect('/admin/textos?error=1');

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime  = finfo_file($finfo, $_FILES['imagen']['tmp_name']);
        finfo_close($finfo);

        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($mime, $permitidos)) $this->redirect('/admin/textos?error=tipo');

        $ext  = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'][$mime];
        $nombre = bin2hex(random_bytes(8)) . '.' . $ext;
        move_uploaded_file($_FILES['imagen']['tmp_name'], __DIR__ . '/../../..' . '/public/images/banner/' . $nombre);

        getDB()->prepare("UPDATE configuracion SET valor=? WHERE clave=?")->execute([$nombre, $clave]);
        $this->redirect('/admin/textos?ok=1');
    }

    public function restablecer() {
        if (!$this->isPost()) $this->redirect('/admin/textos');
        getDB()->query("UPDATE configuracion c JOIN configuracion_default d ON c.clave=d.clave SET c.valor=d.valor");
        $this->redirect('/admin/textos?restablecido=1');
    }
}
