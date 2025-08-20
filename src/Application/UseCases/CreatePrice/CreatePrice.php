<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreatePrice;

use Src\Application\Services\PriceCreatorService;
use Src\Domain\ValueObjects\LayerId;
use Src\Domain\ValueObjects\ProductId;

class CreatePrice
{
    public function __construct(
        private readonly PriceCreatorService $priceCreator,
    ) {}

    public function execute(CreatePriceInput $input): CreatePriceOutput
    {
        $price = $this->priceCreator->handle(
            layerId: LayerId::restore($input->layerId),
            productId: ProductId::restore($input->productId),
            value: $input->value
        );

        // dispara eventos

        return new CreatePriceOutput(
            priceId: $price->getId(),
        );
    }
}