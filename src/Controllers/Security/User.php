<?php

namespace Controllers\Security;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Security\Users as DaoUsers;
use Dao\Security\Security as DaoSecurity;
use Utilities\Site;
use Utilities\Validators;

// CRUD de usuario del sistema
class User extends PrivateController
{
    private $viewData = [];
    private $mode = "DSP";

    private $modeDescriptions = [
        "DSP" => "Detalle del Usuario %s",
        "INS" => "Nuevo Usuario",
        "UPD" => "Editar Usuario %s",
        "DEL" => "Eliminar Usuario %s"
    ];

    private $readonly = "";
    private $showCommitBtn = true;

    private $user = [
        "usercod" => 0,
        "username" => "",
        "useremail" => "",
        "userpswd" => "",
        "userfching" => "",
        "userpswdest" => "ACT",
        "userpswdexp" => "",
        "userest" => "ACT",
        "useractcod" => "",
        "userpswdchg" => "",
        "usertipo" => "NOR"
    ];

    // =============================
    // RUN
    // =============================
    public function run(): void
    {
        // Orquesta carga, validaciaIn y guardado del formulario de usuario
        try {
            $this->getData();
            if ($this->isPostBack() && $this->validateData()) {
                $this->handlePost();
            }
            $this->setViewData();
            Renderer::render("security/user", $this->viewData);
        } catch (\Exception $ex) {
            Site::redirectToWithMsg(
                "index.php?page=Security_Users",
                $ex->getMessage()
            );
        }
    }

    // =============================
    // GETDATA
    // =============================
    private function getData()
    {
        // Carga datos iniciales segaUn modo y usuario objetivo
        $this->mode = $_GET["mode"] ?? "NOF";

        if (!isset($this->modeDescriptions[$this->mode])) {
            throw new \Exception("Modo inválido");
        }

        $this->readonly = ($this->mode === "DEL" || $this->mode === "DSP") ? "readonly" : "";
        $this->showCommitBtn = $this->mode !== "DSP";

        if ($this->mode === "INS") {
            $this->user = [
                "usercod" => 0,
                "username" => "",
                "useremail" => "",
                "userpswd" => "",
                "userfching" => "",
                "userpswdest" => "ACT",
                "userpswdexp" => "",
                "userest" => "ACT",
                "useractcod" => "",
                "userpswdchg" => "",
                "usertipo" => "NOR"
            ];
        } else {
            $userData = DaoUsers::getUserById(intval($_GET["id"]));
            if (!$userData) {
                throw new \Exception("Usuario no encontrado");
            }
            $this->user = array_merge($this->user, $userData);
        }
    }

    /**
     * Devuelve true si el usuario logueado esta editando su propio perfil
     * Usa $_SESSION["login"]["userId"] guardado por Utilities\Security::login()
     */
    // =============================
    // ISEDITINGSELF
    // =============================
    private function isEditingSelf(): bool
    {
        $loggedUserId = \Utilities\Security::getUserId();
        return intval($this->user["usercod"]) === intval($loggedUserId);
    }

    // =============================
    // VALIDATEDATA
    // =============================
    private function validateData(): bool
    {
        // Valida entradas y aplica restricciones de autoediciaIn
        $errors = [];

        $this->user["usercod"] = intval($_POST["usercod"] ?? 0);
        $this->user["username"] = trim($_POST["username"] ?? "");
        $this->user["useractcod"] = "admin";
        $this->user["userfching"] = date("Y-m-d H:i:s");
        $this->user["userpswdexp"] = date("Y-m-d H:i:s", strtotime("+90 days"));

        // Email: solo editable en INS, en UPD/DEL se conserva el de la BD
        if ($this->mode === "INS") {
            $this->user["useremail"] = trim($_POST["useremail"] ?? "");
        }

        // Estado y Tipo: si se edita a si mismo, conservar los de la BD
        if ($this->isEditingSelf() && $this->mode === "UPD") {
            $currentData = DaoUsers::getUserById($this->user["usercod"]);
            $this->user["userest"] = $currentData["userest"];
            $this->user["usertipo"] = $currentData["usertipo"];
        } else {
            $this->user["userest"] = $_POST["userest"] ?? "ACT";
            $this->user["usertipo"] = $_POST["usertipo"] ?? "NOR";
        }

        if ($this->mode === "INS") {
            $this->user["userpswd"] = trim($_POST["userpswd"] ?? "");
        }

        if (Validators::IsEmpty($this->user["username"]))
            $errors["username_error"] = "Nombre requerido";
        if ($this->mode === "INS" && Validators::IsEmpty($this->user["useremail"]))
            $errors["useremail_error"] = "Email requerido";
        if ($this->mode === "INS" && Validators::IsEmpty($this->user["userpswd"]))
            $errors["userpswd_error"] = "Password requerido";
        if (!in_array($this->user["userest"], ["ACT", "INA"]))
            $errors["userest_error"] = "Estado inválido";
        if (!in_array($this->user["usertipo"], ["NOR", "ADM", "CON"]))
            $errors["usertipo_error"] = "Tipo inválido";

        if (count($errors) > 0) {
            foreach ($errors as $k => $v) {
                $this->user[$k] = $v;
            }
            return false;
        }
        return true;
    }

