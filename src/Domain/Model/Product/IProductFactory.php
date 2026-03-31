<?php

declare(strict_types=1);

namespace App\Domain\Model\Product;

interface IProductFactory
{

    public function create(string $uuid, string $sku, string $name, string $price, int $quantity): Product;

}
