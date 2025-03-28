<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - Mayorisander</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <!-- Encabezado -->
    <header>
        <div class="logo">
            <img src="../assets/img/logo.png" alt="Logo de Mayorisander">
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

    <!-- Contenido principal -->
    <main>
        <h1>Nuestros Productos</h1>
        <section class="productos">
            <div class="producto">
                <img src="../assets/img/producto1.jpg" alt="Producto 1">
                <h3>Producto 1</h3>
                <p>Descripción breve del producto.</p>
                <span class="precio">$20.00</span>
                <button onclick="añadirAlCarrito('Producto 1', 20)">Añadir al carrito</button>
            </div>
            <!-- Repite para más productos -->
        </section>
    </main>

    <!-- Pie de página -->
    <footer>
        <p>&copy; 2023 Mayorisander. Todos los derechos reservados.</p>
    </footer>

    <!-- Enlace al archivo JavaScript -->
    <script src="../assets/js/script.js"></script>
</body>
</html>