<?php

declare(strict_types=1);

namespace App\Infrastructure\ConsoleCommand;

use App\Domain\Model\Product\Product;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Uid\Uuid;

#[AsCommand(
    name       : 'app:sync-products',
    description: 'Sync products from products microservice database to orders database',
)]
class SyncProductsCommand extends Command
{

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly Connection             $productsConnection,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int {
        $io = new SymfonyStyle($input, $output);

        $io->title('Syncing Products');

        $em          = $this->entityManager;
        $productRepo = $em->getRepository(Product::class);

        $batchSize = 100;
        $offset    = 0;
        $synced    = 0;
        $updated   = 0;
        $errors    = 0;

        while (true) {
            // Fetch products in batches
            $productsData = $this->productsConnection->fetchAllAssociative(
                query : 'SELECT id, name, price, sku, quantity FROM products LIMIT ? OFFSET ?',
                params: [$batchSize, $offset],
            );

            if (true === empty($productsData)) {
                break;
            }

            foreach ($productsData as $data) {
                try {
                    $existingProduct = $productRepo->findOneBy(['sku' => $data['sku']]);
                    $quantity        = $data['quantity'];

                    if ($existingProduct) {
                        $existingProduct->setQuantity(quantity: $quantity);

                        $updated++;
                    } else {
                        $product = new Product(
                            name    : $data['name'],
                            price   : $data['price'],
                            sku     : $data['sku'],
                            quantity: $quantity,
                        );
                        $product->setID(uuid: Uuid::fromString(uuid: $data['id']));

                        $em->persist(object: $product);

                        $synced++;
                    }
                } catch (Exception $e) {
                    $io->error('Error syncing product SKU ' . $data['sku'] . ': ' . $e->getMessage());
                    $errors++;
                }
            }

            // Flush after each batch
            $em->flush();
            $em->clear(); // Clear entity manager to free memory

            $offset += $batchSize;

            $io->text("Processed batch ending at offset $offset. Synced: $synced, Updated: $updated, Errors: $errors");
        }

        $io->success("Total synced $synced new products, updated $updated products. Errors: $errors");

        return Command::SUCCESS;
    }

}
