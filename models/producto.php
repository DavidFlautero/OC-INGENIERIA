<?php
require_once __DIR__ . '/../config/db.php';

/**
 * Clase Producto - Modelo para gestión de productos
 */
class Producto {
    
    // Constantes para valores por defecto
    const DEFAULT_LIMIT = 10;
    const DEFAULT_OFFSET = 0;
    const STOCK_UMBRAL = 10;
    const MAX_DESTACADOS = 10;
    const MAX_MAS_VENDIDOS = 5;

    private $id_producto;
    private $id_subcategoria;
    private $nombre_producto;
    private $descripcion_producto;
    private $precio_producto;
    private $costo;
    private $stock_producto;
    private $oferta_producto;
    private $fecha_producto;
    private $destacado;
    private $estado;
    private $codigo_externo;
    private $proveedor;
    private $margen_minimo;
    private $porcentaje_descuento;
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        if ($this->db->connect_error) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    // ==================== GETTERS Y SETTERS ====================

    /**
     * @return int
     */
    public function getId_producto() { 
        return $this->id_producto; 
    }

    /**
     * @param int $id
     */
    public function setId_producto($id) { 
        $this->id_producto = $this->db->real_escape_string($id); 
    }

    /**
     * @return int
     */
    public function getId_subcategoria() { 
        return $this->id_subcategoria; 
    }

    /**
     * @param int $id_subcategoria
     */
    public function setId_subcategoria($id_subcategoria) {
        $this->id_subcategoria = $this->db->real_escape_string($id_subcategoria);
    }

    /**
     * @return string
     */
    public function getNombre_producto() { 
        return $this->nombre_producto; 
    }

    /**
     * @param string $nombre_producto
     */
    public function setNombre_producto($nombre_producto) {
        $this->nombre_producto = $this->db->real_escape_string($nombre_producto);
    }

    /**
     * @return string
     */
    public function getDescripcion_producto() { 
        return $this->descripcion_producto; 
    }

    /**
     * @param string $descripcion_producto
     */
    public function setDescripcion_producto($descripcion_producto) {
        $this->descripcion_producto = $this->db->real_escape_string($descripcion_producto);
    }

    /**
     * @return float
     */
    public function getPrecio_producto() { 
        return $this->precio_producto; 
    }

    /**
     * @param float $precio_producto
     */
    public function setPrecio_producto($precio_producto) {
        $this->precio_producto = $this->db->real_escape_string($precio_producto);
    }

    /**
     * @return float
     */
    public function getCosto() { 
        return $this->costo; 
    }

    /**
     * @param float $costo
     */
    public function setCosto($costo) { 
        $this->costo = $this->db->real_escape_string($costo); 
    }

    /**
     * @return int
     */
    public function getStock_producto() { 
        return $this->stock_producto; 
    }

    /**
     * @param int $stock_producto
     */
    public function setStock_producto($stock_producto) {
        $this->stock_producto = $this->db->real_escape_string($stock_producto);
    }

    /**
     * @return string
     */
    public function getOferta_producto() { 
        return $this->oferta_producto; 
    }

    /**
     * @param string $oferta_producto
     */
    public function setOferta_producto($oferta_producto) {
        $this->oferta_producto = $this->db->real_escape_string($oferta_producto);
    }

    /**
     * @return string
     */
    public function getFecha_producto() { 
        return $this->fecha_producto; 
    }

    /**
     * @param string $fecha_producto
     */
    public function setFecha_producto($fecha_producto) {
        $this->fecha_producto = $this->db->real_escape_string($fecha_producto);
    }

    /**
     * @return string
     */
    public function getDestacado() { 
        return $this->destacado; 
    }

    /**
     * @param string $destacado
     */
    public function setDestacado($destacado) {
        $this->destacado = $this->db->real_escape_string($destacado);
    }

    /**
     * @return string
     */
    public function getEstado() { 
        return $this->estado; 
    }

    /**
     * @param string $estado
     */
    public function setEstado($estado) {
        $this->estado = $this->db->real_escape_string($estado);
    }
    
