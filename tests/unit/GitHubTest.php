<?php

use PHPUnit\Framework\TestCase;
use Vesna\DataboxIntegrationChallenge\GitHub;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

class GitHubTest extends TestCase
{
    private $clientMock;
    private $github;

    protected function setUp(): void
    {
        // Mock the Guzzle Client
        $this->clientMock = $this->createMock(Client::class);

        // Replace the client in GitHub with the mock
        $this->github = new GitHub();
        $reflection = new \ReflectionClass($this->github);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->github, $this->clientMock);
    }

    public function testFetchData()
    {
        $userResponse = new Response(200, [], json_encode([
            'public_repos' => 10,
            'followers' => 100
        ]));

        $repoResponse = new Response(200, [], json_encode([
            'stargazers_count' => 500
        ]));

        // Set up the expectations for the Client
        $this->clientMock->expects($this->exactly(2))
            ->method('request')
            ->willReturnOnConsecutiveCalls($userResponse, $repoResponse);

        $data = $this->github->fetchData();

        $expected = [
            'freeCodeCamp_public_repos' => 10,
            'freeCodeCamp_followers' => 100,
            'freeCodeCamp_stars' => 500
        ];

        $this->assertEquals($expected, $data);
    }
}
