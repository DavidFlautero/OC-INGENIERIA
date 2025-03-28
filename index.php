<?php
session_start(); // Inicia la sesión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zenith  - Cart</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script src="assets/js/searchcar.js"></script>
    <script src="assets/js/carrito.js"></script>
    <script src="assets/js/auth.js"></script>
    <script src="assets/js/whatsapp.js"></script>
    <script src="assets/js/slider.js"></script>
    <script src="assets/js/infinite-scroll.js"></script>
 
</head>
<body>
    <!-- Barra superior -->
    <div class="top-bar">
        <!-- Íconos de redes sociales (izquierda) -->
        <div class="social-icons">
            <a href="https://www.instagram.com/mayorisander/" target="_blank">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="https://wa.me/tu_numero" target="_blank">
                <i class="fab fa-whatsapp"></i>
            </a>
        </div>

        <!-- Mensaje deslizante (centro) -->
        <div class="message-bar">
            <div class="messages">
                <?php
                // Simulación de mensajes dinámicos (luego los cargaremos desde la base de datos)
                $messages = [
                    "¡Envíos a todo el país!",
                    "¡Descuentos exclusivos por tiempo limitado!",
                    "¡Nuevos productos disponibles!",
                    "¡Compra minima 50.000!"
                ];
                foreach ($messages as $message) {
                    echo "<p>$message</p>";
                }
                ?>
            </div>
        </div>

        <!-- Correo y ubicación (derecha) -->
        <div class="contact-info">
            <a href="mailto:alexandervillamil1987@gmail.com">
                <i class="fas fa-envelope"></i>
            </a>
            <a href="https://maps.google.com/?q=URIBURU 754" target="_blank">
                <i class="fas fa-map-marker-alt"></i>
            </a>
        </div>
    </div>

    <!-- Encabezado -->
    <header>
        <div class="logo">
            <img src="assets/images/logo.png" alt="Logo de Mayorisander">
        </div>
        <div class="search-bar">
            <form action="./config/search.php" method="GET">
                <input type="text" id="search-input" name="query" placeholder="Buscar producto..." autocomplete="off">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i> <!-- Ícono de lupa -->
                </button>
                <div id="search-results" class="search-results"></div> <!-- Resultados de autocompletado -->
            </form>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Inicio</a></li>
                <li><a href="#" onclick="openLoginSlider()">Iniciar Sesión</a></li>
                <li>
                    <a href="views/carrito.php">
                        <i class="fas fa-shopping-cart"></i> Carrito
                        <span id="carrito-count">0</span>
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    <!-- Sección de Categorías -->
    <section class="categorias-homecenter">
        <div class="lista-categorias">
            <!-- Categoría 1: Bazar y Hogar -->
            <div class="categoria">
            <h3>Bazar y Hogar</h3>
            <div class="subcategorias">
                <a href="#" data-id="1">Blanquería</a>
                <a href="#" data-id="2">Herramientas, grifería y balanzas</a>
                <a href="#" data-id="3">Luces, lámparas, focos y cámaras</a>
                <a href="#" data-id="4">Relojes y espejos</a>
            </div>
        </div>

        <!-- Categoría 2: Accesorios -->
        <div class="categoria">
            <h3>Accesorios</h3>
            <div class="subcategorias">
                <a href="#" data-id="5">Accesorios para auto, moto y bici</a>
                <a href="#" data-id="6">Accesorios para PC y TV</a>
                <a href="#" data-id="7">Auriculares</a>
                <a href="#" data-id="8">Cables</a>
                <a href="#" data-id="9">Cargadores</a>
                <a href="#" data-id="10">Parlantes</a>
                <a href="#" data-id="11">Billeteras, mochilas y bolsos</a>
            </div>
        </div>

        <!-- Categoría 3: Belleza y Cuidado Personal -->
        <div class="categoria">
            <h3>Belleza y Cuidado Personal</h3>
            <div class="subcategorias">
                <a href="#" data-id="12">Cabello</a>
                <a href="#" data-id="13">Cuidado facial y personal</a>
                <a href="#" data-id="14">Maquillaje facial</a>
                <a href="#" data-id="15">Brochas y pinceles</a>
                <a href="#" data-id="16">Labios</a>
                <a href="#" data-id="17">Ojos</a>
                <a href="#" data-id="18">Uñas</a>
                <a href="#" data-id="19">Perfumes</a>
            </div>
        </div>

        <!-- Categoría 4: Fitness y Bienestar -->
        <div class="categoria">
            <h3>Fitness y Bienestar</h3>
            <div class="subcategorias">
                <a href="#" data-id="20">Fitness, masajeadores y más</a>
                <a href="#" data-id="21">Defensa personal y supervivencia</a>
            </div>
        </div>

        <!-- Categoría 5: Juegos -->
        <div class="categoria">
            <h3>Juegos</h3>
            <div class="subcategorias">
                <a href="#" data-id="22">Videojuegos</a>
                <a href="#" data-id="23">Juegos de mesa</a>
            </div>
        </div>

        <!-- Categoría 6: Celulares -->
        <div class="categoria">
            <h3>Celulares</h3>
            <div class="subcategorias">
                <a href="#" data-id="24">Smartphones</a>
                <a href="#" data-id="25">Accesorios para celular</a>
                <a href="#" data-id="26">Fundas y protectores</a>
                <a href="#" data-id="27">Repuestos</a>
            </div>
        </div>

        <!-- Categoría 7: Útiles -->
        <div class="categoria">
            <h3>Útiles</h3>
            <div class="subcategorias">
                <a href="#" data-id="28">Útiles de oficina</a>
                <a href="#" data-id="29">Útiles escolares</a>
            </div>
        </div>

        <!-- Categoría 8: Temporadas -->
        <div class="categoria">
            <h3>Temporadas</h3>
            <div class="subcategorias">
                <a href="#" data-id="30">Verano</a>
                <a href="#" data-id="31">Otoño Invierno</a>
                <a href="#" data-id="32">Navidad</a>
            </div>
        </div>
    </div>
