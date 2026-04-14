<?php

namespace Utilities;

use Dao\Security\Security as DaoSecurity;
class Security {
    private function __construct()
    {
        
    }
    private function __clone()
    {
        
    }
    public static function logout()
    {
        if (isset($_SESSION["login"]["userId"])) {
            DaoSecurity::clearActiveSessionToken(intval($_SESSION["login"]["userId"]));
        }

        unset($_SESSION["login"]);
        unset($_SESSION["userName"]);
        unset($_SESSION["userEmail"]);
        unset($_SESSION["sessionToken"]);
    }
    public static function login($userId, $userName, $userEmail)
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $sessionToken = bin2hex(random_bytes(32));
        DaoSecurity::setActiveSessionToken($userId, $sessionToken);

        $_SESSION["login"] = array(
            "isLogged" => true,
            "userId" => $userId,
            "userName" => $userName,
            "userEmail" => $userEmail
        );
        $_SESSION["userName"] = $userName;
        $_SESSION["userEmail"] = $userEmail;
        $_SESSION["sessionToken"] = $sessionToken;
    }
    public static function isLogged():bool
    {
        if (!(isset($_SESSION["login"]) && $_SESSION["login"]["isLogged"])) {
            return false;
        }

        $userId = intval($_SESSION["login"]["userId"] ?? 0);
        $sessionToken = strval($_SESSION["sessionToken"] ?? "");
        if ($userId <= 0 || $sessionToken === "") {
            self::logout();
            return false;
        }

        $activeToken = strval(DaoSecurity::getActiveSessionToken($userId));
        if ($activeToken === "" || !hash_equals($activeToken, $sessionToken)) {
            self::logout();
            return false;
        }

        return true;
    }
    public static function getUser()
    {
        if (isset($_SESSION["login"])) {
            return $_SESSION["login"];
        }
        return false;
    }
    public static function getUserId()
    {
        if (isset($_SESSION["login"])) {
            return $_SESSION["login"]["userId"];
        }
        return 0;
    }
    public static function isAuthorized($userId, $function, $type = 'FNC'):bool
    {
        if (\Utilities\Context::getContextByKey("DEVELOPMENT") == "1") {
            $functionInDb = DaoSecurity::getFeature($function);
            if (!$functionInDb) {
                DaoSecurity::addNewFeature($function, $function, "ACT", $type);
            }
        }
        return DaoSecurity::getFeatureByUsuario($userId, $function);
    }
    public static function isInRol($userId, $rol):bool
    {
        if (\Utilities\Context::getContextByKey("DEVELOPMENT") == "1") {
            $rolInDb = DaoSecurity::getRol($rol);
            if (!$rolInDb) {
                DaoSecurity::addNewRol($rol, $rol, "ACT");
            }
        }
        return DaoSecurity::isUsuarioInRol($userId, $rol);
    }
}
