<?php

namespace Vesna\DataboxIntegrationChallenge;

use Databox\Client;

class DataboxIntegration
{
    private $client;

    public function __construct($token)
    {
        if (!$token) {
            throw new \Exception('Databox token not provided.');
        }
        $this->client = new Client($token);
    }

    public function pushData(array $data)
    {
        foreach ($data as $key => $value) {
            $response = $this->client->push($key, $value);
            if (!$response) {
                Logger::log("Failed to push $key to Databox");
                return false;
            }
            Logger::log("Successfully pushed $key to Databox");
        }
        return true;
    }
}
