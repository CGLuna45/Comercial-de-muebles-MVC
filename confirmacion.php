<?php
session_start();
require_once 'db.php';

// Muestra el comprobante de una transaccion ya registrada

if (!isset($_GET['id'])) {
    header("Location: carrito.php");
    exit;
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM transacciones WHERE transaccionId = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();
$transaccion = $resultado->fetch_assoc();

if (!$transaccion) {
    die("No se encontró la transacción.");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación - Cédrika</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --cedro: #5C4033;
            --dorado: #C5A059;
            --arena: #F2E8DF;
            --blanco: #FAF9F6;
            --gris: #333333;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background-color: var(--arena);
            color: var(--gris);
        }

        .contenedor {
            width: 90%;
            max-width: 800px;
            margin: 60px auto;
        }

        .caja {
            background-color: var(--blanco);
            border-left: 8px solid var(--dorado);
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }

        h1 {
            color: var(--cedro);
            margin-bottom: 20px;
        }

        p {
            margin-bottom: 12px;
            font-size: 1rem;
        }

        .btn {
            display: inline-block;
            margin-top: 20px;
            background-color: var(--cedro);
            color: white;
            text-decoration: none;
            padding: 12px 22px;
            border-radius: 8px;
            font-weight: bold;
        }

        .btn:hover {
            opacity: 0.92;
        }
    </style>
</head>
<body>

<div class="contenedor">
    <div class="caja">
        <h1>Compra realizada con éxito</h1>

        <p><strong>Número de transacción:</strong> <?php echo $transaccion['transaccionId']; ?></p>
        <p><strong>Fecha:</strong> <?php echo $transaccion['transaccionFecha']; ?></p>
        <p><strong>Total pagado:</strong> L. <?php echo number_format($transaccion['transaccionTotal'], 2); ?></p>
        <p><strong>Estado:</strong> <?php echo htmlspecialchars($transaccion['paypalStatus']); ?></p>
        <p><strong>Referencia:</strong> <?php echo htmlspecialchars($transaccion['paypalOrderId']); ?></p>

        <a href="index.php" class="btn">Volver al inicio</a>
    </div>
</div>

</body>
</html>