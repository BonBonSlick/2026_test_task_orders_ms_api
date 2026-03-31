<?php

declare(strict_types=1);

namespace App\Domain\Model\Order;

interface IOrderFactory
{

    public function create(string $productID, int $quantity, string $customerName): Order;

}
