<?php

namespace DK\Components\TimeZoneDB\ResponseParsers;

class XMLResponseParser implements ResponseParserInterface
{
    /** @inheritDoc */
    public function getResponseFormat(): string
    {
        return 'xml';
    }

    /** @inheritDoc */
    public function parse(string $data): array
    {
        $xml = simplexml_load_string($data);

        return [
            'status' => strval($xml->status),
            'message' => strval($xml->message),
            'countryCode' => strval($xml->countryCode),
            'countryName' => strval($xml->countryName),
            'regionName' => strval($xml->regionName),
            'cityName' => strval($xml->cityName),
            'zoneName' => strval($xml->zoneName),
            'abbreviation' => strval($xml->abbreviation),
            'gmtOffset' => intval($xml->gmtOffset),
            'dst' => intval($xml->dst),
            'zoneStart' => intval($xml->zoneStart),
            'zoneEnd' => intval($xml->zoneEnd),
            'nextAbbreviation' => strval($xml->nextAbbreviation),
            'timestamp' => intval($xml->timestamp),
            'formatted' => strval($xml->formatted),
        ];
    }
}
