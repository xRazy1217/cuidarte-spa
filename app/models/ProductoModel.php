<?php
require_once __DIR__ . '/Model.php';

class ProductoModel extends Model {
    protected $table = 'productos';

    public function getAllConVariantes($soloActivos = true) {
        $where = $soloActivos ? 'p.activo=1' : '1';
        $sql = "SELECT p.*, c.nombre as categoria_nombre
                FROM productos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE $where ORDER BY p.created_at DESC";
        return $this->db->query($sql)->fetchAll();
    }

    public function getDestacados() {
        $sql = "SELECT p.*, MIN(v.precio) as precio_desde
                FROM productos p
                LEFT JOIN variantes_productos v ON v.producto_id = p.id AND v.activo=1
                WHERE p.activo=1 AND p.destacado=1
                GROUP BY p.id LIMIT 6";
        return $this->db->query($sql)->fetchAll();
    }

    public function getBySlug($slug) {
        $st = $this->db->prepare(
            "SELECT p.*, c.nombre as categoria_nombre
             FROM productos p
             LEFT JOIN categorias c ON p.categoria_id = c.id
             WHERE p.slug=? AND p.activo=1 LIMIT 1"
        );
        $st->execute([$slug]);
        return $st->fetch();
    }

    public function getVariantes($producto_id) {
        $st = $this->db->prepare("SELECT * FROM variantes_productos WHERE producto_id=? AND activo=1 ORDER BY precio");
        $st->execute([$producto_id]);
        return $st->fetchAll();
    }

    public function getImagenes($producto_id) {
        $st = $this->db->prepare("SELECT * FROM imagenes_productos WHERE producto_id=? ORDER BY orden");
        $st->execute([$producto_id]);
        return $st->fetchAll();
    }

    public function getAll() {
        return $this->db->query(
            "SELECT p.*, c.nombre as categoria_nombre, MIN(v.precio) as precio_desde
             FROM productos p
             LEFT JOIN categorias c ON p.categoria_id=c.id
             LEFT JOIN variantes_productos v ON v.producto_id=p.id AND v.activo=1
             WHERE p.activo=1
             GROUP BY p.id ORDER BY p.nombre"
        )->fetchAll();
    }

    public function getAllAdmin() {
        return $this->db->query(
            "SELECT p.*, c.nombre as categoria_nombre
             FROM productos p
             LEFT JOIN categorias c ON p.categoria_id=c.id
             ORDER BY p.created_at DESC"
        )->fetchAll();
    }
}
