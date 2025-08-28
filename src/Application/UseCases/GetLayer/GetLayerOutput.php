<?php

declare(strict_types=1);

namespace Src\Application\UseCases\GetLayer;

readonly class GetLayerOutput
{
    public function __construct(
        public string $layerId,
        public string $code,
        public string $type,
        public ?string $parentId = null,
        public ?string $discountType = null,
        public ?int $discountValue = null,
    ) {}
}