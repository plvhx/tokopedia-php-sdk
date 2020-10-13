<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use Gandung\Tokopedia\Service\Campaign;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class CampaignTest extends TestCase
{
    use ServiceTestTrait;

    public function testCanViewSlashPrice()
    {
        $campaign    = new Campaign($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/campaign/view-slash-price.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $campaign->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $campaign->viewSlashPrice(31337, 1, 2, Campaign::SLASH_PRICE_ALL),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanViewCampaignProduct()
    {
        $campaign    = new Campaign($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/campaign/view-campaign-product.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $campaign->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $campaign->viewCampaignProducts(1337, "sdfsdfsdf"),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanAddSlashPriceWithSuccessResponse()
    {
        $campaign    = new Campaign($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/campaign/add-slash-price-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $campaign->setHttpClient($httpClient);

        $postData     = [[
            'product_id'          => 15331520,
            'discounted_price'    => 2000,
            'discount_percentage' => 0,
            'start_time_unix'     => 1592290500,
            'end_time_unix'       => 1592808840,
            'max_order'           => 2,
            'initial_quota'       => 6
        ]];
        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $campaign->addSlashPrice(1337, $postData),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanAddSlashPriceWithFailedResponse()
    {
        $campaign    = new Campaign($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/campaign/add-slash-price-fail.json'
        );

        $mockHandler->append(new Response(
            422,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $campaign->setHttpClient($httpClient);

        $postData     = [[
            'product_id'          => 15331520,
            'discounted_price'    => 2000,
            'discount_percentage' => 0,
            'start_time_unix'     => 1592290500,
            'end_time_unix'       => 1592808840,
            'max_order'           => 2,
            'initial_quota'       => 6
        ]];
        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $campaign->addSlashPrice(1337, $postData),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateSlashPriceWithSuccessResponse()
    {
        $campaign    = new Campaign($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/campaign/update-slash-price-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $campaign->setHttpClient($httpClient);

        $postData     = [[
            'product_id'          => 15331520,
            'discounted_price'    => 2000,
            'discount_percentage' => 0,
            'start_time_unix'     => 1592290500,
            'end_time_unix'       => 1592808840,
            'max_order'           => 2,
            'initial_quota'       => 6
        ]];
        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $campaign->updateSlashPrice(1337, $postData),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateSlashPriceWithFailedResponse()
    {
        $campaign    = new Campaign($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/campaign/update-slash-price-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $campaign->setHttpClient($httpClient);

        $postData     = [[
            'product_id'          => 15331520,
            'discounted_price'    => 2000,
            'discount_percentage' => 0,
            'start_time_unix'     => 1592290500,
            'end_time_unix'       => 1592808840,
            'max_order'           => 2,
            'initial_quota'       => 6
        ]];
        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $campaign->updateSlashPrice(1337, $postData),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanCancelSlashPriceWithSuccessResponse()
    {
        $campaign    = new Campaign($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/campaign/cancel-slash-price-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $campaign->setHttpClient($httpClient);

        $postData     = [[
            'slash_price_product_id' => 19602,
            'product_id'             => 15351412
        ]];
        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $campaign->cancelSlashPrice(1337, $postData),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanCancelSlashPriceWithFailedResponse()
    {
        $campaign    = new Campaign($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/campaign/cancel-slash-price-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $campaign->setHttpClient($httpClient);

        $postData     = [[
            'slash_price_product_id' => 19602,
            'product_id'             => 15351412
        ]];
        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $campaign->cancelSlashPrice(1337, $postData),
            true
        );

        $this->assertEquals($deserialized, $response);
    }
}
