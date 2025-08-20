<?php

namespace Src\Infrastructure\Repositories;

use PDO;
use PDOException;
use Src\Domain\Entities\Price;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\PriceId;
use Src\Domain\ValueObjects\ProductId;

class PriceDatabaseRepository implements PriceRepository
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

    public function existsByLayerIdAndProductId(LayerId $layerId, ProductId $productId): bool
    {
        $price = $this->findByLayerIdAndProductId(
            layerId: $layerId,
            productId: $productId
        );
        return boolval($price);
    }

    /**
     * @param LayerId $layerId
     * @param ProductId[] $productIds
     * @return Price[]
     */
    public function findByLayerIdAndProductIds(LayerId $layerId, array $productIds): array
    {
        $placeholders = [];
        foreach ($productIds as $k => $productId) {
            $placeholders[] = ":id{$k}";
        }
        $query = 'SELECT * FROM app.prices WHERE layer_id = :layer_id AND product_id IN (' . implode(', ', $placeholders) . ')';
        $stmt = $this->databaseConnection->prepare($query);
        $stmt->bindValue(':layer_id', $layerId->getValue());
        foreach ($productIds as $key => $productId) {
            $stmt->bindValue(":id{$key}", $productId->getValue(), PDO::PARAM_STR);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll();

        if (count($rows) === 0) {
            return [];
        }

        return array_map(
            callback: fn($row) => Price::restore(
                id: $row['id'],
                layerId: $row['layer_id'],
                productId: $row['product_id'],
                value: $row['value_cents'],
            ),
            array: $rows
        );
    }

    /**
     * @param LayerId $layerId
     * @param ProductId $productId
     * @return ?Price
     */
    public function findByLayerIdAndProductId(LayerId $layerId, ProductId $productId): ?Price
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM app.prices WHERE layer_id = :layer_id AND product_id = :product_id LIMIT 1');
        $stmt->execute(['layer_id' => $layerId->getValue(), 'product_id' => $productId->getValue()]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Price::restore(
            id: $row['id'],
            layerId: $row['layer_id'],
            productId: $row['product_id'],
            value: $row['value_cents'],
        );
    }

    public function save(Price $price): void
    {
        $stmt = $this->databaseConnection->prepare(
            'INSERT INTO app.prices (id, layer_id, product_id, value_cents)
            VALUES (:price_id, :layer_id, :product_id, :value)'
        );

        $stmt->execute([
            'price_id' => $price->getId(),
            'layer_id' => $price->getLayerId(),
            'product_id' => $price->getProductId(),
            'value' => $price->getValue(),
        ]);
    }
}
