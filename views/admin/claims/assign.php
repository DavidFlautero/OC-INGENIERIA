<?php
// views/admin/claims/assign.php

require_once __DIR__ . '/../../../models/reclamo.php';

if (!isset($_GET['id'])) {
    die("ID de reclamo no proporcionado.");
}

$claimId = $_GET['id'];
$reclamo = new Reclamo();
$claim = $reclamo->getById($claimId); // Asegúrate de tener este método en reclamo.php

if (!$claim) {
    die("Reclamo no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Reclamo</title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
    <h1>Asignar Reclamo</h1>
    <form action="../../../controllers/assignClaim.php" method="POST">
        <input type="hidden" name="claim_id" value="<?php echo $claim['id_reclamo']; ?>">

        <label for="assigned_to">Asignar a:</label>
        <select name="assigned_to" id="assigned_to" required>
            <option value="1">Usuario 1</option>
            <option value="2">Usuario 2</option>
            <!-- Agrega más opciones según tu base de datos -->
        </select>

        <label for="status">Estado:</label>
        <select name="status" id="status" required>
            <option value="pendiente">Pendiente</option>
            <option value="en_progreso">En progreso</option>
            <option value="resuelto">Resuelto</option>
        </select>

        <button type="submit">Asignar</button>
    </form>
</body>
</html>