<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Auth;

use Gandung\Tokopedia\AbstractService;

use function base64_encode;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class Authorization extends AbstractService implements AuthorizationInterface
{
	/**
	 * {@inheritdoc}
	 */
	public function authorize()
	{
		$headers = [
			'Authorization' => sprintf('Basic %s', $this->getSerializedBasicCredential()),
			'Content-Length' => 0
		];

		$buf  = $this->getHttpClient()->request(
			'POST',
			'/token?grant_type=client_credentials',
			['headers' => $headers]
		);

		return new Credential($buf);
	}

	/**
	 * Get serialized basic credential in form <client_id>:<client_secret>
	 *
	 * @return string
	 */
	private function getSerializedBasicCredential()
	{
		return base64_encode(sprintf('%s:%s', $this->getClientID(), $this->getClientSecret()));
	}
}
