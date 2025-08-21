<?php

namespace Src\Infrastructure\Repositories;

use PDO;
use PDOException;
use Src\Domain\Entities\Product;
use Src\Domain\Repositories\ProductRepository;
use Src\Domain\ValueObjects\ProductId;

class ProductDatabaseRepository implements ProductRepository
{
    public function __construct(
        private readonly PDO $databaseConnection
    ) {}

    public function findById(ProductId $productId): ?Product
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM products WHERE id = :productId');
        $stmt->execute(['productId' => $productId->getValue()]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Product::restore(
            id: $row['id'],
            name: $row['name'],
        );
    }

    public function save(Product $product): void
    {
        $stmt = $this->databaseConnection->prepare(
            'INSERT INTO products (id, name)
            VALUES (:productId, :name)'
        );

        $stmt->execute([
            'productId' => $product->getId(),
            'name' => $product->getName(),
        ]);
    }
}
