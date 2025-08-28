<?php

declare(strict_types=1);

namespace Src\Application\UseCases\GetProduct;

use Src\Domain\Exceptions\ProductNotFoundException;
use Src\Domain\Repositories\ProductRepository;
use Src\Domain\ValueObjects\ProductId;

class GetProduct
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {}

    public function execute(GetProductInput $input): GetProductOutput
    {
        $product = $this->productRepository->findById(ProductId::restore($input->productId));
        if (is_null($product)) {
            throw new ProductNotFoundException();
        }
        return new GetProductOutput(
            productId: $product->getId(),
            name: $product->getName(),
        );
    }
}
