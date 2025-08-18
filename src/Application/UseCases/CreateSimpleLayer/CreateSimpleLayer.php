<?php

declare(strict_types=1);

namespace Src\Application\UseCases\CreateSimpleLayer;

use Src\Domain\Entities\Layer;
use Src\Domain\Exceptions\LayerAlreadExistsException;
use Src\Domain\Repositories\LayerRepository;

class CreateSimpleLayer
{
    public function __construct(
        private readonly LayerRepository $layerRepository,
    ) {}

    public function execute(CreateSimpleLayerInput $input): CreateSimpleLayerOutput
    {
        $layerFound = $this->layerRepository->findByCode($input->code);
        if ($layerFound) {
            throw new LayerAlreadExistsException;
        }

        $layer = Layer::create(
            code: $input->code,
        );

        $this->layerRepository->save($layer);

        // disparar um evento

        return new CreateSimpleLayerOutput(
            layerId: $layer->getId(),
        );
    }
}