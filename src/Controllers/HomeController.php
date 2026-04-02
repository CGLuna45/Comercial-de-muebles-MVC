<?php

namespace Controllers;

use \Dao\Products\Products as ProductsDao;
use \Views\Renderer as Renderer;
use \Utilities\Site as Site;

class HomeController extends PrivateController
{
    public function run(): void
    {
        // Redirige al index estático (UI de catálogo) para mantener el diseño principal consistente
        \Utilities\Site::redirectTo("index.php");
    }
}
