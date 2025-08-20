<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateDiscountLayer;

readonly class CreateDiscountLayerOutput
{
    public function __construct(
        public string $layerId,
    ) {}
}
