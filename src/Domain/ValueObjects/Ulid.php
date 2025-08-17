<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

use Exception;
use Ramsey\Identifier\Ulid\UlidFactory;
use Stringable;

class Ulid implements Stringable
{
    private string $value;

    private function __construct(string $value)
    {
        if (!$this->validate($value)) {
            throw new Exception('Invalid value');
        }
        $this->value = $value;
    }

    public static function create():static
    {
        $id = (new UlidFactory())->create();

        return new static((string) $id);
    }

    public static function restore(string $value): static
    {
        return new static((string) $value);
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    private function validate($value): bool
    {
        try {
            (new UlidFactory)->createFromString($value);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }
}
