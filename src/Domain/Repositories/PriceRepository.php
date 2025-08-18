<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\Price;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;

interface PriceRepository
{
    public function existsByLayerIdAndProductId(LayerId $layerId, ProductId $productId): bool;

    public function save(Price $price): void;

}