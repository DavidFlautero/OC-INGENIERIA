<?php
// Verifica si la clase ya está definida
if (!class_exists('DataBase')) {
    class DataBase {
        public static function connect() {
            $db = new mysqli('localhost', 'root', '', 'tienda_freelancer');
            if ($db->connect_error) {
                die("Error de conexión: " . $db->connect_error);
            }
            $db->query("SET NAMES 'utf8'");
            return $db;
        }
    }
}

// Crear una instancia de la conexión
$conexion = DataBase::connect();
?>