<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cédrika | Catálogo Completo</title>
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

        .search-container {
            background: linear-gradient(rgba(92,64,51,0.88), rgba(92,64,51,0.88)),
                        url('/inicio-cedrika/img/sala.jpg') center/cover;
            padding: 70px 20px;
            text-align: center;
            color: white;
        }

        .search-container h2 {
            font-size: 2.2rem;
            margin-bottom: 12px;
        }

        .search-container p {
            margin-bottom: 25px;
            opacity: 0.95;
        }

        .search-form {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .search-box, .filter-select {
            padding: 14px 18px;
            border-radius: 999px;
            border: none;
            outline: none;
            font-size: 1rem;
        }

        .search-box {
            width: 340px;
        }

        .filter-select {
            min-width: 180px;
        }

        .btn-search {
            background: var(--dorado);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 999px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-search:hover {
            background: #a8823f;
        }

        .container {
            padding: 55px 7%;
        }

        .section-title {
            text-align: center;
            margin-bottom: 35px;
        }

        .section-title h3 {
            color: var(--cedro);
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .section-title p {
            color: var(--gris);
        }

        .success-msg {
            background: #d4edda;
            color: #155724;
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 600;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(270px, 1fr));
            gap: 28px;
        }

        .card {
            background: white;
            border-radius: var(--radio);
            overflow: hidden;
            box-shadow: var(--sombra);
            transition: 0.3s;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card:hover {
            transform: translateY(-7px);
        }

        .card img {
            width: 100%;
            height: 230px;
            object-fit: cover;
        }

        .card-content {
            padding: 20px;
        }

        .category {
            display: inline-block;
            background: #f5e7d1;
            color: var(--cedro);
            font-size: 0.8rem;
            font-weight: bold;
            padding: 6px 12px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        .card h4 {
            color: var(--cedro);
            margin-bottom: 10px;
            font-size: 1.15rem;
        }

        .desc {
            font-size: 0.92rem;
            color: var(--gris);
            line-height: 1.5;
            min-height: 55px;
            margin-bottom: 14px;
        }

        .price {
            font-weight: 800;
            font-size: 1.3rem;
            color: var(--cedro);
            margin-bottom: 18px;
            display: block;
        }

        .stock {
            font-size: 0.85rem;
            color: #666;
            margin-bottom: 12px;
        }

        .form-cart {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .qty-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .qty-row span {
            font-size: 0.9rem;
            color: #555;
        }

        .qty-input {
            width: 75px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
        }

        .btn-add {
            background: var(--dorado);
            color: white;
            border: none;
            padding: 13px;
            border-radius: 999px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-add:hover {
            background: var(--cedro);
        }

        .empty-msg {
            text-align: center;
            font-size: 1.05rem;
            color: #666;
            padding: 40px 0;
        }

        footer {
            background: var(--cedro);
            color: white;
            text-align: center;
            padding: 35px 20px;
            margin-top: 60px;
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

            .search-container h2 {
                font-size: 1.7rem;
            }

            .search-box {
                width: 100%;
                max-width: 330px;
            }
        }
    </style>
</head>
<body>

<header class="header">
    <a href="index.php" class="logo-box">
        <img src="/inicio-cedrika/img/logo-cedrika.png" alt="logo" class="logo-img">
        <span class="logo-txt">CÉDRIKA</span>
    </a>

    <nav class="nav-menu">
        <a href="index.php">Inicio</a>
        <a href="catalogo.php">Catálogo</a>
        <a href="carrito.php">🛒 Carrito <span class="badge"><?php echo $cart_count; ?></span></a>
    </nav>
</header>

<section class="search-container">
    <h2>Explora nuestra colección</h2>
    <p>Encuentra muebles elegantes para transformar tus espacios.</p>

    <form action="catalogo.php" method="GET" class="search-form">
        <input type="text" name="q" class="search-box" placeholder="¿Qué mueble buscas?" value="<?php echo htmlspecialchars($search); ?>">

        <select name="categoria" class="filter-select">
            <option value="">Todas las categorías</option>
            <option value="Sala" <?php echo ($categoria == 'Sala') ? 'selected' : ''; ?>>Sala</option>
            <option value="Comedor" <?php echo ($categoria == 'Comedor') ? 'selected' : ''; ?>>Comedor</option>
            <option value="Escritorio" <?php echo ($categoria == 'Escritorio') ? 'selected' : ''; ?>>Escritorio</option>
        </select>

        <button type="submit" class="btn-search">Buscar</button>
    </form>
</section>

<div class="container">
    <div class="section-title">
        <h3>Catálogo General</h3>
        <p>Diseños exclusivos para sala, comedor y escritorio.</p>
    </div>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <div class="success-msg">Producto agregado al carrito correctamente.</div>
    <?php endif; ?>

    <div class="grid">
        <?php if (!empty($productos)): ?>
            <?php foreach($productos as $p): ?>
                <div class="card">
                    <img src="/inicio-cedrika/<?php echo htmlspecialchars($p['imagen']); ?>"
                         alt="<?php echo htmlspecialchars($p['nombre']); ?>"
                         onerror="this.src='/inicio-cedrika/img/no-image.jpg'">

                    <div class="card-content">
                        <span class="category"><?php echo htmlspecialchars($p['categoria']); ?></span>
                        <h4><?php echo htmlspecialchars($p['nombre']); ?></h4>
                        <p class="desc">
                            Mueble elegante y funcional ideal para complementar tu espacio con estilo.
                        </p>
                        <span class="price">L <?php echo number_format($p['precio'], 2); ?></span>
                        <p class="stock">Stock disponible: <?php echo $p['stock']; ?></p>

                        <form method="POST" action="carrito.php" class="form-cart">
                            <input type="hidden" name="accion" value="agregar">
                            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">

                            <div class="qty-row">
                                <span>Cantidad:</span>
                                <input type="number" name="cantidad" value="1" min="1" max="<?php echo $p['stock']; ?>" class="qty-input" required>
                            </div>

                            <button type="submit" class="btn-add">Añadir al carrito</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="empty-msg">No se encontraron productos para tu búsqueda.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>© 2026 CÉDRIKA | La Ceiba, Honduras</p>
</footer>

</body>
</html>