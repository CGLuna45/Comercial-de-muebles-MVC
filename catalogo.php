<?php
require_once 'db.php';
require_once 'controllers/ProductoController.php';

$controller = new ProductoController($db);
$controller->catalogo();
?>