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

                $userId = \Utilities\Security::getUserId();
                if ($userId <= 0) {
                    throw new \Exception("No se pudo identificar al usuario de la transacción.");
                }

                $items = $dataview["items"];
                if (count($items) === 0) {
                    throw new \Exception("La orden no contiene productos para registrar.");
                }

                $conn = \Dao\Dao::getConn();
                $conn->beginTransaction();

                try {
                    $stmtCarritilla = $conn->prepare(
                        "INSERT INTO carritilla (usuarioId, carritillaStatus) VALUES (:usuarioId, 'ACT')"
                    );
                    $stmtCarritilla->execute(array("usuarioId" => $userId));
                    $carritillaId = intval($conn->lastInsertId());

                    $stmtTransaccion = $conn->prepare(
                        "INSERT INTO transacciones
                            (usuarioId, carritillaId, transaccionTotal, transaccionStatus, paypalOrderId, paypalStatus, paypalPayerId, paypalFecha)
                         VALUES
                            (:usuarioId, :carritillaId, :transaccionTotal, :transaccionStatus, :paypalOrderId, :paypalStatus, :paypalPayerId, :paypalFecha)"
                    );
                    $stmtTransaccion->execute(array(
                        "usuarioId" => $userId,
                        "carritillaId" => $carritillaId,
                        "transaccionTotal" => floatval($dataview["total"]),
                        "transaccionStatus" => "PAG",
                        "paypalOrderId" => $result->id ?? $sessionToken,
                        "paypalStatus" => $status,
                        "paypalPayerId" => $result->payer->payer_id ?? "",
                        "paypalFecha" => date("Y-m-d H:i:s"),
                    ));
                    $transaccionId = intval($conn->lastInsertId());

                    $stmtDetalle = $conn->prepare(
                        "INSERT INTO transacciones_detalle
                            (transaccionId, productId, transDetalleCantidad, transDetallePrecio, transDetalleSubtotal)
                         VALUES
                            (:transaccionId, :productId, :transDetalleCantidad, :transDetallePrecio, :transDetalleSubtotal)"
                    );

                    $stmtDecreaseStock = $conn->prepare(
                        "UPDATE products
                            SET productStock = productStock - :quantity,
                                productStatus = CASE WHEN (productStock - :quantity) > 0 THEN 'ACT' ELSE 'INA' END
                         WHERE productId = :productId
                           AND productStock >= :quantity"
                    );

                    foreach ($items as $item) {
                        $this->ensureProductRecord($conn, $item);

                        $productId = intval($item["id"]);
                        $quantity = intval($item["quantity"]);
                        $stmtDecreaseStock->execute(array(
                            "productId" => $productId,
                            "quantity" => $quantity,
                        ));

                        if (intval($stmtDecreaseStock->rowCount()) <= 0) {
                            throw new \Exception("Stock insuficiente para el producto #" . $productId);
                        }

                        $stmtDetalle->execute(array(
                            "transaccionId" => $transaccionId,
                            "productId" => $productId,
                            "transDetalleCantidad" => $quantity,
                            "transDetallePrecio" => floatval($item["price"]),
                            "transDetalleSubtotal" => floatval($item["lineSubtotal"]),
                        ));
                    }

                    $conn->commit();
                } catch (\Throwable $persistEx) {
                    if ($conn->inTransaction()) {
                        $conn->rollBack();
                    }
                    throw $persistEx;
                }

                $_SESSION["cart"] = array();
            } else {
                $dataview["message"] = "PayPal no confirmo el pago como completado.";
            }
            $dataview["orderjson"] = json_encode($result, JSON_PRETTY_PRINT);
        } catch (\Exception $ex) {
            $dataview["isSuccess"] = false;
            $dataview["message"] = "Ocurrio un problema al capturar la orden.";
        }

        unset($_SESSION["orderid"]);
        unset($_SESSION["checkout_summary"]);
        \Views\Renderer::render("paypal/accept", $dataview);
    }

    private function ensureProductRecord(\PDO $conn, array $item): void
    {
        $productId = intval($item["id"]);
        $this->ensureCategoryRecord($conn);
        $stmtCheck = $conn->prepare("SELECT COUNT(*) AS total FROM products WHERE productId = :productId");
        $stmtCheck->execute(array("productId" => $productId));
        $exists = intval($stmtCheck->fetchColumn() ?? 0);
        if ($exists > 0) {
            return;
        }

        $stmtInsert = $conn->prepare(
            "INSERT INTO products
                (productId, categoriaId, productName, productDescription, productPrice, productStock, productImgUrl, productStatus)
             VALUES
                (:productId, :categoriaId, :productName, :productDescription, :productPrice, :productStock, :productImgUrl, 'ACT')"
        );
        $stmtInsert->execute(array(
            "productId" => $productId,
            "categoriaId" => 1,
            "productName" => (string) ($item["name"] ?? "Producto"),
            "productDescription" => "Producto sincronizado desde el catálogo de compra",
            "productPrice" => floatval($item["price"]),
            "productStock" => intval($item["quantity"]),
            "productImgUrl" => (string) ($item["image"] ?? ""),
        ));
    }

    private function ensureCategoryRecord(\PDO $conn): void
    {
        $stmtCheck = $conn->prepare("SELECT COUNT(*) FROM categorias WHERE categoriaId = 1");
        $stmtCheck->execute();
        if (intval($stmtCheck->fetchColumn() ?? 0) > 0) {
            return;
        }

        $stmtInsert = $conn->prepare(
            "INSERT INTO categorias (categoriaId, categoriaNombre, categoriaDescripcion, categoriaStatus)
             VALUES (1, 'General', 'Categoría general', 'ACT')"
        );
        $stmtInsert->execute();
    }
}
