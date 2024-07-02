<?php

namespace R3H6\Oauth2Server\Tests\Functional;

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

final class ResourceServerTest extends ApplicationTestCase
{
    /**
     * {
     *    "aud": "fc17757d-aa0c-481d-96d7-c2504ce7199f",
     *    "jti": "732f700207d1983acdc1868efee2c51fafe32c349ce392092f48a8de73559200d51941e65310a4ab",
     *    "iat": 1719947495.521719,
     *    "nbf": 1719947495.521721,
     *    "exp": 2035480295.518346,
     *    "sub": "1",
     *    "scopes": [
     *        "api",
     *        "read"
     *    ]
     * }
     */
    private const ACCESS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJmYzE3NzU3ZC1hYTBjLTQ4MWQtOTZkNy1jMjUwNGNlNzE5OWYiLCJqdGkiOiI3MzJmNzAwMjA3ZDE5ODNhY2RjMTg2OGVmZWUyYzUxZmFmZTMyYzM0OWNlMzkyMDkyZjQ4YThkZTczNTU5MjAwZDUxOTQxZTY1MzEwYTRhYiIsImlhdCI6MTcxOTk0NzQ5NS41MjE3MTksIm5iZiI6MTcxOTk0NzQ5NS41MjE3MjEsImV4cCI6MjAzNTQ4MDI5NS41MTgzNDYsInN1YiI6IjEiLCJzY29wZXMiOlsiYXBpIiwicmVhZCJdfQ.AfYG4QVYI5m1rgO_-IiWeYAO8TFAavidzbzUmXDYCdiYV3B1voBP7CkKk61PBLON6ASyuQljm8ZUfciSi8NN1IEY-izP7a28b-CliB-vhgaArdS2vDnGLNbD7X6O7Di-oBxI34tvc92Wij4ShT-ZRCeVuS3gKrqXyInJuo4om_pghglS6vcSvvf5KJzmEzbnn4up16ntqkfjBQKMfQexQDkZJDEDS82dWNze3rEUDbIC4U-82VPTxVHZj3q5k-L4ydgXgkrLtl-cg67Od8J9h_bwIf3REc-tEsULvL1-VYiuFnq0tTpiThdLyVNbZHROM-z83dR4yP-TZvB_lFMJ9Q';

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
