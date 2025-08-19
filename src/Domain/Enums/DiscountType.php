<?php

declare(strict_types=1);

namespace Src\Domain\Enums;

enum DiscountType: string
{
    case PERCENTAGE = 'PERCENTAGE';
    case FIXED = 'FIXED';
}
