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
  <title>Panel de Administración - Online Tienda</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="../dist/css/adminlte.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
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

            <!-- Inventario -->
            <li class="nav-item">
              <a href="../pages/inventario/index.html" class="nav-link">
                <i class="nav-icon bi bi-clipboard-data-fill"></i>
                <p>Inventario</p>
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

    <!-- Contenido Principal ORIGINAL COMPLETO -->
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6">
              <h3 class="mb-0">Gestión de Productos</h3>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                <li class="breadcrumb-item active">Productos</li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
          <!-- Sección Superior ORIGINAL -->
          <div class="row">
            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><i class="bi bi-trophy me-2"></i>Más Vendidos</h3>
                </div>
                <div class="card-body p-0">
                  <?php if (!empty($productosMasVendidos)): ?>
                    <ul class="list-group list-group-flush">
                      <?php foreach ($productosMasVendidos as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <div>
                            <div class="fw-bold"><?= htmlspecialchars($item['nombre_producto']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($item['nombre_categoria']) ?></small>
                          </div>
                          <span class="badge badge-vendidos rounded-pill"><?= $item['total_vendido'] ?> ventas</span>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                  <?php else: ?>
                    <div class="alert alert-info m-3">No hay datos de ventas</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><i class="bi bi-exclamation-triangle me-2"></i>Bajo Stock</h3>
                </div>
                <div class="card-body p-0">
                  <?php if (!empty($productosBajoStock)): ?>
                    <ul class="list-group list-group-flush">
                      <?php foreach ($productosBajoStock as $item): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                          <div>
                            <div class="fw-bold"><?= htmlspecialchars($item['nombre_producto']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($item['nombre_categoria']) ?></small>
                          </div>
                          <span class="badge badge-stock rounded-pill"><?= $item['stock_producto'] ?> unidades</span>
                        </li>
                      <?php endforeach; ?>
                    </ul>
                    <!-- Botón "Ver más" para bajo stock -->
                    <?php if ($totalBajoStock > $paginaBajoStock * $porPagina): ?>
                      <div class="text-center mt-3">
                        <a href="?pagina_bajo_stock=<?= $paginaBajoStock + 1 ?>" class="btn btn-primary">Ver más</a>
                      </div>
                    <?php endif; ?>
                  <?php else: ?>
                    <div class="alert alert-success m-3">Stock en niveles óptimos</div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Promociones (con paginación) -->
          <div class="card mt-4">
            <div class="card-header bg-danger text-white">
              <h5><i class="bi bi-exclamation-triangle"></i> Acciones Recomendadas</h5>
            </div>
            <div class="card-body">
              <?php if (!empty($promociones)): ?>
                <div class="row">
                  <?php foreach ($promociones as $sugerencia): ?>
                    <div class="col-md-6 mb-3">
                      <div class="card h-100 border-danger">
                        <div class="card-body">
                          <h6 class="text-danger">
                            <?= htmlspecialchars($sugerencia['nombre_producto']) ?>
                          </h6>
                          <div class="row">
                            <div class="col-md-4">
                              <ul class="list-unstyled small">
                                <li>Stock: <?= $sugerencia['stock_producto'] ?></li>
                                <li>Precio: $<?= number_format($sugerencia['precio_producto'], 2) ?></li>
                              </ul>
                            </div>
                            <div class="col-md-8">
                              <div class="alert alert-warning">
                                <div class="d-flex justify-content-between align-items-center">
                                  <div>
                                    <strong>¡En oferta!</strong><br>
                                    <span class="h5">$<?= number_format($sugerencia['precio_producto'] * 0.9, 2) ?></span>
                                  </div>
                                  <div class="text-end">
                                    <button class="btn btn-sm btn-danger">Aplicar</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
                <!-- Botón "Ver más" para promociones -->
                <?php if ($totalPromociones > $paginaPromociones * $porPagina): ?>
                  <div class="text-center mt-3">
                    <a href="?pagina_promociones=<?= $paginaPromociones + 1 ?>" class="btn btn-primary">Ver más promociones</a>
                  </div>
                <?php endif; ?>
              <?php else: ?>
                <div class="alert alert-success">¡No hay promociones disponibles!</div>
              <?php endif; ?>
            </div>
          </div>
 <!-- Buscador de Edición -->
<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5><i class="bi bi-search"></i> Buscar Productos para Editar</h5>
    </div>
    <div class="card-body">
        <div class="input-group">
            <input type="text" 
                   id="buscadorEdicion" 
                   class="form-control" 
                   placeholder="Escribe nombre o ID del producto..."
                   aria-label="Buscar productos">
            <button class="btn btn-primary" type="button">
                <i class="bi bi-arrow-repeat"></i>
            </button>
        </div>
        
        <!-- Resultados de Búsqueda -->
        <div id="resultadosEdicion" class="mt-3" style="max-height: 400px; overflow-y: auto;">
            <!-- Resultados dinámicos -->
        </div>
    </div>
</div>

<!-- Modal de Edición -->
<div class="modal fade" id="modalEdicion" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Editar Producto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contenidoModalEdicion">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>
          <!-- Tabla de Productos ORIGINAL COMPLETA -->
          <div class="row mt-4">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <div class="row align-items-center g-2">
                    <div class="col-md-4 col-lg-3">
                      <button class="btn btn-primary w-100" 
                              data-bs-toggle="modal" 
                              data-bs-target="#nuevoProductoModal">
                        <i class="bi bi-plus-circle me-2"></i>Añadir
                      </button>
                    </div>
                    <div class="col-md-4 col-lg-3">
                      <select id="filter-status" class="form-select">
                        <option value="all">Todos</option>
                        <option value="active">Activos</option>
                        <option value="paused">Pausados</option>
                      </select>
                    </div>
                  </div>
                  <h3 class="card-title mt-3 mb-0">Lista de Productos</h3>
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
                  <!-- Botón "Ver más" para productos -->
                  <?php if ($producto->countAll() > $paginaProductos * 10): ?>
                    <div class="text-center mt-3">
                      <a href="?pagina_productos=<?= $paginaProductos + 1 ?>" class="btn btn-primary">Ver más productos</a>
                    </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
	<!-- Modal para crear productos -->
<div class="modal fade" id="nuevoProductoModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bi bi-box-seam"></i> Nuevo Producto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="ajaxMessage" class="mb-3"></div>
        <form id="formNuevoProducto" enctype="multipart/form-data">
          <div class="row g-3">
            <!-- Sección de información básica -->
            <div class="col-md-6">
              <label class="form-label">Nombre*</label>
              <input type="text" name="nombre" class="form-control" required>
            </div>
            
            <!-- Sección de precios -->
            <div class="col-md-6">
              <label class="form-label">Precio de venta*</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="precio" step="0.01" min="0.01" class="form-control" required
                       id="precioVenta" oninput="calcularMargen()">
              </div>
            </div>
            
            <div class="col-md-6">
              <label class="form-label">Costo de compra*</label>
              <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="costo" step="0.01" min="0.01" class="form-control" required
                       id="costoCompra" oninput="calcularMargen()">
              </div>
            </div>
            
            <div class="col-md-6">
              <label class="form-label">Margen estimado</label>
              <div class="input-group">
                <input type="text" id="margenCalculado" class="form-control bg-light" readonly>
                <span class="input-group-text">%</span>
              </div>
            </div>
            
            <!-- Selector de categoría -->
            <div class="col-md-6">
              <label class="form-label">Subcategoría*</label>
              <select name="categoria" class="form-select" required>
                <?php
                $categoriaModel = new Categoria();
                $categorias = $categoriaModel->getAll();
                
                while ($categoria = $categorias->fetch_object()) {
                  $subcategorias = $categoriaModel->getSubcategoriasByCategoria($categoria->id_categoria);
                  if ($subcategorias->num_rows > 0) {
                    while ($subcategoria = $subcategorias->fetch_object()) {
                      echo "<option value='{$subcategoria->id_subcategoria}'>";
                      echo htmlspecialchars($subcategoria->nombre_subcategoria);
                      echo "</option>";
                    }
                  }
                }
                ?>
              </select>
            </div>
            
            <!-- Stock -->
            <div class="col-md-6">
              <label class="form-label">Stock inicial</label>
              <input type="number" name="stock" class="form-control" value="0" min="0">
            </div>
            
            <!-- Descripción -->
            <div class="col-12">
              <label class="form-label">Descripción</label>
              <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>
            
            <!-- Opciones -->
            <div class="col-md-6">
              <label class="form-label">Oferta</label>
              <select name="oferta" class="form-select">
                <option value="no">No</option>
                <option value="si">Sí</option>
              </select>
            </div>
            
            <div class="col-md-6">
              <label class="form-label">Destacado</label>
              <select name="destacado" class="form-select">
                <option value="no">No</option>
                <option value="si">Sí</option>
              </select>
            </div>
            
            <!-- Imágenes -->
            <div class="col-12">
              <label class="form-label">Imágenes*</label>
              <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*" required>
              <small class="text-muted">Máximo 5 imágenes (JPEG, PNG, WEBP)</small>
              <div id="imagePreview" class="mt-2 d-flex flex-wrap gap-2"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        <button type="submit" form="formNuevoProducto" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>

<script>
// Función para calcular margen en tiempo real
function calcularMargen() {
  const precio = parseFloat(document.getElementById('precioVenta').value) || 0;
  const costo = parseFloat(document.getElementById('costoCompra').value) || 0;
  
  if (costo > 0 && precio >= costo) {
    const margen = ((precio - costo) / precio) * 100;
    document.getElementById('margenCalculado').value = margen.toFixed(2);
    document.getElementById('margenCalculado').classList.remove('text-danger');
    document.getElementById('margenCalculado').classList.add('text-success');
  } else {
    document.getElementById('margenCalculado').value = "Inválido";
    document.getElementById('margenCalculado').classList.remove('text-success');
    document.getElementById('margenCalculado').classList.add('text-danger');
  }
}

// Validación de precio vs costo
document.getElementById('formNuevoProducto').addEventListener('submit', function(e) {
  const precio = parseFloat(document.getElementById('precioVenta').value);
  const costo = parseFloat(document.getElementById('costoCompra').value);
  
  if (precio <= costo) {
    e.preventDefault();
    alert('El precio de venta debe ser mayor que el costo de compra');
    document.getElementById('precioVenta').focus();
  }
});
</script>

<style>
/* Estilos para el margen calculado */
#margenCalculado.text-success {
  font-weight: bold;
  color: #28a745 !important;
}
#margenCalculado.text-danger {
  font-weight: bold;
  color: #dc3545 !important;
}
</style>
</div>
    <!-- Footer Original COMPLETO -->
    <footer class="app-footer">
      <strong>Copyright &copy; 2025 Online Tienda.</strong> Todos los derechos reservados.
    </footer>
  </div>

  <!-- Scripts ORIGINALES + NUEVOS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Filtro por estado
    document.getElementById('filter-status').addEventListener('change', function() {
      const status = this.value;
      document.querySelectorAll('tbody tr').forEach(row => {
        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
      });
    });

    // Confirmar eliminación
    function confirmDelete(id) {
    if (confirm('¿Eliminar producto?')) {
        fetch(`../../controllers/ProductoController.php?action=eliminarProducto&id=${id}`, {
            method: 'DELETE' // Cambiado de GET a DELETE
        })
        .then(response => {
            if (response.ok) {
                // Eliminar la fila sin recargar
                document.querySelector(`tr[data-id="${id}"]`)?.remove();
                // Opcional: Recargar después de 1 seg
                setTimeout(() => location.reload(), 1000); 
            } else {
                alert('Error al eliminar');
            }
        });
    }
	}

    // Previsualización de imágenes
    document.querySelector('input[name="imagenes[]"]').addEventListener('change', function(e) {
      const preview = document.getElementById('imagePreview');
      preview.innerHTML = '';
      Array.from(this.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = document.createElement('img');
          img.src = e.target.result;
          img.className = 'img-preview';
          preview.appendChild(img);
        };
        reader.readAsDataURL(file);
      });
    });

    // Envío AJAX
    document.getElementById('formNuevoProducto').addEventListener('submit', function(e) {
      e.preventDefault();
      
      const formData = new FormData(this);
      formData.append('action', 'guardarProducto');
	// Validación adicional antes del fetch
		const precio = parseFloat(formData.get('precio'));
		if (precio <= 0) {
		document.getElementById('ajaxMessage').innerHTML = 
		'<div class="alert alert-danger">El precio debe ser mayor a cero</div>';
		return false; // Detiene el envío
	}
      fetch('../../controllers/ProductoController.php?action=guardarProducto', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        const messageDiv = document.getElementById('ajaxMessage');
        if (data.success) {
          messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        } else {
          messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
        }
      })
      .catch(error => {
        document.getElementById('ajaxMessage').innerHTML = 
          `<div class="alert alert-danger">Error al conectar con el servidor</div>`;
      });
    });
  </script>
  <script>
  function generarSeccionHistorial(historial) {
    return `
        <div class="card mb-3">
            <div class="card-header bg-info text-white">
                <h5 class="card-title mb-0">Registro de Cambios</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Razón del Cambio*</label>
                    <select name="razon_cambio" class="form-select" required>
                        ${generarOpcionesRazones()}
                    </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Historial Reciente</label>
                    <div class="historial-container">
                        ${historial.length > 0 ? 
                            historial.map(item => generarItemHistorial(item)).join('') : 
                            '<div class="alert alert-info">No hay historial</div>'}
                    </div>
                </div>
            </div>
        </div>
    `;
}
<script>

