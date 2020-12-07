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

    public function testCanGetProductInfoWithSuccessResponse3()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/get-product-info-ok-3.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->getProductInfoFromRelatedShopID(1337, 1, 10, 1),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetProductInfoWithFailedResponse3()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/get-product-info-fail-3.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->getProductInfoFromRelatedShopID(1337, 1, 10, 1),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetAllVariantsByCategoryIDWithSuccessResponse()
    {
        $product = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient = new Client(['handler' => $mockHandler]);
        $contents = file_get_contents(
            __DIR__ . '/../data-fixtures/product/get-all-variants-by-category-id-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->getAllVariantsByCategoryID(31337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetAllVariantsByCategoryIDWithFailedResponse()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/get-all-variants-by-category-id-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->getAllVariantsByCategoryID(31337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetAllVariantsByProductIDWithSuccessResponse()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/get-all-variants-by-product-id-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->getAllVariantsByProductID(31337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetAllVariantsByProductIDWithFailedResponse()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/get-all-variants-by-product-id-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->getAllVariantsByProductID(31337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetAllEtalaseWithSuccessResponse()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/get-all-etalase-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->getAllEtalase(31337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetAllEtalaseWithFailedResponse()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/get-all-etalase-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->getAllEtalase(31337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanCreateProductsWithSuccessResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/create-product-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/create-product-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->createProducts(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanCreateProductsWithFailedResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/create-product-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/create-product-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->createProducts(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanEditProductWithSuccessResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/edit-product-request-data.json'
        );
        $contents = file_get_contents(
            __DIR__ . '/../data-fixtures/product/edit-product-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->editProduct(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanEditProductWithFailedResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/edit-product-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/edit-product-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->editProduct(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanCheckUploadIDWithSuccessResponse()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/check-upload-status-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->checkUploadStatus(31337, 1337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanCheckUploadIDWithFailedResponse()
    {
        $product     = new Product($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/product/check-upload-status-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->checkUploadStatus(31337, 1337),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanSetActiveProductWithSuccessResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/set-active-product-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/set-active-product-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->setActiveProduct(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanSetActiveProductWithFailedResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/set-active-product-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/set-active-product-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->setActiveProduct(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanSetInactiveProductWithSuccessResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/set-inactive-product-request-data.json'
        );
        $contents = file_get_contents(
            __DIR__ . '/../data-fixtures/product/set-inactive-product-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->setInactiveProduct(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanSetInactiveProductWithFailedResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/set-inactive-product-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/set-inactive-product-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->setInactiveProduct(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdatePriceOnlyWithSuccessResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/update-price-only-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/update-price-only-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->updatePriceOnly(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdatePriceOnlyWithFailedResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/update-price-only-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/update-price-only-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->updatePriceOnly(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateStockOnlyWithSuccessResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/update-stock-only-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/update-stock-only-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->updateStockOnly(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanUpdateStockOnlyWithFailedResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/update-stock-only-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/update-stock-only-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->updateStockOnly(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanDeleteProductWithSuccessResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/delete-product-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/delete-product-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->deleteProduct(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanDeleteProductWithFailedResponse()
    {
        $product        = new Product($this->getAuthorization());
        $mockHandler    = new MockHandler();
        $httpClient     = new Client(['handler' => $mockHandler]);
        $requestPayload = file_get_contents(
            __DIR__ . '/../data-fixtures/product/delete-product-request-data.json'
        );
        $contents       = file_get_contents(
            __DIR__ . '/../data-fixtures/product/delete-product-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $product->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $product->deleteProduct(31337, json_decode($requestPayload, true)),
            true
        );

        $this->assertEquals($deserialized, $response);
    }
}
