<?php

namespace R3H6\Oauth2Server\Tests\Functional;

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

final class ResourceServerTest extends ApplicationTestCase
{
    /**
     * @test
     */
    public function returnErrorResponseForNonAuthorizedRequest(): void
    {
        $request = (new InternalRequest('https://localhost/api'))
            ->withMethod('POST')
            ->withQueryParams(['type' => 1719943655786]);

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('access_denied', (string)$response->getBody());
    }

    /**
     * @test
     */
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
