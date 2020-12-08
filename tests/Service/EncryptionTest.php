<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use InvalidArgumentException;
use Gandung\Tokopedia\Service\Encryption;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class EncryptionTest extends TestCase
{
    use ServiceTestTrait;

    public function testWillThrowExceptionWhenFilepathIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $encryption = new Encryption($this->getAuthorization());
        $encryption->registerPublicKey(sprintf("%s/%s", getcwd(), 'nonexistent-files'));
    }

    public function testCanRegisterPublicKeyWithSuccessResponse()
    {
        $encryption  = new Encryption($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/encryption/register-public-key-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $encryption->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $encryption->registerPublicKey(__DIR__ . '/../data-fixtures/encryption/public-key-valid.pub'),
            true
        );

        $this->assertEquals($deserialized, $response);
    }

    public function testCanRegisterPublicKeyWithFailedResponse()
    {
        $encryption  = new Encryption($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/encryption/register-public-key-fail.json'
        );

        $mockHandler->append(new Response(
            500,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $encryption->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode(
            $encryption->registerPublicKey(__DIR__ . '/../data-fixtures/encryption/public-key-valid.pub'),
            true
        );

        $this->assertEquals($deserialized, $response);
    }
}
