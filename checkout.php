<?php
session_start();

$subtotal = 0;
$cantidadTotal = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += $item['precio'] * $item['cantidad'];
        $cantidadTotal += $item['cantidad'];
    }
}

if ($cantidadTotal <= 0) {
    header("Location: carrito.php");
    exit;
}

$isv = $subtotal * 0.15;
$total = $subtotal + $isv;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Cédrika</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F2E8DF;
            color: #333333;
            margin: 0;
            padding: 0;
        }

        .contenedor {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            display: flex;
            gap: 20px;
        }

        .caja {
            background-color: #FAF9F6;
            border-radius: 12px;
            padding: 20px;
            flex: 1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        h1, h2 {
            color: #5C4033;
        }

        .producto {
            border-bottom: 1px solid #ddd;
            padding: 10px 0;
        }

        .btn {
            display: inline-block;
            background-color: #C5A059;
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 15px;
            font-weight: bold;
        }

        .btn-secundario {
            background-color: #5C4033;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <div class="caja">
        <h1>Resumen de tu compra</h1>

        <?php foreach ($_SESSION['cart'] as $item) { ?>
            <div class="producto">
                <p><strong><?php echo $item['nombre']; ?></strong></p>
                <p>Cantidad: <?php echo $item['cantidad']; ?></p>
                <p>Precio: L. <?php echo number_format($item['precio'], 2); ?></p>
                <p>Subtotal: L. <?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></p>
            </div>
        <?php } ?>
    </div>

    <div class="caja">
        <h2>Pago Simulado</h2>
        <p><strong>Subtotal:</strong> L. <?php echo number_format($subtotal, 2); ?></p>
        <p><strong>ISV (15%):</strong> L. <?php echo number_format($isv, 2); ?></p>
        <p><strong>Total:</strong> L. <?php echo number_format($total, 2); ?></p>

        <form action="procesar_pago.php" method="post">
            <button type="submit" class="btn">Confirmar Pago</button>
        </form>

        <a href="carrito.php" class="btn btn-secundario">Volver al carrito</a>
    </div>
</div>

</body>
</html>