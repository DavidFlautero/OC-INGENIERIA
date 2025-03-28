<?php
require '../config/db.php'; // Conexión a la base de datos
require '../vendor/autoload.php'; // Carga las dependencias de Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Google\Client;
use Google\Service\Gmail;

header('Content-Type: application/json');

// Verifica si el método es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
    exit;
}

// Obtiene los datos del formulario
$nombre = $_POST['nombre'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validaciones básicas
if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
    echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
    exit;
}
if ($password !== $confirm_password) {
    echo json_encode(["status" => "error", "message" => "Las contraseñas no coinciden."]);
    exit;
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "El correo electrónico no es válido."]);
    exit;
}

// Conexión a la base de datos
$db = Database::connect();

// Verifica si el correo ya está registrado
$stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(["status" => "error", "message" => "El correo electrónico ya está registrado."]);
    exit;
}
$stmt->close();

// Hashea la contraseña
$password_hash = password_hash($password, PASSWORD_BCRYPT);
$verificacion_codigo = rand(100000, 999999); // Código de 6 dígitos
$verificacion_expiracion = date("Y-m-d H:i:s", strtotime("+1 day")); // Expira en 24 horas

// Inserta el usuario en la base de datos
$stmt = $db->prepare("INSERT INTO usuarios (nombre_completo, email, telefono, password_hash, verificacion_codigo, verificacion_expiracion, creado_en) VALUES (?, ?, ?, ?, ?, ?, NOW())");
$stmt->bind_param("ssssss", $nombre, $email, $telefono, $password_hash, $verificacion_codigo, $verificacion_expiracion);

