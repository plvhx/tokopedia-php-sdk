<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Logistic extends Resource
{
	/**
	 * Get shipment info.
	 *
	 * @param int $shopID Shop ID.
	 * @return string
	 */
	public function getShipmentInfo(int $shopID)
	{
		$credential = $this->getAuthorization()->authorize();
		$endpoint   = sprintf(
			'/v2/logistic/fs/%s/info',
			$this->getFulfillmentServiceID()
		);
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams            = [];
		$queryParams['shop_id'] = $shopID;

		$response = $this->getHttpClient()->request(
			'GET',
			sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
			['headers' => $headers]
		);

		return $this->getContents($response);
	}
}
