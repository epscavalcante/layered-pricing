<?php

use Src\Infrastructure\Config\Config;
use Tests\DatabaseMigrations;
use Tests\TestCase;

Config::load(dirname(__DIR__));

pest()->extend(TestCase::class);

uses(DatabaseMigrations::class)->in('Integration');
