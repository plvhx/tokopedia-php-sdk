<?php

declare(strict_types=1);

namespace Gandung\Tokopedia;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface ServiceInterface
{
	/**
	 * Get base URL.
	 *
	 * @return string
	 */
	public function getBaseUrl();
}
