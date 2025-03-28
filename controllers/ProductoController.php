<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// controllers/ProductoController.php
ob_start();

session_start();
error_reporting(E_ALL);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

require_once __DIR__ . '/../models/Producto.php';
require_once __DIR__ . '/../models/Categoria.php';
require_once __DIR__ . '/../models/Imagen_Producto.php';

class ProductoController {

    /////////////////////////////////////////////////////////////////
    // MÉTODOS CORREGIDOS
    public function listarPorSubcategoria() {
        try {
            // Validar y obtener parámetros
            $subcategoria = filter_var($_GET['subcategoria'], FILTER_VALIDATE_INT);
            $pagina = filter_var($_GET['pagina'] ?? 1, FILTER_VALIDATE_INT);
            $porPagina = 12; // Productos por página

            if (!$subcategoria || $subcategoria <= 0) {
                throw new Exception("ID de subcategoría inválido", 400);
            }

            if ($pagina <= 0) {
                throw new Exception("Número de página inválido", 400);
            }

            // Llamar al modelo para obtener productos
            $producto = new Producto();
            $resultado = $producto->filtrarAvanzado([
                'subcategoria' => $subcategoria,
                'pagina' => $pagina,
                'porPagina' => $porPagina
            ]);

            // Verificar si se obtuvieron resultados
            if (empty($resultado['productos'])) {
                throw new Exception("No se encontraron productos para esta subcategoría", 404);
            }

            // Responder con los productos y la información de paginación
            $this->responderExito('', [
                'productos' => $resultado['productos'],
                'paginas' => $resultado['paginas']
            ]);

        } catch (Exception $e) {
            $this->responderError($e->getMessage(), $e->getCode());
        }
    }

