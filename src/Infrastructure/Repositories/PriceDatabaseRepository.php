<?php

namespace Src\Infrastructure\Repositories;

use PDO;
use Src\Domain\Entities\Price;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;

class PriceDatabaseRepository implements PriceRepository
{
    public function __construct(private readonly PDO $databaseConnection) {}

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
        $query = 'SELECT * FROM prices WHERE layer_id = :layer_id AND product_id IN (' . implode(', ', $placeholders) . ')';
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
                value: $row['value'],
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
        $stmt = $this->databaseConnection->prepare('SELECT * FROM prices WHERE layer_id = :layer_id AND product_id = :product_id LIMIT 1');
        $stmt->execute(['layer_id' => $layerId->getValue(), 'product_id' => $productId->getValue()]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Price::restore(
            id: $row['id'],
            layerId: $row['layer_id'],
            productId: $row['product_id'],
            value: $row['value'],
        );
    }

    public function save(Price $price): void
    {
        $stmt = $this->databaseConnection->prepare(
            'INSERT INTO prices (id, layer_id, product_id, value)
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
