-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-03-2026 a las 02:44:15
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;

--
-- Base de datos: `comercial_muebles`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritilla`
--

CREATE TABLE `carritilla` (
    `carritillaId` int(11) NOT NULL,
    `usuarioId` bigint(10) NOT NULL,
    `carritillaStatus` char(3) NOT NULL DEFAULT 'ACT',
    `carritillaFecha` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritilla_detalle`
--

CREATE TABLE `carritilla_detalle` (
    `detalleId` int(11) NOT NULL,
    `carritillaId` int(11) NOT NULL,
    `productId` int(11) NOT NULL,
    `detalleCantidad` int(11) NOT NULL DEFAULT 1,
    `detallePrecio` decimal(10, 2) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
    `categoriaId` int(11) NOT NULL,
    `categoriaNombre` varchar(100) NOT NULL,
    `categoriaDescripcion` varchar(255) NOT NULL,
    `categoriaStatus` char(3) NOT NULL DEFAULT 'ACT'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones`
--

CREATE TABLE `funciones` (
    `funcionId` int(11) NOT NULL,
    `funcionNombre` varchar(100) NOT NULL,
    `funcionDescripcion` varchar(200) NOT NULL,
    `funcionStatus` char(3) NOT NULL DEFAULT 'ACT'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `funciones_roles`
--

CREATE TABLE `funciones_roles` (
    `funcionRolId` int(11) NOT NULL,
    `funcionId` int(11) NOT NULL,
    `rolId` int(11) NOT NULL,
    `frStatus` char(3) NOT NULL DEFAULT 'ACT',
    `frFechaInicio` datetime NOT NULL DEFAULT current_timestamp(),
    `frFechaFin` datetime NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `highlights`
--

CREATE TABLE `highlights` (
    `highlightId` int(11) NOT NULL,
    `productId` int(11) NOT NULL,
    `highlightStart` datetime NOT NULL,
    `highlightEnd` datetime NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
    `id` int(11) NOT NULL,
    `nombre` varchar(100) NOT NULL,
    `categoria` varchar(50) NOT NULL,
    `precio` decimal(10, 2) NOT NULL,
    `imagen` varchar(255) NOT NULL,
    `pagina` varchar(50) NOT NULL,
    `ancla` varchar(50) NOT NULL,
    `stock` int(11) DEFAULT 10
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO
    `productos` (
        `id`,
        `nombre`,
        `categoria`,
        `precio`,
        `imagen`,
        `pagina`,
        `ancla`,
        `stock`
    )
VALUES (
        1,
        'Sofá Amalfi',
        'Sala',
        18300.00,
        'img/sofa-amalfi.jpg',
        '',
        '',
        10
    ),
    (
        2,
        'Mesa de Centro Terra',
        'Sala',
        6500.00,
        'img/mesa-centro.jpg',
        '',
        '',
        10
    ),
    (
        3,
        'Sillón Nórdico',
        'Sala',
        9200.00,
        'img/sillon-nordico.jpg',
        '',
        '',
        10
    ),
    (
        4,
        'Sofá Verona',
        'Sala',
        24900.00,
        'img/sofa-verona.jpg',
        '',
        '',
        10
    ),
    (
        5,
        'Mesa Auxiliar Brisa',
        'Sala',
        3800.00,
        'img/mesa-auxiliar-brisa.jpg',
        '',
        '',
        10
    ),
    (
        6,
        'Butaca Cedro',
        'Sala',
        7600.00,
        'img/butaca-cedro.jpg',
        '',
        '',
        10
    ),
    (
        7,
        'Mueble TV Oslo',
        'Sala',
        11500.00,
        'img/mueble-tv-oslo.jpg',
        '',
        '',
        10
    ),
    (
        8,
        'Consola Siena',
        'Sala',
        8900.00,
        'img/consola-siena.jpg',
        '',
        '',
        10
    ),
    (
        9,
        'Mesa Roble Real',
        'Comedor',
        14200.00,
        'img/mesa-roble.jpg',
        '',
        '',
        10
    ),
    (
        10,
        'Silla Siena',
        'Comedor',
        3100.00,
        'img/silla-siena.jpg',
        '',
        '',
        10
    ),
    (
        11,
        'Juego Capri',
        'Comedor',
        22500.00,
        'img/juego-capri.jpg',
        '',
        '',
        10
    ),
    (
        12,
        'Mesa Aura',
        'Comedor',
        12800.00,
        'img/mesa-aura.jpg',
        '',
        '',
        10
    ),
    (
        13,
        'Bufetera Verona',
        'Comedor',
        10400.00,
        'img/bufetera-verona.jpg',
        '',
        '',
        10
    ),
    (
        14,
        'Vitrina Cedro',
        'Comedor',
        13600.00,
        'img/vitrina-cedro.jpg',
        '',
        '',
        10
    ),
    (
        15,
        'Silla Milano',
        'Comedor',
        4200.00,
        'img/silla-milano.jpg',
        '',
        '',
        10
    ),
    (
        16,
        'Mesa Imperial',
        'Comedor',
        18900.00,
        'img/mesa-imperial.jpg',
        '',
        '',
        10
    ),
    (
        17,
        'Escritorio Cedro',
        'Escritorio',
        14300.00,
        'img/escritorio-cedro.jpg',
        '',
        '',
        10
    ),
    (
        18,
        'Librero Minimal',
        'Escritorio',
        7300.00,
        'img/librero.jpg',
        '',
        '',
        10
    ),
    (
        19,
        'Silla Ejecutiva',
        'Escritorio',
        5900.00,
        'img/silla-ejecutiva-oslo.jpg',
        '',
        '',
        10
    ),
    (
        20,
        'Escritorio Verona',
        'Escritorio',
        12500.00,
        'img/escritorio-verona.jpg',
        '',
        '',
        10
    ),
    (
        21,
        'Archivador Terra',
        'Escritorio',
        6200.00,
        'img/archivador-terra.jpg',
        '',
        '',
        10
    ),
    (
        22,
        'Estantería Aura',
        'Escritorio',
        8100.00,
        'img/estanteria-aura.jpg',
        '',
        '',
        10
    ),
    (
        23,
        'Mesa Estudio',
        'Escritorio',
        9400.00,
        'img/mesa-estudio-siena.jpg',
        '',
        '',
        10
    ),
    (
        24,
        'Escritorio Ejecutivo',
        'Escritorio',
        16800.00,
        'img/escritorio-ejecutivo-roble.jpg',
        '',
        '',
        10
    );

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
    `productId` int(11) NOT NULL,
    `categoriaId` int(11) NOT NULL,
    `productName` varchar(255) NOT NULL,
    `productDescription` text NOT NULL,
    `productPrice` decimal(10, 2) NOT NULL,
    `productStock` int(11) NOT NULL DEFAULT 0,
    `productImgUrl` varchar(255) NOT NULL,
    `productStatus` char(3) NOT NULL DEFAULT 'ACT'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
    `rolId` int(11) NOT NULL,
    `rolNombre` varchar(50) NOT NULL,
    `rolDescripcion` varchar(150) NOT NULL,
    `rolStatus` char(3) NOT NULL DEFAULT 'ACT'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_usuarios`
--

CREATE TABLE `roles_usuarios` (
    `rolUsuarioId` int(11) NOT NULL,
    `usuarioId` bigint(10) NOT NULL,
    `rolId` int(11) NOT NULL,
    `ruStatus` char(3) NOT NULL DEFAULT 'ACT',
    `ruFechaInicio` datetime NOT NULL DEFAULT current_timestamp(),
    `ruFechaFin` datetime NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sales`
--

CREATE TABLE `sales` (
    `saleId` int(11) NOT NULL,
    `productId` int(11) NOT NULL,
    `salePrice` decimal(10, 2) NOT NULL,
    `saleStart` datetime NOT NULL,
    `saleEnd` datetime NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
    `transaccionId` int(11) NOT NULL,
    `usuarioId` bigint(10) NOT NULL,
    `carritillaId` int(11) NOT NULL,
    `transaccionTotal` decimal(10, 2) NOT NULL,
    `transaccionStatus` char(3) NOT NULL DEFAULT 'PEN',
    `transaccionFecha` datetime NOT NULL DEFAULT current_timestamp(),
    `paypalOrderId` varchar(100) DEFAULT NULL,
    `paypalStatus` varchar(50) DEFAULT NULL,
    `paypalPayerId` varchar(100) DEFAULT NULL,
    `paypalFecha` datetime DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones_detalle`
--

CREATE TABLE `transacciones_detalle` (
    `transDetalleId` int(11) NOT NULL,
    `transaccionId` int(11) NOT NULL,
    `productId` int(11) NOT NULL,
    `transDetalleCantidad` int(11) NOT NULL,
    `transDetallePrecio` decimal(10, 2) NOT NULL,
    `transDetalleSubtotal` decimal(10, 2) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
    `usercod` bigint(10) NOT NULL,
    `useremail` varchar(80) DEFAULT NULL,
    `username` varchar(80) DEFAULT NULL,
    `userpswd` varchar(128) DEFAULT NULL,
    `userfching` datetime DEFAULT NULL,
    `userpswdest` char(3) DEFAULT 'ACT',
    `userpswdexp` datetime DEFAULT NULL,
    `userest` char(3) DEFAULT 'ACT',
    `useractcod` varchar(128) DEFAULT NULL,
    `userpswdchg` varchar(128) DEFAULT NULL,
    `usertipo` char(3) DEFAULT 'NOR'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Datos de prueba y configuración inicial
--

INSERT INTO `categorias` (`categoriaId`, `categoriaNombre`, `categoriaDescripcion`, `categoriaStatus`) VALUES
(1, 'Sala', 'Muebles para sala de estar', 'ACT'),
(2, 'Comedor', 'Muebles para comedor', 'ACT'),
(3, 'Dormitorio', 'Muebles para dormitorio', 'ACT'),
(4, 'Oficina', 'Muebles para oficina', 'ACT');

INSERT INTO `products` (`productId`, `categoriaId`, `productName`, `productDescription`, `productPrice`, `productStock`, `productImgUrl`, `productStatus`)
SELECT
    p.id,
    CASE p.categoria
        WHEN 'Sala' THEN 1
        WHEN 'Comedor' THEN 2
        WHEN 'Dormitorio' THEN 3
        WHEN 'Escritorio' THEN 4
        ELSE 1
    END AS categoriaId,
    p.nombre AS productName,
    CONCAT(p.nombre, ' - Catálogo CÉDRIKA') AS productDescription,
    p.precio AS productPrice,
    p.stock AS productStock,
    p.imagen AS productImgUrl,
    'ACT' AS productStatus
FROM productos p;

INSERT INTO `roles` (`rolId`, `rolNombre`, `rolDescripcion`, `rolStatus`) VALUES
(1, 'admin', 'Administrador del sistema', 'ACT'),
(2, 'cliente', 'Cliente de la tienda', 'ACT'),
(3, 'auditor', 'Auditor solo lectura', 'ACT');

INSERT INTO `funciones` (`funcionId`, `funcionNombre`, `funcionDescripcion`, `funcionStatus`) VALUES
(1, 'Menu_Products', 'Acceso al menú de productos', 'ACT'),
(2, 'Menu_Users', 'Acceso al menú de usuarios', 'ACT'),
(3, 'Menu_Transactions', 'Acceso al menú de transacciones', 'ACT'),
(4, 'Menu_Purchases', 'Acceso al menú de compras', 'ACT');

INSERT INTO `usuario` (`usercod`, `useremail`, `username`, `userpswd`, `userfching`, `userpswdest`, `userpswdexp`, `userest`, `useractcod`, `userpswdchg`, `usertipo`) VALUES
(1, 'admincerika@gmail.com', 'Administrador CEDRIKA', '$2y$10$2bj3XCZBvURKI7JMe0c/bOfgMWjDHfLRuOR6Ag/2iDuZK6yKnldta', NOW(), 'ACT', DATE_ADD(NOW(), INTERVAL 3650 DAY), 'ACT', SHA2('admincerika@gmail.com', 256), NOW(), 'ADM');

INSERT INTO `roles_usuarios` (`rolUsuarioId`, `usuarioId`, `rolId`, `ruStatus`, `ruFechaInicio`, `ruFechaFin`) VALUES
(1, 1, 1, 'ACT', NOW(), '2099-12-31 23:59:59');

INSERT INTO `funciones_roles` (`funcionRolId`, `funcionId`, `rolId`, `frStatus`, `frFechaInicio`, `frFechaFin`) VALUES
(1, 1, 1, 'ACT', NOW(), '2099-12-31 23:59:59'),
(2, 2, 1, 'ACT', NOW(), '2099-12-31 23:59:59'),
(3, 3, 1, 'ACT', NOW(), '2099-12-31 23:59:59'),
(4, 4, 1, 'ACT', NOW(), '2099-12-31 23:59:59');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carritilla`
--
ALTER TABLE `carritilla`
ADD PRIMARY KEY (`carritillaId`),
ADD KEY `fk_carritilla_usuario_idx` (`usuarioId`);

--
-- Indices de la tabla `carritilla_detalle`
--
ALTER TABLE `carritilla_detalle`
ADD PRIMARY KEY (`detalleId`),
ADD KEY `fk_detalle_carritilla_idx` (`carritillaId`),
ADD KEY `fk_detalle_product_idx` (`productId`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias` ADD PRIMARY KEY (`categoriaId`);

--
-- Indices de la tabla `funciones`
--
ALTER TABLE `funciones`
ADD PRIMARY KEY (`funcionId`),
ADD UNIQUE KEY `uk_funciones_nombre` (`funcionNombre`);

--
-- Indices de la tabla `funciones_roles`
--
ALTER TABLE `funciones_roles`
ADD PRIMARY KEY (`funcionRolId`),
ADD KEY `fk_fr_funcion_idx` (`funcionId`),
ADD KEY `fk_fr_rol_idx` (`rolId`);

--
-- Indices de la tabla `highlights`
--
ALTER TABLE `highlights`
ADD PRIMARY KEY (`highlightId`),
ADD KEY `fk_highlights_products_idx` (`productId`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos` ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
ADD PRIMARY KEY (`productId`),
ADD KEY `fk_products_categoria_idx` (`categoriaId`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
ADD PRIMARY KEY (`rolId`),
ADD UNIQUE KEY `uk_roles_nombre` (`rolNombre`);

--
-- Indices de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
ADD PRIMARY KEY (`rolUsuarioId`),
ADD KEY `fk_ru_usuario_idx` (`usuarioId`),
ADD KEY `fk_ru_rol_idx` (`rolId`);

--
-- Indices de la tabla `sales`
--
ALTER TABLE `sales`
ADD PRIMARY KEY (`saleId`),
ADD KEY `fk_sales_products_idx` (`productId`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
ADD PRIMARY KEY (`transaccionId`),
ADD KEY `fk_trans_usuario_idx` (`usuarioId`),
ADD KEY `fk_trans_carritilla_idx` (`carritillaId`);

--
-- Indices de la tabla `transacciones_detalle`
--
ALTER TABLE `transacciones_detalle`
ADD PRIMARY KEY (`transDetalleId`),
ADD KEY `fk_td_transaccion_idx` (`transaccionId`),
ADD KEY `fk_td_product_idx` (`productId`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
ADD PRIMARY KEY (`usercod`),
ADD UNIQUE KEY `uk_usuario_email` (`useremail`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carritilla`
--
ALTER TABLE `carritilla`
MODIFY `carritillaId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carritilla_detalle`
--
ALTER TABLE `carritilla_detalle`
MODIFY `detalleId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
MODIFY `categoriaId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `funciones`
--
ALTER TABLE `funciones`
MODIFY `funcionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `funciones_roles`
--
ALTER TABLE `funciones_roles`
MODIFY `funcionRolId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `highlights`
--
ALTER TABLE `highlights`
MODIFY `highlightId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,
AUTO_INCREMENT = 25;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles` MODIFY `rolId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
MODIFY `rolUsuarioId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sales`
--
ALTER TABLE `sales`
MODIFY `saleId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
MODIFY `transaccionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones_detalle`
--
ALTER TABLE `transacciones_detalle`
MODIFY `transDetalleId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
MODIFY `usercod` bigint(10) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `carritilla`
--
ALTER TABLE `carritilla`
ADD CONSTRAINT `fk_carritilla_usuario` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`usercod`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `carritilla_detalle`
--
ALTER TABLE `carritilla_detalle`
ADD CONSTRAINT `fk_detalle_carritilla` FOREIGN KEY (`carritillaId`) REFERENCES `carritilla` (`carritillaId`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_detalle_product` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `funciones_roles`
--
ALTER TABLE `funciones_roles`
ADD CONSTRAINT `fk_fr_funcion` FOREIGN KEY (`funcionId`) REFERENCES `funciones` (`funcionId`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_fr_rol` FOREIGN KEY (`rolId`) REFERENCES `roles` (`rolId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `highlights`
--
ALTER TABLE `highlights`
ADD CONSTRAINT `fk_highlights_products` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
ADD CONSTRAINT `fk_products_categoria` FOREIGN KEY (`categoriaId`) REFERENCES `categorias` (`categoriaId`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `roles_usuarios`
--
ALTER TABLE `roles_usuarios`
ADD CONSTRAINT `fk_ru_rol` FOREIGN KEY (`rolId`) REFERENCES `roles` (`rolId`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `fk_ru_usuario` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`usercod`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `sales`
--
ALTER TABLE `sales`
ADD CONSTRAINT `fk_sales_products` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
ADD CONSTRAINT `fk_trans_carritilla` FOREIGN KEY (`carritillaId`) REFERENCES `carritilla` (`carritillaId`) ON UPDATE CASCADE,
ADD CONSTRAINT `fk_trans_usuario` FOREIGN KEY (`usuarioId`) REFERENCES `usuario` (`usercod`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `transacciones_detalle`
--
ALTER TABLE `transacciones_detalle`
ADD CONSTRAINT `fk_td_product` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON UPDATE CASCADE,
ADD CONSTRAINT `fk_td_transaccion` FOREIGN KEY (`transaccionId`) REFERENCES `transacciones` (`transaccionId`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;