<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
require_once __DIR__ . '/../config/db.php';
//require_once __DIR__ . '/../models/Producto.php';

class PromocionController {
    private $db;
    private $producto;

    public function __construct() {
        $this->db = Database::connect();
        $this->producto = new Producto();
    }

    // Generar sugerencias de promociones inteligentes
    public function generarSugerencias() {
        try {
            $productos = $this->producto->getDatosParaPromociones();
            $sugerencias = [];

            foreach ($productos as $producto) {
                $acciones = $this->analizarProducto($producto);
                if (!empty($acciones)) {
                    $sugerencias[] = [
                        'producto' => $producto,
                        'acciones' => $acciones
                    ];
                }
            }

            return $sugerencias;

        } catch (PDOException $e) {
            error_log("Error en generarSugerencias: " . $e->getMessage());
            return [];
        }
    }

    // Aplicar una promoción desde el formulario
    public function aplicarPromocion($data) {
        try {
            // Validación básica
            if (!isset($data['producto_id']) || !is_numeric($data['producto_id'])) {
                throw new Exception("ID de producto inválido");
            }

            // Formatear precio correctamente
            $precio_promo = $this->formatearPrecioBD($data['precio_promocion']);
            $producto_id = (int)$data['producto_id'];

            // Obtener datos del producto
            $producto = $this->producto->getById($producto_id);
            if (!$producto) {
                throw new Exception("Producto no encontrado con ID: $producto_id");
            }

            // Calcular márgenes usando los alias
            $margen = (($precio_promo - $producto['costo']) / $precio_promo) * 100;
            
            // Validar margen mínimo
            if ($margen < $producto['margen_minimo']) {
                throw new Exception("Margen mínimo no alcanzado (Actual: {$margen}%, Mínimo: {$producto['margen_minimo']}%)");
            }

            // Insertar en base de datos
            $stmt = $this->db->prepare("
                INSERT INTO producto_promociones 
                (producto_id, tipo, valor, fecha_inicio, fecha_fin, margen_final)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

            $exito = $stmt->execute([
                $producto_id,
                $data['tipo_promocion'],
                $precio_promo,
                $data['fecha_inicio'],
                $data['fecha_fin'],
                $margen
            ]);

            return [
                'success' => $exito,
                'message' => $exito 
                    ? 'Promoción aplicada exitosamente' 
                    : 'Error al guardar la promoción'
            ];

        } catch (Exception $e) {
            error_log("Error en aplicarPromocion: " . $e->getMessage());
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    // Obtener límites para mostrar en el modal
    public function getLimites($producto_id) {
        try {
            $producto = $this->producto->getById($producto_id);
            
            if (empty($producto)) {
                throw new Exception("Producto ID $producto_id no encontrado");
            }
    
            $costo = (float)$producto['costo'];
            $margen_min = (float)$producto['margen_minimo'];
            
            if ($margen_min >= 100) {
                throw new Exception("Margen mínimo inválido: $margen_min");
            }
    
            $precio_min = $costo / (1 - ($margen_min / 100));
            
            return [
                'precio_minimo' => number_format($precio_min, 3, '.', ''),
                'margen_minimo' => $margen_min
            ];
    
        } catch (Exception $e) {
            error_log("Error en getLimites: " . $e->getMessage());
            return [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }

    // Método para obtener promociones
    public function obtenerPromociones() {
        try {
            $sql = "SELECT * FROM tbl_promociones WHERE estado = 'activa'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $promociones = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            return $promociones;
        } catch (Exception $e) {
            error_log("Error en PromocionController: " . $e->getMessage());
            return [];
        }
    }

    private function analizarProducto($producto) {
        $acciones = [];
        
        // Asegurar que todas las claves existen antes de usarlas
        $precio = (float)($producto['precio_producto'] ?? 0);
        $costo = (float)($producto['costo'] ?? 0);
        $stock = (int)($producto['stock_producto'] ?? 0);
        $dias_sin_venta = (int)($producto['dias_sin_venta'] ?? 0);
        $margen_minimo = (float)($producto['margen_minimo'] ?? 0);
        $unidades_vendidas = (int)($producto['unidades_vendidas'] ?? 0);
        
        // Evitar errores en margen_actual y división por cero
        $margen_actual = ($costo > 0) ? (($precio - $costo) / $costo) * 100 : 0;
    
        // Calcular rotación mensual (evitar división por cero)
        $rotacion_mensual = ($stock > 0) ? ($unidades_vendidas / max(1, $stock / 30)) : 0;
    
        // Regla 1: Stock alto + baja rotación
        if ($stock > 100 && $rotacion_mensual < 5) {
            $acciones[] = $this->generarAccion($producto, 15, 'Alto stock y baja rotación');
        }
    
        // Regla 2: Sin ventas recientes
        if (($dias_sin_venta > 60) || ($dias_sin_venta === 0)) {
            $acciones[] = $this->generarAccion($producto, 20, 'Sin ventas en 60 días');
        }
    
        // Regla 3: Margen muy por encima del mínimo
        if ($margen_actual - $margen_minimo > 30) {
            $acciones[] = $this->generarAccion($producto, 10, 'Margen alto permitiría descuento');
        }
    
        return $acciones;
    }

    private function generarAccion($producto, $descuento, $motivo) {
        // Asegurar claves y valores numéricos
        $precio_actual = (float)($producto['precio_producto'] ?? 0);
        $costo = (float)($producto['costo'] ?? 0);
        
        // Calcular precio sugerido
        $precio_sugerido = $precio_actual * (1 - ($descuento / 100));
        
        // Validar precios antes de calcular el margen
        if ($precio_sugerido <= 0 || $costo <= 0) {
            error_log("Error en generarAccion: Precios inválidos (Precio: $precio_actual, Descuento: $descuento%)");
            return []; // Omitir acción si hay datos inválidos
        }
        
        // Calcular margen de forma segura
        $margen_nuevo = (($precio_sugerido - $costo) / $precio_sugerido) * 100;
        
        return [
            'tipo' => 'descuento',
            'descuento' => $descuento,
            'precio_actual' => $precio_actual,
            'precio_sugerido' => round($precio_sugerido, 3),
            'margen_nuevo' => round($margen_nuevo, 2),
            'minimo_permitido' => (float)($producto['margen_minimo'] ?? 0),
            'motivo' => $motivo
        ];
    }

    private function formatearPrecioBD($precio) {
        // Convertir formatos locales a decimal BD
        $precio_limpio = str_replace(['.', ','], ['', '.'], $precio);
        return number_format((float)$precio_limpio, 3, '.', '');
    }

    // Manejar solicitud AJAX
    public function handleRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $response = $this->aplicarPromocion($_POST);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($response);
            exit;
        }
    }
}

// Uso del controlador
if (isset($_REQUEST['action'])) {
    $controller = new PromocionController();
    
    switch($_REQUEST['action']) {
        case 'aplicar':
            $controller->handleRequest();
            break;
        case 'get_limites':
            if(isset($_GET['producto_id'])) {
                echo json_encode($controller->getLimites($_GET['producto_id']));
            }
            break;
        default:
            echo json_encode(['error' => 'Acción no válida']);
    }
    exit;
}
?>