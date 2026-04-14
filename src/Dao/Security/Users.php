<?php

namespace Dao\Security;

use Dao\Table;

class Users extends Table
{
    public static function getAllUsers(): array
    {
        $sql = "SELECT 
                    u.usercod,
                    u.username,
                    u.useremail,
                    u.userest,
                    CASE WHEN ru.rolId = 1 THEN 'ADM' ELSE u.usertipo END AS usertipo
                FROM usuario u
                LEFT JOIN roles_usuarios ru ON ru.usuarioId = u.usercod AND ru.ruStatus = 'ACT'
                ORDER BY u.username ASC";
        return self::obtenerRegistros($sql, []);
    }

    public static function getUserById(int $usercod): array|false
    {
        $sql = "SELECT 
                    u.usercod,
                    u.username,
                    u.useremail,
                    u.userest,
                    CASE WHEN ru.rolId = 1 THEN 'ADM' ELSE u.usertipo END AS usertipo
                FROM usuario u
                LEFT JOIN roles_usuarios ru ON ru.usuarioId = u.usercod AND ru.ruStatus = 'ACT'
                WHERE u.usercod = :usercod";
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

        $sql = "INSERT INTO usuario 
            (username, useremail, userpswd, userfching, userpswdest, userpswdexp, userest, useractcod, userpswdchg, usertipo)
            VALUES
            (:username, :useremail, :userpswd, :userfching, :userpswdest, :userpswdexp, :userest, :useractcod, :userpswdchg, :usertipo)";

        $params = [
            "username" => $username,
            "useremail" => $useremail,
            "userpswd" => $userpswd,
            "userfching" => $userfching,
            "userpswdest" => $userpswdest,
            "userpswdexp" => $userpswdexp,
            "userest" => $userest,
            "useractcod" => $useractcod,
            "userpswdchg" => $userpswdchg,
            "usertipo" => $usertipo,
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

        $sql = "UPDATE usuario SET
        username = :username,
        useremail = :useremail,
        userest = :userest,
        usertipo = :usertipo
        WHERE usercod = :usercod";

        $params = [
            "usercod" => $usercod,
            "username" => $username,
            "useremail" => $useremail,
            "userest" => $userest,
            "usertipo" => $usertipo,
        ];

        return self::executeNonQuery($sql, $params);
    }

    public static function deleteUser(int $usercod): int
    {
        $sql = "DELETE FROM usuario WHERE usercod = :usercod";
        $params = ["usercod" => $usercod];
        return self::executeNonQuery($sql, $params);
    }

    public static function searchUsers(string $partialName = "", string $status = "", string $usertipo = ""): array
    {
        $sql = "SELECT 
                    u.usercod,
                    u.username,
                    u.useremail,
                    u.userest,
                    CASE WHEN ru.rolId = 1 THEN 'ADM' ELSE u.usertipo END AS usertipo
                FROM usuario u
                LEFT JOIN roles_usuarios ru ON ru.usuarioId = u.usercod AND ru.ruStatus = 'ACT'
                WHERE 1=1 ";
        $params = [];

        if ($partialName !== "") {
            $sql .= " AND u.username LIKE :partialName";
            $params["partialName"] = "%" . $partialName . "%";
        }

        if (in_array($status, ["ACT", "INA"])) {
            $sql .= " AND u.userest = :status";
            $params["status"] = $status;
        }

        if (in_array($usertipo, ["NOR", "ADM", "CON"])) {
            $sql .= " AND (CASE WHEN ru.rolId = 1 THEN 'ADM' ELSE u.usertipo END) = :usertipo";
            $params["usertipo"] = $usertipo;
        }

        $sql .= " ORDER BY u.username ASC";

        return self::obtenerRegistros($sql, $params);
    }
}