<?php

declare(strict_types=1);

namespace Src\Application\UseCases\GetPrice;

use Src\Domain\Exceptions\PriceNotFoundException;
use Src\Domain\Repositories\PriceRepository;
use Src\Domain\ValueObjects\PriceId;

class GetPrice
{
    public function __construct(
        private readonly PriceRepository $priceRepository,
    ) {}

    public function execute(GetPriceInput $input): GetPriceOutput
    {
        $price = $this->priceRepository->findById(PriceId::restore($input->priceId));
        if (is_null($price)) {
            throw new PriceNotFoundException();
        }
        return new GetPriceOutput(
            priceId: $price->getId(),
            layerId: $price->getLayerId(),
            productId: $price->getProductId(),
            value: $price->getValue()
        );
    }
}
