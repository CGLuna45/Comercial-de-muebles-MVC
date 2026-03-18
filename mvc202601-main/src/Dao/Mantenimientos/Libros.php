<?php

namespace Dao\Mantenimientos;

use Dao\Table;

class Libros extends Table
{
    /*
    libros
    
    id int NOT NULL PRIMARY KEY AUTO_INCREMENT COMMENT 'Primary Key',
    titulo VARCHAR(255) NOT NULL COMMENT 'Título del libro',
    resumen VARCHAR(255) NOT NULL UNIQUE COMMENT 'Resumen del libro, debe ser único',
    autor VARCHAR(255) NOT NULL COMMENT 'Nombre del autor del libro',
    fecha_publicacion DATE NOT NULL COMMENT 'Fecha de publicación del libro',
    genero VARCHAR(100) NOT NULL COMMENT 'Género literario del libro',
    precio DECIMAL(10, 2) NOT NULL COMMENT 'Precio del libro'
*/

    public static function getAllLibros(): array
    {
        $libros = [];
        $sqlstr = "SELECT * from libros;";
        $libros = self::obtenerRegistros($sqlstr, []);
        return $libros;
    }

    public static function getLibroById(int $id): array
    {
        $sqlstr = "SELECT * from libros where id= :id;";
        $param = ["id" => $id];
        return self::obtenerUnRegistro($sqlstr, $param);
    }

    public static function crearLibro(
        $titulo,
        $resumen,
        $autor,
        $fecha_publicacion,
        $genero,
        $precio
    ): int {
        $sqlstr = "insert into libros ( titulo, resumen, autor, fecha_publicacion, genero, precio)
                   values (:titulo, :resumen, :autor, :fecha_publicacion, :genero, :precio);";

        $affectedRow = self::executeNonQuery($sqlstr, [
            "titulo" => $titulo,
            "resumen" => $resumen,
            "autor" => $autor,
            "fecha_publicacion" => $fecha_publicacion,
            "genero" => $genero,
            "precio" => $precio
        ]);
        return $affectedRow;
    }

    public static function actualizarLibro(
        $id,
        $titulo,
        $resumen,
        $autor,
        $fecha_publicacion,
        $genero,
        $precio
    ): int {
        $sqlstr = "update libros set titulo = :titulo, resumen = :resumen, autor = :autor,
                    fecha_publicacion = :fecha_publicacion, genero = :genero, precio = :precio
                    where id = :id;";

        $affectedRow = self::executeNonQuery($sqlstr, [
            "id" => $id,
            "titulo" => $titulo,
            "resumen" => $resumen,
            "autor" => $autor,
            "fecha_publicacion" => $fecha_publicacion,
            "genero" => $genero,
            "precio" => $precio
        ]);
        return $affectedRow;
    }

    public static function eliminarLibro(
        $id
    ): int {
        $sqlstr = "delete from libros where id = :id;";

        $affectedRow = self::executeNonQuery($sqlstr, ["id" => $id]);
        return $affectedRow;
    }
}
