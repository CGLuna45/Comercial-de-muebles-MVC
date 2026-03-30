<?php

namespace Dao\Products;

use Dao\Table;

class ProductosElectronica extends Table
{
    public static function getAll(): array
    {
        $sql = "SELECT * FROM ProductosElectronica;";
        return self::obtenerRegistros($sql, []);
    }

    public static function getById(int $id): array
    {
        $sql = "SELECT * FROM ProductosElectronica WHERE id_producto=:id;";
        $producto = self::obtenerUnRegistro($sql, ["id" => $id]);

        if (!$producto) {
            return [];
        }

        return $producto;
    }

    public static function insert(
        string $nombre,
        string $tipo,
        float $precio,
        string $marca,
        string $fecha
    ): int {

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

    public static function update(
        int $id,
        string $nombre,
        string $tipo,
        float $precio,
        string $marca,
        string $fecha
    ): int {

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

    public static function delete(int $id): int
    {
        $sql = "DELETE FROM ProductosElectronica WHERE id_producto=:id;";
        return self::executeNonQuery($sql, ["id" => $id]);
    }
}
