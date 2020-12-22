<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use InvalidArgumentException;

use function http_build_query;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Campaign extends Resource
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

        $endpoint = sprintf(
            '/v1/slash-price/fs/%d/view',
            $this->getFulfillmentServiceID()
        );

        $queryParams             = [];
        $queryParams['shop_id']  = $shopID;
        $queryParams['page']     = $page;
        $queryParams['per_page'] = $perPage;
        $queryParams['status']   = $status;

        $response = $this->call(
            'GET',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
        );

        return $this->getContents($response);
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
        $endpoint = sprintf(
            '/v1/campaign/fs/%d/view',
            $this->getFulfillmentServiceID()
        );

        $queryParams               = [];
        $queryParams['shop_id']    = $shopID;
        $queryParams['product_id'] = $productID;

        $response = $this->call(
            'GET',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams)),
        );

        return $this->getContents($response);
    }

    /**
     * Add slash price for given shop ID.
     *
     * @param int $shopID Shop ID.
     * @param array $data
     * @return string
     */
    public function addSlashPrice(int $shopID, array $data)
    {
        $endpoint = sprintf(
            '/v1/slash-price/fs/%d/add',
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

    /**
     * Update slash price for given shop ID.
     *
     * @param int $shopID Shop ID.
     * @param array $data
     * @return string
     */
    public function updateSlashPrice(int $shopID, array $data)
    {
        $endpoint = sprintf(
            '/v1/slash-price/fs/%d/update',
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

    /**
     * Cancel slash price.
     *
     * @param int $shopID Shop ID.
     * @param array $data
     * @return string
     */
    public function cancelSlashPrice(int $shopID, array $data)
    {
        $endpoint = sprintf(
            '/v1/slash-price/fs/%d/cancel',
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
            case self::SLASH_PRICE_ALL:
            case self::SLASH_PRICE_ACTIVE:
            case self::SLASH_PRICE_INACTIVE:
            case self::SLASH_PRICE_COMING_SOON:
            case self::SLASH_PRICE_REDIRECTED:
                break;
            default:
                throw new InvalidArgumentException("Invalid slash price status.");
        }

        return;
    }
}
