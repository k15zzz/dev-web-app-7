<?php

namespace App\Repositories;

use Core\Base\Layers\Repository;
use PDO;

class TentRepository extends Repository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Поиск палаток по бренду.
     *
     * @param string $brand - Название бренда
     * @return array - Массив данных о палатках
     */
    public function searchByBrand(string $brand): array
    {
        $sql = "SELECT * FROM tents WHERE brand = :brand";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['brand' => $brand]);

        // Возвращаем массив с данными палаток
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Поиск палаток по вместимости.
     *
     * @param int $capacity - Вместимость палатки
     * @return array - Массив данных о палатках
     */
    public function searchByCapacity(int $capacity): array
    {
        $sql = "SELECT * FROM tents WHERE capacity = :capacity";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['capacity' => $capacity]);

        // Возвращаем массив с данными палаток
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
