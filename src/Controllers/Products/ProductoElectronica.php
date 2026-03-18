<?php

namespace Controllers\Products;

use Controllers\PublicController;
use Dao\Products\ProductosElectronica as Dao;
use Views\Renderer;
use Utilities\Site;

class ProductoElectronica extends PublicController
{

    private $viewData = [];
    private $mode = "DSP";

    private $producto = [
        "id_producto" => 0,
        "nombre" => "",
        "tipo" => "",
        "precio" => "",
        "marca" => "",
        "fecha_lanzamiento" => ""
    ];

    public function run(): void
    {

        $this->mode = $_POST["mode"] ?? $_GET["mode"] ?? "DSP";


        if ($this->mode != "INS" && isset($_GET["id"])) {
            $this->producto = Dao::getById(intval($_GET["id"]));
        }

        if ($this->isPostBack()) {

            $id = intval($_POST["id_producto"] ?? 0);

            $nombre = htmlspecialchars(trim($_POST["nombre"] ?? ""));
            $tipo = htmlspecialchars(trim($_POST["tipo"] ?? ""));
            $precio = floatval($_POST["precio"] ?? 0);
            $marca = htmlspecialchars(trim($_POST["marca"] ?? ""));
            $fecha = $_POST["fecha_lanzamiento"] ?? "";


            if ($nombre == "") {
                return;
            }

            if ($fecha == "") {
                $fecha = date("Y-m-d");
            }

            switch ($this->mode) {

                case "INS":

                    Dao::insert(
                        $nombre,
                        $tipo,
                        $precio,
                        $marca,
                        $fecha
                    );

                    break;

                case "UPD":

                    Dao::update(
                        $id,
                        $nombre,
                        $tipo,
                        $precio,
                        $marca,
                        $fecha
                    );

                    break;

                case "DEL":

                    Dao::delete($id);

                    break;
            }

            Site::redirectTo("index.php?page=Products_ProductosElectronica");
        }


        $this->viewData = $this->producto;
        $this->viewData["mode"] = $this->mode;

        Renderer::render(
            "products/producto_electronica",
            $this->viewData
        );
    }
}
