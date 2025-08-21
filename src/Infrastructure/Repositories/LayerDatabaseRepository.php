<?php

namespace Src\Infrastructure\Repositories;

use PDO;
use Src\Domain\Entities\Layer;
use Src\Domain\Enums\LayerType;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\ValueObjects\LayerId;

class LayerDatabaseRepository implements LayerRepository
{
    public function __construct(
        private readonly PDO $databaseConnection
    ) {}

    public function findById(LayerId $layerId): ?Layer
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM layers WHERE id = :layerId LIMIT 1');
        $stmt->execute(['layerId' => $layerId->getValue()]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Layer::restore(
            id: $row['id'],
            code: $row['code'],
            type: $row['type'],
            parentId: $row['parent_id'],
            discountType: $row['discount_type'],
            discountValue: $row['discount_value'],
        );
    }

    public function findByCode(string $code): ?Layer
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM layers WHERE code = :code LIMIT 1');
        $stmt->execute(['code' => $code]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Layer::restore(
            id: $row['id'],
            code: $row['code'],
            type: $row['type'],
            parentId: $row['parent_id'],
            discountType: $row['discount_type'],
            discountValue: $row['discount_value'],
        );
    }

    public function findByIdAndType(LayerId $layerId, LayerType $type): ?Layer
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM layers WHERE id = :layerId AND type = :type LIMIT 1');
        $stmt->execute(['layerId' => $layerId->getValue(), 'type' => $type->value]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Layer::restore(
            id: $row['id'],
            code: $row['code'],
            type: $row['type'],
            parentId: $row['parent_id'],
            discountType: $row['discount_type'],
            discountValue: $row['discount_value'],
        );
    }

    public function save(Layer $layer): void
    {
        $query = "INSERT INTO layers (id, code, type, parent_id, discount_value, discount_type) VALUES (:id, :code, :type, :parent_id, :discount_value, :discount_type)";
        $stmt = $this->databaseConnection->prepare($query);

        $stmt->bindValue(':id', $layer->getId());
        $stmt->bindValue(':code', $layer->getCode());
        $stmt->bindValue(':type', $layer->getType());
        $stmt->bindValue(':parent_id', $layer->getParentId());
        $stmt->bindValue(':discount_type', $layer->getDiscountType());
        $stmt->bindValue(':discount_value', $layer->getDiscountValue());
        $stmt->execute();
    }
}
