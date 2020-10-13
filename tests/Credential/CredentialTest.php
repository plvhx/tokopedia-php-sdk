<?php

declare(strict_types=1);

namespace Gandung\Tokopedia\Tests\Credential;

use InvalidArgumentException;
use Gandung\Tokopedia\Credential\Credential;
use Gandung\Tokopedia\Credential\CredentialInterface;
use PHPUnit\Framework\TestCase;

use function base64_encode;
use function time;

/**
 * @author Paulus Gandung Prakosa <rvn.plvhx@gmail.com>
 */
class CredentialTest extends TestCase
{
    public function testWillThrowExceptionWhenInstantiate()
    {
        $this->expectException(InvalidArgumentException::class);
        $credential = new Credential(['foobar']);
    }

    public function credentialMetadataProvider()
    {
        $metadata = [
            'access_token'    => base64_encode('abc:cba'),
            'event_code'      => '',
            'expires_in'      => time() + 3600,
            'last_login_type' => '',
            'sq_check'        => '',
            'token_type'      => 'bearer'
        ];

        return [[$metadata]];
    }

    /**
     * @dataProvider credentialMetadataProvider
     */
    public function testCanGetSpecifiedFieldInCredentialMetadata($metadata)
    {
        $credential = new Credential($metadata);
        $this->assertInstanceOf(CredentialInterface::class, $credential);
        $this->assertEquals($metadata['access_token'], $credential->getAccessToken());
        $this->assertEquals($metadata['expires_in'], $credential->getExpiresIn());
        $this->assertEquals($metadata['token_type'], $credential->getTokenType());
        $this->assertEquals($metadata, $credential->getData());
    }
}
