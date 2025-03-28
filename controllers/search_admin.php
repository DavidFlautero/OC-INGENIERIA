<?php
// search_admin.php

require '../config/db.php'; // Asegúrate de que la ruta sea correcta


// search_admin.php

require '../config/db.php';

// Verificar autenticación (agregar según tu sistema)
session_start();
if (!isset($_SESSION['admin'])) {
    http_response_code(401);
    exit('Acceso no autorizado');
}

// Configurar tipo de contenido
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');

// Determinar el tipo de búsqueda
$action = $_GET['action'] ?? 'default';
$query = $_GET['query'] ?? '';

try {
    $db = Database::connect();
    
    switch ($action) {
        case 'buscarParaEditar':
            // Búsqueda para edición con más campos
            $stmt = $db->prepare("
                SELECT 
                    p.id_producto,
                    p.nombre_producto,
                    p.precio_producto,
                    p.stock_producto,
                    p.estado,
                    p.descripcion_producto,
                    c.nombre_categoria AS categoria,
                    s.id_subcategoria,
                    GROUP_CONCAT(i.nombre_imagen ORDER BY i.orden SEPARATOR '|') AS imagenes
                FROM tbl_productos p
                LEFT JOIN tbl_subcategorias s ON p.id_subcategoria = s.id_subcategoria
                LEFT JOIN tbl_categorias c ON s.id_categoria = c.id_categoria
                LEFT JOIN tbl_imagenes_productos i ON p.id_producto = i.id_producto
                WHERE p.nombre_producto LIKE ? OR p.id_producto = ?
                GROUP BY p.id_producto
                LIMIT 10
            ");
            $searchTerm = "%$query%";
            $stmt->bind_param("ss", $searchTerm, $query);
            break;

        default:
            // Búsqueda estándar para facturación
            $stmt = $db->prepare("
                SELECT 
                    p.id_producto, 
                    p.nombre_producto, 
                    p.precio_producto, 
                    p.stock_producto, 
                    c.nombre_categoria AS categoria,  
                    COALESCE(MIN(i.nombre_imagen), 'default.jpg') AS imagen 
                FROM tbl_productos p
                LEFT JOIN tbl_imagenes_productos i ON p.id_producto = i.id_producto
                LEFT JOIN tbl_subcategorias s ON p.id_subcategoria = s.id_subcategoria
                LEFT JOIN tbl_categorias c ON s.id_categoria = c.id_categoria
                WHERE p.nombre_producto LIKE ?
                GROUP BY p.id_producto
                LIMIT 10
            ");
            $searchTerm = "%$query%";
            $stmt->bind_param("s", $searchTerm);
    }

    if (!$stmt->execute());
?>