<?php

use Src\Application\Services\PriceCreatorService;
use Src\Application\UseCases\CreateDiscountPrice\CreateDiscountPrice;
use Src\Application\UseCases\CreateDiscountPrice\CreateDiscountPriceInput;
use Src\Application\UseCases\CreateDiscountPrice\CreateDiscountPriceOutput;
use Src\Application\UseCases\CreatePrice\CreatePrice;
use Src\Application\UseCases\CreatePrice\CreatePriceInput;
use Src\Application\UseCases\CreatePrice\CreatePriceOutput;
use Src\Domain\Entities\Layer;
use Src\Domain\Entities\Price;
use Src\Domain\Entities\Product;
use Src\Domain\Enums\DiscountType;
use Src\Domain\Enums\LayerType;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Exceptions\PriceNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\Repositories\ProductRepository;
use Src\Domain\ValueObjects\ProductId;

test('Deve criar um preço com desconto', function () {
    $baseLayer = Layer::create(
        code: 'base',
    );
    $discountLayer = Layer::createDiscountLayer(
        baseLayerId: $baseLayer->getId(),
        code: 'discountLayer',
        discountType: DiscountType::FIXED->value,
        discountValue: 15
    );
    
    
    $layerRepository = Mockery::mock(LayerRepository::class);
    // pro usecase
    $layerRepository->shouldReceive('findByIdAndType')
        ->once()
        ->andReturn($discountLayer);

    // pro priceCreator
    $layerRepository->shouldReceive('findById')
        ->once()
        ->andReturn($discountLayer);
    
    // pro priceCalculator
    $product = Product::create(
        name: 'Produto'
    );
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldReceive('findById')
        ->once()
        ->andReturn($product);

    $price = Price::create(
        layerId: $baseLayer->getId(),
        productId: $product->getId(),
        value: 2000,
    );
    $priceRepository = Mockery::mock(PriceRepository::class);
    // pro usecase
    $priceRepository->shouldReceive('findByLayerIdAndProductId')
        ->once()
        ->andReturn($price);
    //pro priceCreator
    $priceRepository->shouldReceive('existsByLayerIdAndProductId')
        ->once()
        ->andReturnFalse();
    $priceRepository->shouldReceive('save')
        ->once()
        ->andReturn();

    $priceCreator = new PriceCreatorService(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        productRepository: $productRepository,
    );

    $useCase = new CreateDiscountPrice(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        priceCreator: $priceCreator
    );

    $input = new CreateDiscountPriceInput(
        layerId: $discountLayer->getId(),
        productId: $product->getId(),
    );

    $output = $useCase->execute(
        input: $input
    );

    expect($output)->toBeInstanceOf(CreateDiscountPriceOutput::class);
    expect($output->priceId)->toBeString();
});

test('Deve falhar ao usar uma layer base para criar um preço com desconto', function () {
    $baseLayer = Layer::create(
        code: 'base',
    );
    $discountLayer = Layer::create(
        code: 'discountLayer',
    );
    
    $layerRepository = Mockery::mock(LayerRepository::class);
    // pro usecase
    $layerRepository->shouldReceive('findByIdAndType')
        ->once()
        ->andReturnNull();

    // pro priceCreator
    $layerRepository->shouldNotReceive('findById');
    
    // pro priceCalculator
    $product = Product::create(
        name: 'Produto'
    );
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldNotReceive('findById');

    $price = Price::create(
        layerId: $baseLayer->getId(),
        productId: $product->getId(),
        value: 2000,
    );
    $priceRepository = Mockery::mock(PriceRepository::class);
    // pro usecase
    $priceRepository->shouldNotReceive('findByLayerIdAndProductId');
    //pro priceCreator
    $priceRepository->shouldNotReceive('existsByLayerIdAndProductId');
    $priceRepository->shouldNotReceive('save');

    $priceCreator = new PriceCreatorService(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        productRepository: $productRepository,
    );

    $useCase = new CreateDiscountPrice(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        priceCreator: $priceCreator
    );

    $input = new CreateDiscountPriceInput(
        layerId: $discountLayer->getId(),
        productId: $product->getId(),
    );

    $output = $useCase->execute(
        input: $input
    );
})->throws(LayerNotFoundException::class);

