<?php
namespace Dao\Products;
use Dao\Table;

// DAO de categorias disponibles para formularios de producto
class Categorias extends Table
{
    // =============================
    // GETALL
    // =============================
    public static function getAll(): array
    {
        // Lista categorias activas para selects de formulario
        $sql = "SELECT categoriaId, categoriaNombre FROM categorias WHERE categoriaStatus = 'ACT' ORDER BY categoriaNombre ASC";
        return self::obtenerRegistros($sql, []);
    }
}