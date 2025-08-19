<?php

use Src\Domain\ValueObjects\FixedDiscountRule;

test('Deve criar uma Regra de desconto de Valor fixo', function ($percentageValue) {
    $rule = new FixedDiscountRule(
        value: $percentageValue
    );
    expect($rule)->toBeInstanceOf(FixedDiscountRule::class);
    expect($rule->getValue())->toBe($percentageValue);
})->with([
    [1],
    [10],
    [30],
    [100],
    [1000],
    [15000],
]);


test('Não deve criar uma Regra de desconto de valor fixo', function ($percentageValue) {
    $rule = new FixedDiscountRule(
        value: $percentageValue
    );
})->throws(Exception::class, 'Valor do desconto fixo inválido')
    ->with([
        [-10000],
        [-24],
        [-1],
        [0],
    ]);
