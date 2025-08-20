<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateDiscountPrice;

readonly class CreateDiscountPriceInput
{
    public function __construct(
        public string $layerId,
        public string $productId,
    ) {}
}