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

/**
 * AuthCode
 */
class AuthCode extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /** @var string */
    protected $identifier;

    /** @var \DateTime */
    protected $expiresAt;

    /** @var string */
    protected $user;

    /** @var string */
    protected $scopes;

    /** @var string */
    protected $client;

    /** @var bool */
    protected $revoked;

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setScopes($scopes)
    {
        $this->scopes = $scopes;
    }

    public function getScopes()
    {
        return $this->scopes;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getClient()
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
