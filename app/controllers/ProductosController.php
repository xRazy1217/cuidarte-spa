<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/OtrosModels.php';

class ProductosController extends Controller {
    private $pm;

    public function __construct() {
        $this->pm = new ProductoModel();
    }

    public function index() {
        $categorias = (new CategoriaModel())->all('activo=1');
        $cat_id = isset($_GET['cat']) ? (int)$_GET['cat'] : null;
        $productos = $cat_id
            ? $this->pm->all("activo=1 AND categoria_id=?", [$cat_id])
            : $this->pm->getAll();

        // Agregar precio_desde a cada producto
        foreach ($productos as &$p) {
            $vars = $this->pm->getVariantes($p['id']);
            $p['precio_desde'] = $vars ? min(array_column($vars, 'precio')) : 0;
        }

        $this->render('productos', [
            'productos'  => $productos,
            'categorias' => $categorias,
            'cat_activa' => $cat_id,
            'pageTitle'  => 'Productos | ' . SITE_NAME,
        ]);
    }

    public function detalle($slug) {
        $producto = $this->pm->getBySlug($slug);
        if (!$producto) { http_response_code(404); echo "Producto no encontrado"; return; }

        $this->render('producto_detalle', [
            'producto'  => $producto,
            'variantes' => $this->pm->getVariantes($producto['id']),
            'imagenes'  => $this->pm->getImagenes($producto['id']),
            'pageTitle' => $producto['nombre'] . ' | ' . SITE_NAME,
            'pageDesc'  => $producto['descripcion_corta'],
        ]);
    }
}
