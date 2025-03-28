<?php
class Categoria {
    private $id_categoria;
    private $nombre_categoria;
    private $db;

    // Constructor: establece la conexión a la base de datos
    public function __construct() {
        $this->db = Database::connect();
    }

    // Getters y Setters
    public function getId_categoria() {
        return $this->id_categoria;
    }

    public function getNombre_categoria() {
        return $this->nombre_categoria;
    }

    public function setId_categoria($id_categoria) {
        $this->id_categoria = $this->db->real_escape_string($id_categoria);
    }

    public function setNombre_categoria($nombre_categoria) {
        $this->nombre_categoria = $this->db->real_escape_string($nombre_categoria);
    }

    // Obtener todas las categorías
    public function getAll() {
        $sql = "SELECT * FROM tbl_categorias ORDER BY nombre_categoria ASC;";
        $categorias = $this->db->query($sql);
        return $categorias;
    }

    // Obtener subcategorías por ID de categoría
    public function getSubcategoriasByCategoria($id_categoria) {
        $sql = "SELECT * FROM tbl_subcategorias WHERE id_categoria = $id_categoria ORDER BY nombre_subcategoria ASC;";
        $subcategorias = $this->db->query($sql);
        return $subcategorias;
    }

    // Obtener una categoría por su ID
    public function getOne() {
        $sql = "SELECT * FROM tbl_categorias WHERE id_categoria = {$this->getId_categoria()};";
        $categoria = $this->db->query($sql);
        return $categoria->fetch_object();
    }

    // Guardar una nueva categoría
    public function save() {
        $sql = "INSERT INTO tbl_categorias VALUES (NULL, '{$this->getNombre_categoria()}');";
        $save = $this->db->query($sql);

        if ($save) {
            return true;
        } else {
            return false;
        }
    }

    // Actualizar una categoría existente
    public function update() {
        $sql = "UPDATE tbl_categorias SET nombre_categoria = '{$this->getNombre_categoria()}' WHERE id_categoria = {$this->getId_categoria()};";
        $update = $this->db->query($sql);

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    // Eliminar una categoría
    public function delete() {
        $sql = "DELETE FROM tbl_categorias WHERE id_categoria = {$this->getId_categoria()};";
        $delete = $this->db->query($sql);

        if ($delete) {
            return true;
        } else {
            return false;
        }
    }

    // Obtener productos de una categoría específica
    public function getProductosByCategoria() {
        $sql = "SELECT p.* FROM tbl_productos p "
             . "INNER JOIN tbl_categorias c ON p.id_categoria = c.id_categoria "
             . "WHERE c.id_categoria = {$this->getId_categoria()} "
             . "ORDER BY p.nombre_producto ASC;";
        $productos = $this->db->query($sql);
        return $productos;
    }

    // Obtener categorías con sus productos (para estadísticas o reportes)
    public function getCategoriasWithProductos() {
        $sql = "SELECT c.nombre_categoria, COUNT(p.id_producto) AS total_productos "
             . "FROM tbl_categorias c "
             . "LEFT JOIN tbl_productos p ON c.id_categoria = p.id_categoria "
             . "GROUP BY c.id_categoria "
             . "ORDER BY total_productos DESC;";
        $categorias = $this->db->query($sql);
        return $categorias;
    }
}