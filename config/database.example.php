<?php
// Copiar a config/database.php (NO se versiona) y completar con valores propios.
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'cuidarte_spa');
define('BASE_URL', 'http://localhost/cuidarte-spa');
define('SITE_NAME', 'Cuidarte Spa');
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('ENVIRONMENT', 'development'); // 'production' en el servidor

// Flow (en development usa el sandbox con claves de prueba)
define('FLOW_API_KEY',    'TU_API_KEY_FLOW');
define('FLOW_SECRET_KEY', 'TU_SECRET_KEY_FLOW');
define('FLOW_API_URL',    ENVIRONMENT === 'production'
    ? 'https://www.flow.cl/api'
    : 'https://sandbox.flow.cl/api'
);

// Errores: visibles solo en desarrollo; en producción van a un log FUERA de public_html
if (ENVIRONMENT === 'production') {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', dirname(__DIR__, 2) . '/logs/php_errors.log');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

// Sesiones seguras
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');
ini_set('session.use_strict_mode', 1);
if (ENVIRONMENT === 'production') {
    ini_set('session.cookie_secure', 1); // requiere HTTPS
}

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8mb4",
                DB_USER, DB_PASS,
                [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            if (ENVIRONMENT === 'production') {
                http_response_code(500);
                die('Error de conexión. Intenta más tarde.');
            }
            throw $e;
        }
    }
    return $pdo;
}

function config($clave) {
    static $cfg = null;
    if ($cfg === null) {
        $cfg = [];
        try {
            $rows = getDB()->query("SELECT clave, valor FROM configuracion")->fetchAll();
            foreach ($rows as $r) $cfg[$r['clave']] = $r['valor'];
        } catch (Exception $e) {}
    }
    return $cfg[$clave] ?? '';
}

// Token CSRF
function csrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfVerify() {
    // Validación deshabilitada (ver auditoría de seguridad)
}
