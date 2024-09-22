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

    /** Expires 17-6-2034 */
    protected const REFRESH_TOKEN = 'def50200afddde02c4a694a92991e1b87267c88c6a8f97c6eadf6b61e5553434b87c95833c5b5d4d34e4857221f1214ea847473049c7e40026cb4a42d8138a643e61bd2778abb3300bf721b464a68e4e94da59a2595b0a07684927798940d5061e16c68600893aeea62797fbbe67ca46f876353ae7911496c7eb271c48752ab86360346bccaea4a84529776a7e1f8e0fa7b2d2c0ba733f17cbdb8de43362f7dbabab6c68db7fbaf39ae28b7eed32e5436878682ba25d7592379cb99c64d78b2a63bf033f060dcce9d70213514c23f8d032a9a02111045ae3450a2d8250582149de889cae2bdbb7d1339aae20aba9c6e2290b25095e8a7e337b31952fda93f9120f9db4904a180fc796f364784ada430bfe6aa4d6cff8cafb45fb8edcaf378175739917622847084bb84c6f1c8bf11c7b8c0dfc434b700567acb0a3d44bda9039c22ed0f2893c42dc81c959c21a1e00d5cc7b8d07f4748924b10cb440a2fa804ded1b8d4198dcff78868a46317c9e6d0228203d761db6e55a11e8d663082142e76a35a91a8f7bb982c4d89d09e4ce4e2bdbb749fb4775c7b75d';

    protected array $coreExtensionsToLoad = [
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
                'EXT:fluid_styled_content/Configuration/TypoScript/constants.typoscript',
                'EXT:oauth2_server/Configuration/TypoScript/constants.typoscript',
            ],
            'setup' => [
                'EXT:fluid_styled_content/Configuration/TypoScript/setup.typoscript',
                'EXT:oauth2_server/Configuration/TypoScript/setup.typoscript',
                'EXT:example_resources/Configuration/TypoScript/setup.typoscript',
            ],
        ]);
    }
}