    /**
     * @return string
     */
    public function getCodigoExterno() { 
        return $this->codigo_externo; 
    }

    /**
     * @param string $codigo
     */
    public function setCodigoExterno($codigo) {
        $this->codigo_externo = $this->db->real_escape_string($codigo);
    }

    /**
     * @return string
     */
    public function getProveedor() { 
        return $this->proveedor; 
    }

    /**
     * @param string $proveedor
     */
    public function setProveedor($proveedor) {
        $this->proveedor = $this->db->real_escape_string($proveedor);
    }

    /**
     * @return float
     */
    public function getMargen_minimo() { 
        return $this->margen_minimo; 
    }

    /**
     * @param float $margen
     */
    public function setMargen_minimo($margen) { 
        $this->margen_minimo = $this->db->real_escape_string($margen); 
    }

    /**
     * @return float
     */
    public function getPorcentaje_descuento() { 
        return $this->porcentaje_descuento; 
    }

    /**
     * @param float $descuento
     */
    public function setPorcentaje_descuento($descuento) { 
        $this->porcentaje_descuento = $this->db->real_escape_string($descuento); 
    }

    // ==================== MÉTODOS PRINCIPALES ====================

    /**
     * Guarda un nuevo producto en la base de datos
     * @return int ID del producto insertado
     * @throws Exception Si hay errores de validación o en la base de datos
     */
    public function save() {
        // Validaciones
		  if ($this->precio_producto <= 0) {
        throw new Exception("El precio debe ser mayor que cero");
		}
        if (empty($this->getId_subcategoria())) { 
            throw new Exception("Debes seleccionar una subcategoría.");
        }
        if (empty($this->getNombre_producto())) {
            throw new Exception("El nombre del producto es obligatorio.");
        }
        if (empty($this->getPrecio_producto())) {
            throw new Exception("El precio es obligatorio.");
        }

        if (!is_numeric($this->getPrecio_producto()) || $this->getPrecio_producto() <= 0) {
            throw new Exception("El precio debe ser un número positivo.");
        }
        if (!is_numeric($this->getStock_producto()) || $this->getStock_producto() < 0) {
            throw new Exception("El stock debe ser un número positivo o cero.");
        }

        $stmt = $this->db->prepare("
            INSERT INTO tbl_productos (
                id_subcategoria, nombre_producto, descripcion_producto, 
                precio_producto, stock_producto, oferta_producto, 
                fecha_producto, destacado, estado, costo,
                codigo_externo, proveedor, margen_minimo, porcentaje_descuento
            ) VALUES (?, ?, ?, ?, ?, ?, CURDATE(), ?, 'active', ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "issdssssssdd",
            $this->id_subcategoria,
            $this->nombre_producto,
            $this->descripcion_producto,
            $this->precio_producto,
            $this->stock_producto,
            $this->oferta_producto,
            $this->destacado,
            $this->costo,
            $this->codigo_externo,
            $this->proveedor,
            $this->margen_minimo,
            $this->porcentaje_descuento
        );
        
        if ($stmt->execute()) {
            return $this->db->insert_id;
        } else {
            throw new Exception("Error al guardar producto: " . $stmt->error);
        }
    }

    /**
     * Cambia el estado de un producto (active/paused)
     * @param int $id_producto
     * @return bool
     * @throws Exception
     */
    public function toggleEstado($id_producto) {
        $stmt = $this->db->prepare("
            UPDATE tbl_productos 
            SET estado = IF(estado = 'active', 'paused', 'active') 
            WHERE id_producto = ?
        ");
        $stmt->bind_param("i", $id_producto);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al cambiar estado: " . $stmt->error);
        }
        return true;
    }

    /**
     * Elimina un producto
     * @return bool
     * @throws Exception
     */
    public function delete() {
        $stmt = $this->db->prepare("DELETE FROM tbl_productos WHERE id_producto = ?");
        $stmt->bind_param("i", $this->id_producto);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar producto: " . $stmt->error);
        }
        return true;
    }

