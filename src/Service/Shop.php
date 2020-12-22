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
        $endpoint = sprintf(
            '/v1/shop/fs/%d/shop-info',
            $this->getFulfillmentServiceID()
        );

        $queryParams = [];

        if ($shopID > 0) {
            $queryParams['shop_id'] = $shopID;
        }

        $response = $this->call(
            'GET',
            sprintf(
                '%s%s',
                $endpoint,
                sizeof($queryParams) === 0 ? '' : '?' . http_build_query($queryParams)
            )
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
        $response = $this->call(
            'POST',
            sprintf(
                '/v2/shop/fs/%d/shop-status',
                $this->getFulfillmentServiceID()
            ),
            $data
        );

        return $this->getContents($response);
    }
}
