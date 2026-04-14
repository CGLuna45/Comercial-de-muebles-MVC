<?php

namespace Dao\Commerce;

use Dao\Table;
use Dao\Products\Products as ProductsDao;

class Compras extends Table
{
    // =============================
    // GETPURCHASES
    // =============================
    public static function getPurchases(
        string $partial = "",
        string $status = "",
        int $page = 0,
        int $itemsPerPage = 10
    ): array {
        // Obtiene ventas por producto con filtros y total vendido
        ProductsDao::syncLegacyProducts();

        $sql = "SELECT
                    p.productId AS productId,
                    p.productName AS productName,
                    COALESCE(c.categoriaNombre, 'General') AS categoria,
                    p.productPrice AS precio,
                    p.productStock AS stock,
                    COALESCE(v.totalVendidas, 0) AS cantidadesVendidas
                FROM products p
                LEFT JOIN categorias c
                    ON c.categoriaId = p.categoriaId
                LEFT JOIN (
                    SELECT
                        td.productId,
                        SUM(td.transDetalleCantidad) AS totalVendidas
                    FROM transacciones_detalle td
                    GROUP BY td.productId
                ) v ON v.productId = p.productId
                WHERE 1=1 ";
        $countSql = "SELECT COUNT(*) AS total
                FROM products p
                LEFT JOIN categorias c
                    ON c.categoriaId = p.categoriaId
                WHERE 1=1 ";
        $params = [];

        if ($partial !== "") {
            $sql .= " AND (p.productName LIKE :partial OR c.categoriaNombre LIKE :partial OR CAST(p.productId AS CHAR) LIKE :partial) ";
            $countSql .= " AND (p.productName LIKE :partial OR c.categoriaNombre LIKE :partial OR CAST(p.productId AS CHAR) LIKE :partial) ";
            $params["partial"] = "%" . $partial . "%";
        }

        if (in_array($status, ["AGO", "BAJ", "OK"])) {
            if ($status === "AGO") {
                $sql .= " AND p.productStock <= 0 ";
                $countSql .= " AND p.productStock <= 0 ";
            }
            if ($status === "BAJ") {
                $sql .= " AND p.productStock > 0 AND p.productStock <= 5 ";
                $countSql .= " AND p.productStock > 0 AND p.productStock <= 5 ";
            }
            if ($status === "OK") {
                $sql .= " AND p.productStock > 5 ";
                $countSql .= " AND p.productStock > 5 ";
            }
        }

        $sql .= " ORDER BY p.productStock ASC, p.productId ASC";

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
