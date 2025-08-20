<?php

namespace Src\Infrastructure\Database;

use PDO;
use PDOException;

class PdoDatabaseConnection implements DatabaseConnection
{
    private PDO $connection;

    public function __construct()
    {
        $this->connect();
    }

    private function connect(): void
    {
        try {
            $this->connection = new PDO(
                dsn: "mysql:host=mysql;dbname=bank;charset=utf8mb4",
                username: 'root',
                password: 'root',
                options: [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lança exceções em erros
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => false, // Desabilita conexão persistente,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]
            );
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