<?php

use Src\Infrastructure\Config\Config;

return
[
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds'
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_environment' => 'default',
        'default' => [
            'adapter' => Config::get('DB_CONNECTION','mysql'),
            'host' => Config::get('DB_HOST', 'mysql'),
            'name' => Config::get('DB_DATABASE', 'app'),
            'user' => Config::get('DB_USERNAME', 'root'),
            'pass' => Config::get('DB_PASSWORD', 'root'),
            'port' => Config::get('DB_PORT', 3306),
            'charset' => 'utf8',
        ],

        'testing' => [
            'adapter' => 'sqlite',
            'name' => './database',
            'suffix' => 'sqlite',
            //'memory' => true,
        ],
    ],
    'version_order' => 'creation'
];
