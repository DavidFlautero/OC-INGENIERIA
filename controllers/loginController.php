<?php
session_start(); // Iniciar la sesión

// Incluir la conexión a la base de datos
require '../config/db.php'; // Asegúrate de que este archivo esté correctamente configurado

// Obtener la conexión a la base de datos
$conexion = Database::connect();

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Validar que los campos no estén vacíos
    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
        exit();
    }

    // Validar el formato del correo electrónico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "El correo electrónico no es válido."]);
        exit();
    }

    // Buscar el usuario en la base de datos
    $query = "SELECT id, nombre_completo, password_hash FROM usuarios WHERE email = ?";
    $stmt = $conexion->prepare($query); // Usar $conexion para preparar la consulta
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Error al preparar la consulta."]);
        exit();
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Depuración: Mostrar el hash de la contraseña almacenada
        error_log("Hash de la contraseña almacenada: " . $user['password_hash']);

        // Verificar la contraseña
        if (password_verify($password, $user['password_hash'])) {
            // Iniciar sesión
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nombre_completo'];

                // Redirigir al perfil del usuario
                header("Location: http://localhost/onlinetienda/views/perfil_usuario.php"); // Cambia "perfil.php" por la URL correcta
                exit();
            } else {
                // Depuración: Mostrar la contraseña proporcionada y el hash almacenado
            error_log("Contraseña proporcionada: " . $password);
            error_log("Hash de la contraseña almacenada: " . $user['password_hash']);

            echo json_encode(["status" => "error", "message" => "Correo electrónico o contraseña incorrectos."]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Correo electrónico o contraseña incorrectos."]);
        exit();
    }
} else {
    // Si el formulario no fue enviado, redirigir al formulario de login
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    exit();
}
?>