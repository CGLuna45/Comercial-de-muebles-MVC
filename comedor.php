<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cedrika | Comedor</title>
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
        <h3>Comedor</h3>
        <p>Explora nuestra colección exclusiva de comedor.</p>
    </div>

    <div class="products-grid">
        <div class="product-card" id="mesa-roble">
            <img src="img/mesa-roble.jpg" alt="Mesa Roble Real">
            <h4>Mesa Roble Real</h4>
            <p class="category">Comedor</p>
            <p class="desc">Mesa de comedor con acabado fino y gran presencia.</p>
            <p class="price">L 14,200</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="silla-siena">
            <img src="img/silla-sienajpg.jpg" alt="Silla Siena">
            <h4>Silla Siena</h4>
            <p class="category">Comedor</p>
            <p class="desc">Diseño cómodo y elegante para tu comedor.</p>
            <p class="price">L 3,100</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="juego-capri">
            <img src="img/juego-capri.jpg" alt="Juego Capri">
            <h4>Juego Capri</h4>
            <p class="category">Comedor</p>
            <p class="desc">Juego completo para compartir momentos especiales.</p>
            <p class="price">L 22,500</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="mesa-aura">
            <img src="img/mesa-aura.jpg" alt="Mesa Aura">
            <h4>Mesa Aura</h4>
            <p class="category">Comedor</p>
            <p class="desc">Mesa redonda pensada para espacios cálidos.</p>
            <p class="price">L 12,800</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="bufetera-verona">
            <img src="img/bufetera.jpg" alt="Bufetera Verona">
            <h4>Bufetera Verona</h4>
            <p class="category">Comedor</p>
            <p class="desc">Almacenamiento y diseño en una sola pieza.</p>
            <p class="price">L 10,400</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="vitrina-cedro">
            <img src="img/vitrina-cristal.jpg" alt="Vitrina Cedro">
            <h4>Vitrina Cedro</h4>
            <p class="category">Comedor</p>
            <p class="desc">Vitrina elegante para vajilla y decoración.</p>
            <p class="price">L 13,600</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="silla-milano">
            <img src="img/silla-milano.jpg" alt="Silla Milano">
            <h4>Silla Milano</h4>
            <p class="category">Comedor</p>
            <p class="desc">Silla tapizada con líneas modernas y suaves.</p>
            <p class="price">L 4,200</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="mesa-imperial">
            <img src="img/comedor-imperial.jpg" alt="Mesa Imperial">
            <h4>Mesa Imperial</h4>
            <p class="category">Comedor</p>
            <p class="desc">Mesa amplia ideal para reuniones familiares.</p>
            <p class="price">L 18,900</p>
            <button>Añadir a la carretilla</button>
        </div>
    </div>
</section>

</body>
</html>