    public function guardarProducto() {
        try {
            // 1. Verificación básica
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido", 405);
            }

            // 2. Validación de campos
            $errores = $this->validarCamposProducto($_POST, $_FILES);
            if (!empty($errores)) {
                throw new Exception(implode("\n", $errores), 400);
            }

            // 3. Crear y configurar el producto
            $producto = $this->crearProductoDesdeRequest($_POST);
            
            // 4. Establecer valores por defecto para campos requeridos
            $producto->setCosto(floatval($_POST['costo']));
            $producto->setMargen_minimo(30); // 30% de margen mínimo por defecto
            $producto->setPorcentaje_descuento(0); // Sin descuento por defecto
            $producto->setCodigoExterno(''); // Valor por defecto
            $producto->setProveedor(''); // Valor por defecto

            // 5. Guardar producto
            $id_producto = $producto->save();
            if (!$id_producto) {
                throw new Exception("Error al guardar el producto", 500);
            }

            // 6. Guardar imágenes
            $this->guardarImagenes($id_producto, $_FILES['imagenes']);

            // 7. Respuesta exitosa
            $this->responderExito('Producto creado correctamente', [
                'id' => $id_producto,
                'margen' => $this->calcularMargen($producto->getPrecio_producto(), $producto->getCosto())
            ]);

        } catch (Exception $e) {
            error_log("Error en guardarProducto: " . $e->getMessage());
            $this->responderError($e->getMessage(), $e->getCode());
        }
    }

    private function validarCamposProducto($post, $files) {
        $errores = [];
        
        // Validación de nombre
        if (empty($post['nombre'])) {
            $errores[] = "El nombre del producto es obligatorio";
        }

        // Validación de precio y costo
        if (empty($post['precio']) || floatval($post['precio']) <= 0) {
            $errores[] = "El precio debe ser mayor a cero";
        }
        
        if (empty($post['costo']) || floatval($post['costo']) <= 0) {
            $errores[] = "El costo debe ser mayor a cero";
        }
        
        if (isset($post['precio'], $post['costo']) && floatval($post['precio']) <= floatval($post['costo'])) {
            $errores[] = "El precio debe ser mayor que el costo";
        }

        // Validación de categoría
        if (empty($post['categoria']) || !ctype_digit($post['categoria'])) {
            $errores[] = "Debes seleccionar una categoría válida";
        }

        // Validación de imágenes
        if (empty($files['imagenes']['name'][0])) {
            $errores[] = "Debes subir al menos una imagen";
        }

        return $errores;
    }

    private function crearProductoDesdeRequest($post) {
        $producto = new Producto();
        $producto->setNombre_producto(htmlspecialchars(trim($post['nombre'])));
        $producto->setPrecio_producto(floatval($post['precio']));
        $producto->setId_subcategoria(intval($post['categoria']));
        $producto->setDescripcion_producto($post['descripcion'] ?? '');
        $producto->setStock_producto($post['stock'] ?? 0);
        $producto->setOferta_producto($post['oferta'] ?? 'no');
        $producto->setDestacado($post['destacado'] ?? 'no');
        
        return $producto;
    }

    private function calcularMargen($precio, $costo) {
        return $precio > 0 ? round((($precio - $costo) / $precio * 100), 2) : 0;
    }

    private function validarEntrada($campos, $datos) {
        $errores = [];
        foreach ($campos as $campo => $tipo) {
            if (empty($datos[$campo])) {
                $errores[] = "El campo {$campo} es requerido";
                continue;
            }
            
            switch ($tipo) {
                case 'numero':
                    if (!is_numeric($datos[$campo]) || $datos[$campo] <= 0) {
                        $errores[] = "Formato inválido para {$campo}";
                    }
                    break;
                    
                case 'entero':
                    if (!filter_var($datos[$campo], FILTER_VALIDATE_INT) || $datos[$campo] <= 0) {
                        $errores[] = "Formato inválido para {$campo}";
                    }
                    break;
            }
        }
        return $errores;
    }

    private function validarImagenes($archivos) {
        $errores = [];
        $mimesPermitidos = ['image/jpeg', 'image/png', 'image/webp'];
        
        foreach ($archivos['tmp_name'] as $tmpName) {
            if (!empty($tmpName)) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($tmpName);
                if (!in_array($mime, $mimesPermitidos)) {
                    $errores[] = "Tipo de archivo no permitido: $mime";
                }
            }
        }
        return $errores;
    }

    private function crearProductoDesdePost($post) {
        $producto = new Producto();
        $producto->setNombre_producto(htmlspecialchars($post['nombre']));
        $producto->setPrecio_producto((float)$post['precio']);
        $producto->setId_subcategoria((int)$post['categoria']);
        $producto->setDescripcion_producto($post['descripcion'] ?? '');
        $producto->setStock_producto((int)($post['stock'] ?? 0));
        $producto->setOferta_producto($post['oferta'] ?? 'no');
        $producto->setDestacado($post['destacado'] ?? 'no');
        return $producto;
    }

    private function guardarImagenes($idProducto, $archivos) {
        $imagenes = $this->procesarImagenes($archivos);
        $imagenProducto = new Imagen_Producto();
        $imagenProducto->guardar($idProducto, $imagenes);
    }

    private function procesarImagenes($archivos) {
        $nombres = [];
        $uploadDir = __DIR__ . '/../assets/uploads/';
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        foreach ($archivos['tmp_name'] as $key => $tmpName) {
            if ($archivos['error'][$key] !== UPLOAD_ERR_OK) continue;
            
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($tmpName);
            
            if (!in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
                continue;
            }

            $extension = pathinfo($archivos['name'][$key], PATHINFO_EXTENSION);
            $nombreUnico = uniqid('img_') . '.' . $extension;
            
            if (move_uploaded_file($tmpName, $uploadDir . $nombreUnico)) {
                $nombres[] = $nombreUnico;
            }
        }
        
        if (empty($nombres)) {
            throw new Exception("Error al procesar las imágenes");
        }
        
        return $nombres;
    }

    /////////////////////////////////////////////////////////////////
    // MÉTODOS DE CONSULTA
    
    public function getProductosDestacados() {
        try {
            $producto = new Producto();
            $productos = $producto->getDestacados();
            $this->responderExito('', $productos);
        } catch (Exception $e) {
            $this->responderError($e->getMessage());
        }
    }

    public function getProductosPorSubcategoria() {
        try {
            if (!isset($_GET['subcategoria'])) {
                throw new Exception("Parámetro requerido", 400);
            }
            
            $subcategoria = filter_var($_GET['subcategoria'], FILTER_VALIDATE_INT);
            if (!$subcategoria || $subcategoria <= 0) {
                throw new Exception("ID inválido", 400);
            }

            $producto = new Producto();
            $productos = $producto->getBySubcategoria($subcategoria);
            
            $this->responderExito('', $productos);
        } catch (Exception $e) {
            $this->responderError($e->getMessage(), $e->getCode());
        }
    }

    public function obtenerParaEdicion() {
        try {
            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            $producto = new Producto();
            $data = $producto->getById($id);
            $this->responderExito('', $data);
        } catch (Exception $e) {
            $this->responderError($e->getMessage());
        }
    }

    public function actualizar() {
        try {
            $id = filter_var($_POST['id_producto'], FILTER_VALIDATE_INT);
            $producto = new Producto();
            
            $producto->setId_producto($id);
            $producto->setNombre_producto($_POST['nombre']);
            $producto->setPrecio_producto($_POST['precio']);
            $producto->setStock_producto($_POST['stock']);
            $producto->setEstado($_POST['estado']);
            $producto->setId_subcategoria($_POST['categoria']);
            
            if ($producto->update()) {
                $this->responderExito('Producto actualizado');
            } else {
                throw new Exception("Error al actualizar");
            }
        } catch (Exception $e) {
            $this->responderError($e->getMessage());
        }
    }

    public function filtrarProductos() {
        try {
            $parametros = [
                'busqueda' => trim($_GET['busqueda'] ?? ''),
                'precio' => $_GET['precio'] ?? 'all',
                'subcategoria' => filter_var($_GET['subcategoria'] ?? 0, FILTER_VALIDATE_INT),
                'pagina' => max(1, filter_var($_GET['pagina'] ?? 1, FILTER_VALIDATE_INT)),
                'porPagina' => 12
            ];
    
            if ($parametros['subcategoria'] < 0) {
                throw new Exception("ID de subcategoría inválido", 400);
            }
    
            $producto = new Producto();
            $resultado = $producto->filtrarAvanzado($parametros);
    
            $this->responderExito('', [
                'productos' => $resultado['productos'],
                'paginas' => $resultado['paginas']
            ]);
    
        } catch (Exception $e) {
            error_log("Error en filtrarProductos: " . $e->getMessage());
            $this->responderError($e->getMessage(), $e->getCode());
        }
    }

    /////////////////////////////////////////////////////////////////
    // MÉTODOS ADMINISTRATIVOS
    
    public function cambiarEstado() {
        try {
            $id = filter_var($_POST['id_producto'], FILTER_VALIDATE_INT);
            $producto = new Producto();
            $nuevoEstado = $producto->cambiarEstado($id);
            
            $this->responderExito('Estado actualizado', [
                'nuevoEstado' => $nuevoEstado,
                'textoBoton' => $nuevoEstado == 'active' ? 'Pausar' : 'Activar',
                'claseBoton' => $nuevoEstado == 'active' ? 'btn-secondary' : 'btn-success'
            ]);
            
        } catch (Exception $e) {
            $this->responderError($e->getMessage());
        }
    }

    public function editarProducto() {
        try {
            $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
            if (!$id || $id <= 0) {
                throw new Exception("ID de producto inválido", 400);
            }

            $producto = new Producto();
            $productoData = $producto->getById($id);
            
            $categoriaModel = new Categoria();
            $categorias = $categoriaModel->getAll();

            require_once __DIR__ . '/../../views/admin/products/editar.php';

        } catch (Exception $e) {
            $this->responderError($e->getMessage(), $e->getCode());
        }
    }

    public function actualizarProducto() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception("Método no permitido", 405);
            }

            $id = filter_var($_POST['id_producto'] ?? 0, FILTER_VALIDATE_INT);
            if (!$id || $id <= 0) {
                throw new Exception("ID inválido", 400);
            }

            $errores = $this->validarCamposProducto($_POST, $_FILES);
            if (!empty($errores)) {
                throw new Exception(implode("\n", $errores), 400);
            }

            $producto = $this->crearProductoDesdeRequest($_POST);
            $producto->setId_producto($id);
            $producto->update();

            if (!empty($_FILES['imagenes']['name'][0])) {
                $this->guardarImagenes($id, $_FILES['imagenes']);
            }

            header("Location: ../../productos/?success=1");
            exit;

        } catch (Exception $e) {
            $_SESSION['error_edicion'] = $e->getMessage();
            header("Location: editar.php?id=$id");
            exit;
        }
    }

    public function eliminarProducto() {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
                throw new Exception("Método no permitido", 405);
            }

            $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
            if (!$id || $id <= 0) {
                throw new Exception("ID inválido", 400);
            }

            $producto = new Producto();
            if (!$producto->getById($id)) {
                throw new Exception("Producto no existe", 404);
            }

            $imagenProducto = new Imagen_Producto();
            $imagenProducto->eliminarPorProducto($id);
            
            if (!$producto->delete()) {
                throw new Exception("Error al eliminar");
            }
            
            $this->responderExito("Producto eliminado");

        } catch (Exception $e) {
            $this->responderError($e->getMessage(), $e->getCode());
        }
    }

    /////////////////////////////////////////////////////////////////
    // UTILITARIOS
    
    private function responderExito($mensaje = '', $datos = []) {
        ob_clean();
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => $mensaje,
            'data' => $datos
        ]);
        exit;
    }

    private function responderError($mensaje, $codigo = 500) {
        ob_clean();
        http_response_code($codigo);
        echo json_encode([
            'success' => false,
            'message' => $mensaje
        ]);
        exit;
    }

    public function listarProductos() {
        ob_clean();
        header('Content-Type: text/html; charset=utf-8');
        $producto = new Producto();
        $productos = $producto->getAll();
        require_once __DIR__ . '/../views/admin/products/list.php';
        exit;
    }
}

try {
    $accionesPermitidas = [
        'guardarProducto', 'getProductosDestacados',
        'getProductosPorSubcategoria', 'filtrarProductos',
        'cambiarEstado', 'eliminarProducto', 'listarProductos',
        'listarPorSubcategoria', 'obtenerParaEdicion', 'actualizar',
        'editarProducto', 'actualizarProducto'
    ];

    $action = $_GET['action'] ?? '';
    
    if (empty($action) || !in_array($action, $accionesPermitidas)) {
        throw new Exception("Acción no válida", 400);
    }

    $controller = new ProductoController();
    $controller->$action();

} catch (Exception $e) {
    http_response_code($e->getCode() ?: 500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
    exit;
}
?>