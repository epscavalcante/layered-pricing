<?php

use Src\Domain\Entities\Layer;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;
use Src\Domain\Services\PriceCalculator;
use Src\Domain\ValueObjects\LayerId;

test('Deve cacular o preço de uma layer simples', function (int $baseValue, string $layerType, int $expectedFinalValue, ?string $discountType = null, ?int $discountValue = null) {
    
    $parentId = $discountType && $discountValue 
        ? LayerId::create() :  null;
    
    $layer = Layer::create(
        code: uniqid('LAYER_'),
        type: $layerType,
        parentId: $parentId,
        discountType: $discountType,
        discountValue: $discountValue,
    );
    expect($layerType)->toBe($layer->getType());
    $finalValue = PriceCalculator::calculate($baseValue, $layer);
    expect($finalValue)->toBe($expectedFinalValue);
})->with([
    // verificar a questão do valor ser < que 0 deveria ser um VO
    [1000, LayerType::NORMAL->value, 1000],
    [1000, LayerType::DISCOUNT->value, 900, DiscountType::PERCENTAGE->value, 10],
    [5000, LayerType::DISCOUNT->value, 3750, DiscountType::PERCENTAGE->value, 25],
    [5000, LayerType::DISCOUNT->value, 3750, DiscountType::PERCENTAGE->value, 25],
    [1000, LayerType::DISCOUNT->value, 670, DiscountType::PERCENTAGE->value, 33],
    [2550, LayerType::DISCOUNT->value, 1708, DiscountType::PERCENTAGE->value, 33],
]);
