<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Auth;

use Gandung\Tokopedia\AbstractService;

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
		$this->setEndpoint('/token');

		$buf = $this->getHttpClient()->request(
			'POST',
			sprintf('%s?grant_type=client_credentials', $this->getEndpoint()),
			[]
		);

		return new Credential($buf);
	}
}
