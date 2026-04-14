<?php
require_once 'models/Producto.php';

// Orquesta filtros del catalogo publico y prepara datos para la vista legacy
class ProductoController {
    private $modelo;

    // =============================
    // __CONSTRUCT
    // =============================
    public function __construct($db) {
        // Inicializa modelo del catalogo con conexion activa
        $this->modelo = new Producto($db);
    }

    // =============================
    // CATALOGO
    // =============================
    public function catalogo() {
        // Aplica filtros del catalogo y entrega datos a la vista
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $search = isset($_GET['q']) ? trim($_GET['q']) : '';
        $categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

        // Obtener productos segaUn filtros
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

        // Variables de sesion para mostrar en HTML
        $isLogged = isset($_SESSION['login']) && $_SESSION['login']['isLogged'];
        $userName = $_SESSION['userName'] ?? '';
        $userEmail = $_SESSION['userEmail'] ?? '';

        include 'views/catalogo.view.php';
    }
}
?>