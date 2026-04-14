<?php

namespace Controllers\Security;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Security\Roles as DaoRoles;
use Utilities\Site;
use Utilities\Validators;

// CRUD de rol de seguridad
class Rol extends PrivateController
{
    private $viewData = [];
    private $mode = "DSP";

    private $modeDescriptions = [
        "DSP" => "Detalle del Rol %s",
        "INS" => "Nuevo Rol",
        "UPD" => "Editar Rol %s",
        "DEL" => "Eliminar Rol %s"
    ];

    private $readonly = "";
    private $showCommitBtn = true;

    private $rol = [
        "rolescod" => "",
        "rolesdsc" => "",
        "rolesest" => "ACT"
    ];

    // =============================
    // RUN
    // =============================
    public function run(): void
    {
        // Controla ciclo CRUD de un rol
        try {
            $this->getData();
            if ($this->isPostBack() && $this->validateData()) {
                $this->handlePost();
            }
            $this->setViewData();
            Renderer::render("security/rol", $this->viewData);
        } catch (\Exception $ex) {
            Site::redirectToWithMsg(
                "index.php?page=Security_Roles",
                $ex->getMessage()
            );
        }
    }

    // =============================
    // GETDATA
    // =============================
    private function getData(): void
    {
        // Carga datos de rol segaUn modo de operacion
        $this->mode = $_GET["mode"] ?? "NOF";

        if (!isset($this->modeDescriptions[$this->mode])) {
            throw new \Exception("Modo inválido");
        }

        $this->readonly = ($this->mode === "DEL" || $this->mode === "DSP") ? "readonly" : "";
        $this->showCommitBtn = $this->mode !== "DSP";

        if ($this->mode !== "INS") {
            $rolData = DaoRoles::getRoleById($_GET["id"]);
            if (!$rolData) {
                throw new \Exception("Rol no encontrado");
            }
            $this->rol = array_merge($this->rol, $rolData);
        }
    }

    // =============================
    // VALIDATEDATA
    // =============================
    private function validateData(): bool
    {
        // Valida campos requeridos del rol
        $errors = [];

        $this->rol["rolescod"] = trim($_POST["rolescod"] ?? "");
        $this->rol["rolesdsc"] = trim($_POST["rolesdsc"] ?? "");
        $this->rol["rolesest"] = $_POST["rolesest"] ?? "ACT";

        if (Validators::IsEmpty($this->rol["rolescod"])) {
            $errors["rolescod_error"] = "Código de rol requerido";
        }
        if (Validators::IsEmpty($this->rol["rolesdsc"])) {
            $errors["rolesdsc_error"] = "Descripción requerida";
        }
        if (!in_array($this->rol["rolesest"], ["ACT", "INA"])) {
            $errors["rolesest_error"] = "Estado inválido";
        }

        if (count($errors) > 0) {
            foreach ($errors as $k => $v) {
                $this->rol[$k] = $v;
            }
            return false;
        }
        return true;
    }

    // =============================
    // HANDLEPOST
    // =============================
    private function handlePost(): void
    {
        // Ejecuta INS/UPD/DEL segaUn modo seleccionado
        switch ($this->mode) {
            case "INS":
                DaoRoles::insertRole(
                    $this->rol["rolescod"],
                    $this->rol["rolesdsc"],
                    $this->rol["rolesest"]
                );
                Site::redirectToWithMsg("index.php?page=Security_Roles", "Rol creado correctamente");
                break;

            case "UPD":
                DaoRoles::updateRole(
                    $this->rol["rolescod"],
                    $this->rol["rolesdsc"],
                    $this->rol["rolesest"]
                );
                Site::redirectToWithMsg("index.php?page=Security_Roles", "Rol actualizado correctamente");
                break;

            case "DEL":
                DaoRoles::deleteRole($this->rol["rolescod"]);
                Site::redirectToWithMsg("index.php?page=Security_Roles", "Rol eliminado correctamente");
                break;
        }
    }

    // =============================
    // SETVIEWDATA
    // =============================
    private function setViewData(): void
    {
        $this->viewData["FormTitle"] = sprintf($this->modeDescriptions[$this->mode], $this->rol["rolescod"]);
        $this->viewData["readonly"] = $this->readonly;
        $this->viewData["showCommitBtn"] = $this->showCommitBtn;

        // Prepara variables para formulario de rol
        // Variables para el select de estado
        $estadoKey = "rolesest_" . strtolower($this->rol["rolesest"]);
        $this->rol[$estadoKey] = "selected";

        // Fusionar todas las variables del rol en el primer nivel de viewData
        $this->viewData = array_merge($this->viewData, $this->rol);
    }
}
