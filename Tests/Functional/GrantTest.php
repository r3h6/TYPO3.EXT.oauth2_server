<?php

namespace R3H6\Oauth2Server\Tests\Functional;

use TYPO3\CMS\Core\Http\Uri;
use TYPO3\CMS\Core\Http\Request;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\Http\RequestHandler;
use Psr\Http\Server\RequestHandlerInterface;
use R3H6\Oauth2Server\Middleware\AuthorizationHandler;
use TYPO3\CMS\Core\DependencyInjection\ContainerBuilder;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\Response;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalResponse;
use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequestContext;

class GrantTest extends FunctionalTestCase
{
    protected $testExtensionsToLoad = [
        'typo3conf/ext/oauth2_server',
    ];

    protected $pathsToLinkInTestInstance = [
        'typo3conf/ext/oauth2_server/Tests/Fixtures/config/sites' => 'typo3conf/sites',
    ];

    protected $configurationToUseInTestInstance = [
        'LOG' => [
            'R3H6' => [
                'Oauth2Server' => [
                    'writerConfiguration' => [
                        \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                            \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [],
                        ]
                    ]
                ]
            ],
            'TYPO3' => [
                'CMS' => [
                    'Frontend' => [
                        'Authentication' => [
                            'writerConfiguration' => [
                                \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                                    \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                                        'logFile' =>  'typo3temp/var/log/auth.log'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importDataSet('EXT:oauth2_server/Tests/Fixtures/Database/pages.xml');
        $this->setUpFrontendRootPage(1);
    }

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

        $redirect = new Uri($response->getHeader('location')[0]);
        $params = [];
        parse_str($redirect->getQuery(), $params);

        $response = $this->doFrontendRequest(
            'POST',
            '/oauth/token',
            [
                'grant_type' => 'authorization_code',
                'client_id' => '660e56d72c12f9a1e2ec',
                'client_secret' => 'CCJL1/s3TQLMHj9le2bBUlD7tmkPZKlOTZGgBQRb3BE=',
                'redirect_uri' => 'http://localhost/',
                'code' => $params['code'],
            ]
        );

        $token = json_decode((string) $response->getBody(), true);
        $this->assertSame('Bearer', $token['token_type']);
        $this->assertArrayHasKey('expires_in', $token);
        $this->assertArrayHasKey('access_token', $token);
    }

    protected function doFrontendRequest(string $method, string $uri, array $params = [], string $cookie = null): InternalResponse
    {
        $request = new InternalRequest($uri);
        $context = null;
        $globalSettings = [];

        if ($method === 'POST') {
            $globalSettings = array_merge($globalSettings, [
                '_SERVER' => ['REQUEST_METHOD' => 'POST'],
                '_POST' => $params,
            ]);
        } else {
            foreach ($params as $key => $value) {
                $request = $request->withQueryParameter($key, $value);
            }
        }

        if ($cookie !== null) {
            $globalSettings['_COOKIE'] = ['fe_typo_user' => $cookie];
        }

        if (!empty($globalSettings)) {
            $context = new InternalRequestContext();
            $context = $context->withGlobalSettings($globalSettings);
        }

        return $this->executeFrontendRequest($request, $context);
    }

    protected function getLastCookie(): string
    {
        preg_match_all('/Set Cookie: (?<cookie>[a-z0-9]+)/', file_get_contents('typo3temp/var/log/auth.log'), $matches);
        return end($matches['cookie']);
    }
}
