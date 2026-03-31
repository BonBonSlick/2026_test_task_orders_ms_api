<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\Model\Order\IOrderFactory;
use App\Domain\Model\Order\Order;
use Symfony\Component\Uid\Uuid;

final class OrderFactory implements IOrderFactory
{

    public function create(string $productID, int $quantity, string $customerName): Order {
        return new Order(
            productID   : Uuid::fromString($productID),
            quantity    : $quantity,
            customerName: $customerName,
        );
    }

}
