<?php

namespace Dao\Products;

use Dao\Table;

class Products extends Table
{
    private static function normalizeProductRow(?array $product): ?array
    {
        if (!$product) {
            return null;
        }

        if (isset($product['productId'])) {
            return $product;
        }

        return [
            'productId' => intval($product['id'] ?? 0),
            'categoriaId' => intval($product['categoriaId'] ?? 0),
            'productName' => strval($product['productName'] ?? ($product['nombre'] ?? '')),
            'productDescription' => strval($product['productDescription'] ?? ''),
            'productPrice' => floatval($product['productPrice'] ?? ($product['precio'] ?? 0)),
            'productStock' => intval($product['productStock'] ?? ($product['stock'] ?? 0)),
            'productImgUrl' => strval($product['productImgUrl'] ?? ($product['imagen'] ?? '')),
            'productStatus' => strval($product['productStatus'] ?? 'ACT'),
        ];
    }

    private static function ensureFallbackCategoryId(): int
    {
        $category = self::obtenerUnRegistro(
            "SELECT categoriaId FROM categorias WHERE categoriaStatus = 'ACT' ORDER BY categoriaId ASC LIMIT 1",
            []
        );

        if (!empty($category["categoriaId"])) {
            return intval($category["categoriaId"]);
        }

        self::executeNonQuery(
            "INSERT INTO categorias (categoriaId, categoriaNombre, categoriaDescripcion, categoriaStatus)
             VALUES (1, 'General', 'Categoria general', 'ACT')",
            []
        );

        return 1;
    }

    private static function ensureLegacyCategoryRecords(): void
    {
        $sqlstr = "INSERT INTO categorias (categoriaNombre, categoriaDescripcion, categoriaStatus)
                   SELECT DISTINCT
                        p.categoria,
                        CONCAT('Categoria importada: ', p.categoria),
                        'ACT'
                   FROM productos p
                   LEFT JOIN categorias c
                     ON c.categoriaNombre = p.categoria
                    AND c.categoriaStatus = 'ACT'
                   WHERE p.categoria IS NOT NULL
                     AND TRIM(p.categoria) <> ''
                     AND c.categoriaId IS NULL";

        self::executeNonQuery($sqlstr, []);
    }

    public static function syncLegacyProducts(): void
    {
        self::ensureLegacyCategoryRecords();
        $fallbackCategoryId = self::ensureFallbackCategoryId();

                // Keep category aligned with legacy source without overriding stock adjustments.
                $updateCategorySql = "UPDATE products p
                                                            INNER JOIN productos lp
                                                                ON lp.id = p.productId
                                                            INNER JOIN categorias c
                                                                ON c.categoriaNombre = lp.categoria
                                                             AND c.categoriaStatus = 'ACT'
                                                            SET p.categoriaId = c.categoriaId
                                                            WHERE p.categoriaId <> c.categoriaId";
                self::executeNonQuery($updateCategorySql, []);

            // Keep status consistent with current stock levels.
            $syncStatusSql = "UPDATE products
                      SET productStatus = CASE WHEN productStock > 0 THEN 'ACT' ELSE 'INA' END
                      WHERE productStatus <> CASE WHEN productStock > 0 THEN 'ACT' ELSE 'INA' END";
            self::executeNonQuery($syncStatusSql, []);

        $sqlstr = "INSERT INTO products (
                        productId,
                        categoriaId,
                        productName,
                        productDescription,
                        productPrice,
                        productStock,
                        productImgUrl,
                        productStatus
                    )
                    SELECT
                        p.id,
                        COALESCE(
                            (
                                SELECT c.categoriaId
                                FROM categorias c
                                WHERE c.categoriaNombre = p.categoria
                                  AND c.categoriaStatus = 'ACT'
                                ORDER BY c.categoriaId ASC
                                LIMIT 1
                            ),
                            :fallbackCategoryId
                        ) AS categoriaId,
                        p.nombre AS productName,
                        CONCAT(p.nombre, ' - Catalogo CEDRIKA') AS productDescription,
                        p.precio AS productPrice,
                        p.stock AS productStock,
                        p.imagen AS productImgUrl,
                        CASE WHEN p.stock > 0 THEN 'ACT' ELSE 'INA' END AS productStatus
                    FROM productos p
                    LEFT JOIN products pr
                        ON pr.productId = p.id
                    WHERE pr.productId IS NULL";

