<?php include '../partials/header.php'; ?>

<section class="productos-destacados">
    <div class="container">
        <h2 class="text-center mb-4">Productos Destacados</h2>
        <div class="row">
            <?php
            // Obtener productos destacados
            $producto = new Producto();
            $productosDestacados = $producto->getDestacados();

            if ($productosDestacados && $productosDestacados->num_rows > 0) :
                while ($prod = $productosDestacados->fetch_object()) :
            ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="../assets/images/<?= $prod->imagen_producto; ?>" class="card-img-top" alt="<?= $prod->nombre_producto; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $prod->nombre_producto; ?></h5>
                                <p class="card-text">$<?= number_format($prod->precio_producto, 2); ?></p>
                                <a href="<?= base_url; ?>producto/detalle&id=<?= $prod->id_producto; ?>" class="btn btn-primary">Ver Detalle</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else : ?>
                <div class="col-12">
                    <p class="text-center">No hay productos destacados disponibles.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include '../partials/footer.php'; ?>