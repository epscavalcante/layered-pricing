<?php

declare(strict_types=1);

namespace Src\Domain\Enums;

enum LayerType: string
{
    case NORMAL = 'NORMAL';
    case DISCOUNT = 'DISCOUNT';
}