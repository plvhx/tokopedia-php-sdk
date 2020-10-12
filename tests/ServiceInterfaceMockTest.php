<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests;

use Gandung\Tokopedia\ServiceInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Uri;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class ServiceInterfaceMockTest extends TestCase
{
    /**
     * @var object
     */
    private $service;

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function getMockedService()
    {
        return $this->service;
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $mockObject
     * @return void
     */
    private function setMockedService(MockObject $mockObject)
    {
        $this->service = $mockObject;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->setMockedService($this->createMock(ServiceInterface::class));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
    }

    public function testCanMockGetBaseUrl()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('getBaseUrl')
            ->will($this->returnValue(null));

        $this->assertNull($this->getMockedService()->getBaseUrl());
    }

    public function testCanMockSetBaseUrl()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('setBaseUrl')
            ->withConsecutive([$this->equalTo('https://example.com')]);

        $this->getMockedService()
            ->expects($this->once())
            ->method('getBaseUrl')
            ->will($this->returnValue('https://example.com'));

        $this->getMockedService()->setBaseUrl('https://example.com');
        $this->assertEquals('https://example.com', $this->getMockedService()->getBaseUrl());
    }

    public function testCanMockGetFulfillmentServiceID()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('getFulfillmentServiceID')
            ->will($this->returnValue(0));

        $this->assertEquals(0, $this->getMockedService()->getFulfillmentServiceID());
    }

    public function testCanMockSetFulfillmentServiceID()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('setFulfillmentServiceID')
            ->withConsecutive([$this->equalTo(31337)]);

        $this->getMockedService()
            ->expects($this->once())
            ->method('getFulfillmentServiceID')
            ->will($this->returnValue(31337));

        $this->getMockedService()->setFulfillmentServiceID(31337);
        $this->assertEquals(31337, $this->getMockedService()->getFulfillmentServiceID());
    }

    public function testCanMockGetClientID()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('getClientID')
            ->will($this->returnValue(null));

        $this->assertNull($this->getMockedService()->getClientID());
    }

    public function testCanMockSetClientID()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('setClientID')
            ->withConsecutive([$this->equalTo('this_is_a_fake_client_id')]);

        $this->getMockedService()
            ->expects($this->once())
            ->method('getClientID')
            ->will($this->returnValue('this_is_a_fake_client_id'));

        $this->getMockedService()->setClientID('this_is_a_fake_client_id');
        $this->assertEquals('this_is_a_fake_client_id', $this->getMockedService()->getClientID());
    }

    public function testCanMockGetClientSecret()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('getClientSecret')
            ->will($this->returnValue(null));

        $this->assertNull($this->getMockedService()->getClientSecret());
    }

    public function testCanMockSetClientSecret()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('setClientSecret')
            ->withConsecutive([$this->equalTo('this_is_a_fake_client_secret')]);

        $this->getMockedService()
            ->expects($this->once())
            ->method('getClientSecret')
            ->will($this->returnValue('this_is_a_fake_client_secret'));

        $this->getMockedService()->setClientSecret('this_is_a_fake_client_secret');
        $this->assertEquals(
            'this_is_a_fake_client_secret',
            $this->getMockedService()->getClientSecret()
        );
    }

    public function testCanMockGetEndpoint()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('getEndpoint')
            ->will($this->returnValue(null));

        $this->assertNull($this->getMockedService()->getEndpoint());
    }

    public function testCanMockSetEndpoint()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('setEndpoint')
            ->withConsecutive([$this->equalTo('/foo/bar/baz')]);

        $this->getMockedService()
            ->expects($this->once())
            ->method('getEndpoint')
            ->will($this->returnValue('/foo/bar/baz'));

        $this->getMockedService()->setEndpoint('/foo/bar/baz');
        $this->assertEquals('/foo/bar/baz', $this->getMockedService()->getEndpoint());
    }

    public function testCanMockGetHttpClient()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('getHttpClient')
            ->will($this->returnValue(null));

        $this->assertNull($this->getMockedService()->getHttpClient());
    }

    public function testCanMockSetHttpClient()
    {
        $mockedHttpClient = new Client(['handler' => new MockHandler()]);

        $this->getMockedService()
            ->expects($this->once())
            ->method('setHttpClient')
            ->withConsecutive([$this->equalTo($mockedHttpClient)]);

        $this->getMockedService()
            ->expects($this->once())
            ->method('getHttpClient')
            ->will($this->returnValue($mockedHttpClient));

        $this->getMockedService()->setHttpClient($mockedHttpClient);
        $this->assertInstanceOf(
            ClientInterface::class,
            $this->getMockedService()->getHttpClient()
        );
    }

    public function testCanMockGetUri()
    {
        $this->getMockedService()
            ->expects($this->once())
            ->method('getUri')
            ->will($this->returnValue(new Uri('http://example.com/foo/bar/baz')));

        $this->assertInstanceOf(Uri::class, $this->getMockedService()->getUri());
    }

    public function testCanMockGetContents()
    {
        $response = new Response(200, [], "this_is_a_dummy_response");

        $this->getMockedService()
            ->expects($this->once())
            ->method('getContents')
            ->withConsecutive([$this->equalTo($response)])
            ->will($this->returnValue($response->getBody()->getContents()));

        $this->assertEquals(
            'this_is_a_dummy_response',
            $this->getMockedService()->getContents($response)
        );
    }
}
