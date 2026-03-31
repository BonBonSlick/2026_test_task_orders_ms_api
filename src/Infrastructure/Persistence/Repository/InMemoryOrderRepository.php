<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Order\IOrderRepository;
use App\Domain\Model\Order\Order;

/**
 * Used for testing to avoid database interactions.
 */
final class InMemoryOrderRepository implements IOrderRepository
{

    private array $orders = [];

    public function findById(string $uuid): ?Order {
        return $this->orders[$uuid] ?? null;
    }

    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array {
        $filtered = array_filter(
            array   : $this->orders,
            callback: static function (Order $order) use ($criteria): bool {
                foreach ($criteria as $getterName => $value) {
                    $actualValue =
                        method_exists($order, $getterName)
                            ? $order->$getterName()
                            : ($order->$getterName ?? null);

                    if ($actualValue !== $value) {
                        return false;
                    }
                }

                return true;
            },
        );

        return array_values($filtered);
    }

    public function findAllOrders(): array {
        return $this->orders;
    }

    public function save(Order $order): void {
        $this->orders[$order->id()->toRfc4122()] = $order;
    }
}
