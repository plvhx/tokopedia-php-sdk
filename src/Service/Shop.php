<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use Gandung\Tokopedia\AbstractService;

use function http_build_query;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Shop extends AbstractService
{
	/**
	 * Get shop info.
	 *
	 * @param int $shopID Shop ID.
	 * @return string
	 */
	public function getShopInfo(int $shopID = 0)
	{
		$endpoint = sprintf(
			'/v1/shop/fs/%s/shop-info',
			$this->getFulfillmentServiceID()
		);

		$queryParams = [];

		if ($shopID > 0) {
			$queryParams['shop_id'] = $shopID;
		}

		return $this->getHttpClient()->request(
			'GET',
			sprintf(
				'%s%s',
				$endpoint,
				sizeof($queryParams) === 0 ? '' : '?' . http_build_query($queryParams)
			)
		);
	}

	/**
	 * Update shop status.
	 *
	 * @param array $data
	 * @return string
	 */
	public function updateShopStatus(array $data)
	{
		return $this->getHttpClient()->request(
			'POST',
			sprintf(
				'/v2/shop/fs/%s/shop-status',
				$this->getFulfillmentServiceID()
			),
			$data
		);
	}
}
