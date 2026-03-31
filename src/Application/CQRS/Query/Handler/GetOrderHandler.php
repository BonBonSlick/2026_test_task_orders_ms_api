<?php

declare(strict_types=1);

namespace App\Application\CQRS\Query\Handler;

use App\Application\CQRS\Query\UseCase\GetOrder;
use App\Domain\Interface\IQueryHandler;
use App\Domain\Model\Order\IOrderRepository;
use App\Domain\Model\Order\Order;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetOrderHandler implements IQueryHandler
{

    public function __construct(private IOrderRepository $orderRepository) {}

    /**
     * @return Order[]
     */
    public function __invoke(GetOrder $query): array {
        $criteria = [];
        if (null !== $query->id) {
            $criteria['id'] = $query->id;
        }
        if (null !== $query->name) {
            $criteria['name'] = $query->name;
        }
        if (null !== $query->sku) {
            $criteria['sku'] = $query->sku;
        }

        return $this->orderRepository->findBy(
            criteria: $criteria,
        );
    }

}
