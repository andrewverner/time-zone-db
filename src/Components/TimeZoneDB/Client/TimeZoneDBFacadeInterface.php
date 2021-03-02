<?php

namespace DK\Components\TimeZoneDB\Client;

use DK\Components\TimeZoneDB\DTO\TimeZoneDTO;
use DK\Components\TimeZoneDB\Exceptions\TimeZoneDBException;

interface TimeZoneDBFacadeInterface
{
    /**
     * @param float $lat
     * @param float $lng
     * @return TimeZoneDTO
     * @throws TimeZoneDBException
     */
    public function getTimeZoneByPosition(float $lat, float $lng): TimeZoneDTO;
}