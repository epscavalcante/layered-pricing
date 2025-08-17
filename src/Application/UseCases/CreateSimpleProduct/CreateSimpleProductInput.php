<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateSimpleProduct;

readonly class CreateSimpleProductInput
{
    public function __construct(
        public string $name,
    ) {}
}