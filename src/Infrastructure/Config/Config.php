<?php

namespace Src\Infrastructure\Config;

use Dotenv\Dotenv;

class Config
{
    private static array $config = [];

    public static function load(string $basePath): void
    {
        $envFile = $basePath . '/.env';

        // Se o arquivo existe, carrega com Dotenv
        if (file_exists($envFile)) {
            $dotenv = Dotenv::createImmutable($basePath);
            $dotenv->load();
        }

        self::$config = $_ENV + $_SERVER;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        dd(self::$config);
        return self::$config[$key] ?? $default;
    }
}
