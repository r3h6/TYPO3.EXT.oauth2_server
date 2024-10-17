<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
use TYPO3\CMS\Core\Http\StreamFactory;
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

final class ResourceOwnerPasswordCredentialsGrantTest extends ApplicationTestCase
{
    #[Test]
    public function resourceOwnerPasswordCredentialsGrant(): void
    {
        $request = (new InternalRequest('https://localhost/oauth2/token'))
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody((new StreamFactory())->createStream(http_build_query([
                'grant_type' => 'password',
                'client_id' => 'test0000-0000-0000-0000-000000000004',
                'client_secret' => 'Password1!',
                'username' => 'kasper',
                'password' => 'Password1!',
                'scope' => 'email',
            ])));

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('access_token', (string)$response->getBody());
    }

    #[Test]
    public function resourceOwnerPasswordCredentialsGrantWithInvalidCredentials(): void
    {
        $request = (new InternalRequest('https://localhost/oauth2/token'))
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody((new StreamFactory())->createStream(http_build_query([
                'grant_type' => 'password',
                'client_id' => 'test0000-0000-0000-0000-000000000004',
                'client_secret' => 'Password1!',
                'username' => 'kasper',
                'password' => 'invalid',
                'scope' => 'email',
            ])));

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('access_denied', (string)$response->getBody());
    }
}
