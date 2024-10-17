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

final class RefreshTokenGrantTest extends ApplicationTestCase
{
    #[Test]
    public function refreshTokenGrant(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/refreshToken.csv');
        $request = (new InternalRequest('https://localhost/oauth2/token'))
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody((new StreamFactory())->createStream(http_build_query([
                'grant_type' => 'refresh_token',
                'refresh_token' => self::REFRESH_TOKEN,
                'client_id' => 'a491f467-deaa-4bd1-b095-43811001f2b4',
                'client_secret' => 'Password1!',
                'scope' => 'api read',
            ])));

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('access_token', (string)$response->getBody());
    }
}
