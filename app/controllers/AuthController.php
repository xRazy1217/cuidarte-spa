<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Model.php';
require_once __DIR__ . '/../models/Mailer.php';

class AuthController extends Controller {
    public function login() {
        if (isset($_SESSION['usuario'])) $this->redirect('/admin');

        $error = '';
        if ($this->isPost()) {
            // Rate limiting básico: máx 5 intentos por 15 minutos
            $intentos = $_SESSION['login_intentos'] ?? 0;
            $ultimo   = $_SESSION['login_ultimo'] ?? 0;
            $rlKey = 'admin-login:' . clientIp();
            if (rlBloqueado($rlKey, 10, 900)) {
                $error = 'Demasiados intentos. Espera 15 minutos.';
            } elseif ($intentos >= 5 && (time() - $ultimo) < 900) {
                $error = 'Demasiados intentos. Espera 15 minutos.';
            } else {
                if ((time() - $ultimo) >= 900) $_SESSION['login_intentos'] = 0;

                $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
                $pass  = $_POST['password'] ?? '';
                $st = getDB()->prepare("SELECT * FROM usuarios WHERE email=? AND activo=1 LIMIT 1");
                $st->execute([$email]);
                $user = $st->fetch();

                if ($user && password_verify($pass, $user['password'])) {
                    session_regenerate_id(false); // regenerar ID sin destruir datos
                    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // regenerar token CSRF
                    $_SESSION['login_intentos'] = 0;
                    rlLimpiar($rlKey);
                    $_SESSION['usuario'] = [
                        'id'     => $user['id'],
                        'nombre' => $user['nombre'],
                        'rol'    => $user['rol'],
                    ];
                    $this->redirect($user['rol'] === 'admin' ? '/admin' : '/');
                } else {
                    rlRegistrar($rlKey, 900);
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

    public function activar($token) {
        if (!$token) $this->redirect('/login');
        
        $db = getDB();
        $st = $db->prepare("SELECT id FROM usuarios WHERE token_activacion=? AND activo=0 LIMIT 1");
        $st->execute([$token]);
        $usuario = $st->fetch();
        
        if ($usuario) {
            $db->prepare("UPDATE usuarios SET activo=1, token_activacion=NULL WHERE id=?")->execute([$usuario['id']]);
            $this->redirect('/login?activado=1');
        } else {
            $this->redirect('/login?error=token_invalido');
        }
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
                    // Generar token de activación
                    $token = bin2hex(random_bytes(32));
                    $db->prepare("INSERT INTO usuarios (nombre,email,password,rol,activo,token_activacion) VALUES (?,?,?,'cliente',0,?)")
                       ->execute([$nombre, $email, password_hash($pass, PASSWORD_DEFAULT), $token]);
                    
                    // Enviar email de activación
                    $enlace_activacion = BASE_URL . '/activar-cuenta/' . $token;
                    $asunto = "Confirma tu email en " . SITE_NAME;
                    $cuerpo = "<h2>Bienvenido a " . SITE_NAME . "</h2>";
                    $cuerpo .= "<p>Hola <strong>$nombre</strong>,</p>";
                    $cuerpo .= "<p>Para activar tu cuenta, haz click en el siguiente enlace:</p>";
                    $cuerpo .= "<p><a href='$enlace_activacion' style='background:#4A90A4;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;display:inline-block'>Activar mi cuenta</a></p>";
                    $cuerpo .= "<p>O copia este enlace en tu navegador:</p>";
                    $cuerpo .= "<p>$enlace_activacion</p>";
                    $cuerpo .= "<p><small>Este enlace expira en 24 horas.</small></p>";
                    $cuerpo .= "<p>Saludos,<br>" . SITE_NAME . "</p>";
                    
                    $mailer = new Mailer();
                    $mailer->enviar($email, $asunto, $cuerpo, true);
                    
                    $this->redirect('/login?registro=ok&email_enviado=1');
                }
            }
        }
        $this->render('registro', ['error' => $error, 'pageTitle' => 'Registro | ' . SITE_NAME]);
    }
}
