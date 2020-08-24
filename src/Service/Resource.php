<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Service;

use Gandung\Tokopedia\AbstractService;

class Resource extends AbstractService
{
	/**
	 * @var AuthorizationInterface
	 */
	private $authorization;

	/**
	 * @param AuthorizationInterface $authorization
	 * @param array $config
	 * @return void
	 */
	public function __construct(AuthorizationInterface $authorization, array $config = [])
	{
		parent::__construct($config);
		$this->setAuthorization($authorization);
	}

	/**
	 * Get authorization object.
	 *
	 * @return AuthorizationInterface
	 */
	public function getAuthorization()
	{
		return $this->authorization;
	}

	/**
	 * Set authorization object.
	 *
	 * @param AuthorizationInterface $authorization
	 * @return void
	 */
	public function setAuthorization(AuthorizationInterface $authorization)
	{
		$this->authorization = $authorization;
	}
}
