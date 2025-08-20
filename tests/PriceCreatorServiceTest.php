<?php

use Src\Application\Services\PriceCreatorService;
use Src\Application\UseCases\CreatePrice\CreatePrice;
use Src\Application\UseCases\CreatePrice\CreatePriceInput;
use Src\Application\UseCases\CreatePrice\CreatePriceOutput;
use Src\Domain\Entities\Layer;
use Src\Domain\Entities\Price;
use Src\Domain\Entities\Product;
use Src\Domain\Enums\LayerType;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Exceptions\PriceAlreadExistsException;
use Src\Domain\Exceptions\ProductNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\Repositories\ProductRepository;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;

test('Deve falhar criar um Price para uma layer que não existe', function () {
    $layerRepository = Mockery::mock(LayerRepository::class);
    $layerRepository->shouldReceive('findById')
        ->once()
        ->andReturnNull();
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldNotReceive('findById');
    $priceRepository = Mockery::mock(PriceRepository::class);
    $priceRepository->shouldNotReceive('existsByLayerIdAndProductId');

    $priceCreator = new PriceCreatorService(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        productRepository: $productRepository,
    );

    $priceCreator->handle(
        layerId: LayerId::create(),
        productId: ProductId::create(),
        value: 0
    );
})->throws(LayerNotFoundException::class);

test('Deve falhar criar um Price para um produto que não existe', function () {
    $layerRepository = Mockery::mock(LayerRepository::class);
    $layer = Layer::create(
        code: 'layer',
    );
    $layerRepository->shouldReceive('findById')
        //->with(LayerId::restore($layer->getId()))
        ->once()
        ->andReturn($layer);
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldReceive('findById')
        //->with(ProductId::create())
        ->once()
        ->andReturnNull();
    $priceRepository = Mockery::mock(PriceRepository::class);
    $priceRepository->shouldNotReceive('existsByLayerIdAndProductId');

    $priceCreator = new PriceCreatorService(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        productRepository: $productRepository,
    );

    $priceCreator->handle(
        layerId: LayerId::create(),
        productId: ProductId::create(),
        value: 0
    );
})->throws(ProductNotFoundException::class);

test('Deve falhar criar um Price que já existe', function () {
    $layerRepository = Mockery::mock(LayerRepository::class);

    $layer = Layer::create(
        code: 'layer',
    );
    $layerRepository->shouldReceive('findById')
        //->with(LayerId::restore($layer->getId()))
        ->once()
        ->andReturn($layer);
    $productRepository = Mockery::mock(ProductRepository::class);
    $product = Product::create(
        name: 'Produto'
    );
    $productRepository->shouldReceive('findById')
        //->with(ProductId::restore($product->getId()))
        ->once()
        ->andReturn($product);
    $priceRepository = Mockery::mock(PriceRepository::class);
    $priceRepository->shouldReceive('existsByLayerIdAndProductId')
        //->with(LayerId::restore($layer->getId()), ProductId::restore($product->getId()))
        ->once()
        ->andReturn(true);

    $priceCreator = new PriceCreatorService(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        productRepository: $productRepository,
    );

    $priceCreator->handle(
        layerId: LayerId::restore($layer->getId()),
        productId: ProductId::restore($product->getId()),
        value: 0
    );
})->throws(PriceAlreadExistsException::class);

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

    $price = $priceCreator->handle(
        layerId: LayerId::restore($layer->getId()),
        productId: ProductId::restore($product->getId()),
        value: 0
    );

    expect($price)->toBeInstanceOf(Price::class);
});
