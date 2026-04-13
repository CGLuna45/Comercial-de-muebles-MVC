<?php
namespace Dao\Security;

if (version_compare(phpversion(), '7.4.0', '<')) {
    define('PASSWORD_ALGORITHM', 1);  //BCRYPT
} else {
    define('PASSWORD_ALGORITHM', '2y');  //BCRYPT
}
/*
usercod     bigint(10) AI PK
useremail   varchar(80)
username    varchar(80)
userpswd    varchar(128)
userfching  datetime
userpswdest char(3)
userpswdexp datetime
userest     char(3)
useractcod  varchar(128)
userpswdchg varchar(128)
usertipo    char(3)
*/

use Exception;

class Security extends \Dao\Table
{
    static public function getUsuarios($filter = "", $page = -1, $items = 0)
    {
        $sqlstr = "";
        if ($filter == "" && $page == -1 && $items == 0) {
            $sqlstr = "SELECT * FROM usuario;";
        } else {
            //TODO: Terminar consultas FACET
            if ($page = -1 and $items = 0) {
                $sqlstr = sprintf("SELECT * FROM usuarios %s;", $filter);
            } else {
                $offset = ($page - 1 * $items);
                $sqlstr = sprintf(
                    "SELECT * FROM usuarios %s limit %d, %d;",
                    $filter,
                    $offset,
                    $items
                );
            }
        }
        return self::obtenerRegistros($sqlstr, array());
    }

    static public function newUsuario($email, $password)
    {
        if (!\Utilities\Validators::IsValidEmail($email)) {
            throw new Exception("Correo no es válido");
        }
        if (!\Utilities\Validators::IsValidPassword($password)) {
            throw new Exception("Contraseña debe ser almenos 8 caracteres, 1 número, 1 mayúscula, 1 símbolo especial");
        }

        $newUser = self::_usuarioStruct();
        //Tratamiento de la Contraseña
        $hashedPassword = self::_hashPassword($password);

        unset($newUser["usercod"]);
        unset($newUser["userfching"]);
        unset($newUser["userpswdchg"]);

        $newUser["useremail"]    = $email;
        $newUser["username"]     = "John Doe";
        $newUser["userpswd"]     = $hashedPassword;
        $newUser["userpswdest"]  = Estados::ACTIVO;
        $newUser["userpswdexp"]  = date('Y-m-d', time() + 7776000);  //(3*30*24*60*60) (m d h mi s)
        $newUser["userest"]      = Estados::ACTIVO;
        $newUser["useractcod"]   = hash("sha256", $email . time());
        $newUser["usertipo"]     = UsuarioTipo::PUBLICO;

        $sqlIns = "INSERT INTO `usuario` (`useremail`, `username`, `userpswd`,
            `userfching`, `userpswdest`, `userpswdexp`, `userest`, `useractcod`,
            `userpswdchg`, `usertipo`)
            VALUES
            ( :useremail, :username, :userpswd,
            now(), :userpswdest, :userpswdexp, :userest, :useractcod,
            now(), :usertipo);";

