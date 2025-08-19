<?php

use Src\Domain\Enums\DiscountType;
use Src\Domain\Factories\DiscountRuleFactory;
use Src\Domain\ValueObjects\FixedDiscountRule;
use Src\Domain\ValueObjects\PercentageDiscountRule;

test('Deve criar uma discount rule de porcentagem', function () {

    $discountRule = DiscountRuleFactory::create(
        type: DiscountType::PERCENTAGE->value,
        value: 23
    );

    expect($discountRule)->toBeInstanceOf(PercentageDiscountRule::class);
});


test('Deve criar uma discount rule de desconto fixo', function () {

    $discountRule = DiscountRuleFactory::create(
        type: DiscountType::FIXED->value,
        value: 55
    );

    expect($discountRule)->toBeInstanceOf(FixedDiscountRule::class);
});