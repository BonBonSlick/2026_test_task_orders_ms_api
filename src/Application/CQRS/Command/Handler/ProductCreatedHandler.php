<?php

declare(strict_types=1);

namespace App\Application\CQRS\Command\Handler;

use App\Domain\Interface\ICommandHandler;
use App\Domain\Model\Product\IProductFactory;
use App\Domain\Model\Product\IProductRepository;
use Shared\Contracts\DTO\ProductCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsMessageHandler]
final readonly class ProductCreatedHandler implements ICommandHandler
{

    public function __construct(
        private IProductRepository $productRepository,
        private IProductFactory    $productFactory,
    ) {}

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ProductCreated $dto): void {
        $this->productRepository->save(
            product: $this->productFactory->create(
                       uuid    : $dto->id,
                       sku     : $dto->sku,
                       name    : $dto->name,
                       price   : $dto->price,
                       quantity: $dto->quantity,
                   ),
        );
    }

}
