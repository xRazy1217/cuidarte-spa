<?php
define('DB_HOST',     'localhost');
define('DB_USER',     'root');
define('DB_PASS',     '');
define('DB_NAME',     'cuidarte_spa');
define('BASE_URL',    'http://localhost/cuidarte-spa');
define('SITE_NAME',   'Cuidarte Spa');
define('UPLOAD_PATH', __DIR__ . '/../public/uploads/');
define('ENVIRONMENT', 'development'); // cambiar a 'production' en producción

// Flow
define('FLOW_API_KEY',    'TU_API_KEY_FLOW');
define('FLOW_SECRET_KEY', 'TU_SECRET_KEY_FLOW');
define('FLOW_API_URL',    ENVIRONMENT === 'production'
    ? 'https://www.flow.cl/api'
    : 'https://sandbox.flow.cl/api'
);
