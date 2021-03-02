<?php

namespace DK\Repositories;

use DK\Repositories\DTO\CityDTO;

class CityRepository implements CityRepositoryInterface
{
    /** @var \PDO */
    private \PDO $pdo;

    /**
     * CityRepository constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /** @inheritDoc */
    public function getCityById(string $id): ?CityDTO
    {
        $statement = $this->pdo->prepare('SELECT * FROM city WHERE id = :id');
        $statement->execute(['id' => $id]);
        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        return $result ? new CityDTO($result) : null;
    }

    /** @inheritDoc */
    public function getAllCities(): array
    {
        $statement = $this->pdo->prepare('SELECT * FROM city');
        $statement->execute();
        $rows = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $data = [];
        foreach ($rows as $row) {
            $data[] = new CityDTO($row);
        }

        return $data;
    }

    /** @inheritDoc */
    public function updateGtmDiffById(string $id, int $gtmDiff): bool
    {
        $statement = $this->pdo->prepare('UPDATE city SET gtm_diff = :gtmDiff WHERE id = :id');

        return $statement->execute(['gtmDiff' => $gtmDiff, 'id' => $id]);
    }
}
