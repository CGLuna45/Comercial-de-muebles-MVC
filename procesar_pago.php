<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: carrito.php");
    exit;
}

/* =========================
   1. Calcular total
========================= */
$subtotal = 0;

foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['precio'] * $item['cantidad'];
}

$isv = $subtotal * 0.15;
$total = $subtotal + $isv;

/* =========================
   2. Obtener o crear usuario
========================= */
$usuarioId = 0;

$sqlUsuario = "SELECT usuarioId FROM usuarios ORDER BY usuarioId ASC LIMIT 1";
$resUsuario = $db->query($sqlUsuario);

if ($resUsuario && $resUsuario->num_rows > 0) {
    $filaUsuario = $resUsuario->fetch_assoc();
    $usuarioId = $filaUsuario['usuarioId'];
} else {
    $nombreDemo = "Usuario Demo";
    $emailDemo = "demo@cedrika.com";
    $passDemo = password_hash("123456", PASSWORD_DEFAULT);
    $statusDemo = "ACT";

    $sqlInsertUsuario = "INSERT INTO usuarios (usuarioNombre, usuarioEmail, usuarioPass, usuarioStatus)
                         VALUES (?, ?, ?, ?)";

    $stmtInsertUsuario = $db->prepare($sqlInsertUsuario);
    $stmtInsertUsuario->bind_param("ssss", $nombreDemo, $emailDemo, $passDemo, $statusDemo);

    if (!$stmtInsertUsuario->execute()) {
        die("Error al crear usuario demo: " . $stmtInsertUsuario->error);
    }

    $usuarioId = $db->insert_id;
}

/* =========================
   3. Crear carritilla
========================= */
$sqlCarritilla = "INSERT INTO carritilla (usuarioId, carritillaStatus) VALUES (?, 'ACT')";
$stmtCarritilla = $db->prepare($sqlCarritilla);
$stmtCarritilla->bind_param("i", $usuarioId);

if (!$stmtCarritilla->execute()) {
    die("Error al crear la carritilla: " . $stmtCarritilla->error);
}

$carritillaId = $db->insert_id;

/* =========================
   4. Crear datos simulados
========================= */
$paypalOrderId = "SIM-" . date("YmdHis");
$paypalStatus = "COMPLETED";
$paypalPayerId = "PAYER-" . rand(1000, 9999);
$paypalFecha = date("Y-m-d H:i:s");
$transaccionStatus = "PAG";

/* =========================
   5. Guardar transacción
========================= */
$sqlTransaccion = "INSERT INTO transacciones
(usuarioId, carritillaId, transaccionTotal, transaccionStatus, paypalOrderId, paypalStatus, paypalPayerId, paypalFecha)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmtTransaccion = $db->prepare($sqlTransaccion);
$stmtTransaccion->bind_param(
    "iidsssss",
    $usuarioId,
    $carritillaId,
    $total,
    $transaccionStatus,
    $paypalOrderId,
    $paypalStatus,
    $paypalPayerId,
    $paypalFecha
);

if (!$stmtTransaccion->execute()) {
    die("Error al guardar la transacción: " . $stmtTransaccion->error);
}

$transaccionId = $db->insert_id;


$categoriaId = 0;

$sqlCategoria = "SELECT categoriaId FROM categorias ORDER BY categoriaId ASC LIMIT 1";
$resCategoria = $db->query($sqlCategoria);

if ($resCategoria && $resCategoria->num_rows > 0) {
    $filaCategoria = $resCategoria->fetch_assoc();
    $categoriaId = $filaCategoria['categoriaId'];
} else {
    $nombreCategoria = "General";
    $descripcionCategoria = "Categoría general";
    $statusCategoria = "ACT";

    $sqlInsertCategoria = "INSERT INTO categorias (categoriaNombre, categoriaDescripcion, categoriaStatus)
                           VALUES (?, ?, ?)";
    $stmtInsertCategoria = $db->prepare($sqlInsertCategoria);
    $stmtInsertCategoria->bind_param("sss", $nombreCategoria, $descripcionCategoria, $statusCategoria);

    if (!$stmtInsertCategoria->execute()) {
        die("Error al crear categoría: " . $stmtInsertCategoria->error);
    }

    $categoriaId = $db->insert_id;
}

/* =========================
   7. Guardar detalle de transacción
========================= */
$sqlBuscarProducto = "SELECT productId FROM products WHERE productName = ? LIMIT 1";
$stmtBuscarProducto = $db->prepare($sqlBuscarProducto);

$sqlInsertProducto = "INSERT INTO products
(categoriaId, productName, productDescription, productPrice, productStock, productImgUrl, productStatus)
VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmtInsertProducto = $db->prepare($sqlInsertProducto);

$sqlDetalle = "INSERT INTO transacciones_detalle
(transaccionId, productId, transDetalleCantidad, transDetallePrecio, transDetalleSubtotal)
VALUES (?, ?, ?, ?, ?)";
$stmtDetalle = $db->prepare($sqlDetalle);

foreach ($_SESSION['cart'] as $item) {
    $nombreProducto = $item['nombre'];
    $precioProducto = $item['precio'];
    $cantidadProducto = $item['cantidad'];
    $imagenProducto = $item['imagen'];
    $subtotalLinea = $precioProducto * $cantidadProducto;
    $stockProducto = 100;
    $descripcionProducto = "Producto generado automáticamente desde carrito";
    $statusProducto = "ACT";

    // Buscar si ya existe en products
    $stmtBuscarProducto->bind_param("s", $nombreProducto);
    $stmtBuscarProducto->execute();
    $resBuscarProducto = $stmtBuscarProducto->get_result();

    if ($resBuscarProducto && $resBuscarProducto->num_rows > 0) {
        $filaProducto = $resBuscarProducto->fetch_assoc();
        $productId = $filaProducto['productId'];
    } else {
        // Crear producto en products si no existe
        $stmtInsertProducto->bind_param(
            "issdiss",
            $categoriaId,
            $nombreProducto,
            $descripcionProducto,
            $precioProducto,
            $stockProducto,
            $imagenProducto,
            $statusProducto
        );

        if (!$stmtInsertProducto->execute()) {
            die("Error al crear producto en products: " . $stmtInsertProducto->error);
        }

        $productId = $db->insert_id;
    }

    // Guardar detalle
    $stmtDetalle->bind_param(
        "iiidd",
        $transaccionId,
        $productId,
        $cantidadProducto,
        $precioProducto,
        $subtotalLinea
    );

    if (!$stmtDetalle->execute()) {
        die("Error al guardar detalle: " . $stmtDetalle->error);
    }
}

/* =========================
   8. Vaciar carrito 
========================= */
$_SESSION['cart'] = array();

header("Location: confirmacion.php?id=" . $transaccionId);
exit;