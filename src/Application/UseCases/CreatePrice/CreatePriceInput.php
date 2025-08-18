<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreatePrice;

readonly class CreatePriceInput
{
    public function __construct(
        public string $layerId,
        public string $productId,
        public int $value,
    ) {}
}