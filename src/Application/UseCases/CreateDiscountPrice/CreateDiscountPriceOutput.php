<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateDiscountPrice;

readonly class CreateDiscountPriceOutput
{
    public function __construct(
        public string $priceId,
    ) {}
}