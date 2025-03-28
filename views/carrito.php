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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pagina-carrito">
    <!-- Encabezado -->
    <header>
        <div class="logo">
            <img src="../assets/images/logo.png" alt="Logo de Mayorisander">
        </div>
        <nav>
            <ul>
                <li><a href="../index.php">Inicio</a></li>
                <li><a href="productos.php">Productos</a></li>
                <li><a href="login.php">Iniciar Sesión</a></li>
                <li>
                    <a href="carrito.php">
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

        <!-- Mensajes de sesión -->
        <?php if (isset($_SESSION['carrito_agregado'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['carrito_agregado'] ?>
            </div>
            <?php unset($_SESSION['carrito_agregado']); ?>
        <?php endif; ?>

        <!-- Contenedor de productos del carrito -->
        <div id="carrito-productos">
            <!-- Los productos se cargarán aquí mediante JavaScript -->
        </div>

        <!-- Total de la compra -->
        <div id="total-carrito" class="text-right">
            <h3>Total: <span id="total-precio">$0.00</span></h3>
        </div>
    </div>

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
            if (carrito[indice]) {
                carrito[indice].cantidad += cambio;
                if (carrito[indice].cantidad < 1) {
                    carrito.splice(indice, 1);
                }
                localStorage.setItem('carrito', JSON.stringify(carrito));
                mostrarCarrito();
                actualizarContadorCarrito();
            }
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
        document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);

        // Funcionalidad del modal
        function abrirModalCarrito() {
            document.getElementById('modal-carrito').style.display = 'block';
            mostrarResumenCarrito();
        }

        function cerrarModalCarrito() {
            document.getElementById('modal-carrito').style.display = 'none';
        }

        function mostrarResumenCarrito() {
            const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
            const resumen = document.getElementById('resumen-carrito');
            const totalPrecioModal = document.getElementById('total-precio-modal');

            if (carrito.length === 0) {
                resumen.innerHTML = '<div class="alert alert-info">Tu carrito está vacío.</div>';
                totalPrecioModal.textContent = '$0.00';
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

            resumen.innerHTML = html;
            totalPrecioModal.textContent = `$${total.toFixed(2)}`;
        }

        // Cerrar modal al hacer clic en la "X"
        document.querySelector('.cerrar-modal').addEventListener('click', cerrarModalCarrito);

        // Eliminar producto desde el modal
        window.eliminarProductoModal = function(indice) {
            eliminarProducto(indice);
            mostrarResumenCarrito();
        };
    </script>
</body>
</html>