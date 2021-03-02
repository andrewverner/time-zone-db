<?php

namespace DK\Components\TimeZoneConverter;

use DK\Repositories\CityRepositoryInterface;

/**
 * Class TimeZoneConverter
 * @package DK\Components\TimeZoneConverter
 */
class TimeZoneConverter
{
    /** @var CityRepositoryInterface */
    private CityRepositoryInterface $cityRepository;

    /**
     * TimeZoneConverter constructor.
     * @param CityRepositoryInterface $cityRepository
     */
    public function __construct(CityRepositoryInterface $cityRepository)
    {
        $this->cityRepository = $cityRepository;
    }

    /**
     * Получения локального времени в городе по переданному идентификатору города и метке времени по UTC+0
     *
     * @param string $id
     * @param \DateTimeImmutable $timeStamp
     * @return \DateTimeImmutable|null
     */
    public function getLocalCityTimeByCityId(string $id, \DateTimeImmutable $timeStamp): ?\DateTimeImmutable
    {
        $city = $this->cityRepository->getCityById($id);
        if (!$city || is_null($city->getGtmDiff())) {
            return null;
        }

        return $timeStamp->setTimestamp($timeStamp->getTimestamp() + $city->getGtmDiff());
    }

    /**
     * Обратное преобразование из локального времени и идентификатора города в метку времени по UTC+0
     *
     * @param string $id
     * @param \DateTimeImmutable $timeStamp
     * @return \DateTimeImmutable|null
     */
    public function getUtcTimeByCityId(string $id, \DateTimeImmutable $timeStamp): ?\DateTimeImmutable
    {
        $city = $this->cityRepository->getCityById($id);
        if (!$city || is_null($city->getGtmDiff())) {
            return null;
        }

        return $timeStamp->setTimestamp($timeStamp->getTimestamp() - $city->getGtmDiff());
    }
}