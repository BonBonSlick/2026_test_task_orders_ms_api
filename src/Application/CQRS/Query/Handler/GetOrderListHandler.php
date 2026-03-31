<?php

declare(strict_types=1);

namespace App\Application\CQRS\Query\Handler;

use App\Application\CQRS\Query\UseCase\GetOrderList;
use App\Domain\Interface\IQueryHandler;
use App\Domain\Model\Order\IOrderRepository;
use App\Domain\Model\Order\Order;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetOrderListHandler implements IQueryHandler
{

    public function __construct(private IOrderRepository $orderRepository) {}

    /**
     * @return Order[]
     */
    public function __invoke(GetOrderList $query): array {
        return $this->orderRepository->findAllOrders();
    }

}
