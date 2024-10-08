<?php

namespace App\Repositories;

use Core\Base\Layers\Repository;
use PDO;

class PostRepository extends Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getPrice(string $material, string $publicationType): int
    {
        $stmt = $this->pdo->prepare('SELECT price FROM post WHERE name = :name AND type = :type');
        $stmt->execute(['name' => $material, 'type' => $publicationType]);
        return $stmt->fetchColumn();
    }

    public function getPublicationTypes(): array {
        return $this->pdo->query('SELECT DISTINCT type FROM post')->fetchAll();
    }

    public function getPublicationNames(): array {
        return $this->pdo->query('SELECT DISTINCT name FROM post')->fetchAll();
    }
}
