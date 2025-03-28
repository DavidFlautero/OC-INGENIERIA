<?php
require_once '../models/pedido.php';

class PedidoController {

    // Método para la página principal de pedidos (no implementado)
    public function index() {
        echo "Controlador pedidos, Acción index";
    }

    // Método para la gestión de pedidos (solo para administradores)
    public function gestion() {
        Utils::isAdmin(); // Verifica si el usuario es administrador

        $pedido = new Pedido();
        $pedidos = $pedido->getPedidosGestion(); // Obtiene todos los pedidos
        $total = $pedido->getCoste(); // Obtiene el coste total de los pedidos

        require_once 'views/pedido/index.php';
    }
	
    // Método para ver el estado de los pedidos del usuario
    public function estado() {
        if (isset($_SESSION['user'])) {
            $pedido = new Pedido();
            $pedido->setId_usuario($_SESSION['user']->id_usuario);
            $pedidos = $pedido->getPedidosByUser();
            require_once 'views/pedido/estado.php';
        } else {
            header("Location:" . base_url);
        }
    }

    // Método para agregar un nuevo pedido
    public function add() {
        if (isset($_SESSION['user'])) { // Verifica si el usuario está logueado
            // Validar los campos del formulario
            $ciudad_pedido    = isset($_POST['ciudad_pedido'])    ? trim($_POST['ciudad_pedido'])    : false;
            $direccion_pedido = isset($_POST['direccion_pedido']) ? trim($_POST['direccion_pedido']) : false;
            $zip_pedido       = isset($_POST['zip_pedido'])       ? trim($_POST['zip_pedido'])       : false;

            if ($ciudad_pedido && $direccion_pedido && $zip_pedido) {
                $pedido = new Pedido();
                $pedido->setCiudad_pedido($ciudad_pedido);
                $pedido->setDireccion_pedido($direccion_pedido);
                $pedido->setZip_pedido($zip_pedido);
                $pedido->setId_usuario($_SESSION['user']->id_usuario);

                // Calcular el coste total del carrito
                $coste = Utils::statsCarrito();
                $pedido->setCoste_pedido($coste['total']);

                // Guardar el pedido y la relación con los productos
                $save = $pedido->save();
                $savePedido = $pedido->save_relacion();

                if ($save && $savePedido) {
                    $_SESSION['pedido_confirmado'] = true; // Pedido registrado correctamente
                    unset($_SESSION['carrito']); // Vaciar el carrito
                    header("Location:" . base_url . "pedido/confirmado");
                } else {
                    $_SESSION['pedido_error'] = "Error al registrar el pedido";
                    header("Location:" . base_url . "carrito/index");
                }
            } else {
                $_SESSION['pedido_error'] = "Datos del pedido incompletos";
                header("Location:" . base_url . "carrito/index");
            }
        } else {
            header("Location:" . base_url); // Redirigir si el usuario no está logueado
        }
    }

    // Método para mostrar la página de pedido confirmado
    public function confirmado() {
        if (isset($_SESSION['pedido_confirmado'])) {
            unset($_SESSION['pedido_confirmado']); // Limpiar la sesión
            require_once 'views/pedido/confirmado.php';
        } else {
            header("Location:" . base_url); // Redirigir si no hay pedido confirmado
        }
    }

    // Método para listar los pedidos del usuario logueado
    public function misPedidos() {
        if (isset($_SESSION['user'])) {
            $id_usuario = $_SESSION['user']->id_usuario;
            $pedido = new Pedido();
            $pedido->setId_usuario($id_usuario);
            $pedidos = $pedido->getPedidosByUser(); // Obtener los pedidos del usuario

            require_once 'views/pedido/mispedidos.php';
        } else {
            header("Location:" . base_url); // Redirigir si el usuario no está logueado
        }
    }

    // Método para ver el detalle de un pedido
    public function verpedido() {
        if (isset($_GET['id_pedido']) && $_GET['id_pedido'] != '') {
            $id_pedido = $_GET['id_pedido'];
            $pedido = new Pedido();
            $pedido->setId_pedido($id_pedido);
            $pedido_detalle = $pedido->getPedidoSelect(); // Obtener el detalle del pedido

            require_once 'views/pedido/verpedido.php';
        } else {
            header("Location:" . base_url); // Redirigir si no se proporciona un ID de pedido
        }
    }
}
