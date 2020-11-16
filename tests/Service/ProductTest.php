<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use InvalidArgumentException;
use Gandung\Tokopedia\Service\Product;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class ProductTest extends TestCase
{
	use ServiceTestTrait;

	public function testCanGetProductInfoWithSuccessResponse1()
	{
		$product     = new Product($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/product/get-product-info-ok-1.json'
		);

		$mockHandler->append(new Response(
			200,
			['Content-Type' => 'application/json'],
			$contents
		));

		$product->setHttpClient($httpClient);

		$deserialized = json_decode($contents, true);
		$response     = json_decode(
			$product->getProductInfo(1337, "http://foo.bar/1337"),
			true
		);

		$this->assertEquals($deserialized, $response);
	}

	public function testCanGetProductInfoWithFailedResponse1()
	{
		$product     = new Product($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/product/get-product-info-fail-1.json'
		);

		$mockHandler->append(new Response(
			400,
			['Content-Type' => 'application/json'],
			$contents
		));

		$product->setHttpClient($httpClient);

		$deserialized = json_decode($contents, true);
		$response     = json_decode(
			$product->getProductInfo(1337, "http://foo.bar/1337"),
			true
		);

		$this->assertEquals($deserialized, $response);
	}

	public function testCanGetProductInfoWithSuccessResponse2()
	{
		$product     = new Product($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/product/get-product-info-ok-2.json'
		);

		$mockHandler->append(new Response(
			200,
			['Content-Type' => 'application/json'],
			$contents
		));

		$product->setHttpClient($httpClient);

		$deserialized = json_decode($contents, true);
		$response     = json_decode(
			$product->getProductInfoBySKU("sdfsdf"),
			true
		);

		$this->assertEquals($deserialized, $response);
	}

	public function testCanGetProductInfoWithFailedResponse2()
	{
		$product     = new Product($this->getAuthorization());
		$mockHandler = new MockHandler();
		$httpClient  = new Client(['handler' => $mockHandler]);
		$contents    = file_get_contents(
			__DIR__ . '/../data-fixtures/product/get-product-info-fail-2.json'
		);

		$mockHandler->append(new Response(
			400,
			['Content-Type' => 'application/json'],
			$contents
		));

		$product->setHttpClient($httpClient);

		$deserialized = json_decode($contents, true);
		$response     = json_decode(
			$product->getProductInfoBySKU("sdfsdf"),
			true
		);

		$this->assertEquals($deserialized, $response);
	}

	public function testCanGetProductInfo3WillReturnExceptionWhenPageNumberIsInvalid()
	{
		$this->expectException(InvalidArgumentException::class);

		$product = new Product($this->getAuthorization());
		$product->getProductInfoFromRelatedShopID(1337, -1, 10, 2);
	}

	public function testCanGetProductInfo3WillReturnExceptionWhenPerPageNumberIsInvalid()
	{
		$this->expectException(InvalidArgumentException::class);

		$product = new Product($this->getAuthorization());
		$product->getProductInfoFromRelatedShopID(1337, 1, -10, 2);
	}

	public function testCanGetProductInfo3WillReturnExceptionWhenSortTypeIsInvalid()
	{
		$this->expectException(InvalidArgumentException::class);

		$product = new Product($this->getAuthorization());
		$product->getProductInfoFromRelatedShopID(1337, 1, 10, 1337);
	}
}
