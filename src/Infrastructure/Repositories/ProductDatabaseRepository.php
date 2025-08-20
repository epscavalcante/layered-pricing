<?php

namespace Src\Infrastructure\Repositories;

use PDO;
use PDOException;
use Src\Domain\Entities\Product;
use Src\Domain\Repositories\ProductRepository;
use Src\Domain\ValueObjects\ProductId;

class ProductDatabaseRepository implements ProductRepository
{
    private PDO $databaseConnection;

    public function __construct()
    {
        try {
            $this->databaseConnection = new PDO(
                dsn: "mysql:host=mysql;dbname=app;charset=utf8mb4",
                username: 'root',
                password: 'root',
                options: [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em erros
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => false, // Desabilita conexão persistente,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
        } catch (PDOException $e) {
            // Trate erro de conexão de forma adequada (log, retry, etc)
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    public function findById(ProductId $productId): ?Product
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM app.products WHERE id = :productId');
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
            VALUES (:productId, :name)
            ON DUPLICATE KEY UPDATE
                name = VALUES(name)'
        );

        $stmt->execute([
            'productId' => $product->getId(),
            'name' => $product->getName(),
        ]);
    }
}
