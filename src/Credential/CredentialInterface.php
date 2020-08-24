<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Credential;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
interface CredentialInterface
{
	/**
	 * Get access token.
	 *
	 * @return string
	 */
	public function getAccessToken();

	/**
	 * Get expires in seconds.
	 *
	 * @return int
	 */
	public function getExpiresIn();

	/**
	 * Get access token type.
	 *
	 * @return string
	 */
	public function getTokenType();
}
