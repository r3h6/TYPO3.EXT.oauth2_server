<?php

namespace R3H6\Oauth2Server\Configuration;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\Exception\MissingArrayPathException;

class RuntimeConfiguration implements SingletonInterface
{
    private const EXTENSION_KEY = 'oauth2_server';

    /**
     * @var array
     */
    private $configuration = [
        'privateKey' => 'EXT:oauth2_server/Resources/Private/Keys/private.key',
        'publicKey' => 'EXT:oauth2_server/Resources/Private/Keys/public.key',
        'server' => [
            'routes' => [
                'GET:oauth/authorize' => 'R3H6\Oauth2Server\Controller\AuthorizationController::startAuthorization',
                'POST:oauth/authorize' => 'R3H6\Oauth2Server\Controller\AuthorizationController::approveAuthorization',
                'DELETE:oauth/authorize' => 'R3H6\Oauth2Server\Controller\AuthorizationController::denyAuthorization',
                'POST:oauth/token' => 'R3H6\Oauth2Server\Controller\TokenController::issueAccessToken',
                'POST:oauth/revoke' => 'R3H6\Oauth2Server\Controller\RevokeController::revokeAccessToken',
            ],
            'tokensExpireIn' => 'P1M',
            'grantTypes' => [
                'implicit' => [
                    'enabled' => false,
                ],
            ],
            'consentPageUid' => null,
        ],
    ];

    public function __construct()
    {
        $this->loadExtensionConfiguration();
    }

    public function get($path, $defaultValue = null)
    {
        try {
            return ArrayUtility::getValueByPath($this->configuration, $path, '.');
        } catch (MissingArrayPathException $exception) {
            // Ignore
        }
        return $defaultValue;
    }

    public function merge(array $overrideConfiguration): void
    {
        ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $overrideConfiguration, true, true, false);
    }

    private function loadExtensionConfiguration(): void
    {
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $overrideConfiguration = [
            'publicKey' => $extensionConfiguration->get(self::EXTENSION_KEY, 'publicKey'),
            'privateKey' => $extensionConfiguration->get(self::EXTENSION_KEY, 'privateKey'),
            'server' => [
                'consentPageUid' => $extensionConfiguration->get(self::EXTENSION_KEY, 'server/consentPageUid'),
            ],
        ];

        $this->merge(array_filter($overrideConfiguration));
    }
}
