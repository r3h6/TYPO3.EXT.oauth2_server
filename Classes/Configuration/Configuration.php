<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Configuration;

use R3H6\Oauth2Server\Controller\AuthorizationController;
use R3H6\Oauth2Server\Controller\RevokeController;
use R3H6\Oauth2Server\Controller\TokenController;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\ArrayUtility;

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
 * Configuration
 */
class Configuration implements \ArrayAccess, SingletonInterface
{
    /**
     * @var array
     */
    private static $configuration = [
        'privateKey' => 'EXT:oauth2_server/Resources/Private/Keys/private.key',
        'publicKey' => 'EXT:oauth2_server/Resources/Private/Keys/public.key',
        'routePrefix' => 'oauth2',
        'accessTokensExpireIn' => 'P1M',
        'refreshTokensExpireIn' => 'P1M',
        'requireCodeChallengeForPublicClients' => true,
        'consentPageUid' => 0,
        'loginPageUid' => 0,
        'scopes' => [],
        'resources' => [],
        'endpoints' => [
            'oauth2_authorize' => [
                'path' => '/authorize',
                'target' => AuthorizationController::class . '::startAuthorization',
                'authorization' => false,
                'methods' => ['GET'],
            ],
            'oauth2_authorize_approve' => [
                'path' => '/authorize',
                'target' => AuthorizationController::class . '::approveAuthorization',
                'authorization' => false,
                'methods' => ['POST'],
            ],
            'oauth2_authorize_deny' => [
                'path' => '/authorize',
                'target' => AuthorizationController::class . '::denyAuthorization',
                'authorization' => false,
                'methods' => ['DELETE'],
            ],
            'oauth2_token' => [
                'path' => '/token',
                'target' => TokenController::class . '::issueAccessToken',
                'authorization' => false,
                'methods' => ['POST'],
            ],
            'oauth2_revoke' => [
                'path' => '/revoke',
                'target' => RevokeController::class . '::revokeAccessToken',
            ],
        ],
    ];

    public function getRoutePrefix(): string
    {
        return self::$configuration['routePrefix'];
    }

    public function getServerClass(): string
    {
        return self::$configuration['server'];
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

    public function getRequireCodeChallengeForPublicClients(): bool
    {
        return (bool)self::$configuration['requireCodeChallengeForPublicClients'];
    }

    public function getConsentPageUid(): int
    {
        return (int)self::$configuration['consentPageUid'];
    }

    public function getLoginPageUid(): int
    {
        return (int)self::$configuration['loginPageUid'];
    }

    public function getEndpoints(): array
    {
        return self::$configuration['endpoints'];
    }
    public function getScopes(): array
    {
        return self::$configuration['scopes'];
    }

    public function merge(array $overrideConfiguration): void
    {
        ArrayUtility::mergeRecursiveWithOverrule(self::$configuration, $overrideConfiguration, true, true, false);
    }

    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Array access to configuration is read only', 1613841524309);
    }

    public function offsetExists($offset)
    {
        return isset(self::$configuration[$offset]);
    }

    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Array access to configuration is read only', 1613841557884);
    }

    public function offsetGet($offset)
    {
        return isset(self::$configuration[$offset]) ? self::$configuration[$offset] : null;
    }
}
