<?php
session_start();

$cart_count = 0;
if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $cart_count += $item['cantidad'];
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cédrika | Inicio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --cedro: #5C4033;
            --dorado: #C5A059;
            --arena: #F7F1EB;
            --blanco: #ffffff;
            --gris: #777;
            --sombra: 0 8px 24px rgba(0,0,0,0.08);
            --radio: 18px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: var(--arena);
            color: #333;
        }

        .header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(8px);
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: var(--sombra);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
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
            text-decoration: none;
            color: var(--cedro);
            font-weight: 700;
            font-size: 0.95rem;
            transition: 0.3s;
        }

        .nav-menu a:hover {
            color: var(--dorado);
        }

        .badge {
            background: #d35400;
            color: white;
            padding: 3px 8px;
            border-radius: 999px;
            font-size: 0.78rem;
            margin-left: 6px;
        }

        .hero {
            min-height: 88vh;
            background: linear-gradient(rgba(92,64,51,0.65), rgba(92,64,51,0.65)),
                        url('img/sala.jpg') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
            color: white;
        }

        .hero-content {
            max-width: 800px;
        }

        .hero h1 {
            font-size: 3rem;
            margin-bottom: 18px;
        }

        .hero p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 28px;
        }

        .btn-main {
            display: inline-block;
            background: var(--dorado);
            color: white;
            padding: 15px 32px;
            border-radius: 999px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-main:hover {
            background: var(--cedro);
        }

        .section {
            padding: 70px 7%;
            text-align: center;
        }

        .section h2 {
            color: var(--cedro);
            font-size: 2rem;
            margin-bottom: 14px;
        }

        .section p {
            color: var(--gris);
            max-width: 700px;
            margin: auto;
            line-height: 1.8;
        }

        .cards {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 28px;
        }

        .card {
            background: white;
            border-radius: var(--radio);
            padding: 30px 20px;
            box-shadow: var(--sombra);
        }

        .card h3 {
            color: var(--cedro);
            margin-bottom: 10px;
        }

        .card p {
            color: var(--gris);
            line-height: 1.6;
        }

        .gallery {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 25px;
        }

        .gallery img {
            width: 100%;
            height: 240px;
            object-fit: cover;
            border-radius: 18px;
            box-shadow: var(--sombra);
        }

        .reviews {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .review-card {
            background: white;
            border-radius: var(--radio);
            padding: 25px;
            box-shadow: var(--sombra);
            text-align: left;
        }

        .review-card h4 {
            color: var(--cedro);
            margin-bottom: 8px;
        }

        .stars {
            color: #f1b500;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        footer {
            background: var(--cedro);
            color: white;
            text-align: center;
            padding: 35px 20px;
            margin-top: 40px;
        }

        @media(max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 15px;
            }

            .nav-menu {
                flex-wrap: wrap;
                justify-content: center;
            }

            .hero h1 {
                font-size: 2.2rem;
            }
        }
    </style>
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
    </nav>
</header>

<section class="hero">
    <div class="hero-content">
        <h1>Elegancia artesanal para cada rincón</h1>
        <p>
            Descubre nuestra colección exclusiva de muebles para sala, comedor y escritorio.
            Diseños modernos, acabados finos y esencia catracha.
        </p>
        <a href="catalogo.php" class="btn-main">Explorar Catálogo</a>
    </div>
</section>

<section class="section">
    <h2>Nuestras Categorías</h2>
    <p>Encuentra piezas ideales para transformar tu hogar con estilo, comodidad y personalidad.</p>

    <div class="cards">
        <div class="card">
            <h3>Sala</h3>
            <p>Sofás, mesas de centro, sillones y muebles decorativos para tu sala.</p>
        </div>
        <div class="card">
            <h3>Comedor</h3>
            <p>Mesas, sillas, vitrinas y bufeteras para compartir en familia.</p>
        </div>
        <div class="card">
            <h3>Escritorio</h3>
            <p>Escritorios, sillas ejecutivas, libreros y muebles funcionales para trabajar.</p>
        </div>
    </div>
</section>

<section class="section">
    <h2>Sobre Nuestra Tienda</h2>
    <p>
        En <strong>CÉDRIKA</strong> nos especializamos en muebles elegantes, funcionales y modernos
        para transformar cada espacio de tu hogar u oficina. Nos enfocamos en ofrecer calidad,
        diseño y confort en cada pieza.
    </p>

    <div class="cards">
        <div class="card">
            <h3>Calidad Garantizada</h3>
            <p>Trabajamos con materiales duraderos y acabados finos para brindar muebles resistentes y atractivos.</p>
        </div>
        <div class="card">
            <h3>Diseño Exclusivo</h3>
            <p>Nuestros estilos están pensados para adaptarse a hogares modernos, elegantes y acogedores.</p>
        </div>
        <div class="card">
            <h3>Atención Personalizada</h3>
            <p>Buscamos ayudarte a encontrar el mueble ideal según tus gustos, espacio y necesidades.</p>
        </div>
    </div>
</section>

<section class="section">
    <h2>Inspiración para tu Espacio</h2>
    <p>Algunas ilustraciones de ambientes que reflejan el estilo que ofrecemos en Cédrika.</p>

    <div class="gallery">
        <img src="img/ilustracion-sala.jpg" alt="Sala elegante">
        <img src="img/ilustracion-comedor.jpg" alt="Comedor moderno">
        <img src="img/ilustracion-escritorio.jpg" alt="Escritorio elegante">
    </div>
</section>

<section class="section">
    <h2>Reseñas de Clientes</h2>
    <p>La experiencia de nuestros clientes es parte esencial de nuestra identidad.</p>

    <div class="reviews">
        <div class="review-card">
            <h4>María Fernández</h4>
            <div class="stars">★★★★★</div>
            <p>“Compré un sofá para mi sala y superó mis expectativas. Muy elegante y cómodo.”</p>
        </div>

        <div class="review-card">
            <h4>Carlos Mejía</h4>
            <div class="stars">★★★★★</div>
            <p>“Excelente atención y muebles de muy buena calidad. El comedor quedó hermoso.”</p>
        </div>

        <div class="review-card">
            <h4>Ana López</h4>
            <div class="stars">★★★★★</div>
            <p>“El escritorio que compré combina perfecto con mi espacio de trabajo. Muy recomendado.”</p>
        </div>
    </div>
</section>

<footer>
    <p>© 2026 CÉDRIKA | La Ceiba, Honduras</p>
</footer>

</body>
</html>
