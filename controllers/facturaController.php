<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/Producto.php';
header('Content-Type: application/json');

class FacturaController {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        if ($this->db->connect_error) {
            die(json_encode([
                'success' => false,
                'error' => 'Error de conexión: ' . $this->db->connect_error
            ]));
        }
    }

    public function guardarFactura($datos, $archivos) {
        $this->db->begin_transaction();
        try {
            error_log("Iniciando guardado de factura...");
            
            // Validaciones esenciales
            if (empty($datos['proveedor'])) {
                throw new Exception("El campo proveedor es obligatorio");
            }
            
            if (empty($datos['productos']) || !is_array($datos['productos'])) {
                throw new Exception("Debe agregar al menos un producto");
            }

            // Guardar imagen
            $imagenPath = $this->guardarImagen($archivos['fotoFactura']);
            error_log("Imagen guardada en: $imagenPath");

            // Insertar factura principal
            $idFactura = $this->insertarFactura($datos, $imagenPath);
            error_log("Factura base creada ID: $idFactura");

            // Procesar productos
            foreach ($datos['productos'] as $index => $productoData) {
                error_log("Procesando producto #$index: " . print_r($productoData, true));
                
                // Validar estructura del producto
                if (!isset($productoData['id'], $productoData['existente'], $productoData['cantidad'], $productoData['precio_compra'])) {
                    throw new Exception("Estructura inválida en producto #$index");
                }

                // Guardar detalle en factura
                $this->guardarDetalle($idFactura, $productoData);

                // Actualizar stock y precios solo para productos existentes
                if ($productoData['existente'] && $productoData['id']) {
                    error_log("Actualizando producto existente ID: {$productoData['id']}");
                    
                    // Validar valores numéricos positivos
                    if (!is_numeric($productoData['cantidad']) || $productoData['cantidad'] <= 0) {
                        throw new Exception("Cantidad inválida en producto #$index");
                    }
                    
                    if (!is_numeric($productoData['precio_compra']) || $productoData['precio_compra'] <= 0) {
                        throw new Exception("Precio de compra inválido en producto #$index");
                    }

                    // Validar existencia del producto
                    $producto = new Producto();
                    if (!$producto->getById($productoData['id'])) {
                        throw new Exception("Producto ID {$productoData['id']} no encontrado");
                    }

                    // Actualizar en BD
                    $producto->actualizarStockYCostos(
                        $productoData['id'],
                        $productoData['cantidad'],
                        $productoData['precio_compra']
                    );
                    error_log("Producto ID {$productoData['id']} actualizado");
                }
            }

            $this->db->commit();
            error_log("Factura $idFactura guardada exitosamente");
            
            return [
                'success' => true,
                'id_factura' => $idFactura,
                'message' => 'Factura guardada correctamente'
            ];

        } catch(Exception $e) {
            $this->db->rollback();
            error_log("ERROR en guardarFactura: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function insertarFactura($datos, $imagenPath) {
        $stmt = $this->db->prepare("
            INSERT INTO tbl_facturas (
                proveedor, fecha, total, imagen_factura, created_at
            ) VALUES (?, ?, ?, ?, NOW())
        ");
        
        if (!$stmt) {
            throw new Exception("Error preparando query: " . $this->db->error);
        }

        $stmt->bind_param(
            "ssds",
            $datos['proveedor'],
            $datos['fecha'],
            $datos['total'],
            $imagenPath
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error al guardar factura: " . $stmt->error);
        }
        
        $insertId = $this->db->insert_id;
        $stmt->close();
        
        return $insertId;
    }

    private function guardarDetalle($idFactura, $producto) {
        $stmt = $this->db->prepare("
            INSERT INTO tbl_factura_detalles (
                id_factura, id_producto, nombre_producto, cantidad, 
                precio_unitario, precio_compra, precio_venta, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        
        if (!$stmt) {
            throw new Exception("Error preparando detalle: " . $this->db->error);
        }

        $idProducto = $producto['existente'] ? $producto['id'] : null;
        $precioVenta = $producto['precio_venta'] ?? null;
        
        $stmt->bind_param(
            "iisiddd",
            $idFactura,
            $idProducto,
            $producto['nombre'],
            $producto['cantidad'],
            $producto['precio_compra'],
            $producto['precio_compra'], // precio_compra = precio_unitario
            $precioVenta
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Error guardando detalle: " . $stmt->error);
        }
        $stmt->close();
    }

    private function guardarImagen($imagen) {
        if ($imagen['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Error al subir imagen: Código " . $imagen['error']);
        }

        // Validar tamaño máximo (5MB)
        $maxSize = 5 * 1024 * 1024;
        if ($imagen['size'] > $maxSize) {
            throw new Exception("La imagen excede el tamaño máximo de 5MB");
        }

        $uploadDir = __DIR__ . '/../../assets/uploads/facturas/';
        if (!file_exists($uploadDir) && !mkdir($uploadDir, 0777, true)) {
            throw new Exception("No se pudo crear directorio para facturas");
        }

        $extension = strtolower(pathinfo($imagen['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("Formato no permitido. Use JPG, PNG o PDF");
        }

        $nombreArchivo = 'factura_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $rutaCompleta = $uploadDir . $nombreArchivo;

        if (!move_uploaded_file($imagen['tmp_name'], $rutaCompleta)) {
            throw new Exception("Error al guardar archivo en servidor");
        }

        return $nombreArchivo;
    }

    public function obtenerFacturas($limit = 10, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT f.*, COUNT(fd.id) as total_productos 
            FROM tbl_facturas f
            LEFT JOIN tbl_factura_detalles fd ON f.id_factura = fd.id_factura
            GROUP BY f.id_factura
            ORDER BY f.fecha DESC
            LIMIT ? OFFSET ?
        ");
        
        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        
        return $result;
    }

    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}

// ===== Manejo de la solicitud =====
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new FacturaController();
        $response = $controller->guardarFactura($_POST, $_FILES);
        echo json_encode($response);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => 'Error interno: ' . $e->getMessage()
        ]);
    }
}