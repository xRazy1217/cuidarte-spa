<?php
require_once __DIR__ . '/Model.php';

class PedidoModel extends Model {
    protected $table = 'pedidos';

    public function getConDetalle($id) {
        $pedido = $this->find($id);
        if (!$pedido) return null;
        $st = $this->db->prepare(
            "SELECT d.*, v.nombre as variante_nombre, p.nombre as producto_nombre, p.imagen_principal
             FROM detalle_pedidos d
             JOIN variantes_productos v ON d.variante_id = v.id
             JOIN productos p ON v.producto_id = p.id
             WHERE d.pedido_id = ?"
        );
        $st->execute([$id]);
        $pedido['items'] = $st->fetchAll();
        return $pedido;
    }

    public function insertDetalle($data) {
        $this->db->prepare(
            "INSERT INTO detalle_pedidos (pedido_id,variante_id,producto_nombre,variante_nombre,cantidad,precio_unitario)
             VALUES (?,?,?,?,?,?)"
        )->execute(array_values($data));
    }

    public function getRecientes($limit = 10) {
        return $this->db->query(
            "SELECT * FROM pedidos ORDER BY created_at DESC LIMIT $limit"
        )->fetchAll();
    }

    public function totalHoy() {
        return $this->db->query(
            "SELECT COALESCE(SUM(total),0) FROM pedidos WHERE DATE(created_at)=CURDATE() AND estado='pagado'"
        )->fetchColumn();
    }
}

class ReservaModel extends Model {
    protected $table = 'reservas';

    public function getHorarios($servicio_id, $fecha) {
        $st = $this->db->prepare(
            "SELECT * FROM horarios_disponibles
             WHERE servicio_id=? AND fecha=? AND disponible=1
             AND cupos_ocupados < cupo_maximo
             ORDER BY hora_inicio"
        );
        $st->execute([$servicio_id, $fecha]);
        return $st->fetchAll();
    }

    public function bloquearHorario($horario_id) {
        // Increment occupied slots; mark unavailable only when full
        $this->db->prepare(
            "UPDATE horarios_disponibles
             SET cupos_ocupados = cupos_ocupados + 1,
                 disponible = IF(cupos_ocupados + 1 >= cupo_maximo, 0, 1)
             WHERE id=?"
        )->execute([$horario_id]);
    }

    public function getRecientes($limit = 10) {
        return $this->db->query(
            "SELECT r.*, s.nombre as servicio_nombre, h.fecha, h.hora_inicio
             FROM reservas r
             JOIN servicios s ON r.servicio_id=s.id
             JOIN horarios_disponibles h ON r.horario_id=h.id
             ORDER BY r.created_at DESC LIMIT $limit"
        )->fetchAll();
    }
}

class BlogModel extends Model {
    protected $table = 'blog';

    public function getPublicados($limit = 10, $offset = 0) {
        return $this->db->query(
            "SELECT * FROM blog WHERE publicado=1 ORDER BY created_at DESC LIMIT $limit OFFSET $offset"
        )->fetchAll();
    }

    public function getBySlug($slug) {
        $st = $this->db->prepare("SELECT * FROM blog WHERE slug=? AND publicado=1 LIMIT 1");
        $st->execute([$slug]);
        return $st->fetch();
    }
}

class CuponModel extends Model {
    protected $table = 'cupones';

    public function validar($codigo) {
        $st = $this->db->prepare(
            "SELECT * FROM cupones WHERE UPPER(codigo)=UPPER(?) AND activo=1
             AND (fecha_expiracion IS NULL OR fecha_expiracion >= CURDATE())
             AND (uso_maximo=0 OR usos_actuales < uso_maximo) LIMIT 1"
        );
        $st->execute([$codigo]);
        return $st->fetch();
    }

    public function usar($id) {
        $this->db->prepare("UPDATE cupones SET usos_actuales=usos_actuales+1 WHERE id=?")->execute([$id]);
    }
}

class ServicioModel extends Model {
    protected $table = 'servicios';

    public function getActivos() {
        return $this->all('activo=1');
    }

    public function getPrecio($servicio_id, $modalidad = 'online') {
        $s = $this->find($servicio_id);
        if (!$s) return 0;
        return $modalidad === 'presencial' && $s['precio_presencial']
            ? $s['precio_presencial']
            : $s['precio'];
    }
}

class CategoriaModel extends Model {
    protected $table = 'categorias';
}
