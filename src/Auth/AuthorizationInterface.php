<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Auth;

use Gandung\Tokopedia\ServiceInterface;

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
}
