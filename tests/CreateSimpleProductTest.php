<?php

use Src\Application\UseCases\CreateSimpleProduct\CreateSimpleProduct;
use Src\Application\UseCases\CreateSimpleProduct\CreateSimpleProductInput;
use Src\Application\UseCases\CreateSimpleProduct\CreateSimpleProductOutput;
use Src\Domain\Entities\Product;
use Src\Domain\Repositories\ProductRepository;

test('Deve criar um produto', function () {
    $input = new CreateSimpleProductInput(
        name: 'Produto teste'
    );

    $product = Product::create('Produto teste');
    $productRepository = Mockery::mock(ProductRepository::class);
    $productRepository->shouldReceive('save')->andReturn($product);
    $useCase = new CreateSimpleProduct(
        productRepository: $productRepository
    );
    $output = $useCase->execute(
        input: $input
    );
    expect($output)->toBeInstanceOf(CreateSimpleProductOutput::class);
    expect($output->productId)->toBeString();
});
