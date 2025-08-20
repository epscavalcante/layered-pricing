<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateDiscountPrice;

use Exception;
use Src\Application\Services\PriceCreatorService;
use Src\Domain\Enums\LayerType;
use Src\Domain\Exceptions\LayerHaventDiscountTypeException;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Exceptions\PriceNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;

class CreateDiscountPrice
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
        private readonly PriceRepository $priceRepository,
        private readonly PriceCreatorService $priceCreator,
    ) {}

    public function execute(CreateDiscountPriceInput $input): CreateDiscountPriceOutput
    {
        $layerId = LayerId::restore($input->layerId);
        $discountLayer = $this->layerRepository->findByIdAndType(
            layerId: $layerId,
            type: LayerType::DISCOUNT
        );
        if (is_null($discountLayer)) {
            throw new LayerNotFoundException();
        }

        if (is_null($discountLayer->getParentId())) {
            throw new Exception('Layer de desconto sem parent Layer');
        }

        $productId = ProductId::restore($input->productId);
        $basePrice = $this->priceRepository->findByLayerIdAndProductId(
            layerId: $layerId,
            productId: $productId,
        );

        if (is_null($basePrice)) {
            throw new PriceNotFoundException();
        }

        $price = $this->priceCreator->handle(
            layerId: $layerId,
            productId: $productId,
            value: $basePrice->getValue()
        );

        // dispara evento

        return new CreateDiscountPriceOutput(
            priceId: $price->getId(),
        );
    }
}
