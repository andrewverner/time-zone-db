<?php

namespace DK\Components\TimeZoneDB\Client;

use DK\Components\TimeZoneDB\DTO\GetTimeZoneDTO;
use DK\Components\TimeZoneDB\Exceptions\TimeZoneDBException;
use DK\Components\TimeZoneDB\Factories\RequestFactoryInterface;
use DK\Components\TimeZoneDB\ResponseParsers\ResponseParserInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TimeZoneDBClient
 * @package DK\Components\TimeZoneDB
 */
final class TimeZoneDBClient
{
    /** @var string */
    private const API_KEY = '0QS917JBBYT7';

    /** @var string */
    private const ENDPOINT_URL = 'http://api.timezonedb.com/v2.1';

    private const REQUEST_GET_TIME_ZONE = 'get-time-zone';

    /** @var ClientInterface */
    private ClientInterface $httpClient;

    /** @var RequestFactoryInterface */
    private RequestFactoryInterface $requestFactory;

    /** @var ResponseParserInterface */
    private ResponseParserInterface $responseParser;

    /**
     * TimeZoneDBClient constructor.
     * @param ClientInterface $httpClient
     * @param RequestFactoryInterface $requestFactory
     * @param ResponseParserInterface $responseParser
     */
    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        ResponseParserInterface $responseParser
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->responseParser = $responseParser;
    }

    /**
     * @param float $lat
     * @param float $lng
     * @return GetTimeZoneDTO
     * @throws TimeZoneDBException
     */
    public function getTimeZoneByPosition(float $lat, float $lng): GetTimeZoneDTO
    {
        $requestUri = sprintf(
            '%s/%s?%s',
            self::ENDPOINT_URL,
            self::REQUEST_GET_TIME_ZONE,
            http_build_query([
                'key' => self::API_KEY,
                'format' => $this->responseParser->getResponseFormat(),
                'by' => 'position',
                'lat' => $lat,
                'lng' => $lng,
            ])
        );

        $request = $this->requestFactory->getGuzzleRequest('GET', $requestUri);

        try {
            $response = $this->sendRequest($request);
            $data = $this->responseParser->parse($response->getBody()->getContents());
            $dto = new GetTimeZoneDTO($data);

            if ($dto->getStatus() !== 'OK') {
                throw new TimeZoneDBException('Invalid response status from the service');
            }

            return $dto;
        } catch (\Throwable $exception) {
            throw new TimeZoneDBException(sprintf(
                'An error has occurred while sending a request: [%d] %s',
                $exception->getCode(),
                $exception->getMessage()
            ));
        }
    }

    private function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }
}
