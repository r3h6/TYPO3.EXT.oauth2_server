<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Configuration;

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

class Configuration implements SingletonInterface
{
    private array $configuration = [
        'privateKey' => 'EXT:oauth2_server/Resources/Private/Keys/private.key',
        'publicKey' => 'EXT:oauth2_server/Resources/Private/Keys/public.key',
        'routePrefix' => 'oauth2',
        'accessTokensExpireIn' => 'PT1H',
        'refreshTokensExpireIn' => 'P1M',
        'requireCodeChallengeForPublicClients' => true,
        'consentPageUid' => null,
        'loginPageUid' => null,
        'scopes' => [],
        'resourceRoutes' => [],
        'extensions' => [],
    ];

    public function getOauth2Routes(): array
    {
        $routes = ['EXT:oauth2_server/Configuration/OAuth2/'];
        foreach ($this->configuration['extensions'] as $extensionKey) {
            $routes[] = 'EXT:' . $extensionKey . '/Configuration/OAuth2/';
        }
        return $routes;
    }

    public function getResourceRoutes(): array
    {
        return $this->configuration['resourceRoutes'];
    }

    public function getRoutePrefix(): string
    {
        return $this->configuration['routePrefix'];
    }

    public function getServerClass(): string
    {
        return $this->configuration['serverClass'];
    }

    public function getResources(): array
    {
        return $this->configuration['resources'];
    }

    public function getPrivateKey(): string
    {
        return $this->configuration['privateKey'];
    }

    public function getPublicKey(): string
    {
        return $this->configuration['publicKey'];
    }

    public function getAccessTokensExpireIn(): string
    {
        return $this->configuration['accessTokensExpireIn'];
    }

    public function getRefreshTokensExpireIn(): string
    {
        return $this->configuration['refreshTokensExpireIn'];
    }

    public function getRequireCodeChallengeForPublicClients(): bool
    {
        return (bool)$this->configuration['requireCodeChallengeForPublicClients'];
    }

    public function getConsentPageUid(): int
    {
        if ($this->configuration['consentPageUid'] === null) {
            throw new \RuntimeException('consentPageUid is not configured', 1717097624785);
        }
        return (int)$this->configuration['consentPageUid'];
    }

    public function getLoginPageUid(): int
    {
        if ($this->configuration['loginPageUid'] === null) {
            throw new \RuntimeException('loginPageUid is not configured', 1717097631265);
        }
        return (int)$this->configuration['loginPageUid'];
    }

    public function getScopes(): array
    {
        return $this->configuration['scopes'];
    }

    public function merge(array $overrideConfiguration): void
    {
        ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $overrideConfiguration, true, true, false);
    }
}
