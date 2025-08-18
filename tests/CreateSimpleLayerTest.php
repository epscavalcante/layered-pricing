<?php

use Src\Application\UseCases\CreateSimpleLayer\CreateSimpleLayer;
use Src\Application\UseCases\CreateSimpleLayer\CreateSimpleLayerInput;
use Src\Application\UseCases\CreateSimpleLayer\CreateSimpleLayerOutput;
use Src\Domain\Entities\Layer;
use Src\Domain\Exceptions\LayerAlreadExistsException;
use Src\Domain\Repositories\LayerRepository;

test('Deve criar uma layer base', function () {
    $input = new CreateSimpleLayerInput(
        code: 'Layer Example',
    );

    $layerFakeRepository = Mockery::mock(LayerRepository::class);
    $layerFakeRepository->shouldReceive('findByCode')->once()->andReturnNull();
    $layerFakeRepository->shouldReceive('save')->once()->andReturn();
    $createLayer = new CreateSimpleLayer(
        layerRepository: $layerFakeRepository,
    );

    $output = $createLayer->execute(
        input: $input
    );

    expect($output)->toBeInstanceOf(CreateSimpleLayerOutput::class);
    expect($output->layerId)->toBeString();
});


test('Deve falhar ao criar uma que ja existe uma layer', function () {
    $layer = Layer::create(
        code: 'EXISTS',
    );
    $input = new CreateSimpleLayerInput(
        code: $layer->code,
    );

    $layerFakeRepository = Mockery::mock(LayerRepository::class);
    $layerFakeRepository->shouldReceive('findByCode')->once()->andReturn($layer);
    $createLayer = new CreateSimpleLayer(
        layerRepository: $layerFakeRepository,
    );

    $createLayer->execute(
        input: $input
    );
})->throws(LayerAlreadExistsException::class, 'Layer already exists');