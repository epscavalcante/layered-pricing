<?php

use Src\Domain\Entities\Product;
use Src\Domain\ValueObjects\ProductId;

test('Deve criar um produto', function () {
    $product = Product::create('Product');
    expect($product)->toBeInstanceOf(Product::class);
    expect($product->getId())->ToBeString();
    expect($product->name)->ToBe('Product');
});


test('Deve restaurar um produto', function () {
    $productId = ProductId::create();

    $product = Product::restore($productId->getValue(), 'Product');
    expect($product)->toBeInstanceOf(Product::class);
    expect($product->getId())->ToBe($productId->getValue());
    expect($product->name)->ToBe('Product');
});