    // ==================== MÉTODOS DE STOCK Y PRECIOS ====================

    /**
     * Actualiza stock y costos de un producto con transacción
     * @param int $idProducto
     * @param int $cantidad
     * @param float $precioCompra
     * @param float|null $precioVenta
     * @throws Exception
     */
    public function actualizarStockYCostos($idProducto, $cantidad, $precioCompra, $precioVenta = null) {
        if (!is_numeric($cantidad)) {
            throw new Exception("La cantidad debe ser numérica");
        }
        if (!is_numeric($precioCompra) || $precioCompra < 0) {
            throw new Exception("El precio de compra debe ser un número positivo");
        }

        $this->db->begin_transaction();
        
        try {
            // Actualizar stock y costo
            $stmt = $this->db->prepare("
                UPDATE tbl_productos 
                SET 
                    stock_producto = stock_producto + ?,
                    costo = ?
                WHERE id_producto = ?
            ");
            $stmt->bind_param("idi", $cantidad, $precioCompra, $idProducto);
            $stmt->execute();
            
            // Si se especifica precio_venta, actualizarlo
            if ($precioVenta !== null) {
                if (!is_numeric($precioVenta) || $precioVenta < 0) {
                    throw new Exception("El precio de venta debe ser un número positivo");
                }

                $stmt = $this->db->prepare("
                    UPDATE tbl_productos 
                    SET precio_producto = ?
                    WHERE id_producto = ?
                ");
                $stmt->bind_param("di", $precioVenta, $idProducto);
                $stmt->execute();
            }
            
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollback();
            throw new Exception("Error al actualizar producto: " . $e->getMessage());
        }
    }

    /**
     * Calcula el precio mínimo basado en costo y margen
     * @param int $producto_id
     * @return float
     * @throws Exception
     */
    public function calcularPrecioMinimo($producto_id) {
        $stmt = $this->db->prepare("
            SELECT ROUND(costo / (1 - (margen_minimo / 100)), 3) AS precio_minimo
            FROM tbl_productos 
            WHERE id_producto = ?
        ");
        $stmt->bind_param("i", $producto_id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al calcular precio mínimo: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        return $result->fetch_assoc()['precio_minimo'];
    }

    // ==================== MÉTODOS DE CONSULTA ====================

    /**
     * Obtiene todos los productos activos
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function getAll($limit = self::DEFAULT_LIMIT, $offset = self::DEFAULT_OFFSET) {
        $sql = "SELECT 
                    p.*, 
                    c.nombre_categoria, 
                    img.nombre_imagen 
                FROM tbl_productos p
                INNER JOIN tbl_subcategorias s ON p.id_subcategoria = s.id_subcategoria
                INNER JOIN tbl_categorias c ON s.id_categoria = c.id_categoria
                LEFT JOIN tbl_imagenes_productos img ON p.id_producto = img.id_producto AND img.orden = 1
                WHERE p.estado = 'active'
                ORDER BY p.id_producto DESC
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al obtener productos: " . $stmt->error);
        }
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Cuenta todos los productos activos
     * @return int
     * @throws Exception
     */
    public function countAll() {
        $sql = "SELECT COUNT(*) as total FROM tbl_productos WHERE estado = 'active'";
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw new Exception("Error al contar productos: " . $this->db->error);
        }
        
        return $result->fetch_assoc()['total'];
    }

    /**
     * Obtiene productos en promoción
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function getPromociones($limit = 4, $offset = 0) {
        $sql = "SELECT 
                    p.*, 
                    c.nombre_categoria,
                    img.nombre_imagen 
                FROM tbl_productos p
                INNER JOIN tbl_subcategorias s ON p.id_subcategoria = s.id_subcategoria
                INNER JOIN tbl_categorias c ON s.id_categoria = c.id_categoria
                LEFT JOIN tbl_imagenes_productos img ON p.id_producto = img.id_producto AND img.orden = 1
                WHERE p.oferta_producto = 'si' AND p.estado = 'active'
                ORDER BY p.id_producto DESC
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $limit, $offset);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al obtener promociones: " . $stmt->error);
        }
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Cuenta productos en promoción
     * @return int
     * @throws Exception
     */
    public function countPromociones() {
        $sql = "SELECT COUNT(*) as total FROM tbl_productos WHERE oferta_producto = 'si' AND estado = 'active'";
        $result = $this->db->query($sql);
        
        if (!$result) {
            throw new Exception("Error al contar promociones: " . $this->db->error);
        }
        
        return $result->fetch_assoc()['total'];
    }

    /**
     * Obtiene productos con bajo stock
     * @param int $umbral
     * @param int $limit
     * @param int $offset
     * @return array
     * @throws Exception
     */
    public function getBajoStock($umbral = self::STOCK_UMBRAL, $limit = 4, $offset = 0) {
        $sql = "SELECT p.*, c.nombre_categoria 
                FROM tbl_productos p
                INNER JOIN tbl_subcategorias s ON p.id_subcategoria = s.id_subcategoria
                INNER JOIN tbl_categorias c ON s.id_categoria = c.id_categoria
                WHERE p.stock_producto < ? AND p.estado = 'active'
                ORDER BY p.stock_producto ASC
                LIMIT ? OFFSET ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $umbral, $limit, $offset);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al obtener productos con bajo stock: " . $stmt->error);
        }
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Cuenta productos con bajo stock
     * @param int $umbral
     * @return int
     * @throws Exception
     */
    public function countBajoStock($umbral = self::STOCK_UMBRAL) {
        $sql = "SELECT COUNT(*) as total FROM tbl_productos WHERE stock_producto < ? AND estado = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $umbral);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al contar productos con bajo stock: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
        return $result->fetch_assoc()['total'];
    }

    /**
     * Obtiene datos para gestión de promociones
     * @return array
     * @throws Exception
     */
    public function getDatosParaPromociones() {
        $result = $this->db->query("
            SELECT 
                p.id_producto as id,
                p.precio_producto as precio,
                p.costo,
                p.margen_minimo as margen,
                p.stock_producto as stock,
                COALESCE(SUM(pp.unidades), 0) as unidades_vendidas,
                DATEDIFF(NOW(), MAX(tp.fecha_pedido)) as dias_sin_venta
            FROM tbl_productos p
            LEFT JOIN tbl_pedidos_productos pp ON p.id_producto = pp.id_producto
            LEFT JOIN tbl_pedidos tp ON pp.id_pedido = tp.id_pedido
            GROUP BY p.id_producto
        ");
        
        if (!$result) {
            throw new Exception("Error al obtener datos para promociones: " . $this->db->error);
        }
        
        $datos = [];
        while ($fila = $result->fetch_assoc()) {
            $datos[] = $fila;
        }
        return $datos;
    }

    /**
     * Obtiene productos destacados
     * @return array
     * @throws Exception
     */
    public function getDestacados() {
        $sql = "SELECT 
                    p.*, 
                    img.nombre_imagen 
                FROM tbl_productos p
                LEFT JOIN tbl_imagenes_productos img ON p.id_producto = img.id_producto AND img.orden = 1
                WHERE p.destacado = 'si' AND p.estado = 'active'
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $limit = self::MAX_DESTACADOS;
        $stmt->bind_param("i", $limit);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al obtener productos destacados: " . $stmt->error);
        }
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene productos por subcategoría
     * @param int $subcategoria
     * @return array
     * @throws Exception
     */
    public function getBySubcategoria($subcategoria) {
        $sql = "SELECT 
                    p.*, 
                    img.nombre_imagen 
                FROM tbl_productos p
                LEFT JOIN tbl_imagenes_productos img ON p.id_producto = img.id_producto AND img.orden = 1
                WHERE p.id_subcategoria = ? 
                AND p.estado = 'active'";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $subcategoria);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al obtener productos por subcategoría: " . $stmt->error);
        }
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Filtra productos por búsqueda y rango de precios
     * @param string $busqueda
     * @param string $precio
     * @return array
     * @throws Exception
     */
    public function filtrar($busqueda, $precio) {
        $sql = "SELECT 
                    p.*, 
                    img.nombre_imagen 
                FROM tbl_productos p
                LEFT JOIN tbl_imagenes_productos img ON p.id_producto = img.id_producto AND img.orden = 1
                WHERE 1=1";
        $params = [];
        $types = "";
        
        if (!empty($busqueda)) {
            $sql .= " AND (p.nombre_producto LIKE ? OR p.descripcion_producto LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
            $types .= "ss";
        }
        
        if (!empty($precio)) {
            list($min, $max) = explode('-', $precio);
            $sql .= " AND p.precio_producto BETWEEN ? AND ?";
            $params[] = $min;
            $params[] = $max;
            $types .= "dd";
        }
        
        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error al filtrar productos: " . $stmt->error);
        }
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene los productos más vendidos
     * @return array
     * @throws Exception
     */
    public function getMasVendidos() {
        $sql = "SELECT 
                    p.*, 
                    c.nombre_categoria, 
                    SUM(pp.unidades) as total_vendido,
                    img.nombre_imagen 
                FROM tbl_productos p
                INNER JOIN tbl_subcategorias s ON p.id_subcategoria = s.id_subcategoria
                INNER JOIN tbl_categorias c ON s.id_categoria = c.id_categoria
                LEFT JOIN tbl_imagenes_productos img ON p.id_producto = img.id_producto AND img.orden = 1
                INNER JOIN tbl_pedidos_productos pp ON p.id_producto = pp.id_producto
                WHERE p.estado = 'active'
                GROUP BY p.id_producto
                ORDER BY total_vendido DESC
                LIMIT ?";
        
        $stmt = $this->db->prepare($sql);
        $limit = self::MAX_MAS_VENDIDOS;
        $stmt->bind_param("i", $limit);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al obtener productos más vendidos: " . $stmt->error);
        }
        
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Obtiene un producto por ID
     * @param int $id
     * @return array|null
     * @throws Exception
     */
    public function getById($id) {
        $sql = "SELECT 
                    p.*, 
                    img.nombre_imagen 
                FROM tbl_productos p
                LEFT JOIN tbl_imagenes_productos img ON p.id_producto = img.id_producto AND img.orden = 1
                WHERE p.id_producto = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al obtener producto: " . $stmt->error);
        }
        
        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Filtrado avanzado con paginación
     * @param array $parametros
     * @return array
     * @throws Exception
     */
    public function filtrarAvanzado($parametros) {
        $busqueda = $parametros['busqueda'] ?? '';
        $precio = $parametros['precio'] ?? 'all';
        $subcategoria = $parametros['subcategoria'] ?? null;
        $pagina = $parametros['pagina'] ?? 1;
        $porPagina = $parametros['porPagina'] ?? 12;
        $offset = ($pagina - 1) * $porPagina;

        $sql = "SELECT SQL_CALC_FOUND_ROWS * 
                FROM tbl_productos 
                WHERE estado = 'active'";

        $params = [];
        $types = "";

        if ($subcategoria && $subcategoria > 0) {
            $sql .= " AND id_subcategoria = ?";
            $params[] = $subcategoria;
            $types .= "i";
        }

        if (!empty($busqueda)) {
            $sql .= " AND (nombre_producto LIKE ? OR descripcion_producto LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
            $types .= "ss";
        }

        if ($precio !== 'all') {
            list($min, $max) = explode('-', $precio);
            $sql .= " AND precio_producto BETWEEN ? AND ?";
            $params[] = $min;
            $params[] = $max;
            $types .= "dd";
        }

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $porPagina;
        $params[] = $offset;
        $types .= "ii";

        $stmt = $this->db->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error en filtrado avanzado: " . $stmt->error);
        }

        $productos = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        
        // Obtener el total de registros
        $total = $this->db->query("SELECT FOUND_ROWS()")->fetch_row()[0];
        $paginas = ceil($total / $porPagina);

        return [
            'productos' => $productos,
            'paginas' => $paginas,
            'total' => $total
        ];
    }

    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
?>