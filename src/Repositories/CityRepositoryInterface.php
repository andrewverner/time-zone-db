<?php

namespace DK\Repositories;

use DK\Repositories\DTO\CityDTO;

/**
 * Interface CityRepositoryInterface
 * @package DK\Repositories
 */
interface CityRepositoryInterface
{
    /**
     * @param string $id
     * @return CityDTO|null
     */
    public function getCityById(string $id): ?CityDTO;

    /** @return CityDTO[] */
    public function getAllCities(): array;

    /**
     * @param string $id
     * @param int $gtmDiff
     * @return bool
     */
    public function updateGtmDiffById(string $id, int $gtmDiff): bool;
}
