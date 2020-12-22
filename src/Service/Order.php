<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use InvalidArgumentException;
use RuntimeException;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Order extends Resource
{
    /**
     * @var int
     */
    const ORDER_SELLER_ACCEPT_ORDER = 400;

    /**
     * @var int
     */
    const ORDER_SELLER_REJECT_ORDER = 10;

    /**
     * @var int
     */
    const ORDER_SHIPMENT = 500;

    /**
     * @var int
     */
    const ORDER_REJECT_SHIPPING_CASE = 0;

    /**
     * @var int
     */
    const ORDER_REJECT_PRODUCT_OUT_OF_STOCK = 1;

    /**
     * @var int
     */
    const ORDER_REJECT_PRODUCT_VARIANT_UNAVAILABLE = 2;

    /**
     * @var int
     */
    const ORDER_REJECT_WRONG_PRICE_OR_WEIGHT = 3;

    /**
     * @var int
     */
    const ORDER_REJECT_SHOP_CLOSED = 4;

    /**
     * @var int
     */
    const ORDER_REJECT_OTHERS = 5;

    /**
     * @var int
     */
    const ORDER_REJECT_COURIER_PROBLEM = 7;

    /**
     * @var int
     */
    const ORDER_REJECT_BUYER_REQUEST = 8;

    /**
     * Get all orders.
     *
     * @param int $fromDate From date (timestamp).
     * @param int $toDate To date (timestamp).
     * @param int $page Current pagination page.
     * @param int $perPage How much item to be showed per page.
     * @param int $shopID Shop ID.
     * @param int $warehouseID Warehouse ID.
     * @param int $status Status.
     * @return string
     * @throws InvalidArgumentException When current page number less than or equal to zero.
     * @throws RuntimeException When Shop ID and Warehouse ID both exists.
     * @throws InvalidArgumentException When status code is invalid.
     */
    public function getAllOrders(
        int $fromDate,
        int $toDate,
        int $page,
        int $perPage,
        int $shopID = 0,
        int $warehouseID = 0,
        int $status = 0
    ) {
        if ($page <= 0) {
            throw new InvalidArgumentException(
                "Page number cannot be less than or equal to zero."
            );
        }

        if ($perPage <= 0) {
            throw new InvalidArgumentException(
                "Per page number cannot be less than or equal to zero."
            );
        }

        if ($shopID > 0 && $warehouseID > 0) {
            throw new RuntimeException(
                "Cannot set both shop ID and warehouse ID. Select one."
            );
        }

        if ($status !== 0) {
            $this->validateOrderStatusCode($status);
        }

        $queryParams = [
            'fs_id'     => $this->getFulfillmentServiceID(),
            'from_date' => $fromDate,
            'to_date'   => $toDate,
            'page'      => $page,
            'per_page'  => $perPage
        ];

        if ($shopID !== 0) {
            $queryParams['shop_id'] = $shopID;
        }

        if ($warehouseID !== 0) {
            $queryParams['warehouse_id'] = $warehouseID;
        }

        $queryParams['status'] = $status;

        $response = $this->call(
            'GET',
            sprintf('/v2/order/list?%s', http_build_query($queryParams))
        );

        return $this->getContents($response);
    }

    /**
     * Get single order.
     *
     * @param int $orderID Order ID
     * @param string $invoiceNo Invoice number.
     * @return string
     */
    public function getSingleOrder(int $orderID, string $invoiceNo = '')
    {
        $endpoint = sprintf(
            '/v2/fs/%d/order',
            $this->getFulfillmentServiceID()
        );

        $queryParams = [
            'order_id' => $orderID
        ];

        if ($invoiceNo !== '') {
            $queryParams['invoice_num'] = $invoiceNo;
        }

        $response = $this->call(
            'GET',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams))
        );

        return $this->getContents($response);
    }

    /**
     * Get shipping label.
     *
     * @param int $orderID Order ID.
     * @param int $printed Whether want to print or not.
     * @return string
     */
    public function getShippingLabel(int $orderID, int $printed = 0)
    {
        $endpoint = sprintf(
            '/v1/order/%d/fs/%d/shipping-label',
            $orderID,
            $this->getFulfillmentServiceID()
        );

        $queryParams = [
            'printed' => $printed
        ];

        $response = $this->call(
            'GET',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams))
        );

        return $this->getContents($response);
    }

    /**
     * Accept order.
     *
     * @param int $orderID Order ID.
     * @return string
     */
    public function acceptOrder(int $orderID)
    {
        $response = $this->call(
            'POST',
            sprintf(
                '/v1/order/%d/fs/%d/ack',
                $orderID,
                $this->getFulfillmentServiceID()
            )
        );

        return $this->getContents($response);
    }

    /**
     * Reject order.
     *
     * @param int $orderID Order ID.
     * @param array $data
     * @return string
     */
    public function rejectOrder(int $orderID, array $data)
    {
        $response = $this->call(
            'POST',
            sprintf(
                '/v1/order/%d/fs/%d/nack',
                $orderID,
                $this->getFulfillmentServiceID()
            ),
            $data
        );

        return $this->getContents($response);
    }

    /**
     * Update order status.
     *
     * @param int $orderID Order ID.
     * @param array $data
     * @return string
     */
    public function updateOrderStatus(int $orderID, array $data)
    {
        $response = $this->call(
            'POST',
            sprintf(
                '/v1/order/%d/fs/%d/status',
                $orderID,
                $this->getFulfillmentServiceID()
            ),
            $data
        );

        return $this->getContents($response);
    }

    /**
     * Request pick up.
     *
     * @param array $data
     * @return string
     */
    public function requestPickUp(array $data)
    {
        $response = $this->call(
            'POST',
            sprintf(
                '/inventory/v1/fs/%d/pick-up',
                $this->getFulfillmentServiceID()
            ),
            $data
        );

        return $this->getContents($response);
    }

    /**
     * Get aggregated order status codes.
     *
     * @return array
     */
    private function getAggregatedOrderStatusCodes()
    {
        return [
            self::ORDER_SELLER_ACCEPT_ORDER,
            self::ORDER_SELLER_REJECT_ORDER,
            self::ORDER_SHIPMENT
        ];
    }

    /**
     * Get aggregated order reject status codes.
     *
     * @return array
     */
    private function getAggregatedOrderRejectStatusCodes()
    {
        return [
            self::ORDER_REJECT_SHIPPING_CASE,
            self::ORDER_REJECT_PRODUCT_OUT_OF_STOCK,
            self::ORDER_REJECT_PRODUCT_VARIANT_UNAVAILABLE,
            self::ORDER_REJECT_WRONG_PRICE_OR_WEIGHT,
            self::ORDER_REJECT_SHOP_CLOSED,
            self::ORDER_REJECT_OTHERS,
            self::ORDER_REJECT_COURIER_PROBLEM,
            self::ORDER_REJECT_BUYER_REQUEST
        ];
    }

    /**
     * Validate order status code.
     *
     * @return void
     * @throws InvalidArgumentException When order status code is invalid.
     */
    private function validateOrderStatusCode(int $status)
    {
        if (!in_array($status, $this->getAggregatedOrderStatusCodes(), true)) {
            throw new InvalidArgumentException(
                "Invalid order status code."
            );
        }

        return;
    }

    /**
     * Validate order reject status code.
     *
     * @return void
     * @throws InvalidArgumentException When order reject status code is invalid.
     */
    private function validateOrderRejectStatusCode(int $status)
    {
        if (!in_array($status, $this->getAggregatedOrderRejectStatusCodes(), true)) {
            throw new InvalidArgumentException(
                "Invalid order reject status code."
            );
        }

        return;
    }
}
