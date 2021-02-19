<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use Gandung\Tokopedia\Service\Webhooks;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class WebhooksTest extends TestCase
{
	use ServiceTestTrait;

	public function testCanGetRegisteredWebhooksWithSuccessResponse()
	{
		$webhooks    = new Webhooks($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/webhooks/list-registered-webhooks-ok.json'
		);

		$mockHandler->append(new Response(
			200,
			['Content-Type' => 'application/json'],
			$contents
		));

		$webhooks->setHttpClient($httpClient);

		$deserialized = json_decode($contents, true);
		$response     = json_decode($webhooks->listRegisteredWebhooks(), true);

		$this->assertEquals($deserialized, $response);
	}

	public function testCanRegisterWebhooksWithSuccessResponse()
	{
		$webhooks    = new Webhooks($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/webhooks/register-webhooks-ok.json'
		);

		$mockHandler->append(new Response(
			200,
			['Content-Type' => 'application/json'],
			$contents
		));

		$webhooks->setHttpClient($httpClient);

		$response = $webhooks->registerWebhooks([
			'fs_id' => 31337,
			'order_notification_url' => 'http://example.com/api/v1/order/notification',
			'order_cancellation_url' => 'http://example.com/api/v1/order/cancellation',
			'order_status_url' => 'http://example.com/api/v1/order/status',
			'order_request_cancellation_url' => 'http://example.com/api/v1/order/req/cancel',
			'chat_notification_url' => 'http://example.com/api/v1/chat/notification',
			'product_creation_url' => 'http://example.com/api/v1/product/create',
			'webhook_secret' => uniqid()
		]);

		$deserialized = json_decode($contents, true);
		$response     = json_decode($response, true);

		$this->assertEquals($deserialized, $response);
	}

	public function testCanRegisterWebhooksWithFailedResponse()
	{
		$webhooks    = new Webhooks($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/webhooks/register-webhooks-fail.json'
		);

		$mockHandler->append(new Response(
			200,
			['Content-Type' => 'application/json'],
			$contents
		));

		$webhooks->setHttpClient($httpClient);

		$response = $webhooks->registerWebhooks([
			'fs_id' => 31337,
			'order_notification_url' => 'http://example.com/api/v1/order/notification',
			'order_cancellation_url' => 'http://example.com/api/v1/order/cancellation',
			'order_status_url' => 'http://example.com/api/v1/order/status',
			'order_request_cancellation_url' => 'http://example.com/api/v1/order/req/cancel',
			'chat_notification_url' => 'http://example.com/api/v1/chat/notification',
			'product_creation_url' => 'http://example.com/api/v1/product/create',
			'webhook_secret' => uniqid()
		]);

		$deserialized = json_decode($contents, true);
		$response     = json_decode($response, true);

		$this->assertEquals($deserialized, $response);
	}

	public function testCanTriggerWebhookManuallyWithSuccessResponse()
	{
		$webhooks    = new Webhooks($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/webhooks/trigger-webhook-ok.json'
		);

		$mockHandler->append(new Response(
			200,
			['Content-Type' => 'application/json'],
			$contents
		));

		$webhooks->setHttpClient($httpClient);

		$response = $webhooks->triggerWebhook([
			'type' => 'order_notification',
			'order_id' => 576694264,
			'url' => 'http://example.com/api/v1/order/notification',
			'is_encrypted' => true
		]);

		$deserialized = json_decode($contents, true);
		$response     = json_decode($response, true);

		$this->assertEquals($deserialized, $response);
	}

	public function testCanTriggerWebhookManuallyWithFailedResponse()
	{
		$webhooks    = new Webhooks($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/webhooks/trigger-webhook-fail.json'
		);

		$mockHandler->append(new Response(
			200,
			['Content-Type' => 'application/json'],
			$contents
		));

		$webhooks->setHttpClient($httpClient);

		$response = $webhooks->triggerWebhook([
			'type' => 'order_notification',
			'order_id' => 576694264,
			'url' => 'http://example.com/api/v1/order/notification',
			'is_encrypted' => true
		]);

		$deserialized = json_decode($contents, true);
		$response     = json_decode($response, true);

		$this->assertEquals($deserialized, $response);
	}
}
