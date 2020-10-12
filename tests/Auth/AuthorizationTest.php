<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Auth;

use Cache\Adapter\Common\CacheItem;
use Cache\Adapter\PHPArray\ArrayCachePool;
use Gandung\Tokopedia\Auth\Authorization;
use Gandung\Tokopedia\Auth\AuthorizationInterface;
use Gandung\Tokopedia\Credential\CredentialInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class AuthorizationTest extends TestCase
{
    /**
     * @var \Gandung\Tokopedia\Auth\AuthorizationInterface
     */
    private $authorization;

    private function getAuthorization()
    {
        return $this->authorization;
    }

    private function setAuthorization(AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->setAuthorization(new Authorization(
            new ArrayCachePool(),
            ['client_secret' => 'abc', 'client_id' => 'cba']
        ));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
    }

    public function testCanAuthorizeAndGetFreshCredential()
    {
        $mockHandler = new MockHandler();
        $mockHandler->append(new Response(200, [], json_encode(
            [
                'access_token'    => sprintf("c:%s", base64_encode("abc:cba")),
                'event_code'      => '',
                'expires_in'      => time() + 3600,
                'last_login_type' => '',
                'sq_check'        => '',
                'token_type'      => 'bearer'
            ]
        )));

        $this->getAuthorization()->setHttpClient(new Client([
            'handler' => $mockHandler
        ]));

        $this->assertInstanceOf(
            CredentialInterface::class,
            $this->getAuthorization()->authorize()
        );
    }

    public function testCanAuthorizeAndGetCredentialFromCache()
    {
        $mockHandler = new MockHandler();
        $mockHandler->append(new Response(200, [], json_encode(
            [
                'access_token'    => sprintf("c:%s", base64_encode("abc:cba")),
                'event_code'      => '',
                'expires_in'      => time() + 3600,
                'last_login_type' => '',
                'sq_check'        => '',
                'token_type'      => 'bearer'
            ]
        )));

        $this->getAuthorization()->setHttpClient(new Client([
            'handler' => $mockHandler
        ]));

        // grab fresh credential from mock server
        $this->getAuthorization()->authorize();

        // this credential after this call must be
        // come from cache.
        $this->assertInstanceOf(
            CredentialInterface::class,
            $this->getAuthorization()->authorize()
        );
    }

    public function testCanGetCachePool()
    {
        $this->assertInstanceOf(
            CacheItemPoolInterface::class,
            $this->getAuthorization()->getCachePool()
        );
    }

    public function testCanSetCachePool()
    {
        $this->getAuthorization()->setCachePool($this->createMock(CacheItemPoolInterface::class));
        $this->assertInstanceOf(
            CacheItemPoolInterface::class,
            $this->getAuthorization()->getCachePool()
        );
    }

    public function testCanGetCacheTag()
    {
        $this->assertEquals(
            'authorization_metadata',
            $this->getAuthorization()->getCacheTag()
        );
    }

    public function testCanSetCacheTag()
    {
        $this->getAuthorization()->setCacheTag('foo_tag');
        $this->assertEquals('foo_tag', $this->getAuthorization()->getCacheTag());
    }
}
