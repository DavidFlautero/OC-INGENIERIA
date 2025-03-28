<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
      <a href="./index.html" class="brand-link">
        <img
          src="../../dist/assets/img/AdminLTELogo.png"
          alt="AdminLTE Logo"
          class="brand-image opacity-75 shadow"
        />
        <span class="brand-text fw-light">Mayorisander</span>
      </a>
    </div>
    <div class="sidebar-wrapper">
      <nav class="mt-2">
        <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
          <li class="nav-item">
            <a href="index.html" class="nav-link active">
              <i class="nav-icon bi bi-speedometer"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../pages/usuarios/vendedores.html" class="nav-link">
              <i class="nav-icon bi bi-people-fill"></i>
              <p>Vendedores</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../pages/productos/index.html" class="nav-link">
              <i class="nav-icon bi bi-box-seam-fill"></i>
              <p>Productos</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../pages/pedidos/index.html" class="nav-link">
              <i class="nav-icon bi bi-cart-fill"></i>
              <p>Pedidos</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../pages/reclamos/index.html" class="nav-link">
              <i class="nav-icon bi bi-exclamation-circle-fill"></i>
              <p>Reclamos</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="../pages/estadisticas/index.html" class="nav-link">
              <i class="nav-icon bi bi-bar-chart-fill"></i>
              <p>Estadísticas</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
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
        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-primary">
            <div class="inner">
              <h3>150</h3>
              <p>Pedidos Pendientes</p>
            </div>
            <div class="icon">
              <i class="bi bi-cart-fill"></i>
            </div>
            <a href="../pages/pedidos/index.php" class="small-box-footer">
              Más info <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-success">
            <div class="inner">
              <h3>53</h3>
              <p>Ventas Hoy</p>
            </div>
            <div class="icon">
              <i class="bi bi-currency-dollar"></i>
            </div>
            <a href="#" class="small-box-footer">
              Más info <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-warning">
            <div class="inner">
              <h3>12</h3>
              <p>Reclamos Pendientes</p>
            </div>
            <div class="icon">
              <i class="bi bi-exclamation-circle-fill"></i>
            </div>
            <a href="../pages/reclamos/index.php" class="small-box-footer">
              Más info <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>

        <div class="col-lg-3 col-6">
          <div class="small-box text-bg-danger">
            <div class="inner">
              <h3>65</h3>
              <p>Productos en Stock</p>
            </div>
            <div class="icon">
              <i class="bi bi-box-fill"></i>
            </div>
            <a href="../pages/productos/index.php" class="small-box-footer">
              Más info <i class="bi bi-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>

      <!-- Gráficos y Estadísticas -->
      <div class="row">
        <div class="col-lg-7">
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title">Ventas Mensuales</h3>
            </div>
            <div class="card-body">
              <div id="revenue-chart"></div>
            </div>
          </div>
        </div>

        <div class="col-lg-5">
          <div class="card mb-4">
            <div class="card-header">
              <h3 class="card-title">Reclamos por Vendedor</h3>
            </div>
            <div class="card-body">
              <div id="reclamos-chart"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>