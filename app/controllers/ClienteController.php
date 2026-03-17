<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/OtrosModels.php';

class ClienteController extends Controller {

    private function requireLogin() {
        if (empty($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] ?? '') !== 'cliente') {
            $this->redirect('/mi-cuenta/ingresar');
        }
    }

    public function registrarse() {
        if (!empty($_SESSION['usuario']) && ($_SESSION['usuario']['rol'] ?? '') === 'cliente') {
            $this->redirect('/mi-cuenta');
        }
        $error = $ok = '';
        if ($this->isPost()) {
            $nombre = $this->sanitize($_POST['nombre'] ?? '');
            $email  = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $pass   = $_POST['password'] ?? '';
            if (!$nombre || !$email || strlen($pass) < 6) {
                $error = 'Completa todos los campos. La contraseña debe tener al menos 6 caracteres.';
            } else {
                $db = getDB();
                $st = $db->prepare("SELECT id FROM usuarios WHERE email=? LIMIT 1");
                $st->execute([$email]);
                if ($st->fetch()) {
                    $error = 'Ya existe una cuenta con ese email.';
                } else {
                    $db->prepare("INSERT INTO usuarios (nombre, email, password, rol, activo) VALUES (?,?,?,'cliente',1)")
                       ->execute([$nombre, $email, password_hash($pass, PASSWORD_DEFAULT)]);
                    session_regenerate_id(true);
                    $id = $db->lastInsertId();
                    $_SESSION['usuario'] = ['id' => $id, 'nombre' => $nombre, 'email' => $email, 'rol' => 'cliente'];
                    $this->redirect('/mi-cuenta');
                }
            }
        }
        $this->render('registrarse', ['error' => $error, 'pageTitle' => 'Crear cuenta | ' . SITE_NAME], 'public');
    }

    public function ingresar() {
        if (!empty($_SESSION['usuario']) && ($_SESSION['usuario']['rol'] ?? '') === 'cliente') {
            $this->redirect('/mi-cuenta');
        }

        $error = '';
        if ($this->isPost()) {
            $email = $this->sanitize($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';
            $db    = getDB();
            $st    = $db->prepare("SELECT * FROM usuarios WHERE email=? AND rol='cliente' LIMIT 1");
            $st->execute([$email]);
            $user  = $st->fetch();

            if (!$user) {
                $error = 'Email no encontrado.';
            } elseif (!$user['activo']) {
                $error = 'Tu cuenta no está activada. Revisa tu email.';
            } elseif (!password_verify($pass, $user['password'] ?? '')) {
                $error = 'Contraseña incorrecta.';
            } else {
                session_regenerate_id(true);
                $_SESSION['usuario'] = [
                    'id'     => $user['id'],
                    'nombre' => $user['nombre'],
                    'email'  => $user['email'],
                    'rol'    => 'cliente',
                ];
                $next = $this->sanitize($_GET['next'] ?? '');
                $this->redirect($next ?: '/mi-cuenta');
            }
        }

        $this->render('login_cliente', [
            'error'     => $error,
            'pageTitle' => 'Ingresar | ' . SITE_NAME,
        ], 'public');
    }

    public function logoutCliente() {
        unset($_SESSION['usuario']);
        session_regenerate_id(true);
        $this->redirect('/mi-cuenta/ingresar');
    }

    public function recuperar() {
        $ok = $error = '';
        if ($this->isPost()) {
            $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $db    = getDB();
            $st    = $db->prepare("SELECT id, nombre FROM usuarios WHERE email=? AND rol='cliente' LIMIT 1");
            $st->execute([$email]);
            $user  = $st->fetch();
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $db->prepare("UPDATE usuarios SET token_activacion=? WHERE id=?")->execute([$token, $user['id']]);
                $link  = BASE_URL . '/mi-cuenta/nueva-password/' . $token;
                $msg   = "Hola {$user['nombre']},\n\nRecibimos una solicitud para restablecer tu contraseña.\nHaz clic aquí:\n\n$link\n\nSi no solicitaste esto, ignora este mensaje.\n\n" . SITE_NAME;
                @mail($email, 'Restablecer contraseña | ' . SITE_NAME, $msg, 'From: ' . config('email_contacto'));
            }
            // Siempre mostrar éxito para no revelar si el email existe
            $ok = 'Si el email existe, recibirás un enlace en tu correo.';
        }
        $this->render('recuperar_password', ['ok' => $ok, 'error' => $error, 'pageTitle' => 'Recuperar contraseña | ' . SITE_NAME], 'public');
    }

    public function nuevaPassword($token) {
        $db  = getDB();
        $st  = $db->prepare("SELECT * FROM usuarios WHERE token_activacion=? LIMIT 1");
        $st->execute([$token]);
        $user = $st->fetch();
        $error = '';
        if (!$user) {
            $this->render('recuperar_password', ['error' => 'Link inválido o expirado.', 'pageTitle' => 'Recuperar contraseña | ' . SITE_NAME], 'public');
            return;
        }
        if ($this->isPost()) {
            $pass = $_POST['password'] ?? '';
            if (strlen($pass) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } else {
                $db->prepare("UPDATE usuarios SET password=?, token_activacion=NULL, activo=1 WHERE id=?")
                   ->execute([password_hash($pass, PASSWORD_DEFAULT), $user['id']]);
                session_regenerate_id(true);
                $_SESSION['usuario'] = ['id' => $user['id'], 'nombre' => $user['nombre'], 'email' => $user['email'], 'rol' => 'cliente'];
                $this->redirect('/mi-cuenta?ok=password');
            }
        }
        $this->render('nueva_password', ['token' => $token, 'error' => $error, 'pageTitle' => 'Nueva contraseña | ' . SITE_NAME], 'public');
    }

    public function index() {
        $this->requireLogin();
        $this->redirect('/mi-cuenta/perfil');
    }

    public function pedidos() {
        $this->requireLogin();
        $st = getDB()->prepare("SELECT * FROM pedidos WHERE usuario_id=? ORDER BY created_at DESC");
        $st->execute([$_SESSION['usuario']['id']]);
        $this->render('pedidos', [
            'pedidos'   => $st->fetchAll(),
            'pageTitle' => 'Mis Compras | ' . SITE_NAME,
        ], 'public');
    }

    public function reservas() {
        $this->requireLogin();
        $st = getDB()->prepare(
            "SELECT r.*, s.nombre as servicio_nombre, h.fecha, h.hora_inicio
             FROM reservas r
             JOIN servicios s ON r.servicio_id=s.id
             JOIN horarios_disponibles h ON r.horario_id=h.id
             WHERE r.email_cliente=?
             ORDER BY h.fecha DESC"
        );
        $st->execute([$_SESSION['usuario']['email'] ?? '']);
        $this->render('reservas_cliente', [
            'reservas'  => $st->fetchAll(),
            'pageTitle' => 'Mis Reservas | ' . SITE_NAME,
        ], 'public');
    }

    public function perfil() {
        $this->requireLogin();
        $error = $ok = '';

        if ($this->isPost()) {
            $nombre     = $this->sanitize($_POST['nombre'] ?? '');
            $telefono   = $this->sanitize($_POST['telefono'] ?? '');
            $passActual = $_POST['password_actual'] ?? '';
            $passNueva  = $_POST['password_nueva'] ?? '';
            $db         = getDB();
            $user       = $db->prepare("SELECT * FROM usuarios WHERE id=? LIMIT 1");
            $user->execute([$_SESSION['usuario']['id']]);
            $user = $user->fetch();

            $data = ['nombre' => $nombre, 'telefono' => $telefono];

            if ($passNueva) {
                if (!$passActual || !password_verify($passActual, $user['password'] ?? '')) {
                    $error = 'La contraseña actual no es correcta.';
                } elseif (strlen($passNueva) < 6) {
                    $error = 'La nueva contraseña debe tener al menos 6 caracteres.';
                } else {
                    $data['password'] = password_hash($passNueva, PASSWORD_DEFAULT);
                }
            }

            if (!$error) {
                $set = implode(',', array_map(fn($k) => "$k=?", array_keys($data)));
                $db->prepare("UPDATE usuarios SET $set WHERE id=?")
                   ->execute([...array_values($data), $_SESSION['usuario']['id']]);
                $_SESSION['usuario']['nombre'] = $nombre;
                $ok = 'Perfil actualizado correctamente.';
            }
        }

        $user = getDB()->query("SELECT * FROM usuarios WHERE id=" . (int)$_SESSION['usuario']['id'])->fetch();
        $this->render('perfil', [
            'user'      => $user,
            'error'     => $error,
            'ok'        => $ok,
            'pageTitle' => 'Mi Perfil | ' . SITE_NAME,
        ], 'public');
    }

    public function solicitarReembolso($id) {
        $this->requireLogin();
        if (!$this->isPost()) $this->redirect('/mi-cuenta/pedidos');

        $motivo = $this->sanitize($_POST['motivo'] ?? '');
        if (!$motivo) $this->redirect('/mi-cuenta/pedidos?error=motivo');

        $db = getDB();
        $st = $db->prepare("SELECT id FROM pedidos WHERE id=? AND usuario_id=? AND estado='pagado' LIMIT 1");
        $st->execute([(int)$id, $_SESSION['usuario']['id']]);

        if ($st->fetch()) {
            $db->prepare("UPDATE pedidos SET solicitud_reembolso=1, motivo_reembolso=? WHERE id=?")
               ->execute([$motivo, (int)$id]);
        }

        $this->redirect('/mi-cuenta/pedidos?reembolso=solicitado');
    }

    public function activar($token) {
        $db  = getDB();
        $st  = $db->prepare("SELECT * FROM usuarios WHERE token_activacion=? AND activo=0 LIMIT 1");
        $st->execute([$token]);
        $user = $st->fetch();

        if (!$user) {
            $this->render('activar', ['error' => true, 'pageTitle' => 'Activar Cuenta | ' . SITE_NAME], 'public');
            return;
        }

        if ($this->isPost()) {
            $pass = $_POST['password'] ?? '';
            if (strlen($pass) < 6) {
                $this->render('activar', ['token' => $token, 'error_pass' => true, 'pageTitle' => 'Activar Cuenta | ' . SITE_NAME], 'public');
                return;
            }
            $db->prepare("UPDATE usuarios SET password=?, activo=1, token_activacion=NULL WHERE id=?")
               ->execute([password_hash($pass, PASSWORD_DEFAULT), $user['id']]);
            session_regenerate_id(true);
            $_SESSION['usuario'] = ['id' => $user['id'], 'nombre' => $user['nombre'], 'rol' => 'cliente', 'email' => $user['email']];
            $this->redirect('/mi-cuenta?activada=1');
        }

        $this->render('activar', ['token' => $token, 'nombre' => $user['nombre'], 'pageTitle' => 'Activar Cuenta | ' . SITE_NAME], 'public');
    }
}
