<?php

namespace Controllers\Security;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Security\Roles as DaoRoles;
use Utilities\Site;

class Roles extends PublicController
{
    private $viewData = [];

    public function run(): void
    {
        try {
            // Obtener parámetros de filtro y paginación
            $partialName = $_GET["partialName"] ?? "";
            $status = $_GET["status"] ?? "";
            $orderBy = $this->getOrderBy();
            $orderDescending = $this->getOrderDescending();
            $page = intval($_GET["page"] ?? 0);
            $itemsPerPage = 10; // Puedes hacerlo configurable

            // Obtener datos del DAO
            $result = DaoRoles::getRoles(
                $partialName,
                $status,
                $orderBy,
                $orderDescending,
                $page,
                $itemsPerPage
            );

            // Preparar datos para la vista
            $this->viewData["roles"] = $result["roles"];
            $this->viewData["total"] = $result["total"];
            $this->viewData["page"] = $result["page"];
            $this->viewData["itemsPerPage"] = $result["itemsPerPage"];

            // Variables para mantener los filtros en el formulario
            $this->viewData["partialName"] = $partialName;
            $this->viewData["status"] = $status;
            $this->viewData["status_EMP"] = $status === "" ? "selected" : "";
            $this->viewData["status_ACT"] = $status === "ACT" ? "selected" : "";
            $this->viewData["status_INA"] = $status === "INA" ? "selected" : "";

            // Variables para ordenamiento (similar a productos)
            $this->setOrderVariables();

            // Paginación
            $this->viewData["pagination"] = $this->getPaginationHtml(
                $result["page"],
                ceil($result["total"] / $itemsPerPage),
                "index.php?page=Security_Roles&partialName=" . urlencode($partialName) . "&status=" . urlencode($status)
            );

            Renderer::render("security/roles", $this->viewData);
        } catch (\Exception $ex) {
            Site::redirectToWithMsg(
                "index.php?page=Security_Roles",
                "Error: " . $ex->getMessage()
            );
        }
    }

    private function getOrderBy(): string
    {
        $allowed = ["rolescod", "rolesdsc", "rolesest"];
        $orderBy = $_GET["orderBy"] ?? "";
        return in_array($orderBy, $allowed) ? $orderBy : "";
    }

    private function getOrderDescending(): bool
    {
        return isset($_GET["orderDescending"]) && $_GET["orderDescending"] === "1";
    }

    private function setOrderVariables(): void
    {
        // Para cada columna, definimos variables que indican si está ordenada
        $orderBy = $this->getOrderBy();
        $desc = $this->getOrderDescending();

        $this->viewData["OrderByRolescod"] = $orderBy === "rolescod" && !$desc;
        $this->viewData["OrderByRolescodDesc"] = $orderBy === "rolescod" && $desc;
        $this->viewData["OrderByRolesdsc"] = $orderBy === "rolesdsc" && !$desc;
        $this->viewData["OrderByRolesdscDesc"] = $orderBy === "rolesdsc" && $desc;
        $this->viewData["OrderByRolesest"] = $orderBy === "rolesest" && !$desc;
        $this->viewData["OrderByRolesestDesc"] = $orderBy === "rolesest" && $desc;
    }

    private function getPaginationHtml(int $currentPage, int $totalPages, string $baseUrl): string
    {
        if ($totalPages <= 1) return "";

        $html = '<div class="pagination">';
        for ($i = 0; $i < $totalPages; $i++) {
            $active = $i === $currentPage ? 'class="active"' : '';
            $url = $baseUrl . "&page=" . $i;
            $html .= "<a $active href=\"$url\">" . ($i + 1) . "</a> ";
        }
        $html .= '</div>';
        return $html;
    }
}
