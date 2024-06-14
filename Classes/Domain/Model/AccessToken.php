<?php

declare(strict_types=1);

namespace R3H6\Oauth2Server\Domain\Model;

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

class AccessToken extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    protected string $identifier = '';
    protected ?\DateTimeImmutable $expiresAt = null;
    protected string $user = '';
    protected string $scopes = '';
    protected string $client = '';
    protected bool $revoked = false;

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setExpiresAt(?\DateTimeImmutable $expiresAt): void
    {
        $this->expiresAt = $expiresAt;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setScopes(string $scopes): void
    {
        $this->scopes = $scopes;
    }

    public function getScopes(): string
    {
        return $this->scopes;
    }

    public function setClient(string $client): void
    {
        $this->client = $client;
    }

    public function getClient(): string
    {
        return $this->client;
    }

    public function setRevoked(bool $revoked): void
    {
        $this->revoked = $revoked;
    }

    public function getRevoked(): bool
    {
        return $this->revoked;
    }
}
