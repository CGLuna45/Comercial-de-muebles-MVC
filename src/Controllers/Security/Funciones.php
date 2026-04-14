<?php

namespace Controllers\Security;

use Controllers\PrivateController;
use Views\Renderer;
use Dao\Security\Funciones as DaoFunciones;
use Utilities\Site;

// Listado y filtros de funciones/permiso
class Funciones extends PrivateController
{
    private $viewData = [];

    // =============================
    // RUN
    // =============================
    public function run(): void
    {
        try {
            $partialName = $_GET["partialName"] ?? "";
            $status = $_GET["status"] ?? "";
            // Lista funciones con filtros, orden y paginacion manual
            $type = $_GET["type"] ?? "";
            $orderBy = $this->getOrderBy();
            $orderDescending = $this->getOrderDescending();
            $page = intval($_GET["page"] ?? 0);
            $itemsPerPage = 10;

            $result = DaoFunciones::getFunciones(
                $partialName,
                $status,
                $type,
                $orderBy,
                $orderDescending,
                $page,
                $itemsPerPage
            );

            $this->viewData["funciones"] = $result["funciones"];
            $this->viewData["total"] = $result["total"];
            $this->viewData["page"] = $result["page"];
            $this->viewData["itemsPerPage"] = $result["itemsPerPage"];

            $this->viewData["partialName"] = $partialName;
            $this->viewData["status"] = $status;
            $this->viewData["status_EMP"] = $status === "" ? "selected" : "";
            $this->viewData["status_ACT"] = $status === "ACT" ? "selected" : "";
            $this->viewData["status_INA"] = $status === "INA" ? "selected" : "";

            $this->viewData["type"] = $type;
            // Opciones para el filtro de tipo (puedes definirlas aquaA o cargarlas de la BD)
            $this->viewData["type_EMP"] = $type === "" ? "selected" : "";
            $this->viewData["type_MNU"] = $type === "MNU" ? "selected" : "";
            $this->viewData["type_FNC"] = $type === "FNC" ? "selected" : "";
            $this->viewData["type_CTL"] = $type === "CTL" ? "selected" : "";

            $this->setOrderVariables();

            $this->viewData["pagination"] = $this->getPaginationHtml(
                $result["page"],
                ceil($result["total"] / $itemsPerPage),
                "index.php?page=Security_Funciones&partialName=" . urlencode($partialName) . "&status=" . urlencode($status) . "&type=" . urlencode($type)
            );

            Renderer::render("security/funciones", $this->viewData);
        } catch (\Exception $ex) {
            Site::redirectToWithMsg(
                "index.php?page=Security_Funciones",
                "Error: " . $ex->getMessage()
            );
        }
    }

    // =============================
    // GETORDERBY
    // =============================
    private function getOrderBy(): string
    {
        $allowed = ["fncod", "fndsc", "fnest", "fntyp"];
        // Normaliza campo de orden permitido
        $orderBy = $_GET["orderBy"] ?? "";
        return in_array($orderBy, $allowed) ? $orderBy : "";
    }

    // =============================
    // GETORDERDESCENDING
    // =============================
    private function getOrderDescending(): bool
    {
        // Interpreta bandera de orden descendente
        return isset($_GET["orderDescending"]) && $_GET["orderDescending"] === "1";
    }

    // =============================
    // SETORDERVARIABLES
    // =============================
    private function setOrderVariables(): void
    {
        // Marca variables de vista para indicadores de orden
        $orderBy = $this->getOrderBy();
        $desc = $this->getOrderDescending();

        $this->viewData["OrderByFncod"] = $orderBy === "fncod" && !$desc;
        $this->viewData["OrderByFncodDesc"] = $orderBy === "fncod" && $desc;
        $this->viewData["OrderByFndsc"] = $orderBy === "fndsc" && !$desc;
        $this->viewData["OrderByFndscDesc"] = $orderBy === "fndsc" && $desc;
        $this->viewData["OrderByFnest"] = $orderBy === "fnest" && !$desc;
        $this->viewData["OrderByFnestDesc"] = $orderBy === "fnest" && $desc;
        $this->viewData["OrderByFntyp"] = $orderBy === "fntyp" && !$desc;
        $this->viewData["OrderByFntypDesc"] = $orderBy === "fntyp" && $desc;
    }

    // =============================
    // GETPAGINATIONHTML
    // =============================
    private function getPaginationHtml(int $currentPage, int $totalPages, string $baseUrl): string
    {
        if ($totalPages <= 1) return "";

        // Genera HTML simple de navegaciaIn por paginas
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
