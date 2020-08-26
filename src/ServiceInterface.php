<?php

declare(strict_types=1);

namespace Gandung\Tokopedia;

use Psr\Http\Client\ClientInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface ServiceInterface
{
	/**
	 * Get base URL.
	 *
	 * @return string
	 */
	public function getBaseUrl();

	/**
	 * Set base URL.
	 *
	 * @param string $baseUrl Base URL.
	 * @return void
	 */
	public function setBaseUrl(string $baseUrl);

	/**
	 * Get fulfillment service ID.
	 *
	 * @return int
	 */
	public function getFulfillmentServiceID();

	/**
	 * Set fulfillment service ID.
	 *
	 * @param string $fulfillmentServiceID
	 * @return void
	 */
	public function setFulfillmentServiceID(int $fulfillmentServiceID);

	/**
	 * Get client ID.
	 *
	 * @return string
	 */
	public function getClientID();

	/**
	 * Set client ID.
	 *
	 * @param string $clientID
	 * @return void
	 */
	public function setClientID(string $clientID);

	/**
	 * Get client secret.
	 *
	 * @return string
	 */
	public function getClientSecret();

	/**
	 * Set client secret.
	 *
	 * @param string $clientSecret
	 * @return void
	 */
	public function setClientSecret(string $clientSecret);

	/**
	 * Get URI path.
	 *
	 * @return string
	 */
	public function getEndpoint();

	/**
	 * Set URI path.
	 *
	 * @param string $endpoint
	 * @return void
	 */
	public function setEndpoint(string $endpoint);

	/**
	 * Get http client instance.
	 *
	 * @return ClientInterface
	 */
	public function getHttpClient();

	/**
	 * Set http client instance.
	 *
	 * @param ClientInterface $client
	 * @return void
	 */
	public function setHttpClient(ClientInterface $client);

	/**
	 * Get base URL and given URI path as UriInterface instance.
	 *
	 * @return UriInterface
	 */
	public function getUri();
}
