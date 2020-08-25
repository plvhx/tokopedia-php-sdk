<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use function http_build_query;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Category extends Resource
{
	/**
	 * Get all categories.
	 *
	 * @param string $keyword Product keyword.
	 * @return string
	 */
	public function getAllCategories(string $keyword = '')
	{
		$credential = $this->getAuthorization()->authorize();
		$endpoint   = sprintf(
			'/inventory/v1/fs/%s/product/category',
			$this->getFulfillmentServiceID()
		);
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams = [];

		if ('' !== $keyword) {
			$queryParams['keyword'] = $keyword;
		}

		return $this->getHttpClient()->request(
			'GET',
			sprintf(
				'%s%s',
				$endpoint,
				sizeof($queryParams) === 0 ? '' : '?' . http_build_query($queryParams)
			),
			['headers' => $headers]
		);
	}
}
