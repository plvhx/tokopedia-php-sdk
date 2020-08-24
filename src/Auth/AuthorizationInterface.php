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
}
