<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\Layer;
use Src\Domain\ValueObjects\LayerId;

interface LayerRepository
{
    public function findByCode(string $code): ?Layer;

    public function save(Layer $layer): void;
}