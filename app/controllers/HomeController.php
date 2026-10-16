<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/ProductoModel.php';
require_once __DIR__ . '/../models/OtrosModels.php';

class HomeController extends Controller {
    public function index() {
        $pm = new ProductoModel();
        $sm = new ServicioModel();
        $bm = new BlogModel();
        $this->render('home', [
            'destacados'  => $pm->getDestacados(),
            'servicios'   => $sm->getActivos(),
            'articulos'   => $bm->getPublicados(3),
            'pageTitle'   => config('meta_title_home'),
            'pageDesc'    => config('meta_desc_home'),
        ]);
    }

    public function nosotros() {
        $this->render('nosotros', [
            'pageTitle' => 'Quiénes Somos | Terapia RAI y Crianza Consciente en La Serena | ' . SITE_NAME,
            'pageDesc'  => 'Conoce a Patricia y Elizabeth, fundadoras de CuidArte. Especialistas en Terapia RAI, Aromaterapia y Pedagogía Waldorf en La Serena, Chile.',
        ]);
    }
}
