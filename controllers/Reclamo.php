<?php
class Reclamo {
    private $id_reclamo;
    private $id_pedido;
    private $id_producto;
    private $id_usuario;
    private $descripcion;
    private $imagen;
    private $video;
    private $estado;
    private $fecha;

    // Getters y Setters
    // ...

    // Método para guardar un reclamo
    public function save() {
        $sql = "INSERT INTO reclamos VALUES (NULL, {$this->id_pedido}, {$this->id_producto}, {$this->id_usuario}, '{$this->descripcion}', '{$this->imagen}', '{$this->video}', 'pendiente', CURDATE());";
        return Database::execute($sql);
    }

    // Método para obtener todos los reclamos
    public function getAll() {
        $sql = "SELECT * FROM reclamos ORDER BY fecha DESC;";
        return Database::query($sql);
    }

    // Método para obtener reclamos de un usuario
    public function getReclamosByUser() {
        $sql = "SELECT * FROM reclamos WHERE id_usuario = {$this->id_usuario} ORDER BY fecha DESC;";
        return Database::query($sql);
    }

    // Método para gestionar un reclamo (aprobado/rechazado)
    public function gestionar() {
        $sql = "UPDATE reclamos SET estado = '{$this->estado}' WHERE id_reclamo = {$this->id_reclamo};";
        return Database::execute($sql);
    }
}