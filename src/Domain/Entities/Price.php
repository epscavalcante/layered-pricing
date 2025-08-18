<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\PriceId;
use Src\Domain\ValueObjects\ProductId;

class Price
{
    private function __construct(
        private readonly PriceId $id,
        private readonly LayerId $layerId,
        private readonly ProductId $productId,
        private readonly int $value,
    ) {}

    public static function create(string $layerId, string $productId, int $value)
    {
        $id = PriceId::create();
        $layerId = LayerId::restore($layerId);
        $productId = ProductId::restore($productId);
        return new self(
            id: $id,
            layerId: $layerId,
            productId: $productId,
            value: $value
        );
    }

    public static function restore(string $id, string $layerId, string $productId, int $value)
    {
        return new self(
            id: PriceId::restore($id),
            layerId: LayerId::restore($layerId),
            productId: ProductId::restore($productId),
            value: $value,
        );
    }

    public function getId(): string
    {
        return $this->id->getValue();
    }

    public function getLayerId(): string
    {
        return $this->layerId->getValue();
    }

    public function getProductId(): string
    {
        return $this->productId->getValue();
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
