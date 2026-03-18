<?php

namespace Controllers\Products;

use Controllers\PublicController;
use Dao\Products\ProductosElectronica as Dao;
use Views\Renderer;

class ProductosElectronica extends PublicController
{
    private $viewData = [];

    public function run(): void
    {
        $this->viewData["productos"] = Dao::getAll();

        Renderer::render(
            "products/productos_electronica",
            $this->viewData
        );
    }
}
