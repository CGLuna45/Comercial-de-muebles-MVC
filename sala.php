<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cedrika | Sala de Estar</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
    <div class="logo-area">
        <h1>CÉDRIKA</h1>
    </div>

    <div class="search-cart-group">
        <a href="index.php" class="back-btn">← Menú</a>

        <form action="catalogo.php" method="GET" class="search-box">
            <input type="text" name="buscar" placeholder="Buscar muebles...">
            <button type="submit">🔍</button>
        </form>

        <a href="carrito.php" class="cart-btn">🛒</a>
    </div>
</header>


<section class="products-section">
    <div class="section-title">
        <h3>Sala de Estar</h3>
        <p>Explora nuestra colección exclusiva de sala de estar.</p>
    </div>

    <div class="products-grid">
        <div class="product-card" id="sofa-amalfi">
            <img src="img/sofa-amalfi.jpg" alt="Sofá Amalfi">
            <h4>Sofá Amalfi</h4>
            <p class="category">Sala</p>
            <p class="desc">Diseño elegante de tres plazas para una sala sofisticada.</p>
            <p class="price">L 18,300</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="mesa-centro">
            <img src="img/mesa-centro.jpg" alt="Mesa de Centro Terra">
            <h4>Mesa de Centro Terra</h4>
            <p class="category">Sala</p>
            <p class="desc">Mesa decorativa ideal para ambientes acogedores.</p>
            <p class="price">L 6,500</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="sillon-nordico">
            <img src="img/sillon-nordico.jpg" alt="Sillón Nórdico">
            <h4>Sillón Nórdico</h4>
            <p class="category">Sala</p>
            <p class="desc">Sillón individual cómodo con un estilo moderno.</p>
            <p class="price">L 9,200</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="sofa-verona">
            <img src="img/sofa-verona.jpg" alt="Sofá Verona">
            <h4>Sofá Verona</h4>
            <p class="category">Sala</p>
            <p class="desc">Modelo amplio perfecto para espacios contemporáneos.</p>
            <p class="price">L 24,900</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="mesa-auxiliar-brisa">
            <img src="img/mesa-auxiliar-brisa.webp" alt="Mesa Auxiliar Brisa">
            <h4>Mesa Auxiliar Brisa</h4>
            <p class="category">Sala</p>
            <p class="desc">Complemento funcional y delicado para tu sala.</p>
            <p class="price">L 3,800</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="butaca-cedro">
            <img src="img/butaca-cedro.jpg" alt="Butaca Cedro">
            <h4>Butaca Cedro</h4>
            <p class="category">Sala</p>
            <p class="desc">Butaca elegante con acabado premium y gran comodidad.</p>
            <p class="price">L 7,600</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="mueble-tv-oslo">
            <img src="img/mueble-tv-oslo.webp" alt="Mueble TV Oslo">
            <h4>Mueble TV Oslo</h4>
            <p class="category">Sala</p>
            <p class="desc">Mueble moderno para televisión con estilo limpio.</p>
            <p class="price">L 11,500</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="consola-siena">
            <img src="img/consola-siena.webp" alt="Consola Siena">
            <h4>Consola Siena</h4>
            <p class="category">Sala</p>
            <p class="desc">Pieza decorativa ideal para entradas o rincones.</p>
            <p class="price">L 8,900</p>
            <button>Añadir a la carretilla</button>
        </div>
    </div>
</section>

</body>
</html>
