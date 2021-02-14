<?php

namespace R3H6\Oauth2Server\Tests\Functional;

use Defuse\Crypto\Crypto;
use League\OAuth2\Server\CryptKey;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use R3H6\Oauth2Server\Domain\Model\RefreshToken;

class RefreshTokenGrantTest extends FunctionalTestCase
{
    use \R3H6\Oauth2Server\Tests\Functional\FunctionalTestHelper;

    /**
     * @todo
     */
    public function accessTokenIsIssued()
    {
        $refreshToken = $this->createRefreshToken();
        $this->assertSame('', $refreshToken);
        $response = $this->doFrontendRequest(
            'POST',
            '/oauth2/token',
            [
                'grant_type' => 'refresh_token',
                'refresh_token' => $this->createRefreshToken(),
                'client_id' => '660e56d72c12f9a1e2ec',
                'client_secret' => 'CCJL1/s3TQLMHj9le2bBUlD7tmkPZKlOTZGgBQRb3BE=',
                // 'scope' => 'optional',
            ]
        );

        $token = json_decode((string) $response->getBody(), true);
        $this->assertSame('Bearer', $token['token_type']);
        $this->assertArrayHasKey('expires_in', $token);
        $this->assertArrayHasKey('access_token', $token);
    }

    private function createRefreshToken()
    {
        $refreshToken = new RefreshToken();

        $refreshTokenPayload = \json_encode([
            'client_id'        => 'cdef5159119bc8de4743',// '660e56d72c12f9a1e2ec',
            'refresh_token_id' => $refreshToken->getIdentifier(),
            'access_token_id'  => '',
            'scopes'           => '',
            'user_id'          => 1,
            'expire_time'      => strtotime('+1 day'),
        ]);

        return Crypto::encryptWithPassword($refreshTokenPayload, self::ENCRYPTION_KEY);
    }

}
