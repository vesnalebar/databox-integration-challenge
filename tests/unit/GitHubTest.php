<?php

use PHPUnit\Framework\TestCase;
use Vesna\DataboxIntegrationChallenge\GitHub;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class GitHubTest extends TestCase {
    public function testFetchDataStructure() {
        // Mock the Guzzle client
        $mock = new MockHandler([
            new Response(200, [], json_encode([
                'public_repos' => 5,
                'followers' => 10,
                'login' => 'testuser'
            ])),
            new Response(200, [], json_encode([
                ['stargazers_count' => 1],
                ['stargazers_count' => 2],
                ['stargazers_count' => 3]
            ]))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);

        // Pass the mocked client to GitHub
        $gitHub = new GitHub($client);
        $data = $gitHub->fetchData();

        $this->assertArrayHasKey('public_repos', $data);
        $this->assertArrayHasKey('followers', $data);
        $this->assertArrayHasKey('stars', $data);
    }
}
