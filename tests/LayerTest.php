<?php

use Src\Domain\Entities\Layer;
use Src\Domain\ValueObjects\LayerId;

test('Deve criar uma layer', function () {
    $layer = Layer::create('layer');
    expect($layer)->toBeInstanceOf(layer::class);
    expect($layer->getId())->ToBeString();
    expect($layer->code)->ToBe('layer');
});

test('Deve restaurar uma layer', function () {
    $layerId = layerId::create();

    $layer = Layer::restore($layerId->getValue(), 'layer');
    expect($layer)->toBeInstanceOf(layer::class);
    expect($layer->getId())->ToBe($layerId->getValue());
    expect($layer->code)->ToBe('layer');
});

