<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Repository;

use App\Domain\Model\Order\IOrderRepository;
use App\Domain\Model\Order\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Order>
 */
final class OrderRepository extends ServiceEntityRepository implements IOrderRepository
{

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $order): void {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }

    public function findById(string $id): ?Order {
        return $this->find($id);
    }

    /**
     * @return Order[]
     */
    public function findAllOrders(): array {
        return $this->findAll();
    }

    //    /**
    //     * @return Order[] Returns an array of Order objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
