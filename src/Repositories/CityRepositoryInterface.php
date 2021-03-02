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
     * @param int $id
     * @return CityDTO|null
     */
    public function getCityById(int $id): ?CityDTO;
}
