<?php


// Si usas algún autoloader o configuración de base de datos, inclúyelo aquí
require_once __DIR__ . '/../../config/db.php';  // Ajusta la ruta según tu estructura
require_once __DIR__ . '/../../models/FacturaModel.php';
require_once __DIR__ . '/../../models/Producto.php';


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cargar Factura - Online Tienda</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
  <style>
    .buscador-container {
      position: relative;
    }

    .resultados-busqueda {
      position: absolute;
      z-index: 1000;
      width: 100%;
      max-height: 200px;
      overflow-y: auto;
      background: white;
      border: 1px solid #ddd;
      border-top: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: none;
    }

    .resultado-item {
      padding: 10px;
      cursor: pointer;
      border-bottom: 1px solid #eee;
    }

    .resultado-item:hover {
      background-color: #f8f9fa;
    }

    #previsualizacionImagen {
      display: none;
      margin-top: 10px;
    }

    #imagenPrevisualizada {
      max-width: 100%;
      height: auto;
    }

    #cargando {
      display: none;
      margin-top: 10px;
    }
    
    /* Nuevos estilos para la tabla de precios */
    .table-precios th {
      white-space: nowrap;
      vertical-align: middle;
    }
    
    .margen-positivo {
      color: #28a745;
      font-weight: bold;
    }
    
    .margen-negativo {
      color: #dc3545;
      font-weight: bold;
    }
    
    .precio-compra {
      background-color: #fff3cd;
    }
    
    .precio-venta {
      background-color: #d4edda;
    }
  </style>
