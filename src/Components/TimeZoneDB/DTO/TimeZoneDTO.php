<?php

namespace DK\Components\TimeZoneDB\DTO;

class TimeZoneDTO
{
    /** @var string */
    private $status;

    /** @var string */
    private $message;

    /** @var string */
    private $countryCode;

    /** @var string */
    private $countryName;

    /** @var string */
    private $regionName;

    /** @var string */
    private $cityName;

    /** @var string */
    private $zoneName;

    /** @var string */
    private $abbreviation;

    /** @var int */
    private $gmtOffset;

    /** @var int */
    private $dst;

    /** @var int */
    private $zoneStart;

    /** @var int */
    private $zoneEnd;

    /** @var string */
    private $nextAbbreviation;

    /** @var int */
    private $timestamp;

    /** @var string */
    private $formatted;

    /**
     * GetTimeZoneDTO constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->{$key} = $value;
        }
    }

    /** @return string|null */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /** @return string|null */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /** @return string|null */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /** @return string|null */
    public function getCountryName(): ?string
    {
        return $this->countryName;
    }

    /** @return string|null */
    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    /** @return string|null */
    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    /** @return string|null */
    public function getZoneName(): ?string
    {
        return $this->zoneName;
    }

    /** @return string|null */
    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    /** @return int|null */
    public function getGmtOffset(): ?int
    {
        return $this->gmtOffset;
    }

    /** @return int|null */
    public function getDst(): ?int
    {
        return $this->dst;
    }

    /** @return int|null */
    public function getZoneStart(): ?int
    {
        return $this->zoneStart;
    }

    /** @return int|null */
    public function getZoneEnd(): ?int
    {
        return $this->zoneEnd;
    }

    /** @return string|null */
    public function getNextAbbreviation(): ?string
    {
        return $this->nextAbbreviation;
    }

    /** @return int|null */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /** @return string|null */
    public function getFormatted(): ?string
    {
        return $this->formatted;
    }
}
