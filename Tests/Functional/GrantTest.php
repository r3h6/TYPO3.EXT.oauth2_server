<?php

namespace R3H6\Oauth2Server\Tests\Functional;


class GrantTest extends FunctionalTestCase
{
    use \R3H6\Oauth2Server\Tests\Functional\FunctionalTestHelper;

    /**
     * @test
     */
    public function clientCredentialsGrant()
    {
        $response = $this->doFrontendRequest(
            'GET',
            '/oauth/authorize',
            [
                'response_type' => 'code',
                'client_id' => '660e56d72c12f9a1e2ec',
                'redirect_uri' => 'http://localhost/',
            ]
        );

        $response = $this->doFrontendRequest(
            'POST',
            '/?logintype=login',
            ['user' => 'user', 'pass' => 'password'],
            $this->getLastCookie()
        );

        $response = $this->doFrontendRequest(
            'POST',
            '/oauth/authorize',
            [],
            $this->getLastCookie()
        );

        $response = $this->doFrontendRequest(
            'POST',
            '/oauth/token',
            [
                'grant_type' => 'authorization_code',
                'client_id' => '660e56d72c12f9a1e2ec',
                'client_secret' => 'CCJL1/s3TQLMHj9le2bBUlD7tmkPZKlOTZGgBQRb3BE=',
                'redirect_uri' => 'http://localhost/',
                'code' => $this->getCodeFromResponse($response),
            ]
        );

        $token = json_decode((string) $response->getBody(), true);
        $this->assertSame('Bearer', $token['token_type']);
        $this->assertArrayHasKey('expires_in', $token);
        $this->assertArrayHasKey('access_token', $token);
    }

}
