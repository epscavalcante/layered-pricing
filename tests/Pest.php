<?php

use Src\Infrastructure\Config\Config;
use Tests\DatabaseMigrations;

Config::load(dirname(__DIR__));

uses(DatabaseMigrations::class)
    ->beforeEach(fn () => DatabaseMigrations::migrate())
    ->in('Integration');

pest()->extend(Tests\TestCase::class)->in('Unit');

