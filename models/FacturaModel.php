<?php
require_once __DIR__ . '/../config/db.php';

class FacturaModel {
    private $pdo;
    
    public function __construct() {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }

    // Guarda la factura y devuelve el ID
    public function guardarFactura($proveedor, $fecha, $total, $imagen = null) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO facturas_proveedores 
            (proveedor, fecha, total, imagen_factura) 
            VALUES (?, ?, ?, ?)"
        );
        $stmt->execute([$proveedor, $fecha, $total, $imagen]);
        return $this->pdo->lastInsertId();
    }

    // Guarda los productos de la factura
    public function guardarProductosFactura($idFactura, $productos) {
        foreach ($productos as $producto) {
            $stmt = $this->pdo->prepare(
                "INSERT INTO factura_productos 
                (id_factura, id_producto, cantidad, precio_compra) 
                VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([
                $idFactura,
                $producto['id'] ?? null,
                $producto['cantidad'],
                $producto['precio_compra']
            ]);

            // Si el producto existe, actualiza stock y precio
            if ($producto['existente'] && $producto['id']) {
                $this->actualizarProducto(
                    $producto['id'],
                    $producto['cantidad'],
                    $producto['precio_compra']
                );
            }
        }
    }

    // Actualiza stock y precio de compra
    private function actualizarProducto($idProducto, $cantidad, $precioCompra) {
        $stmt = $this->pdo->prepare(
            "UPDATE tbl_productos SET 
            stock_producto = stock_producto + ?, 
            costo = ?
            WHERE id_producto = ?"
        );
        $stmt->execute([$cantidad, $precioCompra, $idProducto]);
    }
}