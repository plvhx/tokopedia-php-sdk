<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use InvalidArgumentException;
use Gandung\Tokopedia\Service\Interaction;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

use function file_get_contents;
use function json_decode;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class InteractionTest extends TestCase
{
    use ServiceTestTrait;

    public function testListMessageWillThrowExceptionWhenPageNumberIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $interaction = new Interaction($this->getAuthorization());
        $interaction->getListMessage(1337, -1);
    }

    public function testListMessageWillThrowExceptionWhenPerPageNumberIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $interaction = new Interaction($this->getAuthorization());
        $interaction->getListMessage(1337, 1, -1);
    }

    public function testListMessageWillThrowExceptionWhenOrderTypeIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $interaction = new Interaction($this->getAuthorization());
        $interaction->getListMessage(1337, 1, 10, '');
    }

    public function testListMessageWillThrowExceptionWhenFilterTypeIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $interaction = new Interaction($this->getAuthorization());
        $interaction->getListMessage(
            1337,
            1,
            10,
            Interaction::INTERACTION_MESSAGE_ORDER_ASC,
            ''
        );
    }

    public function testCanGetListOfMessageWithSuccessResponse()
    {
        $interaction = new Interaction($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/interaction/list-message.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $interaction->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($interaction->getListMessage(1337), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanGetListOfMessageWithFailedResponse()
    {
        $interaction = new Interaction($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/interaction/list-message-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $interaction->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($interaction->getListMessage(0), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testListReplyWillThrowExceptionWhenPageNumberIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $interaction = new Interaction($this->getAuthorization());
        $interaction->getListReply(1337, 7373, -1);
    }

    public function testListReplyWillThrowExceptionWhenPerPageNumberIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $interaction = new Interaction($this->getAuthorization());
        $interaction->getListReply(1337, 7373, 1, -10);
    }

    public function testListReplyWillThrowExceptionWhenOrderTypeIsInvalid()
    {
        $this->expectException(InvalidArgumentException::class);

        $interaction = new Interaction($this->getAuthorization());
        $interaction->getListReply(1337, 7373, 1, 10, '');
    }

    public function testGetListOfReplyWithSuccessResponse()
    {
        $interaction = new Interaction($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/interaction/list-reply-ok.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $interaction->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($interaction->getListReply(1337, 7373), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testGetListOfReplyWithFailedResponse()
    {
        $interaction = new Interaction($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/interaction/list-reply-fail.json'
        );

        $mockHandler->append(new Response(
            400,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $interaction->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($interaction->getListReply(1337, 7373), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanInitiateChat()
    {
        $interaction = new Interaction($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/interaction/initiate-chat.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $interaction->setHttpClient($httpClient);

        $deserialized = json_decode($contents, true);
        $response     = json_decode($interaction->initiateChat(1337), true);

        $this->assertEquals($deserialized, $response);
    }

    public function testCanSendNormalReply()
    {
        $interaction = new Interaction($this->getAuthorization());
        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);
        $contents    = file_get_contents(
            __DIR__ . '/../data-fixtures/interaction/send-reply.json'
        );

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            $contents
        ));

        $interaction->setHttpClient($httpClient);

        $postData     = [
            'shop_id' => 23456,
            'message' => 'Terimakasih'
        ];
        $deserialized = json_decode($contents, true);
        $response     = json_decode($interaction->sendReply(1337, $postData), true);

        $this->assertEquals($deserialized, $response);
    }
}
