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
     * @param int $dst
     * @return bool
     */
    public function updateGtmDiffAndDstById(string $id, int $gtmDiff, int $dst = 0): bool;
}
