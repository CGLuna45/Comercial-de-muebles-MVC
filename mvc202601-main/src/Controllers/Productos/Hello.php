<?php

namespace Controllers\Productos;

use Controllers\PublicController;
use Views\Renderer;

class Hello extends PublicController
{
    private string $txtNombre = "";
    private string $txtResultado = "";
    private array $viewData = [];

    public function run(): void
    {
        if ($this->isPostBack()) {
            $this->txtNombre = $_POST["txtNombre"] ?? ""; //COALESCE
            if ($this->txtNombre === "") {
                $this->txtResultado = "Error: El nombre viene vacio!!";
            } else {
                $this->txtResultado = "Bienvenido, hola " . $this->txtNombre;
            }
        }
        $this->preparaViewData();
        Renderer::render("productos/hello", $this->viewData);
    }

    private function preparaViewData()
    {
        $this->viewData["txtNombre"] = $this->txtNombre;
        $this->viewData["mensajeFinal"] = $this->txtResultado;
    }
}
