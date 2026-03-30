<?php
require_once 'models/Producto.php';

class ProductoController {
    private $modelo;

    public function __construct($db) {
        $this->modelo = new Producto($db);
    }

    public function catalogo() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

        // Obtener productos según filtros
        if (!empty($search) && !empty($categoria)) {
            $resultado = $this->modelo->buscarYCategoria($search, $categoria);
        } elseif (!empty($search)) {
            $resultado = $this->modelo->buscar($search);
        } elseif (!empty($categoria)) {
            $resultado = $this->modelo->filtrarPorCategoria($categoria);
        } else {
            $resultado = $this->modelo->obtenerTodos();
        }

        $productos = [];
        if ($resultado) {
            while ($row = $resultado->fetch_assoc()) {
                $productos[] = $row;
            }
        }

        // Contador real del carrito (suma cantidades)
        $cart_count = 0;
        if (isset($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $item) {
                $cart_count += $item['cantidad'];
            }
        }

        include 'views/catalogo.view.php';
    }
}
?>