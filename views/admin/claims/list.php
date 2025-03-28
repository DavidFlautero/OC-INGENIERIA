<?php
// views/admin/claims/list.php

require_once __DIR__ . '/../../../models/reclamo.php';

// Crear una instancia del modelo Reclamo
$reclamo = new Reclamo();

// Obtener todos los reclamos
$claims = $reclamo->getAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Reclamos</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <h1>Lista de Reclamos</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Pedido</th>
                <th>Producto</th>
                <th>Usuario</th>
                <th>Descripción</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($claims as $claim): ?>
                <tr>
                    <td><?php echo htmlspecialchars($claim['id_reclamo']); ?></td>
                    <td><?php echo htmlspecialchars($claim['id_pedido']); ?></td>
                    <td><?php echo htmlspecialchars($claim['id_producto']); ?></td>
                    <td><?php echo htmlspecialchars($claim['id_usuario']); ?></td>
                    <td><?php echo htmlspecialchars($claim['descripcion']); ?></td>
                    <td><?php echo htmlspecialchars($claim['estado']); ?></td>
                    <td><?php echo htmlspecialchars($claim['fecha']); ?></td>
                    <td>
                        <a href="detail.php?id=<?php echo $claim['id_reclamo']; ?>">Ver Detalle</a>
                        <a href="assign.php?id=<?php echo $claim['id_reclamo']; ?>">Asignar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>