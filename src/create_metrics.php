<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Vesna\DataboxIntegrationChallenge\Logger;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class DataboxMetricsSetup
{
    private $client;

    public function __construct($token)
    {
        if (!$token) {
            throw new \Exception('Databox token not provided.');
        }
        $this->client = new Client([
            'base_uri' => 'https://push.databox.com/v1/',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($token . ':')
            ]
        ]);
    }

    private function createMetric($metric)
    {
        $key = $metric['key'];
        $name = $metric['name'];
        $type = $metric['type'] ?? 'total';
        $dataType = $metric['data_type'] ?? 'float';
        $unit = $metric['unit'] ?? '';
        $description = $metric['description'] ?? '';
        $aggregation = $metric['aggregation'] ?? 'latest';

        try {
            $this->client->post('metrics', [
                'json' => [
                    'key' => $key,
                    'name' => $name,
                    'type' => $type,
                    'data_type' => $dataType,
                    'unit' => $unit,
                    'description' => $description,
                    'aggregation' => $aggregation
                ]
            ]);
            Logger::log("Metric created: $key");
        } catch (RequestException $e) {
            Logger::log("Error creating metric $key: " . $e->getMessage());
            throw $e;
        }
    }

    public function create_new_metrics($configFile)
    {
        $config = json_decode(file_get_contents($configFile), true);
        if (!$config) {
            throw new \Exception("Error reading config file: $configFile");
        }

        foreach ($config['metrics'] as $metric) {
            $this->createMetric($metric);
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
