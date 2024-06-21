<?php

namespace Vesna\DataboxIntegrationChallenge;

use GuzzleHttp\Client;

class GitHub
{
    private $metrics;
    private $username;
    private $repository;
    private $baseUrl;
    private $client;

    public function __construct()
    {
        $config = json_decode(file_get_contents(__DIR__ . '/../config/github.json'), true);
        $this->metrics = $config['metrics'];
        $this->username = $config['username'];
        $this->repository = $config['repository'];
        $this->baseUrl = $config['base_url'];
        $this->client = new Client();
    }

    public function fetchData()
    {
        $data = [];

        // Fetch user data
        $response = $this->client->request('GET', $this->baseUrl . '/users/' . $this->username);
        $userData = json_decode($response->getBody(), true);

        foreach ($this->metrics as $metric) {
            if (isset($metric['type']) && $metric['type'] === 'count') {
                // Fetch repositories data for counting
                $response = $this->client->request('GET', $this->baseUrl . '/repos/' . $this->username . '/' . $this->repository);
                $repoData = json_decode($response->getBody(), true);
                $count = $repoData[$metric['path']] ?? 0;
                $data[$this->username . '_' . $metric['key']] = $count;
            } else {
                $data[$this->username . '_' . $metric['key']] = $userData[$metric['path']] ?? null;
            }

            if ($data[$this->username . '_' . $metric['key']] === null) {
                Logger::log("Metric {$this->username}_{$metric['key']} is null and will not be sent to Databox.");
                unset($data[$this->username . '_' . $metric['key']]);
            }
        }

        return $data;
    }
}
