<?php

declare(strict_types=1);

namespace Gandung\Tokopedia;

use Gandung\Tokopedia\Auth\AuthorizationInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
trait ServiceFactoryTrait
{
    /**
     * Get authorization object.
     *
     * @return AuthorizationInterface
     */
    private function getAuthorization()
    {
        return $this->authorization;
    }

    /**
     * Set authorization object.
     *
     * @param AuthorizationInterface $authorization
     * @return void
     */
    private function setAuthorization(AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }
}
