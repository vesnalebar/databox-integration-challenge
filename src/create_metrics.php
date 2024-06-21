<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use Vesna\DataboxIntegrationChallenge\Logger;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class DataboxMetricsSetup
{
    private $client;
    private $existingMetrics = [];

    public function __construct($token)
    {
        if (!$token) {
            throw new \Exception('Databox token not provided.');
        }
        $this->client = new Client([
            'base_uri' => 'https://push.databox.com/',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($token . ':')
            ]
        ]);
        $this->existingMetrics = $this->fetchExistingMetrics();
    }

    private function fetchExistingMetrics()
    {
        try {
            $response = $this->client->get('metrics');
            $metrics = json_decode($response->getBody(), true);
            return array_column($metrics, 'key');
        } catch (\Exception $e) {
            Logger::log("Error fetching existing metrics: " . $e->getMessage());
            return [];
        }
    }

    private function createMetricIfNotExists($key, $name, $type = 'number', $dataType = 'float')
    {
        if (!in_array($key, $this->existingMetrics)) {
            try {
                $this->client->post('metrics', [
                    'json' => [
                        'key' => $key,
                        'name' => $name,
                        'type' => $type,
                        'data_type' => $dataType,
                        'unit' => ''
                    ]
                ]);
                $this->existingMetrics[] = $key;
                Logger::log("Metric created: $key");
            } catch (\Exception $e) {
                Logger::log("Error creating metric $key: " . $e->getMessage());
            }
        }
    }

    public function create_new_metrics($configFile)
    {
        $config = json_decode(file_get_contents($configFile), true);
        if (!$config) {
            throw new \Exception("Error reading config file: $configFile");
        }

        foreach ($config['metrics'] as $metric) {
            $this->createMetricIfNotExists($metric['key'], $metric['name'], $metric['type'], $metric['data_type']);
        }
    }
}

Logger::log("Metrics setup script started");

try {
    $databoxMetricsSetup = new DataboxMetricsSetup($_ENV['DATABOX_TOKEN']);
    $databoxMetricsSetup->create_new_metrics(__DIR__ . '/../config/new_metrics.json');
    Logger::log("Metrics setup completed successfully");
    echo "Metrics setup completed successfully\n";
} catch (Exception $e) {
    Logger::log("Error setting up metrics: " . $e->getMessage());
    echo "Error setting up metrics: " . $e->getMessage() . "\n";
}

Logger::log("Metrics setup script ended");
