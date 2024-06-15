<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Functional;

use TYPO3\CMS\Core\Http\StreamFactory;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

final class ClientCredentialsGrantTest extends ApplicationTestCase
{
    /**
     * @test
     */
    public function clientCredentialsGrant(): void
    {
        $request = (new InternalRequest('https://localhost/oauth2/token'))
            ->withMethod('POST')
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded')
            ->withBody((new StreamFactory())->createStream(http_build_query([
                'grant_type' => 'client_credentials',
                'client_id' => 'test0000-0000-0000-0000-000000000003',
                'client_secret' => 'Password1!',
                'scope' => 'email',
            ])));

        $context = new InternalRequestContext();
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('access_token', (string)$response->getBody(), 'Response: ' . $response->getBody());
    }
}
