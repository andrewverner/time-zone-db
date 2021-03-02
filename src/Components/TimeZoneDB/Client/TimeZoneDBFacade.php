<?php

namespace DK\Components\TimeZoneDB\Client;

use DK\Components\TimeZoneDB\DTO\TimeZoneDTO;
use DK\Components\TimeZoneDB\Exceptions\TimeZoneDBException;
use DK\Components\TimeZoneDB\Factories\RequestFactoryInterface;
use DK\Components\TimeZoneDB\ResponseParsers\ResponseParserStrategyInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class TimeZoneDBFacade
 * @package DK\Components\TimeZoneDB
 */
final class TimeZoneDBFacade implements TimeZoneDBFacadeInterface
{
    /** @var string */
    private const ENDPOINT_URL = 'http://api.timezonedb.com/v2.1';

    /** @var string */
    private const REQUEST_GET_TIME_ZONE = 'get-time-zone';

    /** @var string */
    private $apiKey;

    /** @var ClientInterface */
    private ClientInterface $httpClient;

    /** @var RequestFactoryInterface */
    private RequestFactoryInterface $requestFactory;

    /** @var ResponseParserStrategyInterface */
    private ResponseParserStrategyInterface $responseParser;

    /**
     * TimeZoneDBFacade constructor.
     * @param ClientInterface $httpClient
     * @param RequestFactoryInterface $requestFactory
     * @param ResponseParserStrategyInterface $responseParser
     * @param string $apiKey
     */
    public function __construct(
        ClientInterface $httpClient,
        RequestFactoryInterface $requestFactory,
        ResponseParserStrategyInterface $responseParser,
        string $apiKey
    ) {
        $this->httpClient = $httpClient;
        $this->requestFactory = $requestFactory;
        $this->responseParser = $responseParser;
        $this->apiKey = $apiKey;
    }

    /** @inheritDoc */
    public function getTimeZoneByPosition(float $lat, float $lng): TimeZoneDTO
    {
        $requestUri = sprintf(
            '%s/%s?%s',
            self::ENDPOINT_URL,
            self::REQUEST_GET_TIME_ZONE,
            http_build_query([
                'key' => $this->apiKey,
                'format' => $this->responseParser->getResponseFormat(),
                'by' => 'position',
                'lat' => $lat,
                'lng' => $lng,
            ])
        );

        $request = $this->requestFactory->getGuzzleRequest('GET', $requestUri);

        try {
            $response = $this->sendRequest($request);
            $responseContent = $response->getBody()->getContents();
            $data = $this->responseParser->parse($responseContent);
            if (!$data) {
                throw new \Exception(sprintf('Invalid response data format: %s', $responseContent));
            }

            $dto = new TimeZoneDTO($data);

            if ($dto->getStatus() !== 'OK') {
                throw new \Exception('Invalid response status from the service');
            }

            return $dto;
        } catch (\Throwable $exception) {
            throw new TimeZoneDBException(sprintf(
                'An error has occurred: [%d] %s',
                $exception->getCode(),
                $exception->getMessage()
            ));
        }
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    private function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->httpClient->sendRequest($request);
    }
}
