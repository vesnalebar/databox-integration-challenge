<?php

use PHPUnit\Framework\TestCase;
use Vesna\DataboxIntegrationChallenge\OpenWeatherMap;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class OpenWeatherMapTest extends TestCase {
    protected function setUp(): void {
        // Set environment variable for the test
        $_ENV['OPENWEATHERMAP_API_KEY'] = 'test_api_key';
    }

    public function testFetchDataStructure() {
        // Mock the Guzzle client
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'main' => [
                    'temp' => 15.0,
                    'humidity' => 80,
                    'pressure' => 1012
                ]
            ]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // Pass the mocked client to OpenWeatherMap
        $openWeatherMap = new OpenWeatherMap($client);
        $data = $openWeatherMap->fetchData('London');

        $this->assertArrayHasKey('main', $data);
        $this->assertArrayHasKey('temp', $data['main']);
        $this->assertArrayHasKey('humidity', $data['main']);
        $this->assertArrayHasKey('pressure', $data['main']);
    }
}
