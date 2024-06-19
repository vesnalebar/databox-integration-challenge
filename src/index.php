<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Vesna\DataboxIntegrationChallenge\GitHub;
use Vesna\DataboxIntegrationChallenge\OpenWeatherMap;
use Vesna\DataboxIntegrationChallenge\DataboxIntegration;
use Vesna\DataboxIntegrationChallenge\Logger;
use GuzzleHttp\Client;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

Logger::log("Script started");

// Instantiate the Guzzle client
$client = new Client([
    'headers' => [
        'Authorization' => 'token ' . $_ENV['GITHUB_TOKEN']
    ]
]);

// Fetch data from GitHub
$gitHub = new GitHub($client);
$gitHubData = $gitHub->fetchData();

// Fetch weather data from OpenWeatherMap
$client = new Client(); // New client for OpenWeatherMap
$openWeatherMap = new OpenWeatherMap($client);
$city = 'London'; // You can change this to any city you want
$weatherData = $openWeatherMap->fetchData($city);
print_r($weatherData);
Logger::log("Fetched weather data for $city: " . json_encode($weatherData));

// Combine data and push to Databox
$dataToPush = [
    'github_public_repos' => $gitHubData['public_repos'],
    'github_followers' => $gitHubData['followers'],
    'github_stars' => $gitHubData['stars'],
    'weather_temperature' => $weatherData['main']['temp'],
    'weather_humidity' => $weatherData['main']['humidity'],
    'weather_pressure' => $weatherData['main']['pressure']
];

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
