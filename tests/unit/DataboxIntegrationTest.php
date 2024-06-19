<?php

use PHPUnit\Framework\TestCase;
use Vesna\DataboxIntegrationChallenge\DataboxIntegration;
use Databox\Client;

class DataboxIntegrationTest extends TestCase
{
    private $databox;
    private $clientMock;

    protected function setUp(): void
    {
        // Create a mock for the Databox\Client class
        $this->clientMock = $this->createMock(Client::class);

        // Use reflection to inject the mock client into DataboxIntegration
        $this->databox = new DataboxIntegration('fake_token');
        $reflection = new \ReflectionClass($this->databox);
        $property = $reflection->getProperty('client');
        $property->setAccessible(true);
        $property->setValue($this->databox, $this->clientMock);
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(DataboxIntegration::class, $this->databox);
    }

    public function testPushData()
    {
        $this->clientMock->method('push')
                         ->willReturn(true);

        $data = [
            'metric_key' => 'metric_value'
        ];

        $result = $this->databox->pushData($data);
        $this->assertTrue($result);
    }
}
