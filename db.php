<?php
$db = new mysqli("localhost", "root", "", "comercial_muebles");

if ($db->connect_error) {
    die("Error de conexión: " . $db->connect_error);
}

$db->set_charset("utf8");
?>