<?php

declare(strict_types=1);

namespace App\Application\CQRS\Command\Handler;

use App\Application\CQRS\Command\UseCase\CreateOrder;
use App\Domain\Exception\NotEnoughProductsException;
use App\Domain\Interface\ICommandHandler;
use App\Domain\Model\Order\IOrderFactory;
use App\Domain\Model\Order\IOrderRepository;
use App\Domain\Model\Product\IProductRepository;
use Shared\Contracts\DTO\OrderCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler(sign: true)]
final readonly class CreateOrderHandler implements ICommandHandler
{

    public function __construct(
        private IProductRepository  $productRepository,
        private IOrderRepository    $orderRepository,
        private IOrderFactory       $orderFactory,
        private MessageBusInterface $rmqBus,
    ) {}

    /**
     * @throws ExceptionInterface
     * @throws NotEnoughProductsException
     */
    public function __invoke(CreateOrder $command): void {
        $product = $this->productRepository->findById($command->productID);

        if ($product->quantity() < $command->quantity) {
            throw new NotEnoughProductsException();
        }
        $product->decreaseQuantity(quantity: $command->quantity);

        $order = $this->orderFactory->create(
            productID   : $command->productID,
            quantity    : $command->quantity,
            customerName: $command->customerName,
        );
        $this->orderRepository->save(order: $order);

        $this->rmqBus->dispatch(
            message: new OrderCreated(
                         productID: $command->productID,
                         orderID  : $order->id()->toRfc4122(),
                         quantity : $order->quantity(),
                     ),
        );
    }

}
