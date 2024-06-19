<?php

namespace Vesna\DataboxIntegrationChallenge;

use GuzzleHttp\Client;

class GitHub {
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function fetchData() {
        // Fetch user data
        $response = $this->client->request('GET', 'https://api.github.com/user');
        $userData = json_decode($response->getBody(), true);

        // Fetch repositories data
        $response = $this->client->request('GET', 'https://api.github.com/user/repos');
        $repoData = json_decode($response->getBody(), true);

        // Calculate total stars from repositories
        $stars = 0;
        foreach ($repoData as $repo) {
            $stars += $repo['stargazers_count'];
        }

        return [
            'public_repos' => $userData['public_repos'],
            'followers' => $userData['followers'],
            'stars' => $stars
        ];
    }
}
