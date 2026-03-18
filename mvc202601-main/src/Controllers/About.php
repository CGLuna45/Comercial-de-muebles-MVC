<?php

namespace Controllers;

use Views\Renderer;

class About extends PublicController
{
    public function run(): void
    {
        $viewData = [
            "nombre" => "Orlando J Betancourth",
            "correo" => "obetancourthunicah@gmail.com",
            "telefono" => "0000-0000"
        ];

        Renderer::render("about", $viewData);
    }
}
