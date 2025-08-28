<?php

use Src\Application\UseCases\GetProduct\GetProduct;
use Src\Application\UseCases\GetProduct\GetProductInput;
use Src\Application\UseCases\GetProduct\GetProductOutput;
use Src\Domain\Entities\Product;
use Src\Domain\Exceptions\ProductNotFoundException;
use Src\Domain\ValueObjects\ProductId;
use Src\Infrastructure\Database\SqliteDatabaseConnection;
use Src\Infrastructure\Repositories\ProductDatabaseRepository;
use Tests\DatabaseMigrations;

uses(DatabaseMigrations::class);

beforeEach(function () {
    $this->reset();
    $databaseConnection = new SqliteDatabaseConnection;
    /** @var ProductRepository */
    $this->productRepository = new ProductDatabaseRepository(
        databaseConnection: $databaseConnection->getConnection()
    );
});

test('Deve lançar ProductNotFoundException', function () {
    $useCase = new GetProduct(
        productRepository: $this->productRepository
    );
    $input = new GetProductInput(
        productId: (string) ProductId::create(),
    );
    $useCase->execute($input);
})->throws(ProductNotFoundException::class);

test('Deve encontrar uma produto', function () {
    $product = Product::create(
        name: uniqid('product ', true),
    );
    $this->productRepository->save($product);

    $useCase = new GetProduct(
        productRepository: $this->productRepository
    );
    $input = new GetProductInput(
        productId: $product->getId(),
    );
    $output = $useCase->execute($input);
    expect($output)->toBeInstanceOf(GetProductOutput::class);
    expect($output->productId)->toBe($product->getId());
    expect($output->name)->toBe($product->getName());
});
