<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use Gandung\Tokopedia\AbstractService;

use function http_build_query;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Category extends AbstractService
{
	/**
	 * Get all categories.
	 *
	 * @param string $keyword Product keyword.
	 * @return string
	 */
	public function getAllCategories(string $keyword = '')
	{
		$this->setEndpoint(
			sprintf(
				'/inventory/v1/fs/%s/product/category',
				$this->getFulfillmentServiceID()
			)
		);

		$queryParams = [];

		if ('' !== $keyword) {
			$queryParams['keyword'] = $keyword;
		}

		return $this->getHttpClient()->request(
			'GET',
			sprintf(
				'%s%s',
				$this->getEndpoint(),
				sizeof($queryParams) === 0 ? '' : '?' . http_build_query($queryParams)
			)
		);
	}
}
