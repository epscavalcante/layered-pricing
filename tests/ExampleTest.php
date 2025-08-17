<?php

use Src\Example;

test('Example', function () {
    $example = new Example;
    expect($example->sayHello())->toBe('Hello');
});
