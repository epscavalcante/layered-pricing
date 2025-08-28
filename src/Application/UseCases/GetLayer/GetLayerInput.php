<?php

declare(strict_types=1);

namespace Src\Application\UseCases\GetLayer;

readonly class GetLayerInput
{
    public function __construct(
        public string $layerId,
    ) {}
}