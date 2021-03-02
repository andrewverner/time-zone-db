<?php

namespace DK\Components\TimeZoneDB\ResponseParsers;

/**
 * Class JSONResponseParserStrategy
 * @package DK\Components\TimeZoneDB\ResponseParsers
 */
class JSONResponseParserStrategy implements ResponseParserStrategyInterface
{
    /** @inheritDoc */
    public function getResponseFormat(): string
    {
        return 'json';
    }

    /** @inheritDoc */
    public function parse(string $data): ?array
    {
        return json_decode($data, true);
    }
}