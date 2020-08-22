<?php

declare(strict_types=1);

namespace Gandung\Tokopedia;

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
	 * @return string
	 */
	public function getFulfillmentServiceID();

	/**
	 * Set fulfillment service ID.
	 *
	 * @param string $fulfillmentServiceID
	 * @return void
	 */
	public function setFulfillmentServiceID(string $fulfillmentServiceID);

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
}
