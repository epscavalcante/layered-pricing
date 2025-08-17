<?php

use Src\Domain\ValueObjects\Ulid;

test('Deve criar um ulid', function () {

    $ulid = Ulid::create();

    expect($ulid)->toBeInstanceOf(Ulid::class);
    expect($ulid->getValue())->toBeString();

});

test('Deve criar restaurar um ulid', function () {
    $ulidString = (string) Ulid::create();

    expect(Ulid::restore($ulidString))->toBeInstanceOf(Ulid::class);
});


test('Não deve criar um Ulid', function () {
    Ulid::restore('fake');
})->throws(Exception::class, 'Invalid value');