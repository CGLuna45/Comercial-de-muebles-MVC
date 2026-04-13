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
                    COALESCE(u.usuarioNombre, 'Sin usuario') AS usuarioNombre,
                    COALESCE(u.usuarioEmail, 'N/A') AS usuarioEmail,
                    t.transaccionTotal,
                    t.transaccionStatus,
                    t.transaccionFecha,
                    t.paypalOrderId,
                    t.paypalStatus
                FROM transacciones t
                LEFT JOIN usuarios u ON u.usuarioId = t.usuarioId
                WHERE 1=1 ";
        $countSql = "SELECT COUNT(*) AS total FROM transacciones t LEFT JOIN usuarios u ON u.usuarioId = t.usuarioId WHERE 1=1 ";
        $params = [];

        if ($partial !== "") {
            $sql .= " AND (u.usuarioNombre LIKE :partial OR u.usuarioEmail LIKE :partial OR t.paypalOrderId LIKE :partial) ";
            $countSql .= " AND (u.usuarioNombre LIKE :partial OR u.usuarioEmail LIKE :partial OR t.paypalOrderId LIKE :partial) ";
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
}
