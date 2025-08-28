<?php

declare(strict_types=1);

namespace Src\Application\UseCases\GetPrice;

readonly class GetPriceInput
{
    public function __construct(
        public string $priceId,
    ) {}
}