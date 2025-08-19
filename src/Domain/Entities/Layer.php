<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

use Exception;
use Src\Domain\Enums\LayerType as Type;
use Src\Domain\Factories\DiscountRuleFactory;
use Src\Domain\ValueObjects\DiscountRule;
use Src\Domain\ValueObjects\LayerId;


class Layer
{
    private function __construct(
        private LayerId $id,
        private string $code,
        private Type $type,
        private ?LayerId $parentId = null,
        private ?DiscountRule $discountRule = null,
    ) {}

    public static function create(string $code, ?string $type = null)
    {
        $type = $type ? Type::tryFrom($type) : Type::NORMAL;
        return new self(
            id: LayerId::create(),
            type: $type,
            code: $code,
            discountRule: null
        );
    }

    public static function createDiscountLayer(string $baseLayerId, string $code, string $discountType, int $discountValue)
    {
        $discountRule = DiscountRuleFactory::create($discountType, $discountValue);
        return new self(
            id: LayerId::create(),
            parentId: LayerId::restore($baseLayerId),
            type: Type::DISCOUNT,
            code: $code,
            discountRule: $discountRule
        );
    }

    public static function restore(string $id, string $type, string $code)
    {
        return new self(
            id: LayerId::restore($id),
            type: Type::tryFrom($type),
            code: $code,
        );
    }

    public function getId(): string
    {
        return $this->id->getValue();
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getType(): string
    {
        return $this->type->value;
    }

    public function getDiscountType(): ?string
    {
        return $this->discountRule?->getType()?->value;
    }

    public function getDiscountValue(): ?int
    {
        return $this->discountRule?->getValue();
    }
}
