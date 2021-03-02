<?php
require_once 'vendor/autoload.php';

$pdo = new PDO('mysql:dbname=mirai;host=localhost', 'root', 'password');
$cityRepository = new \DK\Repositories\CityRepository($pdo);

$timeStamp = new DateTimeImmutable('2021-02-10 12:00:00');
$converter = new \DK\Components\TimeZoneConverter\TimeZoneConverter($cityRepository);
$localDate = $converter->getLocalCityTimeByCityId('47b90fca-4963-4320-8e24-cb8201950d41', $timeStamp);
if ($localDate) {
    echo $localDate->format('Y-m-d H:i:s') . PHP_EOL;
}

$utcDate = $converter->getUtcTimeByCityId('47b90fca-4963-4320-8e24-cb8201950d41', $timeStamp);
if ($utcDate) {
    echo $utcDate->format('Y-m-d H:i:s') . PHP_EOL;
}
