<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateSimpleLayer;

readonly class CreateSimpleLayerOutput
{
    public function __construct(
        public string $layerId,
    ) {}
}
