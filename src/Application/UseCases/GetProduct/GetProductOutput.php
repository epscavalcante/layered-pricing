<?php

declare(strict_types=1);

namespace Src\Application\UseCases\GetProduct;

readonly class GetProductOutput
{
    public function __construct(
        public string $productId,
        public string $name,
    ) {}
}