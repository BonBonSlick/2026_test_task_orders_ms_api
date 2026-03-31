<?php

declare(strict_types=1);

namespace App\Domain\Model\Order;

interface IOrderRepository
{

    public function findById(string $uuid): ?Order;

    public function findBy(
        array      $criteria,
        array|null $orderBy = null,
        int|null   $limit = null,
        int|null   $offset = null,
    ): array;

}
