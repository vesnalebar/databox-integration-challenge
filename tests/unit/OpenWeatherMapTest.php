<?php

use PHPUnit\Framework\TestCase;
use Vesna\DataboxIntegrationChallenge\OpenWeatherMap;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class OpenWeatherMapTest extends TestCase
{
    private $clientMock;
    private $openWeatherMap;

    protected function setUp(): void
    {
        // Mock the Guzzle Client
        $this->clientMock = $this->createMock(Client::class);

        // Replace the client in OpenWeatherMap with the mock
        $this->openWeatherMap = new OpenWeatherMap();
        $reflection = new \ReflectionClass($this->openWeatherMap);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->openWeatherMap, $this->clientMock);

        // Set the environment variable for the test
        $_ENV['OPENWEATHERMAP_API_KEY'] = 'test_api_key';
    }

    public function testFetchData()
    {
        $weatherResponse = new Response(200, [], json_encode([
            'main' => [
                'temp' => 15,
                'humidity' => 60,
                'pressure' => 1012
            ],
            'wind' => [
                'speed' => 5.5
            ]
        ]));

        // Set up the expectations for the Client
        $this->clientMock->expects($this->once())
            ->method('request')
            ->with(
                'GET',
                'https://api.openweathermap.org/data/2.5/weather',
                [
                    'query' => [
                        'q' => 'Beltinci',
                        'appid' => 'test_api_key',
                        'units' => 'metric'
                    ]
                ]
            )
            ->willReturn($weatherResponse);

        $data = $this->openWeatherMap->fetchData();

        $expected = [
            'Beltinci_temp' => 15,
            'Beltinci_humidity' => 60,
            'Beltinci_pressure' => 1012,
            'Beltinci_wind_speed' => 5.5
        ];

        $this->assertEquals($expected, $data);
    }
}
