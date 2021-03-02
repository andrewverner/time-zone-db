<?php

namespace DK\Components\TimeZoneDB\Factories;

use Psr\Http\Message\RequestInterface;

/**
 * Interface RequestFactoryInterface
 * @package DK\Components\TimeZoneDB\Factories
 */
interface RequestFactoryInterface
{
    /**
     * @param $method
     * @param $uri
     * @param array $headers
     * @param null $body
     * @return RequestInterface
     */
    public function getGuzzleRequest(string $method, string $uri, array $headers = [], $body = null): RequestInterface;
}
