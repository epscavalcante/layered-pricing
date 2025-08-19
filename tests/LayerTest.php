<?php

use Src\Domain\Entities\Layer;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;
use Src\Domain\ValueObjects\LayerId;

test('Deve criar uma layer normal', function () {
    $layer = Layer::create('layer');
    expect($layer)->toBeInstanceOf(Layer::class);
    expect($layer->getId())->ToBeString();
    expect($layer->getCode())->ToBe('layer');
    expect($layer->getType())->ToBe(LayerType::NORMAL->value);
});

test('Deve restaurar uma layer normal', function () {
    $layerId = layerId::create();

    $layer = Layer::restore($layerId->getValue(), LayerType::NORMAL->value, 'layer');
    expect($layer)->toBeInstanceOf(layer::class);
    expect($layer->getId())->ToBe($layerId->getValue());
    expect($layer->getCode())->ToBe('layer');
    expect($layer->getType())->ToBe(LayerType::NORMAL->value);
});

test('Deve criar uma layer de desconto percentual', function () {
    $base = Layer::create('base');
    $percentualDiscountLayer = Layer::createDiscountLayer(
        baseLayerId: LayerId::restore($base->getId()),
        code: 'layer',
        discountType: DiscountType::PERCENTAGE->value,
        discountValue: 15
    );
    expect($percentualDiscountLayer)->toBeInstanceOf(Layer::class);
    expect($percentualDiscountLayer->getId())->ToBeString();
    expect($percentualDiscountLayer->getCode())->ToBe('layer');
    expect($percentualDiscountLayer->getType())->ToBe(LayerType::DISCOUNT->value);
    expect($percentualDiscountLayer->getDiscountType())->ToBe(DiscountType::PERCENTAGE->value);
    expect($percentualDiscountLayer->getDiscountValue())->ToBe(15);
});

test('Deve criar uma layer de desconto fixo', function () {
    $base = Layer::create('base');
    $percentualDiscountLayer = Layer::createDiscountLayer(
        baseLayerId: LayerId::restore($base->getId()),
        code: 'layer',
        discountType: DiscountType::FIXED->value,
        discountValue: 55
    );
    expect($percentualDiscountLayer)->toBeInstanceOf(Layer::class);
    expect($percentualDiscountLayer->getId())->ToBeString();
    expect($percentualDiscountLayer->getCode())->ToBe('layer');
    expect($percentualDiscountLayer->getType())->ToBe(LayerType::DISCOUNT->value);
    expect($percentualDiscountLayer->getDiscountType())->ToBe(DiscountType::FIXED->value);
    expect($percentualDiscountLayer->getDiscountValue())->ToBe(55);
});
