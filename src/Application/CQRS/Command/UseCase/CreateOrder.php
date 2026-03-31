<?php

declare(strict_types=1);

namespace App\Application\CQRS\Command\UseCase;

final readonly class CreateOrder
{

    public function __construct(
        public string $productID,
        public string $customerName,
        public int    $quantity,
    ) {}

}
