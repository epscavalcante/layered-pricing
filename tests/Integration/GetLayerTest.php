<?php

use Src\Application\UseCases\GetLayer\GetLayer;
use Src\Application\UseCases\GetLayer\GetLayerInput;
use Src\Application\UseCases\GetLayer\GetLayerOutput;
use Src\Domain\Entities\Layer;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\ValueObjects\LayerId;
use Src\Infrastructure\Database\SqliteDatabaseConnection;
use Src\Infrastructure\Repositories\LayerDatabaseRepository;
use Tests\DatabaseMigrations;

uses(DatabaseMigrations::class);

beforeEach(function () {
    $this->reset();
    $databaseConnection = new SqliteDatabaseConnection;
    /** @var LayerRepository */
    $this->layerRepository = new LayerDatabaseRepository(
        databaseConnection: $databaseConnection->getConnection()
    );
});

test('Deve lançar LayerNotFoundException', function () {
    $useCase = new GetLayer(
        layerRepository: $this->layerRepository
    );
    $input = new GetLayerInput(
        layerId: (string) LayerId::create(),
    );
    $useCase->execute($input);
})->throws(LayerNotFoundException::class);

test('Deve encontrar uma layer simples', function () {
    $layer = Layer::create(
        code: uniqid('layer_', true),
    );
    $this->layerRepository->save($layer);

    $useCase = new GetLayer(
        layerRepository: $this->layerRepository
    );
    $input = new GetLayerInput(
        layerId: $layer->getId(),
    );
    $output = $useCase->execute($input);
    expect($output)->toBeInstanceOf(GetLayerOutput::class);
    expect($output->layerId)->toBe($layer->getId());
    expect($output->code)->toBe($layer->getCode());
    expect($output->type)->toBe(LayerType::NORMAL->value);
    expect($output->parentId)->toBeNull();
    expect($output->discountType)->toBeNull();
    expect($output->discountValue)->toBeNull();
});

test('Deve encontrar uma layer de desconto', function () {
    $baseLayer = Layer::create(
        code: uniqid('layer_', true),
    );
    $this->layerRepository->save($baseLayer);
    $discountLayer = Layer::createDiscountLayer(
        baseLayerId: $baseLayer->getId(),
        discountType: DiscountType::PERCENTAGE->value,
        discountValue: 25,
        code: uniqid('layer_', true),
    );
    $this->layerRepository->save($discountLayer);

    $useCase = new GetLayer(
        layerRepository: $this->layerRepository
    );
    $input = new GetLayerInput(
        layerId: $discountLayer->getId(),
    );
    $output = $useCase->execute($input);
    expect($output)->toBeInstanceOf(GetLayerOutput::class);
    expect($output->layerId)->toBe($discountLayer->getId());
    expect($output->parentId)->toBe($discountLayer->getParentId());
    expect($output->code)->toBe($discountLayer->getCode());
    expect($output->type)->toBe(LayerType::DISCOUNT->value);
    expect($output->discountType)->toBe(DiscountType::PERCENTAGE->value);
    expect($output->discountValue)->toBe(25);
});
