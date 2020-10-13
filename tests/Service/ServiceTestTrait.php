<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Service;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Gandung\Tokopedia\Auth\Authorization;
use Gandung\Tokopedia\Auth\AuthorizationInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
trait ServiceTestTrait
{
    /**
     * @var \Gandung\Tokopedia\Auth\AuthorizationInterface
     */
    private $authorization;

    public function getAuthorization()
    {
        return $this->authorization;
    }

    public function setAuthorization(AuthorizationInterface $authorization)
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

        $mockHandler = new MockHandler();
        $httpClient  = new Client(['handler' => $mockHandler]);

        $mockHandler->append(new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode([
                'access_token'    => sprintf("c:%s", base64_encode("abc:cba")),
                'event_code'      => '',
                'expires_in'      => time() + 3600,
                'last_login_type' => '',
                'sq_check'        => '',
                'token_type'      => 'bearer'
            ])
        ));

        $this->getAuthorization()->setHttpClient($httpClient);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
    }
}
