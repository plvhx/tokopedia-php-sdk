<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Fixtures;

use Gandung\Tokopedia\AbstractService;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class ConcreteService extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    protected function getDefaultBaseUrl()
    {
        return 'http://shit.org';
    }
}