    // =============================
    // HANDLEPOST
    // =============================
    private function handlePost()
    {
        // Ejecuta accion INS/UPD/DEL segaUn modo
        switch ($this->mode) {
            case "INS":
                $hashedPswd = \Dao\Security\Security::hashPasswordPublic($this->user["userpswd"]);
                $newId = DaoUsers::insertUser(
                    $this->user["username"],
                    $this->user["useremail"],
                    $hashedPswd,
                    $this->user["userfching"],
                    $this->user["userpswdest"],
                    $this->user["userpswdexp"],
                    $this->user["userest"],
                    $this->user["useractcod"],
                    $hashedPswd,
                    $this->user["usertipo"]
                );
                $this->assignRole(intval($newId), $this->user["usertipo"]);
                Site::redirectToWithMsg("index.php?page=Security_Users", "Usuario creado correctamente");
                break;

            case "UPD":
                DaoUsers::updateUser(
                    $this->user["usercod"],
                    $this->user["username"],
                    $this->user["useremail"],
                    "",
                    $this->user["userfching"],
                    $this->user["userpswdest"] ?? "ACT",
                    $this->user["userpswdexp"],
                    $this->user["userest"],
                    $this->user["useractcod"],
                    "",
                    $this->user["usertipo"]
                );
                if (!$this->isEditingSelf()) {
                    $this->assignRole($this->user["usercod"], $this->user["usertipo"]);
                }
                Site::redirectToWithMsg("index.php?page=Security_Users", "Usuario actualizado correctamente");
                break;

            case "DEL":
                \Dao\Security\Security::removeAllRolesFromUser($this->user["usercod"]);
                DaoUsers::deleteUser($this->user["usercod"]);
                Site::redirectToWithMsg("index.php?page=Security_Users", "Usuario eliminado correctamente");
                break;
        }
    }

    /**
     * Elimina todos los roles del usuario y asigna el que corresponde
     * segaUn el tipo (usertipo) seleccionado en el formulario
     *



     * Mapa de tipos -> IDs de rol en la tabla roles:
     *   ADM => 1  (Administrador)
     *   NOR => 2  (Normal)
     *   CON => 3  (Consulta)
     */
    // =============================
    // ASSIGNROLE
    // =============================
    private function assignRole(int $usercod, string $usertipo): void
    {
        \Dao\Security\Security::removeAllRolesFromUser($usercod);

        $rolMap = [
            "ADM" => 1,
            "NOR" => 2,
            "CON" => 3
        ];

        if (isset($rolMap[$usertipo])) {
            \Dao\Security\Security::assignRolToUser($usercod, $rolMap[$usertipo]);
        }
    }

    /**
     * Genera el HTML completo de un <input> como string listo para la vista
     * Asi evitamos poner atributos dinamicos dentro del HTML del template,
     * que el renderer no soporta correctamente
     */
    // =============================
    // BUILDINPUT
    // =============================
    private function buildInput(
        string $type,
        string $name,
        string $value,
        bool $readonly = false,
        string $autocomplete = "off"
    ): string {
        $ro = $readonly ? ' readonly' : '';
        $val = htmlspecialchars($value, ENT_QUOTES);
        return '<input type="' . $type . '" name="' . $name . '" value="' . $val . '"'
            . $ro . ' autocomplete="' . $autocomplete . '"'
            . ' class="form-input">';
    }

