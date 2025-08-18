<?php

use Src\Domain\Entities\Price;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\PriceId;
use Src\Domain\ValueObjects\ProductId;

test('Deve criar um price', function () {
    $layerId = LayerId::create();
    $productId = ProductId::create();
    
    $price = Price::create($layerId->getValue(), $productId->getValue(), 1000);
    expect($price)->toBeInstanceOf(price::class);
    expect($price->getId())->ToBeString();
    expect($price->getLayerId())->ToBe($layerId->getValue());
    expect($price->getProductId())->ToBe($productId->getValue());
    expect($price->getValue())->ToBe(1000);
});

test('Deve restaurar um price', function () {
    $priceId = PriceId::create();
    $layerId = LayerId::create();
    $productId = ProductId::create();
    $price = Price::restore($priceId->getValue(), $layerId->getValue(), $productId->getValue(), 0);
    expect($price)->toBeInstanceOf(Price::class);
    expect($price->getId())->ToBe($priceId->getValue());
    expect($price->getLayerId())->ToBe($layerId->getValue());
    expect($price->getProductId())->ToBe($productId->getValue());
    expect($price->getValue())->ToBe(0);
});

