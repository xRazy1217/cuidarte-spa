<?php
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../models/OtrosModels.php';

class BlogController extends AdminController {
    private $bm;

    public function __construct() {
        $this->bm = new BlogModel();
    }

    public function index() {
        $this->render('blog/index', [
            'pageTitle' => 'Blog | Admin',
            'articulos' => $this->bm->all('1=1', [], 'ORDER BY created_at DESC') ?: $this->bm->all(),
        ]);
    }

    public function crear() {
        if ($this->isPost()) {
            $id = $this->guardar(null);
            $this->redirect('/admin/blog/editar/' . (int)$id . '?ok=1');
        }
        $this->render('blog/form', ['pageTitle' => 'Nuevo Artículo | Admin', 'articulo' => null]);
    }

    public function editar($id) {
        $id = (int)$id;
        $articulo = $this->bm->find($id);
        if (!$articulo) $this->redirect('/admin/blog');
        if ($this->isPost()) {
            $this->guardar($id);
            $this->redirect('/admin/blog/editar/' . $id . '?ok=1');
        }
        $this->render('blog/form', [
            'pageTitle' => 'Editar Artículo | Admin',
            'articulo'  => $articulo,
            'ok'        => isset($_GET['ok']),
        ]);
    }

    private function guardar($id) {
        $nombre = $this->sanitize($_POST['titulo'] ?? '');
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $nombre)), '-'));
        $data = [
            'titulo'     => $nombre,
            'slug'       => $id ? $slug . '-' . (int)$id : $slug,
            'contenido'  => $_POST['contenido'] ?? '',
            'resumen'    => $this->sanitize($_POST['resumen'] ?? ''),
            'categoria'  => $this->sanitize($_POST['categoria'] ?? ''),
            'publicado'  => isset($_POST['publicado']) ? 1 : 0,
            'meta_title' => $this->sanitize($_POST['meta_title'] ?? ''),
            'meta_desc'  => $this->sanitize($_POST['meta_desc'] ?? ''),
        ];

        if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];
            $ext = strtolower(pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION));
            $mime = mime_content_type($_FILES['imagen']['tmp_name']);
            if (isset($allowed[$ext]) && $allowed[$ext] === $mime && $_FILES['imagen']['size'] <= 2 * 1024 * 1024) {
                $nombre_archivo = bin2hex(random_bytes(16)) . '.' . $ext;
                $destino = UPLOAD_PATH . 'blog/' . $nombre_archivo;
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $destino)) {
                    $data['imagen'] = '/public/uploads/blog/' . $nombre_archivo;
                }
            }
        }

    if ($id) { $this->bm->update($id, $data); return $id; }
        return $this->bm->insert($data);
    }

    public function eliminar($id) {
        $this->bm->delete($id);
        $this->redirect('/admin/blog?eliminado=1');
    }
}
