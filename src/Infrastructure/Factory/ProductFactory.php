<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\Model\Product\IProductFactory;
use App\Domain\Model\Product\Product;
use Symfony\Component\Uid\Uuid;

final class ProductFactory implements IProductFactory
{

    public function create(string $uuid, string $sku, string $name, string $price, int $quantity): Product {
        $product = new Product(
            name    : $name,
            price   : $price,
            sku     : $sku,
            quantity: $quantity,
        );
        $product->setID(Uuid::fromString($uuid));

        return $product;
    }

}