</section>
<!-- Filtros -->
<div class="filtros">
    <input type="text" id="busqueda" placeholder="Buscar por nombre...">
    <select id="filtro-precio">
        <option value="">Todos los precios</option>
        <option value="0-50">$0 - $50</option>
        <option value="50-100">$50 - $100</option>
        <option value="100-200">$100 - $200</option>
    </select>
    <button id="aplicar-filtros">Filtrar</button>
</div>
               

    <!-- Slider -->
    <div class="slider-container">
        <section class="slider">
            <div>
                <img src="assets/images/slider1.png" alt="Slide 1">
            </div>
            <div>
                <img src="assets/images/slider2.png" alt="Slide 2">
            </div>
            <div>
                <img src="assets/images/slider3.png" alt="Slide 3">
            </div>
        </section>
    </div>

    <!-- Sección de Servicios -->
    <section class="services-grid">
        <div class="service-item">
            <i class="fas fa-shopping-cart" style="color: black; font-size: 24px;"></i>
            <h3>¿CÓMO COMPRAR?</h3>
            <a href="#">Ver más</a>
        </div>
        <div class="service-item">
            <i class="fas fa-truck" style="color: black; font-size: 24px;"></i>
            <h3>¿Hacen Envíos?</h3>
            <h4>Hacemos envíos a todo el país con el expreso que quieras</h4>
        </div>
        <div class="service-item">
            <i class="fas fa-credit-card" style="color: black; font-size: 24px;"></i>
            <h3>¿Como Pago?</h3>
            <h4>Aceptamos Mercado Pago, transferencías y depósitos bancarios y pagos en el local</h4>
        </div>
        <div class="service-item">
            <i class="fas fa-tag" style="color: black; font-size: 24px;"></i>
            <h3>¿Tienen compra mínima?</h3>
            <h4>Si $50.000 Podés combinar entre diferentes artículos</h4>
        </div>
    </section>

    <section class="destacados">
        <h2 id="titulo-seccion">Productos Destacados</h2>
        
        <!-- Spinner de carga -->
        <div id="loading-spinner">
            <div class="loading-spinner"></div>
            <p>Cargando productos...</p>
        </div>
        
        <div id="productos-destacados" class="productos">
            <!-- Los productos se cargarán aquí mediante JavaScript -->
        </div>
    </section>

    <!-- Slider lateral del carrito -->
    <div id="slider-carrito" class="slider-carrito">
        <div class="slider-contenido">
            <span class="cerrar-slider">&times;</span>
            <h2>Resumen del Carrito</h2>
            <div id="resumen-carrito">
                <!-- Los productos se cargarán aquí mediante JavaScript -->
            </div>
            <div id="total-carrito-slider" class="text-right">
                <h3>Total: <span id="total-precio-slider">$0.00</span></h3>
            </div>
            <!-- Botón "Continuar compra" y mensaje de sesión -->
            <button class="btn-continuar">Continuar compra</button>
            <div class="mensaje-sesion">
                Para continuar, <a onclick="abrirSliderLogin()">inicia sesión</a> o <a onclick="showRegisterForm()">crea una cuenta</a>.
            </div>
        </div>
    </div>

    <!-- Botón flotante de WhatsApp -->
    <div id="whatsapp-button">
        <img src="assets/images/whatsapp.png" alt="WhatsApp" width="50" height="50">
    </div>

    <!-- Modal para seleccionar asistente -->
    <div id="whatsapp-modal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <p>Selecciona un operador :</p>
            <div class="operadoras">
                <!-- Operadora 1 -->
                <div class="operadora">
                    <img src="assets/images/operadora1.png" alt="Operadora 1">
                    <p>Juanita</p>
                    <a href="https://wa.me/+1131380761?text=Hola,%20quiero%20hablar%20con%20la%20Operadora%201." target="_blank">Chatear</a>
                </div>
                <!-- Operadora 2 -->
                <div class="operadora">
                    <img src="assets/images/operadora2.png" alt="Operadora 2">
                    <p>Valentina</p>
                    <a href="https://wa.me/+541127612109?text=Hola,%20quiero%20hablar%20con%20la%20Operadora%202." target="_blank">Chatear</a>
                </div>
                <!-- Operador disponible ahora -->
                <div id="operador-disponible" class="operadora" style="display: none;">
                    <img src="assets/images/operador.png" alt="Operador Disponible">
                    <p>Operador NO Disponible</p>
                    <a href="https://wa.me/+541127612109?text=Hola,%20quiero%20hablar%20con%20el%20Operador%20Disponible." target="_blank">Chatear Ahora</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Slider de Inicio de Sesión -->
    <div id="loginSlider" class="login-slider">
        <div class="login-content">
            <!-- Botón para cerrar el slider -->
            <button class="close-slider" onclick="closeLoginSlider()">&times;</button>

            <!-- Formulario de Inicio de Sesión -->
            <div id="loginForm">
                <h3>Iniciar Sesión</h3>
                <form id="loginFormContent" action="controllers/LoginController.php" method="POST">
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <input type="email" id="email" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" id="password" name="password" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                </form>
                <div class="login-links">
                    <button type="button" onclick="showRegisterForm()" class="btn-create-account">Crear cuenta</button>
                    <a href="#" onclick="showForgotPasswordForm()">¿Olvidaste tu contraseña?</a>
                </div>
            </div>

            <!-- Formulario de Registro -->
            <div id="registerForm" style="display: none">
                <h3>Crear Cuenta</h3>
                <form id="registerFormContent" onsubmit="registerUser(event)">
                    <div class="form-group">
                        <label for="regName">Nombre completo</label>
                        <input type="text" id="regName" name="nombre" required />
                    </div>
                    <div class="form-group">
                        <label for="regEmail">Correo electrónico</label>
                        <input type="email" id="regEmail" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="regPhone">Número de teléfono</label>
                        <input type="tel" id="regPhone" name="telefono" required />
                    </div>
                    <div class="form-group">
                        <label for="regPassword">Contraseña</label>
                        <input type="password" id="regPassword" name="password" required />
                    </div>
                    <div class="form-group">
                        <label for="regConfirmPassword">Confirmar Contraseña</label>
                        <input type="password" id="regConfirmPassword" name="confirm_password" required />
                    </div>
                    <button type="submit" class="btn btn-primary">Registrarse</button>
                </form>
            </div>

            <!-- Paso de Verificación -->
            <div id="verificationStep" style="display: none; opacity: 0; transform: translateY(20px); transition: all 0.3s ease-in-out;">
                <h3>Verificación</h3>
                <p>Te enviaremos un código de verificación por el medio que elijas.</p>
                <div class="form-group">
                    <input type="radio" id="verificationEmail" name="verificationMethod" value="email" checked />
                    <label for="verificationEmail">Correo electrónico</label>
                    <input type="radio" id="verificationWhatsApp" name="verificationMethod" value="whatsapp" />
                    <label for="verificationWhatsApp">WhatsApp</label>
                </div>
                <div class="form-group verification-input">
                    <input type="text" id="verificationCode" placeholder="Ingresa el código" />
                    <button class="btn btn-secondary" onclick="sendVerificationCode()">Enviar código</button>
                </div>
                <p id="resendCode" style="cursor: pointer; color: blue; text-decoration: underline;" onclick="resendCode()">Reenviar código</p>
            </div>
        </div>
    </div>

    <!-- Script para cargar productos dinámicamente -->
