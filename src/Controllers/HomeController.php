<?php

namespace Controllers;

use \Dao\Products\Products as ProductsDao;
use \Views\Renderer as Renderer;
use \Utilities\Site as Site;

// Dashboard principal para usuarios autenticados
class HomeController extends PrivateController
{
    // =============================
    // RUN
    // =============================
    public function run(): void
{
    // Carga productos de home y renderiza dashboard privado
    $dataView = [];
    $dataView['userName'] = \Utilities\Security::getUser()['userName'] ?? 'Admin';
    \Views\Renderer::render("dashboard", $dataView);
}
}
