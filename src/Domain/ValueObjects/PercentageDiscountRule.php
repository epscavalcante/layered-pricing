<?php

declare(strict_types=1);

namespace Src\Domain\ValueObjects;

use Exception;
use Src\Domain\Enums\DiscountType;

class PercentageDiscountRule extends DiscountRule
{
    protected function validate(int $value): void
    {
        if ($value > 100 || $value <= 0) {
            throw new Exception('Porcentagem de desconto inválida');
        }
    }

    public function getType(): DiscountType
    {
        return DiscountType::PERCENTAGE;
    }
}
