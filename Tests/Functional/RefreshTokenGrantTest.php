<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Tests\Functional;

use Defuse\Crypto\Crypto;
use R3H6\Oauth2Server\Domain\Model\RefreshToken;

/***
 *
 * This file is part of the "OAuth2 Server" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * RefreshTokenGrantTest
 */
class RefreshTokenGrantTest extends FunctionalTestCase
{
    use \R3H6\Oauth2Server\Tests\Functional\FunctionalTestHelper;

    /**
     * @test
     */
    public function accessTokenIsIssued()
    {
        self::markTestIncomplete('Todo');

        $refreshToken = $this->createRefreshToken();
        self::assertSame('', $refreshToken);
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

        $token = json_decode((string)$response->getBody(), true);
        self::assertSame('Bearer', $token['token_type']);
        self::assertArrayHasKey('expires_in', $token);
        self::assertArrayHasKey('access_token', $token);
    }

    private function createRefreshToken()
    {
        $refreshToken = new RefreshToken();

        $refreshTokenPayload = \json_encode([
            'client_id'        => 'cdef5159119bc8de4743', // '660e56d72c12f9a1e2ec',
            'refresh_token_id' => $refreshToken->getIdentifier(),
            'access_token_id'  => '',
            'scopes'           => '',
            'user_id'          => 1,
            'expire_time'      => strtotime('+1 day'),
        ]);

        return Crypto::encryptWithPassword($refreshTokenPayload, self::ENCRYPTION_KEY);
    }
}
