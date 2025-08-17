<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateSimpleProduct;

use Src\Domain\Entities\Product;
use Src\Domain\Repositories\ProductRepository;

class CreateSimpleProduct
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function execute(CreateSimpleProductInput $input): CreateSimpleProductOutput
    {
        $product = Product::create(
            name: $input->name,
        );

        $this->productRepository->save($product);

        // disparar um evento

        return new CreateSimpleProductOutput(
            productId: $product->getId(),
        );
    }
}