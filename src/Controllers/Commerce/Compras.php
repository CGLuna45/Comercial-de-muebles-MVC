<?php

namespace Controllers\Commerce;

use Controllers\PrivateController;
use Dao\Commerce\Compras as DaoCompras;
use Dao\Products\Products as ProductsDao;
use Utilities\Context;
use Utilities\Paging;
use Utilities\Site;
use Views\Renderer;

class Compras extends PrivateController
{
    private $partial = '';
    private $status = '';
    private $pageNumber = 1;
    private $itemsPerPage = 10;
    private $viewData = [];

    public function run(): void
    {
        if ($this->isPostBack()) {
            $action = $_POST['action'] ?? '';
            if ($action === 'updateStock') {
                $this->updateStock();
            }
        }

        $this->getParamsFromContext();
        $this->getParams();

        $tmp = DaoCompras::getPurchases(
            $this->partial,
            $this->status,
            $this->pageNumber - 1,
            $this->itemsPerPage
        );

        $purchases = $tmp['purchases'];
        $total = $tmp['total'];
        $pages = $total > 0 ? ceil($total / $this->itemsPerPage) : 1;
        if ($this->pageNumber > $pages) {
            $this->pageNumber = $pages;
        }

        $this->setParamsToContext();
        $this->viewData['partial'] = $this->partial;
        $this->viewData['status'] = $this->status;
        $this->viewData['purchases'] = $purchases;
        $this->viewData['totalPurchases'] = $total;
        $this->viewData['status_EMP'] = $this->status === '' ? 'selected' : '';
        $this->viewData['status_AGO'] = $this->status === 'AGO' ? 'selected' : '';
        $this->viewData['status_BAJ'] = $this->status === 'BAJ' ? 'selected' : '';
        $this->viewData['status_OK'] = $this->status === 'OK' ? 'selected' : '';

        $this->viewData['pagination'] = Paging::getPagination(
            $total,
            $this->itemsPerPage,
            $this->pageNumber,
            'index.php?page=Commerce_Compras',
            'Commerce_Compras'
        );

        Renderer::render('commerce/compras', $this->viewData);
    }

    private function updateStock(): void
    {
        $productId = intval($_POST['productId'] ?? 0);
        $newStock = intval($_POST['newStock'] ?? -1);

        if ($productId <= 0 || $newStock < 0) {
            Site::redirectToWithMsg('index.php?page=Commerce_Compras', 'No se pudo actualizar el stock.');
        }

        $result = ProductsDao::updateProductStock($productId, $newStock);
        if ($result > 0) {
            Site::redirectToWithMsg('index.php?page=Commerce_Compras', 'Stock actualizado exitosamente.');
        }

        Site::redirectToWithMsg('index.php?page=Commerce_Compras', 'No se pudo actualizar el stock.');
    }

    private function getParams(): void
    {
        $this->partial = $_GET['partial'] ?? $this->partial;
        $this->status = $_GET['status'] ?? $this->status;
        if ($this->status === 'EMP') {
            $this->status = '';
        }
        $this->pageNumber = intval($_GET['pageNum'] ?? $this->pageNumber);
        $this->itemsPerPage = intval($_GET['itemsPerPage'] ?? $this->itemsPerPage);
        if ($this->pageNumber < 1) {
            $this->pageNumber = 1;
        }
        if ($this->itemsPerPage < 1) {
            $this->itemsPerPage = 10;
        }
    }

    private function getParamsFromContext(): void
    {
        $this->partial = Context::getContextByKey('purchases_partial');
        $this->status = Context::getContextByKey('purchases_status');
        $this->pageNumber = intval(Context::getContextByKey('purchases_page'));
        $this->itemsPerPage = intval(Context::getContextByKey('purchases_itemsPerPage'));
        if ($this->pageNumber < 1) {
            $this->pageNumber = 1;
        }
        if ($this->itemsPerPage < 1) {
            $this->itemsPerPage = 10;
        }
    }

    private function setParamsToContext(): void
    {
        Context::setContext('purchases_partial', $this->partial, true);
        Context::setContext('purchases_status', $this->status, true);
        Context::setContext('purchases_page', $this->pageNumber, true);
        Context::setContext('purchases_itemsPerPage', $this->itemsPerPage, true);
    }
}
