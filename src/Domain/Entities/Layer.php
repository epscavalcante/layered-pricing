<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

use Src\Domain\ValueObjects\LayerId;

class Layer
{
    private function __construct(
        private LayerId $id,
        public string $code,
    ) {}

    public static function create(string $code)
    {
        return new self(
            id: LayerId::create(),
            code: $code,
        );
    }

    public static function restore(string $id, string $code)
    {
        return new self(
            id: LayerId::restore($id),
            code: $code,
        );
    }

    public function getId(): string
    {
        return $this->id->getValue();
    }
}
