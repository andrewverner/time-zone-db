<?php

namespace DK\Components\TimeZoneDB;

use DK\Components\TimeZoneDB\Client\TimeZoneDBFacadeInterface;
use DK\Components\TimeZoneDB\DTO\TimeZoneDTO;
use DK\Repositories\CityRepositoryInterface;

/**
 * Class TimeZoneDBClient
 * @package DK\Components\TimeZoneDB
 */
class TimeZoneDBClient
{
    /** @var TimeZoneDBFacadeInterface */
    private TimeZoneDBFacadeInterface $timeZoneDBFacade;

    /** @var CityRepositoryInterface */
    private CityRepositoryInterface $cityRepository;

    /** @var \Throwable */
    private \Throwable $error;

    /**
     * TimeZoneDBClient constructor.
     * @param TimeZoneDBFacadeInterface $timeZoneDBFacade
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(TimeZoneDBFacadeInterface $timeZoneDBFacade, CityRepositoryInterface $cityRepository)
    {
        $this->timeZoneDBFacade = $timeZoneDBFacade;
        $this->cityRepository = $cityRepository;
    }

    /**
     * @param string $cityId
     * @return DTO\TimeZoneDTO|null
     */
    public function getTimeZoneByCityID(string $cityId): ?TimeZoneDTO
    {
        $cityDTO = $this->cityRepository->getCityById($cityId);
        if (!$cityDTO) {
            return null;
        }

        try {
            return $this->timeZoneDBFacade->getTimeZoneByPosition($cityDTO->getLatitude(), $cityDTO->getLongitude());
        } catch (\Throwable $exception) {
            $this->error = $exception;

            return null;
        }
    }

    /** @return \Throwable|null */
    public function getError(): ?\Throwable
    {
        return $this->error;
    }
}
