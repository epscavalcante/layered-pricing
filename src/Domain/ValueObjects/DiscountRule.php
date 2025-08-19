<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

use Src\Domain\Enums\DiscountType;

abstract class DiscountRule
{
    private int $value;

    public function __construct(int $value)
    {
        $this->validate($value);
        $this->value = $value;
    }

    abstract protected function validate(int $value): void;

    abstract public function getType(): DiscountType;

    public function getValue(): int
    {
        return $this->value;
    }
}
