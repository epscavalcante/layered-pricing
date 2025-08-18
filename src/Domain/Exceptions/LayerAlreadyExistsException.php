<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

class LayerAlreadExistsException extends AlreadyExistsException
{
    public function __construct()
    {
        parent::__construct('Layer already exists');
    }
}
