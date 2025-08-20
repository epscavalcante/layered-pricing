<?php

use Src\Application\Services\PriceCreatorService;
use Src\Application\UseCases\CreatePrice\CreatePrice;
use Src\Application\UseCases\CreatePrice\CreatePriceInput;
use Src\Application\UseCases\CreatePrice\CreatePriceOutput;
use Src\Domain\Entities\Layer;
use Src\Domain\Entities\Product;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\Repositories\ProductRepository;

test('Deve criar um preço', function () {
    $layer = Layer::create(
        code: 'layer',
    );
    $layerRepository = Mockery::mock(LayerRepository::class);
    $layerRepository->shouldReceive('findById')
        //->with(LayerId::restore($layer->getId()))
        ->once()
        ->andReturn($layer);

    $product = Product::create(
        name: 'Produto'
    );
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldReceive('findById')
        //->with(ProductId::restore($product->getId()))
        ->once()
        ->andReturn($product);
    $priceRepository = Mockery::mock(PriceRepository::class);
    $priceRepository->shouldReceive('existsByLayerIdAndProductId')
        //->with(LayerId::restore($layer->getId()), ProductId::restore($product->getId()))
        ->once()
        ->andReturn(false);
    $priceRepository->shouldReceive('save')
        ->once()
        ->andReturn();

    $priceCreator = new PriceCreatorService(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        productRepository: $productRepository,
    );

    $useCase = new CreatePrice(
        priceCreator: $priceCreator
    );

    $input = new CreatePriceInput(
        layerId: $layer->getId(),
        productId: $product->getId(),
        value: 250
    );

    $output = $useCase->execute(
        input: $input
    );

    expect($output)->toBeInstanceOf(CreatePriceOutput::class);
    expect($output->priceId)->toBeString();
});
