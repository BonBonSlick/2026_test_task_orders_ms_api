<?php

declare(strict_types=1);

namespace App\Application\CQRS\Command\Handler;

use App\Domain\Interface\ICommandHandler;
use App\Domain\Model\Order\IOrderRepository;
use App\Domain\Model\Product\IProductFactory;
use App\Domain\Model\Product\IProductRepository;
use Shared\Contracts\DTO\Product\ProductQuantityDecreased;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsMessageHandler]
final readonly class ProductQuantityDecreasedHandler implements ICommandHandler
{

    public function __construct(
        private IProductRepository $productRepository,
        private IOrderRepository   $orderRepository,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ProductQuantityDecreased $dto): void {
        $product = $this->productRepository->findById($dto->productID);
        $product->setQuantity($dto->updatedQuantity);

        $this->productRepository->save($product);

        $order = $this->orderRepository->findById($dto->orderID);
        $order->confirm();

        $this->orderRepository->save($order);
    }

}
