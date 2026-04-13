<?php

namespace Dao\Commerce;

use Dao\Table;

class Compras extends Table
{
    public static function getPurchases(
        string $partial = "",
        string $status = "",
        int $page = 0,
        int $itemsPerPage = 10
    ): array {
        $sql = "SELECT
                    p.id AS productId,
                    p.nombre AS productName,
                    p.categoria,
                    p.precio,
                    p.stock,
                    CASE
                        WHEN p.stock <= 0 THEN 25
                        WHEN p.stock <= 5 THEN (20 - p.stock)
                        ELSE 0
                    END AS sugeridoComprar
                FROM productos p
                WHERE 1=1 ";
        $countSql = "SELECT COUNT(*) AS total
                FROM productos p
                WHERE 1=1 ";
        $params = [];

        if ($partial !== "") {
            $sql .= " AND (p.nombre LIKE :partial OR p.categoria LIKE :partial OR CAST(p.id AS CHAR) LIKE :partial) ";
            $countSql .= " AND (p.nombre LIKE :partial OR p.categoria LIKE :partial OR CAST(p.id AS CHAR) LIKE :partial) ";
            $params["partial"] = "%" . $partial . "%";
        }

        if (in_array($status, ["AGO", "BAJ", "OK"])) {
            if ($status === "AGO") {
                $sql .= " AND p.stock <= 0 ";
                $countSql .= " AND p.stock <= 0 ";
            }
            if ($status === "BAJ") {
                $sql .= " AND p.stock > 0 AND p.stock <= 5 ";
                $countSql .= " AND p.stock > 0 AND p.stock <= 5 ";
            }
            if ($status === "OK") {
                $sql .= " AND p.stock > 5 ";
                $countSql .= " AND p.stock > 5 ";
            }
        }

        $sql .= " ORDER BY p.stock ASC, p.id ASC";

        $totalResult = self::obtenerUnRegistro($countSql, $params);
        $total = intval($totalResult["total"] ?? 0);

        $offset = $page * $itemsPerPage;
        $sql .= " LIMIT $offset, $itemsPerPage";

        $rows = self::obtenerRegistros($sql, $params);

        return [
            "purchases" => $rows,
            "total" => $total,
            "page" => $page,
            "itemsPerPage" => $itemsPerPage
        ];
    }
}
