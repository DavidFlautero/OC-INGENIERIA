<?php
class Usuario {
    private $id_usuario;
    private $nombre_usuario;
    private $email_usuario;
    private $password_usuario;
    private $rol_usuario;
    private $db;

    // Constructor: establece la conexión a la base de datos
    public function __construct() {
        $this->db = Database::connect();
    }

    // Getters y Setters
    public function getId_usuario() {
        return $this->id_usuario;
    }

    public function getNombre_usuario() {
        return $this->nombre_usuario;
    }

    public function getEmail_usuario() {
        return $this->email_usuario;
    }

    public function getPassword_usuario() {
        return password_hash($this->db->real_escape_string($this->password_usuario), PASSWORD_BCRYPT, ['cost' => 4]);
    }

    public function getRol_usuario() {
        return $this->rol_usuario;
    }

    public function setId_usuario($id_usuario) {
        $this->id_usuario = $this->db->real_escape_string($id_usuario);
    }

    public function setNombre_usuario($nombre_usuario) {
        $this->nombre_usuario = $this->db->real_escape_string($nombre_usuario);
    }

    public function setEmail_usuario($email_usuario) {
        $this->email_usuario = $this->db->real_escape_string($email_usuario);
    }

    public function setPassword_usuario($password_usuario) {
        $this->password_usuario = $password_usuario;
    }

    public function setRol_usuario($rol_usuario) {
        $this->rol_usuario = $this->db->real_escape_string($rol_usuario);
    }

    // Guardar un nuevo usuario
    public function save() {
        $sql = "INSERT INTO usuarios VALUES (NULL, '{$this->getNombre_usuario()}', '{$this->getEmail_usuario()}', '{$this->getPassword_usuario()}', 'user', CURDATE());";
        $save = $this->db->query($sql);

        if ($save) {
            return true;
        } else {
            return false;
        }
    }

    // Autenticar un usuario (login)
    public function login() {
        $email = $this->email_usuario;
        $password = $this->password_usuario;

        // Verificar si el usuario existe
        $sql = "SELECT * FROM usuarios WHERE email = '$email';";
        $login = $this->db->query($sql);

        if ($login && $login->num_rows == 1) {
            $usuario = $login->fetch_object();

            // Verificar la contraseña
            if (password_verify($password, $usuario->password_usuario)) {
                return $usuario;
            }
        }

        return false;
    }

    // Obtener un usuario por su ID
    public function getOne() {
        $sql = "SELECT * FROM usuarios WHERE id = {$this->getId_usuario()};";
        $usuario = $this->db->query($sql);
        return $usuario->fetch_object();
    }

    // Obtener los datos del usuario por ID
    public function obtenerPorId($userId) {
        $sql = "SELECT nombre_completo, email, telefono, direccion FROM usuarios WHERE id = $userId;";
        $result = $this->db->query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); // Devuelve los datos del usuario como un array asociativo
        } else {
            return false; // Si no se encuentra el usuario, devuelve false
        }
    }

    // Actualizar la información del usuario
    public function update() {
        $sql = "UPDATE usuarios SET "
             . "nombre_completoo = '{$this->getNombre_usuario()}', "
             . "email = '{$this->getEmail_usuario()}' "
             . "WHERE id = {$this->getId_usuario()};";
        $update = $this->db->query($sql);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    // Cambiar la contraseña del usuario
    public function cambiarPassword() {
        $sql = "UPDATE usuarios SET password_usuario = '{$this->getPassword_usuario()}' WHERE id_usuario = {$this->getId_usuario()};";
        $update = $this->db->query($sql);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    // Eliminar un usuario
    public function delete() {
        $sql = "DELETE FROM usuarios WHERE id_usuario = {$this->getId_usuario()};";
        $delete = $this->db->query($sql);

        if ($delete) {
            return true;
        } else {
            return false;
        }
    }

    // Obtener todos los usuarios (para el administrador)
    public function getAll() {
        $sql = "SELECT * FROM usuarios ORDER BY id_usuario DESC;";
        $usuarios = $this->db->query($sql);
        return $usuarios;
    }

    // Verificar si un email ya está registrado
    public function emailExists($email) {
        $sql = "SELECT id_usuario FROM tbl_usuarios WHERE email_usuario = '$email';";
        $result = $this->db->query($sql);

        if ($result && $result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }
}
?>