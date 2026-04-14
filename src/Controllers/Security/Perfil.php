<?php

namespace Controllers\Security;

use Controllers\PrivateController;
use Dao\Commerce\Transacciones as DaoTransacciones;
use Dao\Security\Security as DaoSecurity;
use Utilities\Paging;
use Utilities\Validators;
use Views\Renderer;

class Perfil extends PrivateController
{
    private $viewData = [];
    private $pageNumber = 1;
    private $itemsPerPage = 8;
    private $profileError = '';
    private $profileSuccess = '';

    public function run(): void
    {
        $this->getParams();
        $userId = \Utilities\Security::getUserId();

        if ($this->isPostBack()) {
            $this->processProfileUpdate($userId);
        }

        $userData = DaoSecurity::getUsuarioById($userId);
        if (!$userData) {
            throw new \Exception('No se pudo cargar la información del usuario');
        }

        $transactionsData = DaoTransacciones::getTransactionsByUser(
            $userId,
            $this->pageNumber - 1,
            $this->itemsPerPage
        );

        $transactions = $transactionsData['transactions'];
        foreach ($transactions as &$transaction) {
            $transaction['details'] = DaoTransacciones::getTransactionDetailsByTransactionId(
                intval($transaction['transaccionId'] ?? 0)
            );
        }
        unset($transaction);
        $totalTransactions = $transactionsData['total'];
        $pages = $totalTransactions > 0 ? ceil($totalTransactions / $this->itemsPerPage) : 1;
        if ($this->pageNumber > $pages) {
            $this->pageNumber = $pages;
        }

        $this->viewData['userNombre'] = $userData['username'] ?? '';
        $this->viewData['userEmail'] = $userData['useremail'] ?? '';
        $this->viewData['userStatus'] = $userData['userest'] ?? '';
        $this->viewData['userTipo'] = $userData['usertipo'] ?? '';
        $this->viewData['profileError'] = $this->profileError;
        $this->viewData['profileSuccess'] = $this->profileSuccess;
        $this->viewData['hasProfileError'] = ($this->profileError !== '');
        $this->viewData['hasProfileSuccess'] = ($this->profileSuccess !== '');
        $this->viewData['transactions'] = $transactions;
        $this->viewData['totalTransactions'] = $totalTransactions;
        $this->viewData['pageNumber'] = $this->pageNumber;
        $this->viewData['itemsPerPage'] = $this->itemsPerPage;
        $this->viewData['pagination'] = Paging::getPagination(
            $totalTransactions,
            $this->itemsPerPage,
            $this->pageNumber,
            'index.php?page=Security_Perfil',
            'Security_Perfil'
        );

        Renderer::render('security/perfil', $this->viewData);
    }

    private function processProfileUpdate(int $userId): void
    {
        $newName = trim($_POST['userNombre'] ?? '');

        if (Validators::IsEmpty($newName)) {
            $this->profileError = 'El nombre no puede quedar vacío';
            return;
        }

        if (mb_strlen($newName) > 80) {
            $this->profileError = 'El nombre no puede exceder 80 caracteres';
            return;
        }

        DaoSecurity::updateUsuarioNombre($userId, $newName);
        if (isset($_SESSION['login'])) {
            $_SESSION['login']['userName'] = $newName;
        }
        $_SESSION['userName'] = $newName;
        $this->profileSuccess = 'Nombre actualizado correctamente';
    }

    private function getParams(): void
    {
        $this->pageNumber = intval($_GET['pageNum'] ?? $this->pageNumber);
        $this->itemsPerPage = intval($_GET['itemsPerPage'] ?? $this->itemsPerPage);
        if ($this->pageNumber < 1) {
            $this->pageNumber = 1;
        }
        if ($this->itemsPerPage < 1) {
            $this->itemsPerPage = 8;
        }
    }
}
