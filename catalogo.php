<?php
$productos = [
    ["nombre" => "Sofá Amalfi", "categoria" => "Sala", "precio" => "L 18,300", "imagen" => "img/sofa-amalfi.jpg", "pagina" => "sala.php", "ancla" => "sofa-amalfi"],
    ["nombre" => "Mesa de Centro Terra", "categoria" => "Sala", "precio" => "L 6,500", "imagen" => "img/mesa-centro.jpg", "pagina" => "sala.php", "ancla" => "mesa-centro"],
    ["nombre" => "Sillón Nórdico", "categoria" => "Sala", "precio" => "L 9,200", "imagen" => "img/sillon-nordico.jpg", "pagina" => "sala.php", "ancla" => "sillon-nordico"],
    ["nombre" => "Sofá Verona", "categoria" => "Sala", "precio" => "L 24,900", "imagen" => "img/sofa-verona.jpg", "pagina" => "sala.php", "ancla" => "sofa-verona"],
    ["nombre" => "Mesa Auxiliar Brisa", "categoria" => "Sala", "precio" => "L 3,800", "imagen" => "img/mesa-auxiliar-brisa.jpg", "pagina" => "sala.php", "ancla" => "mesa-auxiliar-brisa"],
    ["nombre" => "Butaca Cedro", "categoria" => "Sala", "precio" => "L 7,600", "imagen" => "img/butaca-cedro.jpg", "pagina" => "sala.php", "ancla" => "butaca-cedro"],
    ["nombre" => "Mueble TV Oslo", "categoria" => "Sala", "precio" => "L 11,500", "imagen" => "img/mueble-tv-oslo.jpg", "pagina" => "sala.php", "ancla" => "mueble-tv-oslo"],
    ["nombre" => "Consola Siena", "categoria" => "Sala", "precio" => "L 8,900", "imagen" => "img/consola-siena.jpg", "pagina" => "sala.php", "ancla" => "consola-siena"],
    ["nombre" => "Mesa Roble Real", "categoria" => "Comedor", "precio" => "L 14,200", "imagen" => "img/mesa-roble.jpg", "pagina" => "comedor.php", "ancla" => "mesa-roble"],
    ["nombre" => "Silla Siena", "categoria" => "Comedor", "precio" => "L 3,100", "imagen" => "img/silla-siena.jpg", "pagina" => "comedor.php", "ancla" => "silla-siena"],
    ["nombre" => "Juego Capri", "categoria" => "Comedor", "precio" => "L 22,500", "imagen" => "img/juego-capri.jpg", "pagina" => "comedor.php", "ancla" => "juego-capri"],
    ["nombre" => "Mesa Aura", "categoria" => "Comedor", "precio" => "L 12,800", "imagen" => "img/mesa-aura.jpg", "pagina" => "comedor.php", "ancla" => "mesa-aura"],
    ["nombre" => "Bufetera Verona", "categoria" => "Comedor", "precio" => "L 10,400", "imagen" => "img/bufetera-verona.jpg", "pagina" => "comedor.php", "ancla" => "bufetera-verona"],
    ["nombre" => "Vitrina Cedro", "categoria" => "Comedor", "precio" => "L 13,600", "imagen" => "img/vitrina-cedro.jpg", "pagina" => "comedor.php", "ancla" => "vitrina-cedro"],
    ["nombre" => "Silla Milano", "categoria" => "Comedor", "precio" => "L 4,200", "imagen" => "img/silla-milano.jpg", "pagina" => "comedor.php", "ancla" => "silla-milano"],
    ["nombre" => "Mesa Imperial", "categoria" => "Comedor", "precio" => "L 18,900", "imagen" => "img/mesa-imperial.jpg", "pagina" => "comedor.php", "ancla" => "mesa-imperial"],
    ["nombre" => "Escritorio Cedro", "categoria" => "Escritorio", "precio" => "L 14,300", "imagen" => "img/escritorio-cedro.jpg", "pagina" => "escritorio.php", "ancla" => "escritorio-cedro"],
    ["nombre" => "Librero Minimal", "categoria" => "Escritorio", "precio" => "L 7,300", "imagen" => "img/librero.jpg", "pagina" => "escritorio.php", "ancla" => "librero"],
    ["nombre" => "Silla Ejecutiva", "categoria" => "Escritorio", "precio" => "L 5,900", "imagen" => "img/silla-ejecutiva-oslo.jpg", "pagina" => "escritorio.php", "ancla" => "silla-ejecutiva-oslo"],
    ["nombre" => "Escritorio Verona", "categoria" => "Escritorio", "precio" => "L 12,500", "imagen" => "img/escritorio-verona.jpg", "pagina" => "escritorio.php", "ancla" => "escritorio-verona"],
    ["nombre" => "Archivador Terra", "categoria" => "Escritorio", "precio" => "L 6,200", "imagen" => "img/archivador-terra.jpg", "pagina" => "escritorio.php", "ancla" => "archivador-terra"],
    ["nombre" => "Estantería Aura", "categoria" => "Escritorio", "precio" => "L 8,100", "imagen" => "img/estanteria-aura.jpg", "pagina" => "escritorio.php", "ancla" => "estanteria-aura"],
    ["nombre" => "Mesa Estudio", "categoria" => "Escritorio", "precio" => "L 9,400", "imagen" => "img/mesa-estudio-siena.jpg", "pagina" => "escritorio.php", "ancla" => "mesa-estudio-siena"],
    ["nombre" => "Escritorio Ejecutivo", "categoria" => "Escritorio", "precio" => "L 16,800", "imagen" => "img/escritorio-ejecutivo-roble.jpg", "pagina" => "escritorio.php", "ancla" => "escritorio-ejecutivo-roble"],
];

