<?php
// controllers/assignClaim.php

require_once __DIR__ . '/../models/reclamo.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $claimId = $_POST['claim_id'];
    $assignedTo = $_POST['assigned_to'];
    $status = $_POST['status'];

    $reclamo = new Reclamo();
    $reclamo->setIdReclamo($claimId);
    $reclamo->setEstado($status);

    // Si tienes un método para asignar reclamos
    $result = $reclamo->asignarReclamo($claimId, $assignedTo, $status);

    // O si usas el método gestionar()
    // $result = $reclamo->gestionar();

    if ($result) {
        header("Location: /views/admin/claims/list.php"); // Redirige a la lista de reclamos
        exit();
    } else {
        die("Error al asignar el reclamo.");
    }
}
?>