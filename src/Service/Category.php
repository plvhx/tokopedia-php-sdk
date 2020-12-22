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
        $endpoint = sprintf(
            '/inventory/v1/fs/%d/product/category',
            $this->getFulfillmentServiceID()
        );

        $queryParams = [];

        if ('' !== $keyword) {
            $queryParams['keyword'] = $keyword;
        }

        $response = $this->call(
            'GET',
            sprintf(
                '%s%s',
                $endpoint,
                sizeof($queryParams) === 0 ? '' : '?' . http_build_query($queryParams)
            ),
        );

        return $this->getContents($response);
    }
}
