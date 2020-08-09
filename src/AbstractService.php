<?php

declare(strict_types=1);

namespace Gandung\Tokopedia;

use InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
abstract class AbstractService
{
	/**
	 * @var UriInterface
	 */
	private $baseUrl;

	/**
	 * @var string
	 */
	private $endpoint;

	/**
	 * @var string
	 */
	private $fulfillmentServiceID;

	/**
	 * @var string
	 */
	private $clientID;

	/**
	 * @var string
	 */
	private $clientSecret;

	/**
	 * @var ClientInterface
	 */
	private $client;

	/**
	 * @param array $config
	 * @return void
	 */
	public function __construct(array $config = [])
	{
		$this->initialize($config);
	}

	/**
	 * Initialize application configuration.
	 *
	 * @param array $config Configuration array.
	 * @return void
	 */
	public function initialize(array $config)
	{
		$this->setBaseUrl($config['base_url'] === '' ? '' : $config['base_url']);
		$this->setFulfillmentServiceID($config['fs_id'] === '' ? '' : $config['fs_id']);
		$this->setClientID($config['client_id'] === '' ? '' : $config['client_id']);
		$this->setClientSecret($config['client_secret'] === '' ? '' : $config['client_secret']);
		$this->setHttpClient(
			new Client([
				'base_uri' => $this->getBaseUrl()
			])
		);
	}

	/**
	 * Get base URL.
	 *
	 * @return string
	 */
	public function getBaseUrl()
	{
		return $this->baseUrl;
	}

	/**
	 * Set base URL.
	 *
	 * @param string $baseUrl Base URL.
	 * @return void
	 */
	public function setBaseUrl(string $baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}

	/**
	 * Get fulfillment service ID.
	 *
	 * @return string
	 */
	public function getFulfillmentServiceID()
	{
		return $this->fulfillmentServiceID;
	}

	/**
	 * Set fulfillment service ID.
	 *
	 * @param string $fulfillmentServiceID
	 * @return void
	 */
	public function setFulfillmentServiceID(string $fulfillmentServiceID)
	{
		$this->fulfillmentServiceID = $fulfillmentServiceID;
	}

	/**
	 * Get client ID.
	 *
	 * @return string
	 */
	public function getClientID()
	{
		return $this->clientID;
	}

	/**
	 * Set client ID.
	 *
	 * @param string $clientID Client ID.
	 * @return void
	 */
	public function setClientID(string $clientID)
	{
		$this->clientID = $clientID;
	}

	/**
	 * Get client secret.
	 *
	 * @return string
	 */
	public function getClientSecret()
	{
		return $this->clientSecret;
	}

	/**
	 * Set client secret.
	 *
	 * @param string $clientSecret Client secret.
	 * @return void
	 */
	public function setClientSecret(string $clientSecret)
	{
		$this->clientSecret = $clientSecret;
	}

	/**
	 * Get URI path.
	 *
	 * @return string
	 */
	public function getEndpoint()
	{
		return $this->endpoint;
	}

	/**
	 * Set URL path.
	 *
	 * @param string $endpoint Endpoint URI.
	 * @return void
	 */
	public function setEndpoint(string $endpoint)
	{
		$this->endpoint = $endpoint;
	}

	/**
	 * Get http client instance.
	 *
	 * @return ClientInterface
	 */
	public function getHttpClient()
	{
		return $this->client;
	}

	/**
	 * Set http client instance.
	 *
	 * @param ClientInterface $client Http client instance.
	 * @return void
	 */
	public function setHttpClient(ClientInterface $client)
	{
		$this->client = $client;
	}

	/**
	 * Get base URL and given URI path as UriInterface instance.
	 *
	 * @return UriInterface
	 */
	public function getUri()
	{
		return (new Uri($this->getBaseUrl()))->withPath($this->getEndpoint());
	}
}
