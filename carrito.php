<?php
session_start();
require_once 'db.php';

// Variables de sesión para mostrar en HTML
$isLogged = isset($_SESSION['login']) && $_SESSION['login']['isLogged'];
$userName = $_SESSION['userName'] ?? '';
$userEmail = $_SESSION['userEmail'] ?? '';

// Crear carrito si no existe
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cartSyncMessages = [];
if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $sessionKey => &$item) {
        $productId = intval($item['id'] ?? $sessionKey);
        if ($productId <= 0) {
            unset($_SESSION['cart'][$sessionKey]);
            continue;
        }

        $resStock = $db->query("SELECT productId, productName, productPrice, productImgUrl, productStock, productStatus FROM products WHERE productId = $productId LIMIT 1");
        if (!$resStock || $resStock->num_rows === 0) {
            unset($_SESSION['cart'][$sessionKey]);
            $cartSyncMessages[] = "Se eliminó un producto del carrito porque ya no está disponible.";
            continue;
        }

        $currentProduct = $resStock->fetch_assoc();
        $availableStock = intval($currentProduct['productStock'] ?? 0);
        $currentStatus = strval($currentProduct['productStatus'] ?? 'INA');

        if ($availableStock <= 0 || $currentStatus !== 'ACT') {
            unset($_SESSION['cart'][$sessionKey]);
            $cartSyncMessages[] = "Se eliminó " . $currentProduct['productName'] . " del carrito por falta de stock.";
            continue;
        }

        $item['nombre'] = $currentProduct['productName'];
        $item['precio'] = floatval($currentProduct['productPrice']);
        $item['imagen'] = $currentProduct['productImgUrl'];

        if (intval($item['cantidad']) > $availableStock) {
            $item['cantidad'] = $availableStock;
            $cartSyncMessages[] = "Se ajustó la cantidad de " . $currentProduct['productName'] . " al stock disponible.";
        }
    }
    unset($item);
}

// =============================
// AGREGAR AL CARRITO
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'agregar') {
    $id = intval($_POST['id']);
    $cantidad = intval($_POST['cantidad']);

    if ($cantidad < 1) {
        $cantidad = 1;
    }

    $res = $db->query("SELECT productId, productName, productPrice, productImgUrl, productStock FROM products WHERE productId = $id LIMIT 1");
    if ($res && $res->num_rows > 0) {
        $producto = $res->fetch_assoc();

        if (intval($producto['productStock']) <= 0) {
            header('Location: catalogo.php?status=nosotock');
            exit;
        }

        if ($cantidad > $producto['productStock']) {
            $cantidad = $producto['productStock'];
        }

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['cantidad'] += $cantidad;

            if ($_SESSION['cart'][$id]['cantidad'] > $producto['productStock']) {
                $_SESSION['cart'][$id]['cantidad'] = $producto['productStock'];
            }
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $producto['productId'],
                'nombre' => $producto['productName'],
                'precio' => $producto['productPrice'],
                'imagen' => $producto['productImgUrl'],
                'cantidad' => $cantidad,
            ];
        }
    }

    header('Location: catalogo.php?status=success');
    exit;
}

// =============================
// ELIMINAR UN PRODUCTO
// =============================
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    unset($_SESSION['cart'][$id]);
    header('Location: carrito.php');
    exit;
}

// =============================
// VACIAR CARRITO
// =============================
if (isset($_GET['action']) && $_GET['action'] === 'empty') {
    unset($_SESSION['cart']);
    $_SESSION['cart'] = [];
    header('Location: carrito.php');
    exit;
}

// =============================
// CONTADOR DEL CARRITO
// =============================
$cart_count = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['cantidad'];
}

