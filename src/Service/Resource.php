<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use GuzzleHttp\Client;
use Gandung\Tokopedia\AbstractService;
use Gandung\Tokopedia\Auth\AuthorizationInterface;

use function array_merge;
use function sprintf;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
abstract class Resource extends AbstractService
{
    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @param AuthorizationInterface $authorization
     * @param array $config
     * @return void
     */
    public function __construct(AuthorizationInterface $authorization)
    {
        $this->initialize($authorization);
    }

    /**
     * Initialize current class constructor.
     *
     * @param AuthorizationInterface $authorization
     * @return void
     */
    private function initialize(AuthorizationInterface $authorization)
    {
        $this->setAuthorization($authorization);
        $this->setBaseUrl($this->getDefaultBaseUrl());
        $this->setFulfillmentServiceID($authorization->getFulfillmentServiceID());
        $this->setClientID($authorization->getClientID());
        $this->setClientSecret($authorization->getClientSecret());
        $this->setHttpClient(
            new Client([
                'base_uri' => $this->getBaseUrl()
            ])
        );
    }

    /**
     * Get authorization object.
     *
     * @return AuthorizationInterface
     */
    public function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * Set authorization object.
     *
     * @param AuthorizationInterface $authorization
     * @return void
     */
    public function setAuthorization(AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * {@inheritdoc}
     */
    protected function getDefaultBaseUrl()
    {
        return 'https://fs.tokopedia.net';
    }

    /**
     * Process an authorized request.
     *
     * @param string $method
     * @param string $uri
     * @param array $data
     * @param bool $isMultipart
     * @return ResponseInterface
     */
    protected function call(string $method, string $uri, array $data = [], bool $isMultipart = false)
    {
        $credential = $this->getAuthorization()->authorize();
        $headers    = ['Authorization' => sprintf('Bearer %s', $credential->getAccessToken())];
        $option     = ['headers' => $headers];

        if (!empty($data)) {
            $option = array_merge($option, !$isMultipart ? ['json' => $data] : ['multipart' => [$data]]);
        }

        return $this->getHttpClient()->request($method, $uri, $option);
    }
}
