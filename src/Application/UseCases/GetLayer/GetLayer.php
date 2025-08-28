<?php

declare(strict_types=1);

namespace Src\Application\UseCases\GetLayer;

use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\ValueObjects\LayerId;

class GetLayer
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
    ) {}

    public function execute(GetLayerInput $input): GetLayerOutput
    {
        $layer = $this->layerRepository->findById(LayerId::restore($input->layerId));
        if (is_null($layer)) {
            throw new LayerNotFoundException();
        }
        return new GetLayerOutput(
            layerId: $layer->getId(),
            code: $layer->getCode(),
            type: $layer->getType(),
            parentId: $layer->getParentId(),
            discountType: $layer->getDiscountType(),
            discountValue: $layer->getDiscountValue(),
        );
    }
}
