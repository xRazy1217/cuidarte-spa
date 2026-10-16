<?php
/**
 * Clase Mailer - Gestor de correos simple
 * Archivo: app/models/Mailer.php
 */

class Mailer {
    private $config;
    private $error = '';

    public function __construct() {
        $this->config = require __DIR__ . '/../../config/smtp.php';
    }

    /**
     * Enviar correo simple
     * @param string $destino Email del destinatario
     * @param string $asunto Asunto del correo
     * @param string $cuerpo Cuerpo del correo (HTML o texto)
     * @param bool $es_html Si es HTML o texto plano
     * @return bool
     */
    public function enviar($destino, $asunto, $cuerpo, $es_html = true) {
        try {
            // Validar email
            if (!filter_var($destino, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido: $destino");
            }

            // Headers
            $headers = "From: " . $this->config['from_email'] . "\r\n";
            $headers .= "Reply-To: " . $this->config['from_email'] . "\r\n";
            $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
            
            if ($es_html) {
                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
            } else {
                $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            }

            // Enviar
            $resultado = @mail($destino, $asunto, $cuerpo, $headers);
            
            // No lanzar excepción si falla, solo registrar
            if (!$resultado) {
                error_log("Email no enviado a: $destino");
            }

            return true;

        } catch (Exception $e) {
            $this->error = "Error enviando correo: " . $e->getMessage();
            error_log($this->error);
            return false;
        }
    }

    /**
     * Enviar correo a múltiples destinatarios
     * @param array $destinatarios Array de emails
     * @param string $asunto
     * @param string $cuerpo
     * @param bool $es_html
     * @return bool
     */
    public function enviarMultiple($destinatarios, $asunto, $cuerpo, $es_html = true) {
        foreach ($destinatarios as $email) {
            if (!$this->enviar($email, $asunto, $cuerpo, $es_html)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Obtener último error
     */
    public function getError() {
        return $this->error;
    }
}
