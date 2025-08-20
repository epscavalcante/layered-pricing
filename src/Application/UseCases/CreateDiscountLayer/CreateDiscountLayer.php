<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateDiscountLayer;

use Src\Domain\Entities\Layer;
use Src\Domain\Exceptions\LayerAlreadExistsException;
use Src\Domain\Exceptions\LayerNotFoundException;
use Src\Domain\Repositories\LayerRepository;
use Src\Domain\ValueObjects\LayerId;

class CreateDiscountLayer
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
    ) {}

    public function execute(CreateDiscountLayerInput $input): CreateDiscountLayerOutput
    {
        $layerFound = $this->layerRepository->findByCode($input->code);
        if ($layerFound) {
            throw new LayerAlreadExistsException;
        }

        $parentLayer = $this->layerRepository->findById(LayerId::restore($input->layerId));
        if (is_null($parentLayer)) {
            throw new LayerNotFoundException;
        }

        $layer = Layer::createDiscountLayer(
            code: $input->code,
            baseLayerId: $parentLayer->getId(),
            discountValue: $input->value,
            discountType: $input->type,
        );

        $this->layerRepository->save($layer);

        // disparar um evento

        return new CreateDiscountLayerOutput(
            layerId: $layer->getId(),
        );
    }
}