<?php

namespace DK\Repositories;

use DK\Repositories\DTO\CityDTO;

class CityRepository implements CityRepositoryInterface
{
    /** @var \PDO */
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @inheritDoc */
    public function getCityById(int $id): ?CityDTO
    {
        $query = 'SELECT * FROM city WHERE id = ?';
        $statement = $this->pdo->prepare($query, [$id]);

        $data = $statement->execute($statement);

        var_dump($data);

        return new CityDTO([]);
    }
}