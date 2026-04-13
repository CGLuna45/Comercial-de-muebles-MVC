<?php

namespace Dao\Security;

use Dao\Table;

class Users extends Table
{
    public static function getAllUsers(): array
    {
        $sql = "SELECT 
                    u.usuarioId AS usercod,
                    u.usuarioNombre AS username,
                    u.usuarioEmail AS useremail,
                    u.usuarioStatus AS userest,
                    CASE WHEN ru.rolId = 1 THEN 'ADM' ELSE 'PBL' END AS usertipo
                FROM usuarios u
                LEFT JOIN roles_usuarios ru ON ru.usuarioId = u.usuarioId AND ru.ruStatus = 'ACT'
                ORDER BY u.usuarioNombre ASC";
        return self::obtenerRegistros($sql, []);
    }

    public static function getUserById(int $usercod): array|false
    {
        $sql = "SELECT 
                    u.usuarioId AS usercod,
                    u.usuarioNombre AS username,
                    u.usuarioEmail AS useremail,
                    u.usuarioStatus AS userest,
                    CASE WHEN ru.rolId = 1 THEN 'ADM' ELSE 'PBL' END AS usertipo
                FROM usuarios u
                LEFT JOIN roles_usuarios ru ON ru.usuarioId = u.usuarioId AND ru.ruStatus = 'ACT'
                WHERE u.usuarioId = :usercod";
        $params = ["usercod" => $usercod];
        return self::obtenerUnRegistro($sql, $params);
    }

    public static function insertUser(
        string $username,
        string $useremail,
        string $userpswd,
        string $userfching,
        string $userpswdest,
        string $userpswdexp,
        string $userest,
        string $useractcod,
        string $userpswdchg,
        string $usertipo
    ): int {

        $sql = "INSERT INTO usuarios 
            (usuarioNombre, usuarioEmail, usuarioPass, usuarioStatus)
            VALUES
            (:username, :useremail, :userpswd, :userest)";

        $params = [
            "username" => $username,
            "useremail" => $useremail,
            "userpswd" => $userpswd,
            "userest" => $userest,
        ];

        self::executeNonQuery($sql, $params);

        return self::getLastInsertId();
    }

    public static function updateUser(
        int $usercod,
        string $username,
        string $useremail,
        string $userpswd,
        string $userfching,
        string $userpswdest,
        string $userpswdexp,
        string $userest,
        string $useractcod,
        string $userpswdchg,
        string $usertipo
    ): int {

        $sql = "UPDATE usuarios SET
        usuarioNombre = :username,
        usuarioEmail = :useremail,
        usuarioStatus = :userest
        WHERE usuarioId = :usercod";

        $params = [
            "usercod" => $usercod,
            "username" => $username,
            "useremail" => $useremail,
            "userest" => $userest,
        ];

        return self::executeNonQuery($sql, $params);
    }

    public static function deleteUser(int $usercod): int
    {
        $sql = "DELETE FROM usuarios WHERE usuarioId = :usercod";
        $params = ["usercod" => $usercod];
        return self::executeNonQuery($sql, $params);
    }

    public static function searchUsers(string $partialName = "", string $status = "", string $usertipo = ""): array
    {
        $sql = "SELECT 
                    u.usuarioId AS usercod,
                    u.usuarioNombre AS username,
                    u.usuarioEmail AS useremail,
                    u.usuarioStatus AS userest,
                    CASE WHEN ru.rolId = 1 THEN 'ADM' ELSE 'PBL' END AS usertipo
                FROM usuarios u
                LEFT JOIN roles_usuarios ru ON ru.usuarioId = u.usuarioId AND ru.ruStatus = 'ACT'
                WHERE 1=1 ";
        $params = [];

        if ($partialName !== "") {
            $sql .= " AND u.usuarioNombre LIKE :partialName";
            $params["partialName"] = "%" . $partialName . "%";
        }

        if (in_array($status, ["ACT", "INA"])) {
            $sql .= " AND u.usuarioStatus = :status";
            $params["status"] = $status;
        }

        if (in_array($usertipo, ["NOR", "ADM", "CON"])) {
            $sql .= " AND (CASE WHEN ru.rolId = 1 THEN 'ADM' ELSE 'PBL' END) = :usertipo";
            $params["usertipo"] = $usertipo;
        }

        $sql .= " ORDER BY u.usuarioNombre ASC";

        return self::obtenerRegistros($sql, $params);
    }
}