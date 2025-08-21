<?php

namespace Src\Infrastructure\Database;

interface DatabaseConnection
{
    public function getConnection(): \PDO;
}