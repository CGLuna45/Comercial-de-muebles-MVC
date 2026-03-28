<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cedrika | Escritorio</title>
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
        <h3>Escritorio</h3>
        <p>Explora nuestra colección exclusiva de escritorio.</p>
    </div>

    <div class="products-grid">
        <div class="product-card" id="escritorio-cedro">
            <img src="img/escritorio-cedro.jpg" alt="Escritorio Cedro">
            <h4>Escritorio Cedro</h4>
            <p class="category">Escritorio</p>
            <p class="desc">Diseño minimalista ideal para oficina o estudio.</p>
            <p class="price">L 14,300</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="librero">
            <img src="img/librero-minimal.webp" alt="Librero Minimal">
            <h4>Librero Minimal</h4>
            <p class="category">Escritorio</p>
            <p class="desc">Práctico, moderno y perfecto para organizar.</p>
            <p class="price">L 7,300</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="silla-ejecutiva-oslo">
            <img src="img/silla-ejecutiva.webp" alt="Silla Ejecutiva">
            <h4>Silla Ejecutiva</h4>
            <p class="category">Escritorio</p>
            <p class="desc">Silla ergonómica para largas jornadas.</p>
            <p class="price">L 5,900</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="escritorio-verona">
            <img src="img/escritorio-verona.jpg" alt="Escritorio Verona">
            <h4>Escritorio Verona</h4>
            <p class="category">Escritorio</p>
            <p class="desc">Superficie amplia con diseño moderno.</p>
            <p class="price">L 12,500</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="archivador-terra">
            <img src="img/archivador.webp" alt="Archivador Terra">
            <h4>Archivador Terra</h4>
            <p class="category">Escritorio</p>
            <p class="desc">Organización funcional con estilo.</p>
            <p class="price">L 6,200</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="estanteria-aura">
            <img src="img/estanteria aurora.jpg" alt="Estantería Aura">
            <h4>Estantería Aura</h4>
            <p class="category">Escritorio</p>
            <p class="desc">Estantería elegante para libros y accesorios.</p>
            <p class="price">L 8,100</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="mesa-estudio-siena">
            <img src="img/mesa-estudio.webp" alt="Mesa Estudio">
            <h4>Mesa Estudio</h4>
            <p class="category">Escritorio</p>
            <p class="desc">Pieza funcional para estudio con diseño limpio.</p>
            <p class="price">L 9,400</p>
            <button>Añadir a la carretilla</button>
        </div>
        <div class="product-card" id="escritorio-ejecutivo-roble">
            <img src="img/mesaejecutivo.webp" alt="Escritorio Ejecutivo">
            <h4>Escritorio Ejecutivo</h4>
            <p class="category">Escritorio</p>
            <p class="desc">Modelo amplio ideal para oficinas ejecutivas.</p>
            <p class="price">L 16,800</p>
            <button>Añadir a la carretilla</button>
        </div>
    </div>
</section>

</body>
</html>
