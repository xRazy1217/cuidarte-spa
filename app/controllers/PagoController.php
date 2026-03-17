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
        if (!$token) $this->redirect('/carrito');

        $estado = Flow::obtenerEstado($token);
        $pedidoId = (int)($estado['commerceOrder'] ?? 0);

        if (($estado['status'] ?? 0) == 2) {
            // Pago exitoso
            $this->redirect('/pedido/gracias/' . $pedidoId);
        } else {
            // Pago fallido o cancelado
            $this->redirect('/checkout?error=pago');
        }
    }
}
