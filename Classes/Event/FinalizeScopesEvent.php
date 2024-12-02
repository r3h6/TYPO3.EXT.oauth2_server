<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Event;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use R3H6\Oauth2Server\Configuration\Configuration;

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

final class FinalizeScopesEvent
{
    public function __construct(
        private array $scopes,
        private readonly string $grantType,
        private readonly ClientEntityInterface $clientEntity,
        private readonly null|int|string $userIdentifier,
        private readonly Configuration $configuration,
    ) {}

    public function setScopes(array $scopes): void
    {
        $this->scopes = $scopes;
    }

    public function getScopes(): array
    {
        return $this->scopes;
    }

    public function getGrantType(): string
    {
        return $this->grantType;
    }

    public function getClientEntity(): ClientEntityInterface
    {
        return $this->clientEntity;
    }

    public function getUserIdentifier(): null|int|string
    {
        return $this->userIdentifier;
    }

    public function getConfiguration(): Configuration
    {
        return $this->configuration;
    }
}
