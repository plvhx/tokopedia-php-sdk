<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use InvalidArgumentException;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Interaction extends Resource
{
    /**
     * @var string
     */
    const INTERACTION_MESSAGE_ORDER_ASC = "asc";

    /**
     * @var string
     */
    const INTERACTION_MESSAGE_ORDER_DESC = "desc";

    /**
     * @var string
     */
    const INTERACTION_MESSAGE_FILTER_ALL = "all";

    /**
     * @var string
     */
    const INTERACTION_MESSAGE_FILTER_READ = "read";

    /**
     * @var string
     */
    const INTERACTION_MESSAGE_FILTER_UNREAD = "unread";

    /**
     * @var int
     */
    const INTERACTION_MESSAGE_NO_ATTACHMENT = 0;

    /**
     * @var int
     */
    const INTERACTION_MESSAGE_ATTACHMENT_IMAGE = 2;

    /**
     * @var int
     */
    const INTERACTION_MESSAGE_ATTACHMENT_PDF = 17;

    /**
     * @var int
     */
    const INTERACTION_MESSAGE_ATTACHMENT_CUSTOM_QUOTATION = 19;

    /**
     * Get list of chat message.
     *
     * @param int $shopID Shop ID.
     * @param int $page Current page number.
     * @param int $perPage How much message showed per page.
     * @param string $order
     * @param string $filter
     * @return string
     * @throws InvalidArgumentException when 'page' parameter less or equal to zero.
     * @throws InvalidArgumentException when 'perPage' parameter less or equal to zero.
     * @throws InvalidArgumentException when 'order' parameter is invalid.
     * @throws InvalidArgumentException when 'filter' parameter is invalid.
     */
    public function getListMessage(
        int $shopID,
        int $page = 1,
        int $perPage = 10,
        string $order = self::INTERACTION_MESSAGE_ORDER_ASC,
        string $filter = self::INTERACTION_MESSAGE_FILTER_ALL
    ) {
        if ($page <= 0) {
            throw new InvalidArgumentException("'page' parameter must be larger than zero.");
        }

        if ($perPage <= 0) {
            throw new InvalidArgumentException("'perPage' parameter must be larger than zero.");
        }

        $this->validateMessageOrder($order);
        $this->validateMessageFilter($filter);

        $endpoint = sprintf(
            '/v1/chat/fs/%s/messages',
            $this->getFulfillmentServiceID()
        );

        $queryParams             = [];
        $queryParams['shop_id']  = $shopID;
        $queryParams['page']     = $page;
        $queryParams['per_page'] = $perPage;
        $queryParams['order']    = $order;
        $queryParams['filter']   = $filter;

        $response = $this->call(
            'GET',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams))
        );

        return $this->getContents($response);
    }

    /**
     * Get list of chat reply.
     *
     * @param int $messageID Message ID.
     * @param int $shopID Shop ID.
     * @param int $page Current page number.
     * @param int $perPage How much message showed per page.
     * @param string $order
     * @return string
     * @throws InvalidArgumentException When 'page' parameter less than or equal to zero.
     * @throws InvalidArgumentException When 'perPage' parameter less than or equal to zero.
     * @throws InvalidArgumentException When 'order' parameter is invalid.
     */
    public function getListReply(
        int $messageID,
        int $shopID,
        int $page = 1,
        int $perPage = 10,
        string $order = self::INTERACTION_MESSAGE_ORDER_ASC
    ) {
        if ($page <= 0) {
            throw new InvalidArgumentException("'page' parameter must be larger than zero.");
        }

        if ($perPage <= 0) {
            throw new InvalidArgumentException("'perPage' parameter must be larger than zero.");
        }

        $this->validateMessageOrder($order);

        $endpoint = sprintf(
            '/v1/chat/fs/%s/messages/%d/replies',
            $this->getFulfillmentServiceID(),
            $messageID
        );

        $queryParams             = [];
        $queryParams['shop_id']  = $shopID;
        $queryParams['page']     = $page;
        $queryParams['per_page'] = $perPage;
        $queryParams['order']    = $order;

        $response = $this->call(
            'GET',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams))
        );

        return $this->getContents($response);
    }

    /**
     * Initiate chat with given order ID.
     *
     * @param int $orderID Order ID.
     * @return string
     */
    public function initiateChat(int $orderID)
    {
        $endpoint = sprintf(
            '/v1/chat/fs/%s/initiate',
            $this->getFulfillmentServiceID()
        );

        $queryParams             = [];
        $queryParams['order_id'] = $orderID;

        $response = $this->call(
            'GET',
            sprintf('%s?%s', $endpoint, http_build_query($queryParams))
        );

        return $this->getContents($response);
    }

    /**
     * Send message reply.
     *
     * @param int $message ID.
     * @param array $data.
     * @return string
     */
    public function sendReply(int $messageID, array $data)
    {
        $endpoint   = sprintf(
            '/v1/chat/fs/%s/messages/%d/reply',
            $this->getFulfillmentServiceID(),
            $messageID
        );

        $response = $this->call('POST', $endpoint, $data);

        return $this->getContents($response);
    }

    /**
     * Validate given message order.
     *
     * @param string $order
     * @return void
     * @throws InvalidArgumentException When 'order' parameter is invalid.
     */
    private function validateMessageOrder(string $order)
    {
        switch ($order) {
            case self::INTERACTION_MESSAGE_ORDER_ASC:
            case self::INTERACTION_MESSAGE_ORDER_DESC:
                break;
            default:
                throw new InvalidArgumentException("Invalid message order.");
        }
    }

    /**
     * Validate given message filter.
     *
     * @param string $filter
     * @return void
     * @throws InvalidArgumentException When 'filter' parameter is invalid.
     */
    private function validateMessageFilter(string $filter)
    {
        switch ($filter) {
            case self::INTERACTION_MESSAGE_FILTER_ALL:
            case self::INTERACTION_MESSAGE_FILTER_READ:
            case self::INTERACTION_MESSAGE_FILTER_UNREAD:
                break;
            default:
                throw new InvalidArgumentException("Invalid message filter.");
        }
    }

    /**
     * Validate given message attachment type.
     *
     * @param int $attachmentType
     * @return void
     * @throws InvalidArgumentException When 'filter' parameter is invalid.
     */
    private function validateMessageAttachmentType(int $attachmentType)
    {
        switch ($attachmentType) {
            case self::INTERACTION_MESSAGE_NO_ATTACHMENT:
            case self::INTERACTION_MESSAGE_ATTACHMENT_IMAGE:
            case self::INTERACTION_MESSAGE_ATTACHMENT_PDF:
            case self::INTERACTION_MESSAGE_ATTACHMENT_CUSTOM_QUOTATION:
                break;
            default:
                throw new InvalidArgumentException("Invalid message attachment type.");
        }
    }
}
