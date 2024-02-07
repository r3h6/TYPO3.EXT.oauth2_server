<?php

declare(strict_types=1);
namespace R3H6\Oauth2Server\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
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
 * RefreshToken
 */
class RefreshToken extends AbstractEntity
{
    /** @var string */
    protected $identifier;

    /** @var \DateTime */
    protected $expiresAt;

    /** @var bool */
    protected $revoked;

    /** @var string */
    protected $accessToken;

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

    public function setRevoked($revoked)
    {
        $this->revoked = $revoked;
    }

    public function getRevoked()
    {
        return $this->revoked;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }
}
