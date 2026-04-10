<?php

namespace Controllers;

use \Dao\Products\Products as ProductsDao;
use \Views\Renderer as Renderer;
use \Utilities\Site as Site;

class HomeController extends PrivateController
{
    public function run(): void
{
    $dataView = [];
    $dataView['userName'] = \Utilities\Security::getUser()['userName'] ?? 'Admin';
    \Views\Renderer::render("dashboard", $dataView);
}
}
