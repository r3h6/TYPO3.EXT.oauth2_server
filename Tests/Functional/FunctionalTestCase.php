<?php

namespace R3H6\Oauth2Server\Tests\Functional;

abstract class FunctionalTestCase extends \TYPO3\TestingFramework\Core\Functional\FunctionalTestCase
{
    // protected const ENCRYPTION_KEY = 'aa06c08658128b1247afeb704b26475edfa8b70afb5369ea66bb7a8098950cdb75b7ec73140a352b6fb51aa5b9f69042';

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
                ]
            ]
        ],
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

}
