<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Tests\Functional;

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

/**
 * FunctionalTestCase
 */
abstract class FunctionalTestCase extends \TYPO3\TestingFramework\Core\Functional\FunctionalTestCase
{
    protected $testExtensionsToLoad = [
        'typo3conf/ext/oauth2_server',
    ];

    protected $pathsToLinkInTestInstance = [
        'typo3conf/ext/oauth2_server/Tests/Fixtures/config/sites' => 'typo3conf/sites',
    ];

    protected $configurationToUseInTestInstance = [
        // 'SYS' => [
        //     'encryptionKey' => self::ENCRYPTION_KEY,
        // ],
        'EXTENSIONS' => [
            'oauth2_server' => [
                'server' => [
                    'consentPageUid' => '1',
                ],
            ],
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
            'TYPO3' => [
                'CMS' => [
                    'Frontend' => [
                        'Authentication' => [
                            'writerConfiguration' => [
                                \TYPO3\CMS\Core\Log\LogLevel::DEBUG => [
                                    \TYPO3\CMS\Core\Log\Writer\FileWriter::class => [
                                        'logFile' =>  'typo3temp/var/log/auth.log',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->importDataSet('EXT:oauth2_server/Tests/Fixtures/Database/pages.xml');
        $this->setUpFrontendRootPage(1);
    }
}
