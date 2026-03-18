<?php
class Flow {

    private static function firmar(array $params): string {
        ksort($params);
        $cadena = '';
        foreach ($params as $k => $v) $cadena .= $k . $v;
        return hash_hmac('sha256', $cadena, FLOW_SECRET_KEY);
    }

    public static function crearOrden(int $id, float $monto, string $email, string $concepto, string $tipo = 'pedido'): array {
        $urlConfirmacion = $tipo === 'reserva'
            ? BASE_URL . '/pago/confirmar-reserva'
            : BASE_URL . '/pago/confirmar';
        $urlRetorno = $tipo === 'reserva'
            ? BASE_URL . '/pago/retorno-reserva'
            : BASE_URL . '/pago/retorno';

        $params = [
            'apiKey'          => FLOW_API_KEY,
            'commerceOrder'   => $tipo . '_' . $id,
            'subject'         => $concepto,
            'currency'        => 'CLP',
            'amount'          => (int)round($monto),
            'email'           => $email,
            'urlConfirmation' => $urlConfirmacion,
            'urlReturn'       => $urlRetorno,
        ];
        $params['s'] = self::firmar($params);

        $ch = curl_init(FLOW_API_URL . '/payment/create');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => ENVIRONMENT !== 'development',
        ]);
        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) error_log('Flow curl error: ' . $err);
        return json_decode($resp, true) ?? ['_curl_error' => $err, '_response' => $resp];
    }

    public static function reembolsar(string $token, float $monto, string $concepto): array {
        $params = [
            'apiKey'  => FLOW_API_KEY,
            'refundCommerceOrder' => uniqid('ref_'),
            'receiverEmail'      => '',
            'returnAmount'       => (int)round($monto),
            'originalFlowOrder'  => $token,
            'comment'            => $concepto,
        ];
        $params['s'] = self::firmar($params);

        $ch = curl_init(FLOW_API_URL . '/refund/create');
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($params),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => ENVIRONMENT !== 'development',
        ]);
        $resp = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($err) error_log('Flow refund error: ' . $err);
        return json_decode($resp, true) ?? ['_curl_error' => $err];
    }

    public static function obtenerEstado(string $token): array {
        $params = [
            'apiKey' => FLOW_API_KEY,
            'token'  => $token,
        ];
        $params['s'] = self::firmar($params);

        $url = FLOW_API_URL . '/payment/getStatus?' . http_build_query($params);
        $ch  = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        return json_decode($resp, true) ?? [];
    }
}
