<?php
session_start(); // Inicia la sesión si no está iniciada
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras - Mayorisander</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- FontAwesome para los íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pagina-carrito">
    <!-- Encabezado sin barra de búsqueda -->
    <header>
        <div class="logo">
            <img src="../assets/images/logo.png" alt="Logo de Mayorisander">
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="../pages/productos.php">Productos</a></li>
                <li><a href="../pages/login.php">Iniciar Sesión</a></li>
                <li>
                    <a href="../pages/carrito.php">
                        <i class="fas fa-shopping-cart"></i> Carrito
                        <span id="carrito-count">0</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Contenido principal -->
    <div class="container">
        <h1>Carrito de Compras</h1>

        <!-- Mostrar mensajes de confirmación o error -->
        <?php if (isset($_SESSION['carrito_agregado'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['carrito_agregado'] ?>
            </div>
            <?php unset($_SESSION['carrito_agregado']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['carrito_eliminado'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['carrito_eliminado'] ?>
            </div>
            <?php unset($_SESSION['carrito_eliminado']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['carrito_actualizado'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['carrito_actualizado'] ?>
            </div>
            <?php unset($_SESSION['carrito_actualizado']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_carrito'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error_carrito'] ?>
            </div>
            <?php unset($_SESSION['error_carrito']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['carrito_vacio'])): ?>
            <div class="alert alert-info">
                <?= $_SESSION['carrito_vacio'] ?>
            </div>
            <?php unset($_SESSION['carrito_vacio']); ?>
        <?php endif; ?>

        <!-- Mostrar productos del carrito -->
        <div id="carrito-productos">
            <!-- Los productos se cargarán aquí mediante JavaScript -->
        </div>

        <!-- Mostrar el total de la compra -->
        <div id="total-carrito" class="text-right">
            <h3>Total: <span id="total-precio">$0.00</span></h3>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Función para mostrar los productos del carrito
        function mostrarCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const contenedor = document.getElementById('carrito-productos');
            const totalPrecio = document.getElementById('total-precio');

            if (carrito.length === 0) {
                contenedor.innerHTML = '<div class="alert alert-info">Tu carrito está vacío.</div>';
                totalPrecio.textContent = '$0.00';
                return;
            }

            let html = '<table class="table"><thead><tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th><th>Acciones</th></tr></thead><tbody>';
            let total = 0;

            carrito.forEach((producto, indice) => {
                const subtotal = producto.precio * producto.cantidad;
                total += subtotal;

                html += `
                    <tr>
                        <td>${producto.nombre}</td>
                        <td>$${producto.precio.toFixed(2)}</td>
                        <td>
                            <button onclick="cambiarCantidad(${indice}, -1)" class="btn btn-secondary">-</button>
                            ${producto.cantidad}
                            <button onclick="cambiarCantidad(${indice}, 1)" class="btn btn-secondary">+</button>
                        </td>
                        <td>$${subtotal.toFixed(2)}</td>
                        <td>
                            <button onclick="eliminarProducto(${indice})" class="btn btn-danger">Eliminar</button>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table>';
            contenedor.innerHTML = html;
            totalPrecio.textContent = `$${total.toFixed(2)}`;
        }

        // Función para cambiar la cantidad de un producto
        function cambiarCantidad(indice, cambio) {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            carrito[indice].cantidad += cambio;
            if (carrito[indice].cantidad < 1) {
                carrito.splice(indice, 1);
            }
            localStorage.setItem('carrito', JSON.stringify(carrito));
            mostrarCarrito();
            actualizarContadorCarrito();
        }

        // Función para eliminar un producto
        function eliminarProducto(indice) {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            carrito.splice(indice, 1);
            localStorage.setItem('carrito', JSON.stringify(carrito));
            mostrarCarrito();
            actualizarContadorCarrito();
        }

        // Función para actualizar el contador del carrito
        function actualizarContadorCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const carritoCount = document.getElementById('carrito-count');
            if (carritoCount) {
                carritoCount.textContent = carrito.length;
            }
        }

        // Mostrar el carrito al cargar la página
        document.addEventListener('DOMContentLoaded', mostrarCarrito);
        // Actualizar el contador del carrito al cargar la página
        document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
    </script>
    <!-- Modal del carrito -->
<div id="modal-carrito" class="modal">
    <div class="modal-contenido">
        <span class="cerrar-modal">&times;</span>
        <h2>Carrito de Compras</h2>
        <div id="resumen-carrito">
            <!-- Los productos se cargarán aquí mediante JavaScript -->
        </div>
        <div id="total-carrito-modal" class="text-right">
            <h3>Total: <span id="total-precio-modal">$0.00</span></h3>
        </div>
        <a href="carrito.php" class="btn btn-ver-carrito">Ver Carrito</a>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Función para abrir el modal del carrito
    function abrirModalCarrito() {
        $('#modal-carrito').css('display', 'block');
        mostrarResumenCarrito();
    }

    // Función para cerrar el modal del carrito
    $('.cerrar-modal').on('click', function() {
        $('#modal-carrito').css('display', 'none');
    });

    // Función para mostrar el resumen del carrito en el modal
    function mostrarResumenCarrito() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const resumen = $('#resumen-carrito');
        const totalPrecioModal = $('#total-precio-modal');

        if (carrito.length === 0) {
            resumen.html('<div class="alert alert-info">Tu carrito está vacío.</div>');
            totalPrecioModal.text('$0.00');
            return;
        }

        let html = '';
        let total = 0;

        carrito.forEach((producto, indice) => {
            const subtotal = producto.precio * producto.cantidad;
            total += subtotal;

            html += `
                <div class="item-carrito">
                    <img src="${producto.imagen}" alt="${producto.nombre}">
                    <div>
                        <p>${producto.nombre} - $${producto.precio.toFixed(2)} x ${producto.cantidad}</p>
                        <button onclick="eliminarProductoModal(${indice})" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            `;
        });

        resumen.html(html);
        totalPrecioModal.text(`$${total.toFixed(2)}`);
    }

    // Función para eliminar un producto desde el modal
    window.eliminarProductoModal = function(indice) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        carrito.splice(indice, 1);
        localStorage.setItem('carrito', JSON.stringify(carrito));
        mostrarResumenCarrito();
        actualizarContadorCarrito();
    };

    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const carritoCount = $('#carrito-count');
        if (carritoCount.length) {
            carritoCount.text(carrito.length);
        }
    }

    // Mostrar el carrito al cargar la página
    document.addEventListener('DOMContentLoaded', mostrarResumenCarrito);
    // Actualizar el contador del carrito al cargar la página
    document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);
</script>
</body>
</html>