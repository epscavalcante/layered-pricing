<?php

namespace Src\Domain\Services;

use Exception;
use Src\Domain\Entities\Layer;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;

class PriceCalculator
{
    /**
     * Calcula o valor final do Price para uma layer
     */
    public static function calculate(int $baseValue, Layer $layer): int
    {
        return match ($layer->getType()) {
            LayerType::NORMAL->value => $baseValue,
            LayerType::DISCOUNT->value => self::calculateDiscount($baseValue, $layer->getDiscountType(), $layer->getDiscountValue()),
            default => throw new Exception('Calculator unavailable')
        };
    }

    public static function calculateDiscount(int $baseValue, string $discountType, int $discountValue): int
    {
        return match ($discountType) {
            DiscountType::PERCENTAGE->value => self::calculatePercentageDiscount($baseValue, $discountValue),
            DiscountType::FIXED->value      => self::calculateFixedDiscount($baseValue, $discountValue),
            default      => throw new \DomainException('Tipo de desconto inválido')
        };
    }

    public static function calculatePercentageDiscount(int $value, int $percentage): int
    {
        return round($value * (1 - $percentage / 100), 0);
    }

    public static function calculateFixedDiscount(int $value, int $discount): int
    {
        if ($discount > $value) {
            // Regra de negócio Deveria dar erro ou aplicar 0 por padrão?
        }

        return $value - $discount;
    }
}
