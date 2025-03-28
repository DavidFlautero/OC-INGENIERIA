<?php
session_start();
require '../config/db.php'; // Asegúrate de tener conexión a la DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $codigo = $_POST['codigo'] ?? '';

    if (empty($email) || empty($codigo)) {
        echo json_encode(["status" => "error", "message" => "Faltan datos."]);
        exit;
    }

    // Verificar si el código existe en la base de datos
    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ? AND codigo_verificacion = ? AND verificado = 0");
    $stmt->bind_param("ss", $email, $codigo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Código correcto, activar cuenta
        $stmt = $conn->prepare("UPDATE usuarios SET verificado = 1 WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        echo json_encode(["status" => "success", "message" => "Cuenta verificada con éxito."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Código incorrecto o cuenta ya verificada."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
