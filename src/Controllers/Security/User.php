<?php

namespace Controllers\Security;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Security\Users as DaoUsers;
use Utilities\Site;
use Utilities\Validators;

class User extends PublicController
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

    public function run(): void
    {
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

    private function getData()
    {
        $this->mode = $_GET["mode"] ?? "NOF";

        if (!isset($this->modeDescriptions[$this->mode])) {
            throw new \Exception("Modo inválido");
        }

        $this->readonly = ($this->mode === "DEL" || $this->mode === "DSP") ? "readonly" : "";
        $this->showCommitBtn = $this->mode !== "DSP";

        if ($this->mode !== "INS") {
            $userData = DaoUsers::getUserById(intval($_GET["id"]));
            if (!$userData) {
                throw new \Exception("Usuario no encontrado");
            }
            // Fusionar con valores por defecto
            $this->user = array_merge($this->user, $userData);
        }
    }

    private function validateData(): bool
    {
        $errors = [];

        $this->user["usercod"] = intval($_POST["usercod"] ?? 0);
        $this->user["username"] = trim($_POST["username"] ?? "");
        $this->user["useremail"] = trim($_POST["useremail"] ?? "");
        $this->user["userpswd"] = trim($_POST["userpswd"] ?? "");
        $this->user["userest"] = $_POST["userest"] ?? "ACT";
        $this->user["usertipo"] = $_POST["usertipo"] ?? "NOR";
        $this->user["useractcod"] = $_SESSION['usuario'] ?? "admin";
        $this->user["userfching"] = date("Y-m-d H:i:s");

        $this->user["userpswdexp"] = $_POST["userpswdexp"] ?? date("Y-m-d H:i:s", strtotime("+90 days"));
        $this->user["userpswdchg"] = $_POST["userpswdchg"] ?? $this->user["userpswd"];

        if (Validators::IsEmpty($this->user["username"])) $errors["username_error"] = "Nombre requerido";
        if (Validators::IsEmpty($this->user["useremail"])) $errors["useremail_error"] = "Email requerido";
        if ($this->mode === "INS" && Validators::IsEmpty($this->user["userpswd"])) $errors["userpswd_error"] = "Password requerido";
        if (!in_array($this->user["userest"], ["ACT", "INA"])) $errors["userest_error"] = "Estado inválido";
        if (!in_array($this->user["usertipo"], ["NOR", "ADM", "CON"])) $errors["usertipo_error"] = "Tipo inválido";

        if (count($errors) > 0) {
            foreach ($errors as $k => $v) {
                $this->user[$k] = $v;
            }
            return false;
        }
        return true;
    }

    private function handlePost()
    {
        switch ($this->mode) {
            case "INS":
                DaoUsers::insertUser(
                    $this->user["username"],
                    $this->user["useremail"],
                    $this->user["userpswd"],
                    $this->user["userfching"],
                    $this->user["userpswdest"],
                    $this->user["userpswdexp"],
                    $this->user["userest"],
                    $this->user["useractcod"],
                    $this->user["userpswdchg"],
                    $this->user["usertipo"]
                );
                Site::redirectToWithMsg("index.php?page=Security_Users", "Usuario creado correctamente");
                break;

            case "UPD":
                DaoUsers::updateUser(
                    $this->user["usercod"],
                    $this->user["username"],
                    $this->user["useremail"],
                    $this->user["userpswd"],
                    $this->user["userfching"],
                    $this->user["userpswdest"],
                    $this->user["userpswdexp"],
                    $this->user["userest"],
                    $this->user["useractcod"],
                    $this->user["userpswdchg"],
                    $this->user["usertipo"]
                );
                Site::redirectToWithMsg("index.php?page=Security_Users", "Usuario actualizado correctamente");
                break;

            case "DEL":
                DaoUsers::deleteUser($this->user["usercod"]);
                Site::redirectToWithMsg("index.php?page=Security_Users", "Usuario eliminado correctamente");
                break;
        }
    }

    private function setViewData(): void
    {
        // Título según modo
        $this->viewData["FormTitle"] = sprintf($this->modeDescriptions[$this->mode], $this->user["username"]);

        // Atributos del formulario
        $this->viewData["readonly"] = $this->readonly;
        $this->viewData["showCommitBtn"] = $this->showCommitBtn;

        // Variables para los selects (selected)
        $statusKey = "userest_" . strtolower($this->user["userest"]);
        $this->user[$statusKey] = "selected";

        if ($this->user["usertipo"] === "NOR") {
            $this->user["usertipo_nor"] = "selected";
        } elseif ($this->user["usertipo"] === "ADM") {
            $this->user["usertipo_adm"] = "selected";
        } elseif ($this->user["usertipo"] === "CON") {
            $this->user["usertipo_con"] = "selected";
        }
        $tipoKey = "usertipo_" . strtolower($this->user["usertipo"]);
        $this->user[$tipoKey] = "selected";

        // Fusionar todas las variables de usuario en el primer nivel de viewData
        // para que en el template se usen directamente como {{username}}, {{usercod}}, etc.
        $this->viewData = array_merge($this->viewData, $this->user);
    }
}
