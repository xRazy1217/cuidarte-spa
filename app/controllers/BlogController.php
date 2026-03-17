<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/OtrosModels.php';

class BlogController extends Controller {
    private $bm;

    public function __construct() {
        $this->bm = new BlogModel();
    }

    public function index() {
        $pagina = max(1, (int)($_GET['pagina'] ?? 1));
        $porPagina = 6;
        $offset = ($pagina - 1) * $porPagina;
        $total = $this->bm->count('publicado=1');
        $totalPaginas = ceil($total / $porPagina);

        $this->render('blog', [
            'articulos'      => $this->bm->getPublicados($porPagina, $offset),
            'pagina_actual'  => $pagina,
            'total_paginas'  => $totalPaginas,
            'pageTitle'      => 'Blog | ' . SITE_NAME,
        ]);
    }

    public function detalle($slug) {
        $articulo = $this->bm->getBySlug($slug);
        if (!$articulo) { http_response_code(404); echo "Artículo no encontrado"; return; }

        $this->render('articulo', [
            'articulo'  => $articulo,
            'pageTitle' => $articulo['titulo'] . ' | ' . SITE_NAME,
            'pageDesc'  => $articulo['resumen'],
        ]);
    }
}
