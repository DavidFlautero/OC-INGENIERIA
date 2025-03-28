<?php
// controllers/MensajeController.php

session_start(); // Iniciar sesión para manejar mensajes de éxito/error

// Incluir modelos necesarios
require_once __DIR__ . '/../models/Mensaje.php';

class MensajeController {

    // Método para obtener mensajes activos
    public function obtenerMensajesActivos() {
        try {
            $mensaje = new Mensaje();
            $mensajes = $mensaje->getMensajesActivos(); // Obtener mensajes activos

            // Devolver los mensajes en formato JSON
            header('Content-Type: application/json');
            echo json_encode([
                "success" => true,
                "data" => $mensajes
            ]);
            exit;
        } catch (Exception $e) {
            // Manejar errores y devolver un JSON con el mensaje de error
            header('Content-Type: application/json');
            http_response_code(500); // Error del servidor
            echo json_encode([
                "success" => false,
                "message" => "Error al obtener los mensajes: " . $e->getMessage()
            ]);
            exit;
        }
    }
}

// Uso del controlador
$action = $_GET['action'] ?? 'obtenerMensajesActivos'; // Acción por defecto
$controller = new MensajeController();

// Ejecutar la acción correspondiente
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    // Devolver un error en formato JSON si la acción no existe
    header('Content-Type: application/json');
    http_response_code(404); // No encontrado
    echo json_encode([
        "success" => false,
        "message" => "Error: Acción no válida."
    ]);
    exit;
}
?>