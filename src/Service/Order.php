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

		if ($shopID > 0 && $warehouseID > 0) {
			throw new RuntimeException(
				"Cannot set both shop ID and warehouse ID. Select one."
			);	
		}

		if ($status !== 0) {
			$this->validateOrderStatusCode($status);
		}

		$credential = $this->getAuthorization()->authorize();
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams              = [];
		$queryParams['fs_id']     = $this->getFulfillmentServiceID();
		$queryParams['from_date'] = $fromDate;
		$queryParams['to_date']   = $toDate;
		$queryParams['page']      = $page;
		$queryParams['per_page']  = $perPage;

		if ($shopID !== 0) {
			$queryParams['shop_id'] = $shopID;	
		}

		if ($warehouseID !== 0) {
			$queryParams['warehouse_id'] = $warehouseID;
		}

		$queryParams['status'] = $status;

		return $this->getHttpClient()->request(
			'GET',
			sprintf('/v2/order/list?%s', http_build_query($queryParams)),
			['headers' => $headers]
		);
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
		$credential = $this->getAuthorization()->authorize();
		$endpoint   = sprintf(
			'/v2/fs/%s/order',
			$this->getFulfillmentServiceID()
		);
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams             = [];
		$queryParams['order_id'] = $orderID;

		if ($invoiceNo !== '') {
			$queryParams['invoice_num'] = $invoiceNo;
		}

		return $this->getHttpClient()->request(
			'GET',
			sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
			['headers' => $headers]
		);
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
		$credential = $this->getAuthorization()->authorize();
		$endpoint   = sprintf(
			'/v1/order/%d/fs/%s/shipping-label',
			$orderID,
			$this->getFulfillmentServiceID()
		);
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		$queryParams            = [];
		$queryParams['printed'] = $printed;

		return $this->getHttpClient()->request(
			'GET',
			sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
			['headers' => $headers]
		);
	}

	/**
	 * Accept order.
	 *
	 * @param int $orderID Order ID.
	 * @return string
	 */
	public function acceptOrder(int $orderID)
	{
		$credential = $this->getAuthorization()->authorize();
		$headers    = [
			'Authorization' => sprintf("Bearer %s", $credential->getAccessToken())
		];

		return $this->getHttpClient()->request(
			'POST',
			sprintf(
				'/v1/order/%d/fs/%s/ack',
				$orderID,
				$this->getFulfillmentServiceID()
			),
			['headers' => $headers]
		);
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
		return $this->getHttpClient()->request(
			'POST',
			sprintf(
				'/v1/order/%d/fs/%s/nack',
				$orderID,
				$this->getFulfillmentServiceID()
			),
			$data
		);
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
		return $this->getHttpClient()->request(
			'POST',
			sprintf(
				'/v1/order/%d/fs/%s/status',
				$orderID,
				$this->getFulfillmentServiceID()
			),
			$data
		);
	}

	/**
	 * Request pick up.
	 *
	 * @param array $data
	 * @return string
	 */
	public function requestPickUp(array $data)
	{
		return $this->getHttpClient()->request(
			'POST',
			sprintf(
				'/inventory/v1/fs/%s/pick-up',
				$this->getFulfillmentServiceID()
			),
			$data
		);
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
