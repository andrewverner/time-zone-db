<?php
require_once 'vendor/autoload.php';

$pdo = new PDO('mysql:dbname=dbname;host=localhost', 'root', 'password');
$cityRepository = new \DK\Repositories\CityRepository($pdo);

$timeStamp = new DateTimeImmutable();
$converter = new \DK\Components\TimeZoneConverter\TimeZoneConverter($cityRepository);
$localDate = $converter->getLocalCityTimeByCityId('4df26c3c-f627-4f39-b1f7-7af9438dbd54', $timeStamp);
if ($localDate) {
    echo $localDate->format('Y-m-d H:i:s') . PHP_EOL;
}

$utcDate = $converter->getUtcTimeByCityId('4df26c3c-f627-4f39-b1f7-7af9438dbd54', $timeStamp);
if ($utcDate) {
    echo $utcDate->format('Y-m-d H:i:s') . PHP_EOL;
}
