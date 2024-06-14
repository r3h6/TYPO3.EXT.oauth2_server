<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Functional;

use Symfony\Component\Mailer\Transport\NullTransport;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

final class AuthorizationServerTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/oauth2_server',
    ];

    protected array $pathsToLinkInTestInstance = [
        'typo3conf/ext/oauth2_server/Tests/Fixtures/config/sites' => 'typo3conf/sites',
    ];

    protected array $configurationToUseInTestInstance = [
        'EXTENSIONS' => [
            'oauth2_server' => [
                'loginPageUid' => 1,
                'consentPageUid' => 2,
            ],
        ],
        'MAIL' => [
            'transport' => NullTransport::class,
        ],
        'LOG' => [
            'R3H6' => [
                'Oauth2Server' => [
                    'writerConfiguration' => [
                        \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [],
                        ],
                    ],
                ],
            ],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/base.csv');
        $this->setUpFrontendRootPage(1, [
            'constants' => ['EXT:oauth2_server/Configuration/TypoScript/constants.typoscript'],
            'setup' => ['EXT:oauth2_server/Configuration/TypoScript/setup.typoscript'],
        ]);
    }

    /**
     * @test
     */
    public function authorizationEndpointRedirectsToLoginPage(): void
    {
        $request = new InternalRequest('https://localhost/oauth2/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => 'fc17757d-aa0c-481d-96d7-c2504ce7199a',
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
            'client_id' => 'fc17757d-aa0c-481d-96d7-c2504ce7199a',
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
            'client_id' => 'fc17757d-aa0c-481d-96d7-c2504ce7199b',
            'redirect_uri' => 'https://localhost/redirect',
            'state' => 'bwqjmz2j2gs',
            'scope' => 'email',
        ]));
        $context = (new InternalRequestContext())->withFrontendUserId(1);
        $response = $this->executeFrontendSubRequest($request, $context);
        self::assertStringContainsString('https://localhost/redirect?code=', $response->getHeaderLine('Location'), 'Response: ' . $response->getBody());
    }
}
