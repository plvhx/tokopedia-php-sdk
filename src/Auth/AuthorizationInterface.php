<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Auth;

use Gandung\Tokopedia\ServiceInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface AuthorizationInterface extends ServiceInterface
{
    /**
     * Get credential object instance by authorizing the
     * application.
     *
     * @return Credential
     */
    public function authorize();

    /**
     * Set cache pool instance.
     *
     * @param CacheItemPoolInterface $cachePool
     * @return void
     */
    public function setCachePool(CacheItemPoolInterface $cachePool);

    /**
     * Get cache pool instance.
     *
     * @return CacheItemPoolInterface
     */
    public function getCachePool();

    /**
     * Set cache tag.
     *
     * @param string $cacheTag
     * @return void
     */
    public function setCacheTag(string $cacheTag);

    /**
     * Get cache tag
     *
     * @return string
     */
    public function getCacheTag();

    /**
     * Determine if authorization object caller
     * want to save fetched authorization metadata
     * or otherwise.
     *
     * @var bool $cached
     * @return void
     */
    public function useCache(bool $cached);

    /**
     * Check if current authorization metadata
     * is cached or not.
     *
     * @return bool
     */
    public function isCached(): bool;
}
