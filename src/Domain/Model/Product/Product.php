<?php

declare(strict_types=1);

namespace App\Domain\Model\Product;

use App\Infrastructure\Persistence\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Attributes as OA;
use Shared\Contracts\Model\AbstractProduct;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[OA\Schema]
#[ORM\Table(name: 'products')]
class Product extends AbstractProduct
{

    public function setQuantity(int $quantity): void {
        $this->quantity = $quantity;
    }

    public function setID(Uuid $uuid): void {
        $this->id = $uuid;
    }
}
