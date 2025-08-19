<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

use Exception;
use Src\Domain\Enums\DiscountType;

class FixedDiscountRule extends DiscountRule
{
    protected function validate(int $value): void
    {
        if ($value <= 0) {
            throw new Exception('Valor do desconto fixo inválido');
        }
    }

    public function getType(): DiscountType
    {
        return DiscountType::FIXED;
    }
}
