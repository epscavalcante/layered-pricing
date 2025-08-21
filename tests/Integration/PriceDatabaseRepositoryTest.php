<?php

use Src\Domain\Entities\Price;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;
use Src\Infrastructure\Database\SqliteDatabaseConnection;
use Src\Infrastructure\Repositories\PriceDatabaseRepository;
use Tests\DatabaseMigrations;

uses(DatabaseMigrations::class);

beforeEach(function () {
    $this->reset();
    $sqliteConnection = new SqliteDatabaseConnection;

    /** @var ProductRepository */
    $this->repository = new PriceDatabaseRepository(
        databaseConnection: $sqliteConnection->getConnection()
    );
});

test('Deve retornar null quando preço não for encontrado pela layerId e productId', function () {
    $exists = $this->repository->findByLayerIdAndProductId(LayerId::create(), ProductId::create());
    expect($exists)->toBeNull();
});

test('Deve retornar false quando preço não for encontrado pela layerId e productId', function () {
    $exists = $this->repository->existsByLayerIdAndProductId(LayerId::create(), ProductId::create());
    expect($exists)->toBeFalsy();
});

test('Deve retornar uma lista vazia quando preços não forem encontrados pela layerId e productIds', function () {
    $emptyList = $this->repository->findByLayerIdAndProductIds(LayerId::create(), [ProductId::create()]);
    expect($emptyList)->toHaveLength(0);
});

test('Deve retornar uma lista de preços encontrados pela layerId e productIds', function () {
    $layerId1 = LayerId::create();
    $layerId2 = LayerId::create();
    $productId1 = ProductId::create();
    $productId2 = ProductId::create();
    $productId3 = ProductId::create();
    $price1 = Price::create(
        layerId: $layerId1->getValue(),
        productId: $productId1->getValue(),
        value: 976423,
    );
    $price2 = Price::create(
        layerId: $layerId1->getValue(),
        productId: $productId2->getValue(),
        value: 123,
    );
    $price3 = Price::create(
        layerId: $layerId2->getValue(),
        productId: $productId3->getValue(),
        value: 9873,
    );
    $this->repository->save($price1);
    $this->repository->save($price2);
    $this->repository->save($price3);
    $list1 = $this->repository->findByLayerIdAndProductIds($layerId1, [$productId1, $productId2]);
    expect($list1)->toHaveLength(2);
    $list2 = $this->repository->findByLayerIdAndProductIds($layerId1, [$productId2]);
    expect($list2)->toHaveLength(1);
    $list3 = $this->repository->findByLayerIdAndProductIds($layerId1, [$productId3]);
    expect($list3)->toHaveLength(0);
    $list4 = $this->repository->findByLayerIdAndProductIds($layerId2, [$productId3]);
    expect($list4)->toHaveLength(1);
    $list5 = $this->repository->findByLayerIdAndProductIds($layerId2, [$productId1, $productId2]);
    expect($list5)->toHaveLength(0);
});

test('Deve retornar true quando preço for encontrado pela layerId e productId', function () {
    $layerId1 = LayerId::create();
    $productId1 = ProductId::create();
    $price = Price::create(
        layerId: $layerId1->getValue(),
        productId: $productId1->getValue(),
        value: 976423,
    );
    $this->repository->save($price);
    $exists = $this->repository->existsByLayerIdAndProductId($layerId1, $productId1);
    expect($exists)->toBeTruthy();
});

test('Deve retornar price quando preço for encontrado pela layerId e productId', function () {
    $layerId1 = LayerId::create();
    $productId1 = ProductId::create();
    $price = Price::create(
        layerId: $layerId1->getValue(),
        productId: $productId1->getValue(),
        value: 976423,
    );
    $this->repository->save($price);
    $priceFound = $this->repository->findByLayerIdAndProductId($layerId1, $productId1);
    expect($priceFound)->toBeInstanceOf(Price::class);
    expect($price->getId())->toBe($priceFound->getId());
});

test('Deve salvar um preço', function () {
    $layerId = LayerId::create();
    $productId = ProductId::create();
    $price = Price::create(
        layerId: $layerId->getValue(),
        productId: $productId->getValue(),
        value: 4599,
    );

    $this->repository->save($price);
    $priceFound = $this->repository->existsByLayerIdAndProductId($layerId, $productId);
    expect($priceFound)->toBeTruthy();
});