<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Functional;

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

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

final class AuthorizationCodeGrantTest extends ApplicationTestCase
{
    /**
     * @test
     */
    public function authorizationEndpointRedirectsToLoginPage(): void
    {
        $request = new InternalRequest('https://localhost/oauth2/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => 'test0000-0000-0000-0000-000000000001',
            'redirect_uri' => 'https://localhost/redirect',
            'state' => 'bwqjmz2j2gs',
        ]));
        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('https://localhost/?redirect_url=', $response->getHeaderLine('Location'), 'Response: ' . $response->getBody());
    }

    /**
     * @test
     */
    public function authorizationEndpointRedirectsToConsentPage(): void
    {
        $request = new InternalRequest('https://localhost/oauth2/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => 'test0000-0000-0000-0000-000000000001',
            'redirect_uri' => 'https://localhost/redirect',
            'state' => 'bwqjmz2j2gs',
            'scope' => 'email',
        ]));
        $context = (new InternalRequestContext())->withFrontendUserId(1);
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('https://localhost/consent', $response->getHeaderLine('Location'), 'Response: ' . $response->getBody());
    }

    /**
     * @test
     */
    public function authorizationEndpointReturnsAuthCode(): void
    {
        $request = new InternalRequest('https://localhost/oauth2/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => 'test0000-0000-0000-0000-000000000002',
            'redirect_uri' => 'https://localhost/redirect',
            'state' => 'bwqjmz2j2gs',
            'scope' => 'email',
        ]));
        $context = (new InternalRequestContext())->withFrontendUserId(1);
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('https://localhost/redirect?code=', $response->getHeaderLine('Location'), 'Response: ' . $response->getBody());
    }
}
