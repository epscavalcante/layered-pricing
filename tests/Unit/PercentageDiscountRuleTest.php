<?php

use Src\Domain\ValueObjects\PercentageDiscountRule;

test('Deve criar uma Regra de desconto de Percentagem', function ($percentageValue) {
    $percentageDiscountRule = new PercentageDiscountRule(
        value: $percentageValue
    );
    expect($percentageDiscountRule)->toBeInstanceOf(PercentageDiscountRule::class);
    expect($percentageDiscountRule->getValue())->toBe($percentageValue);
})->with([
    [1],
    [10],
    [30],
    [100],
]);


test('Não deve criar uma Regra de desconto de Percentagem', function ($percentageValue) {
    $percentageDiscountRule = new PercentageDiscountRule(
        value: $percentageValue
    );
})->throws(Exception::class, 'Porcentagem de desconto inválida')
    ->with([
        [-10],
        [-1],
        [0],
        [101],
        [10100],
    ]);
