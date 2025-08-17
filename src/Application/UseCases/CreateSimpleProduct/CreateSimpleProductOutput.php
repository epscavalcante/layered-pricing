<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateSimpleProduct;

readonly class CreateSimpleProductOutput
{
    public function __construct(
        public string $productId,
    ) {}
}