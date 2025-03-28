<?php
// Datos falsos del cupón
$cupon = [
    'codigo' => 'fsd5f5x9es',
    'valor' => 5000,
    'reclamo' => 'n x265s',
    'dias_restantes' => 10, // Contador de días
];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créditos y Cupones</title>
    <!-- Incluir FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Estilos generales */
        body, html {
            margin: 0;
            padding: 0;
            height: 100%; /* Asegura que el body ocupe toda la altura de la pantalla */
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa; /* Fondo color hueso */
            color: #333; /* Texto oscuro */
        }

        /* Contenedor principal */
        .contenedor-principal {
            display: flex;
            flex: 1; /* Ocupa todo el espacio disponible */
            margin-top: 20px;
            height: calc(100vh - 20px); /* Ajusta la altura menos el margen superior */
        }

        /* Menú lateral */
        .menu-lateral {
            width: 250px;
            background-color: #343a40; /* Negro/gris oscuro */
            color: #fff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            height: 100%; /* Ocupa toda la altura del contenedor principal */
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
            flex: 1; /* Ocupa todo el espacio restante */
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
            overflow-y: auto; /* Permite el desplazamiento vertical si el contenido es largo */
        }

        /* Títulos destacados */
        .contenido-principal h1 {
            color: #007bff; /* Azul claro */
            margin-bottom: 30px;
            font-size: 32px; /* Más grande */
            font-weight: 700; /* Más grueso */
            text-align: center; /* Centrado */
        }

        /* Estilos para la sección de Cupones */
        .seccion-cupones {
            margin-bottom: 40px;
        }

        .cupon {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
        }

        .cupon h3 {
            margin-top: 0;
            color: #007bff;
        }

        .cupon p {
            margin: 10px 0;
            font-size: 18px;
        }

        .cupon .codigo {
            font-size: 24px;
            font-weight: bold;
            color: #28a745; /* Verde */
        }

        .cupon .reclamo {
            font-size: 14px;
            color: #6c757d; /* Gris */
        }

        .cupon .contador {
            font-size: 16px;
            color: #dc3545; /* Rojo */
            margin-top: 20px;
        }

        /* Estilos responsivos */
        @media (max-width: 768px) {
            .cupon .codigo {
                font-size: 20px;
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
                <img src="../assets/img/foto.png" alt="Foto de perfil">
                <h2>David Flautero</h2>
            </div>
            <ul>
                <li><a href="perfil_usuario.php"><i class="fas fa-user"></i> Información Personal</a></li>
                <li><a href="miscompras.php"><i class="fas fa-shopping-cart"></i> Mis Compras</a></li>
                <li><a href="miscompras.php#reclamos"><i class="fas fa-exclamation-circle"></i> Reclamos</a></li>
                <li><a href="/perfil_usuario.php#direcciones"><i class="fas fa-map-marker-alt"></i> Direcciones de Entrega</a></li>
                <li><a href="#" style="color: #007bff;"><i class="fas fa-tags"></i> Créditos y Cupones</a></li> <!-- Resaltado -->
                <li><a href=""><i class="fas fa-headset"></i> Soporte y Ayuda</a></li>
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="contenido-principal">
            <!-- Sección de Créditos y Cupones -->
            <div class="seccion-cupones" id="creditos-cupones">
                <h1>Créditos y Cupones</h1>
                <div class="cupon">
                    <h3>Cupón Activo</h3>
                    <p><strong>Código:</strong> <span class="codigo"><?= $cupon['codigo'] ?></span></p>
                    <p><strong>Valor:</strong> $<?= number_format($cupon['valor'], 2) ?></p>
                    <p class="reclamo">*Por el reclamo <?= $cupon['reclamo'] ?></p>
                    <p class="contador">Tienes <strong><?= $cupon['dias_restantes'] ?></strong> días para usar este cupón.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>