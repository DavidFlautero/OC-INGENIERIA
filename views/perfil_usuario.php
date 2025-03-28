<?php
// Simulamos que el usuario está logueado y sus datos están en una sesión
session_start();

// Datos falsos del usuario
$usuario = [
    'id' => 1,
    'nombre' => 'David Flautero',
    'email' => 'david.flautero@example.com',
    'foto_perfil' => '../assets/images/foto.jpg', // Ruta relativa a la imagen de perfil
    'direccion' => 'Olavarría 961, Código Postal 110, La Boca, Buenos Aires', // Dirección falsa
    'telefono' => '123-456-7890', // Teléfono falso
    'dni' => '12345678', // DNI falso
    'ciudad' => 'Buenos Aires', // Ciudad falsa
    'pais' => 'Argentina', // País falso
    'codigo_postal' => '110', // Código postal falso
    'tipo_negocio' => '', // Tipo de negocio (vacío por defecto)
    'notas' => '', // Notas adicionales (vacías por defecto)
];

// Mensaje de bienvenida
$mensajeBienvenida = "Bienvenido, " . $usuario['nombre'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <style>
        /* Estilos generales */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
            color: #333;
        }

        .contenedor-principal {
            display: flex;
            flex: 1;
            margin-top: 20px;
        }

        /* Menú lateral */
        .menu-lateral {
            width: 250px;
            background-color: #343a40;
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
            color: #007bff;
        }

        .menu-lateral ul li a i {
            margin-right: 10px;
            font-size: 20px;
        }

        /* Contenido principal */
        .contenido-principal {
            flex: 1;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 30px;
        }

        .formulario-container {
            flex: 1;
        }

        .seccion {
            margin-bottom: 40px;
        }

        .seccion h2 {
            color: #007bff;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
        }

        .grupo-formulario {
            margin-bottom: 20px;
        }

        .grupo-formulario label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }

        .grupo-formulario input,
        .grupo-formulario textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .grupo-formulario input:focus,
        .grupo-formulario textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .grupo-formulario textarea {
            resize: vertical;
            min-height: 120px;
        }

        button[type="submit"] {
            padding: 12px 24px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Mapa */
        .mapa {
            flex: 1;
            background-color: #f4f4f4;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            height: 400px;
        }

        .mapa iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Estilos para el acordeón de direcciones */
        .acordeon-direccion {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
            overflow: hidden;
        }

        .acordeon-cabecera {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background-color: #f8f9fa;
            cursor: pointer;
            border-bottom: 1px solid #ddd;
        }

        .acordeon-cabecera h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .acordeon-cabecera .icono {
            font-size: 20px;
            color: #007bff;
        }

        .acordeon-contenido {
            padding: 15px;
            background-color: #fff;
            display: none;
        }

        .acordeon-contenido.activo {
            display: block;
        }

        /* Botón para agregar nueva dirección */
        .boton-agregar-direccion {
            margin-top: 20px;
            text-align: center;
        }

        .boton-agregar-direccion button {
            padding: 12px 24px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        .boton-agregar-direccion button:hover {
            background-color: #218838;
        }

        /* Estilos para el formulario de nueva dirección */
        .formulario-nueva-direccion {
            margin-top: 20px;
            display: none;
        }

        .formulario-nueva-direccion.activo {
            display: block;
        }

        /* Estilos para el switch de entrega */
        .switch-entrega {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .switch-entrega label {
            margin-right: 10px;
            font-weight: bold;
            color: #555;
        }

        .switch-entrega .switch {
            position: relative;
            display: inline-block;
            width: 180px;
            height: 34px;
        }

        .switch-entrega .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .switch-entrega .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #f1f1f1;
            transition: 0.4s;
            border-radius: 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 10px;
        }

        .switch-entrega .slider .texto-izquierda,
        .switch-entrega .slider .texto-derecha {
            font-size: 14px;
            font-weight: bold;
            transition: color 0.4s;
        }

        .switch-entrega .slider .texto-izquierda {
            color: #555;
        }

        .switch-entrega .slider .texto-derecha {
            color: #555;
        }

        .switch-entrega input:checked + .slider {
            background-color: #e0f7e0;
        }

        .switch-entrega input:checked + .slider .texto-izquierda {
            color: #555;
        }

        .switch-entrega input:checked + .slider .texto-derecha {
            color: #28a745;
        }

        .switch-entrega .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .switch-entrega input:checked + .slider:before {
            transform: translateX(146px);
        }
    </style>
    <!-- Iconos de FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <?php include 'partials/header.php'; ?>

    <!-- Contenedor principal -->
    <div class="contenedor-principal">
        <!-- Menú lateral -->
        <div class="menu-lateral">
            <div class="foto-perfil">
                <img src="<?= htmlspecialchars($usuario['foto_perfil']) ?>" alt="Foto de perfil">
                <h2><?= htmlspecialchars($usuario['nombre']) ?></h2>
            </div>
            <ul>
                <li><a href="#informacion-personal"><i class="fas fa-user"></i> Información Personal</a></li>
                <li><a href="miscompras.php"><i class="fas fa-shopping-cart"></i> Historial de Pedidos</a></li>
                <li><a href="miscompras.php#reclamos"><i class="fas fa-exclamation-circle"></i> Reclamos</a></li>
                <li><a href="#direcciones"><i class="fas fa-map-marker-alt"></i> Direcciones de Entrega</a></li>
                <li><a href="creditos-cupones.php"><i class="fas fa-tags"></i> Cupones y Descuentos</a></li>
                <li><a href="#"><i class="fas fa-headset"></i> Soporte y Ayuda</a></li>
                <li><a href="#"><i class="fas fa-headset"></i> Salir</a></li>
            </ul>
        </div>

        <!-- Contenido principal -->
        <div class="contenido-principal">
            <!-- Formulario de Información Personal -->
            <div class="formulario-container">
                <h1><?= htmlspecialchars($mensajeBienvenida) ?></h1>

                <!-- Sección: Información Personal -->
                <div class="seccion" id="informacion-personal">
                    <h2>Información Personal</h2>
                    <form id="formulario-perfil">
                        <!-- Datos Personales -->
                        <div class="grupo-formulario">
                            <label for="nombre">Nombre Completo:</label>
                            <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                        </div>
                        <div class="grupo-formulario">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                        </div>
                        <div class="grupo-formulario">
                            <label for="telefono">Teléfono:</label>
                            <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>
                        </div>
                        <div class="grupo-formulario">
                            <label for="dni">DNI:</label>
                            <input type="text" id="dni" name="dni" value="<?= htmlspecialchars($usuario['dni']) ?>" required>
                        </div>
                    </form>
                </div>

                <!-- Sección: Mis Direcciones -->
                <div class="seccion" id="direcciones">
                    <h2>Mis Direcciones</h2>

                    <!-- Acordeón de direcciones -->
                    <div class="acordeon-direccion">
                        <div class="acordeon-cabecera">
                            <h3>Casa Principal</h3>
                            <span class="icono">+</span>
                        </div>
                        <div class="acordeon-contenido">
                            <div class="grupo-formulario">
                                <label for="calle">Calle:</label>
                                <input type="text" id="calle" name="calle" value="Olavarría 961" required>
                            </div>
                            <div class="grupo-formulario">
                                <label for="altura">Altura:</label>
                                <input type="text" id="altura" name="altura" value="123" required>
                            </div>
                            <div class="grupo-formulario">
                                <label for="entre-calles">Entre calles:</label>
                                <input type="text" id="entre-calles" name="entre-calles" value="Av. San Juan y Av. Caseros">
                            </div>
                            <div class="grupo-formulario">
                                <label for="detalles-repartidor">Detalles para el repartidor:</label>
                                <input type="text" id="detalles-repartidor" name="detalles-repartidor" value="La casa de rejas negras frente a la tienda de ropa">
                            </div>
                            <div class="grupo-formulario">
                                <label for="edificio-condominio">Edificio/Condominio:</label>
                                <input type="text" id="edificio-condominio" name="edificio-condominio" value="Edificio Central">
                            </div>
                            <div class="switch-entrega">
                                <label>Entrega:</label>
                                <div class="switch">
                                    <input type="checkbox" id="entrega-switch">
                                    <span class="slider">
                                        <span class="texto-izquierda">Dejar en portería</span>
                                        <span class="texto-derecha">Recibir personalmente</span>
                                    </span>
                                </div>
                            </div>
                            <div class="grupo-formulario">
                                <label for="instrucciones-adicionales">Instrucciones adicionales (opcional):</label>
                                <textarea id="instrucciones-adicionales" name="instrucciones-adicionales" rows="4">Dejar en portería si no estoy</textarea>
                            </div>
                            <div class="mapa">
                                <iframe
                                    src="https://www.openstreetmap.org/export/embed.html?bbox=-58.40,-34.65,-58.35,-34.60&layer=mapnik&marker=-34.6350,-58.3628"
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                    </div>

                    <!-- Botón para agregar nueva dirección -->
                    <div class="boton-agregar-direccion">
                        <button id="agregar-direccion">Agregar Nueva Dirección</button>
                    </div>

                    <!-- Formulario de nueva dirección (oculto por defecto) -->
                    <div class="formulario-nueva-direccion">
                        <h3>Nueva Dirección</h3>
                        <form id="formulario-nueva-direccion">
                            <div class="grupo-formulario">
                                <label for="nueva-calle">Calle:</label>
                                <input type="text" id="nueva-calle" name="nueva-calle" required>
                            </div>
                            <div class="grupo-formulario">
                                <label for="nueva-altura">Altura:</label>
                                <input type="text" id="nueva-altura" name="nueva-altura" required>
                            </div>
                            <div class="grupo-formulario">
                                <label for="nueva-entre-calles">Entre calles:</label>
                                <input type="text" id="nueva-entre-calles" name="nueva-entre-calles">
                            </div>
                            <div class="grupo-formulario">
                                <label for="nueva-detalles-repartidor">Detalles para el repartidor:</label>
                                <input type="text" id="nueva-detalles-repartidor" name="nueva-detalles-repartidor">
                            </div>
                            <div class="grupo-formulario">
                                <label for="nueva-edificio-condominio">Edificio/Condominio:</label>
                                <input type="text" id="nueva-edificio-condominio" name="nueva-edificio-condominio">
                            </div>
                            <div class="switch-entrega">
                                <label>Entrega:</label>
                                <div class="switch">
                                    <input type="checkbox" id="nueva-entrega-switch">
                                    <span class="slider">
                                        <span class="texto-izquierda">Dejar en portería</span>
                                        <span class="texto-derecha">Recibir personalmente</span>
                                    </span>
                                </div>
                            </div>
                            <div class="grupo-formulario">
                                <label for="nueva-instrucciones-adicionales">Instrucciones adicionales (opcional):</label>
                                <textarea id="nueva-instrucciones-adicionales" name="nueva-instrucciones-adicionales" rows="4"></textarea>
                            </div>
                            <button type="submit">Guardar Dirección</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script para manejar el acordeón
        document.querySelectorAll('.acordeon-cabecera').forEach(cabecera => {
            cabecera.addEventListener('click', () => {
                const contenido = cabecera.nextElementSibling;
                contenido.classList.toggle('activo');
                const icono = cabecera.querySelector('.icono');
                icono.textContent = contenido.classList.contains('activo') ? '-' : '+';
            });
        });

        // Script para mostrar/ocultar el formulario de nueva dirección
        document.getElementById('agregar-direccion').addEventListener('click', () => {
            const formularioNuevaDireccion = document.querySelector('.formulario-nueva-direccion');
            formularioNuevaDireccion.classList.toggle('activo');
        });

        // Script para el switch de entrega
        document.getElementById('entrega-switch').addEventListener('change', function() {
            const textoIzquierda = document.querySelector('.texto-izquierda');
            const textoDerecha = document.querySelector('.texto-derecha');

            if (this.checked) {
                textoIzquierda.style.color = '#555'; // Gris
                textoDerecha.style.color = '#28a745'; // Verde
            } else {
                textoIzquierda.style.color = '#28a745'; // Verde
                textoDerecha.style.color = '#555'; // Gris
            }
        });

        document.getElementById('nueva-entrega-switch').addEventListener('change', function() {
            const textoIzquierda = document.querySelector('.nueva-texto-izquierda');
            const textoDerecha = document.querySelector('.nueva-texto-derecha');

            if (this.checked) {
                textoIzquierda.style.color = '#555'; // Gris
                textoDerecha.style.color = '#28a745'; // Verde
            } else {
                textoIzquierda.style.color = '#28a745'; // Verde
                textoDerecha.style.color = '#555'; // Gris
            }
        });
    </script>
</body>
</html>