<?php

namespace Dao\Security;

use Dao\Table;

class Users extends Table
{
    public static function getAllUsers(): array
    {
        $sql = "SELECT * FROM usuario ORDER BY username ASC";
        return self::obtenerRegistros($sql, []);
    }

    public static function getUserById(int $usercod): array|false
    {
        $sql = "SELECT * FROM usuario WHERE usercod = :usercod";
        $params = ["usercod" => $usercod]; // Sin :
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
            "usertipo" => $usertipo
        ];
        return self::executeNonQuery($sql, $params);
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
                userpswd = :userpswd,
                userfching = :userfching,
                userpswdest = :userpswdest,
                userpswdexp = :userpswdexp,
                userest = :userest,
                useractcod = :useractcod,
                userpswdchg = :userpswdchg,
                usertipo = :usertipo
                WHERE usercod = :usercod";
        $params = [
            "usercod" => $usercod,
            "username" => $username,
            "useremail" => $useremail,
            "userpswd" => $userpswd,
            "userfching" => $userfching,
            "userpswdest" => $userpswdest,
            "userpswdexp" => $userpswdexp,
            "userest" => $userest,
            "useractcod" => $useractcod,
            "userpswdchg" => $userpswdchg,
            "usertipo" => $usertipo
        ];
        return self::executeNonQuery($sql, $params);
    }

    public static function deleteUser(int $usercod): int
    {
        $sql = "DELETE FROM usuario WHERE usercod = :usercod";
        $params = ["usercod" => $usercod];
        return self::executeNonQuery($sql, $params);
    }

    public static function searchUsers(string $partialName = "", string $status = ""): array
    {
        $sql = "SELECT * FROM usuario WHERE 1=1 ";
        $params = [];

        if ($partialName !== "") {
            $sql .= " AND username LIKE :partialName";
            $params["partialName"] = "%" . $partialName . "%";
        }

        if (in_array($status, ["ACT", "INA"])) {
            $sql .= " AND userest = :status";
            $params["status"] = $status;
        }

        $sql .= " ORDER BY username ASC";
        return self::obtenerRegistros($sql, $params);
    }
}
