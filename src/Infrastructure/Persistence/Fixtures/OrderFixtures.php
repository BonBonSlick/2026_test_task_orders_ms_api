<?php

namespace App\Infrastructure\Persistence\Fixtures;

use App\Domain\Model\Order\IOrderFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Random\RandomException;
use Random\Randomizer;

use function random_int;

class OrderFixtures extends Fixture
{

    public function __construct(private readonly IOrderFactory $orderFactory) {}

    /**
     * @throws RandomException
     */
    public function load(ObjectManager $manager): void {
        $randomizer = new Randomizer();
        for ($iteration = 0; $iteration < 50; $iteration++) {
            $order = $this->orderFactory->create(
                productID   : '550e8400-e29b-41d4-a716-446655440000',
                quantity    : random_int(0, 100),
                customerName: 'Test Customer ' . random_int(0, 100),
            );
            if (0 === random_int(0, 1)) {
                $order->confirm();
            }
            $manager->persist(
                object: $order,
            );

            if (0 === ($iteration % 5)) {
                $manager->flush();
            }
        }

        $manager->flush();
    }

}
