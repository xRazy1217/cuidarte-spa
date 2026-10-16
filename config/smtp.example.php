<?php
/**
 * Copiar a config/smtp.php (NO se versiona) y completar con valores propios.
 */
return [
    'host'       => 'mail.tu-dominio.cl',
    'port'       => 465,
    'username'   => 'correo@tu-dominio.cl',
    'password'   => 'CAMBIAR',
    'from_email' => 'correo@tu-dominio.cl',
    'from_name'  => 'Cuidarte Spa',
    'encryption' => 'ssl', // 'ssl' (465) o 'tls' (587)
    'debug'      => 0,
];
