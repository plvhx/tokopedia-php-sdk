<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests;

use Gandung\Tokopedia\ServiceInterface;
use Gandung\Tokopedia\Tests\Fixtures\ConcreteService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class ConcreteServiceTest extends TestCase
{
    /**
     * @var \Gandung\Tokopedia\ServiceInterface
     */
    private $service;

    private function getService()
    {
        return $this->service;
    }

    private function setService(ServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->setService(new ConcreteService());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
    }

    public function testCanGetBaseUrl()
    {
        $this->assertEquals('http://shit.org', $this->getService()->getBaseUrl());
    }

    public function testCanSetBaseUrl()
    {
        $this->getService()->setBaseUrl('http://example.com/nonexistent_path');
        $this->assertEquals(
            'http://example.com/nonexistent_path',
            $this->getService()->getBaseUrl()
        );
    }

    public function testCanGetFulfillmentServiceID()
    {
        $this->assertEquals(0, $this->getService()->getFulfillmentServiceID());
    }

    public function testCanSetFulfillmentServiceID()
    {
        $this->getService()->setFulfillmentServiceID(31337);
        $this->assertEquals(
            31337,
            $this->getService()->getFulfillmentServiceID()
        );
    }

    public function testCanGetClientID()
    {
        $this->assertEmpty($this->getService()->getClientID());
    }

    public function testCanSetClientID()
    {
        $this->getService()->setClientID('this_is_a_fake_client_id');
        $this->assertEquals(
            'this_is_a_fake_client_id',
            $this->getService()->getClientID()
        );
    }

    public function testCanGetClientSecret()
    {
        $this->assertEmpty($this->getService()->getClientSecret());
    }

    public function testCanSetClientSecret()
    {
        $this->getService()->setClientSecret('this_is_a_fake_client_secret');
        $this->assertEquals(
            'this_is_a_fake_client_secret',
            $this->getService()->getClientSecret()
        );
    }

    public function testCanGetEndpoint()
    {
        $this->assertEmpty($this->getService()->getEndpoint());
    }

    public function testCanSetEndpoint()
    {
        $this->getService()->setEndpoint('/foo/bar/baz');
        $this->assertEquals(
            '/foo/bar/baz',
            $this->getService()->getEndpoint()
        );
    }

    public function testCanGetHttpClient()
    {
        $this->assertInstanceOf(ClientInterface::class, $this->getService()->getHttpClient());
    }

    public function testCanSetHttpClient()
    {
        $this->getService()->setHttpClient(new Client([
            'handler' => new MockHandler()
        ]));
        $this->assertInstanceOf(ClientInterface::class, $this->getService()->getHttpClient());
    }

    public function testCanGetUri()
    {
        $this->getService()->setEndpoint('/foo/bar/baz');
        $this->assertEquals('/foo/bar/baz', $this->getService()->getUri()->getPath());
    }

    public function testCanGetContentsFromGivenResponseObject()
    {
        $this->assertEquals(
            'this_is_a_dummy_response',
            $this->getService()->getContents(new Response(200, [], 'this_is_a_dummy_response'))
        );
    }
}