<script>
$(document).ready(function() {
    // Variables globales para paginación
    let currentPage = 1;
    let totalPages = 1;
    let isLoading = false;
    let currentSubcategory = null;
    let currentSubcategoryName = '';

    // Función para cargar productos destacados
    function loadDestacados() {
        console.log("Cargando productos destacados..."); // Debug
        $.ajax({
            url: 'controllers/ProductoController.php?action=getProductosDestacados',
            method: 'GET',
            success: function(response) {
                try {
                    // Verificar si la respuesta es un JSON válido
                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }

                    console.log("Respuesta del servidor (destacados):", response); // Debug

                    if (response.success) {
                        mostrarProductos(response.data, true); // true para limpiar
                    } else {
                        console.error("Error del backend (destacados):", response.error);
                    }
                } catch (e) {
                    console.error("Error al procesar la respuesta JSON (destacados):", e);
                    console.error("Respuesta del servidor (destacados):", response); // Debug
                }
            },
            error: function(xhr, status, error) {
                console.error("Error en la solicitud AJAX (destacados):", error);
                console.error("Respuesta del servidor (destacados):", xhr.responseText); // Debug
            }
        });
    }

    // Evento de clic en subcategorías
    $('.subcategorias a').on('click', function(e) {
        e.preventDefault();
        currentSubcategory = $(this).data('id');
        currentSubcategoryName = $(this).text().trim();
        currentPage = 1;
        
        // Cambiar título
        $('#titulo-seccion').text(currentSubcategoryName);
        
        cargarProductos(true); // true para limpiar
    });

    // Función unificada para cargar productos
    function cargarProductos(reset = false) {
        if (isLoading || currentPage > totalPages) return;

        isLoading = true;
        $('#loading-spinner').show();

        const params = {
            subcategoria: currentSubcategory,
            pagina: currentPage,
            busqueda: $('#busqueda').val(),
            precio: $('#filtro-precio').val(),
            action: currentSubcategory ? 'listarPorSubcategoria' : 'filtrarProductos'
        };

        $.ajax({
            url: 'controllers/ProductoController.php',
            method: 'GET',
            data: params,
            dataType: 'json',
            success: function(response) {
                try {
                    // Verificar si la respuesta es un JSON válido
					console.log("Respuesta del servidor:", response);
                    if (typeof response === 'string') {
                        response = JSON.parse(response);
                    }

                    console.log("Respuesta del servidor:", response); // Debug

                    if (response.success) {
                        if (response.data.productos && response.data.productos.length > 0) {
                            mostrarProductos(response.data.productos, reset);
                            totalPages = response.data.paginas;
                            currentPage++;
                        } else {
                            $('#productos-destacados').html('<p>No se encontraron productos para esta subcategoría.</p>');
                        }
                    } else {
                        console.error("Error del backend:", response.error);
                        $('#productos-destacados').html('<p>Error al cargar los productos.</p>');
                    }
                } catch (e) {
                    console.error("Error al procesar la respuesta JSON:", e);
                    console.error("Respuesta del servidor:", response); // Debug
                    $('#productos-destacados').html('<p>Error en el formato de la respuesta del servidor.</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error AJAX:", error);
                console.error("Respuesta del servidor:", xhr.responseText); // Debug
                $('#productos-destacados').html('<p>Error de conexión al cargar los productos.</p>');
            },
            complete: function() {
                isLoading = false;
                $('#loading-spinner').hide();
            }
        });
    }

    // Scroll infinito
    $(window).scroll(function() {
        const scrollPosition = $(window).scrollTop() + $(window).height();
        const documentHeight = $(document).height();
        
        console.log("Scroll position:", scrollPosition, "Document height:", documentHeight); // Debug
        
        if (documentHeight - scrollPosition < 200 && !isLoading) {
            console.log("Cargando más productos..."); // Debug
            cargarProductos();
        }
    });

    // Función para mostrar productos
    function mostrarProductos(productos, reset = false) {
        let html = '';
        productos.forEach(producto => {
            // Validar campos obligatorios
            if (!producto.imagen_producto || !producto.nombre_producto || !producto.descripcion_producto || !producto.precio_producto) {
                console.error("Producto con campos faltantes:", producto);
                return; // Saltar este producto
            }

            // Asegurarse de que el precio sea un número válido
            const precio = parseFloat(producto.precio_producto);
            if (isNaN(precio)) {
                console.error("Precio inválido para el producto:", producto);
                return; // Saltar este producto
            }

            html += `
                <div class="producto">
                    <img src="assets/images/${producto.imagen_producto}" alt="${producto.nombre_producto}">
                    <h3>${producto.nombre_producto}</h3>
                    <p>${producto.descripcion_producto}</p>
                    <span class="precio">$${precio.toFixed(2)}</span>
                    <button onclick="añadirAlCarrito(${producto.id_producto}, '${producto.nombre_producto}', ${precio}, 'assets/images/${producto.imagen_producto}')">Añadir al carrito</button>
                </div>
            `;
        });

        if (reset) {
            $('#productos-destacados').html(html);
        } else {
            $('#productos-destacados').append(html);
        }
    }

    // Aplicar filtros
    $('#aplicar-filtros').on('click', function() {
        // currentSubcategory = null; // Comentar si no es necesario resetear
        currentPage = 1;
        cargarProductos(true);
    });

    // Código existente de mensajes deslizantes
    $.ajax({
        url: 'controllers/MensajeController.php',
        method: 'GET',
        success: function(response) {
            try {
                const data = JSON.parse(response);
                if (data.success && data.data.length > 0) {
                    console.log("Mensajes cargados:", data.data); // Debug
                    let html = '';
                    data.data.forEach(mensaje => {
                        html += `<p>${mensaje.texto_mensaje}</p>`;
                    });
                    $('#messages-container').html(html);
                } else {
                    console.log("No hay mensajes disponibles."); // Debug
                    $('#messages-container').html('<p>¡Ofertas especiales disponibles!</p>');
                }
            } catch (e) {
                console.error("Error al procesar mensajes: ", e);
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en la solicitud AJAX (mensajes): ", error);
            $('#messages-container').html('<p>¡Bienvenido a Mayorisander!</p>');
        }
    });

    // Cargar destacados al inicio
    loadDestacados();
});

// Función para añadir productos al carrito
function añadirAlCarrito(id, nombre, precio, imagen) {
    console.log("Añadiendo al carrito:", id, nombre, precio, imagen); // Debug

    // Aquí puedes implementar la lógica para añadir el producto al carrito
    // Por ejemplo, usar localStorage o hacer una solicitud AJAX al servidor
    alert(`Producto "${nombre}" añadido al carrito.`);
}
</script>

    <!-- Footer -->
    <?php include './views/partials/footer.php'; ?>
</body>
</html>