<?php
require_once '../models/categoria.php';
require_once '../models/producto.php';

class CategoriaController {

    // Método para la gestión de categorías (solo para administradores)
    public function gestion() {
        Utils::isAdmin(); // Verifica si el usuario es administrador

        $categoria = new Categoria();
        $categorias = $categoria->getCategoria(); // Obtiene todas las categorías

        require_once 'views/categoria/index.php';
    }

    // Método para mostrar el formulario de creación de categorías (solo para administradores)
    public function crear() {
        Utils::isAdmin(); // Verifica si el usuario es administrador
        require_once 'views/categoria/crear.php';
    }

    // Método para ver los productos de una categoría específica
    public function ver() {
        if (isset($_GET['id_categoria'])) {
            $id_categoria = $_GET['id_categoria'];

            // Obtener la categoría seleccionada
            $categoria = new Categoria();
            $categoria->setId_categoria($id_categoria);
            $categoria = $categoria->getSelectCategoria();

            // Obtener los productos de la categoría
            $producto = new Producto();
            $producto->setId_categoria($id_categoria);
            $productos = $producto->getProductosForCat();

            require_once 'views/categoria/ver.php';
        } else {
            header("Location:" . base_url); // Redirigir si no se proporciona un ID de categoría
        }
    }

    // Método para guardar una nueva categoría (solo para administradores)
    public function save() {
        Utils::isAdmin(); // Verifica si el usuario es administrador

        if (isset($_POST) && isset($_POST['nameCategoria'])) {
            $nombre_categoria = trim($_POST['nameCategoria']); // Eliminar espacios en blanco

            if (!empty($nombre_categoria)) {
                $categoria = new Categoria();
                $categoria->setNombre_categoria($nombre_categoria);
                $save = $categoria->save();

                if ($save) {
                    $_SESSION['register'] = "complete"; // Categoría guardada correctamente
                } else {
                    $_SESSION['register'] = "failed"; // Error al guardar la categoría
                }
            } else {
                $_SESSION['register'] = "failed"; // Nombre de categoría vacío
            }
        } else {
            $_SESSION['register'] = "failed"; // Datos no recibidos
        }

        header("Location:" . base_url . "categoria/gestion"); // Redirigir a la gestión de categorías
    }
}