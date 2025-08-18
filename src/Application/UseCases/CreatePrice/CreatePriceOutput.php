<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreatePrice;

readonly class CreatePriceOutput
{
    public function __construct(
        public string $priceId,
    ) {}
}