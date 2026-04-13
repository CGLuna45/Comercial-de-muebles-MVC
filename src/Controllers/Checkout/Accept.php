<?php

namespace Controllers\Checkout;

use Controllers\PublicController;

class Accept extends PublicController
{
    public function run(): void
    {
        if (!\Utilities\Security::isLogged()) {
            \Utilities\Site::redirectTo("index.php?page=Sec_Login");
        }

        $dataview = array(
            "isSuccess" => false,
            "message" => "No se pudo confirmar el pago.",
            "orderjson" => "",
            "subtotal" => "0.00",
            "tax" => "0.00",
            "total" => "0.00",
            "invoiceNumber" => "INV-" . date("YmdHis"),
            "payerName" => "",
            "payerEmail" => "",
            "paymentDate" => date("d/m/Y H:i"),
            "paymentId" => "",
            "items" => array(),
        );

        $token = $_GET["token"] ?? "";
        $sessionToken = $_SESSION["orderid"] ?? "";
        $checkoutSummary = $_SESSION["checkout_summary"] ?? array();
        if (isset($checkoutSummary["subtotal"])) {
            $dataview["subtotal"] = $checkoutSummary["subtotal"];
        }
        if (isset($checkoutSummary["tax"])) {
            $dataview["tax"] = $checkoutSummary["tax"];
        }
        if (isset($checkoutSummary["total"])) {
            $dataview["total"] = $checkoutSummary["total"];
        }
        if (isset($checkoutSummary["cartItems"])) {
            $dataview["items"] = $checkoutSummary["cartItems"];
        }

        if ($token === "" || $sessionToken === "" || $token !== $sessionToken) {
            $dataview["message"] = "La orden no coincide con la sesion activa.";
            \Views\Renderer::render("paypal/accept", $dataview);
            return;
        }

        try {
            $PayPalRestApi = new \Utilities\PayPal\PayPalRestApi(
                \Utilities\Context::getContextByKey("PAYPAL_CLIENT_ID"),
                \Utilities\Context::getContextByKey("PAYPAL_CLIENT_SECRET")
            );
            $result = $PayPalRestApi->captureOrder($sessionToken);

            $status = $result->status ?? "";
            if ($status === "COMPLETED") {
                $dataview["isSuccess"] = true;
                $dataview["message"] = "Pago confirmado correctamente.";
                $dataview["paymentId"] = $result->id ?? $sessionToken;
                $dataview["payerName"] = trim(($result->payer->name->given_name ?? "") . " " . ($result->payer->name->surname ?? ""));
                $dataview["payerEmail"] = $result->payer->email_address ?? "";

                foreach ($dataview["items"] as $item) {
                    \Dao\Products\Products::decrementProductStock(
                        (int) $item["id"],
                        (int) $item["quantity"]
                    );
                }

                $_SESSION["cart"] = array();
            } else {
                $dataview["message"] = "PayPal no confirmo el pago como completado.";
            }
            $dataview["orderjson"] = json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $ex) {
            $dataview["message"] = "Ocurrio un problema al capturar la orden.";
        }

        unset($_SESSION["orderid"]);
        unset($_SESSION["checkout_summary"]);
        \Views\Renderer::render("paypal/accept", $dataview);
    }
}
