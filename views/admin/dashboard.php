<?php include '../partials/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar (menú lateral) -->
        <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
            <div class="position-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">
                            <i class="fas fa-home"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="products/list.php">
                            <i class="fas fa-box"></i> Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders/list.php">
                            <i class="fas fa-shopping-cart"></i> Pedidos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clients/list.php">
                            <i class="fas fa-users"></i> Clientes
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="claims/list.php">
                            <i class="fas fa-exclamation-circle"></i> Reclamos
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Contenido principal -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <h1 class="mt-4">Dashboard</h1>
            <div class="row">
                <!-- Tarjeta de Ventas Totales -->
                <div class="col-md-3">
                    <div class="card bg-primary text-white mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Ventas Totales</h5>
                            <p class="card-text">$10,000</p>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de Pedidos Pendientes -->
                <div class="col-md-3">
                    <div class="card bg-success text-white mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Pedidos Pendientes</h5>
                            <p class="card-text">15</p>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de Productos en Stock -->
                <div class="col-md-3">
                    <div class="card bg-warning text-dark mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Productos en Stock</h5>
                            <p class="card-text">120</p>
                        </div>
                    </div>
                </div>
                <!-- Tarjeta de Reclamos Activos -->
                <div class="col-md-3">
                    <div class="card bg-danger text-white mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Reclamos Activos</h5>
                            <p class="card-text">5</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../partials/footer.php'; ?>