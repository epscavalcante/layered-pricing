<?php

use Src\Domain\Entities\Product;
use Src\Domain\ValueObjects\ProductId;
use Src\Infrastructure\Repositories\ProductDatabaseRepository;

test('Deve salvar um produto', function () {
    $product = Product::create(
        name: uniqid('Product ', true),
    );

    $repository = new ProductDatabaseRepository;
    $repository->save($product);

    $productExists = $repository->findById(ProductId::restore($product->getId()));
    expect($productExists)->toBeInstanceOf(Product::class);
});

test('Deve encontar um produto pelo ID', function () {
    $repository = new ProductDatabaseRepository;
    $product = $repository->findById(ProductId::restore('01J8M5R6T9Q9H8X7L2E9ZP4K8C'));

    expect($product)->toBeInstanceOf(Product::class);
    expect($product->getName())->toBe('Produto A');
});


test('Deve retornar null quando não encontrar o produto pelo ID', function () {
    $repository = new ProductDatabaseRepository;
    $product = $repository->findById(ProductId::create());
    expect($product)->toBeNull();
});