if ($stmt->execute()) {
    // Configuración de OAuth 2.0
    $client = new Client();
    $client->setAuthConfig('../credential.json'); // Ruta al archivo JSON de credenciales
    $client->addScope(Gmail::GMAIL_SEND);
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Si ya tienes un token guardado, úsalo
    if (file_exists('../token.json')) {
        $accessToken = json_decode(file_get_contents('../token.json'), true);
        $client->setAccessToken($accessToken);
    }

    // Si no hay token o está expirado, obtén uno nuevo
    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            <?php
            require '../config/db.php'; // Conexión a la base de datos
            require '../vendor/autoload.php'; // Carga las dependencias de Composer
            
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;
            use Google\Client;
            use Google\Service\Gmail;
            
            header('Content-Type: application/json');
            
            // Verifica si el método es POST
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                echo json_encode(["status" => "error", "message" => "Método no permitido."]);
                exit;
            }
            
            // Obtiene los datos del formulario
            $nombre = $_POST['nombre'];
            $email = $_POST['email'];
            $telefono = $_POST['telefono'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Validaciones básicas
            if (empty($nombre) || empty($email) || empty($password) || empty($confirm_password)) {
                echo json_encode(["status" => "error", "message" => "Todos los campos son obligatorios."]);
                exit;
            }
            if ($password !== $confirm_password) {
                echo json_encode(["status" => "error", "message" => "Las contraseñas no coinciden."]);
                exit;
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                echo json_encode(["status" => "error", "message" => "El correo electrónico no es válido."]);
                exit;
            }
            
            // Conexión a la base de datos
            $db = Database::connect();
            
            // Verifica si el correo ya está registrado
            $stmt = $db->prepare("SELECT id FROM usuarios WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            
            if ($stmt->num_rows > 0) {
                echo json_encode(["status" => "error", "message" => "El correo electrónico ya está registrado."]);
                exit;
            }
            $stmt->close();
            
            // Hashea la contraseña
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            $verificacion_codigo = rand(100000, 999999); // Código de 6 dígitos
            $verificacion_expiracion = date("Y-m-d H:i:s", strtotime("+1 day")); // Expira en 24 horas
            
            // Inserta el usuario en la base de datos
            $stmt = $db->prepare("INSERT INTO usuarios (nombre_completo, email, telefono, password_hash, verificacion_codigo, verificacion_expiracion, creado_en) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $nombre, $email, $telefono, $password_hash, $verificacion_codigo, $verificacion_expiracion);
            
            if ($stmt->execute()) {
                // Configuración de OAuth 2.0
                $client = new Client();
                $client->setAuthConfig('../credential.json'); // Ruta al archivo JSON de credenciales
                $client->addScope(Gmail::GMAIL_SEND);
                $client->setAccessType('offline');
                $client->setPrompt('select_account consent');
            
                // Si ya tienes un token guardado, úsalo
                if (file_exists('../token.json')) {
                    $accessToken = json_decode(file_get_contents('../token.json'), true);
                    $client->setAccessToken($accessToken);
                }
            
                // Si no hay token o está expirado, obtén uno nuevo
                if ($client->isAccessTokenExpired()) {
                    if ($client->getRefreshToken()) {
                        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    } else {
                        // Redirige al usuario a la URL de autorización
                        $authUrl = $client->createAuthUrl();
                        echo json_encode(["status" => "redirect", "url" => $authUrl]);
                        exit;
                    }
                    // Guarda el token actualizado
                    file_put_contents('../token.json', json_encode($client->getAccessToken()));
                }
            
                // Enviar el código de verificación por correo usando Gmail API
                try {
                    $gmail = new Gmail($client);
            
                    $mail = new PHPMailer(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Encoding = 'base64';
                    $mail->setFrom('nicolasmillos2025@gmail.com', 'Mayorisander');
                    $mail->addAddress($email, $nombre);
                    $mail->Subject = 'Código de verificación';
                    $mail->Body = "Hola $nombre,<br><br>Tu código de verificación es: <strong>$verificacion_codigo</strong>.<br><br>Gracias por registrarte.";
            
                    // Prepara el mensaje para Gmail API
                    $rawMessage = rtrim(strtr(base64_encode($mail->getSentMIMEMessage()), '+/', '-_'), '=');
                    $message = new \Google\Service\Gmail\Message();
                    $message->setRaw($rawMessage);
            
                    // Envía el correo
                    $gmail->users_messages->send('me', $message);
            
                    echo json_encode(["status" => "success", "message" => "Registro exitoso. Revisa tu correo para verificar tu cuenta."]);
                } catch (Exception $e) {
                    echo json_encode(["status" => "error", "message" => "Error al enviar el correo: " . $e->getMessage()]);
                }
            } else {
                echo json_encode(["status" => "error", "message" => "Error al registrar el usuario: " . $stmt->error]);
            }
            
            $stmt->close();
            $db->close();
            exit;
            ?>        } else {
            // Redirige al usuario a la URL de autorización
            $authUrl = $client->createAuthUrl();
            echo json_encode(["status" => "redirect", "url" => $authUrl]);
            exit;
        }
        // Guarda el token actualizado
        file_put_contents('../token.json', json_encode($client->getAccessToken()));
    }

    // Enviar el código de verificación por correo usando Gmail API
    try {
        $gmail = new Gmail($client);

        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';
        $mail->setFrom('nicolasmillos2025@gmail.com', 'Mayorisander');
        $mail->addAddress($email, $nombre);
        $mail->Subject = 'Código de verificación';
        $mail->Body = "Hola $nombre,<br><br>Tu código de verificación es: <strong>$verificacion_codigo</strong>.<br><br>Gracias por registrarte.";

        // Prepara el mensaje para Gmail API
        $rawMessage = rtrim(strtr(base64_encode($mail->getSentMIMEMessage()), '+/', '-_'), '=');
        $message = new \Google\Service\Gmail\Message();
        $message->setRaw($rawMessage);

        // Envía el correo
        $gmail->users_messages->send('me', $message);

        echo json_encode(["status" => "success", "message" => "Registro exitoso. Revisa tu correo para verificar tu cuenta."]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Error al enviar el correo: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Error al registrar el usuario: " . $stmt->error]);
}

$stmt->close();
$db->close();
exit;
?>