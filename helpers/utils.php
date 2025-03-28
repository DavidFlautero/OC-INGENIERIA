<?php
// Verificar si el usuario es administrador
function isAdmin() {
    if (!isset($_SESSION['admin'])) {
        header("Location:" . base_url);
        exit();
    }
}

// Verificar si el usuario está logueado
function isLogged() {
    if (!isset($_SESSION['user'])) {
        header("Location:" . base_url);
        exit();
    }
}

// Calcular el coste total del carrito
function statsCarrito() {
    $stats = array(
        'count' => 0,
        'total' => 0
    );

    if (isset($_SESSION['carrito'])) {
        $stats['count'] = count($_SESSION['carrito']);

        foreach ($_SESSION['carrito'] as $producto) {
            $stats['total'] += $producto['precio_producto'] * $producto['unidad_producto'];
        }
    }

    return $stats;
}

// Mostrar mensajes de éxito o error
function showMessages() {
    if (isset($_SESSION['register']) && $_SESSION['register'] == 'complete') {
        echo "<div class='alert alert-success'>Registro completado correctamente.</div>";
        unset($_SESSION['register']);
    } elseif (isset($_SESSION['register']) && $_SESSION['register'] == 'failed') {
        echo "<div class='alert alert-danger'>Error en el registro. Por favor, inténtalo de nuevo.</div>";
        unset($_SESSION['register']);
    }

    if (isset($_SESSION['error_login'])) {
        echo "<div class='alert alert-danger'>{$_SESSION['error_login']}</div>";
        unset($_SESSION['error_login']);
    }

    if (isset($_SESSION['reclamo']) && $_SESSION['reclamo'] == 'complete') {
        echo "<div class='alert alert-success'>Reclamo enviado correctamente.</div>";
        unset($_SESSION['reclamo']);
    } elseif (isset($_SESSION['reclamo']) && $_SESSION['reclamo'] == 'failed') {
        echo "<div class='alert alert-danger'>Error al enviar el reclamo. Por favor, inténtalo de nuevo.</div>";
        unset($_SESSION['reclamo']);
    }
}

// Redirigir a una URL específica
function redirect($url) {
    header("Location: $url");
    exit();
}

// Sanitizar datos de entrada
function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Generar un token CSRF
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Validar un token CSRF
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Obtener la fecha actual en un formato específico
function getCurrentDate($format = 'Y-m-d H:i:s') {
    return date($format);
}

// Convertir un array a JSON
function arrayToJson($array) {
    return json_encode($array, JSON_PRETTY_PRINT);
}

// Convertir JSON a array
function jsonToArray($json) {
    return json_decode($json, true);
}

// Subir un archivo al servidor
function uploadFile($file, $destination, $allowedTypes = ['image/jpeg', 'image/png', 'image/gif']) {
    if ($file['error'] === UPLOAD_ERR_OK) {
        $fileType = $file['type'];
        if (in_array($fileType, $allowedTypes)) {
            $fileName = uniqid() . '_' . basename($file['name']);
            $filePath = $destination . $fileName;

            if (move_uploaded_file($file['tmp_name'], $filePath)) {
                return $fileName;
            } else {
                throw new Exception("Error al mover el archivo subido.");
            }
        } else {
            throw new Exception("Tipo de archivo no permitido.");
        }
    } else {
        throw new Exception("Error al subir el archivo.");
    }
}

// Obtener la URL base del proyecto
function base_url() {
    return BASE_URL;
}

// Mostrar un mensaje de error y redirigir
function showErrorAndRedirect($message, $url) {
    $_SESSION['error'] = $message;
    redirect($url);
}

// Mostrar un mensaje de éxito y redirigir
function showSuccessAndRedirect($message, $url) {
    $_SESSION['success'] = $message;
    redirect($url);
}

// Verificar si un archivo es una imagen válida
function isImage($file) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    return in_array($file['type'], $allowedTypes);
}

// Generar un nombre único para un archivo
function generateUniqueFileName($file) {
    return uniqid() . '_' . basename($file['name']);
}

// Verificar si un archivo es un video válido
function isVideo($file) {
    $allowedTypes = ['video/mp4', 'video/webm', 'video/ogg'];
    return in_array($file['type'], $allowedTypes);
}