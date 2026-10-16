<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/OtrosModels.php';
require_once __DIR__ . '/../models/Mailer.php';

class CarritoController extends Controller {
    public function index() {
        $this->render('carrito', [
            'carrito'   => $_SESSION['carrito'] ?? [],
            'pageTitle' => 'Carrito | ' . SITE_NAME,
        ]);
    }

    public function agregar() {
        csrfCheck();
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
        csrfCheck();
        $key = 'v_' . (int)($_POST['variante_id'] ?? 0);
        unset($_SESSION['carrito'][$key]);
        $this->json(['success' => true]);
    }

    public function actualizar() {
        csrfCheck();
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
        $flow = Flow::crearOrden($pedido_id, $total, $email, 'Pedido #' . str_pad($pedido_id, 6, '0', STR_PAD_LEFT) . ' - ' . SITE_NAME, 'pedido');

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
        $cuerpo = "<h2>Bienvenido a " . SITE_NAME . "</h2>";
        $cuerpo .= "<p>Hola <strong>$nombre</strong>,</p>";
        $cuerpo .= "<p>¡Gracias por tu compra! Hemos creado una cuenta para ti.</p>";
        $cuerpo .= "<p>Haz clic en el siguiente enlace para activar tu cuenta y crear tu contraseña:</p>";
        $cuerpo .= "<p><a href='$link' style='background:#4A90A4;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block'>Activar mi cuenta</a></p>";
        $cuerpo .= "<p>O copia este enlace: $link</p>";
        $cuerpo .= "<p><small>Si no realizaste esta compra, ignora este mensaje.</small></p>";
        $cuerpo .= "<p>Saludos,<br>" . SITE_NAME . "</p>";
        
        $mailer = new Mailer();
        $mailer->enviar($email, $asunto, $cuerpo, true);
    }

    private function enviarComprobante($email, $nombre, $pedido_id, $carrito, $total, $descuento) {
        $num    = str_pad($pedido_id, 6, '0', STR_PAD_LEFT);
        $asunto = "Comprobante de pedido #$num | " . SITE_NAME;
        $cuerpo = "<h2>Comprobante de Pedido</h2>";
        $cuerpo .= "<p>Hola <strong>$nombre</strong>,</p>";
        $cuerpo .= "<p>Recibimos tu pedido <strong>#$num</strong>.</p>";
        $cuerpo .= "<h3>Detalles del pedido:</h3>";
        $cuerpo .= "<table style='width:100%;border-collapse:collapse'>";
        $cuerpo .= "<tr style='background:#f5f5f5'><th style='padding:10px;text-align:left;border:1px solid #ddd'>Producto</th><th style='padding:10px;text-align:center;border:1px solid #ddd'>Cantidad</th><th style='padding:10px;text-align:right;border:1px solid #ddd'>Precio</th></tr>";
        
        foreach ($carrito as $item) {
            $subtotalItem = $item['precio'] * $item['cantidad'];
            $cuerpo .= "<tr><td style='padding:10px;border:1px solid #ddd'>{$item['producto_nombre']} ({$item['variante_nombre']})</td>";
            $cuerpo .= "<td style='padding:10px;text-align:center;border:1px solid #ddd'>{$item['cantidad']}</td>";
            $cuerpo .= "<td style='padding:10px;text-align:right;border:1px solid #ddd'>$" . number_format($subtotalItem, 0, ',', '.') . "</td></tr>";
        }
        
        if ($descuento > 0) {
            $cuerpo .= "<tr style='background:#f0f0f0'><td colspan='2' style='padding:10px;border:1px solid #ddd;text-align:right'><strong>Descuento:</strong></td><td style='padding:10px;text-align:right;border:1px solid #ddd;color:green'>-$" . number_format($descuento, 0, ',', '.') . "</td></tr>";
        }
        
        $cuerpo .= "<tr style='background:#4A90A4;color:white'><td colspan='2' style='padding:10px;border:1px solid #ddd;text-align:right'><strong>TOTAL:</strong></td><td style='padding:10px;text-align:right;border:1px solid #ddd'><strong>$" . number_format($total, 0, ',', '.') . "</strong></td></tr>";
        $cuerpo .= "</table>";
        $cuerpo .= "<p style='margin-top:20px'>Nos contactaremos contigo pronto para coordinar el pago y envío.</p>";
        $cuerpo .= "<p>Saludos,<br>" . SITE_NAME . "</p>";
        
        $mailer = new Mailer();
        $mailer->enviar($email, $asunto, $cuerpo, true);
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
        csrfCheck();
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
