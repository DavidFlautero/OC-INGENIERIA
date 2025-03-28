<!doctype html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Panel de Administración - Online Tienda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="TuNombre" />
    <meta name="description" content="Panel de administración para Online Tienda." />
    <meta name="keywords" content="panel, administración, tienda, online" />

    <!-- Fonts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />

    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="../../dist/css/adminlte.css" />

    <!-- ApexCharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- Estilos Personalizados -->
    <style>
      /* Ajustes para reducir la altura de las tarjetas de resumen */
      .small-box {
        transition: transform 0.2s ease-in-out;
        height: 120px; /* Altura reducida */
      }
      .small-box .inner {
        padding: 10px; /* Padding interno reducido */
      }
      .small-box h3 {
        font-size: 1.5rem; /* Tamaño de fuente reducido */
      }

      /* Ajustes para reducir el espacio entre filas */
      .row {
        margin-bottom: 10px; /* Margen inferior reducido */
      }

      /* Ajustes para reducir el tamaño de los gráficos */
      #ventas-anuales-chart,
      #comparativa-ventas-chart,
      #productos-vendidos-chart {
        height: 250px; /* Altura reducida para todos los gráficos */
      }
    </style>
  </head>

  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <!-- Header -->
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
                <img
                  src="../../dist/assets/img/user2-160x160.jpg"
                  class="user-image rounded-circle shadow"
                  alt="User Image"
                />
                <span class="d-none d-md-inline">Administrador</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                <li class="user-header text-bg-primary">
                  <img
                    src="../../dist/assets/img/user2-160x160.jpg"
                    class="rounded-circle shadow"
                    alt="User Image"
                  />
                  <p>
                    Administrador
                    <small>Miembro desde Nov. 2023</small>
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

      <!-- Sidebar -->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <div class="sidebar-brand">
          <a href="./index.html" class="brand-link">
            <img
              src="../../dist/assets/img/AdminLTELogo.png"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <span class="brand-text fw-light">Online Tienda</span>
          </a>
        </div>
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
              <!-- Dashboard -->
              <li class="nav-item">
                <a href="#" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>

              <!-- Gestión de Productos -->
              <li class="nav-item">
                <a href="../../productos/index.php" class="nav-link">
                  <i class="nav-icon bi bi-box-seam-fill"></i>
                  <p>Productos</p>
                </a>
              </li>

              <!-- Gestión de Pedidos -->
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

              <!-- Gestión de Inventario -->
              <li class="nav-item">
                <a href="../pages/inventario/index.html" class="nav-link">
                  <i class="nav-icon bi bi-clipboard-data-fill"></i>
                  <p>Inventario</p>
                </a>
              </li>

              <!-- Calendario y Horarios -->
              <li class="nav-item">
                <a href="../pages/calendario/index.html" class="nav-link">
                  <i class="nav-icon bi bi-calendar-event-fill"></i>
                  <p>Calendario</p>
                </a>
              </li>

              <!-- Usuarios y Vendedores -->
              <li class="nav-item">
                <a href="../pages/usuarios/index.html" class="nav-link">
                  <i class="nav-icon bi bi-people-fill"></i>
                  <p>Usuarios</p>
                </a>
              </li>

              <!-- Códigos de Descuento -->
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

              <!-- Mensajes del Slider -->
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

      <!-- Main Content -->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Dashboard</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="#">Inicio</a></li>
                  <li class="breadcrumb-item active">Dashboard</li>
                </ol>
              </div>
            </div>
          </div>
        </div>

        <div class="app-content">
          <div class="container-fluid">
            <!-- Tarjetas de Resumen -->
            <div class="row">
              <!-- Ventas del Día -->
              <div class="col-lg-3 col-6">
                <div class="small-box text-bg-success shadow-sm">
                  <div class="inner">
                    <h3>$5,000</h3>
                    <p>Ventas del Día</p>
                  </div>
                  <div class="icon">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <a href="#" class="small-box-footer">
                    Más info <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              <!-- Pedidos Pendientes -->
              <div class="col-lg-3 col-6">
                <div class="small-box text-bg-warning shadow-sm">
                  <div class="inner">
                    <h3>25</h3>
                    <p>Pedidos Pendientes</p>
                  </div>
                  <div class="icon">
                    <i class="bi bi-cart-dash-fill"></i>
                  </div>
                  <a href="../pages/pedidos/index.html" class="small-box-footer">
                    Más info <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              <!-- Reclamos Pendientes -->
              <div class="col-lg-3 col-6">
                <div class="small-box text-bg-danger shadow-sm">
                  <div class="inner">
                    <h3>12</h3>
                    <p>Reclamos Pendientes</p>
                  </div>
                  <div class="icon">
                    <i class="bi bi-exclamation-circle-fill"></i>
                  </div>
                  <a href="../pages/reclamos/index.html" class="small-box-footer">
                    Más info <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>

              <!-- Productos con Bajo Stock -->
              <div class="col-lg-3 col-6">
                <div class="small-box text-bg-info shadow-sm">
                  <div class="inner">
                    <h3>8</h3>
                    <p>Productos con Bajo Stock</p>
                  </div>
                  <div class="icon">
                    <i class="bi bi-box-seam"></i>
                  </div>
                  <a href="../pages/inventario/index.html" class="small-box-footer">
                    Más info <i class="bi bi-arrow-right"></i>
                  </a>
                </div>
              </div>
            </div>

            <!-- Métrica Anual -->
            <div class="row mt-4">
              <div class="col-12">
                <div class="card shadow-sm">
                  <div class="card-header">
                    <h3 class="card-title">Ventas Anuales</h3>
                  </div>
                  <div class="card-body">
                    <div id="ventas-anuales-chart"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Gráficos y Estadísticas -->
            <div class="row mt-4">
              <!-- Gráfico de Barras Comparativas -->
              <div class="col-lg-7">
                <div class="card shadow-sm">
                  <div class="card-header">
                    <h3 class="card-title">Comparativa de Ventas</h3>
                  </div>
                  <div class="card-body">
                    <div id="comparativa-ventas-chart"></div>
                  </div>
                </div>
              </div>

              <!-- Gráfico de Productos Más Vendidos -->
              <div class="col-lg-5">
                <div class="card shadow-sm">
                  <div class="card-header">
                    <h3 class="card-title">Productos Más Vendidos</h3>
                  </div>
                  <div class="card-body">
                    <div id="productos-vendidos-chart"></div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Alertas y Notificaciones -->
            <div class="row mt-4">
              <!-- Alertas de Stock Crítico -->
              <div class="col-lg-6">
                <div class="card shadow-sm">
                  <div class="card-header">
                    <h3 class="card-title">Alertas de Stock Crítico</h3>
                  </div>
                  <div class="card-body">
                    <ul class="list-group">
                      <li class="list-group-item">Producto A - Faltan 5 unidades</li>
                      <li class="list-group-item">Producto B - Faltan 3 unidades</li>
                    </ul>
                  </div>
                </div>
              </div>

              <!-- Reclamos sin Resolver -->
              <div class="col-lg-6">
                <div class="card shadow-sm">
                  <div class="card-header">
                    <h3 class="card-title">Reclamos sin Resolver</h3>
                  </div>
                  <div class="card-body">
                    <ul class="list-group">
                      <li class="list-group-item">Reclamo #123 - Pendiente</li>
                      <li class="list-group-item">Reclamo #124 - Pendiente</li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>

      <!-- Footer -->
      <footer class="app-footer">
        <strong>Copyright &copy; 2023 Online Tienda.</strong> Todos los derechos reservados.
      </footer>
    </div>

    <!-- Scripts -->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>
    <script src="../../dist/js/adminlte.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
      integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8="
      crossorigin="anonymous"
    ></script>
    <script>
      // Configuración de gráficos
      const ventasAnualesOptions = {
        series: [
          {
            name: "Ventas",
            data: [12000, 15000, 18000, 20000, 22000, 25000, 28000, 30000, 32000, 35000, 38000, 40000],
          },
        ],
        chart: {
          type: "line",
          height: 250, // Altura reducida
        },
        xaxis: {
          categories: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        },
      };

      const comparativaVentasOptions = {
        series: [
          {
            name: "Semana Actual",
            data: [30, 40, 35, 50, 49, 60, 70],
          },
          {
            name: "Semana Pasada",
            data: [20, 35, 30, 45, 40, 55, 65],
          },
        ],
        chart: {
          type: "bar",
          height: 250, // Altura reducida
        },
        xaxis: {
          categories: ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"],
        },
      };

      const productosVendidosOptions = {
        series: [44, 55, 41, 17, 15],
        labels: ["Producto A", "Producto B", "Producto C", "Producto D", "Producto E"],
        chart: {
          type: "donut",
          height: 250, // Altura reducida
        },
      };

      const ventasAnualesChart = new ApexCharts(
        document.querySelector("#ventas-anuales-chart"),
        ventasAnualesOptions
      );
      ventasAnualesChart.render();

      const comparativaVentasChart = new ApexCharts(
        document.querySelector("#comparativa-ventas-chart"),
        comparativaVentasOptions
      );
      comparativaVentasChart.render();

      const productosVendidosChart = new ApexCharts(
        document.querySelector("#productos-vendidos-chart"),
        productosVendidosOptions
      );
      productosVendidosChart.render();
    </script>
  </body>
</html>