<?php

namespace DK\Repositories\DTO;

class CityDTO
{
    /** @var int */
    private int $id;

    /** @var string */
    private string $name;

    /** @var float */
    private float $latitude;

    /** @var float */
    private float $longitude;

    /**
     * CityDTO constructor.
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

    /** @return float */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /** @return float */
    public function getLongitude(): float
    {
        return $this->longitude;
    }
}