<?php
class Producto {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    // Obtener todos los productos
    public function obtenerTodos() {
        $sql = "SELECT * FROM productos ORDER BY categoria, nombre";
        return $this->db->query($sql);
    }

    // Buscar productos por nombre o categoría
    public function buscar($search) {
        $search = $this->db->real_escape_string($search);
        $sql = "SELECT * FROM productos 
                WHERE nombre LIKE '%$search%' 
                OR categoria LIKE '%$search%'
                ORDER BY categoria, nombre";
        return $this->db->query($sql);
    }

    // Filtrar por categoría
    public function filtrarPorCategoria($categoria) {
        $categoria = $this->db->real_escape_string($categoria);
        $sql = "SELECT * FROM productos WHERE categoria = '$categoria' ORDER BY nombre";
        return $this->db->query($sql);
    }

    // Buscar + categoría
    public function buscarYCategoria($search, $categoria) {
        $search = $this->db->real_escape_string($search);
        $categoria = $this->db->real_escape_string($categoria);

        $sql = "SELECT * FROM productos
                WHERE categoria = '$categoria'
                AND (nombre LIKE '%$search%' OR categoria LIKE '%$search%')
                ORDER BY nombre";

        return $this->db->query($sql);
    }

    // Obtener producto por ID
    public function obtenerPorId($id) {
        $id = intval($id);
        $sql = "SELECT * FROM productos WHERE id = $id LIMIT 1";
        return $this->db->query($sql);
    }
}
?>