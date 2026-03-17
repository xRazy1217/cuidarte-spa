<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/OtrosModels.php';

class CarritoController extends Controller {
    public function index() {
        $this->render('carrito', [
            'carrito'   => $_SESSION['carrito'] ?? [],
            'pageTitle' => 'Carrito | ' . SITE_NAME,
        ]);
    }

    public function agregar() {
        $variante_id = (int)($_POST['variante_id'] ?? 0);
        $cantidad    = max(1, (int)($_POST['cantidad'] ?? 1));

        $pm = new ProductoModel();
        $db = getDB();
        $st = $db->prepare("SELECT v.*, p.nombre as producto_nombre, p.slug, p.imagen_principal
                             FROM variantes_productos v
                             JOIN productos p ON v.producto_id=p.id
                             WHERE v.id=? AND v.activo=1");
        $st->execute([$variante_id]);
        $variante = $st->fetch();

        if (!$variante) $this->json(['error' => 'Variante no encontrada'], 404);

        if (!isset($_SESSION['carrito'])) $_SESSION['carrito'] = [];

        $key = "v_{$variante_id}";
        if (isset($_SESSION['carrito'][$key])) {
            $_SESSION['carrito'][$key]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$key] = [
                'variante_id'     => $variante_id,
                'producto_nombre' => $variante['producto_nombre'],
                'variante_nombre' => $variante['nombre'],
                'precio'          => $variante['precio'],
                'imagen'          => $variante['imagen_principal'],
                'slug'            => $variante['slug'],
                'cantidad'        => $cantidad,
            ];
        }

        $count = array_sum(array_column($_SESSION['carrito'], 'cantidad'));
        $this->json(['success' => true, 'count' => $count]);
    }

    public function eliminar() {
        $key = 'v_' . (int)($_POST['variante_id'] ?? 0);
        unset($_SESSION['carrito'][$key]);
        $this->json(['success' => true]);
    }

    public function actualizar() {
        $key = 'v_' . (int)($_POST['variante_id'] ?? 0);
        $cantidad = max(1, (int)($_POST['cantidad'] ?? 1));
        if (isset($_SESSION['carrito'][$key])) {
            $_SESSION['carrito'][$key]['cantidad'] = $cantidad;
        }
        $this->json(['success' => true]);
    }

    public function checkout() {
        if (empty($_SESSION['carrito'])) $this->redirect('/carrito');

        if ($this->isPost()) {
            $this->procesarPedido();
            return;
        }

        $this->render('checkout', [
            'carrito'   => $_SESSION['carrito'],
            'pageTitle' => 'Checkout | ' . SITE_NAME,
        ]);
    }

    private function procesarPedido() {
        $nombre    = $this->sanitize($_POST['nombre'] ?? '');
        $email     = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $telefono  = $this->sanitize($_POST['telefono'] ?? '');
        $direccion = $this->sanitize($_POST['direccion'] ?? '');
        $cupon_code = $this->sanitize($_POST['cupon'] ?? '');

        $carrito = $_SESSION['carrito'] ?? [];
        if (!$carrito || !$nombre || !$email) $this->redirect('/checkout?error=1');

        // Validar stock antes de procesar
        $db = getDB();
        foreach ($carrito as $item) {
            $st = $db->prepare("SELECT stock FROM variantes_productos WHERE id=? AND activo=1");
            $st->execute([$item['variante_id']]);
            $v = $st->fetch();
            if (!$v || $v['stock'] < $item['cantidad']) {
                $this->redirect('/carrito?error=stock');
            }
        }

        $subtotal  = array_sum(array_map(fn($i) => $i['precio'] * $i['cantidad'], $carrito));
        $descuento = 0;
        $cupon_id  = null;

        if ($cupon_code) {
            $cupon = (new CuponModel())->validar($cupon_code);
            if ($cupon) {
                $cupon_id  = $cupon['id'];
                $descuento = $cupon['tipo'] === 'porcentaje'
                    ? $subtotal * ($cupon['valor'] / 100)
                    : $cupon['valor'];
                (new CuponModel())->usar($cupon_id);
            }
        }

        $total = max(0, $subtotal - $descuento);

        // Asociar pedido a usuario si existe o crear cuenta automática
        $usuario_id = null;
        $db = getDB();

        if (isset($_SESSION['usuario'])) {
            $usuario_id = $_SESSION['usuario']['id'];
        } else {
            $existe = $db->prepare("SELECT id, activo FROM usuarios WHERE email=? LIMIT 1");
            $existe->execute([$email]);
            $user = $existe->fetch();

            if ($user) {
                $usuario_id = $user['id'];
            } else {
                // Crear cuenta inactiva con token de activación
                $token = bin2hex(random_bytes(32));
                $db->prepare(
                    "INSERT INTO usuarios (nombre, email, password, rol, activo, token_activacion) VALUES (?, ?, NULL, 'cliente', 0, ?)"
                )->execute([$nombre, $email, $token]);
                $usuario_id = $db->lastInsertId();
                $this->enviarActivacion($email, $nombre, $token);
            }
        }

        $pm = new PedidoModel();
        $pedido_id = $pm->insert([
            'usuario_id'     => $usuario_id,
            'nombre_cliente' => $nombre,
            'email_cliente'  => $email,
            'telefono'       => $telefono,
            'direccion'      => $direccion,
            'total'          => $total,
            'descuento'      => $descuento,
            'cupon_id'       => $cupon_id,
            'estado'         => 'pendiente',
        ]);

        foreach ($carrito as $item) {
            $pm->insertDetalle([
                'pedido_id'       => $pedido_id,
                'variante_id'     => $item['variante_id'],
                'producto_nombre' => $item['producto_nombre'],
                'variante_nombre' => $item['variante_nombre'],
                'cantidad'        => $item['cantidad'],
                'precio_unitario' => $item['precio'],
            ]);
        }

        $this->enviarComprobante($email, $nombre, $pedido_id, $carrito, $total, $descuento);

        unset($_SESSION['carrito']);

        // Redirigir a Flow
        require_once __DIR__ . '/../models/Flow.php';
        $flow = Flow::crearOrden($pedido_id, $total, $email, 'Pedido #' . str_pad($pedido_id, 6, '0', STR_PAD_LEFT) . ' - ' . SITE_NAME);

        if (!empty($flow['url']) && !empty($flow['token'])) {
            header('Location: ' . $flow['url'] . '?token=' . $flow['token']);
            exit;
        } else {
            $this->redirect('/pedido/gracias/' . $pedido_id);
        }
    }

