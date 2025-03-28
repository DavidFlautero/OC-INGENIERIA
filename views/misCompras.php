<?php
// Datos falsos de pedidos y reclamos
$pedidos = [
    [
        'id' => 1001,
        'fecha' => '2023-10-01',
        'total' => 150.75,
        'estado' => 'Entregado',
        'productos' => [
            ['id' => 1, 'nombre' => 'Producto A', 'cantidad' => 2, 'precio_unitario' => 50.00],
            ['id' => 2, 'nombre' => 'Producto B', 'cantidad' => 1, 'precio_unitario' => 50.75],
        ],
    ],
    [
        'id' => 1002,
        'fecha' => '2023-10-05',
        'total' => 200.00,
        'estado' => 'En proceso',
        'productos' => [
            ['id' => 3, 'nombre' => 'Producto C', 'cantidad' => 1, 'precio_unitario' => 200.00],
        ],
    ],
];

$reclamos = [
    [
        'id' => 2001,
        'pedido_id' => 1001,
        'productos' => [
            ['id' => 1, 'nombre' => 'Producto A', 'cantidad' => 2],
        ],
        'estado' => 'Resuelto',
        'respuesta' => 'Se le ha asignado un bono de descuento del 10% para su próxima compra.',
    ],
    [
        'id' => 2002,
        'pedido_id' => 1002,
        'productos' => [
            ['id' => 3, 'nombre' => 'Producto C', 'cantidad' => 1],
        ],
        'estado' => 'En Proceso de Revisión',
        'respuesta' => '',
    ],
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Compras</title>
    <!-- Incluir FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos generales */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa; /* Fondo color hueso */
            color: #333; /* Texto oscuro */
        }

        /* Contenedor principal */
        .contenedor-principal {
            display: flex;
            flex: 1;
            margin-top: 20px;
        }

        /* Menú lateral */
        .menu-lateral {
            width: 250px;
            background-color: #343a40; /* Negro/gris oscuro */
            color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .menu-lateral .foto-perfil {
            text-align: center;
            margin-bottom: 20px;
        }

        .menu-lateral .foto-perfil img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 4px solid #fff;
            object-fit: cover;
        }

        .menu-lateral .foto-perfil h2 {
            margin: 15px 0 0;
            font-size: 20px;
            font-weight: bold;
        }

        .menu-lateral ul {
            list-style: none;
            padding: 0;
        }

        .menu-lateral ul li {
            margin: 20px 0;
        }

        .menu-lateral ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
        }

        .menu-lateral ul li a:hover {
            color: #007bff; /* Azul claro al hacer hover */
        }

        .menu-lateral ul li a i {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Contenido principal */
        .contenido-principal {
            flex: 1;
            padding: 30px;
            background-color: #fff; /* Fondo blanco */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Títulos destacados */
        .contenido-principal h1 {
            color: #007bff; /* Azul claro */
            margin-bottom: 30px;
            font-size: 32px; /* Más grande */
            font-weight: 700; /* Más grueso */
            text-align: center; /* Centrado */
        }

        /* Estilos para la sección de Mis Compras y Reclamos */
        .seccion-pedidos, .seccion-reclamos {
            margin-bottom: 40px;
        }

        .pedido, .reclamo {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .pedido h3, .reclamo h3 {
            margin-top: 0;
            color: #007bff;
        }

        .detalles-pedido, .detalles-reclamo {
            margin-top: 10px;
            padding-left: 20px;
            border-left: 2px solid #007bff;
        }

        .detalles-pedido p, .detalles-reclamo p {
            margin: 5px 0;
        }

        /* Estilos modernos para los botones */
        .btn {
            padding: 8px 16px;
            background-color: #007bff; /* Azul claro */
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-right: 10px;
            margin-bottom: 10px;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            width: auto; /* Ancho automático */
            max-width: 200px; /* Ancho máximo */
        }

        .btn:hover {
            background-color: #0056b3; /* Azul más oscuro al hacer hover */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn:active {
            transform: translateY(1px);
        }

        .btn-reclamo {
            background-color: #dc3545; /* Rojo */
            color: #fff;
        }

        .btn-reclamo:hover {
            background-color: #c82333; /* Rojo más oscuro al hacer hover */
        }

        /* Estilos para los íconos de estado */
        .estado-reclamo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .estado-reclamo .icono {
            font-size: 20px;
            color: #6c757d; /* Gris */
        }

        .estado-reclamo .icono.activo {
            color: #007bff; /* Azul */
            font-weight: bold;
            text-decoration: underline;
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .btn {
                width: 100%; /* En móviles, ocupan el 100% del ancho */
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header incluido desde partials/header.php -->
    <?php include 'partials/header.php'; ?>

    <!-- Contenedor principal -->
    <div class="contenedor-principal">
        <!-- Menú lateral -->
        <div class="menu-lateral">
            <div class="foto-perfil">
                <img src="../assets/images/foto.jpg" alt="Foto de perfil">
                <h2>David Flautero</h2>
            </div>
            <ul>
                <li><a href="perfil_usuario.php#informacion-personal"><i class="fas fa-user"></i> Información Personal</a></li>
                <li><a href="#mis-compras" style="color: #007bff;"><i class="fas fa-shopping-cart"></i> Mis Compras</a></li> <!-- Resaltado -->
                <li><a href="miscompras.php#reclamos"><i class="fas fa-exclamation-circle"></i> Reclamos</a></li>
                <li><a href="perfil_usuario.php#direcciones"><i class="fas fa-map-marker-alt"></i> Direcciones de Entrega</a></li>
                <li><a href="creditos-cupones.php"><i class="fas fa-tags"></i> Cupones y Descuentos</a></li>
                <li><a href="#soporte"><i class="fas fa-headset"></i> Soporte y Ayuda</a></li>
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="contenido-principal">
            <!-- Sección de Mis Compras -->
            <div class="seccion-pedidos" id="mis-compras">
                <h1>Mis Compras</h1>
                <?php foreach ($pedidos as $pedido): ?>
                    <div class="pedido">
                        <h3>Pedido #<?= $pedido['id'] ?></h3>
                        <p><strong>Fecha:</strong> <?= $pedido['fecha'] ?></p>
                        <p><strong>Total:</strong> $<?= number_format($pedido['total'], 2) ?></p>
                        <p><strong>Estado:</strong> <?= $pedido['estado'] ?></p>
                        <button class="btn" onclick="mostrarDetalles(<?= $pedido['id'] ?>)">Ver Detalles</button>
                        <?php if ($pedido['estado'] === 'Entregado'): ?>
                            <button class="btn btn-reclamo" onclick="iniciarReclamo(<?= $pedido['id'] ?>)">Reportar un Problema</button>
                        <?php endif; ?>
                        <div class="detalles-pedido" id="detalles-<?= $pedido['id'] ?>" style="display: none;">
                            <h4>Productos:</h4>
                            <?php foreach ($pedido['productos'] as $producto): ?>
                                <p><?= $producto['nombre'] ?> - Cantidad: <?= $producto['cantidad'] ?> - Precio: $<?= number_format($producto['precio_unitario'], 2) ?></p>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Sección de Reclamos -->
            <div class="seccion-reclamos" id="reclamos">
                <h1>Reclamos</h1>
                <?php foreach ($reclamos as $reclamo): ?>
                    <div class="reclamo">
                        <h3>Reclamo #<?= $reclamo['id'] ?></h3>
                        <p><strong>Pedido:</strong> #<?= $reclamo['pedido_id'] ?></p>
                        <p><strong>Productos Reclamados:</strong></p>
                        <ul>
                            <?php foreach ($reclamo['productos'] as $producto): ?>
                                <li><?= $producto['nombre'] ?> - Cantidad: <?= $producto['cantidad'] ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <!-- Íconos de estado -->
                        <div class="estado-reclamo">
                            <div class="icono <?= $reclamo['estado'] === 'Enviado' ? 'activo' : '' ?>">
                                <i class="fas fa-paper-plane"></i> <!-- Ícono para "Enviado" -->
                                <span>Enviado</span>
                            </div>
                            <div class="icono <?= $reclamo['estado'] === 'En Proceso de Revisión' ? 'activo' : '' ?>">
                                <i class="fas fa-search"></i> <!-- Ícono para "En Revisión" -->
                                <span>En Revisión</span>
                            </div>
                            <div class="icono <?= $reclamo['estado'] === 'Resuelto' ? 'activo' : '' ?>">
                                <i class="fas fa-check-circle"></i> <!-- Ícono para "Resuelto" -->
                                <span>Resuelto</span>
                            </div>
                        </div>
                        <?php if ($reclamo['estado'] === 'Resuelto'): ?>
                            <button class="btn" onclick="mostrarRespuesta(<?= $reclamo['id'] ?>)">Ver Respuesta</button>
                            <div class="respuesta-reclamo" id="respuesta-<?= $reclamo['id'] ?>" style="display: none;">
                                <p><strong>Respuesta:</strong> <?= $reclamo['respuesta'] ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        // Función para mostrar/ocultar detalles del pedido
        function mostrarDetalles(pedidoId) {
            const detalles = document.getElementById(`detalles-${pedidoId}`);
            detalles.style.display = detalles.style.display === 'none' ? 'block' : 'none';
        }

        // Función para mostrar/ocultar la respuesta del reclamo
        function mostrarRespuesta(reclamoId) {
            const respuesta = document.getElementById(`respuesta-${reclamoId}`);
            respuesta.style.display = respuesta.style.display === 'none' ? 'block' : 'none';
        }

        // Función para iniciar un reclamo
        function iniciarReclamo(pedidoId) {
            alert(`Iniciar reclamo para el pedido #${pedidoId}`);
            // Aquí podrías redirigir a un formulario de reclamo o abrir un modal.
        }
    </script>
</body>
</html>