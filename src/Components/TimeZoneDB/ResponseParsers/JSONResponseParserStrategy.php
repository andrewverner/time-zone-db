<?php

namespace DK\Components\TimeZoneDB\ResponseParsers;

class JSONResponseParser implements ResponseParserInterface
{
    /** @inheritDoc */
    public function getResponseFormat(): string
    {
        return 'json';
    }

    /** @inheritDoc */
    public function parse(string $data): array
    {
        return json_decode($data, true);
    }
}