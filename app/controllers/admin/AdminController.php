<?php
require_once __DIR__ . '/../Controller.php';

class AdminController extends Controller {
    protected function render($view, $data = [], $layout = 'admin') {
        parent::render($view, $data, $layout);
    }
}
