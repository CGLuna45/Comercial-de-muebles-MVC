<?php

namespace Dao\Commerce;

use Dao\Table;

class Purchases extends Table
{
    public static function getPurchases(
        string $partial = '',
        string $status = '',
        int $page = 0,
        int $itemsPerPage = 10
    ): array {
        $sql = "SELECT
                    td.transDetalleId,
                    td.transaccionId,
                    td.productId,
                    COALESCE(p.nombre, CONCAT('Producto #', td.productId)) AS productName,
                    td.transDetalleCantidad,
                    td.transDetallePrecio,
                    td.transDetalleSubtotal,
                    t.transaccionFecha,
                    t.transaccionStatus,
                COALESCE(u.username, 'Sin usuario') AS usuarioNombre
                FROM transacciones_detalle td
                INNER JOIN transacciones t ON t.transaccionId = td.transaccionId
                LEFT JOIN productos p ON p.id = td.productId
            LEFT JOIN usuario u ON u.usercod = t.usuarioId
                WHERE 1=1 ";
        $countSql = 'SELECT COUNT(*) AS total
                FROM transacciones_detalle td
                INNER JOIN transacciones t ON t.transaccionId = td.transaccionId
                LEFT JOIN productos p ON p.id = td.productId
            LEFT JOIN usuario u ON u.usercod = t.usuarioId
                WHERE 1=1 ';
        $params = [];

        if ($partial !== '') {
            $sql .= ' AND (p.nombre LIKE :partial OR u.username LIKE :partial OR CAST(td.transaccionId AS CHAR) LIKE :partial) ';
            $countSql .= ' AND (p.nombre LIKE :partial OR u.username LIKE :partial OR CAST(td.transaccionId AS CHAR) LIKE :partial) ';
            $params['partial'] = '%'.$partial.'%';
        }

        if (in_array($status, ['PEN', 'PRO', 'PAG', 'CAN', 'ERR', 'COM', 'ACT', 'INA'])) {
            $sql .= ' AND t.transaccionStatus = :status ';
            $countSql .= ' AND t.transaccionStatus = :status ';
            $params['status'] = $status;
        }

        $sql .= ' ORDER BY t.transaccionFecha DESC, td.transDetalleId DESC';

        $totalResult = self::obtenerUnRegistro($countSql, $params);
        $total = intval($totalResult['total'] ?? 0);

        $offset = $page * $itemsPerPage;
        $sql .= " LIMIT $offset, $itemsPerPage";

        $rows = self::obtenerRegistros($sql, $params);

        return [
            'purchases' => $rows,
            'total' => $total,
            'page' => $page,
            'itemsPerPage' => $itemsPerPage,
        ];
    }
}
