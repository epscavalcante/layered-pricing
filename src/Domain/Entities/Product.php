<?php

declare(strict_types=1);

namespace Src\Domain\Entities;

use Src\Domain\ValueObjects\ProductId;

class Product
{
    private function __construct(
        private ProductId $id,
        private string $name,
    ) {}

    public static function create($name): self
    {
        return new self(
            id: ProductId::create(),
            name: $name
        );
    }

    public static function restore(string $id, string $name): self
    {
        return new self(
            id: ProductId::restore($id),
            name: $name
        );
    }

    public function getId(): string
    {
        return $this->id->getValue();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