</head>
<body class="bg-light">
  <div class="container mt-5">
    <h1 class="text-center mb-4">Cargar Factura</h1>

    <!-- Formulario de carga de factura -->
    <form id="formCargarFactura" enctype="multipart/form-data" method="POST">
      <!-- Campo para subir la imagen -->
      <div class="mb-3">
        <label for="fotoFactura" class="form-label">Foto de la Factura</label>
        <input type="file" class="form-control" id="fotoFactura" name="fotoFactura" accept="image/*" required>
      </div>

      <!-- Previsualización de la imagen -->
      <div id="previsualizacionImagen">
        <img id="imagenPrevisualizada" src="#" alt="Previsualización de la factura">
      </div>

      <!-- Indicador de "Cargando" -->
      <div id="cargando">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Cargando...</span>
        </div>
        <span>Procesando factura...</span>
      </div>

      <!-- Datos del proveedor y fecha -->
      <div class="row mb-3">
        <div class="col-md-6">
          <label for="proveedor" class="form-label">Proveedor</label>
          <input type="text" class="form-control" id="proveedor" name="proveedor" required>
        </div>
        <div class="col-md-6">
          <label for="fecha" class="form-label">Fecha de Factura</label>
          <input type="date" class="form-control" id="fecha" name="fecha" required>
        </div>
      </div>

      <!-- Buscador de productos -->
      <div class="mb-3 buscador-container">
        <label class="form-label">Buscar Productos</label>
        <input type="text" class="form-control" id="buscadorProductos" placeholder="Escribe para buscar...">
        <div class="resultados-busqueda" id="resultadosBusqueda"></div>
      </div>

      <!-- Tabla de productos - VERSIÓN MEJORADA -->
      <div class="table-responsive mt-4">
        <table class="table table-bordered table-precios">
          <thead class="table-dark">
            <tr>
              <th>Producto</th>
              <th width="100">Cantidad</th>
              <th width="120" class="precio-compra">Precio Compra</th>
              <th width="120" class="precio-venta">Precio Venta</th>
              <th width="100">Margen %</th>
              <th width="120">Subtotal</th>
              <th width="100">Acciones</th>
            </tr>
          </thead>
          <tbody id="productosFactura">
            <!-- Filas dinámicas -->
          </tbody>
          <tfoot>
            <tr>
              <td colspan="5" class="text-end"><strong>Total:</strong></td>
              <td id="totalFactura" class="fw-bold">0.00</td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Campos ocultos para el total y datos adicionales -->
      <input type="hidden" name="total" id="totalHidden">
      <input type="hidden" name="tipo_factura" value="compra">

      <!-- Botón para guardar la factura -->
      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-lg btn-primary mt-3">
          <i class="bi bi-save me-2"></i>Guardar Factura
        </button>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    let productosSeleccionados = [];

    // Función para limpiar el texto
    function limpiarTexto(texto) {
      // Eliminar caracteres extraños
      texto = texto.replace(/[^a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ.,]/g, '');

      // Corregir espacios dobles
      texto = texto.replace(/\s+/g, ' ');

      // Normalizar nombres comunes
      texto = texto.replace(/PARLAwrE/g, 'Parlante');
      texto = texto.replace(/riBUR0H/g, 'Tiburón');
      texto = texto.replace(/AL ZAAFARAH/g, 'Al Zaafarah');

      return texto.trim();
    }

    // Función para corregir nombres de productos
    function corregirNombreProducto(nombre) {
      const correcciones = {
        "PARLAwrE SONIDO": "Parlante Sonido",
        "riBUR0H LUZ Y SONIDO 30CN": "Tiburón Luz y Cascada",
        "ARD AL ZAAFARAH mm HOOD 50W.": "Al Zaafarah Hood 50W"
      };

      // Buscar el nombre en el diccionario de correcciones
      if (correcciones[nombre]) {
        return correcciones[nombre];
      }

      // Si no hay corrección, devolver el nombre original
      return nombre;
    }

    // Función para calcular el margen de ganancia
    function calcularMargen(precioCompra, precioVenta) {
      if (!precioCompra || precioCompra <= 0) return 0;
      const margen = ((precioVenta - precioCompra) / precioCompra) * 100;
      return parseFloat(margen.toFixed(2));
    }

    // Previsualización de la imagen
    document.getElementById('fotoFactura').addEventListener('change', function(e) {
      const imagenFactura = e.target.files[0];
      console.log("Imagen seleccionada:", imagenFactura);

      if (!imagenFactura) {
        alert('Por favor, selecciona una imagen de la factura.');
        return;
      }

      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('imagenPrevisualizada').src = e.target.result;
        document.getElementById('previsualizacionImagen').style.display = 'block';
      };
      reader.readAsDataURL(imagenFactura);

      // Mostrar "Cargando"
      document.getElementById('cargando').style.display = 'block';

      // Procesar la imagen con OCR.Space
      procesarImagenConOCR(imagenFactura)
        .then(data => {
          console.log("Datos extraídos del OCR:", data);
          completarFormulario(data);
        })
        .catch(error => {
          console.error('Error al procesar la factura:', error);
          alert('Error al procesar la factura: ' + error.message);
        })
        .finally(() => {
          document.getElementById('cargando').style.display = 'none';
        });
    });

    // Función para procesar la imagen con OCR.Space
    async function procesarImagenConOCR(imagen) {
      const apiKey = 'K81117657188957'; // Reemplaza con tu API Key de OCR.Space
      const url = "https://api.ocr.space/parse/image";

      const formData = new FormData();
      formData.append('apikey', apiKey);
      formData.append('language', 'spa'); // Idioma español
      formData.append('isOverlayRequired', false);
      formData.append('file', imagen);

      const response = await fetch(url, {
        method: 'POST',
        body: formData,
      });

      if (!response.ok) {
        throw new Error('Error en la respuesta del servidor');
      }

      const result = await response.json();
      if (result.IsErroredOnProcessing) {
        throw new Error(result.ErrorMessage);
      }

      const textoExtraido = result.ParsedResults[0].ParsedText;
      return parsearTextoFactura(textoExtraido);
    }

    function parsearTextoFactura(texto) {
      const datos = { productos: [] };

      // Limpiar el texto antes de procesarlo
      texto = limpiarTexto(texto);

      // Extraer proveedor, fecha y total
      const proveedorMatch = texto.match(/Proveedor:\s*(.*)/i);
      const fechaMatch = texto.match(/Fecha:\s*(\d{4}-\d{2}-\d{2})/i);
      const totalMatch = texto.match(/Total:\s*\$?([\d,.]+)/i);

      datos.proveedor = proveedorMatch ? proveedorMatch[1].trim() : null;
      datos.fecha = fechaMatch ? fechaMatch[1].trim() : null;
      datos.total = totalMatch ? parseFloat(totalMatch[1].replace(',', '')) : null;

      // Detectar sección de productos
      const seccionProductos = texto.split("P duc to")[1];
      if (!seccionProductos) {
        console.log("No se encontró la sección de productos.");
        return datos;
      }

      // Separar nombres de productos y cantidades/precios
      const partes = seccionProductos.split(/(\d+\s+\d+)/);
      let nombresProductos = [];
      let cantidades = [];
      let precios = [];

      for (let i = 0; i < partes.length; i++) {
        const parte = partes[i].trim();
        if (!parte) continue;

        if (parte.match(/[a-zA-Z]/)) {
          nombresProductos.push(parte);
        }

        const cantidadPrecioMatch = parte.match(/(\d+)\s+(\d+)/);
        if (cantidadPrecioMatch) {
          const cantidad = parseInt(cantidadPrecioMatch[1].trim());
          const precio = parseFloat(`${cantidadPrecioMatch[1]}.${cantidadPrecioMatch[2]}`);
          cantidades.push(cantidad);
          precios.push(precio);
        }
      }

      // Asociar nombres, cantidades y precios
      for (let i = 0; i < nombresProductos.length; i++) {
        if (nombresProductos[i] && cantidades[i] && precios[i]) {
          datos.productos.push({
            nombre: corregirNombreProducto(nombresProductos[i]),
            cantidad: cantidades[i],
            precio_compra: precios[i]
          });
        }
      }

      return datos;
    }

    // Función para completar los campos del formulario
    function completarFormulario(datos) {
      if (datos.proveedor) {
        document.getElementById('proveedor').value = datos.proveedor;
      }
      if (datos.fecha) {
        document.getElementById('fecha').value = datos.fecha;
      }
      if (datos.total) {
        document.getElementById('totalHidden').value = datos.total;
        document.getElementById('totalFactura').textContent = datos.total.toFixed(2);
      }

      if (datos.productos.length > 0) {
        verificarProductosEnBD(datos.productos);
      }
    }

    // Función para verificar productos en la base de datos
    function verificarProductosEnBD(productosExtraidos) {
      fetch('../../controllers/search_admin.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productos: productosExtraidos })
      })
      .then(response => response.json())
      .then(data => {
        productosSeleccionados = data.map(p => ({
          id: p.id_producto,
          nombre: p.nombre_producto,
          cantidad: p.cantidad || 1,
          precio_compra: p.precio_compra || p.precio_producto, // Usar precio de compra o precio_producto como fallback
          precio_venta: p.precio_producto,
          margen: calcularMargen(p.precio_compra || p.precio_producto, p.precio_producto),
          existente: p.existente
        }));

        actualizarTabla();
      })
      .catch(error => {
        console.error('Error al verificar productos:', error);
      });
    }

    // Buscador en vivo
    document.getElementById('buscadorProductos').addEventListener('input', function(e) {
      const query = e.target.value.trim();
      const resultados = document.getElementById('resultadosBusqueda');

      if (query.length >= 2) {
        fetch(`../../controllers/search_admin.php?query=${encodeURIComponent(query)}`)
          .then(response => response.json())
          .then(data => {
            if (data.length > 0) {
              resultados.innerHTML = data.map(producto => `
                <div class="resultado-item" 
                     data-id="${producto.id_producto}"
                     data-nombre="${producto.nombre_producto}"
                     data-precio-compra="${producto.precio_producto}"
                     data-precio-venta="${producto.precio_producto}"
                     onclick="seleccionarProducto(this)">
                  <img src="../../admin-panel/assets/uploads/imagenes_productos/${producto.imagen}" alt="${producto.nombre_producto}" style="width: 50px; height: 50px; margin-right: 10px;">
                  ${producto.nombre_producto} - Compra: $${producto.precio_producto} | Venta: $${producto.precio_producto}
                </div>
              `).join('');
              resultados.style.display = 'block';
            } else {
              resultados.innerHTML = '<div class="resultado-item">No se encontraron productos</div>';
              resultados.style.display = 'block';
            }
          })
          .catch(error => {
            console.error('Error en la búsqueda:', error);
            resultados.innerHTML = '<div class="resultado-item text-danger">Error al buscar productos</div>';
            resultados.style.display = 'block';
          });
      } else {
        resultados.style.display = 'none';
      }
    });

    // Función para seleccionar un producto
    function seleccionarProducto(elemento) {
      const producto = {
        id: elemento.dataset.id,
        nombre: elemento.dataset.nombre,
        precio_compra: elemento.dataset.precioCompra,
        precio_venta: elemento.dataset.precioVenta,
        cantidad: 1,
        margen: calcularMargen(elemento.dataset.precioCompra, elemento.dataset.precioVenta),
        existente: true
      };

      const existe = productosSeleccionados.some(p => p.id === producto.id);
      if (!existe) {
        productosSeleccionados.push(producto);
        actualizarTabla();
      }

      document.getElementById('buscadorProductos').value = '';
      document.getElementById('resultadosBusqueda').style.display = 'none';
    }

    // Función para actualizar la tabla de productos
    function actualizarTabla() {
      const tbody = document.getElementById('productosFactura');
      const total = productosSeleccionados.reduce((sum, p) => sum + (p.precio_compra * p.cantidad), 0);

      tbody.innerHTML = productosSeleccionados.map((p, index) => {
        // Recalcular margen cada vez
        p.margen = calcularMargen(p.precio_compra, p.precio_venta);
        
        return `
        <tr class="${p.existente ? '' : 'producto-nuevo'}">
          <td>${p.nombre}
            <input type="hidden" name="productos[${index}][id]" value="${p.id || ''}">
            <input type="hidden" name="productos[${index}][nombre]" value="${p.nombre}">
            <input type="hidden" name="productos[${index}][existente]" value="${p.existente ? 1 : 0}">
          </td>
          <td>
            <input type="number" class="form-control" 
                   name="productos[${index}][cantidad]" 
                   value="${p.cantidad}" 
                   min="1" 
                   onchange="actualizarCantidad(${index}, this.value)">
          </td>
          <td>
            <input type="number" step="0.01" class="form-control precio-compra"
                   name="productos[${index}][precio_compra]" 
                   value="${p.precio_compra}"
                   onchange="actualizarPrecioCompra(${index}, this.value)">
          </td>
          <td class="precio-venta">
            $${parseFloat(p.precio_venta).toFixed(2)}
            <input type="hidden" name="productos[${index}][precio_venta]" value="${p.precio_venta}">
          </td>
          <td class="${p.margen >= 0 ? 'margen-positivo' : 'margen-negativo'}">
            ${p.margen}%
          </td>
          <td>$${(p.precio_compra * p.cantidad).toFixed(2)}</td>
          <td>
            <button type="button" class="btn btn-danger btn-sm" 
                    onclick="eliminarProducto(${index})">
              <i class="bi bi-trash"></i>
            </button>
          </td>
        </tr>
      `}).join('');

      document.getElementById('totalFactura').textContent = total.toFixed(2);
      document.getElementById('totalHidden').value = total.toFixed(2);
    }

    // Función para actualizar la cantidad de un producto
    function actualizarCantidad(index, nuevaCantidad) {
      productosSeleccionados[index].cantidad = parseInt(nuevaCantidad);
      actualizarTabla();
    }

    // Función para actualizar el precio de compra
    function actualizarPrecioCompra(index, nuevoPrecio) {
      productosSeleccionados[index].precio_compra = parseFloat(nuevoPrecio);
      actualizarTabla();
    }

    // Función para eliminar un producto de la tabla
    function eliminarProducto(index) {
      productosSeleccionados.splice(index, 1);
      actualizarTabla();
    }

    // Validación del formulario
    document.getElementById('formCargarFactura').addEventListener('submit', function(e) {
      if (productosSeleccionados.length === 0) {
        e.preventDefault();
        alert('Debe agregar al menos un producto antes de guardar la factura.');
      }
      
      // Validación adicional de márgenes
      const margenesBajos = productosSeleccionados.filter(p => {
        return p.margen < (p.margen_minimo || 20); // 20% es el margen mínimo por defecto
      });
      
      if (margenesBajos.length > 0) {
        const confirmar = confirm(`Algunos productos tienen márgenes bajos (menos del 20%). ¿Desea continuar?`);
        if (!confirmar) {
          e.preventDefault();
        }
      }
    });

    // Función para calcular el margen (global)
    window.calcularMargen = calcularMargen;
  </script>
</body>
</html>