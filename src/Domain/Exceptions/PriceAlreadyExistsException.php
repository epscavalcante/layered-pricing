<?php

declare(strict_types=1);

namespace Src\Domain\Exceptions;

class PriceAlreadExistsException extends AlreadyExistsException
{
    public function __construct()
    {
        parent::__construct('Price already exists');
    }
}