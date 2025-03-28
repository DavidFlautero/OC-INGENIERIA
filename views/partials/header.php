<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mayorisander - Tienda Online</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Dependencias de Slick Slider -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
</head>
<body class="<?= basename($_SERVER['PHP_SELF']) === 'carrito.php' ? 'pagina-carrito' : '' ?>">
    <!-- Encabezado -->
    <header>
        <div class="logo">
            <img src="../assets/images/logo.png" alt="Logo de Mayorisander">
        </div>
        <!-- Barra de búsqueda -->
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Buscar producto...">
            <div id="search-results"></div> <!-- Aquí se mostrarán las sugerencias -->
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li><a href="carrito.php"><i class="fas fa-shopping-cart"></i> Carrito</a></li>
            </ul>
        </nav>
    </header>

    <!-- Script para manejar el carrito -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Función para actualizar el contador del carrito
        function actualizarContadorCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const carritoCount = document.getElementById('carrito-count');
            carritoCount.textContent = carrito.length;
        }

        // Actualizar el contador del carrito al cargar la página
        document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
    </script>