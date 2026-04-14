<?php
// Modelo del catalogo publico legacy sobre tabla products/categorias
class Producto {
    private $db;

    // =============================
    // __CONSTRUCT
    // =============================
    public function __construct($conexion) {
        // Guarda la conexion para consultas del catalogo
        $this->db = $conexion;
    }

    // Obtener todos los productos
    // =============================
    // OBTENERTODOS
    // =============================
    public function obtenerTodos() {
        // Devuelve catalogo activo con stock disponible
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

    // Buscar productos por nombre o categoria
    // =============================
    // BUSCAR
    // =============================
    public function buscar($search) {
        // Aplica busqueda parcial por nombre o categoria
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

    // Filtrar por categoria
    // =============================
    // FILTRARPORCATEGORIA
    // =============================
    public function filtrarPorCategoria($categoria) {
        // Devuelve solo productos activos de una categoria
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

    // Buscar + categoria
    // =============================
    // BUSCARYCATEGORIA
    // =============================
    public function buscarYCategoria($search, $categoria) {
        // Combina filtro por texto y categoria
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
    // =============================
    // OBTENERPORID
    // =============================
    public function obtenerPorId($id) {
        // Consulta un producto especifico para operaciones puntuales
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