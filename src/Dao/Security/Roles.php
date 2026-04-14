<?php

namespace Dao\Security;

use Dao\Table;

// DAO de roles para busquedas y mantenimiento administrativo
class Roles extends Table
{
    // =============================
    // GETROLES
    // =============================
    public static function getRoles(
        string $partialName = "",
        string $status = "",
        string $orderBy = "",
        bool $orderDescending = false,
        int $page = 0,
        int $itemsPerPage = 10
    ): array {
        // Lista roles con filtros, orden y paginacion
        $sql = "SELECT * FROM roles WHERE 1=1 ";
        $countSql = "SELECT COUNT(*) as total FROM roles WHERE 1=1 ";
        $params = [];

        if ($partialName !== "") {
            $sql .= " AND (rolescod LIKE :partialName OR rolesdsc LIKE :partialName) ";
            $countSql .= " AND (rolescod LIKE :partialName OR rolesdsc LIKE :partialName) ";
            $params["partialName"] = "%" . $partialName . "%";
        }

        if ($status !== "") {
            $sql .= " AND rolesest = :status ";
            $countSql .= " AND rolesest = :status ";
            $params["status"] = $status;
        }

        // Validar orderBy para evitar inyeccion SQL
        $allowedOrderFields = ["rolescod", "rolesdsc", "rolesest"];
        if ($orderBy !== "" && in_array($orderBy, $allowedOrderFields)) {
            $sql .= " ORDER BY " . $orderBy;
            if ($orderDescending) {
                $sql .= " DESC";
            }
        }

        // Obtener total de registros
        $totalResult = self::obtenerUnRegistro($countSql, $params);
        $total = $totalResult["total"] ?? 0;

        // PaginaciaIn
        if ($itemsPerPage > 0) {
            $offset = $page * $itemsPerPage;
            $sql .= " LIMIT $offset, $itemsPerPage";
        }

        $registros = self::obtenerRegistros($sql, $params);

        return [
            "roles" => $registros,
            "total" => $total,
            "page" => $page,
            "itemsPerPage" => $itemsPerPage
        ];
    }

    // =============================
    // GETROLEBYID
    // =============================
    public static function getRoleById(string $rolescod): array|false
    {
        // Devuelve un rol puntual por codigo
        $sql = "SELECT * FROM roles WHERE rolescod = :rolescod";
        $params = ["rolescod" => $rolescod];
        return self::obtenerUnRegistro($sql, $params);
    }

    // =============================
    // INSERTROLE
    // =============================
    public static function insertRole(
        string $rolescod,
        string $rolesdsc,
        string $rolesest
    ): int {
        // Inserta un nuevo rol en catalogo de seguridad
        $sql = "INSERT INTO roles (rolescod, rolesdsc, rolesest) 
                VALUES (:rolescod, :rolesdsc, :rolesest)";
        $params = [
            "rolescod" => $rolescod,
            "rolesdsc" => $rolesdsc,
            "rolesest" => $rolesest
        ];
        return self::executeNonQuery($sql, $params);
    }

    // =============================
    // UPDATEROLE
    // =============================
    public static function updateRole(
        string $rolescod,
        string $rolesdsc,
        string $rolesest
    ): int {
        // Actualiza descripcion y estado del rol
        $sql = "UPDATE roles 
                SET rolesdsc = :rolesdsc,
                    rolesest = :rolesest
                WHERE rolescod = :rolescod";
        $params = [
            "rolescod" => $rolescod,
            "rolesdsc" => $rolesdsc,
            "rolesest" => $rolesest
        ];
        return self::executeNonQuery($sql, $params);
    }

    // =============================
    // DELETEROLE
    // =============================
    public static function deleteRole(string $rolescod): int
    {
        // Elimina rol por codigo
        $sql = "DELETE FROM roles WHERE rolescod = :rolescod";
        $params = ["rolescod" => $rolescod];
        return self::executeNonQuery($sql, $params);
    }
}
