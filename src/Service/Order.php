<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use InvalidArgumentException;
use Gandung\Tokopedia\AbstractService;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Order extends AbstractService
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
	 * @throws InvalidArgumentException When current page number less than 1.
	 * @throws InvalidArgumentException When Shop ID and Warehouse ID both exists.
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
		$this->setEndpoint('/v2/order/list');

		if ($page < 1) {
			// throw something..
		}

		if ($shopID > 0 && $warehouseID > 0) {
			// throw something..
		}

		if ($status !== 0) {
			$this->validateOrderStatusCode($status);
		}

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
			sprintf(
				'%s?%s',
				$this->getEndpoint(),
				http_build_query($queryParams)
			)
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
		$this->setEndpoint(
			sprintf(
				'/v2/fs/%s/order',
				$this->getFulfillmentServiceID()
			)
		);

		$queryParams             = [];
		$queryParams['order_id'] = $orderID;

		if ($invoiceNo !== '') {
			$queryParams['invoice_num'] = $invoiceNo;
		}

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
	 * Get shipping label.
	 *
	 * @param int $orderID Order ID.
	 * @param int $printed Whether want to print or not.
	 * @return string
	 */
	public function getShippingLabel(int $orderID, int $printed = 0)
	{
		$this->setEndpoint(
			sprintf(
				'/v1/order/%d/fs/%s/shipping-label',
				$orderID,
				$this->getFulfillmentServiceID()
			)
		);

		$queryParams            = [];
		$queryParams['printed'] = $printed;

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
	 * Accept order.
	 *
	 * @param int $orderID Order ID.
	 * @return string
	 */
	public function acceptOrder(int $orderID)
	{
		$this->setEndpoint(
			sprintf(
				'/v1/order/%d/fs/%s/ack',
				$orderID,
				$this->getFulfillmentServiceID()
			)
		);

		return $this->getHttpClient()->request('POST', $this->getEndpoint());
	}

	public function rejectOrder(int $orderID, array $data)
	{
		$this->setEndpoint(
			sprintf(
				'/v1/order/%d/fs/%s/nack',
				$orderID,
				$this->getFulfillmentServiceID()
			)
		);

		return $this->getHttpClient()->request('POST', $this->getEndpoint(), $data);
	}

	public function updateOrderStatus(int $orderID, array $data)
	{
		$this->setEndpoint(
			sprintf(
				'/v1/order/%d/fs/%s/status',
				$orderID,
				$this->getFulfillmentServiceID()
			)
		);

		return $this->getHttpClient()->request('POST', $this->getEndpoint(), $data);
	}

	public function requestPickUp(array $data)
	{
		$this->setEndpoint(
			sprintf(
				'/inventory/v1/fs/%s/pick-up',
				$this->getFulfillmentServiceID()
			)
		);

		return $this->getHttpClient()->request('POST', $this->getEndpoint(), $data);
	}

	private function getAggregatedOrderStatusCodes()
	{
		return [
			self::ORDER_SELLER_ACCEPT_ORDER,
			self::ORDER_SELLER_REJECT_ORDER,
			self::ORDER_SHIPMENT
		];
	}

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

	private function validateOrderStatusCode(int $status)
	{
		if (!in_array($status, $this->getAggregatedOrderStatusCodes(), true)) {
			throw new InvalidArgumentException(
				"Invalid order status code."
			);
		}

		return;
	}
}
