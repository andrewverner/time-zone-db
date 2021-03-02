<?php

namespace DK\Components\TimeZoneDB\ResponseParsers;

/**
 * Interface ResponseParserStrategyInterface
 * @package DK\Components\TimeZoneDB\ResponseParsers
 */
interface ResponseParserStrategyInterface
{
    /** @return string */
    public function getResponseFormat(): string;

    /**
     * @param string $data
     * @return array|null
     */
    public function parse(string $data): ?array;
}
