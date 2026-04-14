<?php

namespace Dao\Commerce;

use Dao\Table;

class Transacciones extends Table
{
    public static function getTransactions(
        string $partial = "",
        string $status = "",
        int $page = 0,
        int $itemsPerPage = 10
    ): array {
        $sql = "SELECT
                    t.transaccionId,
                    t.usuarioId,
                    COALESCE(u.username, 'Sin usuario') AS usuarioNombre,
                    COALESCE(u.useremail, 'N/A') AS usuarioEmail,
                    t.transaccionTotal,
                    t.transaccionStatus,
                    t.transaccionFecha,
                    t.paypalOrderId,
                    t.paypalStatus
                FROM transacciones t
                LEFT JOIN usuario u ON u.usercod = t.usuarioId
                WHERE 1=1 ";
        $countSql = "SELECT COUNT(*) AS total FROM transacciones t LEFT JOIN usuario u ON u.usercod = t.usuarioId WHERE 1=1 ";
        $params = [];

        if ($partial !== "") {
            $sql .= " AND (u.username LIKE :partial OR u.useremail LIKE :partial OR t.paypalOrderId LIKE :partial) ";
            $countSql .= " AND (u.username LIKE :partial OR u.useremail LIKE :partial OR t.paypalOrderId LIKE :partial) ";
            $params["partial"] = "%" . $partial . "%";
        }

        if (in_array($status, ["PEN", "PRO", "PAG", "CAN", "ERR", "COM", "ACT", "INA"])) {
            $sql .= " AND t.transaccionStatus = :status ";
            $countSql .= " AND t.transaccionStatus = :status ";
            $params["status"] = $status;
        }

        $sql .= " ORDER BY t.transaccionFecha DESC, t.transaccionId DESC";

        $totalResult = self::obtenerUnRegistro($countSql, $params);
        $total = intval($totalResult["total"] ?? 0);

        $offset = $page * $itemsPerPage;
        $sql .= " LIMIT $offset, $itemsPerPage";

        $rows = self::obtenerRegistros($sql, $params);

        return [
            "transactions" => $rows,
            "total" => $total,
            "page" => $page,
            "itemsPerPage" => $itemsPerPage
        ];
    }

    public static function getTransactionsByUser(int $userId, int $page = 0, int $itemsPerPage = 10): array
    {
        $sql = "SELECT
                    t.transaccionId,
                    t.usuarioId,
                    COALESCE(u.username, 'Sin usuario') AS usuarioNombre,
                    COALESCE(u.useremail, 'N/A') AS usuarioEmail,
                    t.transaccionTotal,
                    t.transaccionStatus,
                    t.transaccionFecha,
                    t.paypalOrderId,
                    t.paypalStatus
                FROM transacciones t
                LEFT JOIN usuario u ON u.usercod = t.usuarioId
                WHERE t.usuarioId = :userId
                ORDER BY t.transaccionFecha DESC, t.transaccionId DESC";
        $countSql = "SELECT COUNT(*) AS total FROM transacciones t WHERE t.usuarioId = :userId";
        $params = ["userId" => $userId];

        $totalResult = self::obtenerUnRegistro($countSql, $params);
        $total = intval($totalResult["total"] ?? 0);

        $offset = $page * $itemsPerPage;
        $sql .= " LIMIT $offset, $itemsPerPage";

        $rows = self::obtenerRegistros($sql, $params);

        return [
            "transactions" => $rows,
            "total" => $total,
            "page" => $page,
            "itemsPerPage" => $itemsPerPage
        ];
    }

    public static function getTransactionDetailsByTransactionId(int $transactionId): array
    {
        $sql = "SELECT
                    td.transDetalleId,
                    td.transaccionId,
                    td.productId,
                    COALESCE(p.productName, CONCAT('Producto #', td.productId)) AS productName,
                    td.transDetalleCantidad,
                    td.transDetallePrecio,
                    td.transDetalleSubtotal
                FROM transacciones_detalle td
                LEFT JOIN products p ON p.productId = td.productId
                WHERE td.transaccionId = :transaccionId
                ORDER BY td.transDetalleId ASC";

        return self::obtenerRegistros($sql, ["transaccionId" => $transactionId]);
    }
}