    private function enviarActivacion($email, $nombre, $token) {
        $link   = BASE_URL . '/activar-cuenta/' . $token;
        $asunto = 'Activa tu cuenta en ' . SITE_NAME;
        $msg    = "Hola $nombre,\n\nGracias por tu compra. Hemos creado una cuenta para ti.\n"
                . "Haz clic en el siguiente enlace para activarla y crear tu contraseña:\n\n$link\n\n"
                . "Si no realizaste esta compra, ignora este mensaje.\n\n" . SITE_NAME;
        @mail($email, $asunto, $msg, 'From: ' . config('email_contacto'));
    }

    private function enviarComprobante($email, $nombre, $pedido_id, $carrito, $total, $descuento) {
        $num    = str_pad($pedido_id, 6, '0', STR_PAD_LEFT);
        $asunto = "Comprobante de pedido #$num | " . SITE_NAME;
        $items  = '';
        foreach ($carrito as $item) {
            $subtotalItem = $item['precio'] * $item['cantidad'];
            $items .= "- {$item['producto_nombre']} ({$item['variante_nombre']}) x{$item['cantidad']}: $" . number_format($subtotalItem, 0, ',', '.') . "\n";
        }
        if ($descuento > 0) $items .= "  Descuento: -$" . number_format($descuento, 0, ',', '.') . "\n";
        $msg = "Hola $nombre,\n\nRecibimos tu pedido #$num.\n\n$items\nTOTAL: $" . number_format($total, 0, ',', '.') . "\n\n"
             . "Nos contactaremos contigo para coordinar el pago y envío.\n\n" . SITE_NAME;
        @mail($email, $asunto, $msg, 'From: ' . config('email_contacto'));
    }

    public function datos() {
        $items = array_values($_SESSION['carrito'] ?? []);
        $this->json(['items' => $items]);
    }

    public function gracias($id) {
        $pedido = (new PedidoModel())->getConDetalle($id);
        $this->render('pedido_gracias', [
            'pedido'    => $pedido,
            'pageTitle' => '¡Pedido recibido! | ' . SITE_NAME,
        ]);
    }

    public function validarCupon() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') $this->json(['error' => 'Método no permitido'], 405);
        csrfVerify();
        $codigo   = $this->sanitize($_POST['codigo'] ?? '');
        $subtotal = (float)($_POST['subtotal'] ?? 0);
        $cupon    = (new CuponModel())->validar($codigo);
        if (!$cupon) $this->json(['error' => 'Cupón inválido o expirado']);
        $descuento = $cupon['tipo'] === 'porcentaje'
            ? $subtotal * ($cupon['valor'] / 100)
            : (float)$cupon['valor'];
        $this->json(['success' => true, 'descuento' => $descuento, 'tipo' => $cupon['tipo'], 'valor' => $cupon['valor']]);
    }
}
