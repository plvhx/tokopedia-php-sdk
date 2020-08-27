<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use function http_build_query;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Shop extends Resource
{
	/**
	 * Get shop info.
	 *
	 * @param int $shopID Shop ID.
	 * @return string
	 */
	public function getShopInfo(int $shopID = 0)
	{
		$credential = $this->getAuthorization()->authorize();
		$endpoint   = sprintf(
			'/v1/shop/fs/%s/shop-info',
			$this->getFulfillmentServiceID()
		);
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams = [];

		if ($shopID > 0) {
			$queryParams['shop_id'] = $shopID;
		}

		$response = $this->getHttpClient()->request(
			'GET',
			sprintf(
				'%s%s',
				$endpoint,
				sizeof($queryParams) === 0 ? '' : '?' . http_build_query($queryParams)
			),
			['headers' => $headers]
		);

		return $this->getContents($response);
	}

	/**
	 * Update shop status.
	 *
	 * @param array $data
	 * @return string
	 */
	public function updateShopStatus(array $data)
	{
		$credential = $this->getAuthorization()->authorize();
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$response = $this->getHttpClient()->request(
			'POST',
			sprintf(
				'/v2/shop/fs/%s/shop-status',
				$this->getFulfillmentServiceID()
			),
			[
				'headers' => $headers,
				'json'    => $data
			]
		);

		return $this->getContents($response);
	}
}
