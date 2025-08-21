<?php

use Src\Application\UseCases\CreateDiscountLayer\CreateDiscountLayer;
use Src\Application\UseCases\CreateDiscountLayer\CreateDiscountLayerInput;
use Src\Application\UseCases\CreateDiscountLayer\CreateDiscountLayerOutput;
use Src\Domain\Entities\Layer;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Exceptions\LayerAlreadExistsException;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\ValueObjects\LayerId;

test('Deve criar uma layer de desconto', function () {

    $layerRepository = Mockery::mock(LayerRepository::class);
    $layerRepository->shouldReceive('findByCode')
        ->once()
        ->andReturnNull();

    $layer = Layer::create('layer');
    $layerRepository->shouldReceive('findById')
        ->once()
        ->andReturn($layer);

    $layerRepository->shouldReceive('save')
        ->once()
        ->andReturn();

    $useCase = new CreateDiscountLayer(
        layerRepository: $layerRepository
    );
    $input = new CreateDiscountLayerInput(
        layerId: LayerId::create()->getValue(),
        code: 'layer_2',
        type: DiscountType::FIXED->value,
        value: 10
    );
    $output = $useCase->execute($input);
    expect($output)->toBeInstanceOf(CreateDiscountLayerOutput::class);
    expect($output->layerId)->toBeString();
});


test('Deve falhar ao criar uma layer com code existente', function () {
    $layer = Layer::create('layer');

    $layerRepository = Mockery::mock(LayerRepository::class);
    $layerRepository->shouldReceive('findByCode')
        ->once()
        ->andReturn($layer);

    $useCase = new CreateDiscountLayer(
        layerRepository: $layerRepository
    );
    $input = new CreateDiscountLayerInput(
        layerId: $layer->getId(),
        code: 'layer',
        type: DiscountType::FIXED->value,
        value: 10
    );
    $useCase->execute($input);
})->throws(LayerAlreadExistsException::class);


test('Deve falhar ao tentar layer de desconto com uma base layer que não existe', function () {
    $layerRepository = Mockery::mock(LayerRepository::class);
    $layerRepository->shouldReceive('findByCode')
        ->once()
        ->andReturnNull();

    $layerRepository->shouldReceive('findById')
        ->once()
        ->andReturnNull();

    $useCase = new CreateDiscountLayer(
        layerRepository: $layerRepository
    );
    $input = new CreateDiscountLayerInput(
        layerId: LayerId::create()->getValue(),
        code: 'layer',
        type: DiscountType::FIXED->value,
        value: 10
    );
    $useCase->execute($input);
})->throws(LayerNotFoundException::class);