<?php

namespace DK\Repositories\DTO;

class CityDTO
{
    /** @var string */
    private string $id;

    /** @var string */
    private string $name;

    /** @var string */
    private string $latitude;

    /** @var string */
    private string $longitude;

    /** @var ?int */
    private ?int $gtm_diff;

    /** @var int */
    private int $dst;

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

    /** @return string */
    public function getId(): string
    {
        return $this->id;
    }

    /** @return string */
    public function getName(): string
    {
        return $this->name;
    }

    /** @return string */
    public function getLatitude(): string
    {
        return $this->latitude;
    }

    /** @return string */
    public function getLongitude(): string
    {
        return $this->longitude;
    }

    /** @return int */
    public function getGtmDiff(): int
    {
        return $this->gtm_diff;
    }

    /** @return int */
    public function getDst(): int
    {
        return $this->dst;
    }
}