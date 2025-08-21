<?php

namespace Tests;

trait DatabaseMigrations
{
    public function reset(): void
    {
        echo "running migrations..." . PHP_EOL;
        exec('composer migrations -- migrate -e testing', $output, $exitCode);

        if ($exitCode !== 0) {
            throw new \RuntimeException(
                "Falha ao rodar migrations: " . implode("\n", $output)
            );
        }
    }
}