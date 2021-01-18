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
                'GET:oauth/authorize' => 'R3H6\Oauth2Server\Controller\AuthorizationController::startAuthorization',
                'POST:oauth/authorize' => 'R3H6\Oauth2Server\Controller\AuthorizationController::approveAuthorization',
                'DELETE:oauth/authorize' => 'R3H6\Oauth2Server\Controller\AuthorizationController::denyAuthorization',
                'POST:oauth/token' => 'R3H6\Oauth2Server\Controller\TokenController::accessTokenAction',
            ],
            'privateKey' => 'EXT:oauth2_server/Resources/Private/Keys/private.key',
            'publicKey' => 'EXT:oauth2_server/Resources/Private/Keys/public.key',
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
