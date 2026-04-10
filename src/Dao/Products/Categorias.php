<?php
namespace Dao\Products;
use Dao\Table;

class Categorias extends Table
{
    public static function getAll(): array
    {
        $sql = "SELECT categoriaId, categoriaNombre FROM categorias WHERE categoriaStatus = 'ACT' ORDER BY categoriaNombre ASC";
        return self::obtenerRegistros($sql, []);
    }
}