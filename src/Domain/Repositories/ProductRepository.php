<?php

declare(strict_types=1);

namespace Src\Domain\Repositories;

use Src\Domain\Entities\Product;
use Src\Domain\ValueObjects\ProductId;

interface ProductRepository
{
    public function findById(ProductId $productId): ?Product;

    public function save(Product $product): void;
}