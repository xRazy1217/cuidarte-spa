<?php
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../models/OtrosModels.php';

class CategoriasController extends AdminController {
    private $cm;
    public function __construct() { $this->cm = new CategoriaModel(); }

    public function index() {
        $this->render('categorias/index', ['pageTitle' => 'Categorías | Admin', 'categorias' => $this->cm->all()]);
    }

    public function crear() {
        if ($this->isPost()) {
            $nombre = $this->sanitize($_POST['nombre'] ?? '');
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $nombre)), '-'));
            $this->cm->insert(['nombre' => $nombre, 'slug' => $slug, 'descripcion' => $this->sanitize($_POST['descripcion'] ?? ''), 'activo' => 1]);
            $this->redirect('/admin/categorias?ok=1');
        }
        $this->render('categorias/form', ['pageTitle' => 'Nueva Categoría | Admin', 'categoria' => null]);
    }

    public function editar($id) {
        $id = (int) $id;
        $categoria = $this->cm->find($id);
        if ($this->isPost()) {
            $nombre = $this->sanitize($_POST['nombre'] ?? '');
            $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $nombre)), '-'));
            $this->cm->update($id, ['nombre' => $nombre, 'slug' => $slug, 'descripcion' => $this->sanitize($_POST['descripcion'] ?? ''), 'activo' => isset($_POST['activo']) ? 1 : 0]);
            $this->redirect('/admin/categorias?ok=1');
        }
        $this->render('categorias/form', ['pageTitle' => 'Editar Categoría | Admin', 'categoria' => $categoria]);
    }

    public function eliminar($id) {
        $id = (int) $id;
        $this->cm->delete($id);
        $this->redirect('/admin/categorias');
    }
}
