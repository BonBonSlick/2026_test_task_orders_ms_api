<?php

declare(strict_types=1);

namespace App\Infrastructure\Validation;

use OpenApi\Attributes as OA;
use Symfony\Component\Validator\Constraints as Assert;

#[OA\Schema]
final readonly class CreateOrderDTO
{

    public function __construct(
        #[OA\Property(description: 'Customer name.', maxLength: 255, minLength: 2,)]
        #[Assert\Length(
            min: 2,
            max: 255,
        )]
        public string $customerName,

        #[Assert\Uuid]
        public string $productID,

        #[OA\Property(description: 'Total orders in stock.', minimum: 0,)]
        #[Assert\PositiveOrZero]
        public int    $quantity,
    ) {}

}
