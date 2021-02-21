<?php

namespace R3H6\Oauth2Server\Tests\Functional;

class ResourceOwnerPasswordCredentialsGrantTest extends FunctionalTestCase
{
    use \R3H6\Oauth2Server\Tests\Functional\FunctionalTestHelper;

    /**
     * @test
     */
    public function accessTokenIsIssued()
    {
        $response = $this->doFrontendRequest(
            'POST',
            '/oauth2/token',
            [
                'grant_type' => 'password',
                'client_id' => '660e56d72c12f9a1e2ec',
                'client_secret' => 'CCJL1/s3TQLMHj9le2bBUlD7tmkPZKlOTZGgBQRb3BE=',
                'username' => 'user',
                'password' => 'password',
            ]
        );

        $token = json_decode((string)$response->getBody(), true);
        self::assertSame('Bearer', $token['token_type']);
        self::assertArrayHasKey('expires_in', $token);
        self::assertArrayHasKey('access_token', $token);
    }
}
