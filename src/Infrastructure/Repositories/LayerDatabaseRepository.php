<?php

namespace Src\Infrastructure\Repositories;

use PDO;
use PDOException;
use Src\Domain\Entities\Layer;
use Src\Domain\Enums\LayerType;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\ValueObjects\LayerId;

class LayerDatabaseRepository implements LayerRepository
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

    public function findById(LayerId $layerId): ?Layer
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM app.layers WHERE id = :layerId LIMIT 1');
        $stmt->execute(['layerId' => $layerId->getValue()]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Layer::restore(
            id: $row['id'],
            code: $row['code'],
            type: $row['type'],
            parentId: $row['layer_id'],
            discountType: $row['discount_type'],
            discountValue: $row['discount_value'],
        );
    }

    public function findByCode(string $code): ?Layer
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM app.layers WHERE code = :code LIMIT 1');
        $stmt->execute(['code' => $code]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Layer::restore(
            id: $row['id'],
            code: $row['code'],
            type: $row['type'],
            parentId: $row['layer_id'],
            discountType: $row['discount_type'],
            discountValue: $row['discount_value'],
        );
    }

    public function findByIdAndType(LayerId $layerId, LayerType $type): ?Layer
    {
        $stmt = $this->databaseConnection->prepare('SELECT * FROM app.layers WHERE id = :layerId AND type = :type LIMIT 1');
        $stmt->execute(['layerId' => $layerId->getValue(), 'type' => $type->value]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return Layer::restore(
            id: $row['id'],
            code: $row['code'],
            type: $row['type'],
            parentId: $row['layer_id'],
            discountType: $row['discount_type'],
            discountValue: $row['discount_value'],
        );
    }

    public function save(Layer $layer): void
    {
        $query = "INSERT INTO layers (id, code, type, layer_id, discount_value, discount_type) VALUES (:id, :code, :type, :layer_id, :discount_value, :discount_type)";
        $stmt = $this->databaseConnection->prepare($query);

        $stmt->bindValue(':id', $layer->getId());
        $stmt->bindValue(':code', $layer->getCode());
        $stmt->bindValue(':type', $layer->getType());
        $stmt->bindValue(':layer_id', $layer->getParentId());
        $stmt->bindValue(':discount_type', $layer->getDiscountType());
        $stmt->bindValue(':discount_value', $layer->getDiscountValue());
        $stmt->execute();
    }
}
