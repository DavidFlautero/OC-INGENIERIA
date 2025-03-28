<?php
require_once '../models/producto.php';
require_once '../models/pedido.php';
require_once '../models/reclamo.php';

class AdminController {

    // Método para mostrar el dashboard del administrador
    public function index() {
        Utils::isAdmin(); // Verificar que el usuario sea administrador
        require_once 'views/admin/index.php';
    }

    // Método para gestionar ofertas
    public function gestionOfertas() {
        Utils::isAdmin();
        $producto = new Producto();
        $productos = $producto->getAll(); // Obtener todos los productos
        require_once 'views/admin/gestion_ofertas.php';
    }

    // Método para agregar un producto a las ofertas
    public function agregarOferta() {
        Utils::isAdmin();
        if (isset($_GET['id_producto'])) {
            $id_producto = $_GET['id_producto'];
            $producto = new Producto();
            $producto->setId_producto($id_producto);
            $producto->agregarOferta(); // Método para marcar el producto como oferta
            header("Location:" . base_url . "admin/gestionOfertas");
        }
    }

    // Método para ver reclamos
    public function verReclamos() {
        Utils::isAdmin();
        $reclamo = new Reclamo();
        $reclamos = $reclamo->getAll(); // Obtener todos los reclamos
        require_once 'views/admin/ver_reclamos.php';
    }

    // Método para aprobar o rechazar un reclamo
    public function gestionReclamo() {
        Utils::isAdmin();
        if (isset($_GET['id_reclamo']) && isset($_GET['estado'])) {
            $id_reclamo = $_GET['id_reclamo'];
            $estado = $_GET['estado']; // "aprobado" o "rechazado"
            $reclamo = new Reclamo();
            $reclamo->setId_reclamo($id_reclamo);
            $reclamo->setEstado($estado);
            $reclamo->gestionar(); // Método para actualizar el estado del reclamo
            header("Location:" . base_url . "admin/verReclamos");
        }
    }

    // Método para ver estadísticas de ventas
    public function estadisticas() {
        Utils::isAdmin();
        $pedido = new Pedido();
        $ventas_semana = $pedido->getVentasSemana();
        $ventas_mes = $pedido->getVentasMes();
        $productos_mas_vendidos = $pedido->getProductosMasVendidos();
        require_once 'views/admin/estadisticas.php';
    }
}