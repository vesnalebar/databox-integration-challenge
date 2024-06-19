<?php

namespace Vesna\DataboxIntegrationChallenge;

use GuzzleHttp\Client;

class OpenWeatherMap {
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchData($city) {
        $response = $this->client->request('GET', 'https://api.openweathermap.org/data/2.5/weather', [
            'query' => [
                'q' => $city,
                'appid' => $_ENV['OPENWEATHERMAP_API_KEY'],
                'units' => 'metric'
            ]
        ]);

        return json_decode($response->getBody(), true);
    }
}