// =============================
// TOTAL
// =============================
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['precio'] * $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cédrika | Tu Carrito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root { 
            --cedro: #5C4033; 
            --dorado: #C5A059; 
            --arena: #F2E8DF; 
            --blanco: #ffffff; 
        }

        * { 
            text-decoration: none !important; 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', sans-serif;
        }

        body { 
            background-color: var(--arena); 
            color: var(--cedro);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .logo-img {
            width: 52px;
            height: 52px;
            object-fit: contain;
        }

        .logo-txt {
            font-size: 1.7rem;
            color: var(--cedro);
            font-weight: 800;
            letter-spacing: 2px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .nav-menu a {
            color: var(--cedro) !important; 
            font-weight: 700;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .nav-menu a:hover { color: var(--dorado) !important; }

        .badge {
            background: #d35400;
            color: white;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 0.78rem;
            margin-left: 6px;
        }

        .container { 
            padding: 50px 7%; 
            max-width: 1100px; 
            margin: 0 auto;
            width: 100%;
            flex: 1;
        }

        .site-footer {
            background: var(--cedro);
            color: white;
            text-align: center;
            padding: 30px;
            margin-top: 50px;
        }

        .cart-table { 
            width: 100%; 
            border-collapse: collapse; 
            background: white; 
            border-radius: 15px; 
            overflow: hidden; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
        }

        .cart-table th { 
            background: var(--cedro); 
            color: white; 
            padding: 15px; 
            text-align: left; 
        }

        .cart-table td { 
            padding: 18px; 
            border-bottom: 1px solid #eee; 
            vertical-align: middle;
        }

        .product-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-info img {
            width: 90px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }

        .total-box { 
            margin-top: 30px; 
            text-align: right; 
            background: white; 
            padding: 30px; 
            border-radius: 15px; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); 
        }

        .btn-gold { 
            background: var(--dorado); 
            color: white !important; 
            padding: 12px 35px; 
            border-radius: 30px; 
            font-weight: bold; 
            border: none; 
            cursor: pointer; 
            display: inline-block;
            transition: 0.3s;
        }

        .btn-gold:hover { 
            background: #A68045; 
            transform: scale(1.05); 
        }

        .btn-empty { 
            color: #e74c3c !important; 
            font-size: 0.95rem; 
            margin-right: 20px; 
            font-weight: bold; 
        }

        .btn-empty:hover { 
            text-decoration: underline !important; 
        }

        .btn-remove {
            color: #c0392b !important;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .btn-remove:hover {
            text-decoration: underline !important;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .cart-alert {
            background: #fff4df;
            border: 1px solid #f1d7a4;
            color: #7a5a1b;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 12px;
            font-weight: 600;
        }

        @media(max-width: 768px){
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .cart-table, .cart-table thead, .cart-table tbody, .cart-table tr, .cart-table th, .cart-table td {
                display: block;
                width: 100%;
            }

            .cart-table thead {
                display: none;
            }

            .cart-table tr {
                margin-bottom: 20px;
                background: white;
                border-radius: 15px;
                overflow: hidden;
            }

            .product-info {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
    <script src="https://kit.fontawesome.com/c1f13ce5c9.js" crossorigin="anonymous"></script>
</head>
<body>

<header class="header">
    <a href="index.php" class="logo-box">
        <img src="img/logo-cedrika.png" alt="logo" class="logo-img">
        <span class="logo-txt">CÉDRIKA</span>
    </a>
    <nav class="nav-menu">
        <a href="index.php">Inicio</a>
        <a href="catalogo.php">Catálogo</a>
        <a href="carrito.php">🛒 Carrito <span class="badge"><?php echo $cart_count; ?></span></a>
        <?php if ($isLogged) { ?>
            <a href="index.php?page=Security_Perfil" style="color: var(--cedro); font-weight:700; text-decoration:none;">Hola, <?php echo htmlspecialchars($userName); ?></a>
            <a href="index.php?page=Sec_Logout">Cerrar Sesión</a>
        <?php } else { ?>
            <a href="index.php?page=Sec_Login"><i class="fas fa-sign-in-alt"></i>&nbsp;Iniciar Sesión</a>
            <a href="index.php?page=Sec_Register"><i class="fas fa-sign-in-alt"></i>&nbsp;Crear Cuenta</a>
        <?php } ?>
    </nav>
</header>

<div class="container">
    <h2 style="margin-bottom: 30px; border-left: 5px solid var(--dorado); padding-left: 15px;">Tu Historial de Compra / Carrito</h2>

    <?php foreach ($cartSyncMessages as $cartMsg) { ?>
    <div class="cart-alert"><?php echo htmlspecialchars($cartMsg); ?></div>
    <?php } ?>

    <?php if ($cart_count > 0) { ?>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['cart'] as $item) {
                $sub = $item['precio'] * $item['cantidad'];
                ?>
            <tr>
                <td>
                    <div class="product-info">
                        <img src="<?php echo htmlspecialchars($item['imagen']); ?>" alt="<?php echo htmlspecialchars($item['nombre']); ?>">
                        <strong><?php echo htmlspecialchars($item['nombre']); ?></strong>
                    </div>
                </td>
                <td>L <?php echo number_format($item['precio'], 0); ?></td>
                <td><?php echo $item['cantidad']; ?></td>
                <td style="font-weight: bold; color: var(--cedro);">L <?php echo number_format($sub, 0); ?></td>
                <td>
                    <a href="carrito.php?action=remove&id=<?php echo $item['id']; ?>" class="btn-remove">Eliminar</a>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <div class="total-box">
        <h3 style="font-size: 1.5rem; margin-bottom: 20px;">
            Total Pedido: <span style="color: var(--dorado);">L <?php echo number_format($total, 0); ?></span>
        </h3>

        <div class="actions">
            <a href="catalogo.php" class="btn-gold">Seguir Comprando</a>
            <a href="carrito.php?action=empty" class="btn-empty">Vaciar Carrito</a>
            <a href="index.php?page=Checkout_Checkout" class="btn-gold">Finalizar Compra</a>
        </div>
    </div>

    <?php } else { ?>
    <div style="text-align: center; padding: 80px; background: white; border-radius: 20px;">
        <p style="font-size: 1.3rem; color: #999; margin-bottom: 20px;">El carrito está vacío por el momento.</p>
        <a href="catalogo.php" class="btn-gold">Ir al Catálogo</a>
    </div>
    <?php } ?>
</div>

<footer class="site-footer">
    <p>© 2026 CÉDRIKA | Mueblería Artesanal</p>
</footer>

</body>
</html>
