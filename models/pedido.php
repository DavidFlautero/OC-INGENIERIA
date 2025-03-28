<?php
class Pedido {
    private $id_pedido;
    private $id_usuario;
    private $ciudad_pedido;
    private $direccion_pedido;
    private $zip_pedido;
    private $coste_pedido;
    private $estado_pedido;
    private $fecha_pedido;
    private $db;

    // Constructor: establece la conexión a la base de datos
    public function __construct() {
        $this->db = Database::connect();
    }

    // Getters y Setters
    public function getId_pedido() {
        return $this->id_pedido;
    }

    public function getId_usuario() {
        return $this->id_usuario;
    }

    public function getCiudad_pedido() {
        return $this->ciudad_pedido;
    }

    public function getDireccion_pedido() {
        return $this->direccion_pedido;
    }

    public function getZip_pedido() {
        return $this->zip_pedido;
    }

    public function getCoste_pedido() {
        return $this->coste_pedido;
    }

    public function getEstado_pedido() {
        return $this->estado_pedido;
    }

    public function getFecha_pedido() {
        return $this->fecha_pedido;
    }

    public function setId_pedido($id_pedido) {
        $this->id_pedido = $this->db->real_escape_string($id_pedido);
    }

    public function setId_usuario($id_usuario) {
        $this->id_usuario = $this->db->real_escape_string($id_usuario);
    }

    public function setCiudad_pedido($ciudad_pedido) {
        $this->ciudad_pedido = $this->db->real_escape_string($ciudad_pedido);
    }

    public function setDireccion_pedido($direccion_pedido) {
        $this->direccion_pedido = $this->db->real_escape_string($direccion_pedido);
    }

    public function setZip_pedido($zip_pedido) {
        $this->zip_pedido = $this->db->real_escape_string($zip_pedido);
    }

    public function setCoste_pedido($coste_pedido) {
        $this->coste_pedido = $this->db->real_escape_string($coste_pedido);
    }

    public function setEstado_pedido($estado_pedido) {
        $this->estado_pedido = $this->db->real_escape_string($estado_pedido);
    }

    public function setFecha_pedido($fecha_pedido) {
        $this->fecha_pedido = $this->db->real_escape_string($fecha_pedido);
    }

    // Guardar un nuevo pedido
    public function save() {
        $sql = "INSERT INTO tbl_pedidos VALUES (NULL, {$this->getId_usuario()}, '{$this->getCiudad_pedido()}', '{$this->getDireccion_pedido()}', '{$this->getZip_pedido()}', {$this->getCoste_pedido()}, 'pendiente', CURDATE());";
        $save = $this->db->query($sql);

        if ($save) {
            return $this->db->insert_id; // Devuelve el ID del pedido recién creado
        } else {
            return false;
        }
    }

    // Guardar la relación entre el pedido y los productos
    public function save_relacion() {
        $sql = "";
        foreach ($_SESSION['carrito'] as $producto) {
            $sql .= "INSERT INTO tbl_pedidos_productos VALUES (NULL, {$this->getId_pedido()}, {$producto['id_producto']}, {$producto['unidad_producto']});";
        }

        $save = $this->db->multi_query($sql);

        if ($save) {
            return true;
        } else {
            return false;
        }
    }

    // Obtener todos los pedidos de un usuario
    public function getPedidosByUser() {
        $sql = "SELECT * FROM tbl_pedidos WHERE id_usuario = {$this->getId_usuario()} ORDER BY fecha_pedido DESC;";
        $pedidos = $this->db->query($sql);
        return $pedidos;
    }

    // Obtener un pedido específico por su ID
    public function getOne() {
        $sql = "SELECT * FROM tbl_pedidos WHERE id_pedido = {$this->getId_pedido()};";
        $pedido = $this->db->query($sql);
        return $pedido->fetch_object();
    }

    // Obtener los productos de un pedido específico
    public function getProductosByPedido() {
        $sql = "SELECT p.*, pp.unidades FROM tbl_productos p "
             . "INNER JOIN tbl_pedidos_productos pp ON p.id_producto = pp.id_producto "
             . "WHERE pp.id_pedido = {$this->getId_pedido()};";
        $productos = $this->db->query($sql);
        return $productos;
    }

    // Obtener todos los pedidos (para el administrador)
    public function getAll() {
        $sql = "SELECT * FROM tbl_pedidos ORDER BY fecha_pedido DESC;";
        $pedidos = $this->db->query($sql);
        return $pedidos;
    }

    // Actualizar el estado de un pedido
    public function updateEstado() {
        $sql = "UPDATE tbl_pedidos SET estado_pedido = '{$this->getEstado_pedido()}' WHERE id_pedido = {$this->getId_pedido()};";
        $update = $this->db->query($sql);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    // Obtener estadísticas de ventas (por día, semana, mes)
    public function getVentasByFecha($fecha_inicio, $fecha_fin) {
        $sql = "SELECT SUM(coste_pedido) AS total_ventas FROM tbl_pedidos "
             . "WHERE fecha_pedido BETWEEN '{$fecha_inicio}' AND '{$fecha_fin}';";
        $ventas = $this->db->query($sql);
        return $ventas->fetch_object()->total_ventas;
    }

    // Obtener los productos más vendidos
    public function getProductosMasVendidos() {
        $sql = "SELECT p.nombre_producto, SUM(pp.unidades) AS total_vendido "
             . "FROM tbl_pedidos_productos pp "
             . "INNER JOIN tbl_productos p ON pp.id_producto = p.id_producto "
             . "GROUP BY p.id_producto "
             . "ORDER BY total_vendido DESC "
             . "LIMIT 5;";
        $productos = $this->db->query($sql);
        return $productos;
    }
}