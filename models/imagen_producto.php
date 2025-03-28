<?php
require_once __DIR__ . '/../config/db.php';

class Imagen_Producto {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
        // Verificación de conexión mejorada (para MySQLi)
        if ($this->db->connect_errno) {
            die("Error de conexión: " . $this->db->connect_error);
        }
    }

    // Guardar múltiples imágenes para un producto
    public function guardar($id_producto, $imagenes) {
        foreach ($imagenes as $orden => $nombre_imagen) {
            $stmt = $this->db->prepare("
                INSERT INTO tbl_imagenes_productos (
                    id_producto, 
                    orden, 
                    nombre_imagen
                ) VALUES (?, ?, ?)
            ");
            // Corregir tipo de dato para "orden" (es entero)
            $stmt->bind_param("iis", $id_producto, $orden, $nombre_imagen);
            
            if (!$stmt->execute()) {
                die("Error al guardar imagen: " . $stmt->error);
            }
            $stmt->close();
        }
    }

    // Eliminar una imagen por ID
    public function eliminar($id_imagen) {
        $stmt = $this->db->prepare("
            DELETE FROM tbl_imagenes_productos 
            WHERE id_imagen = ?
        ");
        $stmt->bind_param("i", $id_imagen);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Obtener todas las imágenes de un producto
    public function obtenerPorProducto($id_producto) {
        $stmt = $this->db->prepare("
             tbl_imagenes_productos 
            WHERE id_producto = ? 
            ORDER BY orden ASC
        ");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Eliminar todas las imágenes de un producto (al eliminar el producto)
    public function eliminarPorProducto($id_producto) {
        $stmt = $this->db->prepare("
            DELETE FROM tbl_imagenes_productos 
            WHERE id_producto = ?
        ");
        $stmt->bind_param("i", $id_producto);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Cerrar conexión
    public function __destruct() {
        if ($this->db) {
            $this->db->close();
        }
    }
}
?>