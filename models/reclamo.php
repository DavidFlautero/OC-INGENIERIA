<?php
require_once __DIR__ . '../../config/db.php'; // Ajusta la ruta según la ubicación de db.php

class Reclamo {
    private $db;

    public function __construct() {
        $this->db = DataBase::connect(); // Obtiene la conexión a la base de datos
    }

    public function getAll() {
        $query = "SELECT * FROM tbl_reclamos"; // Ajusta el nombre de la tabla si es necesario
        $result = $this->db->query($query); // Ejecuta la consulta

        if (!$result) {
            die("Error en la consulta: " . $this->db->error); // Manejo de errores
        }

        $reclamos = [];
        while ($row = $result->fetch_assoc()) {
            $reclamos[] = $row; // Almacena cada fila en un array
        }

        return $reclamos; // Retorna los resultados como un array asociativo
    }
}
?>