<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests;

use Gandung\Tokopedia\ServiceFactory;
use Gandung\Tokopedia\Auth\Authorization;
use Gandung\Tokopedia\Service\Campaign;
use Gandung\Tokopedia\Service\Category;
use Gandung\Tokopedia\Service\Encryption;
use Gandung\Tokopedia\Service\Interaction;
use Gandung\Tokopedia\Service\Logistic;
use Gandung\Tokopedia\Service\Order;
use Gandung\Tokopedia\Service\Product;
use Gandung\Tokopedia\Service\Shop;
use Gandung\Tokopedia\Service\Webhooks;
use PHPUnit\Framework\TestCase;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class ServiceFactoryTest extends TestCase
{
    /**
     * @var \Gandung\Tokopedia\ServiceFactoryInterface
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->cachePool = $this->createMock(CacheItemPoolInterface::class);
        $this->factory   = new ServiceFactory(new Authorization($this->cachePool, []));
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
    }

    public function testCanGetCampaignServiceObject()
    {
        $this->assertInstanceOf(Campaign::class, $this->factory->getCampaign());
    }

    public function testCanGetCategoryServiceObject()
    {
        $this->assertInstanceOf(Category::class, $this->factory->getCategory());
    }

    public function testCanGetEncryptionServiceObject()
    {
        $this->assertInstanceOf(Encryption::class, $this->factory->getEncryption());
    }

    public function testCanGetInteractionServiceObject()
    {
        $this->assertInstanceOf(Interaction::class, $this->factory->getInteraction());
    }

    public function testCanGetLogisticServiceObject()
    {
        $this->assertInstanceOf(Logistic::class, $this->factory->getLogistic());
    }

    public function testCanGetOrderServiceObject()
    {
        $this->assertInstanceOf(Order::class, $this->factory->getOrder());
    }

    public function testCanGetProductServiceObject()
    {
        $this->assertInstanceOf(Product::class, $this->factory->getProduct());
    }

    public function testCanGetShopServiceObject()
    {
        $this->assertInstanceOf(Shop::class, $this->factory->getShop());
    }

    public function testCanGetWebhooksServiceObject()
    {
        $this->assertInstanceOf(Webhooks::class, $this->factory->getWebhooks());
    }
}
