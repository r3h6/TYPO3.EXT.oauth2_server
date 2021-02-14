<?php

namespace R3H6\Oauth2Server\Configuration;

use R3H6\Oauth2Server\Http\Oauth2Server;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\Exception\MissingArrayPathException;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Oauth2Configuration implements SingletonInterface
{
    public const REQUEST_ATTRIBUTE_NAME = 'oauth2';

    private const EXTENSION_KEY = 'oauth2_server';

    /**
     * @var array
     */
    private static $configuration = [
        'privateKey' => 'EXT:oauth2_server/Resources/Private/Keys/private.key',
        'publicKey' => 'EXT:oauth2_server/Resources/Private/Keys/public.key',
        'server' => Oauth2Server::class,
        'routePrefix' => 'oauth2',
        'accessTokensExpireIn' => 'P1M',
        'refreshTokensExpireIn' => 'P1M',
        'enableImplicitGrantType' => false,
        'consentPageUid' => null,
        'scopes' => [],
        'firewall' => [],
        'resources' => [],
        // 'routes' => [
        //     'ouath2_authorize' => [
        //         'path' =>       '/oauth2/authorize',
        //         'controller' => 'R3H6\Oauth2Server\Controller\AuthorizationController::startAuthorization',
        //         'methods' =>    'GET',
        //     ],
        //     'ouath2_authorize_deny' => [
        //         'path' =>       '/oauth2/authorize',
        //         'controller' => 'R3H6\Oauth2Server\Controller\AuthorizationController::denyAuthorization',
        //         'methods' =>    'POST',
        //     ],
        //     'ouath2_authorize_approve' => [
        //         'path' =>       '/oauth2/authorize',
        //         'controller' => 'R3H6\Oauth2Server\Controller\AuthorizationController::approveAuthorization',
        //         'methods' =>    'DELETE',
        //     ],
        //     'ouath2_token' => [
        //         'path' =>       '/oauth2/token',
        //         'controller' => 'R3H6\Oauth2Server\Controller\TokenController::issueAccessToken',
        //         'methods' =>    'POST',
        //     ],
        //     'ouath2_revoke' => [
        //         'path' =>       '/oauth2/revoke',
        //         'controller' => 'R3H6\Oauth2Server\Controller\TokenController::revokeAccessToken',
        //         'methods' =>    'POST',
        //     ],
        // ],

    ];

    public function __construct()
    {
        $this->loadExtensionConfiguration();
    }

    public function get($path, $defaultValue = null)
    {
        try {
            return ArrayUtility::getValueByPath(self::$configuration, $path, '.');
        } catch (MissingArrayPathException $exception) {
            // Ignore
        }
        return $defaultValue;
    }

    public function getRoutePrefix(): string
    {
        return self::$configuration['routePrefix'];
    }

    public function getServerClass(): string
    {
        return self::$configuration['server'];
    }

    public function getFirewall(): array
    {
        return self::$configuration['firewall'];
    }

    public function getResources(): array
    {
        return self::$configuration['resources'];
    }

    public function getPrivateKey(): string
    {
        return self::$configuration['privateKey'];
    }

    public function getPublicKey(): string
    {
        return self::$configuration['publicKey'];
    }

    public function getAccessTokensExpireIn(): string
    {
        return self::$configuration['accessTokensExpireIn'];
    }

    public function getRefreshTokensExpireIn(): string
    {
        return self::$configuration['refreshTokensExpireIn'];
    }

    public function getConsentPageUid(): int
    {
        return (int)self::$configuration['consentPageUid'];
    }

    public function getEnableImplicitGrantType(): bool
    {
        return (bool)self::$configuration['enableImplicitGrantType'];
    }

    public function merge(array $overrideConfiguration): void
    {
        ArrayUtility::mergeRecursiveWithOverrule(self::$configuration, $overrideConfiguration, true, true, false);
    }

    private function loadExtensionConfiguration(): void
    {
        $extensionConfiguration = GeneralUtility::makeInstance(ExtensionConfiguration::class);
        $overrideConfiguration = [
            'publicKey' => $extensionConfiguration->get(self::EXTENSION_KEY, 'publicKey'),
            'privateKey' => $extensionConfiguration->get(self::EXTENSION_KEY, 'privateKey'),
            'consentPageUid' => $extensionConfiguration->get(self::EXTENSION_KEY, 'server/consentPageUid'),
        ];

        $this->merge(array_filter($overrideConfiguration));
    }
}
