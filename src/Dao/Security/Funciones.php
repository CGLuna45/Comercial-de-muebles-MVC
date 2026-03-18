<?php

namespace Dao\Security;

use Dao\Table;

class Funciones extends Table
{
    public static function getFunciones(
        string $partialName = "",
        string $status = "",
        string $type = "",
        string $orderBy = "",
        bool $orderDescending = false,
        int $page = 0,
        int $itemsPerPage = 10
    ): array {
        $sql = "SELECT * FROM funciones WHERE 1=1 ";
        $countSql = "SELECT COUNT(*) as total FROM funciones WHERE 1=1 ";
        $params = [];

        if ($partialName !== "") {
            $sql .= " AND (fncod LIKE :partialName OR fndsc LIKE :partialName) ";
            $countSql .= " AND (fncod LIKE :partialName OR fndsc LIKE :partialName) ";
            $params["partialName"] = "%" . $partialName . "%";
        }

        if ($status !== "") {
            $sql .= " AND fnest = :status ";
            $countSql .= " AND fnest = :status ";
            $params["status"] = $status;
        }

        if ($type !== "") {
            $sql .= " AND fntyp = :type ";
            $countSql .= " AND fntyp = :type ";
            $params["type"] = $type;
        }

        $allowedOrderFields = ["fncod", "fndsc", "fnest", "fntyp"];
        if ($orderBy !== "" && in_array($orderBy, $allowedOrderFields)) {
            $sql .= " ORDER BY " . $orderBy;
            if ($orderDescending) {
                $sql .= " DESC";
            }
        }

        $totalResult = self::obtenerUnRegistro($countSql, $params);
        $total = $totalResult["total"] ?? 0;

        if ($itemsPerPage > 0) {
            $offset = $page * $itemsPerPage;
            $sql .= " LIMIT $offset, $itemsPerPage";
        }

        $registros = self::obtenerRegistros($sql, $params);

        return [
            "funciones" => $registros,
            "total" => $total,
            "page" => $page,
            "itemsPerPage" => $itemsPerPage
        ];
    }

    public static function getFuncionById(string $fncod): array|false
    {
        $sql = "SELECT * FROM funciones WHERE fncod = :fncod";
        $params = ["fncod" => $fncod];
        return self::obtenerUnRegistro($sql, $params);
    }

    public static function insertFuncion(
        string $fncod,
        string $fndsc,
        string $fnest,
        string $fntyp
    ): int {
        $sql = "INSERT INTO funciones (fncod, fndsc, fnest, fntyp) 
                VALUES (:fncod, :fndsc, :fnest, :fntyp)";
        $params = [
            "fncod" => $fncod,
            "fndsc" => $fndsc,
            "fnest" => $fnest,
            "fntyp" => $fntyp
        ];
        return self::executeNonQuery($sql, $params);
    }

    public static function updateFuncion(
        string $fncod,
        string $fndsc,
        string $fnest,
        string $fntyp
    ): int {
        $sql = "UPDATE funciones 
                SET fndsc = :fndsc,
                    fnest = :fnest,
                    fntyp = :fntyp
                WHERE fncod = :fncod";
        $params = [
            "fncod" => $fncod,
            "fndsc" => $fndsc,
            "fnest" => $fnest,
            "fntyp" => $fntyp
        ];
        return self::executeNonQuery($sql, $params);
    }

    public static function deleteFuncion(string $fncod): int
    {
        $sql = "DELETE FROM funciones WHERE fncod = :fncod";
        $params = ["fncod" => $fncod];
        return self::executeNonQuery($sql, $params);
    }
}