        self::executeNonQuery($sqlstr, ["fallbackCategoryId" => $fallbackCategoryId]);
    }

    public static function getProducts(
        string $partialName = "",
        string $status = "",
        string $orderBy = "",
        bool $orderDescending = false,
        int $page = 0,
        int $itemsPerPage = 10,
        int $categoriaId = 0
    ) {
        self::syncLegacyProducts();

        $legacyCount = self::obtenerUnRegistro("SELECT COUNT(*) AS count FROM products", [])["count"] ?? 0;

        if ((int)$legacyCount === 0) {
            $sqlstr = "SELECT 
                        p.id AS productId,
                        0 AS categoriaId,
                        p.nombre AS productName,
                        '' AS productDescription,
                        p.precio AS productPrice,
                        p.imagen AS productImgUrl,
                        'ACT' AS productStatus,
                        'Activo' AS productStatusDsc
                    FROM productos p";
            $sqlstrCount = "SELECT COUNT(*) AS count FROM productos p";
            $conditions = [];
            $params = [];

            if ($partialName != "") {
                $conditions[] = "(p.nombre LIKE :partialName OR p.categoria LIKE :partialName)";
                $params["partialName"] = "%" . $partialName . "%";
            }
            if (!in_array($status, ["ACT", "INA", ""])) {
                throw new \Exception("Error Processing Request Status has invalid value");
            }
            if ($status === "INA") {
                return ["products" => [], "total" => 0, "page" => 0, "itemsPerPage" => $itemsPerPage];
            }

            if (count($conditions) > 0) {
                $sqlstr .= " WHERE " . implode(" AND ", $conditions);
                $sqlstrCount .= " WHERE " . implode(" AND ", $conditions);
            }

            $orderMap = [
                "productId" => "p.id",
                "productName" => "p.nombre",
                "productPrice" => "p.precio",
                "" => ""
            ];
            if (!array_key_exists($orderBy, $orderMap)) {
                throw new \Exception("Error Processing Request OrderBy has invalid value");
            }
            if ($orderBy != "") {
                $sqlstr .= " ORDER BY " . $orderMap[$orderBy];
                if ($orderDescending) {
                    $sqlstr .= " DESC";
                }
            }

            $numeroDeRegistros = self::obtenerUnRegistro($sqlstrCount, $params)["count"];
            $pagesCount = ceil($numeroDeRegistros / $itemsPerPage);
            if ($page > $pagesCount - 1) {
                $page = $pagesCount - 1;
            }
            if ($page < 0) {
                $page = 0;
            }
            $sqlstr .= " LIMIT " . $page * $itemsPerPage . ", " . $itemsPerPage;

            $registros = self::obtenerRegistros($sqlstr, $params);
            return ["products" => $registros, "total" => $numeroDeRegistros, "page" => $page, "itemsPerPage" => $itemsPerPage];
        }

        $sqlstr = "SELECT p.productId, p.categoriaId, p.productName, p.productDescription, p.productPrice, p.productImgUrl, p.productStatus, case when p.productStatus = 'ACT' then 'Activo' when p.productStatus = 'INA' then 'Inactivo' else 'Sin Asignar' end as productStatusDsc
    FROM products p";
        $sqlstrCount = "SELECT COUNT(*) as count FROM products p";
        $conditions = [];
        $params = [];
        if ($partialName != "") {
            $conditions[] = "p.productName LIKE :partialName";
            $params["partialName"] = "%" . $partialName . "%";
        }
        if (!in_array($status, ["ACT", "INA", ""])) {
            throw new \Exception("Error Processing Request Status has invalid value");
        }
        if ($status != "") {
            $conditions[] = "p.productStatus = :status";
            $params["status"] = $status;
        }
        if ($categoriaId > 0) {
            $conditions[] = "p.categoriaId = :categoriaId";
            $params["categoriaId"] = $categoriaId;
        }
        if (count($conditions) > 0) {
            $sqlstr .= " WHERE " . implode(" AND ", $conditions);
            $sqlstrCount .= " WHERE " . implode(" AND ", $conditions);
        }
        if (!in_array($orderBy, ["productId", "productName", "productPrice", ""])) {
            throw new \Exception("Error Processing Request OrderBy has invalid value");
        }
        if ($orderBy != "") {
            $sqlstr .= " ORDER BY " . $orderBy;
            if ($orderDescending) {
                $sqlstr .= " DESC";
            }
        }
        $numeroDeRegistros = self::obtenerUnRegistro($sqlstrCount, $params)["count"];
        $pagesCount = ceil($numeroDeRegistros / $itemsPerPage);
        if ($page > $pagesCount - 1) {
            $page = $pagesCount - 1;
        }
        if ($page < 0) {
            $page = 0;
        }
        $sqlstr .= " LIMIT " . $page * $itemsPerPage . ", " . $itemsPerPage;

        $registros = self::obtenerRegistros($sqlstr, $params);
        return ["products" => $registros, "total" => $numeroDeRegistros, "page" => $page, "itemsPerPage" => $itemsPerPage];
    }

    public static function getProductById(int $productId)
    {
        $sqlstr = "SELECT productId, categoriaId, productName, productDescription, productPrice, productStock, productImgUrl, productStatus
                   FROM products
                   WHERE productId = :productId
                   LIMIT 1";
        $params = ["productId" => $productId];
        $product = self::obtenerUnRegistro($sqlstr, $params);

        if ($product) {
            return $product;
        }

        $sqlstr = "SELECT id, 0 AS categoriaId, nombre, '' AS productDescription, precio, stock, imagen, 'ACT' AS productStatus
                   FROM productos
                   WHERE id = :productId
                   LIMIT 1";
        $legacyProduct = self::obtenerUnRegistro($sqlstr, $params);
        return self::normalizeProductRow($legacyProduct);
    }

    public static function decrementProductStock(int $productId, int $quantity)
    {
        $sqlstr = "UPDATE products
            SET productStock = productStock - :quantity,
                productStatus = CASE WHEN (productStock - :quantity) > 0 THEN 'ACT' ELSE 'INA' END
            WHERE productId = :productId
              AND productStock >= :quantity";
        $params = [
            "productId" => $productId,
            "quantity" => $quantity
        ];

        return self::executeNonQuery($sqlstr, $params);
    }

    public static function insertProduct(
        int $categoriaId,
        string $productName,
        string $productDescription,
        float $productPrice,
        string $productImgUrl,
        string $productStatus
    ) {
        $sqlstr = "INSERT INTO products (categoriaId, productName, productDescription, productPrice, productImgUrl, productStatus)
                   VALUES (:categoriaId, :productName, :productDescription, :productPrice, :productImgUrl, 'INA')";
        $params = [
            "categoriaId" => $categoriaId,
            "productName" => $productName,
            "productDescription" => $productDescription,
            "productPrice" => $productPrice,
            "productImgUrl" => $productImgUrl
        ];
        return self::executeNonQuery($sqlstr, $params);
    }

    public static function updateProduct(
        int $productId,
        int $categoriaId,
        string $productName,
        string $productDescription,
        float $productPrice,
        string $productImgUrl,
        string $productStatus
    ) {
        $sqlstr = "UPDATE products
                   SET categoriaId = :categoriaId,
                       productName = :productName,
                       productDescription = :productDescription,
                       productPrice = :productPrice,
                       productImgUrl = :productImgUrl,
                       productStatus = CASE WHEN productStock > 0 THEN 'ACT' ELSE 'INA' END
                   WHERE productId = :productId";
        $params = [
            "productId" => $productId,
            "categoriaId" => $categoriaId,
            "productName" => $productName,
            "productDescription" => $productDescription,
            "productPrice" => $productPrice,
            "productImgUrl" => $productImgUrl
        ];
        return self::executeNonQuery($sqlstr, $params);
    }

    public static function updateProductStock(int $productId, int $productStock)
    {
        $sqlstr = "UPDATE products
                   SET productStock = :productStock,
                       productStatus = CASE WHEN :productStock > 0 THEN 'ACT' ELSE 'INA' END
                   WHERE productId = :productId";
        $params = [
            "productId" => $productId,
            "productStock" => $productStock
        ];

        return self::executeNonQuery($sqlstr, $params);
    }

    public static function deleteProduct(int $productId)
    {
        $sqlstr = "DELETE FROM products WHERE productId = :productId";
        $params = ["productId" => $productId];
        return self::executeNonQuery($sqlstr, $params);
    }

    public static function getFeaturedProducts()
    {
        $sqlstr = "SELECT p.productId, p.productName, p.productDescription, p.productPrice, p.productImgUrl, p.productStatus FROM products p INNER JOIN highlights h ON p.productId = h.productId WHERE h.highlightStart <= NOW() AND h.highlightEnd >= NOW()";
        $params = [];
        return self::obtenerRegistros($sqlstr, $params);
    }

    public static function getNewProducts()
    {
        $sqlstr = "SELECT p.productId, p.productName, p.productDescription, p.productPrice, p.productImgUrl, p.productStatus FROM products p WHERE p.productStatus = 'ACT' ORDER BY p.productId DESC LIMIT 3";
        $params = [];
        return self::obtenerRegistros($sqlstr, $params);
    }

    public static function getDailyDeals()
    {
        $sqlstr = "SELECT p.productId, p.productName, p.productDescription, s.salePrice as productPrice, p.productImgUrl, p.productStatus FROM products p INNER JOIN sales s ON p.productId = s.productId WHERE s.saleStart <= NOW() AND s.saleEnd >= NOW()";
        $params = [];
        return self::obtenerRegistros($sqlstr, $params);
    }
}