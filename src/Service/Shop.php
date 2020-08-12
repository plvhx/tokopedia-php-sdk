<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use Gandung\Tokopedia\AbstractService;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Shop extends AbstractService
{
	public function getShopInfo(int $shopID = 0)
	{
		$this->setEndpoint(
			sprintf(
				'/v1/shop/fs/%s/shop-info',
				$this->getFulfillmentServiceID()
			)
		);

		$queryParams            = [];
		$queryParams['shop_id'] = $shopID;

		return $this->getHttpClient()->request(
			'GET',
			sprintf(
				'%s?%s',
				$this->getEndpoint(),
				http_build_query($queryParams)
			)
		);
	}
}
