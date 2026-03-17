<?php
require_once __DIR__ . '/AdminController.php';
require_once __DIR__ . '/../../models/ProductoModel.php';
require_once __DIR__ . '/../../models/OtrosModels.php';

class ProductosController extends AdminController {
    private $pm;

    public function __construct() {
        $this->pm = new ProductoModel();
    }

    public function index() {
        $this->render('productos/index', [
            'pageTitle' => 'Productos | Admin',
            'productos' => $this->pm->getAllAdmin(),
        ]);
    }

    public function crear() {
        $categorias = (new CategoriaModel())->all();
        if ($this->isPost()) {
            $id = $this->guardar(null);
            $this->redirect('/admin/productos/editar/' . $id . '?ok=1');
        }
        $this->render('productos/form', [
            'pageTitle'  => 'Nuevo Producto | Admin',
            'producto'   => null,
            'variantes'  => [],
            'categorias' => $categorias,
        ]);
    }

    public function editar($id) {
        $producto = $this->pm->find($id);
        if (!$producto) $this->redirect('/admin/productos');
        $categorias = (new CategoriaModel())->all();

        if ($this->isPost()) {
            $this->guardar($id);
            $this->redirect('/admin/productos/editar/' . $id . '?ok=1');
        }

        $this->render('productos/form', [
            'pageTitle'  => 'Editar Producto | Admin',
            'producto'   => $producto,
            'variantes'  => $this->pm->getVariantes($id),
            'imagenes'   => $this->pm->getImagenes($id),
            'categorias' => $categorias,
            'ok'         => isset($_GET['ok']),
        ]);
    }

    private function guardar($id) {
        $data = [
            'categoria_id'     => (int)($_POST['categoria_id'] ?? 0) ?: null,
            'nombre'           => $this->sanitize($_POST['nombre'] ?? ''),
            'slug'             => $this->generarSlug($_POST['nombre'] ?? '', $id),
            'descripcion'      => $_POST['descripcion'] ?? '',
            'descripcion_corta'=> $this->sanitize($_POST['descripcion_corta'] ?? ''),
            'destacado'        => isset($_POST['destacado']) ? 1 : 0,
            'activo'           => isset($_POST['activo']) ? 1 : 0,
            'meta_title'       => $this->sanitize($_POST['meta_title'] ?? ''),
            'meta_desc'        => $this->sanitize($_POST['meta_desc'] ?? ''),
        ];

        if ($id) {
            $this->pm->update($id, $data);
        } else {
            $id = $this->pm->insert($data);
        }

        // Imagen principal
        if (!empty($_FILES['imagen_principal']['name'])) {
            $ruta = $this->subirImagen($_FILES['imagen_principal'], 'products');
            if ($ruta) $this->pm->update($id, ['imagen_principal' => $ruta]);
        }

        // Imágenes adicionales
        if (!empty($_FILES['imagenes']['name'][0])) {
            $db = getDB();
            foreach ($_FILES['imagenes']['tmp_name'] as $i => $tmp) {
                if (!$_FILES['imagenes']['error'][$i]) {
                    $file = ['name' => $_FILES['imagenes']['name'][$i], 'tmp_name' => $tmp, 'error' => 0];
                    $ruta = $this->subirImagen($file, 'products');
                    if ($ruta) $db->prepare("INSERT INTO imagenes_productos (producto_id,ruta) VALUES (?,?)")->execute([$id, $ruta]);
                }
            }
        }

        // Variantes
        if (!empty($_POST['variante_nombre'])) {
            $db = getDB();
            $db->prepare("DELETE FROM variantes_productos WHERE producto_id=?")->execute([$id]);
            foreach ($_POST['variante_nombre'] as $i => $vnom) {
                if (trim($vnom)) {
                    $db->prepare("INSERT INTO variantes_productos (producto_id,nombre,precio,stock,activo) VALUES (?,?,?,?,1)")
                       ->execute([$id, $this->sanitize($vnom), (float)($_POST['variante_precio'][$i] ?? 0), (int)($_POST['variante_stock'][$i] ?? 0)]);
                }
            }
        }

        return $id;
    }

    public function eliminar($id) {
        $this->pm->update($id, ['activo' => 0]);
        $this->redirect('/admin/productos?eliminado=1');
    }

    private function generarSlug($nombre, $id = null) {
        $slug = strtolower(trim(preg_replace('/[^a-z0-9]+/i', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $nombre)), '-'));
        $db = getDB();
        $check = $db->prepare("SELECT id FROM productos WHERE slug=?" . ($id ? " AND id!=?" : ""));
        $params = $id ? [$slug, $id] : [$slug];
        $check->execute($params);
        if ($check->fetch()) $slug .= '-' . time();
        return $slug;
    }

    private function subirImagen($file, $carpeta) {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png','webp'])) return null;
        if ($file['size'] > 5 * 1024 * 1024) return null; // max 5MB
        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, ['image/jpeg','image/png','image/webp'])) return null;
        $nombre = bin2hex(random_bytes(16)) . '.' . $ext;
        $destino = UPLOAD_PATH . $carpeta . '/' . $nombre;
        if (move_uploaded_file($file['tmp_name'], $destino)) {
            return '/public/uploads/' . $carpeta . '/' . $nombre;
        }
        return null;
    }
}
