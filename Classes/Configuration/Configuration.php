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

/**
 * @implements \ArrayAccess<string, mixed>
 */
class Configuration implements \ArrayAccess, SingletonInterface
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
        'resources' => [],
    ];

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->configuration[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->configuration[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new \RuntimeException('Configuration is read-only', 1717703562513);
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new \RuntimeException('Configuration is read-only', 1717703572984);
    }

    public function getRoutePrefix(): string
    {
        return $this->configuration['routePrefix'];
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
        ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $overrideConfiguration, true, false, false);
    }

    public function toArray(): array
    {
        return $this->configuration;
    }
}