    /**
     * Genera el HTML completo de un <select> como string listo para la vista
     */
    // =============================
    // BUILDSELECT
    // =============================
    private function buildSelect(
        string $name,
        array $options,
        string $selected,
        bool $disabled = false
    ): string {
        $dis = $disabled ? ' disabled' : '';
        $html = '<select name="' . $name . '" class="form-select"' . $dis . '>';
        foreach ($options as $val => $label) {
            $sel = ($selected === $val) ? ' selected' : '';
            $html .= '<option value="' . $val . '"' . $sel . '>'
                . htmlspecialchars($label) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    // =============================
    // SETVIEWDATA
    // =============================
    private function setViewData(): void
    {
        // Arma componentes dinamicos para el template
        $this->viewData["FormTitle"] = sprintf(
            $this->modeDescriptions[$this->mode],
            $this->user["username"] ?? ""
        );
        $this->viewData["mode"] = $this->mode;

        $isSelf = $this->isEditingSelf() && $this->mode === "UPD";
        $isReadonly = ($this->mode === "DEL" || $this->mode === "DSP");
        $selectsLocked = ($this->mode === "DEL" || $this->mode === "DSP" || $isSelf);

        //  Campo Nombre 
        $this->viewData["fieldNombre"] = $this->buildInput(
            "text",
            "username",
            $this->user["username"] ?? "",
            $isReadonly
        );
        $this->viewData["errorNombre"] = $this->user["username_error"] ?? "";

        //  Campo Email 
        // Solo editable en INS; readonly en UPD, DEL y DSP
        $emailReadonly = ($this->mode !== "INS");
        $this->viewData["fieldEmail"] = $this->buildInput(
            "email",
            "useremail",
            $this->user["useremail"] ?? "",
            $emailReadonly
        );
        $this->viewData["errorEmail"] = $this->user["useremail_error"] ?? "";

        //  Campo Password (solo en INS) 
        $this->viewData["is_insert"] = $this->mode === "INS";
        $this->viewData["fieldPswd"] = $this->buildInput(
            "password",
            "userpswd",
            "",
            false,
            "new-password"
        );
        $this->viewData["errorPswd"] = $this->user["userpswd_error"] ?? "";

        //Select Estado 
        $this->viewData["fieldEstado"] = $this->buildSelect(
            "userest",
            ["ACT" => "Activo", "INA" => "Inactivo"],
            $this->user["userest"] ?? "ACT",
            $selectsLocked
        );
        $this->viewData["errorEstado"] = $this->user["userest_error"] ?? "";
        $this->viewData["warningEstado"] = $isSelf
            ? '<div class="self-note">&#9888; No puedes cambiar tu propio estado</div>'
            : "";

        // Select Tipo 
        $this->viewData["fieldTipo"] = $this->buildSelect(
            "usertipo",
            ["NOR" => "Normal", "ADM" => "Administrador", "CON" => "Consultor"],
            $this->user["usertipo"] ?? "NOR",
            $selectsLocked
        );
        $this->viewData["errorTipo"] = $this->user["usertipo_error"] ?? "";
        $this->viewData["warningTipo"] = $isSelf
            ? '<div class="self-note">&#9888; No puedes cambiar tu propio tipo</div>'
            : "";

        // Boton de accion 
        // DSP  -> sin boton
        // INS/UPD -> boton dorado "Guardar"
        // DEL  -> boton rojo "Eliminar"
        if ($this->mode === "DSP") {
            $this->viewData["commitBtn"] = "";
        } elseif ($this->mode === "DEL") {
            $this->viewData["commitBtn"] =
                '<button type="submit" class="btn-eliminar-confirm">Eliminar</button>';
        } else {
            $this->viewData["commitBtn"] =
                '<button type="submit" class="btn-confirmar">Guardar</button>';
        }

        // usercod para la action del form
        $this->viewData["u_usercod"] = $this->user["usercod"] ?? 0;
    }
}