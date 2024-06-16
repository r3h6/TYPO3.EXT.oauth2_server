<?php

namespace R3H6\Oauth2Server\Event;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use R3H6\Oauth2Server\Configuration\Configuration;

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
