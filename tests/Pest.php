<?php

use Src\Infrastructure\Config\Config;

Config::load(dirname(__DIR__));

pest()->extend(Tests\TestCase::class)->in('Unit');
