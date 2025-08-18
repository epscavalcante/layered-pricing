<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreatePrice;

use Src\Domain\Entities\Price;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Exceptions\PriceAlreadExistsException;
use Src\Domain\Exceptions\ProductNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\Repositories\ProductRepository;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;

class CreatePrice
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
        private readonly PriceRepository $priceRepository,
        private readonly ProductRepository $productRepository,
    ) {}

    public function execute(CreatePriceInput $input): CreatePriceOutput
    {
        $layerId = LayerId::restore($input->layerId);
        $layer = $this->layerRepository->findById($layerId);
        if (is_null($layer)) throw new LayerNotFoundException();

        $productId = ProductId::restore($input->productId);
        $product = $this->productRepository->findById($productId);
        if (is_null($product)) throw new ProductNotFoundException();

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
            value: $input->value
        );

        $this->priceRepository->save($price);

        // dispara evento

        return new CreatePriceOutput(
            priceId: $price->getId(),
        );
    }
}