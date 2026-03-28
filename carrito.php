<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cedrika | Carrito</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
    <div class="logo-area"><h1>CÉDRIKA</h1></div>
    <div class="search-cart-group">
        <a href="index.php" class="back-btn">← Menú</a>
        <form action="catalogo.php" method="GET" class="search-box">
            <input type="text" name="buscar" placeholder="Buscar muebles...">
            <button type="submit">🔍</button>
        </form>
        <a href="carrito.php" class="cart-btn">🛒</a>
    </div>
</header>

<section class="cart-page">
    <h2 class="cart-title">Tu Carretilla de Compras</h2>

    <div class="cart-layout">
        <div class="cart-items">
            <div class="cart-item">
                <img src="img/sofa-amalfi.jpg" alt="Sofá Amalfi">
                <div class="cart-item-info">
                    <h4>Sofá Amalfi</h4>
                    <p>Categoría: Sala</p>
                    <p>Cantidad: 1</p>
                </div>
                <div class="cart-price">L 18,300</div>
            </div>

            <div class="cart-item">
                <img src="img/mesa-roble.jpg" alt="Mesa Roble Real">
                <div class="cart-item-info">
                    <h4>Mesa Roble Real</h4>
                    <p>Categoría: Comedor</p>
                    <p>Cantidad: 1</p>
                </div>
                <div class="cart-price">L 14,200</div>
            </div>

            <div class="cart-item">
                <img src="img/escritorio-cedro.jpg" alt="Escritorio Cedro">
                <div class="cart-item-info">
                    <h4>Escritorio Cedro</h4>
                    <p>Categoría: Escritorio</p>
                    <p>Cantidad: 1</p>
                </div>
                <div class="cart-price">L 14,300</div>
            </div>
        </div>

        <div class="cart-summary">
            <h3 style="color:#5C4033; margin-bottom:18px;">Resumen del Pedido</h3>

            <div class="summary-row"><span>Subtotal</span><span>L 46,800</span></div>
            <div class="summary-row"><span>Envío</span><span>L 350</span></div>
            <div class="summary-row"><span>Impuesto</span><span>L 702</span></div>
            <div class="summary-total"><span>Total</span><span>L 47,852</span></div>

            <button class="pay-btn" onclick="window.location.href='pago-exitoso.php'">Pagar con Tarjeta</button>
            <button class="secondary-btn" onclick="window.location.href='catalogo.php'">Seguir comprando</button>
        </div>
    </div>
</section>

</body>
</html>
