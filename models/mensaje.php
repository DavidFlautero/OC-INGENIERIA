<?php
// models/Mensaje.php

require_once __DIR__ . '/../config/db.php'; // Incluir la conexión a la base de datos

class Mensaje {
    private $db;

    public function __construct() {
        global $db; // Usar la conexión global definida en db.php
        $this->db = $db;
    }

    // Método para obtener mensajes activos
    public function getMensajesActivos() {
        $sql = "SELECT contenido FROM tbl_mensajes WHERE estado = 'activo' LIMIT 3";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>