// assets/js/infinite-scroll.js

$(document).ready(function() {
    // Variables globales para controlar la paginación
    let currentPage = 1;
    let totalPages = 1;
    let isLoading = false;
    let currentSubcategory = null;
    let currentSearch = '';
    let currentPriceFilter = 'all';
    let xhr; // FIX: Nueva variable para controlar solicitudes AJAX
    let scrollTimeout; // FIX: Para debounce

    // Función principal para cargar productos
    function cargarProductos(reset = false) {
        if (isLoading || currentPage > totalPages) return;

        // FIX: Cancelar solicitud anterior
        if (xhr && xhr.readyState !== 4) xhr.abort();

        isLoading = true;
        $('#loading-spinner').show();

        // Parámetros de la solicitud
        const params = {
            subcategoria: currentSubcategory,
            pagina: reset ? 1 : currentPage, // FIX: Resetear página si es necesario
            busqueda: currentSearch,
            precio: currentPriceFilter
        };

        // Determinar la URL según el tipo de filtro
        const url = currentSubcategory 
            ? 'controllers/ProductoController.php?action=listarPorSubcategoria'
            : 'controllers/ProductoController.php?action=filtrarProductos';

        xhr = $.ajax({ // FIX: Guardar referencia de la solicitud
            url: url,
            method: 'GET',
            data: params,
            dataType: 'json',
            success: function(response) {
                try {
                    console.log("Respuesta del servidor:", response);

                    if (response.success) {
                        if (reset) {
                            $('#productos-destacados').empty();
                            currentPage = 1; // FIX: Resetear aquí también
                        }

                        if (response.data.productos && response.data.productos.length > 0) {
                            mostrarProductos(response.data.productos, reset);
                            totalPages = response.data.paginas;
                            currentPage++;
                        } else {
                            if (reset) mostrarError("No se encontraron productos.");
                        }
                    } else {
                        console.error("Error del backend:", response.error);
                        mostrarError(response.error || "Error al cargar los productos.");
                    }
                } catch (e) {
                    console.error("Error al procesar la respuesta:", e);
                    mostrarError("Error en el formato de la respuesta del servidor.");
                }
            },
            error: function(xhr, status, error) {
                // FIX: Ignorar errores por cancelación
                if (status !== "abort") {
                    console.error("Error en la solicitud:", error);
                    mostrarError("Error de conexión al cargar los productos.");
                }
            },
            complete: function() {
                isLoading = false;
                $('#loading-spinner').hide();
            }
        });
    }

    // Función para mostrar productos (COMPLETAMENTE PRESERVADA)
    function mostrarProductos(productos, reset = false) {
        let html = '';
        productos.forEach(producto => {
            if (!producto.nombre_producto || !producto.precio_producto) {
                console.error("Producto con campos obligatorios faltantes:", producto);
                return;
            }

            const imagen = producto.nombre_imagen ? `/admin-panel/assets/uploads/imagenes_productos/${producto.nombre_imagen}` : 'admin-panel/assets/uploads/imagenes_productos/producto_1_imagen_1.jpg';
			
            const descripcion = producto.descripcion_producto || "Sin descripción";
            const precio = parseFloat(producto.precio_producto);

            if (isNaN(precio)) {
                console.error("Precio inválido para el producto:", producto);
                return;
            }

            if (producto.stock_producto <= 0) {
                console.error("Producto sin stock:", producto);
                return;
            }

            html += `
                <div class="col-md-4 mb-4">
                    <div class="card producto">
                        <img src="${imagen}" class="card-img-top" alt="${producto.nombre_producto}">
                        <div class="card-body">
                            <h5 class="card-title">${producto.nombre_producto}</h5>
                            <p class="card-text">${descripcion}</p>
                            <p class="card-text">$${precio.toFixed(2)}</p>
                            <button class="btn btn-primary" 
                                onclick="añadirAlCarrito(
                                    ${producto.id_producto}, 
                                    '${producto.nombre_producto}', 
                                    ${precio}, 
                                    '${imagen}'
                                )">
                                Añadir al carrito
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        if (reset) {
            $('#productos-destacados').html(html);
        } else {
            $('#productos-destacados').append(html);
        }
    }

    // Función para mostrar errores (PRESERVADA)
    function mostrarError(mensaje) {
        $('#productos-destacados').html(`
            <div class="col-12 text-center">
                <div class="alert alert-danger">${mensaje}</div>
            </div>
        `);
    }

    // Evento: Click en subcategorías (CORREGIDO)
    $('.subcategorias a').on('click', function(e) {
        e.preventDefault();
        if (xhr) xhr.abort(); // FIX: Cancelar solicitud anterior
        currentSubcategory = $(this).data('id');
        currentPage = 1;
        totalPages = 1; // FIX: Reset crítico
        currentSearch = '';
        currentPriceFilter = 'all';
        $('#busqueda').val('');
        $('#filtro-precio').val('all');
        cargarProductos(true);
    });

    // Evento: Aplicar filtros (CORREGIDO)
    $('#aplicar-filtros').on('click', function() {
        if (xhr) xhr.abort(); // FIX: Cancelar solicitud anterior
        currentSubcategory = null;
        currentPage = 1;
        totalPages = 1; // FIX: Reset crítico
        currentSearch = $('#busqueda').val();
        currentPriceFilter = $('#filtro-precio').val();
        cargarProductos(true);
    });

    // Evento: Scroll infinito (CORREGIDO)
    $(window).scroll(function() {
        clearTimeout(scrollTimeout); // FIX: Debounce
        scrollTimeout = setTimeout(() => {
            const scrollPosition = $(window).scrollTop() + $(window).height();
            const documentHeight = $(document).height();
            
            if (documentHeight - scrollPosition < 100 && !isLoading && currentPage <= totalPages) {
                cargarProductos();
            }
        }, 300);
    });

    // Carga inicial de productos destacados
  // cargarProductos(false);
});