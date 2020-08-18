<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use InvalidArgumentException;
use Gandung\Tokopedia\AbstractService;

use function http_build_query;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Campaign extends AbstractService
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

		$this->setEndpoint(
			sprintf(
				'/v1/slash-price/fs/%s/view',
				$this->getFulfillmentServiceID()
			)
		);

		$queryParams             = [];
		$queryParams['shop_id']  = $shopID;
		$queryParams['page']     = $page;
		$queryParams['per_page'] = $perPage;
		$queryParams['status']   = $status;

		return $this->getHttpClient()->request(
			'GET',
			sprintf(
				'%s?%s',
				$this->getEndpoint(),
				http_build_query($queryParams)
			)
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
		$this->setEndpoint(
			sprintf(
				'/v1/campaign/fs/%s/view',
				$this->getFulfillmentServiceID()
			)
		);

		$queryParams               = [];
		$queryParams['shop_id']    = $shopID;
		$queryParams['product_id'] = $productID;

		return $this->getHttpClient()->request(
			'GET',
			sprintf(
				'%s?%s',
				$this->getEndpoint(),
				http_build_query($queryParams)
			)
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
