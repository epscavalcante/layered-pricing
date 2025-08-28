<?php

declare(strict_types=1);

namespace Src\Application\UseCases\GetPrice;

readonly class GetPriceOutput
{
    public function __construct(
        public string $priceId,
        public string $layerId,
        public string $productId,
        public int $value,
    ) {}
}