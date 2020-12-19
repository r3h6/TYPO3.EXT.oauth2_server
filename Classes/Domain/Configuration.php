<?php

namespace R3H6\Oauth2Server\Domain;

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\Exception\MissingArrayPathException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Configuration implements SingletonInterface
{
    private $configuration = [
        'server' => [
            'routes' => [
                'GET:oauth/authorize' => 'R3H6\Oauth2Server\Controller\AuthorizationController::authenticateAction',
                'POST:oauth/authorize' => 'R3H6\Oauth2Server\Controller\AuthorizationController::authorizeAction',
                'POST:oauth/approve' => 'R3H6\Oauth2Server\Controller\AuthorizationController::approveAction',
                'POST:oauth/deny' => 'R3H6\Oauth2Server\Controller\AuthorizationController::denyAction',
                'POST:oauth/token' => 'R3H6\Oauth2Server\Controller\AuthorizationController::accessTokenAction',
            ],
            'privateKey' => 'EXT:oauth2_server/Resources/Private/Keys/private.key',
            'tokensExpireIn' => 'P1M',
            'grantTypes' => [
                'implicit' => [
                    'enabled' => false,
                ],
            ],
        ],
    ];

    public function merge(array $overrideConfiguration)
    {
        ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $overrideConfiguration);
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
}