        return self::executeNonQuery($sqlIns, $newUser);
    }

    static public function getUsuarioByEmail($email)
    {
        $sqlstr = "SELECT * from `usuario` where `useremail` = :useremail ;";
        $params = array("useremail" => $email);

        return self::obtenerUnRegistro($sqlstr, $params);
    }

    static private function _saltPassword($password)
    {
        return hash_hmac(
            "sha256",
            $password,
            \Utilities\Context::getContextByKey("PWD_HASH")
        );
    }

    static private function _hashPassword($password)
    {
        return password_hash(self::_saltPassword($password), PASSWORD_ALGORITHM);
    }

    static public function hashPasswordPublic($password)
    {
        return self::_hashPassword($password);
    }

    static public function verifyPassword($raw_password, $hash_password)
    {
        return password_verify(
            self::_saltPassword($raw_password),
            $hash_password
        );
    }

    static private function _usuarioStruct()
    {
        return array(
            "usercod"     => "",
            "useremail"   => "",
            "username"    => "",
            "userpswd"    => "",
            "userfching"  => "",
            "userpswdest" => "",
            "userpswdexp" => "",
            "userest"     => "",
            "useractcod"  => "",
            "userpswdchg" => "",
            "usertipo"    => "",
        );
    }

    static public function getFeature($fncod)
    {
        $sqlstr = "SELECT * from funciones where funcionNombre=:funcionNombre;";
        $featuresList = self::obtenerRegistros($sqlstr, array("funcionNombre" => $fncod));
        return count($featuresList) > 0;
    }

    static public function addNewFeature($fncod, $fndsc, $fnest, $fntyp)
    {
        $sqlins = "INSERT INTO `funciones` (`funcionNombre`, `funcionDescripcion`, `funcionStatus`)
        VALUES (:funcionNombre, :funcionDescripcion, :funcionStatus);";

        return self::executeNonQuery(
            $sqlins,
            array(
                "funcionNombre" => $fncod,
                "funcionDescripcion" => $fndsc,
                "funcionStatus" => $fnest
            )
        );
    }

    static public function getFeatureByUsuario($userCod, $fncod)
    {
        $sqlstr = "SELECT f.funcionId
            FROM funciones f
            INNER JOIN funciones_roles fr ON fr.funcionId = f.funcionId
            INNER JOIN roles_usuarios ru ON ru.rolId = fr.rolId
            WHERE f.funcionNombre = :funcionNombre
              AND f.funcionStatus = 'ACT'
              AND fr.frStatus = 'ACT'
              AND ru.ruStatus = 'ACT'
              AND ru.usuarioId = :usuarioId
            LIMIT 1;";
        $resultados = self::obtenerRegistros(
            $sqlstr,
            array(
                "usuarioId" => $userCod,
                "funcionNombre" => $fncod
            )
        );
        return count($resultados) > 0;
    }

    static public function getRol($rolescod)
    {
        $params = array();
        if (is_numeric($rolescod)) {
            $sqlstr = "SELECT * from roles where rolId=:rolId;";
            $params = array("rolId" => $rolescod);
        } else {
            $sqlstr = "SELECT * from roles where rolNombre=:rolNombre;";
            $params = array("rolNombre" => $rolescod);
        }
        $rolesList = self::obtenerRegistros($sqlstr, $params);
        return count($rolesList) > 0;
    }

    static public function addNewRol($rolescod, $rolesdsc, $rolesest)
    {
        if (is_numeric($rolescod)) {
            $sqlins = "INSERT INTO `roles` (`rolId`, `rolNombre`, `rolDescripcion`, `rolStatus`)
            VALUES (:rolId, :rolNombre, :rolDescripcion, :rolStatus);";
            return self::executeNonQuery(
                $sqlins,
                array(
                    "rolId" => $rolescod,
                    "rolNombre" => "ROL_" . $rolescod,
                    "rolDescripcion" => $rolesdsc,
                    "rolStatus" => $rolesest
                )
            );
        }

        $sqlins = "INSERT INTO `roles` (`rolNombre`, `rolDescripcion`, `rolStatus`)
        VALUES (:rolNombre, :rolDescripcion, :rolStatus);";

        return self::executeNonQuery(
            $sqlins,
            array(
                "rolNombre" => $rolescod,
                "rolDescripcion" => $rolesdsc,
                "rolStatus" => $rolesest
            )
        );
    }

    static public function isUsuarioInRol($userCod, $rolescod)
    {
        $sqlstr = "SELECT r.rolId
            FROM roles r
            INNER JOIN roles_usuarios ru ON ru.rolId = r.rolId
            WHERE r.rolStatus = 'ACT'
              AND ru.ruStatus = 'ACT'
              AND ru.usuarioId = :usuarioId
              AND r.rolId = :rolId
            LIMIT 1;";
        $resultados = self::obtenerRegistros(
            $sqlstr,
            array(
                "usuarioId" => $userCod,
                "rolId" => $rolescod
            )
        );
        return count($resultados) > 0;
    }

    static public function getRolesByUsuario($userCod)
    {
        $sqlstr = "SELECT r.*
            FROM roles r
            INNER JOIN roles_usuarios ru ON ru.rolId = r.rolId
            WHERE r.rolStatus = 'ACT'
              AND ru.ruStatus = 'ACT'
              AND ru.usuarioId = :usuarioId;";
        $resultados = self::obtenerRegistros(
            $sqlstr,
            array(
                "usuarioId" => $userCod
            )
        );
        return $resultados;
    }

    static public function removeRolFromUser($userCod, $rolescod)
    {
        $sqldel = "UPDATE roles_usuarios set ruStatus='INA'
        where rolId=:rolId and usuarioId=:usuarioId;";
        return self::executeNonQuery(
            $sqldel,
            array("rolId" => $rolescod, "usuarioId" => $userCod)
        );
    }

    static public function removeFeatureFromRol($fncod, $rolescod)
    {
        $sqldel = "UPDATE funciones_roles fr
            INNER JOIN funciones f ON f.funcionId = fr.funcionId
            SET fr.frStatus='INA'
            WHERE f.funcionNombre=:funcionNombre AND fr.rolId=:rolId;";
        return self::executeNonQuery(
            $sqldel,
            array("funcionNombre" => $fncod, "rolId" => $rolescod)
        );
    }

    // ─── NUEVOS MÉTODOS ──────────────────────────────────────────────────────────

    /**
     * Desactiva todos los roles activos de un usuario.
     * Se llama antes de asignar el nuevo rol para evitar duplicados activos.
     */
    static public function removeAllRolesFromUser($userCod)
    {
        $sql = "UPDATE roles_usuarios SET ruStatus = 'INA' WHERE usuarioId = :usuarioId";
        return self::executeNonQuery($sql, array("usuarioId" => $userCod));
    }

    /**
     * Asigna un rol a un usuario.
     * Si ya existe el registro lo reactiva; si no, inserta uno nuevo.
     */
    static public function assignRolToUser($userCod, $rolescod)
    {
        $sqlCheck = "SELECT * FROM roles_usuarios WHERE usuarioId = :usuarioId AND rolId = :rolId";
        $exists   = self::obtenerRegistros(
            $sqlCheck,
            array("usuarioId" => $userCod, "rolId" => $rolescod)
        );

        if (count($exists) > 0) {
            // Ya existe: solo reactivar
            $sql = "UPDATE roles_usuarios SET ruStatus = 'ACT', ruFechaInicio = now(), ruFechaFin = '2099-12-31 23:59:59'
                    WHERE usuarioId = :usuarioId AND rolId = :rolId";
            return self::executeNonQuery(
                $sql,
                array("usuarioId" => $userCod, "rolId" => $rolescod)
            );
        } else {
            // No existe: insertar nuevo
            $sql = "INSERT INTO roles_usuarios (usuarioId, rolId, ruStatus, ruFechaInicio, ruFechaFin)
                    VALUES (:usuarioId, :rolId, 'ACT', now(), '2099-12-31 23:59:59')";
            return self::executeNonQuery(
                $sql,
                array("usuarioId" => $userCod, "rolId" => $rolescod)
            );
        }
    }

    // ─────────────────────────────────────────────────────────────────────────────

    static public function getUnAssignedFeatures($rolescod)
    {
        // TODO: implementar
    }

    static public function getUnAssignedRoles($userCod)
    {
        // TODO: implementar
    }

    private function __construct()
    {
    }

    private function __clone()
    {
    }
}