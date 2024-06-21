<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Vesna\DataboxIntegrationChallenge\GitHub;
use Vesna\DataboxIntegrationChallenge\OpenWeatherMap;
use Vesna\DataboxIntegrationChallenge\DataboxIntegration;
use Vesna\DataboxIntegrationChallenge\Logger;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

Logger::log("Script started");

if ($argc < 2) {
    echo "Usage: php index.php <operation>\n";
    echo "Operations: weather, github\n";
    exit(1);
}

$operation = $argv[1];

if ($operation === 'weather') {
    // Fetch weather data from OpenWeatherMap
    $openWeatherMap = new OpenWeatherMap();
    $weatherData = $openWeatherMap->fetchData();
    print_r($weatherData);
    Logger::log("Fetched weather data: " . json_encode($weatherData));

    // Push data to Databox
    $dataToPush = $weatherData;

} elseif ($operation === 'github') {
    // Fetch data from GitHub
    $gitHub = new GitHub();
    $gitHubData = $gitHub->fetchData();
    print_r($gitHubData);
    Logger::log("Fetched GitHub data: " . json_encode($gitHubData));

    // Push data to Databox
    $dataToPush = $gitHubData;
} else {
    echo "Unknown operation: $operation\n";
    echo "Usage: php index.php <operation>\n";
    echo "Operations: weather, github\n";
    exit(1);
}

try {
    $databoxIntegration = new DataboxIntegration($_ENV['DATABOX_TOKEN']);
    $success = $databoxIntegration->pushData($dataToPush);
    if ($success) {
        echo "Data successfully pushed to Databox\n";
        Logger::log("Data successfully pushed to Databox");
    } else {
        echo "Failed to push data to Databox\n";
        Logger::log("Failed to push data to Databox");
    }
} catch (Exception $e) {
    echo "Error pushing data to Databox: " . $e->getMessage() . "\n";
    Logger::log("Error pushing data to Databox: " . $e->getMessage());
}

Logger::log("Script ended");
