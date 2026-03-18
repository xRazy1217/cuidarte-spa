<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Flow.php';
require_once __DIR__ . '/../models/OtrosModels.php';

class PagoController extends Controller {

    // Flow llama a esta URL por POST para confirmar el pago (servidor a servidor)
    public function confirmar() {
        $token = $_POST['token'] ?? '';
        if (!$token) { http_response_code(400); exit; }

        $estado = Flow::obtenerEstado($token);

        // status 2 = pagado en Flow
        if (($estado['status'] ?? 0) == 2) {
            $pedidoId = (int)($estado['commerceOrder'] ?? 0);
            if ($pedidoId) {
                $db = getDB();
                $db->prepare("UPDATE pedidos SET estado='pagado', flow_token=? WHERE id=? AND estado='pendiente'")
                   ->execute([$token, $pedidoId]);
            }
        }

        echo 'OK';
        exit;
    }

    // Flow redirige al cliente aquí después de pagar
    public function retorno() {
        $token = $_GET['token'] ?? '';
        if (!$token) {
            $this->render('pago_fallido', [
                'pageTitle' => 'Pago no completado | ' . SITE_NAME,
                'tipo'      => 'pedido',
                'id'        => null,
            ]);
            return;
        }

        $estado = Flow::obtenerEstado($token);
        $commerceOrder = $estado['commerceOrder'] ?? '';
        $pedidoId = (int)$commerceOrder;

        if (($estado['status'] ?? 0) == 2) {
            $this->redirect('/pedido/gracias/' . $pedidoId);
        } else {
            $this->render('pago_fallido', [
                'pageTitle' => 'Pago no completado | ' . SITE_NAME,
                'tipo'      => 'pedido',
                'id'        => $pedidoId ?: null,
            ]);
        }
    }

    // Webhook Flow para reservas
    public function confirmarReserva() {
        $token = $_POST['token'] ?? '';
        if (!$token) { http_response_code(400); exit; }

        $estado = Flow::obtenerEstado($token);

        if (($estado['status'] ?? 0) == 2) {
            $commerceOrder = $estado['commerceOrder'] ?? '';
            $reservaId = (int)str_replace('reserva_', '', $commerceOrder);
            if ($reservaId) {
                $db = getDB();
                $reserva = $db->prepare("SELECT * FROM reservas WHERE id=? LIMIT 1");
                $reserva->execute([$reservaId]);
                $r = $reserva->fetch();
                $db->prepare("UPDATE reservas SET estado='confirmada', flow_token=? WHERE id=? AND estado='pendiente'")
                   ->execute([$token, $reservaId]);
                // Enviar email de confirmación
                if ($r) {
                    $this->enviarConfirmacionReserva($r['email_cliente'], $r['nombre_cliente'], $reservaId, $r['monto']);
                }
            }
        }

        echo 'OK';
        exit;
    }

    // Flow redirige al cliente después de pagar reserva
    public function retornoReserva() {
        $token = $_GET['token'] ?? '';
        if (!$token) {
            $this->render('pago_fallido', [
                'pageTitle' => 'Pago no completado | ' . SITE_NAME,
                'tipo'      => 'reserva',
                'id'        => null,
            ]);
            return;
        }

        $estado = Flow::obtenerEstado($token);
        $commerceOrder = $estado['commerceOrder'] ?? '';
        $reservaId = (int)str_replace('reserva_', '', $commerceOrder);

        if (($estado['status'] ?? 0) == 2) {
            $this->redirect('/reservar/confirmacion/' . $reservaId);
        } else {
            $this->render('pago_fallido', [
                'pageTitle' => 'Pago no completado | ' . SITE_NAME,
                'tipo'      => 'reserva',
                'id'        => $reservaId ?: null,
            ]);
        }
    }

    private function enviarConfirmacionReserva($email, $nombre, $reservaId, $monto) {
        $num    = str_pad($reservaId, 6, '0', STR_PAD_LEFT);
        $asunto = "Reserva confirmada #$num | " . SITE_NAME;
        $msg    = "Hola $nombre,\n\nTu pago fue recibido y tu reserva #$num está confirmada.\n"
                . "Monto pagado: $" . number_format($monto, 0, ',', '.') . "\n\n"
                . "Nos contactaremos contigo para coordinar los detalles.\n\n" . SITE_NAME;
        @mail($email, $asunto, $msg, 'From: ' . config('email_contacto'));
    }
}
