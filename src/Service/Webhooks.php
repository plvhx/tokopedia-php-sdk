<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Webhooks extends Resource
{
    /**
     * List all registered webhooks.
     *
     * @return string
     */
    public function listRegisteredWebhooks()
    {
        $endpoint = sprintf(
            '/v1/fs/%d',
            $this->getFulfillmentServiceID()
        );
        $response = $this->call('GET', $endpoint);

        return $this->getContents($response);
    }

    /**
     * Register list of url webhooks.
     *
     * @param array $data
     * @return string
     */
    public function registerWebhooks(array $data)
    {
        $endpoint = sprintf(
            '/v1/fs/%d/register',
            $this->getFulfillmentServiceID()
        );
        $response = $this->call('POST', $endpoint, $data);

        return $this->getContents($response);
    }

    /**
     * Manually trigger webhook.
     *
     * @param array $data
     * @return string
     */
    public function triggerWebhook(array $data)
    {
        $endpoint = sprintf(
            '/v1/fs/%d/trigger',
            $this->getFulfillmentServiceID()
        );
        $response = $this->call('POST', $endpoint, $data);

        return $this->getContents($response);
    }
}
