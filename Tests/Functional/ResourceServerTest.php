<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Functional;

use PHPUnit\Framework\Attributes\Test;
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

final class ResourceServerTest extends ApplicationTestCase
{
    #[Test]
    public function returnErrorResponseForNonAuthorizedRequest(): void
    {
        $request = (new InternalRequest('https://localhost/api'))
            ->withMethod('POST')
            ->withQueryParams(['type' => 1719943655786]);

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('access_denied', (string)$response->getBody());
    }

    #[Test]
    public function returnSuccessResponseForAuthorizedRequest(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/accessToken.csv');
        $request = (new InternalRequest('https://localhost/api'))
            ->withMethod('POST')
            ->withQueryParams(['type' => 1719943655786])
            ->withHeader('Authorization', 'Bearer ' . self::ACCESS_TOKEN);

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('Hello World!', (string)$response->getBody());
    }

    /**
     * @test
     * @group simple
     */
    public function returnErrorResponseIfRequiredScopeIsMissing(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/accessToken.csv');
        $request = (new InternalRequest('https://localhost/test/missing-scopes'))
            ->withMethod('POST')
            ->withQueryParams(['type' => 1719943655786])
            ->withHeader('Authorization', 'Bearer ' . self::ACCESS_TOKEN);

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('access_denied', (string)$response->getBody());
    }

    /**
     * @test
     * @group simple
     */
    public function returnSuccessResponseForValidScope(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/accessToken.csv');
        $request = (new InternalRequest('https://localhost/test/valid-scopes'))
            ->withMethod('POST')
            ->withQueryParams(['type' => 1719943655786])
            ->withHeader('Authorization', 'Bearer ' . self::ACCESS_TOKEN);

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('Hello World!', (string)$response->getBody());
    }
}
