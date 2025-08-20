<?php

use Src\Domain\Entities\Layer;
use Src\Domain\Entities\Product;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;
use Src\Infrastructure\Repositories\LayerDatabaseRepository;
use Src\Infrastructure\Repositories\ProductDatabaseRepository;

test('Deve salvar uma layer normal', function () {
    $layer = Layer::create(
        code: uniqid('LAYER_', true),
    );

    $repository = new LayerDatabaseRepository;
    $repository->save($layer);

    $layerExists = $repository->findById(LayerId::restore($layer->getId()));
    expect($layerExists)->toBeInstanceOf(Layer::class);
    expect($layerExists->getId())->toBe($layer->getId());
    expect($layerExists->getType())->toBe(LayerType::NORMAL->value);
    expect($layerExists->getParentId())->toBeNull();
    expect($layerExists->getDiscountType())->toBeNull();
    expect($layerExists->getDiscountValue())->toBeNull();
    expect($layerExists->isDiscount())->toBeFalsy();
});

test('Deve salvar uma layer de desconto percentual', function () {
    $base = Layer::create(
        code: uniqid('LAYER_', true),
    );
    $percentageDiscount = Layer::createDiscountLayer(
        baseLayerId: $base->getId(),
        code: uniqid('LAYER_', true),
        discountType: DiscountType::PERCENTAGE->value,
        discountValue: 17,
    );

    $repository = new LayerDatabaseRepository;
    $repository->save($base);
    $repository->save($percentageDiscount);

    $percentageDiscountLayerExists = $repository->findById(LayerId::restore($percentageDiscount->getId()));
    expect($percentageDiscountLayerExists)->toBeInstanceOf(Layer::class);
    expect($percentageDiscountLayerExists->getId())->toBe($percentageDiscount->getId());
    expect($percentageDiscountLayerExists->getType())->toBe(LayerType::DISCOUNT->value);
    expect($percentageDiscountLayerExists->getParentId())->toBe($base->getId());
    expect($percentageDiscountLayerExists->getDiscountType())->toBe(DiscountType::PERCENTAGE->value);
    expect($percentageDiscountLayerExists->getDiscountValue())->toBe(17);
    expect($percentageDiscountLayerExists->isDiscount())->toBeTruthy();
});


test('Deve salvar uma layer de desconto fixo', function () {
    $base = Layer::create(
        code: uniqid('LAYER_', true),
    );
    $percentageDiscount = Layer::createDiscountLayer(
        baseLayerId: $base->getId(),
        code: uniqid('LAYER_', true),
        discountType: DiscountType::FIXED->value,
        discountValue: 5,
    );

    $repository = new LayerDatabaseRepository;
    $repository->save($base);
    $repository->save($percentageDiscount);

    $percentageDiscountLayerExists = $repository->findById(LayerId::restore($percentageDiscount->getId()));
    expect($percentageDiscountLayerExists)->toBeInstanceOf(Layer::class);
    expect($percentageDiscountLayerExists->getId())->toBe($percentageDiscount->getId());
    expect($percentageDiscountLayerExists->getType())->toBe(LayerType::DISCOUNT->value);
    expect($percentageDiscountLayerExists->getParentId())->toBe($base->getId());
    expect($percentageDiscountLayerExists->getDiscountType())->toBe(DiscountType::FIXED->value);
    expect($percentageDiscountLayerExists->getDiscountValue())->toBe(5);
    expect($percentageDiscountLayerExists->isDiscount())->toBeTruthy();
});

test('Deve encontar uma layer pelo ID', function () {
    $layer = Layer::create(
        code: uniqid('LAYER_'),
    );
    $repository = new LayerDatabaseRepository;
    $repository->save($layer);

    $layerFound = $repository->findById(LayerId::restore($layer->getId()));
    expect($layerFound)->toBeInstanceOf(Layer::class);
});

test('Deve encontar uma layer pelo Code', function () {
    $layer = Layer::create(
        code: uniqid('LAYER_'),
    );
    $repository = new LayerDatabaseRepository;
    $repository->save($layer);

    $layerFound = $repository->findByCode(($layer->getCode()));
    expect($layerFound)->toBeInstanceOf(Layer::class);
});

test('Deve encontar uma layer pelo ID e Type', function () {
    $layer = Layer::create(
        code: uniqid('LAYER_'),
    );
    $repository = new LayerDatabaseRepository;
    $repository->save($layer);

    $layerFound = $repository->findByIdAndType(LayerId::restore($layer->getId()), LayerType::tryFrom($layer->getType()));
    expect($layerFound)->toBeInstanceOf(Layer::class);
});

test('Deve retornar null ao não encontar uma layer pelo ID', function () {
    $repository = new LayerDatabaseRepository;
    $layerNotFound = $repository->findById(LayerId::create());
    expect($layerNotFound)->toBeNull();
});

test('Deve retornar null ao não encontar uma layer pelo Code', function () {
    $repository = new LayerDatabaseRepository;
    $layerNotFound = $repository->findByCode('FAKE_CODE');
    expect($layerNotFound)->toBeNull();
});

test('Deve retornar null ao não encontar uma layer pelo Id e pelo Type', function () {
    $repository = new LayerDatabaseRepository;
    $layerNotFound = $repository->findByIdAndType(LayerId::create(), LayerType::NORMAL);
    expect($layerNotFound)->toBeNull();
});