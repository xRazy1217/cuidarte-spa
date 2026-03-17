<?php
require_once __DIR__ . '/../models/OtrosModels.php';

class Controller {
    protected function render($view, $data = [], $layout = 'public') {
        // Inject serviciosModal globally for the reservation modal in layout
        if ($layout === 'public' && !isset($data['serviciosModal'])) {
            try {
                $data['serviciosModal'] = (new ServicioModel())->getActivos();
            } catch (Exception $e) {
                $data['serviciosModal'] = [];
            }
        }
        // Restrict view and layout to alphanumeric + underscore to prevent path traversal
        $view   = preg_replace('/[^a-zA-Z0-9_\/]/', '', $view);
        $layout = preg_replace('/[^a-zA-Z0-9_]/', '', $layout);

        $viewFile   = __DIR__ . "/../views/{$layout}/{$view}.php";
        $layoutFile = __DIR__ . "/../views/layouts/{$layout}.php";

        // Ensure resolved paths stay within the views directory
        $viewsBase = realpath(__DIR__ . '/../views');
        if (!$viewsBase
            || strpos(realpath($viewFile) ?: '', $viewsBase) !== 0
            || strpos(realpath($layoutFile) ?: '', $viewsBase) !== 0
        ) {
            http_response_code(404);
            echo 'Página no encontrada';
            return;
        }

        extract($data);
        ob_start();
        require $viewFile;
        // $content is internal rendered HTML, never from user input
        $content = ob_get_clean();
        require $layoutFile;
    }

    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
        exit;
    }

    protected function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function isPost() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return false;
        csrfVerify();
        return true;
    }

    protected function sanitize($val) {
        return htmlspecialchars(strip_tags(trim($val)));
    }
}
