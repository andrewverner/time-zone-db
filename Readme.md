# Конвертер временной зоны

## Запуск проекта

Подойдёт любое окружение с PHP7.4+ и MySQL5.6+

## Входные скрипты

`index.php` - веб с демонстрацией конвертации времени

`console.php` - консольная версия для запуска задачи обновления дыннх БД по крону `php console.php`

## Настройка подключения к БД

В `index.php` и `console.php` создаётся экземпляр PDO, куда передаются необходимые параметры.
*Можно было бы потратить больше времени и использовать `doctrine-orm` или же делегировать
создание экземпляра PDO одиночке, но лезть настолько глубоко не стал*

После того, как всё развёрнуто, необходимо испортировать `city.sql` в БД и запустить `composer`

`mysql -u <username> -p<password> <database> < city.sql`

`composer install`

`composer dump-autoload`

В `console.php` перед запуском в 8 строке необходимо указать валидный API-ключ

## Работа

### Репозиторий городов

```php
$pdo = new PDO('mysql:dbname=dbname;host=localhost', 'username', 'password');
$cityRepository = new \DK\Repositories\CityRepository($pdo);

//получение полного списка городов
$cityRepository->getAllCities();

//Получение города по идентификатору
$cityRepository->getCityById('80defa05-74a0-4624-9d8d-d275407f6f11');

//Обновление смещения относительно UTC по идентификатору
$cityRepository->updateGtmDiffById('80defa05-74a0-4624-9d8d-d275407f6f11', -18000);
```

### Конвертер временных зон

```php
$pdo = new PDO('mysql:dbname=dbname;host=localhost', 'username', 'password');
$cityRepository = new \DK\Repositories\CityRepository($pdo);
$converter = new \DK\Components\TimeZoneConverter\TimeZoneConverter($cityRepository);

//Получения локального времени в городе по переданному идентификатору города и метке времени по UTC+0
$converter->getLocalCityTimeByCityId('80defa05-74a0-4624-9d8d-d275407f6f11', new DateTimeImmutable());

//Обратное преобразование из локального времени и идентификатора города в метку времени по UTC+0
$converter->getUtcTimeByCityId('80defa05-74a0-4624-9d8d-d275407f6f11', new DateTimeImmutable());
```

### Фасад для работы с API

```php
$facade = new \DK\Components\TimeZoneDB\Client\TimeZoneDBFacade(
    new \GuzzleHttp\Client(),
    new \DK\Components\TimeZoneDB\Factories\RequestFactory(),
    new \DK\Components\TimeZoneDB\ResponseParsers\JSONResponseParserStrategy(),
    'apiKey'
);

//Получение временной зоны по координатам
$facade->getTimeZoneByPosition(29.5067, -95.4767);
```

### Клиент для работы с API

```php
$facade = new \DK\Components\TimeZoneDB\Client\TimeZoneDBFacade(
    new \GuzzleHttp\Client(),
    new \DK\Components\TimeZoneDB\Factories\RequestFactory(),
    new \DK\Components\TimeZoneDB\ResponseParsers\JSONResponseParserStrategy(),
    'apiKey'
);

$pdo = new PDO('mysql:dbname=mirai;host=localhost', 'root', 'password');
$cityRepository = new \DK\Repositories\CityRepository($pdo);
$client = new \DK\Components\TimeZoneDB\TimeZoneDBClient($facade, $cityRepository);

//Получение временной зоны по идентификатору города
$client->getTimeZoneByCityID('80defa05-74a0-4624-9d8d-d275407f6f11');
```

## API

### DK\Repositories\CityRepository

Репозиторий для работы с таблицей городов.

| Реализация интерфейса | DK\Repositories\CityRepositoryInterface |
|---|---|

| Свойство | Тип | Описание |
|---|---|---|
| private $pdo | \PDO | Экземпляр \PDO |

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public __construct | \PDO $pdo | | |
| public getCityById | string $id | Получение экземпляра DTO города по его идентификатору | DK\Repositories\DTO\CityDTO |
| public getAllCities | | Получение массива всех городов | DK\Repositories\DTO\CityDTO[] |
| public updateGtmDiffById | string $id, int $gtmDiff | Обновление значения смещения часовой зоны относительно UTC по идентификатору | bool |

### DK\Repositories\DTO\CityDTO

DTO города

| Свойство | Тип | Описание |
|---|---|---|
| private $id | string | Идентификатор |
| private $name | string | Имя |
| private $latitude | string | Широта |
| private $longitude | string | Долгота |
| private $gtm_diff | string | Смещение относительно UTC |

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public __construct | array $data | | |
| public getId | | Получение идентфиикатора | string |
| public getName | | Получение имени | string |
| public getLatitude | | Получение широты | string |
| public getLongitude | | Получение долготы | string |
| public getGtmDiff | | Получение смещения относительно UTC | int |

### DK\Components\TimeZoneConverter\TimeZoneConverter

Конвертер временной зоны

| Свойство | Тип | Описание |
|---|---|---|
| private $cityRepository | DK\Repositories\CityRepositoryInterface | Экземпляр репозитория городов |

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public __construct | DK\Repositories\CityRepositoryInterface $cityRepository | | |
| public getLocalCityTimeByCityId | string $id, \DateTimeImmutable $timeStamp | Получения локального времени в городе по переданному идентификатору города и метке времени по UTC+0 | DateTimeImmutable |
| public getUtcTimeByCityId | string $id, \DateTimeImmutable $timeStamp | Обратное преобразование из локального времени и идентификатора города в метку времени по UTC+0 | DateTimeImmutable |

