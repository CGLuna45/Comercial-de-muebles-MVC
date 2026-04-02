<?php

namespace Controllers\Checkout;

use Controllers\PrivateController;
class Error extends PrivateController
{
    public function run(): void
    {
        echo "error";
        die();
    }
}

?>
