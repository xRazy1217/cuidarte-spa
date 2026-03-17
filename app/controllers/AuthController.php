<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Model.php';

class AuthController extends Controller {
    public function login() {
        if (isset($_SESSION['usuario'])) $this->redirect('/admin');

        $error = '';
        if ($this->isPost()) {
            // Rate limiting básico: máx 5 intentos por 15 minutos
            $intentos = $_SESSION['login_intentos'] ?? 0;
            $ultimo   = $_SESSION['login_ultimo'] ?? 0;
            if ($intentos >= 5 && (time() - $ultimo) < 900) {
                $error = 'Demasiados intentos. Espera 15 minutos.';
            } else {
                if ((time() - $ultimo) >= 900) $_SESSION['login_intentos'] = 0;

                $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
                $pass  = $_POST['password'] ?? '';
                $st = getDB()->prepare("SELECT * FROM usuarios WHERE email=? AND activo=1 LIMIT 1");
                $st->execute([$email]);
                $user = $st->fetch();

                if ($user && password_verify($pass, $user['password'])) {
                    session_regenerate_id(true); // prevenir session fixation
                    $_SESSION['login_intentos'] = 0;
                    $_SESSION['usuario'] = [
                        'id'     => $user['id'],
                        'nombre' => $user['nombre'],
                        'rol'    => $user['rol'],
                    ];
                    $this->redirect($user['rol'] === 'admin' ? '/admin' : '/');
                } else {
                    $_SESSION['login_intentos'] = $intentos + 1;
                    $_SESSION['login_ultimo']   = time();
                    $error = 'Credenciales incorrectas.';
                }
            }
        }
        $this->render('login', ['error' => $error, 'pageTitle' => 'Iniciar Sesión | ' . SITE_NAME]);
    }

    public function logout() {
        session_regenerate_id(true);
        session_destroy();
        $this->redirect('/login');
    }

    public function registro() {
        $error = '';
        if ($this->isPost()) {
            $nombre = $this->sanitize($_POST['nombre'] ?? '');
            $email  = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
            $pass   = $_POST['password'] ?? '';

            if (!$nombre || !$email || strlen($pass) < 6) {
                $error = 'Completa todos los campos. La contraseña debe tener al menos 6 caracteres.';
            } else {
                $db = getDB();
                $existe = $db->prepare("SELECT id FROM usuarios WHERE email=?");
                $existe->execute([$email]);
                if ($existe->fetch()) {
                    $error = 'El email ya está registrado.';
                } else {
                    $db->prepare("INSERT INTO usuarios (nombre,email,password,rol) VALUES (?,?,?,'cliente')")
                       ->execute([$nombre, $email, password_hash($pass, PASSWORD_DEFAULT)]);
                    $this->redirect('/login?registro=ok');
                }
            }
        }
        $this->render('registro', ['error' => $error, 'pageTitle' => 'Registro | ' . SITE_NAME]);
    }
}
