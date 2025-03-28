<?php
session_start();
require '../config/db.php'; // Conexión a la DB

header('Content-Type: application/json'); // Establece el tipo de respuesta

// Obtener los datos enviados desde JavaScript
$input = json_decode(file_get_contents("php://input"), true);
$email = $input['email'] ?? '';
$codigo = $input['code'] ?? '';


// Verificar que los datos existen
if (empty($email) || empty($codigo)) {
    echo json_encode(["status" => "error", "message" => "Faltan datos."]);
    exit;
}

// Preparar la consulta SQL
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = ? AND codigo_verificacion = ? AND verificado = 0");
if (!$stmt) {
    echo json_encode(["status" => "error", "message" => "Error en la consulta SQL."]);
    exit;
}

$stmt->bind_param("ss", $email, $codigo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Código correcto, activar cuenta
    $stmt = $conn->prepare("UPDATE usuarios SET verificado = 1 WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        echo json_encode(["status" => "success", "message" => "Cuenta verificada con éxito."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error al actualizar la cuenta."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Código incorrecto o cuenta ya verificada."]);
}

// Cerrar conexiones
$stmt->close();
$conexion->close();
?>
