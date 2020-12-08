<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use InvalidArgumentException;

use function clearstatcache;
use function fopen;
use function http_build_query;
use function sprintf;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Encryption extends Resource
{
    /**
     * Register public key.
     *
     * @param string $filename Public key file name.
     * @return string
     */
    public function registerPublicKey(string $filename)
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(
                sprintf("File (%s) not exists.", $filename)
            );
        }

        // assume given filename has exists. so,
        // clear the inode cache.
        clearstatcache();

        $endpoint = sprintf(
            '/v1/fs/%s/register',
            $this->getFulfillmentServiceID()
        );

        $response = $this->call(
            'POST',
            sprintf("%s?%s", $endpoint, http_build_query(['upload' => 1])),
            [
                'name'     => 'public_key',
                'contents' => fopen($filename, 'r'),
                'filename' => basename($filename)
            ],
            true
        );

        return $this->getContents($response);
    }
}
