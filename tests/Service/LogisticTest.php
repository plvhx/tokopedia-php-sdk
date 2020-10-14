<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use Gandung\Tokopedia\Service\Logistic;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class LogisticTest extends TestCase
{
    use ServiceTestTrait;

    public function testGetShipmentInfoWithSuccessResponse()
    {
        $logistic    = new Logistic($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/logistic/get-shipment-info-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $logistic->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($logistic->getShipmentInfo(1337), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testGetShipmentInfoWithFailedResponse()
    {
        $logistic    = new Logistic($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/logistic/get-shipment-info-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $logistic->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($logistic->getShipmentInfo(0), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateShipmentInfoWithSuccessResponse()
    {
        $logistic    = new Logistic($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/logistic/update-shipment-info-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $logistic->setHttpClient($httpClient);

        $postData     = ['1' => ['6' => 0], '23' => ['45' => 1]];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($logistic->updateShipmentInfo(1337, $postData), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateShipmentInfoWithFailedResponse()
    {
        $logistic    = new Logistic($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/logistic/update-shipment-info-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $logistic->setHttpClient($httpClient);

        $postData     = [];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($logistic->updateShipmentInfo(1337, $postData), true);

        $this->assertEquals($deserialized, $response);
    }
}