// essse cenário deveria ser impossível
// layer de desconto devem ter uma layer base
// ta aqui pq eu consigo burlar a entidade da forma atual
test('Deve falhar retornar uma layer de desconto sem uma layer base', function () {
    $baseLayer = Layer::create(
        code: 'base',
    );
    $discountLayer = Layer::create(
        code: 'discountLayer',
        type: LayerType::DISCOUNT->value,
        parentId: null,
        discountType: DiscountType::PERCENTAGE->value,
        discountValue: 20,
    );
    
    $layerRepository = Mockery::mock(LayerRepository::class);
    // pro usecase
    $layerRepository->shouldReceive('findByIdAndType')
        ->once()
        ->andReturn($discountLayer);

    // pro priceCreator
    $layerRepository->shouldNotReceive('findById');
    
    // pro priceCalculator
    $product = Product::create(
        name: 'Produto'
    );
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldNotReceive('findById');

    $price = Price::create(
        layerId: $baseLayer->getId(),
        productId: $product->getId(),
        value: 2000,
    );
    $priceRepository = Mockery::mock(PriceRepository::class);
    // pro usecase
    $priceRepository->shouldNotReceive('findByLayerIdAndProductId');
    //pro priceCreator
    $priceRepository->shouldNotReceive('existsByLayerIdAndProductId');
    $priceRepository->shouldNotReceive('save');

    $priceCreator = new PriceCreatorService(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        productRepository: $productRepository,
    );

    $useCase = new CreateDiscountPrice(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        priceCreator: $priceCreator
    );

    $input = new CreateDiscountPriceInput(
        layerId: $discountLayer->getId(),
        productId: $product->getId(),
    );

    $useCase->execute(
        input: $input
    );
})->throws(Exception::class, 'Layer de desconto sem parent Layer');


test('Deve falhar ao não encontrar o preço da layer base da layer de desconto', function () {
    $baseLayer = Layer::create(
        code: 'base',
    );
    $discountLayer = Layer::create(
        code: 'discountLayer',
        type: LayerType::DISCOUNT->value,
        parentId: $baseLayer->getId(),
        discountType: DiscountType::PERCENTAGE->value,
        discountValue: 20,
    );
    
    $layerRepository = Mockery::mock(LayerRepository::class);
    // pro usecase
    $layerRepository->shouldReceive('findByIdAndType')
        ->once()
        ->andReturn($discountLayer);

    // pro priceCreator
    $layerRepository->shouldNotReceive('findById');
    
    // pro priceCalculator
    $product = Product::create(
        name: 'Produto'
    );
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldNotReceive('findById');

    $price = Price::create(
        layerId: $baseLayer->getId(),
        productId: $product->getId(),
        value: 2000,
    );
    $priceRepository = Mockery::mock(PriceRepository::class);
    // pro usecase
    $priceRepository->shouldReceive('findByLayerIdAndProductId')
        ->once()
        ->andReturnNull();
    //pro priceCreator
    $priceRepository->shouldNotReceive('existsByLayerIdAndProductId');
    $priceRepository->shouldNotReceive('save');

    $priceCreator = new PriceCreatorService(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        productRepository: $productRepository,
    );

    $useCase = new CreateDiscountPrice(
        layerRepository: $layerRepository,
        priceRepository: $priceRepository,
        priceCreator: $priceCreator
    );

    $input = new CreateDiscountPriceInput(
        layerId: $discountLayer->getId(),
        productId: $product->getId(),
    );

    $useCase->execute(
        input: $input
    );
})->throws(PriceNotFoundException::class);