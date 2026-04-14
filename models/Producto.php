<?php
class Producto {
    private $db;

    public function __construct($conexion) {
        $this->db = $conexion;
    }

    // Obtener todos los productos
    public function obtenerTodos() {
        $sql = "SELECT
                    p.productId AS id,
                    p.productName AS nombre,
                    c.categoriaNombre AS categoria,
                    p.productPrice AS precio,
                    p.productStock AS stock,
                    p.productImgUrl AS imagen
                FROM products p
                LEFT JOIN categorias c ON c.categoriaId = p.categoriaId
                                WHERE p.productStatus = 'ACT'
                                    AND p.productStock > 0
                ORDER BY c.categoriaNombre, p.productName";
        return $this->db->query($sql);
    }

    // Buscar productos por nombre o categoría
    public function buscar($search) {
        $search = $this->db->real_escape_string($search);
        $sql = "SELECT
                    p.productId AS id,
                    p.productName AS nombre,
                    c.categoriaNombre AS categoria,
                    p.productPrice AS precio,
                    p.productStock AS stock,
                    p.productImgUrl AS imagen
                FROM products p
                LEFT JOIN categorias c ON c.categoriaId = p.categoriaId
                WHERE p.productStatus = 'ACT'
                                    AND p.productStock > 0
                  AND (p.productName LIKE '%$search%' OR c.categoriaNombre LIKE '%$search%')
                ORDER BY c.categoriaNombre, p.productName";
        return $this->db->query($sql);
    }

    // Filtrar por categoría
    public function filtrarPorCategoria($categoria) {
        $categoria = $this->db->real_escape_string($categoria);
        $sql = "SELECT
                    p.productId AS id,
                    p.productName AS nombre,
                    c.categoriaNombre AS categoria,
                    p.productPrice AS precio,
                    p.productStock AS stock,
                    p.productImgUrl AS imagen
                FROM products p
                LEFT JOIN categorias c ON c.categoriaId = p.categoriaId
                WHERE p.productStatus = 'ACT'
                                    AND p.productStock > 0
                  AND c.categoriaNombre = '$categoria'
                ORDER BY p.productName";
        return $this->db->query($sql);
    }

    // Buscar + categoría
    public function buscarYCategoria($search, $categoria) {
        $search = $this->db->real_escape_string($search);
        $categoria = $this->db->real_escape_string($categoria);

        $sql = "SELECT
                    p.productId AS id,
                    p.productName AS nombre,
                    c.categoriaNombre AS categoria,
                    p.productPrice AS precio,
                    p.productStock AS stock,
                    p.productImgUrl AS imagen
                FROM products p
                LEFT JOIN categorias c ON c.categoriaId = p.categoriaId
                WHERE p.productStatus = 'ACT'
                  AND c.categoriaNombre = '$categoria'
                  AND (p.productName LIKE '%$search%' OR c.categoriaNombre LIKE '%$search%')
                ORDER BY p.productName";

        return $this->db->query($sql);
    }

    // Obtener producto por ID
    public function obtenerPorId($id) {
        $id = intval($id);
        $sql = "SELECT
                    p.productId AS id,
                    p.productName AS nombre,
                    c.categoriaNombre AS categoria,
                    p.productPrice AS precio,
                    p.productStock AS stock,
                    p.productImgUrl AS imagen
                FROM products p
                LEFT JOIN categorias c ON c.categoriaId = p.categoriaId
                                WHERE p.productId = $id
                                    AND p.productStock > 0
                LIMIT 1";
        return $this->db->query($sql);
    }
}
?>