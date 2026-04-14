<?php
// Forzar TCP (127.0.0.1) en lugar de socket local para evitar "No such file or directory"
// ConexiaIn mysqli usada por las paginas legacy fuera del router MVC
$db = new mysqli("127.0.0.1", "root", "", "nwdb", 3306);

if ($db->connect_error) {
    die("Error de conexión: " . $db->connect_error);
}

$db->set_charset("utf8");
