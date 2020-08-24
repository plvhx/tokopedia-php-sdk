<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use InvalidArgumentException;

use function http_build_query;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Campaign extends Resource
{
	/**
	 * @var string
	 */
	const SLASH_PRICE_ALL = 'ALL';

	/**
	 * @var string
	 */
	const SLASH_PRICE_ACTIVE = 'ACTIVE';

	/**
	 * @var string
	 */
	const SLASH_PRICE_INACTIVE = 'INACTIVE';

	/**
	 * @var string
	 */
	const SLASH_PRICE_COMING_SOON = 'COMING_SOON';

	/**
	 * @var string
	 */
	const SLASH_PRICE_REDIRECTED = 'REDIRECTED';

	/**
	 * View slash price.
	 *
	 * @param int $shopID Shop ID.
	 * @param int $page Current page number.
	 * @param int $perPage Per page number.
	 * @param string $status Slash price status.
	 * @return string
	 * @throws InvalidArgumentException When slash price status is invalid.
	 */
	public function viewSlashPrice(int $shopID, int $page, int $perPage, string $status = '')
	{
		if (!empty($status)) {
			$this->validateSlashPriceStatus($status);
		}

		$credential  = $this->getAuthorization()->authorize();
		$endpoint    = sprintf(
			'/v1/slash-price/fs/%s/view',
			$this->getFulfillmentServiceID()
		);
		$headers     = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams             = [];
		$queryParams['shop_id']  = $shopID;
		$queryParams['page']     = $page;
		$queryParams['per_page'] = $perPage;
		$queryParams['status']   = $status;

		return $this->getHttpClient()->request(
			'GET',
			sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
			['headers' => $headers]
		);
	}

	/**
	 * View campaign products.
	 *
	 * @param int $shopID Shop ID.
	 * @param string $productID Product ID (can separated by comma).
	 * @return string
	 */
	public function viewCampaignProducts(int $shopID, string $productID)
	{
		$credential = $this->getAuthorization()->authorize();
		$endpoint   = sprintf(
			'/v1/campaign/fs/%s/view',
			$this->getFulfillmentServiceID()
		);
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams               = [];
		$queryParams['shop_id']    = $shopID;
		$queryParams['product_id'] = $productID;

		return $this->getHttpClient()->request(
			'GET',
			sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
			['headers' => $headers]
		);
	}

	/**
	 * Add slash price for given shop ID.
	 *
	 * @param int $shopID Shop ID.
	 * @param array $data
	 * @return string
	 */
	public function addSlashPrice(int $shopID, array $data)
	{
		$credential = $this->getAuthorization()->authorize();
		$endpoint   = sprintf(
			'/v1/slash-price/fs/%s/add',
			$this->getFulfillmentServiceID()
		);
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams            = [];
		$queryParams['shop_id'] = $shopID;

		return $this->getHttpClient()->request(
			'POST',
			sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
			[
				'headers' => $headers,
				'json'    => $data
			]
		);
	}

	/**
	 * Update slash price for given shop ID.
	 *
	 * @param int $shopID Shop ID.
	 * @param array $data
	 * @return string
	 */
	public function updateSlashPrice(int $shopID, array $data)
	{
		$credential = $this->getAuthorization()->authorize();
		$endpoint   = sprintf(
			'/v1/slash-price/fs/%s/update',
			$this->getFulfillmentServiceID()
		);
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams            = [];
		$queryParams['shop_id'] = $shopID;

		return $this->getHttpClient()->request(
			'POST',
			sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
			[
				'headers' => $headers,
				'json'    => $data
			]
		);
	}

	/**
	 * Cancel slash price.
	 *
	 * @param int $shopID Shop ID.
	 * @param array $data
	 * @return string
	 */
	public function cancelSlashPrice(int $shopID, array $data)
	{
		$credential = $this->getAuthorization()->authorize();
		$endpoint = sprintf(
			'/v1/slash-price/fs/%s/cancel',
			$this->getFulfillmentServiceID()
		);
		$headers = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams            = [];
		$queryParams['shop_id'] = $shopID;

		return $this->getHttpClient()->request(
			'POST',
			sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
			[
				'headers' => $headers,
				'json'    => $data
			]
		);
	}

	/**
	 * Validate slash price status.
	 *
	 * @param string $status Slash price status.
	 * @return void
	 * @throws InvalidArgumentException When slash price status is invalid.
	 */
	private function validateSlashPriceStatus(string $status)
	{
		switch ($status) {
			case SLASH_PRICE_ALL:
			case SLASH_PRICE_ACTIVE:
			case SLASH_PRICE_INACTIVE:
			case SLASH_PRICE_COMING_SOON:
			case SLASH_PRICE_REDIRECTED:
			default:
				throw new InvalidArgumentException("Invalid slash price status.");
		}

		return;
	}
}
