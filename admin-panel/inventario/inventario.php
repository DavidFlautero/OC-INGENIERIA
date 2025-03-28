<?php
require_once '../../config/db.php';
require_once '../../models/producto.php';
require_once '../../models/Categoria.php';
require_once '../../controllers/PromocionController.php';

$producto = new Producto();
$categoriaModel = new Categoria();
$promoController = new PromocionController();

// Obtener parámetros de paginación
$paginaProductos = isset($_GET['pagina_productos']) ? (int)$_GET['pagina_productos'] : 1;
$paginaPromociones = isset($_GET['pagina_promociones']) ? (int)$_GET['pagina_promociones'] : 1;
$paginaBajoStock = isset($_GET['pagina_bajo_stock']) ? (int)$_GET['pagina_bajo_stock'] : 1;

// Límite de elementos por página
$porPagina = 4;

// Obtener datos con paginación
$productos = $producto->getAll(10, ($paginaProductos - 1) * 10); // Paginación para productos
$promociones = $producto->getPromociones($porPagina, ($paginaPromociones - 1) * $porPagina);
$productosBajoStock = $producto->getBajoStock(5, $porPagina, ($paginaBajoStock - 1) * $porPagina);

// Contar totales para paginación
$totalPromociones = $producto->countPromociones();
$totalBajoStock = $producto->countBajoStock(5);

