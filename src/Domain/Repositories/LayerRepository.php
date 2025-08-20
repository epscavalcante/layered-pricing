<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\Layer;
use Src\Domain\Enums\LayerType;
use Src\Domain\ValueObjects\LayerId;

interface LayerRepository
{
    public function findById(LayerId $layerId): ?Layer;

    public function findByIdAndType(LayerId $layerId, LayerType $type): ?Layer;

    public function findByCode(string $code): ?Layer;

    public function save(Layer $layer): void;
}
