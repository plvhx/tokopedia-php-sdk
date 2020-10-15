<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use InvalidArgumentException;
use RuntimeException;
use Gandung\Tokopedia\Service\Order;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;
use function time;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class OrderTest extends TestCase
{
    use ServiceTestTrait;

    public function testCanGetAllOrdersAndWillThrowExceptionIfPageNumberIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $fromDate = time();
        $toDate   = $fromDate + 3600;
        $order    = new Order($this->getAuthorization());

        $order->getAllOrders($fromDate, $toDate, -1, 10);
    }

    public function testCanGetAllOrdersAndWillThrowExceptionIfPerPageNumberIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $fromDate = time();
        $toDate   = $fromDate + 3600;
        $order    = new Order($this->getAuthorization());

        $order->getAllOrders($fromDate, $toDate, 1, -10);
    }

    public function testCanGetAllOrdersAndWillThrowExceptionIfShopAndWarehouseIDAreSet()
    {
        $this->expectException(RuntimeException::class);

        $fromDate = time();
        $toDate   = $fromDate + 3600;
        $order    = new Order($this->getAuthorization());

        $order->getAllOrders($fromDate, $toDate, 1, 10, 1, 1);
    }

    public function testCanGetAllOrdersAndWillThrowExceptionIfStatusTypeIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $fromDate = time();
        $toDate   = $fromDate + 3600;
        $order    = new Order($this->getAuthorization());

        $order->getAllOrders($fromDate, $toDate, 1, 10, 1, 0, 1337);
    }

    public function testCanGetAllOrdersWithSuccessResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/get-all-orders-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $fromDate     = time();
        $toDate       = $fromDate + 3600;
        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->getAllOrders($fromDate, $toDate, 1, 10), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetAllOrdersWithFailedResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/get-all-orders-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $fromDate     = time();
        $toDate       = $fromDate + 3600;
        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->getAllOrders($fromDate, $toDate, 1, 10), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetSingleOrderWithSuccessResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/get-single-order-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->getSingleOrder(1337, 'sdfsdf'), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetSingleOrderWithFailedResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/get-single-order-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->getSingleOrder(1337, 'sdfsdfsdf'), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetShippingLabelWithFailedResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/get-shipping-label-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->getShippingLabel(1337, 1), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanAcceptOrderWithSuccessResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/accept-order-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->acceptOrder(1337), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanAcceptOrderWithFailedResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/accept-order-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->acceptOrder(1337), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanRejectOrderWithSuccessResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/reject-order-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $postData     = [
            'reason_code'         => 1,
            'reason'              => 'out of stock',
            'shop_close_and_date' => '17/05/2017',
            'shop_close_note'     => 'Maaf Pak, shop saya tutup untuk liburan.'
        ];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->rejectOrder(1337, $postData), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanRejectOrderWithFailedResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/reject-order-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $postData     = [
            'reason_code'         => 1,
            'reason'              => 'out of stock',
            'shop_close_and_date' => '17/05/2017',
            'shop_close_note'     => 'Maaf pak, shop saya tutup untuk liburan.'
        ];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->rejectOrder(10, $postData), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateOrderStatusWithSuccessResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/update-order-status-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $postData     = [
            'order_status' => 500,
            'shipping_ref_num' => 'RESIM4NT413'
        ];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->updateOrderStatus(1337, $postData), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateOrderStatusWithFailedResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/update-order-status-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $postData     = [
            'order_status' => 500,
            'shipping_ref_num' => 'RESIM4NT413'
        ];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->updateOrderStatus(1337, $postData), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanRequestPickUpWithSuccessResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/request-pick-up-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $postData     = [
            'order_id' => 180745398,
            'shop_id'  => 1707045
        ];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->requestPickUp($postData), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanRequestPickUpWithFailedResponse()
    {
        $order       = new Order($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/order/request-pick-up-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $order->setHttpClient($httpClient);

        $postData     = [
            'order_id' => 180745398,
            'shop_id'  => 1707045
        ];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($order->requestPickUp($postData), true);

        $this->assertEquals($deserialized, $response);
    }
}
