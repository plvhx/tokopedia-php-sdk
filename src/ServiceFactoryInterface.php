<?php

declare(strict_types=1);

namespace Gandung\Tokopedia;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface ServiceFactoryInterface
{
    /**
     * Get campaign service.
     *
     * @return ServiceInterface
     */
    public function getCampaign();

    /**
     * Get category service.
     *
     * @return ServiceInterface
     */
    public function getCategory();

    /**
     * Get interaction service.
     *
     * @return ServiceInterface
     */
    public function getInteraction();

    /**
     * Get logistic service.
     *
     * @return ServiceInterface
     */
    public function getLogistic();

    /**
     * Get order service.
     *
     * @return ServiceInterface
     */
    public function getOrder();

    /**
     * Get product service.
     *
     * @return ServiceInterface
     */
    public function getProduct();

    /**
     * Get shop service.
     *
     * @return ServiceInterface
     */
    public function getShop();
}
