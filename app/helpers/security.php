<?php
/**
 * Utilidades de seguridad versionadas (CSRF y límite de intentos).
 * Viven aquí y no en config/database.php porque ese archivo no está en git.
 */

/**
 * Verifica el token CSRF de una petición POST.
 * Modo por defecto: SOLO REGISTRA (no bloquea) los POST sin token válido en el error_log,
 * para detectar formularios sin token antes de activar el bloqueo.
 * Para bloquear: define('CSRF_ENFORCE', true) en config/database.php.
 */
function csrfCheck() {
    $enviado  = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
    $esperado = $_SESSION['csrf_token'] ?? '';
    if ($esperado !== '' && is_string($enviado) && hash_equals($esperado, $enviado)) return true;

    $uri = preg_replace('/[^\x20-\x7e]/', '', parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '');
    error_log('[CSRF] POST sin token válido: ' . $uri);
    if (defined('CSRF_ENFORCE') && CSRF_ENFORCE) {
        http_response_code(419);
        die('Sesión expirada o solicitud no válida. Recarga la página e intenta de nuevo.');
    }
    return false;
}

function clientIp() {
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0'; // en SiteGround REMOTE_ADDR es la IP real del cliente
}

function _rlFile($key) {
    $dir = sys_get_temp_dir() . '/cuidarte_rl';
    if (!is_dir($dir)) @mkdir($dir, 0700, true);
    return $dir . '/' . hash('sha256', $key) . '.json';
}

/** ¿Superó $max intentos en los últimos $ventana segundos? */
function rlBloqueado($key, $max = 10, $ventana = 900) {
    $f = _rlFile($key);
    if (!is_file($f)) return false;
    $ts = json_decode((string)@file_get_contents($f), true) ?: [];
    $ts = array_filter($ts, fn($t) => $t > time() - $ventana);
    return count($ts) >= $max;
}

/** Registra un intento fallido. */
function rlRegistrar($key, $ventana = 900) {
    $f  = _rlFile($key);
    $fp = @fopen($f, 'c+');
    if (!$fp) return;
    flock($fp, LOCK_EX);
    $ts = json_decode((string)stream_get_contents($fp), true) ?: [];
    $ts = array_values(array_filter($ts, fn($t) => $t > time() - $ventana));
    $ts[] = time();
    ftruncate($fp, 0); rewind($fp); fwrite($fp, json_encode($ts));
    flock($fp, LOCK_UN); fclose($fp);
}

function rlLimpiar($key) {
    $f = _rlFile($key);
    if (is_file($f)) @unlink($f);
}
