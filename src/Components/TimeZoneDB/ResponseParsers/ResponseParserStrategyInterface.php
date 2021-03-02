<?php

namespace DK\Components\TimeZoneDB\ResponseParsers;

/**
 * Interface ResponseParserInterface
 * @package DK\Components\TimeZoneDB\ResponseParsers
 */
interface ResponseParserInterface
{
    /** @return string */
    public function getResponseFormat(): string;

    /**
     * @param string $data
     * @return array
     */
    public function parse(string $data): array;
}
