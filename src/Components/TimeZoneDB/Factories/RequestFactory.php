<?php

namespace DK\Components\TimeZoneDB\Factories;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;

/**
 * Class RequestFactory
 * @package DK\Components\TimeZoneDB\Factories
 */
class RequestFactory implements RequestFactoryInterface
{
    /** @inheritDoc */
    public function getGuzzleRequest(string $method, string $uri, array $headers = [], $body = null): RequestInterface
    {
        return new Request($method, $uri, $headers, $body);
    }
}