<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Credential;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
final class Credential implements CredentialInterface
{
	/**
	 * @var array
	 */
	private $data;

	/**
	 * @param array $data
	 * @return void
	 */
	public function __construct(array $data)
	{
		$this->initialize($data);
	}

	/**
	 * @param array $data
	 * @return void
	 */
	private function initialize(array $data)
	{
		$this->validateCredentialField($data);
		$this->data = $data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAccessToken()
	{
		return $this->data['access_token'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getExpiresIn()
	{
		return $this->data['expires_in'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTokenType()
	{
		return $this->data['token_type'];
	}

	/**
	 * Validate required fields in credential metadata.
	 *
	 * @param array $data
	 * @return void
	 * @throws InvalidArgumentException When one or more required fields missing.
	 */
	private function validateCredentialField(array $data)
	{
		$requiredFields = $this->getRequiredFields();
		$validated      = true;

		foreach (array_keys($data) as $key) {
			if (!in_array($key, $requiredFields, true)) {
				$validated = false;
				break;
			}
		}

		if (!$validated) {
			throw new InvalidArgumentException(
				sprintf(
					"Missing '%s' field in credential metadata.",
					$key
				)
			);
		}
	}

	/**
	 * Get required fields.
	 *
	 * @return array
	 */
	private function getRequiredFields()
	{
		return [
			'access_token',
			'expires_in',
			'token_type'
		];
	}
}
