<?php

namespace Src\Infrastructure\Database;

use PDO;
use PDOException;
use Src\Infrastructure\Config\Config;

class SqliteDatabaseConnection implements DatabaseConnection
{
    private PDO $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        $host = Config::get('DB_HOST');
        try {
            $this->connection = new PDO(
                dsn: "sqlite:{$host}",
                options: [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em erros
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => false, // Desabilita conexão persistente,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
            $this->connection->exec(file_get_contents("/var/www/.docker/initsqlitedb.sql"));
        } catch (PDOException $e) {
            // Trate erro de conexão de forma adequada (log, retry, etc)
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}