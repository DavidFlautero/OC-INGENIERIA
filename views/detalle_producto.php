<?php include 'partials/header.php'; ?>

<section class="producto-detalle">
    <div class="container">
        <div class="row">
            <!-- Columna de imágenes -->
            <div class="col-md-6">
                <div class="producto-imagen-principal">
                    <img src="assets/images/<?= $producto->imagen_producto; ?>" alt="<?= $producto->nombre_producto; ?>" class="img-fluid">
                </div>
            </div>

            <!-- Columna de detalles -->
            <div class="col-md-6">
                <h1><?= $producto->nombre_producto; ?></h1>
                <p class="text-muted">Código: <?= $producto->id_producto; ?></p>
                <p class="precio">$<?= number_format($producto->precio_producto, 2); ?></p>
                <?php if ($producto->oferta_producto == 'si') : ?>
                    <p class="text-success"><strong>¡En oferta!</strong></p>
                <?php endif; ?>

                <!-- Formulario para agregar al carrito -->
                <form action="<?= base_url; ?>carrito/add" method="POST">
                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" value="1" min="1" max="<?= $producto->stock_producto; ?>">
                    </div>
                    <input type="hidden" name="id_producto" value="<?= $producto->id_producto; ?>">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-cart-plus"></i> Agregar al carrito
                    </button>
                </form>

                <!-- Descripción del producto -->
                <div class="descripcion-producto mt-4">
                    <h3>Descripción</h3>
                    <p><?= $producto->descripcion_producto; ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'partials/footer.php'; ?>