// Obtener datos adicionales
$productosMasVendidos = $producto->getMasVendidos();
$categorias = $categoriaModel->getAll();
$sugerencias = $promoController->generarSugerencias();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Inventario - Online Tienda</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="../dist/css/adminlte.css" />
  <style>
    .btn-action { margin: 2px; }
    .status-active { color: #28a745; }
    .status-paused { color: #dc3545; }
    .card { margin-bottom: 20px; box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075); }
    .product-image { max-width: 60px; height: auto; border-radius: 4px; }
    .table-responsive { border-radius: 4px; overflow: hidden; }
    .badge-vendidos { background-color: #4e73df; }
    .badge-stock { background-color: #e74a3b; }
    .img-preview { max-width: 100px; height: auto; margin: 5px; border: 2px solid #dee2e6; border-radius: 5px; }
  </style>
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <!-- Header Original COMPLETO -->
    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
          </li>
          <li class="nav-item d-none d-md-block">
            <a href="#" class="nav-link">Inicio</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
              <img src="../../dist/assets/img/user2-160x160.jpg" class="user-image rounded-circle shadow" alt="User Image" />
              <span class="d-none d-md-inline">Administrador</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
              <li class="user-header text-bg-primary">
                <img src="../../dist/assets/img/user2-160x160.jpg" class="rounded-circle shadow" alt="User Image" />
                <p>
                  Administrador
                  <small>Miembro desde Nov. 2025</small>
                </p>
              </li>
              <li class="user-footer">
                <a href="#" class="btn btn-default btn-flat">Perfil</a>
                <a href="#" class="btn btn-default btn-flat float-end">Cerrar sesión</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Sidebar Original COMPLETO -->
    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
      <div class="sidebar-brand">
        <a href="index.html" class="brand-link">
          <img src="../dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
          <span class="brand-text fw-light">Online Tienda</span>
        </a>
      </div>
      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
            <!-- Dashboard -->
            <li class="nav-item">
              <a href="../dist/pages/index.html" class="nav-link">
                <i class="nav-icon bi bi-speedometer"></i>
                <p>Dashboard</p>
              </a>
            </li>

            <!-- Productos -->
            <li class="nav-item">
              <a href="#" class="nav-link active">
                <i class="nav-icon bi bi-box-seam-fill"></i>
                <p>Productos</p>
              </a>
            </li>

            <!-- Inventario -->
            <li class="nav-item">
              <a href="inventario.php" class="nav-link">
                <i class="nav-icon bi bi-clipboard-data-fill"></i>
                <p>Inventario</p>
              </a>
            </li>

            <!-- Pedidos -->
            <li class="nav-item">
              <a href="../pages/pedidos/index.html" class="nav-link">
                <i class="nav-icon bi bi-cart-fill"></i>
                <p>Pedidos</p>
              </a>
            </li>

            <!-- Reclamos -->
            <li class="nav-item">
              <a href="../pages/reclamos/index.html" class="nav-link">
                <i class="nav-icon bi bi-exclamation-circle-fill"></i>
                <p>Reclamos</p>
              </a>
            </li>

            <!-- Calendario -->
            <li class="nav-item">
              <a href="../pages/calendario/index.html" class="nav-link">
                <i class="nav-icon bi bi-calendar-event-fill"></i>
                <p>Calendario</p>
              </a>
            </li>

            <!-- Usuarios -->
            <li class="nav-item">
              <a href="../pages/usuarios/index.html" class="nav-link">
                <i class="nav-icon bi bi-people-fill"></i>
                <p>Usuarios</p>
              </a>
            </li>

            <!-- Descuentos -->
            <li class="nav-item">
              <a href="../pages/descuentos/index.html" class="nav-link">
                <i class="nav-icon bi bi-tag-fill"></i>
                <p>Descuentos</p>
              </a>
            </li>

            <!-- Facturas -->
            <li class="nav-item">
              <a href="../pages/facturas/index.html" class="nav-link">
                <i class="nav-icon bi bi-receipt-cutoff"></i>
                <p>Facturas</p>
              </a>
            </li>

            <!-- Mensajes -->
            <li class="nav-item">
              <a href="../pages/mensajes/index.html" class="nav-link">
                <i class="nav-icon bi bi-chat-left-text-fill"></i>
                <p>Mensajes</p>
              </a>
            </li>
          </ul>
        </nav>
      </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Gestión de Inventario</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active">Inventario</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <!-- Botón para cargar facturas -->
          <div class="row mb-3">
            <div class="col-md-4">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cargarFacturaModal">
                <i class="bi bi-receipt me-2"></i>Cargar Factura
              </button>
            </div>
          </div>

          <!-- Modal para cargar facturas -->
         <!-- Modal para cargar facturas -->
<div class="modal fade" id="cargarFacturaModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-receipt"></i> Cargar Factura</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Formulario para cargar facturas -->
        <form id="formCargarFactura" action="../../controllers/FacturaController.php" method="POST" enctype="multipart/form-data">
          <!-- Paso 1: Subir Foto de la Factura -->
          <div id="paso1">
            <div class="mb-3">
              <label for="fotoFactura" class="form-label">Subir Foto de la Factura</label>
              <input type="file" class="form-control" id="fotoFactura" name="fotoFactura" accept="image/*" required>
            </div>
            <div id="mensajeOCR" class="alert alert-info" style="display: none;"></div>
            <button type="button" class="btn btn-primary" onclick="procesarFactura()">Procesar Factura</button>
          </div>

          <!-- Paso 2: Datos de la Factura -->
          <div id="paso2" style="display: none;">
            <div class="mb-3">
              <label for="proveedor" class="form-label">Proveedor</label>
              <input type="text" class="form-control" id="proveedor" name="proveedor" required>
            </div>
            <div class="mb-3">
              <label for="fechaCompra" class="form-label">Fecha de Compra</label>
              <input type="date" class="form-control" id="fechaCompra" name="fechaCompra" required>
            </div>
            <div class="mb-3">
              <label for="totalFactura" class="form-label">Total de la Factura</label>
              <input type="number" step="0.01" class="form-control" id="totalFactura" name="totalFactura" required>
            </div>
            <button type="button" class="btn btn-secondary" onclick="siguientePaso(1)">Anterior</button>
            <button type="button" class="btn btn-primary" onclick="siguientePaso(3)">Siguiente</button>
          </div>

          <!-- Paso 3: Agregar Productos -->
          <div id="paso3" style="display: none;">
            <div class="mb-3">
              <label for="productos" class="form-label">Productos</label>
              <div id="productos">
                <!-- Aquí se agregarán dinámicamente los productos -->
              </div>
              <button type="button" class="btn btn-secondary mt-2" onclick="agregarProducto()">
                <i class="bi bi-plus"></i> Agregar Producto
              </button>
            </div>
            <button type="button" class="btn btn-secondary" onclick="siguientePaso(2)">Anterior</button>
            <button type="button" class="btn btn-primary" onclick="siguientePaso(4)">Siguiente</button>
          </div>

          <!-- Paso 4: Resumen y Confirmación -->
          <div id="paso4" style="display: none;">
            <h5>Resumen de la Factura</h5>
            <p><strong>Proveedor:</strong> <span id="resumenProveedor"></span></p>
            <p><strong>Fecha de Compra:</strong> <span id="resumenFecha"></span></p>
            <p><strong>Total:</strong> $<span id="resumenTotal"></span></p>
            <h6>Productos:</h6>
            <ul id="resumenProductos"></ul>
            <button type="button" class="btn btn-secondary" onclick="siguientePaso(3)">Anterior</button>
            <button type="submit" class="btn btn-success">Guardar Factura</button>
          </div>

          <input type="hidden" name="action" value="guardarFactura">
        </form>
      </div>
    </div>
  </div>
</div>

          <!-- Tabla de inventario -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Lista de Productos</h3>
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Nombre</th>
                      <th>Precio</th>
                      <th>Stock</th>
                      <th>Categoría</th>
                      <th>Imagen</th>
                      <th>Destacado</th>
                      <th>Estado</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($productos as $prod): ?>
                      <tr data-status="<?= $prod['estado'] ?>">
                        <td><?= htmlspecialchars($prod['nombre_producto']) ?></td>
                        <td>$<?= number_format($prod['precio_producto'], 2) ?></td>
                        <td><?= $prod['stock_producto'] ?></td>
                        <td><?= htmlspecialchars($prod['nombre_categoria']) ?></td>
                        <td>
                          <?php if (!empty($prod['nombre_imagen'])): ?>
                            <img src="../assets/uploads/imagenes_productos/<?= $prod['nombre_imagen'] ?>" 
                                 class="product-image" 
                                 alt="<?= htmlspecialchars($prod['nombre_producto']) ?>">
                          <?php else: ?>
                            <span class="text-muted">No hay imagen disponible.</span>
                          <?php endif; ?>
                        </td>
                        <td>
                          <span class="badge <?= $prod['destacado'] === 'si' ? 'bg-warning' : 'bg-secondary' ?>">
                            <?= $prod['destacado'] === 'si' ? 'Destacado' : 'Normal' ?>
                          </span>
                        </td>
                        <td>
                          <span class="badge <?= $prod['estado'] === 'active' ? 'bg-success' : 'bg-danger' ?>">
                            <?= $prod['estado'] === 'active' ? 'Activo' : 'Pausado' ?>
                          </span>
                        </td>
                        <td>
                          <div class="d-flex gap-2">
                            <a href="editar.php?id=<?= $prod['id_producto'] ?>" 
                               class="btn btn-sm btn-warning btn-action">
                              <i class="bi bi-pencil"></i>
                            </a>
                            <button onclick="confirmDelete(<?= $prod['id_producto'] ?>)" 
                                    class="btn btn-sm btn-danger btn-action">
                              <i class="bi bi-trash"></i>
                            </button>
                            <form method="POST" action="/onlinetienda/controllers/PromocionController.php">
                              <input type="hidden" name="action" value="toggleEstado">
                              <input type="hidden" name="id_producto" value="<?= $prod['id_producto'] ?>">
                              <button type="submit" 
                                      class="btn btn-sm <?= $prod['estado'] === 'active' ? 'btn-secondary' : 'btn-success' ?>">
                                <?= $prod['estado'] === 'active' ? 'Pausar' : 'Activar' ?>
                              </button>
                            </form>
                          </div>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Footer Original COMPLETO -->
    <footer class="app-footer">
      <strong>Copyright &copy; 2025 Online Tienda.</strong> Todos los derechos reservados.
    </footer>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Función para agregar productos dinámicamente
    function agregarProducto() {
      const productosDiv = document.getElementById('productos');
      const productoHTML = `
        <div class="row g-3 mb-3">
          <div class="col-md-4">
            <input type="text" class="form-control" name="nombreProducto[]" placeholder="Nombre del Producto" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" class="form-control" name="precioUnitario[]" placeholder="Precio Unitario" required>
          </div>
          <div class="col-md-2">
            <input type="number" class="form-control" name="cantidad[]" placeholder="Cantidad" required>
          </div>
          <div class="col-md-2">
            <input type="number" step="0.01" class="form-control" name="precioTotal[]" placeholder="Precio Total" required>
          </div>
          <div class="col-md-2">
            <button type="button" class="btn btn-danger" onclick="eliminarProducto(this)">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      `;
      productosDiv.insertAdjacentHTML('beforeend', productoHTML);
    }

    // Función para eliminar productos
    function eliminarProducto(boton) {
      boton.closest('.row').remove();
    }

    // Confirmar eliminación
    function confirmDelete(id) {
      if (confirm('¿Estás seguro de eliminar este producto permanentemente?')) {
        window.location.href = `controllers/ProductoController.php?action=delete&id=${id}`;
      }
    }
  </script>
</body>
</html>