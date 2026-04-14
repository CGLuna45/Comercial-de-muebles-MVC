<?php
session_start();
require_once 'db.php';

// Procesa el pago simulado legacy, registra transaccion y descuenta stock en products

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
$usuarioId = intval($_SESSION['login']['userId'] ?? 0);

if ($usuarioId <= 0) {
    $nombreDemo = "Usuario Demo";
    $emailDemo = "demo@cedrika.com";
    $passDemo = password_hash("123456", PASSWORD_DEFAULT);
    $fechaDemo = date("Y-m-d H:i:s");
    $passExpDemo = date("Y-m-d H:i:s", strtotime("+90 days"));
    $statusDemo = "ACT";
    $actCodDemo = hash("sha256", $emailDemo . time());
    $tipoDemo = "NOR";

    $sqlInsertUsuario = "INSERT INTO usuario (username, useremail, userpswd, userfching, userpswdest, userpswdexp, userest, useractcod, userpswdchg, usertipo)
                         VALUES (?, ?, ?, ?, 'ACT', ?, ?, ?, ?, ?)";

    $stmtInsertUsuario = $db->prepare($sqlInsertUsuario);
    $stmtInsertUsuario->bind_param("sssssssss", $nombreDemo, $emailDemo, $passDemo, $fechaDemo, $passExpDemo, $statusDemo, $actCodDemo, $fechaDemo, $tipoDemo);

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
   7. Guardar detalle de transacción y descontar stock
========================= */
$sqlBuscarProducto = "SELECT productId, productStock FROM products WHERE productId = ? LIMIT 1";
$stmtBuscarProducto = $db->prepare($sqlBuscarProducto);

$sqlActualizarStock = "UPDATE products
                                             SET productStock = productStock - ?,
                                                     productStatus = CASE WHEN (productStock - ?) > 0 THEN 'ACT' ELSE 'INA' END
                       WHERE productId = ?
                         AND productStock >= ?";
$stmtActualizarStock = $db->prepare($sqlActualizarStock);

$sqlDetalle = "INSERT INTO transacciones_detalle
(transaccionId, productId, transDetalleCantidad, transDetallePrecio, transDetalleSubtotal)
VALUES (?, ?, ?, ?, ?)";
$stmtDetalle = $db->prepare($sqlDetalle);

foreach ($_SESSION['cart'] as $item) {
    $productId = intval($item['id'] ?? 0);
    $nombreProducto = $item['nombre'];
    $precioProducto = $item['precio'];
    $cantidadProducto = $item['cantidad'];
    $subtotalLinea = $precioProducto * $cantidadProducto;

    if ($productId <= 0) {
        die("Error: producto inválido en la carretilla ($nombreProducto).");
    }

    // Verificar existencia y stock del producto
    $stmtBuscarProducto->bind_param("i", $productId);
    $stmtBuscarProducto->execute();
    $resBuscarProducto = $stmtBuscarProducto->get_result();

    if (!$resBuscarProducto || $resBuscarProducto->num_rows === 0) {
        die("Error: el producto #$productId no existe en catálogo.");
    }

    $filaProducto = $resBuscarProducto->fetch_assoc();
    if (intval($filaProducto['productStock']) < $cantidadProducto) {
        die("Stock insuficiente para '$nombreProducto'. Disponible: " . intval($filaProducto['productStock']));
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

    // Descontar stock
    $stmtActualizarStock->bind_param("iiii", $cantidadProducto, $cantidadProducto, $productId, $cantidadProducto);
    if (!$stmtActualizarStock->execute() || $stmtActualizarStock->affected_rows <= 0) {
        die("Error al actualizar stock para el producto #$productId.");
    }
}

/* =========================
   8. Vaciar carrito 
========================= */
$_SESSION['cart'] = array();

header("Location: confirmacion.php?id=" . $transaccionId);
exit;