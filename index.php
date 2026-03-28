<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cedrika | Inicio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
    <div class="logo-area">
        <h1>CÉDRIKA</h1>
    </div>

    <div class="search-cart-group">
        <form action="catalogo.php" method="GET" class="search-box">
            <input type="text" name="buscar" placeholder="Buscar muebles...">
            <button type="submit">🔍</button>
        </form>

        <a href="carrito.php" class="cart-btn">🛒</a>
    </div>
</header>

<section class="hero-logo">
    <img src="img/logo-cedrika.png" alt="Logo Cedrika">
</section>

<section class="publicidad">
    <h3>MUEBLES QUE TRANSFORMAN TUS ESPACIOS</h3>
    <p>
        Descubre una colección exclusiva de muebles diseñados para sala, comedor y escritorio,
        con acabados elegantes y modernos que elevan cada espacio.
    </p>
</section>

<section class="categories">
    <h3>NUESTRAS CATEGORÍAS</h3>
    <div class="category-grid">
        <a href="sala.php" class="category-card">
            <img src="img/sala.jpg" alt="Sala">
            <span>Sala de estar</span>
        </a>

        <a href="comedor.php" class="category-card">
            <img src="img/mesa-comedor.webp" alt="Comedor">
            <span>Comedor</span>
        </a>

        <a href="escritorio.php" class="category-card">
            <img src="img/escritorio.jpg" alt="Escritorio">
            <span>Escritorio</span>
        </a>
    </div>
</section>

<section class="info-section" id="misionvision">
    <div class="info-box">
        <h3>Misión</h3>
        <p>
            Ofrecer muebles de alta calidad con diseños elegantes y funcionales,
            brindando a nuestros clientes espacios modernos, cómodos y únicos.
        </p>
    </div>

    <div class="info-box">
        <h3>Visión</h3>
        <p>
            Ser una empresa líder en Honduras en la venta de muebles modernos,
            reconocida por nuestro estilo, calidad y atención al cliente.
        </p>
    </div>
</section>

<section class="resenas" id="resenas">
    <h3>Reseñas</h3>
    <div class="resenas-grid">
        <div class="resena-card">
            <p>"Excelente calidad y diseño, mi sala quedó hermosa."</p>
            <span>- María López</span>
        </div>
        <div class="resena-card">
            <p>"Muy buen servicio y muebles modernos, recomendado."</p>
            <span>- Carlos Mejía</span>
        </div>
        <div class="resena-card">
            <p>"Cedrika tiene estilo elegante y precios accesibles."</p>
            <span>- Ana Rodríguez</span>
        </div>
    </div>
</section>

<footer class="footer" id="contacto">
    <div class="footer-content">
        <p><strong>Contáctanos:</strong> 9261-3780</p>
        <p><strong>Ubicación:</strong> San Pedro Sula, Calle Principal</p>
        <p>© 2026 Cedrika. Todos los derechos reservados.</p>
    </div>
</footer>

</body>
</html>
