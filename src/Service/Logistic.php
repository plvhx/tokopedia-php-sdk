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
        $endpoint = sprintf(
            '/v2/logistic/fs/%s/info',
            $this->getFulfillmentServiceID()
        );

        $queryParams            = [];
        $queryParams['shop_id'] = $shopID;

        $response = $this->call(
            'GET',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams))
        );

        return $this->getContents($response);
    }

    /**
     * @param int $shopID
     * @param int $firstOrderID
     * @param int $nextOrderID
     * @param int $orderID
     * @param int $perPage
     * @return string
     */
    public function getJOBAndCOD(
        int $shopID = 0,
        int $firstOrderID = 0,
        int $nextOrderID = 0,
        int $orderID = 0,
        int $perPage = 0
    ) {
        $endpoint = sprintf(
            '/v1/fs/%s/fulfillment_order',
            $this->getFulfillmentServiceID()
        );

        $queryParams = [];

        if ($shopID > 0) {
            $queryParams['shop_id'] = $shopID;
        }

        if ($firstOrderID > 0) {
            $queryParams['first_order_id'] = $firstOrderID;
        }

        if ($nextOrderID > 0) {
            $queryParams['next_order_id'] = $nextOrderID;
        }

        if ($orderID > 0) {
            $queryParams['order_id'] = $orderID;
        }

        if ($perPage > 0) {
            $queryParams['per_page'] = $perPage;
        }

        $response = $this->call(
            'GET',
            sprintf(
                "%s%s",
                $endpoint,
                !sizeof($queryParams) ? '' : '?' . http_build_query($queryParams)
            )
        );

        return $this->getContents($response);
    }

    /**
     * Update shipment info.
     *
     * @param int $shopID
     * @param array $data
     * @return string
     */
    public function updateShipmentInfo(int $shopID, array $data)
    {
        $endpoint = sprintf(
            '/v2/logistic/fs/%s/update',
            $this->getFulfillmentServiceID()
        );

        $queryParams            = [];
        $queryParams['shop_id'] = $shopID;

        $response = $this->call(
            'POST',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
            $data
        );

        return $this->getContents($response);
    }
}
