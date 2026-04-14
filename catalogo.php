<?php
require_once 'db.php';
require_once 'controllers/ProductoController.php';

// Punto de entrada del catalogo publico (flujo clasico)

$controller = new ProductoController($db);
$controller->catalogo();
?>
