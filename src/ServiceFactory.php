<?php

declare(strict_types=1);

namespace Gandung\Tokopedia;

use Gandung\Tokopedia\Auth\AuthorizationInterface;
use Gandung\Tokopedia\Service\Campaign;
use Gandung\Tokopedia\Service\Category;
use Gandung\Tokopedia\Service\Encryption;
use Gandung\Tokopedia\Service\Interaction;
use Gandung\Tokopedia\Service\Logistic;
use Gandung\Tokopedia\Service\Order;
use Gandung\Tokopedia\Service\Product;
use Gandung\Tokopedia\Service\Shop;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
final class ServiceFactory implements ServiceFactoryInterface
{
    use ServiceFactoryTrait;

    /**
     * @var AuthorizationInterface
     */
    private $authorization;

    /**
     * @param AuthorizationInterface $authorization
     * @return void
     */
    public function __construct(AuthorizationInterface $authorization)
    {
        $this->setAuthorization($authorization);
    }

    /**
     * {@inheritdoc}
     */
    public function getCampaign()
    {
        return new Campaign($this->getAuthorization());
    }

    /**
     * {@inheritdoc}
     */
    public function getCategory()
    {
        return new Category($this->getAuthorization());
    }

    /**
     * {@inheritdoc}
     */
    public function getEncryption()
    {
        return new Encryption($this->getAuthorization());
    }

    /**
     * {@inheritdoc}
     */
    public function getInteraction()
    {
        return new Interaction($this->getAuthorization());
    }

    /**
     * {@inheritdoc}
     */
    public function getLogistic()
    {
        return new Logistic($this->getAuthorization());
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return new Order($this->getAuthorization());
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct()
    {
        return new Product($this->getAuthorization());
    }

    /**
     * {@inheritdoc}
     */
    public function getShop()
    {
        return new Shop($this->getAuthorization());
    }
}
