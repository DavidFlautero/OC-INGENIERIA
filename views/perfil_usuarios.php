<?php
session_start();

// Verificación de sesión (comentada temporalmente)
/*
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}
*/

require_once '../models/Usuario.php';
require_once '../config/db.php';

// Simulación de datos de usuario (para pruebas)
$usuario = new Usuario();
$userId = 1; // ID de prueba
$datosUsuario = $usuario->obtenerPorId($userId);

// Procesar la actualización de la foto de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['foto_perfil'])) {
    $directorio = 'assets/uploads/perfiles/';
    $nombreArchivo = $userId . '_' . basename($_FILES['foto_perfil']['name']);
    $rutaArchivo = $directorio . $nombreArchivo;

    // Mover el archivo subido al directorio de perfiles
    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $rutaArchivo)) {
        if ($usuario->updateFotoPerfil($userId, $nombreArchivo)) {
            $mensaje = "Foto de perfil actualizada correctamente.";
        } else {
            $error = "Error al actualizar la foto de perfil.";
        }
    } else {
        $error = "Error al subir la imagen.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        /* Estilos para el menú lateral y el contenido */
        .perfil-container {
            display: flex;
            margin-top: 20px;
        }
        .menu-lateral {
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            border-right: 1px solid #ddd;
        }
        .menu-lateral a {
            display: block;
            padding: 10px;
            margin: 5px 0;
            color: #333;
            text-decoration: none;
            border-radius: 5px;
        }
        .menu-lateral a:hover {
            background-color: #007bff;
            color: #fff;
        }
        .contenido-derecha {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include 'partials/header.php'; ?>

    <div class="container perfil-container">
        <!-- Menú Lateral -->
        <div class="menu-lateral">
            <a href="#" onclick="cargarSeccion('informacion')">Información Personal</a>
            <a href="#" onclick="cargarSeccion('historial')">Historial de Pedidos</a>
            <a href="#" onclick="cargarSeccion('reclamos')">Reclamos</a>
            <a href="#" onclick="cargarSeccion('direcciones')">Direcciones de Entrega</a>
            <a href="#" onclick="cargarSeccion('pagos')">Métodos de Pago</a>
            <a href="#" onclick="cargarSeccion('favoritos')">Favoritos</a>
            <a href="#" onclick="cargarSeccion('cupones')">Cupones y Descuentos</a>
            <a href="#" onclick="cargarSeccion('soporte')">Soporte y Ayuda</a>
        </div>

        <!-- Contenido Dinámico -->
        <div class="contenido-derecha" id="contenido-derecha">
            <!-- Aquí se cargará el contenido de cada sección -->
            <h2>Bienvenido, <?= htmlspecialchars($datosUsuario['nombre_completo']) ?></h2>
            <p>Selecciona una opción del menú para comenzar.</p>
        </div>
    </div>

    <?php include 'partials/footer.php'; ?>

    <script>
        // Función para cargar el contenido de cada sección
        function cargarSeccion(seccion) {
            const contenido = document.getElementById('contenido-derecha');

            // Simulación de carga de contenido (puedes reemplazar con AJAX)
            switch (seccion) {
                case 'informacion':
                    contenido.innerHTML = `
                        <h2>Información Personal</h2>
                        <form action="perfil_usuario.php" method="POST">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($datosUsuario['nombre_completo']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($datosUsuario['email_usuario']) ?>" required>
                            </div>
                            <button type="submit" name="actualizar_perfil" class="btn btn-primary">Actualizar Perfil</button>
                        </form>
                        <form action="perfil_usuario.php" method="POST" enctype="multipart/form-data">
                            <h3>Cambiar Foto de Perfil</h3>
                            <?php if (!empty($datosUsuario['foto_perfil'])): ?>
                                <img src="assets/uploads/perfiles/<?= htmlspecialchars($datosUsuario['foto_perfil']) ?>" alt="Foto de perfil" width="150" height="150">
                            <?php else: ?>
                                <p>No hay foto de perfil.</p>
                            <?php endif; ?>
                            <input type="file" name="foto_perfil" accept="image/*" required>
                            <button type="submit" class="btn btn-primary">Cambiar Foto</button>
                        </form>
                    `;
                    break;
                case 'historial':
                    contenido.innerHTML = `
                        <h2>Historial de Pedidos</h2>
                        <p>Aquí se mostrará el listado de pedidos realizados.</p>
                    `;
                    break;
                case 'reclamos':
                    contenido.innerHTML = `
                        <h2>Reclamos</h2>
                        <p>Aquí se mostrarán los reclamos abiertos y cerrados.</p>
                    `;
                    break;
                case 'direcciones':
                    contenido.innerHTML = `
                        <h2>Direcciones de Entrega</h2>
                        <p>Aquí se mostrarán las direcciones guardadas.</p>
                    `;
                    break;
                case 'pagos':
                    contenido.innerHTML = `
                        <h2>Métodos de Pago</h2>
                        <p>Aquí se mostrarán las tarjetas guardadas.</p>
                    `;
                    break;
                case 'favoritos':
                    contenido.innerHTML = `
                        <h2>Favoritos</h2>
                        <p>Aquí se mostrarán los productos favoritos.</p>
                    `;
                    break;
                case 'cupones':
                    contenido.innerHTML = `
                        <h2>Cupones y Descuentos</h2>
                        <p>Aquí se mostrarán los cupones disponibles.</p>
                    `;
                    break;
                case 'soporte':
                    contenido.innerHTML = `
                        <h2>Soporte y Ayuda</h2>
                        <p>Aquí se mostrarán las preguntas frecuentes y opciones de contacto.</p>
                    `;
                    break;
                default:
                    contenido.innerHTML = `
                        <h2>Bienvenido, <?= htmlspecialchars($datosUsuario['nombre_completo']) ?></h2>
                        <p>Selecciona una opción del menú para comenzar.</p>
                    `;
            }
        }
    </script>
</body>
</html>