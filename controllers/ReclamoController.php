<?php
require_once '../models/reclamo.php';
require_once '../models/pedido.php';

class ReclamoController {

    // Método para mostrar el formulario de reclamo
    public function crear() {
        if (isset($_SESSION['user'])) {
            require_once 'views/reclamo/crear.php';
        } else {
            header("Location:" . base_url);
        }
    }

    // Método para guardar un reclamo
    public function save() {
        if (isset($_SESSION['user']) && isset($_POST)) {
            $id_pedido = $_POST['id_pedido'];
            $id_producto = $_POST['id_producto'];
            $descripcion = $_POST['descripcion'];
            $imagen = $_FILES['imagen'];
            $video = $_FILES['video'];

            $reclamo = new Reclamo();
            $reclamo->setId_pedido($id_pedido);
            $reclamo->setId_producto($id_producto);
            $reclamo->setDescripcion($descripcion);
            $reclamo->setId_usuario($_SESSION['user']->id_usuario);

            // Guardar la imagen y el video
            if ($imagen['name'] != '') {
                $imagen_name = uniqid() . '_' . $imagen['name'];
                move_uploaded_file($imagen['tmp_name'], 'uploads/reclamos/' . $imagen_name);
                $reclamo->setImagen($imagen_name);
            }

            if ($video['name'] != '') {
                $video_name = uniqid() . '_' . $video['name'];
                move_uploaded_file($video['tmp_name'], 'uploads/reclamos/' . $video_name);
                $reclamo->setVideo($video_name);
            }

            $save = $reclamo->save();
            if ($save) {
                $_SESSION['reclamo'] = "complete";
            } else {
                $_SESSION['reclamo'] = "failed";
            }
            header("Location:" . base_url . "reclamo/crear");
        } else {
            header("Location:" . base_url);
        }
    }

    // Método para ver el estado de un reclamo
    public function estado() {
        if (isset($_SESSION['user'])) {
            $reclamo = new Reclamo();
            $reclamo->setId_usuario($_SESSION['user']->id_usuario);
            $reclamos = $reclamo->getReclamosByUser();
            require_once 'views/reclamo/estado.php';
        } else {
            header("Location:" . base_url);
        }
    }
}