$buscar = isset($_GET["buscar"]) ? trim($_GET["buscar"]) : "";
$resultados = [];

if ($buscar !== "") {
    foreach ($productos as $producto) {
        if (strcasecmp($producto["nombre"], $buscar) === 0) {
            header("Location: " . $producto["pagina"] . "#" . $producto["ancla"]);
            exit;
        }
    }
}

if ($buscar === "") {
    $resultados = $productos;
} else {
    foreach ($productos as $producto) {
        if (
            stripos($producto["nombre"], $buscar) !== false ||
            stripos($producto["categoria"], $buscar) !== false
        ) {
            $resultados[] = $producto;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cedrika | Catálogo</title>
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
            <input type="text" name="buscar" placeholder="Buscar muebles..." value="<?php echo htmlspecialchars($buscar); ?>">
            <button type="submit">🔍</button>
        </form>

        <a href="carrito.php" class="cart-btn">🛒</a>
    </div>
</header>

<section class="products-section">
    <div class="section-title">
        <h3>Catálogo General</h3>
        <p>Explora todos nuestros muebles disponibles.</p>
    </div>

    <?php if ($buscar !== ""): ?>
        <h2 class="results-title">Resultados para: "<?php echo htmlspecialchars($buscar); ?>"</h2>
    <?php endif; ?>

    <?php if (count($resultados) > 0): ?>
        <div class="products-grid">
            <?php foreach ($resultados as $producto): ?>
                <a href="<?php echo $producto["pagina"] . '#' . $producto["ancla"]; ?>" style="text-decoration:none; color:inherit;">
                    <div class="product-card">
                        <img src="<?php echo $producto["imagen"]; ?>" alt="<?php echo htmlspecialchars($producto["nombre"]); ?>">
                        <h4><?php echo htmlspecialchars($producto["nombre"]); ?></h4>
                        <p class="category"><?php echo htmlspecialchars($producto["categoria"]); ?></p>
                        <p class="price"><?php echo htmlspecialchars($producto["precio"]); ?></p>
                        <button>Ver producto</button>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-results">No se encontraron resultados para tu búsqueda.</p>
    <?php endif; ?>
</section>

</body>
</html>
