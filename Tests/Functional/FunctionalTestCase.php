<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Tests\Functional;

use TYPO3\CMS\Core\Log\LogLevel;
use TYPO3\CMS\Core\Log\Writer\FileWriter;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
    protected array $testExtensionsToLoad = [
        'typo3conf/ext/oauth2_server',
    ];

    protected array $pathsToLinkInTestInstance = [
        'typo3conf/ext/oauth2_server/Tests/Fixtures/config/sites' => 'typo3conf/sites',
    ];

    protected array $configurationToUseInTestInstance = [
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
                        LogLevel::DEBUG => [
                            FileWriter::class => [],
                        ],
                    ],
                ],
            ],
            'TYPO3' => [
                'CMS' => [
                    'Frontend' => [
                        'Authentication' => [
                            'writerConfiguration' => [
                                LogLevel::DEBUG => [
                                    FileWriter::class => [
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
        $this->importCSVDataSet(GeneralUtility::getFileAbsFileName('EXT:oauth2_server/Tests/Fixtures/Database/pages.csv'));
        $this->setUpFrontendRootPage(1);
    }
}
