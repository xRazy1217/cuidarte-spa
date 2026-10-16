<?php
require_once __DIR__ . '/../Controller.php';
require_once __DIR__ . '/../../models/OtrosModels.php';

class ServiciosAdminController extends Controller {
    public function index() {
        try {
            $servicios = (new ServicioModel())->all();
        } catch (Exception $e) {
            $servicios = [];
        }
        $this->render('servicios_lista', [
            'servicios' => $servicios,
            'pageTitle' => 'Servicios | Admin'
        ], 'admin');
    }

    public function crear() {
        if ($this->isPost()) {
            $nombre = $this->sanitize($_POST['nombre'] ?? '');
            $descripcion = $_POST['descripcion'] ?? '';
            $duracion = (int)($_POST['duracion_minutos'] ?? 60);
            $precio = (float)($_POST['precio'] ?? 0);
            $precio_presencial = (float)($_POST['precio_presencial'] ?? 0);
            $modalidad = $_POST['modalidad'] ?? 'ambas';

            if (!$nombre || !$descripcion) {
                $this->redirect('/admin/servicios?error=campos_requeridos');
            }

            $imagen = '';
            if (!empty($_FILES['imagen']['name'])) {
                $imagen = $this->subirImagen($_FILES['imagen'], 'services');
            }

            $db = getDB();
            $db->prepare(
                "INSERT INTO servicios (nombre, descripcion, duracion_minutos, precio, precio_presencial, modalidad, imagen, activo) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, 1)"
            )->execute([$nombre, $descripcion, $duracion, $precio, $precio_presencial, $modalidad, $imagen]);

            $this->redirect('/admin/servicios?success=creado');
        }

        $this->render('servicio_form', [
            'servicio' => null,
            'pageTitle' => 'Crear Servicio | Admin'
        ], 'admin');
    }

    public function editar($id) {
        $servicio = (new ServicioModel())->find($id);
        if (!$servicio) $this->redirect('/admin/servicios?error=no_encontrado');

        if ($this->isPost()) {
            $nombre = $this->sanitize($_POST['nombre'] ?? '');
            $descripcion = $_POST['descripcion'] ?? '';
            $duracion = (int)($_POST['duracion_minutos'] ?? 60);
            $precio = (float)($_POST['precio'] ?? 0);
            $precio_presencial = (float)($_POST['precio_presencial'] ?? 0);
            $modalidad = $_POST['modalidad'] ?? 'ambas';

            if (!$nombre || !$descripcion) {
                $this->redirect("/admin/servicios?error=campos_requeridos");
            }

            $imagen = $servicio['imagen'];
            if (!empty($_FILES['imagen']['name'])) {
                $imagen = $this->subirImagen($_FILES['imagen'], 'services');
            }

            getDB()->prepare(
                "UPDATE servicios SET nombre=?, descripcion=?, duracion_minutos=?, precio=?, precio_presencial=?, modalidad=?, imagen=? WHERE id=?"
            )->execute([$nombre, $descripcion, $duracion, $precio, $precio_presencial, $modalidad, $imagen, $id]);

            $this->redirect('/admin/servicios?success=actualizado');
        }

        $this->render('servicio_form', [
            'servicio' => $servicio,
            'pageTitle' => 'Editar Servicio | Admin'
        ], 'admin');
    }

    public function eliminar($id) {
        $servicio = (new ServicioModel())->find($id);
        if (!$servicio) $this->redirect('/admin/servicios?error=no_encontrado');

        getDB()->prepare("DELETE FROM servicios WHERE id=?")->execute([$id]);
        $this->redirect('/admin/servicios?success=eliminado');
    }

    private function subirImagen($file, $carpeta) {
        if (empty($file['tmp_name'])) return '';
        
        $contenido = file_get_contents($file['tmp_name']);
        if ($contenido === false) return '';
        
        // Convertir a base64
        $base64 = base64_encode($contenido);
        return $base64;
    }
}