</script>
<script>
function generarItemHistorial(item) {
    return `
        <div class="cambio-detalle mb-2">
            <div class="d-flex justify-content-between">
                <small class="text-muted">${formatFecha(item.fecha)}</small>
                <span class="badge bg-secondary">${item.razon}</span>
            </div>
            <p class="mb-1 small">${item.detalles}</p>
            ${item.cambios ? `<small class="text-muted">Cambios: ${item.cambios}</small>` : ''}
        </div>
    `;
}
  </script>
  <script>
  //Buscador en vivo
  // Buscador de Edición
document.getElementById('buscadorEdicion').addEventListener('input', function(e) {
    const termino = e.target.value.trim();
    const resultados = document.getElementById('resultadosEdicion');
    
    if (termino.length >= 2) {
        resultados.innerHTML = '<div class="text-center my-3"><div class="spinner-border text-primary"></div></div>';
        
        fetch(`../../controllers/search_admin.php?action=buscarParaEditar&query=${encodeURIComponent(termino)}`)
            .then(response => response.json())
            .then(data => {
                resultados.innerHTML = data.map(producto => `
                    <div class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${producto.nombre_producto}</h6>
                                <small class="text-muted">
                                    ID: ${producto.id_producto} | 
                                    Stock: ${producto.stock_producto} | 
                                    Precio: $${producto.precio_producto}
                                </small>
                            </div>
                            <div>
                                <span class="badge ${producto.estado === 'active' ? 'bg-success' : 'bg-danger'} me-2">
                                    ${producto.estado}
                                </span>
                                <button class="btn btn-sm btn-warning" 
                                        onclick="cargarModalEdicion(${producto.id_producto})">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');
            })
            .catch(error => {
                resultados.innerHTML = `<div class="alert alert-danger">Error al buscar productos</div>`;
            });
    } else {
        resultados.innerHTML = '';
    }
});

// Cargar datos en el modal
		// Función mejorada para cargar el modal de edición
async function cargarModalEdicion(idProducto) {
    try {
        // 1. Obtener datos del producto
        const responseProducto = await fetch(`../../controllers/ProductoController.php?action=obtenerParaEdicion&id=${idProducto}`);
        const producto = await responseProducto.json();
        
        // 2. Obtener historial de cambios
        const responseHistorial = await fetch(`../../controllers/ProductoController.php?action=obtenerHistorial&id_producto=${idProducto}`);
        const historial = await responseHistorial.json();
        
        // 3. Generar HTML del modal
        const modalContent = `
            <form id="formEditarProducto" onsubmit="guardarCambios(event, ${idProducto})">
                <input type="hidden" name="id_producto" value="${idProducto}">
                
                <div class="row">
                    <!-- Columna Izquierda - Datos del Producto -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Nombre del Producto</label>
                            <input type="text" name="nombre" class="form-control" value="${producto.nombre_producto}" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Precio Actual</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="precio_actual" class="form-control" 
                                           value="${producto.precio_producto}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nuevo Precio</label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" step="0.01" name="nuevo_precio" class="form-control" 
                                           placeholder="Ingrese nuevo precio">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Stock Actual</label>
                                <input type="number" name="stock_actual" class="form-control" 
                                       value="${producto.stock_producto}" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Ajuste de Stock</label>
                                <div class="input-group">
                                    <select name="tipo_ajuste" class="form-select">
                                        <option value="sumar">Sumar</option>
                                        <option value="restar">Restar</option>
                                        <option value="reemplazar">Reemplazar</option>
                                    </select>
                                    <input type="number" name="cantidad_ajuste" class="form-control" 
                                           placeholder="Cantidad" min="0">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Más campos del producto... -->
                    </div>
                    
                    <!-- Columna Derecha - Historial y Justificación -->
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">Registro de Cambios</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Razón del Cambio*</label>
                                    <select name="razon_cambio" class="form-select" required>
                                        <option value="">Seleccione una razón...</option>
                                        <option value="ajuste_inventario">Ajuste de inventario</option>
                                        <option value="producto_danado">Producto dañado</option>
                                        <option value="error_registro">Error en registro</option>
                                        <option value="promocion">Promoción especial</option>
                                        <option value="otro">Otro motivo</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Detalles del Cambio*</label>
                                    <textarea name="detalles_cambio" class="form-control" rows="3" 
                                              placeholder="Explique detalladamente por qué realiza estos cambios..." required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Historial Reciente</label>
                                    <div class="historial-container" style="max-height: 200px; overflow-y: auto;">
                                        ${historial.length > 0 ? 
                                            historial.map(item => `
                                                <div class="cambio-detalle mb-2">
                                                    <div class="d-flex justify-content-between">
                                                        <small class="text-muted">${item.fecha}</small>
                                                        <span class="badge bg-secondary">${item.razon}</span>
                                                    </div>
                                                    <p class="mb-1 small">${item.detalles}</p>
                                                    ${item.cambios ? `<small class="text-muted">Cambios: ${item.cambios}</small>` : ''}
                                                </div>
                                            `).join('') : 
                                            '<div class="alert alert-info py-2">No hay historial registrado</div>'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        `;
        
        // 4. Insertar en el modal y mostrarlo
        document.getElementById('contenidoModalEdicion').innerHTML = modalContent;
        const modal = new bootstrap.Modal(document.getElementById('modalEdicion'));
        modal.show();
        
    } catch (error) {
        console.error('Error al cargar modal de edición:', error);
        alert('Error al cargar los datos del producto');
    }
}

// Función para guardar cambios
async function guardarCambios(event, idProducto) {
    event.preventDefault();
    
    const formData = new FormData(event.target);
    formData.append('action', 'actualizarProducto');
    
    try {
        const response = await fetch('../../controllers/ProductoController.php', {
            method: 'POST',
            body: formData
        });
        
        const resultado = await response.json();
        
        if (resultado.success) {
            alert('Cambios guardados correctamente');
            // Cerrar modal y actualizar vista
            bootstrap.Modal.getInstance(document.getElementById('modalEdicion')).hide();
            // Actualizar la tabla/buscador
            actualizarVistaProductos();
        } else {
            throw new Error(resultado.message || 'Error al guardar cambios');
        }
    } catch (error) {
        console.error('Error al guardar cambios:', error);
        alert(`Error: ${error.message}`);
    }
}

// Función para actualizar la vista después de editar
function actualizarVistaProductos() {
    // Aquí puedes:
    // 1. Recargar la página completa (sencillo pero menos eficiente)
    location.reload();
    
    // O 2. Actualizar solo los elementos modificados (más complejo pero mejor UX)
    // Ejemplo:
    // const termino = document.getElementById('buscadorEdicion').value;
    // if (termino) {
    //    buscarProductos(termino);
    // } else {
    //    cargarTablaProductos();
    // }
}
                    </div>
                    
                    <!-- Columna Derecha -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Estado</label>
                            <select name="estado" class="form-select">
                                <option value="active" ${producto.estado === 'active' ? 'selected' : ''}>Activo</option>
                                <option value="paused" ${producto.estado === 'paused' ? 'selected' : ''}>Pausado</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Categoría</label>
                            <select name="categoria" class="form-select" required>
                                ${await generarOpcionesCategorias(producto.id_subcategoria)}
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Imágenes</label>
                            <div class="row g-2" id="galeriaImagenes">
                                ${imagenes.map(img => `
                                    <div class="col-4">
                                        <img src="../../assets/uploads/imagenes_productos/${img}" 
                                             class="img-thumbnail" style="height: 100px; object-fit: cover;">
                                    </div>
                                `).join('')}
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i>Guardar Cambios
                    </button>
                </div>
            </form>
        `;

        // Inicializar el modal
        new bootstrap.Modal(document.getElementById('modalEdicion')).show();
        
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar los datos del producto');
    }
}



// Guardar cambios
document.getElementById('contenidoModalEdicion').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../../controllers/ProductoController.php?action=actualizar', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Producto actualizado correctamente');
            bootstrap.Modal.getInstance(document.getElementById('modalEdicion')).hide();
            document.getElementById('buscadorEdicion').dispatchEvent(new Event('input'));
        } else {
            throw new Error(data.message || 'Error al actualizar');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message);
    });
});
  </script>
</body>
</html>