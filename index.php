<?php
require_once __DIR__ . '/config/database.php';
session_start();

// URL secreta para acceso admin (cambiar en producción)
define('ADMIN_SECRET_URL', 'gestion-privada');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
$uri = substr($uri, strlen($base));
$uri = trim($uri, '/');
$segments = $uri ? explode('/', $uri) : [];

$controller = $segments[0] ?? 'home';
$action     = $segments[1] ?? 'index';
$param      = $segments[2] ?? null;

// Ruta secreta de login admin
if ($controller === ADMIN_SECRET_URL) {
    require_once __DIR__ . '/app/controllers/AuthController.php';
    (new AuthController())->login();
    exit;
}

// Rutas admin
if ($controller === 'admin') {
    $adminCtrl = $segments[1] ?? 'dashboard';
    $adminAction = $segments[2] ?? 'index';
    $adminParam  = $segments[3] ?? null;

    if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'admin') {
        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    // Try AdminController file first, then plain Controller file
    $file      = __DIR__ . '/app/controllers/admin/' . ucfirst($adminCtrl) . 'AdminController.php';
    $fileAlt   = __DIR__ . '/app/controllers/admin/' . ucfirst($adminCtrl) . 'Controller.php';
    $fileFound = file_exists($file) ? $file : (file_exists($fileAlt) ? $fileAlt : null);

    if ($fileFound) {
        require_once $fileFound;
        $class = ucfirst($adminCtrl) . 'AdminController';
        if (!class_exists($class)) $class = ucfirst($adminCtrl) . 'Controller';
        $obj = new $class();
        if (method_exists($obj, $adminAction)) $obj->$adminAction($adminParam);
        else $obj->index();
    } else {
        require_once __DIR__ . '/app/controllers/admin/DashboardController.php';
        (new DashboardController())->index();
    }
    exit;
}

// Rutas públicas
$map = [
    'home'       => ['HomeController',       'index'],
    ''           => ['HomeController',       'index'],
    'productos'  => ['ProductosController',  'index'],
    'producto'   => ['ProductosController',  'detalle'],
    'servicios'  => ['ServiciosController',  'index'],
    'reservar'   => ['ReservasController',   'index'],
    'blog'       => ['BlogController',       'index'],
    'articulo'   => ['BlogController',       'detalle'],
    'nosotros'   => ['HomeController',       'nosotros'],
    'contacto'   => ['ContactoController',   'index'],
    'carrito'    => ['CarritoController',    'index'],
    'checkout'   => ['CarritoController',    'checkout'],
    'pedido'     => ['CarritoController',    'gracias'],
    'login'      => ['AuthController',       'login'],
    'logout'     => ['AuthController',       'logout'],
    'registro'   => ['AuthController',       'registro'],
    'mi-cuenta'  => ['ClienteController',    'index'],
    'activar-cuenta' => ['ClienteController','activar'],
    'pago'       => ['PagoController',       'index'],
];

// Rutas cliente con sub-acciones
if ($controller === 'mi-cuenta') {
    require_once __DIR__ . '/app/controllers/ClienteController.php';
    $obj = new ClienteController();
    $metodos = ['pedidos', 'reservas', 'perfil', 'ingresar', 'logoutCliente', 'recuperar', 'registrarse'];
    if ($action === 'logout') $obj->logoutCliente();
    elseif ($action === 'nueva-password') $obj->nuevaPassword($param);
    elseif ($action === 'registrarse') $obj->registrarse();
    elseif ($action === 'solicitar-reembolso') $obj->solicitarReembolso($param);
    elseif (in_array($action, $metodos)) $obj->$action();
    else $obj->index();
    exit;
}

if ($controller === 'activar-cuenta') {
    require_once __DIR__ . '/app/controllers/ClienteController.php';
    (new ClienteController())->activar($action);
    exit;
}

if ($controller === 'pago') {
    require_once __DIR__ . '/app/controllers/PagoController.php';
    $obj = new PagoController();
    if ($action === 'confirmar') $obj->confirmar();
    elseif ($action === 'retorno') $obj->retorno();
    exit;
}

// Controladores cuyo segundo segmento es siempre un slug
$slugRoutes = ['producto', 'articulo'];

// Normalizar action con guiones a camelCase (ej: validar-cupon → validarCupon)
$actionCamel = lcfirst(str_replace(' ', '', ucwords(str_replace('-', ' ', $action))));

$route = $map[$controller] ?? null;

if (!$route) {
    http_response_code(404);
    require_once __DIR__ . '/app/controllers/HomeController.php';
    (new HomeController())->render('404', ['pageTitle' => 'Página no encontrada | ' . SITE_NAME]);
    exit;
}

$ctrlFile = __DIR__ . '/app/controllers/' . $route[0] . '.php';

if (file_exists($ctrlFile)) {
    require_once $ctrlFile;
    $obj = new $route[0]();
    if (in_array($controller, $slugRoutes)) {
        $obj->detalle($action);
    } elseif (method_exists($obj, $actionCamel) && $actionCamel !== 'index') {
        $obj->$actionCamel($param);
    } else {
        $obj->{$route[1]}($param);
    }
} else {
    http_response_code(404);
    require_once __DIR__ . '/app/controllers/HomeController.php';
    (new HomeController())->render('404', ['pageTitle' => 'Página no encontrada | ' . SITE_NAME]);
}
