<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\Price;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;

interface PriceRepository
{
    public function existsByLayerIdAndProductId(LayerId $layerId, ProductId $productId): bool;

    /**
     * @param LayerId $layerId
     * @param ProductId[] $productIds
     * @return Price[]
     */
    public function findByLayerIdAndProductIds(LayerId $layerId, ?array $productIds = []): array;

    /**
     * @param LayerId $layerId
     * @param ProductId $productId
     * @return ?Price
     */
    public function findByLayerIdAndProductId(LayerId $layerId, ProductId $productId): ?Price;

    public function save(Price $price): void;
}