### DK\Components\TimeZoneDB\TimeZoneDBClient

Клиент для работы с API TimeZoneDB

| Свойство | Тип | Описание |
|---|---|---|
| private $timeZoneDBFacade | DK\Components\TimeZoneDB\Client\TimeZoneDBFacadeInterface | Фасад для отправки и обработки ответов от API |
| private $cityRepository | DK\Repositories\CityRepositoryInterface | Репозиторий городов |
| private $error | Throwable | Хранит экземпляр ошибки, если такие произошли во время работы |

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public __construct | DK\Components\TimeZoneDB\Client\TimeZoneDBFacadeInterface $timeZoneDBFacade, Dk\Repositories\CityRepositoryInterface $cityRepository | | |
| public getTimeZoneByCityID | string $cityId | Получение DTO ответа от TimeZoneDB по идентификатору города | DK\Components\TimeZoneDB\DTO\TimeZoneDTO |
| public getError | | Получение экземпляра ошибки | Throwable |

### DK\Components\TimeZoneDB\Client\TimeZoneDBFacade

Фасад для работы с API

| Реализация интерфейса | DK\Components\TimeZoneDB\Client\TimeZoneDBFacadeInterface |
|---|---|

| Свойство | Тип | Описание |
|---|---|---|
| private $apiKey | string | Ключ API |
| private $httpClient | Psr\Http\Client\ClientInterface | Экземпляр http-клиента |
| private $requestFactory | DK\Components\TimeZoneDB\Factories\RequestFactoryInterface | Экземпляр фабрики запросов |
| private $responseParser | DK\Components\TimeZoneDB\ResponseParsers\ResponseParserStrategyInterface | Экземпляр стратегии обработки ответа |

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public __construct | Psr\Http\Client\ClientInterface $httpClient, DK\Components\TimeZoneDB\Factories\RequestFactoryInterface $requestFactory, DK\Components\TimeZoneDB\ResponseParsers\ResponseParserStrategyInterface $responseParser, string $apiKey | | |
| public getTimeZoneByPosition | float $lat, float $lng | Получение данных временной зоны по координатам | DK\Components\TimeZoneDB\DTO\TimeZoneDTO |
| private sendRequest | Psr\Http\Message\RequestInterface $request | Отправка запроса | Psr\Http\Message\ResponseInterface |

### DK\Components\TimeZoneDB\DTO\TimeZoneDTO

DTO ответа от API

| Свойство | Тип | Описание |
|---|---|---|
| private $status | string | Статус ответа |
| private $message | string | Сообщение |
| private $countryCode | string | Код страны |
| private $countryName | string | Имя страны |
| private $regionName | string | Имя региона |
| private $cityName | string | Имя города |
| private $zoneName | string | Имя зоны |
| private $abbreviation | string | Аббревиатура зоны |
| private $gmtOffset | int | Смещение относительно UTC |
| private $dst | int | Флаг использования летнего времени |
| private $zoneStart | int | UNIX метка времени начала зоны |
| private $zoneEnd | int | UNIX метка времени конца зоны |
| private $nextAbbreviation | string | |
| private $timestamp | int | UNIX метка локального времени |
| private $formatted | string | Отформатированная строка даты-времени **Y-m-d h:i:s** |

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public __construct | array $data | | |
| public getStatus | | Получение статуса ответа | string |
| public getMessage | | Получение сообщения | string |
| public getCountryCode | | Получение кода страны | string |
| public getCountryName | | Получение имени сттаны | string |
| public getRegionName | | Получение имени региона | string |
| public getCityName | | Получение имени города | string |
| public getZoneName | | Получение имени зоны | string |
| public getAbbreviation | | Получение аббревиатуры зоны | string |
| public getGmtOffset | | Получение смещения относительно UTC | int |
| public getDst | | Получение флага использования леинего времени | int |
| public getZoneStart | | Получение UNIX метки времени начала зоны | int |
| public getZoneEnd | | Получение UNIX метки времени конца зоны | int |
| public getNextAbbreviation | | | string |
| public getTimestamp | | Получение UNIX метки локального времени | int |
| public getFormatted | | Получение отформатированной строки даты-времени | string |

### DK\Components\TimeZoneDB\Exceptions\TimeZoneDBException

Наследник `Exception` для обозначения ошибок клиента API

### DK\Components\TimeZoneDB\Factories\RequestFactory

Фабрика запросов

| Реализация интерфейса | DK\Components\TimeZoneDB\Factories\RequestFactoryInterface |
|---|---|

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public getGuzzleRequest | string $method, string $uri, array $headers = [], $body = null | Получение экземпляра запроса Guzzle | Psr\Http\Message\RequestInterface |

### DK\Components\TimeZoneDB\ResponseParsers\JSONResponseParserStrategy

JSON парсер ответа от API

| Реализация интерфейса | DK\Components\TimeZoneDB\ResponseParsers\ResponseParserStrategyInterface |
|---|---|

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public getResponseFormat | | Получение строкового кода для JSON ответа от API | string |
| public parse | string $data | Разбор ответа от API | array |

### DK\Components\TimeZoneDB\ResponseParsers\XMLResponseParserStrategy

XML парсер ответа от API

| Реализация интерфейса | DK\Components\TimeZoneDB\ResponseParsers\ResponseParserStrategyInterface |
|---|---|

| Метод | Аргументы | Описание | Возвращаемый результат |
|---|---|---|---|
| public getResponseFormat | | Получение строкового кода для XML ответа от API | string |
| public parse | string $data | Разбор ответа от API | array |
