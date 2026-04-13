<?php

namespace Controllers\Checkout;

use Controllers\PublicController;

class Checkout extends PublicController
{
    public function run(): void
    {
        if (!\Utilities\Security::isLogged()) {
            $redirTo = urlencode('index.php?page=Checkout_Checkout');
            \Utilities\Site::redirectTo('index.php?page=Sec_Login&redirto='.$redirTo);
        }

        $items = $this->getSessionCartItems();
        if (count($items) === 0) {
            \Utilities\Site::redirectTo('carrito.php');
        }

        $subtotal = 0.0;
        $tax = 0.0;
        $count = 0;
        foreach ($items as $item) {
            $lineSubtotal = ((float) $item['price']) * ((int) $item['quantity']);
            $lineTax = $lineSubtotal * 0.15;
            $subtotal += $lineSubtotal;
            $tax += $lineTax;
            $count += (int) $item['quantity'];
        }

        $viewData = [
            'cartItems' => $items,
            'subtotal' => number_format($subtotal, 2, '.', ''),
            'tax' => number_format($tax, 2, '.', ''),
            'total' => number_format($subtotal + $tax, 2, '.', ''),
            'cartCount' => $count,
            'generalError' => '',
        ];

        if ($this->isPostBack()) {
            try {
                $baseUrl = $this->getBaseUrl();
                $PayPalOrder = new \Utilities\Paypal\PayPalOrder(
                    'CEDRIKA-'.time(),
                    $baseUrl.'index.php?page=Checkout_Error',
                    $baseUrl.'index.php?page=Checkout_Accept'
                );

                foreach ($items as $item) {
                    $unitPrice = round((float) $item['price'], 2);
                    $unitTax = round($unitPrice * 0.15, 2);
                    $PayPalOrder->addItem(
                        $item['name'],
                        'Mueble seleccionado en catalogo CEDRIKA',
                        'PRD-'.$item['id'],
                        $unitPrice,
                        $unitTax,
                        (int) $item['quantity'],
                        'PHYSICAL_GOODS'
                    );
                }

                $PayPalRestApi = new \Utilities\PayPal\PayPalRestApi(
                    \Utilities\Context::getContextByKey('PAYPAL_CLIENT_ID'),
                    \Utilities\Context::getContextByKey('PAYPAL_CLIENT_SECRET')
                );
                $PayPalRestApi->getAccessToken();
                $response = $PayPalRestApi->createOrder($PayPalOrder);

                if (!isset($response->id)) {
                    throw new \Exception('No fue posible generar la orden de PayPal.');
                }

                $_SESSION['orderid'] = $response->id;
                $_SESSION['checkout_summary'] = $viewData;

                if (isset($response->links)) {
                    foreach ($response->links as $link) {
                        if (isset($link->rel) && $link->rel === 'approve') {
                            \Utilities\Site::redirectTo($link->href);
                        }
                    }
                }

                throw new \Exception('No se encontro el enlace de aprobacion de PayPal.');
            } catch (\Exception $ex) {
                $viewData['generalError'] = 'No se pudo iniciar el pago en este momento.';
            }
        }

        \Views\Renderer::render('paypal/checkout', $viewData);
    }

    private function getSessionCartItems(): array
    {
        $cart = $_SESSION['cart'] ?? [];
        $items = [];
        foreach ($cart as $item) {
            if (!isset($item['id'], $item['nombre'], $item['precio'], $item['cantidad'])) {
                continue;
            }
            $price = (float) $item['precio'];
            $quantity = (int) $item['cantidad'];
            $items[] = [
                'id' => (int) $item['id'],
                'name' => (string) $item['nombre'],
                'price' => number_format($price, 2, '.', ''),
                'quantity' => $quantity,
                'lineSubtotal' => number_format($price * $quantity, 2, '.', ''),
            ];
        }

        return $items;
    }

    private function getBaseUrl(): string
    {
        $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        $scheme = $https ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        if ($scriptDir === '/' || $scriptDir === '.') {
            $scriptDir = '';
        }

        return $scheme.'://'.$host.$scriptDir.'/';
    }
}
