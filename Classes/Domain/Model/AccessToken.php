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
    /** @var string */
    protected $identifier = '';

    /** @var \DateTimeImmutable */
    protected $expiresAt;

    /** @var string */
    protected $user = '';

    /** @var string */
    protected $scopes = '';

    /** @var string */
    protected $client = '';

    /** @var bool */
    protected $revoked = false;

    public function setIdentifier(string $identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function setExpiresAt(\DateTimeImmutable $expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    public function getExpiresAt(): \DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setUser(string $user)
    {
        $this->user = $user;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function setScopes(string $scopes)
    {
        $this->scopes = $scopes;
    }

    public function getScopes(): string
    {
        return $this->scopes;
    }

    public function setClient(string $client)
    {
        $this->client = $client;
    }

    public function getClient(): string
    {
        return $this->client;
    }

    public function setRevoked($revoked)
    {
        $this->revoked = $revoked;
    }

    public function getRevoked()
    {
        return $this->revoked;
    }
}
