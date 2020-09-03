<?php

declare(strict_types=1);

namespace Gandung\Tokopedia;

use InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
abstract class AbstractService implements ServiceInterface
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
	 * @var int
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
	private function initialize(array $config)
	{
		$this->setBaseUrl(
			$this->validateAndReturnDefaultValue(
				$config,
				'base_url',
				$this->getDefaultBaseUrl()
			)
		);

		$this->setFulfillmentServiceID(
			$this->validateAndReturnDefaultValue(
				$config,
				'fs_id',
				0
			)
		);

		$this->setClientID(
			$this->validateAndReturnDefaultValue(
				$config,
				'client_id',
				''
			)
		);

		$this->setClientSecret(
			$this->validateAndReturnDefaultValue(
				$config,
				'client_secret',
				''
			)
		);

		$this->setHttpClient(
			new Client([
				'base_uri' => $this->getBaseUrl()
			])
		);
	}

	/**
	 * Validate and return default value.
	 *
	 * @param array $data
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	private function validateAndReturnDefaultValue(array $data, string $key, $default)
	{
		if (!empty($data[$key]) || isset($data[$key])) {
			return $data[$key];
		}

		return $default;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getBaseUrl()
	{
		return $this->baseUrl;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setBaseUrl(string $baseUrl)
	{
		$this->baseUrl = $baseUrl;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFulfillmentServiceID()
	{
		return $this->fulfillmentServiceID;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setFulfillmentServiceID(int $fulfillmentServiceID)
	{
		$this->fulfillmentServiceID = $fulfillmentServiceID;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientID()
	{
		return $this->clientID;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setClientID(string $clientID)
	{
		$this->clientID = $clientID;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getClientSecret()
	{
		return $this->clientSecret;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setClientSecret(string $clientSecret)
	{
		$this->clientSecret = $clientSecret;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getEndpoint()
	{
		return $this->endpoint;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setEndpoint(string $endpoint)
	{
		$this->endpoint = $endpoint;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHttpClient()
	{
		return $this->client;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setHttpClient(ClientInterface $client)
	{
		$this->client = $client;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUri()
	{
		return (new Uri($this->getBaseUrl()))->withPath($this->getEndpoint());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContents(ResponseInterface $response)
	{
		return $response->getBody()->getContents();
	}

	/**
	 * Get default base url if not provided.
	 *
	 * @return string
	 */
	abstract protected function getDefaultBaseUrl();
}
