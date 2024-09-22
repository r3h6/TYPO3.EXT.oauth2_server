<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Functional;

use Symfony\Component\HttpFoundation\Response;
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

final class RevokeTokenTest extends ApplicationTestCase
{
    /**
     * @test
     */
    public function revokeAccessToken(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/accessToken.csv');
        $request = (new InternalRequest('https://localhost/oauth2/revoke'))
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withHeader('Authorization', 'Basic ' . base64_encode('fc17757d-aa0c-481d-96d7-c2504ce7199f:Password1!'))
            ->withBody((new StreamFactory())->createStream(http_build_query([
                'token' => self::ACCESS_TOKEN,
            ])));

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), 'Response: ' . $response->getBody());
        $this->assertCSVDataSet(__DIR__ . '/Assertions/revokedAccessToken.csv');
    }

    /**
     * @test
     */
    public function revokeRefreshToken(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/refreshToken.csv');
        $request = (new InternalRequest('https://localhost/oauth2/revoke'))
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withHeader('Authorization', 'Basic ' . base64_encode('fc17757d-aa0c-481d-96d7-c2504ce7199f:Password1!'))
            ->withBody((new StreamFactory())->createStream(http_build_query([
                'token' => self::REFRESH_TOKEN,
            ])));

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), 'Response: ' . $response->getBody());
        $this->assertCSVDataSet(__DIR__ . '/Assertions/revokedRefreshToken.csv');
    }
}
