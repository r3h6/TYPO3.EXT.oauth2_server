<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Tests\Functional;

use Symfony\Component\Mailer\Transport\NullTransport;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

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

abstract class ApplicationTestCase extends FunctionalTestCase
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
    protected const ACCESS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJmYzE3NzU3ZC1hYTBjLTQ4MWQtOTZkNy1jMjUwNGNlNzE5OWYiLCJqdGkiOiI3MzJmNzAwMjA3ZDE5ODNhY2RjMTg2OGVmZWUyYzUxZmFmZTMyYzM0OWNlMzkyMDkyZjQ4YThkZTczNTU5MjAwZDUxOTQxZTY1MzEwYTRhYiIsImlhdCI6MTcxOTk0NzQ5NS41MjE3MTksIm5iZiI6MTcxOTk0NzQ5NS41MjE3MjEsImV4cCI6MjAzNTQ4MDI5NS41MTgzNDYsInN1YiI6IjEiLCJzY29wZXMiOlsiYXBpIiwicmVhZCJdfQ.AfYG4QVYI5m1rgO_-IiWeYAO8TFAavidzbzUmXDYCdiYV3B1voBP7CkKk61PBLON6ASyuQljm8ZUfciSi8NN1IEY-izP7a28b-CliB-vhgaArdS2vDnGLNbD7X6O7Di-oBxI34tvc92Wij4ShT-ZRCeVuS3gKrqXyInJuo4om_pghglS6vcSvvf5KJzmEzbnn4up16ntqkfjBQKMfQexQDkZJDEDS82dWNze3rEUDbIC4U-82VPTxVHZj3q5k-L4ydgXgkrLtl-cg67Od8J9h_bwIf3REc-tEsULvL1-VYiuFnq0tTpiThdLyVNbZHROM-z83dR4yP-TZvB_lFMJ9Q';

    /**
     * Expires: 2034
     * Scopes: api, read
     */
    protected const REFRESH_TOKEN = 'def50200fb242851e8053651b6dc2f9a13972fb0c5634ed0bec15e4775ae60dd18ec693bc72f2b4e22ec6097e2c85bf291b59d7fae3fc4c7519b0de2382c8c0d7bb85504635cb234d65a0391626d859beec6ae5e311a5352ca143b4a39e6f653945f4cceb1f213b68b9eba194acc85382a27b63e90aa31662c63bf3419b3c16695f11aaccb7e8541fb8c75887eba4786f33361b258f56695524ccba99b008706e294de153cc14a1b6b70fc01ae41f6a49be7b399a8c5a7ffdef5ff7e2b0a13ad2c883731dd6806bcecf3614d2d4cec19d884f05e2cd3caa568e908c2c8a61dec4228d2bc76ba2c498f2bd8ec6d6710a6298def72867ecf3aeed5dbb48c6bee1ed2c2a25389fc9e1920fec73d58f6d7f8d686ee143bec8327462784b49c08bb9abe52a1a46a4dc06f11a5d9d9b995db72ce7127376c569732c548380244872387773531d78ca890285085bd580e8ce6e5c572b978b06471d0ce90222f9bfdbd09b64b3fea1eee7c9fc618cf68486e6681ee700d25690346975f75536c116f1e4b2acf420f869ddcd1562fb1e38cc7a3ddb318';

    protected array $coreExtensionsToLoad = [
        'felogin',
        'fluid_styled_content',
    ];

    protected array $testExtensionsToLoad = [
        'typo3conf/ext/oauth2_server',
        'typo3conf/ext/oauth2_server/Tests/Fixtures/example_resources',
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
        'SYS' => [
            'encryptionKey' => 'e901a86f5faa521ac6c4359c281affac7a3fbdb734b85269262248a14fb349399300a3f1286f5bbd227e10933ea90413',
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
            'constants' => [
                'EXT:felogin/Configuration/TypoScript/constants.typoscript',
                'EXT:fluid_styled_content/Configuration/TypoScript/constants.typoscript',
                'EXT:oauth2_server/Configuration/TypoScript/constants.typoscript',
            ],
            'setup' => [
                'EXT:felogin/Configuration/TypoScript/setup.typoscript',
                'EXT:fluid_styled_content/Configuration/TypoScript/setup.typoscript',
                'EXT:oauth2_server/Configuration/TypoScript/setup.typoscript',
                'EXT:example_resources/Configuration/TypoScript/setup.typoscript',
            ],
        ]);
    }
}
