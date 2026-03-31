<?php

declare(strict_types=1);

namespace App\Application\CQRS\Command\Handler;

use App\Domain\Interface\ICommandHandler;
use App\Domain\Model\Order\IOrderRepository;
use App\Domain\Model\Product\IProductRepository;
use Shared\Contracts\DTO\Product\ProductOutOfStock;
use Shared\Contracts\DTO\Product\ProductQuantityDecreased;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsMessageHandler]
final readonly class ProductOutOfStockHandler implements ICommandHandler
{

    public function __construct(
        private IProductRepository $productRepository,
        private IOrderRepository   $orderRepository,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ProductOutOfStock $dto): void {
        $product = $this->productRepository->findById($dto->productID);
        $product->setQuantity(0);

        $this->productRepository->save($product);

        $order = $this->orderRepository->findById($dto->orderID);
        $order->outOfStock();

        $this->orderRepository->save($order);
    }

}
