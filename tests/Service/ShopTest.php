<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use InvalidArgumentException;
use Gandung\Tokopedia\Service\Shop;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class ShopTest extends TestCase
{
    use ServiceTestTrait;

    public function testCanGetShopInfoWithSuccessResponse()
    {
        $shop        = new Shop($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/shop/get-shop-info-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $shop->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $shop->getShopInfo(31337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetShopInfoWithFailedResponse()
    {
        $shop        = new Shop($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/shop/get-shop-info-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $shop->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response = json_decode(
            $shop->getShopInfo(31337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateShopStatusWithSuccessResponse()
    {
        $shop           = new Shop($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/shop/update-shop-status-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/shop/update-shop-status-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $shop->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $shop->updateShopStatus(json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateShopStatusWithFailedResponse()
    {
        $shop           = new Shop($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/shop/update-shop-status-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/shop/update-shop-status-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $shop->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $shop->updateShopStatus(json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }
}
