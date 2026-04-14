<?php

namespace Utilities;

use Dao\Security\Security as DaoSecurity;

class Security
{
    // =============================
    // __CONSTRUCT
    // =============================
    private function __construct()
    {
        // Evita instanciacion directa de la utilidad
    }

    // =============================
    // __CLONE
    // =============================
    private function __clone()
    {
        // Evita clonacion para mantener uso estatico
    }

    // =============================
    // LOGOUT
    // =============================
    public static function logout()
    {
        // Cierra sesion local y token activo en base de datos
        if (isset($_SESSION['login']['userId'])) {
            DaoSecurity::clearActiveSessionToken(intval($_SESSION['login']['userId']));
        }

        unset($_SESSION['login']);
        unset($_SESSION['userName']);
        unset($_SESSION['userEmail']);
        unset($_SESSION['sessionToken']);
    }

    // =============================
    // LOGIN
    // =============================
    public static function login($userId, $userName, $userEmail)
    {
        // Inicia sesion y registra token unico para sesion simultanea
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }

        $sessionToken = bin2hex(random_bytes(32));
        DaoSecurity::setActiveSessionToken($userId, $sessionToken);

        $_SESSION['login'] = [
            'isLogged' => true,
            'userId' => $userId,
            'userName' => $userName,
            'userEmail' => $userEmail,
        ];
        $_SESSION['userName'] = $userName;
        $_SESSION['userEmail'] = $userEmail;
        $_SESSION['sessionToken'] = $sessionToken;
    }

    // =============================
    // ISLOGGED
    // =============================
    public static function isLogged(): bool
    {
        // Valida sesion local y su token vigente contra base de datos
        if (!(isset($_SESSION['login']) && $_SESSION['login']['isLogged'])) {
            return false;
        }

        $userId = intval($_SESSION['login']['userId'] ?? 0);
        $sessionToken = strval($_SESSION['sessionToken'] ?? '');
        if ($userId <= 0 || $sessionToken === '') {
            self::logout();

            return false;
        }

        $activeToken = strval(DaoSecurity::getActiveSessionToken($userId));
        if ($activeToken === '' || !hash_equals($activeToken, $sessionToken)) {
            self::logout();

            return false;
        }

        return true;
    }

    // =============================
    // GETUSER
    // =============================
    public static function getUser()
    {
        // Retorna la estructura completa del usuario en sesion
        if (isset($_SESSION['login'])) {
            return $_SESSION['login'];
        }

        return false;
    }

    // =============================
    // GETUSERID
    // =============================
    public static function getUserId()
    {
        // Retorna solo el ID del usuario autenticado
        if (isset($_SESSION['login'])) {
            return $_SESSION['login']['userId'];
        }

        return 0;
    }

    // =============================
    // ISAUTHORIZED
    // =============================
    public static function isAuthorized($userId, $function, $type = 'FNC'): bool
    {
        // Verifica si el usuario tiene permiso para funcion/controlador
        if (Context::getContextByKey('DEVELOPMENT') == '1') {
            $functionInDb = DaoSecurity::getFeature($function);
            if (!$functionInDb) {
                DaoSecurity::addNewFeature($function, $function, 'ACT', $type);
            }
        }

        return DaoSecurity::getFeatureByUsuario($userId, $function);
    }

    // =============================
    // ISINROL
    // =============================
    public static function isInRol($userId, $rol): bool
    {
        // Verifica membresia del usuario en un rol concreto
        if (Context::getContextByKey('DEVELOPMENT') == '1') {
            $rolInDb = DaoSecurity::getRol($rol);
            if (!$rolInDb) {
                DaoSecurity::addNewRol($rol, $rol, 'ACT');
            }
        }

        return DaoSecurity::isUsuarioInRol($userId, $rol);
    }
}
