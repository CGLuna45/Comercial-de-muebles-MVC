-- ============================================================
-- SCRIPT BASE DE DATOS - COMERCIAL DE MUEBLES
-- Proyecto Final - Desarrollo de Negocios Web
-- Basado en el framework SimplePHPMvcOOP del Ing.
-- ============================================================

CREATE DATABASE IF NOT EXISTS comercial_muebles;
USE comercial_muebles;

-- ============================================================
-- SECCION 1: ESQUEMA DE SEGURIDAD RBAC
-- Basado en el esquema visto en clase
-- ============================================================

CREATE TABLE `usuarios` (
  `usuarioId`     int(11)       NOT NULL AUTO_INCREMENT,
  `usuarioNombre` varchar(100)  NOT NULL,
  `usuarioEmail`  varchar(150)  NOT NULL,
  `usuarioPass`   varchar(255)  NOT NULL,
  `usuarioStatus` char(3)       NOT NULL DEFAULT 'ACT',
  PRIMARY KEY (`usuarioId`),
  UNIQUE KEY `uk_usuarios_email` (`usuarioEmail`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `roles` (
  `rolId`          int(11)      NOT NULL AUTO_INCREMENT,
  `rolNombre`      varchar(50)  NOT NULL,
  `rolDescripcion` varchar(150) NOT NULL,
  `rolStatus`      char(3)      NOT NULL DEFAULT 'ACT',
  PRIMARY KEY (`rolId`),
  UNIQUE KEY `uk_roles_nombre` (`rolNombre`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `funciones` (
  `funcionId`          int(11)      NOT NULL AUTO_INCREMENT,
  `funcionNombre`      varchar(100) NOT NULL,
  `funcionDescripcion` varchar(200) NOT NULL,
  `funcionStatus`      char(3)      NOT NULL DEFAULT 'ACT',
  PRIMARY KEY (`funcionId`),
  UNIQUE KEY `uk_funciones_nombre` (`funcionNombre`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `roles_usuarios` (
  `rolUsuarioId`  int(11)  NOT NULL AUTO_INCREMENT,
  `usuarioId`     int(11)  NOT NULL,
  `rolId`         int(11)  NOT NULL,
  `ruStatus`      char(3)  NOT NULL DEFAULT 'ACT',
  `ruFechaInicio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ruFechaFin`    datetime NOT NULL,
  PRIMARY KEY (`rolUsuarioId`),
  KEY `fk_ru_usuario_idx` (`usuarioId`),
  KEY `fk_ru_rol_idx`     (`rolId`),
  CONSTRAINT `fk_ru_usuario` FOREIGN KEY (`usuarioId`) REFERENCES `usuarios` (`usuarioId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_ru_rol`     FOREIGN KEY (`rolId`)     REFERENCES `roles`    (`rolId`)     ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `funciones_roles` (
  `funcionRolId`  int(11)  NOT NULL AUTO_INCREMENT,
  `funcionId`     int(11)  NOT NULL,
  `rolId`         int(11)  NOT NULL,
  `frStatus`      char(3)  NOT NULL DEFAULT 'ACT',
  `frFechaInicio` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `frFechaFin`    datetime NOT NULL,
  PRIMARY KEY (`funcionRolId`),
  KEY `fk_fr_funcion_idx` (`funcionId`),
  KEY `fk_fr_rol_idx`     (`rolId`),
  CONSTRAINT `fk_fr_funcion` FOREIGN KEY (`funcionId`) REFERENCES `funciones` (`funcionId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_fr_rol`     FOREIGN KEY (`rolId`)     REFERENCES `roles`     (`rolId`)     ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SECCION 2: CATALOGO DE PRODUCTOS
-- Adaptado para Comercial de Muebles
-- ============================================================

CREATE TABLE `categorias` (
  `categoriaId`          int(11)      NOT NULL AUTO_INCREMENT,
  `categoriaNombre`      varchar(100) NOT NULL,
  `categoriaDescripcion` varchar(255) NOT NULL,
  `categoriaStatus`      char(3)      NOT NULL DEFAULT 'ACT',
  PRIMARY KEY (`categoriaId`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `products` (
  `productId`          int(11)        NOT NULL AUTO_INCREMENT,
  `categoriaId`        int(11)        NOT NULL,
  `productName`        varchar(255)   NOT NULL,
  `productDescription` text           NOT NULL,
  `productPrice`       decimal(10,2)  NOT NULL,
  `productStock`       int(11)        NOT NULL DEFAULT 0,
  `productImgUrl`      varchar(255)   NOT NULL,
  `productStatus`      char(3)        NOT NULL DEFAULT 'ACT',
  PRIMARY KEY (`productId`),
  KEY `fk_products_categoria_idx` (`categoriaId`),
  CONSTRAINT `fk_products_categoria` FOREIGN KEY (`categoriaId`) REFERENCES `categorias` (`categoriaId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `highlights` (
  `highlightId`    int(11)  NOT NULL AUTO_INCREMENT,
  `productId`      int(11)  NOT NULL,
  `highlightStart` datetime NOT NULL,
  `highlightEnd`   datetime NOT NULL,
  PRIMARY KEY (`highlightId`),
  KEY `fk_highlights_products_idx` (`productId`),
  CONSTRAINT `fk_highlights_products` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `sales` (
  `saleId`      int(11)       NOT NULL AUTO_INCREMENT,
  `productId`   int(11)       NOT NULL,
  `salePrice`   decimal(10,2) NOT NULL,
  `saleStart`   datetime      NOT NULL,
  `saleEnd`     datetime      NOT NULL,
  PRIMARY KEY (`saleId`),
  KEY `fk_sales_products_idx` (`productId`),
  CONSTRAINT `fk_sales_products` FOREIGN KEY (`productId`) REFERENCES `products` (`productId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SECCION 3: CARRITO DE COMPRAS
-- ============================================================

CREATE TABLE `carritilla` (
  `carritillaId`     int(11)       NOT NULL AUTO_INCREMENT,
  `usuarioId`        int(11)       NOT NULL,
  `carritillaStatus` char(3)       NOT NULL DEFAULT 'ACT',
  `carritillaFecha`  datetime      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`carritillaId`),
  KEY `fk_carritilla_usuario_idx` (`usuarioId`),
  CONSTRAINT `fk_carritilla_usuario` FOREIGN KEY (`usuarioId`) REFERENCES `usuarios` (`usuarioId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `carritilla_detalle` (
  `detalleId`       int(11)       NOT NULL AUTO_INCREMENT,
  `carritillaId`    int(11)       NOT NULL,
  `productId`       int(11)       NOT NULL,
  `detalleCantidad` int(11)       NOT NULL DEFAULT 1,
  `detallePrecio`   decimal(10,2) NOT NULL,
  PRIMARY KEY (`detalleId`),
  KEY `fk_detalle_carritilla_idx` (`carritillaId`),
  KEY `fk_detalle_product_idx`    (`productId`),
  CONSTRAINT `fk_detalle_carritilla` FOREIGN KEY (`carritillaId`) REFERENCES `carritilla`  (`carritillaId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_detalle_product`    FOREIGN KEY (`productId`)    REFERENCES `products`    (`productId`)    ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SECCION 4: TRANSACCIONES Y PASARELA DE PAGOS
-- ============================================================

CREATE TABLE `transacciones` (
  `transaccionId`        int(11)        NOT NULL AUTO_INCREMENT,
  `usuarioId`            int(11)        NOT NULL,
  `carritillaId`         int(11)        NOT NULL,
  `transaccionTotal`     decimal(10,2)  NOT NULL,
  `transaccionStatus`    char(3)        NOT NULL DEFAULT 'PEN',
  `transaccionFecha`     datetime       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `paypalOrderId`        varchar(100)   DEFAULT NULL,
  `paypalStatus`         varchar(50)    DEFAULT NULL,
  `paypalPayerId`        varchar(100)   DEFAULT NULL,
  `paypalFecha`          datetime       DEFAULT NULL,
  PRIMARY KEY (`transaccionId`),
  KEY `fk_trans_usuario_idx`    (`usuarioId`),
  KEY `fk_trans_carritilla_idx` (`carritillaId`),
  CONSTRAINT `fk_trans_usuario`    FOREIGN KEY (`usuarioId`)    REFERENCES `usuarios`   (`usuarioId`)    ON UPDATE CASCADE,
  CONSTRAINT `fk_trans_carritilla` FOREIGN KEY (`carritillaId`) REFERENCES `carritilla` (`carritillaId`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

CREATE TABLE `transacciones_detalle` (
  `transDetalleId`       int(11)       NOT NULL AUTO_INCREMENT,
  `transaccionId`        int(11)       NOT NULL,
  `productId`            int(11)       NOT NULL,
  `transDetalleCantidad` int(11)       NOT NULL,
  `transDetallePrecio`   decimal(10,2) NOT NULL,
  `transDetalleSubtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`transDetalleId`),
  KEY `fk_td_transaccion_idx` (`transaccionId`),
  KEY `fk_td_product_idx`     (`productId`),
  CONSTRAINT `fk_td_transaccion` FOREIGN KEY (`transaccionId`) REFERENCES `transacciones` (`transaccionId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_td_product`     FOREIGN KEY (`productId`)     REFERENCES `products`      (`productId`)     ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SECCION 5: DATOS DE PRUEBA
-- ============================================================

-- Roles
INSERT INTO `roles` (`rolNombre`, `rolDescripcion`, `rolStatus`) VALUES
('admin',   'Administrador del sistema', 'ACT'),
('cliente', 'Cliente de la tienda',      'ACT'),
('auditor', 'Auditor solo lectura',      'ACT');

-- Categorias de muebles
INSERT INTO `categorias` (`categoriaNombre`, `categoriaDescripcion`, `categoriaStatus`) VALUES
('Sala',       'Muebles para sala de estar',  'ACT'),
('Comedor',    'Muebles para comedor',        'ACT'),
('Dormitorio', 'Muebles para dormitorio',     'ACT'),
('Oficina',    'Muebles para oficina',        'ACT');

-- Productos
INSERT INTO `products` (`categoriaId`, `productName`, `productDescription`, `productPrice`, `productStock`, `productImgUrl`, `productStatus`) VALUES
(1, 'Sofa 3 Plazas',        'Sofá moderno de 3 plazas tapizado en tela', 5500.00, 10, 'https://placehold.co/290x250?text=Sofa-3-Plazas&font=roboto',        'ACT'),
(1, 'Sofa Esquinero',       'Sofá esquinero en L tapizado en cuero',     8900.00,  5, 'https://placehold.co/290x250?text=Sofa-Esquinero&font=roboto',       'ACT'),
(2, 'Mesa de Comedor 6p',   'Mesa de comedor para 6 personas en madera', 4200.00,  8, 'https://placehold.co/290x250?text=Mesa-Comedor&font=roboto',          'ACT'),
(2, 'Sillas de Comedor',    'Juego de 6 sillas tapizadas para comedor',  2800.00, 15, 'https://placehold.co/290x250?text=Sillas-Comedor&font=roboto',        'ACT'),
(3, 'Cama King Size',       'Cama king size con cabecera tapizada',      7500.00,  6, 'https://placehold.co/290x250?text=Cama-King&font=roboto',             'ACT'),
(3, 'Ropero 6 Puertas',     'Ropero de 6 puertas con espejo',           6300.00,  4, 'https://placehold.co/290x250?text=Ropero-6P&font=roboto',             'ACT'),
(4, 'Escritorio Ejecutivo', 'Escritorio ejecutivo en madera y vidrio',   3800.00,  7, 'https://placehold.co/290x250?text=Escritorio-Ejecutivo&font=roboto',  'ACT'),
(4, 'Silla Ergonomica',     'Silla ergonómica con soporte lumbar',       2100.00, 12, 'https://placehold.co/290x250?text=Silla-Ergonomica&font=roboto',      'ACT');

-- Destacados
INSERT INTO `highlights` (`productId`, `highlightStart`, `highlightEnd`) VALUES
(1, '2026-01-01 00:00:00', '2026-12-31 23:59:59'),
(5, '2026-01-01 00:00:00', '2026-12-31 23:59:59');

-- Ofertas del dia
INSERT INTO `sales` (`productId`, `salePrice`, `saleStart`, `saleEnd`) VALUES
(3, 3500.00, '2026-01-01 00:00:00', '2026-12-31 23:59:59'),
(7, 3200.00, '2026-01-01 00:00:00', '2026-12-31 23:59:59');

-- ============================================================
-- USUARIO CON PERMISOS LIMITADOS PARA LA APLICACION
-- ============================================================
CREATE USER IF NOT EXISTS 'comercial'@'%' IDENTIFIED BY 'comercial';
GRANT SELECT, INSERT, UPDATE, DELETE ON comercial_muebles.* TO 'comercial'@'%';
