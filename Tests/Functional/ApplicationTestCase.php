<?php

namespace R3H6\Oauth2Server\Tests\Functional;

use Symfony\Component\Mailer\Transport\NullTransport;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

abstract class ApplicationTestCase extends FunctionalTestCase
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
            'constants' => ['EXT:oauth2_server/Configuration/TypoScript/constants.typoscript'],
            'setup' => ['EXT:oauth2_server/Configuration/TypoScript/setup.typoscript'],
        ]);
    }
}
