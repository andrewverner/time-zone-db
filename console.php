<?php
require_once 'vendor/autoload.php';

$facade = new \DK\Components\TimeZoneDB\Client\TimeZoneDBFacade(
    new \GuzzleHttp\Client(),
    new \DK\Components\TimeZoneDB\Factories\RequestFactory(),
    new \DK\Components\TimeZoneDB\ResponseParsers\JSONResponseParserStrategy(),
    'apiKey'
);

$pdo = new PDO('mysql:dbname=mirai;host=localhost', 'root', 'password');
$cityRepository = new \DK\Repositories\CityRepository($pdo);
$client = new \DK\Components\TimeZoneDB\TimeZoneDBClient($facade, $cityRepository);

$cities = $cityRepository->getAllCities();
foreach ($cities as $city) {
    sleep(1);

    $timeZoneData = $client->getTimeZoneByCityID($city->getId());
    if (!$timeZoneData) {
        echo sprintf('Time zone data for %s is empty', $city->getId());

        continue;
    }

    if (!$cityRepository->updateGtmDiffById($city->getId(), $timeZoneData->getGmtOffset())) {
        echo sprintf('Update for city %s has been failed', $city->getId()) . PHP_EOL;
    }
}

echo 'Job is done';
