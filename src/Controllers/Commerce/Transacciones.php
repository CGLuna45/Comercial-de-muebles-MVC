<?php

namespace Controllers\Commerce;

use Controllers\PrivateController;
use Dao\Commerce\Transacciones as DaoTransacciones;
use Utilities\Context;
use Utilities\Paging;
use Views\Renderer;

class Transacciones extends PrivateController
{
    private $partial = "";
    private $status = "";
    private $pageNumber = 1;
    private $itemsPerPage = 10;
    private $viewData = [];

    public function run(): void
    {
        $this->getParamsFromContext();
        $this->getParams();

        $tmp = DaoTransacciones::getTransactions(
            $this->partial,
            $this->status,
            $this->pageNumber - 1,
            $this->itemsPerPage
        );

        $transactions = $tmp["transactions"];
        $total = $tmp["total"];
        $pages = $total > 0 ? ceil($total / $this->itemsPerPage) : 1;
        if ($this->pageNumber > $pages) {
            $this->pageNumber = $pages;
        }

        $this->setParamsToContext();
        $this->viewData["partial"] = $this->partial;
        $this->viewData["status"] = $this->status;
        $this->viewData["transactions"] = $transactions;
        $this->viewData["totalTransactions"] = $total;
        $this->viewData["status_EMP"] = $this->status === "" ? "selected" : "";
        $this->viewData["status_ACT"] = $this->status === "ACT" ? "selected" : "";
        $this->viewData["status_COM"] = $this->status === "COM" ? "selected" : "";
        $this->viewData["status_CAN"] = $this->status === "CAN" ? "selected" : "";
        $this->viewData["status_ERR"] = $this->status === "ERR" ? "selected" : "";

        $this->viewData["pagination"] = Paging::getPagination(
            $total,
            $this->itemsPerPage,
            $this->pageNumber,
            "index.php?page=Commerce_Transacciones",
            "Commerce_Transacciones"
        );

        Renderer::render("commerce/transacciones", $this->viewData);
    }

    private function getParams(): void
    {
        $this->partial = $_GET["partial"] ?? $this->partial;
        $this->status = $_GET["status"] ?? $this->status;
        if ($this->status === "EMP") {
            $this->status = "";
        }
        $this->pageNumber = intval($_GET["pageNum"] ?? $this->pageNumber);
        $this->itemsPerPage = intval($_GET["itemsPerPage"] ?? $this->itemsPerPage);
        if ($this->pageNumber < 1) $this->pageNumber = 1;
        if ($this->itemsPerPage < 1) $this->itemsPerPage = 10;
    }

    private function getParamsFromContext(): void
    {
        $this->partial = Context::getContextByKey("transactions_partial");
        $this->status = Context::getContextByKey("transactions_status");
        $this->pageNumber = intval(Context::getContextByKey("transactions_page"));
        $this->itemsPerPage = intval(Context::getContextByKey("transactions_itemsPerPage"));
        if ($this->pageNumber < 1) $this->pageNumber = 1;
        if ($this->itemsPerPage < 1) $this->itemsPerPage = 10;
    }

    private function setParamsToContext(): void
    {
        Context::setContext("transactions_partial", $this->partial, true);
        Context::setContext("transactions_status", $this->status, true);
        Context::setContext("transactions_page", $this->pageNumber, true);
        Context::setContext("transactions_itemsPerPage", $this->itemsPerPage, true);
    }
}
