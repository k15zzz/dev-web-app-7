<?php

namespace App\Repositories;

use Core\Base\Layers\Repository;

class TestRepository extends Repository
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function read(): string
    {
        return "Данные";
    }
}