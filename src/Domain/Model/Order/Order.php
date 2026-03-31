<?php

declare(strict_types=1);

namespace App\Domain\Model\Order;

use App\Infrastructure\Persistence\Repository\OrderRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[OA\Schema]
#[ORM\Table(name: 'orders')]
class  Order
{

    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME,)]
    private readonly Uuid              $id;

    #[ORM\Column(enumType: OrderStatusEnum::class)]
    private OrderStatusEnum            $status;

    #[ORM\Column]
    private readonly DateTimeImmutable $createdAt;

    public function __construct(
        #[ORM\Column]
        private readonly Uuid   $productID,

        #[ORM\Column]
        private readonly int    $quantity,

        #[ORM\Column(length: 255)]
        private readonly string $customerName,
    ) {
        $this->id        = Uuid::v4();
        $this->status    = OrderStatusEnum::PROCESSING;
        $this->createdAt = new DateTimeImmutable();
    }

    public function createdAt(): DateTimeImmutable {
        return $this->createdAt;
    }

    public function productID(): Uuid {
        return $this->productID;
    }

    public function quantity(): int {
        return $this->quantity;
    }

    public function id(): Uuid {
        return $this->id;
    }

    public function status(): OrderStatusEnum {
        return $this->status;
    }

    public function confirm(): void {
        $this->status = OrderStatusEnum::CONFIRMED;
    }

    public function outOfStock(): void {
        $this->status = OrderStatusEnum::OUT_OF_STOCK;
    }

    public function customerName(): string {
        return $this->customerName;
    }

}
