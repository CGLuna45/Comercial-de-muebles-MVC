<?php

namespace Controllers\Checkout;

use Controllers\PublicController;

class Error extends PublicController
{
    public function run(): void
    {
        if (!\Utilities\Security::isLogged()) {
            \Utilities\Site::redirectTo("index.php?page=Sec_Login");
        }

        $viewData = array(
            "message" => "El pago fue cancelado o no se pudo completar.",
        );
        \Views\Renderer::render("paypal/error", $viewData);
    }
}
