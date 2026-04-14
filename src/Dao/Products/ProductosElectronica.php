<?php

namespace Dao\Products;

use Dao\Table;

// DAO auxiliar de tabla legacy ProductosElectronica
class ProductosElectronica extends Table
{
    // =============================
    // GETALL
    // =============================
    public static function getAll(): array
    {
        // Devuelve todos los registros de la tabla auxiliar
        $sql = "SELECT * FROM ProductosElectronica;";
        return self::obtenerRegistros($sql, []);
    }

    // =============================
    // GETBYID
    // =============================
    public static function getById(int $id): array
    {
        // Busca un registro puntual por ID
        $sql = "SELECT * FROM ProductosElectronica WHERE id_producto=:id;";
        $producto = self::obtenerUnRegistro($sql, ["id" => $id]);

        if (!$producto) {
            return [];
        }

        return $producto;
    }

    // =============================
    // INSERT
    // =============================
    public static function insert(
        string $nombre,
        string $tipo,
        float $precio,
        string $marca,
        string $fecha
    ): int {
        // Inserta un producto en la tabla auxiliar

        $sql = "INSERT INTO ProductosElectronica
        (nombre,tipo,precio,marca,fecha_lanzamiento)
        VALUES
        (:nombre,:tipo,:precio,:marca,:fecha);";

        $params = [
            "nombre" => $nombre,
            "tipo" => $tipo,
            "precio" => $precio,
            "marca" => $marca,
            "fecha" => $fecha
        ];

        return self::executeNonQuery($sql, $params);
    }

    // =============================
    // UPDATE
    // =============================
    public static function update(
        int $id,
        string $nombre,
        string $tipo,
        float $precio,
        string $marca,
        string $fecha
    ): int {
        // Actualiza un registro de la tabla auxiliar

        $sql = "UPDATE ProductosElectronica SET
        nombre=:nombre,
        tipo=:tipo,
        precio=:precio,
        marca=:marca,
        fecha_lanzamiento=:fecha
        WHERE id_producto=:id;";

        $params = [
            "id" => $id,
            "nombre" => $nombre,
            "tipo" => $tipo,
            "precio" => $precio,
            "marca" => $marca,
            "fecha" => $fecha
        ];

        return self::executeNonQuery($sql, $params);
    }

    // =============================
    // DELETE
    // =============================
    public static function delete(int $id): int
    {
        // Elimina un registro por ID
        $sql = "DELETE FROM ProductosElectronica WHERE id_producto=:id;";
        return self::executeNonQuery($sql, ["id" => $id]);
    }
}
