<?php

namespace Controllers\Security;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Security\Users as DaoUsers;
use Utilities\Context;
use Utilities\Paging;

class Users extends PublicController
{
    private $viewData = [];
    private $partialName = "";
    private $status = "";
    private $orderBy = "";
    private $orderDescending = false;
    private $pageNumber = 1;
    private $itemsPerPage = 10;
    private $users = [];
    private $usersCount = 0;
    private $pages = 0;

    public function run(): void
    {
        $this->getParamsFromContext();
        $this->getParams();

        $tmpUsers = DaoUsers::searchUsers($this->partialName, $this->status);
        $this->usersCount = count($tmpUsers);
        $this->pages = $this->usersCount > 0 ? ceil($this->usersCount / $this->itemsPerPage) : 1;

        $start = ($this->pageNumber - 1) * $this->itemsPerPage;
        $this->users = array_slice($tmpUsers, $start, $this->itemsPerPage);

        $this->setParamsToContext();
        $this->setParamsToDataView();
        Renderer::render("security/users", $this->viewData);
    }

    private function getParams(): void
    {
        $this->partialName = $_GET["partialName"] ?? $this->partialName;
        $this->status = $_GET["status"] ?? $this->status;
        $this->orderBy = $_GET["orderBy"] ?? $this->orderBy;
        $this->orderDescending = isset($_GET["orderDescending"]) ? boolval($_GET["orderDescending"]) : $this->orderDescending;
        $this->pageNumber = intval($_GET["pageNum"] ?? $this->pageNumber);
        $this->itemsPerPage = intval($_GET["itemsPerPage"] ?? $this->itemsPerPage);
    }

    private function getParamsFromContext(): void
    {
        $this->partialName = Context::getContextByKey("users_partialName");
        $this->status = Context::getContextByKey("users_status");
        $this->orderBy = Context::getContextByKey("users_orderBy");
        $this->orderDescending = boolval(Context::getContextByKey("users_orderDescending"));
        $this->pageNumber = intval(Context::getContextByKey("users_page"));
        $this->itemsPerPage = intval(Context::getContextByKey("users_itemsPerPage"));
    }

    private function setParamsToContext(): void
    {
        Context::setContext("users_partialName", $this->partialName, true);
        Context::setContext("users_status", $this->status, true);
        Context::setContext("users_orderBy", $this->orderBy, true);
        Context::setContext("users_orderDescending", $this->orderDescending, true);
        Context::setContext("users_page", $this->pageNumber, true);
        Context::setContext("users_itemsPerPage", $this->itemsPerPage, true);
    }

    private function setParamsToDataView(): void
    {
        $this->viewData["partialName"] = $this->partialName;
        $this->viewData["status"] = $this->status;
        $this->viewData["orderBy"] = $this->orderBy;
        $this->viewData["orderDescending"] = $this->orderDescending;
        $this->viewData["pageNum"] = $this->pageNumber;
        $this->viewData["itemsPerPage"] = $this->itemsPerPage;
        $this->viewData["usersCount"] = $this->usersCount;
        $this->viewData["pages"] = $this->pages;
        $this->viewData["users"] = $this->users;

        $this->viewData["pagination"] = Paging::getPagination(
            $this->usersCount,
            $this->itemsPerPage,
            $this->pageNumber,
            "index.php?page=Security_Users",
            "Security_Users"
        );
    }
}
