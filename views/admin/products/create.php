<?php
session_start();
require_once '../../../config/db.php';
require_once '../../../models/Categoria.php';

$categoria = new Categoria();
$categorias = $categoria->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Producto - Panel de Administración</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .main-container { max-width: 800px; margin: 2rem auto; padding: 20px; }
        .preview-img { max-width: 150px; margin: 10px; border: 2px dashed #dee2e6; padding: 5px; }
    </style>
</head>
<body class="bg-light">
    <div class="main-container bg-white rounded shadow-sm p-4">
        <h2 class="mb-4"><i class="bi bi-box-seam me-2"></i>Nuevo Producto</h2>

        <!-- Mensajes AJAX -->
        <div id="mensaje-ajax" class="mb-3"></div>

        <!-- Formulario -->
        <form id="form-crear-producto" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre del Producto*</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Precio*</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="precio" step="0.01" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Subcategoría*</label>
                    <select name="categoria" class="form-select" required>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat['id_subcategoria'] ?>">
                                <?= htmlspecialchars($cat['nombre_subcategoria']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Stock</label>
                    <input type="number" name="stock" class="form-control" value="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Oferta</label>
                    <select name="oferta" class="form-select">
                        <option value="no">No</option>
                        <option value="si">Sí</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Destacado</label>
                    <select name="destacado" class="form-select">
                        <option value="no">No</option>
                        <option value="si">Sí</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Imágenes del Producto*</label>
                <input type="file" name="imagenes[]" class="form-control" multiple accept="image/*" required>
                <small class="text-muted">Mínimo 1 imagen (JPEG, PNG, WEBP)</small>
                <div id="preview-imagenes" class="mt-2"></div>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-2"></i>Guardar Producto
            </button>
        </form>
    </div>

    <script>
        // Previsualización de imágenes
        document.querySelector('input[name="imagenes[]"]').addEventListener('change', function(e) {
            const preview = document.getElementById('preview-imagenes');
            preview.innerHTML = '';
            
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-img img-thumbnail';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });

        // Envío AJAX
        document.getElementById('form-crear-producto').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('action', 'guardarProducto');

            const mensajeDiv = document.getElementById('mensaje-ajax');
            mensajeDiv.innerHTML = '<div class="alert alert-info">Guardando producto...</div>';

            fetch('/onlinetienda/controllers/ProductoController.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mensajeDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    this.reset();
                    document.getElementById('preview-imagenes').innerHTML = '';
                    setTimeout(() => window.location.href = 'list.php', 1500);
                } else {
                    mensajeDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(error => {
                mensajeDiv.innerHTML = `<div class="alert alert-danger">Error de conexión</div>`;
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>