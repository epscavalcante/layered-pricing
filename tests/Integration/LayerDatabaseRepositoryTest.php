<?php

use Src\Domain\Entities\Layer;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;
use Src\Domain\ValueObjects\LayerId;
use Src\Infrastructure\Database\SqliteDatabaseConnection;
use Src\Infrastructure\Repositories\LayerDatabaseRepository;
use Tests\DatabaseMigrations;

uses(DatabaseMigrations::class);

beforeEach(function () {
    $this->reset();
    $databaseConnection = new SqliteDatabaseConnection;

    /** @var ProductRepository */
    $this->repository = new LayerDatabaseRepository(
        databaseConnection: $databaseConnection->getConnection()
    );
});

test('Deve salvar uma layer normal', function () {
    $layer = Layer::create(
        code: uniqid('LAYER_', true),
    );
    $this->repository->save($layer);
    $layerExists = $this->repository->findById(LayerId::restore($layer->getId()));
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

    $this->repository->save($base);
    $this->repository->save($percentageDiscount);

    $percentageDiscountLayerExists = $this->repository->findById(LayerId::restore($percentageDiscount->getId()));
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

    $this->repository->save($base);
    $this->repository->save($percentageDiscount);

    $percentageDiscountLayerExists = $this->repository->findById(LayerId::restore($percentageDiscount->getId()));
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
    $this->repository->save($layer);

    $layerFound = $this->repository->findById(LayerId::restore($layer->getId()));
    expect($layerFound)->toBeInstanceOf(Layer::class);
});

test('Deve encontar uma layer pelo Code', function () {
    $layer = Layer::create(
        code: uniqid('LAYER_'),
    );
    $this->repository->save($layer);

    $layerFound = $this->repository->findByCode(($layer->getCode()));
    expect($layerFound)->toBeInstanceOf(Layer::class);
});

test('Deve encontar uma layer pelo ID e Type', function () {
    $layer = Layer::create(
        code: uniqid('LAYER_'),
    );
    $this->repository->save($layer);

    $layerFound = $this->repository->findByIdAndType(LayerId::restore($layer->getId()), LayerType::tryFrom($layer->getType()));
    expect($layerFound)->toBeInstanceOf(Layer::class);
});

test('Deve retornar null ao não encontar uma layer pelo ID', function () {
    $layerNotFound = $this->repository->findById(LayerId::create());
    expect($layerNotFound)->toBeNull();
});

test('Deve retornar null ao não encontar uma layer pelo Code', function () {
    $layerNotFound = $this->repository->findByCode('FAKE_CODE');
    expect($layerNotFound)->toBeNull();
});

test('Deve retornar null ao não encontar uma layer pelo Id e pelo Type', function () {
    $layerNotFound = $this->repository->findByIdAndType(LayerId::create(), LayerType::NORMAL);
    expect($layerNotFound)->toBeNull();
});