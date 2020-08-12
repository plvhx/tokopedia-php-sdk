<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use Gandung\Tokopedia\AbstractService;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Logistic extends AbstractService
{
	/**
	 * Get shipment info.
	 *
	 * @param int $shopID Shop ID.
	 * @return string
	 */
	public function getShipmentInfo(int $shopID)
	{
		$this->setEndpoint(
			sprintf(
				'/v2/logistic/fs/%s/info',
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
