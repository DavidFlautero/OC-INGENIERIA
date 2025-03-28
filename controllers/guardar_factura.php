<?php
// controllers/guardar_factura.php

require '../models/FacturaModel.php'; // Incluir el modelo

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $proveedor = $_POST['proveedor'];
    $fecha = $_POST['fecha'];
    $total = $_POST['total'];
    $productos = json_decode($_POST['productos'], true); // Decodificar el JSON de productos

    // Validar los datos
    if (empty($proveedor) || empty($fecha) || empty($total) || empty($productos)) {
        echo json_encode(["success" => false, "message" => "Todos los campos son obligatorios."]);
        exit;
    }

    // Crear una instancia del modelo
    $facturaModel = new FacturaModel();

    try {
        // Guardar la factura y actualizar el stock
        $facturaModel->guardarFactura($proveedor, $fecha, $total, $productos);

        // Respuesta de éxito
        echo json_encode(["success" => true, "message" => "Factura guardada correctamente."]);
    } catch (Exception $e) {
        // Respuesta de error
        echo json_encode(["success" => false, "message" => "Error al guardar la factura: " . $e->getMessage()]);
    }
} else {
    // Si la solicitud no es POST, devolver un error
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>