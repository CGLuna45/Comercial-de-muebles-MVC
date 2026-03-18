<?php
// CRUD. (Create, Read, Update Delete) 
//       ( Insert, Select, Update, Delete)
//       ( INS, DSP, UPD, DEL)

namespace Controllers\Mantenimientos\Libros;

use Controllers\PublicController;
use Views\Renderer;

use Dao\Mantenimientos\Libros as LibrosDAO;

const LIST_VIEW_TEMPLATE = "mantenimientos/libros/listado";

class Listado extends PublicController
{

    private array $librosList = [];
    public function run(): void
    {
        $this->librosList = LibrosDAO::getAllLibros();
        Renderer::render(LIST_VIEW_TEMPLATE, $this->prepareViewData());
    }

    private function prepareViewData()
    {
        return [
            "libros" => $this->librosList
        ];
    }
}
