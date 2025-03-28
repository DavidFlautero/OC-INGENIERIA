<?php include './partials/header.php'; ?>

<section class="productos">
    <h2>Nuestros Productos</h2>
    <div class="productos-grid">
        <?php
        include '../config/db.php'; // Conexión a la base de datos

        $query = "SELECT * FROM tbl_productos"; // Obtiene todos los productos
        $resultado = $conexion->query($query);

        while ($producto = $resultado->fetch_assoc()) {
            echo '<div class="producto">';
            echo '<img src="assets/images/' . $producto['imagen'] . '" alt="' . $producto['nombre'] . '">';
            echo '<h3>' . $producto['nombre'] . '</h3>';
            echo '<p>$' . $producto['precio'] . '</p>';
            echo '<a href="detalle_producto.php?id=' . $producto['id'] . '">Ver Detalle</a>';
            echo '</div>';
        }
        ?>
    </div>
</section>

<?php include './partials/footer.php'; ?>