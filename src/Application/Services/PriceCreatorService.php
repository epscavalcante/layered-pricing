<?php

declare(strict_types=1);

namespace Src\Application\Services;

use Src\Domain\Entities\Price;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Exceptions\PriceAlreadExistsException;
use Src\Domain\Exceptions\ProductNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\Repositories\ProductRepository;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\Services\PriceCalculator;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;

class PriceCreatorService
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
        private readonly PriceRepository $priceRepository,
        private readonly ProductRepository $productRepository,
    ) {}

    public function handle(LayerId $layerId, ProductId $productId, int $value): Price
    {
        $layer = $this->layerRepository->findById($layerId);
        if (is_null($layer)) {
            throw new LayerNotFoundException();
        }

        $product = $this->productRepository->findById($productId);
        if (is_null($product)) {
            throw new ProductNotFoundException();
        }

        $priceExists = $this->priceRepository->existsByLayerIdAndProductId(
            layerId: $layerId,
            productId: $productId,
        );

        if ($priceExists) {
            throw new PriceAlreadExistsException;
        }

        $price = Price::create(
            layerId: $layer->getId(),
            productId: $product->getId(),
            value: PriceCalculator::calculate($value, $layer)
        );

        $this->priceRepository->save($price);

        return $price;
    }
}
