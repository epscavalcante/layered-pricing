<?php

namespace Tests;

class DatabaseMigrations
{
    public static function migrate(): void
    {
        exec('composer migrations migrate', $output, $exitCode);

        if ($exitCode !== 0) {
            throw new \RuntimeException(
                "Falha ao rodar migrations: " . implode("\n", $output)
            );
        }
    }
}
