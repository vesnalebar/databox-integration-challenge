<?php

namespace Vesna\DataboxIntegrationChallenge;

use GuzzleHttp\Client;

class OpenWeatherMap
{
    private $city;
    private $metrics;
    private $client;
    private $baseUrl;

    public function __construct()
    {
        $config = json_decode(file_get_contents(__DIR__ . '/../config/weather.json'), true);
        $this->city = $config['city'];
        $this->metrics = $config['metrics'];
        $this->baseUrl = $config['base_url'];
        $this->client = new Client();
    }

    public function fetchData()
    {
        $response = $this->client->request('GET', $this->baseUrl, [
            'query' => [
                'q' => $this->city,
                'appid' => $_ENV['OPENWEATHERMAP_API_KEY'],
                'units' => 'metric'
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        Logger::log("Fetched raw weather data: " . json_encode($data));
        return $this->extractMetrics($data);
    }

    private function extractMetrics($data)
    {
        $extracted = [];
        foreach ($this->metrics as $key => $path) {
            $citySpecificKey = $this->city . '_' . $key;
            $value = $this->getValueFromPath($data, $path);
            if ($value === null) {
                Logger::log("Metric {$citySpecificKey} is null and will not be sent to Databox.");
            } else {
                $extracted[$citySpecificKey] = $value;
            }
        }
        Logger::log("Processed weather data to send: " . json_encode($extracted));
        return $extracted;
    }

    private function getValueFromPath($data, $path)
    {
        $keys = explode('.', $path);
        foreach ($keys as $key) {
            if (!isset($data[$key])) {
                return null;
            }
            $data = $data[$key];
        }
        return $data;
    }
}
