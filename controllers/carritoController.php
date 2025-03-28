<?php
require_once '../models/producto.php';

class CarritoController {

    // Método para mostrar el carrito de compras
    public function index() {
        // Verificar si hay productos en el carrito
        if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
            $_SESSION['carrito_vacio'] = "Tu carrito está vacío.";
        } else {
            // Obtener los productos del carrito desde la sesión
            $carrito = $_SESSION['carrito'];

            // Obtener productos de reemplazo para cada producto en el carrito
            foreach ($carrito as &$producto) {
                if ($producto['stock_producto'] == 0) {
                    $productoModel = new Producto();
                    $producto['reemplazos'] = $productoModel->getProductosSimilares($producto['id_producto']);
                }
            }
        }

        // Cargar la vista del carrito
        require_once 'views/carrito/index.php';
    }

    // Método para agregar un producto al carrito
    public function add() {
        if (isset($_GET['id_producto'])) {
            $id_producto = $_GET['id_producto'];

            // Verificar si el producto existe en la base de datos
            $productoModel = new Producto();
            $productoModel->setId_producto($id_producto);
            $producto = $productoModel->getSelectProducto();

            if (!$producto) {
                $_SESSION['error_carrito'] = "El producto no existe.";
                header("Location:" . base_url);
                exit();
            }

            // Verificar si el producto ya está en el carrito
            if (isset($_SESSION['carrito'])) {
                $producto_en_carrito = false;

                foreach ($_SESSION['carrito'] as $indice => $elemento) {
                    if ($elemento['id_producto'] == $id_producto) {
                        $_SESSION['carrito'][$indice]['unidad_producto']++; // Incrementar la cantidad
                        $producto_en_carrito = true;
                        break;
                    }
                }

                // Si el producto no está en el carrito, agregarlo
                if (!$producto_en_carrito) {
                    $this->agregarProductoAlCarrito($id_producto);
                }
            } else {
                // Si no hay carrito, crearlo y agregar el producto
                $this->agregarProductoAlCarrito($id_producto);
            }

            $_SESSION['carrito_agregado'] = "Producto agregado al carrito.";
            header("Location:" . base_url . "carrito/index");
            exit();
        } else {
            $_SESSION['error_carrito'] = "No se proporcionó un ID de producto.";
            header("Location:" . base_url);
            exit();
        }
    }

    // Método para eliminar un producto del carrito
    public function remove() {
        if (isset($_GET['indice'])) {
            $indice = $_GET['indice'];

            // Verificar si el índice existe en el carrito
            if (isset($_SESSION['carrito'][$indice])) {
                unset($_SESSION['carrito'][$indice]); // Eliminar el producto del carrito
                $_SESSION['carrito_eliminado'] = "Producto eliminado del carrito.";
            } else {
                $_SESSION['error_carrito'] = "El producto no existe en el carrito.";
            }
        } else {
            $_SESSION['error_carrito'] = "No se proporcionó un índice válido.";
        }

        header("Location:" . base_url . "carrito/index");
        exit();
    }

    // Método para aumentar la cantidad de un producto en el carrito
    public function up() {
        if (isset($_GET['indice'])) {
            $indice = $_GET['indice'];

            // Verificar si el índice existe en el carrito
            if (isset($_SESSION['carrito'][$indice])) {
                $_SESSION['carrito'][$indice]['unidad_producto']++; // Incrementar la cantidad
                $_SESSION['carrito_actualizado'] = "Cantidad actualizada.";
            } else {
                $_SESSION['error_carrito'] = "El producto no existe en el carrito.";
            }
        } else {
            $_SESSION['error_carrito'] = "No se proporcionó un índice válido.";
        }

        header("Location:" . base_url . "carrito/index");
        exit();
    }

    // Método para disminuir la cantidad de un producto en el carrito
    public function down() {
        if (isset($_GET['indice'])) {
            $indice = $_GET['indice'];

            // Verificar si el índice existe en el carrito
            if (isset($_SESSION['carrito'][$indice])) {
                $_SESSION['carrito'][$indice]['unidad_producto']--; // Disminuir la cantidad

                // Si la cantidad llega a 0, eliminar el producto del carrito
                if ($_SESSION['carrito'][$indice]['unidad_producto'] == 0) {
                    unset($_SESSION['carrito'][$indice]);
                    $_SESSION['carrito_eliminado'] = "Producto eliminado del carrito.";
                } else {
                    $_SESSION['carrito_actualizado'] = "Cantidad actualizada.";
                }
            } else {
                $_SESSION['error_carrito'] = "El producto no existe en el carrito.";
            }
        } else {
            $_SESSION['error_carrito'] = "No se proporcionó un índice válido.";
        }

        header("Location:" . base_url . "carrito/index");
        exit();
    }

    // Método para vaciar el carrito
    public function delete_all() {
        if (isset($_SESSION['carrito']) && !empty($_SESSION['carrito'])) {
            unset($_SESSION['carrito']); // Vaciar el carrito
            $_SESSION['carrito_vacio'] = "El carrito se ha vaciado.";
        } else {
            $_SESSION['error_carrito'] = "El carrito ya está vacío.";
        }

        header("Location:" . base_url . "carrito/index");
        exit();
    }

    // Método privado para agregar un producto al carrito
    private function agregarProductoAlCarrito($id_producto) {
        $productoModel = new Producto();
        $productoModel->setId_producto($id_producto);
        $producto = $productoModel->getSelectProducto();

        if (is_object($producto)) {
            $_SESSION['carrito'][] = array(
                "id_producto" => $producto->id_producto,
                "precio_producto" => $producto->precio_producto,
                "nombre_producto" => $producto->nombre_producto,
                "unidad_producto" => 1,
                "oferta_producto" => $producto->oferta_producto,
                "imagen_producto" => $producto->imagen_producto,
                "stock_producto" => $producto->stock_producto // Añadir el stock del producto
            );
        }
    }
}