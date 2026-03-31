<?php

declare(strict_types=1);

namespace App\Application\CQRS\Command\Handler;

use App\Application\CQRS\Command\UseCase\CreateOrder;
use App\Domain\Interface\ICommandHandler;
use App\Domain\Model\Order\IOrderFactory;
use App\Domain\Model\Order\IOrderRepository;
use Shared\Contracts\DTO\OrderCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler(sign: true)]
final readonly class CreateOrderHandler implements ICommandHandler
{

    public function __construct(
        private IOrderRepository    $orderRepository,
        private IOrderFactory       $orderFactory,
        private MessageBusInterface $rmqBus,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(CreateOrder $command): void {
        $order = $this->orderFactory->create(
            productID   : $command->productID,
            quantity    : $command->quantity,
            customerName: $command->customerName,
        );
        $this->orderRepository->save(order: $order);

        $this->rmqBus->dispatch(
            message: new OrderCreated(
                         productID: $order->id()->toRfc4122(),
                         quantity : $order->quantity(),
                     ),
        );
    }

}
