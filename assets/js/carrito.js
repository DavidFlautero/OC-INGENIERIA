// carrito.js
$(document).ready(function () {
    // Función para abrir el slider del carrito
    function abrirSliderCarrito() {
        $('#slider-carrito').addClass('abierto');
        mostrarResumenCarrito();
    }

    // Función para cerrar el slider del carrito
    $('.cerrar-slider').on('click', function () {
        $('#slider-carrito').removeClass('abierto');
    });

    // Función para mostrar el resumen del carrito
    function mostrarResumenCarrito() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const resumen = $('#resumen-carrito');
        const totalPrecioSlider = $('#total-precio-slider');
        const btnContinuarCompra = $('#btn-continuar-compra'); // Selecciona el botón

        if (carrito.length === 0) {
            resumen.html('<div class="alert alert-info">Tu carrito está vacío.</div>');
            totalPrecioSlider.text('$0.00');
            btnContinuarCompra.prop('disabled', true); // Deshabilita el botón si el carrito está vacío
            return;
        }

        let html = '';
        let total = 0;

        carrito.forEach((producto, indice) => {
            const subtotal = producto.precio * producto.cantidad;
            total += subtotal;

            html += `
                <div class="item-carrito">
                    <img src="${producto.imagen}" alt="${producto.nombre}" style="width: 50px; height: 50px;">
                    <div>
                        <p>${producto.nombre} - $${producto.precio.toFixed(2)}</p>
                        <div class="cantidad-control">
                            <button onclick="disminuirCantidad(${indice})">-</button>
                            <span>${producto.cantidad}</span>
                            <button onclick="aumentarCantidad(${indice})">+</button>
                        </div>
                        <button onclick="eliminarProductoSlider(${indice})" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            `;
        });

        // Botón para continuar al checkout
        html += `<button id="btn-continuar-compra" class="btn btn-primary" ${total < 50000 ? 'disabled' : ''}>Continuar compra</button>`;

        resumen.html(html);
        totalPrecioSlider.text(`$${total.toFixed(2)}`);

        // Habilitar o deshabilitar el botón según el total
        if (total < 50000) {
            btnContinuarCompra.prop('disabled', true); // Deshabilita el botón si el total es menor a $50,000
        } else {
            btnContinuarCompra.prop('disabled', false); // Habilita el botón si el total es igual o mayor a $50,000
        }
    }

    // Redirigir al checkout
    window.irAlCheckout = function () {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito.length === 0) {
            alert('Tu carrito está vacío.');
            return;
        }
        window.location.href = 'checkout.html';
    };

    // Funciones para manejar el carrito
    window.aumentarCantidad = function (indice) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[indice]) {
            carrito[indice].cantidad += 1;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            mostrarResumenCarrito();
            actualizarContadorCarrito();
        }
    };

    window.disminuirCantidad = function (indice) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        if (carrito[indice] && carrito[indice].cantidad > 1) {
            carrito[indice].cantidad -= 1;
            localStorage.setItem('carrito', JSON.stringify(carrito));
            mostrarResumenCarrito();
            actualizarContadorCarrito();
        }
    };

    window.eliminarProductoSlider = function (indice) {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        carrito.splice(indice, 1);
        localStorage.setItem('carrito', JSON.stringify(carrito));
        mostrarResumenCarrito();
        actualizarContadorCarrito();
    };

    // Función para añadir productos al carrito
    window.añadirAlCarrito = function (id, nombre, precio, imagen) {
        const producto = { id, nombre, precio, imagen, cantidad: 1 };
        let carrito = JSON.parse(localStorage.getItem('carrito')) || [];

        const productoExistente = carrito.find(p => p.id === id);
        if (productoExistente) {
            productoExistente.cantidad += 1;
        } else {
            carrito.push(producto);
        }

        localStorage.setItem('carrito', JSON.stringify(carrito));
        alert(`¡${nombre} añadido al carrito!`);
        actualizarContadorCarrito();
        abrirSliderCarrito();
    };

    // Función para actualizar el contador del carrito
    function actualizarContadorCarrito() {
        const carrito = JSON.parse(localStorage.getItem('carrito')) || [];
        const carritoCount = $('#carrito-count');
        if (carritoCount.length) {
            carritoCount.text(carrito.length);
        }
    }
});