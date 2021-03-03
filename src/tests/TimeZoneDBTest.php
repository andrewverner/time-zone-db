<?php
namespace DK\tests;

use Codeception\Test\Unit;
use DK\Components\TimeZoneConverter\TimeZoneConverter;
use DK\Components\TimeZoneDB\Client\TimeZoneDBFacade;
use DK\Components\TimeZoneDB\Client\TimeZoneDBFacadeInterface;
use DK\Components\TimeZoneDB\DTO\TimeZoneDTO;
use DK\Components\TimeZoneDB\Exceptions\TimeZoneDBException;
use DK\Components\TimeZoneDB\Factories\RequestFactory;
use DK\Components\TimeZoneDB\ResponseParsers\JSONResponseParserStrategy;
use DK\Components\TimeZoneDB\TimeZoneDBClient;
use DK\Repositories\CityRepositoryInterface;
use DK\Repositories\DTO\CityDTO;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class TimeZoneDBTest extends Unit
{
    /** @var \DK\tests\UnitTester */
    protected $tester;

    public function testCityRepository()
    {
        $cityRepository = $this->getCityRepositoryMock();
        $cityDTO = $cityRepository->getCityById('someId');

        $this->assertInstanceOf(CityDTO::class, $cityRepository->getCityById('someId'));
        $this->assertSame('someId', $cityDTO->getId());
        $this->assertSame('City name', $cityDTO->getName());
        $this->assertSame('50', $cityDTO->getLatitude());
        $this->assertSame('-25', $cityDTO->getLongitude());
        $this->assertSame(-18000, $cityDTO->getGtmDiff());
    }

    public function testTimeDBFacadeOK()
    {
        $guzzleClient = $this->getGuzzleClientMock();
        $facade = new TimeZoneDBFacade($guzzleClient, new RequestFactory(), new JSONResponseParserStrategy(), 'apiKey');

        $this->assertInstanceOf(TimeZoneDBFacadeInterface::class, $facade);
        $timeZoneData = $facade->getTimeZoneByPosition(50, -25);
        $this->assertInstanceOf(TimeZoneDTO::class, $timeZoneData);
        $this->assertSame('OK', $timeZoneData->getStatus());
        $this->assertSame(-18000, $timeZoneData->getGmtOffset());
    }

    public function testTimeDBFacadeError()
    {
        $guzzleClient = $this->getGuzzleClientMock(200, 'Error');
        $facade = new TimeZoneDBFacade($guzzleClient, new RequestFactory(), new JSONResponseParserStrategy(), 'apiKey');

        $exception = null;
        $this->assertInstanceOf(TimeZoneDBFacadeInterface::class, $facade);
        try {
            $facade->getTimeZoneByPosition(50, -25);
        } catch (\Throwable $exception) {
        }
        $this->assertInstanceOf(TimeZoneDBException::class, $exception);
    }

    public function testTimeZoneDBClient()
    {
        $guzzleClient = $this->getGuzzleClientMock();
        $facade = new TimeZoneDBFacade($guzzleClient, new RequestFactory(), new JSONResponseParserStrategy(), 'apiKey');

        $client = new TimeZoneDBClient($facade, $this->getCityRepositoryMock());
        $this->assertInstanceOf(TimeZoneDBClient::class, $client);

        $timeZoneDTO = $client->getTimeZoneByCityID('someId');
        $this->assertInstanceOf(TimeZoneDTO::class, $timeZoneDTO);
        $this->assertSame('OK', $timeZoneDTO->getStatus());
        $this->assertSame(-18000, $timeZoneDTO->getGmtOffset());
    }

    public function testTimeZoneConverter()
    {
        $cityRepository = $this->getCityRepositoryMock();
        $converter = new TimeZoneConverter($cityRepository);
        $city = $cityRepository->getCityById('someId');

        $timeStamp = new \DateTimeImmutable();
        $localTimeStamp = $timeStamp->setTimestamp($timeStamp->getTimestamp() + $city->getGtmDiff())
            ->format('Y-m-d H:i:s');
        $utcTimeStamp = $timeStamp->setTimestamp($timeStamp->getTimestamp() - $city->getGtmDiff())
            ->format('Y-m-d H:i:s');

        $this->assertSame($localTimeStamp, $converter->getLocalCityTimeByCityId('someId', $timeStamp)->format('Y-m-d H:i:s'));
        $this->assertSame($utcTimeStamp, $converter->getUtcTimeByCityId('someId', $timeStamp)->format('Y-m-d H:i:s'));
    }

    private function getCityRepositoryMock()
    {
        $cityRepository = $this->createMock(CityRepositoryInterface::class);
        $cityDTO = new CityDTO([
            'id' => 'someId',
            'name' => 'City name',
            'latitude' => 50,
            'longitude' => -25,
            'gtm_diff' => -18000,
        ]);
        $cityRepository->method('getCityById')->willReturn($cityDTO);

        return $cityRepository;
    }

    public function getGuzzleClientMock($statusCode = 200, $status = 'OK', $gmtOffset = -18000)
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getContents')->willReturn(json_encode([
            'status' => $status,
            'message' => '',
            'countryCode' => 'US',
            'countryName' => 'United States',
            'regionName' => 'New York',
            'cityName' => 'Statue of Liberty',
            'zoneName' => 'America/New_York',
            'abbreviation' => 'EST',
            'gmtOffset' => $gmtOffset,
            'dst' => 0,
            'zoneStart' => 1604210400,
            'zoneEnd' => 1615705200,
            'nextAbbreviation' => 'EDT',
            'timestamp' => 1614692725,
            'formatted' => '2021-03-02 13:45:25',
        ]));

        $responseGuzzle = $this->createMock(ResponseInterface::class);
        $responseGuzzle->method('getStatusCode')->willReturn($statusCode);
        $responseGuzzle->method('getBody')->willReturn($stream);

        $guzzleClient = $this->createMock(Client::class);
        $guzzleClient->method('sendRequest')->willReturn($responseGuzzle);

        return $guzzleClient;
    }
}
