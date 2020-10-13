<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use Gandung\Tokopedia\Service\Category;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class CategoryTest extends TestCase
{
    use ServiceTestTrait;

    public function testCanGetAllCategories()
    {
        $category    = new Category($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/category/get-all-categories.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $category->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($category->getAllCategories(), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetAllCategoriesWithKeyword()
    {
        $category    = new Category($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/category/get-all-categories-with-keyword.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $category->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($category->getAllCategories('fashion-anak'), true);

        $this->assertEquals($deserialized, $response);
    }
}
